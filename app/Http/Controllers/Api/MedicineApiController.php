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




class MedicineApiController extends Controller

// class DriverApiController extends PushNotificationController
{


    public function dosagelist(Request $request)
    {
        try {
            $request->validate([
                'clinic_id' => 'required|numeric'
            ]);

            $listOfDosage = Dosage::select(
                "id",
                "dosage",
                "clinic_id",
            )->orderBy('id', 'asc')->where('clinic_id', $request->clinic_id)->get();
            return response()->json([
                'success' => true,
                'message' => "successfully fetched Dosagelist...",
                'data' => $listOfDosage,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function AddMedicine(Request $request)
    {

        try {
            // Validate the incoming request
            $request->validate([
                'medicine_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('medicines')->where(function ($query) use ($request) {
                        return $query->where('clinic_id', $request->clinic_id);
                    }),
                ],
                'dosage_id' => 'required',
                'clinic_id' => 'required|numeric',
                'comment' => 'required',
            ], [
                'medicine_name.unique' => 'This Medicines already exists for this clinic.',
            ]);


            Medicine::create([
                'medicine_name' => $request->medicine_name,
                'dosage_id' => $request->dosage_id,
                'comment' => $request->comment,
                'clinic_id' => $request->clinic_id,

            ]);

            return response()->json([
                'success' => true,
                'message' => 'Medicine Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function Medicinelist(Request $request)
    {
        try {
            $request->validate([

                'clinic_id' => 'required|numeric'

            ]);

            $listOfMedicine = Medicine::select(
                "id",
                "medicine_name",
                "clinic_id",
                "dosage_id",
                "comment",
            )->orderBy('medicine_name', 'asc')->where('clinic_id', $request->clinic_id)->get();
            return response()->json([
                'success' => true,
                'message' => "successfully fetched Medicinelist...",
                'data' => $listOfMedicine,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function MedicineUpdate(Request $request)
    {
        try {
            $request->validate([

                'medicine_name' => 'required',
                'dosage_id' => 'required',
                'comment' => 'required',
            ]);

            // Find the vendor by vendor_id
            $Medicine = Medicine::find($request->id);
            if ($Medicine) {
                // Update the vendor's address and mobile
                $Medicine->update([
                    'medicine_name' => $request->medicine_name,
                    'dosage_id' => $request->dosage_id,
                    'comment' => $request->comment,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Medicine updated successfully.',
                    'data' => $Medicine,  // Return the updated vendor data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Medicine not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Medicine: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function Medicinedelete(Request $request)
    {
        try {
            // Find the deal option by DealsOption_id
            $request->validate([

                "id" => 'required'
            ]);
            $Medicine = Medicine::find($request->id);


            if ($Medicine) {
                // Delete the deal option
                $Medicine->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Medicine Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Medicine not found',
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
