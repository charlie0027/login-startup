<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefProvince extends Model
{
    protected $guarded = [];

    public function region()
    {
        return $this->belongsTo(RefRegion::class);
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
