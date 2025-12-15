<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class UserRolePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function view(User $user): Response
    {
        $detail = $user->userDetail;

        if (!$detail || $detail->roles->isEmpty()) {
            return Response::deny('Default users cannot view this section.');
        }

        return $detail->hasPermission('view')
            ? Response::allow('You can view.')
            : Response::deny('You cannot view.');
    }

    public function create(User $user): Response
    {
        $detail = $user->userDetail;

        if (!$detail || $detail->roles->isEmpty()) {
            return Response::deny('Default users cannot view this section.');
        }

        return $detail->hasPermission('create')
            ? Response::allow('You can create.')
            : Response::deny('You cannot create.');
    }

    public function update(User $user): Response
    {
        $detail = $user->userDetail;

        if (!$detail || $detail->roles->isEmpty()) {
            return Response::deny('Default users cannot view this section.');
        }

        return $detail->hasPermission('update')
            ? Response::allow('You can update.')
            : Response::deny('You cannot update.');
    }

    public function delete(User $user): Response
    {
        $detail = $user->userDetail;

        if (!$detail || $detail->roles->isEmpty()) {
            return Response::deny('Default users cannot view this section.');
        }

        return $detail->hasPermission('delete')
            ? Response::allow('You can delete.')
            : Response::deny('You cannot delete.');
    }

    public function export(User $user): Response
    {
        $detail = $user->userDetail;

        if (!$detail || $detail->roles->isEmpty()) {
            return Response::deny('Default users cannot view this section.');
        }

        return $detail->hasPermission('export')
            ? Response::allow('You can export.')
            : Response::deny('You cannot export.');
    }

    public function print(User $user): Response
    {
        $detail = $user->userDetail;

        if (!$detail || $detail->roles->isEmpty()) {
            return Response::deny('Default users cannot view this section.');
        }

        return $detail->hasPermission('print')
            ? Response::allow('You can print.')
            : Response::deny('You cannot print.');
    }

    public function view_sidenav(User $user): Response
    {
        $detail = $user->userDetail;

        if (!$detail || $detail->roles->isEmpty()) {
            return Response::deny('Default users cannot view this section.');
        }

        return $detail->hasPermission('view_sidenav')
            ? Response::allow('You can view.')
            : Response::deny('You cannot view.');
    }

    public function view_tab(User $user): Response
    {
        $detail = $user->userDetail;

        if (!$detail || $detail->roles->isEmpty()) {
            return Response::deny('Default users cannot view this section.');
        }

        return $detail->hasPermission('view_tab')
            ? Response::allow('You can view.')
            : Response::deny('You cannot view.');
    }
}
