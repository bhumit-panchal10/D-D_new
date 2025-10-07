<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderDetail extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'patient_id', 'treatment_id', 'patient_treatment_id', 'qty', 'rate', 'amount', 'discount', 'net_amount', 'clinic_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function patientTreatment()
    {
        return $this->belongsTo(PatientTreatment::class);
    }
}
