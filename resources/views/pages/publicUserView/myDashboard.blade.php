@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-widget dashboard-widget-color-1">
                    <div class="dashboard-widget-info">
                        <h1>120</h1>
                        <span>Total Booking</span>
                    </div>
                    <div class="dashboard-widget-icon">
                        <i class="fal fa-shopping-bag"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-widget dashboard-widget-color-2">
                    <div class="dashboard-widget-info">
                        <h1>26</h1>
                        <span>Pending Booking</span>
                    </div>
                    <div class="dashboard-widget-icon">
                        <i class="fal fa-loader"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-widget dashboard-widget-color-3">
                    <div class="dashboard-widget-info">
                        <h1>$60,050</h1>
                        <span>You Earned</span>
                    </div>
                    <div class="dashboard-widget-icon">
                        <i class="fal fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8">
                <div class="user-profile-card">
                    <h4 class="user-profile-card-title">Sales Chart</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <div id="chart"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-4">
                <div class="user-profile-card">
                    <h4 class="user-profile-card-title">Notifications</h4>
                    <div class="user-notification">
                        <div class="user-notification-item">
                            <a href="#">
                                <div class="user-notification-icon">
                                    <i class="far fa-home"></i>
                                </div>
                                <div class="user-notification-info">
                                    <p>Your Booking <b>#123456</b> Roltak Hotel Is Confirmed!</p>
                                    <span>just now</span>
                                </div>
                            </a>
                        </div>
                        <div class="user-notification-item">
                            <a href="#">
                                <div class="user-notification-icon">
                                    <i class="far fa-envelope"></i>
                                </div>
                                <div class="user-notification-info">
                                    <p>Your Booking <b>#123456</b> Roltak Hotel Is Confirmed!</p>
                                    <span>15 min ago</span>
                                </div>
                            </a>
                        </div>
                        <div class="user-notification-item">
                            <a href="#">
                                <div class="user-notification-icon">
                                    <i class="far fa-heart"></i>
                                </div>
                                <div class="user-notification-info">
                                    <p>Your Booking <b>#123456</b> Roltak Hotel Is Confirmed!</p>
                                    <span>15 days ago</span>
                                </div>
                            </a>
                        </div>
                        <div class="user-notification-item">
                            <a href="#">
                                <div class="user-notification-icon">
                                    <i class="far fa-comment"></i>
                                </div>
                                <div class="user-notification-info">
                                    <p>Your Booking <b>#123456</b> Roltak Hotel Is Confirmed!</p>
                                    <span>2 months ago</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-12">
                <div class="user-profile-card profile-booking">
                    <h4 class="user-profile-card-title">Recent Booking</h4>
                    <div class="table-responsive">
                        <table class="table text-nowrap">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Booking ID</th>
                                    <th>Type</th>
                                    <th>Date</th>
                                    <th>Price</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>01.</td>
                                    <td><b>#12453</b></td>
                                    <td>Hotel</td>
                                    <td>Oct 22, 2025</td>
                                    <td>$11,569</td>
                                    <td><span class="badge badge-success">Confirmed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>02.</td>
                                    <td><b>#12453</b></td>
                                    <td>Flight</td>
                                    <td>Oct 22, 2025</td>
                                    <td>$11,569</td>
                                    <td><span class="badge badge-success">Confirmed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>03.</td>
                                    <td><b>#12453</b></td>
                                    <td>Activity</td>
                                    <td>Oct 22, 2025</td>
                                    <td>$11,569</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td>
                                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>04.</td>
                                    <td><b>#12453</b></td>
                                    <td>Car</td>
                                    <td>Oct 22, 2025</td>
                                    <td>$11,569</td>
                                    <td><span class="badge badge-success">Confirmed</span></td>
                                    <td>
                                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>05.</td>
                                    <td><b>#12453</b></td>
                                    <td>Cruise</td>
                                    <td>Oct 22, 2025</td>
                                    <td>$11,569</td>
                                    <td><span class="badge badge-danger">Cancel</span></td>
                                    <td>
                                        <a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection