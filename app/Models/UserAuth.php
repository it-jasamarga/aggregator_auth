<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Tymon\JWTAuth\Contracts\JWTSubject;
use JWTAuth;
use Auth;

class UserAuth extends Authenticatable implements JWTSubject
{
    protected $table = 'USER_AUTH';
    protected $fillable = [
        'ID',
        'EMPLOYEE_ID',
        'USERNAME',
        'PASSWORD',
        'IS_LDAP',
        'IS_MOBILE',
        'ACTIVE',
        'PASSWORD_EXPIRES',
        'TOKEN',
        'TOKEN_FIREBASE',
        'LAST_LOGIN',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }

    public function getToken(){
        return (\Auth::check()) ? JWTAuth::fromUser(auth()->user()) : '';
    }
    
    public function employee(){
        return $this->belongsTo(Employee::class,'employee_id');
    }
}
