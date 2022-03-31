<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\MasterCompany;

class SantunanDukaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $company = [];
        if(($this->employee->unitKerja) && isset($this->employee->unitKerja->company_id)){
            $company = MasterCompany::find($this->employee->unitKerja->company_id);
        }

        return [
            'id' => $this->id,
            'employee_id' => $this->employee_id,
            'employee_name' => $this->employee->name,
            'employee_npp' => $this->employee->npp,
            'employee_kd_comp' => $this->employee->kd_comp,
            'employee_personnel_number' => $this->employee->personnel_number,
            'employee_unit_kerja' => ($this->employee->unitKerja) ? $this->employee->unitKerja->name : null,
            'employee_company' => ($company) ? $company->name : null,
            'status' => $this->status,
            'keydate' => $this->keydate
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
