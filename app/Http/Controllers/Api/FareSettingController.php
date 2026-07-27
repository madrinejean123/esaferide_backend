<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FareSetting;
use Illuminate\Http\Request;

class FareSettingController extends Controller
{
    public function show(Request $request)
    {
        return FareSetting::firstOrCreate([], [
            'minimum_fare' => 1000,
            'base_fare' => 1000,
            'per_km_rate' => 600,
        ]);
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'minimum_fare' => ['required', 'numeric', 'min:0'],
            'base_fare' => ['required', 'numeric', 'min:0'],
            'per_km_rate' => ['required', 'numeric', 'min:0'],
        ]);

        $settings = FareSetting::first();
        if ($settings) {
            $settings->update($data);
        } else {
            $settings = FareSetting::create($data);
        }

        return $settings;
    }
}
