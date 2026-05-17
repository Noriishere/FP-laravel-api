<?php

namespace App\Console\Commands;

use App\Models\Booking;
use Illuminate\Console\Command;

class ExpireBookings extends Command
{
    protected $signature = 'bookings:expire';

    protected $description =
        'Expire unpaid bookings after 15 minutes';

    public function handle()
    {
        $expiredBookings = Booking::where(
            'payment_status',
            'pending'
        )
            ->where(
                'expired_at',
                '<',
                now()
            )
            ->get();

        foreach ($expiredBookings as $booking) {

            $booking->update([

                'status' => 'cancelled',

                'payment_status' => 'expired',
            ]);
        }

        $this->info(
            $expiredBookings->count()
            .' bookings expired.'
        );
    }
}