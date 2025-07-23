<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Supervisor;
use App\Models\UserRole;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;

class SupervisorController extends Controller
{
    /** add supervisor page */
    public function supervisorAdd()
    {
        return view('supervisor.add-supervisor');
    }

    /** supervisor list */
    public function supervisorList()
    {
        $listSupervisor = DB::table('users')
            ->join('supervisors', 'supervisors.supervisor_id', 'users.id')
            ->select('users.user_id', 'users.name', 'users.avatar', 'supervisors.id', 'supervisors.gender', 'supervisors.mobile', 'supervisors.address')
            ->get();
        return view('supervisor.list-supervisors', compact('listSupervisor'));
    }

    /** supevisor Grid */
    public function supervisorGrid()
    {
        $supervisorGrid = Supervisor::all();
        return view('supervisor.supervisors-grid', compact('supervisorGrid'));
    }

    /** save record */
    public function saveRecord(Request $request)
    {
        $request->validate([
            'full_name'       => 'required|string',
            'gender'          => 'required|string',
            'date_of_birth'   => 'required|string',
            'mobile'          => 'required|string',
            'joining_date'    => 'required|string',
            'qualification'   => 'required|string',
            'experience'      => 'required|string',
            'username'        => 'required|string',
            'email'           => 'required|email|unique:users,email',
            'password'        => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required',
            'address'         => 'required|string',
            'city'            => 'required|string',
            'state'           => 'required|string',
            'zip_code'        => 'required|string',
            'country'         => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            $todayDate = Carbon::now()->toDayDateTimeString();

            // 1. Create User
            $user = User::create([
                'name'         => $request->full_name,
                'email'        => $request->email,
                'join_date'    => $todayDate,
                'role_name'    => 'Supervisor',
                'phone_number' => $request->mobile,
                'password'     => Hash::make($request->password),
                'status'       => 'active',
            ]);

            // 2. Assign Role
            UserRole::create([
                'user_id'   => $user->id,
                'role_id'   => 3,
                'is_active' => 1,
            ]);

            // 3. Save Supervisor Record
            $supervisor = new Supervisor();
            $supervisor->supervisor_id       = $user->id; 
            $supervisor->full_name     = $request->full_name;
            $supervisor->gender        = $request->gender;
            $supervisor->date_of_birth = $request->date_of_birth;
            $supervisor->mobile        = $request->mobile;
            $supervisor->joining_date  = $request->joining_date;
            $supervisor->qualification = $request->qualification;
            $supervisor->experience    = $request->experience;
            $supervisor->username      = $request->username;
            $supervisor->address       = $request->address;
            $supervisor->city          = $request->city;
            $supervisor->state         = $request->state;
            $supervisor->zip_code      = $request->zip_code;
            $supervisor->country       = $request->country;
            $supervisor->save();

            DB::commit();
            Toastr::success('Supervisor has been added successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Supervisor Save Error: ' . $e->getMessage());
            Toastr::error('Failed to add new supervisor.', 'Error');
             return redirect()->route('supervisor/list-supervisors')->with('success', 'User added successfully.');
        }
    }

    /** edit record */
    public function editRecord($id)
    {
        $supervisor = SUpervisor::where('id', $id)->first();
        return view('supervisor.edit-supervisor', compact('supervisor'));
    }

    /** update record teacher */
    public function updateRecordSupervisor(Request $request)
    {
        DB::beginTransaction();
        try {

            $updateRecord = [
                'full_name'     => $request->full_name,
                'gender'        => $request->gender,
                'date_of_birth' => $request->date_of_birth,
                'mobile'        => $request->mobile,
                'joining_date'  => $request->joining_date,
                'qualification' => $request->qualification,
                'experience'    => $request->experience,
                'username'      => $request->username,
                'address'       => $request->address,
                'city'          => $request->city,
                'state'         => $request->state,
                'zip_code'      => $request->zip_code,
                'country'      => $request->country,
            ];
            Supervisor::where('id', $request->id)->update($updateRecord);

            Toastr::success('Has been update successfully :)', 'Success');
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('fail, update record  :)', 'Error');
            return redirect()->back();
        }
    }

    /** delete record */
    public function teacherDelete(Request $request)
    {
        DB::beginTransaction();
        try {

        Supervisor::destroy($request->id);
            DB::commit();
            Toastr::success('Deleted record successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Deleted record fail :)', 'Error');
            return redirect()->back();
        }
    }
}
