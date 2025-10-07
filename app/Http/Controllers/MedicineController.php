<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Medicine;
use App\Models\Dosage;

use Illuminate\Validation\Rule;

class MedicineController extends Controller
{
    public function index()
    {
        $dosages = Dosage::where(['clinic_id' => auth()->user()->clinic_id])->get();
        $medicines = Medicine::with('Dosage')->where(['clinic_id' => auth()->user()->clinic_id])->paginate(config('app.per_page'));
        return view('medicines.index', compact('medicines', 'dosages'));
    }

    public function store(Request $request)
    {
        $request->validate(
            [
                'medicine_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('medicines')->where(function ($query) {
                        return $query->where('clinic_id', auth()->user()->clinic_id);
                    }),
                ]
            ],

            [
                'medicine_name.unique' => 'This medicine already exists.',
            ]
        );

        $data = $request->all();
        $data['clinic_id'] = auth()->user()->clinic_id;

        Medicine::create($data);


        return redirect()->back()->with('success', 'Medicine added successfully!');
    }



    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'medicine_name' => [
                    'required',
                    'string',
                    'max:255',
                ],
            ],

        );

        $medicine = Medicine::findOrFail($id);
        $medicine->update($request->all());

        return redirect()->back()->with('success', 'Medicine updated successfully!');
    }

    public function destroy(Request $request, $id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->delete();

        return redirect()->back()->with('success', 'Medicine deleted successfully!');
    }
}
