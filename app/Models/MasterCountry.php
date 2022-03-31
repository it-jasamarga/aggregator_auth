<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCountry extends Model
{
	protected $table = 'master_country';
	protected $primaryKey = 'id';
	protected $fillable = [
        'ID',
        'DESCRIPTION',
        'ACTIVE',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
        'CODE',
    ];
}
