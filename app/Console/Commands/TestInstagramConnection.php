<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class TestInstagramConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Instagram API connection and display feed status';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Testing Instagram Integration...');
        $this->newLine();

        // Check configuration
        $accessToken = config('services.instagram.access_token');
        $userId = config('services.instagram.user_id');
        $graphVersion = config('services.instagram.graph_version', 'v19.0');
        $limit = config('services.instagram.limit', 8);

        // Validate credentials
        if (empty($accessToken)) {
            $this->error('❌ INSTAGRAM_ACCESS_TOKEN is not set in .env file');
            $this->warn('   Please add: INSTAGRAM_ACCESS_TOKEN=your_token_here');
            return 1;
        }

        if (empty($userId)) {
            $this->error('❌ INSTAGRAM_USER_ID is not set in .env file');
            $this->warn('   Please add: INSTAGRAM_USER_ID=your_user_id_here');
            $this->info('   Run: php artisan instagram:get-user-id');
            return 1;
        }

        $this->info('✅ Configuration found:');
        $this->line('   Access Token: ' . substr($accessToken, 0, 20) . '...');
        $this->line('   User ID: ' . $userId);
        $this->line('   Graph Version: ' . $graphVersion);
        $this->line('   Media Limit: ' . $limit);
        $this->newLine();

        // Test API connection
        $this->info('🌐 Testing API connection...');
        
        try {
            $version = ltrim($graphVersion, 'v');
            $endpoint = sprintf('https://graph.facebook.com/v%s/%s/media', $version, $userId);
            
            $this->line('   Endpoint: ' . $endpoint);
            
            $response = Http::timeout(10)
                ->acceptJson()
                ->get($endpoint, [
                    'fields' => 'id,caption,media_type,media_url,permalink,timestamp',
                    'access_token' => $accessToken,
                    'limit' => 3, // Just test with 3 items
                ]);

            if (!$response->successful()) {
                $error = $response->json();
                $this->error('❌ API Request Failed!');
                $this->error('   Status: ' . $response->status());
                $this->error('   Error: ' . ($error['error']['message'] ?? 'Unknown error'));
                
                if (isset($error['error']['code'])) {
                    $this->warn('   Error Code: ' . $error['error']['code']);
                    
                    // Provide helpful suggestions
                    if ($error['error']['code'] == 190) {
                        $this->warn('   → Token may be expired. Get a new access token.');
                    } elseif ($error['error']['code'] == 100) {
                        $this->warn('   → Invalid user ID. Run: php artisan instagram:get-user-id');
                    }
                }
                
                return 1;
            }

            $data = $response->json();
            $items = $data['data'] ?? [];

            if (empty($items)) {
                $this->warn('⚠️  Connection successful but no media found');
                $this->info('   This might be normal if the Instagram account has no posts.');
                return 0;
            }

            $this->info('✅ Connection successful!');
            $this->newLine();
            $this->info('📸 Found ' . count($items) . ' media item(s):');
            $this->newLine();

            foreach ($items as $index => $item) {
                $this->line('   [' . ($index + 1) . '] ' . ($item['media_type'] ?? 'UNKNOWN'));
                if (isset($item['caption'])) {
                    $caption = strlen($item['caption']) > 50 
                        ? substr($item['caption'], 0, 50) . '...' 
                        : $item['caption'];
                    $this->line('       Caption: ' . $caption);
                }
                if (isset($item['permalink'])) {
                    $this->line('       Link: ' . $item['permalink']);
                }
                $this->newLine();
            }

            // Test cache
            $this->info('💾 Testing cache...');
            Cache::forget('instagram.feed.v1');
            $cacheKey = 'instagram.feed.v1';
            $ttl = config('services.instagram.cache_ttl', 900);
            
            Cache::remember($cacheKey, $ttl, function () use ($items) {
                return ['data' => $items];
            });

            if (Cache::has($cacheKey)) {
                $this->info('✅ Cache is working correctly');
            } else {
                $this->warn('⚠️  Cache might not be configured properly');
            }

            $this->newLine();
            $this->info('🎉 Instagram integration is working correctly!');
            $this->info('   Visit your website to see the feed in action.');
            
            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Exception occurred:');
            $this->error('   ' . $e->getMessage());
            return 1;
        }
    }
}

