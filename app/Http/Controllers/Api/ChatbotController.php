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
            'message' => 'required|string'
        ]);

        $conversation = ChatbotConversation::firstOrCreate(
            [
                'user_id' => auth('api')->id()
            ],
            [
                'state' => 'main_menu',
                'data' => []
            ]
        );

        return response()->json([
            'success' => true,
            'conversation' => $conversation,
            'message' => 'Halo 👋 Selamat datang di Chatbot GASSIN.'
        ]);
    }
}