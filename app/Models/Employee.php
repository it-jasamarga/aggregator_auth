<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Filters\Filterable;

class Employee extends Model
{
    use Filterable;
    protected $table = 'employee';
    protected $primaryKey = 'id';
    protected $fillable = [
        'NAME',
        'IS_PUSAT',
        'NATIONAL_IDENTIFIER',
        'PLACE_OF_BIRTH',
        'DATE_OF_BIRTH',
        'GENDER',
        'NPP',
        'NEW_NPP',
        'ADDRESS_KTP',
        'CITYID_KTP',
        'PROVINCEID_KTP',
        'ADDRESS_DOMICILE',
        'CITYID_DOMICILE',
        'PROVINCEID_DOMICILE',
        'FRONT_TITLE_EDUCATION',
        'END_TITLE_EDUCATION',
        'RELIGION',
        'MARITAL_STATUS',
        'NPWP',
        'STATUS_NPWP',
        'BLOOD_TYPE',
        'HEIGHT',
        'WEIGHT',
        'TELEPHONE_NO',
        'EMAIL',
        'BPJS_KES_NO',
        'BPJS_KET_NO',
        'DATE_OF_ENTRY',
        'EMPLOYEE_GROUP_ID',
        'IS_RANGKAP_JABATAN',
        'IS_PENUGASAN',
        'SK_NO',
        'SK_DATE',
        'SK_EFFECTIVE_DATE',
        'SK_PHK_NO',
        'SK_PHK_DATE',
        'SK_PHK_OUT_DATE',
        'REF_PHK_ID',
        'KET_PHK',
        'URL_IMAGE',
        'JOB_FUNCTION_ID',
        'EMPLOYEE_STATUS',
        'PASPOR_NO',
        'UNIT_ID',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
        'PERSONNEL_NUMBER',
        'KD_COMP',
        'FACEBOOK',
        'INSTAGRAM',
        'TWITTER',
        'EMPLOYEE_SUBGROUP',
        'ATTACHMENT_KK',
        'ATTACHMENT_KTP',
        'ATTACHMENT_NPWP',
        'ATTACHMENT_BUKU_NIKAH',
        'ATTACHMENT_BPJS_KET',
        'ATTACHMENT_BPJS_KES',
        'ATTACHMENT_DANA_PENSIUN',
        'SUMMARY',
        'INTEREST',
        'NO_DANA_PENSIUN'

    ];
    /**
     * position
     *
     * @return HasMany
     */
    public function position(): HasMany
    {
        return $this->hasMany(EmployeePosition::class, 'employee_id');
    }

    /**
     * position
     *
     * @return HasMany
     */
    public function positionActive(): hasOne
    {
        return $this->hasOne(EmployeePosition::class, 'employee_id');
    }
    
    /**
     * latestEducation
     *
     * @return HasOne
     */
    public function latestEducation(): HasOne
    {
        return $this->hasOne(EmployeeEducation::class, 'employee_id')->latest('start_date');
    }

    /**
     * Education
     *
     * @return HasOne
     */
    public function allEducation(): hasMany
    {
        return $this->hasMany(EmployeeEducation::class, 'employee_id')->orderBy('ref_jenjang_pendidikan_id');
    }

    /**
     * Hobby
     *
     * @return HasOne
     */
    public function allHobby(): hasMany
    {
        return $this->hasMany(EmployeeHobby::class, 'employee_id')->orderByDesc('created_at');
    }

    /**
     * Training
     *
     * @return HasOne
     */
    public function allTraining(): hasMany
    {
        return $this->hasMany(EmployeeTraining::class, 'employee_id')->orderBy('tgl_awal');
    }

    /**
     * cityKtp
     *
     * @return BelongsTo
     */
    public function cityKtp(): BelongsTo
    {
        return $this->belongsTo(MasterCity::class, 'cityid_ktp');
    }
    
    /**
     * provinceKtp
     *
     * @return BelongsTo
     */
    public function provinceKtp(): BelongsTo
    {
        return $this->belongsTo(MasterProvince::class, 'provinceid_ktp');
    }
    
    /**
     * cityKtp
     *
     * @return void
     */
    public function cityDomicile()
    {
        return $this->belongsTo(MasterCity::class, 'cityid_domicile');
    }
    
    /**
     * provinceKtp
     *
     * @return BelongsTo
     */
    public function provinceDomicile(): BelongsTo
    {
        return $this->belongsTo(MasterProvince::class, 'provinceid_domicile');
    }
    
    /**
     * Get all of the family for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function family(): HasMany
    {
        return $this->hasMany(EmployeeFamily::class, 'employee_id')->orderByDesc('date_of_birth');
    }


    /**
     * Get all of the family for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function self(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'id','id');
    }
     
    public function empGroup(): BelongsTo
    {
        return $this->belongsTo(MasterEmployeeGroup::class, 'employee_group_id');
    }

    /**
     * Get all of the family for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empSubGroup(): BelongsTo
    {
        return $this->belongsTo(MasterEmployeesSubGroup::class, 'employee_subgroup','key');
    }

    /**
     * Get all of the payslip for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function empPayslip(): HasMany
    {
        return $this->hasMany(Payslip::class, 'employee_id');
    }

    /**
     * Get all of the Santunan Duka for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function santunanDuka(): HasMany
    {
        return $this->hasMany(SantunanDuka::class, 'employee_id');
    }

    /**
     * Relation Unit Kerja for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function unitKerja(): BelongsTo
    {
        return $this->belongsTo(OrganizationHierarchy::class, 'unit_id','ID');
    }

    /**
     * Url Image Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    // public function setUrlImageAttribute()
    // {
    //     return 'https://jmclick.jasamarga.co.id/jmstars_api'.$this->attribute['url_image'];
    // }

     /**
     * Get all of the Grade for the Employee
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function allGrade(): HasMany
    {
        return $this->hasMany(EmployeeGrade::class, 'employee_id');
    }
}
