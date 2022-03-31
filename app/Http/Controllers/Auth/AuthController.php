<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Services\Auth\AuthService;

class AuthController extends Controller
{
	protected $service;
	
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct(AuthService $service)
	{
		$this->service = $service;
	}
	
	/**
	 * login
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function login()
	{
		$service = $this->service;
        return $service->login(); 
	}
}
