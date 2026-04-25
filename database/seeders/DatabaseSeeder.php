<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Driver;
use App\Models\Route;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
{
    User::factory()->count(5)->create();
    User::create([
    'name' => 'Admin',
    'email' => 'bagasb65nurdiansyah777@gmail.com',
    'password' => 'admin123',
    'role' => 'admin',
    'email_verified_at' => now()
    ]);
    User::create([
    'name' => 'Akmal',
    'email' => 'miawaugch@gmail.com',
    'password' => 'admin123',
    'role' => 'customer',
    'email_verified_at' => now()
    ]);
    Driver::factory()->count(3)->create();
    Vehicle::factory()->count(2)->create();
    Route::factory()->count(3)->create();
    $schedules = Schedule::factory()->count(5)->create();

    foreach ($schedules as $schedule) {
        $capacity = $schedule->vehicle->capacity;

        for ($i = 1; $i <= $capacity; $i++) {
            Seat::create([
                'schedule_id' => $schedule->id,
                'seat_number' => $i,
                'status' => 'available'
            ]);
        }
    }

    $bookings = Booking::factory()->count(10)->create();

    foreach ($bookings as $booking) {
        $seats = Seat::where('schedule_id', $booking->schedule_id)
            ->inRandomOrder()
            ->take(rand(1, 3))
            ->get();

        foreach ($seats as $seat) {
            DB::table('booking_seats')->insert([
                'booking_id' => $booking->id,
                'seat_id' => $seat->id
            ]);
        }
    }
}
}
