@include('partials.holiday-package-request.wizard', [
    'config' => $config ?? null,
    'locale' => $locale ?? app()->getLocale(),
])
