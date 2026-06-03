<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    protected $fillable = [
        'user_id', 'email_notifications', 'sms_notifications', 'language', 'timezone',
    ];

    protected function casts(): array
    {
        return [
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
