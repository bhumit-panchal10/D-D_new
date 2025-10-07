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
use App\Models\Dosage;
use GuzzleHttp\Client;
use App\Models\Clinic;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;




class UserApiController extends Controller

// class DriverApiController extends PushNotificationController
{


    public function Userlist(Request $request)
    {
        try {
            $request->validate([
                'clinic_id' => 'required|numeric'
            ]);

            $listOfUser = User::select(
                "id",
                "name",
                "email",
                "address",
                "mobile_number",
                "role_id",
                "status",
                "dob",
                "clinic_id",
            )->orderBy('id', 'asc')->where('clinic_id', $request->clinic_id)->get();
            return response()->json([
                'success' => true,
                'message' => "successfully fetched Userlist...",
                'data' => $listOfUser,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function AddUser(Request $request)
    {

        try {
            // Validate the incoming request
            $request->validate([
                'mobile_number' => [
                    'required',
                    'string',
                    'max:255',
                    Rule::unique('users')->where(function ($query) use ($request) {
                        return $query->where('clinic_id', $request->clinic_id);
                    }),
                ],
                'name' => 'required',
                'email' => 'required',
                'address' => 'required',
                'dob' => 'required|date',
                'password' => 'required',
                'clinic_id' => 'required',


            ], [
                'mobile_number.unique' => 'This Mobile Number already exists for this clinic.',
            ]);


            User::create([
                'name' => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'dob' => $request->dob,
                'role_id' => 3,
                'mobile_number' => $request->mobile_number,
                'password' => Hash::make($request->password),
                'clinic_id' => $request->clinic_id,


            ]);

            return response()->json([
                'success' => true,
                'message' => 'User Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function UserUpdate(Request $request)
    {
        try {
            $request->validate([

                'name' => 'required',
                'email' => 'required',
                'address' => 'required',
                'dob' => 'required|date',
                'mobile_number' => 'required',
                'id' => 'required',
            ]);

            // Find the vendor by vendor_id
            $User = User::find($request->id);
            if ($User) {
                // Update the vendor's address and mobile
                $User->update([
                    'name' => $request->name,
                    'email' => $request->email,
                    'address' => $request->address,
                    'dob' => $request->dob,
                    'mobile_number' => $request->mobile_number
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'User updated successfully.',
                    'data' => $User,  // Return the updated vendor data
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update User: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function status(Request $request)
    {
        try {
            $request->validate([

                'status' => 'required',
                'id' => 'required',
            ]);

            $User = User::find($request->id);
            if ($User) {
                $User->update([
                    'status' => $request->status
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Status updated successfully.',
                    'data' => $User,
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Status not found.',
                ], 404);
            }
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Status: ' . $th->getMessage(),
            ], 500);
        }
    }

    public function ChangePassword(Request $request)
    {
        try {
            $request->validate([
                'password' => 'required|string|min:6',
                'id' => 'required|integer',
                'clinic_id' => 'required|integer',
            ]);

            $User = User::find($request->id);

            if (!$User) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.',
                ], 404);
            }

            $hashedPassword = Hash::make($request->password);

            if ($User) {
                $User->update([
                    'password' => $hashedPassword,
                ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Password updated successfully.',

            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update password: ' . $th->getMessage(),
            ], 500);
        }
    }


    public function Userdelete(Request $request)
    {
        try {
            // Find the deal option by DealsOption_id
            $request->validate([

                "id" => 'required'
            ]);
            $User = User::find($request->id);


            if ($User) {
                // Delete the deal option
                $User->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'User Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
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
