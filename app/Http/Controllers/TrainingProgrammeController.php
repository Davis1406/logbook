<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TrainingProgramme;
use Illuminate\Support\Facades\Log;

class TrainingProgrammeController extends Controller
{
    /** Display list */
    public function list()
    {
        $programmes = TrainingProgramme::all();
        return view('training_programmes.list', compact('programmes'));
    }

    /** Show create form */
    public function create()
    {
        return view('training_programmes.add-training-programme');
    }

    /** Store new item */
    public function store(Request $request)
    {
        try {
            TrainingProgramme::create($request->all());
            return redirect()->route('training-programmes.list')->with('success', 'TrainingProgramme created successfully.');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            return back()->with('error', 'Failed to create TrainingProgramme.');
        }
    }

    /** Show edit form */
    public function edit($id)
    {
        $programme = TrainingProgramme::findOrFail($id);
        return view('training_programmes.edit-training-programme', compact('programme'));
    }

    /** Update item */
    public function update(Request $request, $id)
    {
        $item = TrainingProgramme::findOrFail($id);
        $item->update($request->all());
        return redirect()->route('trainingprogramme.index')->with('success', 'TrainingProgramme updated successfully.');
    }

    /** Delete item */
    public function destroy($id)
    {
        $item = TrainingProgramme::findOrFail($id);
        $item->delete();
        return back()->with('success', 'TrainingProgramme deleted successfully.');
    }
}
