<!DOCTYPE html>
<html lang="en">

<head>
    <!-- meta tags -->
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="L'Heure De Voyage" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="cookie-consent-url" content="{{ route('cookie-consent.store') }}">
    @php $defaultSiteName = ($siteSettings ?? null)?->company_name ?? "L'Heure De Voyage"; @endphp
    <x-seo-meta
        :title="trim(View::yieldContent('title') ?: $defaultSiteName)"
        :description="trim(View::yieldContent('meta_description')) ?: null"
        :keywords="trim(View::yieldContent('meta_keywords')) ?: null"
        :canonical="trim(View::yieldContent('canonical')) ?: null"
    />

    <!-- favicon -->
    <link rel="icon" type="image/x-icon" href="{{ ($siteSettings ?? null)?->favicon_url ?? asset('assets/img/logo/favicon.png') }}">

    <!-- css -->
    @include('layouts.styles')

    <!-- Additional Page-Specific Styles -->
    @stack('styles')
</head>

<body class="@yield('body-class', '')">

    <!-- header area -->
    <header class="header">

        <!-- header-top -->
        @include('layouts.header')
        <!-- header-top end -->

        <!-- navbar -->
        @include('layouts.navbar')
        <!-- navbar end -->

    </header>
    <!-- header area end -->

    <main class="main">
        <!-- Page Content -->
        @yield('content')
    </main>

    <!-- footer area -->
    @include('layouts.footer')
    <!-- footer area end -->

    <x-cookie-consent-banner />

    <!-- scroll-top -->
    <a href="#" id="scroll-top"><i class="far fa-angle-up"></i></a>
    <!-- scroll-top end -->

    <!-- js -->
    @include('layouts.scripts')
    <script src="{{ asset('assets/js/cookie-consent.js') }}?v={{ file_exists(public_path('assets/js/cookie-consent.js')) ? filemtime(public_path('assets/js/cookie-consent.js')) : time() }}"></script>
    <!-- Additional Page-Specific Scripts -->
    @stack('scripts')

</body>

</html>