<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfCustomerAuthenticated
{
    /**
     * Redirect already-signed-in customers away from login/register.
     * Admin sessions are independent and must not affect the user login page.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth('web')->check()) {
            return redirect()->route('my-dashboard');
        }

        return $next($request);
    }
}
