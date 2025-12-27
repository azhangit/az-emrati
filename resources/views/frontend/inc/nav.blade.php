    
     @include('frontend.partials.search-modal')
    
@php
    $navBaseUrl = rtrim(config('app.nav_base_url', env('NAV_BASE_URL', 'http://localhost:8000')), '/');
@endphp

    <!-- Top Bar Banner -->
    {{-- @php
        $topbar_banner = get_setting('topbar_banner');
        $topbar_banner_medium = get_setting('topbar_banner_medium');
        $topbar_banner_small = get_setting('topbar_banner_small');
        $topbar_banner_asset = uploaded_asset($topbar_banner);
    @endphp
    @if ($topbar_banner != null)
        <div class="position-relative top-banner removable-session z-1035 d-none" data-key="top-banner"
            data-value="removed">
            <a href="{{ get_setting('topbar_banner_link') }}" class="d-block text-reset h-40px h-lg-60px">
                <!-- For Large device -->
                <img src="{{ $topbar_banner_asset }}" class="d-none d-xl-block img-fit h-100" alt="{{ translate('topbar_banner') }}">
                <!-- For Medium device -->
                <img src="{{ $topbar_banner_medium != null ? uploaded_asset($topbar_banner_medium) : $topbar_banner_asset }}"
                    class="d-none d-md-block d-xl-none img-fit h-100" alt="{{ translate('topbar_banner') }}">
                <!-- For Small device -->
                <img src="{{ $topbar_banner_small != null ? uploaded_asset($topbar_banner_small) : $topbar_banner_asset }}"
                    class="d-md-none img-fit h-100" alt="{{ translate('topbar_banner') }}">
            </a>
            <button class="btn text-white h-100 absolute-top-right set-session" data-key="top-banner"
                data-value="removed" data-toggle="remove-parent" data-parent=".top-banner">
                <i class="la la-close la-2x"></i>
            </button>
        </div>
    @endif --}}

    <!-- Top Bar -->
    
    
    <link rel="stylesheet" href="https://emiraticoffee.ae/public/assets/css/custom-style.css">
    
    <div class="top-navbar d-flex align-items-center ">
        <div class="container-fluid">
            <div class="row ">
                <div class="col-lg-12 col ">
                    <ul class="list-inline d-flex justify-content-lg-end mb-0">
                         <!--Language switcher -->
                        @if (get_setting('show_language_switcher') == 'on')
                            <li class="list-inline-item dropdown d-block ml-auto ml-lg-0 mr-4" id="lang-change">

                                <a href="javascript:void(0)" class="dropdown-toggle fs-12 py-2"
                                    data-toggle="dropdown" data-display="static">
                                    <span class="">{{ $system_language->name }}</span>
                                </a>
                                <ul class="dropdown-menu  dropdown-menu-right" id="lang-switch-ul">
                                    @foreach (get_all_active_language() as $key => $language)
                                        <li>
                                            <a href="javascript:void(0)" data-flag="{{ $language->code }}"
                                                class="dropdown-item @if ($system_language->code == $language->code) active @endif">
                                                <img src="{{ static_asset('assets/img/placeholder.jpg') }}"
                                                    data-src="{{ static_asset('assets/img/flags/' . $language->code . '.png') }}"
                                                    class="mr-1 lazyload" alt="{{ $language->name }}" height="11">
                                                <span class="language">{{ $language->name }}</span>
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif

                      

     

                


  


                         <!--Currency Switcher -->
                        @if (get_setting('show_currency_switcher') == 'on')
                            <li class="list-inline-item dropdown  ml-lg-0 mr-0" id="currency-change">
                                @php
                                    $system_currency = get_system_currency();
                                @endphp

                                <a href="javascript:void(0)" class="dropdown-toggle fs-12 py-2"
                                    data-toggle="dropdown" data-display="static">
                                    {{ $system_currency->name }}
                                </a>
                                <ul class="dropdown-menu " id="currency-switch-ul">
                                    @foreach (get_all_active_currency() as $key => $currency)
                                        <li>
                                            <a class="dropdown-item @if ($system_currency->code == $currency->code) active @endif"
                                                href="javascript:void(0)"
                                                data-currency="{{ $currency->code }}">{{ $currency->name }}
                                                ({{ $currency->symbol }})</a>
                                        </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif

                    </ul>
                </div>
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                
                <!--d-xl-block-->
<div class="d-none col-6  ml-auto mr-0 d-none">
                        @auth
                            <span
                                class="d-flex align-items-center nav-user-info   z-3 justify-content-end @if (isAdmin()) ml-5 @endif"
                                id="nav-user-info">
                                 Image 
                                <span
                                    class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                                    @if ($user->avatar_original != null)
                                        <img src="{{ $user_avatar }}"
                                            class="img-fit h-100" alt="{{ translate('avatar') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                    @else
                                        <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image" alt="{{ translate('avatar') }}"
                                            onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                                    @endif
                                </span>
                                 Name 
                                <h4 class="h5 fs-14 fw-700 text-dark ml-2 mb-0">{{ $user->name }}</h4>
                                
                            </span>
                            <div class="hover-user-top-menu  position-absolute right-0">
                                <div class="container">
                                    <div class="position-static float-right">
                                        <div class="aiz-user-top-menu bg-white rounded-0 border-top shadow-sm" style="width:220px;">
                                            <ul class="list-unstyled no-scrollbar mb-0 text-left">
                                                @if (isAdmin())
                                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                                        <a href="{{ route('admin.dashboard') }}"
                                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 16 16">
                                                                <path id="Path_2916" data-name="Path 2916"
                                                                    d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                                    fill="#b5b5c0" />
                                                            </svg>
                                                            <span
                                                                class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                                        </a>
                                                    </li>
                                                @else
                                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                                        <a href="{{ route('dashboard') }}"
                                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 16 16">
                                                                <path id="Path_2916" data-name="Path 2916"
                                                                    d="M15.3,5.4,9.561.481A2,2,0,0,0,8.26,0H7.74a2,2,0,0,0-1.3.481L.7,5.4A2,2,0,0,0,0,6.92V14a2,2,0,0,0,2,2H14a2,2,0,0,0,2-2V6.92A2,2,0,0,0,15.3,5.4M10,15H6V9A1,1,0,0,1,7,8H9a1,1,0,0,1,1,1Zm5-1a1,1,0,0,1-1,1H11V9A2,2,0,0,0,9,7H7A2,2,0,0,0,5,9v6H2a1,1,0,0,1-1-1V6.92a1,1,0,0,1,.349-.76l5.74-4.92A1,1,0,0,1,7.74,1h.52a1,1,0,0,1,.651.24l5.74,4.92A1,1,0,0,1,15,6.92Z"
                                                                    fill="#b5b5c0" />
                                                            </svg>
                                                            <span
                                                                class="user-top-menu-name has-transition ml-3">{{ translate('Dashboard') }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                
                                                @if (isCustomer())
                                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                                        <a href="{{ route('purchase_history.index') }}"
                                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                viewBox="0 0 16 16">
                                                                <g id="Group_25261" data-name="Group 25261"
                                                                    transform="translate(-27.466 -542.963)">
                                                                    <path id="Path_2953" data-name="Path 2953"
                                                                        d="M14.5,5.963h-4a1.5,1.5,0,0,0,0,3h4a1.5,1.5,0,0,0,0-3m0,2h-4a.5.5,0,0,1,0-1h4a.5.5,0,0,1,0,1"
                                                                        transform="translate(22.966 537)" fill="#b5b5bf" />
                                                                    <path id="Path_2954" data-name="Path 2954"
                                                                        d="M12.991,8.963a.5.5,0,0,1,0-1H13.5a2.5,2.5,0,0,1,2.5,2.5v10a2.5,2.5,0,0,1-2.5,2.5H2.5a2.5,2.5,0,0,1-2.5-2.5v-10a2.5,2.5,0,0,1,2.5-2.5h.509a.5.5,0,0,1,0,1H2.5a1.5,1.5,0,0,0-1.5,1.5v10a1.5,1.5,0,0,0,1.5,1.5h11a1.5,1.5,0,0,0,1.5-1.5v-10a1.5,1.5,0,0,0-1.5-1.5Z"
                                                                        transform="translate(27.466 536)" fill="#b5b5bf" />
                                                                    <path id="Path_2955" data-name="Path 2955"
                                                                        d="M7.5,15.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                                        transform="translate(23.966 532)" fill="#b5b5bf" />
                                                                    <path id="Path_2956" data-name="Path 2956"
                                                                        d="M7.5,21.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                                        transform="translate(23.966 529)" fill="#b5b5bf" />
                                                                    <path id="Path_2957" data-name="Path 2957"
                                                                        d="M7.5,27.963h1a.5.5,0,0,1,.5.5v1a.5.5,0,0,1-.5.5h-1a.5.5,0,0,1-.5-.5v-1a.5.5,0,0,1,.5-.5"
                                                                        transform="translate(23.966 526)" fill="#b5b5bf" />
                                                                    <path id="Path_2958" data-name="Path 2958"
                                                                        d="M13.5,16.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                                        transform="translate(20.966 531.5)" fill="#b5b5bf" />
                                                                    <path id="Path_2959" data-name="Path 2959"
                                                                        d="M13.5,22.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                                        transform="translate(20.966 528.5)" fill="#b5b5bf" />
                                                                    <path id="Path_2960" data-name="Path 2960"
                                                                        d="M13.5,28.963h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                                        transform="translate(20.966 525.5)" fill="#b5b5bf" />
                                                                </g>
                                                            </svg>
                                                            <span
                                                                class="user-top-menu-name has-transition ml-3">{{ translate('Purchase History') }}</span>
                                                        </a>
                                                    </li>
                                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                                        <a href="{{ route('digital_purchase_history.index') }}"
                                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16.001" height="16"
                                                                viewBox="0 0 16.001 16">
                                                                <g id="Group_25262" data-name="Group 25262"
                                                                    transform="translate(-1388.154 -562.604)">
                                                                    <path id="Path_2963" data-name="Path 2963"
                                                                        d="M77.864,98.69V92.1a.5.5,0,1,0-1,0V98.69l-1.437-1.437a.5.5,0,0,0-.707.707l1.851,1.852a1,1,0,0,0,.707.293h.172a1,1,0,0,0,.707-.293l1.851-1.852a.5.5,0,0,0-.7-.713Z"
                                                                        transform="translate(1318.79 478.5)" fill="#b5b5bf" />
                                                                    <path id="Path_2964" data-name="Path 2964"
                                                                        d="M67.155,88.6a3,3,0,0,1-.474-5.963q-.009-.089-.015-.179a5.5,5.5,0,0,1,10.977-.718,3.5,3.5,0,0,1-.989,6.859h-1.5a.5.5,0,0,1,0-1l1.5,0a2.5,2.5,0,0,0,.417-4.967.5.5,0,0,1-.417-.5,4.5,4.5,0,1,0-8.908.866.512.512,0,0,1,.009.121.5.5,0,0,1-.52.479,2,2,0,1,0-.162,4l.081,0h2a.5.5,0,0,1,0,1Z"
                                                                        transform="translate(1324 486)" fill="#b5b5bf" />
                                                                </g>
                                                            </svg>
                                                            <span
                                                                class="user-top-menu-name has-transition ml-3">{{ translate('Downloads') }}</span>
                                                        </a>
                                                    </li>
                                                    @if (get_setting('conversation_system') == 1)
                                                        <li class="user-top-nav-element border border-top-0" data-id="1">
                                                            <a href="{{ route('conversations.index') }}"
                                                                class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                                                    viewBox="0 0 16 16">
                                                                    <g id="Group_25263" data-name="Group 25263"
                                                                        transform="translate(1053.151 256.688)">
                                                                        <path id="Path_3012" data-name="Path 3012"
                                                                            d="M134.849,88.312h-8a2,2,0,0,0-2,2v5a2,2,0,0,0,2,2v3l2.4-3h5.6a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2m1,7a1,1,0,0,1-1,1h-8a1,1,0,0,1-1-1v-5a1,1,0,0,1,1-1h8a1,1,0,0,1,1,1Z"
                                                                            transform="translate(-1178 -341)" fill="#b5b5bf" />
                                                                        <path id="Path_3013" data-name="Path 3013"
                                                                            d="M134.849,81.312h8a1,1,0,0,1,1,1v5a1,1,0,0,1-1,1h-.5a.5.5,0,0,0,0,1h.5a2,2,0,0,0,2-2v-5a2,2,0,0,0-2-2h-8a2,2,0,0,0-2,2v.5a.5.5,0,0,0,1,0v-.5a1,1,0,0,1,1-1"
                                                                            transform="translate(-1182 -337)" fill="#b5b5bf" />
                                                                        <path id="Path_3014" data-name="Path 3014"
                                                                            d="M131.349,93.312h5a.5.5,0,0,1,0,1h-5a.5.5,0,0,1,0-1"
                                                                            transform="translate(-1181 -343.5)" fill="#b5b5bf" />
                                                                        <path id="Path_3015" data-name="Path 3015"
                                                                            d="M131.349,99.312h5a.5.5,0,1,1,0,1h-5a.5.5,0,1,1,0-1"
                                                                            transform="translate(-1181 -346.5)" fill="#b5b5bf" />
                                                                    </g>
                                                                </svg>
                                                                <span
                                                                    class="user-top-menu-name has-transition ml-3">{{ translate('Conversations') }}</span>
                                                            </a>
                                                        </li>
                                                    @endif
                
                                                    @if (get_setting('wallet_system') == 1)
                                                        <li class="user-top-nav-element border border-top-0" data-id="1">
                                                            <a href="{{ route('wallet.index') }}"
                                                                class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                                    xmlns:xlink="http://www.w3.org/1999/xlink" width="16"
                                                                    height="16" viewBox="0 0 16 16">
                                                                    <defs>
                                                                        <clipPath id="clip-path1">
                                                                            <rect id="Rectangle_1386" data-name="Rectangle 1386"
                                                                                width="16" height="16" fill="#b5b5bf" />
                                                                        </clipPath>
                                                                    </defs>
                                                                    <g id="Group_8102" data-name="Group 8102"
                                                                        clip-path="url(#clip-path1)">
                                                                        <path id="Path_2936" data-name="Path 2936"
                                                                            d="M13.5,4H13V2.5A2.5,2.5,0,0,0,10.5,0h-8A2.5,2.5,0,0,0,0,2.5v11A2.5,2.5,0,0,0,2.5,16h11A2.5,2.5,0,0,0,16,13.5v-7A2.5,2.5,0,0,0,13.5,4M2.5,1h8A1.5,1.5,0,0,1,12,2.5V4H2.5a1.5,1.5,0,0,1,0-3M15,11H10a1,1,0,0,1,0-2h5Zm0-3H10a2,2,0,0,0,0,4h5v1.5A1.5,1.5,0,0,1,13.5,15H2.5A1.5,1.5,0,0,1,1,13.5v-9A2.5,2.5,0,0,0,2.5,5h11A1.5,1.5,0,0,1,15,6.5Z"
                                                                            fill="#b5b5bf" />
                                                                    </g>
                                                                </svg>
                                                                <span
                                                                    class="user-top-menu-name has-transition ml-3">{{ translate('My Wallet') }}</span>
                                                            </a>
                                                        </li>
                                                    @endif
                                                    <li class="user-top-nav-element border border-top-0" data-id="1">
                                                        <a href="{{ route('support_ticket.index') }}"
                                                            class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16.001"
                                                                viewBox="0 0 16 16.001">
                                                                <g id="Group_25259" data-name="Group 25259"
                                                                    transform="translate(-316 -1066)">
                                                                    <path id="Subtraction_184" data-name="Subtraction 184"
                                                                        d="M16427.109,902H16420a8.015,8.015,0,1,1,8-8,8.278,8.278,0,0,1-1.422,4.535l1.244,2.132a.81.81,0,0,1,0,.891A.791.791,0,0,1,16427.109,902ZM16420,887a7,7,0,1,0,0,14h6.283c.275,0,.414,0,.549-.111s-.209-.574-.34-.748l0,0-.018-.022-1.064-1.6A6.829,6.829,0,0,0,16427,894a6.964,6.964,0,0,0-7-7Z"
                                                                        transform="translate(-16096 180)" fill="#b5b5bf" />
                                                                    <path id="Union_12" data-name="Union 12"
                                                                        d="M16414,895a1,1,0,1,1,1,1A1,1,0,0,1,16414,895Zm.5-2.5V891h.5a2,2,0,1,0-2-2h-1a3,3,0,1,1,3.5,2.958v.54a.5.5,0,1,1-1,0Zm-2.5-3.5h1a.5.5,0,1,1-1,0Z"
                                                                        transform="translate(-16090.998 183.001)" fill="#b5b5bf" />
                                                                </g>
                                                            </svg>
                                                            <span
                                                                class="user-top-menu-name has-transition ml-3">{{ translate('Support Ticket') }}</span>
                                                        </a>
                                                    </li>
                                                @endif
                                                <li class="user-top-nav-element border border-top-0" data-id="1">
                                                    <a href="{{ route('logout') }}"
                                                        class="text-truncate text-dark px-4 fs-14 d-flex align-items-center hov-column-gap-1">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="15.999"
                                                            viewBox="0 0 16 15.999">
                                                            <g id="Group_25503" data-name="Group 25503"
                                                                transform="translate(-24.002 -377)">
                                                                <g id="Group_25265" data-name="Group 25265"
                                                                    transform="translate(-216.534 -160)">
                                                                    <path id="Subtraction_192" data-name="Subtraction 192"
                                                                        d="M12052.535,2920a8,8,0,0,1-4.569-14.567l.721.72a7,7,0,1,0,7.7,0l.721-.72a8,8,0,0,1-4.567,14.567Z"
                                                                        transform="translate(-11803.999 -2367)" fill="#d43533" />
                                                                </g>
                                                                <rect id="Rectangle_19022" data-name="Rectangle 19022" width="1"
                                                                    height="8" rx="0.5" transform="translate(31.5 377)"
                                                                    fill="#d43533" />
                                                            </g>
                                                        </svg>
                                                        <span
                                                            class="user-top-menu-name text-primary has-transition ml-3">{{ translate('Logout') }}</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                </div>
                            </div>
                        @else
                            Login & Registration 
                            <span class="d-flex align-items-center justify-content-end nav-user-info ml-3">
                                 Image 
                                <span
                                    class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012"
                                        viewBox="0 0 19.902 20.012">
                                        <path id="fe2df171891038b33e9624c27e96e367"
                                            d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                                            transform="translate(-2.064 -1.995)" fill="#91919b" />
                                    </svg>
                                </span>
                                <a href="{{ route('user.login') }}"
                                    class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                                <a href="{{ route('user.registration') }}"
                                    class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a>
                            </span>
                        @endauth
                    </div>
                <div class="col-6 text-right d-none d-lg-block">
                    <ul class="list-inline mb-0 h-100 d-flex justify-content-end align-items-center">
                        @if (get_setting('vendor_system_activation') == 1)
                             Become a Seller 
                            <li class="list-inline-item mr-0 pl-0 py-2">
                                <a href="{{ route('shops.create') }}"
                                    class="text-secondary fs-12 pr-3 d-inline-block border-width-2 border-right">{{ translate('Become a Seller !') }}</a>
                            </li>
                             Seller Login 
                            <li class="list-inline-item mr-0 pl-0 py-2">
                                <a href="{{ route('seller.login') }}"
                                    class="text-secondary fs-12 pl-3 d-inline-block">{{ translate('Login to Seller') }}</a>
                            </li>
                        @endif
                        @if (get_setting('helpline_number'))
                             Helpline 
                            <li class="list-inline-item ml-3 pl-3 mr-0 pr-0">
                                <a href="tel:{{ get_setting('helpline_number') }}"
                                    class="text-secondary fs-12 d-inline-block py-2">
                                    <span>{{ translate('Helpline') }}</span>
                                    <span>{{ get_setting('helpline_number') }}</span>
                                </a>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>

    {{-- <header class="@if (get_setting('header_stikcy') == 'on') sticky-top @endif z-1020 bg-white">
        <!-- Search Bar -->
        <div class="position-relative logo-bar-area border-bottom border-md-nonea z-1025">
            <div class="container">
                <div class="d-flex align-items-center">
                    <!-- top menu sidebar button -->
                    <button type="button" class="btn d-lg-none mr-3 mr-sm-4 p-0 active" data-toggle="class-toggle"
                        data-target=".aiz-top-menu-sidebar">
                        <svg id="Component_43_1" data-name="Component 43  1" xmlns="http://www.w3.org/2000/svg"
                            width="16" height="16" viewBox="0 0 16 16">
                            <rect id="Rectangle_19062" data-name="Rectangle 19062" width="16" height="2"
                                transform="translate(0 7)" fill="#919199" />
                            <rect id="Rectangle_19063" data-name="Rectangle 19063" width="16" height="2"
                                fill="#919199" />
                            <rect id="Rectangle_19064" data-name="Rectangle 19064" width="16" height="2"
                                transform="translate(0 14)" fill="#919199" />
                        </svg>

                    </button>
                    <!-- Header Logo -->
                    <div class="col-auto pl-0 pr-3 d-flex align-items-center">
                        <a class="d-block py-20px mr-3 ml-0" href="{{ route('home') }}">
                            @php
                                $header_logo = get_setting('header_logo');
                            @endphp
                            @if ($header_logo != null)
                                <img src="{{ uploaded_asset($header_logo) }}" alt="{{ env('APP_NAME') }}"
                                    class="mw-100 h-30px h-md-40px" height="40">
                            @else
                                <img src="{{ static_asset('assets/img/f-logo-1.png') }}" alt="{{ env('APP_NAME') }}"
                                    class="mw-100 h-30px h-md-40px" height="40">
                            @endif
                        </a>
                    </div>
                    <!-- Search Icon for small device -->
                    <div class="d-lg-none ml-auto mr-0">
                        <a class="p-2 d-block text-reset" href="javascript:void(0);" data-toggle="class-toggle"
                            data-target=".front-header-search">
                            <i class="las la-search la-flip-horizontal la-2x"></i>
                        </a>
                    </div>
                    <!-- Search field -->
                    <div class="flex-grow-1 front-header-search d-flex align-items-center bg-white mx-xl-5">
                        <div class="position-relative flex-grow-1 px-3 px-lg-0">
                            <form action="{{ route('search') }}" method="GET" class="stop-propagation">
                                <div class="d-flex position-relative align-items-center">
                                    <div class="d-lg-none" data-toggle="class-toggle"
                                        data-target=".front-header-search">
                                        <button class="btn px-2" type="button"><i
                                                class="la la-2x la-long-arrow-left"></i></button>
                                    </div>
                                    <div class="search-input-box">
                                        <input type="text"
                                            class="border border-soft-light form-control fs-14 hov-animate-outline"
                                            id="search" name="keyword"
                                            @isset($query)
                                            value="{{ $query }}"
                                        @endisset
                                            placeholder="{{ translate('I am shopping for...') }}" autocomplete="off">

                                        <svg id="Group_723" data-name="Group 723" xmlns="http://www.w3.org/2000/svg"
                                            width="20.001" height="20" viewBox="0 0 20.001 20">
                                            <path id="Path_3090" data-name="Path 3090"
                                                d="M9.847,17.839a7.993,7.993,0,1,1,7.993-7.993A8,8,0,0,1,9.847,17.839Zm0-14.387a6.394,6.394,0,1,0,6.394,6.394A6.4,6.4,0,0,0,9.847,3.453Z"
                                                transform="translate(-1.854 -1.854)" fill="#b5b5bf" />
                                            <path id="Path_3091" data-name="Path 3091"
                                                d="M24.4,25.2a.8.8,0,0,1-.565-.234l-6.15-6.15a.8.8,0,0,1,1.13-1.13l6.15,6.15A.8.8,0,0,1,24.4,25.2Z"
                                                transform="translate(-5.2 -5.2)" fill="#b5b5bf" />
                                        </svg>
                                    </div>
                                </div>
                            </form>
                            <div class="typed-search-box stop-propagation document-click-d-none d-none bg-white rounded shadow-lg position-absolute left-0 top-100 w-100"
                                style="min-height: 200px">
                                <div class="search-preloader absolute-top-center">
                                    <div class="dot-loader">
                                        <div></div>
                                        <div></div>
                                        <div></div>
                                    </div>
                                </div>
                                <div class="search-nothing d-none p-3 text-center fs-16">

                                </div>
                                <div id="search-content" class="text-left">

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Search box -->
                    
                    <div class="d-none d-lg-none ml-3 mr-0">
                        <div class="nav-search-box">
                            <a href="#" class="nav-box-link">
                                <i class="la la-search la-flip-horizontal d-inline-block nav-box-icon"></i>
                            </a>
                        </div>
                    </div>
                    <!-- Compare -->
                    <div class="d-none d-lg-block ml-3 mr-0">
                        <div class="" id="compare">
                            @include('frontend.'.get_setting('homepage_select').'.partials.compare')
                        </div>
                    </div>
                    <!-- Wishlist -->
                    <div class="d-none d-lg-block mr-3" style="margin-left: 36px;">
                        <div class="" id="wishlist">
                            @include('frontend.'.get_setting('homepage_select').'.partials.wishlist')
                        </div>
                    </div>
                    @if (!isAdmin())
                        <!-- Notifications -->
                        <ul class="list-inline mb-0 h-100 d-none d-xl-flex justify-content-end align-items-center">
                            <li class="list-inline-item ml-3 mr-3 pr-3 pl-0 dropdown">
                                <a class="dropdown-toggle no-arrow text-secondary fs-12" data-toggle="dropdown"
                                    href="javascript:void(0);" role="button" aria-haspopup="false"
                                    aria-expanded="false">
                                    <span class="">
                                        <span class="position-relative d-inline-block">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14.668" height="16"
                                                viewBox="0 0 14.668 16">
                                                <path id="_26._Notification" data-name="26. Notification"
                                                    d="M8.333,16A3.34,3.34,0,0,0,11,14.667H5.666A3.34,3.34,0,0,0,8.333,16ZM15.06,9.78a2.457,2.457,0,0,1-.727-1.747V6a6,6,0,1,0-12,0V8.033A2.457,2.457,0,0,1,1.606,9.78,2.083,2.083,0,0,0,3.08,13.333H13.586A2.083,2.083,0,0,0,15.06,9.78Z"
                                                    transform="translate(-0.999)" fill="#91919b" />
                                            </svg>
                                            @if (Auth::check() && count($user->unreadNotifications) > 0)
                                                <span
                                                    class="badge badge-primary badge-inline badge-pill absolute-top-right--10px">{{ count($user->unreadNotifications) }}</span>
                                            @endif
                                        </span>
                                </a>

                                @auth
                                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-lg py-0 rounded-0">
                                        <div class="p-3 bg-light border-bottom">
                                            <h6 class="mb-0">{{ translate('Notifications') }}</h6>
                                        </div>
                                        <div class="px-3 c-scrollbar-light overflow-auto " style="max-height:300px;">
                                            <ul class="list-group list-group-flush">
                                                @forelse($user->unreadNotifications as $notification)
                                                    <li class="list-group-item">
                                                        @if ($notification->type == 'App\Notifications\OrderNotification')
                                                            @if ($user->user_type == 'customer')
                                                                <a href="{{ route('purchase_history.details', encrypt($notification->data['order_id'])) }}"
                                                                    class="text-secondary fs-12">
                                                                    <span class="ml-2">
                                                                        {{ translate('Order code: ') }}
                                                                        {{ $notification->data['order_code'] }}
                                                                        {{ translate('has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                                    </span>
                                                                </a>
                                                            @elseif ($user->user_type == 'seller')
                                                                <a href="{{ route('seller.orders.show', encrypt($notification->data['order_id'])) }}"
                                                                    class="text-secondary fs-12">
                                                                    <span class="ml-2">
                                                                        {{ translate('Order code: ') }}
                                                                        {{ $notification->data['order_code'] }}
                                                                        {{ translate('has been ' . ucfirst(str_replace('_', ' ', $notification->data['status']))) }}
                                                                    </span>
                                                                </a>
                                                            @endif
                                                        @endif
                                                    </li>
                                                @empty
                                                    <li class="list-group-item">
                                                        <div class="py-4 text-center fs-16">
                                                            {{ translate('No notification found') }}
                                                        </div>
                                                    </li>
                                                @endforelse
                                            </ul>
                                        </div>
                                        <div class="text-center border-top">
                                            <a href="{{ route('all-notifications') }}"
                                                class="text-secondary fs-12 d-block py-2">
                                                {{ translate('View All Notifications') }}
                                            </a>
                                        </div>
                                    </div>
                                @endauth
                            </li>
                        </ul>
                    @endif

                    
                </div>
            </div>

            <!-- Loged in user Menus -->
            
        </div>


    </header> --}}
    

    {{-- header section    --}}


     <style>
        .navbar a i{
           
            font-size:17px;
            color: #E8E8ED;
        }
        
        
                .navbar span i{
           
            font-size:17px;
            color: #E8E8ED;
        }
        

        
        
        .navbar-nav{
            justify-content:space-between !important;
            max-width:1024px;
            width:100%;

       
        }
        
        .navbar{
            
        padding:0 22px !important;
        }        
        .navbar .nav-link{
            color: #E8E8ED !important;
            font-size: 12px;
            font-weight:400;
            padding:12px;
        }
        .navbar .icon i
        {
            color: white !important;
        }
        
        .navbar .stop-propagation i{
          color: #000  !important;
        }



        
        
       .top-navbar .dropdown a {
    color: #E8E8ED !important;
    text-decoration: none;
        }

        
        
.navbar        .dropdown:hover>.dropdown-menu {
  display: block !important;
  

}

.navbar .dropdown>.dropdown-toggle:active {
  /*Without this, clicking will make it sticky*/
    pointer-events: none !important;

}




    
 .dropdown{
    position:static;
}

/*.dropdown-menu{*/
/*    width:100vw;*/
/*    background:black;*/
/*    border:0;*/
/*        border-radius:0;*/
/*        display:none;*/
/*        padding:40px 0;*/
       
/*}*/

.navbar  .dropdown-menu{
    width:100vw;
    border:0;
        border-radius:0;
        display:none;
        padding: 0;
        height: 100vh;
    background:black;

       
}


    .top-navbar{
        height:20px;
        background: black;
        color: #fff !important;
        z-index: 99999;
        
    }
    
        .top-navbar .dropdown-menu{
        background: black;
border: 0;
        
    }
    
    .nav-link:hover {
      color: #ffffff !important;
    
    }
    
    #d-d img{
        width:100%;
        height:300px !important;
        
    }
    
    /*#d-d{*/
    /*    max-width:1024px !important;*/
    /*    margin-inline: auto;*/
    /*}*/
    
    #d-d .img-banner{
        align-content:center;
    }
    
    #d-d .col-9 , .col-4 , .col-3{
        padding:0 !important;
    }
    
    .navbar img{
      width:  21px;
    }
    .bg-body-tertiary{
    z-index: 1111;
    width: 100%;
    background:black !important;
}

.scrolled {
    background-color: rgba(22, 22, 23, .8) !important;
}


 .bg-body-tertiary:hover{
    background:black !important;

}


.navbar-toggler:focus {
  outline: none;
  box-shadow: none;
}






    .icon a{
      text-decoration: none;
      color: white;
    }

 #head-banner{
    
    color: rgb(134, 134, 139);
    padding: 0;
    font-size: 12px;
    font-weight: 400;

}


 .icon-container {
            display: flex;
            gap: 15px;
        }
        .icon-container i {
            font-size: 24px;
            color: #333;
        }
        .icon-container.hidden {
            display: none !important;
        }
        .btn-toggle {
            color: white;
            border: none;
            cursor: pointer;
            border-radius: 5px;
        }

 
         
         
                 @media (max-width: 991.98px) {
 .navbar .nav-link {
font-size: 28px;
        line-height: 1.1428571429;
        font-weight: 600;
        letter-spacing: .007em;
    padding: 0.5rem 1rem;
  }

#icon-container i{
    font-size: 24px;
    padding-right: 10px;
}


  
  
          .navbar-nav{
            
        padding:14px 26px 0 !important;
        }
        
        .navbar a i{
           
            font-size:22px;
        }
        
        
                .navbar span i{
           
            font-size:22px;
        }
        
            .navbar img{
      width:  23px;
    }
          .navbar-collapse{
            min-height: 96vh;
        }
        
         .navbar{
     min-height: 48px;
}
         
         

                          }     
                          
                          /*user dropdown*/
        /* Custom Dropdown Styling */
        .custom-dropdown {
            position: relative;
        }

        .custom-dropdown-menu {
            display: none;
            position: absolute;
            background: #000000;
            list-style: none;
            padding: 0;
            min-width: 150px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            top: 100%; /* Position below icon */
            left: 0;
            color: #E8E8ED ;
        }
        
        .custom-dropdown .dropdown-menu ul{
            padding-left:1rem !important;
        }

        .custom-dropdown-item {
            padding: 10px;
            display: block;
            text-decoration: none;
            color: #E8E8ED  !important;
            font-size: 12px !important;
            font-weight: 400 !important;
        }

        .custom-dropdown-item:hover {
            color: #fff;
        }

        /* Show dropdown on hover */
        .custom-dropdown:hover .custom-dropdown-menu {
            display: block;
        }
        
        /*mob user dropdown*/
        
    .my-custom-dropdown {
            position: relative;
            list-style: none;
        }

        .my-custom-dropdown-menu {
            display: none;
            position: absolute;
            background: #000000;
            list-style: none;
            padding: 5px;
            min-width: 150px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.1);
            border-radius: 5px;
            z-index: 1050;
            top: 100%; /* Position below icon */
            left: -12px;
        }

        .my-custom-dropdown-item {
            padding: 10px;
            display: block;
            text-decoration: none;
            color: #E8E8ED  !important;
        }

        .my-custom-dropdown-item:hover {
            color: #fff;
            text-decoration: none;
        }
        
        
    

/* Initially hidden dropdown */
.dropdown-menu {
    opacity: 0;
    transform: translateY(-5px);
    visibility: hidden;
    /*pointer-events: none;*/
    transition: opacity 0.2s ease-in, transform 0.2s ease-in; /* Faster closing */
    display: block;
}

/*.show.dropdown-menu {*/
/*    visibility: visible;*/
/*    opacity: 1;*/
/*    margin-top: 0 !important;*/
/*}*/

/* Smooth fade-in and slide-down effect */
.navbar .dropdown:hover .dropdown-menu,
.navbar .dropdown:focus-within .dropdown-menu {
    opacity: 1;
    transform: translateY(0);
    visibility: visible;
    pointer-events: auto;
    transition: opacity 0.8s ease-out, transform 0.8s ease-out; /* Slower opening */
}


.switch-language{
   min-width: 245px;
    max-width: 340px;
    
}
li{
    list-style: none;
    text-transform: math-auto !important;
}

.emirati-nav .dropdown-menu .first-row{
    max-width: 1024px;
    margin-inline: auto;
    padding-block: 40px;
}

.emirati-nav .dropdown-menu .nav-blur{
   height: 100dvh;
   width: 100%;
  background: rgba(255, 255, 255, 0.2); /* semi-transparent */
    backdrop-filter: blur(4px);
    -webkit-backdrop-filter: blur(4px);
}


.emirati-dropdown{
    background-color: transparent !important;
}

.main-row{
    background-color: #000000;
}

.nav-cart-box .dropdown-menu{
    height:max-content;
}

      </style>
{{-- end header css      --}}
{{-- remix icon cdn --}}
 <link
      href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
      rel="stylesheet"/>
      
      
      
      


          <nav class=" navbar emirati-nav navbar-expand-lg bg-body-tertiary sticky-top" id="navbar">
              
           
                

              <div class="icon d-block d-lg-none">
                  
              <a class="navbar-brand " href="{{ url($navBaseUrl . '/')}}">
                <img src="{{asset('/assets/img/home-page/logo-head.png')}}" alt="" class="" />
              </a>
              </div>
                  
                  <div class="icon d-flex align-items-center ms-auto  d-block d-lg-none icon-container" id="icon-container">
                  


                
                <!--<a href="{{url('https://emiraticoffee.ae/register')}}"><i class="ri-account-circle-line"></i></a>-->
                <!--            <li class=" my-custom-dropdown">-->
                    <!-- Dropdown Toggle Button -->
                <!--    <a href="#" class=" my-custom-dropdown-btn">-->
                <!--        <i class="ri-account-circle-line" ></i>-->
                <!--    </a>-->

                    <!-- Dropdown Menu -->
                <!--    <ul class="my-custom-dropdown-menu">-->
                <!--        <li><a class="my-custom-dropdown-item" href="https://emiraticoffee.ae/register">Register</a></li>-->
                <!--        <li><a class="my-custom-dropdown-item" href="#">Profile</a></li>-->
                <!--        <li><a class="my-custom-dropdown-item" href="https://emiraticoffee.ae/logout">Logout</a></li>-->
                <!--    </ul>-->
                <!--</li>-->
           

        <li class="nav-item">
  <span class="" data-action="open-search" data-device="mobile">
    <i class="ri-search-2-line"></i>
  </span>
</li>

                <li class="my-custom-dropdown">
    <!-- Dropdown Toggle Button -->
    <span  class="my-custom-dropdown-btn">
        <i class="ri-account-circle-line"></i>
    </span>

    <!-- Dropdown Menu -->
    <ul class="custom-dropdown-menu rtl-text">

        @if(Auth::check()) 
            <!-- User is logged in: Show Profile & Logout -->
            <!--<li><a class="my-custom-dropdown-item rtl-text" href="#">{{ translate('Profile') }}</a></li>-->
            <li><a class="custom-dropdown-item nav-link rtl-text" href="{{ url($navBaseUrl . '/logout') }}">{{ translate('Logout') }}</a></li>
          <li><a class="custom-dropdown-item rtl-text nav-link " href="{{ url($navBaseUrl . '/dashboard') }}">{{ translate('Dashboard') }}</a></li>
        @else
            <!-- User is NOT logged in: Show Register -->
            <li><a class="custom-dropdown-item rtl-text nav-link" href="{{ url($navBaseUrl . '/register') }}">{{ translate('Register') }}</a></li>
            <li><a class="custom-dropdown-item rtl-text nav-link" href="{{ url($navBaseUrl . '/login') }}">{{ translate('Login') }}</a></li>
        @endif
    </ul>
</li>






                           <div class=" has-transition" data-hover="dropdown">
                        <div class="nav-cart-box dropdown h-100 position-relative carted-update" id="cart_items" style="width: max-content;">
                            @include('frontend.'.get_setting('homepage_select').'.partials.cart')
                        </div>
                    </div>





                <!--<a href=""> <i class="pe-3 ri-shopping-bag-line"></i> </a>-->
                
                
                
                
                
                
                
                
                
                
                
                

                                    

              </div>
              <button
                class="navbar-toggler btn-toggle pe-0"
                type="button"
                id="toggle-button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent"
                aria-expanded="false"
                aria-label="Toggle navigation"
                style="color: white"
              >
                <i class="ri-menu-line"></i>
              </button>



              <div class="collapse justify-content-center navbar-collapse" id="navbarSupportedContent">
                  

                  <ul class="navbar-nav mb-md-0 align-items-lg-center">
    <!-- Logo, mobile Coffee link, etc... -->
   <a class="navbar-brand p-0 d-none d-lg-block " href="{{ url($navBaseUrl . '/')}}">
                <img src="{{asset('/assets/img/home-page/logo-head.png')}}" alt="" class="" />
              </a>         
 {{-- Coffee Dropdown --}}
 <div class="dropdown d-lg-none d-block">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/coffee') }}">
    {{ translate('Coffee') }}
  </a>
  </div>
 <div class="dropdown d-lg-none d-block">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/limited-edition') }}">
    {{ translate('Limited Edition') }}
  </a>
  </div>
 <div class="dropdown d-lg-none d-block">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/capsules') }}">
    {{ translate('Capsules') }}
  </a>
  </div>
 <div class="dropdown d-lg-none d-block">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/Instant-Coffee') }}">
    {{ translate('Instant Coffee') }}
  </a>
  </div>
 <div class="dropdown d-lg-none d-block">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/drip-bags') }}">
    {{ translate('Drip Bags') }}
  </a>
  </div>
 <div class="dropdown d-lg-none d-block">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/tea') }}">
    {{ translate('Tea') }}
  </a>
  </div>
<div class="dropdown d-lg-block d-none">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/coffee') }}">
    {{ translate('Coffee') }}
  </a>
  <ul class="dropdown-menu emirati-dropdown">
    <div class="" id="d-d">
      <div class="row main-row ">
                    <div class="first-row">

        <div>
          <h3 id="head-banner">{{ translate('Coffee Varieties') }}</h3>
        </div>
        <div class="col-12">
          <div class="row">
            {{-- 1st Column --}}
            <div class="col-4 products-dd">
              <ul>
                @foreach($coffeeProducts->slice(0, ceil($coffeeProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            {{-- 2nd Column --}}
            <div class="col-4 products-dd">
              <ul>
                @foreach($coffeeProducts->slice(ceil($coffeeProducts->count()/3), ceil($coffeeProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            {{-- 3rd Column --}}
            <div class="col-4 products-dd">
              <ul>
                @foreach($coffeeProducts->slice(2*ceil($coffeeProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
      </div>
      <div class="nav-blur"></div>
    </div>
  </ul>
</div>

{{-- Exclusive Dropdown --}}
<div class="dropdown d-lg-block d-none">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/limited-edition') }}">
    {{ translate('Limited Edition') }}
  </a>
  <ul class="dropdown-menu emirati-dropdown">
    <div class="" id="d-d">
      <div class="row main-row">
          <div class="first-row">
        <div>
          <h3 id="head-banner">{{ translate('Exclusive Varieties') }}</h3>
        </div>
        <div class="col-12">
          <div class="row">
            <div class="col-4 products-dd">
              <ul>
                @foreach($exclusiveProducts->slice(0, ceil($exclusiveProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                       {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="col-4 products-dd">
              <ul>
                @foreach($exclusiveProducts->slice(ceil($exclusiveProducts->count()/3), ceil($exclusiveProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                                            {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}

                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="col-4 products-dd">
              <ul>
                @foreach($exclusiveProducts->slice(2*ceil($exclusiveProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                                            {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}

                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        </div>
      </div>
            <div class="nav-blur"></div>

    </div>
  </ul>
</div>
{{-- Capsules Dropdown --}}
<div class="dropdown d-lg-block d-none">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/capsules') }}">
    {{ translate('Capsules') }}
  </a>
  <ul class="dropdown-menu emirati-dropdown">
    <div class="" id="d-d">
      <div class="row main-row">
      <div class=" first-row">
          
        <div>
          <h3 id="head-banner">{{ translate('Capsules Varieties') }}</h3>
        </div>
        <div class="col-12">
          <div class="row">
            <div class="col-4 products-dd">
              <ul>
                @foreach($capsuleProducts->slice(0, ceil($capsuleProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="col-4 products-dd">
              <ul>
                @foreach($capsuleProducts->slice(ceil($capsuleProducts->count()/3), ceil($capsuleProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="col-4 products-dd">
              <ul>
                @foreach($capsuleProducts->slice(2*ceil($capsuleProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        </div>
      </div>
            <div class="nav-blur"></div>

    </div>
  </ul>
</div>
{{-- InstantCoffee Dropdown --}}
<div class="dropdown d-lg-block d-none">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/Instant-Coffee') }}">
    {{ translate('Instant Coffee') }}
  </a>
  <ul class="dropdown-menu emirati-dropdown">
    <div class="" id="d-d">
      <div class="row main-row">
          <div class="first-row">
        <div>
          <h3 id="head-banner">{{ translate('Instant Coffee Varieties') }}</h3>
        </div>
        <div class="col-12">
          <div class="row">
            <div class="col-4 products-dd">
              <ul>
                @foreach($instantProducts->slice(0, ceil($instantProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="col-4 products-dd">
              <ul>
                @foreach($instantProducts->slice(ceil($instantProducts->count()/3), ceil($instantProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="col-4 products-dd">
              <ul>
                @foreach($instantProducts->slice(2*ceil($instantProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>

        </div>
                          </div>
      </div>
        <div class="nav-blur"></div>
    </div>

  </ul>

</div>

{{-- Drip Bags Dropdown --}}
<div class="dropdown d-lg-block d-none">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/drip-bags') }}">
    {{ translate('Drip Bags') }}
  </a>
  <ul class="dropdown-menu emirati-dropdown">
    <div class="" id="d-d">
      <div class="row main-row">
                <div class=" first-row">
        <div>
          <h3 id="head-banner">{{ translate('Drip Bags Varieties') }}</h3>
        </div>
        <div class="col-12">
          <div class="row">
            <div class="col-4 products-dd">
              <ul>
                @foreach($dripProducts->slice(0, ceil($dripProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="col-4 products-dd">
              <ul>
                @foreach($dripProducts->slice(ceil($dripProducts->count()/3), ceil($dripProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="col-4 products-dd">
              <ul>
                @foreach($dripProducts->slice(2*ceil($dripProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
      </div>
      </div>
              <div class="nav-blur"></div>

    </div>
  </ul>

</div>
{{-- Tea Dropdown --}}
<div class="dropdown d-lg-block d-none">
  <a class="nav-link" href="{{ url($navBaseUrl . '/category/tea') }}">
    {{ translate('Tea') }}
  </a>
  <ul class="dropdown-menu emirati-dropdown">
    <div class="" id="d-d">
      <div class="row main-row">
                          <div class=" first-row">

        <div>
          <h3 id="head-banner">{{ translate('Tea Varieties') }}</h3>
        </div>
        <div class="col-12">
          <div class="row">
            <div class="col-4 products-dd">
              <ul>
                @foreach($teaProducts->slice(0, ceil($teaProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="col-4 products-dd">
              <ul>
                @foreach($teaProducts->slice(ceil($teaProducts->count()/3), ceil($teaProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
            <div class="col-4 products-dd">
              <ul>
                @foreach($teaProducts->slice(2*ceil($teaProducts->count()/3)) as $product)
                  <li>
                    <a class="dropdown-item openPopup" href="#" data-slug="{{ $product->slug }}">
                        {{ \Illuminate\Support\Str::title($product->getTranslation('name')) }}
                    </a>
                  </li>
                @endforeach
              </ul>
            </div>
          </div>
        </div>
        </div>
      </div>
              <div class="nav-blur"></div>

    </div>
  </ul>

</div>
                    
                    
                    
   
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url($navBaseUrl . '/subscription')}}">{{ translate('Subscribe') }}</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" href="{{ url($navBaseUrl . '/academy')}}">{{ translate('Academy') }}</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" href="{{ url($navBaseUrl . '/wholesale')}}">{{ translate('Wholesale') }}</a>
                  </li>
                  
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url($navBaseUrl . '/travel')}}">{{ translate('Travel') }}</a>
                  </li>

                  <li class="nav-item">
                    <a class="nav-link" href="{{ url($navBaseUrl . '/event')}}">{{ translate('Events') }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url($navBaseUrl . '/about')}}">{{ translate('About US') }}</a>
                  </li>
                  <li class="nav-item">
                    <a class="nav-link" href="{{ url($navBaseUrl . '/contact')}}">{{ translate('Contact US') }}</a>
                  </li>
                  
                  


                  <!--<li class="nav-item d-none d-lg-block ">-->
                  <!--<a href="{{url('https://emiraticoffee.ae/register')}}" class="nav-link"><i class="ri-account-circle-line"></i></a>-->
                  <!--</li>-->
                  
                <!--<li class="nav-item custom-dropdown d-none d-lg-block">-->
                <!--    <a href="" class="nav-link" id="uniqueDropdownBtn">-->
                <!--        <i class="ri-account-circle-line" ></i>-->
                <!--    </a>-->
                <!--    <ul class="custom-dropdown-menu">-->
                <!--        <li><a class="custom-dropdown-item" href="{{ url('https://emiraticoffee.ae/register') }}">Register</a></li>-->
                <!--        <li><a class="custom-dropdown-item" href="#">Profile</a></li>-->
                <!--        <li><a class="custom-dropdown-item" href="https://emiraticoffee.ae/logout">Logout</a></li>-->
                <!--    </ul>-->
                <!--</li>-->
          <li class="nav-item custom-dropdown d-none d-lg-block" style="cursor:pointer;">
  <span class="nav-link" data-action="open-search" data-device="desktop">
    <i class="ri-search-2-line"></i>
  </span>
</li>
                <li class="nav-item custom-dropdown d-none d-lg-block">
    <span  class="nav-link" id="uniqueDropdownBtn">
        <i class="ri-account-circle-line"></i>
    </span>
    <ul class="custom-dropdown-menu rtl-text">
        @if(Auth::check()) 
            <!-- User is logged in, show Profile and Logout -->
        <li><a class="custom-dropdown-item nav-link rtl-text" href="{{ url($navBaseUrl . '/dashboard') }}">{{ translate('Dashboard') }}</a></li>
            <!--<li><a class="custom-dropdown-item" href="#">Profile</a></li>-->
            <li><a class="custom-dropdown-item nav-link" href="{{ url($navBaseUrl . '/logout') }}">{{ translate('Logout') }}</a></li>
        @else
            <!-- User is NOT logged in, show Register -->
            <li><a class="custom-dropdown-item nav-link" href="{{ url($navBaseUrl . '/register') }}">{{ translate('Register') }}</a></li>
            <li><a class="custom-dropdown-item nav-link" href="{{ url($navBaseUrl . '/login') }}">{{ translate('Login') }}</a></li>
        @endif
    </ul>
</li>



                      
                      <li class="nav-item d-none d-lg-block">

                           <div class="d-none d-lg-block  has-transition" data-hover="dropdown">
                        <div class="nav-cart-box dropdown nav-link h-100 position-relative carted-update" id="cart_items" style="width: max-content;">
                            @include('frontend.'.get_setting('homepage_select').'.partials.cart')
                        </div>
                    </div>
                    </li>

</ul>

                  
              

            </div>
          </nav>



    <!--{{-- end section header  --}}-->

    <!-- Top Menu Sidebar -->
     <div class="aiz-top-menu-sidebar collapse-sidebar-wrap sidebar-xl sidebar-left d-lg-none z-1035">
        <div class="overlay overlay-fixed dark c-pointer" data-toggle="class-toggle"
            data-target=".aiz-top-menu-sidebar" data-same=".hide-top-menu-bar"></div>
        <div class="collapse-sidebar c-scrollbar-light text-left">
            <button type="button" class="btn btn-sm p-4 hide-top-menu-bar" data-toggle="class-toggle"
                data-target=".aiz-top-menu-sidebar">
                <i class="las la-times la-2x text-primary"></i>
            </button>
            @auth
                <span class="d-flex align-items-center nav-user-info pl-4">
                    <!-- Image -->
                    <span class="size-40px rounded-circle overflow-hidden border border-transparent nav-user-img">
                        @if ($user->avatar_original != null)
                            <img src="{{ $user_avatar }}" class="img-fit h-100" alt="{{ translate('avatar') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                        @else
                            <img src="{{ static_asset('assets/img/avatar-place.png') }}" class="image" alt="{{ translate('avatar') }}"
                                onerror="this.onerror=null;this.src='{{ static_asset('assets/img/avatar-place.png') }}';">
                        @endif
                    </span>
                    <!-- Name -->
                    <h4 class="h5 fs-14 fw-700 text-dark ml-2 mb-0">{{ $user->name }}</h4>
                </span>
            @else
                <!--Login & Registration -->
                <span class="d-flex align-items-center nav-user-info pl-4">
                    <!-- Image -->
                    <span
                        class="size-40px rounded-circle overflow-hidden border d-flex align-items-center justify-content-center nav-user-img">
                        <svg xmlns="http://www.w3.org/2000/svg" width="19.902" height="20.012"
                            viewBox="0 0 19.902 20.012">
                            <path id="fe2df171891038b33e9624c27e96e367"
                                d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1.006,1.006,0,1,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1,10,10,0,0,0-6.25-8.19ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z"
                                transform="translate(-2.064 -1.995)" fill="#91919b" />
                        </svg>
                    </span>
                    <a href="{{ route('user.login') }}"
                        class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block border-right border-soft-light border-width-2 pr-2 ml-3">{{ translate('Login') }}</a>
                    <a href="{{ route('user.registration') }}"
                        class="text-reset opacity-60 hov-opacity-100 hov-text-primary fs-12 d-inline-block py-2 pl-2">{{ translate('Registration') }}</a>
                </span>
            @endauth
            <hr>
            <ul class="mb-0 pl-3 pb-3 h-100">
                @if (get_setting('header_menu_labels') != null)
                    @foreach (json_decode(get_setting('header_menu_labels'), true) as $key => $value)
                        @php
                            $menuLink = json_decode(get_setting('header_menu_links'), true)[$key] ?? '';
                            $processedLink = process_nav_url($menuLink);
                        @endphp
                        <li class="mr-0">
                            <a href="{{ $processedLink }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                            @if (url()->current() == $processedLink) active @endif">
                                {{ translate($value) }}
                            </a>
                        </li>
                    @endforeach
                @endif
                @auth
                    @if (isAdmin())
                        <hr>
                        <li class="mr-0">
                            <a href="{{ route('admin.dashboard') }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links">
                                {{ translate('My Account') }}
                            </a>
                        </li>
                    @else
                        <hr>
                        <li class="mr-0">
                            <a href="{{ route('dashboard') }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['dashboard'], ' active') }}">
                                {{ translate('My Account') }}
                            </a>
                        </li>
                    @endif
                    @if (isCustomer())
                        <li class="mr-0">
                            <a href="{{ route('all-notifications') }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['all-notifications'], ' active') }}">
                                {{ translate('Notifications') }}
                            </a>
                        </li>
                        <li class="mr-0">
                            <a href="{{ route('wishlists.index') }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['wishlists.index'], ' active') }}">
                                {{ translate('Wishlist') }}
                            </a>
                        </li>
                        <li class="mr-0">
                            <a href="{{ route('compare') }}"
                                class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-dark header_menu_links
                                {{ areActiveRoutes(['compare'], ' active') }}">
                                {{ translate('Compare') }}
                            </a>
                        </li>
                    @endif
                    <hr>
                    <li class="mr-0">
                        <a href="{{ route('logout') }}"
                            class="fs-13 px-3 py-3 w-100 d-inline-block fw-700 text-primary header_menu_links">
                            {{ translate('Logout') }}
                        </a>
                    </li>
                @endauth
            </ul>
            <br>
            <br>
        </div>
    </div> 

    <!-- Modal -->
     <div class="modal fade" id="order_details" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
            <div class="modal-content">
                <div id="order-details-modal-body">

                </div>
            </div>
        </div>
    </div>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ----------------------------
    // 1️⃣ Custom dropdowns (mobile + account)
    // ----------------------------
    document.querySelectorAll(".my-custom-dropdown").forEach(dropdown => {
        const btn = dropdown.querySelector(".my-custom-dropdown-btn");
        const menu = dropdown.querySelector(".my-custom-dropdown-menu");

        if (!btn || !menu) return; // Safety check

        // Toggle dropdown on click
        btn.addEventListener("click", function(e) {
            e.preventDefault();
            e.stopPropagation();

            // Close other dropdowns
            document.querySelectorAll(".my-custom-dropdown-menu").forEach(m => {
                if (m !== menu) m.style.display = "none";
            });

            // Toggle current
            menu.style.display = menu.style.display === "block" ? "none" : "block";
        });
    });

    // Close all dropdowns when clicking outside
    document.addEventListener("click", function() {
        document.querySelectorAll(".my-custom-dropdown-menu").forEach(menu => {
            menu.style.display = "none";
        });
    });


    // ----------------------------
    // 2️⃣ Desktop hover dropdowns (if any)
    // ----------------------------
    document.querySelectorAll(".dropdown").forEach(dropdown => {
        const menu = dropdown.querySelector(".dropdown-menu");
        if (!menu) return;

        dropdown.addEventListener("mouseenter", function() {
            menu.classList.add("show");
        });
        dropdown.addEventListener("mouseleave", function() {
            menu.classList.remove("show");
        });
    });


    // ----------------------------
    // 3️⃣ Navbar scroll effect
    // ----------------------------
    const navbar = document.getElementById("navbar");
    if (navbar) {
        window.addEventListener("scroll", function() {
            if (window.scrollY > 30) {
                navbar.classList.add("scrolled");
            } else {
                navbar.classList.remove("scrolled");
            }
        });
    }

});
</script>
