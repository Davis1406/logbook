<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Operation;
use Illuminate\Support\Facades\Log;

class OperationController extends Controller
{
    public function list()
    {
        $role = session('role_name');
        $userId = auth()->id();

        if ($role === 'Trainee') {
            // Show only operations logged by the trainee
            $operations = \App\Models\Operation::where('trainee_id', $userId)->get();
        } elseif ($role === 'Supervisor') {
            // Show operations supervised by this supervisor
            $operations = \App\Models\Operation::where('supervisor_id', $userId)->get();
        } else {
            // Admin or others see all
            $operations = \App\Models\Operation::all();
        }

        return view('operations.list', compact('operations'));
    }


    public function create()
    {
        $role = session('role_name');

        // Only allow trainees to log operations
        if ($role !== 'Trainee') {
            return redirect()->route('operations.list')->with('error', 'You are not authorized to log an operation.');
        }

        // Fetch all rotations and objectives
        $rotations = \App\Models\Rotation::all();
        $objectives = \App\Models\Objective::all();

        return view('operations.add-operation', compact('rotations', 'objectives'));
    }


    /** Store new item */
    public function store(Request $request)
    {
        try {
            Operation::create($request->all());
            return redirect()->route('operation.index')->with('success', 'Operation created successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Failed to create Operation.');
        }
    }

    /** Show edit form */
    public function edit($id)
    {
        $item = Operation::findOrFail($id);
        return view('operations.edit-operation', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $operation = Operation::findOrFail($id);
        $role = session('role_name');
        $userId = auth()->id();

        if ($role === 'Supervisor') {
            if ($operation->supervisor_id != $userId) {
                return back()->with('error', 'Unauthorized.');
            }

            // Only update status
            $operation->status = $request->status;
            $operation->save();
        } elseif ($role === 'Trainee' && $operation->trainee_id == $userId) {
            $operation->update($request->all());
        } else {
            return back()->with('error', 'You are not authorized to update this operation.');
        }

        return redirect()->route('operations.list')->with('success', 'Operation updated successfully.');
    }


    /** Delete item */
    public function destroy($id)
    {
        $item = Operation::findOrFail($id);
        $item->delete();
        return back()->with('success', 'Operation deleted successfully.');
    }
}
