<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EmployeeEducationResource extends JsonResource
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
            'jenjang_pendidikan' => ($this->jenjang) ? $this->jenjang->jenjang : null,
            'jurusan_pendidikan' => ($this->jurusan) ? $this->jurusan->name : null,
            'name' => $this->name,
            'address' => $this->address,
            'country_id' => $this->country_id,
            'country_text' => ($this->country) ? $this->country->description : null,
            'start_date' => $this->start_date,
            'graduate_date' => $this->graduate_date,
            'title' => $this->title,
            'no_ijazah' => $this->no_ijazah,
            'tanggal_ijazah' => $this->tanggal_ijazah,
            'attachment_ijazah' => $this->attachment_ijazah,
            'final_score' => $this->final_score
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
