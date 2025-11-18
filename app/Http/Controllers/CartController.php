<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Cart;
use Auth;
use App\Utility\CartUtility;
use Session;
use Cookie;

class CartController extends Controller
{
    public function index(Request $request)
{
    if (auth()->check()) {
        $user_id = auth()->user()->id;
        $carts = Cart::where('user_id', $user_id)->get();
    } else {
        // Ensure guest session has a temp_user_id
        if (!$request->session()->has('temp_user_id')) {
         //   $request->session()->put('temp_user_id', uniqid('guest_', true));
        }
        $user_id = $request->session()->get('temp_user_id');
        $carts = Cart::where('temp_user_id', $user_id)->get();
    }
    //
    // Fetch cart items for authenticated users or guests
    //echo $carts = Cart::where('user_id', $user_id)->get();
    //dd($carts);
    return view('frontend.view_cart', compact('carts'));
}


    public function showCartModal(Request $request)
    {
        $product = Product::find($request->id);
        return view('frontend.'.get_setting('homepage_select').'.partials.addToCart', compact('product'));
    }

    public function showCartModalAuction(Request $request)
    {
        $product = Product::find($request->id);
        return view('auction.frontend.addToCartAuction', compact('product'));
    }

    public function addToCart(Request $request)
{   
    if (auth()->check()) {
        $user_id = auth()->user()->id;
        $temp_user_id = null;
    } else {
        // Generate a temporary user ID for guest users
        if (!$request->session()->has('temp_user_id')) {
            $temp_user_id = uniqid('guest_', true);
            $request->session()->put('temp_user_id', $temp_user_id);
        } else {
            $temp_user_id = $request->session()->get('temp_user_id');
        }

        $user_id = null; // Ensure user_id is null for guests
    }

    // Fetch cart items for both users and guests
    $carts = Cart::where(function ($query) use ($user_id, $temp_user_id) {
        if ($user_id) {
            $query->where('user_id', $user_id);
        } else {
            $query->where('temp_user_id', $temp_user_id);
        }
    })->get();

    // Debugging session to check temp_user_id
  //  \Log::info('Session Data:', session()->all());

    $check_auction_in_cart = CartUtility::check_auction_in_cart($carts);
    $product = Product::find($request->product_id ?? $request->id);
if (!$product) {
    return [
        'status' => 0,
        'cart_count' => count($carts),
        'modal_view' => '<p>Product not found.</p>',
        'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    ];
}
    if ($check_auction_in_cart && $product->auction_product == 0) {
        return [
            'status' => 0,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.removeAuctionProductFromCart')->render(),
            'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
        ];
    }

    $quantity = $request['quantity'];
   // Pehle variant/stock check karo
$product_stock = null;
if ($request->has('variant')) {
    $product_stock = $product->stocks->where('variant', $request->variant)->first();
}

// Agar variant stock mila
if ($product_stock) {
    if ($quantity < $product_stock->min_qty) {
        return [
            'status' => 0,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.minQtyNotSatisfied', [
                'min_qty' => $product_stock->min_qty
            ])->render(),
            'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
        ];
    }
}
// Agar variant stock missing hai → fallback product ka min_qty
else {
    if ($quantity < ($product->min_qty ?? 1)) {
        return [
            'status' => 0,
            'cart_count' => count($carts),
            'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.minQtyNotSatisfied', [
                'min_qty' => $product->min_qty ?? 1
            ])->render(),
            'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
        ];
    }
}


    // Check the color enabled or disabled for the product
    $str = CartUtility::create_cart_variant($product, $request->all());
    $product_stock = $product->stocks->where('variant', $str)->first();

    $cart = Cart::firstOrNew([
        'variation' => $str,
        'user_id' => $user_id, // Use `null` for guests
        'temp_user_id' => $temp_user_id, // Use temp ID for guests
        'product_id' => $request['id']
    ]);

    if ($cart->exists && $product->digital == 0) {
        if ($product->auction_product == 1 && ($cart->product_id == $product->id)) {
            return [
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.auctionProductAlredayAddedCart')->render(),
                'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
            ];
        }
       if ($product_stock && $product_stock->qty < $cart->quantity + $request['quantity']) {
            return [
                'status' => 0,
                'cart_count' => count($carts),
                'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.outOfStockCart')->render(),
                'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
            ];
        }
        $quantity = $cart->quantity + $request['quantity'];
    }

    $price = CartUtility::get_price($product, $product_stock, $request->quantity);
    $tax = CartUtility::tax_calculation($product, $price);

    CartUtility::save_cart_data($cart, $product, $price, $tax, $quantity);

    // Fetch updated cart count
    $carts = Cart::where(function ($query) use ($user_id, $temp_user_id) {
        if ($user_id) {
            $query->where('user_id', $user_id);
        } else {
            $query->where('temp_user_id', $temp_user_id);
        }
    })->get();
    $qty = 0;
    foreach($carts as $cartItem){
        $qty += $cartItem->quantity;
    }
    //dd($qty);
    return [
        'status' => 1,
        'cart_count' => $qty,//count($carts),
        'modal_view' => view('frontend.'.get_setting('homepage_select').'.partials.addedToCart', compact('product', 'cart','qty'))->render(),
        'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    ];
}


    //removes from Cart
    // Removes an item from the cart
public function removeFromCart(Request $request)
{
    Cart::destroy($request->id);

    // Determine user_id or temp_user_id
    if (auth()->check()) {
        $user_id = auth()->user()->id;
        $temp_user_id = null;
    } else {
        $temp_user_id = $request->session()->get('temp_user_id');
        $user_id = null;
    }

    // Fetch updated cart items for user or guest
    $carts = Cart::where(function ($query) use ($user_id, $temp_user_id) {
        if ($user_id) {
            $query->where('user_id', $user_id);
        } else {
            $query->where('temp_user_id', $temp_user_id);
        }
    })->get();

    return [
        'cart_count' => count($carts),
        'cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart_details', compact('carts'))->render(),
        'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    ];
}

// Updates the quantity for a cart item
public function updateQuantity(Request $request)
{
    $cartItem = Cart::findOrFail($request->id);

    if ($cartItem) {
        $product = Product::find($cartItem->product_id);
        $product_stock = $product->stocks->where('variant', $cartItem->variation)->first();
        $quantity = $product_stock->qty;
        $price = $product_stock->price;

        // Discount calculation
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

        if ($quantity >= $request->quantity) {
            if ($request->quantity >= $product->min_qty) {
                $cartItem->quantity = $request->quantity;
            }
        }

        if ($product->wholesale_product) {
            $wholesalePrice = $product_stock->wholesalePrices
                ->where('min_qty', '<=', $request->quantity)
                ->where('max_qty', '>=', $request->quantity)
                ->first();
            if ($wholesalePrice) {
                $price = $wholesalePrice->price;
            }
        }

        $cartItem->price = $price;
        $cartItem->save();
    }

    // Determine user_id or temp_user_id
    if (auth()->check()) {
        $user_id = auth()->user()->id;
        $temp_user_id = null;
    } else {
        $temp_user_id = $request->session()->get('temp_user_id');
        $user_id = null;
    }

    // Fetch updated cart items for user or guest
    $carts = Cart::where(function ($query) use ($user_id, $temp_user_id) {
        if ($user_id) {
            $query->where('user_id', $user_id);
        } else {
            $query->where('temp_user_id', $temp_user_id);
        }
    })->get();

    return [
        'cart_count' => count($carts),
        'cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart_details', compact('carts'))->render(),
        'nav_cart_view' => view('frontend.'.get_setting('homepage_select').'.partials.cart')->render(),
    ];
}

}
