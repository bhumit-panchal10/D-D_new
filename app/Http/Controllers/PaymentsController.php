<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class PaymentsController extends Controller
{
    public function index($patient_id)
    {
        $patient = Patient::findOrFail($patient_id);
        $payments = Payment::where(['patient_id' => $patient_id, 'clinic_id' => auth()->user()->clinic_id])->paginate(config('app.per_page'));

        return view('payments.index', compact('patient', 'payments'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'mode' => 'required|in:Cash,Online',
            'comments' => 'nullable|string'
        ]);

        $order = Order::findOrFail($request->order_id);
        $payment = Payment::create([
            'patient_id' => $order->patient_id,
            'order_id' => $request->order_id,
            'payment_date' => $request->payment_date,
            'amount' => $request->amount,
            'mode' => $request->mode,
            'comments' => $request->comments,
            'clinic_id' => auth()->user()->clinic_id,

        ]);
        $patient_id = $payment->patient_id;
        $clinic_id = $payment->clinic_id;
        $Payment = Payment::with('clinic', 'patient', 'order')->where(['patient_id' => $patient_id, 'clinic_id' => $clinic_id])->first();

        // WhatsApp API credentials
        $whatsappToken = 'EAATZAZAlCLXjEBO3L964MCRbqZA8kRj95hjONF6DRUaZCkd3bk2LzHvKbvV72eZCqMEOjm9pVaEG9ZCvFd2m1GsxFkysBQPXYmVbE7HSVdrrut3PijBInprtr4KTwvPGbQw0b2AHlIpfgGyeKSOosoc05ztRw8W1y0hlZC84U4ZAW31CikzFYNjtKyc2FgQ03wqi4QZDZD'; // Replace with your WhatsApp API token
        $phoneNumberId = '658603253999245'; // Replace with your phone number ID

        // WhatsApp message data
        $patientname = $Payment->patient->name;

        $patientmobile = $Payment->patient->mobile1;

        $amount = number_format($Payment->order->net_amount, 2);


        $clinicName = $Payment->clinic->name ?? ''; // Or get from auth()->user()->clinic->name

        $contactNumber = $Payment->clinic->mobile_no ?? '';

        $recipient = '+91' . ($patientmobile ?? '7486984607'); // Fallback if not set
        try {
            $response = Http::withToken($whatsappToken)->post("https://graph.facebook.com/v19.0/{$phoneNumberId}/messages", [
                "messaging_product" => "whatsapp",
                "to" => $recipient,
                "type" => "template",
                "template" => [
                    "name" => "thanks_for_payment1",
                    "language" => [
                        "code" => "en_US"
                    ],
                    "components" => [
                        [
                            "type" => "header",
                            "parameters" => [
                                ["type" => "text", "text" => $clinicName]
                            ]
                        ],
                        [
                            "type" => "body",
                            "parameters" => [
                                ["type" => "text", "text" => $patientname],
                                ["type" => "text", "text" => $amount],
                                ["type" => "text", "text" => $clinicName],
                                ["type" => "text", "text" => $contactNumber],
                                ["type" => "text", "text" => $clinicName . ' Care']

                            ]
                        ]
                    ]
                ]
            ]);

            if (!$response->successful()) {
                Log::error('WhatsApp API Error', [
                    'status' => $response->status(),
                    'body' => $response->json()
                ]);

                return back()->with('error', 'WhatsApp API Error: ' . json_encode($response->json()));
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp API Exception', [
                'message' => $e->getMessage()
            ]);

            return back()->with('error', 'Exception: ' . $e->getMessage());
        }

        return redirect()->back()->with('success', 'Payment added successfully.');
    }

    public function edit(Payment $payment)
    {
        $patient = Patient::findOrFail($payment->patient_id);

        return view('payments.edit', compact('payment', 'patient'));
    }

    public function update(Request $request, Payment $payment)
    {
        $request->validate([
            'payment_date' => 'required|date',
            'amount' => 'required|numeric|min:1',
            'mode' => 'required|in:Cash,Online',
            'comments' => 'nullable|string'
        ]);

        $payment->update($request->all());

        return redirect()->route('payments.index', $payment->patient_id)->with('success', 'Payment updated successfully.');
    }

    public function destroy($id)
    {
        Payment::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Payment deleted successfully.');
    }
}
