<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefCity extends Model
{
    protected $guarded = [];

    public function region()
    {
        return $this->belongsTo(RefRegion::class);
    }

    public function province()
    {
        return $this->belongsTo(RefProvince::class);
    }

    public function brgy()
    {
        return $this->hasMany(RefBarangay::class);
    }
}
