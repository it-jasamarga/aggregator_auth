<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Models\SantunanDuka;
use App\Models\EmployeePosition;
class PayslipSingleByEmployeeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $result = [];
        if($request->periode){
            $periode = str_replace('.','',$request->periode);
            $parsePeriode = Carbon::createFromFormat('mY',$periode)->format('Y-m');
            $santunanDuka = SantunanDuka::get();
            if($santunanDuka->count() > 0){
                foreach($santunanDuka as $k => $value){  
                    if(Carbon::parse($value->keydate)->format('Y-m') == $parsePeriode){
                        array_push($result,$value);
                    } 
                }
            }
        }
        return [
            'id' => $this->id,
            'name' => $this->name,
            'npp' => $this->npp,
            'kd_comp' => $this->kd_comp,
            'personnel_number' => $this->personnel_number,
            'santunan_duka' => SantunanDukaResource::collection($result),
            'payslip' => PayslipResource::collection($this->empPayslip)
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
