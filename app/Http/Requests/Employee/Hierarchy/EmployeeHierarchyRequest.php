<?php

namespace App\Http\Requests\Employee\Hierarchy;

use Illuminate\Http\Request;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class EmployeeHierarchyRequest extends BaseRequest
{
	public function __construct(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'CompanyCode' 	=> 'required|alpha|exists:master_company,code',
			'NPP'			=> 'required|exists:employee_position,npp',
            'PositionID' 	=> 'required|numeric|exists:master_position,id',
        ], [
            'CompanyCode.required' 	=> 'Params CompanyCode required',
            'CompanyCode.alpha' 	=> 'Params CompanyCode should be alpha character',
            'CompanyCode.exists' 	=> 'Params CompanyCode invalid',
            'NPP.required' 			=> 'Params NPP required',
            'NPP.exists' 		    => 'Params NPP not found in database',
            'PositionID.required' 	=> 'Params PositionID required',
            'PositionID.alpha' 		=> 'Params PositionID should be alpha character',
            'PositionID.exists' 	=> 'Params PositionID invalid',
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
