<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'origin_name',
        'destination_name',
        'origin_lat',
        'origin_lng',
        'destination_lat',
        'destination_lng',
        'distance',
        'polyline',
    ];
    public function stops()
    {
        return $this->hasMany(RouteStop::class)->orderBy('order');
    }
    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
