<?php

namespace App\Http\Controllers;

use App\Models\Hospitals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class HospitalController extends Controller
{
    /** List hospitals */
    public function hospitalList()
    {
        $hospitals = Hospitals::all();
        return view('hospitals.list-hospitals', compact('hospitals'));
    }

    /** Show add form */
    public function indexHospital()
    {
        return view('hospitals.add-hospital');
    }

    /** Store hospital */
    public function storeHospital(Request $request)
    {
        $request->validate([
            'hospital_name' => 'required|string',
            'address'       => 'required|string',
            'country'       => 'required|string',
            'director'      => 'required|string',
            'status'        => 'required|in:active,inactive',
        ]);
            // dd($request->all());
        try {
            Hospitals::create([
                'hospital_name' => $request->hospital_name,
                'address'       => $request->address,
                'country'       => $request->country,
                'director'      => $request->director,
                'status'        => $request->status,
            ]);

            return redirect()->route('hospital/list/page')->with('success', 'Hospital added successfully.');
        } catch (\Exception $e) {
            Log::error('Add Hospital Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to add hospital.');
        }
    }

    /** Edit page */
    public function editHospital($id)
    {
        $hospital = Hospitals::findOrFail($id);
        return view('hospitals.edit-hospital', compact('hospital'));
    }

    /** Update record */
    public function updateHospital(Request $request, $id)
    {
        $request->validate([
            'hospital_name' => 'required|string',
            'address'       => 'required|string',
            'country'       => 'required|string',
            'director'      => 'required|string',
            'status'        => 'required|in:active,inactive',
        ]);

        try {
            $hospital = Hospitals::findOrFail($id);
            $hospital->update([
                'hospital_name' => $request->hospital_name,
                'address'       => $request->address,
                'country'       => $request->country,
                'director'      => $request->director,
                'status'        => $request->status,
            ]);

            return redirect()->route('hospital/list/page')->with('success', 'Hospital updated successfully.');
        } catch (\Exception $e) {
            Log::error('Update Hospital Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to update hospital.');
        }
    }

    /** Delete record */
    public function deleteHospital($id)
    {
        try {
            Hospitals::destroy($id);
            return redirect()->route('hospital/list/page')->with('success', 'Hospital deleted.');
        } catch (\Exception $e) {
            Log::error('Delete Hospital Error: ' . $e->getMessage());
            return back()->with('error', 'Failed to delete hospital.');
        }
    }
}
