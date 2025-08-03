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
        $listSupervisor = DB::table('users')
            ->join('supervisors', 'supervisors.supervisor_id', '=', 'users.id')
            ->leftJoin('training_programmes', 'supervisors.programme_id', '=', 'training_programmes.id')
            ->leftJoin('hospitals', 'supervisors.hospital_id', '=', 'hospitals.id')
            ->select(
                'users.user_id',
                'users.name',
                'supervisors.avatar',
                'supervisors.id',
                'supervisors.gender',
                'supervisors.country',
                'supervisors.mobile',
                'training_programmes.programme_name',
                'hospitals.hospital_name'
            )
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
        // Validate with manual check to log errors
        $validator = Validator::make($request->all(), [
            'name'    => 'required|string|max:255',
            'gender'       => 'required|string',
            'country'      => 'required|string',
            'mobile'       => 'required|string',
            'programme_id' => 'required|integer|exists:training_programmes,id',
            'hospital_id'  => 'required|integer|exists:hospitals,id',
            'avatar'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($validator->fails()) {
            Log::error('Supervisor Validation Failed', [
                'errors' => $validator->errors()->toArray(),
                'input'  => $request->all()
            ]);
            return redirect()->back()->withErrors($validator)->withInput();
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

            // Create user
            $user = User::create([
                'name'         => $request->name,
                'email'        => strtolower(Str::slug($request->full_name, '.')) . '@example.com',
                'phone_number' => $request->mobile,
                'role_name'    => 'Supervisor',
                'join_date'    => now(),
                'status'       => 'active',
                'password'     => Hash::make(Str::random(10)),
            ]);
            Log::info('User created', ['user_id' => $user->id]);

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
                'name'     => $request->name,
                'gender'        => $request->gender,
                'country'       => $request->country,
                'mobile'        => $request->mobile,
                'programme_id'  => $request->programme_id,
                'hospital_id'   => $request->hospital_id,
                'avatar'        => $avatar,
            ]);
            Log::info('Supervisor record created', ['supervisor_id' => $user->id]);

            DB::commit();
            Toastr::success('Supervisor has been added successfully :)', 'Success');
            return redirect()->route('supervisors/list-supervisors');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Supervisor Save Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            Toastr::error('Failed to add new supervisor.', 'Error');
            return redirect()->back()->withInput();
        }
    }

    /** edit record */
    public function editSupervisor($id)
    {
        $supervisor = Supervisor::findOrFail($id);
        $programmes = TrainingProgramme::all();
        $hospitals = Hospitals::all();

        return view('supervisor.edit-supervisor', compact('supervisor', 'programmes', 'hospitals'));
    }


    public function update(Request $request, $id)
    {
        $operation = Operation::findOrFail($id);
        $role = session('role_name');
        $userId = auth()->id();

        DB::beginTransaction();

        try {
            if ($role === 'Supervisor') {
                // Supervisor can only update status
                if ($operation->supervisor_id != $userId) {
                    Toastr::error('Unauthorized action.', 'Error');
                    return redirect()->back();
                }

                $request->validate([
                    'status' => 'required|in:pending,approved,rejected',
                ]);

                $operation->status = $request->status;
                $operation->save();

                DB::commit();
                Toastr::success('Operation status updated successfully.', 'Success');
                return redirect()->route('operations/list');
            }

            if ($role === 'Trainee') {
                $trainee = \App\Models\Trainees::where('trainee_id', $userId)->firstOrFail();

                if ($operation->trainee_id != $trainee->id) {
                    Toastr::error('You are not authorized to update this operation.', 'Error');
                    return redirect()->back();
                }

                $request->validate([
                    'rotation_id'        => 'required|exists:rotations,id',
                    'objective_id'       => 'required|exists:objectives,id',
                    'procedure_date'     => 'required|date',
                    'procedure_time'     => 'required',
                    'duration'           => 'required|integer|min:1',
                    'participation_type' => 'required|string',
                    'procedure_notes'    => 'nullable|string',
                    'supervisor_id'      => 'nullable|string', // for 'other' option
                    'supervisor_name'    => 'nullable|string',
                ]);

                $operation->update([
                    'programme_id'       => $trainee->programme_id,
                    'hospital_id'        => $trainee->hospital_id,
                    'supervisor_id'      => $request->supervisor_id !== 'other' ? $request->supervisor_id : null,
                    'supervisor_name'    => $request->supervisor_id === 'other' ? $request->supervisor_name : null,
                    'rotation_id'        => $request->rotation_id,
                    'objective_id'       => $request->objective_id,
                    'procedure_date'     => $request->procedure_date,
                    'procedure_time'     => $request->procedure_time,
                    'duration'           => $request->duration,
                    'participation_type' => $request->participation_type,
                    'procedure_notes'    => $request->procedure_notes,
                    'status'             => 'pending', // Reset status on edit
                ]);

                DB::commit();
                Toastr::success('Operation updated successfully.', 'Success');
                return redirect()->route('operations/list');
            }

            Toastr::error('Unauthorized action.', 'Error');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Operation Update Error: ' . $e->getMessage());
            Toastr::error('Failed to update operation.', 'Error');
            return redirect()->back()->withInput();
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
