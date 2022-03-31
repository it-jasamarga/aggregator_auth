<?php
namespace App\Http\Controllers\Employee;

use App\Models\Employee;
use App\Http\Controllers\Controller;
use App\Repositories\EmployeeRepository;
use App\Repositories\EmployeeFamilyRepository;
use App\Repositories\MasterCompanyRepository;
use App\Http\Requests\Employee\GetOneEmployeeRequest;
use App\Http\Requests\Employee\EmployeeFamilyRequest;
use App\Http\Requests\Employee\EmployeeFamilyUpdateRequest;
use Illuminate\Support\Facades\Cache;

class EmployeeFamilyController extends Controller
{   
    protected $employee;
    protected $company;

    public function __construct(
        EmployeeFamilyRepository $empFamily,
        EmployeeRepository $employee,
        MasterCompanyRepository $company
    )
    {
        $this->empFamily = $empFamily;
        $this->employee = $employee;
        $this->company  = $company;
    }
	
	
	public function store(EmployeeFamilyRequest $request){
		return $this->empFamily->create();
	}

	public function update(EmployeeFamilyUpdateRequest $request, $id){
        return $this->empFamily->updateData($id);
	}
}
