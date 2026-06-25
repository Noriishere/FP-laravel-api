<?php

namespace App\Events;

use App\Models\ChatMessage;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AdminReplyReceived implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public ChatMessage $message
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel(
                'chat.'.$this->message->user_id
            ),
        ];
    }

    public function broadcastAs(): string
    {
        return 'admin.reply';
    }

    public function broadcastWith(): array
    {
        return [

            'id'=>$this->message->id,

            'sender'=>$this->message->sender,

            'message'=>$this->message->message,

            'created_at'=>$this->message->created_at,

        ];
    }
}