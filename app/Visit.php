<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Fish\Logger\Logger;
use Carbon\Carbon;

class Visit extends Model
{
	use Logger;
    protected $table = 'visits';
		protected $fillable = [
			'patient_id',
			'serial_number',
			'ticket_number',
			'ticket_type',
			'companion_name',
			'companion_sid',
			'companion_address',
			'companion_job',
			'companion_phone_num',
			'person_relation_id',
			'person_relation_name',
			'person_relation_phone_num',
			'person_relation_address',
			'converted_from',
			'checkup',
			'entry_reason_desc',
			'entry_time',
			'file_number',
			'file_type',
			'cure_type_id',
			'exit_status_id',
			'contract_id',
			//'reference_doctor_id',
			'exit_date',
			'entry_id',
			'user_id',
			'exit_user_id',
			'entry_date',
			'reg_time',
			'watching_status',
			'ticket_status',
			'sent_by_person',
			'ticket_companion_name',
			'ticket_companion_sin',
			'patient_new_id',
			'ambulance_number',
			'paramedic_name',
			'kateb_name',
			'ticket_number',
			'doctor_name',
			'Companion_Ticket_Number',
			'reception_conversion_date',
			'out_patient',
		];

	public function patient(){

		return $this->belongsTo('App\Patient');
	}
	public function relation(){

		return $this->belongsTo('App\Relation');
	}
	public function diagnoses(){

		return $this->hasMany('App\VisitDiagnose');
	}
	public function complaints(){

		return $this->hasMany('App\VisitComplaint');
	}
	public function medicines(){

		return $this->hasMany('App\VisitMedicine');
	}
	public function scopeNumberOfVisitsToday($query){

		return $query->whereDate('created_at','=',date('Y-m-d',time()))
					 ->where('cancelled',false)
					 ->where('ticket_number','!=',0)->get();

	}
	public function scopeNumberOfInpatients($query){

		return $query->where('cancelled',false)
					 ->where('closed',false)
					 ->whereNotNull('entry_date')->count();

	}
	/* Get number of outpatients were reserved from clinic reservation office */
	public function scopeNumberOfOutpatientsFromClinic($query){
		return $query->where('cancelled',false)
					 ->whereDate('created_at','=',Carbon::today()->format('Y-m-d'))
					 ->whereNull('entry_date')
					 ->whereNull('ticket_type')->get();
	}
	/* Get number of outpatients were reserved from desk reservation office */
	public function scopeNumberOfOutpatientsFromDesk($query){
		return $query->where('cancelled',false)
					 ->whereDate('created_at','=',Carbon::today()->format('Y-m-d'))
					 ->whereNull('entry_date')
					 ->whereNotNull('ticket_type')->get();
	}
	public function entrypoint(){
		
		return $this->belongsTo('App\Entrypoint','entry_id');
	}
	public function user(){
		
		return $this->belongsTo('App\User');
	}
	public function exit_user(){
		
		return $this->belongsTo('App\User','exit_user_id');
	}
/*	public function reference_doctor(){
		
		return $this->belongsTo('App\User','reference_doctor_id');
	}*/
	public function exit_status(){
		
		return $this->belongsTo('App\ExistStatus','exit_status_id');
	}
	public function contract(){
		
		return $this->belongsTo('App\Contract');
	}
	public function medicalunits(){

		return $this->belongsToMany('App\MedicalUnit')->withPivot('room_id','reference_doctor_id','user_id','convert_to', 'department_conversion','conversion_date')->withTimestamps();
	}
	public function orders(){

		return $this->hasMany('App\MedicalOrderItem');
	}
	public function cure_type(){
		return $this->belongsTo('App\CureType');
	}
	public function file_type_relation(){
		return $this->belongsTo('App\FileType','file_type');
	}
	public function converted_from_relation(){
		return $this->belongsTo('App\ConvertedFrom','converted_from');
	}
}
