<?php

namespace App\Http\Controllers;

use App\Services\CurrencyService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CurrencyController extends Controller
{
    public function switch(Request $request, CurrencyService $currency): RedirectResponse
    {
        $validated = $request->validate([
            'currency' => ['required', 'string', 'in:CHF,USD'],
        ]);

        $currency->set($validated['currency']);

        return redirect()->back();
    }
}
