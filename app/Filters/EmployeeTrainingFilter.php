<?php
namespace App\Filters;

use App\Models\File;
use Illuminate\Http\Request;
// use Carbon\Carbon;

class EmployeeTrainingFilter extends QueryFilters
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function npp($term) {
        return $this->builder->whereHas('employee', function($q){
            $q->where('npp',"$term");
        });
    }

    public function kd_comp($term) {
        return $this->builder->whereHas('employee', function($q){
            $q->where('kd_comp', "$term");
        });
    }

    public function employee_id($term) {
        return $this->builder->where('employee_id', $term);
    }

    public function pelatihan($term) {
        return $this->builder->where('pelatihan', 'LIKE', "%$term%");
    }

    public function pelaksanaan($term) {
        return $this->builder->where('pelaksanaan', 'LIKE', "%$term%");
    }

    public function tahun($term) {
        return $this->builder->where('tahun', "%$term%");
    }

    public function sort($array) {
        $myArray = explode(',', $array);
        foreach ($myArray as $value) {
          $this->builder->orderBy($value,'desc');
        }
    }


  public function sort_date($type = null) {
    return $this->builder->orderBy('created_at', (!$type || $type == 'asc') ? 'desc' : 'desc');
}

public function sort_title($type = null) {
    return $this->builder->orderBy('title', (!$type || $type == 'asc') ? 'asc' : 'desc');
}
}
