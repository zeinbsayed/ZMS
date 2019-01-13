<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    //
	protected $fillable=[
		'name'
	];
	
	public function visit_contract(){
		
		return $this->hasMany('App\Visit');
	}
}
