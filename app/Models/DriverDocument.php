<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DriverDocument extends Model
{
    protected $fillable = [
        'driver_id',
        'type',
        'file_path',
        'status',
        'note'
    ];
    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}
