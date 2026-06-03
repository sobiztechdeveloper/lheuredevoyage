@extends('layouts.app')

@section('title', 'Access Denied — L\'Heure De Voyage')

@section('content')
<section class="py-120">
    <div class="container text-center">
        <h1 class="display-4 text-primary">403</h1>
        <h2 class="mb-3">Access denied</h2>
        <p class="text-muted mb-4">You do not have permission to view this page.</p>
        <a href="{{ route('home') }}" class="theme-btn">Back to Home</a>
    </div>
</section>
@endsection
