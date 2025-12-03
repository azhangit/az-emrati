<?php

namespace App\Utility;

use App\Models\Cart;
use Cookie;

class CartUtility
{

    public static function create_cart_variant($product, $request)
    {
        $str = null;
        if (isset($request['color'])) {
            $str = $request['color'];
        }

        if (isset($product->choice_options) && count(json_decode($product->choice_options)) > 0) {
            //Gets all the choice values of customer choice option and generate a string like Black-S-Cotton
           foreach (json_decode($product->choice_options) as $choice) {
    $key = 'attribute_id_' . $choice->attribute_id;
    if (isset($data[$key])) {
        $str .= '-' . str_replace(' ', '', $data[$key]);
    }
}

        }
        return $str;
    }

  public static function get_price($product, $product_stock, $quantity)
{
    // Agar stock na mile → fallback product base price
    if (!$product_stock) {
        $price = $product->unit_price ?? 0; 
    } else {
        $price = $product_stock->price;
    }

    // Auction product price handling
    if ($product->auction_product == 1) {
        $price = $product->bids->max('amount') ?? $price;
    }

    // Wholesale handling
    if ($product->wholesale_product && $product_stock) {
        $wholesalePrice = $product_stock->wholesalePrices
            ->where('min_qty', '<=', $quantity)
            ->where('max_qty', '>=', $quantity)
            ->first();
        if ($wholesalePrice) {
            $price = $wholesalePrice->price;
        }
    }

    // Discount calculation
    $price = self::discount_calculation($product, $price);
    return $price;
}


    public static function discount_calculation($product, $price)
    {
        $discount_applicable = false;

        if (
            $product->discount_start_date == null ||
            (strtotime(date('d-m-Y H:i:s')) >= $product->discount_start_date &&
                strtotime(date('d-m-Y H:i:s')) <= $product->discount_end_date)
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
        return $price;
    }

    public static function tax_calculation($product, $price)
    {
        $tax = 0;
        foreach ($product->taxes as $product_tax) {
            if ($product_tax->tax_type == 'percent') {
                $tax += ($price * $product_tax->tax) / 100;
            } elseif ($product_tax->tax_type == 'amount') {
                $tax += $product_tax->tax;
            }
        }

        return $tax;
    }

    public static function save_cart_data($cart, $product, $price, $tax, $quantity)
    {
        $cart->quantity = $quantity;
        $cart->product_id = $product->id;
        $cart->owner_id = $product->user_id;
        $cart->price = $price;
        $cart->tax = $tax;
        $cart->product_referral_code = null;

        if (Cookie::has('referred_product_id') && Cookie::get('referred_product_id') == $product->id) {
            $cart->product_referral_code = Cookie::get('product_referral_code');
        }

        // Cart::create($data);
        $cart->save();
    }

    public static function check_auction_in_cart($carts)
    {
        foreach ($carts as $cart) {
            if ($cart->product && $cart->product->auction_product == 1) {
                return true;
            }
        }

        return false;
    }
}
