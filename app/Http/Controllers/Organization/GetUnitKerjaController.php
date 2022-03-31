<?php

namespace App\Http\Controllers\Organization;

use App\Repositories\OrganizationHierarchyRepository;
use Illuminate\Support\Facades\Cache;


class GetUnitKerjaController
{	
	protected $organizationHierarchy;
	
	/**
	 * __construct
	 *
	 * @param  mixed $organizationHierarchy
	 * @return void
	 */
	public function __construct(OrganizationHierarchyRepository $organizationHierarchy)
	{
		$this->organizationHierarchy = $organizationHierarchy;
	}

	/**
	 * __invoke
	 *
	 * @return void
	 */
	public function __invoke()
	{
		$repo = $this->organizationHierarchy;

		$data = Cache::remember('unit_kerja', 10 * 60, function () use ($repo) {
			return $repo->getUnitKerja();
		});

		if($data == null)
		{
			return response()->json([
				'status'    => 404,
				'message'   => 'Data tidak ditemukan',
			], 404);
		}

		return response()->json([
            'status'    => true,
            'code' 		=> 200,
            'message'   => config('constants.message.success.get'),
            'total'      => count($data),
            'data'      => $data,
        ], 200);
	}

	
}
