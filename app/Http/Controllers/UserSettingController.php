<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserSettingController extends Controller
{
    public function index(): View
    {
        $user = Auth::user()->load('profile');
        $settings = $user->settings()->firstOrCreate(['user_id' => $user->id]);

        return view('pages.publicUserView.mySetting', compact('settings', 'user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'email_notifications' => ['nullable', 'boolean'],
            'sms_notifications' => ['nullable', 'boolean'],
            'language' => ['required', 'string', 'max:5'],
            'timezone' => ['required', 'string', 'max:50'],
        ]);

        $data['email_notifications'] = $request->boolean('email_notifications');
        $data['sms_notifications'] = $request->boolean('sms_notifications');

        Auth::user()->settings()->updateOrCreate(['user_id' => Auth::id()], $data);

        return redirect()->route('my-settings')->with('success', 'Settings saved.');
    }
}
