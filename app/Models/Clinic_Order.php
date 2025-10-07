<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Clinic_Order extends Model
{
    use HasFactory;
    public $table = 'Clinic_order';
    protected $primaryKey = 'Clinic_order_id';
    protected $fillable = [
        'Clinic_order_id',
        'start_date',
        'end_date',
        'amount',
        'iStatus',
        'isDelete',
        'strIP',
        'created_at',
        'updated_at'



    ];
}
