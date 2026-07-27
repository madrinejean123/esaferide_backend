<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Driver;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'role' => ['required', 'string', 'in:student,driver'],
        ]);

        $user = User::create($data);

        if ($user->role === 'driver') {
            Driver::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
                'status' => 'pending',
                'verified' => false,
            ]);
        } elseif ($user->role === 'student') {
            Student::create([
                'user_id' => $user->id,
                'full_name' => $user->name,
            ]);
        }

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('mobile')->plainTextToken,
        ], 201);
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $user = User::where('email', $credentials['email'])->firstOrFail();
        $user->load('driver');

        return response()->json([
            'user' => $user,
            'token' => $user->createToken('mobile')->plainTextToken,
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out']);
    }

    // Links this user's Laravel account to their Firebase Auth UID, called
    // right after the client establishes (or lazily creates) the parallel
    // Firebase session. Lets server-side code (e.g. trip creation) resolve
    // a Firebase UID from Firestore-backed data back to a Laravel user.
    public function linkFirebase(Request $request)
    {
        $data = $request->validate([
            'firebase_uid' => ['required', 'string'],
        ]);

        $request->user()->update(['firebase_uid' => $data['firebase_uid']]);

        return response()->json(['message' => 'Linked']);
    }
}
