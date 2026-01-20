<?php

namespace App\Http\Controllers\Libraries;

use App\Http\Controllers\Controller;
use App\Models\Libraries\Permission;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class PermissionController extends Controller
{
    public function store(Request $request)
    {
        Gate::authorize('libraries_user_roles_create', UserDetail::class);
        $permission = Permission::create($request->validate([
            'name' => 'required|unique:permissions,name',
            'description' => 'required|min:5'
        ]));

        return redirect()->back()->with('success', 'Role successfully saved!');
    }

    public function update(Request $request)
    {
        Gate::authorize('libraries_user_roles_update', UserDetail::class);
        $editPermission = Permission::findOrFail($request->id);

        $request->validate([
            'name' => 'required|unique:permissions,name,' . $request->id,
            'description' => 'required|min:5'
        ]);

        $editPermission->fill([
            'name' => $request->name,
            'description' => $request->description,
        ]);

        if ($editPermission->isDirty()) {
            $editPermission->save();

            return redirect()->back()->with('success', 'Role successfully updated');
        } else {
            return redirect()->back()->with('error', 'Nothing was changed');
        }
    }

    public function destroy(Request $request)
    {
        Gate::authorize('libraries_user_roles_delete', UserDetail::class);
        $permission = Permission::findOrFail($request->id);

        $permission->delete();

        return redirect()->back()->with([
            'success' => 'Permission deleted successfully.',
        ]);
    }
}
