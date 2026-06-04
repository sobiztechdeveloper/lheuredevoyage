<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CarBookingDriver extends Model
{
    protected $fillable = [
        'car_booking_request_id', 'title', 'first_name', 'last_name',
        'date_of_birth', 'nationality', 'license_number', 'license_expiry',
        'passport_number', 'license_file', 'passport_file',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'license_expiry' => 'date',
        ];
    }

    public function bookingRequest(): BelongsTo
    {
        return $this->belongsTo(CarBookingRequest::class, 'car_booking_request_id');
    }

    public function fullName(): string
    {
        return trim(($this->title ? $this->title.' ' : '').$this->first_name.' '.$this->last_name);
    }

    public function passportFileUrl(): ?string
    {
        return $this->passport_file ? asset('storage/'.$this->passport_file) : null;
    }

    public function licenseFileUrl(): ?string
    {
        return $this->license_file ? asset('storage/'.$this->license_file) : null;
    }
}
