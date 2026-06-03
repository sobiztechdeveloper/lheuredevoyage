@php
    $user = auth()->user();
    $profile = $user->profile;
    $unreadNotifications = $user->notifications()->whereNull('read_at')->count();
@endphp
<div class="col-lg-3">
    <div class="user-profile-sidebar">
        <div class="user-profile-sidebar-top">
            <div class="user-profile-img">
                <img src="{{ $profile?->avatar_url ?? asset('assets/img/account/user.jpg') }}" alt="">
                <button type="button" class="profile-img-btn"><i class="far fa-camera"></i></button>
                <input type="file" class="profile-img-file">
            </div>
            <h4>{{ $user->name }}</h4>
            <p>{{ $user->email }}</p>
        </div>
        <ul class="user-profile-sidebar-list">
            <li><a class="{{ request()->routeIs('my-dashboard') ? 'active' : '' }}" href="{{ route('my-dashboard') }}"><i class="far fa-gauge-high"></i> Dashboard</a></li>
            <li><a class="{{ request()->routeIs('my-profile') ? 'active' : '' }}" href="{{ route('my-profile') }}"><i class="far fa-user"></i> My Profile</a></li>
            <li><a class="{{ request()->routeIs('my-bookings-list') ? 'active' : '' }}" href="{{ route('my-bookings-list') }}"><i class="far fa-shopping-bag"></i> My Booking</a></li>
            <li><a class="{{ request()->routeIs('my-flight-bookings.*') ? 'active' : '' }}" href="{{ route('my-flight-bookings.index') }}"><i class="far fa-plane"></i> My Flight Bookings</a></li>
            <li><a class="{{ request()->routeIs('my-quotes.*') ? 'active' : '' }}" href="{{ route('my-quotes.index') }}"><i class="far fa-file-invoice-dollar"></i> My Quotes</a></li>
            <li><a class="{{ request()->routeIs('booking-history') ? 'active' : '' }}" href="{{ route('booking-history') }}"><i class="far fa-clipboard-list"></i> Booking History</a></li>
            <li><a class="{{ request()->routeIs('my-wishlist') ? 'active' : '' }}" href="{{ route('my-wishlist') }}"><i class="far fa-heart"></i> My Wishlist</a></li>
            <li><a class="{{ request()->routeIs('my-wallet') ? 'active' : '' }}" href="{{ route('my-wallet') }}"><i class="far fa-wallet"></i> My Wallet</a></li>
            <li><a class="{{ request()->routeIs('support-tickets.*') ? 'active' : '' }}" href="{{ route('support-tickets.index') }}"><i class="far fa-life-ring"></i> Support</a></li>
            <li><a class="{{ request()->routeIs('my-notifications') ? 'active' : '' }}" href="{{ route('my-notifications') }}"><i class="far fa-bell"></i> Notifications @if($unreadNotifications > 0)<span class="badge">{{ $unreadNotifications }}</span>@endif</a></li>
            <li><a class="{{ request()->routeIs('my-settings') ? 'active' : '' }}" href="{{ route('my-settings') }}"><i class="far fa-cog"></i> Settings</a></li>
            <li>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="border-0 bg-transparent w-100 text-start px-0" style="color:inherit;">
                        <i class="far fa-sign-out"></i> Logout
                    </button>
                </form>
            </li>
        </ul>
    </div>
</div>
