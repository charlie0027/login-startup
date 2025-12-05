<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Auth\RegistrationController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Libraries\UserController;
use App\Http\Controllers\Libraries\UserRoleController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Setting\SettingController;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

$middleware = ['login.message'];

if (config('auth.require_email_verification')) {
    $middleware[] = 'verified';
}

Route::middleware(['auth', 'login.message', 'email.verification.toggle'])->group(function () {

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
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('login.message');

// 1) Verification notice page (shown to logged-in, unverified users)
Route::get('/email/verify', function () {
    if (Auth::user() && Auth::user()->email_verified_at) {
        return redirect()->route('dashboard');
    }
    return inertia('Auth/VerifyEmail'); // Inertia page component
})->middleware('auth')->name('verification.notice');

// 2) Handle the email verification link (signed URL the user clicks from email)
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // sets email_verified_at

    return redirect()->route('dashboard')->with('message', 'Email verified!');
})->middleware(['auth', 'signed'])->name('verification.verify');

// 3) Resend verification email
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');


// for sampling only
// Route::get('/sample-email', function () {
//     return view('emails.reset_password', [
//         'url' => url('/reset-password/sample-token'),
//         'last_name' => 'Doe',
//         'first_name' => 'John',
//     ]);
// });
