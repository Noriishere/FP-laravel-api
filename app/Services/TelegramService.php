<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TelegramService
{
    public function send($message): array
    {
        $response = Http::post(
            "https://api.telegram.org/bot".config('services.telegram.bot_token')."/sendMessage",
            [
                'chat_id' => config('services.telegram.chat_id'),
                'text' => $message,
                'parse_mode' => 'Markdown',
            ]
        );

        if (! $response->successful()) {
            throw new \Exception('Gagal mengirim pesan ke Telegram.');
        }

        return $response->json();
    }
}