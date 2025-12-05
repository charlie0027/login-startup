<?php

namespace App\Http\Middleware;

use App\Models\SetEmailVerification as ModelsSetEmailVerification;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class SetEmailVerification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (ModelsSetEmailVerification::get('require_email_verification', true)) {
            if (Auth::check() && !Auth::user()->email_verified_at) {
                return redirect()->route('verification.notice');
            }
        }

        return $next($request);
    }
}
