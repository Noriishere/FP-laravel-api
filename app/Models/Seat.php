<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }
    public function bookings()
    {
        return $this->belongsToMany(Booking::class, 'booking_seats');
    }
}
