<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SubscriptionDeliveryDueNotification extends Notification
{
    use Queueable;

    protected $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toArray($notifiable)
    {
        return [
            'subscription_id' => $this->data['subscription_id'] ?? null,
            'schedule_id' => $this->data['schedule_id'] ?? null,
            'user_name' => $this->data['user_name'] ?? '',
            'product_name' => $this->data['product_name'] ?? '',
            'next_send_date' => $this->data['next_send_date'] ?? null,
            'status' => 'due_for_delivery',
        ];
    }
}

