<?php

namespace App\Services\OrganizationHierarchy;

use App\Models\OrganizationHierarchy;

class GetOrgHierarchyService
{	
	/**
	 * remapHierarchy
	 *
	 * @param  mixed $data
	 * @return void
	 */
	public function remapHierarchy(?OrganizationHierarchy $data = null)
	{
		if($data == null)
		{
			return $data;
		}

		$data->parent_organization_id = $data->parent->id ?? null;
		$data->parent_organization_name = $data->parent->name ?? null;
		$data->grand_parent_organization_id = $data->parent->parent->id ?? null;
		$data->grand_parent_organization_name = $data->parent->parent->name ?? null;

		$data->unsetRelation('parent');

		return $data;
	}
}
