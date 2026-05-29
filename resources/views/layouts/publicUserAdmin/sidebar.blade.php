<div class="col-lg-3">
    <div class="user-profile-sidebar">
        <div class="user-profile-sidebar-top">
            <div class="user-profile-img">
                <img src="assets/img/account/user.jpg" alt="">
                <button type="button" class="profile-img-btn"><i class="far fa-camera"></i></button>
                <input type="file" class="profile-img-file">
            </div>
            <h4>Antoni Jonson</h4>
            <p>jonson@example.com</p>
        </div>
        <ul class="user-profile-sidebar-list">
            <li><a href="{{ route('my-dashboard') }}"><i class="far fa-gauge-high"></i> Dashboard</a></li>
            <li><a href="{{ route('my-profile') }}"><i class="far fa-user"></i> My Profile</a></li>
            <li><a href="{{ route('my-bookings-list') }}"><i class="far fa-shopping-bag"></i> My Booking</a></li>
            <li><a class="active" href="{{ route('booking-history') }}"><i class="far fa-clipboard-list"></i> Booking History</a></li>
            {{--<li><a href="{{ route('my-listing') }}"><i class="far fa-globe"></i> My Listing</a></li>--}}
            <li><a href="{{ route('my-wishlist') }}"><i class="far fa-heart"></i> My Wishlist</a></li>
            {{--<li><a href="{{ route('my-messages') }}"><i class="far fa-envelope"></i> Messages <span class="badge">02</span></a></li>--}}
            <li><a href="{{ route('my-wallet') }}"><i class="far fa-wallet"></i> My Wallet</a></li>
            <li><a href="{{ route('my-notifications') }}"><i class="far fa-bell"></i> Notifications <span class="badge">05</span></a></li>
            <li><a href="{{ route('my-settings') }}"><i class="far fa-cog"></i> Settings</a></li>
            <li><a href="#"><i class="far fa-sign-out"></i> Logout</a></li>
        </ul>
    </div>
</div>