<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJenjangPendidikan extends Model
{
	protected $primaryKey = 'id';
	protected $table = 'master_jenjang_pendidikan';
	protected $fillable = [
		'ID',
		'JENJANG',
		'CREATED_AT',
		'UPDATED_AT',
		'CREATED_BY',
		'UPDATED_BY	',
	];
}
