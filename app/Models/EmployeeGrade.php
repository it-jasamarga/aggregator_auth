<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Filters\Filterable;

class EmployeeGrade extends Model
{
    use Filterable;

	protected $table = 'employee_grade';
	protected $primaryKey = 'id';
	protected $fillable = [
		'ID',
		'EMPLOYEE_ID',
		'BEGIN_DATE',
		'END_DATE',
		'GRADE',
		'SUB_GRADE',
		'KELOMPOK_JABATAN',
		'PAY_SCALE_AREA',
		'CREATED_AT',
		'UPDATED_AT',
		'CREATED_BY',
		'UPDATED_BY',
	];

	public function employee(){
		return $this->belongsTo(Employee::class,'employee_id');
	}
}
