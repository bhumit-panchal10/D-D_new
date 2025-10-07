<?php

namespace App\Http\Controllers;

use App\Models\Labwork;
use App\Models\Lab;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\PatientTreatment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;


class LabworkController extends Controller
{
    public function index(Request $request)
    {
        $patient_id = $request->patient_id;
        $patient = $patient_id ? Patient::findOrFail($patient_id) : null;
        $labs = Lab::where('clinic_id', auth()->user()->clinic_id)->get();
        $treatments = Treatment::where('clinic_id', auth()->user()->clinic_id)->get();
        $patientTreatments = PatientTreatment::where('clinic_id', auth()->user()->clinic_id)->get();
        $labworks = Labwork::where(['patient_id' => $patient_id, 'clinic_id' => auth()->user()->clinic_id])->paginate(config('app.per_page'));

        return view('labworks.index', compact('patient', 'labs', 'treatments', 'patientTreatments', 'labworks'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'lab_id' => 'required|exists:labs,id',
            'treatment_id' => 'nullable|exists:treatments,id',
            'patient_treatment_id' => 'nullable|exists:patient_treatments,id',
            'entry_date' => 'required|date',
            'comment' => 'nullable|string|max:255', // Now it's optional
        ]);

        // Create Labwork record
        $Labwork = Labwork::create([
            'patient_id' => $request->patient_id,
            'lab_id' => $request->lab_id,
            'treatment_id' => $request->treatment_id,
            'patient_treatment_id' => $request->patient_treatment_id,
            'entry_date' => $request->entry_date,
            'comment' => $request->comment,  // Save the comment
            'clinic_id' => auth()->user()->clinic_id,

        ]);
        $labid = $Labwork->lab_id;
        $lab_works = Labwork::with('lab')->where(['lab_id' => $labid, 'clinic_id' => auth()->user()->clinic_id])->first();
        $labname = $lab_works->lab->lab_name;
        $patientid = $Labwork->patient_id;
        $labworks = Labwork::with('clinic', 'patient')->where(['patient_id' => $patientid, 'clinic_id' => auth()->user()->clinic_id])->first();
        $clinicname = $labworks->clinic->name ?? '';
        $clinicmobile = $labworks->clinic->mobile_no ?? '';
        $patientmobile = $labworks->patient->mobile1 ?? '';


        // WhatsApp API credentials
        $whatsappToken = 'EAATZAZAlCLXjEBO3L964MCRbqZA8kRj95hjONF6DRUaZCkd3bk2LzHvKbvV72eZCqMEOjm9pVaEG9ZCvFd2m1GsxFkysBQPXYmVbE7HSVdrrut3PijBInprtr4KTwvPGbQw0b2AHlIpfgGyeKSOosoc05ztRw8W1y0hlZC84U4ZAW31CikzFYNjtKyc2FgQ03wqi4QZDZD'; // Replace with your WhatsApp API token
        $phoneNumberId = '658603253999245'; // Replace with your phone number ID

        // WhatsApp message data
        $name = $labname;

        $clinicName = $clinicname; // Or get from auth()->user()->clinic->name
        $contactNumber =  $clinicmobile;
        $recipient = '+91' . ($patientmobile ?? '7486984607'); // Fallback if not set

        try {
            $response = Http::withToken($whatsappToken)->post("https://graph.facebook.com/v19.0/{$phoneNumberId}/messages", [
                "messaging_product" => "whatsapp",
                "to" => $recipient,
                "type" => "template",
                "template" => [
                    "name" => "labwork_collection",
                    "language" => [
                        "code" => "en"
                    ],
                    "components" => [
                        [
                            "type" => "header",
                            "parameters" => [
                                ["type" => "text", "text" => $name]
                            ]
                        ],
                        [
                            "type" => "body",
                            "parameters" => [
                                ["type" => "text", "text" => $clinicName],
                                ["type" => "text", "text" => $contactNumber]
                            ]
                        ]
                    ]
                ]
            ]);

            if (!$response->successful()) {
                Log::error('WhatsApp API Error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);

                return back()->with('error', 'WhatsApp API Error: ' . json_encode($response->json()));
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API Exception', [
                'message' => $e->getMessage()
            ]);

            return back()->with('error', 'Exception: ' . $e->getMessage());
        }


        // Redirect back to the labwork index with success message
        return redirect()->route('labworks.index', ['patient_id' => $request->patient_id])
            ->with('success', 'Labwork added successfully.');
    }


    public function markCollected($id)
    {
        $labwork = Labwork::findOrFail($id);
        $labwork->collection_date = now();
        $labwork->save();

        return redirect()->route('labworks.full_list')
            ->with('success', 'Labwork marked as collected.');
    }

    public function markReceived($id)
    {
        $labwork = Labwork::findOrFail($id);
        $labwork->received_date = now();
        $labwork->save();

        return redirect()->route('labworks.full_list')
            ->with('success', 'Labwork marked as received.');
    }
    public function fullList(Request $request)
    {
        $labworks = Labwork::query();

        if ($request->filter == 'pending_collection') {
            $labworks->whereNull('collection_date'); // Only show labwork pending collection
        } elseif ($request->filter == 'pending_received') {
            $labworks->whereNotNull('collection_date')
                ->whereNull('received_date'); // Only show collected but not received labwork
        }

        $labworks = $labworks->where('clinic_id', auth()->user()->clinic_id)->paginate(10);

        return view('labworks.full_list', compact('labworks'));
    }

    public function destroy($id)
    {
        $labwork = Labwork::findOrFail($id);
        $patient_id = $labwork->patient_id;
        $labwork->delete();

        return redirect()->route('labworks.index', ['patient_id' => $patient_id])
            ->with('success', 'Labwork deleted successfully.');
    }
}
