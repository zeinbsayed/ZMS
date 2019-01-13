<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Government extends Model
{
    //
	protected $table = 'government';
	protected $primaryKey = 'government_id';
	protected $fillable = [
        'name',
	];
		
	public function patients(){
	
		return $this->hasMany('App\Patient');
	}
}
