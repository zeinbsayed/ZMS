<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExistStatus extends Model
{
    //
	
	public function visit_exit_status(){
		
		return $this->hasMany('App\Visit');
	}
}
