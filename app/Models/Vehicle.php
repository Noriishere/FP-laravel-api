<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'plate_number',
        'capacity'
    ];
    public function seats()
    {
        return $this->hasMany(Seat::class);
    }
}
