<?php

namespace App\Repositories;

use App\Models\OrganizationHierarchy;

class OrganizationHierarchyRepository extends Repository
{
	/**
	 * getClassName
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return OrganizationHierarchy::class;
	}
	
	/**
	 * buildTreeChild
	 *
	 * @param  mixed $orgId
	 * @return mixed
	 */
	public function buildTreeChild(string $orgId)
	{
		$qb = $this->createQueryBuilder();

		$qb->with('allChild');

		$qb->where('id', $orgId);
		// dd($qb->first());
		return $qb->first();
	}
	
	/**
	 * getUnitKerja
	 *
	 * @return void
	 */
	public function getUnitKerja()
	{
		$exception = [
			'40000514', // Group of Auditor
		];

		$qb = $this->createQueryBuilder();

		$qb->select('organization_hierarchy.id', 'organization_hierarchy.name as nama_unit', 'organization_hierarchy.type_organization');

		$qb->join('employee_position', 'employee_position.unit_kerja_id', 'organization_hierarchy.id');
		
		$qb->whereNotIn('organization_hierarchy.id', $exception);

		// only active position
		$qb->where('employee_position.active', '1');

		// dont include retired position ID
		$qb->where('employee_position.position_id', '!=', '99999999');

		$qb->where('organization_hierarchy.id', 'like', '4%');

		$qb->where('organization_hierarchy.updated_by', '!=', 'cronjob_master_org');

		$qb->groupBy('organization_hierarchy.id', 'organization_hierarchy.name', 'organization_hierarchy.type_organization');

		$qb->orderBy('organization_hierarchy.id', 'asc');

		return $qb->get();
	}

	public function getHierarchy(string $orgId)
	{
		$qb = $this->createQueryBuilder();

		$qb->select([
			'id',
			'parent_id',
			'name as organization_name'
		]);

		$qb->with([
			'parent.parent',
			'childOrganization',
		]);

		$qb->where('id', $orgId);

		return $qb->first();
	}
}
