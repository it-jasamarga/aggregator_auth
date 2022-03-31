<?php

namespace App\Services\Employee\Hierarchy;

use App\Models\Employee;

class GetPeerService
{	
	/**
	 * used for remapping response as designed by analyst
	 * remapPeerData 
	 *
	 * @param  mixed $employee
	 * @param  mixed $peer
	 * @return void
	 */
	public function remapPeerData(?Employee $employee, ?Employee $peer)
	{
		if($employee == null || $peer == null)
		{
			return [];
		}

		$data = [
			"person_name" => $employee->person_name,
            "employee_number" => $employee->employee_number,
            "position_id" => $employee->position_id,
            "position_name" => $employee->position_name,
            "position_id_atasan" => $employee->position_id_atasan,
            "position_name_atasan" => $employee->position_name_atasan,
            "unit_kerja_id" => $employee->unit_kerja_id,
            "unit_kerja" => $employee->unit_kerja_name,
			"unit_kerja_type_org" => $employee->unit_kerja_type_org,
            "organization_id" => $employee->organization_id,
            "organization_name" => $employee->organization_name,
		];

		foreach($peer->bawahan as $row)
		{
			if($row['employee_number'] == $employee->employee_number)
			{
				continue; 
			}
			$data['data_peer'][] = [
				"person_name" => $row['person_name'],
				"employee_number" => $row['employee_number'],
				"position_id" => $row['position_id'],
				"position_name" => $row['position_name'],
				"unit_kerja_id" => $row['unit_kerja_id'],
				"unit_kerja_name" => $row['unit_kerja_name'],
				"unit_kerja_type_org" => $row['unit_kerja_type_org'],
				"organization_id" => $row['organization_id'],
				"organization_name" => $row['organization_name'],
				"kd_comp" => $row['kd_comp'],
				"comp" => $row['comp'],
				'job_id' => $row['job_id'],
				'job_name' => $row['job_name']
			];
		}

		return $data;
	}
}
