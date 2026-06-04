<?php

namespace App\Services;

use App\Models\CookieConsent;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CookieConsentService
{
    public const COOKIE_NAME = 'ldv_cookie_consent';

    public const COOKIE_DAYS = 365;

    /**
     * @return array{necessary: bool, analytics: bool, marketing: bool, preferences: bool, choice: string}
     */
    public function preferencesFromRequest(Request $request): array
    {
        return [
            'necessary' => true,
            'analytics' => $request->boolean('analytics'),
            'marketing' => $request->boolean('marketing'),
            'preferences' => $request->boolean('preferences'),
            'choice' => $request->input('choice', 'custom'),
        ];
    }

    public function store(Request $request, string $choice, bool $analytics, bool $marketing, bool $preferences): CookieConsent
    {
        $token = $request->cookie(self::COOKIE_NAME) ?: Str::uuid()->toString();

        $consent = CookieConsent::query()->updateOrCreate(
            ['consent_token' => $token],
            [
                'user_id' => $request->user()?->id,
                'necessary' => true,
                'analytics' => $analytics,
                'marketing' => $marketing,
                'preferences' => $preferences,
                'choice' => $choice,
                'consented_at' => now(),
                'ip_address' => $request->ip(),
                'user_agent' => Str::limit((string) $request->userAgent(), 500),
            ],
        );

        return $consent;
    }

    public function consentCookieValue(string $token): string
    {
        return $token;
    }
}
