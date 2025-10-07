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
use App\Models\Lab;
use GuzzleHttp\Client;
use App\Models\Clinic;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;




class LabApiController extends Controller

// class DriverApiController extends PushNotificationController
{

    public function AddLab(Request $request)
    {

        try {
            $request->validate([
                'lab_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('labs')->where(function ($query) use ($request) {
                        return $query->where('clinic_id', $request->clinic_id);
                    }),
                ],

                'contact_person' => 'required|string|max:30',
                'mobile' => 'required|string|regex:/^[0-9]{10}$/',
                'address' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:30',
                'clinic_id' => 'required',

            ], [
                'lab_name.unique' => 'This lab already exists.',
            ]);


            Lab::create([
                'lab_name' => $request->lab_name,
                'contact_person' => $request->contact_person,
                'mobile' => $request->mobile,
                'address' => $request->address,
                'email' => $request->email,
                'clinic_id' => $request->clinic_id,

            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lab Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function Lablist(Request $request)
    {
        try {
            $request->validate([

                'clinic_id' => 'required|numeric'

            ]);

            $listOfLab = Lab::select(
                "id",
                "lab_name",
                "contact_person",
                "mobile",
                "address",
                "email",
                "clinic_id",

            )->orderBy('lab_name', 'asc')->where('clinic_id', $request->clinic_id)->get();
            return response()->json([
                'success' => true,
                'message' => "successfully fetched Lablist...",
                'data' => $listOfLab,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function LabUpdate(Request $request)
    {
        try {
            $request->validate([

                'contact_person' => 'required|string|max:30',
                'mobile' => 'required|string|regex:/^[0-9]{10}$/',
                'address' => 'nullable|string|max:255',
                'email' => 'nullable|email|max:30',
                'lab_name' => 'required',
                'id' => 'required',

            ]);

            // Find the vendor by vendor_id
            $Lab = Lab::find($request->id);
            if ($Lab) {
                // Update the vendor's address and mobile
                $Lab->update([
                    'contact_person' => $request->contact_person,
                    'mobile' => $request->mobile,
                    'address' => $request->address,
                    'email' => $request->email,
                    'lab_name' => $request->lab_name,

                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Lab updated successfully.',
                    'data' => $Lab,  // Return the updated vendor data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Lab not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Lab: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function Labdelete(Request $request)
    {
        try {
            // Find the deal option by DealsOption_id
            $request->validate([

                "id" => 'required'
            ]);
            $Lab = Lab::find($request->id);


            if ($Lab) {
                // Delete the deal option
                $Lab->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Lab Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Lab not found',
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
