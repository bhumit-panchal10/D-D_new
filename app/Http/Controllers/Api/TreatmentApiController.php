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
use App\Models\Treatment;
use GuzzleHttp\Client;
use App\Models\Clinic;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;




class TreatmentApiController extends Controller

// class DriverApiController extends PushNotificationController
{
    public function AddTreatment(Request $request)
    {

        try {
            // Validate the incoming request
            $request->validate([
                'treatment_name' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('treatments')->where(function ($query) use ($request) {
                        return $query->where('clinic_id', $request->clinic_id);
                    }),
                ],
                'type' => 'required',
                'clinic_id' => 'required|numeric',
                'lab_work' => 'required|in:yes,no',
                'amount' => 'required|numeric',
            ], [
                'treatment_name.unique' => 'This treatment already exists for this clinic.',
            ]);


            Treatment::create([
                'treatment_name' => $request->treatment_name,
                'type' => $request->type,
                'lab_work' => $request->lab_work,
                'clinic_id' => $request->clinic_id,
                'amount' => $request->amount,

            ]);

            return response()->json([
                'success' => true,
                'message' => 'Treatment Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }

    public function Treatmentlist(Request $request)
    {
        try {
            $request->validate([

                'clinic_id' => 'required|numeric'

            ]);

            $listOftreatment = Treatment::select(
                "id",
                "treatment_name",
                "type",
                "lab_work",
                "clinic_id",
                "amount",
            )->orderBy('treatment_name', 'asc')->where('clinic_id', $request->clinic_id)->get();
            return response()->json([
                'success' => true,
                'message' => "successfully fetched Treatmentlist...",
                'data' => $listOftreatment,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['message' => $th->getMessage()], 500);
        }
    }

    public function TreatmentUpdate(Request $request)
    {
        try {
            $request->validate([
                'treatment_name' => [
                    'required',
                    'string',
                    'max:255',
                ],
                'id' => 'required',
                'type' => 'required',
                'clinic_id' => 'required|numeric',
                'lab_work' => 'required|in:yes,no',
                'amount' => 'required|numeric',
            ]);

            // Find the vendor by vendor_id
            $Treatment = Treatment::find($request->id);
            if ($Treatment) {
                // Update the vendor's address and mobile
                $Treatment->update([
                    'treatment_name' => $request->treatment_name,
                    'type' => $request->type,
                    'lab_work' => $request->lab_work,
                    'amount' => $request->amount,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Treatment updated successfully.',
                    'data' => $Treatment,  // Return the updated vendor data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Treatment not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Treatment: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function Treatmentdelete(Request $request)
    {
        try {
            // Find the deal option by DealsOption_id
            $request->validate([

                "id" => 'required'
            ]);
            $treatment = Treatment::find($request->id);


            if ($treatment) {
                // Delete the deal option
                $treatment->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Treatment Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Treatment not found',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage(),
            ], 500);
        }
    }
}
