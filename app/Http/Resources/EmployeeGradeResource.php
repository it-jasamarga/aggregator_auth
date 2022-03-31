<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EmployeeGradeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
       
        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'name' => ($this->employee) ? $this->employee->name : null,
            'npp' => ($this->employee) ? $this->employee->npp : null,
            'kd_comp' => ($this->employee) ? $this->employee->kd_comp : null,
            'personnel_number' => ($this->employee) ? $this->employee->personnel_number : null,
            'begin_date' => $this->begin_date,
            'end_date' => $this->end_date,
            'grade' => $this->grade,
            'sub_grade' => $this->sub_grade,
            'kelompok_jabatan' => $this->kelompok_jabatan,
            'pay_scale_area' => $this->pay_scale_area,
        ];
    }

    public function withResponse($request, $response)
    {
        $jsonResponse = json_decode($response->getContent(), true);
        $response->setContent(json_encode([
            'status' => true,
            'code' => 200,
            'message' => 'Success Detail Data',
            'data' => $jsonResponse
        ]));
    }
}
