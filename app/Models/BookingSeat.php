<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingSeat extends Model
{
    use HasFactory;
    protected $fillable = [
        'schedule_id',
        'booking_id',
        'seat_id'
    ];
}
