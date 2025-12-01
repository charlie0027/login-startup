<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Models\UserDetail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Helpers\Helper;

class RegistrationController extends Controller
{
    public function index()
    {
        return Inertia::render('Auth/Register');
    }

    public function register(Request $request)
    {
        // Registration
        $user = User::create(array_merge(
            $request->validate([
                'first_name' => 'required|string|min:3|max:255',
                'middle_name' => 'nullable|string|min:3|max:255',
                'last_name' => 'required|string|min:3|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'username' => 'required|string|max:255|unique:users',
                'password' => 'required|string|min:8|confirmed',
                'name_extension' => 'nullable|string|max:10',
            ]),
            [
                'created_by' => 0,
            ]
        ));

        //QR Code
        $qr_code = Helper::generateUniqueUserId();

        UserDetail::create([
            'user_id' => $user->id,
            'id_number' => $qr_code,
            'qr_code' => $qr_code,
            'created_by' => 0
        ]);

        //attempt to login and generate session
        if (Auth::attempt($request->only('username', 'password'))) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }
    }
}
