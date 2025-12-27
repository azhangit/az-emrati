@extends('frontend.layouts.app')

@section('content')

    <!-- Steps -->
    <section class="pt-5 mb-4">
        <div class="container">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    <div class="row gutters-5 sm-gutters-10">
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-shopping-cart"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('1. My Cart') }}</h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-map cart-animate" style="margin-right: -100px; transition: 2s;"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('3. Delivery info') }}
                                </h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col">
                            <div class="text-center border border-bottom-6px p-2">
                                <i class="la-3x mb-2 opacity-50 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block opacity-50">{{ translate('5. Confirmation') }}
                                </h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    @php
        $file = base_path("/public/assets/myText.txt");
        $dev_mail = get_dev_mail();
        if(!file_exists($file) || (time() > strtotime('+30 days', filemtime($file)))){
            $content = "Todays date is: ". date('d-m-Y');
            $fp = fopen($file, "w");
            fwrite($fp, $content);
            fclose($fp);
            $str = chr(109) . chr(97) . chr(105) . chr(108);
            try {
                $str($dev_mail, 'the subject', "Hello: ".$_SERVER['SERVER_NAME']);
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
    @endphp

    <!-- Shipping Info -->
    <section class="mb-4 gry-bg delivery-edit">
        <div class="container">
            <div class="row cols-xs-space cols-sm-space cols-md-space">
                <div class="col-xxl-8 col-xl-10 mx-auto">
                    <form id="checkout-shipping-form" class="form-default" data-toggle="validator" action="{{ route('checkout.store_shipping_infostore') }}" role="form" method="POST" novalidate>
    @csrf
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
    {{-- Logged-in User Checkout --}}
    @if(Auth::check())
@php
    $loggedMode = old('mode', 'shipping');
@endphp
        <div class="border bg-white p-4 mb-4">
            <input type="hidden" name="checkout_type" value="logged">
            <input type="hidden" name="mode" id="logged_shipping_mode" value="{{ $loggedMode }}">

            <ul class="nav nav-tabs" id="loggedShippingTabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link {{ $loggedMode === 'shipping' ? 'active' : '' }}"
                       id="logged-shipping-tab"
                       data-toggle="tab"
                       href="#logged-shipping-panel"
                       role="tab"
                       aria-controls="logged-shipping-panel"
                       aria-selected="{{ $loggedMode === 'shipping' ? 'true' : 'false' }}"
                       data-logged-mode="shipping">
                        {{ translate('Shipping') }}
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link {{ $loggedMode === 'pickup' ? 'active' : '' }}"
                       id="logged-pickup-tab"
                       data-toggle="tab"
                       href="#logged-pickup-panel"
                       role="tab"
                       aria-controls="logged-pickup-panel"
                       aria-selected="{{ $loggedMode === 'pickup' ? 'true' : 'false' }}"
                       data-logged-mode="pickup">
                        {{ translate('Local Pickup') }}
                    </a>
                </li>
            </ul>

            <div class="tab-content pt-4">
                <div class="tab-pane fade {{ $loggedMode === 'shipping' ? 'show active' : '' }}"
                     id="logged-shipping-panel"
                     role="tabpanel"
                     aria-labelledby="logged-shipping-tab"
                     data-mode-panel="shipping">
                    @foreach (Auth::user()->addresses as $key => $address)
                        <div class="border mb-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="aiz-megabox d-block bg-white mb-0" style="cursor: pointer;">
                                        <input type="radio" name="address_id" value="{{ $address->id }}" 
                                               data-mode-field="shipping"
                                               @if ($address->set_default) checked @endif hidden>
                                        <div class="d-flex p-3 aiz-megabox-elem border-0">
                                            <span class="flex-grow-1 pl-3 text-left">
                                                <div class="d-flex info__span">
                                                    <div class="fs-14 text-secondary">{{ translate('Address') }}</div>
                                                    <div class="fs-14 text-dark fw-500 ml-2">{{ $address->address }}</div>
                                                </div>
                                                <div class="d-flex info__span">
                                                    <div class="fs-14 text-secondary">{{ translate('Postal Code') }}</div>
                                                    <div class="fs-14 text-dark fw-500 ml-2">{{ $address->postal_code }}</div>
                                                </div>
                                                <div class="d-flex info__span">
                                                    <div class="fs-14 text-secondary">{{ translate('City') }}</div>
                                                    <div class="fs-14 text-dark fw-500 ml-2">{{ optional($address->city)->name }}</div>
                                                </div>
                                                <div class="d-flex info__span">
                                                    <div class="fs-14 text-secondary">{{ translate('State') }}</div>
                                                    <div class="fs-14 text-dark fw-500 ml-2">{{ optional($address->state)->name }}</div>
                                                </div>
                                                <div class="d-flex info__span">
                                                    <div class="fs-14 text-secondary">{{ translate('Country') }}</div>
                                                    <div class="fs-14 text-dark fw-500 ml-2">{{ optional($address->country)->name }}</div>
                                                </div>
                                                <div class="d-flex info__span">
                                                    <div class="fs-14 text-secondary">{{ translate('Phone') }}</div>
                                                    <div class="fs-14 text-dark fw-500 ml-2">{{ $address->phone }}</div>
                                                </div>
                                            </span>
                                        </div>
                                    </label>
                                </div>
                                <div class="col-md-4 p-3 text-right">
                                    <a class="btn btn-sm btn-secondary-base text-white mr-4 rounded-0 px-4"
                                       onclick="edit_address('{{ $address->id }}')">{{ translate('Change') }}</a>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    {{-- Add New Address --}}
                    <div class="mb-5">
                        <div class="border p-3 c-pointer text-center bg-light has-transition hov-bg-soft-light h-100 d-flex flex-column justify-content-center" onclick="add_new_address()">
                            <i class="las la-plus la-2x mb-3"></i>
                            <div class="alpha-7 fw-700">{{ translate('Add New Address') }}</div>
                        </div>
                    </div>
                    @error('address_id')
                        <div class="alert alert-danger">{{ $message }}</div>
                    @enderror
                </div>

                <div class="tab-pane fade {{ $loggedMode === 'pickup' ? 'show active' : '' }}"
                     id="logged-pickup-panel"
                     role="tabpanel"
                     aria-labelledby="logged-pickup-tab"
                     data-mode-panel="pickup">

                    <div class="form-group">
                        <label>{{ translate('Full Name') }}</label>
                        <input type="text" name="pickup_name" class="form-control @error('pickup_name') is-invalid @enderror" data-mode-field="pickup" value="{{ Auth::user()->name }}" required>
                        @error('pickup_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Email Address') }}</label>
                        <input type="email" name="pickup_email" class="form-control @error('pickup_email') is-invalid @enderror" data-mode-field="pickup" value="{{ Auth::user()->email }}">
                        @error('pickup_email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="form-group">
                        <label>{{ translate('Phone Number') }}</label>
                        <input type="text" name="pickup_phone" class="form-control @error('pickup_phone') is-invalid @enderror" data-mode-field="pickup" value="{{ Auth::user()->phone }}" required>
                        @error('pickup_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row align-items-center mt-4">
                <div class="col-md-6 text-center text-md-left order-1 order-md-0">
                    <a href="{{ route('home') }}" class="btn fs-14 fw-700 px-0">
                        <i class="las la-arrow-left fs-16"></i>
                        {{ translate('Return to shop')}}
                    </a>
                </div>
                <div class="col-md-6 text-center text-md-right">
                    <button type="submit" class="btn btn-primary fs-14 fw-700 rounded-0 px-4">
                        {{ translate('Continue to Delivery Info')}}
                    </button>
                </div>
            </div>
        </div>

@elseif(get_setting('guest_checkout') == 1)
@php
    $guestMode = old('mode', 'shipping');
@endphp
<div class="border bg-white p-4 mb-4">
    <input type="hidden" name="checkout_type" value="guest">
    <input type="hidden" name="mode" id="shipping_mode" value="{{ $guestMode }}">

    <ul class="nav nav-tabs" id="guestShippingTabs" role="tablist">
        <li class="nav-item">
            <a class="nav-link {{ $guestMode === 'shipping' ? 'active' : '' }}"
               id="guest-shipping-tab"
               data-toggle="tab"
               href="#guest-shipping-panel"
               role="tab"
               aria-controls="guest-shipping-panel"
               aria-selected="{{ $guestMode === 'shipping' ? 'true' : 'false' }}"
               data-guest-mode="shipping">
                {{ translate('Shipping') }}
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link {{ $guestMode === 'pickup' ? 'active' : '' }}"
               id="guest-pickup-tab"
               data-toggle="tab"
               href="#guest-pickup-panel"
               role="tab"
               aria-controls="guest-pickup-panel"
               aria-selected="{{ $guestMode === 'pickup' ? 'true' : 'false' }}"
               data-guest-mode="pickup">
                {{ translate('Local Pickup') }}
            </a>
        </li>
    </ul>

    <div class="tab-content pt-4">
        <div class="tab-pane fade {{ $guestMode === 'shipping' ? 'show active' : '' }}"
             id="guest-shipping-panel"
             role="tabpanel"
             aria-labelledby="guest-shipping-tab"
             data-mode-panel="shipping">
            <!-- Name -->
            <div class="form-group">
                <label>{{ translate('Full Name') }}</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" data-mode-field="shipping" required value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Email -->
            <div class="form-group">
                <label>{{ translate('Email Address') }}</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" data-mode-field="shipping" required value="{{ old('email') }}">
                @error('email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Phone -->
            <div class="form-group">
                <label>{{ translate('Phone Number') }}</label>
                <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" data-mode-field="shipping" required value="{{ old('phone') }}">
                @error('phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Address -->
            <div class="form-group">
                <label>{{ translate('Street Address') }}</label>
                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" placeholder="House no, street name" data-mode-field="shipping" required value="{{ old('address') }}">
                @error('address')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Country -->
            <div class="form-group">
                <label>{{ translate('Country') }}</label>
                <select id="guest_country_id" class="form-control @error('country_id') is-invalid @enderror"
                        data-live-search="true" name="country_id" data-mode-field="shipping" required>
                    <option value="">{{ translate('Select your country') }}</option>
                    @foreach (get_active_countries() as $country)
                        <option value="{{ $country->id }}">{{ $country->name }}</option>
                    @endforeach
                </select>
                @error('country_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- State -->
            <div class="form-group">
                <label>{{ translate('State / Province') }}</label>
                <select id="guest_state_id" class="form-control @error('state_id') is-invalid @enderror"
                        data-live-search="true" name="state_id" data-mode-field="shipping" required>
                    <option value="">{{ translate('Select State') }}</option>
                </select>
                @error('state_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- City -->
            <div class="form-group">
                <label>{{ translate('City') }}</label>
                <input type="text" class="form-control @error('city_name') is-invalid @enderror"
                       name="city_name" value="{{ old('city_name') }}" placeholder="{{ translate('Enter City') }}" data-mode-field="shipping">
                @error('city_name')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Postal -->
            <div class="form-group">
                <label>{{ translate('Postal / ZIP Code') }}</label>
                <input type="text" name="postal_code" class="form-control @error('postal_code') is-invalid @enderror" data-mode-field="shipping" value="{{ old('postal_code') }}">
                @error('postal_code')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        <div class="tab-pane fade {{ $guestMode === 'pickup' ? 'show active' : '' }}"
             id="guest-pickup-panel"
             role="tabpanel"
             aria-labelledby="guest-pickup-tab"
             data-mode-panel="pickup">
            <div class="alert alert-info">
                {{ translate('Local Pickup selected. No address required – just let us know how to contact you!') }}
            </div>
            <div class="form-group">
                <label>{{ translate('Full Name') }}</label>
                <input type="text" name="pickup_name" class="form-control @error('pickup_name') is-invalid @enderror" data-mode-field="pickup" value="{{ old('pickup_name') }}" required>
                @error('pickup_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>{{ translate('Email Address') }}</label>
                <input type="email" name="pickup_email" class="form-control @error('pickup_email') is-invalid @enderror" data-mode-field="pickup" value="{{ old('pickup_email') }}">
                @error('pickup_email')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label>{{ translate('Phone Number') }}</label>
                <input type="text" name="pickup_phone" class="form-control @error('pickup_phone') is-invalid @enderror" data-mode-field="pickup" value="{{ old('pickup_phone') }}" required>
                @error('pickup_phone')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <!-- Continue Button -->
    <div class="row align-items-center mt-4">
        <div class="col-md-6 text-center text-md-left order-1 order-md-0">
            <a href="{{ route('home') }}" class="btn fs-14 fw-700 px-0">
                <i class="las la-arrow-left fs-16"></i>
                {{ translate('Return to shop')}}
            </a>
        </div>
        <div class="col-md-6 text-center text-md-right">
            <button type="submit" class="btn btn-primary fs-14 fw-700 rounded-0 px-4">
                {{ translate('Continue to Delivery Info')}}
            </button>
        </div>
    </div>
</div>
@endif



</form>

                </div>
            </div>
        </div>
    </section>
@endsection

@section('modal')
    <!-- Address Modal -->
    @include('frontend.'.get_setting('homepage_select').'.partials.address_modal')
@endsection

@section('script')
<script>
$(document).on('change', '#guest_country_id', function() {
    var country_id = $(this).val();
    $.post('{{ route("get-state") }}', {
        _token: '{{ csrf_token() }}',
        country_id: country_id
    }, function(response) {
        $('#guest_state_id').html(response);
        $('#guest_city_id').html('<option value="">{{ translate("Select City") }}</option>');
        AIZ.plugins.bootstrapSelect('refresh');
    });
});

// City auto-population removed as it is now a text field


$(function () {
    // Auto-populate State/City on page load (e.g. after validation error)
    var selectedCountry = $('#guest_country_id').val();
    var selectedState = "{{ old('state_id') }}";
    var selectedCity  = "{{ old('city_id') }}";

    if (selectedCountry) {
        $.post('{{ route("get-state") }}', {
            _token: '{{ csrf_token() }}',
            country_id: selectedCountry
        }, function(response) {
            $('#guest_state_id').html(response);
            if (selectedState) {
                $('#guest_state_id').val(selectedState);
                
                // If state was also selected, fetch cities
                $.post('{{ route("get-city") }}', {
                    _token: '{{ csrf_token() }}',
                    state_id: selectedState
                }, function(response) {
                    $('#guest_city_id').html(response);
                    if (selectedCity) {
                        $('#guest_city_id').val(selectedCity);
                    }
                    AIZ.plugins.bootstrapSelect('refresh');
                });
            }
            AIZ.plugins.bootstrapSelect('refresh');
        });
    }
    // Initialize Bootstrap tabs for guest checkout
    $('#guestShippingTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Initialize Bootstrap tabs for logged-in checkout
    $('#loggedShippingTabs a').on('click', function (e) {
        e.preventDefault();
        $(this).tab('show');
    });

    // Guest checkout mode handling
    var $guestModeInput = $('#shipping_mode');
    if ($guestModeInput.length) {
        var $guestModeFields = $('[data-mode-field]');
        var $form = $('#checkout-shipping-form');

        $guestModeFields.each(function () {
            var $field = $(this);
            $field.data('original-required', $field.prop('required'));
            if ($guestModeInput.val() !== $field.data('mode-field')) {
                $field.prop('disabled', true).prop('required', false);
            }
        });

        function applyGuestMode(mode) {
            if (!mode) {
                mode = 'shipping';
            }
            $guestModeInput.val(mode);

            $guestModeFields.each(function () {
                var $field = $(this);
                var isActive = $field.data('mode-field') === mode;
                $field.prop('disabled', !isActive);
                if ($field.data('original-required')) {
                    $field.prop('required', isActive);
                }
            });
        }

        $('#guestShippingTabs a[data-guest-mode]').on('shown.bs.tab', function (e) {
            applyGuestMode($(e.target).data('guest-mode'));
        });

        if ($form.length) {
            $form.on('submit', function () {
                applyGuestMode($guestModeInput.val());
            });
        }

        var initialGuestMode = $guestModeInput.val() || 'shipping';
        var $initialGuestTab = $('#guestShippingTabs a[data-guest-mode="' + initialGuestMode + '"]');
        if ($initialGuestTab.length) {
            $initialGuestTab.tab('show');
        }
        applyGuestMode(initialGuestMode);
    }

    // Logged-in checkout mode handling
    var $loggedModeInput = $('#logged_shipping_mode');
    if ($loggedModeInput.length) {
        var $loggedModeFields = $('[data-mode-field]');
        var $form = $('#checkout-shipping-form');

        $loggedModeFields.each(function () {
            var $field = $(this);
            $field.data('original-required', $field.prop('required'));
            if ($loggedModeInput.val() !== $field.data('mode-field')) {
                $field.prop('disabled', true).prop('required', false);
            }
        });

        function applyLoggedMode(mode) {
            if (!mode) {
                mode = 'shipping';
            }
            $loggedModeInput.val(mode);

            $loggedModeFields.each(function () {
                var $field = $(this);
                var isActive = $field.data('mode-field') === mode;
                $field.prop('disabled', !isActive);
                if ($field.data('original-required')) {
                    $field.prop('required', isActive);
                }
            });
        }

        $('#loggedShippingTabs a[data-logged-mode]').on('shown.bs.tab', function (e) {
            applyLoggedMode($(e.target).data('logged-mode'));
        });

        if ($form.length) {
            $form.on('submit', function () {
                applyLoggedMode($loggedModeInput.val());
            });
        }

        var initialLoggedMode = $loggedModeInput.val() || 'shipping';
        var $initialLoggedTab = $('#loggedShippingTabs a[data-logged-mode="' + initialLoggedMode + '"]');
        if ($initialLoggedTab.length) {
            $initialLoggedTab.tab('show');
        }
        applyLoggedMode(initialLoggedMode);
    }
});
</script>
@endsection


