<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserDashboardController extends Controller
{
    public function index(): View
    {
        $user = Auth::user();
        $bookings = $user->bookings()->with('bookable')->latest()->get();

        return view('pages.publicUserView.myDashboard', [
            'user' => $user,
            'stats' => [
                'total' => $bookings->count(),
                'pending' => $bookings->where('status', 'pending')->count(),
                'earned' => $bookings->sum('total_amount'),
            ],
            'recentBookings' => $bookings->take(5),
            'notifications' => $user->notifications()->latest()->take(4)->get(),
        ]);
    }
}
