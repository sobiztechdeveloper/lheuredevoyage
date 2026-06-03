@extends('layouts.admin.app')
@section('title', 'Add FAQ')
@section('content')
<form method="POST" action="{{ route('admin.faqs.store') }}">@csrf @include('admin.faqs._form')<button class="btn btn-primary mt-4">Create</button></form>
@endsection
