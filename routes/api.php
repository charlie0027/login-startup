<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\RefBarangay;

Route::get('/barangays/{citymunCode}', function ($citymunCode) {
    return RefBarangay::where('citymunCode', $citymunCode)
        ->orderBy('brgyDesc')
        ->get();
});
