<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CourseSchedule extends Model
{
    protected $fillable = [
        'course_id',
        'date',
        'start_time',
        'end_time',
        'course_level',
        'price',
        'images',
        'is_available'
    ];
    
    // Get images as array
    public function getImagesArrayAttribute()
    {
        if (empty($this->images)) {
            return [];
        }
        return explode(',', $this->images);
    }

    protected $casts = [
        'date' => 'date',
        'is_available' => 'boolean',
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}

