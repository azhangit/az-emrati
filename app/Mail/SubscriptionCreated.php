<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\ProductSubscription;

class SubscriptionCreated extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;

    public function __construct(ProductSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function build()
    {
        return $this->subject('New Product Subscription')
            ->view('emails.subscription_created')
            ->with([
                'subscription' => $this->subscription,
            ]);
    }
}
