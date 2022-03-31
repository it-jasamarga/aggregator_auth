<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Filters\Filterable;

class SantunanDuka extends Model
{
    use Filterable;
    protected $primaryKey = 'id';
    protected $table = 'SANTUNAN_DUKA';
    protected $fillable = [
        'ID',
        'EMPLOYEE_ID',
        'STATUS',
        'KEYDATE',
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
