<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterStatusKeluarga extends Model
{
	protected $primaryKey = 'id';
	protected $table = 'master_status_keluarga';
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
