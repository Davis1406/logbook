<?php

namespace App\Http\Controllers;

use DB;
use App\Models\Trainees;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;

class TraineeController extends Controller
{
    /** index page trainees list */
    public function trainees()
    {
        $traineesList = Trainees::all();
        return view('trainees.list', compact('traineesList'));
    }

    /** index page Trainees grid */
    public function traineestGrid()
    {
        $traineesList = Trainees::all();
        return view('trainees.trainees-grid', compact('traineesList'));
    }

    /** Trainees add page */
    public function traineeAdd()
    {
        return view('trainees.add-trainee');
    }

    public function traineeSave(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'gender'        => 'required|not_in:0',
            'date_of_birth' => 'required|string',
            'roll'          => 'required|string',
            'blood_group'   => 'required|string',
            'religion'      => 'required|string',
            'email'         => 'required|email|unique:users,email', // Make sure the email is unique in users table
            'class'         => 'required|string',
            'section'       => 'required|string',
            'admission_id'  => 'required|string',
            'phone_number'  => 'required',
            'upload'        => 'required|image',
        ]);

        DB::beginTransaction();
        try {
            // 1. Upload photo
            $upload_file = rand() . '.' . $request->upload->extension();
            $request->upload->move(storage_path('app/public/Trainees-photos/'), $upload_file);

            // 2. Create User
            $user = new User();
            $user->name         = $request->first_name . ' ' . $request->last_name;
            $user->email        = $request->email;
            $user->phone_number = $request->phone_number;
            $user->password     = Hash::make('default123'); // You can later send password setup link
            $user->status       = 'active';
            $user->role_name    = 'trainee';
            $user->join_date    = Carbon::now()->toDayDateTimeString();
            $user->save();

            // 3. Assign Role
            UserRole::create([
                'user_id' => $user->id,
                'role_id' => 2, 
            ]);

            // 4. Save Trainee record
            $trainee = new Trainees();
            $trainee->trainee_id      = $user->id;
            $trainee->first_name   = $request->first_name;
            $trainee->last_name    = $request->last_name;
            $trainee->gender       = $request->gender;
            $trainee->date_of_birth = $request->date_of_birth;
            $trainee->roll         = $request->roll;
            $trainee->blood_group  = $request->blood_group;
            $trainee->religion     = $request->religion;
            $trainee->email        = $request->email;
            $trainee->class        = $request->class;
            $trainee->section      = $request->section;
            $trainee->admission_id = $request->admission_id;
            $trainee->phone_number = $request->phone_number;
            $trainee->upload       = $upload_file;
            $trainee->save();

            DB::commit();
            Toastr::success('Trainee has been added successfully :)', 'Success');
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Failed to add new trainee. Please try again.', 'Error');
            return redirect()->back();
        }
    }

    /** view for edit Trainees */
    public function traineesEdit($id)
    {
        $traineesEdit = Trainees::where('id', $id)->first();
        return view('trainees.edit-trainee', compact('traineesEdit'));
    }

    /** update record */
    public function studentUpdate(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!empty($request->upload)) {
                unlink(storage_path('app/public/Trainees-photos/' . $request->image_hidden));
                $upload_file = rand() . '.' . $request->upload->extension();
                $request->upload->move(storage_path('app/public/Trainees-photos/'), $upload_file);
            } else {
                $upload_file = $request->image_hidden;
            }

            $updateRecord = [
                'upload' => $upload_file,
            ];
            Trainees::where('id', $request->id)->update($updateRecord);

            Toastr::success('Has been update successfully :)', 'Success');
            DB::commit();
            return redirect()->back();
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('fail, update Trainees  :)', 'Error');
            return redirect()->back();
        }
    }

    /** Trainees delete */
    public function studentDelete(Request $request)
    {
        DB::beginTransaction();
        try {

            if (!empty($request->id)) {
                Trainees::destroy($request->id);
                unlink(storage_path('app/public/Trainees-photos/' . $request->avatar));
                DB::commit();
                Toastr::success('Trainees deleted successfully :)', 'Success');
                return redirect()->back();
            }
        } catch (\Exception $e) {
            DB::rollback();
            Toastr::error('Trainees deleted fail :)', 'Error');
            return redirect()->back();
        }
    }

    /** Trainees profile page */
    public function studentProfile($id)
    {
        $studentProfile = Trainees::where('id', $id)->first();
        return view('Trainees.Trainees-profile', compact('studentProfile'));
    }
}
