<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'slot_id','booking_status'];

    // One booking belongs to one user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // One booking corresponds to one slot
    public function slot()
    {
        return $this->belongsTo(Slot::class);
    }
}
