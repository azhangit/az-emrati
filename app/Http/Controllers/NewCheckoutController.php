<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\NewOrder;
use App\Models\Cart;
use Carbon\Carbon;     
use App\Models\Coupon;
use App\Models\CouponUsage;
use App\Models\Product;
use App\Models\PickupPoint;
use App\Models\PickupPointTranslation;

use App\Models\Address;
use Illuminate\Support\Facades\Session;


class NewCheckoutController extends Controller
{
     public function index()
    {
        if (! auth()->check()) {
            return redirect()->route('login')
                             ->with('error', 'You must be logged in to checkout.');
        }

        $cartItems = Cart::where('user_id', auth()->id())
                         ->with('product')
                         ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('home')
                             ->with('warning', 'Your cart is empty.');
        }

        $subtotal = $cartItems->sum(fn($item) => optional($item->product)->price * $item->quantity);
        $shipping = 28.00;
        $total    = $subtotal + $shipping;
        
        // fetch all enabled pickup points
    $pickup_points = PickupPoint::where('pick_up_status', 1)->get();

        // ← New: shipping_info fetch karen agar address_id set hai
        $firstCart     = $cartItems->first();
        $shipping_info = $firstCart
            ? Address::find($firstCart->address_id)
            : null;

        return view('frontend.checkout.new_checkout', compact(
            'cartItems',
            'subtotal',
            'shipping',
            'total',
            'shipping_info',
             'pickup_points'
        ));
    }

    public function store_shipping_info(Request $request)
{
    $cartItems = Cart::where('user_id', auth()->user()->id)->get();
    if ($cartItems->isEmpty()) {
        flash(translate('Your cart is empty'))->warning();
        return redirect()->route('home');
    }

    // Calculate cart summary
    $subtotal = $cartItems->sum(fn($c) => optional($c->product)->price * $c->quantity);
    $shipping = 28.00;
    $total    = $subtotal + $shipping;

    // Carrier list logic (agar needed)
    $carrier_list = [];
    // … your carrier logic …
    
    
    // fetch all enabled pickup points
    $pickup_points = PickupPoint::where('pick_up_status', 1)->get();

    // **Yahin shipping_info nikaalo**
    $firstCart     = $cartItems->first();
    $shipping_info = $firstCart
        ? Address::find($firstCart->address_id)
        : null;

    // Pass sab variables—including shipping_info—into the view
    return view('frontend.checkout.new_checkout', compact(
        'cartItems',
        'subtotal',
        'shipping',
        'total',
        'carrier_list',
        'shipping_info',
        'pickup_points'  
    ));
}


public function store(Request $request)
{
    // Validate the request data.
    $validatedData = $request->validate([
        'delivery_type'        => 'required|in:ship,pickup',
        'delivery_country'     => 'required|string|max:255',
        'delivery_phone'       => 'required|string|max:20',
        'delivery_first_name'  => 'required|string|max:255',
        'delivery_last_name'   => 'required|string|max:255',
        'delivery_address'     => 'required|string',
        'delivery_apartment'   => 'nullable|string|max:255',
        'delivery_city'        => 'required|string|max:255',
        'payment_method'       => 'required|in:creditCard,paypal',
        'stripe_token'         => 'nullable|string',
        'billing_country'      => 'nullable|string|max:255',
        'billing_phone'        => 'nullable|string|max:20',
        'billing_first_name'   => 'nullable|string|max:255',
        'billing_last_name'    => 'nullable|string|max:255',
        'billing_address'      => 'nullable|string',
        'billing_apartment'    => 'nullable|string|max:255',
        'billing_city'         => 'nullable|string|max:255',
    ]);

    // Calculate the order amount dynamically from the cart.
    $carts = Cart::where('user_id', auth()->id())->get();
    $subtotal = 0;
    foreach ($carts as $cartItem) {
        $product = Product::find($cartItem->product_id);
        // Use your helper to calculate the product price; adjust if needed.
        $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem->quantity;
    }
    // Define a fixed shipping cost and calculate VAT (5% of subtotal)
    $shipping = 28.00;
    $vat = $subtotal * 0.05;
    $total = $subtotal + $shipping + $vat;
    // Convert total amount to cents (assuming currency requires cents)
    $amountInCents = round($total * 100);

    // Process Stripe payment if a token is provided.
    if ($request->stripe_token) {
        try {
            \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
            $charge = \Stripe\Charge::create([
                'amount'      => $amountInCents,
                'currency'    => 'aed',
                'source'      => $request->stripe_token,
                'description' => 'Order Payment',
            ]);
        } catch (\Exception $e) {
            // For AJAX, return JSON error. Otherwise, redirect back with error.
            if ($request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 500);
            }
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // Create the new order record using the validated data.
    $newOrder = NewOrder::create($validatedData);

    // Clear user's cart after checkout.
    Cart::where('user_id', auth()->id())->delete();

    // Return response based on request type.
    if ($request->ajax()) {
        return response()->json(['redirect_url' => route('newcheckout.confirmation', $newOrder->id)]);
    }

    return redirect()->route('newcheckout.confirmation', $newOrder->id)
                     ->with('success', 'Order placed successfully!');
}

    // Fetch products in the cart
    public function getCartProducts()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        
        $subtotal = $cartItems->sum(function ($cartItem) {
            return $cartItem->product->price * $cartItem->quantity;
        });

        $shipping = 28.00;
        $total = $subtotal + $shipping;

        return view('frontend.checkout.cart_summary', compact('cartItems', 'subtotal', 'shipping', 'total'));
    }

    // Order confirmation page
    public function confirmation($id)
    {
        $order = NewOrder::findOrFail($id);
        return view('frontend.checkout.confirmation', compact('order'));
    }
    public function calculate_shipping_new(Request $request)
{
    // Fetch cart items for the authenticated user (with product data)
    $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();

    if ($cartItems->isEmpty()) {
        flash(translate('Your cart is empty'))->warning();
        return redirect()->route('home');
    }

    $shipping = 0;

    // Loop over each cart item to calculate shipping cost using your logic
    foreach ($cartItems as $key => $cartItem) {
        $product = $cartItem->product; // Already eager loaded
        $userKey = $product->user_id; // User id for this product

        // Check if shipping type is not carrier-wise or if a pickup point is selected
        if (get_setting('shipping_type') != 'carrier_wise_shipping' ||
            (isset($request['shipping_type_' . $userKey]) && $request['shipping_type_' . $userKey] == 'pickup_point')) {

            if (isset($request['shipping_type_' . $userKey]) && $request['shipping_type_' . $userKey] == 'pickup_point') {
                // If pickup point is selected, shipping cost is zero
                $cartItem->shipping_type = 'pickup_point';
                $cartItem->pickup_point = $request['pickup_point_id_' . $userKey] ?? null;
                $shipping_cost = 0;
            } else {
                // Otherwise, use home delivery shipping cost
                $cartItem->shipping_type = 'home_delivery';
                $shipping_cost = getShippingCost($cartItems, $key);
            }
        } else {
            // For carrier-wise shipping
            $cartItem->shipping_type = 'carrier';
            $cartItem->carrier_id = $request['carrier_id_' . $userKey] ?? null;
            $shipping_cost = getShippingCost($cartItems, $key, $cartItem->carrier_id);
        }

        $shipping += $shipping_cost;
        // Optionally update the cart item if you need to persist shipping details:
        $cartItem->shipping_cost = $shipping_cost;
        $cartItem->save();
    }

    // Now, pass the calculated shipping cost (and cart items, if needed) to the view.
    // In your blade file, you can display it as:
    // <p><strong>Shipping: AED {{ number_format($shipping, 2) }}</strong></p>
    return view('frontend.checkout.new_checkout', compact('cartItems', 'shipping'));
}





public function applyCoupon(Request $request)
{
    $request->validate(['code'=>'required|string']);
    $coupon = Coupon::where('code', $request->code)->first();
    if (! $coupon) {
        return response()->json(['error'=>'Invalid coupon!'], 422);
    }

    // **Safe expiry check: parse into Carbon first**
    // agar start_date integer ho (timestamp), warna string parse karo
    $start = is_numeric($coupon->start_date)
        ? Carbon::createFromTimestamp($coupon->start_date)
        : Carbon::parse($coupon->start_date);
    $end   = is_numeric($coupon->end_date)
        ? Carbon::createFromTimestamp($coupon->end_date)
        : Carbon::parse($coupon->end_date);

    if (Carbon::now()->lt($start) || Carbon::now()->gt($end)) {
        return response()->json(['error'=>'Coupon expired!'], 422);
    }

    // calculate discount
    $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
    $subtotal  = $cartItems->sum(fn($c)=> $c->product->price * $c->quantity);
    $discount  = $coupon->discount_type=='percent'
                 ? ($subtotal * $coupon->discount/100)
                 : $coupon->discount;

    Session::put('coupon_code', $coupon->code);
    Session::put('coupon_discount', $discount);
    Session::put('coupon_applied', true);

    // recompute summary vars
    $subtotal      = max(0, $subtotal - $discount);
    $shipping      = Session::get('shipping_cost', 28.00);
    $shipping_info = Address::find(optional($cartItems->first())->address_id);

    $html = view('frontend.checkout.partials.order_summary', compact(
        'coupon','cartItems','subtotal','shipping','shipping_info'
    ))->render();

    return response()->json(['html'=>$html]);
}

public function removeCoupon(Request $request)
{
    Session::forget(['coupon_code','coupon_discount','coupon_applied']);

    $cartItems     = Cart::where('user_id', auth()->id())->with('product')->get();
    $subtotal      = $cartItems->sum(fn($c)=> $c->product->price * $c->quantity);
    $shipping      = Session::get('shipping_cost', 28.00);
    $shipping_info = Address::find(optional($cartItems->first())->address_id);

    $html = view('frontend.checkout.partials.order_summary', compact(
        'cartItems','subtotal','shipping','shipping_info'
    ))->render();

    return response()->json(['html'=>$html]);
}


    protected function renderSummary()
    {
        $cartItems = Cart::where('user_id', auth()->id())->with('product')->get();
        $subtotal  = $cartItems->sum(fn($c)=> $c->product->price * $c->quantity);
        $shipping  = Session::get('shipping_cost', 28.00);
        $discount  = Session::get('coupon_discount', 0);
        $subtotal -= $discount;
        return view('frontend.checkout.partials.order_summary', compact('cartItems','subtotal','shipping'));
    }
    
public function calculateShipping(Request $request)
{
    $cartItems = Cart::where('user_id', auth()->id())
                     ->with('product')
                     ->get();

    // Determine delivery type (from AJAX payload or session fallback)
    $type = $request->input('delivery_type', Session::get('delivery_type', 'ship'));

    if ($type === 'pickup') {
        // Pickup in store: zero shipping
        $cost = 0;
        Session::put('delivery_type', 'pickup');
        Session::put('pickup_point_id', $request->input('pickup_point_id'));
    } else {
        // Home delivery: your existing logic
        $cost = 0;
        foreach ($cartItems as $item) {
            $cost += getShippingCost(
                $cartItems,
                $item->id,
                $request->input('carrier_id_'.$item->product->user_id)
            );
        }
        Session::put('delivery_type', 'ship');
        Session::forget('pickup_point_id');
    }

    Session::put('shipping_cost', $cost);

    // Recalculate totals
    $running   = $cartItems->sum(fn($c) => $c->product->price * $c->quantity);
    $discount  = Session::get('coupon_discount', 0);
    $subtotal  = max(0, $running - $discount);
    $shipping  = $cost;

    // Get shipping_info only for shipping—ignored for pickup
    $shipping_info = null;
    if ($type === 'ship') {
        $shipping_info = Address::find(optional($cartItems->first())->address_id);
    }

    $html = view('frontend.checkout.partials.order_summary', compact(
        'cartItems','subtotal','shipping','shipping_info'
    ))->render();

    return response()->json(['html' => $html]);
}


}
