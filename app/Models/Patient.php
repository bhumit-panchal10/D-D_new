<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'mobile1',
        'mobile2',
        'dob',
        'address',
        'pincode',
        'reference_by',
        'clinic_id',
        'case_no',
        'gender'



    ];
}
