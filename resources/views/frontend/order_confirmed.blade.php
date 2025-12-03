@extends('frontend.layouts.app')

@section('content')

    <!-- Steps -->
    <section class="pt-5 mb-0">
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
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-map"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('2. Shipping info') }}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-truck"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('3. Delivery info') }}</h3>
                            </div>
                        </div>
                        <div class="col done">
                            <div class="text-center border border-bottom-6px p-2 text-success">
                                <i class="la-3x mb-2 las la-credit-card"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('4. Payment') }}</h3>
                            </div>
                        </div>
                        <div class="col active">
                            <div class="text-center border border-bottom-6px p-2 text-primary">
                                <i class="la-3x mb-2 las la-check-circle"></i>
                                <h3 class="fs-14 fw-600 d-none d-lg-block">{{ translate('5. Confirmation') }}</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Order Confirmation -->
    <section class="py-4">
        <div class="container text-left">
            <div class="row">
                <div class="col-xl-8 mx-auto">
                    @php
                        $first_order = null;
                        $shipping = [];
                        
                        if ($combined_order && $combined_order->orders) {
                            $first_order = $combined_order->orders->first();
                            if ($first_order && $first_order->shipping_address) {
                                $shipping = json_decode($first_order->shipping_address, true) ?? [];
                            }
                        }
                    @endphp

                    <!-- Order Confirmation Text-->
                    <div class="text-center py-4 mb-0">
                        <h1 class="mb-2 fs-28 fw-500 text-success">{{ translate('Thank You for Your Order!')}}</h1>
                        <p class="fs-13 ">
                            {{ translate('A copy of your order summary has been sent to') }}
                            <strong>{{ $shipping['email'] ?? '' }}</strong>
                        </p>
                    </div>

                    <!-- Order Summary -->
                    <div class="mb-4 bg-white p-4 border">
                        <h5 class="fw-600 mb-3 fs-16 pb-2 border-bottom">{{ translate('Order Summary')}}</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <table class="table fs-14">
                                    <tr>
                                        <td class="w-50 fw-600 border-top-0 pl-0 py-2">{{ translate('Order date')}}:</td>
                                        <td class="border-top-0 py-2">{{ $first_order ? date('d-m-Y H:i A', $first_order->date) : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-600 border-top-0 pl-0 py-2">{{ translate('Name')}}:</td>
                                        <td class="border-top-0 py-2">{{ $shipping['name'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-600 border-top-0 pl-0 py-2">{{ translate('Email')}}:</td>
                                        <td class="border-top-0 py-2">{{ $shipping['email'] ?? '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-600 border-top-0 pl-0 py-2">{{ translate('Shipping address')}}:</td>
                                        <td class="border-top-0 py-2">
                                            {{ $shipping['address'] ?? '' }},
                                            {{ $shipping['city'] ?? '' }},
                                            {{ $shipping['country'] ?? '' }}
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="col-md-6">
                                <table class="table">
                                    <tr>
                                        <td class="fw-600 border-top-0 py-2">{{ translate('Order status')}}:</td>
                                        <td class="border-top-0 py-2">{{ $first_order ? translate(ucfirst(str_replace('_', ' ', $first_order->delivery_status))) : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-600 border-top-0 py-2">{{ translate('Total order amount')}}:</td>
                                        <td class="border-top-0 py-2">{{ $combined_order ? single_price($combined_order->grand_total) : '' }}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-600 border-top-0 py-2">{{ translate('Shipping')}}:</td>
                                        <td class="border-top-0 py-2">{{ translate('Flat shipping rate')}}</td>
                                    </tr>
                                    <tr>
                                        <td class="fw-600 border-top-0 py-2">{{ translate('Payment method')}}:</td>
                                        <td class="border-top-0 py-2">{{ $first_order ? translate(ucfirst(str_replace('_', ' ', $first_order->payment_type))) : '' }}</td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Orders Info -->
                    @if ($combined_order && $combined_order->orders)
                        @foreach ($combined_order->orders as $order)
                        <div class="card shadow-none border rounded-0 mb-3">
                            <div class="card-body">
                                <!-- Order Code -->
                                <div class="text-center py-1 mb-4">
                                    <h2 class="h5 fs-20">
                                        {{ translate('Order Code:')}} 
                                        <span class="fw-700 text-primary">{{ $order->code }}</span>
                                    </h2>
                                </div>
                                <!-- Order Details -->
                                <div>
                                    <h5 class="fw-600 mb-3 fs-16 pb-2">{{ translate('Order Details')}}</h5>
                                    <div>
                                        <table class="table table-responsive-md fs-14">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th width="30%">{{ translate('Product')}}</th>
                                                    <th>{{ translate('Variation')}}</th>
                                                    <th>{{ translate('Quantity')}}</th>
                                                    <th>{{ translate('Delivery Type')}}</th>
                                                    <th class="text-right">{{ translate('Price')}}</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($order->orderDetails as $key => $orderDetail)
                                                    <tr>
                                                        <td>{{ $key+1 }}</td>
                                                        <td>
                                                            @if ($orderDetail->product != null)
                                                                <a href="{{ route('product', $orderDetail->product->slug) }}" target="_blank" class="text-reset">
                                                                    {{ $orderDetail->product->getTranslation('name') }}
                                                                </a>
                                                            @else
                                                                <strong>{{ translate('Product Unavailable') }}</strong>
                                                            @endif
                                                        </td>
                                                        <td>{{ $orderDetail->variation }}</td>
                                                        <td>{{ $orderDetail->quantity }}</td>
                                                        <td>
                                                            @if ($order->shipping_type == 'home_delivery')
                                                                {{ translate('Home Delivery') }}
                                                            @elseif ($order->shipping_type == 'carrier')
                                                                {{ translate('Carrier') }}
                                                            @elseif ($order->shipping_type == 'pickup_point' && $order->pickup_point != null)
                                                                {{ $order->pickup_point->getTranslation('name') }} ({{ translate('Pickup Point') }})
                                                            @endif
                                                        </td>
                                                        <td class="text-right">{{ single_price($orderDetail->price) }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <!-- Order Amounts -->
                                    <div class="row">
                                        <div class="col-xl-5 col-md-6 ml-auto">
                                            <table class="table">
                                                <tr>
                                                    <th>{{ translate('Subtotal')}}</th>
                                                    <td class="text-right">{{ single_price($order->orderDetails->sum('price')) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ translate('Shipping')}}</th>
                                                    <td class="text-right">{{ single_price($order->orderDetails->sum('shipping_cost')) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ translate('Tax')}}</th>
                                                    <td class="text-right">{{ single_price($order->orderDetails->sum('tax')) }}</td>
                                                </tr>
                                                <tr>
                                                    <th>{{ translate('Coupon Discount')}}</th>
                                                    <td class="text-right">{{ single_price($order->coupon_discount) }}</td>
                                                </tr>
                                                <tr>
                                                    <th><span class="fw-600">{{ translate('Total')}}</span></th>
                                                    <td class="text-right"><strong>{{ single_price($order->grand_total) }}</strong></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @endif

                </div>
            </div>
        </div>
    </section>
@endsection
