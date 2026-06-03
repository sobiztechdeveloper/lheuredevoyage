<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Illuminate\View\View;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected ReportService $reports,
    ) {}

    public function index(): View
    {
        return view('admin.dashboard', [
            'stats' => $this->reports->dashboardStats(),
        ]);
    }
}
