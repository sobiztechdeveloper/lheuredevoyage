@extends('layouts.app')

@section('title', 'Server Error — L\'Heure De Voyage')

@section('content')
<section class="py-120">
    <div class="container text-center">
        <h1 class="display-4 text-primary">500</h1>
        <h2 class="mb-3">Something went wrong</h2>
        <p class="text-muted mb-4">We are working to fix the issue. Please try again later.</p>
        <a href="{{ route('home') }}" class="theme-btn">Back to Home</a>
    </div>
</section>
@endsection
