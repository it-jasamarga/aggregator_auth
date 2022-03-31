<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterJurusanPendidikan extends Model
{
	protected $table = 'master_jurusan_pendidikan';
	protected $primaryKey = 'id';
	protected $fillable = [
		'ID',
		'NAME',
		'ACTIVE',
		'CREATED_AT',
		'UPDATED_AT',
		'CREATED_BY',
		'UPDATED_BY',
	];
}
