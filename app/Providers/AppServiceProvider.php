<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\CarBookingRequest;
use App\Models\ContactDetail;
use App\Models\CruiseBookingRequest;
use App\Models\FlightBookingRequest;
use App\Models\LegalPage;
use App\Models\Quote;
use App\Models\InsuranceBookingRequest;
use App\Policies\LegalPagePolicy;
use App\Policies\QuotePolicy;
use App\Services\LegalPageService;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Cruise;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\RentalCar;
use App\Models\TourPackage;
use App\Models\TravelInsurance;
use App\Models\TravelDestination;
use App\Models\Airline;
use App\Models\CruiseLine;
use App\Models\WebsiteSetting;
use App\Policies\BookingPolicy;
use App\Policies\CatalogItemPolicy;
use App\Policies\AirlinePolicy;
use App\Policies\CruiseLinePolicy;
use App\Policies\MasterDataPolicy;
use App\Policies\TravelDestinationPolicy;
use App\Policies\SupportTicketPolicy;
use App\Policies\UserPolicy;
use App\Services\MasterDataRegistry;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(\App\Services\CurrencyService::class);
    }

    public function boot(): void
    {
        Gate::policy(Hotel::class, CatalogItemPolicy::class);
        Gate::policy(Flight::class, CatalogItemPolicy::class);
        Gate::policy(TourPackage::class, CatalogItemPolicy::class);
        Gate::policy(Cruise::class, CatalogItemPolicy::class);
        Gate::policy(RentalCar::class, CatalogItemPolicy::class);
        Gate::policy(TravelInsurance::class, CatalogItemPolicy::class);
        Gate::policy(Booking::class, BookingPolicy::class);
        Gate::policy(LegalPage::class, LegalPagePolicy::class);
        Gate::policy(Quote::class, QuotePolicy::class);
        Gate::policy(SupportTicket::class, SupportTicketPolicy::class);
        Gate::policy(User::class, UserPolicy::class);
        Gate::policy(TravelDestination::class, TravelDestinationPolicy::class);
        Gate::policy(Airline::class, AirlinePolicy::class);
        Gate::policy(CruiseLine::class, CruiseLinePolicy::class);

        foreach (MasterDataRegistry::types() as $config) {
            Gate::policy($config['model'], MasterDataPolicy::class);
        }

        Route::bind('masterRecord', function (string $value, \Illuminate\Routing\Route $route) {
            $class = MasterDataRegistry::modelClass($route->parameter('type'));

            return ctype_digit($value)
                ? $class::query()->whereKey($value)->firstOrFail()
                : $class::query()->where('slug', $value)->firstOrFail();
        });

        Paginator::useBootstrapFive();

        View::composer(['components.catalog-card', 'components.catalog-detail-body'], function ($view) {
            if (! auth()->check()) {
                $view->with('wishlistedKeys', []);

                return;
            }

            $keys = auth()->user()->wishlists()
                ->get()
                ->map(fn ($w) => class_basename($w->wishlistable_type).':'.$w->wishlistable_id)
                ->all();

            $view->with('wishlistedKeys', $keys);
        });

        View::composer([
            'layouts.app',
            'layouts.footer',
            'layouts.header',
            'layouts.navbar',
            'layouts.scripts',
            'components.seo-meta',
            'partials.cms.contact-info',
            'pages.publicView.contact',
        ], function ($view) {
            $view->with('siteSettings', WebsiteSetting::cached());
            $view->with('siteContact', ContactDetail::cached());
            $view->with('displayCurrency', display_currency());
        });

        $legalComposer = function ($view) {
            $service = app(LegalPageService::class);
            $view->with('legalFooterPages', LegalPage::footerMenuPages());
            $view->with('legalFooterBarPages', LegalPage::footerBarPages());
            $view->with('legalUrls', $service->activeUrlMap());
        };

        View::composer([
            'layouts.footer',
            'components.legal-booking-consent',
            'components.legal-quote-acceptance',
            'components.cookie-consent-banner',
        ], $legalComposer);

        View::composer(['layouts.admin.sidebar', 'layouts.admin.header'], function ($view) {
            $view->with('flightRequestsNewCount', FlightBookingRequest::query()->where('status', 'new')->count());
            $view->with('hotelRequestsNewCount', \App\Models\HotelBookingRequest::query()->where('status', 'new')->count());
            $view->with('cruiseRequestsNewCount', CruiseBookingRequest::query()->where('status', 'new')->count());
            $view->with('carRequestsNewCount', CarBookingRequest::query()->where('status', 'new')->count());
            $view->with('insuranceRequestsNewCount', InsuranceBookingRequest::query()->where('status', 'new')->count());
        });
    }
}
