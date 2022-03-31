<?php

namespace App\Http\Controllers\Payroll;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\Request;

use App\Repositories\PayslipRepository;

use App\Models\Employee;
use App\Models\Payslip;

use App\Filters\PayslipFilter;
use App\Filters\EmployeeFilter;

use App\Http\Requests\Employee\GetEmployeeByCompanyRequest;
use App\Http\Requests\Employee\PayslipsRequest;
use App\Http\Requests\Employee\PayslipsUpdateRequest;

use App\Http\Resources\PayslipCollection;
use App\Http\Resources\PayslipResource;

use App\Http\Resources\PayslipByEmployeeCollection;
use App\Http\Resources\PayslipByEmployeeResource;
use App\Http\Resources\PayslipSingleByEmployeeResource;

class PayslipController extends Controller
{
	protected $payslipRepo;
    
    /**
     * __construct
     *
     * 
     * @return void
     */
    public function __construct(
        PayslipRepository $payslipRepo
    ){
        
        $this->payslipRepo = $payslipRepo;
    }
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function index(PayslipFilter $request)
	{
		$paginate = isset(request()->paginate) ? request()->paginate : null;
        return response()->json(new PayslipByEmployeeCollection(Employee::with(['empPayslip' => function($q){
            if(request()->periode){
                $q->where('periode',request()->periode);
            }

            if(request()->type){
                $q->where('type',request()->type);
            }
        }],'santunanDuka','empPosition')->filter($request)->orderByDesc('created_at')->get()));

	}

    public function showByEmployee()
    {
        $record = Employee::with(['empPayslip' => function($q){
            if(request()->periode){
                $q->where('periode',request()->periode);
            }

            if(request()->type){
                $q->where('type',request()->type);
            }
        }],'santunanDuka');
        // ->where('npp',request()->npp)->where('kd_comp',request()->kd_comp)->first();
        // dd($record);

        if($npp = request()->npp){
            $record->where('npp',$npp);
        }

        if($kd_comp = request()->kd_comp){
            $record->where('kd_comp',$kd_comp);
        }

        if($unit_id = request()->unit_id){
            $record->where('unit_id',$unit_id);
        }

        // dd($record->first());

        $data = $record->first();
        return new PayslipSingleByEmployeeResource($data);
    }

    public function indexByEmployee(EmployeeFilter $request)
    {
        $paginate = isset(request()->paginate) ? request()->paginate : null;
        return response()->json(new PayslipByEmployeeCollection(Employee::with('empPayslip')->filter($request)->orderByDesc('created_at')->get()));

    }

    public function show($id)
    {
        return new PayslipByEmployeeResource(Employee::findOrFail($id));
    }

    public function store(PayslipsRequest $request){
        return $this->payslipRepo->create();
    }

    public function update(PayslipsUpdateRequest $request, $id){
        return $this->payslipRepo->updateData($id);
    }
}
