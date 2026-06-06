@extends('layouts.admin.app')
@section('title', 'Edit Legal Page')
@section('content')
<form method="POST" action="{{ route('admin.legal-pages.update', $page) }}" class="admin-catalog-form">
    @csrf @method('PUT')
    @include('admin.legal-pages._form', ['page' => $page])
    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.legal-pages.index')])
</form>
@endsection
