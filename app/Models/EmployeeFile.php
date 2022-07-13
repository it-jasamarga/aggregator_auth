<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeFile extends Model
{
    protected $table = 'EMPLOYEE_FILE';
    protected $fillable = [
        'ID',
        'EMPLOYEE_ID',
        'NPP',
        'KD_COMP',
        'URL',
        'ACTIVE',
        'TYPE',
        'CREATED_AT',
        'UPDATED_AT',
        'CREATED_BY',
        'UPDATED_BY',
    ];

    public function getUrlAttribute(){
        return (\Storage::disk('minio')->exists($this->attributes['url'])) ? \Storage::disk('minio')->url($this->attributes['url']) : null;
    }
}
