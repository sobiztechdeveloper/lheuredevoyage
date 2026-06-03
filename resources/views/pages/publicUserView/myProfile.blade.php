@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="user-profile-card">
            <h4 class="user-profile-card-title">Profile Info</h4>
            <div class="col-lg-6">
                <div class="profile-info-list">
                    <ul>
                        <li>Full Name: <span>{{ $user->name }}</span></li>
                        <li>Email: <span>{{ $user->email }}</span></li>
                        <li>Phone: <span>{{ $user->profile?->phone ?? '—' }}</span></li>
                        <li>Address: <span>{{ trim(($user->profile?->city ?? '').', '.($user->profile?->country ?? ''), ', ') ?: '—' }}</span></li>
                        <li>Join Date: <span>{{ $user->created_at->format('d F, Y') }}</span></li>
                    </ul>
                </div>
            </div>
            <div class="mt-3">
                <a href="{{ route('my-settings') }}" class="theme-btn">Edit Profile</a>
            </div>
        </div>
    </div>
</div>

@endsection
