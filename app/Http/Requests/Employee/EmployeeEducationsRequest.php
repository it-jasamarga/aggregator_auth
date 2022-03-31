<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest as FormReq;
use Illuminate\Validation\Rule;

class EmployeeEducationsRequest extends FormReq
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
      $unique = ($id = request()->route('education')) ? $id : '';
      // dd($unique);
      return [
        'EDUCATION.*.NAME' => [
          'string',
          Rule::unique('employee_education')->ignore($unique)->where(function ($query) {
              $query->where('name', request()->NAME);
          })
        ]
      ];
    }
  }
