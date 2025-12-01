<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\AizUploadController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\VerificationController;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\CompareController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\CurrencyController;
use App\Http\Controllers\CustomerPackageController;
use App\Http\Controllers\CustomerProductController;
use App\Http\Controllers\DemoController;
use App\Http\Controllers\FollowSellerController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Payment\AamarpayController;
use App\Http\Controllers\Payment\AuthorizenetController;
use App\Http\Controllers\Payment\BkashController;
use App\Http\Controllers\Payment\InstamojoController;
use App\Http\Controllers\Payment\IyzicoController;
use App\Http\Controllers\Payment\MercadopagoController;
use App\Http\Controllers\Payment\NagadController;
use App\Http\Controllers\Payment\NgeniusController;
use App\Http\Controllers\Payment\PayhereController;
use App\Http\Controllers\Payment\PaykuController;
use App\Http\Controllers\Payment\PaypalController;
use App\Http\Controllers\Payment\PaystackController;
use App\Http\Controllers\Payment\PhonepeController;
use App\Http\Controllers\Payment\RazorpayController;
use App\Http\Controllers\Payment\SslcommerzController;
use App\Http\Controllers\Payment\StripeController;
use App\Http\Controllers\Payment\VoguepayController;
use App\Http\Controllers\ProductQueryController;
use App\Http\Controllers\PurchaseHistoryController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SubscriberController;
use App\Http\Controllers\SupportTicketController;
use App\Http\Controllers\WalletController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\SizeChartController;
use App\Http\Controllers\Controllers;
use App\Http\Controllers\NewCheckoutController;
use App\Http\Controllers\SubscriptionController;
use App\Http\Controllers\SubStableController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Frontend\InstagramFeedController;
     

/*
  |--------------------------------------------------------------------------
  | Web Routes
  |--------------------------------------------------------------------------
  |
  | Here is where you can register web routes for your application. These
  | routes are loaded by the RouteServiceProvider within a group which
  | contains the "web" middleware group. Now create something great!
  |
 */
 

use App\Http\Controllers\Admin\OrdersExportController;

use App\Jobs\SendSubscriptionEmailJob;

// routes/web.php
use App\Http\Controllers\OrderController;

Route::get('/ajax/search-products', [\App\Http\Controllers\Frontend\AjaxSearchController::class, 'products'])
     ->name('ajax.search.products');
     
Route::get('/instagram/feed', InstagramFeedController::class)
    ->name('instagram.feed');
    use Illuminate\Support\Facades\Http;


    Route::get('/instagram/debug-once', function () {
        // 🔴 STEP 1: yahan GRAPH API EXPLORER se direct token paste karo
        $token = 'EAAJ9m4jXiJUBQOZA7wwWQxDxYd04WfXCS7GvpgoLrzE0BwSQEQR6cLZAegVssTUwDVk3NMghYLARNtEyEVt6M5t8CA14EUR2EbTzz43ZB7ZAghLQZBKHNHS8KTG3p2IMrZCCP3PTMZComytFVQRZCrGr5nNZCEwvqSKzbEF6gDqePpwLPyuX0lM2ncGsWEGzeMAk7g13KyK05v98gOiZCZBFjBNzP3vibHFczHzPZAKut63ZCKSOQancU1w5ZCNapdcnV3klMmyiKf26KW3ZBRjrfm9EdZAne2zZCSReZCoQDVLAZDZD'; // poora token, ek line me
    
        $info = [
            'token_len'   => strlen($token),
            'token_start' => substr($token, 0, 12),
            'token_end'   => substr($token, -8),
        ];
    
        $meAccounts = Http::get('https://graph.facebook.com/v24.0/me/accounts', [
            'access_token' => $token,
        ])->json();
    
        $info['me_accounts_raw'] = $meAccounts;
    
        return $info;
    });
    
    
// Route::get('/instagram/debug-once', function () {
//     $token = env('INSTAGRAM_ACCESS_TOKEN');

//     if (!$token) {
//         return response()->json(['error' => 'INSTAGRAM_ACCESS_TOKEN missing in .env'], 500);
//     }

//     // 1) Get all pages for this user
//     $pages = Http::get('https://graph.facebook.com/v24.0/me/accounts', [
//         'access_token' => $token,
//     ])->json();

//     if (isset($pages['error'])) {
//         return response()->json([
//             'step'  => 'me/accounts',
//             'error' => $pages['error'],
//         ], 500);
//     }

//     if (empty($pages['data'])) {
//         return response()->json(['error' => 'No pages found for this token'], 404);
//     }

//     // Try to pick a page that looks like Emirati
//     $page = collect($pages['data'])
//         ->first(fn ($p) => isset($p['name']) && stripos($p['name'], 'emirati') !== false)
//         ?? $pages['data'][0];

//     $pageId = $page['id'] ?? null;

//     if (!$pageId) {
//         return response()->json(['error' => 'Page id not found'], 500);
//     }

//     // 2) Get instagram_business_account from that page
//     $ig = Http::get("https://graph.facebook.com/v24.0/{$pageId}", [
//         'fields'       => 'instagram_business_account',
//         'access_token' => $token,
//     ])->json();

//     if (isset($ig['error'])) {
//         return response()->json([
//             'step'  => 'page -> instagram_business_account',
//             'error' => $ig['error'],
//         ], 500);
//     }

//     $igUserId = $ig['instagram_business_account']['id'] ?? null;

//     if (!$igUserId) {
//         return response()->json([
//             'error' => 'No instagram_business_account connected to this page',
//             'page'  => $page,
//         ], 404);
//     }

//     // 3) Test media
//     $media = Http::get("https://graph.facebook.com/v24.0/{$igUserId}/media", [
//         'fields'       => 'id,caption,media_type,media_url,permalink,timestamp',
//         'access_token' => $token,
//         'limit'        => 5,
//     ])->json();

//     return [
//         'page'       => $page,
//         'ig_user_id' => $igUserId,
//         'media'      => $media,
//     ];
// });

  Route::get('/ajax/search-products', function (\Illuminate\Http\Request $r) {
    try {
        $q = trim((string)$r->query('q',''));
        if (mb_strlen($q) < 2) {
            return response()->json(['ok'=>true,'data'=>['items'=>[],'total'=>0,'page'=>1,'nextPage'=>null]]);
        }

        // ---- helpers ----
        $absUrl = function (?string $url) {
            if (!$url) {
                return function_exists('static_asset')
                    ? static_asset('assets/img/placeholder.jpg')
                    : url('public/assets/img/placeholder.jpg');
            }
            if (preg_match('#^https?://#i', $url)) {
                return (config('app.url') && str_starts_with(config('app.url'),'https://'))
                    ? preg_replace('#^http://#i', 'https://', $url)
                    : $url;
            }
            return url(ltrim($url, '/'));
        };
      $fileFromId = function ($id) {
    // 1) Prefer uploaded_asset → mostly returns real path under /public/uploads/...
    try {
        if (function_exists('uploaded_asset')) {
            $u = uploaded_asset($id);
            if ($u && !preg_match('#/\\d+$#', $u)) { // not like /386
                return $u;
            }
        }
    } catch (\Throwable $e) {}

    // 2) Try get_image (some themes)
    try {
        if (function_exists('get_image')) {
            $u = get_image($id);
            if ($u && !preg_match('#/\\d+$#', $u)) {
                return $u;
            }
        }
    } catch (\Throwable $e) {}

    // 3) Direct DB fallback → core uploads table
    try {
        if (is_numeric($id) && Schema::hasTable('uploads')) {
            $path = DB::table('uploads')->where('id', (int)$id)->value('file_name'); // e.g. uploads/all/abc.jpg
            if ($path) {
                return url('public/'.ltrim($path,'/'));
            }
        }
    } catch (\Throwable $e) {}

    return null; // let caller fallback to placeholder
};
        $buildThumb = function ($row) use ($fileFromId, $absUrl) {
            $thumb = null;

            // 1) thumbnail (path or id)
            $v = $row->thumbnail ?? null;
            if ($v) {
                if (is_string($v) && preg_match('#^https?://#i',$v)) $thumb = $v;
                if (!$thumb && is_numeric($v))                       $thumb = $fileFromId($v);
                if (!$thumb && is_string($v))                        $thumb = $v;
            }

            // 2) thumbnail_img (usually upload id)
            if (!$thumb && !empty($row->thumbnail_img)) {
                $thumb = $fileFromId($row->thumbnail_img) ?: (is_string($row->thumbnail_img) ? $row->thumbnail_img : null);
            }

            // 3) photos (JSON of ids) → first id
            if (!$thumb && isset($row->photos) && $row->photos) {
                $ids = $row->photos;
                if (is_string($ids)) {
                    // try JSON decode or CSV
                    $decoded = json_decode($ids, true);
                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded) && count($decoded)) {
                        $first = $decoded[0] ?? null;
                        if ($first) $thumb = $fileFromId($first);
                    } else {
                        // sometimes stored like "123,456"
                        $parts = array_filter(array_map('trim', explode(',', $ids)));
                        if (!empty($parts)) {
                            $thumb = $fileFromId($parts[0]);
                        }
                    }
                }
            }

            // 4) meta_img (some installs)
            if (!$thumb && !empty($row->meta_img)) {
                $thumb = $fileFromId($row->meta_img) ?: (is_string($row->meta_img) ? $row->meta_img : null);
            }

            return $absUrl($thumb);
        };

        // ---- columns to select (only if present) ----
        $want = ['id','name','slug','thumbnail','thumbnail_img','unit_price','created_at','photos','meta_img'];
        $select = [];
        foreach ($want as $c) if (Schema::hasColumn('products',$c)) $select[] = 'products.'.$c;
        if (empty($select)) $select = ['products.id','products.name'];

        // ---- base query ----
        $qb = DB::table('products')->select($select)
            ->where('products.name','like',"%{$q}%");

        // ---- only published/approved (apply if columns exist) ----
        if (Schema::hasColumn('products','published'))         $qb->where('products.published', 1);
        if (Schema::hasColumn('products','is_published'))      $qb->where('products.is_published', 1);
        if (Schema::hasColumn('products','status'))            $qb->whereIn('products.status',[1,'1','active','published']);
        if (Schema::hasColumn('products','approved'))          $qb->where('products.approved', 1);
        if (Schema::hasColumn('products','approved_by_admin')) $qb->where('products.approved_by_admin', 1);
        if (Schema::hasColumn('products','deleted_at'))        $qb->whereNull('products.deleted_at');
        // optional: sirf admin ke products
        if (Schema::hasColumn('products','added_by'))          $qb->where('products.added_by','admin');

        $rows = $qb->orderByDesc('products.created_at')
                   ->limit((int) $r->query('limit', 8) ?: 8)
                   ->get();

        $items = $rows->map(function($p) use ($buildThumb) {
            // price
            $price = '';
            try {
                if (function_exists('home_discounted_base_price')) {
                    $price = home_discounted_base_price($p);
                } elseif (isset($p->unit_price)) {
                    $t = number_format((float)$p->unit_price, 2);
                    $price = function_exists('currency_symbol') ? currency_symbol().' '.$t : $t;
                }
            } catch (\Throwable $e) {
                if (isset($p->unit_price)) $price = number_format((float)$p->unit_price, 2);
            }

            // url
            try {
                $url = !empty($p->slug) ? route('product', $p->slug) : url('product/'.$p->id);
            } catch (\Throwable $e) {
                $url = url('product/'.$p->id);
            }

            return [
                'id'    => (int) $p->id,
                'name'  => (string) $p->name,
                'slug'  => (string) ($p->slug ?? ''),
                'thumb' => $buildThumb($p),
                'price' => $price,
                'url'   => $url,
            ];
        })->values();

        return response()->json(['ok'=>true,'data'=>['items'=>$items]]);
    } catch (\Throwable $e) {
        \Log::error('ajax/search-products failed', ['err'=>$e->getMessage()]);
        return response()->json(['ok'=>false,'error'=>$e->getMessage()], 500);
    }
});


Route::middleware(['auth']) // apni permission laga sakte ho
    ->get('/admin/exports/orders-all.csv', [OrderController::class, 'exportAllCsv'])
    ->name('admin.exports.orders-all.csv');
Route::post('/orders/export-csv', [\App\Http\Controllers\OrderController::class, 'exportCsv'])
    ->name('orders.export.csv');

Route::get('/test-subscription-email', function () {
    // ID wo dalo jo tumhare subscription_schedules table me hai
    $scheduleId = 1;

    // Sync driver ke liye direct handle bhi kar sakte ho:
    // (new SendSubscriptionEmailJob($scheduleId))->handle();

    // Ya queue pe bhejna ho to:
    dispatch(new SendSubscriptionEmailJob($scheduleId));

    return "Email job dispatched for schedule id: $scheduleId";
});

Route::get('/admin/subscription', [SubStableController::class, 'index'])->name('admin.subscription.index');
Route::get('/admin/subscription/{id}', [SubStableController::class, 'show'])->name('admin.subscription.show'); 

Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->name('product.subscribe');
Route::get('/subscription/get-product-sizes/{id}', [SubscriptionController::class, 'getProductSizes']);

        // NEW ROUTES

        Route::get('/event', function () {
            return view('event');
        });
                Route::get('/main', function () {
            return view('main');
        });
           Route::get('/terms-conditions', function () {
            return view('terms-conditions');
        });
        
        
                        Route::get('/privacy-policy', function () {
            return view('privacy-policy');
        });
        
                
                        Route::get('/travel', function () {
            return view('travel');
        });
        
        
                                Route::get('/refund-return-policy', function () {
            return view('refund-return-policy');
        });
        
                                        Route::get('/shipping-policy', function () {
            return view('shipping-policy');
        });

       Route::get('/academy', [EventController::class, 'academy'])->name('academy');
       Route::get('/institute/{id}/courses', [EventController::class, 'showCoursesByInstitute'])->name('courses.by-institute');
       Route::get('/course/{id}/booking', [EventController::class, 'showBooking'])->name('course.booking');
        // Route::get('/subscription', function () {
        //     return view('subscription');
        // });
        Route::get('/about', function () {
            return view('about');
        });
        Route::get('/wholesale', function () {
            return view('wholesale');
        });
        Route::get('/contact', function () {
            return view('contact');
        });

// Route::post('/subscription-summary', [SubscriptionController::class, 'summary'])->name('subscription.summary')->middleware('auth');


Route::get('/test-session', function () {
    session()->put('test_key', 'Hello, Laravel!');
    return response()->json(session()->all());
});


        // NEW ROUTES END

Route::controller(DemoController::class)->group(function () {
    Route::get('/demo/cron_1', 'cron_1');
    Route::get('/demo/cron_2', 'cron_2');
    Route::get('/convert_assets', 'convert_assets');
    Route::get('/convert_category', 'convert_category');
    Route::get('/convert_tax', 'convertTaxes');
    Route::get('/set-category', 'setCategoryToProductCategory');
    Route::get('/insert_product_variant_forcefully', 'insert_product_variant_forcefully');
    Route::get('/update_seller_id_in_orders/{id_min}/{id_max}', 'update_seller_id_in_orders');
    Route::get('/migrate_attribute_values', 'migrate_attribute_values');
});

Route::get('/refresh-csrf', function () {
    return csrf_token();
});

// AIZ Uploader
Route::controller(AizUploadController::class)->group(function () {
    Route::post('/aiz-uploader', 'show_uploader');
    Route::post('/aiz-uploader/upload', 'upload');
    Route::get('/aiz-uploader/get-uploaded-files', 'get_uploaded_files');
    Route::post('/aiz-uploader/get_file_by_ids', 'get_preview_files');
    Route::get('/aiz-uploader/download/{id}', 'attachment_download')->name('download_attachment');
});

Route::group(['middleware' => ['prevent-back-history','handle-demo-login']], function () {
    Auth::routes(['verify' => true]);
});

// Login
Route::controller(LoginController::class)->group(function () {
    Route::get('/logout', 'logout');
    Route::get('/social-login/redirect/{provider}', 'redirectToProvider')->name('social.login');
    Route::get('/social-login/{provider}/callback', 'handleProviderCallback')->name('social.callback');
    //Apple Callback
    Route::post('/apple-callback', 'handleAppleCallback');
    Route::get('/account-deletion', 'account_deletion')->name('account_delete');
    Route::get('/handle-demo-login', 'handle_demo_login')->name('handleDemoLogin');
});

Route::controller(VerificationController::class)->group(function () {
    Route::get('/email/resend', 'resend')->name('verification.resend');
    Route::get('/verification-confirmation/{code}', 'verification_confirmation')->name('email.verification.confirmation');
});

Route::controller(HomeController::class)->group(function () {
    Route::get('/email-change/callback', 'email_change_callback')->name('email_change.callback');
    Route::post('/password/reset/email/submit', 'reset_password_with_code')->name('password.update');

    Route::get('/users/login', 'login')->name('user.login')->middleware('handle-demo-login');
    Route::get('/seller/login', 'login')->name('seller.login')->middleware('handle-demo-login');
    Route::get('/deliveryboy/login', 'login')->name('deliveryboy.login')->middleware('handle-demo-login');
    Route::get('/users/registration', 'registration')->name('user.registration')->middleware('handle-demo-login');
    Route::post('/users/login/cart', 'cart_login')->name('cart.login.submit')->middleware('handle-demo-login');
    // Route::get('/new-page', 'new_page')->name('new_page');

    Route::post('/import-data', 'import_data');

    //Home Page
    Route::get('/', 'index')->name('home');

    Route::post('/home/section/featured', 'load_featured_section')->name('home.section.featured');
    Route::post('/home/section/todays-deal', 'load_todays_deal_section')->name('home.section.todays_deal');
    Route::post('/home/section/best-selling', 'load_best_selling_section')->name('home.section.best_selling');
    Route::post('/home/section/newest-products', 'load_newest_product_section')->name('home.section.newest_products');
    Route::post('/home/section/home-categories', 'load_home_categories_section')->name('home.section.home_categories');
    Route::post('/home/section/best-sellers', 'load_best_sellers_section')->name('home.section.best_sellers');

    //category dropdown menu ajax call
    Route::post('/category/nav-element-list', 'get_category_items')->name('category.elements');

    //Flash Deal Details Page
    Route::get('/flash-deals', 'all_flash_deals')->name('flash-deals');
    Route::get('/flash-deal/{slug}', 'flash_deal_details')->name('flash-deal-details');

    //Todays Deal Details Page
    Route::get('/todays-deal', 'todays_deal')->name('todays-deal');
    Route::get('/product/api/{slug}', 'product_json')->name('product_json');
    Route::get('/product/{slug}', 'product')->name('product');
    Route::post('/product/variant-price', 'variant_price')->name('products.variant_price');
    Route::get('/shop/{slug}', 'shop')->name('shop.visit');
    Route::get('/shop/{slug}/{type}', 'filter_shop')->name('shop.visit.type');

    Route::get('/customer-packages', 'premium_package_index')->name('customer_packages_list_show');

    Route::get('/brands', 'all_brands')->name('brands.all');
    Route::get('/categories', 'all_categories')->name('categories.all');
    Route::get('/sellers', 'all_seller')->name('sellers');
    Route::get('/coupons', 'all_coupons')->name('coupons.all');
    Route::get('/inhouse', 'inhouse_products')->name('inhouse.all');


    // Policies
    Route::get('/seller-policy', 'sellerpolicy')->name('sellerpolicy');
    Route::get('/return-policy', 'returnpolicy')->name('returnpolicy');
    Route::get('/support-policy', 'supportpolicy')->name('supportpolicy');
    Route::get('/terms', 'terms')->name('terms');
    //  Route::get('/privacy-policy', 'privacypolicy')->name('privacypolicy');

    Route::get('/track-your-order', 'trackOrder')->name('orders.track');
});

// Language Switch
Route::post('/language', [LanguageController::class, 'changeLanguage'])->name('language.change');

// Currency Switch
Route::post('/currency', [CurrencyController::class, 'changeCurrency'])->name('currency.change');

// Size Chart Show
Route::get('/size-charts-show/{id}', [SizeChartController::class, 'show'])->name('size-charts-show');

Route::get('/sitemap.xml', function () {
    return base_path('sitemap.xml');
});

// Classified Product
Route::controller(CustomerProductController::class)->group(function () {
    Route::get('/customer-products', 'customer_products_listing')->name('customer.products');
    Route::get('/customer-products?category={category_slug}', 'search')->name('customer_products.category');
    Route::get('/customer-products?city={city_id}', 'search')->name('customer_products.city');
    Route::get('/customer-products?q={search}', 'search')->name('customer_products.search');
    Route::get('/customer-product/{slug}', 'customer_product')->name('customer.product');
});
Route::get('/subscription', [SubscriptionController::class, 'index'])
     ->name('subscription');
// Search
Route::controller(SearchController::class)->group(function () {
    Route::get('/search', 'index')->name('search');
    Route::get('/search?keyword={search}', 'index')->name('suggestion.search');
    Route::post('/ajax-search', 'ajax_search')->name('search.ajax');
    Route::get('/category/{category_slug}', 'listingByCategory')->name('products.category');
    // Route::get('/subscription', 'subscription')->name('products.subscription');
    Route::get('/brand/{brand_slug}', 'listingByBrand')->name('products.brand');
});

// Cart
Route::controller(CartController::class)->group(function () {
    Route::get('/cart', 'index')->name('cart');
    Route::post('/cart/show-cart-modal', 'showCartModal')->name('cart.showCartModal');
    Route::post('/cart/addtocart', 'addToCart')->name('cart.addToCart');
    Route::post('/cart/removeFromCart', 'removeFromCart')->name('cart.removeFromCart');
    Route::post('/cart/updateQuantity', 'updateQuantity')->name('cart.updateQuantity');
});

//Paypal START
Route::controller(PaypalController::class)->group(function () {
    Route::get('/paypal/payment/done', 'getDone')->name('payment.done');
    Route::get('/paypal/payment/cancel', 'getCancel')->name('payment.cancel');
});
//Mercadopago START
Route::controller(MercadopagoController::class)->group(function () {
    Route::any('/mercadopago/payment/done', 'paymentstatus')->name('mercadopago.done');
    Route::any('/mercadopago/payment/cancel', 'callback')->name('mercadopago.cancel');
});
//Mercadopago 

// SSLCOMMERZ Start
Route::controller(SslcommerzController::class)->group(function () {
    Route::get('/sslcommerz/pay', 'index');
    Route::POST('/sslcommerz/success', 'success');
    Route::POST('/sslcommerz/fail', 'fail');
    Route::POST('/sslcommerz/cancel', 'cancel');
    Route::POST('/sslcommerz/ipn', 'ipn');
});
//SSLCOMMERZ END

//Stipe Start
Route::controller(StripeController::class)->group(function () {
    Route::get('stripe', 'stripe');
    Route::post('/stripe/create-checkout-session', 'create_checkout_session')->name('stripe.get_token');
    Route::any('/stripe/payment/callback', 'callback')->name('stripe.callback');
    Route::get('/stripe/success', 'success')->name('stripe.success');
    Route::get('/stripe/cancel', 'cancel')->name('stripe.cancel');
});
//Stripe END

// Compare
Route::controller(CompareController::class)->group(function () {
    Route::get('/compare', 'index')->name('compare');
    Route::get('/compare/reset', 'reset')->name('compare.reset');
    Route::post('/compare/addToCompare', 'addToCompare')->name('compare.addToCompare');
    Route::get('/compare/details/{id}', 'details')->name('compare.details');
});

// Subscribe
Route::resource('subscribers', SubscriberController::class);

Route::group(['middleware' => ['user', 'verified', 'unbanned']], function () {

    Route::controller(HomeController::class)->group(function () {
        Route::get('/dashboard', 'dashboard')->name('dashboard')->middleware(['prevent-back-history']);
        Route::get('/profile', 'profile')->name('profile');
        Route::post('/new-user-verification', 'new_verify')->name('user.new.verify');
        Route::post('/new-user-email', 'update_email')->name('user.change.email');
        Route::post('/user/update-profile', 'userProfileUpdate')->name('user.profile.update');
    });

    Route::get('/all-notifications', [NotificationController::class, 'index'])->name('all-notifications');
});



    // Checkout Routs
    Route::group(['prefix' => 'checkout'], function () {
        Route::controller(CheckoutController::class)->group(function () {
            Route::get('/', 'get_shipping_info')->name('checkout.shipping_info');
            Route::any('/delivery-info', 'store_shipping_info')->name('checkout.store_shipping_infostore');
            Route::post('/payment-select', 'store_delivery_info')->name('checkout.store_delivery_info');
            Route::get('/order-confirmed', 'order_confirmed')->name('order_confirmed');
            Route::post('/payment', 'checkout')->name('payment.checkout');
            Route::post('/get-pick-up-points', 'get_pick_up_points')->name('shipping_info.get_pick_up_points');
            Route::get('/payment-select', 'get_payment_info')->name('checkout.payment_info');
            Route::post('/apply-coupon-code', 'apply_coupon_code')->name('checkout.apply_coupon_code');
            Route::post('/remove-coupon-code', 'remove_coupon_code')->name('checkout.remove_coupon_code');
            //Club point
            Route::post('/apply-club-point', 'apply_club_point')->name('checkout.apply_club_point');
            Route::post('/remove-club-point', 'remove_club_point')->name('checkout.remove_club_point');
        });
    });


Route::group(['middleware' => ['customer', 'verified', 'unbanned']], function () {
    // Purchase History
    Route::resource('purchase_history', PurchaseHistoryController::class);
    Route::controller(PurchaseHistoryController::class)->group(function () {
        Route::get('/purchase_history/details/{id}', 'purchase_history_details')->name('purchase_history.details');
        Route::get('/purchase_history/destroy/{id}', 'order_cancel')->name('purchase_history.destroy');
        Route::get('digital-purchase-history', 'digital_index')->name('digital_purchase_history.index');
        Route::get('/digital-products/download/{id}', 'download')->name('digital-products.download');

        Route::get('/re-order/{id}', 're_order')->name('re_order');
    });

    // Wishlist
    Route::resource('wishlists', WishlistController::class);
    Route::post('/wishlists/remove', [WishlistController::class, 'remove'])->name('wishlists.remove');

    //Follow
    Route::controller(FollowSellerController::class)->group(function () {
        Route::get('/followed-seller', 'index')->name('followed_seller');
        Route::get('/followed-seller/store', 'store')->name('followed_seller.store');
        Route::get('/followed-seller/remove', 'remove')->name('followed_seller.remove');
    });

    // Wallet
    Route::controller(WalletController::class)->group(function () {
        Route::get('/wallet', 'index')->name('wallet.index');
        Route::post('/recharge', 'recharge')->name('wallet.recharge');
    });

    // Support Ticket
    Route::resource('support_ticket', SupportTicketController::class);
    Route::post('support_ticket/reply', [SupportTicketController::class, 'seller_store'])->name('support_ticket.seller_store');

    // Customer Package
    Route::post('/customer-packages/purchase', [CustomerPackageController::class, 'purchase_package'])->name('customer_packages.purchase');

    // Customer Product
    Route::resource('customer_products', CustomerProductController::class);
    Route::controller(CustomerProductController::class)->group(function () {
        Route::get('/customer_products/{id}/edit', 'edit')->name('customer_products.edit');
        Route::post('/customer_products/published', 'updatePublished')->name('customer_products.published');
        Route::post('/customer_products/status', 'updateStatus')->name('customer_products.update.status');
        Route::get('/customer_products/destroy/{id}', 'destroy')->name('customer_products.destroy');
    });

    // Product Review
    Route::post('/product-review-modal', [ReviewController::class, 'product_review_modal'])->name('product_review_modal');
});


Route::get('translation-check/{check}', [LanguageController::class, 'get_translation']);


Route::group(['middleware' => ['auth']], function () {

    Route::get('invoice/{order_id}', [InvoiceController::class, 'invoice_download'])->name('invoice.download');

    // Reviews
    Route::resource('/reviews', ReviewController::class);

    // Product Conversation
    Route::resource('conversations', ConversationController::class);
    Route::controller(ConversationController::class)->group(function () {
        Route::get('/conversations/destroy/{id}', 'destroy')->name('conversations.destroy');
        Route::post('conversations/refresh', 'refresh')->name('conversations.refresh');
    });

    // Product Query
    Route::resource('product-queries', ProductQueryController::class);

    Route::resource('messages', MessageController::class);

    //Address
    Route::resource('addresses', AddressController::class);
    Route::controller(AddressController::class)->group(function () {
        Route::post('/addresses/update/{id}', 'update')->name('addresses.update');
        Route::get('/addresses/destroy/{id}', 'destroy')->name('addresses.destroy');
        Route::get('/addresses/set-default/{id}', 'set_default')->name('addresses.set_default');
    });
});
       Route::post('/get-states', [AddressController::class, 'getStates'])->name('get-state');
Route::post('/get-cities', [AddressController::class, 'getCities'])->name('get-city');

Route::resource('shops', ShopController::class)->middleware('handle-demo-login');

Route::get('/instamojo/payment/pay-success', [InstamojoController::class, 'success'])->name('instamojo.success');

Route::post('rozer/payment/pay-success', [RazorpayController::class, 'payment'])->name('payment.rozer');

Route::get('/paystack/payment/callback', [PaystackController::class, 'handleGatewayCallback']);
Route::get('/paystack/new-callback', [PaystackController::class, 'paystackNewCallback']);

Route::controller(VoguepayController::class)->group(function () {
    Route::get('/vogue-pay', 'showForm');
    Route::get('/vogue-pay/success/{id}', 'paymentSuccess');
    Route::get('/vogue-pay/callback', 'handleCallback');
    Route::get('/vogue-pay/failure/{id}', 'paymentFailure');
});


//Iyzico
Route::any('/iyzico/payment/callback/{payment_type}/{amount?}/{payment_method?}/{combined_order_id?}/{customer_package_id?}/{seller_package_id?}', [IyzicoController::class, 'callback'])->name('iyzico.callback');

Route::get('/customer-products/admin', [IyzicoController::class, 'initPayment'])->name('profile.edit');

//payhere below
Route::controller(PayhereController::class)->group(function () {
    Route::get('/payhere/checkout/testing', 'checkout_testing')->name('payhere.checkout.testing');
    Route::get('/payhere/wallet/testing', 'wallet_testing')->name('payhere.checkout.testing');
    Route::get('/payhere/customer_package/testing', 'customer_package_testing')->name('payhere.customer_package.testing');

    Route::any('/payhere/checkout/notify', 'checkout_notify')->name('payhere.checkout.notify');
    Route::any('/payhere/checkout/return', 'checkout_return')->name('payhere.checkout.return');
    Route::any('/payhere/checkout/cancel', 'chekout_cancel')->name('payhere.checkout.cancel');

    Route::any('/payhere/wallet/notify', 'wallet_notify')->name('payhere.wallet.notify');
    Route::any('/payhere/wallet/return', 'wallet_return')->name('payhere.wallet.return');
    Route::any('/payhere/wallet/cancel', 'wallet_cancel')->name('payhere.wallet.cancel');

    Route::any('/payhere/seller_package_payment/notify', 'seller_package_notify')->name('payhere.seller_package_payment.notify');
    Route::any('/payhere/seller_package_payment/return', 'seller_package_payment_return')->name('payhere.seller_package_payment.return');
    Route::any('/payhere/seller_package_payment/cancel', 'seller_package_payment_cancel')->name('payhere.seller_package_payment.cancel');

    Route::any('/payhere/customer_package_payment/notify', 'customer_package_notify')->name('payhere.customer_package_payment.notify');
    Route::any('/payhere/customer_package_payment/return', 'customer_package_return')->name('payhere.customer_package_payment.return');
    Route::any('/payhere/customer_package_payment/cancel', 'customer_package_cancel')->name('payhere.customer_package_payment.cancel');
});


// phonepe
Route::controller(PhonepeController::class)->group(function () {
    Route::any('/phonepe/pay', 'pay')->name('phonepe.pay');
    Route::any('/phonepe/redirecturl', 'phonepe_redirecturl')->name('phonepe.redirecturl');
    Route::any('/phonepe/callbackUrl', 'phonepe_callbackUrl')->name('phonepe.callbackUrl');
});


//N-genius
Route::controller(NgeniusController::class)->group(function () {
    Route::any('ngenius/cart_payment_callback', 'cart_payment_callback')->name('ngenius.cart_payment_callback');
    Route::any('ngenius/wallet_payment_callback', 'wallet_payment_callback')->name('ngenius.wallet_payment_callback');
    Route::any('ngenius/customer_package_payment_callback', 'customer_package_payment_callback')->name('ngenius.customer_package_payment_callback');
    Route::any('ngenius/seller_package_payment_callback', 'seller_package_payment_callback')->name('ngenius.seller_package_payment_callback');
});

Route::controller(BkashController::class)->group(function () {
    Route::get('/bkash/create-payment', 'create_payment')->name('bkash.create_payment');
    Route::get('/bkash/callback', 'callback')->name('bkash.callback');
    Route::get('/bkash/success', 'success')->name('bkash.success');
});

Route::get('/checkout-payment-detail', [StripeController::class, 'checkout_payment_detail']);

//Nagad
Route::get('/nagad/callback', [NagadController::class, 'verify'])->name('nagad.callback');

//aamarpay
Route::controller(AamarpayController::class)->group(function () {
    Route::post('/aamarpay/success', 'success')->name('aamarpay.success');
    Route::post('/aamarpay/fail', 'fail')->name('aamarpay.fail');
});

//Authorize-Net-Payment
Route::post('/dopay/online', [AuthorizenetController::class, 'handleonlinepay'])->name('dopay.online');
Route::get('/authorizenet/cardtype', [AuthorizenetController::class, 'cardType'])->name('authorizenet.cardtype');

//payku
Route::get('/payku/callback/{id}', [PaykuController::class, 'callback'])->name('payku.result');

//Blog Section
Route::controller(BlogController::class)->group(function () {
    Route::get('/blog', 'all_blog')->name('blog');
    Route::get('/blog/{slug}', 'blog_details')->name('blog.details');
});

Route::controller(PageController::class)->group(function () {
    //mobile app balnk page for webview
    Route::get('/mobile-page/{slug}', 'mobile_custom_page')->name('mobile.custom-pages');

    //Custom page
    Route::get('/{slug}', 'show_custom_page')->name('custom-pages.show_custom_page');
});


Route::post('/coupon/apply', [\App\Http\Controllers\CouponController::class, 'apply_coupon_code'])->name('coupon.apply');
Route::post('/coupon/remove', [\App\Http\Controllers\CouponController::class, 'remove_coupon_code'])->name('coupon.remove');



Route::get('/new-checkout', [NewCheckoutController::class, 'index'])->name('newcheckout.index');
Route::get('/new-checkout/store-shipping-info', [NewCheckoutController::class, 'store_shipping_info'])->name('newcheckout.store_shipping_info');
Route::post('/new-checkout/store', [NewCheckoutController::class, 'store'])->name('newcheckout.submit');
Route::get('/new-checkout/cart-products', [NewCheckoutController::class, 'getCartProducts'])->name('newcheckout.cart_products');
Route::get('/new-checkout/confirmation/{id}', [NewCheckoutController::class, 'confirmation'])->name('newcheckout.confirmation');
Route::post('/new-checkout/calculate-shipping', [\App\Http\Controllers\NewCheckoutController::class, 'calculate_shipping_new'])
     ->name('newcheckout.calculate_shipping_new');
     Route::post('/new-checkout/apply-coupon',   [NewCheckoutController::class,'applyCoupon'])->name('newcheckout.apply_coupon');
Route::post('/new-checkout/remove-coupon',  [NewCheckoutController::class,'removeCoupon'])->name('newcheckout.remove_coupon');
Route::post('/new-checkout/shipping-calc',  [NewCheckoutController::class,'calculateShipping'])->name('newcheckout.calculate_shipping');





