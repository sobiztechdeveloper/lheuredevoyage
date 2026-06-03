@extends('layouts.admin.app')

@section('title', 'Add Hero Section')

@section('content')
<form method="POST" action="{{ route('admin.hero-sections.store') }}" enctype="multipart/form-data">
    @csrf
    @include('admin.hero-sections._form')
    <button type="submit" class="btn btn-primary mt-4">Create</button>
    <a href="{{ route('admin.hero-sections.index') }}" class="btn btn-link">Cancel</a>
</form>
@endsection
