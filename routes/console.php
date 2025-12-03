<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Http;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('refresh:instagram', function () {
    try {
        // agar route name use karna ho:
        // $url = route('instagram.feed');

        // simple URL from app.url:
        $url = config('app.url') . '/instagram/feed';

        Http::get($url);

        $this->info('Instagram feed cache refreshed: ' . $url);
    } catch (\Throwable $e) {
        $this->error('Failed to refresh instagram feed: '.$e->getMessage());
    }
})->describe('Warm up Instagram feed cache and keep IG token active.');
