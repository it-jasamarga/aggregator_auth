<?php

namespace App\Http\Requests\Organization;

use Illuminate\Http\Request;
use App\Http\Requests\BaseRequest;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class GetOrgHierarchyRequest extends BaseRequest
{
	public function __construct(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'OrganizationID' 		=> 'required|numeric|exists:organization_hierarchy,id',
        ], [
            'OrganizationID.required' 	=> 'Params OrganizationID is required',
            'OrganizationID.numeric' 	=> 'Params OrganizationID is not valid',
            'OrganizationID.exists' 	=> 'Params OrganizationID is not valid',
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
