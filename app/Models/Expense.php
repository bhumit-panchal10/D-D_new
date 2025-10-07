<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    public $table = 'expense';

    protected $primaryKey = 'expense_id';

    protected $fillable = [
        'item_name',
        'amount',
        'enter_by',
        'mode',
        'iStatus',
        'IsDelete',
        'created_at',
        'updated_at',
        'clinic_id',

    ];
}
