<?php

namespace App\Repositories;

use App\Models\EmployeePosition;

class EmployeePositionRepository extends Repository
{    
    /**
     * getClassName
     *
     * @return string
     */
    public function getClassName(): string
    {
        return EmployeePosition::class;
    }

    public function getPositionBy(Type $var = null)
    {
        # code...
    }
}
