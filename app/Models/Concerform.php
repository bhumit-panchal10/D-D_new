<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Concerform extends Model
{
    use HasFactory;

    public $table = 'concern_forms';
    protected $primaryKey = 'iConcernFormId';

    protected $fillable = ['iConcernFormId', 'clinic_id', 'strConcernFormTitle', 'strConcernFormText', 'deleted_at', 'created_at', 'updated_at', 'strIP'];
}
