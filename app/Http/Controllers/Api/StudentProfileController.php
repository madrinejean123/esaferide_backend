<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentProfileController extends Controller
{
    public function show(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(404, 'Not a student account.');
        }

        return $student;
    }

    public function update(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(404, 'Not a student account.');
        }

        $data = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'reg_number' => ['nullable', 'string', 'max:255'],
            'course' => ['nullable', 'string', 'max:255'],
            'year' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:255'],
            'emergency_contact_name' => ['nullable', 'string', 'max:255'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:255'],
            'accessibility_wheelchair' => ['sometimes', 'boolean'],
            'accessibility_visual' => ['sometimes', 'boolean'],
            'accessibility_hearing' => ['sometimes', 'boolean'],
            'accessibility_assistance' => ['sometimes', 'boolean'],
        ]);

        $student->update($data);

        return $student->fresh();
    }

    public function uploadPhoto(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(404, 'Not a student account.');
        }

        $request->validate([
            'photo' => ['required', 'image', 'max:5120'],
        ]);

        $path = $request->file('photo')->store('student-photos', 'public');
        $url = asset('storage/'.$path);

        $student->update(['photo_url' => $url]);

        return response()->json(['photo_url' => $url]);
    }
}
