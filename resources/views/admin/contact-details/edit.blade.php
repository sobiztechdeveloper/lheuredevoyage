@extends('layouts.admin.app')

@section('title', 'Contact Details')

@section('content')
<form method="POST" action="{{ route('admin.contact-details.update') }}">
    @csrf @method('PUT')
    <div class="row g-3">
        <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address', $contactDetail->address) }}</textarea></div>
        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $contactDetail->phone) }}"></div>
        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $contactDetail->email) }}"></div>
        <div class="col-md-6"><label class="form-label">WhatsApp Number</label><input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $contactDetail->whatsapp_number) }}"></div>
        <div class="col-12"><label class="form-label">Google Map Embed (iframe src or full embed HTML)</label><textarea name="google_map_embed" class="form-control" rows="4">{{ old('google_map_embed', $contactDetail->google_map_embed) }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary mt-4">Save Contact Details</button>
</form>
@endsection
