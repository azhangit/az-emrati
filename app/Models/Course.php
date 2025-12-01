<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'institute_id',
        'date',
        'start_time',
        'end_time',
        'course_module',
        'image',
        'description',
        'course_level',
        'price'
    ];

    public function institute()
    {
        return $this->belongsTo(Institute::class);
    }

    public function schedules()
    {
        return $this->hasMany(CourseSchedule::class);
    }

    public function availableSchedules()
    {
        return $this->hasMany(CourseSchedule::class)->where('is_available', true);
    }
}

