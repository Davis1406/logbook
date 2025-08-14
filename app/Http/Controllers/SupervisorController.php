<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Supervisor;
use App\Models\UserRole;
use App\Models\TrainingProgramme;
use App\Models\Hospitals;
use App\Models\Operation;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class SupervisorController extends Controller
{
    public function addSupervisor()
    {
        $programmes = TrainingProgramme::all();
        $hospitals = Hospitals::orderBy('hospital_name')->get();

        // Debug: Check what's actually being retrieved
        return view('supervisor.add-supervisor', compact('programmes', 'hospitals'));
    }

    public function supervisorList()
    {
        $listSupervisor = Supervisor::with(['programme', 'hospital'])
            ->join('users', 'users.id', '=', 'supervisors.supervisor_id')
            ->select(
                'users.user_id',
                'users.name',
                'users.email',
                'supervisors.*', // This ensures you get all supervisor fields including avatar
                'training_programmes.programme_name',
                'hospitals.hospital_name'
            )
            ->leftJoin('training_programmes', 'supervisors.programme_id', '=', 'training_programmes.id')
            ->leftJoin('hospitals', 'supervisors.hospital_id', '=', 'hospitals.id')
            ->get();

        return view('supervisor.list-supervisors', compact('listSupervisor'));
    }

    /** supevisor Grid */
    public function supervisorGrid()
    {
        $supervisorGrid = Supervisor::all();
        return view('supervisor.supervisors-grid', compact('supervisorGrid'));
    }

    public function saveRecord(Request $request)
    {
        // Enhanced validation including email and password fields
        $validator = Validator::make($request->all(), [
            'name'                 => 'required|string|max:255',
            'email'                => 'required|email|unique:users,email',
            'password'             => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string|min:8',
            'gender'               => 'required|string|in:Male,Female',
            'country'              => 'required|string|max:255',
            'mobile'               => 'required|string|max:20',
            'programme_id'         => 'required|integer|exists:training_programmes,id',
            'hospital_id'          => 'required|integer|exists:hospitals,id',
            'avatar'               => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Supervisor Validation Failed', [
                'errors' => $validator->errors()->toArray(),
                'input'  => $request->except(['password', 'password_confirmation']) // Don't log passwords
            ]);

            // Add user-friendly error messages for toastr
            foreach ($validator->errors()->all() as $error) {
                Toastr::error($error, 'Validation Error');
            }

            return redirect()->back()->withErrors($validator)->withInput($request->except(['password', 'password_confirmation']));
        }

        DB::beginTransaction();

        try {
            // Handle avatar upload
            $avatar = null;
            if ($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $originalName = $file->getClientOriginalName();
                $safeName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $originalName);
                $avatar = $file->storeAs('supervisors-photos', $safeName, 'public');
                Log::info('Avatar uploaded', ['filename' => $avatar]);
            }

            // Create user with actual email from form
            $user = User::create([
                'name'         => $request->name,
                'email'        => $request->email, // Use the actual email from the form
                'phone_number' => $request->mobile,
                'role_name'    => 'Supervisor',
                'join_date'    => now(),
                'status'       => 'active',
                'password'     => Hash::make($request->password), // Use the actual password from form
            ]);
            Log::info('User created', ['user_id' => $user->id, 'email' => $user->email]);

            // Assign role
            UserRole::create([
                'user_id'   => $user->id,
                'role_id'   => 2, // supervisor role
                'is_active' => 1,
            ]);
            Log::info('User role assigned', ['user_id' => $user->id]);

            // Save supervisor details
            Supervisor::create([
                'supervisor_id' => $user->id,
                'name'          => $request->name,
                'gender'        => $request->gender,
                'country'       => $request->country,
                'mobile'        => $request->mobile,
                'programme_id'  => $request->programme_id,
                'hospital_id'   => $request->hospital_id,
                'avatar'        => $avatar,
            ]);
            Log::info('Supervisor record created', ['supervisor_id' => $user->id]);

            DB::commit();
            Toastr::success('Supervisor has been added successfully with email: ' . $request->email, 'Success');
            return redirect()->route('supervisors/list');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Supervisor Save Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'input' => $request->except(['password', 'password_confirmation'])
            ]);
            Toastr::error('Failed to add new supervisor: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput($request->except(['password', 'password_confirmation']));
        }
    }

    /** edit record */
    public function editSupervisor($id)
    {
        $supervisor = Supervisor::with('programme', 'hospital')->findOrFail($id);

        // Get the user data as well
        $user = User::find($supervisor->supervisor_id);

        $programmes = TrainingProgramme::all();
        $hospitals = Hospitals::orderBy('hospital_name')->get();

        return view('supervisor.edit-supervisor', compact('supervisor', 'user', 'programmes', 'hospitals'));
    }

    public function updateSupervisor(Request $request, $id)
    {
        $supervisor = Supervisor::findOrFail($id);
        $user = User::findOrFail($supervisor->supervisor_id);

        // Validation rules for update
        $rules = [
            'name'         => 'required|string|max:255',
            'email'        => 'required|email|unique:users,email,' . $user->id,
            'gender'       => 'required|string|in:Male,Female',
            'country'      => 'required|string|max:255',
            'mobile'       => 'required|string|max:20',
            'programme_id' => 'required|integer|exists:training_programmes,id',
            'hospital_id'  => 'required|integer|exists:hospitals,id',
            'avatar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        // Only validate password if it's provided
        if ($request->filled('password')) {
            $rules['password'] = 'required|string|min:8|confirmed';
            $rules['password_confirmation'] = 'required|string|min:8';
        }

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::error('Supervisor Update Validation Failed', [
                'errors' => $validator->errors()->toArray(),
                'supervisor_id' => $id
            ]);

            foreach ($validator->errors()->all() as $error) {
                Toastr::error($error, 'Validation Error');
            }

            return redirect()->back()->withErrors($validator)->withInput($request->except(['password', 'password_confirmation']));
        }

        DB::beginTransaction();

        try {
            // Handle avatar upload
            $avatar = $supervisor->avatar; // Keep existing avatar by default
            if ($request->hasFile('avatar')) {
                // Delete old avatar if exists
                if ($supervisor->avatar && Storage::disk('public')->exists($supervisor->avatar)) {
                    Storage::disk('public')->delete($supervisor->avatar);
                }

                $file = $request->file('avatar');
                $originalName = $file->getClientOriginalName();
                $safeName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $originalName);
                $avatar = $file->storeAs('supervisors-photos', $safeName, 'public');
                Log::info('Avatar updated', ['filename' => $avatar]);
            }

            // Update user record
            $userData = [
                'name'         => $request->name,
                'email'        => $request->email,
                'phone_number' => $request->mobile,
            ];

            // Only update password if provided
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }

            $user->update($userData);
            Log::info('User updated', ['user_id' => $user->id, 'email' => $user->email]);

            // Update supervisor record
            $supervisor->update([
                'name'         => $request->name,
                'gender'       => $request->gender,
                'country'      => $request->country,
                'mobile'       => $request->mobile,
                'programme_id' => $request->programme_id,
                'hospital_id'  => $request->hospital_id,
                'avatar'       => $avatar,
            ]);
            Log::info('Supervisor record updated', ['supervisor_id' => $supervisor->id]);

            DB::commit();
            Toastr::success('Supervisor has been updated successfully', 'Success');
            return redirect()->route('supervisors/list');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Supervisor Update Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'supervisor_id' => $id
            ]);
            Toastr::error('Failed to update supervisor: ' . $e->getMessage(), 'Error');
            return redirect()->back()->withInput($request->except(['password', 'password_confirmation']));
        }
    }

    /** delete record */
    public function teacherDelete(Request $request)
    {
        DB::beginTransaction();
        try {
            $supervisor = Supervisor::findOrFail($request->id);

            // Delete the associated user as well
            if ($supervisor->supervisor_id) {
                $user = User::find($supervisor->supervisor_id);
                if ($user) {
                    // Delete user roles first
                    UserRole::where('user_id', $user->id)->delete();
                    // Delete user
                    $user->delete();
                }
            }

            // Delete avatar file if exists
            if ($supervisor->avatar && Storage::disk('public')->exists($supervisor->avatar)) {
                Storage::disk('public')->delete($supervisor->avatar);
            }

            // Delete supervisor record
            $supervisor->delete();

            DB::commit();
            Toastr::success('Supervisor and associated user deleted successfully', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Supervisor Delete Error: ' . $e->getMessage());
            Toastr::error('Failed to delete supervisor: ' . $e->getMessage(), 'Error');
            return redirect()->back();
        }
    }
}
