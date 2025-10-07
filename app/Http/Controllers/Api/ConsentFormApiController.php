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
use App\Models\Concerform;
use GuzzleHttp\Client;
use App\Models\Clinic;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;




class ConsentFormApiController extends Controller

// class DriverApiController extends PushNotificationController
{

    public function AddConsentForm(Request $request)
    {

        try {
            $request->validate([
                'strConcernFormTitle' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('concern_forms')->where(function ($query) use ($request) {
                        return $query->where('clinic_id', $request->clinic_id);
                    }),
                ],

                'strConcernFormText' => 'required|string',
                'clinic_id' => 'required',

            ], [
                'strConcernFormTitle.unique' => 'This Title already exists.',
            ]);


            Concerform::create([
                'strConcernFormTitle' => $request->strConcernFormTitle,
                'strConcernFormText' => $request->strConcernFormText,
                'clinic_id' => $request->clinic_id,
                'strIP' => $request->ip(),


            ]);

            return response()->json([
                'success' => true,
                'message' => 'Concerform Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function ConsentFormlist(Request $request)
    {
        try {
            $request->validate([

                'clinic_id' => 'required|numeric'

            ]);

            $listOfConcerform = Concerform::select(
                "iConcernFormId",
                "strConcernFormTitle",
                "strConcernFormText",
                "clinic_id",

            )->orderBy('strConcernFormTitle', 'asc')->where('clinic_id', $request->clinic_id)->get();
            return response()->json([
                'success' => true,
                'message' => "successfully fetched Concerformlist...",
                'data' => $listOfConcerform,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function ConsentFormUpdate(Request $request)
    {
        try {
            $request->validate([

                'strConcernFormTitle' => 'required|string',
                'strConcernFormText' => 'required|string',
                'iConcernFormId' => 'required',

            ]);

            // Find the vendor by vendor_id
            $Concerform = Concerform::find($request->iConcernFormId);

            if ($Concerform) {
                // Update the vendor's address and mobile
                $Concerform->update([
                    'strConcernFormTitle' => $request->strConcernFormTitle,
                    'strConcernFormText' => $request->strConcernFormText,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Concerform updated successfully.',
                    'data' => $Concerform,  // Return the updated vendor data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Concerform not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Concerform: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function ConsentFormdelete(Request $request)
    {
        try {
            // Find the deal option by DealsOption_id
            $request->validate([

                "iConcernFormId" => 'required'
            ]);
            $Concerform = Concerform::find($request->iConcernFormId);


            if ($Concerform) {
                // Delete the deal option
                $Concerform->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'ConcerForm Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'ConcerForm not found',
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
