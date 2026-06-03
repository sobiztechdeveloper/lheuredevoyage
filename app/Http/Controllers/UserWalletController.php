<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserWalletController extends Controller
{
    public function index(): View
    {
        $wallet = Auth::user()->wallet()->firstOrCreate(
            ['user_id' => Auth::id()],
            ['balance' => 0, 'currency' => 'USD']
        );

        return view('pages.publicUserView.myWallet', compact('wallet'));
    }
}
