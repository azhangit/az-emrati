<div class="container">
    @if( $carts && count($carts) > 0 )
        <div class="row">
            <div class="col-xxl-8 col-xl-10 mx-auto">
                <div class="border bg-white p-3 p-lg-4 text-left">
                    <div class="mb-4">
                        <!-- Headers -->
                        <div class="row gutters-5 d-none d-lg-flex border-bottom mb-3 pb-3 text-secondary fs-12">
                            <div class="col col-md-1 fw-600">{{ translate('Qty')}}</div>
                            <div class="col-md-5 fw-600">{{ translate('Product')}}</div>
                            <div class="col fw-600">{{ translate('Price')}}</div>
                            <div class="col fw-600">{{ translate('Tax')}}</div>
                            <div class="col fw-600">{{ translate('Total')}}</div>
                            <div class="col-auto fw-600">{{ translate('Remove')}}</div>
                        </div>
                        <!-- Cart Items -->
                        <ul class="list-group list-group-flush">
                            @php
                                $total = 0;
                            @endphp
                            @foreach ($carts as $key => $cartItem)
                                @php
                                    // Handle both array and object access
                                    $itemType = is_object($cartItem) ? ($cartItem->item_type ?? 'product') : ($cartItem['item_type'] ?? 'product');
                                    $cartItemId = is_object($cartItem) ? $cartItem->id : ($cartItem['id'] ?? null);
                                    $price = is_object($cartItem) ? $cartItem->price : ($cartItem['price'] ?? 0);
                                    $tax = is_object($cartItem) ? $cartItem->tax : ($cartItem['tax'] ?? 0);
                                    $quantity = is_object($cartItem) ? $cartItem->quantity : ($cartItem['quantity'] ?? 1);
                                    $total = $total + ($price + $tax) * $quantity;
                                    
                                    // Initialize variables for both types
                                    $course = null;
                                    $courseSchedule = null;
                                    $variationData = [];
                                    $product = null;
                                    $product_stock = null;
                                    $cartItemDigital = 0;
                                    $cartItemSubscription = 0;
                                    
                                    // Handle course items
                                    if ($itemType === 'course') {
                                        $course = $cartItem->course ?? null;
                                        $courseSchedule = $cartItem->courseSchedule ?? null;
                                        $variationData = $cartItem->variation ? json_decode($cartItem->variation, true) : [];
                                        $itemName = $course ? $course->course_module : 'Course';
                                        if ($courseSchedule) {
                                            $itemName .= ' - ' . ($courseSchedule->course_level ?? '');
                                        }
                                    } else {
                                        // Handle product items
                                        $productId = is_object($cartItem) ? $cartItem->product_id : ($cartItem['product_id'] ?? null);
                                        if (!$productId) {
                                            continue; // Skip if no product_id
                                        }
                                        $product = get_single_product($productId);
                                        if (!$product) {
                                            continue; // Skip if product not found
                                        }
                                        $variation = is_object($cartItem) ? $cartItem->variation : ($cartItem['variation'] ?? null);
                                        $product_stock = $product->stocks->where('variant', $variation)->first();
                                        $product_name_with_choice = $product->getTranslation('name');
                                        if ($variation != null) {
                                            $product_name_with_choice = $product->getTranslation('name').' - '.$variation;
                                        }
                                        $itemName = $product_name_with_choice;
                                        $cartItemDigital = is_object($cartItem) ? ($cartItem->digital ?? 0) : ($cartItem['digital'] ?? 0);
                                        $cartItemSubscription = is_object($cartItem) ? ($cartItem->subscription ?? 0) : ($cartItem['subscription'] ?? 0);
                                    }
                                @endphp
                                <li class="list-group-item px-0">
                                    <div class="row gutters-5 align-items-center">
                                        <!-- Quantity -->
                                     <div class="col-md-1 col order-1 order-md-0">
    @if ($itemType === 'course')
        <!-- Course items are always quantity 1 and read-only -->
        <span class="fw-700 fs-14">1</span>
    @elseif ($cartItemDigital != 1 && $product->auction_product == 0)
        <div class="d-flex flex-column align-items-start aiz-plus-minus mr-2 ml-0">

            @if($cartItemSubscription == 1)
                <!-- SUBSCRIPTION ITEM: Only show readonly quantity, hide buttons -->
                <input
                    type="number"
                    name="quantity[{{ $cartItemId }}]"
                    class="col border-0 text-left px-0 flex-grow-1 fs-14 input-number"
                    placeholder="1"
                    value="{{ $quantity }}"
                    min="{{ $product->min_qty }}"
                    max="{{ $product_stock ? $product_stock->qty : 1 }}"
                    readonly
                    style="padding-left:0.75rem !important;">
            @else
                <!-- NORMAL ITEM: Show plus/minus and editable quantity -->
                <button
                    class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                    type="button" data-type="plus"
                    data-field="quantity[{{ $cartItemId }}]">
                    <i class="las la-plus"></i>
                </button>
                <input
                    type="number"
                    name="quantity[{{ $cartItemId }}]"
                    class="col border-0 text-left px-0 flex-grow-1 fs-14 input-number"
                    placeholder="1"
                    value="{{ $quantity }}"
                    min="{{ $product->min_qty }}"
                    max="{{ $product_stock ? $product_stock->qty : 1 }}"
                    onchange="updateQuantity({{ $cartItemId }}, this)"
                    style="padding-left:0.75rem !important;">
                <button
                    class="btn col-auto btn-icon btn-sm btn-circle btn-light"
                    type="button" data-type="minus"
                    data-field="quantity[{{ $cartItemId }}]">
                    <i class="las la-minus"></i>
                </button>
            @endif

        </div>
    @elseif($product->auction_product == 1)
        <span class="fw-700 fs-14">1</span>
    @endif
</div>

                                        <!-- Product/Course Image & name -->
                                        <div class="col-md-5 d-flex align-items-center mb-2 mb-md-0">
                                            <span class="mr-2 ml-0">
                                                @if ($itemType === 'course')
                                                    <img src="{{ $course && $course->image ? uploaded_asset($course->image) : static_asset('assets/img/placeholder.jpg') }}"
                                                        class="img-fit size-70px"
                                                        alt="{{ $itemName }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                @else
                                                    <img src="{{ uploaded_asset($product->thumbnail_img) }}"
                                                        class="img-fit size-70px"
                                                        alt="{{ $product->getTranslation('name')  }}"
                                                        onerror="this.onerror=null;this.src='{{ static_asset('assets/img/placeholder.jpg') }}';">
                                                @endif
                                            </span>
                                            <span class="fs-14">
                                                {{ $itemName }}
                                                @if ($itemType === 'course' && isset($variationData['selected_date']))
                                                    <br><small class="text-secondary">{{ translate('Date') }}: {{ $variationData['selected_date'] }}</small>
                                                @endif
                                                @if ($itemType === 'course' && isset($variationData['selected_time']))
                                                    <br><small class="text-secondary">{{ translate('Time') }}: {{ $variationData['selected_time'] }}</small>
                                                @endif
                                                @if ($itemType === 'course' && $course && $course->institute)
                                                    <br><small class="text-secondary">{{ translate('Institute') }}: {{ $course->institute->name ?? '' }}</small>
                                                @endif
                                            </span>
                                        </div>
                                        <!-- Price -->
                                        <div class="col-md col-4 order-2 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Price')}}</span>
                                            <span class="fw-700 fs-14">{{ single_price($price) }}</span>
                                        </div>
                                        <!-- Tax -->
                                        <div class="col-md col-4 order-3 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Tax')}}</span>
                                            <span class="fw-700 fs-14">{{ single_price($tax) }}</span>
                                        </div>
                                        <!-- Total -->
                                        <div class="col-md col-5 order-4 order-md-0 my-3 my-md-0">
                                            <span class="opacity-60 fs-12 d-block d-md-none">{{ translate('Total')}}</span>
                                         <span class="fw-700 fs-16 text-primary">{{ single_price($price * $quantity) }}</span>

                                        </div>
                                        <!-- Remove From Cart -->
                                        <div class="col-md-auto col-6 order-5 order-md-0 text-right">
                                            <a href="javascript:void(0)" onclick="removeFromCartView(event, {{ $cartItemId }})" class="btn btn-icon btn-sm btn-soft-primary bg-soft-secondary-base hov-bg-primary btn-circle">
                                                <i class="las la-trash fs-16"></i>
                                            </a>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Subtotal -->
                    <div class="px-0 py-2 mb-4 border-top d-flex justify-content-between">
                        <span class="opacity-60 fs-14">{{translate('Subtotal')}}</span>
                        <span class="fw-700 fs-16">{{ single_price($total) }}</span>
                    </div>
                    <div class="row align-items-center">
                        <!-- Return to shop -->
                        <div class="col-md-6 text-center text-md-left order-1 order-md-0">
                            <a href="{{ route('home') }}" class="btn  fs-14 fw-700 px-0">
                                <i class="las la-arrow-left fs-16"></i>
                                {{ translate('Return to shop')}}
                            </a>
                        </div>
                        <!-- Continue to Shipping -->
                        <div class="col-md-6 text-center text-md-right">
                            
                                <a href="{{ route('checkout.shipping_info') }}" class="btn btn-primary fs-14 fw-700 rounded-0 px-4">
                                    {{ translate('Continue to Shipping')}}
                                </a>
                            

                        </div>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="row">
            <div class="col-xl-8 mx-auto">
                <div class="border bg-white p-4">
                    <!-- Empty cart -->
                    <div class="text-center p-3">
                        <i class="las la-frown la-3x opacity-60 mb-3"></i>
                        <h3 class="h4 fw-700">{{translate('Your Cart is empty')}}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script type="text/javascript">
    AIZ.extra.plusMinus();
</script>
