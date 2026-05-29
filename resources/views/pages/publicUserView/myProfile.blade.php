@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="user-profile-card">
            <h4 class="user-profile-card-title">Profile Info</h4>
            <div class="col-lg-6">
                <div class="profile-info-list">
                    <ul>
                        <li>Full Name: <span>Antoni Jonson</span></li>
                        <li>Email: <span>jonson@example.com</span></li>
                        <li>Phone: <span>+2 134 562 458</span></li>
                        <li>Address: <span>New York, USA</span></li>
                        <li>Join Date: <span>21 August, 2025</span></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection