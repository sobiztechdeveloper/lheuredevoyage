<?php

namespace App\Services;

use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Session;

class CurrencyService
{
    private ?string $resolved = null;

    public function code(): string
    {
        if ($this->resolved !== null) {
            return $this->resolved;
        }

        $supported = config('currency.supported', ['CHF', 'USD']);
        $candidate = Session::get(config('currency.session_key'))
            ?? request()->cookie(config('currency.cookie'));

        if (is_string($candidate) && in_array(strtoupper($candidate), $supported, true)) {
            return $this->resolved = strtoupper($candidate);
        }

        return $this->resolved = strtoupper((string) config('currency.default', 'CHF'));
    }

    public function set(string $code): void
    {
        $code = strtoupper(trim($code));
        $supported = config('currency.supported', ['CHF', 'USD']);

        if (! in_array($code, $supported, true)) {
            $code = strtoupper((string) config('currency.default', 'CHF'));
        }

        Session::put(config('currency.session_key'), $code);
        Cookie::queue(config('currency.cookie'), $code, 60 * 24 * 365);
        $this->resolved = $code;
    }

    public function catalogSource(): string
    {
        return strtoupper((string) config('currency.sources.catalog', 'CHF'));
    }

    public function serpapiSource(): string
    {
        return strtoupper((string) config('currency.sources.serpapi', config('services.serpapi.currency', 'USD')));
    }

    public function convert(float $amount, string $from, string $to): float
    {
        $from = strtoupper($from);
        $to = strtoupper($to);

        if ($from === $to) {
            return round($amount, 2);
        }

        $rates = config('currency.rates', []);

        if (isset($rates[$from][$to])) {
            return round($amount * (float) $rates[$from][$to], 2);
        }

        if (isset($rates[$to][$from]) && (float) $rates[$to][$from] > 0) {
            return round($amount / (float) $rates[$to][$from], 2);
        }

        return round($amount, 2);
    }

    public function toDisplay(float $amount, string $fromCurrency): float
    {
        return $this->convert($amount, $fromCurrency, $this->code());
    }

    public function fromDisplay(float $amount, string $toCurrency): float
    {
        return $this->convert($amount, $this->code(), $toCurrency);
    }

    public function format(float $amount, string $fromCurrency, int $decimals = 0): string
    {
        $converted = $this->toDisplay($amount, $fromCurrency);
        $code = $this->code();

        $separator = $code === 'CHF' ? "'" : ',';
        $formatted = number_format($converted, $decimals, '.', $separator);

        return match ($code) {
            'CHF' => 'CHF '.$formatted,
            'USD' => '$'.$formatted,
            default => $code.' '.$formatted,
        };
    }

    public function symbol(): string
    {
        return match ($this->code()) {
            'CHF' => 'CHF',
            'USD' => '$',
            default => $this->code(),
        };
    }
}
