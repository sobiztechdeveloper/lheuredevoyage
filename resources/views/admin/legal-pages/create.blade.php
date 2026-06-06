@extends('layouts.admin.app')
@section('title', 'Create Legal Page')
@section('content')
<form method="POST" action="{{ route('admin.legal-pages.store') }}" class="admin-catalog-form">
    @csrf
    @include('admin.legal-pages._form', ['page' => $page])
    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.legal-pages.index')])
</form>
@endsection
