<?php

namespace App\Http\Controllers\Setting;

use App\Http\Controllers\Controller;
use App\Models\SetEmailVerification;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SettingController extends Controller
{
    public function index()
    {
        return Inertia::render('Settings/Setting', [
            'require_email_verification' => (bool) SetEmailVerification::get('require_email_verification', true),
        ]);
    }

    public function updateEmailVerification(Request $request)
    {
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
}
