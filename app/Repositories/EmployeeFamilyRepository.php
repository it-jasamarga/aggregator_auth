<?php

namespace App\Repositories;

use App\Models\EmployeeFamily;
use App\Models\MasterStatusKeluarga;
use App\Models\MasterJenjangPendidikan;

class EmployeeFamilyRepository extends Repository
{	
	/**
	 * getClassName
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return EmployeeFamily::class;
	}

	public function getEmployeeFamily()
	{
		
	}

	/// -- FOR CREATE API
    public function create(){
        if(request()->FAMILY){
            foreach(request()->FAMILY as $k => $value){
                $value['ATTACHMENT_NIKAH'] = $this->attachNikah($value);
                $value['ATTACHMENT_AKTA'] = $this->attachAkta($value);
                $value['FAMILY_STATUS'] = $this->setStatusKeluarga($value);
                $value['LAST_EDUCATION'] = $this->setLastEducation($value);
                if(isset($value['ID'])){
                    if(!isset($value['ATTACHMENT_NIKAH'])){
                        unset($value['ATTACHMENT_NIKAH']);
                    }
                    if(!isset($value['ATTACHMENT_AKTA'])){
                        unset($value['ATTACHMENT_AKTA']);
                    }
                    if(!isset($value['FAMILY_STATUS'])){
                        unset($value['FAMILY_STATUS']);
                    }
                    if(!isset($value['LAST_EDUCATION'])){
                        unset($value['LAST_EDUCATION']);
                    }

                    $record = EmployeeFamily::findOrFail($value['ID']);
                    $record->update($value);
                }else{
                    $record = EmployeeFamily::create($value);
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
        $this->attachNikah();
        $this->attachAkta();
        $this->setStatusKeluarga();
        $this->setLastEducation();
        
        $record = EmployeeFamily::findOrFail($id)->update(request()->all());

        return response([
            'status' => true,
            'message' => 'success'
        ]);
    }

    /**
     * Store Attachment KTP
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachNikah($value){
        $request = $value;
        $path = null;
        if(@$value['ATTACH_NIKAH']){
            if($value['ATTACH_NIKAH'] && is_file($value['ATTACH_NIKAH'])){
              $fileName = md5($value['ATTACH_NIKAH']->getClientOriginalName().''.strtotime('now')).'.'.$value['ATTACH_NIKAH']->getClientOriginalExtension();
              $value['ATTACH_NIKAH']->storeAs('EmployeeFamily', $fileName, 'public');
              $path = 'EmployeeFamily/'.$fileName;
            }
        }

        return $path;
    }

    /**
     * Store Attachment KK
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachAkta($value){
        $path = null;
        if(@$value['ATTACH_AKTA']){
            if($value['ATTACH_AKTA'] && is_file($value['ATTACH_AKTA'])){
              $fileName = md5($value['ATTACH_AKTA']->getClientOriginalName().''.strtotime('now')).'.'.$value['ATTACH_AKTA']->getClientOriginalExtension();
              $value['ATTACH_AKTA']->storeAs('EmployeeFamily', $fileName, 'public');
              $path = 'EmployeeFamily/'.$fileName;
            }
        }
        return $path;
    }


    /**
     * Get Data Employee Status For Store Data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function setStatusKeluarga($value){
        $record = [];
        if(@$value['FAMILY_STATUS']){
            $record = MasterStatusKeluarga::where('description',$value['FAMILY_STATUS'])->first();
            if(!$record){
                $record = MasterStatusKeluarga::create([
                    'DESCRIPTION' => $value['FAMILY_STATUS']
                ]); 
            }
        }
        
        return ($record) ? $record->id : null;
    }

    /**
     * Get Data Employee Status For Store Data
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function setLastEducation($value){
        $record = [];
        if(@$value['LAST_EDUCATION']){
            $record = MasterJenjangPendidikan::where('jenjang',$value['LAST_EDUCATION'])->first();
            if(!$record){
                $record = MasterJenjangPendidikan::create([
                    'JENJANG' => $value['LAST_EDUCATION']
                ]); 
            }
        }
        return ($record) ? $record->id : null;
    }
}
