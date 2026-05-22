<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = ['verification_status', 'status', 'user_id', 'phone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function documents()
    {
        return $this->hasMany(DriverDocument::class, 'driver_id');
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}
