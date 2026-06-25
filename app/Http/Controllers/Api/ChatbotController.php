<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotConversation;
use App\Models\Schedule;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    private function searchNearestSchedule($origin, $destination)
    {
        $query = Schedule::with([
            'route:id,name',
            'route.stops' => function ($q) {
                $q->orderBy('order');
            },
            'vehicle:id,name,plate_number',
            'driver:id,user_id',
            'driver.user:id,name',
            'seats:id,schedule_id,seat_number',

            'bookings' => function ($q) {
                $q->whereIn('payment_status', ['paid', 'pending'])
                    ->select('id', 'schedule_id', 'pickup_stop_id', 'dropoff_stop_id');
            },

            'bookings.pickupStop:id,order',
            'bookings.dropoffStop:id,order',
            'bookings.bookingSeats:id,booking_id,seat_id',
        ]);

        $query->whereHas('route.stops', function ($q) use ($origin) {
            $q->where('name', 'like', "%{$origin}%")
                ->where('is_pickup', true);
        });

        $query->whereHas('route.stops', function ($q) use ($destination) {
            $q->where('name', 'like', "%{$destination}%")
                ->where('is_dropoff', true);
        });

        // Minimal berangkat 2 jam dari sekarang
        $query->where('departure_time', '>=', now()->addHours(2));

        return $query
            ->orderBy('departure_time')
            ->limit(3)
            ->get()
            ->map(function ($schedule) {

                $stops = $schedule->route?->stops?->values();

                $pickup = $stops
                    ?->first(fn ($s) => str_contains(strtolower($s->name), strtolower(request('origin'))));

                $dropoff = $stops
                    ?->first(fn ($s) => str_contains(strtolower($s->name), strtolower(request('destination'))));

                $availableSeats = $schedule->seats->count();

                if ($pickup && $dropoff) {

                    foreach ($schedule->segment_availability ?? [] as $segment) {

                        if (
                            $segment['from_stop']['id'] == $pickup->id &&
                            $segment['to_stop']['id'] == $dropoff->id
                        ) {

                            $availableSeats = $segment['available_seats'];
                            break;
                        }
                    }
                }

                return [
                    'route' => $schedule->route->name,
                    'vehicle' => $schedule->vehicle->name,
                    'plate' => $schedule->vehicle->plate_number,
                    'driver' => $schedule->driver?->user?->name,
                    'departure' => Carbon::parse($schedule->departure_time)
                        ->translatedFormat('l, d F Y H:i'),
                    'arrival' => Carbon::parse($schedule->arrival_time)
                        ->translatedFormat('H:i'),
                    'price' => number_format($schedule->price, 0, ',', '.'),
                    'available_seats' => $availableSeats,
                ];
            });
    }

    public function message(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = strtolower(trim($request->message));

        $conversation = ChatbotConversation::firstOrCreate(
            [
                'user_id' => auth('api')->id(),
            ],
            [
                'state' => 'main_menu',
                'data' => [],
            ]
        );
        if (in_array($message, ['halo', 'hai', 'hi', 'menu', 'start'])) {

            $conversation->update([
                'state' => 'main_menu',
                'data' => [],
            ]);

            return response()->json([
                'success' => true,
                'message' => "Halo 👋 Selamat datang di Chatbot GASSIN.\n\n".
                    "Silakan pilih layanan:\n\n".
                    "1. Cari Jadwal\n".
                    "2. Status Booking\n".
                    "3. Cara Booking\n".
                    '4. Hubungi Admin',
            ]);
        }
        if ($conversation->state == 'main_menu' && $message == '1') {

            $conversation->update([
                'state' => 'ask_origin',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Silakan masukkan kota keberangkatan Anda.',
            ]);
        }
        if ($conversation->state == 'ask_origin') {

            $conversation->update([
                'state' => 'ask_destination',
                'data' => [
                    'origin' => $request->message,
                ],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Sekarang masukkan kota tujuan Anda.',
            ]);
        }
        if ($conversation->state == 'ask_destination') {

            $origin = $conversation->data['origin'];
            $destination = $request->message;

            $schedules = $this->searchNearestSchedule($origin, $destination);

            if ($schedules->isEmpty()) {

                $conversation->update([
                    'state' => 'main_menu',
                    'data' => [],
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Maaf, tidak ada jadwal yang tersedia dalam 2 jam ke depan.',
                ]);
            }

            $text = "🚐 Jadwal ditemukan\n\n";

            foreach ($schedules as $schedule) {

                $text .= "━━━━━━━━━━━━━━━━━━\n";

                $text .= "📍 Rute\n";
                $text .= $schedule['route']."\n\n";

                $text .= "🕒 Berangkat\n";
                $text .= $schedule['departure']."\n\n";

                $text .= "🕔 Tiba\n";
                $text .= $schedule['arrival']."\n\n";

                $text .= "💰 Harga\n";
                $text .= 'Rp '.$schedule['price']."\n\n";

                $text .= "💺 Kursi tersedia\n";
                $text .= $schedule['available_seats']." kursi\n\n";

                $text .= "🚌 Kendaraan\n";
                $text .= $schedule['vehicle']."\n";
                $text .= $schedule['plate']."\n\n";

                $text .= "👨‍✈️ Driver\n";
                $text .= ($schedule['driver'] ?? '-')."\n\n";
            }

            $text .= "━━━━━━━━━━━━━━━━━━\n";
            $text .= 'Ketik MENU untuk kembali.';

            $conversation->update([
                'state' => 'main_menu',
                'data' => [],
            ]);

            return response()->json([
                'success' => true,
                'message' => $text,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Ketik "menu" untuk melihat daftar layanan.',
        ]);
    }
}
