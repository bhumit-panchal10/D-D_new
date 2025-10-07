<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'date', 'gu_id', 'clinic_id'];

    public function prescriptionDetails()
    {
        return $this->hasMany(PrescriptionDetail::class, 'prescription_id');
    }


    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
