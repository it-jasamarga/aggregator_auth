<?php

namespace App\Http\Controllers\Employee\Hierarchy;

use Illuminate\Support\Facades\Cache;
use App\Repositories\EmployeeRepository;
use App\Repositories\MasterCompanyRepository;
use App\Services\Employee\Hierarchy\GetBawahanService;
use App\Http\Requests\Employee\Hierarchy\EmployeeHierarchyRequest;
use App\Services\Employee\Hierarchy\GetPeerService;
use Exception;
use Illuminate\Support\Facades\Log;

class GetPeerController
{
	protected $employee;
	protected $company;
	protected $serviceBawahan;
	protected $service;
	
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct(
		EmployeeRepository $employee,
		MasterCompanyRepository $company,
		GetBawahanService $serviceBawahan,
		GetPeerService $service
	)
	{
		$this->employee = $employee;
		$this->company	= $company;
		$this->serviceBawahan = $serviceBawahan;
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
		$serviceBawahan = $this->serviceBawahan;
		$service 		= $this->service;

		$companyId 	= Cache::remember("company.{$params->CompanyCode}", 10 * 60, function () use ($companyRepo, $params) {
			return $companyRepo->findOneBy(['code' => $params->CompanyCode]);
		});

		try {
			
			$employeeAtasan = Cache::remember("hierarchy_atasan.{$params->NPP}.{$params->CompanyCode}.{$params->PositionID}", 10 * 60, function () use ($employeeRepo, $companyId, $params) {
				return $employeeRepo->getEmployeeWithAtasan($companyId->id, $params->NPP, $params->PositionID);
			});
	
			$bawahanFromAtasan = Cache::remember("hierarchy_bawahan.{$employeeAtasan->employee_number_atasan}.{$params->CompanyCode}.{$employeeAtasan->position_id_atasan}", 10 * 60, function () use ($employeeRepo, $employeeAtasan, $serviceBawahan) {
				
				$data = $employeeRepo->getEmployeeWithBawahan($employeeAtasan->company_id_asal_atasan, $employeeAtasan->employee_number_atasan, $employeeAtasan->position_id_atasan);
	
				return $serviceBawahan->remapBawahan($data);
			});

		} catch (Exception $e) {
			Log::error($e->getMessage());

			return response()->json([
				'status'    => 404,
				'message'   => 'PositionID untuk NPP tersebut tidak ditemukan atau data atasan untuk karyawan tersebut tidak ditemukan.',
				'log'		=> $e->getMessage(),
			], 404);
		}

		$data = Cache::remember("hierarchy_peer.{$params->NPP}.{$params->CompanyCode}.{$params->PositionID}", 10 * 60, function () use ($service, $employeeAtasan, $bawahanFromAtasan) {
			return $service->remapPeerData($employeeAtasan, $bawahanFromAtasan);
		});

		if($data == null)
		{
			return response()->json([
				'status'    => 404,
				'message'   => 'PositionID untuk NPP tersebut tidak ditemukan atau data atasan untuk karyawan tersebut tidak ditemukan.',
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
