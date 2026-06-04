@extends('layouts.admin.app')
@section('title', 'Edit Legal Page')
@section('content')
<h1 class="h4 mb-3">Edit: {{ $page->title }}</h1>
<form method="POST" action="{{ route('admin.legal-pages.update', $page) }}">
    @csrf @method('PUT')
    @include('admin.legal-pages._form', ['page' => $page])
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-admin-primary">Save Changes</button>
        <a href="{{ $page->publicUrl() }}" class="btn btn-admin-outline" target="_blank" rel="noopener">Preview</a>
        <a href="{{ route('admin.legal-pages.index') }}" class="btn btn-admin-outline">Back</a>
    </div>
</form>
@endsection
