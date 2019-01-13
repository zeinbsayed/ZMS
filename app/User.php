<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Fish\Logger\Logger;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
	use Logger;
	use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password','role_id'
    ];
	
	protected $dates = ['deleted_at'];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];
	
	public function setEmailAttribute($value){
	
		$this->attributes['email']=$value==""?null:$value;
	}
	
	public function entrypoints(){
	
		return $this->belongsToMany('App\Entrypoint')->withTimestamps();
	}
	public function visits(){
	
		return $this->hasMany('App\Visit');
	}
	public function role(){
	
		return $this->belongsTo('App\Role');
	}
	public function has_visits(){
		
		return $this->hasMany('App\Visit');
	}
	public function medicalunits(){
	
		return $this->belongsToMany('App\MedicalUnit','user_id')->withTimestamps();
	}
	public function exit_visits(){
	
		return $this->hasMany('App\Visit');
	}
}
