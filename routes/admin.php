<?php

use App\Http\Controllers\Admin\AboutAdminController;
use App\Http\Controllers\Admin\ActivityLogAdminController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BookingCancellationAdminController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\Admin\ContactDetailController;
use App\Http\Controllers\Admin\CruiseAdminController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FlightAdminController;
use App\Http\Controllers\Admin\FlightBookingRequestAdminController;
use App\Http\Controllers\Admin\QuoteAdminController;
use App\Http\Controllers\Admin\HeroSectionController;
use App\Http\Controllers\Admin\HomeBlockController;
use App\Http\Controllers\Admin\HotelAdminController;
use App\Http\Controllers\Admin\RentalCarAdminController;
use App\Http\Controllers\Admin\TestimonialController;
use App\Http\Controllers\Admin\TourPackageAdminController;
use App\Http\Controllers\Admin\TravelInsuranceAdminController;
use App\Http\Controllers\Admin\ReportAdminController;
use App\Http\Controllers\Admin\SupportTicketAdminController;
use App\Http\Controllers\Admin\UserAdminController;
use App\Http\Controllers\Admin\MasterDataController;
use App\Http\Controllers\Admin\WebsiteSettingController;
use App\Services\MasterDataRegistry;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/', function () {
        if (auth()->check() && auth()->user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login');
    });

    Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::middleware(['auth', 'admin'])->group(function () {
        Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('settings', [WebsiteSettingController::class, 'edit'])->name('settings.edit');
        Route::put('settings', [WebsiteSettingController::class, 'update'])->name('settings.update');

        Route::post('hero-sections/reorder', [HeroSectionController::class, 'reorder'])->name('hero-sections.reorder');
        Route::resource('hero-sections', HeroSectionController::class)->except(['show']);

        Route::resource('testimonials', TestimonialController::class)->except(['show']);

        Route::resource('faqs', FaqController::class)->except(['show']);

        Route::resource('home-blocks', HomeBlockController::class)->except(['show']);

        Route::get('contact-details', [ContactDetailController::class, 'edit'])->name('contact-details.edit');
        Route::put('contact-details', [ContactDetailController::class, 'update'])->name('contact-details.update');

        Route::resource('bookings', BookingAdminController::class)->only(['index', 'show', 'update']);

        Route::get('flight-requests', [FlightBookingRequestAdminController::class, 'index'])->name('flight-requests.index');
        Route::get('flight-requests/{flightBookingRequest}', [FlightBookingRequestAdminController::class, 'show'])->name('flight-requests.show');
        Route::put('flight-requests/{flightBookingRequest}', [FlightBookingRequestAdminController::class, 'update'])->name('flight-requests.update');
        Route::post('flight-requests/{flightBookingRequest}/generate-quote', [QuoteAdminController::class, 'createFromFlight'])->name('flight-requests.generate-quote');

        Route::get('quotes', [QuoteAdminController::class, 'index'])->name('quotes.index');
        Route::get('quotes/create', [QuoteAdminController::class, 'create'])->name('quotes.create');
        Route::post('quotes', [QuoteAdminController::class, 'store'])->name('quotes.store');
        Route::get('quotes/{quote}', [QuoteAdminController::class, 'show'])->name('quotes.show');
        Route::get('quotes/{quote}/edit', [QuoteAdminController::class, 'edit'])->name('quotes.edit');
        Route::put('quotes/{quote}', [QuoteAdminController::class, 'update'])->name('quotes.update');
        Route::delete('quotes/{quote}', [QuoteAdminController::class, 'destroy'])->name('quotes.destroy');
        Route::post('quotes/{quote}/send', [QuoteAdminController::class, 'send'])->name('quotes.send');
        Route::get('quotes/{quote}/pdf', [QuoteAdminController::class, 'pdf'])->name('quotes.pdf');

        Route::get('bookings/{booking}/invoice', [BookingAdminController::class, 'invoice'])->name('bookings.invoice');
        Route::get('bookings/{booking}/invoice/pdf', [BookingAdminController::class, 'invoicePdf'])->name('bookings.invoice.pdf');

        Route::get('cancellation-requests', [BookingCancellationAdminController::class, 'index'])->name('cancellation-requests.index');
        Route::put('cancellation-requests/{cancellationRequest}', [BookingCancellationAdminController::class, 'update'])->name('cancellation-requests.update');

        Route::resource('users', UserAdminController::class)->only(['index', 'show', 'update']);

        Route::resource('support-tickets', SupportTicketAdminController::class)->only(['index', 'show']);
        Route::post('support-tickets/{supportTicket}/reply', [SupportTicketAdminController::class, 'reply'])->name('support-tickets.reply');
        Route::put('support-tickets/{supportTicket}', [SupportTicketAdminController::class, 'update'])->name('support-tickets.update');

        Route::get('activity-logs', [ActivityLogAdminController::class, 'index'])->name('activity-logs.index');

        Route::get('reports/bookings', [ReportAdminController::class, 'bookings'])->name('reports.bookings');
        Route::get('reports/customers', [ReportAdminController::class, 'customers'])->name('reports.customers');

        foreach (MasterDataRegistry::types() as $key => $cfg) {
            $prefix = 'master-data/'.$cfg['route'];
            $name = 'master-data.'.$key.'.';

            Route::get($prefix, [MasterDataController::class, 'index'])->defaults('type', $cfg['route'])->name($name.'index');
            Route::get($prefix.'/trashed', [MasterDataController::class, 'trashed'])->defaults('type', $cfg['route'])->name($name.'trashed');
            Route::get($prefix.'/create', [MasterDataController::class, 'create'])->defaults('type', $cfg['route'])->name($name.'create');
            Route::post($prefix, [MasterDataController::class, 'store'])->defaults('type', $cfg['route'])->name($name.'store');
            Route::get($prefix.'/{masterRecord}/edit', [MasterDataController::class, 'edit'])->defaults('type', $cfg['route'])->name($name.'edit');
            Route::put($prefix.'/{masterRecord}', [MasterDataController::class, 'update'])->defaults('type', $cfg['route'])->name($name.'update');
            Route::delete($prefix.'/{masterRecord}', [MasterDataController::class, 'destroy'])->defaults('type', $cfg['route'])->name($name.'destroy');
            Route::post($prefix.'/{id}/restore', [MasterDataController::class, 'restore'])->defaults('type', $cfg['route'])->name($name.'restore');
            Route::patch($prefix.'/{masterRecord}/toggle-status', [MasterDataController::class, 'toggleStatus'])->defaults('type', $cfg['route'])->name($name.'toggle-status');
        }

        Route::resource('hotels', HotelAdminController::class)->except(['show']);
        Route::resource('flights', FlightAdminController::class)->except(['show']);
        Route::resource('cruises', CruiseAdminController::class)->except(['show']);
        Route::resource('cars', RentalCarAdminController::class)->except(['show']);
        Route::resource('insurances', TravelInsuranceAdminController::class)->except(['show']);
        Route::resource('packages', TourPackageAdminController::class)->except(['show']);

        Route::resource('inquiries', ContactAdminController::class)->only(['index', 'show', 'destroy'])->parameters(['inquiries' => 'contact']);

        Route::get('about', [AboutAdminController::class, 'edit'])->name('about.edit');
        Route::put('about', [AboutAdminController::class, 'update'])->name('about.update');
    });
});
