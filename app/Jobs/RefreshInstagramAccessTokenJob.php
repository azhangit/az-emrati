<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RefreshInstagramAccessTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $currentToken = config('services.instagram.access_token');
        $clientId = config('services.facebook.client_id');
        $clientSecret = config('services.facebook.client_secret');
        $graphVersion = config('services.instagram.graph_version', 'v19.0');

        if (empty($currentToken)) {
            Log::error('Instagram token refresh skipped: INSTAGRAM_ACCESS_TOKEN missing in .env');
            return;
        }

        if (empty($clientId) || empty($clientSecret)) {
            Log::error('Instagram token refresh skipped: FACEBOOK_CLIENT_ID or FACEBOOK_CLIENT_SECRET missing in .env');
            return;
        }

        $endpoint = "https://graph.facebook.com/{$graphVersion}/oauth/access_token";

        try {
            $response = Http::timeout(20)->get($endpoint, [
                'grant_type' => 'fb_exchange_token',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'fb_exchange_token' => $currentToken,
            ]);

            if (!$response->successful()) {
                $error = $response->json();
                Log::error('Instagram token refresh API failed.', [
                    'status' => $response->status(),
                    'error' => $error['error']['message'] ?? 'Unknown error',
                ]);
                return;
            }

            $newToken = $response->json('access_token');
            if (empty($newToken)) {
                Log::error('Instagram token refresh failed: access_token not found in response');
                return;
            }

            if (!$this->updateEnvFile('INSTAGRAM_ACCESS_TOKEN', $newToken)) {
                Log::error('Instagram token refresh failed: unable to update .env');
                return;
            }

            // Reload cached config so new token is used by the app.
            Artisan::call('config:cache');
            Log::info('Instagram Access Token refreshed successfully via daily scheduled job.');
        } catch (\Throwable $e) {
            Log::error('Instagram token refresh exception: ' . $e->getMessage());
        }
    }

    private function updateEnvFile(string $key, string $value): bool
    {
        $path = base_path('.env');
        if (!File::exists($path)) {
            return false;
        }

        $content = File::get($path);

        if (strpos($content, $key . '=') === false) {
            $content .= PHP_EOL . $key . '=' . $value;
        } else {
            $escapedKey = preg_quote($key, '/');
            $content = preg_replace('/^' . $escapedKey . '=.*$/m', $key . '=' . $value, $content);
            if ($content === null) {
                return false;
            }
        }

        File::put($path, $content);
        return true;
    }
}
