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
use App\Models\Pincode;
use GuzzleHttp\Client;
use App\Models\Clinic;
use App\Models\ApiUser;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;



class ClinicApiController extends Controller

// class DriverApiController extends PushNotificationController
{


    public function Cliniclogin(Request $request)
    {
        try {
            $request->validate([
                'mobile_no' => 'required|digits:10',
                'password' => 'required',
            ]);

            $mobile = $request->mobile_no;
            $password = $request->password;

            // Then check in User table
            $user = ApiUser::where('mobile_number', $mobile)->first();
            if ($user && Hash::check($password, $user->password)) {
                $token = JWTAuth::fromUser($user);

                return response()->json([
                    'success' => true,
                    'message' => 'User login successful',
                    'user_type' => 'user',
                    'user' => $user,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
            ], 401);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }


    public function change_password(Request $request)
    {
        try {

            if (Auth::guard('technicialapi')->check()) {

                $request->validate(
                    [
                        "Technicial_id" => 'required',
                        "old_password" => 'required',
                        "new_password" => 'required',
                        "confirm_new_password" => 'required|same:new_password'
                    ],
                    [
                        'Technicial_id.required' => 'Technicial ID is required.',
                        'old_password.required' => 'Old Password is required.',
                        'new_password.required' => 'New Password is required.',
                        'new_password.same' => 'New password and confirmation password must match.'
                    ]
                );

                $Technicial =  Technicial::where(['iStatus' => 1, 'isDelete' => 0, 'Technicial_id' => $request->Technicial_id])->first();
                if (!$Technicial) {
                    return response()->json([
                        'success' => false,
                        'message' => "Technicial not found."
                    ]);
                }

                if (Hash::check($request->old_password, $Technicial->password)) {

                    $newpassword = $request->new_password;
                    $confirmpassword = $request->confirm_new_password;

                    if ($newpassword == $confirmpassword) {

                        $Technicial->update([
                            'password' => Hash::make($confirmpassword),
                            'is_changepasswordfirsttime' => 1
                        ]);
                        return response()->json([
                            'success' => true,
                            'message' => 'Password updated successfully...',
                        ], 200);
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'password and confirm password does not match',
                        ], 200);
                    }
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => 'Current Password does not match',
                    ], 200);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Technicial is not Authorised.',
                ], 401);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            // If there's an error, rollback any database transactions and return an error response.
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function forgot_password(Request $request)
    {
        try {
            $request->validate([
                'mobile_no' => 'required',
            ]);

            // Find the vendor by email
            $Technicial = Technicial::where(['iStatus' => 1, 'isDelete' => 0])
                ->where('mobile_no', $request->mobile_no)
                ->first();

            if (!$Technicial) {
                return response()->json([
                    'success' => false,
                    'message' => "Technicial not found."
                ], 404);
            }

            $otp = rand(1000, 9999);
            $expiry_date = now()->addMinutes(5);

            // Update the OTP and expiry in the database
            $Technicial->update([
                'otp' => $otp,
                'expiry_time' => $expiry_date,
            ]);

            // Send the email
            $sendEmailDetails = DB::table('sendemaildetails')->where(['id' => 9])->first();
            $msg = [
                'FromMail' => $sendEmailDetails->strFromMail,
                'Title' => $sendEmailDetails->strTitle,
                'ToEmail' => $Technicial->email,
                'Subject' => $sendEmailDetails->strSubject,
            ];

            $data = array(
                'otp' => $otp,
                "name" => $Technicial->name
            );


            Mail::send('emails.forgotPassword', ['data' => $data], function ($message) use ($msg) {
                $message->from($msg['FromMail'], $msg['Title']);
                $message->to($msg['ToEmail'])->subject($msg['Subject']);
            });

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully.'
            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }

    public function forgot_password_verifyOTP(Request $request)
    {
        try {
            $request->validate([

                'otp' => 'required'
            ]);

            $password = mt_rand(100000, 999999);


            $Technicial = Technicial::where([

                'otp' => $request->otp
            ])->first();

            if (!$Technicial) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP is invalid. Please enter a valid OTP.',
                ], 400);
            }

            // Check if the OTP has expired
            $expiryTime = Carbon::parse($Technicial->expiry_time);
            if (now()->greaterThan($expiryTime)) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP has expired.',
                ], 400);
            }

            // Mark the OTP as verified and update the last login time
            $Technicial->update([
                // 'isOtpVerified' => 1,
                'password' =>  Hash::make($password),
                'last_login' => now(),
            ]);

            $data = array(
                'password' => $password,
                "name" =>  $Technicial->name,
                "mobile_no" =>  $Technicial->mobile_no


            );

            $sendEmailDetails = DB::table('sendemaildetails')->where(['id' => 9])->first();
            $msg = array(
                'FromMail' => $sendEmailDetails->strFromMail,
                'Title' => $sendEmailDetails->strTitle,
                'ToEmail' => $Technicial->email,
                'Subject' => $sendEmailDetails->strSubject
            );

            Mail::send('emails.forgotpasswordmail', ['data' => $data], function ($message) use ($msg) {
                $message->from($msg['FromMail'], $msg['Title']);
                $message->to($msg['ToEmail'])->subject($msg['Subject']);
            });
            // $vendorDetails = $vendor->only(['vendor_id','vendorname', 'isOtpVerified', 'login_id', 'vendormobile', 'email', 'businessname', 'businessaddress','vendorsocialpage','businesscategory','businessubcategory','is_changepasswordfirsttime']);
            return response()->json([
                'success' => true,
                'message' => 'OTP is valid.',
                // 'vendor_details' => $vendorDetails,

            ], 200);
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }


    public function logout(Request $request)
    {

        try {
            // Validate the vendorid passed in the request
            $request->validate([
                'Technicial_id' => 'required|integer'
            ]);
            // Optionally, fetch the vendor by vendorid (if you need to check or log something)
            $Technicial = Technicial::find($request->Technicial_id);
            if (!$Technicial) {
                return response()->json([
                    'success' => false,
                    'message' => 'Technicial not found.'
                ], 404);
            }
            Auth::logout();
            session()->flush();
            // Optional: If you want to send the vendor details in the response
            return response()->json([
                'success' => true,
                'message' => 'Successfully logged out.',
                'Technicial_id' => $Technicial->Technicial_id,  // Including the vendorid in the response
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token. Unable to logout.',
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function technicialdashboard(Request $request)
    {

        try {
            $request->validate([
                'Technicial_id' => 'required|integer'
            ]);
            $Technicialbal = TechnicialLedger::where('Technicial_id', $request->Technicial_id)
                ->orderBy('Technicial_ledger_id', 'DESC')
                ->first();

            $balance = $Technicialbal->closing_bal ?? 0;

            $assignedPincodes = Pincode::whereIn('pin_id', function ($query) use ($request) {
                $query->select('Pincode_id')
                    ->from('Technicial_Pincode')
                    ->where('Technicial_id', $request->Technicial_id);
            })->pluck('pincode')->toArray();


            $Availableorder = Order::where('order_status', 0)
                ->whereIn('Pincode', $assignedPincodes)
                ->count();
            $Ongoingorder = Order::where('order_status', 1)
                ->orWhere('Technicial_id', $request->Technicial_id)
                ->count();

            return response()->json([
                'success' => true,
                'message' => 'data fetch Successfully.',
                'Balance' => $balance,
                'Availableorder' => $Availableorder,
                'Ongoingorder' => $Ongoingorder
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token. Unable to logout.',
            ], 401);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function profiledetails(Request $request)
    {
        try {

            $request->validate([
                'Technicial_id' => 'required|integer'

            ]);

            $Technicial = Technicial::with('state')->where('Technicial_id', $request->Technicial_id)
                ->where('iStatus', 1)
                ->where('isDelete', 0)
                ->first();
            //dd($Technicial);


            if (!$Technicial) {
                return response()->json([
                    'success' => false,
                    'message' => 'Technicial not found.',
                ], 404);
            }
            return response()->json([
                'success' => true,
                'data' => [
                    "name" => $Technicial->name,
                    "email" => $Technicial->email,
                    "mobile_no" => $Technicial->mobile_no,
                    "Technicial_image" => $Technicial->Technicial_image
                        ? asset('upload/Technicial/' . $Technicial->Technicial_image)
                        : '',
                    "stateid" => $Technicial->stateid,
                    "stateName" => $Technicial->state->stateName,
                    "city" => $Technicial->city,
                    "iStatus" => $Technicial->iStatus,
                    "strIP" => $Technicial->strIP,
                    "created_at" => $Technicial->created_at,
                    "updated_at" => $Technicial->updated_at,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors(),
            ], 422);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while fetching profile details.',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function profileUpdate(Request $request)
    {
        try {

            if (Auth::guard('technicialapi')->check()) {

                $customer = Auth::guard('technicialapi')->user();

                $request->validate([
                    'Technicial_id' => 'required'
                ]);

                $Technicial = Technicial::where(['iStatus' => 1, 'isDelete' => 0, 'Technicial_id' => $request->Technicial_id])->first();

                if (!$Technicial) {
                    return response()->json([
                        'success' => false,
                        'message' => "Technicial not found."
                    ]);
                }

                // Start building the Vendor data
                $TechnicialData = [];

                // Add fields conditionally
                if ($request->has('name')) {
                    $TechnicialData["name"] = $request->name;
                }
                if ($request->has('email')) {
                    $TechnicialData["email"] = $request->email;
                }
                if ($request->has('mobile_no')) {
                    $TechnicialData["mobile_no"] = $request->mobile_no;
                }
                if ($request->has('stateid')) {
                    $TechnicialData["stateid"] = $request->stateid;
                }
                if ($request->has('city')) {
                    $TechnicialData["city"] = $request->city;
                }



                if ($request->hasFile('Technicial_image')) {
                    $root = $_SERVER['DOCUMENT_ROOT'];
                    $image = $request->file('Technicial_image');
                    $imgName = time() . '_' . date('dmYHis') . '.' . $image->getClientOriginalExtension();
                    $destinationPath = $root . '/upload/Technicial/';

                    // Ensure the directory exists
                    if (!file_exists($destinationPath)) {
                        mkdir($destinationPath, 0755, true);
                    }

                    // Move the uploaded image to the destination path
                    $image->move($destinationPath, $imgName);

                    // Delete the old image if it exists
                    if ($customer->Customerimg && file_exists($destinationPath . $customer->Customerimg)) {
                        unlink($destinationPath . $customer->Customerimg);
                    }

                    // Update the image name
                    $TechnicialData['Technicial_image'] = $imgName;
                }

                // Always update 'updated_at'
                $TechnicialData['updated_at'] = now();

                DB::beginTransaction();

                try {

                    Technicial::where(['Technicial_id' => $request->Technicial_id])->update($TechnicialData);

                    DB::commit();

                    return response()->json([
                        'success' => true,
                        'message' => "Technicial Profile updated successfully.",

                    ], 200);
                } catch (\Throwable $th) {
                    DB::rollBack();
                    throw $th;
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Technicial is not authorized.',
                ], 401);
            }
        } catch (ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Throwable $th) {
            DB::rollBack();
            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
}
