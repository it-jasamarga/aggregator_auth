<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repositories\EmployeeRepository;
use App\Http\Requests\Employee\GetEmployeeByUnitRequest;

use App\Http\Resources\EmployeeByUnitKerjaCollection;
use App\Models\Employee;
use App\Filters\EmployeeByUnitKerjaFilter;

class GetEmployeeByUnitController extends Controller
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
	 * @param  mixed $request
	 * @return void
	 */
	public function __invoke(GetEmployeeByUnitRequest $request)
	{
		$params = $request->getParams();
		$employeeRepo 	= $this->employee;

		$data = Cache::remember("per_unit.{$params->UnitID}", 10 * 60, function () use ($employeeRepo, $params) {
			return $employeeRepo->getEmployeeByUnit($params->UnitID);
		});

		return response()->json([
            'status'    => true,
            'code' 		=> 200,
            'message'   => config('constants.message.success.get'),
            'data'      => $data,
        ], 200);
	}

	public function index(GetEmployeeByUnitRequest $request){
		$paginate = isset(request()->paginate) ? request()->paginate : null;
		
		// $data = Cache::remember("per_unit.{request()->UnitID}", 10 * 60, function() {
		
			$data = Employee::whereHas('position', function($q){
	        	$q->where('active','1')->where('unit_kerja_id',request()->UnitID);
	        })->where('employee_status','1')->orderByDesc('created_at')->get();

	    // });

        return response()->json(new EmployeeByUnitKerjaCollection($data));
	}
}
