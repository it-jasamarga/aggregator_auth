<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MapPositionSubcluster extends Model
{
	protected $table = 'map_position_subcluster';

	public function masterSubcluster(){
		return $this->belongsTo(MasterSubCluster::class,'master_subcluster_id');
	}
}
