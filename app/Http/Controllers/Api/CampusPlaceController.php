<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CampusPlace;
use Illuminate\Http\Request;

class CampusPlaceController extends Controller
{
    public function index(Request $request)
    {
        return CampusPlace::orderBy('name')->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'place_id' => ['nullable', 'string'],
            'source' => ['nullable', 'string'],
        ]);
        $data['added_by'] = $request->user()->id;

        return CampusPlace::create($data);
    }

    public function destroy(Request $request, CampusPlace $campusPlace)
    {
        $campusPlace->delete();

        return response()->noContent();
    }
}
