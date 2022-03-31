<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repositories\EmployeeRepository;
use App\Repositories\MasterCompanyRepository;
use App\Http\Requests\Employee\GetEmployeeByCompanyRequest;

class GetEmployeeByCompanyController extends Controller
{
	protected $employee;
	protected $company;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        EmployeeRepository $employee,
        MasterCompanyRepository $company
    )
    {
        $this->employee = $employee;
        $this->company  = $company;
    }
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function __invoke(GetEmployeeByCompanyRequest $request)
	{
		$params         = $request->getParams();
        $employeeRepo   = $this->employee;
        
        $company        = $this->company->findOneBy(['code' => $params->CompanyCode]);
        $kodeComp       = strtoupper($params->CompanyCode);

        $data = Cache::remember("per_company.{$kodeComp}", 10 * 60, function () use ($employeeRepo, $company) {
			return $employeeRepo->getEmployeeByCompany($company->id);
		});

        return response()->json([
            'status'    => true,
            'code'      => 200,
            'message'   => config('constants.message.success.get'),
            'data'      => $data,
        ], 200);
	}
}
