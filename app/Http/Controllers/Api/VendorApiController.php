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
use App\Models\Vendor;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;


class VendorApiController extends Controller

// class DriverApiController extends PushNotificationController
{

    public function Vendorlist(Request $request)
    {
        try {
            $request->validate([
                'clinic_id' => 'required|numeric'
            ]);

            $listOfVendor = Vendor::select(
                "id",
                "company_name",
                "contact_person_name",
                "mobile",
                "email",
                "address",
                "clinic_id",
            )->orderBy('id', 'asc')->where('clinic_id', $request->clinic_id)->get();
            return response()->json([
                'success' => true,
                'message' => "successfully fetched Vendorlist...",
                'data' => $listOfVendor,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function AddVendor(Request $request)
    {

        try {
            // Validate the incoming request
            $request->validate([
                'company_name' => 'required',
                'contact_person_name' => 'required',
                'mobile' => 'required',
                'clinic_id' => 'required',


            ]);
            Vendor::create([
                'company_name' => $request->company_name,
                'contact_person_name' => $request->contact_person_name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'clinic_id' => $request->clinic_id,
                'address' => $request->address,

            ]);

            return response()->json([
                'success' => true,
                'message' => 'Vendor Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function VendorUpdate(Request $request)
    {
        try {
            $request->validate([
                'company_name' => 'required',
                'contact_person_name' => 'required',
                'mobile' => 'required',
                'id' => 'required',
                'email' => 'nullable|email',
                'address' => 'nullable|string',
            ]);

            $Vendor = Vendor::find($request->id);

            if ($Vendor) {
                $Vendor->update([
                    'company_name' => $request->company_name,
                    'contact_person_name' => $request->contact_person_name,
                    'mobile' => $request->mobile,
                    'email' => $request->email,
                    'address' => $request->address,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Vendor updated successfully.',
                    'data' => $Vendor,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Vendor: ' . $th->getMessage(),
            ], 500);
        }
    }


    public function Vendordelete(Request $request)
    {
        try {
            $request->validate([

                "id" => 'required'
            ]);
            $Vendor = Vendor::find($request->id);


            if ($Vendor) {
                $Vendor->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Vendor Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Vendor not found',
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
