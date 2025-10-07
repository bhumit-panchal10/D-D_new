<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Concerform;
use App\Models\PatientConcerform;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\Clinic;
use App\Models\Patient;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

use Illuminate\Support\Facades\Storage;


use Carbon\Carbon;




class ConcerformController extends Controller
{


    public function index()
    {


        $Concerforms = Concerform::where('clinic_id', auth()->user()->clinic_id)->orderBy('iConcernFormId', 'desc')->paginate(config('app.per_page'));

        return view('ConcernForm.index', compact('Concerforms'));
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {


            $Concerform = Concerform::create([
                'strConcernFormTitle'    => $request->title,
                'strConcernFormText' => $request->text,
                'strIP' => $request->ip(),
                'clinic_id' => auth()->user()->clinic_id,

            ]);
            DB::commit();
            return redirect()->route('Consentform.index')->with('success', 'Concern Form Title Add Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function edit(Request $request, $id)
    {
        $Concerform = Concerform::where('iConcernFormId', $id)->first();

        return json_encode($Concerform);
    }


    public function update(Request $request)
    {

        DB::beginTransaction();

        try {

            $user_updated = Concerform::where('iConcernFormId', $request->concernid)->update([
                'strConcernFormTitle'    => $request->edittitle,
                'strConcernFormText'         => $request->text,
                'updated_at' => now(),

            ]);

            DB::commit();
            return redirect()->route('Consentform.index')->with('success', 'Concern Form Updated Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }

    public function thankyou()
    {
        return view('ConcernForm.thankyou');
    }



    public function delete($id)
    {
        try {
            Concerform::where('iConcernFormId', $id)->delete();

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => $th->getMessage()]);
        }
    }

    // public function concentform(Request $request, $patient_id, $iConcernFormId, $PatientsConcernFormId)
    // {
    //     $patientData = Patient::where('id', $patient_id)->first();
    //     $ConcernForm = Concerform::where('iConcernFormId', $iConcernFormId)->first();

    //     $patientNamePrefix = $patientData->name_prefix ?? "";
    //     $patientName = $patientData->name ?? "";
    //     $patientAddress = $patientData->address ?? "";
    //     $patientEmail = $patientData->email ?? "";
    //     $dateOfBirth = $patientData->dob ?? "";

    //     // 🧮 Age Calculation using Carbon
    //     $from = new \DateTime($dateOfBirth);
    //     $to   = new \DateTime('today');
    //     $ageYear =  $from->diff($to)->y;
    //     $ageMonth =  $from->diff($to)->m;
    //     $today = date('d-m-Y');
    //     $time = date('H:i');

    //     $age = $ageYear . " years";
    //     if ($ageYear == 0) {

    //         $age = $ageMonth . " months";
    //     }


    //     $patient[] = [
    //         "name_prefix" => $patientNamePrefix,
    //         "name" => $patientName,
    //         "address" => $patientAddress,
    //         "email" => $patientEmail,
    //         "date_of_birth" => $dateOfBirth,
    //         "age" => $age,
    //         "today" => $today,
    //         "time" => $time,
    //         "patient_id" => $patient_id
    //     ];

    //     $patient = compact('patient');

    //     // Make sure $PatientsConcernFormId exists or is handled properly
    //     return view('ConcernForm.Concentform', compact('patient', 'ConcernForm', 'PatientsConcernFormId'));
    // }

    public function concentform(Request $request, $gu_id)
    {


        $patientconcerform = PatientConcerform::where('gu_id', $gu_id)->first();
        $Clinic = Clinic::where('clinic_id', $patientconcerform->clinic_id)->first();

        $patientData = Patient::where('id', $patientconcerform->patient_id)->first();

        $PatientsConcernFormId = $patientconcerform->patient_concern_form_id;

        $ConcernForm = Concerform::where('iConcernFormId', $patientconcerform->concern_form_id)->first();

        $patientNamePrefix = $patientData->name_prefix ?? "";
        $patientName = $patientData->name ?? "";
        $patientMobile = $patientData->mobile1 ?? "";

        $patientAddress = $patientData->address ?? "";
        $patientEmail = $patientData->email ?? "";
        $dateOfBirth = $patientData->dob ?? "";

        // 🧮 Age Calculation using Carbon
        $from = new \DateTime($dateOfBirth);
        $to   = new \DateTime('today');
        $ageYear =  $from->diff($to)->y;
        $ageMonth =  $from->diff($to)->m;
        $today = date('d-m-Y');
        $time = date('H:i');

        $age = $ageYear . " years";
        if ($ageYear == 0) {

            $age = $ageMonth . " months";
        }


        $patient[] = [
            "name_prefix" => $patientNamePrefix,
            "name" => $patientName,
            "address" => $patientAddress,
            "email" => $patientEmail,
            "date_of_birth" => $dateOfBirth,
            "age" => $age,
            "today" => $today,
            "time" => $time,
            "patient_id" => $patientconcerform->patient_id
        ];
        $patient = compact('patient');

        // Make sure $PatientsConcernFormId exists or is handled properly
        return view('ConcernForm.Concentform', compact('patient', 'ConcernForm', 'PatientsConcernFormId'));
    }

    public function sendWhatsappMessage($gu_id)
    {
        $patientConcerform = PatientConcerform::where('gu_id', $gu_id)->first();
        $Clinic = Clinic::where('clinic_id', $patientConcerform->clinic_id)->first();
        $patientData = Patient::where('id', $patientConcerform->patient_id)->first();

        $patientName = $patientData->name ?? "";
        $patientMobile = $patientData->mobile1 ?? "";
        $clinicName = $Clinic->name ?? "";

        $whatsappToken = 'EAATZAZAlCLXjEBO3L964MCRbqZA8kRj95hjONF6DRUaZCkd3bk2LzHvKbvV72eZCqMEOjm9pVaEG9ZCvFd2m1GsxFkysBQPXYmVbE7HSVdrrut3PijBInprtr4KTwvPGbQw0b2AHlIpfgGyeKSOosoc05ztRw8W1y0hlZC84U4ZAW31CikzFYNjtKyc2FgQ03wqi4QZDZD'; // Replace with your WhatsApp API token
        $phoneNumberId = '658603253999245'; // Replace with your phone number ID
        $templateName = 'concent_form1';

        $url = "https://graph.facebook.com/v19.0/{$phoneNumberId}/messages";

        try {
            $response = Http::withToken($whatsappToken)->post($url, [
                'messaging_product' => 'whatsapp',
                'to' => $patientMobile,
                'type' => 'template',
                'template' => [
                    'name' => $templateName,
                    'language' => ['code' => 'en'],
                    'components' => [
                        [
                            'type' => 'body',
                            'parameters' => [
                                ['type' => 'text', 'text' => $patientName],
                                ['type' => 'text', 'text' => $patientMobile],
                                ['type' => 'text', 'text' => $clinicName],
                            ]
                        ],
                        [
                            'type' => 'button',
                            'sub_type' => 'url',
                            'index' => 0,
                            'parameters' => [
                                ['type' => 'text', 'text' => $gu_id],
                            ]
                        ]
                    ],
                ]
            ]);

            if ($response->successful()) {
                return redirect()->back()->with('success', 'WhatsApp message sent!');
            } else {
                Log::error('WhatsApp failed', ['response' => $response->body()]);
                return redirect()->back()->with('error', 'Failed to send message');
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Exception: ' . $e->getMessage());
        }
    }


    public function upload(Request $request)
    {

        $patient_id = $request->patient_id;
        $iConcernFormId = $request->iConcernFormId;
        $patientData = Patient::where(['id' => $patient_id])->first();
        $ConcernForm = Concerform::where(['iConcernFormId' => $iConcernFormId])->first();

        $case_no = $patientData->case_no ?? "";
        $patientName = $patientData->name ?? "";
        $patientNamePrefix = $patientData->name_prefix ?? "";
        $patientAddress = $patientData->address ?? "";
        $patientEmail = $patientData->email ?? "";
        $dateOfBirth = $patientData->dob ?? "";
        $mobile_no = $patientData->mobile_no ?? "";

        $from = new \DateTime($dateOfBirth);
        $to   = new \DateTime('today');
        $ageYear =  $from->diff($to)->y;
        $ageMonth =  $from->diff($to)->m;
        $today = date('m-d-Y');
        $time = date('H:i');

        $age = $ageYear . " years";
        if ($ageYear == 0) {

            $age = $ageMonth . " months";
        }

        $patient[] = array(
            "name_prefix" => $patientNamePrefix,
            "name" => $patientName,
            "address" => $patientAddress,
            "email" => $patientEmail,
            "date_of_birth" => $dateOfBirth,
            "age" => $age,
            "today" => $today,
            "time" => $time,
            "patient_id" => $patient_id
        );
        $patient = compact('patient');

        set_time_limit(300);
        $folderPath = public_path('assets/signature/');
        if (!File::exists($folderPath)) {
            File::makeDirectory($folderPath, $mode = 0777, true, true);
        }
        $image_parts = explode(";base64,", $request->signed);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $file = $folderPath . $case_no . "_" . $patientName . '.' . $image_type;

        file_put_contents($file, $image_base64);
        $fileName = $request->PatientsConcernFormId . "_" . $case_no . "_" . str_replace(' ', '_', $patientName);

        $arr = array(
            "strFileName" => $fileName . '.pdf',
            "submitedDateTime" => date('Y-m-d H:i:s'),
            "isSubmit" => 1,
        );
        PatientConcerform::where('patient_concern_form_id', $request->PatientsConcernFormId)->update($arr);

        $pdf = PDF::loadView('ConcernForm/Savedconcentform', ['patient' => $patient, 'fileName' => $file, 'ConcernForm' => $ConcernForm]);


        $content = $pdf->download()->getOriginalContent();
        Storage::put('public/signatureform/' . $fileName . '.pdf', $content);
        // Save PDF to public folder (local or live)
        if ($_SERVER['SERVER_NAME'] === "127.0.0.1") {
            $savePath = public_path('assets/signatureform/');
        } else {
            $savePath = public_path('../../public_html/dental_clinic_new/assets/signatureform/');
        }

        if (!File::exists($savePath)) {
            File::makeDirectory($savePath, 0777, true);
        }

        $pdf->save($savePath . $fileName . '.pdf');
        return redirect()->route('thankyou');
        // return redirect()->route('patientconcernform.index', $patient_id)->with('success', 'Signature uploaded successfully.');
    }
}
