<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Libraries\UserController;
use App\Http\Controllers\Libraries\UserRoleController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\TwoFactorAuthController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Setting\SettingController;
use Illuminate\Support\Facades\Route;
// use Illuminate\Http\Request;

Route::redirect('/', '/login');

Route::middleware('user.redirect')->group(function () {
    Route::get('/login', [AuthController::class, 'index'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::get('/register', [RegistrationController::class, 'index']);
    Route::post('/register', [RegistrationController::class, 'register']);

    // Forgot password: show + submit
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');

    // Reset password: show + submit
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.update');
});

Route::middleware(['auth', 'login.message', 'email.verification.toggle', 'two.factor.auth'])->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

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

    //Settings/Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/email-verification', [SettingController::class, 'updateEmailVerification'])->name('settings.updateEmailVerification');
    Route::post('/settings/two-factor-auth', [SettingController::class, 'updateTwoFactorAuth'])->name('settings.updateTwoFactorAuth');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('login.message');

// Verify Email
Route::get('/email/verify', [VerifyEmailController::class, 'verification_notice'])->middleware('auth')->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [VerifyEmailController::class, 'handle_link'])->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', [VerifyEmailController::class, 'resend_verification_email'])->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Two Factor Authentication
Route::get('/two-factor', [TwoFactorAuthController::class, 'index'])->middleware('auth')->name('two-factor.index');
Route::post('/two-factor', [TwoFactorAuthController::class, 'verify'])->middleware('auth')->name('two-factor.verify');


// for sampling only
// Route::get('/sample-email', function () {
//     return view('emails.reset_password', [
//         'url' => url('/reset-password/sample-token'),
//         'last_name' => 'Doe',
//         'first_name' => 'John',
//     ]);
// });
