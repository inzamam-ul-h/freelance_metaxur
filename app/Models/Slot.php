<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Slot extends Model
{
    use HasFactory;
    protected $fillable = ['availability_id', 'slot_start_time', 'slot_end_time', 'slot_status'];

    // One slot belongs to one availability
    public function availability()
    {
        return $this->belongsTo(Availability::class);
    }

    // One slot can have one booking
    public function booking()
    {
        return $this->hasOne(Booking::class);
    }
}
