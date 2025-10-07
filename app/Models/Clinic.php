<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class Clinic extends Authenticatable implements JWTSubject
{
    use HasFactory;

    public $table = 'clinic';
    protected $primaryKey = 'clinic_id';

    protected $fillable = [
        'clinic_id',
        'name',
        'doctor',
        'mobile_no',
        'password',
        'state',
        'city',
        'start_date',
        'end_date',
        'iorder_id',
        'logo',
        'user_id',
        'email',
        'address',
        'casePrefix',
        'casePostfix',
        'iStatus',
        'isDelete',
        'strIP',
        'created_at',
        'updated_at'
    ];

    protected $hidden = [
        'password',
    ];

    public function caseCounters()
    {
        return $this->hasMany(ClinicCaseCounters::class);
    }

    /**
     * Tell Laravel that 'mobile_no' is the login field.
     */
    public function getAuthIdentifierName()
    {
        return 'mobile_no';
    }

    /**
     * Get the identifier that will be stored in the JWT token.
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key-value array containing any custom JWT claims.
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
