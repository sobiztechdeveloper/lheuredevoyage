<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserNotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Auth::user()->notifications()->latest()->paginate(15);
        $unreadCount = Auth::user()->notifications()->whereNull('read_at')->count();

        return view('pages.publicUserView.myNotification', compact('notifications', 'unreadCount'));
    }
}
