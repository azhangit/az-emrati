<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursePurchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'course_id',
        'course_schedule_id',
        'order_id',
        'order_detail_id',
        'payment_method',
        'payment_status',
        'amount',
        'payment_details',
        'transaction_id',
        'selected_date',
        'selected_time',
        'selected_level',
        'code',
    ];

    protected $casts = [
        'selected_date' => 'date',
        // selected_time is stored as string (time range like "9:00 PM - 11:00 PM")
        'payment_details' => 'array',
        'amount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function courseSchedule()
    {
        return $this->belongsTo(CourseSchedule::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function orderDetail()
    {
        return $this->belongsTo(OrderDetail::class);
    }
}
