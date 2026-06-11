<?php

use Carbon\Carbon;
use Illuminate\Support\Carbon as IlluminateCarbon;

if (! function_exists('format_date')) {
    function format_date(DateTimeInterface|string|null $value, ?string $format = null): string
    {
        if ($value === null || $value === '') {
            return '—';
        }

        if ($value instanceof DateTimeInterface) {
            $date = IlluminateCarbon::instance($value);
        } else {
            $date = parse_user_date($value);

            if (! $date) {
                try {
                    $date = Carbon::parse($value);
                } catch (\Throwable) {
                    return '—';
                }
            }
        }

        return $date->format($format ?? config('date.display', 'd/m/Y'));
    }
}

if (! function_exists('format_datetime')) {
    function format_datetime(DateTimeInterface|string|null $value, ?string $format = null): string
    {
        return format_date($value, $format ?? config('date.display_datetime', 'd/m/Y H:i'));
    }
}

if (! function_exists('format_date_long')) {
    function format_date_long(DateTimeInterface|string|null $value, ?string $format = null): string
    {
        return format_date($value, $format ?? config('date.display_long', 'D, d/m/Y'));
    }
}

if (! function_exists('parse_user_date')) {
    function parse_user_date(DateTimeInterface|string|null $value): ?IlluminateCarbon
    {
        if ($value === null || $value === '') {
            return null;
        }

        if ($value instanceof DateTimeInterface) {
            return IlluminateCarbon::instance($value)->startOfDay();
        }

        $value = trim((string) $value);

        foreach (['d/m/y', 'd/m/Y', 'd-m-y', 'd-m-Y', 'n/j/y', 'n/j/Y', 'm/d/y', 'm/d/Y', 'Y-m-d', 'Y/m/d'] as $format) {
            try {
                $parsed = IlluminateCarbon::createFromFormat('!'.$format, $value);

                if ($parsed !== false) {
                    return $parsed->startOfDay();
                }
            } catch (\Throwable) {
                continue;
            }
        }

        try {
            return Carbon::parse($value)->startOfDay();
        } catch (\Throwable) {
            return null;
        }
    }
}

if (! function_exists('normalize_user_date')) {
    function normalize_user_date(DateTimeInterface|string|null $value): ?string
    {
        return parse_user_date($value)?->format(config('date.input', 'Y-m-d'));
    }
}

if (! function_exists('currency_service')) {
    function currency_service(): \App\Services\CurrencyService
    {
        return app(\App\Services\CurrencyService::class);
    }
}

if (! function_exists('display_currency')) {
    function display_currency(): string
    {
        return currency_service()->code();
    }
}

if (! function_exists('format_money')) {
    function format_money(float|int|string|null $amount, string $fromCurrency = 'CHF', int $decimals = 0): string
    {
        return currency_service()->format((float) ($amount ?? 0), strtoupper($fromCurrency), $decimals);
    }
}

if (! function_exists('holiday_package_request_config')) {
    /**
     * @return array<string, mixed>
     */
    function holiday_package_request_config(): array
    {
        return app(\App\Services\HolidayPackageRequestConfigService::class)->build();
    }
}
