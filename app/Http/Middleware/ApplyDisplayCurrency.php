<?php

namespace App\Http\Middleware;

use App\Services\CurrencyService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApplyDisplayCurrency
{
    public function handle(Request $request, Closure $next): Response
    {
        app(CurrencyService::class)->code();

        return $next($request);
    }
}
