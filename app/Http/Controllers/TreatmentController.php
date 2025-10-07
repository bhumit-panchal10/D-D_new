<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Treatment;
use Illuminate\Validation\Rule;


class TreatmentController extends Controller
{
    public function index(Request $request)
    {
        $treatments = Treatment::where(['clinic_id' => auth()->user()->clinic_id])
            ->orderBy('id', 'desc')->paginate(config('app.per_page'));

        $editTreatment = null;

        if ($request->has('edit')) {
            $editTreatment = Treatment::find($request->edit);
        }

        return view('treatments.index', compact('treatments', 'editTreatment'));
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'treatment_name' => 'required|string|max:255|unique:treatments,treatment_name',
        //     'type' => 'required',
        //     'lab_work' => 'required|in:yes,no',

        // ], [
        //     'treatment_name.unique' => 'This treatment already exists.',
        // ]);
        
        $request->validate([
        'treatment_name' => [
            'required',
            'string',
            'max:255',
            Rule::unique('treatments')->where(function ($query) use ($request) {
                return $query->where('clinic_id', auth()->user()->clinic_id);
            }),
        ],
            'type' => 'required',
            'lab_work' => 'required|in:yes,no',
        ], [
            'treatment_name.unique' => 'This treatment already exists for your clinic.',
        ]);

        $data = $request->all();
        $data['clinic_id'] = auth()->user()->clinic_id;

        Treatment::create($data);

        return redirect()->route('treatment.index')->with('success', 'Treatment added successfully!');
    }

     public function update(Request $request, $id)
    {
        $request->validate([
            'treatment_name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('treatments')
                    ->ignore($id) // Ignore the current record
                    ->where(function ($query) {
                        return $query->where('clinic_id', auth()->user()->clinic_id);
                    }),
            ],
            'type' => 'required',
            'lab_work' => 'required|in:yes,no',
        ], [
            'treatment_name.unique' => 'This treatment already exists for your clinic.',
        ]);
    
        $treatment = Treatment::findOrFail($id);
        $treatment->update($request->all());
    
        return redirect()->route('treatment.index', ['page' => $request->query('page', 1)])
            ->with('success', 'Treatment updated successfully!');
    }


    public function destroy(Request $request, $id)
    {
        $treatment = Treatment::findOrFail($id);
        $treatment->delete();


        return redirect()->route('treatment.index', ['page' => $request->query('page', 1)])
            ->with('success', 'Treatment deleted successfully!');
    }
}
