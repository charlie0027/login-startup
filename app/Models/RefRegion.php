<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefRegion extends Model
{
    protected $guarded = [];

    public function province()
    {
        return $this->hasMany(RefProvince::class);
    }

    public function cityMun()
    {
        return $this->hasMany(RefCity::class);
    }

    public function brgy()
    {
        return $this->hasMany(RefBarangay::class);
    }
}
