<?php

namespace App\Http\Controllers\Organization;

use App\Http\Controllers\Controller;
use App\Models\OrganizationHierarchy;
use App\Http\Requests\Organization\GetOrgHierarchyRequest;
use App\Repositories\OrganizationHierarchyRepository;
use App\Services\OrganizationHierarchy\GetOrgHierarchyService;
use Illuminate\Support\Facades\Cache;

class GetOrgHierarchyController extends Controller
{
	protected $organizationHierarchy;
	protected $service;
	
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct(
		OrganizationHierarchyRepository $organizationHierarchy,
		GetOrgHierarchyService $service
		)
	{
		$this->organizationHierarchy = $organizationHierarchy;
		$this->service 				 = $service;
	}
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function __invoke(GetOrgHierarchyRequest $request)
	{
		$params = $request->getParams();
		$repo   = $this->organizationHierarchy;
		$service= $this->service;

		$data = Cache::remember("hierarchy.{$params->OrganizationID}", 10 * 60, function () use ($repo, $service, $params) {
			$data = $repo->getHierarchy($params->OrganizationID);
			return $service->remapHierarchy($data);
		});

		return response()->json([
            'status'    => true,
            'code' 		=> 200,
            'message'   => config('constants.message.success.get'),
            'data'      => $data,
        ], 200);
	}
}
