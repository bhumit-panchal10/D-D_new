<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\PatientAppointment;
use Illuminate\Support\Facades\Log;

use App\Mail\AppointmentReminderToPatient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class SendAppointmentReminderToPatient extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:AppointmentReminderToPatient';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $whatsappToken = 'EAATZAZAlCLXjEBO3L964MCRbqZA8kRj95hjONF6DRUaZCkd3bk2LzHvKbvV72eZCqMEOjm9pVaEG9ZCvFd2m1GsxFkysBQPXYmVbE7HSVdrrut3PijBInprtr4KTwvPGbQw0b2AHlIpfgGyeKSOosoc05ztRw8W1y0hlZC84U4ZAW31CikzFYNjtKyc2FgQ03wqi4QZDZD'; // Replace with your WhatsApp API token
        $phoneNumberId = '658603253999245'; // Replace with your phone number ID


        // Get tomorrow's appointments
        $appointments = PatientAppointment::with(['patient', 'doctor', 'clinic'])
            ->whereDate('appointment_date', now())
            ->get();

        if ($appointments->isEmpty()) {
            $this->info("No appointments for tomorrow.");
            return;
        }

        foreach ($appointments as $appointment) {
            $patient = $appointment->patient;
            $doctor = $appointment->doctor;

            // Skip if any data is missing
            if (!$patient || !$doctor || !$patient->mobile1) {
                continue;
            }

            $clinicName = $appointment->clinic->name ?? 'Your Clinic';
            $clinicMobile = $appointment->clinic->mobile_no ?? '0000000000';



            $recipient = '+91' . $patient->mobile1;
            $doctorName = 'Dr. ' . $doctor->name;
            $patientName = $patient->name;
            $appointmentDate = \Carbon\Carbon::parse($appointment->appointment_date)->format('d-m-Y');
            $appointmentTime = \Carbon\Carbon::parse($appointment->appointment_time)->format('h:i A');

            $response = Http::withToken($whatsappToken)->post("https://graph.facebook.com/v19.0/{$phoneNumberId}/messages", [
                "messaging_product" => "whatsapp",
                "to" => $recipient,
                "type" => "template",
                "template" => [
                    "name" => "appointment_reminder_for_patient1",
                    "language" => ["code" => "en_US"],
                    "components" => [
                        [
                            "type" => "header",
                            "parameters" => [
                                ["type" => "text", "text" => $patientName]
                            ]
                        ],
                        [
                            "type" => "body",
                            "parameters" => [
                                ["type" => "text", "text" => $patientName],
                                ["type" => "text", "text" => $clinicName],
                                ["type" => "text", "text" => $appointmentDate],
                                ["type" => "text", "text" => $appointmentTime],
                                ["type" => "text", "text" => $clinicMobile],
                                ["type" => "text", "text" => $clinicName],
                            ]
                        ]
                    ]
                ]
            ]);

            // Logging success/failure
            if ($response->successful()) {
                $this->info("Reminder sent to {$patientName} ({$recipient})");
            } else {
                Log::error('WhatsApp Send Error', [
                    'patient_id' => $patient->id,
                    'response' => $response->json()
                ]);
            }
        }

        return 0;
    }
}
