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
use App\Models\Medicine;
use App\Models\Dosage;
use GuzzleHttp\Client;
use App\Models\Clinic;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;




class DosageApiController extends Controller

// class DriverApiController extends PushNotificationController
{


    public function AddDosage(Request $request)
    {

        try {
            // Validate the incoming request
            $request->validate([

                'dosage' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('dosages')->where(function ($query) use ($request) {
                        return $query->where('clinic_id', $request->clinic_id);
                    }),

                ],

            ], [
                'dosage.unique' => 'This dosage input already exists.',
            ]);


            Dosage::create([
                'dosage' => $request->dosage,
                'clinic_id' => $request->clinic_id,

            ]);

            return response()->json([
                'success' => true,
                'message' => 'Dosage Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }



    public function DosageUpdate(Request $request)
    {
        try {
            $request->validate([

                'dosage' => 'required',
                'id' => 'required',


            ]);

            // Find the vendor by vendor_id
            $Dosage = Dosage::find($request->id);
            if ($Dosage) {
                // Update the vendor's address and mobile
                $Dosage->update([
                    'dosage' => $request->dosage,

                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Dosage updated successfully.',
                    'data' => $Dosage,  // Return the updated vendor data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Dosage not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Dosage: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function Dosagedelete(Request $request)
    {
        try {
            // Find the deal option by DealsOption_id
            $request->validate([

                "id" => 'required'
            ]);
            $Dosage = Dosage::find($request->id);


            if ($Dosage) {
                // Delete the deal option
                $Dosage->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Dosage Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Dosage not found',
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
