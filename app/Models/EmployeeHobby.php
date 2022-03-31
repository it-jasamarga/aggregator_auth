<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Filters\Filterable;

class EmployeeHobby extends Model
{
    use Filterable;
    protected $table = 'employee_hobby';
    protected $primaryKey = 'id';
    protected $fillable = [
        'ID',
        'EMPLOYEE_ID',
        'HOBBY',
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
