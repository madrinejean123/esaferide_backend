<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\Emergency;
use Illuminate\Http\Request;

class EmergencyController extends Controller
{
    public function index(Request $request)
    {
        return Emergency::with('triggeredByUser:id,name')
            ->latest()
            ->limit(100)
            ->get();
    }

    public function updateStatus(Request $request, Emergency $emergency)
    {
        $data = $request->validate([
            'status' => ['required', 'in:open,responding,resolved'],
            'resolution_note' => ['nullable', 'string'],
        ]);

        $update = [
            'status' => $data['status'],
        ];
        if (array_key_exists('resolution_note', $data)) {
            $update['resolution_note'] = $data['resolution_note'];
        }
        if ($data['status'] === 'resolved') {
            $update['resolved_at'] = now();
            $update['resolved_by'] = $request->user()->id;
        }

        $emergency->update($update);

        return $emergency->fresh('triggeredByUser:id,name');
    }
}
