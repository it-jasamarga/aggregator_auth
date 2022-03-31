<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

use App\Repositories\EmployeeTrainingRepository;

use App\Models\Employee;
use App\Models\EmployeeTraining;

use App\Filters\EmployeeTrainingFilter;
use App\Filters\EmployeeFilter;

use App\Http\Requests\Employee\GetEmployeeByCompanyRequest;
use App\Http\Requests\Employee\EmployeeTrainingRequest;
use App\Http\Requests\Employee\EmployeeTrainingUpdateRequest;

use App\Http\Resources\EmployeeTrainingCollection;
use App\Http\Resources\EmployeeTrainingResource;

use App\Http\Resources\EmployeeTrainingByEmployeeCollection;
use App\Http\Resources\EmployeeTrainingByEmployeeResource;

class EmployeeTrainingController extends Controller
{
	protected $employeeRepo;
    
    /**
     * __construct
     *
     * 
     * @return void
     */
    public function __construct(
        EmployeeTrainingRepository $employeeRepo
    ){
        
        $this->employeeRepo = $employeeRepo;
    }
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function index(EmployeeTrainingFilter $request)
	{

		$paginate = isset(request()->paginate) ? request()->paginate : null;
        return response()->json(new EmployeeTrainingCollection(EmployeeTraining::filter($request)->orderByDesc('created_at')->get()));

	}

    public function show($id)
    {
        return new EmployeeTrainingResource(EmployeeTraining::findOrFail($id));
    }

    public function indexByEmployee(EmployeeFilter $request)
    {
        $paginate = isset(request()->paginate) ? request()->paginate : null;
        return response()->json(new EmployeeTrainingByEmployeeCollection(Employee::with('allTraining')->filter($request)->orderByDesc('created_at')->get()));

    }

    public function showByEmployee($id)
    {
        return new EmployeeTrainingByEmployeeResource(Employee::findOrFail($id));
    }

    public function store(EmployeeTrainingRequest $request){
        return $this->employeeRepo->create();
    }

    public function update(EmployeeTrainingUpdateRequest $request, $id){
        return $this->employeeRepo->updateData($id);
    }
}
