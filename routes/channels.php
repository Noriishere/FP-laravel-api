<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('chat.{userId}', function ($user, $userId) {

    Log::info('Broadcast Auth', [
        'user' => $user,
        'userId' => $userId,
    ]);

    return $user && $user->id == $userId;
});