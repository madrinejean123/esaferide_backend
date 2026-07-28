<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DriverProfileController extends Controller
{
    public function show(Request $request)
    {
        $driver = $request->user()->driver;
        if (! $driver) {
            abort(404, 'Not a driver account.');
        }

        return $driver;
    }

    public function update(Request $request)
    {
        $driver = $request->user()->driver;
        if (! $driver) {
            abort(404, 'Not a driver account.');
        }

        $data = $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'license_expiry_date' => ['nullable', 'date'],
            'psv_insurance_expiry_date' => ['nullable', 'date'],
            'national_id_number' => ['nullable', 'string', 'max:255'],
            'has_good_conduct_certificate' => ['sometimes', 'boolean'],
        ]);

        $data['full_name'] = trim($data['first_name'].' '.$data['last_name']);
        // Re-submitting for review resets verification — matches the
        // pre-migration behavior (any profile edit requires re-approval).
        $data['status'] = 'pending';
        $data['verified'] = false;

        $driver->update($data);

        return $driver->fresh();
    }

    public function uploadDocument(Request $request)
    {
        $driver = $request->user()->driver;
        if (! $driver) {
            abort(404, 'Not a driver account.');
        }

        $request->validate([
            'type' => ['required', 'in:driver_license,psv_sticker,national_id,passport_photo'],
            'file' => ['required', 'image', 'max:5120'],
        ]);

        $column = match ($request->input('type')) {
            'driver_license' => 'driver_license_url',
            'psv_sticker' => 'psv_insurance_sticker_url',
            'national_id' => 'national_id_url',
            'passport_photo' => 'passport_photo_url',
        };

        $path = $request->file('file')->store('driver-documents', 'public');
        $url = asset('storage/'.$path);

        $driver->update([$column => $url]);

        return response()->json([$column => $url]);
    }
}
