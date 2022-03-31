<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeeFamily extends Model
{
	protected $table = 'employee_family';
	protected $primaryKey = 'id';
	protected $fillable = [
		'ID',
		'EMPLOYEE_ID',
		'FAMILY_STATUS',
		'NAME',
		'PLACE_OF_BIRTH',
		'DATE_OF_BIRTH',
		'RELIGION',
		'GENDER',
		'BLOOD_TYPE',
		'JOB',
		'NATIONAL_IDENTIFIER',
		'PASPOR_NO',
		'LAST_EDUCATION',
		'PLACE_OF_DEATH',
		'DATE_OF_DEATH',
		'ATTACHMENT_NIKAH',
		'ATTACHMENT_AKTA',
		'DATE_OF_DEATH',
		'CREATED_AT',
		'UPDATED_AT',
		'CREATED_BY',
		'UPDATED_BY',
	];
	/**
	 * Get the employee that owns the EmployeeFamily
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
	 */
	public function employee(): BelongsTo
	{
		return $this->belongsTo(Employee::class, 'employee_id');
	}

	public function masterFmStatus(): BelongsTo
	{
		return $this->belongsTo(MasterStatusKeluarga::class, 'family_status');
	}

	public function getAttachmentNikahAttribute(){
		return url('storage/'.$this->attributes['attachment_nikah']);
	}

	public function getAttachmentAktaAttribute(){
		return url('storage/'.$this->attributes['attachment_akta']);
	}
}
