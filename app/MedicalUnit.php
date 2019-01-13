<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
class MedicalUnit extends Model
{
    //
	use Logger;
	protected $table = 'medical_units';
	protected $fillable = [
     	'name',
		'type'
	];
	public function visits(){
	
		return $this->belongsToMany('App\Visit')->withPivot('room_id','reference_doctor_id','user_id','convert_to', 'department_conversion','conversion_date')->withTimestamps();
	}
	public function users(){
	
		return $this->belongsToMany('App\User')->withPivot('user_id')->withTimestamps();
	}
	
	public function rooms(){
	
		return $this->hasMany('App\Room');
	}
}
