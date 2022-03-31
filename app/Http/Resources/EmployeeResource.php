<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EmployeeResource extends JsonResource
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
            'person_name' => $this->name,
            'employee_number' => $this->npp,
            'email' => $this->email,
            'telephone_no' => $this->telephone_no,
            'is_penugasan' => $this->is_penugasan,
            'date_of_birth' => $this->date_of_birth,
            'kd_comp' => $this->kd_comp,
            'age' => 49,
            // 'position' => EmployeePositionResource::collection($this->position)
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
