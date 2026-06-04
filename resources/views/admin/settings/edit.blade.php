@extends('layouts.admin.app')

@section('title', 'Website Settings')

@section('content')
<div class="d-flex justify-content-between align-items-start mb-3">
    <div>
        <h2 class="h4 mb-1">Website Settings</h2>
        <p class="text-muted small mb-0">These values power the public site header, footer, SEO, legal pages, and default page banners. Changes appear immediately after save.</p>
    </div>
    <a href="{{ route('home') }}" target="_blank" rel="noopener" class="btn btn-outline-secondary btn-sm">View site</a>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form method="POST" action="{{ route('admin.settings.update') }}" enctype="multipart/form-data">
    @csrf

    <div class="card mb-4">
        <div class="card-header fw-semibold">Company &amp; contact <span class="text-muted fw-normal small">— header top bar, footer, invoices</span></div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label">Company Name</label>
                <input type="text" name="company_name" class="form-control @error('company_name') is-invalid @enderror" value="{{ old('company_name', $settings->company_name) }}">
                @error('company_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Company Email</label>
                <input type="email" name="company_email" class="form-control @error('company_email') is-invalid @enderror" value="{{ old('company_email', $settings->company_email) }}">
                @error('company_email')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Company Phone</label>
                <input type="text" name="company_phone" class="form-control @error('company_phone') is-invalid @enderror" value="{{ old('company_phone', $settings->company_phone) }}">
                @error('company_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Company Address</label>
                <textarea name="company_address" class="form-control @error('company_address') is-invalid @enderror" rows="2">{{ old('company_address', $settings->company_address) }}</textarea>
                @error('company_address')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-4">
                <label class="form-label">VAT Number</label>
                <input type="text" name="vat_number" class="form-control" value="{{ old('vat_number', $settings->vat_number) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Registration Number</label>
                <input type="text" name="registration_number" class="form-control" value="{{ old('registration_number', $settings->registration_number) }}">
            </div>
            <div class="col-md-4">
                <label class="form-label">Business Hours</label>
                <input type="text" name="business_hours" class="form-control" value="{{ old('business_hours', $settings->business_hours) }}" placeholder="Mon–Fri 09:00–18:00 CET">
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-semibold">Branding <span class="text-muted fw-normal small">— navbar, footer, browser tab, social sharing</span></div>
        <div class="card-body row g-3">
            <div class="col-md-6">
                <label class="form-label">Logo</label>
                <p class="text-muted small">Shown in the navigation bar and footer.</p>
                @if($settings->logo)
                    <img src="{{ $settings->logo_url }}" alt="Logo preview" class="d-block mb-2 rounded border bg-white p-2" style="max-height:64px;width:auto;">
                @endif
                <input type="file" name="logo" class="form-control @error('logo') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/svg+xml">
                @error('logo')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-md-6">
                <label class="form-label">Favicon</label>
                <p class="text-muted small">Browser tab icon.</p>
                @if($settings->favicon)
                    <img src="{{ $settings->favicon_url }}" alt="Favicon preview" class="d-block mb-2 rounded border" style="max-height:40px;width:auto;">
                @endif
                <input type="file" name="favicon" class="form-control @error('favicon') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/x-icon,image/vnd.microsoft.icon">
                @error('favicon')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="col-12">
                <label class="form-label">Default breadcrumb background</label>
                <p class="text-muted small mb-2">Used on inner pages (flights, hotels, legal, etc.) when no page-specific banner is set. Recommended: 1920×350 px.</p>
                @if($settings->default_breadcrumb_image)
                    <img src="{{ $settings->default_breadcrumb_image_url }}" alt="Breadcrumb preview" class="d-block mb-2 rounded border" style="max-height:80px;width:auto;max-width:100%;">
                @endif
                <input type="file" name="default_breadcrumb_image" class="form-control @error('default_breadcrumb_image') is-invalid @enderror" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('default_breadcrumb_image')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-semibold">Social media <span class="text-muted fw-normal small">— footer icons</span></div>
        <div class="card-body row g-3">
            <div class="col-md-6"><label class="form-label">Facebook URL</label><input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings->facebook_url) }}" placeholder="https://"></div>
            <div class="col-md-6"><label class="form-label">Instagram URL</label><input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $settings->instagram_url) }}" placeholder="https://"></div>
            <div class="col-md-6"><label class="form-label">LinkedIn URL</label><input type="url" name="linkedin_url" class="form-control" value="{{ old('linkedin_url', $settings->linkedin_url) }}" placeholder="https://"></div>
            <div class="col-md-6"><label class="form-label">YouTube URL</label><input type="url" name="youtube_url" class="form-control" value="{{ old('youtube_url', $settings->youtube_url) }}" placeholder="https://"></div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header fw-semibold">Footer <span class="text-muted fw-normal small">— site-wide footer block</span></div>
        <div class="card-body row g-3">
            <div class="col-12">
                <label class="form-label">Footer Text</label>
                <textarea name="footer_text" class="form-control" rows="3">{{ old('footer_text', $settings->footer_text) }}</textarea>
            </div>
            <div class="col-12">
                <label class="form-label">Copyright Text</label>
                <input type="text" name="copyright_text" class="form-control" value="{{ old('copyright_text', $settings->copyright_text) }}" placeholder="All Rights Reserved.">
            </div>
        </div>
    </div>

    <button type="submit" class="btn btn-primary">Save settings</button>
</form>

<div class="card mt-4 border-0 bg-light">
    <div class="card-body small text-muted">
        <strong>Also managed elsewhere:</strong>
        <a href="{{ route('admin.contact-details.edit') }}">Contact Details</a> (contact page sidebar &amp; map),
        <a href="{{ route('admin.about.edit') }}">About Page</a> (about breadcrumb),
        <a href="{{ route('admin.hero-sections.index') }}">Hero Sections</a> (homepage banner).
    </div>
</div>
@endsection
