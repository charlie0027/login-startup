<?php

namespace App\Http\Controllers;

use App\Models\Libraries\UserRole;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $gender_total = UserDetail::selectRaw("
                gender,
                COUNT(*) as total,
                CASE
                    WHEN gender = 0 THEN 'Female'
                    WHEN gender = 1 THEN 'Male'
                    ELSE 'Not set'
                END as gender_label
            ")
            ->groupBy('gender')
            ->get();

        $role_total = UserRole::withCount('userDetails')
            ->get()
            ->reject(function ($role) {
                return $role->user_details_count === 0;
            })
            ->map(function ($role) {
                return [
                    'role_label' => $role->role_name,
                    'total' => $role->user_details_count,
                ];
            })
            ->values();

        return Inertia::render('Dashboard/Dashboard', [
            'gender_total' => $gender_total,
            'role_total' => $role_total,
        ]);
    }
}
