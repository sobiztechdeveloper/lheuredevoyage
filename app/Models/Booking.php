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

    public function bookableTypeLabel(): string
    {
        return match (class_basename($this->bookable_type)) {
            'TourPackage' => 'Tour Package',
            'Hotel' => 'Hotel',
            'Cruise' => 'Cruise',
            'RentalCar' => 'Rental Car',
            'TravelInsurance' => 'Travel Insurance',
            default => class_basename($this->bookable_type),
        };
    }

    public function travelersLabel(): ?string
    {
        $data = $this->booking_data ?? [];

        if (! isset($data['adult'])) {
            return null;
        }

        $parts = [];
        $adult = (int) $data['adult'];
        $children = (int) ($data['children'] ?? 0);
        $infant = (int) ($data['infant'] ?? 0);

        if ($adult > 0) {
            $parts[] = $adult.' '.($adult === 1 ? 'Adult' : 'Adults');
        }
        if ($children > 0) {
            $parts[] = $children.' '.($children === 1 ? 'Child' : 'Children');
        }
        if ($infant > 0) {
            $parts[] = $infant.' '.($infant === 1 ? 'Infant' : 'Infants');
        }

        return $parts !== [] ? implode(', ', $parts) : null;
    }

    /**
     * @return array<string, string>
     */
    public function displayBookingDetails(): array
    {
        $data = $this->booking_data ?? [];
        $details = [];

        foreach ([
            'guest_name' => 'Contact name',
            'guest_email' => 'Email',
            'guest_phone' => 'Phone',
            'travel_date' => 'Travel date',
            'notes' => 'Notes',
        ] as $key => $label) {
            if (! empty($data[$key])) {
                $details[$label] = (string) $data[$key];
            }
        }

        if ($travelers = $this->travelersLabel()) {
            $details['Travelers'] = $travelers;
        }

        return $details;
    }
}
