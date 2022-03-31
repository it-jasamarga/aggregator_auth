<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

use App\Repositories\EmployeeHobbyRepository;

use App\Models\Employee;
use App\Models\EmployeeHobby;

use App\Filters\EmployeeHobbyFilter;
use App\Filters\EmployeeFilter;

use App\Http\Requests\Employee\GetEmployeeByCompanyRequest;
use App\Http\Requests\Employee\EmployeeHobbyRequest;
use App\Http\Requests\Employee\EmployeeHobbyUpdateRequest;

use App\Http\Resources\EmployeeHobbyCollection;
use App\Http\Resources\EmployeeHobbyResource;

use App\Http\Resources\EmployeeHobbyByEmployeeCollection;
use App\Http\Resources\EmployeeHobbyByEmployeeResource;

class EmployeeHobbyController extends Controller
{
	protected $employeeRepo;
    
    /**
     * __construct
     *
     * 
     * @return void
     */
    public function __construct(
        EmployeeHobbyRepository $employeeRepo
    ){
        
        $this->employeeRepo = $employeeRepo;
    }
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function index(EmployeeHobbyFilter $request)
	{

		$paginate = isset(request()->paginate) ? request()->paginate : null;
        return response()->json(new EmployeeHobbyCollection(EmployeeHobby::filter($request)->orderByDesc('created_at')->get()));

	}

    public function show($id)
    {
        return new EmployeeHobbyResource(EmployeeHobby::findOrFail($id));
    }

    public function indexByEmployee(EmployeeFilter $request)
    {
        $paginate = isset(request()->paginate) ? request()->paginate : null;
        return response()->json(new EmployeeHobbyByEmployeeCollection(Employee::with('allHobby')->filter($request)->orderByDesc('created_at')->get()));

    }

    public function showByEmployee($id)
    {
        return new EmployeeHobbyByEmployeeResource(Employee::findOrFail($id));
    }

    public function store(EmployeeHobbyRequest $request){
        return $this->employeeRepo->create();
    }

    public function update(EmployeeHobbyUpdateRequest $request, $id){
        return $this->employeeRepo->updateData($id);
    }
}
