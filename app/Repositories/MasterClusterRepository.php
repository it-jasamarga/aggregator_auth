<?php

namespace App\Repositories;

use App\Models\MasterCluster;

class MasterClusterRepository extends Repository
{	
	/**
	 * getClassName
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return MasterCluster::class;
	}	
    public function getMasterCluster()
	{

		$qb = $this->createQueryBuilder();

		return $qb->get();
	}
}
