<?php

namespace App\Http\Requests\Employee;

use Illuminate\Http\Request;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GetEmployeeByGradeClusterRequest extends BaseRequest
{
	public function __construct(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'ClusterCode.required' 	=> 'Params ClusterCode required',
            'ClusterCode.alpha' 	=> 'Params ClusterCode should be alpha character',
            'ClusterCode.exists' 	=> 'Params ClusterCode invalid',
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
