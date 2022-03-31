<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest as FormReq;
use Illuminate\Validation\Rule;

class EmployeeHobbyUpdateRequest extends FormReq
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
      return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
      $unique = ($id = request()->route('family')) ? $id : '';
      // dd($unique);
      return [
        'EMPLOYEE_ID' => 'required',
        'HOBBY' => [
          'string',
          Rule::unique('employee_hobby')->ignore($unique)->where(function ($query) {
              $query->where('employee_id',request()->EMPLOYEE_ID)->where('hobby', request()->HOBBY);
          })
        ]
      ];
    }
  }
