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
}
