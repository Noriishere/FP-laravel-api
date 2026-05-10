<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Route extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'distance',
        'polyline',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function stops()
    {
        return $this->hasMany(RouteStop::class)
            ->orderBy('order');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function origin()
    {
        return $this->hasOne(RouteStop::class)
            ->orderBy('order');
    }

    public function destination()
    {
        return $this->hasOne(RouteStop::class)
            ->orderByDesc('order');
    }

    public function getOriginAttribute()
    {
        return $this->stops->first();
    }

    public function getDestinationAttribute()
    {
        return $this->stops->last();
    }
}
