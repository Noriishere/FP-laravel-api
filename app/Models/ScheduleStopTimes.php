<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use DateTimeInterface;
class ScheduleStopTimes extends Model
{
    protected $fillable = [
        'schedule_id',
        'route_stop_id',
        'arrival_time',
        'departure_time',
        'actual_arrival_time',
        'actual_departure_time',
        'status',
        'stop_order',
        'delay_minutes',
        'notes',
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
        'departure_time' => 'datetime',
        'actual_arrival_time' => 'datetime',
        'actual_departure_time' => 'datetime',
    ];

    public function schedule()
    {
        return $this->belongsTo(Schedule::class);
    }

    public function stop()
    {
        return $this->belongsTo(RouteStop::class, 'route_stop_id');
    }
    protected function serializeDate(DateTimeInterface $date)
    {
        return $date
            ->setTimezone('Asia/Jakarta')
            ->format('Y-m-d H:i:s');
    }
}