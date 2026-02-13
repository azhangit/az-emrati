<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;

class RefreshInstagramToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:refresh-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh the long-lived Instagram/Facebook access token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔄 Attempting to refresh Instagram Access Token...');

        // 1. Retrieve Configuration
        $currentToken = config('services.instagram.access_token');
        $clientId = config('services.facebook.client_id');
        $clientSecret = config('services.facebook.client_secret');
        $graphVersion = config('services.instagram.graph_version', 'v19.0');

        // 2. Validate Configuration
        if (empty($currentToken)) {
            $this->error('❌ INSTAGRAM_ACCESS_TOKEN is missing in .env');
            return 1;
        }

        if (empty($clientId) || empty($clientSecret)) {
            $this->error('❌ FACEBOOK_CLIENT_ID or FACEBOOK_CLIENT_SECRET is missing in .env');
            $this->line('   To refresh the token automatically, you must provide your App ID and Secret.');
            return 1;
        }

        // 3. Call Facebook Graph API to exchange token
        // Endpoint: GET /oauth/access_token
        $endpoint = "https://graph.facebook.com/{$graphVersion}/oauth/access_token";
        
        try {
            $response = Http::get($endpoint, [
                'grant_type' => 'fb_exchange_token',
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'fb_exchange_token' => $currentToken,
            ]);

            if (!$response->successful()) {
                $error = $response->json();
                $this->error('❌ Failed to refresh token from Facebook API.');
                $this->error('   Error: ' . ($error['error']['message'] ?? 'Unknown error'));
                return 1;
            }

            $data = $response->json();
            $newToken = $data['access_token'] ?? null;

            if (!$newToken) {
                $this->error('❌ API response did not contain a new access token.');
                return 1;
            }

            // 4. Update the .env file
            if ($this->updateEnvFile('INSTAGRAM_ACCESS_TOKEN', $newToken)) {
                $this->info('✅ New token written to .env file.');
                \Log::info('Instagram Access Token refreshed successfully via scheduled job.');
                
                // 5. Clear Config Cache to apply changes
                $this->call('config:cache');
                $this->info('✅ Application configuration cached.');
                
                $this->info('🎉 Token refreshed successfully!');
                return 0;
            } else {
                $this->error('❌ Failed to update .env file.');
                \Log::error('Failed to update .env file with new Instagram token.');
                return 1;
            }

        } catch (\Exception $e) {
            $this->error('❌ Exception occurred: ' . $e->getMessage());
            \Log::error('Exception during Instagram token refresh: ' . $e->getMessage());
            return 1;
        }
    }

    /**
     * Update a key in the .env file.
     *
     * @param string $key
     * @param string $value
     * @return bool
     */
    protected function updateEnvFile($key, $value)
    {
        $path = base_path('.env');

        if (!File::exists($path)) {
            return false;
        }

        $oldContent = File::get($path);
        
        // Escape the new value if necessary (though simple alphanumeric tokens are fine)
        // We'll wrap in quotes just to be safe if it contains special chars, 
        // but typically FB tokens are safe. Let's stick to simple replacement.
        
        // Pattern matches: KEY=ANYTHING_UNTIL_NEWLINE
        // We expect the existing key might be followed by comments, but usually not in automated environments.
        // We will strictly replace KEY=VALUE
        
        // Check if key exists
        if (strpos($oldContent, $key) === false) {
             // Append if it doesn't exist
             $newContent = $oldContent . PHP_EOL . "{$key}={$value}";
        } else {
            // Replace existing
            $newContent = preg_replace(
                "/^{$key}=.*/m",
                "{$key}={$value}",
                $oldContent
            );
        }

        if ($newContent === null || $newContent === $oldContent) {
            // If regex failed or no change needed (unlikely if token changed)
            if (strpos($oldContent, $value) !== false) {
                 return true; // Already has this value
            }
        }

        try {
            File::put($path, $newContent);
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
