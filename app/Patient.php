<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;

class Patient extends Model
{
    //
	use Logger;
	protected $table = 'patients';
	protected $fillable = [
		'id',
        'name',
		'gender',
        'sin',
		'address',
		'birthdate',
		'social_status',
		'phone_num',
		'nationality',
		'job',
		'new_id',
		'patient_government_id',
		'hasOpenVisits',
    ];
	public function visits(){
	
		return $this->hasMany('App\Visit');
	}
	public function government(){
		return $this->belongsTo('App\Government','patient_government_id');
	}
	public function scopeNumberOfPatientsToday($query){
		
		return $query->whereDate('created_at','=',date('Y-m-d',time()))->get();
		
	}
	
}
