<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Driver;
use App\Models\DriverDocument;
use App\Models\Route;
use App\Models\RouteStop;
use App\Models\Schedule;
use App\Models\ScheduleStopTimes;
use App\Models\Seat;
use App\Models\User;
use App\Models\Vehicle;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Route::factory()
        //     ->count(3)
        //     ->create()
        //     ->each(function ($route) {

        //         $polyline = json_decode($route->polyline, true);

        //         if (!$polyline || count($polyline) < 3) {
        //             return;
        //         }

        //         $cities = [
        //             [
        //                 'name' => 'Jakarta',
        //                 'address' => 'Jakarta, Indonesia',
        //                 'lat' => -6.200000,
        //                 'lng' => 106.816666,
        //             ],
        //             [
        //                 'name' => 'Bandung',
        //                 'address' => 'Bandung, Jawa Barat, Indonesia',
        //                 'lat' => -6.914744,
        //                 'lng' => 107.609810,
        //             ],
        //             [
        //                 'name' => 'Semarang',
        //                 'address' => 'Semarang, Jawa Tengah, Indonesia',
        //                 'lat' => -6.966667,
        //                 'lng' => 110.416664,
        //             ],
        //             [
        //                 'name' => 'Surabaya',
        //                 'address' => 'Surabaya, Jawa Timur, Indonesia',
        //                 'lat' => -7.257472,
        //                 'lng' => 112.752090,
        //             ],
        //             [
        //                 'name' => 'Cirebon',
        //                 'address' => 'Cirebon, Jawa Barat, Indonesia',
        //                 'lat' => -6.732023,
        //                 'lng' => 108.552315,
        //             ],
        //             [
        //                 'name' => 'Bekasi',
        //                 'address' => 'Bekasi, Jawa Barat, Indonesia',
        //                 'lat' => -6.234900,
        //                 'lng' => 106.989601,
        //             ],
        //         ];

        //         $selectedCities = collect($cities)
        //             ->shuffle()
        //             ->take(rand(3, 5))
        //             ->values();

        //         $stops = [];

        //         foreach ($selectedCities as $index => $city) {

        //             $stops[] = [
        //                 'route_id' => $route->id,
        //                 'code' => strtoupper(fake()->unique()->bothify('STP###')),
        //                 'name' => $city['name'],
        //                 'address' => $city['address'],
        //                 'lat' => $city['lat'],
        //                 'lng' => $city['lng'],
        //                 'order' => $index + 1,
        //                 'is_pickup' => true,
        //                 'is_dropoff' => true,
        //                 'created_at' => now(),
        //                 'updated_at' => now(),
        //             ];
        //         }

        //         RouteStop::insert($stops);
        //     });
        // User::factory()->count(5)->create();
        // $drivers = User::factory()->count(5)->create([
        //     'role' => 'driver'
        // ]);

        // foreach ($drivers as $user) {

        //     $driver = Driver::create([
        //         'user_id' => $user->id,
        //         'status' => fake()->randomElement(['online', 'offline', 'busy']),
        //         'verification_status' => 'pending'
        //     ]);

        //     // 🔥 RANDOM SCENARIO
        //     $scenario = rand(1, 4);

        //     // 1 = no document
        //     // 2 = pending
        //     // 3 = approved
        //     // 4 = rejected

        //     if ($scenario === 1) {
        //         continue; // 🔥 driver tanpa dokumen (test case)
        //     }

        //     $types = ['ktp', 'sim', 'selfie'];

        //     $statuses = match ($scenario) {
        //         2 => ['pending', 'pending', 'approved'],
        //         3 => ['approved', 'approved', 'approved'],
        //         4 => ['approved', 'rejected', 'approved'],
        //     };

        //     foreach ($types as $i => $type) {
        //         DriverDocument::create([
        //             'driver_id' => $driver->id,
        //             'type' => $type,
        //             'file_path' => "dummy/$type.jpg",
        //             'status' => $statuses[$i],
        //             'note' => $statuses[$i] === 'rejected' ? 'Dokumen tidak valid' : null
        //         ]);
        //     }

        //     // 🔥 SYNC STATUS DRIVER
        //     if ($scenario === 3) {
        //         $driver->update(['verification_status' => 'approved']);
        //     } elseif ($scenario === 4) {
        //         $driver->update(['verification_status' => 'rejected']);
        //     } else {
        //         $driver->update(['verification_status' => 'pending']);
        //     }
        // }
        // User::create([
        //     'name' => 'Admin',
        //     'email' => 'bagasb65nurdiansyah777@gmail.com',
        //     'password' => 'Gassin2026!',
        //     'role' => 'admin',
        //     'email_verified_at' => now()
        // ]);
        // User::create([
        //     'name' => 'Akmal',
        //     'email' => 'mamalnaresh@gmail.com',
        //     'password' => 'Gassin2026!',
        //     'role' => 'admin',
        //     'email_verified_at' => now()
        // ]);
        // User::create([
        //     'name' => 'Aseptian',
        //     'email' => 'septian2996@gmail.com',
        //     'password' => 'Gassin2026!',
        //     'role' => 'admin',
        //     'email_verified_at' => now()
        // ]);
        // User::create([
        //     'name' => 'Asep',
        //     'email' => 'asep@gmail.com',
        //     'password' => 'admin123',
        //     'role' => 'customer',
        //     'email_verified_at' => now()
        // ]);
        // User::create([
        //     'name' => 'AsepGacor',
        //     'email' => 'asepGacor@gmail.com',
        //     'password' => 'admin123',
        //     'role' => 'driver',
        //     'email_verified_at' => now()
        // ]);
        // Driver::factory()->count(3)->create();
        // Vehicle::factory()->count(2)->create();
        // $routeIds = Route::has('stops')->pluck('id');

        // $schedules = Schedule::factory()->count(5)->create([
        //     'route_id' => $routeIds->random()
        // ]);
        // foreach ($schedules as $schedule) {
        //     for ($i = 1; $i <= $schedule->vehicle->capacity; $i++) {
        //         Seat::create([
        //             'schedule_id' => $schedule->id,
        //             'seat_number' => $i,
        //             'status' => 'available'
        //         ]);
        //     }
        // }
        // Booking::factory()->count(10)->create()->each(function ($booking) {

        //     $usedSeatIds = DB::table('booking_seats')
        //         ->join('bookings', 'booking_seats.booking_id', '=', 'bookings.id')
        //         ->where('bookings.schedule_id', $booking->schedule_id)
        //         ->pluck('seat_id');

        //     $availableSeats = Seat::where('schedule_id', $booking->schedule_id)
        //         ->whereNotIn('id', $usedSeatIds)
        //         ->inRandomOrder()
        //         ->limit($booking->total_seat)
        //         ->pluck('id');

        //     foreach ($availableSeats as $seatId) {
        //         DB::table('booking_seats')->insert([
        //             'booking_id' => $booking->id,
        //             'seat_id' => $seatId,
        //             'schedule_id' => $booking->schedule_id, // 🔥 FIX
        //         ]);
        //     }
        // });
        
    }
}
