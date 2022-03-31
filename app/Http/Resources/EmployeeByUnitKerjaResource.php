<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EmployeeByUnitKerjaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $position = $this->position()->where('active','1')->where('unit_kerja_id',request()->UnitID)->get();

        return [
            'id' => $this->id,
            'person_name' => $this->name,
            'employee_number' => $this->npp,
            'kd_comp' => $this->kd_comp,
            'email' => $this->email,
            'position' => EmployeePositionByUnitKerjaResource::collection($position)
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
