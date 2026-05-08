<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    public function getOriginAttribute()
    {
        return $this->stops->first();
    }

    public function getDestinationAttribute()
    {
        return $this->stops->last();
    }
}