<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CruiseCabin extends Model
{
    protected $fillable = [
        'cruise_id', 'cabin_type', 'name', 'description', 'max_adults', 'max_children',
        'max_occupancy', 'size', 'starting_price', 'featured', 'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'starting_price' => 'decimal:2',
            'featured' => 'boolean',
        ];
    }

    public function cruise(): BelongsTo
    {
        return $this->belongsTo(Cruise::class);
    }

    public function cabinTypeLabel(): string
    {
        return config('cruise.cabin_types.'.$this->cabin_type, $this->cabin_type);
    }

    public function formattedPrice(): string
    {
        return 'CHF '.number_format((float) $this->starting_price, 2);
    }
}
