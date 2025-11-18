<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductSubscription;
use App\Models\SubscriptionSchedule;
use App\Jobs\SendSubscriptionEmailJob;
use Illuminate\Support\Facades\Mail;
use App\Models\AttributeValue;

class SubscriptionController extends Controller
{
// Controller method for main subscription page
public function index(Request $request) {
    $products = Product::where('category_id', 4)->where('published', 1)->get();
    $attributeValues = AttributeValue::where('attribute_id', 3)->get();
    return view('subscription', compact('products', 'attributeValues'));
}
public function getProductSizes(Request $request, $product_id) {
    $grind = $request->input('grind'); // e.g. "Aeropress Grind"
    $product = Product::findOrFail($product_id);
    $choiceOptions = is_string($product->choice_options) ? json_decode($product->choice_options, true) : [];
    $sizes = [];

    foreach ($choiceOptions as $opt) {
        if ($opt['attribute_id'] == 1) { // size attribute
        
            foreach ($opt['values'] as $size) {
                $sizeKey = str_replace([' ', 'GM', 'KG'], ['', 'GM', 'KG'], strtoupper($size));
                $grindKey = str_replace(' ', '', $grind);
                $variant = $sizeKey . '-' . $grindKey; // "1KG-AeropressGrind"

                $stock = \App\Models\ProductStock::where('product_id', $product->id)
                    ->where('variant', $variant)
                    ->first();

                $sizes[] = [
                    'size' => $size,
                    'price' => $stock ? $stock->price : $product->unit_price,
                    'variant' => $variant,
                ];
            }
        }
    }
    return response()->json($sizes);
}

    private function makeVariant($weight, $grind) {
        $weightKey = strtoupper(str_replace(' ', '', $weight));
        $grindKey = str_replace(' ', '', $grind);
        return $weightKey . '-' . $grindKey;
    }
public function subscribe(Request $request)
{
    $weekMap = [
        'EVERY WEEK' => 4,
        'EVERY TWO WEEK' => 2,
        'EVERY FOUR WEEKS' => 1
    ];
    $monthMap = [
        '3 MONTH' => 3,
        '6 MONTH' => 6,
        '12 MONTH' => 12
    ];

    $price_per_pack = (float) $request->input('price_per_pack');
    $week_label = strtoupper(trim($request->input('week')));
    $month_label = strtoupper(trim($request->input('month')));

    $weeks = $weekMap[$week_label] ?? 4;
    $months = $monthMap[$month_label] ?? 3;
    $deliveries = $weeks * $months;
    $total_price = $price_per_pack * $deliveries;

    $request->validate([
        'grind_size' => 'required',
        'product_id' => 'required',
        'weight'     => 'required',
        'week'       => 'required',
        'month'      => 'required',
        'price_per_pack' => 'required',
        'quantity' => 'required',
    ]);

    $variant = $this->makeVariant($request->weight, $request->grind_size);

    // Yahan subscription ko variable me save karo:
    $subscription = \App\Models\ProductSubscription::create([
        'user_id'    => auth()->id(),
        
        'product_id' => $request->product_id,
        'grind_size' => $request->grind_size,
        'weight'     => $request->weight,
        'week'       => $request->week,
        'month'      => $request->month,
        'quantity'   =>$request->quantity,
        'price'      => $total_price,
        'status'     => 'active',
    ]);
    $admin_id = \App\Models\User::where('user_type', 'admin')->value('id');


    \DB::table('carts')->insert([
        'user_id'     => auth()->id(),
        'owner_id'     => 9,   
        'product_id'  => $request->product_id,
        'quantity'    => $request->quantity,
        'price'       => $total_price,
        'variation'   => $variant,
        'subscription'   => 1,
        'created_at'  => now(),
        'updated_at'  => now(),
    ]);

    // Schedule record banao
    // Calculate total_weeks sahi logic se:
    // $weeks = 4 (every week), 2 (every two week), 1 (every four weeks)
    if ($weeks == 4) {
        $total_weeks = $months * 4; // every week
    } elseif ($weeks == 2) {
        $total_weeks = $months * 2; // every two week
    } elseif ($weeks == 1) {
        $total_weeks = $months * 1; // every four weeks
    } else {
        $total_weeks = $months * 4; // default (every week)
    }

    $schedule = \App\Models\SubscriptionSchedule::create([
        'subscription_id'   => $subscription->id,
        'email'             => 'jamers786@gmail.com',
        'frequency_weeks'   => $weeks == 4 ? 1 : ($weeks == 2 ? 2 : 4), // frequency in weeks: 1/2/4
        'total_weeks'       => $total_weeks,
        'sent_count'        => 0,
        'next_send_date'    => now()->addWeeks($weeks == 4 ? 1 : ($weeks == 2 ? 2 : 4)),
        'active'            => true,
    ]);

    // Pehla email bhej do (queue job pe)
    // dispatch(new \App\Jobs\SendSubscriptionEmailJob($schedule->id));

    return redirect('/cart')->with('success', 'Subscription added to cart!');
}



}
