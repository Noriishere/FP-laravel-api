<?php

namespace App\Console\Commands;

use App\Services\GenerateScheduleService;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:generate-daily-schedules')]
#[Description('Generate daily schedules automatically')]
class GenerateDailySchedules extends Command
{
    public function __construct(
        protected GenerateScheduleService $service
    ) {
        parent::__construct();
    }

    public function handle(): int
    {
        $this->service->generate(now()->addDay());

        $this->info('Schedule generated successfully.');

        return self::SUCCESS;
    }
}
