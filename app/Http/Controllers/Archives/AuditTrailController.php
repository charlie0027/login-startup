<?php

namespace App\Http\Controllers\Archives;

use App\Http\Controllers\Controller;
use App\Models\Archives\AuditTrail;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AuditTrailController extends Controller
{
    public function index()
    {
        $audit_trails = AuditTrail::with('user')->paginate(12);
        return Inertia::render('Archives/AuditTrails', [
            'audit_trails' => $audit_trails
        ]);
    }
}
