<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\EmployeeTraining;

use Illuminate\Http\Request;

class EmployeeTrainingRepository extends Repository
{	
	/**
	 * getClassName
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return EmployeeTraining::class;
	}

	/// -- FOR CREATE API
    public function create(){
        if(request()->TRAINING){
            foreach(request()->TRAINING as $k => $value){
                if(isset($value['ID'])){
                    $record = EmployeeTraining::findOrFail($value['ID']);
                    $record->update($value);
                }else{
                    EmployeeTraining::create($value);
                }
            }
            return response([
                'status' => true,
                'message' => 'success'
            ]);
        }else{
            return response([
                'status' => false,
                'message' => 'Training Request Not Found'
            ],400);
        }

        
    }

    /// -- FOR UPDATE API
    public function updateData($id){
        $record = EmployeeTraining::findOrFail($id)->update(request()->all());

        return response([
            'status' => true,
            'message' => 'success'
        ]);
    }

    

}
