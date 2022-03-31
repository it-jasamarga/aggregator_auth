<?php

namespace App\Http\Controllers\Employee;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Repositories\EmployeeRepository;
use App\Repositories\MasterCompanyRepository;
use App\Http\Requests\Employee\GetOneEmployeeRequest;
use Illuminate\Support\Facades\Cache;

use App\Http\Resources\EmployeeFamilyCollection;
use App\Http\Resources\EmployeeFamilyResource;

use App\Http\Resources\EmployeeFamilyByEmployeeCollection;
use App\Http\Resources\EmployeeFamilyByEmployeeResource;

class GetEmployeeFamilyController extends Controller
{	
	protected $employee;
	protected $company;

	public function __construct(
		EmployeeRepository $employee,
		MasterCompanyRepository $company
	)
	{
		$this->employee = $employee;
		$this->company  = $company;
	}

	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function __invoke(GetOneEmployeeRequest $request)
	{
		$params         = $request->getParams();
		return new EmployeeFamilyByEmployeeResource(Employee::with('family')->where('NPP',$params->NPP)->where('kd_comp',$params->CompanyCode)->first());

	}
}
