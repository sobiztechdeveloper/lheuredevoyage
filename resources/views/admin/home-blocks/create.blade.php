@extends('layouts.admin.app')
@section('title', 'Add Home Block')
@section('content')
<form method="POST" action="{{ route('admin.home-blocks.store') }}" enctype="multipart/form-data">@csrf
    @include('admin.home-blocks._form')
    <button class="btn btn-primary mt-4">Create</button>
    <a href="{{ route('admin.home-blocks.index') }}" class="btn btn-link">Cancel</a>
</form>
@endsection
