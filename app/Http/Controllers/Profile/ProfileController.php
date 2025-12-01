<?php

namespace App\Http\Controllers\Profile;

use App\Http\Controllers\Controller;
use App\Models\RefBarangay;
use App\Models\RefCity;
use App\Models\RefProvince;
use App\Models\User;
use App\Models\UserDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ProfileController extends Controller
{
    public function index()
    {
        $users = User::with('userDetail')
            ->where('id', Auth::id())
            ->first();

        $citymuns = RefCity::orderBy('citymunDesc')->get();
        $provinces = RefProvince::orderBy('provDesc')->get();
        return Inertia::render('Profile/MyProfile', [
            'users' => $users,
            'citymuns' => $citymuns,
            'provinces' => $provinces,
            'appUrl' => config('app.url'),
        ]);
    }

    public function update(Request $request)
    {
        $user = User::with('userDetail')->findOrFail($request->id);
        $user_detail = $user->userDetail;

        $request->validate([
            'first_name' => 'required|string|min:3|max:255',
            'middle_name' => 'nullable|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'username' => 'required|string|max:255|unique:users,username,' . $user->id,
            'name_extension' => 'nullable|string|max:10',
            'user_detail.contact' => 'nullable',
            'user_detail.birthdate' => 'required|date',
            'user_detail.gender' => 'required|in:0,1',
            'user_detail.house_number' => 'nullable|string',
            'user_detail.street' => 'nullable|string',
            'user_detail.barangay' => 'nullable|string',
            'user_detail.citymun' => 'nullable|string',
            'user_detail.province' => 'nullable|string',
        ]);

        $user->fill([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'name_extension' => $request->name_extension,
            'username' => $request->username
        ]);

        $user->userDetail->fill($request->input('user_detail'));

        // Check if anything changed
        if (!$user->isDirty() && !$user_detail->isDirty()) {
            return redirect()->back()->with([
                'error' => 'No changes were made to the user.'
            ]);
        } else {

            $user->updated_by = Auth::id();
            $user->save();

            $user_detail->updated_by = Auth::id();
            $user_detail->save();

            return redirect()->back()->with([
                'success' => 'User updated successfully.',
            ]);
        }
    }
}
