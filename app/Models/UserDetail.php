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

    // protected $casts = [
    //     'roles' => 'array'
    // ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userRole()
    {
        return $this->belongsTo(UserRole::class);
    }

    public function roles()
    {
        return $this->belongsToMany(UserRole::class, 'user_detail_role');
    }

    public function permissions()
    {
        $array = $this->roles()->with('permissions')->get()
            ->pluck('permissions')
            ->flatten()
            ->pluck('name') // or whatever column holds the permission slug
            ->unique()
            ->values();

        // dd($array);
        return $array;
    }

    // Convenience method
    public function hasPermission(string $permission): bool
    {
        // dd($permission);
        return $this->permissions()->contains($permission);
    }
}
