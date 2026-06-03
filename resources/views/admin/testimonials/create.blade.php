@extends('layouts.admin.app')
@section('title', 'Add Testimonial')
@section('content')
<form method="POST" action="{{ route('admin.testimonials.store') }}" enctype="multipart/form-data">@csrf @include('admin.testimonials._form')<button class="btn btn-primary mt-4">Create</button></form>
@endsection
