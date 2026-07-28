<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emergency;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    // Dual-write called by the client right after creating the emergency in
    // Firestore (which stays the live/authoritative path — it's what
    // triggers the real-time admin push alert via Cloud Function). This
    // just gives the Laravel-backed admin dashboard a reliable copy to read.
    public function store(Request $request)
    {
        $data = $request->validate([
            'firestore_id' => ['nullable', 'string'],
            'ride_id' => ['nullable', 'string'],
            'triggered_by_role' => ['required', 'in:student,driver'],
            'type' => ['nullable', 'string'],
            'message' => ['nullable', 'string'],
            'lat' => ['nullable', 'numeric'],
            'lng' => ['nullable', 'numeric'],
        ]);

        $emergency = Emergency::create([
            ...$data,
            'triggered_by_user_id' => $request->user()->id,
            'status' => 'open',
        ]);

        return response()->json($emergency, 201);
    }
}
