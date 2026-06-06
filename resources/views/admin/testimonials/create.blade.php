@extends('layouts.admin.app')
@section('title', 'Add Testimonial')
@section('content')
<form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data" class="admin-catalog-form">
    @csrf
    @include('admin.testimonials._form')
    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.testimonials.index')])
</form>
@endsection
