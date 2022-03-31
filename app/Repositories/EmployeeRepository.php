<?php

namespace App\Repositories;

use App\Models\Employee;
use App\Models\MasterJobFunction;
use App\Models\MasterEmployeeGroup;
use App\Models\MasterEmployeesSubGroup;
use App\Models\MasterCity;
use App\Models\MasterProvince;
use App\Models\OrganizationHierarchy;
use App\Repositories\Repository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class EmployeeRepository extends Repository
{        
    /**
     * getClassName
     *
     * @return string
     */
    public function getClassName(): string
    {
        return Employee::class;
    }
        
    /**
     * getOneEmployee
     *
     * @param  mixed $companyId
     * @param  mixed $npp
     * @return Employee
     */
    public function getOneEmployee(?string $companyId = null, ?string $npp = null, ?string $checkData = null): ?Employee
    {
        $qb = $this->createQueryBuilder();

        $qb->select([
            'employee.id',
            'employee.name as person_name',
            'employee_position.npp as employee_number',
            'employee.national_identifier as national_identifier',
            'employee.gender as sex',
            'employee.date_of_entry as original_date_of_hire',
            'employee.npwp as npwp',
            'employee.status_npwp as status_npwp',
            'employee.email as email_address',
            'employee.employee_status as emp_status',
            'employee.url_image as url_image',
            'employee.personnel_number as person_number_sap',
            'employee.employee_group_id',
            'employee.employee_subgroup',
            'employee.job_function_id as fungsi_jabatan',
            'employee.front_title_education as front_title_education',
            'employee.end_title_education as end_title_education',
            'employee.is_rangkap_jabatan as rangkap_jabatan',
            'employee.sk_phk_no as sk_phk_no',
            'employee.sk_phk_date as sk_phk_date',
            'employee.sk_phk_out_date as phk_out_date',
            'employee.ket_phk as ket_phk',
            'employee.address_ktp',
            'employee.cityid_ktp',
            'employee.provinceid_ktp',
            'employee.address_domicile',
            'employee.cityid_domicile',
            'employee.bpjs_kes_no',
			'employee.bpjs_ket_no',
			'employee.blood_type',
			'employee.weight',
			'employee.height',
			'employee.marital_status',
			'employee.religion',
			'employee.place_of_birth',
			'employee.date_of_birth',
            'employee.telephone_no',
            'employee.instagram',
            'employee.twitter',
            'employee.facebook',
            'employee.summary',
            'employee.interest',
            'employee.no_dana_pensiun',
            'employee.attachment_kk',
			'employee.attachment_ktp',
            'employee.attachment_npwp',
            'employee.attachment_buku_nikah',
            'employee.attachment_bpjs_ket',
            'employee.attachment_bpjs_kes',
            'employee.attachment_dana_pensiun',
            
        ]);
       
        $qb->join('employee_position', 'employee_position.employee_id', 'employee.id');
         // join for subcluster
       

        $qb->with([
            'latestEducation.jenjang',
            'cityKtp',
            'provinceKtp',
            'cityDomicile',
            'provinceDomicile',
            'position' => function($query) {

                $query->select([
                    'employee_position.id as employee_position_id',
                    'employee_position.employee_id',
                    'employee_position.npp',
                    'master_position.id as position_id',
                    'master_position.name',
                    'employee_position.active',
                    'employee_position.sk_position_no',
                    'employee_position.start_date',
                    'employee_position.end_date',
                    'organization_hierarchy.name as org_name',
                    'organization_hierarchy.parent_id as org_id_parent',
                    'parent.name as org_parent_name',
                    'parent.id as org_grand_parent',
                    'grand_parent.name as org_grand_parent_name',
                    'employee_position.unit_kerja_id',
                    'unit_kerja.name as unit_kerja',
                    'unit_kerja.type_organization as unit_kerja_type_org',
                    'organization_hierarchy.personal_sub_area as location_id',
                    'master_personal_sub_area.description as location_name',
                    'master_business_area.description as business_area',
                    'company_asal.code as company_code_asal',
                    'company_penugasan.code as company_code_penugasan',
                    'employee_position.grade',
                    'employee_position.sub_grade as subgrade',
                    'master_layer.description as layer',
                    'master_job.id as job_id',
                    'master_job.name as job_name',
                    'employee_position.kelompok_jabatan as job_type',
                    'master_employee_group.description as employee_group',
                    'atasan.personnel_number as person_number_approver',
                    'master_personal_area.description as personal_area',
                    'master_personal_sub_area.description as personal_sub_area',
                    'master_subcluster.cluster_kode',
                    'master_subcluster.kode as subcluster_code',
                    'master_subcluster.name as subcluster_name',
                    'master_subcluster.fungsi as subcluster_fungsi'

                    
                ]);

                $query->leftJoin('master_position', 'employee_position.position_id', 'master_position.id');
                $query->leftJoin('organization_hierarchy', 'master_position.org_id', 'organization_hierarchy.id');
                $query->leftJoin('master_personal_sub_area', 'organization_hierarchy.personal_sub_area', 'master_personal_sub_area.id');
                $query->leftJoin('master_business_area', 'employee_position.business_area_id', 'master_business_area.id');
                $query->leftJoin('master_layer', 'employee_position.layer_id', 'master_layer.id');
                $query->leftJoin('master_job', 'employee_position.job_id', 'master_job.id');
                $query->leftJoin('master_employee_group', 'employee_position.employee_group_id', 'master_employee_group.id');
                $query->leftJoin('master_personal_area', 'organization_hierarchy.personal_area', 'master_personal_area.id');
                //$query->leftJoin('map_position_subcluster', 'map_position_subcluster.position_name', 'employee_position.position');
                
                // left join to organization_hierarchy for get parent_name
                $query->leftJoin('organization_hierarchy parent', 'organization_hierarchy.parent_id', 'parent.id');

                // left join to organization_hierarchy for get grand parent detail
                $query->leftJoin('organization_hierarchy grand_parent', 'parent.parent_id', 'grand_parent.id');

                // left join to organization_hierarchy for get unit kerja
                $query->leftJoin('organization_hierarchy unit_kerja', 'employee_position.unit_kerja_id', 'unit_kerja.id');

                // join for company code asal
                $query->leftJoin('master_company company_asal', 'employee_position.company_id_asal', 'company_asal.id');

                // join for company code penugasan
                $query->leftJoin('master_company company_penugasan', 'employee_position.company_id_penugasan', 'company_penugasan.id');

                // join for atasan
                $query->leftJoin('employee atasan', 'employee_position.atasan_id', 'atasan.id');

                //join map_position_cluster

               
                $query->leftJoin('map_position_subcluster', function($join){
                    $join->on('map_position_subcluster.position_name', '=', 'employee_position.position');
                    $join->on('map_position_subcluster.unit_kerja_id', '=', 'employee_position.unit_kerja_id');
                });
                $query->leftJoin('master_subcluster', 'master_subcluster.id', 'map_position_subcluster.master_subcluster_id');
        

                // order from latest to oldest
                $query->orderBy('employee_position.start_date', 'desc');
            }
        ]);
        $qb->where('employee_position.npp', $npp);
        if($checkData == null){
            $qb->where('employee.kd_comp', request()->CompanyCode);            
        }
        
        if($checkData != null){
            $qb->where(function($query) use ($companyId) {
                $query->where('company_id_asal', $companyId);
                $query->orWhere('company_id_penugasan', $companyId);
            });
        }
        
        $data = $qb->first();
        if($data){
            $date = Carbon::parse($data->date_of_birth);
            $now = Carbon::now();

            $diff = $date->diffInYears($now);
            $data['age'] = $diff;
        }
        return $data;
    }
    
    

    /**
     * getEmployeeByCompany
     *
     * @param  mixed $companyId
     * @return array
     */
    public function getEmployeeByCompany(?string $companyId = null): ?Collection
    {
        $qb = $this->createQueryBuilder();

        $qb->select([
            'employee.id',
            'employee.name as person_name',
            'employee.npp as employee_number',
            'employee.email',
            'employee.telephone_no',
            'employee.is_penugasan',
            'employee.date_of_birth',
        ]);

        $qb->with([
            'position' => function($query) use ($companyId) {
                $query->select([
                    'employee_id',
                    'employee_position.position_id',
                    'master_position.name as position_name',
                    'employee_position.grade',
                    'employee_position.sub_grade as subgrade',
                    'master_layer.description as layer',
                    'master_job.name as job_name',
                    'employee_position.kelompok_jabatan as job_type',
                    'organization_hierarchy.id as org_id',
                    'organization_hierarchy.name as org_name',
                    'employee_position.unit_kerja_id',
                    'unit_kerja.name as unit_kerja',
                    'unit_kerja.type_organization as unit_kerja_type_org',
                    'master_employee_group.description as employee_group',
                    'company_asal.code as kd_comp_asal',
                    'company_penugasan.code as kd_comp_penugasan',
                    'master_subcluster.cluster_kode',
                    'master_subcluster.kode as subcluster_code',
                    'master_subcluster.name as subcluster_name',
                    'master_subcluster.fungsi as subcluster_fungsi',
                    'organization_hierarchy.personal_area as personal_area_id',
                    'master_personal_area.description as personal_area',
                    'organization_hierarchy.personal_sub_area as personal_sub_area_id',
                    'master_personal_sub_area.description as personal_sub_area',
                ]);

                $query->leftJoin('master_position', 'employee_position.position_id', 'master_position.id');
                $query->leftJoin('organization_hierarchy', 'master_position.org_id', 'organization_hierarchy.id');
                
                $query->leftJoin('master_personal_area', 'organization_hierarchy.personal_area', 'master_personal_area.id');
                $query->leftJoin('master_personal_sub_area', 'organization_hierarchy.personal_sub_area', 'master_personal_sub_area.id');
                
                $query->leftJoin('master_position', 'employee_position.position_id', 'master_position.id');
                $query->leftJoin('master_layer', 'employee_position.layer_id', 'master_layer.id');
                $query->leftJoin('master_job', 'employee_position.job_id', 'master_job.id');
                $query->leftJoin('master_employee_group', 'employee_position.employee_group_id', 'master_employee_group.id');

                // left join to organization_hierarchy for get unit kerja
                $query->leftJoin('organization_hierarchy unit_kerja', 'employee_position.unit_kerja_id', 'unit_kerja.id');

                // join to company asal
                $query->leftJoin('master_company company_asal', 'employee_position.company_id_asal', 'company_asal.id');

                // join to company penugasan
                $query->leftJoin('master_company company_penugasan', 'employee_position.company_id_penugasan', 'company_penugasan.id');

                $query->leftJoin('map_position_subcluster', function($join){
                    $join->on('map_position_subcluster.position_name', '=', 'employee_position.position');
                    $join->on('map_position_subcluster.unit_kerja_id', '=', 'employee_position.unit_kerja_id');
                });
                $query->leftJoin('master_subcluster', 'master_subcluster.id', 'map_position_subcluster.master_subcluster_id');
        


                $query->where('employee_position.active', '1');

                // all employee
                if($companyId != null)
                {
                    $query->where(function($q) use ($companyId) {
                        $q->where('company_id_asal', $companyId);
                        $q->orWhere('company_id_penugasan', $companyId);
                    });
                }
            }
        ]);

        $qb->join('employee_position', 'employee_position.employee_id', 'employee.id');
        
        // all employee
        if($companyId != null)
        {
            $qb->where(function($query) use ($companyId) {
                $query->where('company_id_asal', $companyId);
                $query->orWhere('company_id_penugasan', $companyId);
            });
        }

        $qb->where('employee.employee_status', '1');
        $qb->where('employee_position.active', '1');

        $qb->groupBy(
            'employee.id',
            'employee.name',
            'employee.npp',
            'employee.email',
            'employee.telephone_no',
            'employee.is_penugasan',
            'employee.date_of_birth'
        );
        $data = $qb->get();
        if($data->count() > 0){
            $data = $data->map(function($q){
                $date = Carbon::parse($q->date_of_birth);
                $now = Carbon::now();

                $diff = $date->diffInYears($now);
                $q['age'] = $diff;
                return $q;
            });
        }
        return $data;
    }
    
    /**
     * getEmployeeByOrganization
     *
     * @param  mixed $orgId
     * @return void
     */
    public function getEmployeeByOrganization(?array $orgId = [])   
    {
        $qb = $this->createQueryBuilder();

        $qb->select([
            'employee.id',
            'employee.name as person_name',
            'employee.npp as employee_number',
            'employee.kd_comp as kd_comp',
            'employee.email',
            
        ]);

        $qb->with([
            'position' => function($query) use ($orgId) {

                $query->select([
                    'employee_id',
                    'employee_position.position_id',
                    'employee_position.kd_comp',
                    'master_position.name as position_name',
                    'employee_position.grade',
                    'employee_position.sub_grade as subgrade',
                    'master_layer.description as layer',
                    'employee_position.unit_kerja_id',
                    'unit_kerja.name as unit_kerja',
                    'master_position.org_id as organization_id',
                    'organization_hierarchy.name as organization_name',
                    'master_personal_area.description as personal_area',
                    'master_personal_sub_area.description as personal_sub_area',
                    'organization_hierarchy.costcenter',
                    'master_subcluster.cluster_kode',
                    'master_subcluster.kode as subcluster_code',
                    'master_subcluster.name as subcluster_name',
                    'master_subcluster.fungsi as subcluster_fungsi'
                ]);

                $query->leftJoin('master_position', 'master_position.id', 'employee_position.position_id');
                $query->leftJoin('master_layer', 'employee_position.layer_id', 'master_layer.id');
                $query->leftJoin('organization_hierarchy', 'organization_hierarchy.id','master_position.org_id');
                $query->leftJoin('master_personal_sub_area', 'organization_hierarchy.personal_sub_area', 'master_personal_sub_area.id');
                $query->leftJoin('master_personal_area', 'organization_hierarchy.personal_area', 'master_personal_area.id');

                // left join to organization_hierarchy for get unit kerja
                $query->leftJoin('organization_hierarchy unit_kerja', 'employee_position.unit_kerja_id', 'unit_kerja.id');

                $query->leftJoin('map_position_subcluster', function($join){
                    $join->on('map_position_subcluster.position_name', '=', 'employee_position.position');
                    $join->on('map_position_subcluster.unit_kerja_id', '=', 'employee_position.unit_kerja_id');
                });
                $query->leftJoin('master_subcluster', 'master_subcluster.id', 'map_position_subcluster.master_subcluster_id');
        


                $query->where('employee_position.active', '1');
                $query->whereIn('master_position.org_id', $orgId);
            }
        ]);

        $qb->join('employee_position', 'employee_position.employee_id', 'employee.id');
        $qb->join('master_position', 'master_position.id', 'employee_position.position_id');

        $qb->whereIn('master_position.org_id', $orgId);
        
        // active employee
        $qb->where('employee.employee_status', '1');
        $qb->where('employee_position.active', '1');

        $qb->groupBy('employee.id', 'employee.name', 'employee.npp', 'employee.email','employee.kd_comp');

        return $qb->get();
    }

    
    
    /**
     * getEmployeeByUnit
     *
     * @param  mixed $orgId
     * @return void
     */
    public function getEmployeeByUnit(?string $unitId = null)   
    {
        $qb = $this->createQueryBuilder();

        $qb->select([
            'employee.id',
            'employee.name as person_name',
            'employee.npp as employee_number',
            'employee.email',
            
        ]);

        $qb->with([
            'position' => function($query) use ($unitId) {

                $query->select([
                    'employee_id',
                    'employee_position.position_id',
                    'master_position.name as position_name',
                    'employee_position.grade',
                    'employee_position.sub_grade as subgrade',
                    'master_layer.description as layer',
                    'employee_position.unit_kerja_id',
                    'unit_kerja.name as unit_kerja',
                    'master_position.org_id as organization_id',
                    'organization_hierarchy.name as organization_name',
                    'master_personal_area.description as personal_area',
                    'master_personal_sub_area.description as personal_sub_area',
                    'organization_hierarchy.costcenter',
                ]);

                $query->leftJoin('master_position', 'master_position.id', 'employee_position.position_id');
                $query->leftJoin('master_layer', 'employee_position.layer_id', 'master_layer.id');
                $query->leftJoin('organization_hierarchy', 'organization_hierarchy.id','master_position.org_id');
                $query->leftJoin('master_personal_sub_area', 'organization_hierarchy.personal_sub_area', 'master_personal_sub_area.id');
                $query->leftJoin('master_personal_area', 'organization_hierarchy.personal_area', 'master_personal_area.id');

                // left join to organization_hierarchy for get unit kerja
                $query->leftJoin('organization_hierarchy unit_kerja', 'employee_position.unit_kerja_id', 'unit_kerja.id');

                $query->where('employee_position.active', '1');
                $query->where('employee_position.unit_kerja_id', $unitId);
            }
        ]);

        $qb->join('employee_position', 'employee_position.employee_id', 'employee.id');
        $qb->join('master_position', 'master_position.id', 'employee_position.position_id');

        $qb->where('employee_position.unit_kerja_id', $unitId);
        
        // active employee
        $qb->where('employee.employee_status', '1');
        $qb->where('employee_position.active', '1');
        // dd($qb->where('employee.id',41793)->get());
        $qb->groupBy('employee.id', 'employee.name', 'employee.npp', 'employee.email');

        return $qb->get();
    }
    
    /**
     * getEmployeeWithAtasan
     *
     * @param  mixed $companyId
     * @param  mixed $npp
     * @param  mixed $positionId
     * @return void
     */
    public function getEmployeeWithAtasan(?string $companyId = null, ?string $npp = null, ?string $positionId = null): ?Employee
    {
        $qb = $this->createQueryBuilder();

        $qb->select([
            'employee.id',
            'employee.name as person_name',
            'employee.npp as employee_number',
            'employee_position.id as employee_position_ids',
            'employee_position.position_id',
            'master_position.name as position_name',
            'employee_position.unit_kerja_id',
            'unit_kerja.name as unit_kerja_name',
            'unit_kerja.type_organization as unit_kerja_type_org',
            'organization_hierarchy.id as organization_id',
            'organization_hierarchy.name as organization_name',
            'atasan.name as nama_atasan',
            'atasan.npp as employee_number_atasan',
            'master_position_atasan.id as position_id_atasan',
            'master_position_atasan.name as position_name_atasan',
            'unit_kerja_atasan.id as unit_kerja_id_atasan',
            'unit_kerja_atasan.name as unit_kerja_atasan',
            'unit_kerja_atasan.type_organization as unit_kerja_atasan_type_org',
            'organization_hierarchy_atasan.id as organization_id_atasan',
            'organization_hierarchy_atasan.name as organization_name_atasan',
            'atasan_position.company_id_asal as company_id_asal_atasan',
            'company_atasan_asal.name as company_name_asal_atasan',
            'atasan_position.company_id_penugasan as company_id_penugasan_atasan',
            'atasan_position.id as atasan_empeloyee_position_id',
            'company_atasan_penugasan.name as company_name_penugasan_atasan',
        ]);

        $qb->leftJoin('employee_position', 'employee_position.employee_id', 'employee.id');
        $qb->leftJoin('master_position', 'master_position.id', 'employee_position.position_id');
        $qb->leftJoin('organization_hierarchy', 'master_position.org_id', 'organization_hierarchy.id');

        // left join to organization_hierarchy for get unit kerja
        $qb->leftJoin('organization_hierarchy unit_kerja', 'employee_position.unit_kerja_id', 'unit_kerja.id');

        // START JOIN DATA ATASAN

        // left join to employee atasan
        $qb->leftJoin('employee atasan', 'atasan.id', 'employee_position.atasan_id');

        // left join to employee_position atasan
        $qb->leftJoin('employee_position atasan_position', function($join) {
            $join->on('atasan_position.employee_id', 'atasan.id');
            $join->on('atasan_position.position_id', 'employee_position.atasan_position_id');
        });

        //left join to master_position atasan
        $qb->leftJoin('master_position master_position_atasan', 'master_position_atasan.id', 'atasan_position.position_id');

        // left join to organization_hierarchy for get unit kerja atasan
        $qb->leftJoin('organization_hierarchy unit_kerja_atasan', 'atasan_position.unit_kerja_id', 'unit_kerja_atasan.id');

        // left join to master company for atasan
        $qb->leftJoin('master_company company_atasan_asal', 'company_atasan_asal.id', 'atasan_position.company_id_asal');
        $qb->leftJoin('master_company company_atasan_penugasan', 'company_atasan_penugasan.id', 'atasan_position.company_id_penugasan');

        // left join to organization_hierarchy for get unit kerja atasan
        $qb->leftJoin('organization_hierarchy organization_hierarchy_atasan', 'master_position_atasan.org_id', 'organization_hierarchy_atasan.id');

        // END JOIN DATA ATASAN

        $qb->where('employee_position.active', '1');
        $qb->where('master_position.id', $positionId);
        $qb->where('employee_position.npp', $npp);
        $qb->where('atasan_position.active', '1');
        $qb->where(function($query) use ($companyId) {
            $query->where('employee_position.company_id_asal', $companyId);
            $query->orWhere('employee_position.company_id_penugasan', $companyId);
        });
        return $qb->first();
    }
    
    /**
     * getEmployeeWithBawahan
     *
     * @param  mixed $companyId
     * @param  mixed $npp
     * @param  mixed $positionId
     * @return void
     */
    public function getEmployeeWithBawahan(?string $companyId = null, ?string $npp = null, ?string $positionId = null): ?Employee
    {
        $qb = $this->createQueryBuilder();

        $qb->select([
            'employee.id',
            'employee.name as person_name',
            'employee.npp as employee_number',
            'employee_position.position_id',
            'master_position.name as position_name',
            'unit_kerja.id as unit_kerja_id',
            'unit_kerja.name as unit_kerja_name',
            'unit_kerja.type_organization as unit_kerja_type_org',
            'organization_hierarchy.id as organization_id',
            'organization_hierarchy.name as organization_name'
        ]);

        $qb->with([
            'position' => function($query) use ($companyId, $npp, $positionId) {
                $query->where(function($query) use ($companyId) {
                    $query->where('employee_position.company_id_asal', $companyId);
                    $query->orWhere('employee_position.company_id_penugasan', $companyId);
                });
                $query->where('employee_position.position_id', $positionId);
                $query->where('employee_position.active', '1');
            },
            'position.bawahan' => function($query) {

                $query->select([
                    'employee_position.atasan_id',
                    'employee_position.atasan_position_id',
                    'employee_position.id',
                    'employee.name as person_name',
                    'employee.npp as employee_number',
                    'master_position.id as position_id',
                    'master_position.name as position_name',
                    'unit_kerja.id as unit_kerja_id',
                    'unit_kerja.name as unit_kerja_name',
                    'unit_kerja.type_organization as unit_kerja_type_org',
                    'organization_hierarchy.id as organization_id',
                    'organization_hierarchy.name as organization_name',
                ]);

                $query->leftJoin('employee', 'employee.id', 'employee_position.employee_id');
                $query->leftJoin('master_position', 'master_position.id', 'employee_position.position_id');
                $query->leftJoin('organization_hierarchy', 'organization_hierarchy.id', 'master_position.org_id');

                // left join for unit kerja
                $query->leftJoin('organization_hierarchy unit_kerja', 'unit_kerja.id', 'employee_position.unit_kerja_id');

                $query->where('employee_position.active', '1');
            },
        ]);

        $qb->leftJoin('employee_position', 'employee_position.employee_id', 'employee.id');
        $qb->leftJoin('master_position', 'master_position.id', 'employee_position.position_id');
        $qb->leftJoin('organization_hierarchy', 'master_position.org_id', 'organization_hierarchy.id');

        // left join to organization_hierarchy for get unit kerja
        $qb->leftJoin('organization_hierarchy unit_kerja', 'employee_position.unit_kerja_id', 'unit_kerja.id');

        $qb->where('employee_position.active', '1');
        $qb->where('master_position.id', $positionId);
        $qb->where('employee_position.npp', $npp);
        $qb->where(function($query) use ($companyId) {
            $query->where('employee_position.company_id_asal', $companyId);
            $query->orWhere('employee_position.company_id_penugasan', $companyId);
        });

        return $qb->first();
    }

    public function getEmployeeFamily(?string $companyId = null, ?string $npp = null): Employee
    {
        $qb = $this->createQueryBuilder();

        $qb->select([
            'employee.id',
            'employee.name as person_name',
            'employee.npp as employee_number',
        ]);

        $qb->leftJoin('employee_position', 'employee_position.employee_id', 'employee.id');

        $qb->with([
            'family' => function($query) {
                $query->select([
                    'employee_id',
                    'name',
                    'date_of_birth',
                    'place_of_birth',
                    'religion',
                    'gender',
                    'blood_type',
                    'job',
                    'national_identifier',
                    'paspor_no',
                    'attachment_nikah',
                    'attachment_akta',
                    'msk.description as family_status',
                ]);

                $query->join('master_status_keluarga msk', 'msk.id', 'employee_family.family_status');
            }
        ]);

        $qb->where('employee.npp', $npp);
        $qb->where('employee_position.active', '1');
        $qb->where(function($query) use ($companyId) {
            $query->where('employee_position.company_id_asal', $companyId);
            $query->orWhere('employee_position.company_id_penugasan', $companyId);
        });
        $data = $qb->first();
       
        return $qb->first();
    }


    /// -- FOR CREATE API
    public function create(){
        $this->attachKtp();
        $this->attachKK();
        $this->attachKK();
        $this->attachNPWP();
        $this->attachBukuNikah();
        $this->attachBpjsKet();
        $this->attachBpjsKes();
        $this->attachDanaPensiun();
        $this->setJobFunctionId();
        $this->setEmployeeGroupId();
        $this->setEmployeeSubGroupId();
        $this->setProvinceAndCityId();

        request()['EMPLOYEE_STATUS'] = 1;
        if(request()->KD_COMP){
            request()['IS_PUSAT'] = (request()->KD_COMP == 'JSMR') ? 1 : 0;
        }
        $record = Employee::create(request()->all());

        return response([
            'status' => true,
            'message' => 'success'
        ]);
    }

    /// -- FOR UPDATE API
    public function updateData($id){
        $this->attachKtp();
        $this->attachKK();
        $this->attachKK();
        $this->attachNPWP();
        $this->attachBukuNikah();
        $this->attachBpjsKet();
        $this->attachBpjsKes();
        $this->attachDanaPensiun();
        $this->setJobFunctionId();
        $this->setEmployeeGroupId();
        $this->setEmployeeSubGroupId();
        $this->setProvinceAndCityId();
        
        if(request()->KD_COMP){
            request()['IS_PUSAT'] = (request()->KD_COMP == 'JSMR') ? 1 : 0;
        }
        $record = Employee::findOrFail($id)->update(request()->all());

        return response([
            'status' => true,
            'message' => 'success'
        ]);
    }

    /**
     * Store Attachment KTP
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachKtp(){
        $request = request();
        if($request->ATTACH_KTP && is_file($request->ATTACH_KTP)){
          $fileName = md5($request->ATTACH_KTP->getClientOriginalName().''.strtotime('now')).'.'.$request->ATTACH_KTP->getClientOriginalExtension();
          $request->file('ATTACH_KTP')->storeAs('Employee', $fileName, 'public');
          $path = 'Employee/'.$fileName;
          request()['ATTACHMENT_KTP'] = $path;
        }
    }

    /**
     * Store Attachment KK
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachKK(){
        $request = request();
        if($request->ATTACH_KK && is_file($request->ATTACH_KK)){
          $fileName = md5($request->ATTACH_KK->getClientOriginalName().''.strtotime('now')).'.'.$request->ATTACH_KK->getClientOriginalExtension();
          $request->file('ATTACH_KK')->storeAs('Employee', $fileName, 'public');
          $path = 'Employee/'.$fileName;
          request()['ATTACHMENT_KK'] = $path;
        }
    }

    /**
     * Store Attachment NPWP
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachNPWP(){
        $request = request();
        if($request->ATTACH_NPWP && is_file($request->ATTACH_NPWP)){
          $fileName = md5($request->ATTACH_NPWP->getClientOriginalName().''.strtotime('now')).'.'.$request->ATTACH_NPWP->getClientOriginalExtension();
          $request->file('ATTACH_NPWP')->storeAs('Employee', $fileName, 'public');
          $path = 'Employee/'.$fileName;
          request()['ATTACHMENT_NPWP'] = $path;
        }
    }

    /**
     * Store Attachment BukuNikah
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachBukuNikah(){
        $request = request();
        if($request->ATTACH_BUKU_NIKAH && is_file($request->ATTACH_BUKU_NIKAH)){
          $fileName = md5($request->ATTACH_BUKU_NIKAH->getClientOriginalName().''.strtotime('now')).'.'.$request->ATTACH_BUKU_NIKAH->getClientOriginalExtension();
          $request->file('ATTACH_BUKU_NIKAH')->storeAs('Employee', $fileName, 'public');
          $path = 'Employee/'.$fileName;
          request()['ATTACHMENT_BUKU_NIKAH'] = $path;
        }
    }

    /**
     * Store Attachment BpjsKet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachBpjsKet(){
        $request = request();
        if($request->ATTACH_BPJS_KET && is_file($request->ATTACH_BPJS_KET)){
          $fileName = md5($request->ATTACH_BPJS_KET->getClientOriginalName().''.strtotime('now')).'.'.$request->ATTACH_BPJS_KET->getClientOriginalExtension();
          $request->file('ATTACH_BPJS_KET')->storeAs('Employee', $fileName, 'public');
          $path = 'Employee/'.$fileName;
          request()['ATTACHMENT_BPJS_KET'] = $path;
        }
    }

    /**
     * Store Attachment BpjsKes
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachBpjsKes(){
        $request = request();
        if($request->ATTACH_BPJS_KES && is_file($request->ATTACH_BPJS_KES)){
          $fileName = md5($request->ATTACH_BPJS_KES->getClientOriginalName().''.strtotime('now')).'.'.$request->ATTACH_BPJS_KES->getClientOriginalExtension();
          $request->file('ATTACH_BPJS_KES')->storeAs('Employee', $fileName, 'public');
          $path = 'Employee/'.$fileName;
          request()['ATTACHMENT_BPJS_KES'] = $path;
        }
    }

    /**
     * Store Attachment DanaPensiun
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachDanaPensiun(){
        $request = request();
        if($request->ATTACH_DANA_PENSIUN && is_file($request->ATTACH_DANA_PENSIUN)){
          $fileName = md5($request->ATTACH_DANA_PENSIUN->getClientOriginalName().''.strtotime('now')).'.'.$request->ATTACH_DANA_PENSIUN->getClientOriginalExtension();
          $request->file('ATTACH_DANA_PENSIUN')->storeAs('Employee', $fileName, 'public');
          $path = 'Employee/'.$fileName;
          request()['ATTACHMENT_DANA_PENSIUN'] = $path;
        }
    }

    /**
     * Get Data Job Function For Store Data
     *
     * 
     */
    public function setJobFunctionId(){
        $record = MasterJobFunction::where(DB::raw('LOWER(description)'),strtolower(request()->JOB_FUNCTION))->first();
        if(!$record){
            $record = MasterJobFunction::create([
                'DESCRIPTION' => request()->JOB_FUNCTION
            ]);
        }

        request()['JOB_FUNCTION_ID'] = $record->id;
    }

    /**
     * Get Data Employee Group For Store Data
     *
     * 
     */
    public function setEmployeeGroupId(){
        $record = MasterEmployeeGroup::where(DB::raw('LOWER(description)'),strtolower(request()->EMPLOYEE_GROUP))->first();
        if(!$record){
            $record = MasterEmployeeGroup::create([
                'DESCRIPTION' => request()->EMPLOYEE_GROUP
            ]);
        }

        request()['EMPLOYEE_GROUP_ID'] = $record->id;
    }

    /**
     * Get Data Employee Sub Group For Store Data
     *
     * 
     */
    public function setEmployeeSubGroupId(){
        $record = MasterEmployeesSubGroup::where(DB::raw('LOWER(subgroup)'),strtolower(request()->EMPLOYEE_SUBGROUP))->first();

        // if(!$record){
        //     $record = MasterEmployeesSubGroup::create([
        //         'subgroup' => request()->EMPLOYEE_SUBGROUP
        //     ]);
        // }

        request()['EMPLOYEE_SUBGROUP'] = ($record) ? $record->key : null;
    }

    /**
     * Get Data Employee Unit Kerja For Store Data
     *
     * 
     */
    public function setUnitKerjaId(){
        $record = OrganizationHierarchy::where(DB::raw('LOWER(NAME)'),strtolower(request()->UNIT_KERJA))->first();

        // if(!$record){
        //     $record = OrganizationHierarchy::create([
        //         'DESCRIPTION' => request()->UNIT_KERJA
        //     ]);
        // }

        request()['UNIT_ID'] = ($record) ? $record->id : null;
    }

    /**
     * Get Data Unit For Store Data
     *
     * 
     */
    public function setProvinceAndCityId(){
        $province = MasterProvince::where(DB::raw('LOWER(description)'),strtolower(request()->PROVINCE_KTP))->first();

        if(!$province){
            $province = MasterProvince::create([
                'DESCRIPTION' => request()->PROVINCE_KTP
            ]);
        }

        $record = MasterCity::where(DB::raw('LOWER(description)'),strtolower(request()->CITY_KTP))->first();

        if(!$record){
            $record = MasterCity::create([
                'PROVINCEID' => $province->id,
                'DESCRIPTION' => request()->CITY_KTP
            ]);
        }

        request()['PROVINCEID_KTP'] = $province->id;
        request()['CITYID_KTP'] = $record->id;
    }
    
    public function getEmployeeByGradeCluster($params): ?Collection
    {
        
        $qb = $this->createQueryBuilder();
        
        $qb->select([
            'employee.id',
            'employee.name as person_name',
            'employee.npp as employee_number',
            'employee.email',
            'employee.place_of_birth',
			'employee.date_of_birth',
            'employee.telephone_no',
            'employee.is_penugasan',
        ]);

        $qb->with([
            'position' => function($query) use ($params) {
                $query->select([
                    'employee_id',
                    'employee_position.position_id',
                    'master_position.name as position_name',
                    'employee_position.grade',
                    'employee_position.sub_grade as subgrade',
                    'master_layer.description as layer',
                    'master_job.name as job_name',
                    'employee_position.kelompok_jabatan as job_type',
                    'organization_hierarchy.id as org_id',
                    'organization_hierarchy.name as org_name',
                    'employee_position.unit_kerja_id',
                    'unit_kerja.name as unit_kerja',
                    'unit_kerja.type_organization as unit_kerja_type_org',
                    'master_employee_group.description as employee_group',
                    'company_asal.code as kd_comp_asal',
                    'company_penugasan.code as kd_comp_penugasan',
                    'master_subcluster.cluster_kode',
                    'master_subcluster.kode as subcluster_code',
                    'master_subcluster.name as subcluster_name',
                    'master_subcluster.fungsi as subcluster_fungsi'
                ]);

                $query->leftJoin('master_position', 'employee_position.position_id', 'master_position.id');
                $query->leftJoin('organization_hierarchy', 'master_position.org_id', 'organization_hierarchy.id');
                $query->leftJoin('master_position', 'employee_position.position_id', 'master_position.id');
                $query->leftJoin('master_layer', 'employee_position.layer_id', 'master_layer.id');
                $query->leftJoin('master_job', 'employee_position.job_id', 'master_job.id');
                $query->leftJoin('master_employee_group', 'employee_position.employee_group_id', 'master_employee_group.id');

                // left join to organization_hierarchy for get unit kerja
                $query->leftJoin('organization_hierarchy unit_kerja', 'employee_position.unit_kerja_id', 'unit_kerja.id');

                // join to company asal
                $query->leftJoin('master_company company_asal', 'employee_position.company_id_asal', 'company_asal.id');

                // join to company penugasan
                $query->leftJoin('master_company company_penugasan', 'employee_position.company_id_penugasan', 'company_penugasan.id');

                $query->leftJoin('map_position_subcluster', function($join){
                    $join->on('map_position_subcluster.position_name', '=', 'employee_position.position');
                    $join->on('map_position_subcluster.unit_kerja_id', '=', 'employee_position.unit_kerja_id');
                });
                $query->leftJoin('master_subcluster', 'master_subcluster.id', 'map_position_subcluster.master_subcluster_id');
        


                $query->where('employee_position.active', '1');
               
                if ($params->Grade!=null) {
                    $grade_id = array_map(null, explode(',', $params->Grade));
                    //dd($grade_id);
                    $query-> WhereIn('employee_position.grade',$grade_id );

                }
                
                // all employee
                // if($companyId != null)
                // {
                //     $query->where(function($q) use ($companyId) {
                //         $q->where('company_id_asal', $companyId);
                //         $q->orWhere('company_id_penugasan', $companyId);
                //     });
                // }
                    
                if ($params->ClusterCode!=null) {
                    $query->where('master_subcluster.cluster_kode', $params->ClusterCode);
                }
            }
        ]);

        $qb->join('employee_position', 'employee_position.employee_id', 'employee.id');
        if ($params->CompanyCode!=null)  {
            $qb->where('employee.kd_comp', $params->CompanyCode);
        }
        
        // all employee
        // if($companyId != null)
        // {
        //     $qb->where(function($query) use ($companyId) {
        //         $query->where('company_id_asal', $companyId);
        //         $query->orWhere('company_id_penugasan', $companyId);
        //     });
        // }

        $qb->where('employee.employee_status', '1');
        $qb->where('employee_position.active', '1');

        $qb->groupBy(
            'employee.id',
            'employee.name',
            'employee.npp',
            'employee.email',
            'employee.telephone_no',
            'employee.place_of_birth',
			'employee.date_of_birth',
            'employee.is_penugasan'
        );
        // $queries = DB::getQueryLog();
        // print_r($queries);
        return $qb->get();
    }
}
