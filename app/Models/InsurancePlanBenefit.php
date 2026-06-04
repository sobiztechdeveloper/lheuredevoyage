<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsurancePlanBenefit extends Model
{
    protected $fillable = ['travel_insurance_id', 'title', 'description', 'icon', 'sort_order'];

    public function travelInsurance(): BelongsTo
    {
        return $this->belongsTo(TravelInsurance::class);
    }
}
