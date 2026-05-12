<?php
namespace App\Jobs;

use App\Models\User;
use App\Models\SubscriptionSchedule;
use App\Notifications\SubscriptionDeliveryDueNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendSubscriptionEmailJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $scheduleId;

    public function __construct($scheduleId)
    {
        $this->scheduleId = $scheduleId;
    }

    public function handle()
    {
        $schedule = SubscriptionSchedule::with('subscription.product', 'subscription.user')->find($this->scheduleId);
        if (!$schedule || !$schedule->active) {
            return;
        }

        $subscription = $schedule->subscription;
        if (!$subscription) {
            $schedule->active = false;
            $schedule->save();
            return;
        }

        if (in_array($subscription->status, ['pending_delivery', 'completed', 'cancelled'], true)) {
            return;
        }

        $admin = User::where('user_type', 'admin')->first();
        if ($admin) {
            Notification::send($admin, new SubscriptionDeliveryDueNotification([
                'subscription_id' => $subscription->id,
                'schedule_id' => $schedule->id,
                'user_name' => optional($subscription->user)->name,
                'product_name' => optional($subscription->product)->name,
                'next_send_date' => $schedule->next_send_date,
            ]));
        }

        $subscription->status = 'pending_delivery';
        $subscription->save();

        Log::info('Subscription marked pending delivery and admin notified', [
            'schedule_id' => $schedule->id,
            'subscription_id' => $schedule->subscription_id,
            'subscription_status' => $subscription->status,
        ]);
    }

}
