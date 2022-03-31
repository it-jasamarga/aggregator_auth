<?php

namespace App\Http\Requests\Employee;

use Illuminate\Http\Request;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GetEmployeeByUnitRequest extends BaseRequest
{	
	/**
	 * __construct
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function __construct(Request $request)
	{
		$validate = Validator::make($request->all(), [
            'UnitID' 	=> 'required|numeric|exists:employee_position,unit_kerja_id',
        ], [
            'UnitID.required' 	=> 'Params UnitID required',
            'UnitID.numeric' 	=> 'Params UnitID should be numeric',
            'UnitID.exists' 	=> 'Params UnitID invalid',
        ]);

        if(!$validate->passes())
        {
            throw new ValidationException($validate, response()->json([
                'status' => 422,
                'message'=> implode('. ', $validate->errors()->all())
            ], 422));
        }
		
		parent::__construct($request);
	}
}
