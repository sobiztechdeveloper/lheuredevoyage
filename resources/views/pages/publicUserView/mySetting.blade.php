@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="col-lg-12 mb-4">
            <div class="user-profile-card">
                <h4 class="user-profile-card-title">Update Profile Info</h4>
                <div class="user-profile-form">
                    <form action="{{ route('my-profile.update') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Full Name</label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->profile?->phone) }}">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address', $user->profile?->address) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>City</label>
                                    <input type="text" name="city" class="form-control" value="{{ old('city', $user->profile?->city) }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Country</label>
                                    <input type="text" name="country" class="form-control" value="{{ old('country', $user->profile?->country) }}">
                                </div>
                            </div>
                        </div>
                        <button type="submit" class="theme-btn my-3">Update Profile Info <i class="far fa-user"></i></button>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="user-profile-card">
                <h4 class="user-profile-card-title">Preferences</h4>
                <div class="user-profile-form">
                    <form action="{{ route('my-settings.update') }}" method="POST">
                        @csrf
                        <div class="form-check mb-2">
                            <input type="checkbox" name="email_notifications" value="1" class="form-check-input" id="email_notifications" @checked(old('email_notifications', $settings->email_notifications ?? true))>
                            <label class="form-check-label" for="email_notifications">Email notifications</label>
                        </div>
                        <div class="form-check mb-2">
                            <input type="checkbox" name="sms_notifications" value="1" class="form-check-input" id="sms_notifications" @checked(old('sms_notifications', $settings->sms_notifications ?? false))>
                            <label class="form-check-label" for="sms_notifications">SMS notifications</label>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label>Language</label>
                                <input type="text" name="language" class="form-control" value="{{ old('language', $settings->language ?? 'en') }}">
                            </div>
                            <div class="col-md-6">
                                <label>Timezone</label>
                                <input type="text" name="timezone" class="form-control" value="{{ old('timezone', $settings->timezone ?? 'UTC') }}">
                            </div>
                        </div>
                        <button type="submit" class="theme-btn my-3">Save Preferences</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
