<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterEmployeesSubGroup extends Model
{
    protected $table = 'MASTER_EMPLOYEE_SUBGROUP';
    protected $fillable = [
        'ID',
        'SUBGROUP',
        'KEY',
        'ACTIVE',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
    ];
}
