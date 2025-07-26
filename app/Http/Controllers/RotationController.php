<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rotation;
use Illuminate\Support\Facades\Log;

class RotationController extends Controller
{
    /** Display list */
    public function list()
    {
        $rotations = Rotation::all();
        return view('rotations.list', compact('rotations'));
    }

    /** Show create form */
    public function create()
    {
        $programmes = \App\Models\TrainingProgramme::all(); // fetch all training programmes
        return view('rotations.add-rotation', compact('programmes'));
    }

    /** Store new item */
    public function store(Request $request)
    {
        try {
            Rotation::create($request->all());
            return redirect()->route('rotations.list')->with('success', 'Rotation created successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Failed to create Rotation.');
        }
    }

    /** Show edit form */
public function edit($id)
{
    $rotation = Rotation::findOrFail($id);
    $programmes = \App\Models\TrainingProgramme::all();
    return view('rotations.edit-rotation', compact('rotation', 'programmes'));
}


    /** Update item */
    public function update(Request $request, $id)
    {
        $rotation = Rotation::findOrFail($id);
        $rotation->update($request->all());
        return redirect()->route('rotations.list')->with('success', 'Rotation updated successfully.');
    }

    /** Delete item */
    public function destroy($id)
    {
        $item = Rotation::findOrFail($id);
        $item->delete();
        return back()->with('success', 'Rotation deleted successfully.');
    }
}
