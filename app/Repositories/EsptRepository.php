<?php

namespace App\Repositories;

use App\Models\Espt;
use Illuminate\Database\Eloquent\Collection;

class EsptRepository extends Repository
{	
	/**
	 * getClassName
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return Espt::class;
	}
	
	/**
	 * getEmployeeEspt
	 *
	 * @param  mixed $companyId
	 * @param  mixed $npp
	 * @return Collection
	 */
	public function getEmployeeEspt(?string $companyId = null, ?string $npp = null): ?Collection
	{
		if($companyId == null || $npp == null)
			return null;

		$qb = $this->createQueryBuilder();

		$qb->select([
			'employee.name as nama',
			'employee.npp as employee_number',
			'espt.*'
		]);

		$qb->leftJoin('employee', 'employee.id', 'espt.employee_id');
		$qb->leftJoin('employee_position', 'employee.id', 'employee_position.employee_id');

		$qb->where(function($q) use ($companyId) {
			$q->where('company_id_asal', $companyId);
			$q->orWhere('company_id_penugasan', $companyId);
		});

		$qb->where('employee.employee_status', '1');
        $qb->where('employee_position.active', '1');
		$qb->where('employee_position.npp', $npp);

		$qb->orderBy('tahun_pajak', 'desc');
		$qb->orderBy('masa_pajak', 'desc');

		return $qb->get();
	}
}
