<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use App\Repositories\EsptRepository;
use Illuminate\Support\Facades\Cache;
use App\Repositories\MasterCompanyRepository;
use App\Http\Requests\Employee\GetOneEmployeeRequest;

class GetEsptController extends Controller
{
	protected $espt;
	protected $company;
	
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct(
		EsptRepository $espt,
		MasterCompanyRepository $company
	)
	{
		$this->espt 	= $espt;
		$this->company 	= $company;
	}
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function __invoke(GetOneEmployeeRequest $request)
	{
		$params 		= $request->getParams();
		$companyRepo 	= $this->company;
		$esptRepo		= $this->espt;

		$companyId 	= Cache::remember("company.{$params->CompanyCode}", 10 * 60, function () use ($companyRepo, $params) {
			return $companyRepo->findOneBy(['code' => $params->CompanyCode]);
		});

		$data 		= Cache::remember("espt.{$params->CompanyCode}.{$params->NPP}", 10 * 60, function () use ($esptRepo, $companyId, $params) {
			return $esptRepo->getEmployeeEspt($companyId->id, $params->NPP);
		});
		
		if($data == null)
		{
			return response()->json([
				'status'    => 404,
				'message'   => 'Data ESPT untuk karyawan tersebut tidak ditemukan.',
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
