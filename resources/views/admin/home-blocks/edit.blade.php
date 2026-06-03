@extends('layouts.admin.app')
@section('title', 'Edit Home Block')
@section('content')
<form method="POST" action="{{ route('admin.home-blocks.update', $block) }}" enctype="multipart/form-data">@csrf @method('PUT')
    @include('admin.home-blocks._form')
    <button class="btn btn-primary mt-4">Update</button>
    <a href="{{ route('admin.home-blocks.index') }}" class="btn btn-link">Cancel</a>
</form>
@endsection
