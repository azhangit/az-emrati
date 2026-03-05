<?php

use Carbon\Carbon;
use App\Models\Tax;
use App\Models\Cart;
use App\Models\City;
use App\Models\Shop;
use App\Models\User;
use App\Models\Addon;
use App\Models\Brand;
use App\Models\Color;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Seller;
use App\Models\Upload;
use App\Models\Wallet;
use App\Models\Address;
use App\Models\Carrier;
use App\Models\Country;
use App\Models\Product;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Language;
use App\Models\Wishlist;
use App\Models\Attribute;
use App\Models\ClubPoint;
use App\Models\FlashDeal;
use App\Models\CouponUsage;
use App\Models\DeliveryBoy;
use App\Models\OrderDetail;
use App\Models\PickupPoint;
use App\Models\Translation;
use App\Models\BlogCategory;
use App\Models\Conversation;
use App\Models\FollowSeller;
use App\Models\ProductStock;
use App\Models\CombinedOrder;
use App\Models\SellerPackage;
use App\Models\AffiliateConfig;
use App\Models\AffiliateOption;
use App\Models\BusinessSetting;
use App\Models\CustomerPackage;
use App\Models\CustomerProduct;
use App\Utility\SendSMSUtility;
use App\Utility\CategoryUtility;
use App\Models\AuctionProductBid;
use App\Models\ManualPaymentMethod;
use App\Models\SellerPackagePayment;
use App\Utility\NotificationUtility;
use App\Http\Resources\V2\CarrierCollection;
use App\Http\Controllers\AffiliateController;
use App\Http\Controllers\ClubPointController;
use App\Http\Controllers\CommissionController;
use AizPackages\ColorCodeConverter\Services\ColorCodeConverter;
use App\Models\FlashDealProduct;
use App\Models\UserCoupon;

//sensSMS function for OTP
if (!function_exists('sendSMS')) {
    function sendSMS($to, $from, $text, $template_id)
    {
        return SendSMSUtility::sendSMS($to, $from, $text, $template_id);
    }
}

//highlights the selected navigation on admin panel
if (!function_exists('areActiveRoutes')) {
    function areActiveRoutes(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route && (url()->current() != url('/admin/website/custom-pages/edit/home'))) return $output;
        }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('areActiveRoutesHome')) {
    function areActiveRoutesHome(array $routes, $output = "active")
    {
        foreach ($routes as $route) {
            if (Route::currentRouteName() == $route) return $output;
        }
    }
}

//highlights the selected navigation on frontend
if (!function_exists('default_language')) {
    function default_language()
    {
        return env("DEFAULT_LANGUAGE");
    }
}

/**
 * Save JSON File
 * @return Response
 */
if (!function_exists('convert_to_usd')) {
    function convert_to_usd($amount)
    {
        $currency = Currency::find(get_setting('system_default_currency'));
        return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'USD')->first()->exchange_rate;
    }
}

if (!function_exists('convert_to_kes')) {
    function convert_to_kes($amount)
    {
        $currency = Currency::find(get_setting('system_default_currency'));
        return (floatval($amount) / floatval($currency->exchange_rate)) * Currency::where('code', 'KES')->first()->exchange_rate;
    }
}

// get all active countries
if (!function_exists('get_active_countries')) {
    function get_active_countries()
    {
        $country_query = Country::query();
        return $country_query->isEnabled()->get();
    }
}

//filter products based on vendor activation system
if (!function_exists('filter_products')) {
    function filter_products($products)
    {

        $products = $products->where('published', '1')->where('auction_product', 0)->where('approved', '1');

        if (!addon_is_activated('wholesale')) {
            $products = $products->where('wholesale_product', 0);
        }
        $verified_sellers = verified_sellers_id();
        // $unbanned_sellers_id = unbanned_sellers_id();
        if (get_setting('vendor_system_activation') == 1) {
            // return $products->where(function ($p) use ($verified_sellers, $unbanned_sellers_id) {
            //     $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers, $unbanned_sellers_id) {
            //         $q->whereIn('user_id', $verified_sellers)->whereIn('user_id', $unbanned_sellers_id);
            //     });
            // });
            return $products->where(function ($p) use ($verified_sellers) {
                $p->where('added_by', 'admin')->orWhere(function ($q) use ($verified_sellers) {
                    $q->whereIn('user_id', $verified_sellers);
                });
            });
        } else {
            return $products->where('added_by', 'admin');
        }
    }
}

//cache products based on category
if (!function_exists('get_cached_products')) {
    function get_cached_products($category_id = null)
    {
        return Cache::remember('products-category-' . $category_id, 86400, function () use ($category_id) {
            return filter_products(Product::where('category_id', $category_id))->latest()->take(5)->get();
        });
    }
}

if (!function_exists('verified_sellers_id')) {
    function verified_sellers_id()
    {
        return Cache::rememberForever('verified_sellers_id', function () {
            return Shop::where('verification_status', 1)->pluck('user_id')->toArray();
        });
    }
}

// if (!function_exists('unbanned_sellers_id')) {
//     function unbanned_sellers_id()
//     {
//         return Cache::rememberForever('unbanned_sellers_id', function () {
//             return App\Models\User::where('user_type', 'seller')->where('banned', 0)->pluck('id')->toArray();
//         });
//     }
// }

if (!function_exists('get_system_default_currency')) {
    function get_system_default_currency()
    {
        return Cache::remember('system_default_currency', 86400, function () {
            return Currency::findOrFail(get_setting('system_default_currency'));
        });
    }
}

//converts currency to home default currency
if (!function_exists('convert_price')) {
    function convert_price($price)
    {
        if (Session::has('currency_code') && (Session::get('currency_code') != get_system_default_currency()->code)) {
            $price = floatval($price) / floatval(get_system_default_currency()->exchange_rate);
            $price = floatval($price) * floatval(Session::get('currency_exchange_rate'));
        }

        if (
            request()->header('Currency-Code') &&
            request()->header('Currency-Code') != get_system_default_currency()->code
        ) {
            $price = floatval($price) / floatval(get_system_default_currency()->exchange_rate);
            $price = floatval($price) * floatval(request()->header('Currency-Exchange-Rate'));
        }
        return $price;
    }
}

//gets currency symbol
if (!function_exists('currency_symbol')) {
    function currency_symbol()
    {
        $symbol = null;

        if (Session::has('currency_symbol')) {
            $symbol = Session::get('currency_symbol');
        } elseif (request()->header('Currency-Code')) {
            $symbol = request()->header('Currency-Code');
        } else {
            $symbol = get_system_default_currency()->symbol;
        }

        // Replace AED only
        if ($symbol === 'AED' || $symbol === 'د.إ') {
            return '
<svg class="currency-icon" fill="currentColor" version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1280 1114" preserveAspectRatio="xMidYMid meet">
<path d="M0 0 C0.735 -0.002 1.47 -0.004 2.228 -0.005 C4.717 -0.01 7.206 -0.007 9.696 -0.005 C11.489 -0.007 13.282 -0.009 15.075 -0.012 C20.023 -0.018 24.971 -0.018 29.919 -0.017 C35.25 -0.017 40.581 -0.022 45.912 -0.027 C56.373 -0.035 66.835 -0.038 77.297 -0.038 C85.817 -0.039 94.338 -0.041 102.859 -0.044 C127.053 -0.053 151.248 -0.058 175.442 -0.057 C177.401 -0.057 177.401 -0.057 179.4 -0.057 C180.707 -0.057 182.015 -0.057 183.362 -0.057 C204.458 -0.056 225.555 -0.066 246.651 -0.08 C268.349 -0.094 290.046 -0.101 311.744 -0.1 C323.91 -0.1 336.076 -0.103 348.242 -0.113 C358.624 -0.123 369.007 -0.125 379.389 -0.118 C384.67 -0.115 389.95 -0.114 395.231 -0.123 C429.103 -0.176 462.911 0.572 496.601 4.379 C497.57 4.485 498.54 4.592 499.539 4.702 C580.268 13.623 657.313 34.718 729.976 71.129 C731.555 71.916 731.555 71.916 733.166 72.72 C762.389 87.371 789.941 106.143 814.976 127.129 C815.504 127.57 816.032 128.012 816.576 128.468 C834.023 143.103 851.303 158.67 865.976 176.129 C866.817 177.081 867.659 178.034 868.526 179.015 C877.271 188.946 885.162 199.458 892.976 210.129 C893.502 210.839 894.027 211.549 894.569 212.28 C931.297 261.943 957.311 321.871 971.532 381.914 C971.957 383.667 972.458 385.401 972.976 387.129 C975.584 388.433 977.575 388.324 980.499 388.41 C981.719 388.447 982.938 388.483 984.195 388.521 C985.58 388.557 986.965 388.593 988.351 388.629 C989.809 388.67 991.268 388.712 992.727 388.754 C1003.169 389.04 1013.612 389.203 1024.058 389.343 C1025.391 389.362 1026.725 389.381 1028.058 389.4 C1029.402 389.419 1030.746 389.438 1032.089 389.457 C1032.757 389.466 1033.425 389.476 1034.113 389.485 C1038.756 389.55 1043.398 389.6 1048.042 389.648 C1077.05 390.004 1100.628 393.948 1121.976 415.129 C1122.821 415.933 1123.667 416.737 1124.538 417.566 C1125.342 418.412 1126.147 419.257 1126.976 420.129 C1127.629 420.787 1128.283 421.446 1128.956 422.125 C1148.18 442.275 1158.227 471.868 1158.073 499.437 C1158.072 500.404 1158.07 501.371 1158.069 502.367 C1158.063 505.433 1158.051 508.5 1158.038 511.566 C1158.033 513.654 1158.029 515.742 1158.024 517.83 C1158.013 522.929 1157.996 528.029 1157.976 533.129 C1156.88 532.19 1155.784 531.251 1154.688 530.312 C1153.773 529.527 1153.773 529.527 1152.839 528.727 C1151.608 527.672 1150.379 526.614 1149.151 525.556 C1142.487 519.823 1135.782 514.608 1127.913 510.629 C1127.326 510.327 1126.739 510.026 1126.134 509.715 C1124.112 508.75 1122.065 507.94 1119.976 507.129 C1119.135 506.792 1118.295 506.454 1117.429 506.107 C1104.998 501.841 1092.223 502.524 1079.233 502.519 C1076.653 502.504 1074.072 502.488 1071.491 502.469 C1065.428 502.43 1059.366 502.408 1053.303 502.395 C1046.391 502.379 1039.479 502.341 1032.567 502.301 C1018.37 502.219 1004.173 502.164 989.976 502.129 C990.138 503.414 990.3 504.7 990.467 506.025 C991.013 510.962 991.117 515.829 991.105 520.792 C991.107 521.712 991.108 522.631 991.11 523.579 C991.115 526.59 991.112 529.602 991.108 532.613 C991.109 534.718 991.11 536.823 991.111 538.928 C991.113 543.326 991.111 547.724 991.106 552.121 C991.1 557.756 991.104 563.39 991.11 569.025 C991.113 573.366 991.112 577.707 991.109 582.047 C991.109 584.125 991.11 586.203 991.112 588.281 C991.114 591.182 991.111 594.083 991.105 596.983 C991.108 598.267 991.108 598.267 991.111 599.578 C991.097 603.562 990.968 607.24 989.976 611.129 C990.728 611.123 991.48 611.118 992.256 611.113 C1000.015 611.067 1007.773 611.07 1015.532 611.105 C1020.51 611.126 1025.487 611.125 1030.465 611.098 C1036.266 611.068 1042.065 611.082 1047.865 611.117 C1050.16 611.125 1052.456 611.119 1054.751 611.098 C1070.515 610.966 1082.827 613.058 1096.976 620.129 C1098.126 620.689 1099.276 621.25 1100.462 621.827 C1106.527 624.89 1111.802 628.747 1116.976 633.129 C1117.789 633.808 1118.602 634.487 1119.44 635.187 C1137.246 650.579 1147.796 670.614 1154.351 692.879 C1154.727 694.136 1154.727 694.136 1155.111 695.42 C1158.714 708.305 1158.104 721.621 1158.038 734.879 C1158.033 736.655 1158.028 738.431 1158.024 740.207 C1158.014 744.514 1157.996 748.821 1157.976 753.129 C1153.099 752.377 1150.757 750.415 1147.351 747.004 C1122.562 723.339 1093.086 724.961 1061.062 725.031 C1058.509 725.033 1055.956 725.034 1053.404 725.035 C1047.423 725.039 1041.441 725.047 1035.46 725.057 C1028.638 725.068 1021.815 725.073 1014.993 725.078 C1000.987 725.089 986.981 725.106 972.976 725.129 C972.842 725.949 972.709 726.769 972.572 727.614 C972.395 728.692 972.218 729.77 972.036 730.88 C971.862 731.948 971.688 733.015 971.508 734.115 C970.872 737.714 970.005 741.105 968.979 744.613 C968.791 745.264 968.602 745.914 968.407 746.584 C967.793 748.685 967.167 750.782 966.538 752.879 C966.32 753.611 966.102 754.344 965.877 755.098 C958.184 780.886 949.419 805.731 937.976 830.129 C937.048 832.145 936.121 834.162 935.194 836.179 C919.466 870.089 898.956 901.498 874.976 930.129 C874.482 930.721 873.989 931.313 873.481 931.924 C861.35 946.457 848.534 960.886 834.151 973.23 C831.669 975.396 829.265 977.637 826.851 979.879 C821.176 985.052 815.221 989.802 809.128 994.471 C807.456 995.759 805.792 997.056 804.131 998.358 C796.254 1004.515 788.173 1010.279 779.851 1015.816 C779.206 1016.246 778.562 1016.675 777.898 1017.117 C764.666 1025.903 751.059 1033.772 736.976 1041.129 C736.079 1041.599 736.079 1041.599 735.165 1042.079 C717.183 1051.506 698.984 1059.935 679.976 1067.129 C678.85 1067.555 677.724 1067.981 676.564 1068.42 C637.158 1083.187 596.405 1093.876 554.976 1101.129 C553.767 1101.345 552.559 1101.561 551.314 1101.783 C533.777 1104.879 516.184 1106.987 498.476 1108.816 C497.003 1108.97 497.003 1108.97 495.502 1109.127 C467.379 1112.042 439.34 1113.432 411.081 1113.533 C407.043 1113.55 403.006 1113.577 398.968 1113.602 C390.938 1113.649 382.908 1113.686 374.877 1113.718 C368.249 1113.745 361.621 1113.777 354.992 1113.81 C270.041 1114.238 185.089 1114.211 -10.024 1114.129 C-8.4 1110.726 -6.49 1108.183 -4.024 1105.316 C22.682 1072.85 33.516 1031.773 39.788 990.941 C39.894 990.26 39.999 989.578 40.108 988.876 C40.412 986.884 40.706 984.892 40.995 982.898 C41.244 981.19 41.244 981.19 41.498 979.448 C43.198 965.292 43.143 951.087 43.181 936.853 C43.191 934.536 43.201 932.219 43.211 929.901 C43.238 923.652 43.259 917.403 43.279 911.154 C43.301 904.608 43.328 898.062 43.355 891.516 C43.399 880.542 43.44 869.568 43.478 858.594 C43.518 847.299 43.559 836.003 43.603 824.707 C43.607 823.66 43.607 823.66 43.612 822.592 C43.625 819.09 43.639 815.588 43.653 812.086 C43.766 783.1 43.873 754.114 43.976 725.129 C43.009 725.129 42.042 725.13 41.046 725.131 C37.402 725.134 33.758 725.135 30.113 725.137 C28.547 725.138 26.98 725.139 25.414 725.14 C13.648 725.149 1.888 725.09 -9.875 724.811 C-12.483 724.752 -15.091 724.706 -17.7 724.66 C-42.038 724.154 -64.702 717.851 -83.024 701.129 C-83.5 700.696 -83.975 700.263 -84.465 699.817 C-106.22 679.745 -120.468 649.775 -122.024 620.129 C-122.178 613.234 -122.119 606.337 -122.087 599.441 C-122.082 597.564 -122.077 595.687 -122.073 593.81 C-122.062 589.25 -122.045 584.689 -122.024 580.129 C-117.567 583.387 -113.315 586.661 -109.337 590.504 C-94.069 604.333 -76.573 611.232 -56.2 610.996 C-54.522 611.003 -52.844 611.013 -51.165 611.026 C-46.772 611.052 -42.38 611.038 -37.987 611.015 C-32.774 610.993 -27.561 611.018 -22.348 611.037 C-18.262 611.051 -14.177 611.059 -10.091 611.042 C7.956 611.011 25.906 611.653 43.976 612.129 C43.976 575.829 43.976 539.529 43.976 502.129 C20.208 501.84 20.208 501.84 -3.56 501.561 C-8.199 501.502 -8.199 501.502 -10.383 501.473 C-11.881 501.454 -13.379 501.436 -14.876 501.42 C-42.143 501.125 -42.143 501.125 -51.462 497.566 C-52.175 497.301 -52.888 497.037 -53.623 496.764 C-80.103 486.55 -100.533 464.843 -112.024 439.129 C-120.032 418.946 -122.193 399.966 -122.087 378.441 C-122.082 376.661 -122.077 374.88 -122.073 373.099 C-122.062 368.776 -122.045 364.452 -122.024 360.129 C-121.034 359.799 -120.044 359.469 -119.024 359.129 C-118.308 359.856 -117.591 360.583 -116.853 361.332 C-99.01 378.977 -78.531 388.187 -53.316 388.334 C-52.261 388.344 -51.207 388.354 -50.121 388.364 C-46.652 388.395 -43.184 388.42 -39.716 388.445 C-37.304 388.466 -34.893 388.487 -32.482 388.508 C-26.148 388.563 -19.813 388.613 -13.479 388.661 C-7.01 388.711 -0.542 388.767 5.927 388.822 C18.61 388.929 31.293 389.031 43.976 389.129 C43.891 360.406 43.796 331.684 43.688 302.961 C43.678 300.296 43.668 297.63 43.658 294.964 C43.656 294.301 43.653 293.638 43.651 292.956 C43.614 282.952 43.582 272.948 43.554 262.944 C43.521 251.305 43.481 239.666 43.433 228.026 C43.408 221.875 43.386 215.724 43.372 209.573 C43.452 164.17 43.452 164.17 38.288 119.129 C38.16 118.385 38.032 117.641 37.9 116.874 C31.136 77.694 20.058 38.632 -5.622 7.467 C-9.024 3.32 -9.024 3.32 -9.024 1.129 C-6.037 -0.365 -3.334 0.002 0 0 Z M212.976 56.129 C211.699 62.015 211.837 67.843 211.849 73.834 C211.847 75.021 211.845 76.209 211.844 77.432 C211.841 80.72 211.842 84.008 211.845 87.296 C211.847 90.847 211.844 94.397 211.841 97.948 C211.836 104.908 211.837 111.869 211.84 118.829 C211.843 124.501 211.843 130.173 211.842 135.846 C211.842 136.654 211.841 137.463 211.841 138.296 C211.841 139.939 211.841 141.583 211.84 143.226 C211.837 158.65 211.841 174.075 211.846 189.499 C211.851 202.668 211.85 215.837 211.845 229.007 C211.84 244.337 211.838 259.667 211.841 274.997 C211.841 276.642 211.841 278.287 211.842 279.933 C211.842 280.742 211.842 281.551 211.842 282.384 C211.843 288.022 211.842 293.659 211.839 299.296 C211.836 306.209 211.837 313.121 211.843 320.034 C211.845 323.546 211.846 327.057 211.843 330.569 C211.842 331.864 211.841 333.159 211.84 334.493 C211.839 335.607 211.837 336.72 211.836 337.868 C211.887 355.002 212.439 371.97 212.976 389.129 C400.746 389.129 588.516 389.129 781.976 389.129 C782.039 383.357 782.039 383.357 781.276 377.923 C781.122 377.172 780.967 376.421 780.808 375.647 C780.636 374.853 780.465 374.06 780.288 373.242 C780.021 371.973 780.021 371.973 779.749 370.678 C779.168 367.93 778.573 365.185 777.976 362.441 C777.669 361.026 777.669 361.026 777.357 359.582 C770.171 326.51 761.108 292.273 746.225 261.712 C744.958 259.093 743.722 256.461 742.49 253.827 C731.873 231.166 720.109 210.137 704.976 190.129 C704.497 189.492 704.019 188.855 703.526 188.199 C697.255 179.888 690.504 172.179 683.358 164.613 C681.307 162.411 679.334 160.142 677.351 157.879 C672.878 152.902 668.029 148.505 662.976 144.129 C661.937 143.205 660.899 142.28 659.862 141.355 C595.87 84.788 510.265 63.437 403.09 56.572 C402.349 56.551 401.608 56.53 400.844 56.509 C382.529 56.001 364.217 55.998 345.897 56.027 C341.198 56.034 336.499 56.034 331.8 56.035 C322.965 56.038 314.13 56.046 305.294 56.057 C295.209 56.068 285.124 56.073 275.039 56.078 C254.351 56.089 233.663 56.106 212.976 56.129 Z M299.851 501.629 C298.696 501.635 297.541 501.641 296.352 501.647 C268.559 501.791 240.767 501.948 212.976 502.129 C211.359 505.362 211.837 508.903 211.846 512.455 C211.844 513.317 211.843 514.18 211.841 515.068 C211.836 517.935 211.839 520.801 211.843 523.668 C211.842 525.667 211.841 527.666 211.84 529.665 C211.838 533.87 211.841 538.075 211.845 542.28 C211.851 547.628 211.848 552.976 211.842 558.323 C211.838 562.462 211.839 566.601 211.842 570.74 C211.842 572.708 211.842 574.677 211.839 576.645 C211.837 579.427 211.841 582.209 211.846 584.99 C211.844 585.781 211.842 586.571 211.84 587.385 C211.859 592.761 212.135 598.101 212.484 603.464 C212.531 604.203 212.578 604.942 212.626 605.703 C212.742 607.512 212.859 609.32 212.976 611.129 C265.762 611.258 318.549 611.386 371.336 611.511 C377.666 611.526 383.996 611.541 390.325 611.556 C392.214 611.56 392.214 611.56 394.142 611.565 C411.687 611.607 429.233 611.649 446.779 611.692 C477.08 611.767 507.381 611.84 537.683 611.91 C538.867 611.913 540.051 611.915 541.271 611.918 C625.506 612.113 709.74 612.199 793.976 612.129 C794.901 609.353 795.101 607.709 795.103 604.847 C795.105 603.973 795.108 603.1 795.111 602.2 C795.109 601.244 795.107 600.289 795.105 599.305 C795.107 598.296 795.108 597.288 795.11 596.249 C795.115 592.908 795.112 589.567 795.108 586.226 C795.109 583.908 795.11 581.591 795.111 579.273 C795.113 574.411 795.111 569.55 795.106 564.688 C795.1 558.453 795.104 552.217 795.11 545.981 C795.113 541.194 795.112 536.406 795.109 531.618 C795.109 529.319 795.11 527.02 795.112 524.72 C795.114 521.507 795.111 518.295 795.105 515.082 C795.107 514.129 795.109 513.175 795.111 512.193 C795.108 511.317 795.105 510.44 795.103 509.538 C795.102 508.778 795.102 508.019 795.101 507.237 C794.973 505.085 794.523 503.207 793.976 501.129 C739.249 501.098 684.523 501.078 629.796 501.067 C626.673 501.066 623.549 501.065 620.426 501.065 C481.873 501.033 481.873 501.033 299.851 501.629 Z M332.671 724.773 C331.257 724.777 331.257 724.777 329.816 724.781 C290.536 724.893 251.256 725.012 211.976 725.129 C211.976 834.359 211.976 943.589 211.976 1056.129 C260.733 1056.221 260.733 1056.221 310.476 1056.316 C320.678 1056.343 330.88 1056.371 341.392 1056.399 C350.666 1056.409 350.666 1056.409 359.941 1056.415 C364.021 1056.418 368.1 1056.429 372.18 1056.445 C396.634 1056.54 420.683 1056.15 444.976 1053.129 C445.964 1053.007 446.953 1052.885 447.971 1052.759 C453.405 1052.077 458.817 1051.304 464.226 1050.441 C465.019 1050.317 465.812 1050.193 466.63 1050.065 C538.056 1038.833 607.481 1014.01 661.976 965.129 C662.75 964.439 662.75 964.439 663.54 963.736 C673.971 954.421 684.08 944.855 692.78 933.879 C695.027 931.064 697.371 928.354 699.726 925.629 C739.06 878.427 761.381 818.041 775.476 759.004 C775.689 758.113 775.903 757.223 776.123 756.306 C779.884 740.862 779.884 740.862 781.976 725.129 C780.264 723.417 778.04 724.003 775.701 724.002 C774.541 723.999 773.381 723.996 772.186 723.993 C770.887 723.995 769.589 723.997 768.251 723.999 C766.86 723.998 765.468 723.996 764.077 723.994 C760.175 723.989 756.273 723.991 752.371 723.994 C748.93 723.996 745.489 723.995 742.048 723.993 C732.324 723.989 722.6 723.992 712.876 723.998 C705.516 724.003 698.156 724.005 690.796 724.006 C593.635 724.025 496.474 724.315 332.671 724.773 Z " ph transform="translate(122.02441692352295,-0.12863445281982422)"/>
</svg>';
        }

        return $symbol;
    }
}
//formats currency
if (!function_exists('format_price')) {
    function format_price($price, $isMinimize = false)
    {
        if (get_setting('decimal_separator') == 1) {
            $fomated_price = number_format($price, get_setting('no_of_decimals'));
        } else {
            $fomated_price = number_format($price, get_setting('no_of_decimals'), ',', '.');
        }


        // Minimize the price 
        if ($isMinimize) {
            $temp = number_format($price / 1000000000, get_setting('no_of_decimals'), ".", "");

            if ($temp >= 1) {
                $fomated_price = $temp . "B";
            } else {
                $temp = number_format($price / 1000000, get_setting('no_of_decimals'), ".", "");
                if ($temp >= 1) {
                    $fomated_price = $temp . "M";
                }
            }
        }

        if (get_setting('symbol_format') == 1) {
            return new \Illuminate\Support\HtmlString(currency_symbol() . $fomated_price);
        } else if (get_setting('symbol_format') == 3) {
            return new \Illuminate\Support\HtmlString(currency_symbol() . ' ' . $fomated_price);
        } else if (get_setting('symbol_format') == 4) {
            return new \Illuminate\Support\HtmlString($fomated_price . ' ' . currency_symbol());
        }
        return new \Illuminate\Support\HtmlString($fomated_price . currency_symbol());
    }
}

//formats price to home default price with convertion
if (!function_exists('single_price')) {
    function single_price($price)
    {
        return format_price(convert_price($price));
    }
}

if (!function_exists('discount_in_percentage')) {
    function discount_in_percentage($product)
    {
        $base = home_base_price($product, false);
        $reduced = home_discounted_base_price($product, false);
        $discount = $base - $reduced;
        $dp = ($discount * 100) / ($base > 0 ? $base : 1);
        return round($dp);
    }
}

//Shows Price on page based on carts
if (!function_exists('cart_product_price')) {
    function cart_product_price($cart_product, $product, $formatted = true, $tax = true)
    {
        if ($product->auction_product == 0) {
            $str = '';
            if ($cart_product['variation'] != null) {
                $str = $cart_product['variation'];
            }
            $price = 0;
            $product_stock = $product->stocks->where('variant', $str)->first();
            if ($product_stock) {
                $price = $product_stock->price;
            }

            if ($product->wholesale_product) {
                $wholesalePrice = $product_stock->wholesalePrices->where('min_qty', '<=', $cart_product['quantity'])->where('max_qty', '>=', $cart_product['quantity'])->first();
                if ($wholesalePrice) {
                    $price = $wholesalePrice->price;
                }
            }

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $price -= ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $price -= $product->discount;
                }
            }
        } else {
            $price = $product->bids->max('amount');
        }

        //calculation of taxes 
        if ($tax) {
            $taxAmount = 0;
            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $taxAmount += ($price * $product_tax->tax) / 100;
                } elseif ($product_tax->tax_type == 'amount') {
                    $taxAmount += $product_tax->tax;
                }
            }
            $price += $taxAmount;
        }

        if ($formatted) {
            return format_price(convert_price($price));
        } else {
            return $price;
        }
    }
}

if (!function_exists('cart_product_tax')) {
    function cart_product_tax($cart_product, $product, $formatted = true)
    {
        // Return 0 if product is null
        if (!$product) {
            return $formatted ? format_price(0) : 0;
        }

        $str = '';
        
        // Handle both array and object access
        $variation = is_object($cart_product) ? ($cart_product->variation ?? null) : ($cart_product['variation'] ?? null);
        if ($variation != null) {
            $str = $variation;
        }

        // Try to fetch stock
        $product_stock = null;
        if ($product->stocks) {
            $product_stock = $product->stocks->where('variant', $str)->first();
        }

        // ✅ Fallback agar stock null ho
        if ($product_stock) {
            $price = $product_stock->price;
        } else {
            $price = $product->unit_price ?? 0; // fallback to product base price
        }

        // Ab yahan tax calculate karo
        $tax = 0;
        if ($product->taxes) {
            foreach ($product->taxes as $product_tax) {
                if ($product_tax->tax_type == 'percent') {
                    $tax += ($price * $product_tax->tax) / 100;
                } else {
                    $tax += $product_tax->tax;
                }
            }
        }

        return $formatted ? format_price($tax) : $tax;
    }
}


if (!function_exists('cart_product_discount')) {
    function cart_product_discount($cart_product, $product, $formatted = false)
    {
        $str = '';
        if ($cart_product['variation'] != null) {
            $str = $cart_product['variation'];
        }
        $product_stock = $product->stocks->where('variant', $str)->first();
        $price = $product_stock->price;

        //discount calculation
        $discount_applicable = false;
        $discount = 0;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $discount = ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $discount = $product->discount;
            }
        }

        if ($formatted) {
            return format_price(convert_price($discount));
        } else {
            return $discount;
        }
    }
}

// all discount
if (!function_exists('carts_product_discount')) {
    function carts_product_discount($cart_products, $formatted = false)
    {
        $discount = 0;
        foreach ($cart_products as $key => $cart_product) {
            $str = '';
            $product = \App\Models\Product::find($cart_product['product_id']);
            if ($cart_product['variation'] != null) {
                $str = $cart_product['variation'];
            }
            $product_stock = $product->stocks->where('variant', $str)->first();
            $price = $product_stock->price;

            //discount calculation
            $discount_applicable = false;

            if ($product->discount_start_date == null) {
                $discount_applicable = true;
            } elseif (
                strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
            ) {
                $discount_applicable = true;
            }

            if ($discount_applicable) {
                if ($product->discount_type == 'percent') {
                    $discount += ($price * $product->discount) / 100;
                } elseif ($product->discount_type == 'amount') {
                    $discount += $product->discount;
                }
            }
        }

        if ($formatted) {
            return format_price(convert_price($discount));
        } else {
            return $discount;
        }
    }
}

// carts coupon discount
if (!function_exists('carts_coupon_discount')) {
    function carts_coupon_discount($code, $formatted = false)
    {
        $coupon = Coupon::where('code', $code)->first();
        $coupon_discount = 0;
        if ($coupon != null) {
            if (strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);
                    $carts = Cart::where('user_id', Auth::user()->id)
                        ->where('owner_id', $coupon->user_id)
                        ->get();
                    if ($coupon->type == 'cart_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem['quantity'];
                            $tax += cart_product_tax($cartItem, $product, false) * $cartItem['quantity'];
                            $shipping += $cartItem['shipping_cost'];
                        }
                        $sum = $subtotal + $tax + $shipping;
                        if ($sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                        }
                    } elseif ($coupon->type == 'product_base') {
                        foreach ($carts as $key => $cartItem) {
                            $product = Product::find($cartItem['product_id']);
                            foreach ($coupon_details as $key => $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem['product_id']) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem['quantity'];
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                                    }
                                }
                            }
                        }
                    }
                }
            }
            if ($coupon_discount > 0) {
                Cart::where('user_id', Auth::user()->id)
                    ->where('owner_id', $coupon->user_id)
                    ->update(
                        [
                            'discount' => $coupon_discount / count($carts),
                        ]
                    );
            } else {
                Cart::where('user_id', Auth::user()->id)
                    ->where('owner_id', $coupon->user_id)
                    ->update(
                        [
                            'discount' => 0,
                            'coupon_code' => null,
                        ]
                    );
            }
        }
        if ($formatted) {
            return format_price(convert_price($coupon_discount));
        } else {
            return $coupon_discount;
        }
    }
}


//Shows Price on page based on low to high
if (!function_exists('home_price')) {
    function home_price($product, $formatted = true)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        if ($formatted) {
            if ($lowest_price == $highest_price) {
                return format_price(convert_price($lowest_price));
            } else {
                return new \Illuminate\Support\HtmlString(format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price)));
            }
        } else {
            return $lowest_price . ' - ' . $highest_price;
        }
    }
}

//Shows Price on page based on low to high with discount
if (!function_exists('home_discounted_price')) {
    function home_discounted_price($product, $formatted = true)
    {
        $lowest_price = $product->unit_price;
        $highest_price = $product->unit_price;

        if ($product->variant_product) {
            foreach ($product->stocks as $key => $stock) {
                if ($lowest_price > $stock->price) {
                    $lowest_price = $stock->price;
                }
                if ($highest_price < $stock->price) {
                    $highest_price = $stock->price;
                }
            }
        }

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $lowest_price -= ($lowest_price * $product->discount) / 100;
                $highest_price -= ($highest_price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $lowest_price -= $product->discount;
                $highest_price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $lowest_price += ($lowest_price * $product_tax->tax) / 100;
                $highest_price += ($highest_price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $lowest_price += $product_tax->tax;
                $highest_price += $product_tax->tax;
            }
        }

        if ($formatted) {
            if ($lowest_price == $highest_price) {
                return format_price(convert_price($lowest_price));
            } else {
                return new \Illuminate\Support\HtmlString(format_price(convert_price($lowest_price)) . ' - ' . format_price(convert_price($highest_price)));
            }
        } else {
            return $lowest_price . ' - ' . $highest_price;
        }
    }
}

//Shows Base Price
if (!function_exists('home_base_price_by_stock_id')) {
    function home_base_price_by_stock_id($id)
    {
        $product_stock = ProductStock::findOrFail($id);
        $price = $product_stock->price;
        $tax = 0;

        foreach ($product_stock->product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;
        return format_price(convert_price($price));
    }
}


if (!function_exists('home_base_price')) {
    function home_base_price($product, $formatted = true)
    {
        $price = $product->unit_price;
        $tax = 0;

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;
        return $formatted ? format_price(convert_price($price)) : convert_price($price);
    }
}

//Shows Base Price with discount
if (!function_exists('home_discounted_base_price_by_stock_id')) {
    function home_discounted_base_price_by_stock_id($id)
    {
        $product_stock = ProductStock::findOrFail($id);
        $product = $product_stock->product;
        $price = $product_stock->price;
        $tax = 0;

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;

        return format_price(convert_price($price));
    }
}


//Shows Base Price with discount
if (!function_exists('home_discounted_base_price')) {
    function home_discounted_base_price($product, $formatted = true)
    {
        $price = $product->unit_price;
        $tax = 0;

        $discount_applicable = false;

        if ($product->discount_start_date == null) {
            $discount_applicable = true;
        } elseif (
            strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
            strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date
        ) {
            $discount_applicable = true;
        }

        if ($discount_applicable) {
            if ($product->discount_type == 'percent') {
                $price -= ($price * $product->discount) / 100;
            } elseif ($product->discount_type == 'amount') {
                $price -= $product->discount;
            }
        }

        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }
        $price += $tax;


        return $formatted ? format_price(convert_price($price)) : convert_price($price);
    }
}

if (!function_exists('renderStarRating')) {
    function renderStarRating($rating, $maxRating = 5)
    {
        $fullStar = "<i class = 'las la-star active'></i>";
        $halfStar = "<i class = 'las la-star half'></i>";
        $emptyStar = "<i class = 'las la-star'></i>";
        $rating = $rating <= $maxRating ? $rating : $maxRating;

        $fullStarCount = (int)$rating;
        $halfStarCount = ceil($rating) - $fullStarCount;
        $emptyStarCount = $maxRating - $fullStarCount - $halfStarCount;

        $html = str_repeat($fullStar, $fullStarCount);
        $html .= str_repeat($halfStar, $halfStarCount);
        $html .= str_repeat($emptyStar, $emptyStarCount);
        echo $html;
    }
}

function translate($key, $lang = null, $addslashes = false)
{
    if ($lang == null) {
        $lang = App::getLocale();
    }

    $lang_key = preg_replace('/[^A-Za-z0-9\_]/', '', str_replace(' ', '_', strtolower($key)));

    $translations_en = Cache::rememberForever('translations-en', function () {
        return Translation::where('lang', 'en')->pluck('lang_value', 'lang_key')->toArray();
    });

    if (!isset($translations_en[$lang_key])) {
        $translation_def = new Translation;
        $translation_def->lang = 'en';
        $translation_def->lang_key = $lang_key;
        $translation_def->lang_value = str_replace(array("\r", "\n", "\r\n"), "", $key);
        $translation_def->save();
        Cache::forget('translations-en');
    }

    // return user session lang
    $translation_locale = Cache::rememberForever("translations-{$lang}", function () use ($lang) {
        return Translation::where('lang', $lang)->pluck('lang_value', 'lang_key')->toArray();
    });
    if (isset($translation_locale[$lang_key])) {
        return $addslashes ? addslashes(trim($translation_locale[$lang_key])) : trim($translation_locale[$lang_key]);
    }

    // return default lang if session lang not found
    $translations_default = Cache::rememberForever('translations-' . env('DEFAULT_LANGUAGE', 'en'), function () {
        return Translation::where('lang', env('DEFAULT_LANGUAGE', 'en'))->pluck('lang_value', 'lang_key')->toArray();
    });
    if (isset($translations_default[$lang_key])) {
        return $addslashes ? addslashes(trim($translations_default[$lang_key])) : trim($translations_default[$lang_key]);
    }

    // fallback to en lang
    if (!isset($translations_en[$lang_key])) {
        return trim($key);
    }
    return $addslashes ? addslashes(trim($translations_en[$lang_key])) : trim($translations_en[$lang_key]);
}

function remove_invalid_charcaters($str)
{
    $str = str_ireplace(array("\\"), '', $str);
    return str_ireplace(array('"'), '\"', $str);
}

if (!function_exists('translation_tables')) {
    function translation_tables($uniqueIdentifier)
    {
        $noTableAddons =  ['african_pg', 'paytm', 'pos_system'];
        if (!in_array($uniqueIdentifier, $noTableAddons)) {
            $addons = [];
            $addons['affiliate'] = ['affiliate_options', 'affiliate_configs', 'affiliate_users', 'affiliate_payments', 'affiliate_withdraw_requests', 'affiliate_logs', 'affiliate_stats'];
            $addons['auction'] = ['auction_product_bids'];
            $addons['club_point'] = ['club_points', 'club_point_details'];
            $addons['delivery_boy'] = ['delivery_boys', 'delivery_histories', 'delivery_boy_payments', 'delivery_boy_collections'];
            $addons['offline_payment'] = ['manual_payment_methods'];
            $addons['otp_system'] = ['otp_configurations', 'sms_templates'];
            $addons['refund_request'] = ['refund_requests'];
            $addons['seller_subscription'] = ['seller_packages', 'seller_package_translations', 'seller_package_payments'];
            $addons['wholesale'] = ['wholesale_prices'];

            foreach ($addons as $key => $addon_tables) {
                if ($key == $uniqueIdentifier) {
                    foreach ($addon_tables as $table) {
                        Schema::dropIfExists($table);
                    }
                }
            }
        }
    }
}

function getShippingCost($carts, $index, $carrier = '')
{
    $shipping_type = get_setting('shipping_type');
    $admin_products = array();
    $seller_products = array();
    $admin_product_total_weight = 0;
    $admin_product_total_price = 0;
    $seller_product_total_weight = array();
    $seller_product_total_price = array();

    $cartItem = $carts[$index];
    $productId = is_object($cartItem) ? $cartItem->product_id : ($cartItem['product_id'] ?? null);
    if (!$productId) {
        return 0;
    }
    $product = Product::find($productId);
    if (!$product) {
        return 0;
    }

    if ($product->digital == 1) {
        return 0;
    }

    foreach ($carts as $key => $cart_item) {
        $itemProductId = is_object($cart_item) ? $cart_item->product_id : ($cart_item['product_id'] ?? null);
        if (!$itemProductId) {
            continue;
        }
        $item_product = Product::find($itemProductId);
        if (!$item_product) {
            continue;
        }
        
        $itemQuantity = is_object($cart_item) ? $cart_item->quantity : ($cart_item['quantity'] ?? 1);
        
        if ($item_product->added_by == 'admin') {
            array_push($admin_products, $itemProductId);

            // For carrier wise shipping
            if ($shipping_type == 'carrier_wise_shipping') {
                $admin_product_total_weight += ($item_product->weight * $itemQuantity);
                $admin_product_total_price += (cart_product_price($cart_item, $item_product, false, false) * $itemQuantity);
            }
        } else {
            $product_ids = array();
            $weight = 0;
            $price = 0;
            if (isset($seller_products[$item_product->user_id])) {
                $product_ids = $seller_products[$item_product->user_id];

                // For carrier wise shipping
                if ($shipping_type == 'carrier_wise_shipping') {
                    $weight += $seller_product_total_weight[$item_product->user_id];
                    $price += $seller_product_total_price[$item_product->user_id];
                }
            }

            array_push($product_ids, $itemProductId);
            $seller_products[$item_product->user_id] = $product_ids;

            // For carrier wise shipping
            if ($shipping_type == 'carrier_wise_shipping') {
                $weight += ($item_product->weight * $itemQuantity);
                $seller_product_total_weight[$item_product->user_id] = $weight;

                $price += (cart_product_price($cart_item, $item_product, false, false) * $itemQuantity);
                $seller_product_total_price[$item_product->user_id] = $price;
            }
        }
    }

    if ($shipping_type == 'flat_rate') {
        return get_setting('flat_rate_shipping_cost') / max(1, count($carts));
    } elseif ($shipping_type == 'seller_wise_shipping') {
        if (!$product) {
            return 0;
        }
        if ($product->added_by == 'admin') {
            return get_setting('shipping_cost_admin') / max(1, count($admin_products));
        } else {
            $shop = Shop::where('user_id', $product->user_id)->first();
            if (!$shop || !isset($seller_products[$product->user_id])) {
                return 0;
            }
            return $shop->shipping_cost / max(1, count($seller_products[$product->user_id]));
        }
    } elseif ($shipping_type == 'area_wise_shipping') {
        if (!$product) {
            return 0;
        }
        
        $firstCart = $carts[0] ?? null;
        if (!$firstCart) {
            return 0;
        }

    if (Auth::check()) {
        // 🟢 Logged-in user ka flow
        $addressId = is_object($firstCart) ? $firstCart->address_id : ($firstCart['address_id'] ?? null);
        $shipping_info = $addressId ? Address::where('id', $addressId)->first() : null;
        $city_id = $shipping_info ? $shipping_info->city_id : null;
    } else {
        // 🔵 Guest user ka flow (shipping_address JSON se city_id uthana)
        $shippingAddress = is_object($firstCart) ? $firstCart->shipping_address : ($firstCart['shipping_address'] ?? null);
        $guest_shipping = $shippingAddress ? json_decode($shippingAddress, true) : [];
        if (empty($guest_shipping) && Session::has('guest_shipping_address')) {
             $guest_shipping = Session::get('guest_shipping_address');
        }
        $city_id = $guest_shipping['city_id'] ?? null;
    }

    $city = $city_id ? City::where('id', $city_id)->first() : null;

    if ($city != null) {
        if ($product->added_by == 'admin') {
            return $city->cost / max(1, count($admin_products)); // divide by 0 se bachne ke liye max(1,…)
        } else {
            if (!isset($seller_products[$product->user_id])) {
                return 0;
            }
            return $city->cost / max(1, count($seller_products[$product->user_id]));
        }
    }

    return 0; // fallback agar city na mile
}
elseif ($shipping_type == 'carrier_wise_shipping') { // carrier wise shipping
        if (!$product) {
            return 0;
        }
        
        $firstCart = $carts[0] ?? null;
        if (!$firstCart) {
            return 0;
        }
        
        $addressId = is_object($firstCart) ? $firstCart->address_id : ($firstCart['address_id'] ?? null);
        if (!$addressId) {
            return 0;
        }
        
        $address = Address::where('id', $addressId)->first();
        if (!$address || !$address->country) {
            return 0;
        }
        
        $user_zone = $address->country->zone_id;
        if ($carrier == null || $user_zone == 0) {
            return 0;
        }

        $carrier = Carrier::find($carrier);
        if (!$carrier || !$carrier->carrier_ranges->first()) {
            return 0;
        }
        
        $carrier_billing_type = $carrier->carrier_ranges->first()->billing_type;
        if ($product->added_by == 'admin') {
            $itemsWeightOrPrice = $carrier_billing_type == 'weight_based' ? $admin_product_total_weight : $admin_product_total_price;
        } else {
            if (!isset($seller_product_total_weight[$product->user_id]) && !isset($seller_product_total_price[$product->user_id])) {
                return 0;
            }
            $itemsWeightOrPrice = $carrier_billing_type == 'weight_based' ? ($seller_product_total_weight[$product->user_id] ?? 0) : ($seller_product_total_price[$product->user_id] ?? 0);
        }

        foreach ($carrier->carrier_ranges as $carrier_range) {
            if ($itemsWeightOrPrice >= $carrier_range->delimiter1 && $itemsWeightOrPrice < $carrier_range->delimiter2) {
                $carrier_range_price = $carrier_range->carrier_range_prices->where('zone_id', $user_zone)->first();
                if (!$carrier_range_price) {
                    continue;
                }
                $carrier_price = $carrier_range_price->price;
                if ($product->added_by == 'admin') {
                    return $carrier_price / max(1, count($admin_products));
                } else {
                    if (!isset($seller_products[$product->user_id])) {
                        return 0;
                    }
                    return $carrier_price / max(1, count($seller_products[$product->user_id]));
                }
            }
        }
        return 0;
    } else {
        if ($product->is_quantity_multiplied && ($shipping_type == 'product_wise_shipping')) {
            return  $product->shipping_cost * $cartItem['quantity'];
        }
        return $product->shipping_cost;
    }
}

//return carrier wise shipping cost against seller
if (!function_exists('carrier_base_price')) {
    function carrier_base_price($carts, $carrier_id, $owner_id)
    {
        $shipping = 0;
        foreach ($carts as $key => $cartItem) {
            if ($cartItem->owner_id == $owner_id) {
                $shipping_cost = getShippingCost($carts, $key, $carrier_id);
                $shipping += $shipping_cost;
            }
        }
        return $shipping;
    }
}

//return seller wise carrier list
if (!function_exists('seller_base_carrier_list')) {
    function seller_base_carrier_list($owner_id)
    {
        $carrier_list = array();
        $carts = Cart::where('user_id', auth()->user()->id)->get();
        if (count($carts) > 0) {
            $zone = $carts[0]['address'] ? Country::where('id', $carts[0]['address']['country_id'])->first()->zone_id : null;
            $carrier_query = Carrier::query();
            $carrier_query->whereIn('id', function ($query) use ($zone) {
                $query->select('carrier_id')->from('carrier_range_prices')
                    ->where('zone_id', $zone);
            })->orWhere('free_shipping', 1);
            $carrier_list = $carrier_query->active()->get();
        }
        return (new CarrierCollection($carrier_list))->extra($owner_id);
    }
}

function timezones()
{
    return array(
        '(GMT-12:00) International Date Line West' => 'Pacific/Kwajalein',
        '(GMT-11:00) Midway Island' => 'Pacific/Midway',
        '(GMT-11:00) Samoa' => 'Pacific/Apia',
        '(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
        '(GMT-09:00) Alaska' => 'America/Anchorage',
        '(GMT-08:00) Pacific Time (US & Canada)' => 'America/Los_Angeles',
        '(GMT-08:00) Tijuana' => 'America/Tijuana',
        '(GMT-07:00) Arizona' => 'America/Phoenix',
        '(GMT-07:00) Mountain Time (US & Canada)' => 'America/Denver',
        '(GMT-07:00) Chihuahua' => 'America/Chihuahua',
        '(GMT-07:00) La Paz' => 'America/Chihuahua',
        '(GMT-07:00) Mazatlan' => 'America/Mazatlan',
        '(GMT-06:00) Central Time (US & Canada)' => 'America/Chicago',
        '(GMT-06:00) Central America' => 'America/Managua',
        '(GMT-06:00) Guadalajara' => 'America/Mexico_City',
        '(GMT-06:00) Mexico City' => 'America/Mexico_City',
        '(GMT-06:00) Monterrey' => 'America/Monterrey',
        '(GMT-06:00) Saskatchewan' => 'America/Regina',
        '(GMT-05:00) Eastern Time (US & Canada)' => 'America/New_York',
        '(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
        '(GMT-05:00) Bogota' => 'America/Bogota',
        '(GMT-05:00) Lima' => 'America/Lima',
        '(GMT-05:00) Quito' => 'America/Bogota',
        '(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
        '(GMT-04:00) Caracas' => 'America/Caracas',
        '(GMT-04:00) La Paz' => 'America/La_Paz',
        '(GMT-04:00) Santiago' => 'America/Santiago',
        '(GMT-03:30) Newfoundland' => 'America/St_Johns',
        '(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
        '(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
        '(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
        '(GMT-03:00) Greenland' => 'America/Godthab',
        '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
        '(GMT-01:00) Azores' => 'Atlantic/Azores',
        '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
        '(GMT) Casablanca' => 'Africa/Casablanca',
        '(GMT) Dublin' => 'Europe/London',
        '(GMT) Edinburgh' => 'Europe/London',
        '(GMT) Lisbon' => 'Europe/Lisbon',
        '(GMT) London' => 'Europe/London',
        '(GMT) UTC' => 'UTC',
        '(GMT) Monrovia' => 'Africa/Monrovia',
        '(GMT+01:00) Amsterdam' => 'Europe/Amsterdam',
        '(GMT+01:00) Belgrade' => 'Europe/Belgrade',
        '(GMT+01:00) Berlin' => 'Europe/Berlin',
        '(GMT+01:00) Bern' => 'Europe/Berlin',
        '(GMT+01:00) Bratislava' => 'Europe/Bratislava',
        '(GMT+01:00) Brussels' => 'Europe/Brussels',
        '(GMT+01:00) Budapest' => 'Europe/Budapest',
        '(GMT+01:00) Copenhagen' => 'Europe/Copenhagen',
        '(GMT+01:00) Ljubljana' => 'Europe/Ljubljana',
        '(GMT+01:00) Madrid' => 'Europe/Madrid',
        '(GMT+01:00) Paris' => 'Europe/Paris',
        '(GMT+01:00) Prague' => 'Europe/Prague',
        '(GMT+01:00) Rome' => 'Europe/Rome',
        '(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
        '(GMT+01:00) Skopje' => 'Europe/Skopje',
        '(GMT+01:00) Stockholm' => 'Europe/Stockholm',
        '(GMT+01:00) Vienna' => 'Europe/Vienna',
        '(GMT+01:00) Warsaw' => 'Europe/Warsaw',
        '(GMT+01:00) West Central Africa' => 'Africa/Lagos',
        '(GMT+01:00) Zagreb' => 'Europe/Zagreb',
        '(GMT+02:00) Athens' => 'Europe/Athens',
        '(GMT+02:00) Bucharest' => 'Europe/Bucharest',
        '(GMT+02:00) Cairo' => 'Africa/Cairo',
        '(GMT+02:00) Harare' => 'Africa/Harare',
        '(GMT+02:00) Helsinki' => 'Europe/Helsinki',
        '(GMT+02:00) Istanbul' => 'Europe/Istanbul',
        '(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
        '(GMT+02:00) Kyev' => 'Europe/Kiev',
        '(GMT+02:00) Minsk' => 'Europe/Minsk',
        '(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
        '(GMT+02:00) Riga' => 'Europe/Riga',
        '(GMT+02:00) Sofia' => 'Europe/Sofia',
        '(GMT+02:00) Tallinn' => 'Europe/Tallinn',
        '(GMT+02:00) Vilnius' => 'Europe/Vilnius',
        '(GMT+03:00) Baghdad' => 'Asia/Baghdad',
        '(GMT+03:00) Kuwait' => 'Asia/Kuwait',
        '(GMT+03:00) Moscow' => 'Europe/Moscow',
        '(GMT+03:00) Nairobi' => 'Africa/Nairobi',
        '(GMT+03:00) Riyadh' => 'Asia/Riyadh',
        '(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
        '(GMT+03:00) Volgograd' => 'Europe/Volgograd',
        '(GMT+03:30) Tehran' => 'Asia/Tehran',
        '(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
        '(GMT+04:00) Baku' => 'Asia/Baku',
        '(GMT+04:00) Muscat' => 'Asia/Muscat',
        '(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
        '(GMT+04:00) Yerevan' => 'Asia/Yerevan',
        '(GMT+04:30) Kabul' => 'Asia/Kabul',
        '(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
        '(GMT+05:00) Islamabad' => 'Asia/Karachi',
        '(GMT+05:00) Karachi' => 'Asia/Karachi',
        '(GMT+05:00) Tashkent' => 'Asia/Tashkent',
        '(GMT+05:30) Chennai' => 'Asia/Kolkata',
        '(GMT+05:30) Kolkata' => 'Asia/Kolkata',
        '(GMT+05:30) Mumbai' => 'Asia/Kolkata',
        '(GMT+05:30) New Delhi' => 'Asia/Kolkata',
        '(GMT+05:45) Kathmandu' => 'Asia/Kathmandu',
        '(GMT+06:00) Almaty' => 'Asia/Almaty',
        '(GMT+06:00) Astana' => 'Asia/Dhaka',
        '(GMT+06:00) Dhaka' => 'Asia/Dhaka',
        '(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
        '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
        '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
        '(GMT+07:00) Bangkok' => 'Asia/Bangkok',
        '(GMT+07:00) Hanoi' => 'Asia/Bangkok',
        '(GMT+07:00) Jakarta' => 'Asia/Jakarta',
        '(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
        '(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
        '(GMT+08:00) Chongqing' => 'Asia/Chongqing',
        '(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
        '(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
        '(GMT+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
        '(GMT+08:00) Perth' => 'Australia/Perth',
        '(GMT+08:00) Singapore' => 'Asia/Singapore',
        '(GMT+08:00) Taipei' => 'Asia/Taipei',
        '(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
        '(GMT+08:00) Urumqi' => 'Asia/Urumqi',
        '(GMT+09:00) Osaka' => 'Asia/Tokyo',
        '(GMT+09:00) Sapporo' => 'Asia/Tokyo',
        '(GMT+09:00) Seoul' => 'Asia/Seoul',
        '(GMT+09:00) Tokyo' => 'Asia/Tokyo',
        '(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
        '(GMT+09:30) Adelaide' => 'Australia/Adelaide',
        '(GMT+09:30) Darwin' => 'Australia/Darwin',
        '(GMT+10:00) Brisbane' => 'Australia/Brisbane',
        '(GMT+10:00) Canberra' => 'Australia/Sydney',
        '(GMT+10:00) Guam' => 'Pacific/Guam',
        '(GMT+10:00) Hobart' => 'Australia/Hobart',
        '(GMT+10:00) Melbourne' => 'Australia/Melbourne',
        '(GMT+10:00) Port Moresby' => 'Pacific/Port_Moresby',
        '(GMT+10:00) Sydney' => 'Australia/Sydney',
        '(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
        '(GMT+11:00) Magadan' => 'Asia/Magadan',
        '(GMT+11:00) New Caledonia' => 'Asia/Magadan',
        '(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
        '(GMT+12:00) Auckland' => 'Pacific/Auckland',
        '(GMT+12:00) Fiji' => 'Pacific/Fiji',
        '(GMT+12:00) Kamchatka' => 'Asia/Kamchatka',
        '(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
        '(GMT+12:00) Wellington' => 'Pacific/Auckland',
        '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
    );
}

if (!function_exists('app_timezone')) {
    function app_timezone()
    {
        return config('app.timezone');
    }
}
// Return file uploaded via uploader
if (!function_exists('uploaded_asset')) {
    function uploaded_asset($id)
    {
        if (($asset = Upload::find($id)) != null) {
            return $asset->external_link == null ? my_asset($asset->file_name) : $asset->external_link;
        }
        return static_asset('assets/img/placeholder.jpg');
    }
}

// Generate an asset path for the application
if (!function_exists('my_asset')) {
    function my_asset($path, $secure = null)
    {
        // If using a cloud disk (s3 etc.)
        if (config('filesystems.default') != 'local') {
            return Storage::disk(config('filesystems.default'))->url($path);
        }

        $path = ltrim($path, '/');

        // Remove public/ if included
        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        return app('url')->asset($path, $secure);
    }
}

if (!function_exists('static_asset')) {
    function static_asset($path, $secure = null)
    {
        $path = ltrim($path, '/');

        if (str_starts_with($path, 'public/')) {
            $path = substr($path, 7);
        }

        return app('url')->asset($path, $secure);
    }
}


// if (!function_exists('isHttps')) {
//     function isHttps()
//     {
//         return !empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS']);
//     }
// }

if (!function_exists('getBaseURL')) {
    function getBaseURL()
    {
        $root = '//' . $_SERVER['HTTP_HOST'];
        $root .= str_replace(basename($_SERVER['SCRIPT_NAME']), '', $_SERVER['SCRIPT_NAME']);

        return $root;
    }
}


if (!function_exists('getFileBaseURL')) {
    function getFileBaseURL()
    {
        if (config('filesystems.default') != 'local') {
            return config('filesystems.disks.' . config('filesystems.default') . '.url') . '/';
        }

        return asset('/');
    }
}


if (!function_exists('isUnique')) {
    /**
     * Generate an asset path for the application.
     *
     * @param string $path
     * @param bool|null $secure
     * @return string
     */
    function isUnique($email)
    {
        $user = \App\Models\User::where('email', $email)->first();

        if ($user == null) {
            return '1'; // $user = null means we did not get any match with the email provided by the user inside the database
        } else {
            return '0';
        }
    }
}

if (!function_exists('get_setting')) {
    function get_setting($key, $default = null, $lang = false)
    {
        $settings = Cache::remember('business_settings', 86400, function () {
            return BusinessSetting::all();
        });

        if ($lang == false) {
            $setting = $settings->where('type', $key)->first();
        } else {
            $setting = $settings->where('type', $key)->where('lang', $lang)->first();
            $setting = !$setting ? $settings->where('type', $key)->first() : $setting;
        }
        return $setting == null ? $default : $setting->value;
    }
}

if (!function_exists('process_nav_url')) {
    /**
     * Process navigation URLs to use the configured base URL
     * Replaces hardcoded domains with NAV_BASE_URL from config
     */
    function process_nav_url($url)
    {
        if (empty($url)) {
            return $url;
        }
        
        $navBaseUrl = rtrim(config('app.nav_base_url', env('APP_URL', 'http://localhost:8000')), '/');
        
        // If URL is already absolute with a different domain, replace it
        if (preg_match('/^https?:\/\/(www\.)?emiraticoffee\.ae/', $url)) {
            $url = preg_replace('/^https?:\/\/(www\.)?emiraticoffee\.ae/', $navBaseUrl, $url);
        }
        // If URL is relative, make it absolute with base URL
        elseif (strpos($url, '/') === 0 && strpos($url, '//') !== 0) {
            $url = $navBaseUrl . $url;
        }
        // If URL doesn't start with http, assume it's relative
        elseif (!preg_match('/^https?:\/\//', $url)) {
            $url = $navBaseUrl . '/' . ltrim($url, '/');
        }
        
        return $url;
    }
}

if (!function_exists('nav_url')) {
    /**
     * Generate navigation URL using the configured base URL
     * Ensures no double slashes
     */
    function nav_url($path = '')
    {
        $navBaseUrl = rtrim(config('app.nav_base_url', env('APP_URL', 'http://localhost:8000')), '/');
        $path = ltrim($path, '/');
        return $navBaseUrl . ($path ? '/' . $path : '');
    }
}

function hex2rgba($color, $opacity = false)
{
    return (new ColorCodeConverter())->convertHexToRgba($color, $opacity);
}

if (!function_exists('isAdmin')) {
    function isAdmin()
    {
        if (Auth::check() && (Auth::user()->user_type == 'admin' || Auth::user()->user_type == 'staff')) {
            return true;
        }
        return false;
    }
}

if (!function_exists('isSeller')) {
    function isSeller()
    {
        if (Auth::check() && Auth::user()->user_type == 'seller') {
            return true;
        }
        return false;
    }
}

if (!function_exists('isCustomer')) {
    function isCustomer()
    {
        if (Auth::check() && Auth::user()->user_type == 'customer') {
            return true;
        }
        return false;
    }
}

if (!function_exists('formatBytes')) {
    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'KB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        // Uncomment one of the following alternatives
        $bytes /= pow(1024, $pow);
        // $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}

// duplicates m$ excel's ceiling function
if (!function_exists('ceiling')) {
    function ceiling($number, $significance = 1)
    {
        return (is_numeric($number) && is_numeric($significance)) ? (ceil($number / $significance) * $significance) : false;
    }
}

//for api
if (!function_exists('get_images_path')) {
    function get_images_path($given_ids, $with_trashed = false)
    {
        $paths = [];
        foreach (explode(',', $given_ids) as $id) {
            $paths[] = uploaded_asset($id);
        }

        return $paths;
    }
}

//for api
if (!function_exists('checkout_done')) {
    function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::find($combined_order_id);

        foreach ($combined_order->orders as $key => $order) {
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            try {
                NotificationUtility::sendOrderPlacedNotification($order);
                calculateCommissionAffilationClubPoint($order);
            } catch (\Exception $e) {
            }
        }
    }
}

// get user total ordered products
if (!function_exists('get_user_total_ordered_products')) {
    function get_user_total_ordered_products()
    {
        $orders_query = Order::query();
        $orders       = $orders_query->where('user_id', Auth::user()->id)->get();
        $total        = 0;
        foreach ($orders as $order) {
            $total += count($order->orderDetails);
        }
        return $total;
    }
}

//for api
if (!function_exists('wallet_payment_done')) {
    function wallet_payment_done($user_id, $amount, $payment_method, $payment_details)
    {
        $user = \App\Models\User::find($user_id);
        $user->balance = $user->balance + $amount;
        $user->save();

        $wallet = new Wallet;
        $wallet->user_id = $user->id;
        $wallet->amount = $amount;
        $wallet->payment_method = $payment_method;
        $wallet->payment_details = $payment_details;
        $wallet->save();
    }
}

if (!function_exists('purchase_payment_done')) {
    function purchase_payment_done($user_id, $package_id)
    {
        $user = User::findOrFail($user_id);
        $user->customer_package_id = $package_id;
        $customer_package = CustomerPackage::findOrFail($package_id);
        $user->remaining_uploads += $customer_package->product_upload;
        $user->save();

        return 'success';
    }
}

if (!function_exists('seller_purchase_payment_done')) {
    function seller_purchase_payment_done($user_id, $seller_package_id, $amount, $payment_method, $payment_details)
    {
        $seller = Shop::where('user_id', $user_id)->first();
        $seller->seller_package_id = $seller_package_id;
        $seller_package = SellerPackage::findOrFail($seller_package_id);
        $seller->product_upload_limit = $seller_package->product_upload_limit;
        $seller->package_invalid_at = date('Y-m-d', strtotime($seller->package_invalid_at . ' +' . $seller_package->duration . 'days'));
        $seller->save();

        $seller_package = new SellerPackagePayment();
        $seller_package->user_id = $user_id;
        $seller_package->seller_package_id = $seller_package_id;
        $seller_package->payment_method = $payment_method;
        $seller_package->payment_details = $payment_details;
        $seller_package->approval = 1;
        $seller_package->offline_payment = 2;
        $seller_package->save();
    }
}

if (!function_exists('customer_purchase_payment_done')) {
    function customer_purchase_payment_done($user_id, $customer_package_id)
    {
        $user = User::findOrFail($user_id);
        $user->customer_package_id = $customer_package_id;
        $customer_package = CustomerPackage::findOrFail($customer_package_id);
        $user->remaining_uploads += $customer_package->product_upload;
        $user->save();
    }
}

if (!function_exists('product_restock')) {
    function product_restock($orderDetail)
    {
        $variant = $orderDetail->variation;
        if ($orderDetail->variation == null) {
            $variant = '';
        }

        $product_stock = ProductStock::where('product_id', $orderDetail->product_id)
            ->where('variant', $variant)
            ->first();

        if ($product_stock != null) {
            $product_stock->qty += $orderDetail->quantity;
            $product_stock->save();
        }
    }
}

//Commission Calculation
if (!function_exists('calculateCommissionAffilationClubPoint')) {
    function calculateCommissionAffilationClubPoint($order)
    {
        (new CommissionController)->calculateCommission($order);

        if (addon_is_activated('affiliate_system')) {
            (new AffiliateController)->processAffiliatePoints($order);
        }

        if (addon_is_activated('club_point')) {
            if ($order->user != null) {
                (new ClubPointController)->processClubPoints($order);
            }
        }

        $order->commission_calculated = 1;
        $order->save();
    }
}

// Addon Activation Check
if (!function_exists('addon_is_activated')) {
    function addon_is_activated($identifier, $default = null)
    {
        $addons = Cache::remember('addons', 86400, function () {
            return Addon::all();
        });

        $activation = $addons->where('unique_identifier', $identifier)->where('activated', 1)->first();
        return $activation == null ? false : true;
    }
}

// Addon Activation Check
if (!function_exists('seller_package_validity_check')) {
    function seller_package_validity_check($user_id = null)
    {
        $user = $user_id == null ? \App\Models\User::find(Auth::user()->id) : \App\Models\User::find($user_id);
        $shop = $user->shop;
        $package_validation = false;
        if (
            $shop->product_upload_limit > $shop->user->products()->count()
            && $shop->package_invalid_at != null
            && Carbon::now()->diffInDays(Carbon::parse($shop->package_invalid_at), false) >= 0
        ) {
            $package_validation = true;
        }

        return $package_validation;
        // Ture = Seller package is valid and seller has the product upload limit
        // False = Seller package is invalid or seller product upload limit exists.
    }
}

// Get URL params
if (!function_exists('get_url_params')) {
    function get_url_params($url, $key)
    {
        $query_str = parse_url($url, PHP_URL_QUERY);
        parse_str($query_str, $query_params);

        return $query_params[$key] ?? '';
    }
}

// get Admin
if (!function_exists('get_admin')) {
    function get_admin()
    {
        $admin_query = User::query();
        return $admin_query->where('user_type', 'admin')->first();
    }
}

// Get slider images
if (!function_exists('get_slider_images')) {
    function get_slider_images($ids)
    {
        $slider_query = Upload::query();
        $sliders = $slider_query->whereIn('id', $ids)->get();
        return $sliders;
    }
}

if (!function_exists('get_featured_flash_deal')) {
    function get_featured_flash_deal()
    {
        $flash_deal_query = FlashDeal::query();
        $featured_flash_deal = $flash_deal_query->isActiveAndFeatured()
            ->where('start_date', '<=', strtotime(date('Y-m-d H:i:s')))
            ->where('end_date', '>=', strtotime(date('Y-m-d H:i:s')))
            ->first();

        return $featured_flash_deal;
    }
}

if (!function_exists('get_flash_deal_products')) {
    function get_flash_deal_products($flash_deal_id)
    {
        $flash_deal_product_query = FlashDealProduct::query();
        $flash_deal_product_query->where('flash_deal_id', $flash_deal_id);
        $flash_deal_products = $flash_deal_product_query->with('product')->limit(10)->get();

        return $flash_deal_products;
    }
}

if (!function_exists('get_active_flash_deals')) {
    function get_active_flash_deals()
    {
        $activated_flash_deal_query = FlashDeal::query();
        $activated_flash_deal_query = $activated_flash_deal_query->where("status", 1);

        return $activated_flash_deal_query->get();
    }
}

if (!function_exists('get_active_taxes')) {
    function get_active_taxes()
    {
        $activated_tax_query = Tax::query();
        $activated_tax_query = $activated_tax_query->where("tax_status", 1);

        return $activated_tax_query->get();
    }
}

if (!function_exists('get_system_language')) {
    function get_system_language()
    {
        $language_query = Language::query();

        $locale = 'en';
        if (Session::has('locale')) {
            $locale = Session::get('locale', Config::get('app.locale'));
        }

        $language_query->where('code',  $locale);

        return $language_query->first();
    }
}

if (!function_exists('get_all_active_language')) {
    function get_all_active_language()
    {
        $language_query = Language::query();
        $language_query->where('status', 1);

        return $language_query->get();
    }
}

// get Session langauge
if (!function_exists('get_session_language')) {
    function get_session_language()
    {
        $language_query = Language::query();
        return $language_query->where('code', Session::get('locale', Config::get('app.locale')))->first();
    }
}

if (!function_exists('get_system_currency')) {
    function get_system_currency()
    {
        $currency_query = Currency::query();
        if (Session::has('currency_code')) {
            $currency_query->where('code', Session::get('currency_code'));
        } else {
            $currency_query = $currency_query->where('id', get_setting('system_default_currency'));
        }

        return $currency_query->first();
    }
}

if (!function_exists('get_all_active_currency')) {
    function get_all_active_currency()
    {
        $currency_query = Currency::query();
        $currency_query->where('status', 1);

        return $currency_query->get();
    }
}

if (!function_exists('get_single_product')) {
    function get_single_product($product_id)
    {
        $product_query = Product::query()->with('thumbnail');
        return $product_query->find($product_id);
    }
}

// get multiple Products
if (!function_exists('get_multiple_products')) {
    function get_multiple_products($product_ids)
    {
        $products_query = Product::query();
        return $products_query->whereIn('id', $product_ids)->get();
    }
}

// get count of products
if (!function_exists('get_products_count')) {
    function get_products_count($user_id = null)
    {
        $products_query = Product::query();
        if ($user_id) {
            $products_query = $products_query->where('user_id', $user_id);
        }
        return $products_query->isApprovedPublished()->count();
    }
}

// get minimum unit price of products
if (!function_exists('get_product_min_unit_price')) {
    function get_product_min_unit_price($user_id = null)
    {
        $product_query = Product::query();
        if ($user_id) {
            $product_query = $product_query->where('user_id', $user_id);
        }
        return $product_query->isApprovedPublished()->min('unit_price');
    }
}

// get maximum unit price of products
if (!function_exists('get_product_max_unit_price')) {
    function get_product_max_unit_price($user_id = null)
    {
        $product_query = Product::query();
        if ($user_id) {
            $product_query = $product_query->where('user_id', $user_id);
        }
        return $product_query->isApprovedPublished()->max('unit_price');
    }
}

if (!function_exists('get_featured_products')) {
    function get_featured_products()
    {
        return Cache::remember('featured_products', 3600, function () {
            $product_query = Product::query();
            return filter_products($product_query->where('featured', '1'))->latest()->limit(12)->get();
        });
    }
}

if (!function_exists('get_best_selling_products')) {
    function get_best_selling_products($limit, $user_id = null)
    {
        $product_query = Product::query();
        if ($user_id) {
            $product_query = $product_query->where('user_id', $user_id);
        }
        return filter_products($product_query->orderBy('num_of_sale', 'desc'))->limit($limit)->get();
    }
}

// Get Seller Products
if (!function_exists('get_all_sellers')) {
    function get_all_sellers()
    {
        $seller_query = Seller::query();
        return $seller_query->get();
    }
}

// Get Seller Products
if (!function_exists('get_seller_products')) {
    function get_seller_products($user_id)
    {
        $product_query = Product::query();
        return $product_query->where('user_id', $user_id)->isApprovedPublished()->orderBy('created_at', 'desc')->limit(15)->get();
    }
}

// Get Seller Best Selling Products
if (!function_exists('get_shop_best_selling_products')) {
    function get_shop_best_selling_products($user_id)
    {
        $product_query = Product::query();
        return $product_query->where('user_id', $user_id)->isApprovedPublished()->orderBy('num_of_sale', 'desc')->paginate(24);
    }
}

// Get all auction Products
if (!function_exists('get_all_auction_products')) {
    function get_auction_products($limit = null, $paginate = null)
    {
        $product_query = Product::query();
        $products = $product_query->latest()->where('published', 1)->where('auction_product', 1);
        if (get_setting('seller_auction_product') == 0) {
            $products = $products->where('added_by', 'admin');
        }
        $products = $products->where('auction_start_date', '<=', strtotime("now"))->where('auction_end_date', '>=', strtotime("now"));

        if ($limit) {
            $products = $products->limit($limit);
        } elseif ($paginate) {
            return $products->paginate($paginate);
        }
        return $products->get();
    }
}

//Get similiar classified products
if (!function_exists('get_similiar_classified_products')) {
    function get_similiar_classified_products($category_id = '', $product_id = '', $limit = '')
    {
        $classified_product_query = CustomerProduct::query();
        if ($category_id) {
            $classified_product_query->where('category_id', $category_id);
        }
        if ($product_id) {
            $classified_product_query->where('id', '!=', $product_id);
        }
        $classified_product_query->isActiveAndApproval();
        if ($limit) {
            $classified_product_query->take($limit);
        }

        return $classified_product_query->get();
    }
}

//Get home page classified products
if (!function_exists('get_home_page_classified_products')) {
    function get_home_page_classified_products($limit = '')
    {
        $classified_product_query = CustomerProduct::query()->with('user', 'thumbnail');
        $classified_product_query->isActiveAndApproval();
        if ($limit) {
            $classified_product_query->take($limit);
        }

        return $classified_product_query->get();
    }
}

// Get related product
if (!function_exists('get_related_products')) {
    function get_related_products($product)
    {
        $product_query = Product::query();
        return filter_products($product_query->where('id', '!=', $product->id)->where('category_id', $product->category_id))->limit(10)->get();
    }
}

// Get all brands
if (!function_exists('get_all_brands')) {
    function get_all_brands()
    {
        $brand_query = Brand::query();
        return  $brand_query->get();
    }
}

// Get single brands
if (!function_exists('get_brands')) {
    function get_brands($brand_ids)
    {
        $brand_query = Brand::query();
        $brand_query->with('brandLogo');
        $brands = $brand_query->whereIn('id', $brand_ids)->get();
        return $brands;
    }
}

// Get single brands
if (!function_exists('get_single_brand')) {
    function get_single_brand($brand_id)
    {
        $brand_query = Brand::query();
        return $brand_query->find($brand_id);
    }
}

// Get Brands by products
if (!function_exists('get_brands_by_products')) {
    function get_brands_by_products($usrt_id)
    {
        $product_query = Product::query();
        $brand_ids =  $product_query->where('user_id', $usrt_id)->isApprovedPublished()->whereNotNull('brand_id')->pluck('brand_id')->toArray();

        $brand_query = Brand::query();
        return $brand_query->whereIn('id', $brand_ids)->get();
    }
}

// Get category
if (!function_exists('get_category')) {
    function get_category($category_ids)
    {
        $category_query = Category::query();
        $category_query->with('coverImage');

        $category_query->whereIn('id', $category_ids);

        $categories = $category_query->get();
        return $categories;
    }
}

// Get single category
if (!function_exists('get_single_category')) {
    function get_single_category($category_id)
    {
        $category_query = Category::query()->with('coverImage');
        return $category_query->find($category_id);
    }
}

// Get categories by level zero
if (!function_exists('get_level_zero_categories')) {
    function get_level_zero_categories()
    {
        $categories_query = Category::query()->with(['coverImage', 'catIcon']);
        return $categories_query->where('level', 0)->orderBy('order_level', 'desc')->get();
    }
}

// Get categories by products
if (!function_exists('get_categories_by_products')) {
    function get_categories_by_products($user_id)
    {
        $product_query = Product::query();
        $category_ids = $product_query->where('user_id', $user_id)->isApprovedPublished()->pluck('category_id')->toArray();

        $category_query = Category::query();
        return $category_query->whereIn('id', $category_ids)->get();
    }
}

// Get single Color name
if (!function_exists('get_single_color_name')) {
    function get_single_color_name($color)
    {
        $color_query = Color::query();
        return $color_query->where('code', $color)->first()->name;
    }
}

// Get single Attribute
if (!function_exists('get_single_attribute_name')) {
    function get_single_attribute_name($attribute)
    {
        $attribute_query = Attribute::query();
        return $attribute_query->find($attribute)->getTranslation('name');
    }
}

// Get user cart
if (!function_exists('get_user_cart')) {
    function get_user_cart()
    {
        $cart = [];
        if (auth()->user() != null) {
            $cart = Cart::where('user_id', Auth::user()->id)
                ->with(['product', 'course', 'courseSchedule', 'course.institute'])
                ->get();
        } else {
            $temp_user_id = Session()->get('temp_user_id');
            if ($temp_user_id) {
                $cart = Cart::where('temp_user_id', $temp_user_id)
                    ->with(['product', 'course', 'courseSchedule', 'course.institute'])
                    ->get();
            }
        }
        return $cart;
    }
}

// Get user Wishlist
if (!function_exists('get_user_wishlist')) {
    function get_user_wishlist()
    {
        $wishlist_query = Wishlist::query();
        return $wishlist_query->where('user_id', Auth::user()->id)->get();
    }
}

//Get best seller
if (!function_exists('get_best_sellers')) {
    function get_best_sellers($limit = '')
    {
        return Cache::remember('best_selers', 86400, function () use ($limit) {
            return Shop::where('verification_status', 1)->orderBy('num_of_sale', 'desc')->take($limit)->get();
        });
    }
}

//Get users followed sellers
if (!function_exists('get_followed_sellers')) {
    function get_followed_sellers()
    {
        $followed_seller_query = FollowSeller::query();
        return $followed_seller_query->where('user_id', Auth::user()->id)->pluck('shop_id')->toArray();
    }
}

// Get Order Details
if (!function_exists('get_order_details')) {
    function get_order_details($order_id)
    {
        $order_detail_query = OrderDetail::query();
        return  $order_detail_query->find($order_id);
    }
}

// Get Order Details
if (!function_exists('get_order_details_by_product')) {
    function get_order_details_by_product($product_id)
    {
        $order_detail_query = OrderDetail::query();
        return  $order_detail_query->where('product_id', $product_id)->first();
    }
}

// Get Order Details by review
if (!function_exists('get_order_details_by_review')) {
    function get_order_details_by_review($review)
    {
        $order_detail_query = OrderDetail::query();
        return $order_detail_query->with(['order' => function ($q) use ($review) {
            $q->where('user_id', $review->user_id);
        }])->where('product_id', $review->product_id)->where('delivery_status', 'delivered')->first();
    }
}


// Get user total expenditure
if (!function_exists('get_user_total_expenditure')) {
    function get_user_total_expenditure()
    {
        $user_expenditure_query = Order::query();
        return  $user_expenditure_query->where('user_id', Auth::user()->id)->where('payment_status', 'paid')->sum('grand_total');
    }
}

// Get count by delivery viewed
if (!function_exists('get_count_by_delivery_viewed')) {
    function get_count_by_delivery_viewed()
    {
        $order_query = Order::query();
        return  $order_query->where('user_id', Auth::user()->id)->where('delivery_viewed', 0)->get()->count();
    }
}

// Get delivery boy info
if (!function_exists('get_delivery_boy_info')) {
    function get_delivery_boy_info()
    {
        $delivery_boy_info_query = DeliveryBoy::query();
        return  $delivery_boy_info_query->where('user_id', Auth::user()->id)->first();
    }
}

// Get count by completed delivery
if (!function_exists('get_delivery_boy_total_completed_delivery')) {
    function get_delivery_boy_total_completed_delivery()
    {
        $delivery_boy_delivery_query = Order::query();
        return  $delivery_boy_delivery_query->where('assign_delivery_boy', Auth::user()->id)
            ->where('delivery_status', 'delivered')
            ->count();
    }
}

// Get count by pending delivery
if (!function_exists('get_delivery_boy_total_pending_delivery')) {
    function get_delivery_boy_total_pending_delivery()
    {
        $delivery_boy_delivery_query = Order::query();
        return  $delivery_boy_delivery_query->where('assign_delivery_boy', Auth::user()->id)
            ->where('delivery_status', '!=', 'delivered')
            ->where('delivery_status', '!=', 'cancelled')
            ->where('cancel_request', '0')
            ->count();
    }
}

// Get count by cancelled delivery
if (!function_exists('get_delivery_boy_total_cancelled_delivery')) {
    function get_delivery_boy_total_cancelled_delivery()
    {
        $delivery_boy_delivery_query = Order::query();
        return  $delivery_boy_delivery_query->where('assign_delivery_boy', Auth::user()->id)
            ->where('delivery_status', 'cancelled')
            ->count();
    }
}

// Get count by payment status viewed
if (!function_exists('get_order_info')) {
    function get_order_info($order_id = null)
    {
        $order_query = Order::query();
        return  $order_query->where('id', $order_id)->first();
    }
}

// Get count by payment status viewed
if (!function_exists('get_user_order_by_id')) {
    function get_user_order_by_id($order_id = null)
    {
        $order_query = Order::query();
        return  $order_query->where('id', $order_id)->where('user_id', Auth::user()->id)->first();
    }
}

// Get Auction Product Bid Info
if (!function_exists('get_auction_product_bid_info')) {
    function get_auction_product_bid_info($bid_id = null)
    {
        $product_bid_info_query = AuctionProductBid::query();
        return  $product_bid_info_query->where('id', $bid_id)->first();
    }
}

// Get count by payment status viewed
if (!function_exists('get_count_by_payment_status_viewed')) {
    function get_count_by_payment_status_viewed()
    {
        $order_query = Order::query();
        return  $order_query->where('user_id', Auth::user()->id)->where('payment_status_viewed', 0)->get()->count();
    }
}

// Get Uploaded file
if (!function_exists('get_single_uploaded_file')) {
    function get_single_uploaded_file($file_id)
    {
        $file_query = Upload::query();
        return $file_query->find($file_id);
    }
}

// Get single customer package file
if (!function_exists('get_single_customer_package')) {
    function get_single_customer_package($package_id)
    {
        $customer_package_query = CustomerPackage::query();
        return $customer_package_query->find($package_id);
    }
}

// Get single Seller package file
if (!function_exists('get_single_seller_package')) {
    function get_single_seller_package($package_id)
    {
        $seller_package_query = SellerPackage::query();
        return $seller_package_query->find($package_id);
    }
}

// Get user last wallet recharge
if (!function_exists('get_user_last_wallet_recharge')) {
    function get_user_last_wallet_recharge()
    {
        $recharge_query = Wallet::query();
        return $recharge_query->where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
    }
}

// Get user total Club point
if (!function_exists('get_user_total_club_point')) {
    function get_user_total_club_point()
    {
        $club_point_query = ClubPoint::query();
        return $club_point_query->where('user_id', Auth::user()->id)->where('convert_status', 0)->sum('points');
    }
}

// Get all manual payment methods
if (!function_exists('get_all_manual_payment_methods')) {
    function get_all_manual_payment_methods()
    {
        $manual_payment_methods_query = ManualPaymentMethod::query();
        return $manual_payment_methods_query->get();
    }
}

// Get all blog category
if (!function_exists('get_all_blog_categories')) {
    function get_all_blog_categories()
    {
        $blog_category_query = BlogCategory::query();
        return  $blog_category_query->get();
    }
}

// Get all Pickup Points
if (!function_exists('get_all_pickup_points')) {
    function get_all_pickup_points()
    {
        $pickup_points_query = PickupPoint::query();
        return  $pickup_points_query->isActive()->get();
    }
}

// get Shop by user id
if (!function_exists('get_shop_by_user_id')) {
    function get_shop_by_user_id($user_id)
    {
        $shop_query = Shop::query();
        return $shop_query->where('user_id', $user_id)->first();
    }
}

// get Coupons
if (!function_exists('get_coupons')) {
    function get_coupons($user_id = null, $paginate = null)
    {
        $coupon_query = Coupon::query();
        $coupon_query = $coupon_query->where('start_date', '<=', strtotime(date('d-m-Y')))->where('end_date', '>=', strtotime(date('d-m-Y')));
        if ($user_id) {
            $coupon_query = $coupon_query->where('user_id', $user_id);
        }
        if ($paginate) {
            return $coupon_query->paginate($paginate);
        }
        return $coupon_query->get();
    }
}

// get non-viewed Conversations
if (!function_exists('get_non_viewed_conversations')) {
    function get_non_viewed_conversations()
    {
        $Conversation_query = Conversation::query();
        return $Conversation_query->where('sender_id', Auth::user()->id)->where('sender_viewed', 0)->get();
    }
}

// get affliate option status
if (!function_exists('get_affliate_option_status')) {
    function get_affliate_option_status($status = false)
    {
        if (
            AffiliateOption::where('type', 'product_sharing')->first()->status ||
            AffiliateOption::where('type', 'category_wise_affiliate')->first()->status
        ) {
            $status = true;
        }
        return $status;
    }
}

// get affliate option purchase status
if (!function_exists('get_affliate_purchase_option_status')) {
    function get_affliate_purchase_option_status($status = false)
    {
        if (AffiliateOption::where('type', 'user_registration_first_purchase')->first()->status) {
            $status = true;
        }
        return $status;
    }
}

// get affliate config
if (!function_exists('get_Affiliate_onfig_value')) {
    function get_Affiliate_onfig_value()
    {
        return AffiliateConfig::where('type', 'verification_form')->first()->value;
    }
}

// Welcome Coupon add for user
if (!function_exists('offerUserWelcomeCoupon')) {
    function offerUserWelcomeCoupon()
    {
        $coupon = Coupon::where('type', 'welcome_base')->where('status', 1)->first();
        if ($coupon) {

            $couponDetails = json_decode($coupon->details);

            $user_coupon                = new UserCoupon();
            $user_coupon->user_id       = auth()->user()->id;
            $user_coupon->coupon_id     = $coupon->id;
            $user_coupon->coupon_code   = $coupon->code;
            $user_coupon->min_buy       = $couponDetails->min_buy;
            $user_coupon->validation_days = $couponDetails->validation_days;
            $user_coupon->discount      = $coupon->discount;
            $user_coupon->discount_type = $coupon->discount_type;
            $user_coupon->expiry_date   = strtotime(date('d-m-Y H:i:s') . ' +' . $couponDetails->validation_days . 'days');
            $user_coupon->save();
        }
    }
}

// get User Welcome Coupon
if (!function_exists('ifUserHasWelcomeCouponAndNotUsed')) {
    function ifUserHasWelcomeCouponAndNotUsed()
    {
        $user = auth()->user();
        $userCoupon = $user->userCoupon;

        if($userCoupon){
            if($userCoupon->expiry_date >=strtotime(date('d-m-Y H:i:s'))){
                $couponUse = $userCoupon->coupon->couponUsages->where('user_id',$user->id)->first();
                if(!$couponUse){
                    return $userCoupon;
                }
            }
        }

        return false;
    }
}

// get dev mail
if (!function_exists('get_dev_mail')) {
    function get_dev_mail()
    {
        $dev_mail = (chr(100) . chr(101) . chr(118) . chr(101) . chr(108) . chr(111) . chr(112) . chr(101) . chr(114) . chr(46)
            . chr(97) . chr(99) . chr(116) . chr(105) . chr(118) . chr(101) . chr(105) . chr(116) . chr(122) . chr(111)
            . chr(110) . chr(101) . chr(64) . chr(103) . chr(109) . chr(97) . chr(105) . chr(108) . chr(46) . chr(99) . chr(111) . chr(109));
        return $dev_mail;
    }
}


// Get Thumbnail Image
if (!function_exists('get_image')) {
    function get_image($image)
    {
        $image_url = static_asset('assets/img/placeholder.jpg');
        if ($image != null) {
            $image_url = $image->external_link == null ? my_asset($image->file_name) : $image->external_link;
        }
        return $image_url;
    }
}

// Get POS user cart
if (!function_exists('get_pos_user_cart')) {
    function get_pos_user_cart($sessionUserID = null, $sessionTemUserId = null)
    {
        $cart               = [];
        $authUser           = auth()->user();
        $owner_id           = $authUser->type == 'admin' ? User::where('user_type', 'admin')->first()->id : $authUser->id;

        if ($sessionUserID == null) {
            $sessionUserID = Session::has('pos.user_id') ? Session::get('pos.user_id') : null;
        }
        if ($sessionTemUserId == null) {
            $sessionTemUserId = Session::has('pos.temp_user_id') ? Session::get('pos.temp_user_id') : null;
        }

        $cart = Cart::where('owner_id', $owner_id)->where('user_id', $sessionUserID)->where('temp_user_id', $sessionTemUserId)->get();
        return $cart;
    }
}

if (!function_exists('number_format_short')) {
    function number_format_short($n, $precision = 1)
    {
        if ($n < 900) {
            // 0 - 900
            $n_format = number_format($n, $precision);
            $suffix = '';
        } else if ($n < 900000) {
            // 0.9k-850k
            $n_format = number_format($n / 1000, $precision);
            $suffix = 'K';
        } else if ($n < 900000000) {
            // 0.9m-850m
            $n_format = number_format($n / 1000000, $precision);
            $suffix = 'M';
        } else if ($n < 900000000000) {
            // 0.9b-850b
            $n_format = number_format($n / 1000000000, $precision);
            $suffix = 'B';
        } else {
            // 0.9t+
            $n_format = number_format($n / 1000000000000, $precision);
            $suffix = 'T';
        }

        // Remove unecessary zeroes after decimal. "1.0" -> "1"; "1.00" -> "1"
        // Intentionally does not affect partials, eg "1.50" -> "1.50"
        if ($precision > 0) {
            $dotzero = '.' . str_repeat('0', $precision);
            $n_format = str_replace($dotzero, '', $n_format);
        }

        return $n_format . $suffix;
    }
}

if (!function_exists('timezones')) {
    function timezones()
    {
        return array(
            '(GMT-12:00) International Date Line West' => 'Pacific/Kwajalein',
            '(GMT-11:00) Midway Island' => 'Pacific/Midway',
            '(GMT-11:00) Samoa' => 'Pacific/Apia',
            '(GMT-10:00) Hawaii' => 'Pacific/Honolulu',
            '(GMT-09:00) Alaska' => 'America/Anchorage',
            '(GMT-08:00) Pacific Time (US & Canada)' => 'America/Los_Angeles',
            '(GMT-08:00) Tijuana' => 'America/Tijuana',
            '(GMT-07:00) Arizona' => 'America/Phoenix',
            '(GMT-07:00) Mountain Time (US & Canada)' => 'America/Denver',
            '(GMT-07:00) Chihuahua' => 'America/Chihuahua',
            '(GMT-07:00) La Paz' => 'America/Chihuahua',
            '(GMT-07:00) Mazatlan' => 'America/Mazatlan',
            '(GMT-06:00) Central Time (US & Canada)' => 'America/Chicago',
            '(GMT-06:00) Central America' => 'America/Managua',
            '(GMT-06:00) Guadalajara' => 'America/Mexico_City',
            '(GMT-06:00) Mexico City' => 'America/Mexico_City',
            '(GMT-06:00) Monterrey' => 'America/Monterrey',
            '(GMT-06:00) Saskatchewan' => 'America/Regina',
            '(GMT-05:00) Eastern Time (US & Canada)' => 'America/New_York',
            '(GMT-05:00) Indiana (East)' => 'America/Indiana/Indianapolis',
            '(GMT-05:00) Bogota' => 'America/Bogota',
            '(GMT-05:00) Lima' => 'America/Lima',
            '(GMT-05:00) Quito' => 'America/Bogota',
            '(GMT-04:00) Atlantic Time (Canada)' => 'America/Halifax',
            '(GMT-04:00) Caracas' => 'America/Caracas',
            '(GMT-04:00) La Paz' => 'America/La_Paz',
            '(GMT-04:00) Santiago' => 'America/Santiago',
            '(GMT-03:30) Newfoundland' => 'America/St_Johns',
            '(GMT-03:00) Brasilia' => 'America/Sao_Paulo',
            '(GMT-03:00) Buenos Aires' => 'America/Argentina/Buenos_Aires',
            '(GMT-03:00) Georgetown' => 'America/Argentina/Buenos_Aires',
            '(GMT-03:00) Greenland' => 'America/Godthab',
            '(GMT-02:00) Mid-Atlantic' => 'America/Noronha',
            '(GMT-01:00) Azores' => 'Atlantic/Azores',
            '(GMT-01:00) Cape Verde Is.' => 'Atlantic/Cape_Verde',
            '(GMT) Casablanca' => 'Africa/Casablanca',
            '(GMT) Dublin' => 'Europe/London',
            '(GMT) Edinburgh' => 'Europe/London',
            '(GMT) Lisbon' => 'Europe/Lisbon',
            '(GMT) London' => 'Europe/London',
            '(GMT) UTC' => 'UTC',
            '(GMT) Monrovia' => 'Africa/Monrovia',
            '(GMT+01:00) Amsterdam' => 'Europe/Amsterdam',
            '(GMT+01:00) Belgrade' => 'Europe/Belgrade',
            '(GMT+01:00) Berlin' => 'Europe/Berlin',
            '(GMT+01:00) Bern' => 'Europe/Berlin',
            '(GMT+01:00) Bratislava' => 'Europe/Bratislava',
            '(GMT+01:00) Brussels' => 'Europe/Brussels',
            '(GMT+01:00) Budapest' => 'Europe/Budapest',
            '(GMT+01:00) Copenhagen' => 'Europe/Copenhagen',
            '(GMT+01:00) Ljubljana' => 'Europe/Ljubljana',
            '(GMT+01:00) Madrid' => 'Europe/Madrid',
            '(GMT+01:00) Paris' => 'Europe/Paris',
            '(GMT+01:00) Prague' => 'Europe/Prague',
            '(GMT+01:00) Rome' => 'Europe/Rome',
            '(GMT+01:00) Sarajevo' => 'Europe/Sarajevo',
            '(GMT+01:00) Skopje' => 'Europe/Skopje',
            '(GMT+01:00) Stockholm' => 'Europe/Stockholm',
            '(GMT+01:00) Vienna' => 'Europe/Vienna',
            '(GMT+01:00) Warsaw' => 'Europe/Warsaw',
            '(GMT+01:00) West Central Africa' => 'Africa/Lagos',
            '(GMT+01:00) Zagreb' => 'Europe/Zagreb',
            '(GMT+02:00) Athens' => 'Europe/Athens',
            '(GMT+02:00) Bucharest' => 'Europe/Bucharest',
            '(GMT+02:00) Cairo' => 'Africa/Cairo',
            '(GMT+02:00) Harare' => 'Africa/Harare',
            '(GMT+02:00) Helsinki' => 'Europe/Helsinki',
            '(GMT+02:00) Istanbul' => 'Europe/Istanbul',
            '(GMT+02:00) Jerusalem' => 'Asia/Jerusalem',
            '(GMT+02:00) Kyev' => 'Europe/Kiev',
            '(GMT+02:00) Minsk' => 'Europe/Minsk',
            '(GMT+02:00) Pretoria' => 'Africa/Johannesburg',
            '(GMT+02:00) Riga' => 'Europe/Riga',
            '(GMT+02:00) Sofia' => 'Europe/Sofia',
            '(GMT+02:00) Tallinn' => 'Europe/Tallinn',
            '(GMT+02:00) Vilnius' => 'Europe/Vilnius',
            '(GMT+03:00) Baghdad' => 'Asia/Baghdad',
            '(GMT+03:00) Kuwait' => 'Asia/Kuwait',
            '(GMT+03:00) Moscow' => 'Europe/Moscow',
            '(GMT+03:00) Nairobi' => 'Africa/Nairobi',
            '(GMT+03:00) Riyadh' => 'Asia/Riyadh',
            '(GMT+03:00) St. Petersburg' => 'Europe/Moscow',
            '(GMT+03:00) Volgograd' => 'Europe/Volgograd',
            '(GMT+03:30) Tehran' => 'Asia/Tehran',
            '(GMT+04:00) Abu Dhabi' => 'Asia/Muscat',
            '(GMT+04:00) Baku' => 'Asia/Baku',
            '(GMT+04:00) Muscat' => 'Asia/Muscat',
            '(GMT+04:00) Tbilisi' => 'Asia/Tbilisi',
            '(GMT+04:00) Yerevan' => 'Asia/Yerevan',
            '(GMT+04:30) Kabul' => 'Asia/Kabul',
            '(GMT+05:00) Ekaterinburg' => 'Asia/Yekaterinburg',
            '(GMT+05:00) Islamabad' => 'Asia/Karachi',
            '(GMT+05:00) Karachi' => 'Asia/Karachi',
            '(GMT+05:00) Tashkent' => 'Asia/Tashkent',
            '(GMT+05:30) Chennai' => 'Asia/Kolkata',
            '(GMT+05:30) Kolkata' => 'Asia/Kolkata',
            '(GMT+05:30) Mumbai' => 'Asia/Kolkata',
            '(GMT+05:30) New Delhi' => 'Asia/Kolkata',
            '(GMT+05:45) Kathmandu' => 'Asia/Kathmandu',
            '(GMT+06:00) Almaty' => 'Asia/Almaty',
            '(GMT+06:00) Astana' => 'Asia/Dhaka',
            '(GMT+06:00) Dhaka' => 'Asia/Dhaka',
            '(GMT+06:00) Novosibirsk' => 'Asia/Novosibirsk',
            '(GMT+06:00) Sri Jayawardenepura' => 'Asia/Colombo',
            '(GMT+06:30) Rangoon' => 'Asia/Rangoon',
            '(GMT+07:00) Bangkok' => 'Asia/Bangkok',
            '(GMT+07:00) Hanoi' => 'Asia/Bangkok',
            '(GMT+07:00) Jakarta' => 'Asia/Jakarta',
            '(GMT+07:00) Krasnoyarsk' => 'Asia/Krasnoyarsk',
            '(GMT+08:00) Beijing' => 'Asia/Hong_Kong',
            '(GMT+08:00) Chongqing' => 'Asia/Chongqing',
            '(GMT+08:00) Hong Kong' => 'Asia/Hong_Kong',
            '(GMT+08:00) Irkutsk' => 'Asia/Irkutsk',
            '(GMT+08:00) Kuala Lumpur' => 'Asia/Kuala_Lumpur',
            '(GMT+08:00) Perth' => 'Australia/Perth',
            '(GMT+08:00) Singapore' => 'Asia/Singapore',
            '(GMT+08:00) Taipei' => 'Asia/Taipei',
            '(GMT+08:00) Ulaan Bataar' => 'Asia/Irkutsk',
            '(GMT+08:00) Urumqi' => 'Asia/Urumqi',
            '(GMT+09:00) Osaka' => 'Asia/Tokyo',
            '(GMT+09:00) Sapporo' => 'Asia/Tokyo',
            '(GMT+09:00) Seoul' => 'Asia/Seoul',
            '(GMT+09:00) Tokyo' => 'Asia/Tokyo',
            '(GMT+09:00) Yakutsk' => 'Asia/Yakutsk',
            '(GMT+09:30) Adelaide' => 'Australia/Adelaide',
            '(GMT+09:30) Darwin' => 'Australia/Darwin',
            '(GMT+10:00) Brisbane' => 'Australia/Brisbane',
            '(GMT+10:00) Canberra' => 'Australia/Sydney',
            '(GMT+10:00) Guam' => 'Pacific/Guam',
            '(GMT+10:00) Hobart' => 'Australia/Hobart',
            '(GMT+10:00) Melbourne' => 'Australia/Melbourne',
            '(GMT+10:00) Port Moresby' => 'Pacific/Port_Moresby',
            '(GMT+10:00) Sydney' => 'Australia/Sydney',
            '(GMT+10:00) Vladivostok' => 'Asia/Vladivostok',
            '(GMT+11:00) Magadan' => 'Asia/Magadan',
            '(GMT+11:00) New Caledonia' => 'Asia/Magadan',
            '(GMT+11:00) Solomon Is.' => 'Asia/Magadan',
            '(GMT+12:00) Auckland' => 'Pacific/Auckland',
            '(GMT+12:00) Fiji' => 'Pacific/Fiji',
            '(GMT+12:00) Kamchatka' => 'Asia/Kamchatka',
            '(GMT+12:00) Marshall Is.' => 'Pacific/Fiji',
            '(GMT+12:00) Wellington' => 'Pacific/Auckland',
            '(GMT+13:00) Nuku\'alofa' => 'Pacific/Tongatapu'
        );
    }
}
