<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Objective;
use Illuminate\Support\Facades\Log;

class ObjectiveController extends Controller
{
    /** Display list */
    public function list()
    {
        $objectives = Objective::all();
        return view('objectives.list', compact('objectives'));
    }

    /** Show create form */
    public function create()
    {
        $rotations = \App\Models\Rotation::all(); // Fetch all rotations
        return view('objectives.add-objective', compact('rotations'));
    }

    /** Store new item */
    public function store(Request $request)
    {
        try {
            Objective::create($request->all());
            return redirect()->route('objectives.list')->with('success', 'Objective created successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Failed to create Objective.');
        }
    }

    public function edit($id)
    {
        $objective = Objective::findOrFail($id); // Use variable name to match the Blade
        $rotations = \App\Models\Rotation::all(); // Include this for the dropdown
        return view('objectives.edit-objective', compact('objective', 'rotations'));
    }


    /** Update item */
    public function update(Request $request, $id)
    {
        $objective = Objective::findOrFail($id);
        $objective->update($request->all());
        return redirect()->route('objectives.list')->with('success', 'Objective updated successfully.');
    }

    /** Delete item */
    public function destroy($id)
    {
        $item = Objective::findOrFail($id);
        $item->delete();
        return back()->with('success', 'Objective deleted successfully.');
    }
}
