@extends('layouts.admin.app')

@section('title', 'Users')

@section('content')
<form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><input type="search" name="q" class="form-control" placeholder="Search name or email" value="{{ $search }}"></div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="">All statuses</option>
            <option value="active" @selected($filterStatus === 'active')>Active</option>
            <option value="suspended" @selected($filterStatus === 'suspended')>Suspended</option>
        </select>
    </div>
    <div class="col-auto"><button class="btn btn-primary">Filter</button></div>
</form>
<div class="card">
    <table class="table mb-0">
        <thead><tr><th>Name</th><th>Email</th><th>Bookings</th><th>Status</th><th>Joined</th><th></th></tr></thead>
        <tbody>
            @foreach($users as $user)
            <tr>
                <td>{{ $user->name }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->bookings_count }}</td>
                <td><span class="badge bg-{{ ($user->status ?? 'active') === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($user->status ?? 'active') }}</span></td>
                <td>{{ $user->created_at->format('M d, Y') }}</td>
                <td class="text-end"><a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-outline-primary">View</a></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
{{ $users->links() }}
@endsection
