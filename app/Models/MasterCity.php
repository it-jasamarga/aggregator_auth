<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterCity extends Model
{
	protected $table = 'master_city';
	protected $primaryKey = 'id';
	protected $fillable = [
        'ID',
        'PROVINCEID',
        'DESCRIPTION',
        'ACTIVE',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
    ];
}
