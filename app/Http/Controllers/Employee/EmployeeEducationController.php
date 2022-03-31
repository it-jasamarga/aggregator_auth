<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

use App\Repositories\EmployeeEducationRepository;

use App\Models\Employee;
use App\Models\EmployeeEducation;

use App\Filters\EmployeeEducationFilter;
use App\Filters\EmployeeFilter;

use App\Http\Requests\Employee\GetEmployeeByCompanyRequest;
use App\Http\Requests\Employee\EmployeeEducationsRequest;
use App\Http\Requests\Employee\EmployeeEducationsUpdateRequest;

use App\Http\Resources\EmployeeEducationCollection;
use App\Http\Resources\EmployeeEducationResource;

use App\Http\Resources\EmployeeEducationByEmployeeCollection;
use App\Http\Resources\EmployeeEducationByEmployeeResource;

class EmployeeEducationController extends Controller
{
	protected $employeeEducation;
    
    /**
     * __construct
     *
     * 
     * @return void
     */
    public function __construct(
        EmployeeEducationRepository $employeeEducation
    ){
        
        $this->employeeEducation = $employeeEducation;
    }
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function index(EmployeeEducationFilter $request)
	{

		$paginate = isset(request()->paginate) ? request()->paginate : null;
        return response()->json(new EmployeeEducationCollection(EmployeeEducation::filter($request)->orderByDesc('created_at')->get()));

	}

    public function show($id)
    {
        return new EmployeeEducationResource(EmployeeEducation::findOrFail($id));
    }

    public function indexByEmployee(EmployeeFilter $request)
    {
        $paginate = isset(request()->paginate) ? request()->paginate : null;
        return response()->json(new EmployeeEducationByEmployeeCollection(Employee::with('allEducation')->filter($request)->orderByDesc('created_at')->get()));

    }

    public function showByEmployee($id)
    {
        return new EmployeeEducationByEmployeeResource(Employee::findOrFail($id));
    }

    public function store(EmployeeEducationsRequest $request){
        return $this->employeeEducation->create();
    }

    public function update(EmployeeEducationsUpdateRequest $request, $id){
        return $this->employeeEducation->updateData($id);
    }
}
