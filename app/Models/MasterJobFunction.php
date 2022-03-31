<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJobFunction extends Model
{
	protected $table = 'master_jobfunction';
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
