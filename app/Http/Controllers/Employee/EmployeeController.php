<?php

namespace App\Http\Controllers\Employee;

use App\Services\Employee\GetOneEmployeeService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repositories\EmployeeRepository;
use App\Repositories\MasterCompanyRepository;
use App\Http\Requests\Employee\EmployeeRequest;
use App\Http\Requests\Employee\EmployeeUpdateRequest;

class EmployeeController extends Controller
{
	protected $employee;
    protected $service;
    protected $company;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        EmployeeRepository $employee,
        GetOneEmployeeService $service,
        MasterCompanyRepository $company
    )
    {
        $this->employee = $employee;
        $this->service  = $service;
        $this->company  = $company;
    }
	
	
	public function store(EmployeeRequest $request){
		return $this->employee->create();
	}

	public function update($id){
        return $this->employee->updateData($id);
	}
}
