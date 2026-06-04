<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class LegalPage extends Model
{
    use SoftDeletes;

    /** All legal slugs used for URL maps and consent components. */
    public const FOOTER_SLUGS = [
        'terms-and-conditions',
        'privacy-policy',
        'cookie-policy',
        'booking-conditions',
        'cancellation-policy',
        'disclaimer',
        'company-information',
    ];

    /** Primary links in the Help & Legal column (keeps column height balanced). */
    public const FOOTER_MENU_SLUGS = [
        'terms-and-conditions',
        'privacy-policy',
        'booking-conditions',
        'cancellation-policy',
    ];

    /** Secondary links shown in the copyright bar. */
    public const FOOTER_BAR_SLUGS = [
        'cookie-policy',
        'disclaimer',
        'company-information',
    ];

    /** @var array<string, string> */
    public const FOOTER_LABELS = [
        'terms-and-conditions' => 'Terms & Conditions',
        'privacy-policy' => 'Privacy Policy',
        'cookie-policy' => 'Cookie Policy',
        'booking-conditions' => 'Booking Conditions',
        'cancellation-policy' => 'Cancellation Policy',
        'disclaimer' => 'Disclaimer',
        'company-information' => 'Company Information',
    ];

    protected $fillable = [
        'title',
        'slug',
        'content',
        'summary',
        'meta_title',
        'meta_description',
        'is_active',
        'sort_order',
        'created_by',
        'updated_by',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saved(fn () => static::clearCache());
        static::deleted(fn () => static::clearCache());
    }

    public static function clearCache(): void
    {
        Cache::forget('legal_pages.footer');
        Cache::forget('legal_pages.footer_urls');
        Cache::forget('legal_pages.all_active');
    }

    /**
     * Active legal pages shown in the site footer (small set — queried fresh, not cached as models).
     *
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public static function footerPages()
    {
        return static::footerPagesForSlugs(self::FOOTER_SLUGS);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public static function footerMenuPages()
    {
        return static::footerPagesForSlugs(self::FOOTER_MENU_SLUGS);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    public static function footerBarPages()
    {
        return static::footerPagesForSlugs(self::FOOTER_BAR_SLUGS);
    }

    /**
     * @param  array<int, string>  $slugs
     * @return \Illuminate\Database\Eloquent\Collection<int, static>
     */
    protected static function footerPagesForSlugs(array $slugs)
    {
        $pages = static::query()
            ->whereIn('slug', $slugs)
            ->where('is_active', true)
            ->get()
            ->keyBy('slug');

        return collect($slugs)
            ->map(fn (string $slug) => $pages->get($slug))
            ->filter()
            ->values();
    }

    public function footerLabel(): string
    {
        return self::FOOTER_LABELS[$this->slug] ?? $this->title;
    }

    /**
     * @return array<string, string> slug => public URL
     */
    public static function cachedFooterUrlMap(): array
    {
        return Cache::remember('legal_pages.footer_urls', 3600, function () {
            return static::footerPages()
                ->mapWithKeys(fn (self $page) => [$page->slug => $page->publicUrl()])
                ->all();
        });
    }

    public static function generateSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $counter = 1;

        while (static::withTrashed()->where('slug', $slug)->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function publicUrl(): string
    {
        return route('legal.show', $this->slug);
    }

    public function resolvedMetaTitle(): string
    {
        return $this->meta_title ?: $this->title;
    }

    public function resolvedMetaDescription(): string
    {
        return $this->meta_description ?: Str::limit(strip_tags($this->summary ?: $this->content), 160);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('title');
    }
}
