@extends('layouts.publicUserAdmin.app')

@section('userAdmincontent')
<div class="col-lg-9">
    <div class="user-profile-wrapper">
        @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
        <div class="user-profile-card">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h4 class="user-profile-card-title mb-0">Support Tickets</h4>
                <a href="{{ route('support-tickets.create') }}" class="theme-btn btn-sm">New Ticket</a>
            </div>
            <div class="table-responsive">
                <table class="table text-nowrap">
                    <thead>
                        <tr><th>Reference</th><th>Subject</th><th>Status</th><th>Updated</th><th></th></tr>
                    </thead>
                    <tbody>
                        @forelse($tickets as $ticket)
                        <tr>
                            <td><b>{{ $ticket->reference }}</b></td>
                            <td>{{ $ticket->subject }}</td>
                            <td><span class="badge badge-secondary">{{ ucfirst(str_replace('_', ' ', $ticket->status)) }}</span></td>
                            <td>{{ $ticket->updated_at->format(config('date.display')) }}</td>
                            <td><a href="{{ route('support-tickets.show', $ticket) }}">View</a></td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="text-center">No tickets yet.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $tickets->links() }}
        </div>
    </div>
</div>
@endsection
