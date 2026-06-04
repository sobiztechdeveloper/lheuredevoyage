@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

@php
    $contact = $contactDetail ?? \App\Models\ContactDetail::query()->first() ?? new \App\Models\ContactDetail;
@endphp

<x-site-breadcrumb
    title="Contact Us"
    page="contact"
    :image="$contact->breadcrumb_image ? $contact->breadcrumb_image_url : null"
/>

<!-- contact area -->
<div class="contact-area py-120">
    <div class="container">
        <div class="contact-wrapper">
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact-content">
                        @include('partials.cms.contact-info', ['contactDetail' => $contact])
                    </div>
                </div>
                <div class="col-lg-8 align-self-center">
                    <div class="contact-form">
                        <div class="contact-form-header">
                            <h2>{{ $contact->resolvedFormTitle() }}</h2>
                            <p>{{ $contact->resolvedFormSubtitle() }}</p>
                        </div>
                        @if(session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif
                        <form method="POST" action="{{ route('contact.store') }}" id="contact-form">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text" class="form-control" name="name"
                                            placeholder="Your Name" value="{{ old('name') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="email" class="form-control" name="email"
                                            placeholder="Your Email" value="{{ old('email') }}" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="phone"
                                    placeholder="Your Phone" value="{{ old('phone') }}">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="subject"
                                    placeholder="Your Subject" value="{{ old('subject') }}">
                            </div>
                            <div class="form-group">
                                <textarea name="message" cols="30" rows="5" class="form-control"
                                    placeholder="Write Your Message" required>{{ old('message') }}</textarea>
                            </div>
                            <button type="submit" class="theme-btn">Send
                                Message <i class="far fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- end contact area -->

@if($contact->google_map_embed)
<!-- map -->
<div class="contact-map">
    @if(str_contains($contact->google_map_embed, '<iframe'))
        {!! $contact->google_map_embed !!}
    @else
        <iframe src="{{ $contact->google_map_embed }}" style="border:0;" allowfullscreen="" loading="lazy" title="Office location map"></iframe>
    @endif
</div>
<!-- map end -->
@endif

@include('partials.cms.faqs', [
    'faqs' => $faqs ?? collect(),
    'sectionClass' => 'inner-page-faq',
])

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection
