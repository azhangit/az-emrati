<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NewOrder extends Model
{
    protected $fillable = [
        'delivery_type',
        'delivery_country',
        'delivery_phone',
        'delivery_first_name',
        'delivery_last_name',
        'delivery_address',
        'delivery_apartment',
        'delivery_city',
        'payment_method',
        'stripe_token',
        'billing_country',
        'billing_phone',
        'billing_first_name',
        'billing_last_name',
        'billing_address',
        'billing_apartment',
        'billing_city',
    ];
}
