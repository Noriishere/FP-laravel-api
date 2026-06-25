<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatbotConversation;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
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

        return response()->json([
            'success' => true,
            'message' => 'Ketik "menu" untuk melihat daftar layanan.',
        ]);
    }
}
