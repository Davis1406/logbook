<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Models\Trainees;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\TrainingProgramme;
use App\Models\Hospitals;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;


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

    public function traineeAdd()
    {
        $programmes = TrainingProgramme::all();
        $hospitals = Hospitals::all();
        return view('trainees.add-trainee', compact('programmes', 'hospitals'));
    }
    public function traineeSave(Request $request)
    {
        $request->validate([
            'first_name'    => 'required|string',
            'last_name'     => 'required|string',
            'gender'        => 'required|in:Male,Female,Others',
            'country'       => 'required|string',
            'email'         => 'required|email|unique:users,email',
            'phone_number'  => 'required|string',
            'study_year'    => 'required|integer',
            'programme_id'  => 'required|exists:training_programmes,id',
            'hospital_id'   => 'required|exists:hospitals,id',
            'admission_id'  => 'required|string',
            'password'      => 'required|string|min:8|confirmed',
            'password_confirmation' => 'required|string',
            'upload'        => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // dd($request->all());

        DB::beginTransaction();

        try {
            // Default null in case no file is uploaded
            $upload_file = null;

            // Handle file upload BEFORE saving
            if ($request->hasFile('upload')) {
                $file = $request->file('upload');

                // Use the original filename
                $upload_file = $file->getClientOriginalName();

                // Optional: sanitize the filename if needed
                $upload_file = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $upload_file);

                // Store in: storage/app/public/trainees-photos/
                $file->storeAs('trainees-photos', $upload_file);

                // Log success
                Log::info('Trainee photo uploaded successfully', [
                    'filename' => $upload_file,
                    'stored_path' => 'storage/trainees-photos/' . $upload_file
                ]);
            }

            $todayDate = Carbon::now()->toDateTimeString();

            // Create user
            $user = User::create([
                'name'         => $request->first_name . ' ' . $request->last_name,
                'email'        => $request->email,
                'phone_number' => $request->phone_number,
                'password'     => Hash::make($request->password),
                'status'       => 'active',
                'role_name'    => 'Trainee',
                'join_date'    => $todayDate,
            ]);

            // dd($user);

            // Assign user role
            UserRole::create([
                'user_id'   => $user->id,
                'role_id'   => 2, 
                'is_active' => 1,
            ]);

            // Save trainee details
            $trainee = new Trainees();
            $trainee->trainee_id    = $user->id;
            $trainee->first_name    = $request->first_name;
            $trainee->last_name     = $request->last_name;
            $trainee->gender        = $request->gender;
            $trainee->country       = $request->country;
            $trainee->email         = $request->email;
            $trainee->phone_number  = $request->phone_number;
            $trainee->study_year    = $request->study_year;
            $trainee->programme_id  = $request->programme_id;
            $trainee->hospital_id   = $request->hospital_id;
            $trainee->admission_id  = $request->admission_id;
            $trainee->upload        = $upload_file ? 'trainees-photos/' . $upload_file : null;
            $trainee->save();



            DB::commit();
            Toastr::success('Trainee has been added successfully :)', 'Success');
            return redirect()->route('trainees/list');
        } catch (\Exception $e) {
            DB::rollback();
            \Log::error('Trainee Save Error: ' . $e->getMessage());
            Toastr::error('Failed to add new trainee. Please try again.', 'Error');
            return redirect()->back();
        }
    }


public function traineesEdit($id)
{
    $trainee = Trainees::findOrFail($id);
    $programmes = TrainingProgramme::all();
    $hospitals = Hospitals::all();

    return view('trainees.edit-trainee', compact('trainee', 'programmes', 'hospitals'));
}

public function traineeUpdate(Request $request)
{
    DB::beginTransaction();

    try {
        $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'gender'        => 'required',
            'country'       => 'required|string',
            'study_year'    => 'required|integer',
            'email'         => 'required|email',
            'phone_number'  => 'required|string',
            'programme_id'  => 'required|integer',
            'hospital_id'   => 'required|integer',
            'admission_id'  => 'nullable|string',
            'upload'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $trainee = Trainees::findOrFail($request->id);

        // Default to existing image
        $upload_file = $trainee->upload;

        // Handle new upload
        if ($request->hasFile('upload')) {
            // Delete old file if exists (from public disk)
            if ($trainee->upload && Storage::disk('public')->exists($trainee->upload)) {
                Storage::disk('public')->delete($trainee->upload);
            }

            // Use original file name with cleanup
            $originalName = $request->file('upload')->getClientOriginalName();
            $safeName = preg_replace('/[^A-Za-z0-9.\-_]/', '_', $originalName);

            // Store in: storage/app/public/trainees-photos/ (public disk)
            $upload_file = $request->file('upload')->storeAs('trainees-photos', $safeName, 'public');
        }

        // Update trainee record
        $trainee->update([
            'first_name'    => $request->first_name,
            'last_name'     => $request->last_name,
            'gender'        => $request->gender,
            'country'       => $request->country,
            'study_year'    => $request->study_year,
            'email'         => $request->email,
            'phone_number'  => $request->phone_number,
            'programme_id'  => $request->programme_id,
            'hospital_id'   => $request->hospital_id,
            'admission_id'  => $request->admission_id,
            'upload'        => $upload_file,
        ]);

        DB::commit();
        Toastr::success('Trainee has been updated successfully :)', 'Success');
        return redirect()->route('trainees/list');

    } catch (\Exception $e) {
        DB::rollback();
        \Log::error('Trainee Update Error:', ['error' => $e->getMessage()]);
        Toastr::error('Failed to update trainee.', 'Error');
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
