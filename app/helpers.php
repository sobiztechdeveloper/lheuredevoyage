<?php

use Carbon\Carbon;
use Illuminate\Support\Carbon as IlluminateCarbon;

if (! function_exists('format_date')) {
    function format_date(DateTimeInterface|string|null $value, ?string $format = null): string
    {
        if ($value === null || $value === '') {
            return '—';
        }

        $date = $value instanceof DateTimeInterface
            ? IlluminateCarbon::instance($value)
            : Carbon::parse($value);

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

        foreach (['d/m/Y', 'd-m-Y', 'n/j/Y', 'm/d/Y', 'Y-m-d', 'Y/m/d'] as $format) {
            try {
                $parsed = Carbon::createFromFormat($format, $value);

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
