<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\EmployeeHobby;

use Illuminate\Http\Request;

class EmployeeHobbyRepository extends Repository
{	
	/**
	 * getClassName
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return EmployeeHobby::class;
	}

	/// -- FOR CREATE API
    public function create(){
        $record = EmployeeHobby::create(request()->all());

        return response([
            'status' => true,
            'message' => 'success'
        ]);
    }

    /// -- FOR UPDATE API
    public function updateData($id){
        $record = EmployeeHobby::findOrFail($id)->update(request()->all());

        return response([
            'status' => true,
            'message' => 'success'
        ]);
    }

    

}
