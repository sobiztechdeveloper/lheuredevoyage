<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserMyBookingController extends Controller
{
    public function index(): View
    {
        $bookings = Auth::user()
            ->bookings()
            ->with('bookable')
            ->latest()
            ->paginate(10);

        return view('pages.publicUserView.myBooking', compact('bookings'));
    }
}
