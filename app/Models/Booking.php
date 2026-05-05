<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'schedule_id',
        'order_id',
        'total_seat',
        'total_price',
        'status',
        'payment_status',
        'payment_provider',
        'payment_method',
        'payment_ref',
        'expired_at',
        'checked_at',
        'checked_by'
    ];
    protected $casts = [
        'expired_at' => 'datetime',
        'checked_at' => 'datetime',
    ];
    public function checkedBy()
    {
        return $this->belongsTo(User::class, 'checked_by');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    public function seats()
    {
        return $this->belongsToMany(Seat::class, 'booking_seats');
    }
}
