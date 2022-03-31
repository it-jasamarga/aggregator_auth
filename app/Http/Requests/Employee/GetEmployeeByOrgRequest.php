<?php

namespace App\Http\Requests\Employee;

use Illuminate\Http\Request;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GetEmployeeByOrgRequest extends BaseRequest
{
	public function __construct(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'OrganizationID'=> 'required|numeric|exists:organization_hierarchy,id',
        ], [
            'OrganizationID.required' 	=> 'Params OrganizationID required',
            'OrganizationID.numeric' 	=> 'Params OrganizationID should be numeric',
            'OrganizationID.exists' 	=> 'Params OrganizationID invalid',
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
