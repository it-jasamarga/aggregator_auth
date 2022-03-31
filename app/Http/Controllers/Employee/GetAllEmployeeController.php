<?php

namespace App\Http\Controllers\Employee;


use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

use App\Repositories\EmployeeRepository;
use App\Http\Resources\EmployeeResource;
use App\Http\Resources\EmployeeCollection;
use App\Filters\EmployeeFilter;
use App\Models\Employee;

class GetAllEmployeeController
{
	protected $employee;
	
	/**
	 * __construct
	 *
	 * @param  mixed $employee
	 * @return void
	 */
	public function __construct(EmployeeRepository $employee)
	{
		$this->employee = $employee;
	}
	
	/**
	 * __invoke
	 *
	 * @return void
	 */
	public function __invoke()
	{
		
		$employeeRepo = $this->employee;
		$data = Cache::remember("all_employee", 10 * 60, function () use ($employeeRepo) {
			return $employeeRepo->getEmployeeByCompany();

		});

		return response()->json([
            'status'    => true,
            'code' 		=> 200,
            'message'   => config('constants.message.success.get'),
            'total'     => count($data),
            'data'      => $data,
        ], 200);
			// $record =
	}

	public function index(EmployeeFilter $request){
		$paginate = isset(request()->paginate) ? request()->paginate : null;
        return response()->json(new EmployeeCollection(Employee::filter($request)->where('employee_status','1')->orderByDesc('created_at')->get()));
	}
}
