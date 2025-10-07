<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_name',
        'contact_person_name',
        'mobile',
        'email',
        'address',
        'clinic_id',

    ];
}
