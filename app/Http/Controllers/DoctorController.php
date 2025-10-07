<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;


class DoctorController extends Controller
{
    // Display doctor list
    public function index(Request $request)
    {
        $doctors = Doctor::where(['clinic_id' => auth()->user()->clinic_id])->orderBy('created_at', 'desc')->paginate(config('app.per_page')); // Show latest first

        $editDoctor = null; // Ensure editDoctor is null by default
        if ($request->has('edit')) {
            $editDoctor = Doctor::find($request->edit);
        }

        return view('doctor.index', compact('doctors', 'editDoctor'));
    }

    // Store or Update doctor data
    public function store(Request $request)
    {
        $request->validate([
            'doctor_name' => 'required|string|max:100',
            'mobile'      => [
                'required',
                'string',
                'size:10',
                Rule::unique('doctors')->ignore($request->id),
            ],
            'address'     => 'nullable|string|max:255',
            'pincode'     => 'nullable|string|size:6',
        ]);
        $data = $request->all();
        $data['clinic_id'] = auth()->user()->clinic_id;

        Doctor::updateOrCreate(
            ['id' => $request->id],
            $data
        );

        return redirect()->route('doctors.index', ['page' => $request->query('page', 1)])->with('success', 'Doctor details saved successfully.');
    }

    // Delete doctor data
    public function destroy($id)
    {
        $doctor = Doctor::findOrFail($id);
        $doctor->delete();
        return redirect()->route('doctors.index')->with('success', 'Doctor deleted successfully.');
    }
}
