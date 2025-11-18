<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;

class AjaxSearchController extends Controller
{
    /** Absolute URL helper with placeholder fallback */
    private function absUrl(?string $url): string
    {
        if (!$url) {
            return function_exists('static_asset')
                ? static_asset('assets/img/placeholder.jpg')
                : url('public/assets/img/placeholder.jpg');
        }
        if (preg_match('#^https?://#i', $url)) {
            if (config('app.url') && str_starts_with(config('app.url'), 'https://')) {
                return preg_replace('#^http://#i', 'https://', $url);
            }
            return $url;
        }
        return url(ltrim($url, '/'));
    }

    /** Build absolute thumbnail URL from either `thumbnail` or `thumbnail_img` */
    private function buildThumb($row): string
    {
        $v = $row->thumbnail ?? $row->thumbnail_img ?? null;
        $thumb = null;

        try {
            if (is_string($v) && preg_match('#^https?://#i', $v)) {
                $thumb = $v;
            }
            if (!$thumb && function_exists('get_image')) {
                $thumb = get_image($v);
            }
            if (!$thumb && function_exists('uploaded_asset')) {
                $thumb = uploaded_asset($v);
            }
            if (!$thumb && is_string($v)) {
                $thumb = $v;
            }
        } catch (\Throwable $e) {
            // ignore helper errors and fall back to placeholder
        }

        return $this->absUrl($thumb);
    }

    /** Add publish/approve filters only if columns exist */
    private function applyPublishFilters($qb)
    {
        $schema = DB::getSchemaBuilder();

        if ($schema->hasColumn('products', 'published')) {
            $qb->where('products.published', 1);
        }
        if ($schema->hasColumn('products', 'is_published')) {
            $qb->where('products.is_published', 1);
        }
        if ($schema->hasColumn('products', 'status')) {
            $qb->whereIn('products.status', [1, '1', 'active', 'published']);
        }
        if ($schema->hasColumn('products', 'approved')) {
            $qb->where('products.approved', 1);
        }
        if ($schema->hasColumn('products', 'approved_by_admin')) {
            $qb->where('products.approved_by_admin', 1);
        }
        if ($schema->hasColumn('products', 'deleted_at')) {
            $qb->whereNull('products.deleted_at');
        }

        return $qb;
    }

    public function products(Request $request)
    {
        try {
            $q = trim((string) $request->query('q', ''));
            if (mb_strlen($q) < 2) {
                return response()->json([
                    'ok' => true,
                    'data' => ['items' => [], 'total' => 0, 'page' => 1, 'nextPage' => null],
                ]);
            }

            $page    = max(1, (int) $request->query('page', 1));
            $perPage = max(1, (int) $request->query('per_page', 12));

            // Model detection (old/new namespaces)
            $productClass = class_exists(\App\Models\Product::class) ? \App\Models\Product::class
                : (class_exists(\App\Product::class) ? \App\Product::class : null);

            if ($productClass) {
                $qb = $productClass::query()->from('products')
                    ->select([
                        'products.id', 'products.name', 'products.slug',
                        'products.thumbnail', 'products.thumbnail_img',
                        'products.unit_price', 'products.created_at',
                    ])
                    ->where('products.name', 'like', "%{$q}%");

                $this->applyPublishFilters($qb);

                $paginator = $qb->orderByDesc('products.created_at')
                    ->paginate($perPage, ['*'], 'page', $page);

                $rows = $paginator->getCollection();
            } else {
                $qb = DB::table('products')
                    ->select('products.id', 'products.name', 'products.slug',
                        'products.thumbnail', 'products.thumbnail_img',
                        'products.unit_price', 'products.created_at')
                    ->where('products.name', 'like', "%{$q}%");

                $this->applyPublishFilters($qb);

                $paginator = $qb->orderByDesc('products.created_at')
                    ->paginate($perPage, ['*'], 'page', $page);

                $rows = collect($paginator->items());
            }

            // Map to lightweight items for the modal
            $items = $rows->map(function ($p) {
                $id   = (int)($p->id ?? 0);
                $name = (string)($p->name ?? '');
                $slug = (string)($p->slug ?? '');

                // thumb
                $thumb = $this->buildThumb($p);

                // price (HTML/text safe)
                $priceHtml = '';
                try {
                    if (function_exists('home_discounted_base_price')) {
                        $priceHtml = home_discounted_base_price($p);
                    } elseif (!empty($p->unit_price)) {
                        $priceText = number_format((float)$p->unit_price, 2);
                        $priceHtml = function_exists('currency_symbol')
                            ? currency_symbol() . ' ' . $priceText
                            : $priceText;
                    }
                } catch (\Throwable $e) {
                    if (!empty($p->unit_price)) {
                        $priceHtml = number_format((float)$p->unit_price, 2);
                    }
                }

                // product URL
                try {
                    $url = !empty($slug) ? route('product', $slug) : url('product/' . $id);
                } catch (\Throwable $e) {
                    $url = url('product/' . $id);
                }

                return [
                    'id'    => $id,
                    'name'  => $name,
                    'slug'  => $slug,
                    'thumb' => $thumb,
                    'price' => $priceHtml,
                    'url'   => $url,
                ];
            })->values();

            return response()->json([
                'ok'     => true,
                'data'   => [
                    'items'    => $items,
                    'total'    => (int) $paginator->total(),
                    'page'     => (int) $paginator->currentPage(),
                    'nextPage' => $paginator->hasMorePages() ? $paginator->currentPage() + 1 : null,
                ],
            ]);
        } catch (\Throwable $e) {
            \Log::error('Ajax search failed', ['e' => $e]);
            return response()->json([
                'ok'    => false,
                'error' => 'SERVER_ERROR',
                'msg'   => app()->hasDebugModeEnabled() ? $e->getMessage() : 'Something went wrong',
            ], 500);
        }
    }
}
