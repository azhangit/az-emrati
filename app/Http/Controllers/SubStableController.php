<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ProductSubscription;

class SubStableController extends Controller
{
  public function index(Request $request)
    {
        $subscriptions = ProductSubscription::with(['user', 'product'])->orderBy('id', 'desc')->paginate(15);
        return view('backend.subscription.index', [
            'subscriptions' => $subscriptions,
        ]);
    }
public function show($id)
{
    $subscription = \App\Models\ProductSubscription::with(['user', 'product'])->findOrFail($id);

    // User ka address (default ya latest)
    $address = null;
    if ($subscription->user) {
        $address = \App\Models\Address::where('user_id', $subscription->user->id)->latest('id')->first();
    }

    return view('backend.subscription.show', compact('subscription', 'address'));
}

}
