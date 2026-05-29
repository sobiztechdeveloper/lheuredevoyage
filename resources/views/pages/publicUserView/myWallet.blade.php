@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="row">
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-widget dashboard-widget-color-2">
                    <div class="dashboard-widget-info">
                        <h1>$12,000</h1>
                        <span>Wallet Balance</span>
                    </div>
                    <div class="dashboard-widget-icon">
                        <i class="fal fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-widget dashboard-widget-color-3">
                    <div class="dashboard-widget-info">
                        <h1>$50,000</h1>
                        <span>Total Credit</span>
                    </div>
                    <div class="dashboard-widget-icon">
                        <i class="fal fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-lg-4">
                <div class="dashboard-widget dashboard-widget-color-1">
                    <div class="dashboard-widget-info">
                        <h1>$62,000</h1>
                        <span>Total Debit</span>
                    </div>
                    <div class="dashboard-widget-icon">
                        <i class="fal fa-sack-dollar"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="user-profile-card">
                <h4 class="user-profile-card-title">Add Balance</h4>
                <div class="col-lg-12">
                    <div class="user-profile-form">
                        <form action="#">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>Add Amount</label>
                                    <input type="text" class="form-control"
                                        placeholder="Enter amount">
                                </div>
                            </div>
                            <button type="button" class="theme-btn my-3">Add Now</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="user-profile-card profile-booking">
                <h4 class="user-profile-card-title">Your Transaction</h4>
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Transaction ID</th>
                                <th>Type</th>
                                <th>Date</th>
                                <th>Credit</th>
                                <th>Debit</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01.</td>
                                <td><b>#12453</b></td>
                                <td>Card</td>
                                <td>Oct 22, 2025</td>
                                <td>$1,000</td>
                                <td>-</td>
                                <td>$11,569</td>
                                <td><span class="badge badge-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>02.</td>
                                <td><b>#12453</b></td>
                                <td>Card</td>
                                <td>Oct 22, 2025</td>
                                <td>-</td>
                                <td>$1,000</td>
                                <td>$11,569</td>
                                <td><span class="badge badge-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>03.</td>
                                <td><b>#12453</b></td>
                                <td>Card</td>
                                <td>Oct 22, 2025</td>
                                <td>$1,000</td>
                                <td>-</td>
                                <td>$11,569</td>
                                <td><span class="badge badge-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>04.</td>
                                <td><b>#12453</b></td>
                                <td>Card</td>
                                <td>Oct 22, 2025</td>
                                <td>-</td>
                                <td>$1,000</td>
                                <td>$11,569</td>
                                <td><span class="badge badge-success">Completed</span></td>
                            </tr>
                            <tr>
                                <td>05.</td>
                                <td><b>#12453</b></td>
                                <td>Card</td>
                                <td>Oct 22, 2025</td>
                                <td>$1,000</td>
                                <td>-</td>
                                <td>$11,569</td>
                                <td><span class="badge badge-danger">Cancelled</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection