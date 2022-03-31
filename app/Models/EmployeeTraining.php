<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Filters\Filterable;

class EmployeeTraining extends Model
{
    use Filterable;
    protected $table = 'employee_traning';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ID',
        'EMPLOYEE_ID',
        'TAHUN',
        'PELATIHAN',
        'PELAKSANAAN',
        'TGL_AWAL',
        'TGL_AKHIR',
        'HARI',
        'TEMPAT',
        'KOTA',
        'INISIATOR',
        'NO_PENUGASAN',
        'KLP_PLTH1',
        'KLP_PLTH2',
        'NEGARA',
        'NPP',
        'NAMA',
        'GOL',
        'JABATAN',
        'UNITKERJA',
        'NOSERTIFIKAT',
        'NILAI',
        'PERINGKAT',
        'KDCOMP',
        'BIAYA',
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
    
}
