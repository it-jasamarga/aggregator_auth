<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterEmployeeGroup extends Model
{
	protected $table = 'master_employee_group';
	protected $primaryKey = 'id';
	protected $fillable = [
        'ID',
        'DESCRIPTION',
        'ACTIVE',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
    ];
}
