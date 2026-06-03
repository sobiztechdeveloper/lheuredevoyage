<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SupportTicket extends Model
{
    public const STATUSES = ['open', 'awaiting_customer', 'awaiting_admin', 'closed'];

    public const PRIORITIES = ['low', 'normal', 'high'];

    public const CATEGORIES = ['general', 'booking', 'billing', 'technical'];

    protected $fillable = [
        'user_id',
        'reference',
        'subject',
        'category',
        'status',
        'priority',
        'closed_at',
    ];

    protected function casts(): array
    {
        return [
            'closed_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function replies(): HasMany
    {
        return $this->hasMany(SupportTicketReply::class)->orderBy('created_at');
    }

    public static function generateReference(): string
    {
        return 'TKT-'.strtoupper(substr(uniqid(), -8));
    }

    public function isClosed(): bool
    {
        return $this->status === 'closed';
    }
}
