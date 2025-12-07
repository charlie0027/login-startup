<?php

namespace App\Models;

use App\Models\Libraries\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserDetail extends Model
{
    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [];

    protected $casts = [
        'roles' => 'array'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userRole()
    {
        return $this->belongsTo(UserRole::class);
    }

    // Fetch roles by IDs stored in user_details.roles
    public function rolesModels()
    {
        return UserRole::whereIn('id', $this->roles ?? [])->get();
    }

    // Union of permissions from all assigned roles
    public function effectivePermissions(): array
    {
        $permissions = [];
        foreach ($this->rolesModels() as $role) {
            $rolePerms = is_array($role->permissions)
                ? $role->permissions
                : json_decode($role->permissions ?? '[]', true);

            if (is_array($rolePerms)) {
                $permissions = array_merge($permissions, $rolePerms);
            }
        }
        return array_values(array_unique($permissions));
    }

    // Optional convenience method
    public function hasPermission(string $permission): bool
    {
        return in_array($permission, $this->effectivePermissions(), true);
    }

    // For ability:resource style (e.g., view:user_roles)
    public function hasAbility(string $ability, string $resource): bool
    {
        return $this->hasPermission("$ability:$resource");
    }
}
