@extends('layouts.admin.app')

@section('title', 'Edit Hero Section')

@section('content')
<form method="POST" action="{{ route('admin.hero-sections.update', $heroSection) }}" enctype="multipart/form-data" class="admin-catalog-form">
    @csrf @method('PUT')
    @include('admin.hero-sections._form')
    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.hero-sections.index')])
</form>
@endsection
