<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    //
	
	
	public function department(){
		return $this->belongsTo('App\MedicalUnit');
	}
	
}
