<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApiCrashLog extends Model
{
    protected $fillable = [
        'user_id',
        'request_id',

        'method',
        'url',

        'status_code',

        'message',
        'trace',

        'request_body',

        'ip',
        'user_agent',
    ];

    protected $casts = [
        'request_body' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function isServerError(): bool
    {
        return $this->status_code >= 500;
    }
}
