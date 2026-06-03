@extends('layouts.admin.app')

@section('title', 'Cancellation Requests')

@section('content')
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<form method="GET" class="mb-3">
    <select name="status" class="form-select w-auto d-inline-block" onchange="this.form.submit()">
        <option value="">All</option>
        <option value="pending" @selected($filterStatus === 'pending')>Pending</option>
        <option value="approved" @selected($filterStatus === 'approved')>Approved</option>
        <option value="rejected" @selected($filterStatus === 'rejected')>Rejected</option>
    </select>
</form>
@foreach($requests as $req)
<div class="card mb-3">
    <div class="card-body">
        <p><strong>Booking:</strong> <a href="{{ route('admin.bookings.show', $req->booking) }}">{{ $req->booking?->reference }}</a></p>
        <p><strong>Customer:</strong> {{ $req->user?->name }} — {{ $req->reason }}</p>
        <p><strong>Status:</strong> {{ ucfirst($req->status) }}</p>
        @if($req->isPending())
        <form method="POST" action="{{ route('admin.cancellation-requests.update', $req) }}" class="mt-2">
            @csrf @method('PUT')
            <textarea name="admin_notes" class="form-control mb-2" rows="2" placeholder="Admin notes (optional)"></textarea>
            <button name="status" value="approved" class="btn btn-success btn-sm">Approve</button>
            <button name="status" value="rejected" class="btn btn-outline-danger btn-sm">Reject</button>
        </form>
        @elseif($req->admin_notes)
            <p class="small text-muted mb-0">Notes: {{ $req->admin_notes }}</p>
        @endif
    </div>
</div>
@endforeach
{{ $requests->links() }}
@endsection
