<?php

namespace App\Services\Employee\Hierarchy;

use App\Models\MasterCompany;
use App\Models\EmployeePosition;

class GetAtasanService
{	
	/**
	 * used for remapping response as designed by analyst
	 * remapPeerData 
	 *
	 * @param  mixed $employee
	 * @param  mixed $peer
	 * @return void
	 */
	public function remapPeerData($data)
	{
        // dd($data);
        $empPosition = ($data) ? EmployeePosition::find($data->employee_position_ids) : null;
        $data['employee_position_kd_grade'] = ($empPosition) ? $empPosition->grade : null;
        $empGrade = ($empPosition) ? $empPosition->grade : null;
        $empSub_grade = ($empPosition) ? $empPosition->sub_grade : null;
        $empFullGrade = null;
        if($empGrade){
            $empFullGrade = $empGrade;
        }
        if($empSub_grade){
            $empFullGrade = (!is_null($empFullGrade)) ? $empFullGrade.'.'.$empSub_grade : null;
        }

        $data["employee_position_kd_grade"] = ($empPosition) ? $empPosition->grade : null;
        $data["employee_position_grade"] = $empFullGrade;
        $data["employee_position_company"] = [
            "kd_comp" => (($empPosition) && ($empPosition->company)) ? $empPosition->company->code : null,
            "comp" => (($empPosition) && ($empPosition->company)) ? $empPosition->company->name : null
        ];
        $data["employee_position_job"] = [
            'id' => (($empPosition) && ($empPosition->job)) ? $empPosition->job->id : null,
            'name' => (($empPosition) && ($empPosition->job)) ? $empPosition->job->name : null,
        ];

        // FOR ATASAN
        $position = (@$data->atasan_empeloyee_position_id) ? EmployeePosition::find($data->atasan_empeloyee_position_id) : null;
        
        $grade = ($position) ? $position->grade : null;
        $sub_grade = ($position) ? $position->sub_grade : null;
        $fullGrade = null;
        if($grade){
            $fullGrade = $grade;
        }

        if($sub_grade){
            $fullGrade = (!is_null($fullGrade)) ? $fullGrade.'.'.$sub_grade : null;
        }

        $data["atasan_kd_grade"] = ($position) ? $position->grade : null;
        $data["atasan_grade"] = $fullGrade;
        $data["atasan_company"] = [
            "kd_comp" => (($position) && ($position->company)) ? $position->company->code : null,
            "comp" => (($position) && ($position->company)) ? $position->company->name : null
        ];
        $data["atasan_job"] = [
            'id' => (($position) && ($position->job)) ? $position->job->id : null,
            'name' => (($position) && ($position->job)) ? $position->job->name : null,
        ];

        return $data;
    }

}