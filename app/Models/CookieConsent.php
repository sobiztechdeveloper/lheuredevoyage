<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CookieConsent extends Model
{
    protected $fillable = [
        'consent_token',
        'user_id',
        'necessary',
        'analytics',
        'marketing',
        'preferences',
        'choice',
        'consented_at',
        'ip_address',
        'user_agent',
    ];

    protected function casts(): array
    {
        return [
            'necessary' => 'boolean',
            'analytics' => 'boolean',
            'marketing' => 'boolean',
            'preferences' => 'boolean',
            'consented_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
