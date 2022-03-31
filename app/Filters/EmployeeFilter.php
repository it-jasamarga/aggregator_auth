<?php
namespace App\Filters;

use App\Models\File;
use Illuminate\Http\Request;
// use Carbon\Carbon;

class EmployeeFilter extends QueryFilters
{
    protected $request;
    public function __construct(Request $request)
    {
        $this->request = $request;
        parent::__construct($request);
    }

    public function npp($term) {
        return $this->builder->where('npp', $term);
    }

    public function kd_comp($term) {
        return $this->builder->where('kd_comp', $term);
    }

    public function personnel_number($term) {
        return $this->builder->where('personnel_number', $term);
    }

    public function name($term) {
        return $this->builder->where('name', 'LIKE', "%$term%");
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
