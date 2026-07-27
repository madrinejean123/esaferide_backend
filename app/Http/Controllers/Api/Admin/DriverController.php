<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\Driver;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    public function index(Request $request)
    {
        $query = Driver::with('user:id,name,email');

        match ($request->query('filter', 'pending')) {
            'pending' => $query->where('status', 'pending'),
            'approved' => $query->where('verified', true)->where('suspended', false),
            'suspended' => $query->where('suspended', true),
            default => null,
        };

        return $query->latest()->get();
    }

    public function verify(Request $request, Driver $driver)
    {
        $driver->update([
            'verified' => true,
            'verified_at' => now(),
            'verified_by' => $request->user()->id,
            'status' => 'active',
        ]);

        AuditLog::record('verify_driver', 'driver', $driver->user_id, $request->user()->id);

        return $driver->fresh('user:id,name,email');
    }

    public function reject(Request $request, Driver $driver)
    {
        $data = $request->validate(['reason' => ['nullable', 'string']]);

        $driver->update([
            'verified' => false,
            'status' => 'rejected',
            'rejected_at' => now(),
            'rejected_by' => $request->user()->id,
            'rejection_reason' => $data['reason'] ?? null,
        ]);

        AuditLog::record('reject_driver', 'driver', $driver->user_id, $request->user()->id, $data['reason'] ?? null);

        return $driver->fresh('user:id,name,email');
    }

    public function suspend(Request $request, Driver $driver)
    {
        $data = $request->validate(['reason' => ['nullable', 'string']]);

        $driver->update([
            'suspended' => true,
            'suspended_at' => now(),
            'suspended_by' => $request->user()->id,
            'suspended_reason' => $data['reason'] ?? null,
        ]);

        AuditLog::record('suspend_driver', 'driver', $driver->user_id, $request->user()->id, $data['reason'] ?? null);

        return $driver->fresh('user:id,name,email');
    }

    public function unsuspend(Request $request, Driver $driver)
    {
        $driver->update([
            'suspended' => false,
            'unsuspended_at' => now(),
            'unsuspended_by' => $request->user()->id,
        ]);

        AuditLog::record('unsuspend_driver', 'driver', $driver->user_id, $request->user()->id);

        return $driver->fresh('user:id,name,email');
    }
}
