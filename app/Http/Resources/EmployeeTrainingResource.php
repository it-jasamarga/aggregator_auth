<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class EmployeeTrainingResource extends JsonResource
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
            'tahun' => $this->tahun,
            'pelatihan' => $this->pelatihan,
            'pelaksanaan' => $this->pelaksanaan,
            'tgl_awal' => $this->tgl_awal,
            'tgl_akhir' => $this->tgl_akhir,
            'hari' => $this->hari,
            'tempat' => $this->tempat,
            'kota' => $this->kota,
            'inisiator' => $this->inisiator,
            'no_penugasan' => $this->no_penugasan,
            'klp_plth1' => $this->klp_plth1,
            'klp_plth2' => $this->klp_plth2,
            'negara' => $this->negara,
            'npp' => $this->npp,
            'nama' => $this->nama,
            'gol' => $this->gol,
            'jabatan' => $this->jabatan,
            'unitkerja' => $this->unitkerja,
            'nosertifikat' => $this->nosertifikat,
            'nilai' => $this->nilai,
            'peringkat' => $this->peringkat,
            'kdcomp' => $this->kdcomp,
            'biaya' => $this->biaya,
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
