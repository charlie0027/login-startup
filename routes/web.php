<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Libraries\UserController;
use App\Http\Controllers\Libraries\UserRoleController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/login');

Route::middleware('user.redirect')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [RegistrationController::class, 'index']);
    Route::post('/register', [RegistrationController::class, 'register']);
});

Route::middleware('login.message')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    // Libraries/Users
    Route::get('/libraries/users', [UserController::class, 'index'])->name('libraries.user_index');
    Route::post('/libraries/users', [UserController::class, 'store'])->name('libraries.user_store');
    Route::put('/libraries/users/{id}', [UserController::class, 'update'])->name('libraries.user_update');
    Route::delete('/libraries/users/{id}', [UserController::class, 'destroy'])->name('libraries.destroy');
    Route::put('/libraries/users/{id}/update_pw', [UserController::class, 'update_pw'])->name('libraries.user_update_pw');
    Route::put('/libraries/users/{id}/update_roles', [UserController::class, 'update_roles'])->name('libraries.user_update_roles');

    // My Profile
    Route::get('/myprofile/{id}', [ProfileController::class, 'index'])->name('profile.index');
    Route::put('/myprofile/{id}', [ProfileController::class, 'update'])->name('profile.update');

    // Libraries/Roles
    Route::get('/libraries/user_roles', [UserRoleController::class, 'index'])->name('libraries.user_role_index');
    Route::post('/libraries/user_roles', [UserRoleController::class, 'store'])->name('libraries.user_role_store');
    Route::put('/libraries/user_roles/{id}', [UserRoleController::class, 'update'])->name('libraries.user_role_update');
    Route::put('/libraries/user_roles/{id}', [UserRoleController::class, 'deactivate'])->name('libraries.user_role_deactivate');
});


Route::get('/api/barangays/{citymunCode}', function ($citymunCode) {
    return \App\Models\RefBarangay::where('citymunCode', $citymunCode)
        ->orderBy('brgyDesc')
        ->get();
});
