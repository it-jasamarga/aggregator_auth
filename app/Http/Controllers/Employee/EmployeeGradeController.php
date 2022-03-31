<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

use App\Repositories\EmployeeGradeRepository;

use App\Models\Employee;
use App\Models\EmployeeGrade;

use App\Filters\EmployeeGradeFilter;
use App\Filters\EmployeeFilter;

use App\Http\Requests\Employee\GetEmployeeByCompanyRequest;
use App\Http\Requests\Employee\EmployeeGradeRequest;
use App\Http\Requests\Employee\EmployeeGradeUpdateRequest;

use App\Http\Resources\EmployeeGradeCollection;
use App\Http\Resources\EmployeeGradeResource;

use App\Http\Resources\EmployeeGradeByEmployeeCollection;
use App\Http\Resources\EmployeeGradeByEmployeeResource;

class EmployeeGradeController extends Controller
{
	protected $employeeRepo;
    
    /**
     * __construct
     *
     * 
     * @return void
     */
    public function __construct(
        EmployeeGradeRepository $employeeRepo
    ){
        
        $this->employeeRepo = $employeeRepo;
    }
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function index(EmployeeGradeFilter $request)
	{

		$paginate = isset(request()->paginate) ? request()->paginate : null;
        
        return response()->json(new EmployeeGradeCollection(EmployeeGrade::filter($request)->orderByDesc('created_at')->get()));

	}

    public function show($id)
    {
        return new EmployeeGradeResource(EmployeeGrade::findOrFail($id));
    }

    public function indexByEmployee(EmployeeFilter $request)
    {
        $paginate = isset(request()->paginate) ? request()->paginate : null;
        return response()->json(new EmployeeGradeByEmployeeCollection(Employee::with('allGrade')->filter($request)->orderByDesc('created_at')->get()));

    }

    public function showByEmployee($id)
    {
        return new EmployeeGradeByEmployeeResource(Employee::findOrFail($id));
    }

    public function store(EmployeeGradeRequest $request){
        return $this->employeeRepo->create();
    }

    public function update(EmployeeGradeUpdateRequest $request, $id){
        return $this->employeeRepo->updateData($id);
    }
}
