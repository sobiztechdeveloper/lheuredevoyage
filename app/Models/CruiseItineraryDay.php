<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CruiseItineraryDay extends Model
{
    protected $fillable = [
        'cruise_id', 'day_number', 'port_name', 'arrival_time', 'departure_time',
        'description', 'sort_order',
    ];

    public function cruise(): BelongsTo
    {
        return $this->belongsTo(Cruise::class);
    }
}
