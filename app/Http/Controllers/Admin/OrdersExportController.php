<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Symfony\Component\HttpFoundation\StreamedResponse;
use App\Models\Order;

class OrdersExportController extends Controller
{
    /**
     * Export ALL orders (no filters) as a streamed CSV download.
     */
    public function exportAll(): StreamedResponse
    {
        // Long exports can take time
        if (function_exists('set_time_limit')) {
            @set_time_limit(0);
        }

        $file = 'orders_all_' . now()->format('Y-m-d_H-i') . '.csv';

        // Select minimal fields + counts; eager load light relations
        $query = Order::query()
            ->with(['user:id,name', 'shop:id,name'])
            ->withCount('orderDetails')
            ->select([
                'id',
                'code',
                'user_id',
                'guest_id',
                'shop_id',
                'grand_total',
                'delivery_status',
                'payment_type',
                'payment_status',
                'created_at',
            ])
            ->orderBy('id'); // stable streaming order

        $headersRow = [
            'ID',
            'Order Code',
            'Num. of Products',
            'Customer',
            'Seller',
            'Amount',
            'Delivery Status',
            'Payment Method',
            'Payment Status',
            'Created At',
        ];

        return response()->streamDownload(function () use ($query, $headersRow) {
            $out = fopen('php://output', 'w');

            // Excel-friendly UTF-8 BOM
            fwrite($out, "\xEF\xBB\xBF");

            // CSV header row
            fputcsv($out, $headersRow);

            // Stream rows in constant memory
            foreach ($query->cursor() as $order) {
                $customer = $order->user
                    ? $order->user->name
                    : ('Guest (' . ($order->guest_id ?? '-') . ')');

                $seller = $order->shop
                    ? $order->shop->name
                    : 'Inhouse Order';

                $row = [
                    $order->id,
                    self::safe($order->code),
                    (int) $order->order_details_count,
                    self::safe($customer),
                    self::safe($seller),
                    self::money($order->grand_total),
                    ucfirst(str_replace('_', ' ', (string) $order->delivery_status)),
                    ucfirst(str_replace('_', ' ', (string) $order->payment_type)),
                    ucfirst(str_replace('_', ' ', (string) $order->payment_status)),
                    optional($order->created_at)->format('Y-m-d H:i:s'),
                ];

                fputcsv($out, $row);
            }

            fclose($out);
        }, $file, [
            'Content-Type'  => 'text/csv; charset=UTF-8',
            'Cache-Control' => 'no-store, no-cache, must-revalidate',
            'Pragma'        => 'no-cache',
        ]);
    }

    /**
     * Prevent CSV formula injection in Excel (cells starting with = + - @).
     */
    private static function safe(?string $value): string
    {
        if ($value === null) return '';
        $trim = ltrim($value);
        if ($trim !== '' && in_array($trim[0], ['=', '+', '-', '@'])) {
            return "'".$value;
        }
        return $value;
    }

    private static function money($value): string
    {
        if ($value === null) return '0.00';
        return number_format((float)$value, 2, '.', '');
    }
}
