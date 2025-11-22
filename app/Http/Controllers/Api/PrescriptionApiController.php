<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Hash;
use App\Models\Prescription;
use App\Models\PatientTreatment;
use App\Models\PrescriptionDetail;
use App\Models\User;
use App\Models\Patient;
use App\Models\Document;
use GuzzleHttp\Client;
use App\Models\Clinic;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;


class PatientApiController extends Controller

// class DriverApiController extends PushNotificationController
{


    public function Patientlist(Request $request)
    {
        try {
            $request->validate([
                'clinic_id' => 'required|numeric'
            ]);

            $listOfPatient = Patient::select(
                "id",
                "name",
                "mobile1",
                "dob",
                "address",
                "pincode",
                "reference_by",
                "case_no",
                "mobile2",
                "gender",
                "clinic_id",
            )->orderBy('id', 'asc')->where('clinic_id', $request->clinic_id)->get();
            return response()->json([
                'success' => true,
                'message' => "successfully fetched Patientlist...",
                'data' => $listOfPatient,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function AddPatient(Request $request)
    {

        try {
            // Validate the incoming request

            $request->validate([
                'name' => 'required',
                'mobile1' => [
                    'required',
                    'digits:10',
                    Rule::unique('patients')->where(function ($query) use ($request) {
                        return $query->where('clinic_id', $request->clinic_id);
                    }),
                ],
                // 'mobile2' => [
                //     'digits:10',
                //     Rule::unique('patients')->where(function ($query) use ($request) {
                //         return $query->where('clinic_id', $request->clinic_id);
                //     }),
                // ],
                //'dob' => 'required',
                'address' => 'required',
                'pincode' => 'required',
                'clinic_id' => 'required',
                'case_no' => 'required',
                'gender' => 'required',


            ], [
                'mobile1.unique' => 'This Mobile 1 Number already exists for this clinic.',
                //'mobile2.unique' => 'This Mobile 2  Number already exists for this clinic.',

            ]);

            Patient::create([
                'name' => $request->name,
                'mobile1' => $request->mobile1,
                'mobile2' => $request->mobile2 ?? 0,
                'dob' => $request->dob,
                'address' => $request->address,
                'pincode' => $request->pincode,
                'reference_by' => $request->reference_by,
                'case_no' => $request->case_no,
                'gender' => $request->gender,
                'clinic_id' => $request->clinic_id,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Patient Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function PatientUpdate(Request $request)
    {
        try {
            $request->validate([

                'name' => 'required',
                'mobile1' => 'required',
                'mobile2' => 'required',
                'dob' => 'required',
                'address' => 'required',
                'pincode' => 'required',
                'reference_by' => 'required|string',
                'clinic_id' => 'required',
                'case_no' => 'required',
                'gender' => 'required',
                'id' => 'required',
            ]);

            // Find the vendor by vendor_id
            $Patient = Patient::find($request->id);
            if ($Patient) {
                // Update the vendor's address and mobile
                $Patient->update([
                    'name' => $request->name,
                    'mobile1' => $request->mobile1,
                    'mobile2' => $request->mobile2,
                    'dob' => $request->dob,
                    'address' => $request->address,
                    'pincode' => $request->pincode,
                    'reference_by' => $request->reference_by,
                    'case_no' => $request->case_no,
                    'gender' => $request->gender,


                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Patient updated successfully.',
                    'data' => $Patient,  // Return the updated vendor data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Patient: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function Patientdelete(Request $request)
    {
        try {
            $request->validate([

                "id" => 'required'
            ]);
            $Patient = Patient::find($request->id);


            if ($Patient) {
                $Patient->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Patient Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    // tab view start

    //Patient Treatment
    public function PatientTreatmentlist(Request $request)
    {
        try {
            $request->validate([
                'patient_id' => 'required|numeric',
                'clinic_id' => 'required|numeric',
            ]);

            $listOftreatment = PatientTreatment::with(['treatment', 'doctor'])
                ->where(['patient_id' => $request->patient_id, 'clinic_id' => $request->clinic_id])
                ->orderBy('created_at', 'desc')
                ->get();

            // Group treatments by date
            $groupedData = $listOftreatment->groupBy(function ($item) {
                return $item->created_at->toDateString(); // Group by date only
            });

            // Transform grouped data into desired format
            $formattedData = $groupedData->map(function ($items, $key) {
                return [
                    'date' => $key,
                    'treatments' => $items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'patient_id' => $item->patient_id,
                            'treatment_id' => $item->treatment_id,
                            'doctor_id' => $item->doctor_id,
                            'tooth_selection' => $item->tooth_selection,
                            'is_billed' => $item->is_billed,
                            'is_quotation_billed' => $item->is_quotation_billed,
                            'quotation_give' => $item->quotation_give,
                            'rate' => $item->rate,
                            'qty' => $item->qty,
                            'amount' => $item->amount,
                            'treatment' => $item->treatment,
                            'doctor' => $item->doctor,
                        ];
                    }),
                ];
            })->values(); // Reset keys to start from 0

            return response()->json([
                'success' => true,
                'message' => "Successfully fetched Patient Treatmentlist grouped by date.",
                'data' => $formattedData,
            ], 200);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function AddPatientTreatment(Request $request)
    {
        try {

            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'clinic_id' => 'required|exists:clinic,clinic_id',
                'treatment_id' => 'required|exists:treatments,id',
                'doctor_id' => 'required|exists:doctors,id',
                'tooth_selection' => 'required|string',
                'rate' => 'required|numeric',
            ]);

            $qty = $request->tooth_selection ? count(explode(',', $request->tooth_selection)) : 0;
            $amount = $request->rate * $qty;

            PatientTreatment::create([
                'patient_id' => $request->patient_id,
                'clinic_id' => $request->clinic_id,
                'treatment_id' => $request->treatment_id,
                'doctor_id' => $request->doctor_id,
                'tooth_selection' => $request->tooth_selection,
                'rate' => $request->rate,
                'qty' => $qty,
                'amount' => $amount,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Patient Treatment Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function PatientTreatmentdelete(Request $request)
    {
        try {
            $request->validate([
                "id" => 'required'
            ]);
            $treatment = PatientTreatment::findOrFail($request->id);

            if ($treatment) {
                // Delete the deal option
                $treatment->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Patient Treatment Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient Treatment not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    //Patient Prescription
    public function patient_prescription_list(Request $request)
    {
        try {
            $request->validate([
                'patient_id' => 'required|numeric',
                'clinic_id' => 'required|numeric',
            ]);

            $listOfprescription = Prescription::select(
                'prescriptions.id',
                'prescription_details.id as prescription_details_id',
                'prescriptions.date',
                'prescription_details.medicine_id',
                'prescription_details.dosage_id',
                'prescription_details.comments',
                'prescription_details.duration',
                'medicines.medicine_name',
                'dosages.dosage',
            )
                ->where([
                    'prescriptions.patient_id' => $request->patient_id,
                    'prescriptions.clinic_id' => $request->clinic_id
                ])
                ->join('prescription_details', 'prescription_details.prescription_id', '=', 'prescriptions.id')
                ->join('medicines', 'medicines.id', '=', 'prescription_details.medicine_id')
                ->join('dosages', 'dosages.id', '=', 'prescription_details.dosage_id')
                ->orderBy('prescriptions.date', 'desc')
                ->get();

            // Group prescriptions by date (as string)
            $groupedData = $listOfprescription->groupBy(function ($item) {
                return \Carbon\Carbon::parse($item->date)->toDateString(); // Group by 'YYYY-MM-DD'
            });

            // Format and structure the data
            $formattedData = $groupedData->map(function ($items, $key) {
                return [
                    'date' => \Carbon\Carbon::parse($key)->format('d-m-Y'),
                    'treatments' => $items->map(function ($item) {
                        return [
                            'id' => $item->id,
                            'prescription_details_id' => $item->prescription_details_id,
                            'date' => \Carbon\Carbon::parse($item->date)->format('d-m-Y'),
                            'medicine_id' => $item->medicine_id,
                            'medicine_name' => $item->medicine_name,
                            'dosage_id' => $item->dosage_id,
                            'dosage' => $item->dosage,
                            'comments' => $item->comments,
                            'duration' => $item->duration,
                        ];
                    }),
                ];
            })->values(); // Reset array keys

            return response()->json([
                'success' => true,
                'message' => 'Successfully fetched Patient Prescription grouped by date.',
                'data' => $formattedData,
            ]);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function patient_prescription_add(Request $request)
    {
        try {

            $request->validate([
                'patient_id' => 'required|exists:patients,id',
                'clinic_id' => 'required|exists:clinic,clinic_id',
                'medicine_id' => 'required',
                'dosage_id' => 'required',
                'duration' => 'required',
                'comments' => 'nullable',
            ]);

            // Check if a prescription already exists for the patient & clinic today
            $prescription = Prescription::where('patient_id', $request->patient_id)
                ->where('clinic_id', $request->clinic_id)
                ->whereDate('date', now()->toDateString())
                ->first();

            if (!$prescription) {
                // Create new prescription if not found
                $prescription = Prescription::create([
                    'patient_id' => $request->patient_id,
                    'clinic_id' => $request->clinic_id,
                    'date' => now(),
                    'gu_id' => Str::uuid(),
                ]);

                $sendWhatsApp = true;
            } else {
                $sendWhatsApp = false; // Already exists, no need to send again
            }

            PrescriptionDetail::create([
                'prescription_id' => $prescription->id,
                'patient_id' => $request->patient_id, // Added patient_id
                'medicine_id' => $request->medicine_id,
                'duration' => $request->duration,
                'dosage_id' => $request->dosage_id,
                'comments' => $request->comments, // Store comments if provided
            ]);

            // Send WhatsApp only if new prescription created
            if ($sendWhatsApp) {
                $this->sendWhatsAppPrescription($prescription);
            }

            return response()->json([
                'success' => true,
                'message' => 'Patient Prescription Added Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    protected function sendWhatsAppPrescription($prescription)
    {
        $Patient = Patient::where('id', $prescription->patient_id)->first();
        $patientname = $Patient->name;

        $patientmobile = $Patient->mobile1;

        $Clinic = Clinic::where('clinic_id', $prescription->clinic_id)->first();
        $clinicname = $Clinic->name;


        $templateName = 'prescription__test11'; // name of your approved WhatsApp template
        $whatsappToken = 'EAATZAZAlCLXjEBO3L964MCRbqZA8kRj95hjONF6DRUaZCkd3bk2LzHvKbvV72eZCqMEOjm9pVaEG9ZCvFd2m1GsxFkysBQPXYmVbE7HSVdrrut3PijBInprtr4KTwvPGbQw0b2AHlIpfgGyeKSOosoc05ztRw8W1y0hlZC84U4ZAW31CikzFYNjtKyc2FgQ03wqi4QZDZD'; // Replace with your WhatsApp API token
        $fromPhoneNumberId = '658603253999245'; // Replace with your phone number ID

        $guId = $prescription->gu_id;

        //$prescriptionPdfUrl = "http://127.0.0.1:8000/admin/prescriptions/pdf/{$guId}";

        $prescription = Prescription::with('patient', 'prescriptionDetails.medicine', 'prescriptionDetails.dosage')
            ->where('gu_id', $guId)
            ->firstOrFail();

        // Load the PDF view
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

    public function patient_prescription_delete(Request $request)
    {
        try {
            $request->validate([
                "id" => 'required|exists:prescriptions,id'
            ]);
            $prescription = Prescription::findOrFail($request->id);
            PrescriptionDetail::where('prescription_id', $prescription->id)->delete();
            $prescription->delete();

            return response()->json([
                'success' => true,
                'message' => 'Patient Prescription Deleted Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    // Patient Treatment Upload Document
    public function patient_treatment_document_list(Request $request)
    {
        try {

            $request->validate([
                'patient_id' => 'required|numeric',
                'clinic_id' => 'required|numeric',
            ]);

            $documents = Document::where([
                'patient_id' => $request->patient_id,
                'clinic_id' => $request->clinic_id
            ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($doc) {
                    return [
                        'id' => $doc->id ?? 0,
                        'patient_id' => $doc->patient_id ?? 0,
                        'treatment_id' => $doc->treatment_id ?? 0,
                        'patient_treatment_id' => $doc->patient_treatment_id ?? 0,
                        'clinic_id' => $doc->clinic_id ?? 0,
                        'comment' => $doc->comment,
                        'created_at' => $doc->created_at->format('d M Y, h:i A'),
                        'file_url' => $doc->document
                            ? url('D&D_DENTAL_CLINIC/documents/' . $doc->document)
                            : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Successfully fetched patient documents.',
                'data' => $documents,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function patient_treatment_add_document(Request $request)
    {
        try {
            $request->validate([
                'patient_id' => 'required|numeric',
                'clinic_id' => 'required|numeric',
                'document' => [
                    'required',
                    'file',
                    'mimes:jpg,png,pdf',
                    'max:5120'
                ],
                'comment' => 'nullable|string',
                'treatment_id' => 'nullable|exists:treatments,id',
                'patient_treatment_id' => 'nullable|exists:patient_treatments,id',
            ], [
                'document.mimes' => 'Allowed extensions are only .jpg, .png, .pdf',
                'document.max' => 'The file size should not exceed 5MB.',
            ]);

            $img = "";
            if ($request->hasFile('document')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('document');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $destinationpath = $root . '/D&D_DENTAL_CLINIC/documents/';
                if (!file_exists($destinationpath)) {
                    mkdir($destinationpath, 0755, true);
                }
                $image->move($destinationpath, $img);
            }

            Document::create([
                'patient_id' => $request->patient_id ?? 0,
                'treatment_id' => $request->treatment_id ?? 0,
                'patient_treatment_id' => $request->patient_treatment_id ?? 0,
                'document' => $img,
                'comment' => $request->comment,
                'clinic_id' => $request->clinic_id ?? 0,
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function patient_treatment_document_delete(Request $request)
    {
        try {
            $request->validate([
                "id" => 'required|exists:documents,id'
            ]);

            $document = Document::findOrFail($request->id);

            $root = $_SERVER['DOCUMENT_ROOT'];
            $filePath = $root . '/D&D_DENTAL_CLINIC/documents/' . $document->document;

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete the DB record
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    //Upload Document
    public function patient_document_list(Request $request)
    {
        try {


            $request->validate([
                'patient_id' => 'required|numeric',
                'clinic_id' => 'required|numeric',
            ]);


            $documents = Document::where([
                'patient_id' => $request->patient_id,
                'clinic_id' => $request->clinic_id
            ])
                ->orderBy('created_at', 'desc')
                ->get()
                ->map(function ($doc) {
                    return [
                        'id' => $doc->id ?? 0,
                        'patient_id' => $doc->patient_id ?? 0,
                        'treatment_id' => $doc->treatment_id ?? 0,
                        'patient_treatment_id' => $doc->patient_treatment_id ?? 0,
                        'clinic_id' => $doc->clinic_id ?? 0,
                        'comment' => $doc->comment,
                        'created_at' => $doc->created_at->format('d M Y, h:i A'),
                        'file_url' => $doc->document
                            ? url('D&D_DENTAL_CLINIC/documents/' . $doc->document)
                            : null,
                    ];
                });

            return response()->json([
                'success' => true,
                'message' => 'Successfully fetched patient documents.',
                'data' => $documents,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function patient_add_document(Request $request)
    {
        try {
            $request->validate([
                'patient_id' => 'required|numeric',
                'clinic_id' => 'required|numeric',
                'document' => [
                    'required',
                    'file',
                    'mimes:jpg,png,pdf',
                    'max:5120'
                ],
                'comment' => 'nullable|string'
            ], [
                'document.mimes' => 'Allowed extensions are only .jpg, .png, .pdf',
                'document.max' => 'The file size should not exceed 5MB.',
            ]);

            $img = "";
            if ($request->hasFile('document')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('document');
                $img = time() . '.' . $image->getClientOriginalExtension();
                $destinationpath = $root . '/D&D_DENTAL_CLINIC/documents/';
                if (!file_exists($destinationpath)) {
                    mkdir($destinationpath, 0755, true);
                }
                $image->move($destinationpath, $img);
            }
            Document::create([
                'patient_id' => $request->patient_id ?? 0,
                'document' => $img,
                'comment' => $request->comment,
                'clinic_id' => $request->clinic_id ?? 0,
                'created_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document uploaded successfully.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred.',
                'error' => $th->getMessage()
            ], 500);
        }
    }

    public function patient_document_delete(Request $request)
    {
        try {
            $request->validate([
                "id" => 'required|exists:documents,id'
            ]);

            $document = Document::findOrFail($request->id);

            $root = $_SERVER['DOCUMENT_ROOT'];
            $filePath = $root . '/D&D_DENTAL_CLINIC/documents/' . $document->document;

            if (file_exists($filePath)) {
                unlink($filePath);
            }

            // Delete the DB record
            $document->delete();

            return response()->json([
                'success' => true,
                'message' => 'Document deleted successfully.',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
