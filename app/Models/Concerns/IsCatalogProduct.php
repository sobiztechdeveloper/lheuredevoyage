<?php

namespace App\Models\Concerns;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Schema;

trait IsCatalogProduct
{
    use SoftDeletes;

    public static function bootIsCatalogProduct(): void
    {
        static::saving(function (self $model): void {
            $model->syncLegacyCatalogFields();
        });

        static::creating(function (self $model): void {
            if (auth()->check()) {
                $model->created_by ??= auth()->id();
            }
        });

        static::updating(function (self $model): void {
            if (auth()->check()) {
                $model->updated_by = auth()->id();
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function scopeActive(Builder $query): Builder
    {
        $table = $query->getModel()->getTable();

        if (Schema::hasColumn($table, 'status')) {
            return $query->where('status', true);
        }

        return $query->where('is_active', true);
    }

    public function scopeFeatured(Builder $query): Builder
    {
        $table = $query->getModel()->getTable();

        if (Schema::hasColumn($table, 'featured')) {
            return $query->where('featured', true);
        }

        return $query->where('is_featured', true);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        $table = $query->getModel()->getTable();

        return $query->where(function (Builder $q) use ($term, $table) {
            foreach (['name', 'title', 'slug', 'location', 'destination', 'description', 'short_description'] as $column) {
                if (Schema::hasColumn($table, $column)) {
                    $q->orWhere($column, 'like', "%{$term}%");
                }
            }
        });
    }

    public function getNameAttribute(?string $value): string
    {
        return $value ?? $this->attributes['title'] ?? '';
    }

    public function getTitleAttribute(?string $value): string
    {
        return $this->attributes['name'] ?? $value ?? '';
    }

    public function getImageUrlAttribute(): string
    {
        $path = $this->featured_image ?? $this->image ?? null;

        if (! $path) {
            return asset('assets/img/logo/logo.png');
        }

        if (str_starts_with($path, 'http') || str_starts_with($path, 'assets/')) {
            return asset($path);
        }

        return asset('storage/'.$path);
    }

    public function getGalleryUrlsAttribute(): array
    {
        $gallery = $this->gallery_json ?? [];

        if (is_string($gallery)) {
            $gallery = json_decode($gallery, true) ?: [];
        }

        return collect($gallery)->map(function ($path) {
            if (str_starts_with($path, 'http') || str_starts_with($path, 'assets/')) {
                return asset($path);
            }

            return asset('storage/'.$path);
        })->all();
    }

    public function getFormattedPriceAttribute(): string
    {
        $amount = $this->price_per_day ?? $this->price;

        return '$'.number_format((float) $amount, 0);
    }

    public function getPriceUnitAttribute(?string $value): string
    {
        return $value ?? ($this->price_per_day ? 'Per Day' : 'Per Person');
    }

    public function getIsFeaturedAttribute(): bool
    {
        return (bool) ($this->attributes['featured'] ?? $this->attributes['is_featured'] ?? false);
    }

    public function getIsActiveAttribute(): bool
    {
        return (bool) ($this->attributes['status'] ?? $this->attributes['is_active'] ?? true);
    }

    public function syncLegacyCatalogFields(): void
    {
        if (! empty($this->name)) {
            $this->title = $this->name;
        } elseif (! empty($this->title)) {
            $this->name = $this->title;
        }

        if (! empty($this->featured_image)) {
            $this->image = $this->featured_image;
        } elseif (! empty($this->image)) {
            $this->featured_image = $this->image;
        }

        if (isset($this->status)) {
            $this->is_active = (bool) $this->status;
        } elseif (isset($this->is_active)) {
            $this->status = (bool) $this->is_active;
        }

        if (isset($this->featured)) {
            $this->is_featured = (bool) $this->featured;
        } elseif (isset($this->is_featured)) {
            $this->featured = (bool) $this->is_featured;
        }

        if (isset($this->star_rating)) {
            $this->stars = $this->star_rating;
        } elseif (isset($this->stars)) {
            $this->star_rating = $this->stars;
        }

        if (isset($this->price_per_day) && ! isset($this->price)) {
            $this->price = $this->price_per_day;
        }
    }
}
