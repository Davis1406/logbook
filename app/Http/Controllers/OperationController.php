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

        // Get the current trainee
        $trainee = \App\Models\Trainees::where('trainee_id', auth()->id())->firstOrFail();

        // Get rotations that belong to the trainee's programme
        $rotations = \App\Models\Rotation::where('programme_id', $trainee->programme_id)->get();

        // Get all supervisors
        $supervisors = \DB::table('supervisors')
            ->join('users', 'users.id', '=', 'supervisors.supervisor_id')
            ->select('supervisors.supervisor_id', 'users.name')
            ->get();

        return view('operations.add-operation', compact('rotations', 'supervisors', 'trainee'));
    }

    public function getObjectivesByRotation($rotation_id)
    {
        try {
            // Verify that the rotation belongs to the current trainee's programme
            $role = session('role_name');

            if ($role === 'Trainee') {
                $trainee = \App\Models\Trainees::where('trainee_id', auth()->id())->first();

                if (!$trainee) {
                    return response()->json(['error' => 'Trainee not found'], 404);
                }

                // Check if the rotation belongs to the trainee's programme
                $rotation = \App\Models\Rotation::where('id', $rotation_id)
                    ->where('programme_id', $trainee->programme_id)
                    ->first();

                if (!$rotation) {
                    return response()->json(['error' => 'Invalid rotation for your programme'], 403);
                }
            }

            // Get objectives for this rotation
            $objectives = \App\Models\Objective::where('rotation_id', $rotation_id)
                ->select('id', 'objective_code', 'description')
                ->get();

            return response()->json($objectives);
        } catch (\Exception $e) {
            \Log::error('Error fetching objectives: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to load objectives'], 500);
        }
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
            $traineeUserId = auth()->id();
            $trainee = \App\Models\Trainees::where('trainee_id', $traineeUserId)->firstOrFail();

            // Verify that the selected rotation belongs to the trainee's programme
            $rotation = \App\Models\Rotation::where('id', $request->rotation_id)
                ->where('programme_id', $trainee->programme_id)
                ->first();

            if (!$rotation) {
                return back()->with('error', 'Invalid rotation selected for your programme.');
            }

            // Verify that the selected objective belongs to the selected rotation
            $objective = \App\Models\Objective::where('id', $request->objective_id)
                ->where('rotation_id', $request->rotation_id)
                ->first();

            if (!$objective) {
                return back()->with('error', 'Invalid objective selected for the chosen rotation.');
            }

            // Resolve supervisor
            $supervisorId = null;
            $supervisorName = null;

            if ($request->supervisor_id === 'other') {
                if (empty($request->supervisor_name)) {
                    return back()->with('error', 'Please enter a supervisor name.');
                }
                $supervisorName = $request->supervisor_name;
            } elseif (!empty($request->supervisor_id)) {
                // supervisor_id is actually users.id (supervisor.supervisor_id)
                $supervisor = \App\Models\Supervisor::where('supervisor_id', $request->supervisor_id)->first();
                if (!$supervisor) {
                    return back()->with('error', 'Selected supervisor not found.');
                }
                $supervisorId = $supervisor->id;
                $supervisorName = $supervisor->name;
            }

            Operation::create([
                'trainee_id'         => $trainee->id,
                'programme_id'       => $trainee->programme_id,
                'hospital_id'        => $trainee->hospital_id,
                'supervisor_id'      => $supervisorId,
                'supervisor_name'    => $supervisorName,
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

        // Check authorization
        $role = session('role_name');
        $userId = auth()->id();

        if ($role === 'Trainee') {
            $trainee = \App\Models\Trainees::where('trainee_id', $userId)->first();
            if (!$trainee || $operation->trainee_id != $trainee->id) {
                return redirect()->route('operations.list')->with('error', 'You are not authorized to edit this operation.');
            }
        }

        // Get the trainee to ensure proper filtering
        $trainee = \App\Models\Trainees::find($operation->trainee_id);

        // Get rotations for the trainee's programme
        $rotations = Rotation::where('programme_id', $trainee->programme_id)->get();

        // Get objectives for the current rotation (this is important for edit)
        $objectives = Objective::where('rotation_id', $operation->rotation_id)->get();

        // Get all supervisors with proper join
        $supervisors = \DB::table('supervisors')
            ->join('users', 'users.id', '=', 'supervisors.supervisor_id')
            ->select('supervisors.supervisor_id', 'users.name')
            ->get();

        return view('operations.edit-operation', compact('operation', 'rotations', 'objectives', 'supervisors', 'trainee'));
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
                // Get supervisor record by linked user ID
                $supervisor = \App\Models\Supervisor::where('supervisor_id', $userId)->first();

                if (!$supervisor) {
                    return back()->with('error', 'Supervisor record not found.');
                }

                $request->validate([
                    'status' => 'required|in:pending,approved,rejected',
                    'supervisor_remarks' => 'nullable|string|max:2000',
                ]);

                // Update operation status and remarks
                $operation->status = $request->status;
                $operation->supervisor_remarks = $request->supervisor_remarks;

                // If not already assigned to this supervisor, assign them
                if (is_null($operation->supervisor_id)) {
                    $operation->supervisor_id = $supervisor->id;
                    $operation->supervisor_name = $supervisor->name;
                }

                $operation->save();

                $statusText = ucfirst($request->status);
                return redirect()->route('operations/list')
                    ->with('success', "Operation status updated to {$statusText} successfully.");
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
                    'supervisor_id'      => 'required|string', // Now required since we removed 'other'
                ]);

                // Verify that the selected rotation belongs to the trainee's programme
                $rotation = \App\Models\Rotation::where('id', $request->rotation_id)
                    ->where('programme_id', $trainee->programme_id)
                    ->first();

                if (!$rotation) {
                    return back()->with('error', 'Invalid rotation selected for your programme.');
                }

                // Verify that the selected objective belongs to the selected rotation
                $objective = \App\Models\Objective::where('id', $request->objective_id)
                    ->where('rotation_id', $request->rotation_id)
                    ->first();

                if (!$objective) {
                    return back()->with('error', 'Invalid objective selected for the chosen rotation.');
                }

                // Handle supervisor selection - only from dropdown now
                $supervisor = \App\Models\Supervisor::where('supervisor_id', $request->supervisor_id)->first();

                if (!$supervisor) {
                    return back()->with('error', 'Selected supervisor not found.');
                }

                $supervisorId = $supervisor->id;
                $supervisorName = $supervisor->name;

                // Update the operation
                $operation->update([
                    'supervisor_id'      => $supervisorId,
                    'supervisor_name'    => $supervisorName,
                    'rotation_id'        => $request->rotation_id,
                    'objective_id'       => $request->objective_id,
                    'procedure_date'     => $request->procedure_date,
                    'procedure_time'     => $request->procedure_time,
                    'duration'           => $request->duration,
                    'participation_type' => $request->participation_type,
                    'procedure_notes'    => $request->procedure_notes,
                    'status'             => 'pending', // Reset status to pending when trainee edits
                    'supervisor_remarks' => null, // Clear supervisor remarks when trainee edits
                ]);

                return redirect()->route('operations/list')->with('success', 'Operation updated successfully.');
            }

            if ($role === 'Admin') {
                // Admin can update any operation with full access
                $request->validate([
                    'rotation_id'        => 'required|exists:rotations,id',
                    'objective_id'       => 'required|exists:objectives,id',
                    'procedure_date'     => 'required|date',
                    'procedure_time'     => 'required',
                    'duration'           => 'required|integer|min:1',
                    'participation_type' => 'required|string',
                    'procedure_notes'    => 'nullable|string',
                    'supervisor_id'      => 'nullable|string',
                    'status'             => 'nullable|in:pending,approved,rejected',
                    'supervisor_remarks' => 'nullable|string|max:2000',
                ]);

                // Handle supervisor selection for admin
                $supervisorId = null;
                $supervisorName = null;

                if (!empty($request->supervisor_id)) {
                    $supervisor = \App\Models\Supervisor::where('supervisor_id', $request->supervisor_id)->first();
                    if ($supervisor) {
                        $supervisorId = $supervisor->id;
                        $supervisorName = $supervisor->name;
                    }
                }

                $operation->update([
                    'supervisor_id'      => $supervisorId,
                    'supervisor_name'    => $supervisorName,
                    'rotation_id'        => $request->rotation_id,
                    'objective_id'       => $request->objective_id,
                    'procedure_date'     => $request->procedure_date,
                    'procedure_time'     => $request->procedure_time,
                    'duration'           => $request->duration,
                    'participation_type' => $request->participation_type,
                    'procedure_notes'    => $request->procedure_notes,
                    'status'             => $request->status ?? $operation->status,
                    'supervisor_remarks' => $request->supervisor_remarks,
                ]);

                return redirect()->route('operations/list')->with('success', 'Operation updated successfully.');
            }

            return back()->with('error', 'You are not authorized to update this operation.');
        } catch (\Exception $e) {
            Log::error('Operation Update Error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
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
