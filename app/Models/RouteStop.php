<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RouteStop extends Model
{
    protected $fillable = [
        'route_id',
        'code',
        'name',
        'address',
        'lat',
        'lng',
        'order',
        'is_pickup',
        'is_dropoff',
    ];

    protected $casts = [
        'is_pickup' => 'boolean',
        'is_dropoff' => 'boolean',
    ];
    public function scheduleTimes()
    {
        return $this->hasMany(
            ScheduleStopTime::class,
            'route_stop_id'
        );
    }
    public function route()
    {
        return $this->belongsTo(Route::class);
    }
}
