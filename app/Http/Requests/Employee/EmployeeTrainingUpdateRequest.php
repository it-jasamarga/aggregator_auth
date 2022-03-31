<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest as FormReq;
use Illuminate\Validation\Rule;

class EmployeeTrainingUpdateRequest extends FormReq
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
      $unique = ($id = request()->route('training')) ? $id : '';
      // dd($unique);
      return [
        'EMPLOYEE_ID' => 'required',
        'HARI' => 'numeric',
        'TGL_AWAL' => 'date',
        'TGL_AKHIR' => 'date',
        'TGL_AKHIR' => 'date',
      ];
    }
  }
