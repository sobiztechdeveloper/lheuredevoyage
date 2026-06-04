@extends('layouts.admin.app')
@section('title', 'Create Legal Page')
@section('content')
<h1 class="h4 mb-3">Create Legal Page</h1>
<form method="POST" action="{{ route('admin.legal-pages.store') }}">
    @csrf
    @include('admin.legal-pages._form', ['page' => $page])
    <div class="mt-4 d-flex gap-2">
        <button type="submit" class="btn btn-admin-primary">Create Page</button>
        <a href="{{ route('admin.legal-pages.index') }}" class="btn btn-admin-outline">Cancel</a>
    </div>
</form>
@endsection
