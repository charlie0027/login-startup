<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\SetEmailVerification;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        // Will throw AuthorizationException if denied
        Gate::authorize('settings_setting', UserDetail::class);
        return Inertia::render('Settings/Setting', [
            'require_email_verification' => (bool) SetEmailVerification::get('require_email_verification', true),
            'require_two_factor_auth' => (bool) SetEmailVerification::get('require_two_factor_auth', true),
        ]);
    }

    public function updateEmailVerification(Request $request)
    {
        // Will throw AuthorizationException if denied
        Gate::authorize('settings_setting_update', UserDetail::class);
        // dd($request->all());
        if ($request->requireEmailVerification == true) {
            $value = 1;
        } else {
            $value = 0;
        }
        $setting = SetEmailVerification::where('key', 'require_email_verification')->first();
        $setting->update(['value' => $value]);

        return back()->with('success', 'Email verification setting updated!');
    }

    public function updateTwoFactorAuth(Request $request)
    {
        // Will throw AuthorizationException if denied
        Gate::authorize('settings_setting_update', UserDetail::class);
        if ($request->requireTwoFactorAuth == true) {
            $value = 1;
        } else {
            $value = 0;
        }
        $setting = SetEmailVerification::where('key', 'require_two_factor_auth')->first();
        $setting->update(['value' => $value]);

        return back()->with('success', 'Two Factor Authentication setting updated!');
    }
}
