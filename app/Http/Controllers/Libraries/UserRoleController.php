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
        Gate::authorize('libraries_user_roles', UserDetail::class);

        $userRole = UserRole::with('permissions')
            ->when(request('searchInput'), function ($query, $searchInput) {
                $query->where('role_name', 'like', '%' . $searchInput . '%')
                    ->orWhere('role_code', 'like', '%' . $searchInput . '%');
            })
            ->paginate(12, ['*'], 'user_roles_page')
            // add this if you have tabs
            ->appends(['tab' => 'user_role'])
            ->withQueryString();

        $permissions = Permission::query()
            ->when(request('searchInputPermission'), function ($query, $searchInputPermission) {
                $query->where('name', 'like', '%' . $searchInputPermission . '%')
                    ->orWhere('description', 'like', '%' . $searchInputPermission . '%');
            })
            ->orderBy('description')
            ->paginate(12, ['*'], 'permissions_page')
            ->appends(['tab' => 'permissions'])
            ->withQueryString();

        // dd($permissions);

        $allPermissions = Permission::orderBy("description")->get();

        return Inertia::render('Libraries/UserRole', [
            'user_roles' => $userRole,
            'permissions' => $permissions,
            'filters' => request('searchInput'),
            'filters_permission' => request('searchInputPermission'),
            'allPermissions' => $allPermissions
        ]);
    }

    public function store(Request $request)
    {
        // Will throw AuthorizationException if denied
        Gate::authorize('libraries_user_roles_create', UserDetail::class);
        $validated = $request->validate([
            'description' => 'nullable',
            'role_code' => 'unique:user_roles',
            'role_name' => 'unique:user_roles',
            'permissions' => 'array',
        ]);

        $createRole = UserRole::create([
            'description' => $validated['description'],
            'role_code' => $validated['role_code'],
            'role_name' => $validated['role_name'],
            'is_default' => 0,
            'status' => 1,
            'created_by' => Auth::id(),
        ]);

        $createRole->permissions()->sync($validated['permissions']);

        return redirect()->back()->with('success', 'Role successfully saved!');
    }

    public function update(Request $request)
    {
        // Will throw AuthorizationException if denied
        Gate::authorize('libraries_user_roles_update', UserDetail::class);
        // dd($request->permissions);
        // Find associated Id
        $user_role = UserRole::findOrFail($request->id);

        // Validate
        $validated = $request->validate([
            'description' => 'nullable',
            'role_code' => 'unique:user_roles,role_code,' . $request->id,
            'role_name' => 'unique:user_roles,role_name,' . $request->id,
            'permissions' => 'array',
        ]);

        $originalPermissions = $user_role->permissions->pluck('id')->toArray();
        $user_role->permissions()->sync($validated['permissions']);
        $newPermissions = $validated['permissions'];

        $permissionsChanged = count(array_diff($originalPermissions, $newPermissions)) > 0 ||
            count(array_diff($newPermissions, $originalPermissions)) > 0;


        // Fill
        $user_role->fill([
            'description' => $request->description,
            'role_code' => $request->role_code,
            'role_name' => $request->role_name,
            'is_default' => $request->is_default,
            'status' => $request->status,
        ]);

        // Check if changes, save or prompt errors
        if ($user_role->isDirty() || $permissionsChanged) {
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
        Gate::authorize('libraries_user_roles_delete', UserDetail::class);
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
