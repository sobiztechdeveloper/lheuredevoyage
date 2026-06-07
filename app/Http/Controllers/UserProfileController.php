<?php

namespace App\Http\Controllers;

use App\Services\CmsImageUploader;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserProfileController extends Controller
{
    public function index(): View
    {
        $user = Auth::user()->load('profile');

        return view('pages.publicUserView.myProfile', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $userData = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $profileData = $request->validate([
            'phone' => ['nullable', 'string', 'max:50'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'address' => ['nullable', 'string', 'max:255'],
            'city' => ['nullable', 'string', 'max:100'],
            'country' => ['nullable', 'string', 'max:100'],
        ]);

        $user->update($userData);
        $user->profile()->updateOrCreate(['user_id' => $user->id], $profileData);

        return redirect()->route('my-profile')->with('success', 'Profile updated.');
    }

    public function updateAvatar(Request $request, CmsImageUploader $uploader): RedirectResponse
    {
        $request->validate([
            'avatar' => ['required', 'image', 'mimes:jpeg,jpg,png,webp', 'max:2048'],
        ]);

        $user = Auth::user();
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);

        $profile->update([
            'avatar' => $uploader->upload($request->file('avatar'), 'avatars', $profile->avatar),
        ]);

        return back()->with('success', 'Profile photo updated.');
    }
}
