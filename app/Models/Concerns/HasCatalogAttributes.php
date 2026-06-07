<?php

namespace App\Models\Concerns;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Schema;

trait HasCatalogAttributes
{
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        $table = $query->getModel()->getTable();

        return $query->where(function (Builder $q) use ($term, $table) {
            foreach ([
                'name', 'title', 'slug', 'location', 'destination', 'description', 'short_description',
                'airline', 'departure_city', 'arrival_city', 'flight_class',
            ] as $column) {
                if (Schema::hasColumn($table, $column)) {
                    $q->orWhere($column, 'like', "%{$term}%");
                }
            }
        });
    }

    public function getNameAttribute(): string
    {
        return $this->attributes['name'] ?? $this->attributes['title'] ?? '';
    }

    public function getImageUrlAttribute(): string
    {
        if (! $this->image) {
            return asset('assets/img/logo/logo.png');
        }

        if (str_starts_with($this->image, 'http') || str_starts_with($this->image, 'assets/')) {
            return asset($this->image);
        }

        return asset('storage/'.$this->image);
    }

    public function getFormattedPriceAttribute(): string
    {
        return format_money((float) $this->price, currency_service()->catalogSource());
    }

    public function getIsActiveAttribute(): bool
    {
        return (bool) ($this->attributes['is_active'] ?? true);
    }

    public function getIsFeaturedAttribute(): bool
    {
        return (bool) ($this->attributes['is_featured'] ?? false);
    }
}
