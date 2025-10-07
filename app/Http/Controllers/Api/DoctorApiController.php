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
use App\Models\Doctor;
use GuzzleHttp\Client;
use App\Models\Clinic;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;




class DoctorApiController extends Controller

// class DriverApiController extends PushNotificationController
{


    public function Doctorlist(Request $request)
    {
        try {
            $request->validate([
                'clinic_id' => 'required|numeric'
            ]);

            $listOfDoctor = Doctor::select(
                "id",
                "doctor_name",
                "mobile",
                "address",
                "pincode",
                "clinic_id",
            )->orderBy('id', 'asc')->where('clinic_id', $request->clinic_id)->get();
            return response()->json([
                'success' => true,
                'message' => "successfully fetched Doctorlist...",
                'data' => $listOfDoctor,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function AddDoctor(Request $request)
    {

        try {
            // Validate the incoming request
            $request->validate([
                'mobile' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('doctors')->where(function ($query) use ($request) {
                        return $query->where('clinic_id', $request->clinic_id);
                    }),
                ],
                'doctor_name' => 'required',
                'address' => 'required',
                'pincode' => 'required',
                'clinic_id' => 'required',


            ], [
                'mobile.unique' => 'This Mobile Number already exists for this clinic.',
            ]);


            Doctor::create([
                'mobile' => $request->mobile,
                'doctor_name' => $request->doctor_name,
                'address' => $request->address,
                'pincode' => $request->pincode,
                'clinic_id' => $request->clinic_id,


            ]);

            return response()->json([
                'success' => true,
                'message' => 'Doctor Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function DoctorUpdate(Request $request)
    {
        try {
            $request->validate([

                'doctor_name' => 'required',
                'address' => 'required',
                'pincode' => 'required',
                'clinic_id' => 'required',
                'mobile' => 'required',
                'id' => 'required',
            ]);

            // Find the vendor by vendor_id
            $Doctor = Doctor::find($request->id);
            if ($Doctor) {
                // Update the vendor's address and mobile
                $Doctor->update([
                    'doctor_name' => $request->doctor_name,
                    'address' => $request->address,
                    'pincode' => $request->pincode,
                    'mobile' => $request->mobile
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Doctor updated successfully.',
                    'data' => $Doctor,  // Return the updated vendor data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Doctor not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Doctor: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function Doctordelete(Request $request)
    {
        try {
            // Find the deal option by DealsOption_id
            $request->validate([

                "id" => 'required'
            ]);
            $Doctor = Doctor::find($request->id);


            if ($Doctor) {
                // Delete the deal option
                $Doctor->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Doctor Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Doctor not found',
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
