<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        return AuditLog::with(['admin:id,name', 'targetUser:id,name'])
            ->latest()
            ->limit(200)
            ->get();
    }
}
