<?php

namespace App\Http\Controllers;

use App\Models\UserBookingHistory;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserBookingHistoryController extends Controller
{
    public function index(): View
    {
        $histories = UserBookingHistory::query()
            ->where('user_id', Auth::id())
            ->with(['booking.bookable'])
            ->latest()
            ->paginate(10);

        return view('pages.publicUserView.myBookingHistory', compact('histories'));
    }
}
