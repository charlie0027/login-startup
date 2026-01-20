<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;
use Illuminate\Support\Facades\Auth;

class UserRolePolicy
{
    public function __call($method, $arguments)
    {
        $user = $arguments[0]; // first argument is always User
        $detail = $user->userDetail;
        if (!$detail || $detail->roles->isEmpty()) {
            return Response::deny('Default users cannot access this.');
        }
        return $detail->hasPermission($method) ? Response::allow("You can {$method}.") : Response::deny("Access Denied. Please contact your Administrator");
    }
}
