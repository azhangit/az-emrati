<?php
namespace App\Jobs;

use App\Models\SubscriptionSchedule;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\SubscriptionCreated;

class SendSubscriptionEmailJob implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $scheduleId;

    public function __construct($scheduleId)
    {
        $this->scheduleId = $scheduleId;
    }

// public function handle()
// {
//     $schedule = SubscriptionSchedule::find($this->scheduleId);
//     if (!$schedule) return;

//     $subscription = $schedule->subscription;

//     // Email send
//     Mail::to($schedule->email)->send(new SubscriptionCreated($subscription));

//     // Update table!
//     $schedule->sent_count += 1;
//     if ($schedule->sent_count >= $schedule->total_weeks) {
//         $schedule->active = false;
//     } else {
//         $schedule->next_send_date = now()->addWeeks($schedule->frequency_weeks);
//     }
//     $schedule->save();
// }

}
