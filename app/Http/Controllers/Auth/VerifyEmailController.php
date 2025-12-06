<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerifyEmailController extends Controller
{
    // 1) Verification notice page (shown to logged-in, unverified users)
    public function verification_notice()
    {
        if (Auth::user() && Auth::user()->email_verified_at) {
            return redirect()->route('dashboard');
        }
        return inertia('Auth/VerifyEmail'); // Inertia page component
    }

    // 2) Handle the email verification link (signed URL the user clicks from email)
    public function handle_link(EmailVerificationRequest $request)
    {
        $request->fulfill(); // sets email_verified_at

        return redirect()->route('dashboard')->with('message', 'Email verified!');
    }

    // 3) Resend verification email
    public function resend_verification_email(Request $request)
    {
        $request->user()->sendEmailVerificationNotification();
        return back()->with('message', 'Verification link sent!');
    }
}
