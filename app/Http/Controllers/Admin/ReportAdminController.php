<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ReportAdminController extends Controller
{
    public function __construct(
        protected ReportService $reports,
    ) {}

    public function bookings(Request $request): View
    {
        $from = $request->filled('from') ? Carbon::parse($request->input('from')) : now()->subDays(30);
        $to = $request->filled('to') ? Carbon::parse($request->input('to')) : now();

        return view('admin.reports.bookings', [
            'bookings' => $this->reports->bookingReport($from, $to, $request->input('status')),
            'byStatus' => $this->reports->bookingsByStatus(),
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
            'status' => $request->input('status'),
        ]);
    }

    public function customers(Request $request): View
    {
        $from = $request->filled('from') ? Carbon::parse($request->input('from')) : now()->subDays(30);
        $to = $request->filled('to') ? Carbon::parse($request->input('to')) : now();

        $report = $this->reports->customerReport($from, $to);

        return view('admin.reports.customers', [
            'report' => $report,
            'from' => $from->toDateString(),
            'to' => $to->toDateString(),
        ]);
    }
}
