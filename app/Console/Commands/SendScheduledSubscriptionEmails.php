<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionSchedule;
use App\Jobs\SendSubscriptionEmailJob;
use Illuminate\Support\Facades\Schema;

class SendScheduledSubscriptionEmails extends Command
{
    protected $signature = 'send:scheduled-subscription-emails';
    protected $description = 'Send emails for active subscriptions based on schedule';

    public function handle()
    {
        if (!Schema::hasTable('subscription_schedules')) {
            $this->warn('subscription_schedules table not found. Skipping.');
            return self::SUCCESS;
        }

        $schedules = SubscriptionSchedule::where('active', true)
            ->where('next_send_date', '<=', now())
            ->whereHas('subscription', function ($q) {
                $q->whereNotIn('status', ['pending_delivery', 'completed', 'cancelled']);
            })
            ->get();

        $dispatched = 0;
        foreach ($schedules as $schedule) {
            dispatch(new SendSubscriptionEmailJob($schedule->id));
            $dispatched++;
        }

        $this->info("Subscription check complete. Due schedules: {$dispatched}");
        return self::SUCCESS;
    }
}
