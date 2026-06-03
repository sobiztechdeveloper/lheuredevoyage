<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class FlightBookingPassenger extends Model
{
    protected $fillable = [
        'flight_booking_request_id',
        'passenger_type',
        'title',
        'first_name',
        'last_name',
        'gender',
        'date_of_birth',
        'nationality',
        'passport_number',
        'passport_expiry',
        'passport_country',
        'passport_file',
    ];

    protected function casts(): array
    {
        return [
            'date_of_birth' => 'date',
            'passport_expiry' => 'date',
        ];
    }

    public function flightBookingRequest(): BelongsTo
    {
        return $this->belongsTo(FlightBookingRequest::class);
    }

    public function fullName(): string
    {
        return trim($this->title.' '.$this->first_name.' '.$this->last_name);
    }

    public function passportFileUrl(): ?string
    {
        if (! $this->passport_file) {
            return null;
        }

        return asset('storage/'.$this->passport_file);
    }

    public function isPassportImage(): bool
    {
        if (! $this->passport_file) {
            return false;
        }

        return in_array(strtolower(pathinfo($this->passport_file, PATHINFO_EXTENSION)), ['jpg', 'jpeg', 'png', 'gif', 'webp'], true);
    }

    public function isPassportPdf(): bool
    {
        return $this->passport_file && Str::endsWith(strtolower($this->passport_file), '.pdf');
    }
}
