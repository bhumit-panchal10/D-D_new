<?php

namespace App\Http\Controllers;

use PDF; // Import PDF at the top
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationDetail;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\PatientTreatment;

class QuatationController extends Controller
{
    public function index($patient_id)
    {
        $qutations = Quotation::where(['patient_id' => $patient_id, 'clinic_id' => auth()->user()->clinic_id])->paginate(config('app.per_page'));
        $patient = Patient::findOrFail($patient_id);
        return view('quotation.index', compact('qutations', 'patient'));
    }

    // SHOW CREATE FORM
    public function create($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $lastQuotation = Quotation::orderBy('id', 'desc')->first();
        $lastNumber = 0;

        if ($lastQuotation && preg_match('/quota_(\d+)/', $lastQuotation->quotation_no, $matches)) {
            $lastNumber = (int)$matches[1];
        }

        $quotation_no = 'quota_' . str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);
        $patientTreatments = PatientTreatment::where('patient_id', $patient_id)
            ->where('is_quotation_billed', 0) // Show only unbilled treatments
            ->get();


        return view('quotation.create', compact('patient', 'quotation_no', 'patientTreatments'));
    }

    // STORE ORDER & ORDER DETAILS
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'patient_id' => 'required',
            'quotation_no' => 'required|unique:quotation,quotation_no',
            'date'       => 'required|date',
            'patient_treatment_id' => 'required|array',
            'qty'        => 'required|array',
            'rate'       => 'required|array',
            'amount'     => 'required|array',
            'discount'   => 'nullable|array',
            'net_amount' => 'required|array',
        ]);

        // Calculate Order Total
        $total_amount = array_sum($request->amount);
        $total_discount = array_sum($request->discount);
        $total_net_amount = array_sum($request->net_amount);

        // Create Order Entry
        $quotation = Quotation::create([
            'patient_id' => $request->patient_id,
            'quotation_no' => $request->quotation_no,
            'date'       => $request->date,
            'amount'     => $total_amount,
            'discount'   => $total_discount,
            'net_amount' => $total_net_amount,
            'clinic_id' => auth()->user()->clinic_id,

        ]);


        // Store OrderDetails
        foreach ($request->patient_treatment_id as $key => $pt_id) {
            $patientTreatment = PatientTreatment::findOrFail($pt_id);

            QuotationDetail::create([
                'quotation_id'           => $quotation->id ?? 0,
                'patient_id'         => $request->patient_id,
                'treatment_id'       => $patientTreatment->treatment_id,
                'patient_treatment_id' => $patientTreatment->id,
                'clinic_id' => auth()->user()->clinic_id,
                'qty'                => $request->qty[$key],
                'rate'               => $request->rate[$key],
                'amount'             => $request->amount[$key],
                'discount'           => $request->discount[$key],
                'net_amount'         => $request->net_amount[$key],
            ]);

            // Update `is_billed = 1` for each patient_treatment_id
            $patientTreatment->update(['is_quotation_billed' => 1]);
        }

        return redirect()->route('quotation.index', $request->patient_id)->with('success', 'Quotation created successfully.');
    }


    public function destroy($id)
    {

        $quotation = Quotation::findOrFail($id);

        // Get all related OrderDetails
        $QuotationDetails = QuotationDetail::where('quotation_id', $id)->get();

        // Restore patient treatments (set is_billed = 0)
        foreach ($QuotationDetails as $detail) {
            PatientTreatment::where('id', $detail->patient_treatment_id)->update(['is_quotation_billed' => 0]);
        }

        // Delete order details first (to avoid foreign key issues)
        QuotationDetail::where('quotation_id', $id)->delete();

        // Delete the order
        $quotation->delete();

        return redirect()->route('quotation.index', $quotation->patient_id)->with('success', 'Quotation deleted successfully, and treatments are available again.');
    }


    public function generateInvoice($id)
    {
        $qutation = Quotation::with('qutationDetails.patientTreatment.treatment')->findOrFail($id);
        $pdf = PDF::loadView('quotation.invoice', compact('qutation'));

        return $pdf->stream("invoice_{$qutation->quotation_no}.pdf"); // Opens in new tab
    }
}
