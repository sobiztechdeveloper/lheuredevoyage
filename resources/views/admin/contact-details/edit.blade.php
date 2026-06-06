@extends('layouts.admin.app')

@section('title', 'Contact Details')

@section('content')
<form method="POST" action="{{ route('admin.contact-details.update') }}" enctype="multipart/form-data" class="admin-catalog-form">
    @csrf

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-address-book"></i> Contact Information</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                <div class="col-12">
                    <label class="admin-field-label">Address</label>
                    <textarea name="address" class="form-control" rows="2">{{ old('address', $contactDetail->address) }}</textarea>
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="{{ old('phone', $contactDetail->phone) }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">Email</label>
                    <input type="email" name="email" class="form-control" value="{{ old('email', $contactDetail->email) }}">
                </div>
                <div class="col-md-6">
                    <label class="admin-field-label">WhatsApp Number</label>
                    <input type="text" name="whatsapp_number" class="form-control" value="{{ old('whatsapp_number', $contactDetail->whatsapp_number) }}">
                </div>
            </div>
        </div>
    </div>

    <div class="admin-form-card">
        <div class="admin-form-card-header">
            <h2><i class="far fa-envelope"></i> Contact Page Content</h2>
        </div>
        <div class="admin-form-card-body">
            <div class="row g-3">
                @include('admin.partials.single-image-upload', [
                    'name' => 'breadcrumb_image',
                    'label' => 'Breadcrumb Image',
                    'hint' => 'Optional. Overrides the site default on the Contact page only.',
                    'currentUrl' => $contactDetail->breadcrumb_image ? $contactDetail->breadcrumb_image_url : null,
                    'class' => 'col-12',
                ])
                <div class="col-12">
                    <label class="admin-field-label">Contact Form Heading</label>
                    <input type="text" name="form_title" class="form-control" value="{{ old('form_title', $contactDetail->form_title) }}" placeholder="Get in Touch">
                </div>
                <div class="col-12">
                    <label class="admin-field-label">Contact Form Introduction</label>
                    <textarea name="form_subtitle" class="form-control" rows="3" placeholder="Short sentence inviting visitors to send a message.">{{ old('form_subtitle', $contactDetail->form_subtitle) }}</textarea>
                </div>
                <div class="col-12">
                    <label class="admin-field-label">Google Map Embed</label>
                    <textarea name="google_map_embed" class="form-control" rows="4" placeholder="iframe src or full embed HTML">{{ old('google_map_embed', $contactDetail->google_map_embed) }}</textarea>
                </div>
            </div>
        </div>
    </div>

    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.dashboard')])
</form>
@endsection
