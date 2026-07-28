<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RideReport;
use Illuminate\Http\Request;

class RideReportController extends Controller
{
    // Dual-write called from ride_service.dart's createRide(), right after
    // the Firestore ride doc is created. Firestore stays authoritative for
    // the live matching flow — this just gives admin Reports a reliable copy.
    // Idempotent (updateOrCreate) since it's a best-effort, fire-after call.
    public function store(Request $request)
    {
        $data = $request->validate([
            'ride_id' => ['required', 'string'],
            'pickup_label' => ['nullable', 'string'],
            'destination_label' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
        ]);

        $report = RideReport::updateOrCreate(
            ['ride_id' => $data['ride_id']],
            [
                'student_user_id' => $request->user()->id,
                'pickup_label' => $data['pickup_label'] ?? null,
                'destination_label' => $data['destination_label'] ?? null,
                'notes' => $data['notes'] ?? null,
                'status' => 'pending',
                'ride_created_at' => now(),
            ],
        );

        return response()->json($report, 201);
    }

    // Dual-write for every subsequent status change (accept/cancel/reject/
    // complete) — called from the same set of ride_service.dart methods
    // right after their Firestore transaction succeeds. Best-effort.
    public function update(Request $request)
    {
        $data = $request->validate([
            'ride_id' => ['required', 'string'],
            'status' => ['required', 'string'],
            'cancel_reason' => ['nullable', 'string'],
            'fare' => ['nullable', 'numeric'],
        ]);

        $report = RideReport::where('ride_id', $data['ride_id'])->first();
        if (! $report) {
            abort(404, 'Ride report not found — was it never created?');
        }

        $update = ['status' => $data['status']];
        if ($data['status'] === 'accepted') {
            $update['driver_user_id'] = $request->user()->id;
        }
        if (array_key_exists('cancel_reason', $data)) {
            $update['cancel_reason'] = $data['cancel_reason'];
        }
        if (array_key_exists('fare', $data)) {
            $update['fare'] = $data['fare'];
        }

        $report->update($update);

        return $report->fresh();
    }
}
