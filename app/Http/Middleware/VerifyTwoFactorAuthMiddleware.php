<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\SetEmailVerification as ModelsSetEmailVerification;

class VerifyTwoFactorAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (ModelsSetEmailVerification::get('require_two_factor_auth', true)) {
            if (Auth::check() && !session('two_factor_authenticated')) {
                return redirect()->route('two-factor.index');
            }
        }
        return $next($request);
    }
}
