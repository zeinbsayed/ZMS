<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ConvertedFrom extends Model
{
    //
	
	public function visits(){
		return $this->belongsTo('App\Visit');
	}
}
