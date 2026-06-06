@extends('layouts.admin.app')
@section('title', 'Edit FAQ')
@section('content')
<form method="POST" action="{{ route('admin.faqs.update', $faq) }}" class="admin-catalog-form">
    @csrf @method('PUT')
    @include('admin.faqs._form')
    @include('admin.partials.form-sticky-actions', ['cancelUrl' => route('admin.faqs.index')])
</form>
@endsection
