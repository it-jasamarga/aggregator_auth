<?php

namespace App\Http\Requests\Employee;

use Illuminate\Foundation\Http\FormRequest as FormReq;
use Illuminate\Validation\Rule;

class EmployeeTrainingRequest extends FormReq
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
        'TRAINING.*.EMPLOYEE_ID' => 'required',
        'TRAINING.*.HARI' => 'numeric',
        'TRAINING.*.TGL_AWAL' => 'date',
        'TRAINING.*.TGL_AKHIR' => 'date',
        'TRAINING.*.TGL_AKHIR' => 'date',
      ];
    }
  }
