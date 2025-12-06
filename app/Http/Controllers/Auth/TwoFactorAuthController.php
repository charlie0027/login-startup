<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Notifications\SendTwoFactorCodeNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;

class TwoFactorAuthController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();
        $code = rand(100000, 999999);
        $user->two_factor_code = $code;
        $user->save();

        $user->notify(new SendTwoFactorCodeNotification($code));

        // Mail::raw("Your two-factor code is $code", function ($message) use ($user) {
        //     $message->to($user->email)->subject('Two Factor Code');
        // });

        return Inertia::render('Auth/TwoFactorAuth');
    }

    public function verify(Request $request)
    {
        // dd($request->all());
        $request->validate([
            'code' => 'required|integer'
        ]);
        $user = Auth::user();

        if ($request->code == $user->two_factor_code) {
            session(['two_factor_authenticated' => true]);
            return redirect()->intended('/dashboard');
        }

        return redirect()->back()->with(['error'  => 'The provided code is incorrrect']);
    }
}
