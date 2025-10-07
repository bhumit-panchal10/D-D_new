<?php

namespace App\Http\Controllers;

use PDF; // Import PDF at the top
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Patient;
use App\Models\Treatment;
use App\Models\PatientTreatment;

class OrderController extends Controller
{
    public function index($patient_id)
    {
        $orders = Order::where(['patient_id' => $patient_id, 'clinic_id' => auth()->user()->clinic_id])->paginate(config('app.per_page'));
        $patient = Patient::findOrFail($patient_id);
        return view('orders.index', compact('orders', 'patient'));
    }

    // SHOW CREATE FORM
    public function create($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $invoice_no = Order::max('invoice_no') + 1; // Auto-increment invoice
        $patientTreatments = PatientTreatment::where('patient_id', $patient_id)
            ->where('is_billed', 0) // Show only unbilled treatments
            ->get();

        return view('orders.create', compact('patient', 'invoice_no', 'patientTreatments'));
    }

    // STORE ORDER & ORDER DETAILS
    public function store(Request $request)
    {
        // Validate request
        $request->validate([
            'patient_id' => 'required',
            'invoice_no' => 'required|unique:orders,invoice_no',
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
        $order = Order::create([
            'patient_id' => $request->patient_id,
            'invoice_no' => $request->invoice_no,
            'date'       => $request->date,
            'amount'     => $total_amount,
            'discount'   => $total_discount,
            'net_amount' => $total_net_amount,
            'clinic_id' => auth()->user()->clinic_id,

        ]);

        // Store OrderDetails
        foreach ($request->patient_treatment_id as $key => $pt_id) {
            $patientTreatment = PatientTreatment::findOrFail($pt_id);

            OrderDetail::create([
                'order_id'           => $order->id,
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
            $patientTreatment->update(['is_billed' => 1]);
        }

        return redirect()->route('orders.index', $request->patient_id)->with('success', 'Order created successfully.');
    }


    public function destroy($id)
    {
        // Find Order
        $order = Order::findOrFail($id);

        // Get all related OrderDetails
        $orderDetails = OrderDetail::where('order_id', $id)->get();

        // Restore patient treatments (set is_billed = 0)
        foreach ($orderDetails as $detail) {
            PatientTreatment::where('id', $detail->patient_treatment_id)->update(['is_billed' => 0]);
        }

        // Delete order details first (to avoid foreign key issues)
        OrderDetail::where('order_id', $id)->delete();

        // Delete the order
        $order->delete();

        return redirect()->route('orders.index', $order->patient_id)->with('success', 'Invoice deleted successfully, and treatments are available again.');
    }


    public function generateInvoice($id)
    {
        $order = Order::with('orderDetails.patientTreatment.treatment')->findOrFail($id);

        $pdf = PDF::loadView('orders.invoice', compact('order'));

        return $pdf->stream("invoice_{$order->invoice_no}.pdf"); // Opens in new tab
    }
}
