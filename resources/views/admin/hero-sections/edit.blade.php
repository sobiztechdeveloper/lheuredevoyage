@extends('layouts.admin.app')

@section('title', 'Edit Hero Section')

@section('content')
<form method="POST" action="{{ route('admin.hero-sections.update', $heroSection) }}" enctype="multipart/form-data">
    @csrf @method('PUT')
    @include('admin.hero-sections._form')
    <button type="submit" class="btn btn-primary mt-4">Update</button>
    <a href="{{ route('admin.hero-sections.index') }}" class="btn btn-link">Cancel</a>
</form>
@endsection
