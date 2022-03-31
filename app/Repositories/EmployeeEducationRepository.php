<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\EmployeeEducation;
use App\Models\MasterCountry;
use App\Models\MasterJenjangPendidikan;
use App\Models\MasterJurusanPendidikan;


use Illuminate\Http\Request;

class EmployeeEducationRepository extends Repository
{	
	/**
	 * getClassName
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return EmployeeEducation::class;
	}

	/// -- FOR CREATE API
    public function create(){
        if(request()->EDUCATION){
            foreach(request()->EDUCATION as $k => $value){
                $value['ATTACHMENT_IJAZAH'] = $this->attachIjazah($value);
                $value['COUNTRY_ID'] = $this->setCountry($value);
                $value['REF_JURUSAN_PENDIDIKAN_ID'] = $this->setJurusanPendidikan($value);
                $value['REF_JENJANG_PENDIDIKAN_ID'] = $this->setJenjangPendidikan($value);
                if(@$value['ID']){
                    if(!isset($value['ATTACHMENT_IJAZAH'])){
                        unset($value['ATTACHMENT_IJAZAH']);
                    }
                    if(!isset($value['COUNTRY_ID'])){
                        unset($value['COUNTRY_ID']);
                    }
                    if(!isset($value['REF_JURUSAN_PENDIDIKAN_ID'])){
                        unset($value['REF_JURUSAN_PENDIDIKAN_ID']);
                    }
                    if(!isset($value['REF_JENJANG_PENDIDIKAN_ID'])){
                        unset($value['REF_JENJANG_PENDIDIKAN_ID']);
                    }

                    $record = EmployeeEducation::findOrFail($value['ID']);
                    $record->update($value);
                }else{
                    $record = EmployeeEducation::create($value);
                }
            }

            return response([
                'status' => true,
                'message' => 'Success'
            ]);
        }else{
            return response([
                'status' => false,
                'message' => 'Education Request Not Found'
            ],400);
        }
        

        
    }

    /// -- FOR UPDATE API
    public function updateData($id){
        if(request()->EDUCATION){
            foreach(request()->EDUCATION as $k => $value){
                $value['ATTACHMENT_IJAZAH'] = $this->attachIjazah($value);
                $value['COUNTRY_ID'] = $this->setCountry($value);
                $value['REF_JURUSAN_PENDIDIKAN_ID'] = $this->setJurusanPendidikan($value);

                $value['REF_JENJANG_PENDIDIKAN_ID'] = $this->setJenjangPendidikan($value);
                dd($value);
                if(@$value['ID']){
                    dd('asd');
                    if(!isset($value['ATTACHMENT_IJAZAH'])){
                        unset($value['ATTACHMENT_IJAZAH']);
                    }
                    if(!isset($value['COUNTRY_ID'])){
                        unset($value['COUNTRY_ID']);
                    }
                    if(!isset($value['REF_JURUSAN_PENDIDIKAN_ID'])){
                        unset($value['REF_JURUSAN_PENDIDIKAN_ID']);
                    }
                    if(!isset($value['REF_JENJANG_PENDIDIKAN_ID'])){
                        unset($value['REF_JENJANG_PENDIDIKAN_ID']);
                    }

                    $record = EmployeeEducation::findOrFail($value['ID']);
                    unset($value['ID']);
                    $record->update($value);
                }else{
                    $record = EmployeeEducation::create($value);
                }
            }

            return response([
                'status' => true,
                'message' => 'Success'
            ]);
        }else{
            return response([
                'status' => false,
                'message' => 'Education Request Not Found'
            ],400);
        }
    }

    /**
     * Store Attachment KTP
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachIjazah($value){
        $request = $value;
        $path = null;
        if(@$request['ATTACH_IJAZAH'] && is_file($request['ATTACH_IJAZAH'])){
          $fileName = md5($request['ATTACH_IJAZAH']->getClientOriginalName().''.strtotime('now')).'.'.$request['ATTACH_IJAZAH']->getClientOriginalExtension();
          $request['ATTACH_IJAZAH']->storeAs('EmployeeEducation', $fileName, 'public');
          $path = 'EmployeeEducation/'.$fileName;
        }

        return $path;
    }

    /**
     * Get Data Master Country For Store Data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function setCountry($value){
        $record = [];
        if(@$value['COUNTRY']){
            $record = MasterCountry::where('description',$value['COUNTRY'])->first();
            if(!$record){
                if(@$value['COUNTRY']){
                    $record = MasterCountry::create([
                        'DESCRIPTION' => $value['COUNTRY']
                    ]);
                }
            }
        }

        return ($record) ? $record->id : null;
    }

    /**
     * Get Data Master Jurusan Pendidikan For Store Data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function setJurusanPendidikan($value){
        $record = [];
        if(@$value['REF_JURUSAN_PENDIDIKAN']){
            $record = MasterJurusanPendidikan::where('name',$value['REF_JURUSAN_PENDIDIKAN'])->first();
            if(!$record){
                if(@$value['REF_JURUSAN_PENDIDIKAN']){
                    $record = MasterJurusanPendidikan::create([
                        'NAME' => $value['REF_JURUSAN_PENDIDIKAN']
                    ]);
                }
            }
        }

        return ($record) ? $record->id : null;

    }

    /**
     * Get Data Master Jenjang Pendidikan For Store Data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function setJenjangPendidikan($value){
        $record = [];
        if(@$value['REF_JENJANG_PENDIDIKAN']){
            $record = MasterJenjangPendidikan::where('jenjang',$value['REF_JENJANG_PENDIDIKAN'])->first();
            if(!$record){
                if(@$value['REF_JENJANG_PENDIDIKAN']){
                    $record = MasterJenjangPendidikan::create([
                        'JENJANG' => $value['REF_JENJANG_PENDIDIKAN']
                    ]);
                }
            }
        }
        
        return ($record) ? $record->id : null;
    }

}
