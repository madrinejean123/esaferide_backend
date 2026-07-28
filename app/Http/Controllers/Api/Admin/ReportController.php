<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\RideReport;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    // ?start=<ISO date>&end=<ISO date> — matches the admin page's
    // daily/weekly/monthly date-range navigator.
    public function index(Request $request)
    {
        $query = RideReport::with([
            'student:id,name',
            'student.student:user_id,reg_number',
            'driver:id,name',
            'driver.driver:user_id,phone',
        ])->orderByDesc('ride_created_at');

        if ($start = $request->query('start')) {
            $query->where('ride_created_at', '>=', Carbon::parse($start));
        }
        if ($end = $request->query('end')) {
            $query->where('ride_created_at', '<', Carbon::parse($end));
        }

        return $query->limit(1000)->get();
    }
}
