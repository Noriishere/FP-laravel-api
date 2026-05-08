<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'schedule_id',

        'pickup_stop_id',
        'dropoff_stop_id',

        'order_id',

        'total_seat',
        'total_price',

        'status',
        'payment_status',

        'payment_provider',
        'payment_method',
        'payment_ref',

        'checked_at',
        'checked_by',

        'expired_at',
    ];

    protected $casts = [
        'checked_at' => 'datetime',
        'expired_at' => 'datetime',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function pickupStop()
    {
        return $this->belongsTo(
            RouteStop::class,
            'pickup_stop_id'
        );
    }

    public function dropoffStop()
    {
        return $this->belongsTo(
            RouteStop::class,
            'dropoff_stop_id'
        );
    }

    public function checker()
    {
        return $this->belongsTo(
            User::class,
            'checked_by'
        );
    }

    public function bookingSeats()
    {
        return $this->hasMany(BookingSeat::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    public function getRouteAttribute()
    {
        return $this->schedule?->route;
    }

    public function getOriginAttribute()
    {
        return $this->pickupStop;
    }

    public function getDestinationAttribute()
    {
        return $this->dropoffStop;
    }
}