@extends('layouts.admin.app')

@section('title', $user->name)

@section('content')
@if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
<div class="card mb-4">
    <div class="card-body">
        <h2 class="h5">{{ $user->name }}</h2>
        <p class="mb-1"><strong>Email:</strong> {{ $user->email }}</p>
        <p class="mb-1"><strong>Bookings:</strong> {{ $user->bookings_count }}</p>
        <p class="mb-1"><strong>Support tickets:</strong> {{ $user->support_tickets_count }}</p>
        <p class="mb-0"><strong>Registered:</strong> {{ $user->created_at->format(config('date.display')) }}</p>
    </div>
</div>
<form method="POST" action="{{ route('admin.users.update', $user) }}" class="card mb-4">
    @csrf @method('PUT')
    <div class="card-body">
        <label class="form-label">Account status</label>
        <select name="status" class="form-select w-auto">
            <option value="active" @selected(($user->status ?? 'active') === 'active')>Active</option>
            <option value="suspended" @selected($user->status === 'suspended')>Suspended</option>
        </select>
        <button type="submit" class="btn btn-primary ms-2">Save</button>
    </div>
</form>
<div class="card">
    <div class="card-header">Recent bookings</div>
    <ul class="list-group list-group-flush">
        @forelse($user->bookings as $booking)
            <li class="list-group-item">
                <a href="{{ route('admin.bookings.show', $booking) }}">{{ $booking->reference }}</a>
                — {{ ucfirst($booking->status) }}
            </li>
        @empty
            <li class="list-group-item text-muted">No bookings.</li>
        @endforelse
    </ul>
</div>
<a href="{{ route('admin.users.index') }}" class="btn btn-link mt-3">← Back</a>
@endsection
