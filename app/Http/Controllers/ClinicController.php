<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Clinic_Order;
use App\Models\ClinicCaseCounters;
use App\Models\Clinic;
use App\Exports\UsersExport;
use App\Imports\UsersImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;



class ClinicController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // public function __construct()
    // {
    //     $this->middleware('auth');
    //     $this->middleware('permission:user-list|user-create|user-edit|user-delete', ['only' => ['index']]);
    //     $this->middleware('permission:user-create', ['only' => ['create', 'store', 'updateStatus']]);
    //     $this->middleware('permission:user-edit', ['only' => ['edit', 'update']]);
    //     $this->middleware('permission:user-delete', ['only' => ['delete']]);
    // }

    public function ClinicLogin()
    {
        return view('clinic.login');
    }

    public function Loginstore(Request $request)
    {
        // Validate input
        $request->validate([
            'mobile_no' => 'required',
            'password' => 'required',
        ]);

        $credentials = [
            'mobile_number' => $request->mobile_no,
            'password' => $request->password,
        ];

        // Attempt to login
        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            // Join clinic to get clinic info along with status
            $userWithClinic = User::join('clinic', 'clinic.clinic_id', '=', 'users.clinic_id')
                ->select('users.*', 'clinic.logo', 'clinic.iStatus')
                ->where('users.id', $user->id)
                ->first();


            // Check if clinic is inactive
            if (!$userWithClinic || $userWithClinic->iStatus == 0) {
                Auth::logout();
                return redirect()->back()->withInput()->with('error', 'Your clinic account is inactive. Please contact the administrator.');
            }

            // Store logo in session
            Session::put('Logo', $userWithClinic->logo);

            return redirect()->route('home')->with('success', 'Logged in successfully');
        }

        // Authentication failed
        return redirect()->back()->withInput()->with('error', 'Invalid mobile number, password or role.');
    }


    public function cliniclogout(Request $request)
    {
        Auth::logout();
        return view('cliniclogout');
    }


    public function index()
    {

        $clinics = Clinic::orderBy('clinic_id', 'desc')->paginate(config('app.per_page'));

        return view('clinic.index', compact('clinics'));
    }


    public function create()
    {
        return view('clinic.add');
    }


    public function store(Request $request)
    {
        DB::beginTransaction();
        try {

            $request->validate([
                'mobile_number' => 'required|digits:10|unique:clinic,mobile_no',
            ], [
                'mobile_number.unique' => 'Mobile number already exists.',
                'mobile_number.required' => 'Mobile number is required.',
                'mobile_number.digits' => 'Mobile number must be 10 digits.',
            ]);

            // Store Data
            $img = "";
            if ($request->hasFile('logo')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('logo');

                $img = time() . '_' . date('dmYHis') . '.' . $image->getClientOriginalExtension();
                $destinationpath = $root . '/dental_clinic_new/upload/logo/';

                // Create the directory if it doesn't exist
                if (!file_exists($destinationpath)) {
                    mkdir($destinationpath, 0755, true);
                }

                // Move the image to the destination
                $image->move($destinationpath, $img);
            }
            $User = User::create([
                'name'    => $request->name,
                'email' => $request->email,
                'address' => $request->address,
                'mobile_number' => $request->mobile_number,
                'password' => Hash::make($request->password),
                'role_id' => 2,
            ]);


            $Clinic = Clinic::create([
                'name'    => $request->name,
                'doctor'   => $request->doctor_name,
                'mobile_no' => $request->mobile_number,
                'password' => Hash::make($request->password),
                'state' => $request->state,
                'city' => $request->city,
                'email' => $request->email,
                'address' => $request->address,
                'logo' => $img,
                'user_id' => $User->id,
            ]);
            $User->clinic_id = $Clinic->clinic_id;
            $User->save();
            $caseMaster = ClinicCaseCounters::create([
                'clinic_id' => $Clinic->clinic_id,
                'last_number' => 1,
            ]);

            DB::commit();
            return redirect()->route('clinic.index')->with('success', 'Clinic Created Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }


    public function updateStatus($clinic_id, $status)
    {
        // Validation
        $validate = Validator::make([
            'clinic_id'   => $clinic_id,
            'status'    => $status
        ], [
            'clinic_id'   =>  'required|exists:clinic,clinic_id',
            'status'    =>  'required|in:0,1',
        ]);

        // If Validations Fails
        if ($validate->fails()) {
            return redirect()->route('clinic.index')->with('error', $validate->errors()->first());
        }

        try {
            DB::beginTransaction();

            // Update Status
            Clinic::where('clinic_id', $clinic_id)->update(['iStatus' => $status]);

            // Commit And Redirect on index with Success Message
            DB::commit();
            return redirect()->route('clinic.index')->with('success', 'clinic Status Updated Successfully!');
        } catch (\Throwable $th) {

            // Rollback & Return Error Message
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function edit(Request $request, $id)
    {
        $Clinic = Clinic::where('clinic_id', $id)->first();

        if (!$Clinic) {
            return redirect()->route('clinic.index')->with('error', 'Clinic not found or inactive.');
        }
        return view('clinic.edit', compact('Clinic'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'mobile_no' => [
                'required',
                'digits:10',
                Rule::unique('clinic', 'mobile_no')->ignore($request->clinic_id, 'clinic_id'),
            ],
        ], [
            'mobile_no.unique' => 'Mobile number already exists.',
            'mobile_no.required' => 'Mobile number is required.',
            'mobile_no.digits' => 'Mobile number must be 10 digits.',
        ]);

        DB::beginTransaction();
        try {

            $img = "";

            if ($request->hasFile('editmain_img')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('editmain_img');

                // Generate a unique file name
                $img = time() . '_' . date('dmYHis') . '.' . $image->getClientOriginalExtension();
                $destinationpath = $root . '/dental_clinic_new/upload/logo/';


                // Create directory if it doesn't exist
                if (!file_exists($destinationpath)) {
                    mkdir($destinationpath, 0755, true);
                }

                // Move the image to the destination path
                $image->move($destinationpath, $img);

                // Delete the old image if it exists
                $oldImg = $request->input('hiddenPhoto');
                if ($oldImg && file_exists($destinationpath . '/' . $oldImg)) {
                    unlink($destinationpath . '/' . $oldImg);
                }
            } else {
                // If no image is uploaded, keep the old image name
                $img = $request->input('hiddenPhoto');
            }


            $clinic = Clinic::findOrFail($request->clinic_id);
            $clinic->update([
                'name'      => $request->name,
                'doctor'    => $request->doctor,
                'mobile_no' => $request->mobile_no,
                'email' => $request->email,
                'address' => $request->address,
                'password'  => Hash::make($request->password),
                'state'     => $request->state,
                'city'      => $request->city,
                'logo'      => $img,
            ]);

            $user = User::findOrFail($clinic->user_id);
            $user->update([
                'name'          => $request->name,
                'mobile_number' => $request->mobile_no,
                'password'      => Hash::make($request->password),
                'email' => $request->email,
                'address' => $request->address,
            ]);
            Session::put('Logo', $img);

            DB::commit();
            return redirect()->route('clinic.index')->with('success', 'clinic Updated Successfully.');
        } catch (\Throwable $th) {
            // Rollback and return with Error
            DB::rollBack();
            return redirect()->back()->withInput()->with('error', $th->getMessage());
        }
    }


    public function delete(Request $request)
    {
        DB::beginTransaction();
        try {
            // Delete User
            Clinic::where('clinic_id', $request->id)->delete();
            User::where('clinic_id', $request->id)->delete();

            DB::commit();
            return redirect()->route('clinic.index')->with('success', 'Clinic Deleted Successfully!.');
        } catch (\Throwable $th) {
            DB::rollBack();
            return redirect()->back()->with('error', $th->getMessage());
        }
    }


    public function importUsers()
    {
        return view('users.import');
    }

    public function uploadUsers(Request $request)
    {
        Excel::import(new UsersImport, $request->file);

        return redirect()->route('users.index')->with('success', 'User Imported Successfully');
    }



    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }

    // public function password_update(Request $request)
    // {

    //     dd($request);
    //     $newpassword = $request->newpassword;
    //     $confirmpassword = $request->confirmpassword;

    //     if ($newpassword === $confirmpassword) {
    //         // Get the user_id from the clinic
    //         $clinic = DB::table('clinic')->where('clinic_id', $request->id)->first();
    //         dd($clinic);
    //         if ($clinic && $clinic->user_id) {
    //             DB::table('users')
    //                 ->where('id', $clinic->user_id)
    //                 ->update([
    //                     'password' => Hash::make($confirmpassword),
    //                 ]);

    //             return redirect()->route('clinic.index')->with('success', 'Clinic password updated successfully.');
    //         } else {
    //             return redirect()->route('clinic.index')->with('error', 'User not found for this clinic.');
    //         }
    //     } else {
    //         return redirect()->route('clinic.index')->with('error', 'Password and confirm password do not match.');
    //     }
    // }

    public function password_update(Request $request)
    {
        $newpassword = $request->newpassword;
        $confirmpassword = $request->confirmpassword;

        if ($newpassword === $confirmpassword) {
            // Get the user_id from the clinic
            $clinic = DB::table('clinic')->where('clinic_id', $request->id)->first();

            if ($clinic && $clinic->user_id) {
                // Update password in users table
                DB::table('users')
                    ->where('id', $clinic->user_id)
                    ->update([
                        'password' => Hash::make($confirmpassword),
                    ]);

                // Update password in clinic table
                DB::table('clinic')
                    ->where('clinic_id', $request->id)
                    ->update([
                        'password' => Hash::make($confirmpassword),
                    ]);

                return redirect()->route('clinic.index')->with('success', 'Clinic password updated successfully.');
            } else {
                return redirect()->route('clinic.index')->with('error', 'User not found for this clinic.');
            }
        } else {
            return redirect()->route('clinic.index')->with('error', 'Password and confirm password do not match.');
        }
    }

    public function ordercreate(Request $request)
    {
        $start_date = ($request->start_date);
        $end_date = ($request->end_date);
        $amount = ($request->amount);
        $clinic_id = ($request->clinic_id);

        $Clinicorder = Clinic_Order::create([
            'start_date'    => $start_date,
            'end_date'   => $end_date,
            'amount' => $amount,
        ]);


        // dd('if');
        $Password = DB::table('clinic')
            ->where(['clinic_id' => $clinic_id])
            ->update([
                'start_date' => $start_date,
                'end_date' => $end_date,
                'iorder_id' => $Clinicorder->Clinic_order_id,


            ]);
        // dd($Password);
        return redirect()->route('clinic.index')->with('success', 'Clinic order Create Successfully.');
    }
}
