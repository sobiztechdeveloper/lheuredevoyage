@extends('layouts.admin.app')

@section('title', 'Contact Details')

@section('content')
@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif
<form method="POST" action="{{ route('admin.contact-details.update') }}" enctype="multipart/form-data">
    @csrf
    <div class="row g-3">
        <div class="col-12"><label class="form-label">Address</label><textarea name="address" class="form-control" rows="2">{{ old('address', $contactDetail->address) }}</textarea></div>
        <div class="col-md-6"><label class="form-label">Phone</label><input type="text" name="phone" class="form-control" value="{{ old('phone', $contactDetail->phone) }}"></div>
        <div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" class="form-control" value="{{ old('email', $contactDetail->email) }}"></div>
        <div class="col-md-6"><label class="form-label">WhatsApp Number</label><input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $contactDetail->whatsapp_number) }}"></div>
        <div class="col-12">
            <label class="form-label">Contact page breadcrumb image</label>
            <p class="text-muted small mb-2">Optional. Overrides the site default on the Contact page only.</p>
            @if($contactDetail->breadcrumb_image ?? null)
                <div class="mb-2">
                    <img src="{{ $contactDetail->breadcrumb_image_url }}" alt="Current breadcrumb" class="d-block rounded border" style="max-height:120px;width:auto;max-width:100%;">
                    <small class="text-muted d-block mt-1">Current image (also shown on the public Contact page breadcrumb).</small>
                </div>
            @endif
            <input type="file" name="breadcrumb_image" class="form-control @error('breadcrumb_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif">
            @error('breadcrumb_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
        </div>
        <div class="col-12">
            <label class="form-label">Contact form heading</label>
            <input type="text" name="form_title" class="form-control" value="{{ old('form_title', $contactDetail->form_title) }}" placeholder="Get in Touch">
        </div>
        <div class="col-12">
            <label class="form-label">Contact form introduction</label>
            <textarea name="form_subtitle" class="form-control" rows="3" placeholder="Short sentence inviting visitors to send a message.">{{ old('form_subtitle', $contactDetail->form_subtitle) }}</textarea>
        </div>
        <div class="col-12"><label class="form-label">Google Map Embed (iframe src or full embed HTML)</label><textarea name="google_map_embed" class="form-control" rows="4">{{ old('google_map_embed', $contactDetail->google_map_embed) }}</textarea></div>
    </div>
    <button type="submit" class="btn btn-primary mt-4">Save Contact Details</button>
</form>
@endsection
