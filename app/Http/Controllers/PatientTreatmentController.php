<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Patient;
use App\Models\PatientTreatment;
use App\Models\Treatment;
use App\Models\Lab;
use Illuminate\Http\Request;
use App\Models\PatientTreatmentDocument;


class PatientTreatmentController extends Controller
{
    /**
     * Display a listing of the patient treatments for a specific patient.
     */
    public function index($id)
    {
        $patient = Patient::findOrFail($id); // Fetch patient details
        $patientTreatments = PatientTreatment::with(['treatment', 'doctor'])
            ->where(['patient_id' => $id, 'clinic_id' => auth()->user()->clinic_id])
            ->orderBy('created_at', 'desc') // Order by latest entry
            ->paginate(config('app.per_page'));

        $labs = Lab::where('clinic_id', auth()->user()->clinic_id)->get();
        $treatments = Treatment::where('clinic_id', auth()->user()->clinic_id)->get();

        return view('patient_treatments.index', compact('patient', 'labs', 'patientTreatments', 'treatments'));
    }


    public function create($id)
    {
        $patient = Patient::findOrFail($id);
        $treatments = Treatment::where('clinic_id', auth()->user()->clinic_id)->get();
        $doctors = Doctor::where('clinic_id', auth()->user()->clinic_id)->get();

        return view('patient_treatments.create', compact('patient', 'treatments', 'doctors'));
    }


    /**
     * Store a newly created patient treatment in storage.
     */
    public function store(Request $request, $id)
    {
        $request->validate([
            'treatment_id' => 'required|exists:treatments,id',
            //'doctor_id' => 'required|exists:doctors,id',
            'tooth_selection' => 'required|string',
            'rate' => 'required|numeric',
        ]);
        $qty = $request->tooth_selection ? count(explode(',', $request->tooth_selection)) : 0;
        $amount = $request->rate * $qty;

        //dd($request);
        PatientTreatment::create([
            'patient_id' => $id,
            'treatment_id' => $request->treatment_id,
            //'doctor_id' => $request->doctor_id ?? '',
            'tooth_selection' => $request->tooth_selection,
            'rate' => $request->rate,
            'qty' => $qty,
            'amount' => $amount,
            'clinic_id' => auth()->user()->clinic_id,

        ]);

        return redirect()->route('patient_treatments.index', $id)->with('success', 'Patient Treatment added successfully.');
    }

    /**
     * Remove the specified patient treatment from storage.
     */
    public function destroy($id)
    {

        $root = $_SERVER['DOCUMENT_ROOT'];

        $multiDocuments = PatientTreatmentDocument::with('patientTreatment')->where('patient_treatment_id', $id)->get();

        foreach ($multiDocuments as $multidoc) {
            $patientTreatment = $multidoc->patientTreatment;

            if ($patientTreatment) {
                $createdDate = $patientTreatment->created_at->format('Y/m/d'); // e.g. 2025/06/27
                $multiFilePath = $root . '/dental_clinic_new/patient_treatments/' . $createdDate . '/' . $patientTreatment->id . '/' . $multidoc->document;

                if (file_exists($multiFilePath)) {
                    unlink($multiFilePath);
                }
            }

            $multidoc->delete();
        }
        $treatment = PatientTreatment::findOrFail($id);
        $treatment->delete();

        return redirect()->back()->with('success', 'Patient treatment deleted successfully.');
    }
}
