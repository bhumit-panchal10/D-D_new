<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = ['patient_id', 'order_id', 'payment_date', 'amount', 'mode', 'comments', 'clinic_id'];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function clinic()
    {
        return $this->belongsTo(Clinic::class, 'clinic_id', 'clinic_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
