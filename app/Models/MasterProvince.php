<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterProvince extends Model
{
	protected $table = 'master_province';
	protected $primaryKey = 'id';
	 protected $fillable = [
        'ID',
        'COUNTRYID',
        'DESCRIPTION',
        'ACTIVE',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
    ];
}
