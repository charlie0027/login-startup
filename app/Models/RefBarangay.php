<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RefBarangay extends Model
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

    public function cityMun()
    {
        return $this->belongsTo(RefCity::class);
    }
}
