<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\EmployeeGrade;

use Illuminate\Http\Request;

class EmployeeGradeRepository extends Repository
{	
	/**
	 * getClassName
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return EmployeeGrade::class;
	}
    

}
