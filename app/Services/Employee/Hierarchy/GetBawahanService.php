<?php

namespace App\Services\Employee\Hierarchy;

use App\Models\Employee;
use App\Models\EmployeePosition;

class GetBawahanService
{	
	/**
	 * remap data from EmployeeRepository::getEmployeeWithBawahan() so we can get response as designed
	 * remapBawahan
	 *
	 * @param  mixed $employee
	 * @return Employee
	 */
	public function remapBawahan(?Employee $employee): ?Employee
	{

		if($employee == null)
		{
			return $employee;
		}

		$tempBawahan = [];
		$tempBawahanOne = [];
		$employeePositionAp = EmployeePosition::with('employee')->where('atasan_ap_id',$employee->id)
			->where('atasan_ap_position_id',$employee->position_id)
			->where('active','1')
			->get();

		// employee relation hasMany
		foreach($employee->position as $position)
		{

			// employee_position relation to bawahan()
			foreach($position->bawahan as $row)
			{
				$employeePosition = EmployeePosition::find($row->id);
				if($employeePosition){
					if(is_null($employeePosition->atasan_ap_id)){
						$grade = ($employeePosition) ? $employeePosition->grade : null;
						$sub_grade = ($employeePosition) ? $employeePosition->sub_grade : null;
						$fullGrade = null;
						if($grade){
							$fullGrade = $grade;
						}

						if($sub_grade){
							$fullGrade = (!is_null($fullGrade)) ? $fullGrade.'.'.$sub_grade : null;
						}
						

						$tempBawahan[] = [
							"person_name" => $row->person_name,
		                    "employee_number" => $row->employee_number,
		                    "position_id" => $row->position_id,
		                    "position_name" => $row->position_name,
		                    "unit_kerja_id" => $row->unit_kerja_id,
		                    "unit_kerja_name" => $row->unit_kerja_name,
		                    "unit_kerja_type_org" => $row->unit_kerja_type_org,
		                    "organization_id" => $row->organization_id,
		                    "organization_name" => $row->organization_name,
		                    "kd_grade" => $employeePosition->grade,
		                    "grade" => $fullGrade,
							"kd_comp" => ($employeePosition->company) ? $employeePosition->company->code : null,
							"comp" => ($employeePosition->company) ? $employeePosition->company->name : null,
							'job_id' => ($employeePosition->job) ? $employeePosition->job->id : null,
							'job_name' => ($employeePosition->job) ? $employeePosition->job->name : null
						];
					}
				}
			}
		}

		if($employeePositionAp){
			if($employeePositionAp->count() > 0){
				foreach($employeePositionAp as $k => $value){
					$grade = ($value) ? $value->grade : null;
					$sub_grade = ($value) ? $value->sub_grade : null;
					$fullGrade = null;
					if($grade){
						$fullGrade = $grade;
					}

					if($sub_grade){
						$fullGrade = (!is_null($fullGrade)) ? $fullGrade.'.'.$sub_grade : null;
					}
					
					$masterOrganization = [];
					if($value->masterPosition){
						$masterOrganization = $value->masterPosition->masterOrganization;
					}
					$dataPush = [
						"person_name" => ($value->employee) ? $value->employee->name : null,
	                    "employee_number" => ($value->employee) ? $value->employee->npp : null,
	                    "position_id" => $value->position_id ?? null,
	                    "position_name" => $value->position ?? null,
	                    "unit_kerja_id" => $value->unit_kerja_id ?? null,
	                    "unit_kerja_name" => $value->unit_kerja ?? null,
	                    "unit_kerja_type_org" => ($value->masterUnitKerja) ? $value->masterUnitKerja->type_organization : null,
	                    "organization_id" => ($masterOrganization) ? $masterOrganization->id : null,
	                    "organization_name" => ($masterOrganization) ? $masterOrganization->name : null,
	                    "kd_grade" => $value->grade,
	                    "grade" => $fullGrade,
						"kd_comp" => ($value->company) ? $value->company->code : null,
						"comp" => ($value->company) ? $value->company->name : null,
						'job_id' => ($value->job) ? $value->job->id : null,
						'job_name' => ($value->job) ? $value->job->name : null
					];

					if($dataPush){
						array_push($tempBawahan, $dataPush);
					}
				}
			}
		}

		$employee->bawahan = $tempBawahan;

		$employee->unsetRelation('position');

		return $employee;
	}
}
