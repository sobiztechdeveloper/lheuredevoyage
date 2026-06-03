<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AerticketApiLog extends Model
{
    protected $fillable = [
        'correlation_id', 'service', 'method', 'endpoint', 'status_code',
        'duration_ms', 'request_payload', 'response_payload', 'error_message',
    ];

    protected function casts(): array
    {
        return [
            'request_payload' => 'array',
            'response_payload' => 'array',
        ];
    }
}
