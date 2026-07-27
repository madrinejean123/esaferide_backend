<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Favourite;
use Illuminate\Http\Request;

class FavouriteController extends Controller
{
    public function index(Request $request)
    {
        return $request->user()->favourites()->latest()->get();
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'lat' => ['required', 'numeric'],
            'lng' => ['required', 'numeric'],
            'place_id' => ['nullable', 'string'],
        ]);

        return $request->user()->favourites()->create($data);
    }

    public function destroy(Request $request, Favourite $favourite)
    {
        if ($favourite->user_id !== $request->user()->id) {
            abort(403);
        }

        $favourite->delete();

        return response()->noContent();
    }
}
