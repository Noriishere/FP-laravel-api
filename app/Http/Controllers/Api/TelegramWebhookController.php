<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Resend\Log;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        // Simpan dulu semua request untuk debugging
        Log::info('Telegram Webhook', $request->all());

        return response()->json([
            'success' => true
        ]);
    }
}