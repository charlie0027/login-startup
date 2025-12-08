<?php

namespace App\Http\Controllers\Libraries;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helper;
use App\Models\Archives\AuditTrail;
use App\Models\Libraries\UserRole;
use App\Models\UserDetail;
use App\Models\RefBarangay;
use App\Models\RefCity;
use App\Models\RefProvince;
use Illuminate\Support\Facades\Gate;

class UserController extends Controller
{
    public function index()
    {
        // Will throw AuthorizationException if denied
        Gate::authorize('view', UserDetail::class);

        $users = User::with('userDetail')
            ->when(request('searchInput'), function ($query, $searchInput) {
                $query->where('username', 'like', '%' . $searchInput . '%')
                    ->orWhere('first_name', 'like', '%' . $searchInput . '%')
                    ->orWhere('last_name', 'like', '%' . $searchInput . '%')
                    ->orWhere('middle_name', 'like', '%' . $searchInput . '%');
            })
            ->paginate(12)
            ->withQueryString();

        // $barangays = RefBarangay::orderBy('brgyDesc')->get();
        $citymuns = RefCity::orderBy('citymunDesc')->get();
        $provinces = RefProvince::orderBy('provDesc')->get();
        $roles = UserRole::where('status', 1)->get();

        return inertia('Libraries/Users', [
            'users' => $users,
            'filters' => request('searchInput'),
            'citymuns' => $citymuns,
            'provinces' => $provinces,
            'roles' => $roles
        ]);
    }

    public function show() {}

    public function store(Request $request)
    {
        Gate::authorize('create', UserDetail::class);
        $userCreate =  User::create(array_merge(
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
                'created_by' => Auth::id(),
            ]
        ));

        //QR Code
        $qr_code = Helper::generateUniqueUserId();;

        UserDetail::create([
            'user_id' => $userCreate->id,
            'id_number' => $qr_code,
            'qr_code' => $qr_code,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('libraries.user_index')->with([
            'success' => 'User created successfully.',
        ]);
    }

    public function update(Request $request)
    {
        Gate::authorize('update', UserDetail::class);
        $user = User::with('userDetail')->findOrFail($request->id);
        $user_detail = $user->userDetail;

        $request->validate([
            'first_name' => 'required|string|min:3|max:255',
            'middle_name' => 'nullable|string|min:3|max:255',
            'last_name' => 'required|string|min:3|max:255',
            // 'email' => 'required|string|email|max:255|unique:users',
            // 'username' => 'required|string|max:255|unique:users',
            'name_extension' => 'nullable|string|max:10',
            'user_detail.contact' => 'nullable',
            'user_detail.birthdate' => 'required|date',
            'user_detail.gender' => 'required|in:0,1',
            'user_detail.house_number' => 'nullable|string',
            'user_detail.street' => 'nullable|string',
            'user_detail.barangay' => 'nullable|string',
            'user_detail.citymun' => 'nullable|string',
            'user_detail.province' => 'nullable|string',
            'user_detail.id_number' => 'required|max:255|unique:user_details,id_number,' . ($user_detail->id ?? 'null'),
        ], [
            'user_detail.birthdate.required' => 'Please provide birthdate.',
            'user_detail.birthdate.date' => 'The birthdate must be a valid date.',
            'user_detail.gender.required' => 'Please provide  gender.',
            'user_detail.id_number.required' => 'Please provide ID number.',
        ]);

        $user->fill([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
            'name_extension' => $request->name_extension,
            'username' => $request->username
        ]);

        $check_user_detail_exist = UserDetail::where('user_id', $request->id)->get();

        if ($check_user_detail_exist->count() >= 1) {
            $user->userDetail->fill($request->input('user_detail'));
        } else {
            $user_detail = UserDetail::create([
                'contact'      => $request->user_detail['contact'],
                'birthdate'    => $request->user_detail['birthdate'],
                'gender'       => $request->user_detail['gender'],
                'house_number' => $request->user_detail['house_number'],
                'street'       => $request->user_detail['street'],
                'barangay'     => $request->user_detail['barangay'],
                'citymun'      => $request->user_detail['citymun'],
                'province'     => $request->user_detail['province'],
                'user_id' => $request->id,
                'id_number'  => $request->user_detail['id_number'],
                'created_by' => $request->id,
            ]);
        }


        // Check if anything changed
        if ($user_detail->wasRecentlyCreated) {

            $user->updated_by = Auth::id();
            $user->save();

            // Audit trail for creation
            AuditTrail::insertAuditTrail(
                'create',
                'User with details',
                $user_detail->id,
                [], // no "from" values
                $user_detail->getAttributes()
            );

            return redirect()->back()->with([
                'success' => 'User updated successfully.',
            ]);
        } elseif (!$user->isDirty() && !$user_detail->isDirty()) {
            return redirect()->back()->with([
                'error' => 'No changes were made to the user.'
            ]);
        } else {

            AuditTrail::insertAuditTrail('update', 'User with details', $user_detail->id, $user_detail, $user_detail);

            // Save parent first
            $user->updated_by = Auth::id();
            $user->save();

            $user_detail->updated_by = Auth::id();
            $user_detail->save();

            return redirect()->back()->with([
                'success' => 'User updated successfully.',
            ]);
        }
    }

    public function destroy(Request $request)
    {
        Gate::authorize('delete', UserDetail::class);
        $user = User::findOrFail($request->id);
        $user->updated_by = Auth::id();
        $user->save();
        $user->delete();

        return redirect()->back()->with([
            'success' => 'User deleted successfully.',
        ]);
    }

    public function update_pw(Request $request)
    {
        $user = User::find($request->id);

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->fill([
            'password' => $request->password,
        ]);

        // Check if anything changed
        if ($user->isDirty()) {
            $user->updated_by = Auth::id();
            $user->save();
            return redirect()->route('libraries.user_index')->with([
                'success' => 'Password updated successfully.',
            ]);
        } else {
            return redirect()->back()->with([
                'error' => 'No changes were made to the user.'
            ]);
        }
    }

    public function update_roles(Request $request)
    {
        // dd($request->all());
        $user_detail = UserDetail::where('user_id', $request->id)->first();

        if (!$user_detail) {
            // Either create a new record or return an error
            $user_detail = new UserDetail();
            $user_detail->user_id = $request->id;
            $user_detail->created_by = Auth::id();
            $user_detail->id_number = Helper::generateUniqueUserId();
        }

        $roles = $request->roles ? $request->roles : null;

        $user_detail->fill([
            // 'roles' => $roles,
            'roles' => $roles,
            'updated_by' => Auth::id(),
        ]);

        if ($user_detail->isDirty()) {
            $user_detail->save();
            return redirect()->back()->with([
                'success' => 'Roles updated successfully.',
            ]);
        } else {
            return redirect()->back()->with([
                'error' => 'No changes were made to the user.'
            ]);
        }
    }
}
