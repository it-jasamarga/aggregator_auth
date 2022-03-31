<?php

namespace App\Http\Controllers\Employee;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repositories\EmployeeRepository;
use App\Repositories\MasterCompanyRepository;
use App\Http\Requests\Employee\GetEmployeeByGradeClusterRequest;

class GetEmployeeByGradeClusterController extends Controller
{
	protected $employee;
   
    /**
     * __construct
     *
     * @return void
     */
    public function __construct(
        EmployeeRepository $employee
    )
    {
        $this->employee = $employee;
    }
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function __invoke(GetEmployeeByGradeClusterRequest $request)
	{
		$params         = $request->getParams();
        $employeeRepo   = $this->employee;
        //dd($params->ClusterCode);
        $clusterKode       = strtoupper($params->ClusterCode);
        $companyCode       = strtoupper($params->CompanyCode);
        $grade       = strtoupper($params->Grade);
        $data = Cache::remember("per_grade_cluster.{".$clusterKode."_".$companyCode."_".$grade."}", 10 * 60, function () use ($employeeRepo,$params) {
			return $employeeRepo->getEmployeeByGradeCluster($params);
		});
        
        //  $data=$employeeRepo->getEmployeeByGradeCluster($params);
        $emp=array();
        $no=0;
       foreach($data as $res){
           foreach($res->position as $d) {
            $emp[]=$res;
        }
       }
        return response()->json([
            'status'    => true,
            'code'      => 200,
            'message'   => config('constants.message.success.get'),
            'data'      => $emp,
        ], 200);
	}
}
