<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\RideReport;
use App\Models\Student;
use Illuminate\Http\Request;

class OverviewController extends Controller
{
    public function index(Request $request)
    {
        return response()->json([
            'total_rides' => RideReport::count(),
            'students_count' => Student::count(),
            'drivers_count' => Driver::count(),
            'pending_drivers_count' => Driver::where('status', 'pending')->count(),
            'pending_drivers' => Driver::where('status', 'pending')
                ->latest()
                ->limit(5)
                ->get(['id', 'full_name']),
        ]);
    }
}
