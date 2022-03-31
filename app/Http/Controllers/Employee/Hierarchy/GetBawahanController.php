<?php

namespace App\Http\Controllers\Employee\Hierarchy;

use Illuminate\Support\Facades\Cache;
use App\Repositories\EmployeeRepository;
use App\Repositories\MasterCompanyRepository;
use App\Http\Requests\Employee\Hierarchy\EmployeeHierarchyRequest;
use App\Services\Employee\Hierarchy\GetBawahanService;

class GetBawahanController
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
		EmployeeRepository $employee,
		MasterCompanyRepository $company,
		GetBawahanService $service
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
		$companyRepo	= $this->company;
		$service 		= $this->service;

		$companyId 	= Cache::remember("company.{$params->CompanyCode}", 5 * 60, function () use ($companyRepo, $params) {
			return $companyRepo->findOneBy(['code' => $params->CompanyCode]);
		});

		// $data = Cache::remember("hierarchy_bawahan.{$params->NPP}.{$params->CompanyCode}.{$params->PositionID}", 5 * 60, function () use ($employeeRepo, $companyId, $params, $service) {
			$result = $employeeRepo->getEmployeeWithBawahan($companyId->id, $params->NPP, $params->PositionID);

			$data = $service->remapBawahan($result);
		// });


		if($data == null)
		{
			return response()->json([
				'status'    => 404,
				'message'   => 'PositionID untuk NPP tersebut tidak ditemukan',
				'data'      => $data,
			], 404);
		}

		return response()->json([
            'status'    => 200,
            'message'   => config('constants.message.success.get'),
            'data'      => $data,
        ], 200);
	}
}
