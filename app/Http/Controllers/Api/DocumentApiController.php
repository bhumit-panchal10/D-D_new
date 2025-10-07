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
use App\Models\Document;
use GuzzleHttp\Client;
use App\Models\Clinic;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Razorpay\Api\Api;
use Illuminate\Validation\Rule;


class DocumentApiController extends Controller

// class DriverApiController extends PushNotificationController
{


    public function Documentlist(Request $request)
    {
        try {
            $request->validate([
                'clinic_id' => 'required|numeric'
            ]);

            $listOfDocument = Document::with('treatment')->where('clinic_id', $request->clinic_id)->get();
            $data = [];
            foreach ($listOfDocument as $doc) {
                $data[] = [

                    'id' => $doc->id,
                    'patient_id' => $doc->patient_id,
                    'treatment_id' => $doc->treatment->treatment_name ?? '',
                    'comment' => $doc->comment,
                    'created_at' => date('m-d-Y', strtotime($doc->created_at)),
                    'document' => asset(
                        '/D&D_DENTAL_CLINIC/documents/' . $doc->document
                    ),
                ];
            }
            return response()->json([
                'success' => true,
                'message' => "successfully fetched documentlist...",
                'data' => $data,
            ], 200);
        } catch (\Throwable $th) {

            DB::rollBack();

            return response()->json(['error' => $th->getMessage()], 500);
        }
    }
    public function AddDocument(Request $request)
    {

        try {
            // Validate the incoming request
            $messages = [
                'document.mimes' => 'The document must be a file of type: doc, docx, pdf, jpg, jpeg, png.',
            ];
            $request->validate([

                'document' => 'required|mimes:doc,docx,pdf',
                'patient_id' => 'required',
                'treatment_id' => 'required',
                'patient_treatment_id' => 'required',
                'clinic_id' => 'required',
                'comment' => 'required',


            ], $messages);
            $img = "";
            if ($request->hasFile('document')) {
                $root = $_SERVER['DOCUMENT_ROOT'];
                $image = $request->file('document');
                $img = time() . '_' . date('dmYHis') . '.' . $image->getClientOriginalExtension();
                $destinationpath = $root . '/dental_clinic_new/D&D_DENTAL_CLINIC/documents/';
                if (!file_exists($destinationpath)) {
                    mkdir($destinationpath, 0755, true);
                }
                $image->move($destinationpath, $img);
            }
            Document::create([
                'patient_id' => $request->patient_id,
                'treatment_id' => $request->treatment_id,
                'patient_treatment_id' => $request->patient_treatment_id,
                'clinic_id' => $request->clinic_id,
                'comment' => $request->comment,
                'document' => $img,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Document Add Successfully',
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    public function Documentdelete(Request $request)
    {
        try {
            $request->validate([

                "id" => 'required'
            ]);
            $Document = Document::find($request->id);


            if ($Document) {
                $Document->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Document Deleted Successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Document not found',
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
