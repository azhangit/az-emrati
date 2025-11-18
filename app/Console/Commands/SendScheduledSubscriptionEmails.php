<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\SubscriptionSchedule;
use App\Jobs\SendSubscriptionEmailJob;

class SendScheduledSubscriptionEmails extends Command
{
    protected $signature = 'send:scheduled-subscription-emails';
    protected $description = 'Send emails for active subscriptions based on schedule';

    public function handle()
    {
        $schedules = SubscriptionSchedule::where('active', true)
            ->whereDate('next_send_date', '<=', now())
            ->get();

        foreach ($schedules as $schedule) {
            dispatch(new SendSubscriptionEmailJob($schedule->id));

            $schedule->sent_count += 1;
            if ($schedule->sent_count >= $schedule->total_weeks) {
                $schedule->active = false;
            } else {
                $schedule->next_send_date = now()->addMinutes($schedule->frequency_weeks);
            }
            $schedule->save();
        }

        $this->info('Subscription emails dispatched successfully.');
    }
}
