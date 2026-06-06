@extends('layouts.admin.app')
@section('title', 'Edit Testimonial')
@section('content')
<form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data" class="admin-catalog-form">
    @csrf @method('PUT')
    @include('admin.testimonials._form')
    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.testimonials.index')])
</form>
@endsection
