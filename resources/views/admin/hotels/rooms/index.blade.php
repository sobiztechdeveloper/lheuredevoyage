@extends('layouts.admin.app')
@section('title', 'Rooms — '.$hotel->name)
@section('content')
<div class="admin-page-header">
    <div><h1>Rooms: {{ $hotel->name }}</h1></div>
    <div class="d-flex gap-2">
        <a href="{{ route('admin.hotels.rooms.create', $hotel) }}" class="btn btn-admin-primary btn-sm">Add Room</a>
        <a href="{{ route('admin.hotels.edit', $hotel) }}" class="btn btn-admin-outline btn-sm">Edit Hotel</a>
    </div>
</div>
<div class="admin-table-card">
    <table class="table align-middle">
        <thead><tr><th>Name</th><th>Type</th><th>Price</th><th>Status</th><th></th></tr></thead>
        <tbody>
            @forelse($rooms as $room)
            <tr>
                <td>{{ $room->name }}</td>
                <td>{{ $room->room_type }}</td>
                <td>${{ number_format($room->price, 2) }}</td>
                <td>{{ $room->is_active ? 'Active' : 'Inactive' }}</td>
                <td class="text-end">
                    <a href="{{ route('admin.hotels.rooms.edit', [$hotel, $room]) }}" class="btn btn-sm btn-admin-primary">Edit</a>
                    <form action="{{ route('admin.hotels.rooms.destroy', [$hotel, $room]) }}" method="POST" class="d-inline" onsubmit="return confirm('Delete room?')">@csrf @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr><td colspan="5" class="text-center py-4 text-muted">No rooms yet.</td></tr>
            @endforelse
        </tbody>
    </table>
    {{ $rooms->links() }}
</div>
@endsection
