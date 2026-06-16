<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdministrativeRequest;
use Carbon\Carbon;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalRequests = AdministrativeRequest::count();

        $pendingRequests = AdministrativeRequest::where('status', 'pending')->count();

        $approvedRequests = AdministrativeRequest::where('status', 'approved')->count();

        $todayRequests = AdministrativeRequest::whereDate('created_at', Carbon::today())->count();

        $totalRevenue = AdministrativeRequest::where('status', 'approved')
            ->join('request_types', 'administrative_requests.request_type_id', '=', 'request_types.id')
            ->sum('request_types.price');

        $totalRevenueMonthly = AdministrativeRequest::where('status', 'approved')
            ->whereMonth('administrative_requests.created_at', Carbon::now()->month)
            ->join('request_types', 'administrative_requests.request_type_id', '=', 'request_types.id')
            ->sum('request_types.price');

        return view('admin.dashboard_stats', compact(
            'totalRequests',
            'pendingRequests',
            'approvedRequests',
            'todayRequests',
            'totalRevenue',
            'totalRevenueMonthly'
        ));
    }
}
