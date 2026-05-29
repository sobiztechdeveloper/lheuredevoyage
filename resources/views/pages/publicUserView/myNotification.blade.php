@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="col-lg-12">
            <div class="user-profile-card profile-booking">
                <h4 class="user-profile-card-title">Notifications (05)</h4>
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Notification</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>01.</td>
                                <td>
                                    <p>There are many variations of passages orem psum available</p>
                                </td>
                                <td>Oct 22, 2025</td>
                                <td><span class="badge badge-warning">Unread</span></td>
                                <td><a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye"></i> Mark As Read</a></td>
                            </tr>
                            <tr>
                                <td>02.</td>
                                <td>
                                    <p>There are many variations of passages orem psum available</p>
                                </td>
                                <td>Oct 22, 2025</td>
                                <td><span class="badge badge-success">Read</span></td>
                                <td><a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye-slash"></i> Mark As Unread</a></td>
                            </tr>
                            <tr>
                                <td>03.</td>
                                <td>
                                    <p>There are many variations of passages orem psum available</p>
                                </td>
                                <td>Oct 22, 2025</td>
                                <td><span class="badge badge-warning">Unread</span></td>
                                <td><a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye"></i> Mark As Read</a></td>
                            </tr>
                            <tr>
                                <td>04.</td>
                                <td>
                                    <p>There are many variations of passages orem psum available</p>
                                </td>
                                <td>Oct 22, 2025</td>
                                <td><span class="badge badge-success">Read</span></td>
                                <td><a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye-slash"></i> Mark As Unread</a></td>
                            </tr>
                            <tr>
                                <td>05.</td>
                                <td>
                                    <p>There are many variations of passages orem psum available</p>
                                </td>
                                <td>Oct 22, 2025</td>
                                <td><span class="badge badge-success">Read</span></td>
                                <td><a href="#" class="btn btn-outline-secondary btn-sm"><i class="far fa-eye-slash"></i> Mark As Unread</a></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection