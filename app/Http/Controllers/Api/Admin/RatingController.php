<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use Illuminate\Http\Request;

class RatingController extends Controller
{
    public function index(Request $request)
    {
        return Trip::with(['driver:id,name', 'student:id,name'])
            ->whereNotNull('student_rating_stars')
            ->orderByDesc('student_rated_at')
            ->limit(200)
            ->get();
    }
}
