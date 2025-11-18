<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscription_id',
        'email',
        'frequency_weeks',
        'total_weeks',
        'sent_count',
        'next_send_date',
        'active',
    ];

   public function subscription()
{
    return $this->belongsTo(ProductSubscription::class, 'subscription_id');
}
}
