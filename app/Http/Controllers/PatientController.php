<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use App\Models\Patient;
use App\Models\ClinicCaseCounters;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;


class PatientController extends Controller
{
    // Display patients list

    public function autocomplete(Request $request)
    {
        $search = $request->search;

        $results = Patient::select('id', 'name', 'mobile1')
            ->where('name', 'like', "%{$search}%")
            ->orWhere('mobile1', 'like', "%{$search}%")
            ->orWhere('mobile2', 'like', "%{$search}%")
            ->orderBy('name')
            ->limit(10)
            ->get();

        return response()->json($results);
    }
    public function index(Request $request)
    {
        // dd($request);


        $query = Patient::query();

        if ($request->items) {
            $items = $request->items ?? 20;

            $minutes = 30 * 24 * 60 * 60;

            Cookie::forget('Pagination');
            Cookie::queue('Pagination', $items, $minutes);
            $get_all_cookies = $items;
        } else {
            $get_all_cookies = Cookie::get('Pagination');
        }


        // Search logic (by name or mobile)
        if ($request->filled('search')) {
            $searchTerm = $request->search;
            $query->where('name', 'like', "%{$searchTerm}%")
                ->orWhere('mobile2', 'like', "%{$searchTerm}%")
                ->orWhere('mobile1', 'like', "%{$searchTerm}%");
        }

        // Maintain search term in pagination links
        $items = $get_all_cookies;

        $patients = $query->where(['clinic_id' => auth()->user()->clinic_id])->orderBy('id', 'desc')->paginate($get_all_cookies)->appends(['search' => $request->search]);

        return view('patient.index', compact('patients', 'items'));
    }



    public function show($id)
    {
        //$patient = Patient::findOrFail($id);
        $patient = Patient::where('id', $id)
            ->where('clinic_id', auth()->user()->clinic_id)
            ->first();

        return view('patient.show', compact('patient'));
    }

    // Show create form
    public function create()
    {

        $caseMaster = ClinicCaseCounters::where('clinic_id', auth()->user()->clinic_id)->first();
        if (!$caseMaster) {
            return response()->json(['error' => 'Case master not configured.'], 400);
        }

        $caseno = ($caseMaster->prefix ?? '') . '-' .
            str_pad($caseMaster->last_number, 4, '0', STR_PAD_LEFT) . '-' .
            ($caseMaster->postfix ?? '');
        return view('patient.create', compact('caseno'));
    }

    public function fetchByMobile(Request $request)
    {
        $mobile = $request->input('mobile');
        $clinicId = auth()->user()->clinic_id;

        $patient = Patient::where('mobile1', $mobile)
            ->where('clinic_id', $clinicId)
            ->first();

        if ($patient) {
            return response()->json(['exists' => true, 'patient' => $patient]);
        }

        return response()->json(['exists' => false]);
    }

    // Store patient data
    public function store(Request $request)
    {
        $clinicId = auth()->user()->clinic_id;
        $request->validate([
            'case_no' => 'required',
            'name' => 'required|string|max:30',
            'mobile1' => 'required',
            'mobile2' => 'required',
            'dob' => 'nullable|date',
            'gender' => 'required',
            'address' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|size:6',
            'reference_by' => 'nullable|string|max:30',
        ]);


        $caseMaster = ClinicCaseCounters::where('clinic_id', auth()->user()->clinic_id)->first();
        if (!$caseMaster) {
            return redirect()->back()->withErrors(['error' => 'Case master not found.']);
        }
        $caseno = ($caseMaster->prefix ?? '') . '-' .
            str_pad($caseMaster->last_number, 4, '0', STR_PAD_LEFT) . '-' .
            ($caseMaster->postfix ?? '');

        $data = $request->all();
        $data['clinic_id'] = auth()->user()->clinic_id;
        $data['case_no'] = $caseno;

        $patient = Patient::create($data);

        $caseMaster->last_number += 1;
        $caseMaster->save();

        $Clinic = Clinic::where('clinic_id', auth()->user()->clinic_id)->first();
        $clinic_Name = $Clinic->name ?? 'Dental Clinic';
        $mobileNo = $Clinic->mobile_no ?? '';

        // WhatsApp API credentials
        $whatsappToken = 'EAATZAZAlCLXjEBO3L964MCRbqZA8kRj95hjONF6DRUaZCkd3bk2LzHvKbvV72eZCqMEOjm9pVaEG9ZCvFd2m1GsxFkysBQPXYmVbE7HSVdrrut3PijBInprtr4KTwvPGbQw0b2AHlIpfgGyeKSOosoc05ztRw8W1y0hlZC84U4ZAW31CikzFYNjtKyc2FgQ03wqi4QZDZD'; // Replace with your WhatsApp API token
        $phoneNumberId = '658603253999245'; // Replace with your phone number ID

        // WhatsApp message data
        $name = $patient->name;
        $clinicName = $clinic_Name; // Or get from auth()->user()->clinic->name
        $contactNumber =  $mobileNo;

        Http::withToken($whatsappToken)->post("https://graph.facebook.com/v19.0/{$phoneNumberId}/messages", [
            "messaging_product" => "whatsapp",
            "to" => "+91" . $patient->mobile1,
            "type" => "template",
            "template" => [
                "name" => "thanks_for_first_visit1",
                "language" => [
                    "code" => "en_US"
                ],
                "components" => [
                    [
                        "type" => "body",
                        "parameters" => [
                            ["type" => "text", "text" => $name],
                            ["type" => "text", "text" => $clinicName],
                            ["type" => "text", "text" => $contactNumber],
                        ]
                    ]
                ]
            ]
        ]);



        return redirect()->route('patient.index')->with('success', 'Patient added successfully.');
    }

    // Show edit form
    public function edit(Patient $patient)
    {
        return view('patient.edit', compact('patient'));
    }

    // Update patient data
    public function update(Request $request, Patient $patient)
    {
        $request->validate([
            'case_no' => 'required',
            'name' => 'required|string|max:30',
            'mobile1' => 'required',
            'mobile2' => 'required',
            'dob' => 'nullable|date',
            'gender' => 'required',
            'address' => 'nullable|string|max:255',
            'pincode' => 'nullable|string|size:6',
            'reference_by' => 'nullable|string|max:30',
        ]);

        $patient->update($request->all());

        return redirect()->route('patient.index')->with('success', 'Patient updated successfully.');
    }

    // Delete patient data
    public function destroy(Patient $patient)
    {
        $patient->delete();
        return redirect()->route('patient.index')->with('success', 'Patient deleted successfully.');
    }
}
