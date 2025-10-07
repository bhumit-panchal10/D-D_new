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
use App\Models\User;
use App\Models\Patient;
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
                'mobile2' => [
                    'digits:10',
                    Rule::unique('patients')->where(function ($query) use ($request) {
                        return $query->where('clinic_id', $request->clinic_id);
                    }),
                ],
                'dob' => 'required',
                'address' => 'required',
                'pincode' => 'required',
                'clinic_id' => 'required',
                'case_no' => 'required',
                'gender' => 'required',


            ], [
                'mobile1.unique' => 'This Mobile 1 Number already exists for this clinic.',
                'mobile2.unique' => 'This Mobile 2  Number already exists for this clinic.',

            ]);
            Patient::create([
                'name' => $request->name,
                'mobile1' => $request->mobile1,
                'mobile2' => $request->mobile2,
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
}
