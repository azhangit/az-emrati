<?php
// app/Models/Event.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'name',
         'event_type', 
        'trainer',
        'location_id',
        'date',
        'start_time', 'end_time'
    ];
    
    public function location() {
    return $this->belongsTo(Location::class);
}
}
