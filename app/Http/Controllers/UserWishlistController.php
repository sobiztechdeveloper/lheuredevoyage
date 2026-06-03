<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserWishlistController extends Controller
{
    public function index(): View
    {
        $wishlists = Auth::user()->wishlists()->with('wishlistable')->latest()->paginate(12);

        return view('pages.publicUserView.myWishList', compact('wishlists'));
    }
}
