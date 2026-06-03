<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightBookingRequestStatusHistory extends Model
{
    protected $fillable = [
        'flight_booking_request_id', 'status', 'notes', 'changed_by',
    ];

    public function flightBookingRequest(): BelongsTo
    {
        return $this->belongsTo(FlightBookingRequest::class);
    }

    public function changedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
