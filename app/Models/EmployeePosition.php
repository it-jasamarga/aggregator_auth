<?php

namespace App\Models;

use Awobaz\Compoships\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EmployeePosition extends Model
{
    protected $table = 'employee_position';
    protected $primaryKey = 'id';
    
    /**
     * employee
     *
     * @return BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'atasan_id');
    }

    public function bawahan()
    {
        return $this->hasMany(EmployeePosition::class, ['atasan_id', 'atasan_position_id'], ['employee_id', 'position_id']);
    }

    public function company(){
        return $this->belongsTo(MasterCompany::class,'company_id_asal');
    }

    public function job(){
        return $this->belongsTo(MasterJob::class,'job_id');
    }

    public function masterPosition(){
        return $this->belongsTo(MasterPosition::class,'position_id');
    }

    public function masterUnitKerja(){
        return $this->belongsTo(OrganizationHierarchy::class,'unit_kerja_id');
    }

    public function masterLayer(){
        return $this->belongsTo(MasterLayer::class,'layer_id');
    }

     public function masterSubgroup(){
        return $this->belongsTo(MasterEmployeesSubGroup::class,'employee_subgroup_id');
    }
}
