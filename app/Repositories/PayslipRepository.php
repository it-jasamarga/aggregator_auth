<?php

namespace App\Repositories;

use App\Models\Payslip;

class PayslipRepository extends Repository
{	
	/**
	 * getClassName
	 *
	 * @return string
	 */
	public function getClassName(): string
	{
		return Payslip::class;
	}
	
	/**
	 * getEmployeePayslip
	 *
	 * @param  mixed $companyId
	 * @param  mixed $npp
	 * @param  mixed $period
	 * @return Payslip
	 */
	public function getEmployeePayslip(?string $companyId = null, ?string $npp = null, ?string $period = null): ?Payslip
	{
		if($companyId == null || $npp == null || $period == null)
			return null;

		$qb = $this->createQueryBuilder();

		$qb->select([
			'employee.name as nama',
			'employee.npp as employee_number',
			'employee.personnel_number as person_number',
			'payslip.*'
		]);

		$qb->leftJoin('employee', 'employee.id', 'payslip.employee_id');
		$qb->leftJoin('employee_position', 'employee.id', 'employee_position.employee_id');

		$qb->where(function($q) use ($companyId) {
			$q->where('company_id_asal', $companyId);
			$q->orWhere('company_id_penugasan', $companyId);
		});

		$qb->where('employee.employee_status', '1');
        $qb->where('employee_position.active', '1');
		$qb->where('employee_position.npp', $npp);
		$qb->where('payslip.periode', $period);

		return $qb->first();
	}
}
