<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Filters\Filterable;

class EmployeeEducation extends Model
{
    use Filterable;
    protected $table = 'employee_education';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ID',
        'EMPLOYEE_ID',
        'REF_JENJANG_PENDIDIKAN_ID',
        'REF_JURUSAN_PENDIDIKAN_ID',
        'NAME',
        'ADDRESS',
        'COUNTRY_ID',
        'START_DATE',
        'GRADUATE_DATE',
        'TITLE',
        'NO_IJAZAH',
        'TANGGAL_IJAZAH',
        'ATTACHMENT_IJAZAH',
        'FINAL_SCORE',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
    ];
    /**
     * employee
     *
     * @return BelongsTo
     */
    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'employee_id');
    }
    
    /**
     * jenjang
     *
     * @return BelongsTo
     */
    public function jenjang(): BelongsTo
    {
        return $this->belongsTo(MasterJenjangPendidikan::class, 'ref_jenjang_pendidikan_id');
    }

    public function jurusan(): BelongsTo
    {
        return $this->belongsTo(MasterJurusanPendidikan::class, 'ref_jurusan_pendidikan_id');
    }

    public function getAttachmentIjazahAttribute(){
        return url('storage/'.$this->attributes['attachment_ijazah']);
    }

}
