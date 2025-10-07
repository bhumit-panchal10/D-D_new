<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PatientTreatment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'treatment_id',
        'tooth_selection',
        'is_billed',
        'is_quotation_billed',
        'quotation_give',
        'rate',
        'qty',
        'amount',
        'clinic_id',
        'doctor_id'

    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function treatment()
    {
        return $this->belongsTo(Treatment::class);
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            // Calculate `qty` based on comma-separated `tooth_selection`
            $model->qty = $model->tooth_selection ? count(explode(',', $model->tooth_selection)) : 0;

            // Calculate `amount`
            $model->amount = $model->rate * $model->qty;
        });
    }
}
