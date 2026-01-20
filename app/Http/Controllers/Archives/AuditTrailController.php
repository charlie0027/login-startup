<?php

namespace App\Http\Controllers\Archives;

use App\Http\Controllers\Controller;
use App\Models\Archives\AuditTrail;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class AuditTrailController extends Controller
{
    public function index()
    {
        Gate::authorize('archives_audit_trails', UserDetail::class);
        $audit_trails = AuditTrail::with('user')->paginate(12);
        return Inertia::render('Archives/AuditTrails', [
            'audit_trails' => $audit_trails
        ]);
    }
}
