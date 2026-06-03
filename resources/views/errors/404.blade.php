@extends('layouts.app')

@section('title', 'Page Not Found — L\'Heure De Voyage')
@section('meta_description', 'The page you are looking for could not be found.')

@section('content')
<section class="py-120">
    <div class="container text-center">
        <h1 class="display-4 text-primary">404</h1>
        <h2 class="mb-3">Page not found</h2>
        <p class="text-muted mb-4">The page you requested does not exist or has been moved.</p>
        <a href="{{ route('home') }}" class="theme-btn">Back to Home</a>
    </div>
</section>
@endsection
