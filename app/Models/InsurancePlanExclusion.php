<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsurancePlanExclusion extends Model
{
    protected $fillable = ['travel_insurance_id', 'title', 'description', 'sort_order'];

    public function travelInsurance(): BelongsTo
    {
        return $this->belongsTo(TravelInsurance::class);
    }
}
