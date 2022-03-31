<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use App\Repositories\EmployeeRepository;
use App\Http\Requests\Employee\GetEmployeeByOrgRequest;
use App\Services\Employee\GetEmployeeByOrgService;
use Illuminate\Support\Facades\Cache;
use App\Http\Resources\EmployeeByOrganizationCollection;
use App\Models\Employee;
use App\Filters\EmployeeByOrganizationFilter;


class GetEmployeeByOrgController extends Controller
{
	protected $employee;
	protected $service;
	
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct(
		EmployeeRepository $employee,
		GetEmployeeByOrgService $service
	)
	{
		$this->employee = $employee;
		$this->service	= $service;
	}
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	// public function __invoke(GetEmployeeByOrgRequest $request)
	// {
	// 	$params 		= $request->getParams();
	// 	$employeeRepo 	= $this->employee;
		
	// 	$orgId 	= $params->OrganizationID;

	// 	$arrayOrgId = $this->service->generateOrgId($orgId);

	// 	$data = Cache::remember("per_organization.{$orgId}", 10 * 60, function () use ($employeeRepo, $arrayOrgId) {
	// 		return $employeeRepo->getEmployeeByOrganization($arrayOrgId);
	// 	});

	// 	return response()->json([
 //            'status'    => true,
 //            'code' 		=> 200,
 //            'message'   => config('constants.message.success.get'),
 //            'data'      => $data,
 //        ], 200);
	// }

	public function index(EmployeeByOrganizationFilter $filter, GetEmployeeByOrgRequest $request){
		$orgId 	= request()->OrganizationID;
		$arrayOrgId = $this->service->generateOrgId($orgId);

		request()['arrayOrgId'] = $arrayOrgId;
		$paginate = isset(request()->paginate) ? request()->paginate : null;
	        return response()->json(new EmployeeByOrganizationCollection(Employee::whereHas('position', function($q) use($arrayOrgId){
	        	$q->where('active','1')->whereHas('masterPosition',function($q1) use($arrayOrgId){
	                $q1->whereIn('org_id',$arrayOrgId);
	            });
	        })->where('employee_status','1')->orderByDesc('created_at')->get()));
	}
}
