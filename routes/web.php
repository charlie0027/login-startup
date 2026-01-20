<?php

use App\Http\Controllers\Archives\ArchiveController;
use App\Http\Controllers\Archives\AuditTrailController;
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
use App\Http\Controllers\Libraries\PermissionController;
use App\Http\Controllers\Reports\UserReportController;
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
    Route::put('/libraries/user_roles/{id}/update', [UserRoleController::class, 'update'])->name('libraries.user_role_update');
    Route::put('/libraries/user_roles/{id}/deactivate', [UserRoleController::class, 'deactivate'])->name('libraries.user_role_deactivate');

    //Libraries/Permission (Inside User Roles Sidebar)
    Route::post('/libraries/permission', [PermissionController::class, 'store'])->name('libraries.permission_store');
    Route::put('/libraries/permission/{id}/update', [PermissionController::class, 'update'])->name('libraries.permission_update');
    Route::delete('/libraries/permission/{id}/destroy', [PermissionController::class, 'destroy'])->name('permission_destroy');

    //Settings/Settings
    Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
    Route::post('/settings/email-verification', [SettingController::class, 'updateEmailVerification'])->name('settings.updateEmailVerification');
    Route::post('/settings/two-factor-auth', [SettingController::class, 'updateTwoFactorAuth'])->name('settings.updateTwoFactorAuth');

    //Archives/Audit Trails
    Route::get('/archives/audit_trails', [AuditTrailController::class, 'index'])->name('archives.audit_trails.index');

    // Archives/Archives
    Route::get('/archives/archives', [ArchiveController::class, 'index'])->name('archives.archives.index');
    Route::put('/archives/archives', [ArchiveController::class, 'restore'])->name('archives.archives.restore');

    // Reports/UserSummary
    Route::get('/reports/user_reports', [UserReportController::class, 'index'])->name('reports.user_reports.index');
    Route::get('/reports/export_user_summary_excel', [UserReportController::class, 'export_user_summary'])->name('reports.export_user_summary');
    Route::get('/reports/export_user_summary_pdf', [UserReportController::class, 'export_user_summary_pdf'])->name('reports.export_user_summary_pdf');
    Route::get('/reports/export_user_summary_word', [UserReportController::class, 'export_user_summary_word'])->name('reports.export_user_summary_word');
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
