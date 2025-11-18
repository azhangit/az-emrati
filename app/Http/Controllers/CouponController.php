<?php

namespace App\Http\Controllers;

use App\Http\Requests\CouponRequest;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\CouponUsage;

class CouponController extends Controller
{
    public function __construct() {
        // Staff Permission Check
        $this->middleware(['permission:view_all_coupons'])->only('index');
        $this->middleware(['permission:add_coupon'])->only('create');
        $this->middleware(['permission:edit_coupon'])->only('edit');
        $this->middleware(['permission:delete_coupon'])->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $coupons = Coupon::where('user_id', User::where('user_type', 'admin')->first()->id)->orderBy('id','desc')->get();
        return view('backend.marketing.coupons.index', compact('coupons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backend.marketing.coupons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CouponRequest $request)
    {
        $user_id = User::where('user_type', 'admin')->first()->id;
        $status = $request->type == 'welcome_base' ? 0 : 1;
        Coupon::create($request->validated() + [
            'user_id' => $user_id,
            'status' => $status,
        ]);
        flash(translate('Coupon has been saved successfully'))->success();
        return redirect()->route('coupon.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $coupon = Coupon::findOrFail(decrypt($id));
        return view('backend.marketing.coupons.edit', compact('coupon'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(CouponRequest $request, Coupon $coupon)
    {
        $coupon->update($request->validated());
        flash(translate('Coupon has been updated successfully'))->success();
        return redirect()->route('coupon.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Coupon::destroy($id);
        flash(translate('Coupon has been deleted successfully'))->success();
        return redirect()->route('coupon.index');
    }

    public function get_coupon_form(Request $request)
    {
        if($request->coupon_type == "product_base") {
            $admin_id = \App\Models\User::where('user_type', 'admin')->first()->id;
            $products = filter_products(\App\Models\Product::where('user_id', $admin_id))->get();
            return view('partials.coupons.product_base_coupon', compact('products'));
        }
         elseif($request->coupon_type == "subscription_base") {
        $admin_id = \App\Models\User::where('user_type', 'admin')->first()->id;
        // Only products with category_id=4 and published=1
        $products = \App\Models\Product::where('user_id', $admin_id)
            ->where('category_id', 4)
            ->where('published', 1)
            ->get();
        return view('partials.coupons.subscription_base_coupon', compact('products'));
    }
        elseif($request->coupon_type == "cart_base"){
            return view('partials.coupons.cart_base_coupon');
        }
        elseif($request->coupon_type == "welcome_base"){
            return view('partials.coupons.welcome_base_coupon');
        }
    }

    public function get_coupon_form_edit(Request $request)
    {
        if($request->coupon_type == "product_base") {
            $coupon = Coupon::findOrFail($request->id);
            $admin_id = \App\Models\User::where('user_type', 'admin')->first()->id;
            $products = filter_products(\App\Models\Product::where('user_id', $admin_id))->get();
            return view('partials.coupons.product_base_coupon_edit',compact('coupon', 'products'));
        }
         elseif($request->coupon_type == "subscription_base") {
        $admin_id = \App\Models\User::where('user_type', 'admin')->first()->id;
        // Only products with category_id=4 and published=1
        $products = \App\Models\Product::where('user_id', $admin_id)
            ->where('category_id', 4)
            ->where('published', 1)
            ->get();
        return view('partials.coupons.subscription_base_coupon', compact('products'));
    }
        elseif($request->coupon_type == "cart_base"){
            $coupon = Coupon::findOrFail($request->id);
            return view('partials.coupons.cart_base_coupon_edit',compact('coupon'));
        }
        elseif($request->coupon_type == "welcome_base"){
            $coupon = Coupon::findOrFail($request->id);
            return view('partials.coupons.welcome_base_coupon_edit',compact('coupon'));
        }
    }

    public function updateStatus(Request $request)
    {
        foreach (Coupon::where('type', 'welcome_base')->get() as $welcome_coupon) {
            $welcome_coupon->status = 0;
            $welcome_coupon->save();
        }
        
        $coupon = Coupon::findOrFail($request->id);
        $coupon->status = $request->status;
        if ($coupon->save()) {
            return 1;
        }
        return 0;
    }
    
public function apply_coupon_code(Request $request)
{
    try {
        $user = auth()->user();
        $coupon = Coupon::where('code', $request->code)->first();
        $response_message = [];

        // Check if coupon exists and if coupon type is welcome_base, validate the user's coupon availability.
        $couponUser = true;
        if ($coupon && $coupon->type == 'welcome_base') {
            $userCoupon = $user->userCoupon; // Ensure this relationship exists on your User model.
            if (!$userCoupon) {
                $couponUser = false;
            }
        }
        
        if ($coupon && $couponUser) {
            // Coupon expiry check
            if ($coupon->type != 'welcome_base') {
                $validationDateCheckCondition = strtotime(date('d-m-Y')) >= $coupon->start_date 
                    && strtotime(date('d-m-Y')) <= $coupon->end_date;
            } else {
                $validationDateCheckCondition = false;
                if (isset($userCoupon)) {
                    $validationDateCheckCondition = $userCoupon->expiry_date >= strtotime(date('d-m-Y H:i:s'));
                }
            }
            
            if ($validationDateCheckCondition) {
                // Check if user has not already used this coupon.
                if (CouponUsage::where('user_id', $user->id)->where('coupon_id', $coupon->id)->first() == null) {
                    
                    // Decode coupon details. If invalid, assign default values.
                    $coupon_details = json_decode($coupon->details);
                    if (!$coupon_details) {
                        \Log::error('Invalid coupon details for coupon code: ' . $coupon->code);
                        // Use default values so that the process doesn't fail completely.
                        $coupon_details = (object)[
                            'min_buy' => 0,
                            'max_discount' => 0,
                        ];
                    }
                    
                    // Get carts for the user that belong to the coupon owner.
                    $carts = Cart::where('user_id', $user->id)
                        ->where('owner_id', $coupon->user_id)
                        ->get();

                    $coupon_discount = 0;

                    if ($coupon->type == 'cart_base' || $coupon->type == 'welcome_base') {
                        $subtotal = 0;
                        $tax = 0;
                        $shipping = 0;
                        foreach ($carts as $cartItem) {
                            $product = Product::find($cartItem->product_id);
                            $subtotal += cart_product_price($cartItem, $product, false, false) * $cartItem->quantity;
                            $tax += cart_product_tax($cartItem, $product, false) * $cartItem->quantity;
                            $shipping += $cartItem->shipping_cost;
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
                        } elseif ($coupon->type == 'welcome_base' && isset($userCoupon) && $sum >= $userCoupon->min_buy) {
                            $coupon_discount = $userCoupon->discount_type == 'percent' 
                                ? (($sum * $userCoupon->discount) / 100) 
                                : $userCoupon->discount;
                        }
                    } elseif ($coupon->type == 'product_base') {
                        foreach ($carts as $cartItem) {
                            $product = Product::find($cartItem->product_id);
                            foreach ($coupon_details as $coupon_detail) {
                                if ($coupon_detail->product_id == $cartItem->product_id) {
                                    if ($coupon->discount_type == 'percent') {
                                        $coupon_discount += (cart_product_price($cartItem, $product, false, false) * $coupon->discount / 100) * $cartItem->quantity;
                                    } elseif ($coupon->discount_type == 'amount') {
                                        $coupon_discount += $coupon->discount * $cartItem->quantity;
                                    }
                                }
                            }
                        }
                    }
                    
                    if ($coupon_discount > 0) {
                        // Apply the discount evenly across the cart items.
                        Cart::where('user_id', $user->id)
                            ->where('owner_id', $coupon->user_id)
                            ->update([
                                'discount' => $coupon_discount / count($carts),
                                'coupon_code' => $request->code,
                                'coupon_applied' => 1
                            ]);

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

        // Fetch updated cart and shipping info
        $carts = Cart::where('user_id', $user->id)->get();
        $shipping_info = \App\Models\Address::find($carts->first()->address_id);
        
        $returnHTML = view('frontend.' . get_setting('homepage_select') . '.partials.cart_summary', compact('coupon', 'carts', 'shipping_info'))->render();
        return response()->json([
            'response_message' => $response_message,
            'html' => $returnHTML
        ]);
    } catch (\Exception $ex) {
        \Log::error('Error in apply_coupon_code: ' . $ex->getMessage());
        return response()->json([
            'response_message' => translate('An error occurred while applying coupon.')
        ], 500);
    }
}



public function remove_coupon_code(Request $request)
{
    // Remove coupon info from the user's cart
    \App\Models\Cart::where('user_id', auth()->id())
        ->update([
            'discount' => 0,
            'coupon_code' => '',
            'coupon_applied' => 0
        ]);
    
    // Recalculate the cart summary without coupon discount
    $cartItems = \App\Models\Cart::where('user_id', auth()->id())->with('product')->get();
    $subtotal = $cartItems->sum(function($cartItem) {
        return cart_product_price($cartItem, $cartItem->product, false, false) * $cartItem->quantity;
    });
    $shipping = 28.00;
    $vat = $subtotal * 0.05;
    $total = $subtotal + $shipping + $vat;
    
    // Render updated cart summary view
    $html = view('partials.coupons.cart_summary', compact('cartItems', 'subtotal', 'shipping', 'total'))->render();

    return response()->json([
        'response_message' => 'Coupon removed successfully!',
        'html' => $html
    ]);
}
}
