<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Medicine extends Model
{
    use HasFactory;

    protected $fillable = ['medicine_name', 'clinic_id', 'dosage_id', 'comment','days'];


    public function Dosage()
    {
        return $this->belongsTo(Dosage::class, 'dosage_id', 'id');
    }
}
