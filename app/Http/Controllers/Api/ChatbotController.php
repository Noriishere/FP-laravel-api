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
        return Schedule::with([
            'route',
            'route.stops',
            'seats',
            'bookings.bookingSeats',
            'bookings.pickupStop',
            'bookings.dropoffStop',
        ])
            ->where('departure_time', '>=', now()->addHours(2))
            ->whereHas('route.stops', function ($q) use ($origin) {
                $q->where('name', 'like', "%{$origin}%")
                    ->where('is_pickup', true);
            })
            ->whereHas('route.stops', function ($q) use ($destination) {
                $q->where('name', 'like', "%{$destination}%")
                    ->where('is_dropoff', true);
            })
            ->orderBy('departure_time')
            ->limit(3)
            ->get();
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

                $text .=
                    '🕒 '.Carbon::parse($schedule->departure_time)->format('d M Y H:i')."\n";

                $text .=
                    '🚌 Kendaraan : '.$schedule->vehicle?->name."\n\n";
            }

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
