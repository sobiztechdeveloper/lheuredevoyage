<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CruiseBookingPassenger extends Model
{
    protected $fillable = [
        'cruise_booking_request_id', 'is_primary', 'title', 'first_name', 'last_name', 'passenger_type',
        'gender', 'date_of_birth', 'nationality', 'passport_number', 'passport_expiry',
        'passport_country', 'passport_file',
    ];

    protected function casts(): array
    {
        return [
            'is_primary' => 'boolean',
            'date_of_birth' => 'date',
            'passport_expiry' => 'date',
        ];
    }

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(CruiseBookingRequest::class, 'cruise_booking_request_id');
    }

    public function fullName(): string
    {
        return trim(($this->title ? $this->title.' ' : '').$this->first_name.' '.$this->last_name);
    }

    public function passportFileUrl(): ?string
    {
        return $this->passport_file ? asset('storage/'.$this->passport_file) : null;
    }
}
