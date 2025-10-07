<?php

namespace App\Http\Controllers;

use PDF; // Import PDF at the top
use Illuminate\Http\Request;
use App\Models\Concerform;
use App\Models\PatientConcerform;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\PatientTreatment;
use Illuminate\Support\Str;


class PatientConcernFormController extends Controller
{
    public function index($patient_id)
    {
        //dd($patient_id);
        $patientconcernforms = PatientConcerform::with('concerform')->where(['patient_id' => $patient_id, 'clinic_id' => auth()->user()->clinic_id])->paginate(config('app.per_page'));

        $patient = Patient::findOrFail($patient_id);
        $Concerforms = Concerform::where('clinic_id', auth()->user()->clinic_id)->get();
        $clinic_id = auth()->user()->clinic_id;

        return view('patient_concerform.index', compact('patientconcernforms', 'patient', 'Concerforms', 'clinic_id'));
    }


    public function store(Request $request)
    {

        $quotation = PatientConcerform::create([
            'patient_id' => $request->patient_id,
            'clinic_id' => $request->clinic_id,
            'concern_form_id' => $request->concerform_id,
            'gu_id' => Str::uuid(),
            'created_at' => now(),
        ]);

        return redirect()->route('patientconcernform.index', $request->patient_id)->with('success', 'concerform Add successfully.');
    }
}
