<?php

namespace App\Services\Employee;

use App\Models\Employee;
use App\Models\EmployeePosition;
use App\Models\EmployeeEducation;
use App\Models\MasterCompany;

class GetOneEmployeeService
{	
	/**
	 * Remap api response
	 * remapOneEmployee
	 *
	 * @param  mixed $employee
	 * @return Employee
	 */
	public function remapOneEmployee(?Employee $employee): ?Employee
	{
		// set field to remove from root object data (biar rapih sesuai endpoint yang didesain analis)
		$unset = [
			'address_ktp',
			'cityid_ktp',
			'provinceid_ktp',
			'address_domicile',
			'cityid_domicile',
			'bpjs_kes_no',
			'bpjs_ket_no',
			'blood_type',
			'weight',
			'height',
			'marital_status',
			'religion',
			'town_of_birth',
			'date_of_birth',
			'telephone_no',
		];

		// relation remove
		$unsetRelation = [
			'latestEducation',
            'cityKtp',
            'provinceKtp',
            'cityDomicile',
            'provinceDomicile',
            'allEducation',
            'empGroup',
			'empSubGroup',
		];

		if($employee == null)
		{
			return null;
		}

		$data = $employee;
		// dd($data);
		$data->url_image = 'https://jmclick.jasamarga.co.id/jmstars_api'.$employee->url_image;
		$data->attachment_kk = url('storage/'.$employee->attachment_kk);
		$data->attachment_ktp = url('storage/'.$employee->attachment_ktp);
		$data->attachment_npwp = url('storage/'.$employee->attachment_npwp);
		$data->attachment_buku_nikah = url('storage/'.$employee->attachment_buku_nikah);
		$data->attachment_bpjs_ket = url('storage/'.$employee->attachment_bpjs_ket);
		$data->attachment_bpjs_kes = url('storage/'.$employee->attachment_bpjs_kes);
		$data->attachment_dana_pensiun = url('storage/'.$employee->attachment_dana_pensiun);
		$data->employee_group_text = ($employee->empGroup) ? $employee->empGroup->description : null;
		$data->employee_subgroup_text = ($employee->empSubGroup) ? $employee->empSubGroup->subgroup : null;

		$empEducation = $employee->allEducation()->orderByDesc('created_at')->first();
		$data['education_pendidikan_terakhir'] = ($empEducation) ? @$empEducation->jenjang->jenjang : null;
		$data['education_no_ijazah'] = ($empEducation) ? $empEducation->no_ijazah : null;
		$data['education_nama_instansi'] = ($empEducation) ? $empEducation->name : null;
		$data['education_jurusan'] = ($empEducation) ? @$empEducation->jurusan->name : null;

		$data->address = [
			"address_ktp" 			=> $employee->address_ktp ,
			"cityid_ktp" 			=> $employee->cityid_ktp ,
			"city_ktp" 				=> $employee->cityktp->description ?? null,
			"provinceid_ktp" 		=> $employee->provinceid_ktp ,
			"province_ktp" 			=> $employee->provincektp->description ?? null,
			"address_domicile" 		=> $employee->address_domicile ,
			"cityid_domicile" 		=> $employee->cityid_domicile ,
			"city_domicile" 		=> $employee->citydomicile->description ?? null ,
			"provinceid_domicile" 	=> $employee->province_domicile ,
			"province_domicile" 	=> $employee->provincedomicile->description ?? null ,
		];

		$data->bpjs = [
			'bpjs_kes_no' => $employee->bpjs_kes_no,
			'bpjs_ket_no' => $employee->bpjs_ket_no,
		];

		$data->personal_data = [
			'blood_type'	=> $employee->blood_type,
			'weight' 		=> $employee->weight,
			'height' 		=> $employee->height,
			'marital_status'=> $employee->marital_status,
			'religion' 		=> $employee->religion,
			'town_of_birth' => $employee->place_of_birth,
			'date_of_birth' => $employee->date_of_birth,
			'telephone_no' 	=> $employee->telephone_no,
		];

		// dd($employee->allEducation);
		$res = [];
		$allEduc = EmployeeEducation::where('employee_id',$employee->id)->orderBy('ref_jenjang_pendidikan_id')->get();
		if($allEduc->count() > 0){
			foreach($allEduc as $k => $value){
				$res[$k]['pendidikan_terakhir'] = $value->jenjang->jenjang ?? null;
				$res[$k]['no_ijazah'] = $value->no_ijazah ?? null;
				$res[$k]['nama_instansi'] = $value->name ?? null;
				$res[$k]['jurusan'] = $value->jurusan->name ?? null;
			}
		}
		$data->education = $res;


		// $string = preg_replace("/[^A-Z]+/", "", request()->Vusername);
		// if($string){
		// 	similar_text($string, request()->CompanyCode, $sim);
		// 	if($sim > 85){
		// 		$string = request()->CompanyCode;
		// 	}
		// }
		$string = request()->CompanyCode;
		// dd($string);
		$companyId = MasterCompany::where('code','LIKE','%'.$string.'%')->first();
		$companyId = ($companyId) ? $companyId->id : null;
		$data['position_active'] = null;
		if(count($data->position) > 0){
			$result = null;
			$resultPositionAct = null;

			if(request()->CompanyCode){
                $result = $data->position()->where('active',1)->where('npp',request()->NPP)->where('company_id_asal',$companyId)->first();
                // dump('$result',$result);
                if(!$result){
                	$results = $data->position()->where('active',1)->where('npp',request()->NPP)->whereNotNull('company_id_penugasan')->where('company_id_penugasan',$companyId)->first();
                	// dump('$result2',$result);

                	if($results){
	                	$result = $results;
	                }
                }
            }

			foreach($data->position as $k => $value){
				$empGrade = ($value) ? $value->grade : null;
				$empSub_grade = ($value) ? $value->subgrade : null;
				$empFullGrade = null;
				if($empGrade){
					$empFullGrade = $empGrade;
				}
				if($empSub_grade){
					$empFullGrade = (!is_null($empFullGrade)) ? $empFullGrade.'.'.$empSub_grade : null;
				}

				$empPosition1 = isset($value['employee_position_id']) ? EmployeePosition::find($value['employee_position_id']) : null;
			
				$value['company_name'] = ($empPosition1) ? $empPosition1->company->name : null;
				$value['kd_grade'] = $value['grade'];
				$value['grade'] = $empFullGrade;
				// dd($value['employee_position_id']);
				if($result){
					if(isset($value['employee_position_id'])){
						if($value['employee_position_id'] == $result->id){
							$resultPositionAct = $value;
						}
					}
				}
			}
            // dd($resultPositionAct);

			$data['position_active'] = $resultPositionAct;
		}
		// dd($data->position);
		// if(){

		// }

		foreach($unset as $row)
		{
			unset($data->$row);
		}

		foreach($unsetRelation as $row)
		{
			unset($data[strtolower($row)]);
			$data->unsetRelation($row);
		}
		// dd($data->toArray());
		return $data;
	}

	public function remapOneEmployeeNew(?Employee $employee): ?Employee
	{
		$data = $this->remapOneEmployee($employee);
		if($data){
			if(($data->position_active) && isset($data->position_active->company_code_penugasan)){
				$data->position_active->company_code_asal = $data->position_active->company_code_penugasan;
			}
		}
		return $data;
	}
}
