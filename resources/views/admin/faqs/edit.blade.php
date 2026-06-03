@extends('layouts.admin.app')
@section('title', 'Edit FAQ')
@section('content')
<form method="POST" action="{{ route('admin.faqs.update', $faq) }}">@csrf @method('PUT') @include('admin.faqs._form')<button class="btn btn-primary mt-4">Update</button></form>
@endsection
