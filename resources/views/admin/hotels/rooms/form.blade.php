@extends('layouts.admin.app')
@section('title', ($room->exists ? 'Edit' : 'Add').' Room')
@section('content')
<form method="POST" enctype="multipart/form-data" action="{{ $room->exists ? route('admin.hotels.rooms.update', [$hotel, $room]) : route('admin.hotels.rooms.store', $hotel) }}">
    @csrf
    @if($room->exists) @method('PUT') @endif
    <div class="admin-form-card">
        <div class="admin-form-card-body row g-3">
            <div class="col-md-6"><label class="admin-field-label">Room Name *</label><input type="text" name="name" class="form-control" value="{{ old('name', $room->name) }}" required></div>
            <div class="col-md-6"><label class="admin-field-label">Room Type</label><input type="text" name="room_type" class="form-control" value="{{ old('room_type', $room->room_type) }}" placeholder="Deluxe, Suite..."></div>
            <div class="col-12"><label class="admin-field-label">Description</label><textarea name="description" class="form-control" rows="3">{{ old('description', $room->description) }}</textarea></div>
            <div class="col-md-4"><label class="admin-field-label">Room Size</label><input type="text" name="room_size" class="form-control" value="{{ old('room_size', $room->room_size) }}"></div>
            <div class="col-md-4"><label class="admin-field-label">Max Adults</label><input type="number" name="max_adults" class="form-control" value="{{ old('max_adults', $room->max_adults ?? 2) }}"></div>
            <div class="col-md-4"><label class="admin-field-label">Max Children</label><input type="number" name="max_children" class="form-control" value="{{ old('max_children', $room->max_children ?? 0) }}"></div>
            <div class="col-md-4"><label class="admin-field-label">Bed Type</label><input type="text" name="bed_type" class="form-control" value="{{ old('bed_type', $room->bed_type) }}"></div>
            <div class="col-md-4"><label class="admin-field-label">Meal Plan</label><input type="text" name="meal_plan" class="form-control" value="{{ old('meal_plan', $room->meal_plan) }}"></div>
            <div class="col-md-4"><label class="admin-field-label">Price/night *</label><input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $room->price) }}" required></div>
            <div class="col-md-12"><label class="admin-field-label">Features (comma-separated)</label><input type="text" name="features" class="form-control" value="{{ old('features', is_array($room->features) ? implode(', ', $room->features) : '') }}"></div>
            <div class="col-md-6"><label class="admin-field-label">Featured Image</label><input type="file" name="featured_image" class="form-control" accept="image/*"></div>
            <div class="col-md-6"><label class="admin-field-label">Or asset path</label><input type="text" name="featured_image_path" class="form-control" value="{{ old('featured_image_path', str_starts_with($room->featured_image ?? '', 'assets/') ? $room->featured_image : '') }}"></div>
            <div class="col-md-6"><div class="form-check mt-4"><input type="checkbox" name="is_active" value="1" class="form-check-input" id="active" @checked(old('is_active', $room->is_active ?? true))><label for="active">Active</label></div></div>
        </div>
    </div>
    <button type="submit" class="btn btn-admin-primary">Save Room</button>
    <a href="{{ route('admin.hotels.rooms.index', $hotel) }}" class="btn btn-admin-outline">Cancel</a>
</form>
@endsection
