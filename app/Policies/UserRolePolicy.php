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
    public function viewAny(User $user): Response
    {
        $authRoles = Auth::user()->userDetail->roles ?? []; // already an array
        return array_intersect([1, 4, 5], $authRoles)
            ? Response::allow('Welcome, admin! You can view all users.')
            : Response::deny('Only administrators can view this section.');
    }

    public function updateUserDetails(User $user): Response
    {
        $authRoles = Auth::user()->userDetail->roles ?? []; // already an array
        return array_intersect([1, 4], $authRoles)
            ? Response::allow('Welcome, admin! You can view all users.')
            : Response::deny('Only administrators can view this section.');
    }
}
