<?php

namespace App\Http\Controllers\frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Throwable;

class InstagramFeedController extends Controller
{
    /**
     * Return cached Instagram media for the frontend feed.
     */
    public function __invoke(): JsonResponse
    {
        $accessToken = config('services.instagram.access_token');
        $userId = config('services.instagram.user_id');

        if (empty($accessToken) || empty($userId)) {
            \Log::warning('Instagram feed: Missing credentials', [
                'has_access_token' => !empty($accessToken),
                'has_user_id' => !empty($userId)
            ]);
            
            return response()->json([
                'error' => 'Instagram credentials are missing. Please configure INSTAGRAM_ACCESS_TOKEN and INSTAGRAM_USER_ID in your .env file.',
            ], 500);
        }

        $cacheKey = 'instagram.feed.v1';
        $ttl = (int) config('services.instagram.cache_ttl', 900);

        try {
            $payload = Cache::remember($cacheKey, $ttl, function () use ($accessToken, $userId) {
            $version = ltrim(config('services.instagram.graph_version', 'v19.0'), 'v');
            $endpoint = sprintf('https://graph.facebook.com/v%s/%s/media', $version, $userId);
            $fields = [
                'id',
                'caption',
                'media_type',
                'media_url',
                'permalink',
                'thumbnail_url',
                'timestamp',
                'username',
                'children{media_type,media_url,thumbnail_url}',
            ];

                $response = Http::timeout(10)
                ->acceptJson()
                ->get($endpoint, [
                    'fields' => implode(',', $fields),
                    'access_token' => $accessToken,
                    'limit' => (int) config('services.instagram.limit', 8),
                ])
                ->throw();

                $items = collect($response->json('data', []))
                ->filter(function (array $item) {
                    return in_array($item['media_type'] ?? null, ['IMAGE', 'CAROUSEL_ALBUM', 'VIDEO'], true);
                })
                ->map(function (array $item) {
                    $mediaUrl = $item['media_url'] ?? null;
                    $thumbnailUrl = $item['thumbnail_url'] ?? null;

                    if (!$mediaUrl && isset($item['children']['data']) && is_array($item['children']['data'])) {
                        $firstChild = collect($item['children']['data'])
                            ->first(function (array $child) {
                                return !empty($child['media_url']) || !empty($child['thumbnail_url']);
                            });

                        if ($firstChild) {
                            $mediaUrl = $firstChild['media_url'] ?? null;
                            $thumbnailUrl = $thumbnailUrl ?: ($firstChild['thumbnail_url'] ?? null);
                        }
                    }

                    if (!$mediaUrl) {
                        $mediaUrl = $thumbnailUrl;
                    }

                    if (!$mediaUrl) {
                        return null;
                    }

                    return [
                        'id' => $item['id'],
                        'caption' => Str::limit($item['caption'] ?? '', 120),
                        'media_type' => $item['media_type'],
                        'media_url' => $mediaUrl,
                        'thumbnail_url' => $thumbnailUrl ?: $mediaUrl,
                        'permalink' => $item['permalink'],
                        'timestamp' => $item['timestamp'],
                        'username' => $item['username'] ?? null,
                    ];
                })
                ->filter()
                ->values()
                ->all();

            return [
                'data' => $items,
            ];
        });
        } catch (Throwable $e) {
            Cache::forget($cacheKey);
            
            // Log the error for debugging
            \Log::error('Instagram feed error: ' . $e->getMessage(), [
                'exception' => $e,
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => config('app.debug') ? $e->getMessage() : 'Instagram feed request failed.',
                'data' => [],
            ], 502);
        }

        $status = isset($payload['error']) ? 502 : 200;

        return response()->json($payload, $status);
    }
}

