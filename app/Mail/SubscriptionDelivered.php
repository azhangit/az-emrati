<?php

namespace App\Mail;

use App\Models\ProductSubscription;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SubscriptionDelivered extends Mailable
{
    use Queueable, SerializesModels;

    public $subscription;

    public function __construct(ProductSubscription $subscription)
    {
        $this->subscription = $subscription;
    }

    public function build()
    {
        return $this->subject('Your Subscription Delivery Has Been Processed')
            ->view('emails.subscription_delivered')
            ->with([
                'subscription' => $this->subscription,
            ]);
    }
}

