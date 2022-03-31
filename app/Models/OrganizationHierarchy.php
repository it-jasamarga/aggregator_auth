<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrganizationHierarchy extends Model
{
	protected $table = 'organization_hierarchy';
	protected $fillable = [
        'ID',
        'LEVEL',
        'COMPANY_ID',
        'NAME',
        'PARENT_ID',
        'PERSONAL_AREA',
        'PERSONAL_SUB_AREA',
        'COSTCENTER',
        'ACTIVE',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
    ];
	
    /**
	 * company
	 *
	 * @return void
	 */
	public function masterCompany()
	{
		return $this->belongsTo(MasterCompany::class, 'company_id','id');
	}

	/**
	 * parent
	 *
	 * @return void
	 */
	public function parent()
	{
		return $this->belongsTo(OrganizationHierarchy::class, 'parent_id');
	}
	
	/**
	 * child
	 *
	 * @return void
	 */
	public function child()
	{
		return $this->hasMany(OrganizationHierarchy::class, 'parent_id', 'id');
	}
	
	/**
	 * @deprecated ?
	 * allChild
	 *
	 * @return void
	 */
	public function allChild()
	{
		return $this->hasMany(OrganizationHierarchy::class, 'parent_id', 'id')->with('allChild');
	}
	

	/**
	 * masterPersonalArea
	 *
	 * @return void
	 */
	public function masterPersonalArea()
	{
		return $this->belongsTo(MasterPersonalArea::class, 'personal_area');
	}

	/**
	 * masterPersonalSubArea
	 *
	 * @return void
	 */
	public function masterPersonalSubArea()
	{
		return $this->belongsTo(MasterPersonalSubArea::class, 'personal_sub_area');
	}
	

	/**
	 * childOrganization
	 *
	 * @return void
	 */
	public function childOrganization()
	{
		return $this->hasMany(OrganizationHierarchy::class, 'parent_id', 'id')
			->with('childOrganization')
			->select([
				'id', 
				'parent_id', 
				'name as organization_name'
			]);
	}

	/**
	 * masterPosition
	 *
	 * @return void
	 */
	public function masterPosition()
	{
		return $this->hasMany(MasterPosition::class, 'org_id');
	}

	/**
	 * employeePos
	 *
	 * @return void
	 */
	public function employeePos()
	{
		return $this->hasMany(EmployeePosition::class, 'ORG_ID');
	}
}
