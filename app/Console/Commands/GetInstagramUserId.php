<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class GetInstagramUserId extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'instagram:get-user-id {--token= : Instagram Access Token}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Instagram Business Account User ID from access token';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('🔍 Getting Instagram User ID...');
        $this->newLine();

        $accessToken = $this->option('token') ?: config('services.instagram.access_token');

        if (empty($accessToken)) {
            $this->error('❌ Access token is required');
            $this->warn('   Usage: php artisan instagram:get-user-id --token=YOUR_TOKEN');
            $this->warn('   Or set INSTAGRAM_ACCESS_TOKEN in .env file');
            return 1;
        }

        try {
            $this->info('🌐 Fetching account information...');
            
            // First, get the user's Facebook pages
            $response = Http::timeout(10)
                ->acceptJson()
                ->get('https://graph.facebook.com/v19.0/me/accounts', [
                    'access_token' => $accessToken,
                    'fields' => 'id,name,instagram_business_account',
                ]);

            if (!$response->successful()) {
                $error = $response->json();
                $this->error('❌ Failed to fetch accounts');
                $this->error('   Error: ' . ($error['error']['message'] ?? 'Unknown error'));
                
                if (isset($error['error']['code']) && $error['error']['code'] == 190) {
                    $this->warn('   → Token may be expired or invalid');
                }
                
                return 1;
            }

            $accounts = $response->json('data', []);

            if (empty($accounts)) {
                $this->warn('⚠️  No Facebook pages found');
                $this->info('   Make sure your access token has "pages_read_engagement" permission');
                return 1;
            }

            $this->info('✅ Found ' . count($accounts) . ' Facebook page(s):');
            $this->newLine();

            $instagramAccounts = [];

            foreach ($accounts as $index => $account) {
                $this->line('   [' . ($index + 1) . '] ' . ($account['name'] ?? 'Unnamed'));
                $this->line('       Page ID: ' . ($account['id'] ?? 'N/A'));
                
                if (isset($account['instagram_business_account']['id'])) {
                    $igId = $account['instagram_business_account']['id'];
                    $this->info('       ✅ Instagram Business Account ID: ' . $igId);
                    $instagramAccounts[] = [
                        'page_name' => $account['name'] ?? 'Unnamed',
                        'page_id' => $account['id'] ?? '',
                        'instagram_id' => $igId,
                    ];
                } else {
                    $this->warn('       ⚠️  No Instagram Business Account connected');
                }
                $this->newLine();
            }

            if (empty($instagramAccounts)) {
                $this->error('❌ No Instagram Business Accounts found');
                $this->warn('   Make sure:');
                $this->warn('   1. Your Facebook page has an Instagram Business Account connected');
                $this->warn('   2. Your access token has proper permissions');
                return 1;
            }

            if (count($instagramAccounts) === 1) {
                $this->info('📝 Add this to your .env file:');
                $this->line('   INSTAGRAM_USER_ID=' . $instagramAccounts[0]['instagram_id']);
            } else {
                $this->info('📝 Multiple Instagram accounts found. Choose one and add to .env:');
                foreach ($instagramAccounts as $acc) {
                    $this->line('   INSTAGRAM_USER_ID=' . $acc['instagram_id'] . '  # ' . $acc['page_name']);
                }
            }

            $this->newLine();
            $this->info('💡 After updating .env, run: php artisan config:cache');
            $this->info('   Then test with: php artisan instagram:test');

            return 0;

        } catch (\Exception $e) {
            $this->error('❌ Exception occurred:');
            $this->error('   ' . $e->getMessage());
            return 1;
        }
    }
}

