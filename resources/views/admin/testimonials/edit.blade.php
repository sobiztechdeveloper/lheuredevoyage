@extends('layouts.admin.app')
@section('title', 'Edit Testimonial')
@section('content')
<form method="POST" action="{{ route('admin.testimonials.update', $testimonial) }}" enctype="multipart/form-data">@csrf @method('PUT') @include('admin.testimonials._form')<button class="btn btn-primary mt-4">Update</button></form>
@endsection
