<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceBookingTraveler extends Model
{
    protected $fillable = [
        'insurance_booking_request_id', 'is_primary', 'title', 'first_name', 'last_name',
        'date_of_birth', 'nationality', 'passport_number', 'passport_expiry', 'relationship',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'passport_expiry' => 'date',
            'is_primary' => 'boolean',
        ];
    }

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(InsuranceBookingRequest::class, 'insurance_booking_request_id');
    }

    public function fullName(): string
    {
        return trim(($this->title ? $this->title.' ' : '').$this->first_name.' '.$this->last_name);
    }
}
