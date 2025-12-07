<?php

namespace App\Http\Controllers\Libraries;

use App\Http\Controllers\Controller;
use App\Models\Libraries\Permission;
use App\Models\Libraries\UserRole;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class UserRoleController extends Controller
{
    public function index()
    {
        // Will throw AuthorizationException if denied
        Gate::authorize('view', UserDetail::class);

        $userRole = UserRole::query()
            ->when(request('searchInput'), function ($query, $searchInput) {
                $query->where('role_name', 'like', '%' . $searchInput . '%')
                    ->orWhere('role_code', 'like', '%' . $searchInput . '%');
            })
            ->paginate(12)
            ->withQueryString();

        $permissions = Permission::query()
            ->when(request('searchInputPermission'), function ($query, $searchInputPermission) {
                $query->where('name', 'like', '%' . $searchInputPermission . '%')
                    ->orWhere('description', 'like', '%' . $searchInputPermission . '%');
            })
            ->paginate(12)
            ->withQueryString();

        // dd($permissions);

        return Inertia::render('Libraries/UserRole', [
            'user_roles' => $userRole,
            'permissions' => $permissions,
            'filters' => request('searchInput'),
            'filters_permission' => request('searchInputPermission'),
        ]);
    }

    public function store(Request $request)
    {
        // Will throw AuthorizationException if denied
        Gate::authorize('create', UserDetail::class);
        $createRole = UserRole::create(array_merge(
            $request->validate([
                'description' => 'nullable',
                'role_code' => 'unique:user_roles',
                'role_name' => 'unique:user_roles',
                'permissions' => 'array',
            ]),
            [
                'is_default' => 0,
                'status' => 1,
                'created_by' => Auth::id(),
            ]
        ));

        return redirect()->back()->with('success', 'Role successfully saved!');
    }

    public function update(Request $request)
    {
        // Will throw AuthorizationException if denied
        Gate::authorize('update', UserDetail::class);
        // dd($request->permissions);
        // Find associated Id
        $user_role = UserRole::findOrFail($request->id);

        // Validate
        $request->validate([
            'description' => 'nullable',
            'role_code' => 'unique:user_roles,role_code,' . $request->id,
            'role_name' => 'unique:user_roles,role_name,' . $request->id,
            'permissions' => 'array',
        ]);

        // Fill
        $user_role->fill([
            'description' => $request->description,
            'role_code' => $request->role_code,
            'role_name' => $request->role_name,
            'permissions' => $request->permissions,
            'is_default' => $request->is_default,
            'status' => $request->status,
        ]);

        // Check if changes, save or prompt errors
        if ($user_role->isDirty()) {
            $user_role->updated_by = Auth::id();
            $user_role->save();

            return redirect()->back()->with('success', 'Role successfully updated');
        } else {
            return redirect()->back()->with('error', 'Nothing was changed');
        }
    }

    public function deactivate(Request $request)
    {
        // Will throw AuthorizationException if denied
        Gate::authorize('delete', UserDetail::class);
        $user_role = UserRole::findOrFail($request->id);
        $user_role->updated_by = Auth::id();
        if ($request->status == 1) {
            $user_role->status = 0;
            $user_role->save();
            return redirect()->back()->with([
                'success' => 'User deactivated successfully.',
            ]);
        } else {
            $user_role->status = 1;
            $user_role->save();
            return redirect()->back()->with([
                'success' => 'User activated successfully.',
            ]);
        }
    }
}
