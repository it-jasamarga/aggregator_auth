<?php

namespace App\Http\Requests\Payroll;

use Illuminate\Http\Request;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GetPayslipRequest extends BaseRequest
{
	public function __construct(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'CompanyCode' 	=> 'required|alpha|exists:master_company,code',
			'NPP'			=> 'required|exists:employee_position,npp',
			'Period'		=> 'required|numeric|exists:payslip,periode'
        ], [
            'CompanyCode.required' 	=> 'Params CompanyCode required',
            'CompanyCode.alpha' 	=> 'Params CompanyCode should be alpha character',
            'CompanyCode.exists' 	=> 'Params CompanyCode invalid',
            'NPP.required' 			=> 'Params NPP required',
            'NPP.exists' 		    => 'Params NPP not found in database',
			'Period.required'		=> 'Params Period required',
			'Period.numeric'		=> 'Params Period invalid',
			'Period.exists'			=> 'Params Period not found in database',
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
