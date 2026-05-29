@extends('layouts.app')

@section('styles')

@endsection

@section('content')

<!-- profile booking history -->

<div class="user-profile py-120">
    <div class="container">
        <div class="row">
            @include('layouts.publicUserAdmin.sidebar')
            @yield('userAdmincontent')
        </div>
    </div>
</div>

<!-- profile booking history end -->

@endsection

@section('modal')

@endsection

@section('scripts')

@endsection