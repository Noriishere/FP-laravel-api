<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ApiCrashLog;
use App\Models\ApiActivityLog;

class CleanupApiLogs extends Command
{
    protected $signature = 'logs:cleanup';

    protected $description = 'Delete old API logs';

    public function handle()
    {
        $days = 3;

        $activityDeleted = ApiActivityLog::where(
            'created_at',
            '<',
            now()->subDays($days)
        )->delete();

        $crashDeleted = ApiCrashLog::where(
            'created_at',
            '<',
            now()->subDays($days)
        )->delete();

        $this->info(
            "Deleted {$activityDeleted} activity logs and {$crashDeleted} crash logs."
        );

        return self::SUCCESS;
    }
}