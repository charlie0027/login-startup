<?php

namespace App\Http\Controllers;

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

        $role_total = DB::table('user_details')
            ->join(DB::raw("JSON_TABLE(user_details.roles, '$[*]' COLUMNS(role_id INT PATH '$')) as roles"), function ($join) {})
            ->join('user_roles', 'user_roles.id', '=', 'roles.role_id')
            ->select('user_roles.role_name as role_label', DB::raw('COUNT(*) as total'))
            ->groupBy('user_roles.role_name')
            ->get();

        return Inertia::render('Dashboard/Dashboard', [
            'gender_total' => $gender_total,
            'role_total' => $role_total,
        ]);
    }
}
