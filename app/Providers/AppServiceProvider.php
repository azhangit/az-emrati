<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\Product;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Session;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();

        // 🔐 Force HTTPS in production
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }
       //  Guest checkout ke liye temp_user_id
    if (!auth()->check()) {
        if (!Session::has('temp_user_id')) {
            Session::put('temp_user_id', Str::random(20));
        }
    }
        // ☕️ Coffee Varieties
        View::composer('frontend.inc.nav', function ($view) {
            $coffeeProducts = Product::where('category_id', 4)
                ->where('published', 1)
                ->get();

            $exclusiveProducts = Product::where('category_id', 9)
                ->where('published', 1)
                ->get();

            $capsuleProducts = Product::where('category_id', 5)
                ->where('published', 1)
                ->get();

            $instantProducts = Product::where('category_id', 8)
                ->where('published', 1)
                ->get();

            $dripProducts = Product::where('category_id', 6)
                ->where('published', 1)
                ->get();

            $teaProducts = Product::where('category_id', 7)
                ->where('published', 1)
                ->get();

            $view->with(compact(
                'coffeeProducts',
                'exclusiveProducts',
                'capsuleProducts',
                'instantProducts',
                'dripProducts',
                'teaProducts'
            ));
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
