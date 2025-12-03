@extends('frontend.layouts.app')

@php
    $navBaseUrl = rtrim(config('app.nav_base_url', env('NAV_BASE_URL', 'http://localhost:8000')), '/');
@endphp

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.theme.default.min.css">


<link rel="stylesheet" href="{{ static_asset('assets/css/home-page.css') }}">
{{-- @section('content')


    <style>
        @media (max-width: 767px){
            #flash_deal .flash-deals-baner{
                height: 203px !important;
            }
        }
    </style>
    @php $lang = get_system_language()->code;  @endphp
    <!-- Sliders -->
    
        <link rel="stylesheet" href="https://emiraticoffee.ae/public/assets/css/custom-style.css">

    
    <div class="home-banner-area mb-3" style="">
        
        
        <div class="container">
            <div class="d-flex flex-wrap position-relative">
                <div class="position-static d-none d-xl-block">
                    @include('frontend.'.get_setting("homepage_select").'.partials.category_menu')
                </div>

                <!-- Sliders -->
                <div class="home-slider">
                    @if (get_setting('home_slider_images', null, $lang) != null)
                        <div class="aiz-carousel dots-inside-bottom" data-autoplay="true" data-infinite="true">
                            @php
                                $decoded_slider_images = json_decode(get_setting('home_slider_images', null, $lang), true);
                                $sliders = get_slider_images($decoded_slider_images);
                                $home_slider_links = get_setting('home_slider_links', null, $lang);
                            @endphp
                            @foreach ($sliders as $key => $slider)
                                <div class="carousel-box">
                                    <a href="{{ isset(json_decode($home_slider_links, true)[$key]) ? json_decode($home_slider_links, true)[$key] : '' }}">
                                        <!-- Image -->
                                        <img class="d-block mw-100 img-fit overflow-hidden h-180px h-md-320px h-lg-460px overflow-hidden"
                                            src="{{ $slider ? my_asset($slider->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                            alt="{{ env('APP_NAME') }} promo"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Flash Deal -->
    @php
        $flash_deal = get_featured_flash_deal();
    @endphp
    @if ($flash_deal != null)
        <section class="mb-2 mb-md-3 mt-2 mt-md-3" id="flash_deal">
            <div class="container">
                <!-- Top Section -->
                <div class="d-flex flex-wrap mb-2 mb-md-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                        <span class="d-inline-block">{{ translate('Flash Sale') }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="24" viewBox="0 0 16 24"
                            class="ml-3">
                            <path id="Path_28795" data-name="Path 28795"
                                d="M30.953,13.695a.474.474,0,0,0-.424-.25h-4.9l3.917-7.81a.423.423,0,0,0-.028-.428.477.477,0,0,0-.4-.207H21.588a.473.473,0,0,0-.429.263L15.041,18.151a.423.423,0,0,0,.034.423.478.478,0,0,0,.4.2h4.593l-2.229,9.683a.438.438,0,0,0,.259.5.489.489,0,0,0,.571-.127L30.9,14.164a.425.425,0,0,0,.054-.469Z"
                                transform="translate(-15 -5)" fill="#fcc201" />
                        </svg>
                    </h3>
                    <!-- Links -->
                    <div>
                        <div class="text-dark d-flex align-items-center mb-0">
                            <a href="{{ route('flash-deals') }}"
                                class="fs-10 fs-md-12 fw-700 text-reset has-transition opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary mr-3">{{ translate('View All Flash Sale') }}</a>
                            <span class=" border-left border-soft-light border-width-2 pl-3">
                                <a href="{{ route('flash-deal-details', $flash_deal->slug) }}"
                                    class="fs-10 fs-md-12 fw-700 text-reset has-transition opacity-60 hov-opacity-100 hov-text-primary animate-underline-primary">{{ translate('View All Products from This Flash Sale') }}</a>
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Countdown for small device -->
                <div class="bg-white mb-3 d-md-none">
                    <div class="aiz-count-down-circle" end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                </div>

                <div class="row gutters-5 gutters-md-16">
                    <!-- Flash Deals Baner & Countdown -->
                    <div class="flash-deals-baner col-xxl-4 col-lg-5 col-6 h-200px h-md-400px h-lg-475px">
                        <div class="h-100 w-100 w-xl-auto"
                            style="background-image: url('{{ uploaded_asset($flash_deal->banner) }}'); background-size: cover; background-position: center center;">
                            <div class="py-5 px-md-3 px-xl-5 d-none d-md-block">
                                <div class="bg-white">
                                    <div class="aiz-count-down-circle"
                                        end-date="{{ date('Y/m/d H:i:s', $flash_deal->end_date) }}"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Flash Deals Products -->
                    <div class="col-xxl-8 col-lg-7 col-6">
                        @php
                            $flash_deal_products = get_flash_deal_products($flash_deal->id);
                        @endphp
                        <div class="aiz-carousel border-top @if (count($flash_deal_products) > 8) border-right @endif arrow-inactive-none arrow-x-0"
                            data-rows="2" data-items="5" data-xxl-items="5" data-xl-items="3.5" data-lg-items="3" data-md-items="2"
                            data-sm-items="2.5" data-xs-items="1.7" data-arrows="true" data-dots="false">
                            @foreach ($flash_deal_products as $key => $flash_deal_product)
                                <div class="carousel-box border-left border-bottom">
                                    @if ($flash_deal_product->product != null && $flash_deal_product->product->published != 0)
                                        @php
                                            $product_url = route('product', $flash_deal_product->product->slug);
                                            if ($flash_deal_product->product->auction_product == 1) {
                                                $product_url = route('auction-product', $flash_deal_product->product->slug);
                                            }
                                        @endphp
                                        <div
                                            class="h-100px h-md-200px h-lg-auto flash-deal-item position-relative text-center has-transition hov-shadow-out z-1">
                                            <a href="{{ $product_url }}"
                                                class="d-block py-md-3 overflow-hidden hov-scale-img"
                                                title="{{ $flash_deal_product->product->getTranslation('name') }}">
                                                <!-- Image -->
                                                <img src="{{ get_image($flash_deal_product->product->thumbnail) }}"
                                                    class="lazyload h-60px h-md-100px h-lg-140px mw-100 mx-auto has-transition"
                                                    alt="{{ $flash_deal_product->product->getTranslation('name') }}"
                                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                <!-- Price -->
                                                <div
                                                    class="fs-10 fs-md-14 mt-md-3 text-center h-md-48px has-transition overflow-hidden pt-md-4 flash-deal-price lh-1-5">
                                                    <span
                                                        class="d-block text-primary fw-700">{{ home_discounted_base_price($flash_deal_product->product) }}</span>
                                                    @if (home_base_price($flash_deal_product->product) != home_discounted_base_price($flash_deal_product->product))
                                                        <del
                                                            class="d-block fw-400 text-secondary">{{ home_base_price($flash_deal_product->product) }}</del>
                                                    @endif
                                                </div>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Today's deal -->
    <div id="todays_deal"  class="mb-2 mb-md-3 mt-2 mt-md-3">

    </div>

    <!-- Featured Categories -->
    @if (count($featured_categories) > 0)
        <section class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                <div class="bg-white">
                    <!-- Top Section -->
                    <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                        <!-- Title -->
                        <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                            <span class="">{{ translate('Featured Categories') }}</span>
                        </h3>
                        <!-- Links -->
                        <div class="d-flex">
                            <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                href="{{ route('categories.all') }}">{{ translate('View All Categories') }}</a>
                        </div>
                    </div>
                </div>
                <!-- Categories -->
                <div class="bg-white px-3">
                    <div class="row border-top border-right">
                        @foreach ($featured_categories->take(6) as $key => $category)
                        @php
                            $category_name = $category->getTranslation('name');
                        @endphp
                            <div class="col-xl-4 col-md-6 border-left border-bottom py-3 py-md-2rem">
                                <div class="d-sm-flex text-center text-sm-left">
                                    <div class="mb-3">
                                        <img src="{{ isset($category->bannerImage->file_name) ? my_asset($category->bannerImage->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                            class="lazyload w-150px h-auto mx-auto has-transition"
                                            alt="{{ $category->getTranslation('name') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    </div>
                                    <div class="px-2 px-lg-4">
                                        <h6 class="text-dark mb-0 text-truncate-2">
                                            <a class="text-reset fw-700 fs-14 hov-text-primary"
                                                href="{{ route('products.category', $category->slug) }}"
                                                title="{{ $category_name }}">
                                                {{ $category_name }}
                                            </a>
                                        </h6>
                                        @foreach ($category->childrenCategories->take(5) as $key => $child_category)
                                            <p class="mb-0 mt-3">
                                                <a href="{{ route('products.category', $child_category->slug) }}" class="fs-13 fw-300 text-reset hov-text-primary animate-underline-primary">
                                                    {{ $child_category->getTranslation('name') }}
                                                </a>
                                            </p>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- Banner section 1 -->
    @php $homeBanner1Images = get_setting('home_banner1_images', null, $lang);   @endphp
    @if ($homeBanner1Images != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                @php
                    $banner_1_imags = json_decode($homeBanner1Images);
                    $data_md = count($banner_1_imags) >= 2 ? 2 : 1;
                    $home_banner1_links = get_setting('home_banner1_links', null, $lang);
                @endphp
                <div class="w-100">
                    <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                        data-items="{{ count($banner_1_imags) }}" data-xxl-items="{{ count($banner_1_imags) }}"
                        data-xl-items="{{ count($banner_1_imags) }}" data-lg-items="{{ $data_md }}"
                        data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                        data-dots="false">
                        @foreach ($banner_1_imags as $key => $value)
                            <div class="carousel-box overflow-hidden hov-scale-img">
                                <a href="{{ isset(json_decode($home_banner1_links, true)[$key]) ? json_decode($home_banner1_links, true)[$key] : '' }}"
                                    class="d-block text-reset overflow-hidden">
                                    <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                        data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                        class="img-fluid lazyload w-100 has-transition"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Featured Products -->
    <div id="section_featured">

    </div>

    <!-- Banner Section 2 -->
    @php $homeBanner2Images = get_setting('home_banner2_images', null, $lang);   @endphp
    @if ($homeBanner2Images != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                @php
                    $banner_2_imags = json_decode($homeBanner2Images);
                    $data_md = count($banner_2_imags) >= 2 ? 2 : 1;
                    $home_banner2_links = get_setting('home_banner2_links', null, $lang);
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_2_imags) }}" data-xxl-items="{{ count($banner_2_imags) }}"
                    data-xl-items="{{ count($banner_2_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_2_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img">
                            <a href="{{ isset(json_decode($home_banner2_links, true)[$key]) ? json_decode($home_banner2_links, true)[$key] : '' }}"
                                class="d-block text-reset overflow-hidden">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    class="img-fluid lazyload w-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Best Selling  -->
    <div id="section_best_selling">

    </div>

    <!-- New Products -->
    <div id="section_newest">

    </div>

    <!-- Banner Section 3 -->
    @php $homeBanner3Images = get_setting('home_banner3_images', null, $lang);   @endphp
    @if ($homeBanner3Images != null)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                @php
                    $banner_3_imags = json_decode($homeBanner3Images);
                    $data_md = count($banner_3_imags) >= 2 ? 2 : 1;
                    $home_banner3_links = get_setting('home_banner3_links', null, $lang);
                @endphp
                <div class="aiz-carousel gutters-16 overflow-hidden arrow-inactive-none arrow-dark arrow-x-15"
                    data-items="{{ count($banner_3_imags) }}" data-xxl-items="{{ count($banner_3_imags) }}"
                    data-xl-items="{{ count($banner_3_imags) }}" data-lg-items="{{ $data_md }}"
                    data-md-items="{{ $data_md }}" data-sm-items="1" data-xs-items="1" data-arrows="true"
                    data-dots="false">
                    @foreach ($banner_3_imags as $key => $value)
                        <div class="carousel-box overflow-hidden hov-scale-img">
                            <a href="{{ isset(json_decode($home_banner3_links, true)[$key]) ? json_decode($home_banner3_links, true)[$key] : '' }}"
                                class="d-block text-reset overflow-hidden">
                                <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                    data-src="{{ uploaded_asset($value) }}" alt="{{ env('APP_NAME') }} promo"
                                    class="img-fluid lazyload w-100 has-transition"
                                    onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Auction Product -->
    @if (addon_is_activated('auction'))
        <div id="auction_products">

        </div>
    @endif

    <!-- Cupon -->
    @if (get_setting('coupon_system') == 1)
        <div class="mb-2 mb-md-3 mt-2 mt-md-3"
            style="background-color: {{ get_setting('cupon_background_color', '#292933') }}">
            <div class="container">
                <div class="row py-5">
                    <div class="col-xl-8 text-center text-xl-left">
                        <div class="d-lg-flex">
                            <div class="mb-3 mb-lg-0">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                    width="109.602" height="93.34" viewBox="0 0 109.602 93.34">
                                    <defs>
                                        <clipPath id="clip-pathcup">
                                            <path id="Union_10" data-name="Union 10" d="M12263,13778v-15h64v-41h12v56Z"
                                                transform="translate(-11966 -8442.865)" fill="none" stroke="#fff"
                                                stroke-width="2" />
                                        </clipPath>
                                    </defs>
                                    <g id="Group_24326" data-name="Group 24326"
                                        transform="translate(-274.201 -5254.611)">
                                        <g id="Mask_Group_23" data-name="Mask Group 23"
                                            transform="translate(-3652.459 1785.452) rotate(-45)"
                                            clip-path="url(#clip-pathcup)">
                                            <g id="Group_24322" data-name="Group 24322"
                                                transform="translate(207 18.136)">
                                                <g id="Subtraction_167" data-name="Subtraction 167"
                                                    transform="translate(-12177 -8458)" fill="none">
                                                    <path
                                                        d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                        stroke="none" />
                                                    <path
                                                        d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                        stroke="none" fill="#fff" />
                                                </g>
                                            </g>
                                        </g>
                                        <g id="Group_24321" data-name="Group 24321"
                                            transform="translate(-3514.477 1653.317) rotate(-45)">
                                            <g id="Subtraction_167-2" data-name="Subtraction 167"
                                                transform="translate(-12177 -8458)" fill="none">
                                                <path
                                                    d="M12335,13770h-56a8.009,8.009,0,0,1-8-8v-8a8,8,0,0,0,0-16v-8a8.009,8.009,0,0,1,8-8h56a8.009,8.009,0,0,1,8,8v8a8,8,0,0,0,0,16v8A8.009,8.009,0,0,1,12335,13770Z"
                                                    stroke="none" />
                                                <path
                                                    d="M 12335.0009765625 13768.0009765625 C 12338.3095703125 13768.0009765625 12341.0009765625 13765.30859375 12341.0009765625 13762 L 12341.0009765625 13755.798828125 C 12336.4423828125 13754.8701171875 12333.0009765625 13750.8291015625 12333.0009765625 13746 C 12333.0009765625 13741.171875 12336.4423828125 13737.130859375 12341.0009765625 13736.201171875 L 12341.0009765625 13729.9990234375 C 12341.0009765625 13726.6904296875 12338.3095703125 13723.9990234375 12335.0009765625 13723.9990234375 L 12278.9990234375 13723.9990234375 C 12275.6904296875 13723.9990234375 12272.9990234375 13726.6904296875 12272.9990234375 13729.9990234375 L 12272.9990234375 13736.201171875 C 12277.5576171875 13737.1298828125 12280.9990234375 13741.1708984375 12280.9990234375 13746 C 12280.9990234375 13750.828125 12277.5576171875 13754.869140625 12272.9990234375 13755.798828125 L 12272.9990234375 13762 C 12272.9990234375 13765.30859375 12275.6904296875 13768.0009765625 12278.9990234375 13768.0009765625 L 12335.0009765625 13768.0009765625 M 12335.0009765625 13770.0009765625 L 12278.9990234375 13770.0009765625 C 12274.587890625 13770.0009765625 12270.9990234375 13766.412109375 12270.9990234375 13762 L 12270.9990234375 13754 C 12275.4111328125 13753.9990234375 12278.9990234375 13750.4111328125 12278.9990234375 13746 C 12278.9990234375 13741.5888671875 12275.41015625 13738 12270.9990234375 13738 L 12270.9990234375 13729.9990234375 C 12270.9990234375 13725.587890625 12274.587890625 13721.9990234375 12278.9990234375 13721.9990234375 L 12335.0009765625 13721.9990234375 C 12339.412109375 13721.9990234375 12343.0009765625 13725.587890625 12343.0009765625 13729.9990234375 L 12343.0009765625 13738 C 12338.5888671875 13738.0009765625 12335.0009765625 13741.5888671875 12335.0009765625 13746 C 12335.0009765625 13750.4111328125 12338.58984375 13754 12343.0009765625 13754 L 12343.0009765625 13762 C 12343.0009765625 13766.412109375 12339.412109375 13770.0009765625 12335.0009765625 13770.0009765625 Z"
                                                    stroke="none" fill="#fff" />
                                            </g>
                                            <g id="Group_24325" data-name="Group 24325">
                                                <rect id="Rectangle_18578" data-name="Rectangle 18578" width="8"
                                                    height="2" transform="translate(120 5287)" fill="#fff" />
                                                <rect id="Rectangle_18579" data-name="Rectangle 18579" width="8"
                                                    height="2" transform="translate(132 5287)" fill="#fff" />
                                                <rect id="Rectangle_18581" data-name="Rectangle 18581" width="8"
                                                    height="2" transform="translate(144 5287)" fill="#fff" />
                                                <rect id="Rectangle_18580" data-name="Rectangle 18580" width="8"
                                                    height="2" transform="translate(108 5287)" fill="#fff" />
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </div>
                            <div class="ml-lg-3">
                                <h5 class="fs-36 fw-400 text-white mb-3">{{ translate(get_setting('cupon_title')) }}</h5>
                                <h5 class="fs-20 fw-400 text-gray">{{ translate(get_setting('cupon_subtitle')) }}</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 text-center text-xl-right mt-4">
                        <a href="{{ route('coupons.all') }}"
                            class="btn text-white hov-bg-white hov-text-dark border border-width-2 fs-16 px-4"
                            style="border-radius: 28px;background: rgba(255, 255, 255, 0.2);box-shadow: 0px 20px 30px rgba(0, 0, 0, 0.16);">{{ translate('View All Coupons') }}</a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Category wise Products -->
    <div id="section_home_categories" class="mb-2 mb-md-3 mt-2 mt-md-3">

    </div>

    <!-- Classified Product -->
    @if (get_setting('classified_product') == 1)
        @php
            $classified_products = get_home_page_classified_products(6);
        @endphp
        @if (count($classified_products) > 0)
            <section class="mb-2 mb-md-3 mt-2 mt-md-3">
                <div class="container">
                    <!-- Top Section -->
                    <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                        <!-- Title -->
                        <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                            <span class="">{{ translate('Classified Ads') }}</span>
                        </h3>
                        <!-- Links -->
                        <div class="d-flex">
                            <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                                href="{{ route('customer.products') }}">{{ translate('View All Products') }}</a>
                        </div>
                    </div>
                    <!-- Banner -->
                    @php
                        $classifiedBannerImage = get_setting('classified_banner_image', null, $lang);
                        $classifiedBannerImageSmall = get_setting('classified_banner_image_small', null, $lang);
                    @endphp
                    @if ($classifiedBannerImage != null || $classifiedBannerImageSmall != null)
                        <div class="mb-3 overflow-hidden hov-scale-img d-none d-md-block">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ uploaded_asset($classifiedBannerImage) }}"
                                alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                        <div class="mb-3 overflow-hidden hov-scale-img d-md-none">
                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                data-src="{{ $classifiedBannerImageSmall != null ? uploaded_asset($classifiedBannerImageSmall) : uploaded_asset($classifiedBannerImage) }}"
                                alt="{{ env('APP_NAME') }} promo" class="lazyload img-fit h-100 has-transition"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                        </div>
                    @endif
                    <!-- Products Section -->
                    <div class="bg-white">
                        <div class="row no-gutters border-top border-left">
                            @foreach ($classified_products as $key => $classified_product)
                                <div
                                    class="col-xl-4 col-md-6 border-right border-bottom has-transition hov-shadow-out z-1">
                                    <div class="aiz-card-box p-2 has-transition bg-white">
                                        <div class="row hov-scale-img">
                                            <div class="col-4 col-md-5 mb-3 mb-md-0">
                                                <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                    class="d-block overflow-hidden h-auto h-md-150px text-center">
                                                    <img class="img-fluid lazyload mx-auto has-transition"
                                                        src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                        data-src="{{ isset($classified_product->thumbnail->file_name) ? my_asset($classified_product->thumbnail->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                                        alt="{{ $classified_product->getTranslation('name') }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                </a>
                                            </div>
                                            <div class="col">
                                                <h3
                                                    class="fw-400 fs-14 text-dark text-truncate-2 lh-1-4 mb-3 h-35px d-none d-sm-block">
                                                    <a href="{{ route('customer.product', $classified_product->slug) }}"
                                                        class="d-block text-reset hov-text-primary">{{ $classified_product->getTranslation('name') }}</a>
                                                </h3>
                                                <div class="fs-14 mb-3">
                                                    <span
                                                        class="text-secondary">{{ $classified_product->user ? $classified_product->user->name : '' }}</span><br>
                                                    <span
                                                        class="fw-700 text-primary">{{ single_price($classified_product->unit_price) }}</span>
                                                </div>
                                                @if ($classified_product->conditon == 'new')
                                                    <span
                                                        class="badge badge-inline badge-soft-info fs-13 fw-700 p-3 text-info"
                                                        style="border-radius: 20px;">{{ translate('New') }}</span>
                                                @elseif($classified_product->conditon == 'used')
                                                    <span
                                                        class="badge badge-inline badge-soft-danger fs-13 fw-700 p-3 text-danger"
                                                        style="border-radius: 20px;">{{ translate('Used') }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endif

    <!-- Top Sellers -->
    @if (get_setting('vendor_system_activation') == 1)
        @php
            $best_selers = get_best_sellers(5);
        @endphp
        @if (count($best_selers) > 0)
        <section class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                <!-- Top Section -->
                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">
                        <span class="pb-3">{{ translate('Top Sellers') }}</span>
                    </h3>
                    <!-- Links -->
                    <div class="d-flex">
                        <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                            href="{{ route('sellers') }}">{{ translate('View All Sellers') }}</a>
                    </div>
                </div>
                <!-- Sellers Section -->
                <div class="aiz-carousel arrow-x-0 arrow-inactive-none" data-items="5" data-xxl-items="5"
                    data-xl-items="4" data-lg-items="3.4" data-md-items="2.5" data-sm-items="2" data-xs-items="1.4"
                    data-arrows="true" data-dots="false">
                    @foreach ($best_selers as $key => $seller)
                        @if ($seller->user != null)
                            <div
                                class="carousel-box h-100 position-relative text-center border-right border-top border-bottom @if ($key == 0) border-left @endif has-transition hov-animate-outline">
                                <div class="position-relative px-3" style="padding-top: 2rem; padding-bottom:2rem;">
                                    <!-- Shop logo & Verification Status -->
                                    <div class="position-relative mx-auto size-100px size-md-120px">
                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                            class="d-flex mx-auto justify-content-center align-item-center size-100px size-md-120px border overflow-hidden hov-scale-img"
                                            tabindex="0"
                                            style="border: 1px solid #e5e5e5; border-radius: 50%; box-shadow: 0px 10px 20px rgba(0, 0, 0, 0.06);">
                                            <img src="{{ static_asset('assets/img/placeholder-rect.jpg') }}"
                                                data-src="{{ uploaded_asset($seller->logo) }}" alt="{{ $seller->name }}"
                                                class="img-fit lazyload has-transition"
                                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder-rect.jpg') }}';">
                                        </a>
                                        <div class="absolute-top-right z-1 mr-md-2 mt-1 rounded-content bg-white">
                                            @if ($seller->verification_status == 1)
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24.001" height="24"
                                                    viewBox="0 0 24.001 24">
                                                    <g id="Group_25929" data-name="Group 25929"
                                                        transform="translate(-480 -345)">
                                                        <circle id="Ellipse_637" data-name="Ellipse 637" cx="12"
                                                            cy="12" r="12" transform="translate(480 345)"
                                                            fill="#fff" />
                                                        <g id="Group_25927" data-name="Group 25927"
                                                            transform="translate(480 345)">
                                                            <path id="Union_5" data-name="Union 5"
                                                                d="M0,12A12,12,0,1,1,12,24,12,12,0,0,1,0,12Zm1.2,0A10.8,10.8,0,1,0,12,1.2,10.812,10.812,0,0,0,1.2,12Zm1.2,0A9.6,9.6,0,1,1,12,21.6,9.611,9.611,0,0,1,2.4,12Zm5.115-1.244a1.083,1.083,0,0,0,0,1.529l3.059,3.059a1.081,1.081,0,0,0,1.529,0l5.1-5.1a1.084,1.084,0,0,0,0-1.53,1.081,1.081,0,0,0-1.529,0L11.339,13.05,9.045,10.756a1.082,1.082,0,0,0-1.53,0Z"
                                                                transform="translate(0 0)" fill="#3490f3" />
                                                        </g>
                                                    </g>
                                                </svg>
                                            @else
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24.001" height="24"
                                                    viewBox="0 0 24.001 24">
                                                    <g id="Group_25929" data-name="Group 25929"
                                                        transform="translate(-480 -345)">
                                                        <circle id="Ellipse_637" data-name="Ellipse 637" cx="12"
                                                            cy="12" r="12" transform="translate(480 345)"
                                                            fill="#fff" />
                                                        <g id="Group_25927" data-name="Group 25927"
                                                            transform="translate(480 345)">
                                                            <path id="Union_5" data-name="Union 5"
                                                                d="M0,12A12,12,0,1,1,12,24,12,12,0,0,1,0,12Zm1.2,0A10.8,10.8,0,1,0,12,1.2,10.812,10.812,0,0,0,1.2,12Zm1.2,0A9.6,9.6,0,1,1,12,21.6,9.611,9.611,0,0,1,2.4,12Zm5.115-1.244a1.083,1.083,0,0,0,0,1.529l3.059,3.059a1.081,1.081,0,0,0,1.529,0l5.1-5.1a1.084,1.084,0,0,0,0-1.53,1.081,1.081,0,0,0-1.529,0L11.339,13.05,9.045,10.756a1.082,1.082,0,0,0-1.53,0Z"
                                                                transform="translate(0 0)" fill="red" />
                                                        </g>
                                                    </g>
                                                </svg>
                                            @endif
                                        </div>
                                    </div>
                                    <!-- Shop name -->
                                    <h2 class="fs-14 fw-700 text-dark text-truncate-2 h-40px mt-3 mt-md-4 mb-0 mb-md-3">
                                        <a href="{{ route('shop.visit', $seller->slug) }}"
                                            class="text-reset hov-text-primary" tabindex="0">{{ $seller->name }}</a>
                                    </h2>
                                    <!-- Shop Rating -->
                                    <div class="rating rating-mr-1 text-dark mb-3">
                                        {{ renderStarRating($seller->rating) }}
                                        <span class="opacity-60 fs-14">({{ $seller->num_of_reviews }}
                                            {{ translate('Reviews') }})</span>
                                    </div>
                                    <!-- Visit Button -->
                                    <a href="{{ route('shop.visit', $seller->slug) }}" class="btn-visit">
                                        <span class="circle" aria-hidden="true">
                                            <span class="icon arrow"></span>
                                        </span>
                                        <span class="button-text">{{ translate('Visit Store') }}</span>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </section>
        @endif
    @endif

    <!-- Top Brands -->
    @if (get_setting('top_brands') != null)
        <section class="mb-2 mb-md-3 mt-2 mt-md-3">
            <div class="container">
                <!-- Top Section -->
                <div class="d-flex mb-2 mb-md-3 align-items-baseline justify-content-between">
                    <!-- Title -->
                    <h3 class="fs-16 fs-md-20 fw-700 mb-2 mb-sm-0">{{ translate('Top Brands') }}</h3>
                    <!-- Links -->
                    <div class="d-flex">
                        <a class="text-blue fs-10 fs-md-12 fw-700 hov-text-primary animate-underline-primary"
                            href="{{ route('brands.all') }}">{{ translate('View All Brands') }}</a>
                    </div>
                </div>
                <!-- Brands Section -->
                <div class="bg-white px-3">
                    <div
                        class="row row-cols-xxl-6 row-cols-xl-6 row-cols-lg-4 row-cols-md-4 row-cols-3 gutters-16 border-top border-left">
                        @php
                            $top_brands = json_decode(get_setting('top_brands'));
                            $brands = get_brands($top_brands);
                        @endphp
                        @foreach ($brands as $brand)
                            <div
                                class="col text-center border-right border-bottom hov-scale-img has-transition hov-shadow-out z-1">
                                <a href="{{ route('products.brand', $brand->slug) }}" class="d-block p-sm-3">
                                    <img src="{{ isset($brand->brandLogo->file_name) ? my_asset($brand->brandLogo->file_name) : static_asset('assets/img/placeholder.jpg') }}"
                                        class="lazyload h-md-100px mx-auto has-transition p-2 p-sm-4 mw-100"
                                        alt="{{ $brand->getTranslation('name') }}"
                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                    <p class="text-center text-dark fs-12 fs-md-14 fw-700 mt-2">
                                        {{ $brand->getTranslation('name') }}
                                    </p>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

@endsection --}}

@section('content')

    <style>
        /* Hero Section */
        #hero-banner {
    transition: all 0.3s ease-out !important;
}
        
        
        .hero-section {
            position: relative;
            width: 100%;
            height: 152vh;
            overflow: hidden;
            margin-bottom: 12px;
        }

        /* Background Video */
        .hero-section video {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Banner Overlay */
        .hero-banner {
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 75%; /* Initial width */
            height: 60%;
            background: rgba(255, 255, 255, 0.8); /* Initial background */
            color: white;
            display: flex;
            flex-direction: column;
            align-items: center;
          
            text-align: center;
            transition: width 0.3s ease-out, border-radius 0.3s ease-out, background 0.3s ease-out ;
            border-top-left-radius: 30px;
            border-top-right-radius: 30px;
            padding: 20px;
        }
        

.animated-img {
    opacity: 0;
    transform: translateY(60px) scale(0.98);
    filter: blur(2px);
    transition: all 1s cubic-bezier(0.22, 1, 0.36, 1); /* Super smooth easing */
    will-change: transform, opacity;
}

.animated-img.visible {
    opacity: 1;
    transform: translateY(0) scale(1);
    filter: blur(0);
}

.hero-section-pic{
   max-height: 350px;
}


.hero-section-pic img{
    height: 100%;
}

        @media (max-width: 1200px) {
.hero-section-pic{
   max-height: 300px;
}

        }


        /* Responsive Adjustment for Mobile */
        @media (max-width: 768px) {
            .hero-banner {
                width: 88%; /* Minimum width for mobile */
            }
        }
    </style>


<div class="main-content">



    <!-- Hero Section -->
    <div class="hero-section ">
        <!-- Background Video -->
        <video autoplay loop muted playsinline>
            <source src="{{ asset('public/assets/img/home-page/exclusive.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
        
        <!-- Banner Overlay -->
        <div class="hero-banner" id="hero-banner">
            <div class="mt-3 mb-3">
            <h1 class="text-dark " data-title="{{ translate('Add to wishlist') }}">{{ translate('Coffee') }}</h1>
             <p class="text-dark" data-title="{{ translate('Add to wishlist') }}">{{ translate('Explore the Wonders of Geisha') }}</p>
            <div>
    <a class="btn bg-none btn-top rounded-pill " href="{{ url($navBaseUrl . '/category/coffee')}}">{{ translate('Explore') }}</a>
        <a class="btn btn-primary rounded-pill my-3 py-2 px-5 openPopup" href="#" data-slug="geisha-carloman-carranza">{{ translate('Buy Now') }}</a>
            </div>
            </div>
            <!--<img src="{{ asset('public/assets/img/hero-16e.png') }}"  alt="Responsive Image" class="img-fluid mt-3">-->
            <div class="hero-section-pic">
            <picture>
    <source media="(max-width: 567px)" srcset="{{ asset('public/assets/img/home-page/emrati-main.png') }}" class="img-fluid">
<!--hero-16e-mob.png-->
    <img src="{{ asset('public/assets/img/home-page/emrati-main.png') }}" alt="Responsive Image" class="img-fluid animated-img scroll-animate">
<!--new-EMIRATI-COFFEE-10.jpg-->

    <!--/hero-16e.png-->
</picture>
</div>
        </div>
    </div>


<!--srcset="image-480w.jpg 480w, image-800w.jpg 800w"-->

      <!-- banner 2 -->
    <div class="banner-2" >
<!--style="background-image: url('{{ asset('public/assets/img/home-page/hero_iphone16_blue.png') }}" class="img-fluid"-->
        <div class="col-12 "> 

            <div class="row justify-content-center text-center py-3">
            <div class="">
              <h1>{{ translate('Limited Edition') }}</h1>
                <!--<h5>Anaerobic Natural</h5>-->
                <p>{{ translate('Explore Our Private Collection') }}</p>
              </div>
                <div>

                <a href="{{ url($navBaseUrl . '/category/limited-edition')}}" class=" btn btn-top rounded-pill    ">{{ translate('Explore') }}</a>

                <a href="{{ url($navBaseUrl . '/category/limited-edition')}}" class="btn btn-primary rounded-pill    ">{{ translate('Buy Now') }}</a>
                                                                                                    
              </div>

              <!--<div class="py-2">-->
              <!--  <h4>Hello Emirati Coffee!</h4>-->
              <!--</div>-->
            </div>
          </div>
          </div>
    <!-- bannner 2 exit -->

    <div class="banner-1" >
        <!--style="background-image: url('{{ asset('public/assets/img/home-page/hero_iphone16pro.png') }}" class="img-fluid"-->
            <div class="container">

      <div class="row justify-content-center py-3">
          
            <div class="col-md-12">
        <h1>{{ translate('Capsules') }}</h1>
<!--    <p class="p-0  w-50">To make the highest quality specialty coffee accessible through competitive pricing, high quality sourcing and vertical integration</p>-->
<!--<div class="d-flex justify-self-end flex-column">-->
    <p>{{ translate('Try Our Mixed Tape Today') }}</p>
    <div>
    <a class="btn btn-top rounded-pill " href="{{ url($navBaseUrl . '/category/capsules')}}">{{ translate('Explore') }}</a>
        <a class="btn btn-primary rounded-pill" href="{{ url($navBaseUrl . '/category/capsules')}}">{{ translate('Buy Now') }}</a>
</div>
</div>
    </div>
    </div>
    </div>
  
  

  
  
      <!-- banner 3 -->
    <div class="banner-3" >
        <!--style="background-image: url('{{ asset('public/assets/img/home-page/hero_apple_watch_series_10.png') }}" class="img-fluid">-->
    <div class="col-12">
    <div class="row justify-content-center text-center py-3">
    <div>
      <h1>
{{ translate('Instant Coffee') }}
    </h1>
    <!--<h5>Anaerobic Natural</h5>-->
    <p>{{ translate('Hello, Specialty Instant Coffee') }}</p>
    </div>

    <div class="mb-3">

      <a class=" btn btn-top rounded-pill "  href="{{ url($navBaseUrl . '/category/Instant-Coffee')}}">{{ translate('Explore') }}</a>



      <a class=" btn btn-primary rounded-pill "  href="{{ url($navBaseUrl . '/category/Instant-Coffee')}}">{{ translate('Buy Now') }}</a>
    </div>

    <!--<div class="py-4">-->
    <!--  <h4>Hello Emirati Coffee!</h4>-->
    <!--</div>-->



    </div>

    </div>
    </div>


    <!-- banner 3 exit -->
  
  




    
    
    <style>

    @media (max-width: 768px){
       .hero-section p,     .banner-1 p,
    .banner-2 p,
.banner-3 p{
    font-size: 19px !important;
}
    }

    .grid-fluid h3{
    font-size:40px;
    font-weight:600 !important;
    line-height: 1.125 !important;
        letter-spacing: .004em !important;
}
    
    
   .hero-section p, .banner-1 p,
    .banner-2 p,
.banner-3 p{
    font-size: 26px;
    line-height: 1.19048 !important;
    font-weight: 400 !important;

} 
    
    
.hero-section h1, .banner-1 h1,  .banner-2 h1, .banner-3 h1  {
    font-size: 53px ;
    line-height: 1.07143 !important;
    font-weight: 600 !important;
    letter-spacing: -.005em !important;
}




    
    @media (min-width: 769px) and (max-width: 1068px) {
  .hero-section h1, .banner-1 h1,  .banner-2 h1, .banner-3 h1{
       font-size: 48px ;
   }
          .hero-section p,
          .banner-1 p,
    .banner-2 p,
.banner-3 p{
    font-size: 24px !important;
    
   }
   
   
   .grid-fluid h3{
    font-size:32px !important;}
    
    .banner p{
    font-size:19px !important;
}
   
   
    }
    
    .btn-top{
    color: #2997ff !important;
    border-color: #2997ff !important;
    }
    .btn-top:hover{
      color: #fff !important;
    background-color: #007bff !important;
   
    }
    
    
    
    @media (max-width: 768px) {
.banner {
    padding:0 !important;
    
    
}

/*.banner .sub-para{*/

/*    display:flex;*/
/*     margin:10px auto;*/
/*}*/


.grid-fluid h3{
font-size: 32px !important;
}

}




    
    
    


    


    
.owl-carousel {
    width:100%;  /* Fixed width */
    margin: 0 auto; /* Center carousel */
    overflow: hidden; /* Ensure slides don’t break layout */
        margin-top: 4px;
}

.owl-carousel .item img {
    width: 100%;          /* Ensure images scale properly */
    height: 667.25979px;        /* Set a fixed height */
    object-fit: cover;    /* Prevent image distortion */
}


    
    
    
    /* Position navigation arrows on both sides, full height */
.owl-nav {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
}

/* Style individual navigation buttons */
.owl-nav button {
    position: absolute;
    top: 0;
    height: 100%; /* Make them take full height */
    width: 120px;  /* Clickable area width */
    border: none;
  opacity:0;
    cursor: pointer;
}

/* Left Arrow */
.owl-nav .owl-prev {
    left: 0;
}

/* Right Arrow */
.owl-nav .owl-next {
    right: 0;
}

    
    .owl-theme .owl-nav [class*=owl-]:hover{
        background: transparent !important;
    }
    
    .owl-dots{
        margin-top:6px !important;
    }

/* Apply blur and fade to all slides */
.owl-carousel .owl-item {
    filter: blur(1px);
    opacity: 0.6;
    transition: all 0.6s ease-in-out;
}

/* Keep the center slide sharp and bright */
.owl-carousel .owl-item.center {
    filter: blur(0);
    opacity: 1;

    z-index: 2;
}

.owl-theme .owl-nav svg{
    height:33px;
    width:33px;
}


    
    @media (max-width: 768px){
.owl-carousel .item img {
    height: 495px !important;        /* Set a fixed height */
}    

        
    }

    
</style>
    
    
    
    
    <!--grid-container-->
    <!--style-->
        <style>

        .grid-fluid {
            margin: 12px;
            display: grid;
            grid-template-columns: repeat(2, 100px);
            grid-template-columns: repeat(2,minmax(100px, 1fr));
            gap: 12px;
        }
    
        .item-1 {
color: white;
    padding: 20px;
    text-align: center;
    height: 580px;
    background-size: cover;
    background-position: center; 
                        }
    
        .item-2 {
color: white;
    padding: 20px;
    text-align: center;
    height: 580px;
    background-size: cover;
    background-position: center;
        }
    
        .item-3 {
color: white;
    padding: 20px;
    text-align: center;
    height: 580px;
    background-size: cover;
    background-position: center;
        }
    
        .item-4 {
color: white;
    padding: 20px;
    text-align: center;
    height: 580px;
    background-size: cover;
    background-position: center;
        }
    
        .item-5 {
color: white;
    padding: 20px;
    text-align: center;
    height: 580px;
    background-size: cover;
    background-position: center;
        }
    
        .item-6 {
color: white;
    padding: 20px;
    text-align: center;
    height: 580px;
    background-size: cover;
    background-position: center;

        }
        
                .item-7,.item-8 {
color: white;
    padding: 20px;
    text-align: center;
    height: 580px;
    background-size: cover;
    background-position: center;

        }
    


        @media screen and (max-width: 768px) {
            .grid-fluid {
                grid-template-columns: repeat(1, 1fr);
                            margin: 12px 0;
            }
            
            .item-1,.item-2,.item-3,.item-4,.item-5,.item-6{
                height: 530px;
            }
            
            
            
        }
        
        @media (max-width: 567px) {
    .grid-fluid .btn {
        padding: 7px 15px !important;
        font-size: 15px;
        font-weight: 400;
    }
}
    </style>
    <!--style end-->
    
    
    <div class="grid-fluid ">
    <div class="item-1 text-dark" ><div class=" pt-3 " bis_skin_checked="1">
             <!--promo_ipadair_ai.png'-->
             <div class=" " bis_skin_checked="1">
              <h3> {{ translate('Drip Bags') }}</h3>
              </div>
              <p class="sub-para fw-normal">
                  {{ translate(' Taste the World Through Your Cup') }}
                 </p>




                <a class=" btn btn-top  rounded-pill" href="{{ url($navBaseUrl . '/category/drip-bags') }}" bis_skin_checked="1">{{ translate('Explore') }}</a>


                <a class=" btn btn-primary  rounded-pill openPopup" href="#" data-slug="ethiopia-deri-kidame-2" bis_skin_checked="1">{{ translate('Buy Now') }}</a>


          </div>
              </div>
    <div class="item-2 text-dark" >
        <div class=" pt-3 " bis_skin_checked="1">
             <!--promo_apple_fitness.png-->
              <h3 class="">{{ translate('Tea') }}</h3>
            </div>
            <p class="sub-para fw-normal">{{ translate('The Art of Fine Tea') }}
                 </p>
                 <div class="" bis_skin_checked="1">
                     
                     
                     

            <a class="btn btn-top rounded-pill    " href="{{ url($navBaseUrl . '/category/tea') }}" bis_skin_checked="1">{{ translate('Explore') }}</a>

            <a class="btn btn-primary rounded-pill   openPopup  " href="#" data-slug="gozen-organic-matcha-powder" bis_skin_checked="1">{{ translate('Buy Now') }}</a>

          </div>
                 </div>
    <div class="item-3 text-dark">
                <!--promo_apple_watch.png-->
              <div class="" bis_skin_checked="1">
                  
              <h3>{{ translate('Academy') }}</h3>
             <p class="sub-para fw-normal">{{ translate('Get Certified in Coffee Today.') }}
                 </p>

<div class="pt-2" bis_skin_checked="1">
                <a href="{{ url($navBaseUrl . '/academy') }}" class="btn  btn-primary rounded-pill mb-3" bis_skin_checked="1">{{ translate('Sign Up') }}</a>
              

</div>
              </div>
 
    </div>
    <div class="item-4 text-dark">
                <!--promo_apple_card.png'-->
              <!--<h5>{{ translate('Latest News ') }}</h5>-->
              <h3>{{ translate('Travel') }}</h3>

              <p class="sub-para fw-normal">{{ translate('Travel the Coffee World with Emirati Coffee') }}
                 </p>
                <div class="" bis_skin_checked="1">
                <a class=" btn btn-primary  rounded-pill" href="{{ url($navBaseUrl . '/travel') }}" bis_skin_checked="1">{{ translate('Explore') }}</a>
                </div>

    </div>
    <div class="item-5 text-dark">
                <!--promo_iphone_tradein.png-->
              <!--<h6>{{ translate('Event') }}</h6>-->
              <h3>{{ translate('Wholesale') }}</h3>
              <p class="sub-para fw-normal">
                 {{ translate(' Become a Partner Today') }}
                 </p>

                <div class="" bis_skin_checked="1">
                <a class="btn btn-primary  rounded-pill" href="{{ url($navBaseUrl . '/wholesale') }}" bis_skin_checked="1">{{ translate('Explore') }}</a>
                </div>

    </div>
    <div class="item-6 text-dark" >
                <!--promo_macbookpro.png-->
                           <div class="" bis_skin_checked="1">
              <h3>{{ translate('Subscribe') }}</h3>
                <p class="sub-para fw-normal">{{ translate('Never Run Out of Coffee Again') }}
                 </p>

                <a class="btn btn-primary rounded-pill " href="{{ url($navBaseUrl . '/subscription') }}" bis_skin_checked="1">{{ translate('Subscribe Now') }}</a>
                

                </div>

    </div>
    
        <div class="item-7 text-dark" >
                <!--promo_macbookpro.png-->
                           <div class="" bis_skin_checked="1">
              <h3>{{ translate('Event') }}</h3>
                <!--<p class="sub-para fw-normal">Never Run Out of Coffee Again-->
                <!-- </p>-->

                <a class="btn btn-primary rounded-pill " href="{{ url($navBaseUrl . '/event') }}" bis_skin_checked="1">{{ translate('Subscribe Now') }}</a>
                

                </div>

    </div>
        <div class="item-8 text-dark" >
                <!--promo_macbookpro.png-->
                           <div class="" bis_skin_checked="1">
              <h3>{{ translate('About Us') }}</h3>
                <!--<p class="sub-para fw-normal">Never Run Out of Coffee Again-->
                <!-- </p>-->

                <a class="btn btn-primary rounded-pill " href="{{ url($navBaseUrl . '/about') }}" bis_skin_checked="1">{{ translate('Explore') }}</a>
                

                </div>

    </div>


</div>

</div>
























             <!--srcset="public/assets/img/home-page/slide-apple-3-small.png 600w,-->
             <!--        public/assets/img/home-page/slide-apple-3-medium.png 1000w,-->
             <!--        public/assets/img/home-page/slide-apple-3.png 1600w"-->
             <!--sizes="(max-width: 600px) 600px, -->
             <!--       (max-width: 1000px) 1000px, -->
             <!--       1600px" -->

<div class="owl-carousel owl-theme">
    <div class="item">
        <img src="{{ asset('/assets/img/home-page/Colombia2016_5.jpg') }}" 

             alt="Slide 1">
    </div>
    <div class="item">
        <img src="{{ asset('/assets/img/home-page/image006.jpg') }}" 

             alt="Slide 2">
    </div>
    <div class="item">
        <img src="{{ asset('/assets/img/home-page/IMG_0794.jpg') }}" 

             alt="Slide 3">
    </div>
    <div class="item">
        <img src="{{ asset('/assets/img/home-page/IMG_1362.jpg') }}" 

             alt="Slide 4">
    </div>
    <!--<div class="item">-->
    <!--    <img src="public/assets/img/home-page/slide-apple-5.png" -->

    <!--         alt="Slide 5">-->
    <!--</div>-->
</div>



     


<style>
.owl-carousel  .owl-stage {
    transition-timing-function: linear !important;
}

.lower-banner   .item img {
    width: 100%;
    height: 240px !important;
    object-fit: cover;
}

.lower-banner   .owl-carousel .owl-item:not(.active) {
     filter: blur(0) !important; 
     opacity: 1 !important; 
     transition:none !important; 
}

.owl-theme .owl-nav{
    margin-top:0 !important;
}

.instagram-carousel {
    width: 100%;
    overflow: hidden;
    position: relative;
    padding: 10px 0;
}

.instagram-marquee-track {
    display: flex;
    width: max-content;
    animation: instagram-scroll 60s linear infinite;
    gap: 16px;
}

.instagram-carousel:hover .instagram-marquee-track {
    animation-play-state: paused;
}

.instagram-card {
    position: relative;
    overflow: hidden;
    background: #f7f7f7;
    height: 240px;
    width: 420px;
    flex-shrink: 0;
    cursor: pointer;
}

.instagram-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
    display: block;
}

.instagram-card:hover img {
    transform: scale(1.08);
}

.instagram-card::before {
    content: '';
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(0,0,0,0) 0%, rgba(0,0,0,0.3) 50%, rgba(0,0,0,0.85) 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
    z-index: 1;
    pointer-events: none;
}

.instagram-card:hover::before {
    opacity: 1;
}

.instagram-card__meta {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 20px 18px;
    color: #fff;
    opacity: 0;
    transform: translateY(10px);
    transition: all 0.4s ease;
    font-size: 0.9rem;
    line-height: 1.5;
    z-index: 2;
    pointer-events: none;
}

.instagram-card:hover .instagram-card__meta {
    opacity: 1;
    transform: translateY(0);
}

.instagram-card__meta > div {
    max-height: 3em;
    overflow: hidden;
    text-overflow: ellipsis;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    margin-bottom: 8px;
    font-weight: 500;
}

.instagram-card__icon {
    position: absolute;
    top: 14px;
    right: 14px;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(8px);
    color: #fff;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.05em;
    z-index: 3;
    transition: all 0.3s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.3);
}

.instagram-card:hover .instagram-card__icon {
    background: rgba(0,0,0,0.85);
    transform: scale(1.1);
}

.instagram-card__time {
    display: inline-block;
    margin-top: 8px;
    font-size: 0.75rem;
    opacity: 0.8;
}

.instagram-status {
    background: #f7f7f7;
    border-radius: 12px;
}

/* Instagram Modal Styles */
.instagram-modal {
    display: none;
    position: fixed;
    z-index: 9999;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.95);
    backdrop-filter: blur(10px);
    overflow: auto;
    animation: fadeIn 0.3s ease;
}

.instagram-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.instagram-modal-content {
    position: relative;
    max-width: 90%;
    max-height: 90vh;
    margin: auto;
    animation: slideUp 0.3s ease;
}

@keyframes slideUp {
    from { transform: translateY(30px); opacity: 0; }
    to { transform: translateY(0); opacity: 1; }
}

.instagram-modal-content img,
.instagram-modal-content video {
    max-width: 100%;
    max-height: 90vh;
    width: auto;
    height: auto;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.5);
}

.instagram-modal-content video {
    display: block;
}

.instagram-modal-close {
    position: absolute;
    top: -40px;
    right: 0;
    color: #fff;
    font-size: 40px;
    font-weight: 300;
    cursor: pointer;
    line-height: 1;
    transition: transform 0.2s ease;
    z-index: 10000;
}

.instagram-modal-close:hover {
    transform: scale(1.2);
}

.instagram-modal-info {
    position: absolute;
    bottom: -60px;
    left: 0;
    right: 0;
    color: #fff;
    text-align: center;
    padding: 15px;
    background: rgba(0,0,0,0.7);
    border-radius: 8px;
    backdrop-filter: blur(10px);
}

.instagram-modal-info .caption {
    font-size: 0.95rem;
    line-height: 1.5;
    margin-bottom: 8px;
}

.instagram-modal-info .timestamp {
    font-size: 0.85rem;
    opacity: 0.8;
}

@media (max-width: 768px) {
    .instagram-modal-content {
        max-width: 95%;
    }
    
    .instagram-modal-close {
        top: -35px;
        font-size: 35px;
    }
    
    .instagram-modal-info {
        bottom: -50px;
        padding: 12px;
    }
}

@keyframes instagram-scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(-50%);
    }
}

@media only screen and (max-width: 767px){
   .owl-nav button {
      opacity:1;
      width:40px;
   }
   
   .instagram-marquee-track {
       gap: 12px;
   }
}      

@media only screen and (max-width: 768px){
    .instagram-card {
        height: 200px;
        width: 320px;
    }
    
    .instagram-card__meta {
        padding: 16px 14px;
        font-size: 0.85rem;
    }
}

@media only screen and (max-width: 576px){
    .instagram-card {
        height: 180px;
        width: 280px;
    }
    
    .instagram-marquee-track {
        gap: 8px;
    }
    
    .instagram-card__meta {
        padding: 12px 12px;
        font-size: 0.8rem;
    }
}


</style>



    <div class="instagram-feed lower-banner pt-5 ">


        <div class="container-fluid">
            <div class="row">
                <div class="col-12 d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4">
                    <div>
                        <h3 class="mb-1">Instagram</h3>
                        <a class="text-muted" target="_blank" rel="noopener" href="https://instagram.com/emiraticoffee.ae">@emiraticoffee.ae</a>
                    </div>

                </div>
                <div class="col-12">
                    <div id="instagram-feed-status" class="instagram-status text-center text-muted small py-4 d-none"></div>
                    <div id="instagram-feed-carousel" class="instagram-carousel">
                        <div id="instagram-marquee-track" class="instagram-marquee-track"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="text-center py-3">
    <a class="btn btn-primary rounded-pill mt-3 mt-md-0" target="_blank" rel="noopener"
    href="{{ url('https://www.instagram.com/emiraticoffee.ae/') }}">{{ translate('FOLLOW US') }}</a>
    </div>
    <!-- Instagram Modal -->
    <div id="instagram-modal" class="instagram-modal">
        <span class="instagram-modal-close">&times;</span>
        <div class="instagram-modal-content">
            <!-- Content will be inserted here -->
        </div>
        <div class="instagram-modal-info">
            <div class="caption"></div>
            <div class="timestamp"></div>
        </div>
    </div>
            </div>
        </div>
    </div>
    
    
<!-- jQuery (Required for Owl Carousel) -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Owl Carousel JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>

<script>
  $(document).ready(function () {
      var owl = $(".owl-carousel");

      owl.owlCarousel({
          loop: true,
           center: true,
           rtl: true,
          margin: 10,
          dots: true,
           dotsEach: true, // Ensures dots work correctly
          autoplay: true,
          autoplayTimeout: 3000,
          autoplaySpeed: 1500,
          smartSpeed: 1500,
          autoplayHoverPause: true,
          stagePadding: 150,
                    nav: true,
            navText: [
    '<svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#1f1f1f"><path d="M400-80 0-480l400-400 61 61.67L122.67-480 461-141.67 400-80Z"/></svg>', 
    '<svg xmlns="http://www.w3.org/2000/svg" height="40px" viewBox="0 -960 960 960" width="40px" fill="#1f1f1f"><path d="m309.67-81.33-61-61.67L587-481.33 248.67-819.67l61-61.66 400 400-400 400Z"/></svg>'
  ],

          responsive: {
              0: { items: 1, stagePadding: 50 },  
              600: { items: 1, stagePadding: 100 },  
              1000: { items: 1, stagePadding: 150 }  
          }
      });
  });



// $('.owl-carousel').owlCarousel({
//     stagePadding: 50,
//     loop:true,
//     margin:10,
//     nav:true,
//     responsive:{
//         0:{
//             items:1
//         },
//         600:{
//             items:3
//         },
//         1000:{
//             items:5
//         }
//     }
// })

  // Pause animation on hover
  document.addEventListener("DOMContentLoaded", function () {
      const slider = document.querySelector('.slider-5 .reel');
      if (slider) {
          slider.addEventListener('mouseenter', () => {
              slider.style.animationPlayState = 'paused';
          });

          slider.addEventListener('mouseleave', () => {
              slider.style.animationPlayState = 'running';
          });
      }
  });

  // Scroll Event for Hero Banner
  window.addEventListener("scroll", function () {
      let banner = document.getElementById("hero-banner");
      if (!banner) return;

      let scrollTop = window.scrollY;
      let maxScroll = 300;
      let minWidth = window.innerWidth <= 768 ? 88 : 75;
      let maxWidth = 100;
      let minRadius = 30;
      let maxRadius = 0;
      let minBgOpacity = 0.8;
      let maxBgOpacity = 1;

      scrollTop = Math.min(scrollTop, maxScroll);
      let progress = scrollTop / maxScroll;

      let newWidth = minWidth + progress * (maxWidth - minWidth);
      let newRadius = minRadius - progress * minRadius;
      let newBgOpacity = minBgOpacity + progress * (maxBgOpacity - minBgOpacity);
      let newBgColor = `rgba(245, 245, 247, ${newBgOpacity})`;

      banner.style.width = `${newWidth}%`;
      banner.style.borderTopLeftRadius = `${newRadius}px`;
      banner.style.borderTopRightRadius = `${newRadius}px`;
      banner.style.background = newBgColor;
  });
  
  
document.addEventListener('DOMContentLoaded', function () {
    let lastScrollY = window.scrollY;

    const observer = new IntersectionObserver((entries) => {
        const currentScrollY = window.scrollY;
        const scrollingDown = currentScrollY > lastScrollY;
        lastScrollY = currentScrollY;

        entries.forEach(entry => {
            if (entry.isIntersecting && scrollingDown) {
                entry.target.classList.add('visible');
                // Optional: unobserve so it doesn't keep triggering
                // observer.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.3
    });

    document.querySelectorAll('.scroll-animate').forEach(el => {
        observer.observe(el);
    });
});

// Global formatDate function
function formatDate(isoString) {
    try {
        const date = new Date(isoString);
        return date.toLocaleDateString(undefined, {
            month: 'short',
            day: 'numeric',
            year: 'numeric',
        });
    } catch (e) {
        return '';
    }
}

(function () {
    const carouselEl = document.getElementById('instagram-feed-carousel');
    const statusEl = document.getElementById('instagram-feed-status');
    if (!carouselEl) {
        return;
    }
    
    const trackEl = document.getElementById('instagram-marquee-track');
    if (!trackEl) {
        return;
    }

    const endpoint = "{{ route('instagram.feed') }}";

    const setStatus = (message, type = 'info') => {
        if (!statusEl) return;
        statusEl.textContent = message;
        statusEl.classList.remove('d-none', 'text-danger', 'text-muted');
        statusEl.classList.add(type === 'error' ? 'text-danger' : 'text-muted');
    };

    const clearStatus = () => {
        if (!statusEl) return;
        statusEl.textContent = '';
        statusEl.classList.add('d-none');
    };

    const iconForType = (type) => {
        if (type === 'VIDEO') return 'VID';
        if (type === 'CAROUSEL_ALBUM') return 'SET';
        return 'PIC';
    };

    setStatus('{{ translate('Loading Instagram feed...') }}');

    fetch(endpoint, {
        credentials: 'same-origin',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .then(async (response) => {
            if (!response.ok) {
                const errorText = await response.text();
                console.error('Instagram feed HTTP error:', response.status, errorText);
                throw new Error(`HTTP ${response.status}: ${errorText}`);
            }
            const payload = await response.json().catch((e) => {
                console.error('Instagram feed JSON parse error:', e);
                return { error: 'Invalid response from server' };
            });
            if (payload.error) {
                console.error('Instagram feed API error:', payload.error);
                throw new Error(payload.error || 'Failed to load Instagram feed');
            }
            return payload.data || [];
        })
        .then((items) => {
            clearStatus();

            if (!items.length) {
                setStatus('{{ translate('No Instagram posts available at the moment.') }}');
                return;
            }

            // Create cards (no duplication - each item appears once)
            items.forEach((item) => {
                const card = document.createElement('div');
                card.className = 'instagram-card d-block';
                card.setAttribute('data-media-type', item.media_type);
                card.setAttribute('data-media-url', item.media_url);
                card.setAttribute('data-thumbnail-url', item.thumbnail_url || '');
                card.setAttribute('data-caption', item.caption || '');
                card.setAttribute('data-timestamp', item.timestamp || '');
                card.setAttribute('data-permalink', item.permalink || '');

                const img = document.createElement('img');
                // Use thumbnail_url for videos, media_url for images
                img.src = (item.media_type === 'VIDEO' && item.thumbnail_url) ? item.thumbnail_url : item.media_url;
                img.alt = item.caption || 'Instagram post';
                img.loading = 'lazy';
                card.appendChild(img);

                const icon = document.createElement('span');
                icon.className = 'instagram-card__icon';
                icon.textContent = iconForType(item.media_type);
                card.appendChild(icon);

                const meta = document.createElement('div');
                meta.className = 'instagram-card__meta';
                meta.innerHTML = `
                    <div>${item.caption || ''}</div>
                    <span class="instagram-card__time">${formatDate(item.timestamp)}</span>
                `;
                card.appendChild(meta);

                // Store item data in card for later use
                card._itemData = item;

                trackEl.appendChild(card);
            });
            
            // Use event delegation for click handling (works with duplicated cards)
            trackEl.addEventListener('click', function(e) {
                const card = e.target.closest('.instagram-card');
                if (card) {
                    e.preventDefault();
                    // Get item data from data attributes
                    const itemData = {
                        media_type: card.getAttribute('data-media-type'),
                        media_url: card.getAttribute('data-media-url'),
                        thumbnail_url: card.getAttribute('data-thumbnail-url'),
                        caption: card.getAttribute('data-caption'),
                        timestamp: card.getAttribute('data-timestamp'),
                        permalink: card.getAttribute('data-permalink')
                    };
                    openInstagramModal(itemData);
                }
            });
            
            // Duplicate all cards for seamless marquee loop
            const cards = Array.from(trackEl.children);
            cards.forEach(card => {
                const clonedCard = card.cloneNode(true);
                trackEl.appendChild(clonedCard);
            });
        })
        .catch((error) => {
            console.error('Instagram feed error:', error);
            setStatus('{{ translate('Unable to load Instagram feed right now. Please try again later.') }}', 'error');
        });
})();

// Instagram Modal Functions
function openInstagramModal(item) {
    const modal = document.getElementById('instagram-modal');
    const modalContent = modal.querySelector('.instagram-modal-content');
    const modalInfo = modal.querySelector('.instagram-modal-info');
    const captionEl = modalInfo.querySelector('.caption');
    const timestampEl = modalInfo.querySelector('.timestamp');
    
    // Clear previous content
    modalContent.innerHTML = '';
    
    // Create media element
    if (item.media_type === 'VIDEO' && item.media_url) {
        const video = document.createElement('video');
        video.src = item.media_url;
        video.controls = true;
        video.autoplay = true;
        video.style.width = '100%';
        video.style.height = 'auto';
        modalContent.appendChild(video);
    } else {
        const img = document.createElement('img');
        img.src = item.media_url;
        img.alt = item.caption || 'Instagram post';
        modalContent.appendChild(img);
    }
    
    // Set caption and timestamp
    captionEl.textContent = item.caption || '';
    timestampEl.textContent = formatDate(item.timestamp);
    
    // Show modal
    modal.classList.add('active');
    document.body.style.overflow = 'hidden';
}

function closeInstagramModal() {
    const modal = document.getElementById('instagram-modal');
    const modalContent = modal.querySelector('.instagram-modal-content');
    
    modal.classList.remove('active');
    document.body.style.overflow = '';
    
    // Stop video if playing
    const video = modalContent.querySelector('video');
    if (video) {
        video.pause();
        video.src = '';
    }
}

// Close modal on close button click
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('instagram-modal');
    if (modal) {
        const closeBtn = modal.querySelector('.instagram-modal-close');
        if (closeBtn) {
            closeBtn.addEventListener('click', closeInstagramModal);
        }
        
        // Close modal on background click
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeInstagramModal();
            }
        });
        
        // Close modal on ESC key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && modal.classList.contains('active')) {
                closeInstagramModal();
            }
        });
    }
});
</script>


@endsection

