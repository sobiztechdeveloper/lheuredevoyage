@extends('layouts.admin.app')

@section('title', 'Add Hero Section')

@section('content')
<form method="POST" action="{{ route('admin.hero-sections.store') }}" enctype="multipart/form-data" class="admin-catalog-form">
    @csrf
    @include('admin.hero-sections._form')
    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.hero-sections.index')])
</form>
@endsection
