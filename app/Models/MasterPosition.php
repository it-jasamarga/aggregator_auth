<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MasterPosition extends Model
{
	protected $table = 'master_position';

	public function masterOrganization(){
        return $this->belongsTo(OrganizationHierarchy::class,'org_id','id');
	}
}
