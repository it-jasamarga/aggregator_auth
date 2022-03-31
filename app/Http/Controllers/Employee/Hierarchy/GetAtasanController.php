<?php

namespace App\Http\Controllers\Employee\Hierarchy;

use Illuminate\Support\Facades\Cache;
use App\Repositories\EmployeeRepository;
use App\Repositories\MasterCompanyRepository;
use App\Http\Requests\Employee\Hierarchy\EmployeeHierarchyRequest;
use App\Services\Employee\Hierarchy\GetAtasanService;

class GetAtasanController
{
	protected $employee;
	protected $company;
	protected $service;
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct(
		GetAtasanService $service,
		EmployeeRepository $employee,
		MasterCompanyRepository $company
	)
	{
		$this->employee = $employee;
		$this->company	= $company;
		$this->service	= $service;
	}
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function __invoke(EmployeeHierarchyRequest $request)
	{
		$params 		= $request->getParams();
		$employeeRepo   = $this->employee;
		$companyRepo 	= $this->company;
		$service 		= $this->service;
		
		$companyId = Cache::remember("company.{$params->CompanyCode}", 10 * 60, function () use ($companyRepo, $params) {
			return $companyRepo->findOneBy(['code' => $params->CompanyCode]);
		});

		$data = Cache::remember("hierarchy_atasan.{$params->NPP}.{$params->CompanyCode}.{$params->PositionID}", 10 * 60, function () use ($employeeRepo, $companyId, $params, $service) {
			$result =  $employeeRepo->getEmployeeWithAtasan($companyId->id, $params->NPP, $params->PositionID);
			if(!is_null($result)){
				return $service->remapPeerData($result);
			}else{
				return null;
			}
		});

		if($data == null)
		{
			return response()->json([
				'status'    => 404,
				'message'   => 'PositionID untuk NPP tersebut tidak ditemukan atau data atasan untuk karyawan tersebut tidak ditemukan.',
				'data'      => $data,
			], 200);
		}

		return response()->json([
            'status'    => 200,
            'message'   => config('constants.message.success.get'),
            'data'      => $data,
        ], 200);
	}
}
