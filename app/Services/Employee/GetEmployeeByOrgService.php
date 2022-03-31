<?php

namespace App\Services\Employee;

use App\Helpers\Helper;
use App\Repositories\OrganizationHierarchyRepository;

class GetEmployeeByOrgService
{
	protected $organization;

	public function __construct(OrganizationHierarchyRepository $organization)
	{
		$this->organization = $organization;
	}

	public function generateOrgId(?string $orgId = null): array
	{
		if($orgId == null)
		{
			return [];
		}

		$org = [$this->organization->buildTreeChild($orgId)->toArray()];

		$array = [];

		// array_column() but for multidimensional
		array_walk_recursive(
			$org,
			function($leafNode, $key) use(&$array) {
				if ($key === 'id') {
					$array[] = $leafNode;
				}
			}
		);

		return $array;
	}
}
