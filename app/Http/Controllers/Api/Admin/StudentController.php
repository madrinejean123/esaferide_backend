<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        return Student::with('user:id,name,email')->latest()->get();
    }

    public function suspend(Request $request, Student $student)
    {
        $data = $request->validate(['reason' => ['nullable', 'string']]);

        $student->update([
            'suspended' => true,
            'suspended_at' => now(),
            'suspended_by' => $request->user()->id,
            'suspended_reason' => $data['reason'] ?? null,
        ]);

        AuditLog::record('suspend_student', 'student', $student->user_id, $request->user()->id, $data['reason'] ?? null);

        return $student->fresh('user:id,name,email');
    }

    public function unsuspend(Request $request, Student $student)
    {
        $student->update([
            'suspended' => false,
            'unsuspended_at' => now(),
            'unsuspended_by' => $request->user()->id,
        ]);

        AuditLog::record('unsuspend_student', 'student', $student->user_id, $request->user()->id);

        return $student->fresh('user:id,name,email');
    }
}
