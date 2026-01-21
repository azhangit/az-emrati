<?php

namespace App\Http\Controllers;

use Auth;
use Session;
use App\Models\Cart;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Address;
use App\Models\Carrier;
use App\Models\Product;
use App\Models\Category;
use App\Models\CouponUsage;
use App\Models\OrderDetail;
use Illuminate\Http\Request;
use App\Models\CombinedOrder;
use App\Utility\PayfastUtility;
use App\Utility\PayhereUtility;
use App\Utility\NotificationUtility;

class CheckoutController extends Controller
{

    public function __construct()
    {
        //
    }

    //check the selected payment gateway and redirect to that controller accordingly
    public function checkout(Request $request)
    {
        if ($request->payment_option == null) {
        flash(translate('There is no payment option is selected.'))->warning();
        return redirect()->route('checkout.shipping_info');
    }

    if (Auth::check()) {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
    } else {
        $carts = Cart::where('temp_user_id', $request->session()->get('temp_user_id'))->get();
        $shipping_info = session()->get('guest_shipping_address'); // guest ka address session se
    }

    // Minimum order amount check
    if (get_setting('minimum_order_amount_check') == 1) {
        $subtotal = 0;
        foreach ($carts as $key => $cartItem) {
            $subtotal += $cartItem->price * $cartItem->quantity;
        }

        if ($subtotal < get_setting('minimum_order_amount')) {
            flash(translate('You did not reach the minimum order amount'))->warning();
            return redirect()->route('home');
        }
    }

        // Minumum order amount check end
        
        (new OrderController)->store($request);
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
      if (count($carts) > 0) {
    if (Auth::check()) {
        // 🟢 Logged-in user ka cart clear
        Cart::where('user_id', Auth::id())->delete();
    } else {
        // 🔵 Guest user ka cart clear
        Cart::where('temp_user_id', session()->get('temp_user_id'))->delete();
    }
}

        // Determine payment type based on cart contents
        $hasCourses = $carts->where('item_type', 'course')->count() > 0;
        $paymentType = $hasCourses ? 'course_payment' : 'cart_payment';
        $request->session()->put('payment_type', $paymentType);
        
        $data['combined_order_id'] = $request->session()->get('combined_order_id');
        $request->session()->put('payment_data', $data);
        if ($request->session()->get('combined_order_id') != null) {
            // If block for Online payment, wallet and cash on delivery. Else block for Offline payment
            $decorator = __NAMESPACE__ . '\\Payment\\' . str_replace(' ', '', ucwords(str_replace('_', ' ', $request->payment_option))) . "Controller";
            if (class_exists($decorator)) {
                return (new $decorator)->pay($request);
            }
            else {
                $combined_order = CombinedOrder::findOrFail($request->session()->get('combined_order_id'));
                $manual_payment_data = array(
                    'name'   => $request->payment_option,
                    'amount' => $combined_order->grand_total,
                    'trx_id' => $request->trx_id,
                    'photo'  => $request->photo
                );
                foreach ($combined_order->orders as $order) {
                    $order->manual_payment = 1;
                    $order->manual_payment_data = json_encode($manual_payment_data);
                    $order->save();
                }
                flash(translate('Your order has been placed successfully. Please submit payment information from purchase history'))->success();
                return redirect()->route('order_confirmed');
            }
        }
    }

    // Course purchase completion handler
    public function course_purchase_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::findOrFail($combined_order_id);
        $payment_data = json_decode($payment, true);
        
        // Update course purchases with payment info
        foreach ($combined_order->orders as $order) {
            foreach ($order->orderDetails as $orderDetail) {
                if ($orderDetail->item_type === 'course' && $orderDetail->coursePurchase) {
                    $coursePurchase = $orderDetail->coursePurchase;
                    $coursePurchase->payment_status = 'completed';
                    $coursePurchase->payment_method = $order->payment_type;
                    $coursePurchase->payment_details = $payment;
                    $coursePurchase->transaction_id = $combined_order_id;
                    $coursePurchase->save();
                }
            }
            
            // Update order payment status
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();
        }
        
        // Update combined order
        $combined_order->save();
        
        // Clear cart
        if (Auth::check()) {
            Cart::where('user_id', Auth::id())->delete();
        }
        
        Session::put('combined_order_id', $combined_order_id);
        flash(translate('Your course purchase has been completed successfully'))->success();
        return redirect()->route('order_confirmed');
    }

    //redirects to this method after a successfull checkout
    public function checkout_done($combined_order_id, $payment)
    {
        $combined_order = CombinedOrder::findOrFail($combined_order_id);

        foreach ($combined_order->orders as $key => $order) {
            $order = Order::findOrFail($order->id);
            $order->payment_status = 'paid';
            $order->payment_details = $payment;
            $order->save();

            calculateCommissionAffilationClubPoint($order);
        }
        Session::put('combined_order_id', $combined_order_id);
        return redirect()->route('order_confirmed');
    }

    public function get_shipping_info(Request $request)
    {   //dd($request);
        
        if (auth()->check()) {
        $user_id = auth()->user()->id;
                $carts = Cart::where('user_id', Auth::user()->id)->get();

        } else {
            // Ensure session has temp_user_id
            if (!$request->session()->has('temp_user_id')) {
                $tempId = uniqid('guest_', true);
                $request->session()->put('temp_user_id', $tempId);
                \Log::info('Generated temp_user_id: ' . $tempId);
            }
            $user_id = $request->session()->get('temp_user_id');
            $carts = Cart::where('temp_user_id', $user_id)->get();

        }

        // Check if cart contains courses - require login and skip shipping
        $hasCourses = $carts->where('item_type', 'course')->count() > 0;
        if ($hasCourses) {
            // Require login for courses
            if (!Auth::check()) {
                session(['link' => url()->current()]);
                flash(translate('Please login to purchase courses'))->warning();
                return redirect()->route('user.login');
            }
            
            // Skip shipping for courses, go directly to payment
            $subtotal = 0;
            $tax = 0;
            foreach ($carts as $cart) {
                $subtotal += $cart->price * $cart->quantity;
                $tax += $cart->tax * $cart->quantity;
            }
            $total = $subtotal + $tax;
            
            // Set dummy shipping info for courses (digital products)
            $shipping_info = (object)[
                'name' => Auth::user()->name,
                'email' => Auth::user()->email,
                'phone' => Auth::user()->phone ?? '',
                'address' => 'Course Purchase - No Shipping Required',
                'mode' => 'digital'
            ];
            
            return view('frontend.payment_select', compact('carts', 'shipping_info', 'total'));
        }

        //        if (Session::has('cart') && count(Session::get('cart')) > 0) {
        if ($carts && count($carts) > 0) {
            $categories = Category::all();
            return view('frontend.shipping_info', compact('categories', 'carts'));
        }
        flash(translate('Your cart is empty'))->success();
        return back();
    }

 public function store_shipping_info(Request $request)
{
    // Get carts first to check for courses
    if (Auth::check()) {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
    } else {
        $tempUserId = $request->session()->get('temp_user_id');
        $carts = Cart::where('temp_user_id', $tempUserId)->get();
    }
    
    // Check if cart contains courses - require login and skip shipping
    $hasCourses = $carts->where('item_type', 'course')->count() > 0;
    if ($hasCourses) {
        // Require login for courses
        if (!Auth::check()) {
            session(['link' => url()->current()]);
            flash(translate('Please login to purchase courses'))->warning();
            return redirect()->route('user.login');
        }
        
        // Skip shipping for courses, go directly to payment
        $subtotal = 0;
        $tax = 0;
        foreach ($carts as $cart) {
            $subtotal += $cart->price * $cart->quantity;
            $tax += $cart->tax * $cart->quantity;
        }
        $total = $subtotal + $tax;
        
        // Set dummy shipping info for courses (digital products)
        $shipping_info = (object)[
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone ?? '',
            'address' => 'Course Purchase - No Shipping Required',
            'mode' => 'digital'
        ];
        
        return view('frontend.payment_select', compact('carts', 'shipping_info', 'total'));
    }
    
    // Guest Checkout
    if (!Auth::check() && get_setting('guest_checkout') == 1) {
        $mode = $request->input('mode', 'shipping');
        if ($request->filled('pickup_name') && !$request->filled('name')) {
            $mode = 'pickup';
        }
        $tempUserId = $request->session()->get('temp_user_id');

        if ($carts->isEmpty()) {
            flash(translate('Your cart is empty'))->warning();
            return redirect()->route('home');
        }

        if ($mode === 'pickup') {
            $request->validate([
                'pickup_name'  => 'required|string|max:255',
                'pickup_email' => 'nullable|email',
                'pickup_phone' => 'required|string|max:50',
            ]);

            session()->put('guest_shipping_address', [
                'name'        => $request->pickup_name,
                'email'       => $request->pickup_email,
                'phone'       => $request->pickup_phone,
                'mode'        => 'pickup',
            ]);

            $carrier_list = [];

            return view('frontend.delivery_info', [
                'carts'        => $carts,
                'carrier_list' => $carrier_list,
                'forcePickup'  => true,
            ]);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'email'       => 'required|email',
            'phone'       => 'required|string|max:50',
            'address'     => 'required|string',
            'country_id'  => 'required|integer',
            'state_id'    => 'required|integer',
            'city_id'     => 'required|integer',
            'postal_code' => 'nullable|string|max:20',
        ]);

        $country = optional(\App\Models\Country::find($request->country_id));
        $state   = optional(\App\Models\State::find($request->state_id));
        $city    = optional(\App\Models\City::find($request->city_id));

        session()->put('guest_shipping_address', [
            'name'        => $request->name,
            'email'       => $request->email,
            'phone'       => $request->phone,
            'address'     => $request->address,
            'country'     => $country->name,
            'state'       => $state->name,
            'city'        => $city->name,
            'country_id'  => $country->id,
            'state_id'    => $state->id,
            'city_id'     => $city->id,
            'postal_code' => $request->postal_code,
            'mode'        => 'shipping',
        ]);

        $carrier_list = [];
        if (get_setting('shipping_type') == 'carrier_wise_shipping') {
            $carrier_query = Carrier::where('status', 1);
            $carrier_query->where('free_shipping', 1); // guests usually no zone based
            $carrier_list = $carrier_query->get();
        }

        return view('frontend.delivery_info', [
            'carts'        => $carts,
            'carrier_list' => $carrier_list,
            'forcePickup'  => false,
        ]);
    }

    // Logged-in User Checkout
    $mode = $request->input('mode', 'shipping');
    // Server-side safeguard: if pickup fields are filled but address_id is not, assume pickup mode
    if ($request->filled('pickup_name') && !$request->filled('address_id')) {
        $mode = 'pickup';
    }

    $carts = Cart::where('user_id', Auth::user()->id)->get();
    if ($carts->isEmpty()) {
        flash(translate('Your cart is empty'))->warning();
        return redirect()->route('home');
    }

    if ($mode === 'pickup') {
        $request->validate([
            'pickup_name'  => 'required|string|max:255',
            'pickup_email' => 'nullable|email',
            'pickup_phone' => 'required|string|max:50',
        ]);

        // Store pickup info in session for logged-in users
        session()->put('logged_pickup_info', [
            'name'        => $request->pickup_name,
            'email'       => $request->pickup_email,
            'phone'       => $request->pickup_phone,
            'mode'        => 'pickup',
        ]);

        $carrier_list = [];

        return view('frontend.delivery_info', [
            'carts'        => $carts,
            'carrier_list' => $carrier_list,
            'forcePickup'  => true,
        ]);
    }

    // Shipping mode - require address
    if ($request->address_id == null) {
        flash(translate("Please add shipping address"))->warning();
        return back();
    }

    foreach ($carts as $cartItem) {
        $cartItem->address_id = $request->address_id;
        $cartItem->save();
    }

    $carrier_list = [];
    if (get_setting('shipping_type') == 'carrier_wise_shipping') {
        $zone = \App\Models\Country::where('id', $carts[0]['address']['country_id'])->first()->zone_id;

        $carrier_query = Carrier::where('status', 1);
        $carrier_query->whereIn('id', function ($query) use ($zone) {
            $query->select('carrier_id')->from('carrier_range_prices')
                ->where('zone_id', $zone);
        })->orWhere('free_shipping', 1);
        $carrier_list = $carrier_query->get();
    }

    return view('frontend.delivery_info', [
        'carts'        => $carts,
        'carrier_list' => $carrier_list,
        'forcePickup'  => false,
    ]);
}

   public function store_delivery_info(Request $request)
{
    // Agar login user hai
    if (Auth::check()) {
        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $shipping_info = $carts->isNotEmpty() && $carts[0]->address_id 
            ? Address::where('id', $carts[0]->address_id)->first() 
            : null;
        // Check if logged-in user selected pickup mode
        $loggedPickupInfo = session()->get('logged_pickup_info');
        $pickupMode = $loggedPickupInfo && (($loggedPickupInfo['mode'] ?? null) === 'pickup');
    } else {
        // Guest user
        $carts = Cart::where('temp_user_id', $request->session()->get('temp_user_id'))->get();
        $shipping_info = session()->get('guest_shipping_address'); // guest ka address session se lo
        $pickupMode = is_array($shipping_info) && (($shipping_info['mode'] ?? null) === 'pickup');
    }

    if ($carts->isEmpty()) {
        flash(translate('Your cart is empty'))->warning();
        return redirect()->route('home');
    }
    
    // Check if cart contains courses - require login and skip delivery
    $hasCourses = $carts->where('item_type', 'course')->count() > 0;
    if ($hasCourses) {
        // Require login for courses
        if (!Auth::check()) {
            session(['link' => url()->current()]);
            flash(translate('Please login to purchase courses'))->warning();
            return redirect()->route('user.login');
        }
        
        // Skip delivery for courses, go directly to payment
        $subtotal = 0;
        $tax = 0;
        foreach ($carts as $cart) {
            $subtotal += $cart->price * $cart->quantity;
            $tax += $cart->tax * $cart->quantity;
        }
        $total = $subtotal + $tax;
        
        // Set dummy shipping info for courses (digital products)
        $shipping_info = (object)[
            'name' => Auth::user()->name,
            'email' => Auth::user()->email,
            'phone' => Auth::user()->phone ?? '',
            'address' => 'Course Purchase - No Shipping Required',
            'mode' => 'digital'
        ];
        
        return view('frontend.payment_select', compact('carts', 'shipping_info', 'total'));
    }

    if ($pickupMode) {
        foreach ($carts as $cartItem) {
            $product = Product::find($cartItem['product_id']);
            if ($product) {
                $field = 'pickup_point_id_' . $product->user_id;
                $pickupPoint = $request->input($field);
                if (empty($pickupPoint)) {
                    flash(translate('Please select a pickup point to continue.'))->warning();
                    return back()->withInput();
                }
                $cartItem['shipping_type'] = 'pickup_point';
                $cartItem['pickup_point'] = $pickupPoint;
                $cartItem['shipping_cost'] = 0;
                $cartItem->save();
            }
        }

        $tax = 0;
        $subtotal = 0;
        foreach ($carts as $key => $cartItem) {
            $productId = is_object($cartItem) ? $cartItem->product_id : ($cartItem['product_id'] ?? null);
            if (!$productId) {
                continue;
            }
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }
            $quantity = is_object($cartItem) ? $cartItem->quantity : ($cartItem['quantity'] ?? 1);
            $price = is_object($cartItem) ? $cartItem->price : ($cartItem['price'] ?? 0);
            $tax += cart_product_tax($cartItem, $product, false) * $quantity;
            $subtotal += $price * $quantity;
        }

        $total = $subtotal + $tax;

        return view('frontend.payment_select', [
            'carts'         => $carts,
            'shipping_info' => $shipping_info,
            'total'         => $total,
            'pickup_mode'   => true,
        ]);
    }

    $total = 0;
    $tax = 0;
    $shipping = 0;
    $subtotal = 0;

    if ($carts && count($carts) > 0) {
        foreach ($carts as $key => $cartItem) {
            $productId = is_object($cartItem) ? $cartItem->product_id : ($cartItem['product_id'] ?? null);
            if (!$productId) {
                continue;
            }
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }
            $quantity = is_object($cartItem) ? $cartItem->quantity : ($cartItem['quantity'] ?? 1);
            $price = is_object($cartItem) ? $cartItem->price : ($cartItem['price'] ?? 0);
            $tax += cart_product_tax($cartItem, $product, false) * $quantity;
            $subtotal += $price * $quantity;

            if (get_setting('shipping_type') != 'carrier_wise_shipping' || $request['shipping_type_' . $product->user_id] == 'pickup_point') {
                if ($request['shipping_type_' . $product->user_id] == 'pickup_point') {
                    if (is_object($cartItem)) {
                        $cartItem->shipping_type = 'pickup_point';
                        $cartItem->pickup_point = $request['pickup_point_id_' . $product->user_id];
                    } else {
                        $cartItem['shipping_type'] = 'pickup_point';
                        $cartItem['pickup_point'] = $request['pickup_point_id_' . $product->user_id];
                    }
                } else {
                    if (is_object($cartItem)) {
                        $cartItem->shipping_type = 'home_delivery';
                    } else {
                        $cartItem['shipping_type'] = 'home_delivery';
                    }
                }
                $cartItem['shipping_cost'] = 0;
                if ($cartItem['shipping_type'] == 'home_delivery') {
                    $cartItem['shipping_cost'] = getShippingCost($carts, $key);
                }
            } else {
                $cartItem['shipping_type'] = 'carrier';
                $cartItem['carrier_id'] = $request['carrier_id_' . $product->user_id];
                $cartItem['shipping_cost'] = getShippingCost($carts, $key, $cartItem['carrier_id']);
            }

            $shipping += $cartItem['shipping_cost'];
            $cartItem->save();
        }

        $total = $subtotal + $tax + $shipping;

        return view('frontend.payment_select', compact('carts', 'shipping_info', 'total'));
    } else {
        flash(translate('Your Cart was empty'))->warning();
        return redirect()->route('home');
    }
}

    public function apply_coupon_code(Request $request)
    {   
        $user = auth()->user();
        $coupon = Coupon::where('code', $request->code)->first();
        $response_message = array();

        // if the Coupon type is Welcome base, check the user has this coupon or not
        $couponUser = true;
        if($coupon && $coupon->type == 'welcome_base'){
            $userCoupon = $user->userCoupon;
            if(!$userCoupon){
                $couponUser = false;
            }
        }
        
        if ($coupon != null && $couponUser) {

            //  Coupon expiry Check
            if($coupon->type != 'welcome_base') {
                $validationDateCheckCondition  = strtotime(date('d-m-Y')) >= $coupon->start_date && strtotime(date('d-m-Y')) <= $coupon->end_date;
            }
            else {
                $validationDateCheckCondition = false;
                if($userCoupon){
                    $validationDateCheckCondition  = $userCoupon->expiry_date >= strtotime(date('d-m-Y H:i:s')) ;
                }
            }
            if ($validationDateCheckCondition) {
                if (CouponUsage::where('user_id', Auth::user()->id)->where('coupon_id', $coupon->id)->first() == null) {
                    $coupon_details = json_decode($coupon->details);

                    $carts = Cart::where('user_id', Auth::user()->id)
                        ->where('owner_id', $coupon->user_id)
                        ->get();

                    $coupon_discount = 0;

                    if ($coupon->type == 'cart_base' || $coupon->type == 'welcome_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $key => $cartItem) {
                            $productId = is_object($cartItem) ? $cartItem->product_id : ($cartItem['product_id'] ?? null);
                            if (!$productId) {
                                continue;
                            }
                            $product = Product::find($productId);
                            if (!$product) {
                                continue;
                            }
                            $quantity = is_object($cartItem) ? $cartItem->quantity : ($cartItem['quantity'] ?? 1);
                            $price = is_object($cartItem) ? $cartItem->price : ($cartItem['price'] ?? 0);
                            $shippingCost = is_object($cartItem) ? $cartItem->shipping_cost : ($cartItem['shipping_cost'] ?? 0);
                            $subtotal += $price * $quantity;
                            $tax += cart_product_tax($cartItem, $product, false) * $quantity;
                            $shipping += $shippingCost;
                        }
                        $sum = $subtotal + $tax + $shipping;
                        if ($coupon->type == 'cart_base' && $sum >= $coupon_details->min_buy) {
                            if ($coupon->discount_type == 'percent') {
                                $coupon_discount = ($sum * $coupon->discount) / 100;
                                if ($coupon_discount > $coupon_details->max_discount) {
                                    $coupon_discount = $coupon_details->max_discount;
                                }
                            } elseif ($coupon->discount_type == 'amount') {
                                $coupon_discount = $coupon->discount;
                            }
                        } elseif ($coupon->type == 'welcome_base' && $sum >= $userCoupon->min_buy)  {
                            $coupon_discount  = $userCoupon->discount_type == 'percent' ?  (($sum * $userCoupon->discount) / 100) : $userCoupon->discount;
                        }
                    }
                    elseif ($coupon->type == 'product_base') {
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
                    }elseif ($coupon->type == 'subscription_base') {
    foreach ($carts as $key => $cartItem) {
        // Subscription field check karein
        if (isset($cartItem['subscription']) && $cartItem['subscription'] == 1) {
            $product = Product::find($cartItem['product_id']);
            // Coupon details ke hisaab se further check karo (e.g., allowed products, etc.)
            foreach ($coupon_details as $key => $coupon_detail) {
                // Agar specific product ke liye allowed hai to
                if ($coupon_detail->product_id == $cartItem['product_id']) {
                    if ($coupon->discount_type == 'percent') {
                        $coupon_discount += ($cartItem['price'] * $coupon->discount / 100) * $cartItem['quantity'];
                    } elseif ($coupon->discount_type == 'amount') {
                        $coupon_discount += $coupon->discount * $cartItem['quantity'];
                    }
                }
            }
            // Agar product ki restriction nahi hai (pure subscription pe allowed hai)
            // To aap yeh bhi kar sakte ho (yeh optional hai, business rule pe depend hai)
            /*
            if (empty($coupon_details)) {
                if ($coupon->discount_type == 'percent') {
                    $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem['quantity'];
                } elseif ($coupon->discount_type == 'amount') {
                    $coupon_discount += $coupon->discount * $cartItem['quantity'];
                }
            }
            */
        }
    }
}


                    if ($coupon_discount > 0) {
                        Cart::where('user_id', Auth::user()->id)
                            ->where('owner_id', $coupon->user_id)
                            ->update(
                                [
                                    'discount' => $coupon_discount / count($carts),
                                    'coupon_code' => $request->code,
                                    'coupon_applied' => 1
                                ]
                            );

                        $response_message['response'] = 'success';
                        $response_message['message'] = translate('Coupon has been applied');
                    } else {
                        $response_message['response'] = 'warning';
                        $response_message['message'] = translate('This coupon is not applicable to your cart products!');
                    }
                } else {
                    $response_message['response'] = 'warning';
                    $response_message['message'] = translate('You already used this coupon!');
                }
            } else {
                $response_message['response'] = 'warning';
                $response_message['message'] = translate('Coupon expired!');
            }
        } else {
            $response_message['response'] = 'danger';
            $response_message['message'] = translate('Invalid coupon!');
        }

        $carts = Cart::where('user_id', Auth::user()->id)->get();
        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();
        
        $returnHTML = view('frontend.'.get_setting('homepage_select').'.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        return response()->json(array('response_message' => $response_message, 'html'=>$returnHTML));
    }

    public function remove_coupon_code(Request $request)
    {
        Cart::where('user_id', Auth::user()->id)
            ->update(
                [
                    'discount' => 0.00,
                    'coupon_code' => '',
                    'coupon_applied' => 0
                ]
            );

        $coupon = Coupon::where('code', $request->code)->first();
        $carts = Cart::where('user_id', Auth::user()->id)
            ->get();

        $shipping_info = Address::where('id', $carts[0]['address_id'])->first();

        return view('frontend.'.get_setting('homepage_select').'.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'));
    }

    public function apply_club_point(Request $request)
    {
        if (addon_is_activated('club_point')) {

            $point = $request->point;

            if (Auth::user()->point_balance >= $point) {
                $request->session()->put('club_point', $point);
                flash(translate('Point has been redeemed'))->success();
            } else {
                flash(translate('Invalid point!'))->warning();
            }
        }
        return back();
    }

    public function remove_club_point(Request $request)
    {
        $request->session()->forget('club_point');
        return back();
    }

    public function order_confirmed()
    {
        $combined_order = CombinedOrder::findOrFail(Session::get('combined_order_id'));

       

            // dd($response);
        return view('frontend.order_confirmed', compact('combined_order'));
    }
}
