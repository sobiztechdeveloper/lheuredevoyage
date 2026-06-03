@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')

<div class="col-lg-9">
    <div class="user-profile-wrapper">
        <div class="col-lg-12">
            <div class="user-profile-card profile-booking">
                <h4 class="user-profile-card-title">Notifications ({{ $unreadCount }} unread)</h4>
                <div class="table-responsive">
                    <table class="table text-nowrap">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Notification</th>
                                <th>Date</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($notifications as $index => $notification)
                                <tr>
                                    <td>{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}.</td>
                                    <td>
                                        <p><strong>{{ $notification->title }}</strong> — {{ $notification->body }}</p>
                                    </td>
                                    <td>{{ $notification->created_at->format('M d, Y') }}</td>
                                    <td>
                                        @if($notification->read_at)
                                            <span class="badge badge-success">Read</span>
                                        @else
                                            <span class="badge badge-warning">Unread</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-center">No notifications yet.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                {{ $notifications->links() }}
            </div>
        </div>
    </div>
</div>

@endsection
