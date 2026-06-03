<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Booking extends Model
{
    protected $fillable = [
        'user_id',
        'bookable_type',
        'bookable_id',
        'reference',
        'status',
        'total_amount',
        'currency',
        'booking_data',
        'booked_at',
    ];

    protected function casts(): array
    {
        return [
            'booking_data' => 'array',
            'booked_at' => 'datetime',
            'total_amount' => 'decimal:2',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookable(): MorphTo
    {
        return $this->morphTo();
    }

    public function histories(): HasMany
    {
        return $this->hasMany(UserBookingHistory::class)->orderBy('created_at');
    }

    public function cancellationRequests(): HasMany
    {
        return $this->hasMany(BookingCancellationRequest::class);
    }

    public function pendingCancellationRequest(): HasMany
    {
        return $this->cancellationRequests()->where('status', 'pending');
    }

    public static function generateReference(): string
    {
        return 'LDV-'.strtoupper(substr(uniqid(), -8));
    }
}
