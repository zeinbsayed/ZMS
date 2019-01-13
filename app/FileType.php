<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class FileType extends Model
{
    //
	
	public function visits(){
		return $this->belongsTo('App\Visit');
	}
}
