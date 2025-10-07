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
use App\Models\PatientNote;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;

class PatientNotesApiController extends Controller

// class DriverApiController extends PushNotificationController
{

    public function PatientNoteslist(Request $request)
    {
        try {
            $request->validate([

                'clinic_id' => 'required|numeric'

            ]);

            $listOfPatientNote = PatientNote::select(
                "id",
                "patient_id",
                "notes",
                "clinic_id",
            )->orderBy('id', 'asc')->where('clinic_id', $request->clinic_id)->get();
            return response()->json([
                'success' => true,
                'message' => "successfully fetched PatientNotelist...",
                'data' => $listOfPatientNote,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function AddPatientNotes(Request $request)
    {

        try {
            // Validate the incoming request
            $request->validate([

                'patient_id' => 'required',
                'notes' => 'required',
                'clinic_id' => 'required|numeric',

            ]);
            PatientNote::create([
                'notes' => $request->notes,
                'clinic_id' => $request->clinic_id,
                'patient_id' => $request->patient_id,


            ]);

            return response()->json([
                'success' => true,
                'message' => 'Patient Note Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function PatientNotesUpdate(Request $request)
    {
        try {
            $request->validate([
                'notes' => 'required',
                'id' => 'required',
            ]);

            $PatientNote = PatientNote::find($request->id);
            if ($PatientNote) {
                $PatientNote->update([
                    'notes' => $request->notes,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Patient Note updated successfully.',
                    'data' => $PatientNote,  // Return the updated vendor data
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
                'message' => 'Failed to update Patient Note: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function PatientNotesdelete(Request $request)
    {
        try {
            // Find the deal option by DealsOption_id
            $request->validate([

                "id" => 'required'
            ]);
            $PatientNote = PatientNote::find($request->id);


            if ($PatientNote) {
                // Delete the deal option
                $PatientNote->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Patient Note Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Patient Note not found',
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
