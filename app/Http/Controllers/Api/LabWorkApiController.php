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
use App\Models\Labwork;
use GuzzleHttp\Client;
use App\Models\Clinic;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;



class LabWorkApiController extends Controller

// class DriverApiController extends PushNotificationController
{


    public function LabWorklist(Request $request)
    {
        try {
            $request->validate([
                'clinic_id' => 'required|numeric'
            ]);

            $listOflabwork = Labwork::with('treatment', 'lab')->where('clinic_id', $request->clinic_id)->get();
            $data = [];
            foreach ($listOflabwork as $doc) {
                $data[] = [

                    'id' => $doc->id,
                    'patient_id' => $doc->patient_id,
                    'lab_id' => $doc->lab_id,
                    'treatment_id' => $doc->treatment_id,
                    'patient_treatment_id' => $doc->patient_treatment_id,
                    'entry_date' => date('m-d-Y', strtotime($doc->entry_date)),
                    'lab_name' => $doc->lab->lab_name,
                    'treatment_name' => $doc->treatment->treatment_name ?? '',
                    'comment' => $doc->comment,

                ];
            }
            return response()->json([
                'success' => true,
                'message' => "successfully fetched Labworklist...",
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function AddLabWork(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'Lab_id' => 'required|exists:labs,id',
                'patient_id' => 'required|exists:patients,id',
                'treatment_id' => 'required|exists:treatments,id',
                'patient_treatment_id' => 'required|exists:patient_treatments,id',
                'entry_date' => 'required|date',
                'clinic_id' => 'required|exists:clinic,clinic_id',
                'comment' => 'required|string',
            ]);

            // Custom validation for composite unique combination
            $validator->after(function ($validator) use ($request) {
                $exists = DB::table('labworks')
                    ->where('lab_id', $request->Lab_id)
                    ->where('patient_id', $request->patient_id)
                    ->where('treatment_id', $request->treatment_id)
                    ->where('patient_treatment_id', $request->patient_treatment_id)
                    ->exists();

                if ($exists) {
                    $validator->errors()->add(
                        'patient_treatment_id',
                        'This combination of Lab ID, Patient ID, Treatment ID, and Patient Treatment ID already exists.'
                    );
                }
            });

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'errors' => $validator->errors()
                ], 422);
            }

            Labwork::create([
                'lab_id' => $request->Lab_id,
                'patient_id' => $request->patient_id,
                'treatment_id' => $request->treatment_id,
                'patient_treatment_id' => $request->patient_treatment_id,
                'entry_date' => $request->entry_date,
                'clinic_id' => $request->clinic_id,
                'comment' => $request->comment,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Labwork added successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function LabWorkdelete(Request $request)
    {
        try {
            $request->validate([

                "id" => 'required'
            ]);
            $Labwork = Labwork::find($request->id);


            if ($Labwork) {
                $Labwork->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Labwork Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Labwork not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function MarkAsCollected(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:labworks,id'
            ]);

            $Labwork = Labwork::find($request->id);

            if ($Labwork) {
                $Labwork->update([
                    'collection_date' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Mark As Collected Successfully',
                    'collection_date' => date('d-m-Y', strtotime($Labwork->collection_date))

                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Labwork not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function MarkAsReceived(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required|exists:labworks,id'
            ]);

            $Labwork = Labwork::find($request->id);

            if ($Labwork) {
                $Labwork->update([
                    'received_date' => now()
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Mark As Received Successfully',
                    'received_date' => date('d-m-Y', strtotime($Labwork->received_date))
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Labwork not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }
}
