<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\BookingCancellationRequest;
use App\Models\Contact;
use App\Models\FlightBookingRequest;
use App\Models\Quote;
use App\Models\SupportTicket;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ReportService
{
    public function dashboardStats(): array
    {
        $now = now();
        $startOfMonth = $now->copy()->startOfMonth();

        return [
            'users' => User::query()->where('is_admin', false)->count(),
            'users_this_month' => User::query()
                ->where('is_admin', false)
                ->where('created_at', '>=', $startOfMonth)
                ->count(),
            'bookings' => Booking::query()->count(),
            'bookings_pending' => Booking::query()->where('status', 'pending')->count(),
            'bookings_confirmed' => Booking::query()->where('status', 'confirmed')->count(),
            'revenue_month' => (float) Booking::query()
                ->whereIn('status', ['confirmed', 'completed'])
                ->where('created_at', '>=', $startOfMonth)
                ->sum('total_amount'),
            'messages' => Contact::query()->whereNull('read_at')->count(),
            'tickets_open' => SupportTicket::query()->where('status', '!=', 'closed')->count(),
            'cancellations_pending' => BookingCancellationRequest::query()->where('status', 'pending')->count(),
            'flight_requests' => FlightBookingRequest::query()->count(),
            'flight_requests_new' => FlightBookingRequest::query()->where('status', 'new')->count(),
            'quotes_total' => Quote::query()->count(),
            'quotes_pending' => Quote::query()->whereIn('status', ['draft', 'sent', 'viewed'])->count(),
            'quotes_accepted' => Quote::query()->where('status', 'accepted')->count(),
            'quotes_rejected' => Quote::query()->where('status', 'rejected')->count(),
            'quotes_expired' => Quote::query()->where('status', 'expired')->count(),
            'heroes' => \App\Models\HeroSection::query()->active()->count(),
            'testimonials' => \App\Models\Testimonial::query()->active()->count(),
            'faqs' => \App\Models\Faq::query()->active()->count(),
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function bookingReport(?Carbon $from, ?Carbon $to, ?string $status = null): Collection
    {
        return Booking::query()
            ->with(['user', 'bookable'])
            ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('created_at', '<=', $to->endOfDay()))
            ->when($status, fn ($q) => $q->where('status', $status))
            ->latest()
            ->get();
    }

    /**
     * @return array{registrations: Collection, top_customers: Collection}
     */
    public function customerReport(?Carbon $from, ?Carbon $to): array
    {
        $userQuery = User::query()->where('is_admin', false);

        $registrations = (clone $userQuery)
            ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
            ->when($to, fn ($q) => $q->where('created_at', '<=', $to->endOfDay()))
            ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $topCustomers = User::query()
            ->where('is_admin', false)
            ->withCount('bookings')
            ->having('bookings_count', '>', 0)
            ->orderByDesc('bookings_count')
            ->limit(20)
            ->get();

        return [
            'registrations' => $registrations,
            'top_customers' => $topCustomers,
            'summary' => [
                'total' => (clone $userQuery)->count(),
                'in_period' => (clone $userQuery)
                    ->when($from, fn ($q) => $q->where('created_at', '>=', $from))
                    ->when($to, fn ($q) => $q->where('created_at', '<=', $to->endOfDay()))
                    ->count(),
            ],
        ];
    }

    /**
     * @return Collection<int, object>
     */
    public function bookingsByStatus(): Collection
    {
        return Booking::query()
            ->select('status', DB::raw('COUNT(*) as total'), DB::raw('SUM(total_amount) as amount'))
            ->groupBy('status')
            ->get();
    }
}
