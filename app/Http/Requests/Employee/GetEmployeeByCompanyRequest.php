<?php

namespace App\Http\Requests\Employee;

use Illuminate\Http\Request;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GetEmployeeByCompanyRequest extends BaseRequest
{
	public function __construct(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'CompanyCode' 	=> 'required|alpha|exists:master_company,code',
        ], [
            'CompanyCode.required' 	=> 'Params CompanyCode required',
            'CompanyCode.alpha' 	=> 'Params CompanyCode should be alpha character',
            'CompanyCode.exists' 	=> 'Params CompanyCode invalid',
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
