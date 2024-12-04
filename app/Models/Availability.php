<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Availability extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'availability_date',
        'availability_start_time',
        'availability_end_time',
        'availability_status'
    ];

    // One availability belongs to one admin
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // One availability can have many slots
    public function slots()
    {
        return $this->hasMany(Slot::class);
    }
}
