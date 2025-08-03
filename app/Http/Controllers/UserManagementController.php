<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Auth;
use Session;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Trainees;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use App\Rules\MatchOldPassword;


class UserManagementController extends Controller
{
    public function index()
    {
        $users = User::with('trainee')
            ->join('user_roles', 'users.id', '=', 'user_roles.user_id')
            ->where('user_roles.is_active', 1)
            ->select('users.*')
            ->get();

        return view('usermanagement.list_users', compact('users'));
    }



    public function createUser()
    {
        return view('usermanagement.add_user'); // adjust the view path as needed
    }

    public function storeUser(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'status' => 'required|string',
            'role_name' => 'required|string',
            'avatar' => 'nullable|image',
        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;
        $user->status = $request->status;
        $user->role_name = $request->role_name;
        $user->position = $request->position;
        $user->department = $request->department;

        // ✅ Set join_date automatically
        $user->join_date = Carbon::now()->toDayDateTimeString();

        $user->password = bcrypt($request->password);

        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('images'), $filename);
            $user->avatar = $filename;
        }

        $user->save();

        \App\Models\UserRole::create([
            'user_id' => $user->id,
            'role_id' => 1,
        ]);

        return redirect()->route('list/users')->with('success', 'User added successfully.');
    }


    /** user view */
    public function userView($id)
    {
        $users = User::where('user_id', $id)->first();
        return view('usermanagement.user_update', compact('users'));
    }

    /** user Update */
    public function userUpdate(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Session::get('role_name') === 'Admin' || Session::get('role_name') === 'Super Admin') {
                $user_id       = $request->user_id;
                $name         = $request->name;
                $email        = $request->email;
                $role_name    = $request->role_name;
                $position     = $request->position;
                $phone        = $request->phone_number;
                $department   = $request->department;
                $status       = $request->status;

                $image_name = $request->hidden_avatar;
                $image = $request->file('avatar');

                if ($image_name == 'photo_defaults.jpg') {
                    if ($image != '') {
                        $image_name = rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('/images/'), $image_name);
                    }
                } else {

                    if ($image != '') {
                        unlink('images/' . $image_name);
                        $image_name = rand() . '.' . $image->getClientOriginalExtension();
                        $image->move(public_path('/images/'), $image_name);
                    }
                }

                $update = [
                    'user_id'      => $user_id,
                    'name'         => $name,
                    'role_name'    => $role_name,
                    'email'        => $email,
                    'position'     => $position,
                    'phone_number' => $phone,
                    'department'   => $department,
                    'status'       => $status,
                    'avatar'       => $image_name,
                ];

                User::where('user_id', $request->user_id)->update($update);
            } else {
                Toastr::error('User update fail :)', 'Error');
            }
            DB::commit();
            Toastr::success('User updated successfully :)', 'Success');
            return redirect()->route('list/users');
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('User update fail :)', 'Error');
            return redirect()->route('list/users');
        }
    }

    /** user delete */
    public function userDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            if (Session::get('role_name') === 'Super Admin') {
                if ($request->avatar == 'photo_defaults.jpg') {
                    User::destroy($request->user_id);
                } else {
                    User::destroy($request->user_id);
                    unlink('images/' . $request->avatar);
                }
            } else {
                Toastr::error('User deleted fail :)', 'Error');
            }

            DB::commit();
            Toastr::success('User deleted successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('User deleted fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** change password */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password'     => ['required', new MatchOldPassword],
            'new_password'         => ['required'],
            'new_confirm_password' => ['same:new_password'],
        ]);

        User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);
        DB::commit();
        Toastr::success('User change successfully :)', 'Success');
        return redirect()->intended('home');
    }
}
