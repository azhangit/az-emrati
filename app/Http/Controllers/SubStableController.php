<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\SubscriptionDelivered;
use App\Models\ProductSubscription;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class SubStableController extends Controller
{
  public function index(Request $request)
    {
        $scheduleTableExists = Schema::hasTable('subscription_schedules');
        $with = ['user', 'product'];
        if ($scheduleTableExists) {
            $with[] = 'schedule';
        }

        $subscriptions = ProductSubscription::with($with)->orderBy('id', 'desc')->paginate(15);
        return view('backend.subscription.index', [
            'subscriptions' => $subscriptions,
            'scheduleTableExists' => $scheduleTableExists,
        ]);
    }
public function show($id)
{
    $scheduleTableExists = Schema::hasTable('subscription_schedules');
    $with = ['user', 'product'];
    if ($scheduleTableExists) {
        $with[] = 'schedule';
    }

    $subscription = \App\Models\ProductSubscription::with($with)->findOrFail($id);

    // User ka address (default ya latest)
    $address = null;
    if ($subscription->user) {
        $address = \App\Models\Address::where('user_id', $subscription->user->id)->latest('id')->first();
    }

    return view('backend.subscription.show', compact('subscription', 'address', 'scheduleTableExists'));
}

public function markDelivered($id)
{
    $scheduleTableExists = Schema::hasTable('subscription_schedules');
    if (!$scheduleTableExists) {
        return redirect()->back()->with('error', 'Subscription schedule table not found.');
    }

    $subscription = ProductSubscription::with(['schedule', 'user', 'product'])->findOrFail($id);
    $schedule = $subscription->schedule;

    if (!$schedule) {
        return redirect()->back()->with('error', 'Schedule not found for this subscription.');
    }

    if (!$schedule->active || (int) $schedule->sent_count >= (int) $schedule->total_weeks) {
        $subscription->status = 'completed';
        $subscription->save();
        return redirect()->back()->with('success', 'Subscription already completed.');
    }

    $schedule->sent_count = (int) $schedule->sent_count + 1;

    if ($schedule->sent_count >= (int) $schedule->total_weeks) {
        $schedule->active = false;
        $schedule->next_send_date = null;
        $subscription->status = 'completed';
    } else {
        $frequencyWeeks = max((int) $schedule->frequency_weeks, 1);
        $baseDate = $schedule->next_send_date ? Carbon::parse($schedule->next_send_date) : now();
        $schedule->next_send_date = $baseDate->addWeeks($frequencyWeeks);
        $subscription->status = 'active';
    }

    $schedule->save();
    $subscription->save();

    try {
        if (!empty(optional($subscription->user)->email)) {
            Mail::to($subscription->user->email)->send(new SubscriptionDelivered($subscription));
        }
    } catch (\Throwable $e) {
        \Log::warning('Subscription delivered email skipped', [
            'subscription_id' => $subscription->id,
            'error' => $e->getMessage(),
        ]);
    }

    return redirect()->back()->with('success', 'Subscription delivery marked successfully.');
}

}
