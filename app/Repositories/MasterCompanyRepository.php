<?php

namespace App\Repositories;

use App\Models\MasterCompany;

class MasterCompanyRepository extends Repository
{	
	/**
	 * getClassName
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return MasterCompany::class;
	}	
}
