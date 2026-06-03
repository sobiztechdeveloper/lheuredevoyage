<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ActivityLogAdminController extends Controller
{
    public function index(Request $request): View
    {
        $logs = ActivityLog::query()
            ->with('user')
            ->when($request->input('q'), function ($q, $term) {
                $q->where('action', 'like', "%{$term}%");
            })
            ->latest()
            ->paginate(30)
            ->withQueryString();

        return view('admin.activity-logs.index', [
            'logs' => $logs,
            'search' => $request->input('q'),
        ]);
    }
}
