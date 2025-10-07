<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Dosage;
use Illuminate\Validation\Rule;

class DosageController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $dosages = Dosage::where(['clinic_id' => auth()->user()->clinic_id])->latest()->paginate(config('app.per_page'));

        // Check if we are editing a dosage
        $editDosage = null;
        if ($request->has('edit')) {
            $editDosage = Dosage::findOrFail($request->edit);
        }

        return view('dosages.index', compact('dosages', 'editDosage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            
            'dosage' => [
            'required',
            'string',
            'max:255',
             Rule::unique('dosages')->where(function ($query) {
                return $query->where('clinic_id', auth()->user()->clinic_id);
            }),
          ],
            
        ], [
            'dosage.unique' => 'This dosage input already exists.',
        ]);

        Dosage::create([
            'dosage' => $request->dosage,
            'clinic_id' => auth()->user()->clinic_id,

        ]);

        return redirect()->route('dosage.index')->with('success', 'Dosage added successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'dosage' => [
            'required',
            'string',
            'max:255',
            Rule::unique('dosages')
                ->ignore($id) // Ignore the current record
                ->where(function ($query) {
                    return $query->where('clinic_id', auth()->user()->clinic_id);
                }),
            ],
            
        ], [
            'dosage.unique' => 'This dosage input already exists.',
        ]);

        $dosage = Dosage::findOrFail($id);
        $dosage->update([
            'dosage' => $request->dosage,
        ]);

        return redirect()->route('dosage.index', ['page' => $request->query('page', 1)])->with('success', 'Dosage updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, $id)
    {
        $dosage = Dosage::findOrFail($id);
        $dosage->delete();

        return redirect()->route('dosage.index', ['page' => $request->query('page', 1)])->with('success', 'Dosage deleted successfully.');
    }
}
