<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EmployeeGradeByEmployeeResource extends JsonResource
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
            'name' => $this->name,
            'npp' => $this->npp,
            'kd_comp' => $this->kd_comp,
            'personnel_number' => $this->personnel_number,
            'history_grade' => EmployeeGradeResource::collection($this->allGrade)
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
