<?php

namespace App\Http\Controllers\Api;

use App\Events\AdminReplyReceived;
use App\Http\Controllers\Controller;
use App\Models\ChatMessage;
use Illuminate\Http\Request;

class TelegramWebhookController extends Controller
{
    public function handle(Request $request)
    {
        $message = $request->input('message');

        if (! $message) {
            return response()->json(['success' => true]);
        }

        if (! isset($message['reply_to_message'])) {
            return response()->json(['success' => true]);
        }

        if (! isset($message['text'])) {
            return response()->json(['success' => true]);
        }

        $replyMessageId = $message['reply_to_message']['message_id'];

        $customerMessage = ChatMessage::where(
            'telegram_message_id',
            $replyMessageId
        )->first();

        if (! $customerMessage) {
            return response()->json(['success' => true]);
        }

        $chatMessage = ChatMessage::create([
            'user_id' => $customerMessage->user_id,
            'sender' => 'admin',
            'message' => $message['text'],
        ]);

        event(new AdminReplyReceived($chatMessage));

        return response()->json([
            'success' => true,
        ]);
    }
}
