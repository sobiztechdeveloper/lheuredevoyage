<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelBookingGuest extends Model
{
    protected $fillable = [
        'hotel_booking_request_id', 'title', 'first_name', 'last_name', 'guest_type',
        'date_of_birth', 'nationality', 'passport_number', 'passport_expiry', 'passport_file',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'passport_expiry' => 'date',
        ];
    }

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(HotelBookingRequest::class, 'hotel_booking_request_id');
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
