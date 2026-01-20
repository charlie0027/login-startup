<?php

namespace App\Http\Controllers\Archives;

use App\Http\Controllers\Controller;
use App\Models\Archives\AuditTrail;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class ArchiveController extends Controller
{
    public function index()
    {
        Gate::authorize('archives_archives', UserDetail::class);
        $deleted_users = User::onlyTrashed()->paginate(12);
        return Inertia::render('Archives/Archive', [
            'deleted_users' => $deleted_users
        ]);
    }

    public function restore(Request $request)
    {
        Gate::authorize('archives_archives_update', UserDetail::class);
        $user = User::withTrashed()
            ->where([
                'id' => $request->id,
                'username' => $request->username
            ])
            ->first(); // get the actual model

        if ($user) {
            // Capture "before" state
            $from = $user->replicate();

            // Perform restore
            $user->restore();

            // Capture "after" state
            $to = $user->fresh();

            AuditTrail::insertAuditTrail('Restore', 'Users', $user->id, $from, $to);
        }

        return redirect()->back()->with('success', 'User have now been restored, Check list of Users Table');
    }
}
