<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Trip;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TripController extends Controller
{
    // ?as=driver|student (defaults to the user's role) — which side of the
    // trip to view it from. ?since=<ISO date> for date-ranged queries
    // (e.g. driver earnings for the current week).
    public function index(Request $request)
    {
        $user = $request->user();
        $as = $request->query('as', $user->role === 'driver' ? 'driver' : 'student');

        $query = Trip::query()
            ->with(['driver:id,name', 'student:id,name'])
            ->where(
                $as === 'driver' ? 'driver_user_id' : 'student_user_id',
                $user->id,
            );

        if ($since = $request->query('since')) {
            $query->where('created_at', '>=', Carbon::parse($since));
        }

        return $query->latest()->get();
    }

    // Called by the driver client right after a ride is marked completed
    // (that flow itself still lives in Firestore/ride_service.dart — this
    // is a dual-write so trip history has a home in Laravel too).
    public function store(Request $request)
    {
        $data = $request->validate([
            'ride_id' => ['nullable', 'string'],
            'student_firebase_uid' => ['required', 'string'],
            'duration_seconds' => ['required', 'integer', 'min:0'],
            'fare' => ['required', 'numeric', 'min:0'],
            'pickup' => ['nullable', 'array'],
            'destination' => ['nullable', 'array'],
            'pickup_label' => ['nullable', 'string'],
            'destination_label' => ['nullable', 'string'],
        ]);

        $studentUser = User::where('firebase_uid', $data['student_firebase_uid'])->first();

        $trip = Trip::create([
            ...$data,
            'driver_user_id' => $request->user()->id,
            'student_user_id' => $studentUser?->id,
        ]);

        return response()->json($trip, 201);
    }

    // Called from the post-trip rating screens (student rates driver, driver
    // rates student). Matched by ride_id since that's all those screens have.
    public function rate(Request $request)
    {
        $data = $request->validate([
            'ride_id' => ['required', 'string'],
            'role' => ['required', 'in:student,driver'],
            'stars' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['nullable', 'string'],
        ]);

        $trip = Trip::where('ride_id', $data['ride_id'])->latest()->first();
        if (! $trip) {
            abort(404, 'Trip not found for this ride.');
        }

        $expectedUserId = $data['role'] === 'student' ? $trip->student_user_id : $trip->driver_user_id;
        if ($expectedUserId !== $request->user()->id) {
            abort(403, 'You are not a party to this trip.');
        }

        if ($data['role'] === 'student') {
            $trip->update([
                'student_rating_stars' => $data['stars'],
                'student_rating_comment' => $data['comment'] ?? null,
                'student_rated_at' => now(),
            ]);
        } else {
            $trip->update([
                'driver_rating_stars' => $data['stars'],
                'driver_rating_comment' => $data['comment'] ?? null,
                'driver_rated_at' => now(),
            ]);
        }

        return $trip->fresh();
    }
}
