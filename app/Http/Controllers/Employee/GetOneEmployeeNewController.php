<?php

namespace App\Http\Controllers\Employee;

use App\Services\Employee\GetOneEmployeeService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repositories\EmployeeRepository;
use App\Repositories\MasterCompanyRepository;
use App\Http\Requests\Employee\GetOneEmployeeRequest;

class GetOneEmployeeNewController extends Controller
{
	protected $employee;
    protected $service;
    protected $company;
    
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        EmployeeRepository $employee,
        GetOneEmployeeService $service,
        MasterCompanyRepository $company
    )
    {
        $this->employee = $employee;
        $this->service  = $service;
        $this->company  = $company;
    }
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function __invoke(GetOneEmployeeRequest $request)
	{
		$params         = $request->getParams();
        $employeeRepo   = $this->employee;
        $service        = $this->service;

        $company        = $this->company->findOneBy(['code' => $params->CompanyCode]);
        $npp            = $params->NPP;
        $kodeComp       = strtoupper($params->CompanyCode);

        // $data   = Cache::remember("per_employee.{$kodeComp}.{$npp}", 10 * 60, function () use ($employeeRepo, $service, $params, $company) {
            $employee = $employeeRepo->getOneEmployee($company->id, $params->NPP);
            if(!$employee){
                $employee = $employeeRepo->getOneEmployee($company->id, $params->NPP, true);
            }
            $data = $service->remapOneEmployeeNew($employee);
        // });

        return response()->json([
            'status'    => 200,
            'message'   => config('constants.message.success.get'),
            'data'      => $data,
        ], 200);
	}
}
