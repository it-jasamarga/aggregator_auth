<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest as FormReq;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormReq
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
      $unique = ($id = request()->route('employee')) ? $id : '';
      // dd($unique);
      return [
        'NPP' => [
          'required',
          'string',
              Rule::unique('employee')->ignore($unique)->where(function ($query) {
                  $query->where('npp', request()->NPP)->where('kd_comp',request()->KD_COMP);
              })
          ],
        'NAME' => 'required|string'  ,
        'KD_COMP' => 'required|string',
        'NATIONAL_IDENTIFIER' => [
          'required',
          'min:16',
          'max:16',
          Rule::unique('employee')->ignore($unique)->where(function ($query) {
              $query->where('national_identifier', request()->NATIONAL_IDENTIFIER);
          })
        ],
        'PERSONNEL_NUMBER' => [
          Rule::unique('employee')->ignore($unique)->where(function ($query) {
              $query->where('personnel_number', request()->PERSONNEL_NUMBER);
          })
        ],
      ];
    }
  }
