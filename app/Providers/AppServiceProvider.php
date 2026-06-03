<?php

namespace App\Providers;

use App\Models\Booking;
use App\Models\ContactDetail;
use App\Models\FlightBookingRequest;
use App\Models\Quote;
use App\Policies\QuotePolicy;
use App\Models\SupportTicket;
use App\Models\User;
use App\Models\Cruise;
use App\Models\Flight;
use App\Models\Hotel;
use App\Models\RentalCar;
use App\Models\TourPackage;
use App\Models\TravelInsurance;
use App\Models\WebsiteSetting;
use App\Policies\BookingPolicy;
use App\Policies\CatalogItemPolicy;
use App\Policies\MasterDataPolicy;
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
        //
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
        Gate::policy(Quote::class, QuotePolicy::class);
        Gate::policy(SupportTicket::class, SupportTicketPolicy::class);
        Gate::policy(User::class, UserPolicy::class);

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

        View::composer(['layouts.app', 'layouts.footer', 'layouts.header', 'layouts.navbar'], function ($view) {
            $view->with('siteSettings', WebsiteSetting::cached());
            $view->with('siteContact', ContactDetail::cached());
        });

        View::composer(['layouts.admin.sidebar', 'layouts.admin.header'], function ($view) {
            $view->with('flightRequestsNewCount', FlightBookingRequest::query()->where('status', 'new')->count());
        });
    }
}
