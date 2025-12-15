<?php

namespace App\Models\Libraries;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;

class Permission extends Model
{
    use SoftDeletes;
    use HasFactory, Notifiable;

    protected $guarded = [];

    public function roles()
    {
        return $this->belongsToMany(UserRole::class, 'permission_role');
    }
}
