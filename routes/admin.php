<?php

use App\Http\Controllers\Admin\AboutAdminController;
use App\Http\Controllers\Admin\ActivityLogAdminController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\BookingCancellationAdminController;
use App\Http\Controllers\Admin\BookingAdminController;
use App\Http\Controllers\Admin\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\Admin\AirlineAdminController;
use App\Http\Controllers\Admin\CruiseLineAdminController;
use App\Http\Controllers\Admin\ContactAdminController;
use App\Http\Controllers\Admin\DestinationAdminController;
use App\Http\Controllers\Admin\ContactDetailController;
use App\Http\Controllers\Admin\CruiseBookingRequestAdminController;
use App\Http\Controllers\Admin\CruiseAdminController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FlightAdminController;
use App\Http\Controllers\Admin\FlightBookingRequestAdminController;
use App\Http\Controllers\Admin\HotelBookingRequestAdminController;
use App\Http\Controllers\Admin\HotelRoomAdminController;
use App\Http\Controllers\Admin\CarBookingRequestAdminController;
use App\Http\Controllers\Admin\InsuranceBookingRequestAdminController;
use App\Http\Controllers\Admin\AdminBookingRequestFileController;
use App\Http\Controllers\Admin\QuoteAdminController;
use App\Http\Controllers\Admin\LegalPageAdminController;
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
        if (auth('admin')->check()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('admin.login');
    });

    Route::get('login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AdminAuthenticatedSessionController::class, 'store'])->name('login.store');

    Route::middleware(['auth:admin', 'admin.guard'])->group(function () {
        Route::post('logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');

        Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        Route::get('settings', [WebsiteSettingController::class, 'edit'])->name('settings.edit');
        Route::match(['put', 'post'], 'settings', [WebsiteSettingController::class, 'update'])->name('settings.update');

        Route::post('hero-sections/reorder', [HeroSectionController::class, 'reorder'])->name('hero-sections.reorder');
        Route::resource('hero-sections', HeroSectionController::class)->except(['show']);

        Route::resource('testimonials', TestimonialController::class)->except(['show']);

        Route::resource('faqs', FaqController::class)->except(['show']);

        Route::resource('home-blocks', HomeBlockController::class)->except(['show']);

        Route::get('contact-details', [ContactDetailController::class, 'edit'])->name('contact-details.edit');
        Route::match(['put', 'post'], 'contact-details', [ContactDetailController::class, 'update'])->name('contact-details.update');

        Route::resource('bookings', BookingAdminController::class)->only(['index', 'show', 'update']);

        Route::get('flight-requests', [FlightBookingRequestAdminController::class, 'index'])->name('flight-requests.index');
        Route::get('flight-requests/{flightBookingRequest}', [FlightBookingRequestAdminController::class, 'show'])->name('flight-requests.show');
        Route::put('flight-requests/{flightBookingRequest}', [FlightBookingRequestAdminController::class, 'update'])->name('flight-requests.update');
        Route::post('flight-requests/{flightBookingRequest}/generate-quote', [QuoteAdminController::class, 'createFromFlight'])->name('flight-requests.generate-quote');

        Route::get('hotel-requests', [HotelBookingRequestAdminController::class, 'index'])->name('hotel-requests.index');
        Route::get('hotel-requests/{hotelBookingRequest}', [HotelBookingRequestAdminController::class, 'show'])->name('hotel-requests.show');
        Route::put('hotel-requests/{hotelBookingRequest}', [HotelBookingRequestAdminController::class, 'update'])->name('hotel-requests.update');
        Route::post('hotel-requests/{hotelBookingRequest}/generate-quote', [QuoteAdminController::class, 'createFromHotel'])->name('hotel-requests.generate-quote');

        Route::get('cruise-requests', [CruiseBookingRequestAdminController::class, 'index'])->name('cruise-requests.index');
        Route::get('cruise-requests/{cruiseBookingRequest}', [CruiseBookingRequestAdminController::class, 'show'])->name('cruise-requests.show');
        Route::put('cruise-requests/{cruiseBookingRequest}', [CruiseBookingRequestAdminController::class, 'update'])->name('cruise-requests.update');
        Route::post('cruise-requests/{cruiseBookingRequest}/generate-quote', [QuoteAdminController::class, 'createFromCruise'])->name('cruise-requests.generate-quote');

        Route::get('car-requests', [CarBookingRequestAdminController::class, 'index'])->name('car-requests.index');
        Route::get('car-requests/{carBookingRequest}', [CarBookingRequestAdminController::class, 'show'])->name('car-requests.show');
        Route::put('car-requests/{carBookingRequest}', [CarBookingRequestAdminController::class, 'update'])->name('car-requests.update');
        Route::post('car-requests/{carBookingRequest}/generate-quote', [QuoteAdminController::class, 'createFromCar'])->name('car-requests.generate-quote');

        Route::get('insurance-requests', [InsuranceBookingRequestAdminController::class, 'index'])->name('insurance-requests.index');
        Route::get('insurance-requests/{insuranceBookingRequest}', [InsuranceBookingRequestAdminController::class, 'show'])->name('insurance-requests.show');
        Route::put('insurance-requests/{insuranceBookingRequest}', [InsuranceBookingRequestAdminController::class, 'update'])->name('insurance-requests.update');
        Route::post('insurance-requests/{insuranceBookingRequest}/generate-quote', [QuoteAdminController::class, 'createFromInsurance'])->name('insurance-requests.generate-quote');

        Route::get('booking-files/flight/{flightBookingRequest}/{document}', [AdminBookingRequestFileController::class, 'flightDocument'])
            ->where('document', 'ticket|invoice')
            ->name('booking-files.flight.document');
        Route::get('booking-files/flight/{flightBookingRequest}/passport/{passenger}', [AdminBookingRequestFileController::class, 'flightPassport'])
            ->name('booking-files.flight.passport');
        Route::get('booking-files/hotel/{hotelBookingRequest}/{document}', [AdminBookingRequestFileController::class, 'hotelDocument'])
            ->where('document', 'voucher|invoice|transfer_voucher')
            ->name('booking-files.hotel.document');
        Route::get('booking-files/cruise/{cruiseBookingRequest}/{document}', [AdminBookingRequestFileController::class, 'cruiseDocument'])
            ->where('document', 'voucher|invoice|ticket|boarding|boarding_instructions|excursion|excursion_details')
            ->name('booking-files.cruise.document');
        Route::get('booking-files/cruise/{cruiseBookingRequest}/uploaded/{document}', [AdminBookingRequestFileController::class, 'cruiseUploadedDocument'])
            ->name('booking-files.cruise.uploaded');
        Route::get('booking-files/cruise/{cruiseBookingRequest}/passport/{passenger}', [AdminBookingRequestFileController::class, 'cruisePassport'])
            ->name('booking-files.cruise.passport');
        Route::get('booking-files/car/{carBookingRequest}/{document}', [AdminBookingRequestFileController::class, 'carDocument'])
            ->where('document', 'voucher|invoice|rental_agreement')
            ->name('booking-files.car.document');
        Route::get('booking-files/car/{carBookingRequest}/driver/{driver}/{file}', [AdminBookingRequestFileController::class, 'carDriverFile'])
            ->where('file', 'passport|license')
            ->name('booking-files.car.driver');
        Route::get('booking-files/insurance/{insuranceBookingRequest}/{document}', [AdminBookingRequestFileController::class, 'insuranceDocument'])
            ->where('document', 'policy|invoice|coverage|coverage_certificate|claim_instructions')
            ->name('booking-files.insurance.document');
        Route::get('booking-files/insurance/{insuranceBookingRequest}/uploaded/{document}', [AdminBookingRequestFileController::class, 'insuranceUploadedDocument'])
            ->name('booking-files.insurance.uploaded');

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

        Route::get('destinations/trashed', [DestinationAdminController::class, 'trashed'])->name('destinations.trashed');
        Route::get('destinations/import', [DestinationAdminController::class, 'importForm'])->name('destinations.import');
        Route::post('destinations/import', [DestinationAdminController::class, 'import'])->name('destinations.import.store');
        Route::post('destinations/bulk', [DestinationAdminController::class, 'bulk'])->name('destinations.bulk');
        Route::post('destinations/{id}/restore', [DestinationAdminController::class, 'restore'])->name('destinations.restore');
        Route::patch('destinations/{destination}/toggle-status', [DestinationAdminController::class, 'toggleStatus'])->name('destinations.toggle-status');
        Route::resource('destinations', DestinationAdminController::class)->except(['show']);

        Route::get('airlines/trashed', [AirlineAdminController::class, 'trashed'])->name('airlines.trashed');
        Route::post('airlines/{id}/restore', [AirlineAdminController::class, 'restore'])->name('airlines.restore');
        Route::patch('airlines/{airline}/toggle-status', [AirlineAdminController::class, 'toggleStatus'])->name('airlines.toggle-status');
        Route::resource('airlines', AirlineAdminController::class)->except(['show'])->parameters(['airlines' => 'airline']);

        Route::get('cruise-lines/trashed', [CruiseLineAdminController::class, 'trashed'])->name('cruise-lines.trashed');
        Route::post('cruise-lines/{id}/restore', [CruiseLineAdminController::class, 'restore'])->name('cruise-lines.restore');
        Route::patch('cruise-lines/{cruise_line}/toggle-status', [CruiseLineAdminController::class, 'toggleStatus'])->name('cruise-lines.toggle-status');
        Route::resource('cruise-lines', CruiseLineAdminController::class)->except(['show'])->parameters(['cruise-lines' => 'cruise_line']);

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
        Route::get('hotels/{hotel}/rooms', [HotelRoomAdminController::class, 'index'])->name('hotels.rooms.index');
        Route::get('hotels/{hotel}/rooms/create', [HotelRoomAdminController::class, 'create'])->name('hotels.rooms.create');
        Route::post('hotels/{hotel}/rooms', [HotelRoomAdminController::class, 'store'])->name('hotels.rooms.store');
        Route::get('hotels/{hotel}/rooms/{room}/edit', [HotelRoomAdminController::class, 'edit'])->name('hotels.rooms.edit');
        Route::put('hotels/{hotel}/rooms/{room}', [HotelRoomAdminController::class, 'update'])->name('hotels.rooms.update');
        Route::delete('hotels/{hotel}/rooms/{room}', [HotelRoomAdminController::class, 'destroy'])->name('hotels.rooms.destroy');
        Route::resource('flights', FlightAdminController::class)->except(['show']);
        Route::resource('cruises', CruiseAdminController::class)->except(['show']);
        Route::resource('cars', RentalCarAdminController::class)->except(['show']);
        Route::resource('insurances', TravelInsuranceAdminController::class)->except(['show']);
        Route::resource('packages', TourPackageAdminController::class)->except(['show']);

        Route::resource('inquiries', ContactAdminController::class)->only(['index', 'show', 'destroy'])->parameters(['inquiries' => 'contact']);

        Route::get('about', [AboutAdminController::class, 'edit'])->name('about.edit');
        Route::match(['put', 'post'], 'about', [AboutAdminController::class, 'update'])->name('about.update');

        Route::get('legal-pages/trashed', [LegalPageAdminController::class, 'trashed'])->name('legal-pages.trashed');
        Route::post('legal-pages/{id}/restore', [LegalPageAdminController::class, 'restore'])->name('legal-pages.restore');
        Route::resource('legal-pages', LegalPageAdminController::class)
            ->parameters(['legal-pages' => 'legal_page'])
            ->except(['show']);
    });
});
