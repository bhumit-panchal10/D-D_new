<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class ApiUser extends Authenticatable implements JWTSubject
{
    protected $table = 'users'; // Same table as User model

    protected $fillable = [
        'name',
        'mobile_number',
        'email',
        'address',
        'role_id',
        'password',
        'clinic_id',
        'status'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    // JWT methods
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
