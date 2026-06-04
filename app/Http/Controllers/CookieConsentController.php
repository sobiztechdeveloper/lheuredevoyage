<?php

namespace App\Http\Controllers;

use App\Services\CookieConsentService;
use App\Services\LegalPageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Cookie;

class CookieConsentController extends Controller
{
    public function __construct(
        protected CookieConsentService $cookieConsentService,
        protected LegalPageService $legalPageService,
    ) {}

    public function settings(): View
    {
        $cookiePolicyUrl = $this->legalPageService->urlForSlug('cookie-policy');

        return view('pages.publicView.legal.cookie-settings', [
            'cookiePolicyUrl' => $cookiePolicyUrl,
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'choice' => ['required', 'in:accept_all,reject_non_essential,custom'],
            'analytics' => ['nullable', 'boolean'],
            'marketing' => ['nullable', 'boolean'],
            'preferences' => ['nullable', 'boolean'],
        ]);

        $analytics = false;
        $marketing = false;
        $preferences = false;

        if ($validated['choice'] === 'accept_all') {
            $analytics = true;
            $marketing = true;
            $preferences = true;
        } elseif ($validated['choice'] === 'custom') {
            $analytics = $request->boolean('analytics');
            $marketing = $request->boolean('marketing');
            $preferences = $request->boolean('preferences');
        }

        $consent = $this->cookieConsentService->store(
            $request,
            $validated['choice'],
            $analytics,
            $marketing,
            $preferences,
        );

        $cookie = cookie(
            CookieConsentService::COOKIE_NAME,
            $consent->consent_token,
            CookieConsentService::COOKIE_DAYS * 24 * 60,
            '/',
            null,
            $request->secure(),
            true,
            false,
            Cookie::SAMESITE_LAX,
        );

        return response()->json([
            'ok' => true,
            'preferences' => [
                'necessary' => true,
                'analytics' => $analytics,
                'marketing' => $marketing,
                'preferences' => $preferences,
            ],
        ])->withCookie($cookie);
    }
}
