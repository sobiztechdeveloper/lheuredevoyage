@extends('layouts.app')

@section('body-class', 'home-3')

@section('styles')

@endsection

@section('content')

<!-- breadcrumb -->
<div class="site-breadcrumb" style="background: url(assets/img/breadcrumb/01.jpg)">
    <div class="container">
        <h2 class="breadcrumb-title">Contact Us</h2>
        <ul class="breadcrumb-menu">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="active">Contact Us</li>
        </ul>
    </div>
</div>
<!-- breadcrumb end -->


<!-- contact area -->
<div class="contact-area py-120">
    <div class="container">
        <div class="contact-wrapper">
            <div class="row">
                <div class="col-lg-4">
                    <div class="contact-content">
                        @include('partials.cms.contact-info', ['contactDetail' => $contactDetail ?? null])
                    </div>
                </div>
                <div class="col-lg-8 align-self-center">
                    <div class="contact-form">
                        <div class="contact-form-header">
                            <h2>Get In Touch</h2>
                            <p>It is a long established fact that a reader will be distracted by the readable
                                content of a page randomised words which don't look even slightly when looking at its layout. </p>
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

@if(($contactDetail ?? null)?->google_map_embed)
<!-- map -->
<div class="contact-map">
    @if(str_contains($contactDetail->google_map_embed, '<iframe'))
        {!! $contactDetail->google_map_embed !!}
    @else
        <iframe src="{{ $contactDetail->google_map_embed }}" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
    @endif
</div>
<!-- map end -->
@endif

@include('partials.cms.faqs', ['faqs' => $faqs ?? collect()])

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection