<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repositories\PayslipRepository;
use App\Repositories\MasterCompanyRepository;
use App\Http\Requests\Payroll\GetPayslipRequest;

class GetPayslipController extends Controller
{	
	protected $payslip;
	protected $company;
	
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct(
		PayslipRepository $payslip,
        MasterCompanyRepository $company
	)
	{
		$this->payslip = $payslip;
		$this->company = $company;
	}


	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function __invoke(GetPayslipRequest $request)
	{
		$params 		= $request->getParams();
		$payslipRepo	= $this->payslip;
		$companyRepo 	= $this->company;

		$companyId 	= Cache::remember("company.{$params->CompanyCode}", 10 * 60, function () use ($companyRepo, $params) {
			return $companyRepo->findOneBy(['code' => $params->CompanyCode]);
		});

		$data 		= Cache::remember("payslip.{$params->CompanyCode}.{$params->NPP}.{$params->Period}", 10 * 60, function () use ($payslipRepo, $companyId, $params) {
			return $payslipRepo->getEmployeePayslip($companyId->id, $params->NPP, $params->Period);
		});

		if($data == null)
		{
			return response()->json([
				'status'    => 404,
				'message'   => 'Data payslip untuk karyawan tersebut tidak ditemukan.',
				'data'      => $data,
			], 404);
		}

		return response()->json([
            'status'    => true,
            'code' 		=> 200,
            'message'   => config('constants.message.success.get'),
            'data'      => $data,
        ], 200);
	}
}
