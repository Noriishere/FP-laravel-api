<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingSeat;
use App\Models\Driver;
use App\Models\Schedule;
use App\Models\Seat;
use App\Models\User;
use App\Models\Vehicle;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Route;
use App\Models\RouteStop;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Route::factory()->count(3)->create()->each(function ($route) {

            $polyline = json_decode($route->polyline, true);

            if (!$polyline || count($polyline) < 3) return;

            $midIndex = floor(count($polyline) / 2);
            $midPoint = $polyline[$midIndex];

            $points = [
                [
                    'name' => $route->origin_name,
                    'lat' => $route->origin_lat,
                    'lng' => $route->origin_lng,
                ],
                [
                    'name' => 'Stop Tengah',
                    'lat' => $midPoint[0],
                    'lng' => $midPoint[1],
                ],
                [
                    'name' => $route->destination_name,
                    'lat' => $route->destination_lat,
                    'lng' => $route->destination_lng,
                ],
            ];

            foreach ($points as $index => $point) {
                RouteStop::create([
                    'route_id' => $route->id,
                    'name' => $point['name'],
                    'lat' => $point['lat'],
                    'lng' => $point['lng'],
                    'order' => $index
                ]);
            }
        });
        User::factory()->count(5)->create();
        $drivers = User::factory()->count(3)->create([
            'role' => 'driver'
        ]);

        foreach ($drivers as $user) {
            Driver::create([
                'user_id' => $user->id
            ]);
        }
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
        $routeIds = Route::has('stops')->pluck('id');

        $schedules = Schedule::factory()->count(5)->create([
            'route_id' => $routeIds->random()
        ]);
        foreach ($schedules as $schedule) {
            for ($i = 1; $i <= $schedule->vehicle->capacity; $i++) {
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
