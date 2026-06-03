<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Quote extends Model
{
    use SoftDeletes;

    public const STATUSES = [
        'draft',
        'sent',
        'viewed',
        'accepted',
        'rejected',
        'expired',
    ];

    public const TYPES = [
        'flight',
        'hotel',
        'package',
        'cruise',
        'car',
        'insurance',
    ];

    protected $fillable = [
        'quote_number',
        'customer_id',
        'flight_booking_request_id',
        'quote_type',
        'title',
        'description',
        'currency',
        'amount',
        'tax_amount',
        'service_fee',
        'total_amount',
        'valid_until',
        'status',
        'notes',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'service_fee' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'valid_until' => 'date',
        ];
    }

    public static function generateQuoteNumber(): string
    {
        $date = now()->format('Ymd');

        do {
            $suffix = str_pad((string) random_int(1, 9999), 4, '0', STR_PAD_LEFT);
            $number = "QT-{$date}-{$suffix}";
        } while (static::query()->where('quote_number', $number)->exists());

        return $number;
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'customer_id');
    }

    public function flightBookingRequest(): BelongsTo
    {
        return $this->belongsTo(FlightBookingRequest::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(QuoteItem::class)->orderBy('sort_order');
    }

    public function statusHistories(): HasMany
    {
        return $this->hasMany(QuoteStatusHistory::class)->orderByDesc('created_at');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function statusLabel(): string
    {
        return ucfirst(str_replace('_', ' ', $this->status));
    }

    public function typeLabel(): string
    {
        return match ($this->quote_type) {
            'package' => 'Tour Package',
            'car' => 'Rental Car',
            default => ucfirst($this->quote_type),
        };
    }

    public function isExpired(): bool
    {
        return $this->valid_until->isPast() && ! in_array($this->status, ['accepted', 'rejected'], true);
    }

    public function canBeAcceptedOrRejected(): bool
    {
        return in_array($this->status, ['sent', 'viewed'], true) && ! $this->isExpired();
    }
}
