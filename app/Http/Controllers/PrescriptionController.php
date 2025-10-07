<?php

namespace App\Http\Controllers;

use App\Models\Clinic;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Prescription;
use App\Models\PrescriptionDetail;
use App\Models\Patient;
use App\Models\Medicine;
use App\Models\Dosage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;


class PrescriptionController extends Controller
{
    /**
     * Display a listing of the prescriptions.
     */
    public function index($patient_id)
    {
        $patient = Patient::where(['id' => $patient_id, 'clinic_id' => auth()->user()->clinic_id])->first();
       
        $prescriptions = Prescription::where(['patient_id' => $patient_id, 'clinic_id' => auth()->user()->clinic_id])->latest()->paginate(config('app.per_page'));

        return view('prescriptions.index', compact('patient', 'prescriptions'));
    }

    /**
     * Show the form for creating a new prescription.
     */
    public function create($patient_id)
    {
        //$patient = Patient::findOrFail($patient_id);
        $patient = Patient::where(['patient_id' => $patient_id, 'clinic_id' => auth()->user()->clinic_id]);
        $medicines = Medicine::where('clinic_id', auth()->user()->clinic_id)->get();
        $dosages = Dosage::where('clinic_id', auth()->user()->clinic_id)->get();

        return view('prescriptions.create', compact('patient', 'medicines', 'dosages'));
    }

    /**
     * Store a newly created prescription.
     */
    public function store(Request $request)
    {
        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'medicine_id' => 'required|array',
            'dosage_id' => 'required|array',
            'comments' => 'nullable|array', // Ensure comments is an array
        ]);

        // Create Prescription Entry
        $prescription = Prescription::create([
            'patient_id' => $request->patient_id,
            'clinic_id' => auth()->user()->clinic_id,
            'date' => now(),
            'gu_id' => Str::uuid(), // Generate a unique UUID
        ]);

        // Store Prescription Details
        foreach ($request->medicine_id as $index => $medicine_id) {
            PrescriptionDetail::create([
                'prescription_id' => $prescription->id,
                'patient_id' => $request->patient_id, // Added patient_id
                'medicine_id' => $medicine_id,
                'dosage_id' => $request->dosage_id[$index],
                'comments' => $request->comments[$index] ?? null, // Store comments if provided
                'days' => $request->days[$index] ?? null,
                'clinic_id' => auth()->user()->clinic_id,
                'medicine_qty' => $request->qtys[$index] ?? null,
            ]);
        }

        $this->sendWhatsAppPrescription($prescription);

        return redirect()->route('prescriptions.index', $request->patient_id)->with('success', 'Prescription added successfully.');
    }

    protected function sendWhatsAppPrescription($prescription)
    {
        $Patient = Patient::where('id', $prescription->patient_id)->first();
        $patientname = $Patient->name;

        $patientmobile = $Patient->mobile1;

        $Clinic = Clinic::where('clinic_id', $prescription->clinic_id)->first();
        $clinicname = $Clinic->name;


        $templateName = 'prescription__test3'; // name of your approved WhatsApp template
        $whatsappToken = 'EAATZAZAlCLXjEBO3L964MCRbqZA8kRj95hjONF6DRUaZCkd3bk2LzHvKbvV72eZCqMEOjm9pVaEG9ZCvFd2m1GsxFkysBQPXYmVbE7HSVdrrut3PijBInprtr4KTwvPGbQw0b2AHlIpfgGyeKSOosoc05ztRw8W1y0hlZC84U4ZAW31CikzFYNjtKyc2FgQ03wqi4QZDZD'; // Replace with your WhatsApp API token
        $fromPhoneNumberId = '658603253999245'; // Replace with your phone number ID

        $guId = $prescription->gu_id;
        $prescription = Prescription::with('patient', 'prescriptionDetails.medicine', 'prescriptionDetails.dosage')
            ->where('gu_id', $guId)
            ->firstOrFail();

        $pdf = Pdf::loadView('prescriptions.guid_pdf', compact('prescription'));

        $url = "https://graph.facebook.com/v19.0/{$fromPhoneNumberId}/messages";

        $response = Http::withToken($whatsappToken)->post($url, [
            'messaging_product' => 'whatsapp',
            'to' => $patientmobile,
            'type' => 'template',
            'template' => [
                'name' => $templateName,
                'language' => ['code' => 'en'],
                'components' => [
                    [
                        'type' => 'body',
                        'parameters' => [
                            ['type' => 'text', 'text' => $patientname],       // {{1}} - patient name
                            ['type' => 'text', 'text' => $clinicname],        // {{2}} - clinic name
                            ['type' => 'text', 'text' => $clinicname],        // {{3}} - again clinic name
                        ],
                    ],
                    [
                        'type' => 'button',
                        'sub_type' => 'url',
                        'index' => 0,
                        'parameters' => [
                            ['type' => 'text', 'text' => $guId], // {{4}} in button URL
                        ]
                    ]
                ],
            ],
        ]);
        if ($response->failed()) {
            Log::error('WhatsApp message failed to send', ['response' => $response->body()]);
        }
    }



    public function edit($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patient = $prescription->patient;
        $medicines = Medicine::where('clinic_id', auth()->user()->clinic_id)->get();
        $dosages = Dosage::where('clinic_id', auth()->user()->clinic_id)->get();
        $prescriptionDetails = PrescriptionDetail::where('prescription_id', $id)->get();

        return view('prescriptions.edit', compact('prescription', 'patient', 'medicines', 'dosages', 'prescriptionDetails'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'medicine_id' => 'required|array',
            'dosage_id' => 'required|array',
            'comments' => 'nullable|array',
            'detail_id' => 'nullable|array', // Track existing prescription details
            'delete_ids' => 'nullable|array', // Track items to be deleted
        ]);

        $prescription = Prescription::findOrFail($id);

        // Update Prescription Date
        $prescription->update([
            'date' => now(),
        ]);

        // Delete removed items
        if (!empty($request->delete_ids)) {
            PrescriptionDetail::whereIn('id', array_filter($request->delete_ids))->delete();
        }

        // Track updated IDs
        $updatedIds = [];

        foreach ($request->medicine_id as $index => $medicine_id) {
            $detailId = $request->detail_id[$index] ?? null;

            if ($detailId) {
                // Update existing line item
                PrescriptionDetail::where('id', $detailId)->update([
                    'medicine_id' => $medicine_id,
                    'dosage_id' => $request->dosage_id[$index],
                    'comments' => $request->comments[$index] ?? null,
                ]);
                $updatedIds[] = $detailId;
            } else {
                // Insert new line item
                $newDetail = PrescriptionDetail::create([
                    'prescription_id' => $prescription->id,
                    'patient_id' => $prescription->patient_id,
                    'medicine_id' => $medicine_id,
                    'dosage_id' => $request->dosage_id[$index],
                    'comments' => $request->comments[$index] ?? null,
                    'days' => $request->days[$index] ?? null,
                    'medicine_qty' => $request->qtys[$index] ?? null,
                ]);
                $updatedIds[] = $newDetail->id;
            }
        }



        return redirect()->route('prescriptions.index', $prescription->patient_id)
            ->with('success', 'Prescription updated successfully.');
    }


    public function downloadPDF($id)
    {
        $prescription = Prescription::with('patient', 'prescriptionDetails.medicine', 'prescriptionDetails.dosage')->findOrFail($id);

        // Load the PDF view
        $pdf = Pdf::loadView('prescriptions.pdf', compact('prescription'));

        // Open PDF in a new tab
        return $pdf->stream('Prescription_' . $prescription->id . '.pdf');
    }

    public function downloadPDFByGUID($gu_id)
    {
        // Find prescription by GUID instead of ID

        $prescription = Prescription::with('patient', 'prescriptionDetails.medicine', 'prescriptionDetails.dosage')
            ->where('gu_id', $gu_id)
            ->firstOrFail();

        // Load the PDF view
        $pdf = Pdf::loadView('prescriptions.guid_pdf', compact('prescription'));

        // Stream the PDF
        return $pdf->stream('Prescription_' . $prescription->id . '.pdf');
    }


    /**
     * Remove the specified prescription.
     */
    public function destroy($id)
    {
        $prescription = Prescription::findOrFail($id);
        $patient_id = $prescription->patient_id;

        // Delete Prescription Details First
        $prescription->prescriptionDetails()->delete();
        $prescription->delete();

        return redirect()->route('prescriptions.index', $patient_id)->with('success', 'Prescription deleted successfully.');
    }

    public function get_dosages(Request $request, $id)
    {
        $medicine = Medicine::find($id);

        if (!$medicine) {
            return response()->json([]);
        }

        $selectedDosage = Dosage::find($medicine->dosage_id);

        //$selectedDosage = Dosage::where('id', $medicine->dosage_id)->first();

        //$allDosages = Dosage::orderBy('dosage')->get(['id', 'dosage']); // get all
        $allDosages = Dosage::where('clinic_id', auth()->user()->clinic_id)
            ->orderBy('dosage')
            ->get(['id', 'dosage']);


        return response()->json([
            'selected_dosage_id' => $selectedDosage->id ?? null,
            'comment' => $medicine->comment ?? '',
            'days' => $medicine->days ?? 1,
            'dosages' => $allDosages,
        ]);
    }
}
