<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — L'Heure De Voyage</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/all-fontawesome.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin-panel.css') }}">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
    @stack('styles')
</head>
<body class="admin-body">
<div class="admin-wrapper" id="adminWrapper">
    <div class="admin-sidebar-backdrop" id="adminSidebarBackdrop"></div>
    @include('layouts.admin.sidebar')

    <div class="admin-main">
        @include('layouts.admin.header')

        <div class="admin-content">
            @if(session('success'))
                <div id="adminFlashSuccess" class="d-none" data-message="{{ session('success') }}"></div>
            @endif
            @if($errors->any())
                <div id="adminFlashError" class="d-none" data-message="{{ $errors->first() }}"></div>
                <div class="alert alert-danger alert-dismissible fade show mb-3" role="alert">
                    <strong>Please fix the following:</strong>
                    <ul class="mb-0 mt-2 small">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            @yield('content')
        </div>
    </div>
</div>

<div class="admin-toast-container" id="adminToastContainer" aria-live="polite"></div>

<script src="{{ asset('assets/js/jquery-3.7.1.min.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.bundle.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('assets/js/admin-panel.js') }}"></script>
@stack('scripts')
</body>
</html>
