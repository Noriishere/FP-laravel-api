<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DeleteUnverifiedUsers extends Command
{
    protected $signature =
        'users:delete-unverified';

    protected $description =
        'Delete users that are not verified after 24 hours';

    public function handle()
    {
        $users = User::whereNull(
            'email_verified_at'
        )
            ->where(
                'created_at',
                '<',
                now()->subDay()
            )
            ->get();

        foreach ($users as $user) {

            $user->delete();
        }

        $this->info(
            $users->count()
            .' unverified users deleted.'
        );
    }
}