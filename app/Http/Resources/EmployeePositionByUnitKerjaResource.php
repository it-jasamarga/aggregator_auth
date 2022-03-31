<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use App\Models\MapPositionSubcluster;

class EmployeePositionByUnitKerjaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        
        $organization = [];
        if($this->masterPosition){
            $organization = $this->masterPosition->masterOrganization;
        }

        $mapPositionSubcluster = [];
        $masterSubCluster = [];
        $mapPositionSubcluster = MapPositionSubcluster::where('position_name',$this->position)->where('unit_kerja_id',$this->unit_kerja_id)->first();
        if($mapPositionSubcluster){
            $masterSubCluster = $mapPositionSubcluster->masterSubcluster;
        }


        return [
            'employee_id' => $this->employee_id,
            'position_id' => $this->position_id,
            'kd_comp' => $this->kd_comp,
            'position_name' => $this->position,
            'grade' => $this->grade,
            'subgrade' => $this->sub_grade,
            'unit_kerja_id' => $this->unit_kerja_id,
            'unit_kerja' => $this->unit_kerja,
            'layer' => ($this->masterLayer) ? $this->masterLayer->description : null,
            'organization_id'=> ($organization) ? $organization->id : null,
            'organization_name'=> ($organization) ? $organization->name : null,
            'costcenter'=> ($organization) ? $organization->costcenter : null,
            'cluster_kode'=> ($masterSubCluster) ? $masterSubCluster->cluster_kode : null,
            'subcluster_code'=> ($masterSubCluster) ? $masterSubCluster->kode : null,
            'subcluster_name'=> ($masterSubCluster) ? $masterSubCluster->name : null,
            'subcluster_fungsi'=> ($masterSubCluster) ? $masterSubCluster->fungsi : null,
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
