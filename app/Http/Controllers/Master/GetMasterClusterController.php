<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use App\Repositories\MasterClusterRepository;


class GetMasterClusterController extends Controller
{
	protected $cluster;
	
	/**
	 * __construct
	 *
	 * @return void
	 */
	public function __construct(
		MasterClusterRepository $cluster
	)
	{
		$this->cluster 	= $cluster;

	}
	
	/**
	 * __invoke
	 *
	 * @param  mixed $request
	 * @return void
	 */
	public function __invoke()
	{

        $repo = $this->cluster;
        $data = Cache::remember('cluster', 10 * 60, function () use ($repo) {
			return $repo->getMasterCluster();
		});
		

		return response()->json([
            'status'    => true,
            'code' 		=> 200,
            'message'   => config('constants.message.success.get'),
            'data'      => $data,
        ], 200);
	}
}
