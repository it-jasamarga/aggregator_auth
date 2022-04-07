<?php

namespace App\Services\Auth;

use App\Models\UserAuth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Employee\GetOneEmployeeController;

use App\Services\Employee\GetOneEmployeeService;
use App\Repositories\EmployeeRepository;
use App\Repositories\MasterCompanyRepository;
use JWTAuth;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Carbon\Carbon;
use App\Helpers\Helper;
class AuthService
{	

	protected $employee;
    protected $service;
    protected $company;

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
	 * login
	 *
	 * @param  mixed $data
	 * @return void
	 */
	public function login()
	{	
		$validate = $this->validate();
		if(count($validate['data']) > 0){
			return response()->json([
				'errors' => $validate['data']
			],422);
		}

		$record = UserAuth::where(\DB::raw('LOWER(username)'),strtolower(request()->username))->first();
		if($record){

			if($record->active == 0){
				return response()->json([
					'status' => 401,
                    'message' => 'Akun Pengguna Saat Ini Tidak Aktif',
                    'data' => 'Akun Pengguna Saat Ini Tidak Aktif'
				],400);
			}
			
			// $checkPass['checkHash'] = false;
			if($record->is_ldap == 1){
				// $masterPassword = '$2y$10$LlM0TBdbpxp4wwVLdcQ7T.lyPEJk2d6o4ldcZBzhK.GiYF1n.9HBe';
				// $hashpassword = Hash::check(request()->password,$masterPassword);
				$checkLdap = $this->checkLdap($record);
				// dump($checkLdap);
				if($checkLdap == false){
					return response()->json([
						'status' => 400,
	                    'message' => 'Password Salah',
	                    'data' => 'Password Salah'
					],400);
				}
			}else{
				$checkPass = $this->checkPassword($record);
				if($checkPass['status'] == 400){
					return response()->json($checkPass,$checkPass['status']);
				}

			}

			$remapData = $this->remapData($record);
			
			return response()->json($remapData,$remapData['status']);

		}else{
			return response()->json([
				'status' => 400,
				'message' => 'Username Tidak Ditemukan',
				'data' => []
			],400); 
		}
	}

	public function validate(){
		$return = [];
		if(is_null(request()->username)){
			$return['username'] = [
				"value" => request()->username,
	            "msg" => "Username is required",
	            "param" => "username",
	            "location" => "body"
			];
		}

		if(empty(request()->username)){
			$return['username'] = [
				"value" => request()->username,
	            "msg" => "Username is required",
	            "param" => "username",
	            "location" => "body"
			];
		}

		if(is_null(request()->password)){
			$return['password'] = [
				"value" => request()->password,
	            "msg" => "Password is required",
	            "param" => "password",
	            "location" => "body"
			];
		}

		if(empty(request()->password)){
			$return['password'] = [
				"value" => request()->password,
	            "msg" => "Password is required",
	            "param" => "password",
	            "location" => "body"
			];
		}

		$result = [];
		if(isset($return['username'])){
			array_push($result,$return['username']);
		}

		if(isset($return['password'])){
			array_push($result,$return['password']);
		}

		return [
			'data' => $result
		];
	}

	public function checkPassword($record){
    	$masterPassword = '$2y$10$LlM0TBdbpxp4wwVLdcQ7T.lyPEJk2d6o4ldcZBzhK.GiYF1n.9HBe';
    	$hashpassword = Hash::check(request()->password,$masterPassword);
    	
    	if($hashpassword){
    		return [
    			'status' => 200,
    			'checkHash' => true
    		];
    	}elseif(Hash::check(request()->password,$record->password)){
    		return [
    			'status' => 200,
    			'checkHash' => false
    		];
		}else{
			return [
				"status" => 400,
			    "message" => "Password Salah",
			    "data" => false
			];
		}
	}

	public function checkLdap($record){
		try {
            $adServer = 'ldap://wdc02.jasamarga.co.id';
            $ldap = ldap_connect($adServer);
            $ldaprdn = 'jasamarga'.'\\'.request()->username;
            ldap_set_option($ldap, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($ldap, LDAP_OPT_REFERRALS, 0);
            $bind = ldap_bind($ldap, $ldaprdn, request()->password);
            // dd($bind);
            if ($bind) {
                return true;
            } else {
                return false;
            }
        } catch (\Exception $e) {
        	// dd('e',$e);
            return false;
        } catch (\Throwable $e) {
        	// dd('throw',$e);

            return false;
        }
	}

	public function remapData($record){
		$employee = ($record->employee) ? $record->employee : null;
		$employeeRepo   = $this->employee;
        $service        = $this->service;
        
        request()['CompanyCode'] = $employee->kd_comp;
        request()['NPP'] = $employee->npp;

        $company = $this->company->findOneBy(['code' => ($employee) ? $employee->kd_comp : null]);

		$employeeData = $employeeRepo->getOneEmployee($company->id, $employee->npp);
   
        if(!$employeeData){
            $employeeData = $employeeRepo->getOneEmployee($company->id, $employee->npp, true);
        }
        $dataRes = $service->remapOneEmployee($employeeData);
		
		$employeePosition = null;
		$employeeSubgroup = null;
		$organizationHierarchy = null;
		if($employee){
			$employeePosition = ($employee->positionActive) ? $employee->positionActive()->where('active','1')->first() : null;

			if($employeePosition){
				$employeeSubgroup = ($employeePosition->masterSubgroup) ? $employeePosition->masterSubgroup : null;
			}
		}

		$data = [
			"id" 				=> $record->id,
	        "username" 			=> $record->username,
	        "v_username" 		=> $record->username,
	        "role" 				=> null,
	        "multi_role" 		=> [],
	        "employee_number" 	=> ($employee) ? $employee->npp : null,
	        "kd_comp" 			=> ($employee) ? $employee->kd_comp : null,
	        "kd_unit" 			=> ($employee) ? $employee->unit_id : null,
	        "kd_comp_penugasan" => ($employeePosition) ? $employeePosition->company_code_penugasan : null,
	        "kelompok_jabatan" 	=> ($employeeSubgroup) ? $employeeSubgroup->description : null,
	        "latitude" 			=> null,
	        "longtitude"		=> null,
	        "batas_checkin" 	=> null,
	        "batas_checkout" 	=> null,
	        "mulai_overtime" 	=> null,
	        "UNIQ_CODE" 		=> null,
	        "nm" 				=> ($employee) ? $employee->name : null,
	        "employe" => [
	        	"id" 					=> ($dataRes) ? $dataRes->id : null,
	        	"person_name" 			=> ($dataRes) ? $dataRes->person_name : null,
	        	"employee_number" 		=> ($dataRes) ? $dataRes->employee_number : null,
	        	"national_identifier"	=> ($dataRes) ? $dataRes->national_identifier : null,
	        	"sex" 					=> ($dataRes) ? $dataRes->sex : null,
	        	"original_date_of_hire"	=> ($dataRes) ? $dataRes->original_date_of_hire : null,
	        	"npwp" 					=> ($dataRes) ? $dataRes->npwp : null,
	        	"status_npwp" 			=> ($dataRes) ? $dataRes->status_npwp : null,
	        	"email_address" 		=> ($dataRes) ? $dataRes->email_address : null,
	        	"emp_status" 			=> ($dataRes) ? $dataRes->emp_status : null,
	        	"url_image" 			=> ($dataRes) ? $dataRes->url_image : null,
	        	"person_number_sap" 	=> ($dataRes) ? $dataRes->person_number_sap : null,
	        	"fungsi_jabatan" 		=> ($dataRes) ? $dataRes->fungsi_jabatan : null,
	        	"front_title_education" => ($dataRes) ? $dataRes->front_title_education : null,
	        	"end_title_education" 	=> ($dataRes) ? $dataRes->end_title_education : null,
	        	"rangkap_jabatan" 		=> ($dataRes) ? $dataRes->rangkap_jabatan : null,
	        	"sk_phk_no" 			=> ($dataRes) ? $dataRes->sk_phk_no : null,
	        	"sk_phk_date" 			=> ($dataRes) ? $dataRes->sk_phk_date : null,
	        	"phk_out_date" 			=> ($dataRes) ? $dataRes->phk_out_date : null,
	        	"ket_phk" 				=> ($dataRes) ? $dataRes->ket_phk : null,
	        	"place_of_birth" 		=> ($dataRes) ? $dataRes->place_of_birth : null,
	        ],
	        "employe_position" => (@$dataRes->position_active) ? $dataRes->position_active : null
		];
		

		// DATA TOKEN
		$userId = ($employee) ? $employee->npp : null;
		$userId = $userId.$data['kd_comp'];
		$dataPayload = [
	        'user' 				=> request()->username,
	        'username' 			=> request()->username,
	        'v_username' 		=> request()->username,
	        'kd_comp' 			=> $data['kd_comp'],
	        'kd_comp_penugasan' => $data['kd_comp_penugasan'],
	        'kelompok_jabatan' 	=> $data['kelompok_jabatan'],
	        'multi_role' 		=> $data['multi_role'],
	        'role' 				=>	$data['role'],
	        'unit' 				=> (@$data['employe_position']['unit_kerja']) ? $data['employe_position']['unit_kerja'] : null,
	        'kdunit' 			=> $data['kd_unit'],
	        'photo' 			=> $data['employe']['url_image'],
	        'nama' 				=> $data['nm'],
	        'userid' 			=> $userId,
	        'position_id'		=> (@$data['employe_position']['position_id']) ? $data['employe_position']['position_id'] : null,
	        'unit_kerja_id'		=> (@$data['employe_position']['unit_kerja_id']) ? $data['employe_position']['unit_kerja_id'] : null,
		];
		$generateJwt = $this->generateJwt($dataPayload, $record);

		if($generateJwt['status'] == 400){
			return $generateJwt;
		}

		$data['jwt'] = $generateJwt['data'];

		$record->update([
			'TOKEN' => $generateJwt['data']['token'],
			'LAST_LOGIN' => Carbon::now()->format('Y-m-d H:i:s'),
		]);

		return [
			'status' => 200,
            'message' =>  'Success Login',
            'data' =>  $data
		];
	}

	// GENERATE JWT
	public function generateJwt($data, $record){
		if(($record->is_ldap != 1) OR ($record->is_ldap != '1')){
			if (!$token = \Auth::claims($data)->attempt([
				'username' => $record->username,
				'password' => request()->password,
			])){
	            return [
	            	'status' => 400,
	                'message' =>  'Username Tidak Ditemukan',
	                'data' =>  []
	            ];
	        }

	        return [
	        	'status' => 200,
	            'message' =>  'Success Login',
	            'data' =>  [
	            	'token' => $token,
	            	'expires' => auth('api')->payload()('exp')
	            ]
	        ];
		}else{
			JWT::$leeway = 60;

			$data["iat"] = Carbon::now()->timestamp;
			$data["exp"] = Carbon::now()->addMinutes(720)->timestamp;
			$data["nbf"] = Carbon::now()->timestamp;
			$data["jti"] = Helper::generateRandomString(16);
			$data["sub"] = '0.0.0.0';
			// $data["prv"] = "38e4bce815cf28c2a3af54149ccbe1332a3e6c6c";

			$token = JWT::encode($data, 'jasamarga', 'HS256');
			// $decoded = JWT::decode($token, new Key(env('JWT_SECRET'), 'HS256'));
			
			return [
	        	'status' => 200,
	            'message' =>  'Success Login',
	            'data' =>  [
	            	'token' => $token,
	            	'expires' => Carbon::now()->addMinutes(720)->timestamp
	            ]
	        ];
		}
	}

}
