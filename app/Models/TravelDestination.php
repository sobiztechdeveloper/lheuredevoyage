<?php

namespace App\Models;

use App\Enums\DestinationType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class TravelDestination extends Model
{
    use SoftDeletes;

    protected $table = 'travel_destinations';

    protected $fillable = [
        'type',
        'name',
        'slug',
        'code',
        'country',
        'city',
        'region',
        'latitude',
        'longitude',
        'description',
        'sort_order',
        'is_active',
        'created_by',
        'updated_by',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
            'sort_order' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (self $model): void {
            if (empty($model->slug) && ! empty($model->name)) {
                $model->slug = static::generateSlug($model->name, $model->type);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'id';
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function editor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function typeEnum(): ?DestinationType
    {
        return DestinationType::tryFrom($this->type);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    public function scopeOfType(Builder $query, string|array $types): Builder
    {
        $types = is_array($types) ? $types : [$types];

        return $query->whereIn('type', $types);
    }

    public function scopeSearchTerm(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $inner) use ($term) {
            $inner->where('name', 'like', "%{$term}%")
                ->orWhere('code', 'like', "%{$term}%")
                ->orWhere('city', 'like', "%{$term}%")
                ->orWhere('country', 'like', "%{$term}%")
                ->orWhere('region', 'like', "%{$term}%")
                ->orWhere('slug', 'like', "%{$term}%");
        });
    }

    public function scopeAutocomplete(Builder $query, string $term, array $types = []): Builder
    {
        $query->active()->searchTerm($term);

        if ($types !== []) {
            $query->ofType($types);
        }

        return $query->ordered()->limit((int) config('destinations.search_limit', 20));
    }

    public static function generateSlug(string $name, ?string $type = null): string
    {
        $base = Str::slug($name);
        $slug = $type ? Str::slug($type.'-'.$base) : $base;
        $candidate = $slug;
        $counter = 1;

        while (static::withTrashed()->where('slug', $candidate)->exists()) {
            $candidate = $slug.'-'.$counter;
            $counter++;
        }

        return $candidate;
    }

    public function displayLabel(string $format = 'default'): string
    {
        if ($format === 'airport' && $this->code) {
            return "{$this->name} ({$this->code})";
        }

        if ($this->type === DestinationType::Airport->value && $this->code) {
            return "{$this->name} ({$this->code})";
        }

        if ($this->country && ! in_array($this->type, [DestinationType::Country->value, DestinationType::InsuranceRegion->value], true)) {
            return "{$this->name}, {$this->country}";
        }

        return $this->name;
    }

    /**
     * @return array<string, mixed>
     */
    public function toSearchArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'type' => $this->type,
            'country' => $this->country,
            'city' => $this->city,
            'label' => $this->displayLabel(),
            'airport_label' => $this->displayLabel('airport'),
        ];
    }
}
