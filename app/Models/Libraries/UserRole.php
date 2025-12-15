<?php

namespace App\Models\Libraries;

use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class UserRole extends Model
{
    use SoftDeletes;
    use HasFactory, Notifiable;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(User::class);
    }

    public function userDetails()
    {
        return $this->belongsToMany(UserDetail::class, 'user_detail_role');
    }

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_role');
    }
}
