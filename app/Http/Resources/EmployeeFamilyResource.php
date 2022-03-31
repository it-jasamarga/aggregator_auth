<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EmployeeFamilyResource extends JsonResource
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
            'date_of_birth' => $this->date_of_birth,
            'place_of_birth' => $this->place_of_birth,
            'religion' => $this->religion,
            'gender' => $this->gender,
            'blood_type' => $this->blood_type,
            'job' => $this->job,
            'national_identifier' => $this->national_identifier,
            'paspor_no' => $this->paspor_no,
            'attachment_nikah' => $this->attachment_nikah,
            'attachment_akta' => $this->attachment_akta,
            'family_status' => ($this->masterFmStatus) ? $this->masterFmStatus->description : null,
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
