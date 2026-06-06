@extends('layouts.admin.app')
@section('title', 'Add FAQ')
@section('content')
<form method="POST" action="{{ route('admin.faqs.store') }}" class="admin-catalog-form">
    @csrf
    @include('admin.faqs._form')
    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.faqs.index')])
</form>
@endsection
