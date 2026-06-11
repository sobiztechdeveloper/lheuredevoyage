<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HolidayPackageRequest;
use App\Services\HolidayPackageRequestService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HolidayPackageRequestAdminController extends Controller
{
    public function __construct(
        protected HolidayPackageRequestService $service,
    ) {}

    public function index(Request $request): View
    {
        $requests = HolidayPackageRequest::query()
            ->searchTerm($request->input('q'))
            ->when($request->input('status'), fn ($q, $status) => $q->where('status', $status))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        return view('admin.holiday-package-requests.index', [
            'requests' => $requests,
            'statuses' => HolidayPackageRequest::STATUSES,
            'search' => $request->input('q'),
            'filterStatus' => $request->input('status'),
        ]);
    }

    public function show(HolidayPackageRequest $holidayPackageRequest): View
    {
        return view('admin.holiday-package-requests.show', [
            'request' => $holidayPackageRequest,
            'statuses' => HolidayPackageRequest::STATUSES,
            'config' => holiday_package_request_config(),
        ]);
    }

    public function update(Request $request, HolidayPackageRequest $holidayPackageRequest): RedirectResponse
    {
        $data = $request->validate([
            'status' => ['required', Rule::in(HolidayPackageRequest::STATUSES)],
            'agent_notes' => ['nullable', 'string', 'max:5000'],
        ]);

        $this->service->updateStatus(
            $holidayPackageRequest,
            $data['status'],
            $data['agent_notes'] ?? null,
        );

        return redirect()
            ->route('admin.holiday-package-requests.show', $holidayPackageRequest)
            ->with('success', 'Holiday package request updated.');
    }
}
