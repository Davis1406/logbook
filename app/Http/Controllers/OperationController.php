<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operation;
use Illuminate\Support\Facades\Log;
use App\Models\Rotation;
use App\Models\Objective;
use App\Models\Supervisor;

class OperationController extends Controller
{
    public function list()
    {
        $role = session('role_name');
        $userId = auth()->id();
        $currentTraineeId = null;

        if ($role === 'Trainee') {
            $trainee = \App\Models\Trainees::where('trainee_id', $userId)->first();

            if (!$trainee) {
                return redirect()->back()->with('error', 'Trainee record not found.');
            }

            $operations = \App\Models\Operation::where('trainee_id', $trainee->id)->get();
            $currentTraineeId = $trainee->id;
        } elseif ($role === 'Supervisor') {
            $supervisor = \App\Models\Supervisor::where('supervisor_id', $userId)->first();

            if (!$supervisor) {
                return redirect()->back()->with('error', 'Supervisor record not found.');
            }

            $operations = \App\Models\Operation::where('supervisor_id', $supervisor->id)->get();
        } else {
            // Admin
            $operations = \App\Models\Operation::all();
        }

        return view('operations.list', compact('operations', 'currentTraineeId'));
    }



    public function create()
    {
        $role = session('role_name');

        if ($role !== 'Trainee') {
            return redirect()->route('operations.list')->with('error', 'You are not authorized to log an operation.');
        }

        $rotations = \App\Models\Rotation::all();
        $objectives = \App\Models\Objective::all();
        $supervisors = \DB::table('supervisors')
            ->join('users', 'users.id', '=', 'supervisors.supervisor_id')
            ->select('supervisors.supervisor_id', 'users.name')
            ->get();

        return view('operations.add-operation', compact('rotations', 'objectives', 'supervisors'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'rotation_id'        => 'required|exists:rotations,id',
            'objective_id'       => 'required|exists:objectives,id',
            'procedure_date'     => 'required|date',
            'procedure_time'     => 'required',
            'duration'           => 'required|integer|min:1',
            'participation_type' => 'required|string',
            'procedure_notes'    => 'nullable|string',
        ]);

        try {
            $traineeUserId = auth()->id(); // user ID

            $trainee = \App\Models\Trainees::where('trainee_id', $traineeUserId)->firstOrFail();

            Operation::create([
                'trainee_id'         => $trainee->id, // This must match trainees.id
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
                'status'             => 'pending',
            ]);


            return redirect()->route('operations/list')->with('success', 'Operation logged successfully.');
        } catch (\Exception $e) {
            \Log::error('Operation Store Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to log operation.');
        }
    }

    /** Show edit form */
    public function edit($id)
    {
        $operation = Operation::findOrFail($id);

        $rotations = Rotation::all();
        $objectives = Objective::all();
        $supervisors = Supervisor::all();

        return view('operations.edit-operation', compact('operation', 'rotations', 'objectives', 'supervisors'));
    }


    public function view($id)
    {
        $operation = Operation::with(['trainee', 'rotation', 'objective'])->findOrFail($id);
        return view('operations.view-operation', compact('operation'));
    }
    
    public function update(Request $request, $id)
{
    $operation = Operation::findOrFail($id);
    $role = session('role_name');
    $userId = auth()->id();

    try {
        if ($role === 'Supervisor') {
            // Supervisor can only update the status of operations they supervise
            if ($operation->supervisor_id != $userId) {
                return back()->with('error', 'Unauthorized.');
            }

            $request->validate([
                'status' => 'required|in:pending,approved,rejected',
            ]);

            $operation->status = $request->status;
            $operation->save();

            return redirect()->route('operations/list')->with('success', 'Operation status updated successfully.');
        }

        if ($role === 'Trainee') {
            $trainee = \App\Models\Trainees::where('trainee_id', $userId)->firstOrFail();

            if ($operation->trainee_id != $trainee->id) {
                return back()->with('error', 'You are not authorized to update this operation.');
            }

            $request->validate([
                'rotation_id'        => 'required|exists:rotations,id',
                'objective_id'       => 'required|exists:objectives,id',
                'procedure_date'     => 'required|date',
                'procedure_time'     => 'required',
                'duration'           => 'required|integer|min:1',
                'participation_type' => 'required|string',
                'procedure_notes'    => 'nullable|string',
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

            return redirect()->route('operations/list')->with('success', 'Operation updated successfully.');
        }

        return back()->with('error', 'You are not authorized to update this operation.');
    } catch (\Exception $e) {
        Log::error('Operation Update Error: ' . $e->getMessage());
        return back()->with('error', 'Failed to update operation.');
    }
}


    /** Delete item */
    public function destroy($id)
    {
        $item = Operation::findOrFail($id);
        $item->delete();
        return back()->with('success', 'Operation deleted successfully.');
    }
}
