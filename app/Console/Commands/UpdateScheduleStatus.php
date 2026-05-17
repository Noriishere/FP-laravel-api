<?php

namespace App\Console\Commands;

use App\Models\Schedule;
use Illuminate\Console\Command;

class UpdateScheduleStatus extends Command
{
    protected $signature =
        'schedules:update-status';

    protected $description =
        'Update schedule status automatically';

    public function handle()
    {
        /*
        |--------------------------------------------------------------------------
        | ON GOING
        |--------------------------------------------------------------------------
        */

        Schedule::where(
            'status',
            'scheduled'
        )
            ->where(
                'departure_time',
                '<=',
                now()
            )
            ->update([
                'status' => 'on-going',
            ]);

        /*
        |--------------------------------------------------------------------------
        | COMPLETED
        |--------------------------------------------------------------------------
        */

        Schedule::where(
            'status',
            'on-going'
        )
            ->where(
                'arrival_time',
                '<=',
                now()
            )
            ->update([
                'status' => 'completed',
            ]);

        $this->info(
            'Schedule statuses updated'
        );
    }
}