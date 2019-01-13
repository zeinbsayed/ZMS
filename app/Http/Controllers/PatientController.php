<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Patient;
use App\Relation;
use App\MedicalUnit;
use App\Visit;
use App\User;
use App\Entrypoint;
use App\CureType;
use App\FileType;
use App\Contract;
use App\ExistStatus;
use App\ConvertedFrom;
use App\Room;
use App\government;
use Auth;
use DB;
use Validator;
use Session;
use Carbon\Carbon;
//use App\Http\Controllers\Controller;

class PatientController extends Controller
{
    // index action
	public function index(){
		// Restore session after it expired and user return to log in
		$user=User::find(Auth::id());
		$role_id=$user->role->id;
		$role_name=$user->role->name;
		$sub_type_entrypoint=$user->entrypoints()->first()->sub_type;
		//dd($sub_type_entrypoint);
		if($role_name=="Private")
		{
			$medical_units=MedicalUnit::where('type','d')->where('free',0)->whereNull('parent_department_id')->lists('name', 'id');
			$first_medical_unit= MedicalUnit::where('type','d')->where('free',0)->whereNull('parent_department_id')->first();
		}
		else{
			$medical_units=MedicalUnit::where('type','d')->where('free',1)->whereNull('parent_department_id')->lists('name', 'id');
			$first_medical_unit= MedicalUnit::where('type','d')->where('free',1)->whereNull('parent_department_id')->first();
		}
		
		$first_department_doctors=$first_medical_unit->users()->lists('users.name','users.id');
		$first_rooms=$first_medical_unit->rooms()->lists('rooms.name','rooms.id');
		
		$cure_types=CureType::lists('name','id');
		$file_types=FileType::lists('name','id');
		$contracts=Contract::lists('name','id');
		$converted_from=ConvertedFrom::lists('name','id');
		$governments=government::lists('name','government_id');
		
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		//dd($entrypoints);
		$entrypoint_Array=array();
		foreach($user->entrypoints as $row)
			$entrypoint_Array[$row->id]=$row->name;
		$relations= Relation::lists('name','id');
		//dd($entrypoint_Array);
		return view('patient',array('p_active'=>'active','relations'=>$relations,'medical_units'=>$medical_units,'entrypoints'=>$entrypoint_Array,'cure_types'=>$cure_types,'file_types'=>$file_types,
		'contracts'=>$contracts,'converted_from'=>$converted_from,
		'first_department_doctors'=>$first_department_doctors,'governments'=>$governments,'role_id'=>$role_id,'role_name'=>$role_name));
	}
	// index for receiption action
	public function indexTicket($pid,Request $request){
		if($pid!="")
		{
			$patient_data=Patient::find($pid);
		}
		$relations=Relation::lists('name', 'id');
		$medical_units=MedicalUnit::where('type','=','c')->lists('name', 'id');
		$user=User::find(Auth::id());
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		$entrypoint_Array=array();

		$ages=array();
		$days = array();
		$ages[""]=0;
		$days[""]=0;
		for($i=1;$i<=11;$i++)
			$ages[$i]=$i;
		for($i=1;$i<=29;$i++)
			$days[$i]=$i;
		foreach($user->entrypoints as $row)
			$entrypoint_Array[$row->id]=$row->name;
		if($pid!="")
			return view('reserve_ticket',array('r_active'=>'active','relations'=>$relations,'ages'=>$ages,'days'=>$days,'medical_units'=>$medical_units,'entrypoints'=>$entrypoint_Array,'patient_data'=>$patient_data));
		else
			return view('reserve_ticket',array('r_active'=>'active','relations'=>$relations,'ages'=>$ages,'days'=>$days,'medical_units'=>$medical_units,'entrypoints'=>$entrypoint_Array));
	}
	// index for receiption action
	public function indexDesk($pid,Request $request){
		if($pid!="")
		{
			$patient_data=Patient::find($pid);
		}
		$relations=Relation::lists('name', 'id');
		$cure_types=CureType::lists('name','id');
		$file_types=FileType::lists('name','id');
		$medical_units=MedicalUnit::where('type','c')->lists('name', 'id');
		$first_medical_unit= MedicalUnit::where('type','c')->first();
		$first_clinic_doctors=$first_medical_unit->users()->lists('users.name','users.id');
		$entrypoint_users=User::where('role_id',4)->lists('name','id');
		$user=User::find(Auth::id());
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		$entrypoints=array();

		$ages=array();
		$days = array();
		$ages[""]=0;
		$days[""]=0;
		for($i=1;$i<=11;$i++)
			$ages[$i]=$i;
		for($i=1;$i<=29;$i++)
			$days[$i]=$i;
		foreach($user->entrypoints as $row)
			$entrypoints[$row->id]=$row->name;
		$desk_active="active";
		if($pid!="" && $pid!="-1")
			return view('desk_ticket',compact('desk_active','relations','ages','days','medical_units','first_clinic_doctors','entrypoints','entrypoint_users','file_types','cure_types','patient_data'));
		else
			return view('desk_ticket',compact('desk_active','relations','ages','days','medical_units','first_clinic_doctors','entrypoints','entrypoint_users','file_types','cure_types'));
	}
	public function showid($id){
		return view('patient',array('p_active'=>'active','id'=>$id));
	}
	public function printdata($id,$vid){
	
		$user=User::find(Auth::id());
		$user_id=Auth::id();
		$role_name=$user->role->name;
		$role_id=$user->role->id;
	//	if($role_name!="GeneralRecept" || $role_name=="Injuires" )
	//	{
		$patient_visit=Patient::join('visits','visits.patient_id','=','patients.id')
					->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
					->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
					->join('converted_froms','converted_froms.id','=','visits.converted_from')
					->leftJoin('rooms','rooms.id','=','medical_unit_visit.room_id')
					->leftJoin('relations','relations.id','=','visits.person_relation_id')
					->leftJoin('users','users.id','=','medical_unit_visit.reference_doctor_id')
					->where('patients.id','=',$id)
					->where('visits.id','=',$vid)
					//->whereNull('medical_unit_visit.convert_to')
					->select('patients.id as pid','patients.name as pname','gender','birthdate','patients.job as pjob','patients.address as paddress','patients.phone_num as ppnumber','social_status','patients.sin as psid','new_id','relations.name as rel_name'
					,'companion_name as c_name','visits.companion_address as rel_address', 'ticket_number','entry_time',
					'visits.companion_job as rel_job','entry_reason_desc','visits.companion_phone_num as rel_phone','users.name as reference_doctor_name'
					,'visits.companion_sid as rel_sid','entry_date','rooms.name as room_name','medical_units.name as dep_name','patient_new_id','converted_froms.name as converted_from_name','visits.doctor_name as doctor_name',
					'visits.ticket_number as vticket_num','medical_unit_visit.medical_unit_id as med_id')
					->get();
					$entr_role=Visit::join('users','users.id','=','visits.user_id')
						->join('roles','roles.id','=','users.role_id')
						->where('visits.id','=',$vid)
						->select('roles.name as enter_role_name')
						->get();
						
		//dd($entr_role);
		//}
/*		else
		{
		$patient_visit=Patient::join('visits','visits.patient_id','=','patients.id')
					->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
					->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
					->join('converted_froms','converted_froms.id','=','visits.converted_from')
					->leftJoin('rooms','rooms.id','=','medical_unit_visit.room_id')
					->leftJoin('relations','relations.id','=','visits.person_relation_id')
					->leftJoin('users','users.id','=','medical_unit_visit.reference_doctor_id')
					->where('patients.id','=',$id)
					->where('visits.id','=',$vid)
					//->whereNull('medical_unit_visit.convert_to')
					->select('patients.id as pid','patients.name as pname','gender','birthdate','patients.job as pjob','patients.address as paddress','patients.phone_num as ppnumber','social_status','patients.sin as psid','new_id','relations.name as rel_name'
					,'companion_name as c_name','visits.companion_address as rel_address', 'ticket_number','entry_time',
					'visits.companion_job as rel_job','entry_reason_desc','visits.companion_phone_num as rel_phone','users.name as reference_doctor_name'
					,'visits.companion_sid as rel_sid','entry_date','rooms.name as room_name','medical_units.name as dep_name','patient_new_id','converted_froms.name as converted_from_name','visits.doctor_name as doctor_name',
					'visits.ticket_number as vticket_num','medical_unit_visit.medical_unit_id as med_id')
					->get();
					dd($patient_visit);
			*/		
						
			/*$patient_visit= Visit::with(array('medicalunits.rooms','contract','exit_status','cure_type','patient','medicalunits'=>function($query){
							$query->orderBy('pivot_created_at', 'desc');
						}))
						/*->whereHas('medicalunits',function($query) use($medical_type){
							$query->where('type',$medical_type);
						})*/
						/*->whereHas('user',function($query) use($role_id){
								$query->where('role_id',$role_id);
						})
						->where('cancelled',false)*/
		//}

		$default_view="report_entry";
		/*if($role_name=="GeneralRecept")
		{
		return view($default_view,array('data'=>$patient_visit,'medical_visit'=>$medical_visit,'role_name'=>$role_name));
		}
		else*/
		{
		return view($default_view,array('data'=>$patient_visit,'role_name'=>$role_name,'entr_role_name'=>$entr_role));
		}
	}
	public function printcarddata($id){
		$patients= Patient::find($id);
		return view('card',array('data'=>$patients));
	}
	public function print_ticketin($id){
		$visit=Visit::find($id);
		$created_at=$visit->created_at->format('Y/m/d');
		$patient_data=$visit->patient()
												->select('name','birthdate','address')
												->get();

		$medical_unit= $visit->medicalunits()
												 ->select('name')
												 ->get();

		return view('reports.patient_ticket',compact('patient_data','medical_unit','created_at'));
	}
	public function printhistorydata($id){
		$table_header="السجل المرضي";
		$patient=Patient::find($id);
		if(is_null($patient))
			return abort(404);
		$data= 	DB::table('visits')
				->select(DB::raw('visits.created_at,
				 group_concat(( select concat( case when type="c" then "عيادة " when type="d" then "قسم " end,name)  from medical_units
				where medical_units.id=medical_unit_visit.medical_unit_id
				)) as medical_unit,
				( select GROUP_CONCAT(content," ",IFNULL(content_in_english,"")) as content from visit_diagnoses  where visit_diagnoses.visit_id=visits.id
				) as v_dia
				,
				( select GROUP_CONCAT(content) as content from visit_complaints  where visit_complaints.visit_id=visits.id
				) as v_com
				,
				( select GROUP_CONCAT(cure_description) as content from visit_diagnoses  where visit_diagnoses.visit_id=visits.id
				) as v_cure
				,
				( select GROUP_CONCAT(name) as name from visit_medicines  where visit_medicines.visit_id=visits.id
				) as v_med
				,
				( select GROUP_CONCAT(accessories) as name from visit_medicines  where visit_medicines.visit_id=visits.id
				) as v_access_clinic
				,
				( select GROUP_CONCAT(accessories) as name from visit_diagnoses  where visit_diagnoses.visit_id=visits.id
				) as v_access_dep
				'))
				->join('medical_unit_visit','visits.id','=','medical_unit_visit.visit_id')
				->where('visits.patient_id', $id)
				->where('visits.cancelled',false)
				->groupBy('visits.id')
				->orderBy('visits.created_at','desc')
				->get();
	/**
	 Select xray in sql
		( select GROUP_CONCAT((select procedures.name from procedures where procedures.id=medical_order_items.proc_id)) as content from medical_order_items  where medical_order_items.visit_id=visits.id
		) as v_rad
	*/
	//	dd($data);
		return view('reports.patient_history',compact('data','patient','table_header'));
	}
	public function store(Request $request)
	{
		$input=$request->all();
		$user=User::find(Auth::id());
		$role_id=$user->role->id;
		$role_name=$user->role->name;
		//dd($input);
		// check if a patient is already exist in a clinic.
		if($input['patient_id'] != "" && $this->checkIfPatientIsExistTodayInClinic($input['patient_id']) > 0)
		{
			$request->session()->flash('message_type', "false");
			$request->session()->flash('flash_message', "هذا المريض مسجل فى حجز عيادة و لم يتم انهاء زيارته");
		}
		else{
			// check if a patient is already exist in a department.
			if($input['patient_id'] != "" && $this->checkIfPatientIsExistInDepartment($input['patient_id']) > 0)
			{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا المريض موجود فى القسم الداخلي و لم يتم اخراجه");
			}
			else{
				
				// check if a patient is recorded before or not to insert or update
				if($input['patient_id']== "")
				{
					$messages["name.required"]='هذا الحقل مطلوب الأدخال.';
					$messages["name.regex"]='ادخل اسم المريض بشكل صحيح';
					$messages["name.min"]='حقل الأسم الأول يجب الأ يقل عن :min حروف';
					$messages["name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
					$messages["gender.required"]='هذا الحقل مطلوب الأدخال.';
					$messages['sin.sin_format'] =  'رقم البطاقة غير صحيح.';
					$messages["sin.unique"]='رقم البطاقة موجود من قبل';
					$messages['birthdate.required'] = 'هذا الحقل مطلوب الأدخال.';
					$messages['birthdate.date'] = 'حقل تاريخ الميلاد يجب أن يكون تاريخ';
					$messages['address.required'] = 'هذا الحقل مطلوب الأدخال.';
					$messages['address.min'] = 'حقل العنوان يجب الأ يقل عن :min حروف';
					$messages['phone_num.max'] = 'حقل رقم التليفون يجب الأ يتعدي :max رقم';
					$messages['phone_num.min'] = 'حقل رقم التليفون يجب الأ يقل عن :min أرقام';
					$messages["government_id.required"]='يجب اختيار اسم المحافظة.';
					//$constraints['name']='required|min:6|max:255|regex:/(^([a-zA-Z-ا-ى- ]+)(\d+)?$)/u';
					$constraints['name']='required|min:8|max:255|regex:/^[\pL\s\-]+$/u';
					$constraints['gender']='required';
					$constraints['sin']='sin_format|unique:patients,sin';
					$constraints['birthdate'] = 'required|date|before:tomorrow';
					$constraints['address']='required|min:3';
					$constraints['phone_num']='min:7|max:20';
					$constraints['government_id']='required';
				}
				$messages['social_status.min']='حقل الحالة الاجتماعية يجب الأ يقل عن :min حروف';
				$messages['job.min']='حقل المهنة يجب الأ يقل عن :min حروف';
				$messages["person_relation_name.min"]='حقل الأسم الأول يجب الأ يقل عن :min حروف';
				$messages["person_relation_name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
				$messages['person_relation_phone_num.max'] = 'حقل رقم التليفون يجب الأ يتعدي :max رقم';
				$messages['person_relation_phone_num.min'] = 'حقل رقم التليفون يجب الأ يقل عن :min أرقام';
				
				$messages['companion_sid.sin_format'] =  'رقم البطاقة غير صحيح.';
				$messages['companion_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض';
				$messages['companion_name.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['companion_name.min'] ='هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['companion_name.max'] = 'هذا الحقل يجب الأ يتعدي :max حرف';
				$messages['companion_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['companion_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['companion_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['companion_address.max'] = 'هذا الحقل يجب الأ يتعدي :max حرف';
				$messages['companion_job.min'] = 'حقل المهنة يجب الأ يقل عن :min حروف';
				$messages['companion_phone_num.max'] = 'حقل رقم التليفون يجب الأ يتعدي :max رقم';
				$messages['companion_phone_num.min'] = 'حقل رقم التليفون يجب الأ يقل عن :min أرقام';
				
				
				$messages['entry_id.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['medical_id.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['room_number.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['entry_date.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['entry_date.after'] = 'تاريخ الدخول يجب أن يلي تاريخ الميلاد';
				$messages['entry_time.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['reg_time.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['contract_id.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['converted_from.required'] = 'يجب ادخال الجهة المحول منها المريض';
				$messages['entry_reason_desc.required'] = 'يجب ادخال التشخيص المبدئ للمريض';
				if($role_name=="GeneralRecept" || $role_name=="Injuires")
					{
						$messages["doctor_name.required"]='يجب ادخال اسم الطبيب  المعالج';
						$messages["doctor_name.min"]='اسم الطبيب يجب الأيقل عن :min حروف';
						$messages["doctor_name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
						$messages["kateb_name.required"]='هذا الحقل مطلوب الأدخال';
						$messages["kateb_name.min"]="هذا الحقل يجب الأ يقل عن :min حروف";
						$messages["kateb_name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
						$messages["ticket_number.required"]='هذا الحقل مطلوب الأدخال';
						$messages["ticket_number.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
						//$messages["Companion_Ticket_Number.required"]='هذا الحقل مطلوب الأدخال.';
						$constraints['doctor_name']='required|min:2|max:255';
						$constraints['kateb_name']='required|min:2|max:255';
						$constraints['ticket_number']='required|max:20';
						//$constraints['Companion_Ticket_Number']='required';
					}
						$constraints['social_status']='min:4';
						$constraints['job']='min:4';
						$constraints['person_relation_name']='min:2|max:255';
						$constraints['person_relation_phone_num']='min:4|max:20';
						$constraints['contract_id']='required';
						$constraints['converted_from']='required';
						$constraints['entry_reason_desc']='required';
						/*$constraints['medical_id']='required';
						$constraints['room_number']='required';*/
						$constraints['entry_date']='required';
						$constraints['entry_time']='required';
						$constraints['reg_time']='required';
						$constraints['entry_id']='required';
						//dd($role_id);
				if($input['sin']!="")
					$constraints['companion_sid']='sin_format|different:sin';
				else
				{
					$constraints['companion_sid']='sin_format';
					$constraints['companion_name']='min:2|max:50';
					$constraints['companion_address']='min:3';
					$constraints['companion_job']='min:4';
					$constraints['companion_phone_num']='min:4|max:20';
					$constraints['entry_id']='required';
				}
				if((bool)strtotime($input['birthdate']))
				{
					$constraints['entry_date']='required|date|after:'.Carbon::parse($input['birthdate'])->addDays(-1);
				}
				$input=$request->all();
				$this->validate($request,$constraints,$messages);
				$entry_reason_desc_var=$input['entry_reason_desc'];
				//dd($role_id);
				
				if($role_name=="Private")
				{
						$room=Room::find($input['room_number']);
						
						if(!$room->where('number_of_vacancy_beds','<',$room->number_of_beds-1)->exists())
						{
							return redirect()->back()->withFlashMessage('لا يوجد سرير شاغر لعمل دخول مريض');
						} 
				}
				elseif($role_name=="Injuires")
				{
				switch ($input['entry_reason_desc'])
				{
					case 1:
					{
						$entry_reason_desc_var="ادعاء طلق نارى";
						
						break;
					}
					case 2:
					{
						$entry_reason_desc_var="ادعاء اعتداء من أخرين";
						break;
					}
					case 3:
					{
						$entry_reason_desc_var="ادعاء حادث سيارة";
						break;
					}
					case 4:
					{
						$entry_reason_desc_var="ادعاء حادث موتوسيكل";
						break;
					}
					case 5:
					{
						$entry_reason_desc_var="ادعاء سقوط من على سلم";
						break;
					}
					case 6:
					{
						$entry_reason_desc_var="ادعاء سقوط من على الأرض";
						break;
					}
					case 7:
					{
						$entry_reason_desc_var="ادعاء سقوط من علو";
						break;
					}
					case 8:
					{
						$entry_reason_desc_var="ادعاء سقوط من على دابة";
						break;
					}
					case 9:
					{
						$entry_reason_desc_var="ادعاء اصطدام بجسم صلب";
						break;
					}
					case 10:
					{
						$entry_reason_desc_var="ادعاء اصابة بألة حادة";
						break;
					}
					case 11:
					{
						$entry_reason_desc_var=$input['entry_reason_desc'];
						break;
					}
				}
				}
				//dd($entry_reason_desc_var);
				DB::beginTransaction();
				//	dd($input);
				try{
					$patient_code=0;
					$patient_new_code=0;
					if($input['patient_id']== ""){
						$patient_input=
						array(
							'sin'=>$input['sin']==""?null:$input['sin'],
							'name'=>$input['name'],
							'gender'=>$input['gender'],
							'address'=>$input['address'],
							'birthdate'=>$input['birthdate'],
							'phone_num'=>$input['phone_num'],
							'social_status'=>$input['social_status']==""?null:$input['social_status'],
							'job'=>$input['job']==""?null:$input['job'],
							'social_status'=>$input['social_status']==""?null:$input['social_status'],
							'patient_government_id'=>$input['government_id'],
							'hasOpenVisits'=>1,
						);
						$the_last_id=Patient::orderBy('id', 'desc')->first();		
						if($the_last_id != null)
						{
							$patient_input['id']=$the_last_id->id+1;
							//$patient_input['id']=$count;
							$patient_input['new_id']="MN".date('Y').date('m').date('d').$patient_input['id'];
						}
						else
							$patient_input['id']=1;	
							$patient_input['new_id']="MN".date('Y').date('m').date('d').$patient_input['id'];
							$patient_object=Patient::create($patient_input);
							$pid=$patient_object->id;
							$patient_code=$pid;
							$patient_new_code=$patient_input['new_id'];
								
							//dd($patient_object);
					}
					else{
						$exist_patient=Patient::find($input['patient_id']);
						$exist_patient->social_status=$input['social_status']==""?null:$input['social_status'];
						$exist_patient->job=$input['job']==""?null:$input['job'];
						$exist_patient->save();
						$pid=$exist_patient->id;
						$patient_code=$pid;
						$patient_new_code=$exist_patient->new_id;
					}
					$visit=0;
					//dd($visit);
					/*if( $role_id ==4)
					{
					$visit_input_data=
					array(
						'patient_id'=>$pid,
						'ticket_num'=>0,
						'person_relation_name'=>$input['person_relation_name'],
						'person_relation_phone_num'=>$input['person_relation_phone_num'],
						'person_relation_id'=>$input['person_relation_id'],
						'companion_name'=>$input['companion_name']==""?null:$input['companion_name'],
						'companion_sid'=>$input['companion_sid']==""?null:$input['companion_sid'],
						'companion_address'=>$input['companion_address']==""?null:$input['companion_address'],
						'companion_job'=>$input['companion_job']==""?null:$input['companion_job'],
						'companion_phone_num'=>$input['companion_phone_num']==""?null:$input['companion_phone_num'],
						'entry_date'=>$input['entry_date'],
						'entry_time'=>$input['entry_time'],
						'reg_time'=>$input['reg_time'],
						'contract_id'=>$input['contract_id'],
						'converted_from'=>$input['converted_from']==""?null:$input['converted_from'],
						'checkup'=>isset($input['checkup']),
						'entry_reason_desc'=>$input['entry_reason_desc']==""?null:$input['entry_reason_desc'],
						'file_number'=>$patient_code,
						'file_type'=>$input['file_type']==""?null:$input['file_type'],
						'cure_type_id'=>$input['cure_type_id']==""?null:$input['cure_type_id'],
						'reference_doctor_id'=>$input['reference_doctor_id']==""?null:$input['reference_doctor_id'],
						'patient_new_id'=>$patient_new_code,

					);
				}
				elseif($role_id ==8 ||$role_id ==12)
				{
					$visit_input_data=
					array(
						'patient_id'=>$pid,
						'ticket_num'=>0,
						'person_relation_name'=>$input['person_relation_name'],
						'person_relation_phone_num'=>$input['person_relation_phone_num'],
						'person_relation_id'=>$input['person_relation_id'],
						'companion_name'=>$input['companion_name']==""?null:$input['companion_name'],
						'companion_sid'=>$input['companion_sid']==""?null:$input['companion_sid'],
						'companion_address'=>$input['companion_address']==""?null:$input['companion_address'],
						'companion_job'=>$input['companion_job']==""?null:$input['companion_job'],
						'companion_phone_num'=>$input['companion_phone_num']==""?null:$input['companion_phone_num'],
						'entry_date'=>$input['entry_date'],
						'entry_time'=>$input['entry_time'],
						'reg_time'=>$input['reg_time'],
						'contract_id'=>$input['contract_id'],
						'converted_from'=>$input['converted_from']==""?null:$input['converted_from'],
						'checkup'=>isset($input['checkup']),
						'entry_reason_desc'=>$entry_reason_desc_var,
						'file_type'=>$input['file_type']==""?null:$input['file_type'],
						'cure_type_id'=>$input['cure_type_id']==""?null:$input['cure_type_id'],
						//'reference_doctor_id'=>$input['reference_doctor_id']==""?null:$input['reference_doctor_id'],
						'ambulance_number'=>$input['ambulance_number'],
						'paramedic_name'=>$input['paramedic_name'],
						'kateb_name'=>$input['kateb_name'],
						'ticket_number'=>$input['ticket_number'],
						'patient_new_id'=>$patient_new_code,
						'Companion_Ticket_Number'=>$input['Companion_Ticket_Number']==""?null:$input['Companion_Ticket_Number'],
						'file_number'=>$patient_code,
						'out_patient'=>1,
					);
				}
				elseif($role_id ==8)
				{
					$visit_input_data=
					array(
					'doctor_name'=>$input['doctor_name'],
					);
				}
					$new_pid=$patient_code;
					$visit_input_data['user_id']=Auth::id();
					$visit_input_data['entry_id']=$input['entry_id'];
					$visit=Visit::create($visit_input_data);
			*/
					switch ($role_name)
					{
						case "Entrypoint":
						{
						//dd($input);
						$visit_input_data=
						array(
							'patient_id'=>$pid,
							'ticket_num'=>0,
							'person_relation_name'=>$input['person_relation_name'],
							'person_relation_phone_num'=>$input['person_relation_phone_num'],
							'person_relation_id'=>$input['person_relation_id'],
							'companion_name'=>$input['companion_name']==""?null:$input['companion_name'],
							'companion_sid'=>$input['companion_sid']==""?null:$input['companion_sid'],
							'companion_address'=>$input['companion_address']==""?null:$input['companion_address'],
							'companion_job'=>$input['companion_job']==""?null:$input['companion_job'],
							'companion_phone_num'=>$input['companion_phone_num']==""?null:$input['companion_phone_num'],
							'entry_date'=>$input['entry_date'],
							'entry_time'=>$input['entry_time'],
							'reg_time'=>$input['reg_time'],
							'contract_id'=>$input['contract_id'],
							'converted_from'=>$input['converted_from']==""?null:$input['converted_from'],
							'checkup'=>isset($input['checkup']),
							'entry_reason_desc'=>$input['entry_reason_desc']==""?null:$input['entry_reason_desc'],
							'file_number'=>$patient_code,
							'file_type'=>$input['file_type']==""?null:$input['file_type'],
							'cure_type_id'=>$input['cure_type_id']==""?null:$input['cure_type_id'],
							//'reference_doctor_id'=>$input['reference_doctor_id']==""?null:$input['reference_doctor_id'],
							'patient_new_id'=>$patient_new_code,

						);
							$new_pid=$patient_code;
							$visit_input_data['user_id']=Auth::id();
							$visit_input_data['entry_id']=$input['entry_id'];
							$visit=Visit::create($visit_input_data);
							$medicalunit = $input['medical_id'];
							$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>$input['room_number'],'conversion_date'=>$input['entry_date'],'reference_doctor_id'=>$input['reference_doctor_id'],'user_id'=>$user->id)));
							break;
						}
						case "Private":
						{
							$visit_input_data=
					array(
						'patient_id'=>$pid,
						'ticket_num'=>0,
						'person_relation_name'=>$input['person_relation_name'],
						'person_relation_phone_num'=>$input['person_relation_phone_num'],
						'person_relation_id'=>$input['person_relation_id'],
						'companion_name'=>$input['companion_name']==""?null:$input['companion_name'],
						'companion_sid'=>$input['companion_sid']==""?null:$input['companion_sid'],
						'companion_address'=>$input['companion_address']==""?null:$input['companion_address'],
						'companion_job'=>$input['companion_job']==""?null:$input['companion_job'],
						'companion_phone_num'=>$input['companion_phone_num']==""?null:$input['companion_phone_num'],
						'entry_date'=>$input['entry_date'],
						'entry_time'=>$input['entry_time'],
						'reg_time'=>$input['reg_time'],
						'contract_id'=>$input['contract_id'],
						'converted_from'=>$input['converted_from']==""?null:$input['converted_from'],
						'checkup'=>isset($input['checkup']),
						'entry_reason_desc'=>$input['entry_reason_desc']==""?null:$input['entry_reason_desc'],
						'file_number'=>$patient_code,
						'file_type'=>$input['file_type']==""?null:$input['file_type'],
						'cure_type_id'=>$input['cure_type_id']==""?null:$input['cure_type_id'],
						//'reference_doctor_id'=>$input['reference_doctor_id']==""?null:$input['reference_doctor_id'],
						'patient_new_id'=>$patient_new_code,

					);
							$new_pid=$patient_code;
							$visit_input_data['user_id']=Auth::id();
							$visit_input_data['entry_id']=$input['entry_id'];
							$visit=Visit::create($visit_input_data);
							$medicalunit = $input['medical_id'];
							$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>$input['room_number'],'conversion_date'=>$input['entry_date'],'reference_doctor_id'=>$input['reference_doctor_id'],'user_id'=>$user->id)));
							$room->decrement('number_of_vacancy_beds');
							break;
						}
						case "Injuires":
						{
							//dd($visit);
							$visit_input_data=
					array(
						'patient_id'=>$pid,
						'ticket_num'=>0,
						'person_relation_name'=>$input['person_relation_name'],
						'person_relation_phone_num'=>$input['person_relation_phone_num'],
						'person_relation_id'=>$input['person_relation_id'],
						'companion_name'=>$input['companion_name']==""?null:$input['companion_name'],
						'companion_sid'=>$input['companion_sid']==""?null:$input['companion_sid'],
						'companion_address'=>$input['companion_address']==""?null:$input['companion_address'],
						'companion_job'=>$input['companion_job']==""?null:$input['companion_job'],
						'companion_phone_num'=>$input['companion_phone_num']==""?null:$input['companion_phone_num'],
						'entry_date'=>$input['entry_date'],
						'entry_time'=>$input['entry_time'],
						'reg_time'=>$input['reg_time'],
						'contract_id'=>$input['contract_id'],
						'converted_from'=>$input['converted_from']==""?null:$input['converted_from'],
						'checkup'=>isset($input['checkup']),
						'entry_reason_desc'=>$entry_reason_desc_var,
						'file_type'=>$input['file_type']==""?null:$input['file_type'],
						'cure_type_id'=>$input['cure_type_id']==""?null:$input['cure_type_id'],
						'doctor_name'=>$input['doctor_name']==""?null:$input['doctor_name'],
						'ambulance_number'=>$input['ambulance_number'],
						'paramedic_name'=>$input['paramedic_name'],
						'kateb_name'=>$input['kateb_name'],
						'ticket_number'=>$input['ticket_number'],
						'patient_new_id'=>$patient_new_code,
						'Companion_Ticket_Number'=>$input['Companion_Ticket_Number']==""?null:$input['Companion_Ticket_Number'],
						'file_number'=>$patient_code,
						//'out_patient'=>1,
					);
							$new_pid=$patient_code;
							$visit_input_data['user_id']=Auth::id();
							$visit_input_data['entry_id']=$input['entry_id'];
							$visit=Visit::create($visit_input_data);
							$medicalunit = 22;
							$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>99,'conversion_date'=>$input['entry_date'],'reference_doctor_id'=>20107,'user_id'=>$user->id)));
							break;
						}
						case "GeneralRecept":
						{
					$visit_input_data=
						array(
							'patient_id'=>$pid,
							'ticket_num'=>0,
							'person_relation_name'=>$input['person_relation_name'],
							'person_relation_phone_num'=>$input['person_relation_phone_num'],
							'person_relation_id'=>$input['person_relation_id'],
							'companion_name'=>$input['companion_name']==""?null:$input['companion_name'],
							'companion_sid'=>$input['companion_sid']==""?null:$input['companion_sid'],
							'companion_address'=>$input['companion_address']==""?null:$input['companion_address'],
							'companion_job'=>$input['companion_job']==""?null:$input['companion_job'],
							'companion_phone_num'=>$input['companion_phone_num']==""?null:$input['companion_phone_num'],
							'entry_date'=>$input['entry_date'],
							'entry_time'=>$input['entry_time'],
							'reg_time'=>$input['reg_time'],
							'contract_id'=>$input['contract_id'],
							'converted_from'=>$input['converted_from']==""?null:$input['converted_from'],
							'checkup'=>isset($input['checkup']),
							'entry_reason_desc'=>$entry_reason_desc_var,
							'file_type'=>$input['file_type']==""?null:$input['file_type'],
							'cure_type_id'=>$input['cure_type_id']==""?null:$input['cure_type_id'],
							'doctor_name'=>$input['doctor_name']==""?null:$input['doctor_name'],
							'ambulance_number'=>$input['ambulance_number'],
							'paramedic_name'=>$input['paramedic_name'],
							'kateb_name'=>$input['kateb_name'],
							'ticket_number'=>$input['ticket_number'],
							'patient_new_id'=>$patient_new_code,
							'Companion_Ticket_Number'=>$input['Companion_Ticket_Number']==""?null:$input['Companion_Ticket_Number'],
							'file_number'=>$patient_code,
							//'out_patient'=>1,
						);
								$new_pid=$patient_code;
								$visit_input_data['user_id']=Auth::id();
								$visit_input_data['entry_id']=$input['entry_id'];
								$visit=Visit::create($visit_input_data);
								$medicalunit = 26;
								$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>110,'conversion_date'=>$input['entry_date'],'reference_doctor_id'=>20107,'user_id'=>$user->id)));
								break;
						}
						default:
						{
							break;
						}
					}
					/*if($role_name=="Entrypoint" || $role_name=="Private")
					{
						$medicalunit = $input['medical_id'];
						$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>$input['room_number'])));
					}
					elseif($role_name=="Injuires")
					{
						$medicalunit = 22;
						$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>99)));
					}
					if($role_name=="Private")
					{
						$room->decrement('number_of_vacancy_beds');
						//dd($room);
					}*/
					$vid=$visit->id;
					DB::commit();
					$pid=$patient_code;
					$p=Patient::find($pid);
					//dd($patient_input);
					//dd($p);
					if($input['sin']!="")
					{
					$psin=$input['sin'];
					$request->session()->flash('flash_message', ":تم حفظ بيانات المريض بنجاح - كودالمريض $psin - رقم ملف المريض:$pid");
					}
					else
					{
						$request->session()->flash('flash_message', "تم حفظ بيانات المريض بنجاح - كودالمريض : $new_pid - رقم ملف المريض : $pid");
					}
					//$request->session()->flash('flash_message', "رقم ملف المريض : $new_pid");
					$request->session()->flash('message_type', "true");
					$request->session()->flash('id', $pid);
					$request->session()->flash('vid', $vid);
					
				}
				catch(\Exception $e){
					dd($e);
					DB::rollback();
					$request->session()->flash('flash_message', " يوجد خطأ فى عملية الأدخال حاول مرة أخرى ");
				}
			}
		}
		//dd($patient_object);
		return redirect()->action('PatientController@index');
	}
	public function receptPatient_deptConversion($id,$visit_id)
	{
		$medical_units=MedicalUnit::where('type','d')->whereNull('parent_department_id')->lists('name', 'id');
		$first_medical_unit= MedicalUnit::where('type','d')->whereNull('parent_department_id')->first();
		
		$first_department_doctors=$first_medical_unit->users()->lists('users.name','users.id');
		$first_rooms=$first_medical_unit->rooms()->lists('rooms.name','rooms.id');
		
		/*$cure_types=CureType::lists('name','id');
		$file_types=FileType::lists('name','id');
		$contracts=Contract::lists('name','id');
		$converted_from=ConvertedFrom::lists('name','id');
		$governments=government::lists('name','government_id');*/
		
		$user=User::find(Auth::id());
		$role_id=$user->role->id;
		$role_name=$user->role->name;
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		//dd($entrypoints);
		$entrypoint_Array=array();
		foreach($user->entrypoints as $row)
			$entrypoint_Array[$row->id]=$row->name;
		$relations= Relation::lists('name','id');
		$patient_data=DB::table('patients')->select('new_id','name','sin')->where('id','=',$id)->get();
		$current_dept_data=Visit::join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
							->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
							->join('rooms','rooms.id','=','medical_unit_visit.room_id')
							->select('medical_units.id as current_dept','rooms.id as current_room')
							->where('visits.id','=',$visit_id)
							->orderBy('medical_unit_visit.created_at','desc')->first();
		return view('recept_department_conversion2',array('relations'=>$relations,'entrypoints'=>$entrypoint_Array,'medical_units'=>$medical_units,'entrypoints'=>$entrypoint_Array,
		'first_department_doctors'=>$first_department_doctors,'role_id'=>$role_id,'role_name'=>$role_name,'visit_id'=>$visit_id,'patient_data'=>$patient_data,'current_dept_data'=>$current_dept_data));
	}
	public function store_receptPatientDeptConversion(Request $request)
	{
	/*	$input=$request->all();

		$visit=Visit::find($request->v_id);
		$fromRoom=Room::find($request->current_room);
		$toRoom=Room::find($request->room_number);
		
		$toDep=$request->department;
		$fromDep=MedicalUnit::find($request->current_dep);
		DB::beginTransaction();
		try{
			$fromDep->visits()->orderBy('created_at','desc')->updateExistingPivot($request->v_id,array('convert_to'=>$toDep));
			$fromRoom->decrement('number_of_vacancy_beds');
			
			$visit->medicalunits()->attach(array($toDep=>array('room_id'=>$request->room_number,'user_id'=>Auth::id())));
			$toRoom->increment('number_of_vacancy_beds');
			
			DB::commit();
			return redirect()->back()->withSuccess("تم التحويل بالنجاح");
		}
		catch(\Exception $e){
			DB::rollBack();
			return redirect()->back()->withError('حدثت مشكلة مرة أخرى!!');
		}*/
				$input=$request->all();
				$user=User::find(Auth::id());
				//dd($user);
				//dd($input);
			/*	$patient_data=Visit::join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
							->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
							->join('rooms','rooms.id','=','medical_unit_visit.room_id')
							->select('medical_units.id as current_dept','rooms.id as current_room')
							->where('visits.id','=',$input['visit_id'])
							->orderBy('medical_unit_visit.created_at','desc')->first();*/
							
				$messages["medical_id.required"] = 'هذا الحقل مطلوب الأدخال';
				$messages['medical_id.different'] = 'هذا المريض متواجد فى هذا القسم';
				$messages["room_number.required"] = 'هذا الحقل مطلوب الأدخال';
				$messages["entry_date.required"] = 'هذا الحقل مطلوب الأدخال';
				
				$constraints['medical_id']='required|different:current_dept';
				$constraints['room_number']='required';
				$constraints['entry_date']='required';
				$this->validate($request,$constraints,$messages);
				
			/*	$patient_data=DB::table('visits')
							->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
							->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
							->join('rooms','rooms.id','=','medical_unit_visit.room_id')
							->select('medical_units.id as current_dept','rooms.id as current_room')
							->where('visits.id','=',$input['visit_id'])
							->orderBy('medical_unit_visit.created_at','desc')->first();*/
							//->get();
					//		dd($patient_data);
				
				$visit=Visit::find($input['visit_id']);
				$entry_date=Carbon::parse($visit->entry_date);
				$conversion_date=Carbon::parse($input['entry_date']);
				if($entry_date->diffInDays($conversion_date,false) < 0){
					return redirect()->back()->withErrors(array('entry_date'=>'لا يمكن أن يكون تاريج التحويل أقل من تاريخ الدخول'))
									 ->withInput();
					}
				$fromRoom=$input['current_room'];
				$toRoom=$input['room_number'];
				$toDep=$input['medical_id'];
				$fromDep=$input['current_dept'];
				DB::beginTransaction();
			try{
				//$visit->medicalunits()->updateExistingPivot($input['visit_id'],array('convert_to'=>$toDep));
				//$fromRoom->decrement('number_of_vacancy_beds');
				//$visit->reception_conversion_date=$input['entry_date'];
				//$visit->reference_doctor_id=$input['reference_doctor_id'];
				$visit->out_patient=0;
				$visit->save();
				DB::commit();
				$medicalunit = MedicalUnit::find($input['current_dept']);
				//dd($medicalunit);
				$medicalunit->visits()->orderBy('created_at','desc')->updateExistingPivot($input['visit_id'],array('convert_to'=>$toDep));
				DB::commit();
				$medicalunit= $input['medical_id'];
				$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>$input['room_number'],'conversion_date'=>$conversion_date,'reference_doctor_id'=>$input['reference_doctor_id'],'user_id'=>$user->id)));
				DB::commit();
				return redirect()->action('PatientController@showinpatientvisits')->withSuccess('تمت العملية بنجاح');
				}
				catch(\Exception $e)
				{
					dd($e);
					DB::rollBack();
					return redirect()->back()->withFlashMessage('حدثت مشكلة حاول مرة أخرى')
											 ->withMessageType('false');
				}
				
		
	}
	
	public function storeTicket(Request $request,$pid)
	{
		$input=$request->all();

		// If code is not empty, it means that the patient data is already exist

		if(isset($input['id']) && $input['id'] != "")
		{
			// check if a patient is already exist ( visit closed flag is false ) in the same clinic where he/she reserves.
			if($this->checkIfPatientIsExistTodayInClinic($input['id'],$input['medical_id']) > 0)
			{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا المريض تم تسجيل له هذا الحجز و لم يتم أنهاءه");
			}
			else{
				// check if a patient is already exist in a department.
				if($this->checkIfPatientIsExistInDepartment($input['id']) > 0)
				{
					$request->session()->flash('message_type', "false");
					$request->session()->flash('flash_message', "هذا المريض موجود فى القسم الداخلي و لم يتم اخراجه");
				}
				else{

					$desk_ticket=Visit::where('ticket_number',$input['ticket_num'])
														->whereNotNull('ticket_type')
														->first();
					$messages=[
						'ticket_num.required' => 'هذا الحقل مطلوب الأدخال.',
						'ticket_num.numeric' => 'حقل رقم التذكرة يجب أن يكون رقم فقط .',
						'ticket_num.unique' => 'رقم التذكرة موجود من قبل .',
						'entry.required' => 'هذا الحقل مطلوب الأدخال.',
						'medical_id.required' => 'هذا الحقل مطلوب الأدخال.',
					];
					if(count($desk_ticket) > 0)
						$constraints['ticket_num']='required|numeric|unique:visits,ticket_number,'.$desk_ticket->id;
					else {
						$constraints['ticket_num']='required|numeric|unique:visits,ticket_number';
					}

					$constraints['entry']='required';
					$constraints['medical_id']='required';
					if($input['reservation_type'] == "T&E"){

						$messages['c_name.required']= 'هذا الحقل مطلوب الأدخال.';
						$messages['c_name.min']= 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['c_name.max']= 'هذا الحقل يجب الأ يتعدي :max حرف';
						$messages['relation_id.required']= 'هذا الحقل مطلوب الأدخال.';
						$messages['c_address.required']= 'هذا الحقل مطلوب الأدخال.';
						$messages['c_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['job.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['c_sid.required']= 'هذا الحقل مطلوب الأدخال.';
						$messages['c_sid.size'] = 'حقل رقم البطاقة الخاص بالمرافق يجب أن يكون مكون من :size رقم.';
						$messages['c_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض.';
						$messages['entry_reason_desc.required']= 'هذا الحقل مطلوب الأدخال.';
						$messages['entry_time.required']= 'هذا الحقل مطلوب الأدخال.';

						$constraints['c_name']='required|min:2|max:50';
						$constraints['relation_id']='required';
						$constraints['c_address']='required|min:2';
						$constraints['job']='min:2';
						$constraints['c_sid']='required|size:14|different:hidden_sid';
						$constraints['entry_reason_desc']='required';
						$constraints['entry_time']='required';
					}
					$this->validate($request, $constraints ,$messages);

					if($input['reservation_type'] == "T&E"){
						$arr['c_sid']=return_birthdate($input['c_sid']);
						$messages = [
							'c_sid.date' => 'الرقم القومي غير صحيح'
						];
						$validator = Validator::make($arr, [
						  'c_sid' => 'date',
					  ],$messages);

					  if ($validator->fails()) {
						  return redirect()->back()
											 ->withErrors($validator)
											 ->withInput();
					  }
					}
					$visit_input_data=
					array(
						'patient_id'=>$input['id'],
						'ticket_number'=>$input['ticket_num'],
						'user_id'=>Auth::id(),
						'entry_id'=>$input['entry']
					);
					if($input['reservation_type'] == "T&E"){
						$visit_input_data['c_name']=$input['c_name'];
						$visit_input_data['sid']=$input['c_sid'];
						$visit_input_data['relation_id']=$input['relation_id'];
						$visit_input_data['address']=$input['c_address'];
						$visit_input_data['job']=$input['job']==""?null:$input['job'];
						$visit_input_data['entry_time']=$input['entry_time'];
						$visit_input_data['entry_reason_desc']=$input['entry_reason_desc'];
						$visit_input_data['entry_date']=date('Y-m-d');
					}
					$visit=Visit::create($visit_input_data);
					if($input['reservation_type'] == "T&E"){
						// Get the attached department to chosen clinic
						$department_id= MedicalUnit::find($input['medical_id']);
						if(is_null($department_id->parent_department_id)){
								return redirect()->back()
																->withFlashMessage( "يجب أن يكون للعيادة القسم الخاص بها لكي يتم عملية دخول المريض")
																->withMessageType("false")
																->withInput();
						}
						$visit->medicalunits()->attach($department_id->parent_department_id);
					}
					else{
						$visit->medicalunits()->attach($input['medical_id']);
					}
					$request->session()->flash('flash_message', "تم التسجيل بنجاح - كود المريض : $input[id] ");
					$request->session()->flash('vid', $visit->id);
				}
			}
		}
		else{
			$desk_ticket=Visit::where('ticket_number',$input['ticket_num'])
												->whereNotNull('ticket_type')
												->first();
			$messages = [
				'fname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'fname.min' => 'حقل الأسم الأول يجب الأ يقل عن :min حروف',
				'fname.max' => 'حقل الأسم الأول يجب الأ يتعدي :max حرف',
				'fname.alpha' =>'حقل الأسم الأول يجب أن يكون حروف فقط بدون وجود مسافات.',
				'sname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'sname.min' => 'حقل الأسم الثاني يجب الأ يقل عن :min حروف',
				'sname.max' => 'حقل الأسم الثاني يجب الأ يتعدي :max حرف',
				'sname.alpha' =>'حقل الأسم الثاني أن يكون حروف فقط بدون وجود مسافات.',
				'mname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'mname.min' => 'حقل الأسم الثالث يجب الأ يقل عن :min حروف',
				'mname.max' => 'حقل الأسم الثالث يجب الأ يتعدي :max حرف',
				'mname.alpha' =>'حقل الأسم الثالث أن يكون حروف فقط بدون وجود مسافات.',
				'lname.min' => 'حقل الأسم الرابع يجب الأ يقل عن :min حروف',
				'lname.max' => 'حقل الأسم الرابع يجب الأ يتعدي :max حرف',
				'lname.alpha' =>'حقل الأسم الرابع أن يكون حروف فقط بدون وجود مسافات.',
				'gender.required' => 'هذا الحقل مطلوب الأدخال.',
				'address.required' => 'هذا الحقل مطلوب الأدخال.',
				'sid.size' => 'حقل رقم البطاقة يجب أن يكون مكون من :size رقم.',
				'sid.unique' => 'رقم البطاقة موجود من قبل.',
				'year_age.numeric' => 'حقل عدد السنين يجب ان يكون رقم فقط.',
				'year_age.required_without_all' => 'حقل عدد السنين يجب أن يكون أكبر من 0 فى حالة عدم وجود عدد أيام أو عدد أشهر',
				'ticket_num.required' => 'هذا الحقل مطلوب الأدخال.',
				'ticket_num.numeric' => 'حقل رقم التذكرة يجب أن يكون رقم فقط .',
				'ticket_num.unique' => 'رقم التذكرة موجود من قبل .',
				'entry.required' => 'هذا الحقل مطلوب الأدخال.',
				'medical_id.required' => 'هذا الحقل مطلوب الأدخال.',
				'reservation_type.required' => 'هذا الحقل مطلوب الأدخال.',
			];
			//dd($input['ticket_num']);
				$constraints['fname'] = 'required|alpha|min:2|max:20';
				$constraints['sname'] = 'required|alpha|min:2|max:20';
				$constraints['mname'] = 'required|alpha|min:2|max:20';
				$constraints['lname'] = 'alpha|min:2|max:20';
				$constraints['gender'] = 'required';
				$constraints['address'] = 'required';
				$constraints['sid'] = 'size:14|unique:patients,sid';
				$constraints['year_age'] = 'numeric|required_without_all:month_age,day_age';

				if(count($desk_ticket) > 0)
					$constraints['ticket_num']='required|numeric|unique:visits,ticket_number,'.$desk_ticket->id;
				else {
					$constraints['ticket_num']='required|numeric|unique:visits,ticket_number';
				}


				$constraints['entry']='required';
				$constraints['medical_id']='required';
				$constraints['reservation_type']='required';


			if($input['reservation_type'] == "T&E"){

				$messages['c_name.required']= 'هذا الحقل مطلوب الأدخال.';
				$messages['c_name.min']= 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_name.max']= 'هذا الحقل يجب الأ يتعدي :max حرف';
				$messages['relation_id.required']= 'هذا الحقل مطلوب الأدخال.';
				$messages['c_address.required']= 'هذا الحقل مطلوب الأدخال.';
				$messages['c_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['job.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_sid.required']= 'هذا الحقل مطلوب الأدخال.';
				$messages['c_sid.size'] = 'حقل رقم البطاقة الخاص بالمرافق يجب أن يكون مكون من :size رقم.';
				$messages['c_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض.';
				$messages['entry_reason_desc.required']= 'هذا الحقل مطلوب الأدخال.';
				$messages['entry_time.required']= 'هذا الحقل مطلوب الأدخال.';

				$constraints['c_name']='required|min:2|max:50';
				$constraints['relation_id']='required';
				$constraints['c_address']='required|min:2';
				$constraints['job']='min:2';
				$constraints['c_sid']='required|size:14|different:hidden_sid';
				$constraints['entry_reason_desc']='required';
				$constraints['entry_time']='required';
			}
			$this->validate($request, $constraints ,$messages);

			$input['name']=$input['fname']." ".$input['sname']." ".$input['mname']." ".$input['lname'];
			unset($input['fname']);unset($input['sname']);unset($input['mname']);unset($input['lname']);

			if($input['sid'] != ""){

				$arr['sid']=return_birthdate($input['sid']);
				$messages = [
					'sid.date' => 'الرقم القومي غير صحيح'
				];
				$validator = Validator::make($arr, [
					'sid' => 'date',
				],$messages);

				if ($validator->fails()) {
					return redirect()->back()
										 ->withErrors($validator)
										 ->withInput();
				}
				$birthdate=$arr['sid'];
				$patient_input=
				array(
					'sid'=>$input['sid'],
					'name'=>$input['name'],
					'gender'=>$input['gender'],
					'address'=>$input['address'],
					'birthdate'=>$birthdate,
					'age'=>$input['year_age'],
				);
			}
			else{
				$birthdate=strtotime("-".($input['day_age']==""?0:$input['day_age'])." day",time());
				$birthdate=strtotime("-".($input['month_age']==""?0:$input['month_age'])." month",$birthdate);
				$birthdate=strtotime("-".($input['year_age']==""?0:$input['year_age'])." year",$birthdate);

				$birthdate=date('Y-m-d',$birthdate);
				$patient_input=
				array(
					'name'=>$input['name'],
					'gender'=>$input['gender'],
					'address'=>$input['address'],
					'birthdate'=>$birthdate,
					'age'=>$input['year_age'],
				);

			}
			if($input['reservation_type'] == "T&E"){
				$arr['c_sid']=return_birthdate($input['c_sid']);
				$messages = [
					'c_sid.date' => 'الرقم القومي غير صحيح'
				];
				$validator = Validator::make($arr, [
	          'c_sid' => 'date',
	      ],$messages);

	      if ($validator->fails()) {
	          return redirect()->back()
	                      		 ->withErrors($validator)
	                      		 ->withInput();
	      }
			}

			DB::beginTransaction();

			try{
				$the_last_id=Patient::orderBy('id', 'desc')->first();
				$patient_input['id']=($the_last_id->id==null)?1:$the_last_id->id+1;
				$patient_object=Patient::create($patient_input);
				$pid=$patient_object->id;
				$visit_input_data=
				array(
					'patient_id'=>$pid,
					'ticket_number'=>$input['ticket_num'],
					'user_id'=>Auth::id(),
					'entry_id'=>$input['entry']
				);
				if($input['reservation_type'] == "T&E"){
					$visit_input_data['c_name']=$input['c_name'];
					$visit_input_data['sid']=$input['c_sid'];
					$visit_input_data['relation_id']=$input['relation_id'];
					$visit_input_data['address']=$input['c_address'];
					$visit_input_data['job']=$input['job']==""?null:$input['job'];
					$visit_input_data['entry_time']=$input['entry_time'];
					$visit_input_data['entry_reason_desc']=$input['entry_reason_desc'];
					$visit_input_data['entry_date']=date('Y-m-d');
				}
				$visit=Visit::create($visit_input_data);
				if($input['reservation_type'] == "T&E"){
					// Get the attached department to chosen clinic
					$department_id= MedicalUnit::find($input['medical_id']);
					if(is_null($department_id->parent_department_id)){
							DB::rollback();
							return redirect()->back()
															->withFlashMessage( "يجب أن يكون للعيادة القسم الخاص بها لكي يتم عملية دخول المريض")
															->withMessageType("false")
															->withInput();
					}
					$visit->medicalunits()->attach($department_id->parent_department_id);
				}
				else{
					$visit->medicalunits()->attach($input['medical_id']);
				}
				DB::commit();
				$request->session()->flash('flash_message', "تم التسجيل بنجاح - كود المريض : $pid ");
				$request->session()->flash('vid',$visit->id);
			}
			catch(\Exception $e){
				DB::rollback();
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "حدثت مشكلة فى أدخال البيانات! حاول مرة أخرى");
			}
		}
		return redirect()->action('PatientController@indexTicket',array('pid'=>-1));

	}
	public function storeDesk(Request $request,$pid)
	{
		$input=$request->all();
		// If code is not empty, it means that the patient data is already exist
		//dd($input);
		if(isset($input['id']) && $input['id'] != "")
		{
			// check if a patient is already exist ( visit closed flag is false ) in the same clinic where he/she reserves.
			if($this->checkIfPatientIsExistTodayInClinic($input['id'],$input['medical_id'],(isset($input['all_deps'])),$input['reg_date']) > 0)
			{
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا المريض تم تسجيل له هذا الحجز في هذة العيادة و لم يتم أنهاءه");
			}
			else{
				// check if a patient is already exist in a department.
				if($this->checkIfPatientIsExistInDepartment($input['id']) > 0)
				{
					$request->session()->flash('message_type', "false");
					$request->session()->flash('flash_message', "هذا المريض موجود فى القسم الداخلي و لم يتم اخراجه");
				}
				else{
					if($input['ticket_status'] == "T")
						$clinic_ticket=Visit::where('ticket_number',$input['ticket_num'])
															->whereNull('ticket_type')
															->first();
					$messages=[
						'ticket_num.required' => 'هذا الحقل مطلوب الأدخال.',
						'ticket_num.numeric' => 'حقل رقم التذكرة يجب أن يكون رقم فقط .',
						'ticket_num.unique' => 'رقم التذكرة موجود من قبل .',
						'ticket_type.required' => 'هذا الحقل مطلوب الأدخال.',
						'serial_number.required' => 'هذا الحقل مطلوب الأدخال.',
						'reg_date.required' => 'هذا الحقل مطلوب الأدخال.',
						'entry.required' => 'هذا الحقل مطلوب الأدخال.',
						'medical_id.required' => 'هذا الحقل مطلوب الأدخال.',
						'reservation_type.required' => 'هذا الحقل مطلوب الأدخال.',
						'sent_by_person.required' => 'هذا الحقل مطلوب الأدخال.',
						'ticket_companion_name.required_with' => 'هذا الحقل مطلوب فى حال وجود رقم البطاقة',
						'ticket_companion_sin.sin_format' => 'رقم البطاقة غير صحيح.',
						'ticket_companion_sin.different' => 'رقم البطاقة يجب ان يكون مختلف عن رقم بطاقة المريض',
						'sid.sin_format' => 'رقم البطاقة غير صحيح.',
					];
					
					/* in case of ticket status ("T"=> number, "F"=> free word) is T, user types ticket numbar 
					and the oposite condition is F, user types free word */ 
					if($input['ticket_status'] == "T"){
						if(count($clinic_ticket) > 0){
							$constraints['ticket_num']='required|numeric|unique:visits,ticket_number,'.$clinic_ticket->id;
						}
						else{
							$constraints['ticket_num']='required|numeric|unique:visits,ticket_number';
						}
							
					}
					$constraints['ticket_type']='required';
					$constraints['entry']='required';
					$constraints['medical_id']='required';
					$constraints['reservation_type']='required';
					$constraints['sent_by_person']='required';
					$constraints['ticket_companion_name']='required_with:ticket_companion_sin';
					$constraints['ticket_companion_sin']='sin_format|different:sid';
					if(isset($input['sid'])){
						$constraints['sid']='sin_format';
					}
					if($input['reservation_type'] == "T&E"){
						
						$messages['c_name.required_with'] = 'هذا الحقل مطلوب فى حال وجود رقم البطاقة';
						$messages['c_name.min']= 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['c_name.max']= 'هذا الحقل يجب الأ يتعدي :max حرف';
						$messages['c_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['c_job.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
						$messages['c_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض.';
						$messages['c_sid.sin_format'] = 'رقم البطاقة غير صحيح.';
						$messages['file_number.required'] = 'هذا الحقل مطلوب الأدخال.';
						$messages['file_number.unique'] = 'هذا الرقم موجود من قبل';
						$messages['file_type.required'] = 'هذا الحقل مطلوب الأدخال';
						$messages['entry_date.date'] = 'هذا الحقل يجب أن يكون تاريخ';
						$messages['entry_date.after'] = 'تاريخ الدخول يجب أن يكون أكبر من تاريخ التسجيل.';
						
						$constraints['entry_date']='date|after:reg_date';
						$constraints['c_name']='required_with:c_sid|min:2|max:50';
						$constraints['c_address']='min:2';
						$constraints['c_job']='min:2';
						$constraints['c_sid']='sin_format|different:sid';
						$constraints['file_number']='required|unique:visits,file_number';
						$constraints['file_type']='required';
					}
					$this->validate($request, $constraints ,$messages);
					$patient_data=array();
					if(isset($input['sid']) && $input['sid']!=""){
						$birthdate=return_birthdate($input['sid']);
						$patient_data['sid']=$input['sid'];
						$patient_data['birthdate']=$birthdate;
					}
					if(isset($input['address'])){
						$patient_data['address']=$input['address'];
					}
					if(isset($input['job'])){
						$patient_data['job']=$input['job'];
					}
					DB::beginTransaction();
					try{
						Patient::find($input['id'])->update($patient_data);
						$visit_input_data=
						array(
							'patient_id'=>$input['id'],
							'serial_number'=>$input['serial_number'],
							'ticket_number'=>$input['ticket_status'] == "T"?$input['ticket_num']:"مجاني",
							'ticket_status'=>$input['ticket_status'],
							'ticket_type'=>$input['ticket_type'],
							'user_id'=>Auth::id(),
							'entry_id'=>$input['entry'],
							'registration_datetime'=>Carbon::parse($input['reg_date']." ".$input['reg_time']),
							'watching_status'=>$input['watching_status']==""?null:$input['watching_status'],
							'sent_by_person'=>$input['sent_by_person'],
							'ticket_companion_name'=>$input['ticket_companion_name']==""?null:$input['ticket_companion_name'],
							'ticket_companion_sin'=>$input['ticket_companion_sin']==""?null:$input['ticket_companion_sin']
						);
						if($input['reservation_type'] == "T&E"){
							$visit_input_data['c_name']=$input['c_name']==""?null:$input['c_name'];
							$visit_input_data['sid']=$input['c_sid']==""?null:$input['c_sid'];
							$visit_input_data['relation_id']=$input['relation_id']==""?null:$input['relation_id'];
							$visit_input_data['address']=$input['c_address']==""?null:$input['c_address'];
							$visit_input_data['job']=$input['c_job']==""?null:$input['c_job'];
							$visit_input_data['room_number']=$input['room_number']==""?null:$input['room_number'];
							$visit_input_data['file_number']=$input['file_number'];
							$visit_input_data['file_type']=$input['file_type'];
							$visit_input_data['cure_type_id']=$input['cure_type_id']==""?null:$input['cure_type_id'];
							$visit_input_data['contract']=$input['contract']==""?null:$input['contract'];
							$visit_input_data['converted_by_doctor']=$input['converted_by_doctor']==""?null:$input['converted_by_doctor'];
							$visit_input_data['reference_doctor_id']=$input['reference_doctor_id']==""?null:$input['reference_doctor_id'];
							
		
							$visit_input_data['entry_date']=$input['entry_date']==""?$input['reg_date']:$input['entry_date'];
							$visit_input_data['entry_time']=$input['entry_time']==""?$input['reg_time']:$input['entry_time'];
						
						}
						$visit=Visit::create($visit_input_data);
						if($input['reservation_type'] == "T&E"){
							// Get the attached department to chosen clinic
							$department_id= MedicalUnit::find($input['medical_id']);
							if(is_null($department_id->parent_department_id)){
									return redirect()->back()
													->withFlashMessage( "يجب أن يكون للعيادة القسم الخاص بها لكي يتم عملية دخول المريض")
													->withMessageType("false")
													->withInput();
							}
							$visit->medicalunits()->attach($department_id->parent_department_id);
						}
						else{

							if(isset($input['all_deps'])){								
								$visit->all_deps=true;
								$visit->save();
								$all_clinics=MedicalUnit::where('type','c')->lists('id');
								$all_clinics=$all_clinics->toArray();
								$visit->medicalunits()->attach($all_clinics);
								
							}
							else{
								$visit->medicalunits()->attach($input['medical_id']);
							}

						}
						DB::commit();
						$request->session()->flash('flash_message', "تم التسجيل بنجاح - كود المريض : $input[id] ");
						$request->session()->flash('vid', $visit->id);
					}
					catch(\Expection $e){

						DB::rollback();
					}
					
				}
			}
		}
		else{

			$messages = [
				'fname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'fname.min' => 'حقل الأسم الأول يجب الأ يقل عن :min حروف',
				'fname.max' => 'حقل الأسم الأول يجب الأ يتعدي :max حرف',
				'fname.alpha' =>'حقل الأسم الأول يجب أن يكون حروف فقط بدون وجود مسافات.',
				'sname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'sname.min' => 'حقل الأسم الثاني يجب الأ يقل عن :min حروف',
				'sname.max' => 'حقل الأسم الثاني يجب الأ يتعدي :max حرف',
				'sname.alpha' =>'حقل الأسم الثاني أن يكون حروف فقط بدون وجود مسافات.',
				'mname.required' => 'هؤلاء الحقول مطلوب الأدخال بشكل كامل.',
				'mname.min' => 'حقل الأسم الثالث يجب الأ يقل عن :min حروف',
				'mname.max' => 'حقل الأسم الثالث يجب الأ يتعدي :max حرف',
				'mname.alpha' =>'حقل الأسم الثالث أن يكون حروف فقط بدون وجود مسافات.',
				'lname.min' => 'حقل الأسم الرابع يجب الأ يقل عن :min حروف',
				'lname.max' => 'حقل الأسم الرابع يجب الأ يتعدي :max حرف',
				'lname.alpha' =>'حقل الأسم الرابع أن يكون حروف فقط بدون وجود مسافات.',
				'serial_number.required' => 'هذا الحقل مطلوب الأدخال.',
				'reg_date.required' => 'هذا الحقل مطلوب الأدخال.',
				'reg_date.date' => 'هذا الحقل يجب أن يكون تاريخ',
				'gender.required' => 'هذا الحقل مطلوب الأدخال.',
				'address.required' => 'هذا الحقل مطلوب الأدخال.',
				'job.required' => 'هذا الحقل مطلوب الأدخال.',
				'sid.sin_format' => 'رقم البطاقة غير صحيح.',
				'sid.unique' => 'رقم البطاقة موجود من قبل.',
				'year_age.numeric' => 'حقل عدد السنين يجب ان يكون رقم فقط.',
				'year_age.required_without_all' => 'حقل عدد السنين يجب أن يكون أكبر من 0 فى حالة عدم وجود عدد أيام أو عدد أشهر',
				'ticket_num.required' => 'هذا الحقل مطلوب الأدخال.',
				'ticket_num.numeric' => 'حقل رقم التذكرة يجب أن يكون رقم فقط .',
				'ticket_num.unique' => 'رقم التذكرة موجود من قبل .',
				'ticket_type.required' => 'هذا الحقل مطلوب الأدخال.',
				'entry.required' => 'هذا الحقل مطلوب الأدخال.',
				'medical_id.required' => 'هذا الحقل مطلوب الأدخال.',
				'reservation_type.required' => 'هذا الحقل مطلوب الأدخال.',
				'sent_by_person.required' => 'هذا الحقل مطلوب الأدخال.',
				'ticket_companion_name.required_with' => 'هذا الحقل مطلوب فى حال وجود رقم البطاقة',
				'ticket_companion_sin.sin_format' => 'رقم البطاقة غير صحيح.',
				'ticket_companion_sin.different' => 'رقم البطاقة يجب ان يكون مختلف عن رقم بطاقة المريض',
			];
			if($input['ticket_status'] == "T")
				$clinic_ticket=Visit::where('ticket_number',$input['ticket_num'])
														->whereNull('ticket_type')
														->first();

			$constraints['fname'] = 'required|alpha|min:2|max:20';
			$constraints['sname'] = 'required|alpha|min:2|max:20';
			$constraints['mname'] = 'required|alpha|min:2|max:20';
			$constraints['lname'] = 'alpha|min:2|max:20';
			$constraints['serial_number'] = 'required';
			$constraints['reg_date'] = 'required|date';
			$constraints['reg_time'] = 'required';
			$constraints['gender'] = 'required';
			$constraints['address'] = 'required';
			$constraints['job'] = 'required';
			$constraints['sid'] = 'sin_format|unique:patients,sid';
			if($input['sid'] == "")
				$constraints['year_age'] = 'numeric|required_without_all:month_age,day_age';
			/* in case of ticket status ("T"=> number, "F"=> free word) is T, user types ticket numbar 
			and the oposite condition is F, user types free word */ 
			if($input['ticket_status'] == "T"){
				if(count($clinic_ticket) > 0)
					$constraints['ticket_num']='required|numeric|unique:visits,ticket_number,'.$clinic_ticket->id;
				else
					$constraints['ticket_num']='required|numeric|unique:visits,ticket_number';
			}
			$constraints['ticket_type']='required';
			$constraints['entry']='required';
			$constraints['medical_id']='required_if:all_deps,false';
			$constraints['reservation_type']='required';
			$constraints['sent_by_person']='required';
			$constraints['ticket_companion_name']='required_with:ticket_companion_sin';
			$constraints['ticket_companion_sin']='sin_format|different:sid';

			if($input['reservation_type'] == "T&E"){

				$messages['c_name.required_with'] = 'هذا الحقل مطلوب فى حال وجود رقم البطاقة';
				$messages['c_name.min']= 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_name.max']= 'هذا الحقل يجب الأ يتعدي :max حرف';
				$messages['c_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_job.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
				$messages['c_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض.';
				$messages['c_sid.sin_format'] = 'رقم البطاقة غير صحيح.';
				$messages['file_number.required'] = 'هذا الحقل مطلوب الأدخال.';
				$messages['file_number.unique'] = 'هذا الرقم موجود من قبل';
				$messages['file_type.required'] = 'هذا الحقل مطلوب الأدخال';
				$messages['entry_date.date'] = 'هذا الحقل يجب أن يكون تاريخ';
				$messages['entry_date.after'] = 'تاريخ الدخول يجب أن يكون أكبر من تاريخ التسجيل.';
				
				$constraints['entry_date']='date|after:reg_date';
				$constraints['c_name']='required_with:c_sid|min:2|max:50';
				$constraints['c_address']='min:2';
				$constraints['c_job']='min:2';
				$constraints['c_sid']='sin_format|different:sid';
				$constraints['file_number']='required|unique:visits,file_number';
				$constraints['file_type']='required';
			}
			$this->validate($request, $constraints ,$messages);

			$input['name']=$input['fname']." ".$input['sname']." ".$input['mname']." ".$input['lname'];
			unset($input['fname']);unset($input['sname']);unset($input['mname']);unset($input['lname']);

			if($input['sid'] == "")
				$birthdate=return_birthdate($input['day_age'],$input['month_age'],$input['year_age']);
			else
				$birthdate=return_birthdate($input['sid']);
			
			$patient_input=
			array(
				'sid'=>$input['sid']==""?null:$input['sid'],
				'name'=>$input['name'],
				'gender'=>$input['gender'],
				'address'=>$input['address'],
				'birthdate'=>$birthdate,
				'job'=>$input['job'],
			);
			
			DB::beginTransaction();

			try{
				$the_last_id=Patient::orderBy('id', 'desc')->first();
				$patient_input['id']=($the_last_id->id==null)?1:$the_last_id->id+1;
				$patient_object=Patient::create($patient_input);
				$pid=$patient_object->id;
				$visit_input_data=
				array(
					'patient_id'=>$pid,
					'serial_number'=>$input['serial_number'],
					'ticket_number'=>$input['ticket_status'] == "T"?$input['ticket_num']:"مجاني",
					'ticket_status'=>$input['ticket_status'],
					'ticket_type'=>$input['ticket_type'],
					'user_id'=>Auth::id(),
					'entry_id'=>$input['entry'],
					'registration_datetime'=>Carbon::parse($input['reg_date']." ".$input['reg_time']),
					'watching_status'=>$input['watching_status']==""?null:$input['watching_status'],
					'sent_by_person'=>$input['sent_by_person'],
					'ticket_companion_name'=>$input['ticket_companion_name']==""?null:$input['ticket_companion_name'],
					'ticket_companion_sin'=>$input['ticket_companion_sin']==""?null:$input['ticket_companion_sin']
				);
				if($input['reservation_type'] == "T&E"){

					
					$visit_input_data['c_name']=$input['c_name']==""?null:$input['c_name'];
					$visit_input_data['sid']=$input['c_sid']==""?null:$input['c_sid'];
					$visit_input_data['relation_id']=$input['relation_id']==""?null:$input['relation_id'];
					$visit_input_data['address']=$input['c_address']==""?null:$input['c_address'];
					$visit_input_data['job']=$input['c_job']==""?null:$input['c_job'];
					$visit_input_data['room_number']=$input['room_number']==""?null:$input['room_number'];
					$visit_input_data['file_number']=$input['file_number'];
					$visit_input_data['file_type']=$input['file_type'];
					$visit_input_data['cure_type_id']=$input['cure_type_id']==""?null:$input['cure_type_id'];
					$visit_input_data['contract']=$input['contract']==""?null:$input['contract'];
					$visit_input_data['converted_by_doctor']=$input['converted_by_doctor']==""?null:$input['converted_by_doctor'];
					$visit_input_data['reference_doctor_id']=$input['reference_doctor_id']==""?null:$input['reference_doctor_id'];
					
					$visit_input_data['entry_date']=$input['entry_date']==""?$input['reg_date']:$input['entry_date'];
					$visit_input_data['entry_time']=$input['entry_time']==""?$input['reg_time']:$input['entry_time'];
				}
				$visit=Visit::create($visit_input_data);
				if($input['reservation_type'] == "T&E"){
					// Get the attached department to chosen clinic
					$clinic_id= MedicalUnit::find($input['medical_id']);
					if(is_null($clinic_id->parent_department_id)){
							return redirect()->back()
											->withFlashMessage( "يجب أن يكون للعيادة القسم الخاص بها لكي يتم عملية دخول المريض")
											->withMessageType("false")
											->withInput();
					}
					$visit->medicalunits()->attach($clinic_id->parent_department_id);
				}
				else{
					if(isset($input['all_deps'])){
						$visit->all_deps=true;
						$visit->save();
						$all_clinics=MedicalUnit::where('type','c')->lists('id');
						$all_clinics=$all_clinics->toArray();
						$visit->medicalunits()->attach($all_clinics);
						
					}
					else{
						$visit->medicalunits()->attach($input['medical_id']);
					}
				}
				
				DB::commit();
				$request->session()->flash('flash_message', "تم التسجيل بنجاح - كود المريض : $pid ");
				$request->session()->flash('vid',$visit->id);
			}
			catch(\Exception $e){
				DB::rollback();
				dd($e);
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "حدثت مشكلة فى أدخال البيانات! حاول مرة أخرى");
			}
		}
		return redirect()->action('PatientController@indexDesk',array('pid'=>-1));

	}
	public function show(Request $request)
	{
		/*$patients= Patient::join('visits','patients.id','=','visits.patient_id')
							->orderBy('visits.id', 'desc')
							->select('patients.id','name','gender','sin','birthdate','patients.created_at','address','new_id','closed')
							->take(20)->get();*/
		$patients= Patient::orderBy('created_at', 'desc')
						->select('patients.id','name','gender','sin','birthdate','patients.created_at','address','new_id','hasOpenVisits')
							->take(20)->get();
							//dd($patients);

		$user=User::find(Auth::id());
		$rolename=$user->role->name;
		return view('show_patients',array('s_active'=>'active','role_name'=>$rolename,'table_header'=>'بيانات المرضي المضافة حديثا','data'=>$patients));
	}
	public function search(Request $request)
	{
		$input=$request->all();
		$patients= Patient::where(function($query) use ($input){
				if($input['name'] != "")
					$query->where('name','like',"%$input[name]%");
				if($input['code'] != "")
					$query->where('new_id','like',"%$input[code]%");
				if($input['sin'] != "")
					$query->where('sin','=',"$input[sin]");
				if($input['birthdate'] != "")
					$query->where('birthdate','like',"$input[birthdate]");
				if($input['address'] != "")
					$query->where('address','like',"%$input[address]%");
		})->orderBy('created_at', 'desc')->select('new_id','name','gender','sin','birthdate','created_at','address','patients.id','hasOpenVisits')->get();
		$user=User::find(Auth::id());
		$rolename=$user->role->name;
		return view('show_patients',array('s_active'=>'active','role_name'=>$rolename,'table_header'=>'بيانات المرضي حسب نتيجة البحث','data'=>$patients));
		/*//$input=$request->all();
		$patients= Patient::where(function($query) use ($input){
				if($input['name'] != "")
					$query->where('name','like',"%$input[name]%");
				if($input['code'] != "")
					$query->where('id','=',"$input[code]");
				if($input['sin'] != "")
					$query->where('sin','=',"$input[sin]");
				if($input['birthdate'] != "")
					$query->where('birthdate','like',"$input[birthdate]");
				if($input['address'] != "")
					$query->where('address','like',"%$input[address]%");
		})->orderBy('created_at', 'desc')->select('new_id','name','gender','sin','birthdate','created_at','address')->get();

		$user=User::find(Auth::id());
		$rolename=$user->role->name;
		return view('show_patients',array('s_active'=>'active','role_name'=>$rolename,'table_header'=>'بيانات المرضي حسب نتيجة البحث','data'=>$patients));
		*/
	}
	public function addvisit($id,$visit_id){
		// Restore session after it expired and user return to log in
		$patient_visit=array();
		$user_id=Auth::id();
		$user=User::find(Auth::id());
		$role_id=$user->role->id;
		$role_name=$user->role->name;
		$medical_visit=array();
		if($visit_id==-1){
			$patient_visit[0]= Patient::find($id);
			//dd($patient_visit);
			$header="إضافة دخول مريض جديد";
		}
		else{
		$patient_visit = Visit::join('patients','patients.id','=','visits.patient_id')
				  ->join('government','government_id','=','patient_government_id')
				  ->where('visits.id','=',$visit_id)
				  ->where('patients.id','=',$id)
				  ->select('patients.id as id','visits.id as vid','patients.name as name','patients.address','patients.job','patients.birthdate','gender','visits.patient_new_id'
				  ,'patients.social_status','patients.phone_num','patients.sin','companion_name','companion_sid','person_relation_id'
				  ,'companion_address','companion_job','companion_phone_num'
				  ,'person_relation_id','person_relation_name','person_relation_phone_num','entry_id',
				  'entry_time','reg_time','entry_date','file_number','contract_id','cure_type_id','file_type'
				  ,'converted_from','entry_reason_desc','checkup','government.government_id as gov_id',
				  'government.name as gov_name','visits.ambulance_number as ambulance_number','visits.paramedic_name as paramedic_name',
				  'visits.kateb_name as kateb_name','visits.doctor_name as doctor_name','visits.ticket_number as ticket_number',
				  'visits.Companion_Ticket_Number as Companion_Ticket_Number','patients.new_id as new_id')
				  ->get();
			//dd($patient_visit);
			//dd($governments);
			$medical_visit = Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
				  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
				  ->leftJoin('rooms','medical_unit_visit.room_id','=','rooms.id')
				  ->join('users','medical_unit_visit.reference_doctor_id','=','users.id')
				  ->where('visits.id','=',$visit_id)
				  ->where('type','d')
				  ->whereNull('convert_to')
				  ->select('rooms.id as room_id','medical_units.id as dept_id','users.id as doctor_name','medical_unit_visit.visit_id as vid')
				  ->get();
				  //dd($medical_visit);
	//	}
/*		else
		{
		$patient_visit = Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
				  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
				  ->leftJoin('rooms','medical_unit_visit.room_id','=','rooms.id')
				  ->join('patients','patients.id','=','visits.patient_id')
				  ->leftJoin('users','users.id','=','visits.reference_doctor_id')
				  ->join('government','government_id','=','patient_government_id')
				  ->where('visits.id','=',$visit_id)
				  ->where('patients.id','=',$id)
				  ->where('type','d')
				  ->whereNull('convert_to')
				  ->select('patients.id as id','visits.id as vid','patients.name as name','patients.address','patients.job','patients.birthdate','gender','visits.patient_new_id'
				  ,'patients.social_status','patients.phone_num','patients.sin','medical_units.id as dept_id','companion_name','companion_sid','person_relation_id'
				  ,'companion_address','companion_job','companion_phone_num'
				  ,'person_relation_id','person_relation_name','person_relation_phone_num','entry_id',
				  'entry_time','reg_time','entry_date','reference_doctor_id','rooms.id as room_id','file_number','contract_id','cure_type_id','file_type'
				  ,'converted_from','entry_reason_desc','checkup','government.government_id as gov_id','government.name as gov_name','visits.ambulance_number as ambulance_number','visits.paramedic_name as paramedic_name','visits.kateb_name as kateb_name','visits.reception_doctor_name as reception_doctor_name','visits.ticket_number as ticket_number','visits.Companion_Ticket_Number as Companion_Ticket_Number')
				  ->get();
		}*/
			$govdata=government::join('patients','government_id','=','patient_government_id')
								->where('patients.id','=',$id)
								->select('government.name as name','government.government_id as government_id')
								->get();
			$header="تحديث بيانات دخول مريض";				
		}
		$relations=Relation::lists('name', 'id');
		$medicalunits=MedicalUnit::where('type','d')->whereNull('parent_department_id')->lists('name', 'id');
		$user=User::find(Auth::id());
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		$governments=government::lists('name','government_id');
		$entrypoint_Array=array();
		foreach($user->entrypoints as $row)
			$entrypoint_Array[$row->id]=$row->name;
		if($visit_id==-1 ||count($medical_visit)==0){
		//dd($patient_visit);
			$medical_unit= MedicalUnit::where('type','=','d')->first();
			//dd($medical_unit);
			$first_department_doctors=$medical_unit->users()->lists('users.name','users.id');
			$first_rooms=$medical_unit->rooms()->lists('rooms.name','rooms.id');
			$govdata=government::join('patients','government_id','=','patient_government_id')
								->where('patients.id','=',$id)
								->select('government.name as name','government.government_id as government_id')
								->get();
			//dd($first_department_doctors);
		}
		elseif(count($medical_visit)>0)
		{
			$medical_unit= MedicalUnit::find($medical_visit[0]->dept_id);
			$first_department_doctors=$medical_unit->users()->lists('users.name','users.id');
			$first_rooms=$medical_unit->rooms()->lists('rooms.name','rooms.id');
		}
	/*	else
		{
			$medical_unit= MedicalUnit::find($medical_visit[0]->dept_id);
			$first_department_doctors=$medical_unit->users()->lists('users.name','users.id');
			$first_rooms=$medical_unit->rooms()->lists('rooms.name','rooms.id');
		}*/
			
	//	}
		$cure_types=CureType::lists('name','id');
		$file_types=FileType::lists('name','id');
		$contracts=Contract::lists('name','id');
		$converted_from=ConvertedFrom::lists('name','id');
		$sub_type_entrypoint=$user->entrypoints()->first()->sub_type;
		//dd($patient_visit);
		if($role_name=="GeneralRecept")
		{
			return view('addvisits',array('s_active'=>'active','header'=>$header,'data'=>$patient_visit,'relations'=>$relations,'medicalunits'=>$medicalunits,
			'entrypoints'=>$entrypoint_Array,'first_department_doctors'=>$first_department_doctors,'first_rooms'=>$first_rooms,'converted_from'=>$converted_from,
			'cure_types'=>$cure_types,'file_types'=>$file_types,'converted_from'=>$converted_from,'contracts'=>$contracts,'sub_type_entrypoint'=>$sub_type_entrypoint,
			'governments'=>$governments,'govdata'=>$govdata,'role_name'=>$role_name,'medical_visit'=>$medical_visit,'visit_id'=>$visit_id));
		}
		//dd($patient_visit);
//		elseif($role_name=="GeneralRecept" & count($medical_visit)==0)
//		{
			return view('addvisits',array('s_active'=>'active','header'=>$header,'data'=>$patient_visit,'relations'=>$relations,'medicalunits'=>$medicalunits,
			'entrypoints'=>$entrypoint_Array,'converted_from'=>$converted_from,'first_rooms'=>$first_rooms,'first_department_doctors'=>$first_department_doctors,
			'cure_types'=>$cure_types,'file_types'=>$file_types,'contracts'=>$contracts,'sub_type_entrypoint'=>$sub_type_entrypoint,
			'governments'=>$governments,'govdata'=>$govdata,'role_name'=>$role_name,'medical_visit'=>$medical_visit));
//		}
/*		elseif($role_name!="GeneralRecept")
		{
			return view('addvisits',array('s_active'=>'active','header'=>$header,'data'=>$patient_visit,'relations'=>$relations,'medicalunits'=>$medicalunits,
			'entrypoints'=>$entrypoint_Array,'first_department_doctors'=>$first_department_doctors,'first_rooms'=>$first_rooms,'converted_from'=>$converted_from,
			'cure_types'=>$cure_types,'file_types'=>$file_types,'contracts'=>$contracts,'sub_type_entrypoint'=>$sub_type_entrypoint,
			'governments'=>$governments,'govdata'=>$govdata,'role_name'=>$role_name));
		}*/
	}
public function storeVisit(Request $request,$pid,$visit_id)
	{ 
	    $input=$request->all();
		//dd($request);
		$user=User::find(Auth::id());
		$role_id=$user->role->id;
		$role_name=$user->role->name;
		$sub_type_entrypoint=$user->entrypoints()->first()->sub_type;
		$messages["person_relation_name.min"]='حقل الأسم الأول يجب الأ يقل عن :min حروف';
		$messages["person_relation_name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
		$messages['person_relation_phone_num.max'] = 'حقل رقم التليفون يجب الأ يتعدي :max رقم';
		$messages['person_relation_phone_num.min'] = 'حقل رقم التليفون يجب الأ يقل عن :min أرقام';
		
		$messages['companion_sid.sin_format'] =  'رقم البطاقة غير صحيح.';
		$messages['companion_sid.different'] = 'حقل رقم بطاقة المرافق يجب أن يكون مختلف عن رقم بطاقة المريض';
		$messages['companion_name.required'] = 'هذا الحقل مطلوب الأدخال';
		$messages['companion_name.min'] ='هذا الحقل يجب الأ يقل عن :min حروف';
		$messages['companion_name.max'] = 'هذا الحقل يجب الأ يتعدي :max حرف';
		$messages['companion_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
		$messages['companion_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
		$messages['companion_address.min'] = 'هذا الحقل يجب الأ يقل عن :min حروف';
		$messages['companion_address.max'] = 'هذا الحقل يجب الأ يتعدي :max حرف';
		$messages['companion_job.min'] = 'حقل المهنة يجب الأ يقل عن :min حروف';
		$messages['companion_phone_num.max'] = 'حقل رقم التليفون يجب الأ يتعدي :max رقم';
		$messages['companion_phone_num.min'] = 'حقل رقم التليفون يجب الأ يقل عن :min أرقام';
		//$messages['contract_id.required'] = 'هذا الحقل مطلوب الأدخال';
		$messages["government_id.required"]='يجب اختيار اسم المحافظة';
		$messages['converted_from.required'] = 'يجب ادخال الجهة المحول منها المريض';
		$messages['entry_reason_desc.required'] = 'يجب ادخال التشخيص المبدئ للمريض';
		/*if($role_name=="GeneralRecept")
					{
						$messages["reception_doctor_name.required"]='يجب ادخال اسم طبيب الاستقبال المعالج';
						$messages["reception_doctor_name.min"]='اسم الطبيب يجب الأيقل عن :min حروف';
						$messages["reception_doctor_name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
						$messages["kateb_name.required"]='هذا الحقل مطلوب الأدخال';
						$messages["ticket_number.required"]='هذا الحقل مطلوب الأدخال';
						$messages["Companion_Ticket_Number.required"]='هذا الحقل مطلوب الأدخال.';
					}*/
		
		
		$constraints['person_relation_name']='min:2|max:255';
		$constraints['person_relation_phone_num']='min:4|max:20';
		$constraints['companion_sid']='sin_format|different:patient_sin';
		$constraints['companion_name']='min:2|max:50';
		$constraints['companion_address']='min:3';
		$constraints['companion_job']='min:4';
		$constraints['companion_phone_num']='min:4|max:20';
		$constraints['converted_from']='required';
		$constraints['entry_reason_desc']='required';
		//$constraints['contract_id']='required';
		/*if($role_name=="GeneralRecept")
					{
						$constraints['reception_doctor_name']='required|min:2|max:255';
						$constraints['kateb_name']='required|min:2|max:255';
						$constraints['ticket_number']='required';
						$constraints['Companion_Ticket_Number']='required';
					}*/
		//dd('asd ok');
		if($input['v_code']==""){
			//add visit case
					$messages['entry_id.required'] = 'هذا الحقل مطلوب الأدخال';
					$messages['entry_date.required'] = 'هذا الحقل مطلوب الأدخال';
					$messages['entry_time.required'] = 'هذا الحقل مطلوب الأدخال';
					$messages['reg_time.required'] = 'هذا الحقل مطلوب الأدخال';
					$constraints['entry_id']='required';		
					$constraints['entry_date']='required';
					$constraints['entry_time']='required';
					$constraints['reg_time']='required';
			
			//$messages["government_id.required"]='يجب اختيار اسم المحافظة';
			//dd($role_name);
			if($role_name=="GeneralRecept")
					{
					
						$messages["doctor_name.required"]='يجب ادخال اسم طبيب الاستقبال المعالج';
						$messages["doctor_name.min"]='اسم الطبيب يجب الأيقل عن :min حروف';
						$messages["doctor_name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
						$messages["kateb_name.required"]='هذا الحقل مطلوب الأدخال';
						$messages["kateb_name.min"]="هذا الحقل يجب الأ يقل عن :min حروف";
						$messages["kateb_name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
						$messages["ticket_number.required"]='هذا الحقل مطلوب الأدخال';
						//$messages["Companion_Ticket_Number.required"]='هذا الحقل مطلوب الأدخال.';
						$constraints['doctor_name']='required|min:2|max:255';
						$constraints['kateb_name']='required|min:2|max:255';
						$constraints['ticket_number']='required';
						//$constraints['Companion_Ticket_Number']='required';
					}
					else
					{
						$messages['medical_id.required'] = 'هذا الحقل مطلوب الأدخال';
						$messages['room_number.required'] = 'هذا الحقل مطلوب الأدخال';
						$constraints['room_number']='required';
						$constraints['medical_id']='required';
					}
				//	dd('asden');
					//$constraints['government_id']='required';
					/*if($role_name=="GeneralRecept")
					{
						$constraints['reception_doctor_name']='required|min:2|max:255';
						$constraints['kateb_name']='required|min:2|max:255';
						$constraints['ticket_number']='required';
						$constraints['Companion_Ticket_Number']='required';
					}*/
		}
		
		// مش بيخش في ال else اللي تحت بيقف في ال if اللي فوق
		////////////////////////////////////////////////////////////////
		else{
			
			// update case
			//dd('asd');
			if($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_and_exit" ||$sub_type_entrypoint=="entry_only"){
				$messages["name.required"]='هذا الحقل مطلوب الأدخال.';
				$messages["name.min"]='حقل الأسم الأول يجب الأ يقل عن :min حروف';
				$messages["name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
				$messages["gender.required"]='هذا الحقل مطلوب الأدخال.';
				$messages['sin.sin_format'] =  'رقم البطاقة غير صحيح.';
				$messages["sin.unique"]='رقم البطاقة موجود من قبل';
				$messages['birthdate.required'] = 'هذا الحقل مطلوب الأدخال.';
				$messages['birthdate.date'] = 'حقل تاريخ الميلاد يجب أن يكون تاريخ';
				$messages['address.required'] = 'هذا الحقل مطلوب الأدخال.';
				$messages['address.min'] = 'حقل العنوان يجب الأ يقل عن :min حروف';
				$messages['phone_num.max'] = 'حقل رقم التليفون يجب الأ يتعدي :max رقم';
				$messages['phone_num.min'] = 'حقل رقم التليفون يجب الأ يقل عن :min أرقام';
				$messages['social_status.min']='حقل الحالة الاجتماعية يجب الأ يقل عن :min حروف';
				$messages['job.min']='حقل المهنة يجب الأ يقل عن :min حروف';
				$messages["government_id.required"]='يجب اختيار اسم المحافظة.';
				$sin='sin';
				$constraints['name']='required|min:8|max:255';
				$constraints['gender']='required';
				$constraints['sin']='sin_format';
				$constraints['sin']='unique:patients,sin,'.$pid;
				//$constraints['sin']='sin_format';
				$constraints['birthdate'] = 'required|date';
				$constraints['address']='required|min:3';
				$constraints['phone_num']='min:3|max:20';
				$constraints['social_status']='min:4';
				$constraints['job']='min:4';
				$constraints['government_id']='required';
				
				if($role_name=="GeneralRecept")
					{
						$messages["doctor_name.required"]='يجب ادخال اسم طبيب الاستقبال المعالج';
						$messages["doctor_name.min"]='اسم الطبيب يجب الأيقل عن :min حروف';
						$messages["doctor_name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
						$messages["kateb_name.required"]='هذا الحقل مطلوب الأدخال';
						$messages["kateb_name.min"]="هذا الحقل يجب الأ يقل عن :min حروف";
						$messages["kateb_name.max"]='حقل الأسم الأول يجب الأ يتعدي :max حرف';
						$messages["ticket_number.required"]='هذا الحقل مطلوب الأدخال';
						//$messages["Companion_Ticket_Number.required"]='هذا الحقل مطلوب الأدخال.';
						$constraints['doctor_name']='required|min:2|max:255';
						$constraints['kateb_name']='required|min:2|max:255';
						$constraints['ticket_number']='required';
						//$constraints['Companion_Ticket_Number']='required';
					}
				//dd($message)
			}
		}
		
		//$this->validate($request,$constraints,$messages);
		//dd('asd');
		//$room=Room::find($input['room_number']);
		$input['patient_id']=$input['code'];
		#region  //select new_id
		$patient_visit = patient::join('visits','patients.id','=','visits.patient_id')
				  ->where('patients.id','=',$pid)
				  ->select('patients.new_id')
				  ->get();
		//dd($patient_visit);		  
		$new_id=$patient_visit[0]->new_id;
				// dd($patient_visit);
				//dd($input['v_code']);
		#end
		// This condition for updating inpatient visit data which patient has already existed
		// Else we will insert new visit data with current patient data
		if($input['v_code']!="")
		{
		//dd('hellooooooooo');
			if(($role_name!="GeneralRecept")&&($sub_type_entrypoint == "update_only" || $sub_type_entrypoint =='entry_only'))
			{
			
				$new_room=Room::find($input['room_number']);
				
			}
		/*else 
		{
			$new_room=Room::find($input['old_room_number']);
		}*/
			//dd($input);
			DB::beginTransaction();
			try{
			if($sub_type_entrypoint !='entry_only')
			{
				if($role_name=="Private")
				{
					if(!$new_room->where('number_of_vacancy_beds','<',$new_room->number_of_beds-1)->exists())
					{
						return redirect()->back()->withFlashMessage('لا يوجد سرير شاغر لعمل دخول مريض')
												 ->withMessageType('false');
					}
				}
				$medicalunit = MedicalUnit::find($input['medical_id']);
				
				$medicalunit->visits()->orderBy('created_at','desc')->updateExistingPivot($input['v_code'],array('room_id'=>null));
				//dd($input['old_room_number']);
				if($role_name=="Private")
				{
					Room::find($input['room_number'])->decrement('number_of_vacancy_beds');
					Room::find($input['old_room_number'])->increment('number_of_vacancy_beds');
					//dd($role_name);
				}
				
			}
				if($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_and_exit"||$sub_type_entrypoint=="entry_only"){
					$patient_input=
					array(
						'sin'=>$input['sin'],
						'name'=>$input['name'],
						'gender'=>$input['gender'],
						'address'=>$input['address'],
						'birthdate'=>$input['birthdate'],
						'phone_num'=>$input['phone_num'],
						'social_status'=>$input['social_status']==""?null:$input['social_status'],
						'job'=>$input['job']==""?null:$input['job'],
						'social_status'=>$input['social_status']==""?null:$input['social_status'],
						'patient_government_id'=>$input['government_id']==""?null:$input['government_id'],
					);
					Patient::find($input['code'])->update($patient_input);
					//Patient::find($input['code'])->update($patient_input);
					/*if($input['medical_id']!="")
					{
					$visit=Visit::find($input['v_code']);
					$medicalunit = $input['medical_id'];
					$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>$input['room_number'])));
					/*$medical_visit_input=array('visit_id'=>$input['v_code'],'medical_unit_id'=>$input['medical_id'],'room_id'=>$input['room_id'],
					'user_id'=>$input['reference_doctor_id']);
		     		medical_unit_visit::create($medical_visit_input);
					$medical_unit_visit->save();*/
					//}
					/*$request->session()->flash('message_type', "true");
					$request->session()->flash('flash_message', "تم تحديث الملف بالنجاح");
					DB::commit();
					return redirect()->action('PatientController@addvisit',array('id'=>$input['patient_id'],'visit_id'=>$input['v_code']));*/
				}
				elseif($sub_type_entrypoint=="entry_only")
				{
					/*$patient_input=
					array(
						'sin'=>$input['patient_sin']==""?null:$input['patient_sin'],
						'name'=>$input['name'],
						'gender'=>$input['gender'],
						'address'=>$input['address'],
						'birthdate'=>$input['birthdate'],
						'phone_num'=>$input['phone_num'],
						'social_status'=>$input['social_status']==""?null:$input['social_status'],
						'job'=>$input['job']==""?null:$input['job'],
						'social_status'=>$input['social_status']==""?null:$input['social_status'],
						'patient_government_id'=>$input['government_id']==""?null:$input['government_id'],
					);*/
					/*Patient::find($input['code'])->update($patient_input);
					if($input['medical_id']!="")
					{
					$visit=Visit::find($input['v_code']);
					$medicalunit = $input['medical_id'];
					$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>$input['room_number'])));
					/*$medical_visit_input=array('visit_id'=>$input['v_code'],'medical_unit_id'=>$input['medical_id'],'room_id'=>$input['room_id'],
					'user_id'=>$input['reference_doctor_id']);
		     		medical_unit_visit::create($medical_visit_input);
					$medical_unit_visit->save();*/
					/*}
					$request->session()->flash('message_type', "true");
					$request->session()->flash('flash_message', "تم تحديث الملف بالنجاح");
					DB::commit();
					return redirect()->action('PatientController@addvisit',array('id'=>$input['patient_id'],'visit_id'=>$input['v_code']));*/
				}
				//$new_room=Room::find($input['room_number']);
				if($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_and_exit"||$sub_type_entrypoint=="entry_only")
				{
					$visit=Visit::find($input['v_code']);
					$visit->companion_name=$input['companion_name']==""?null:$input['companion_name'];$visit->companion_sid=$input['companion_sid']==""?null:$input['companion_sid'];
					$visit->companion_address=$input['companion_address']==""?null:$input['companion_address'];$visit->companion_job=$input['companion_job']==""?null:$input['companion_job'];
					$visit->companion_phone_num=$input['companion_phone_num']==""?null:$input['companion_phone_num'];
					$visit->person_relation_name=$input['person_relation_name'];$visit->person_relation_phone_num=$input['person_relation_phone_num'];$visit->person_relation_id=$input['person_relation_id']==""?null:$input['person_relation_id'];
					$visit->converted_from=$input['converted_from']==""?null:$input['converted_from'];
					$visit->checkup=isset($input['checkup']);
					$visit->entry_reason_desc=$input['entry_reason_desc']==""?null:$input['entry_reason_desc'];
					//$visit->file_number=$input['file_number']==""?null:$input['file_number'];
					$visit->file_type=$input['file_type'];
					$visit->cure_type_id=$input['cure_type_id']==""?null:$input['cure_type_id'];
					$visit->contract_id=$input['contract_id']==""?null:$input['contract_id'];
					
				if($role_name!="GeneralRecept")
				{
				//  $visit->reference_doctor_id=$input['reference_doctor_id']==""?null:$input['reference_doctor_id'];
				}
				if(($role_name=="GeneralRecept")&&($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_and_exit"||$sub_type_entrypoint=="entry_only"))
				{
					$visit->paramedic_name==""?null:$input['paramedic_name'];
					$visit->ambulance_number=$input['ambulance_number']==""?null:$input['ambulance_number'];
					$visit->ticket_number=$input['ticket_number'];
					$visit->kateb_name=$input['kateb_name'];
					$visit->doctor_name=$input['doctor_name'];
					$visit->Companion_Ticket_Number=$input['Companion_Ticket_Number'];
				}
				//$visit->patient_new_id=$patient_visit['new_id']==""?null:$patient_visit['new_id'];
				/*
				if($visit->exit_date == null){
					$visit->entry_date=$input['entry_date'];
				}
				else{
					$diff=Carbon::parse($input['entry_date'])->diff(Carbon::parse($visit->exit_date));
					if($diff->invert == 1){
						return redirect()->back()
									 ->withErrors(['entry_date' => ' لا يمكن أن يكون تاريخ الدخول أكبر من تاريخ تسجيل خروج المريض '])
									 ->withInput();
					}
					$visit->entry_date=$input['entry_date'];
				}
				$visit->entry_time=$input['entry_time'];
				*/
				//Visit::find($input['v_code'])->update($visit);
				//	dd($visit);
				//$medicalunit->visits()->orderBy('created_at','desc')->updateExistingPivot($input['v_code'],array('room_id'=>$input['room_number']));
				if(($role_name!="GeneralRecept")&&($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_and_exit"||$sub_type_entrypoint=="entry_only"))
				{
					//dd($role_name);
					DB::update('update medical_unit_visit set medical_unit_id = ?,room_id= ? ,reference_doctor_id= ? where visit_id = ?',[$input['medical_id'],$input['room_number'],$input['reference_doctor_id'],$input['v_code']]);
					if($role_name=="Private")
						{
							Room::find($input['room_number'])->decrement('number_of_vacancy_beds');
							Room::find($input['old_room_number'])->increment('number_of_vacancy_beds');
							//dd($role_name);
						}
				}
				
				$visit->save();
				}
					$request->session()->flash('message_type', "true");
					$request->session()->flash('flash_message', "تم تحديث الملف بالنجاح");
					DB::commit();
			}
			 catch(\Exception $e){
				 dd($e);
				 $request->session()->flash('message_type', "false");
				 $request->session()->flash('flash_message', "حدث خطأ حاول مرة اخرى");
				 DB::rollback();
			}
			//dd('asd');
			return redirect()->action('PatientController@addvisit',array('id'=>$input['patient_id'],'visit_id'=>$input['v_code']));
		}
		else{
			// check if a patient is already exist ( visit closed flag is false ) in the same or another clinic where he/she reserve.
			
			if($input['patient_id'] != "" && $this->checkIfPatientIsExistTodayInClinic($input['patient_id']))
			{
			
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا المريض موجود فى عيادة و لم يتم إنهاء زيارته");
			}
			// check if a patient is already exist in a department.
			
			else 
			{
			//dd($this->checkIfPatientIsExistInDepartment($input['patient_id']));
			if($input['patient_id'] != "" && $this->checkIfPatientIsExistInDepartment($input['patient_id']))
			{
			//dd('helllllllllllllllo');
				//dd($this->checkIfPatientIsExistInDepartment($input['patient_id']));
				$request->session()->flash('message_type', "false");
				$request->session()->flash('flash_message', "هذا المريض موجود فى القسم الداخلي و لم يتم اخراجه");
			}
			else{
				if($role_name=="Private")
				{
					$new_room=Room::find($input['room_number']);
					DB::beginTransaction();
						if($new_room->where('number_of_vacancy_beds','<',$new_room->number_of_beds-1)->exists())
							{
								//dd('asd');
								return redirect()->back()->withFlashMessage('لا يوجد سرير شاغر لعمل دخول مريض')
														 ->withMessageType('false');
							}
				}
				try{
					/*if(!$new_room->where('number_of_vacancy_beds','<',$new_room->number_of_beds-1)->exists())
					{
						return redirect()->back()->withFlashMessage('لا يوجد سرير شاغر لعمل دخول مريض')
												 ->withMessageType('false');
					}*/
				
					
					$patient=Patient::find($input['patient_id']);
					$patient->hasOpenVisits=1;
					$patient->save();
					//$patient->address=$input['address'];
					//$patient->phone_num=$input['phone_num'];
					//$patient->save();
						$patient_visit = patient::join('visits','patients.id','=','visits.patient_id')
					  ->where('patients.id','=',$pid)
					  ->select('patients.new_id')
					  ->get();
					  $new_id=$patient_visit[0]->new_id;
				  	//dd($patient_visit);
					$visit=new Visit;
					$visit->user_id=Auth::id();
					$visit->entry_id=$input['entry_id']; $visit->patient_id=$input['patient_id'];
					$visit->companion_name=$input['companion_name']==""?null:$input['companion_name'];$visit->companion_sid=$input['companion_sid']==""?null:$input['companion_sid'];
					$visit->companion_address=$input['companion_address']==""?null:$input['companion_address'];$visit->companion_job=$input['companion_job']==""?null:$input['companion_job'];
					$visit->companion_phone_num=$input['companion_phone_num']==""?null:$input['companion_phone_num'];
					$visit->person_relation_name=$input['person_relation_name'];$visit->person_relation_phone_num=$input['person_relation_phone_num'];$visit->person_relation_id=$input['person_relation_id']==""?null:$input['person_relation_id'];
					$visit->entry_date=$input['entry_date']; $visit->entry_time=$input['entry_time']; $visit->reg_time=$input['reg_time'];
					$visit->converted_from=$input['converted_from']==""?null:$input['converted_from'];
					$visit->checkup=isset($input['checkup']);
					$visit->entry_reason_desc=$input['entry_reason_desc']==""?null:$input['entry_reason_desc'];
					$visit->file_number=$input['file_number']==""?null:$input['file_number'];
					$visit->file_type=$input['file_type'];
					$visit->cure_type_id=$input['cure_type_id']==""?null:$input['cure_type_id'];
					$visit->contract_id=$input['contract_id']==""?null:$input['contract_id'];
					$visit->ticket_number=$input['ticket_number'];
					//$visit->reference_doctor_id=$input['reference_doctor_id']==""?null:$input['reference_doctor_id'];
					//$visit=Visit::find($input['v_code']);
					if($role_name=="GeneralRecept")
					{
						$visit->doctor_name=$input['doctor_name'];
						$visit->Companion_Ticket_Number=$input['Companion_Ticket_Number'];
					}
					//dd($new_id);
					//$visit->patient_new_id=$new_id==""?null:$new_id;
					$visit->patient_new_id=$new_id;
					//echo $patient_visit['new_id'];
					$visit->save();
					if($role_name=="GeneralRecept")
					{
						//$new_room=Room::find($input['room_number']);
						$medicalunit = 26;
						$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>110,'reference_doctor_id'=>20107,'user_id'=>$user->id)));
						//$new_room->decrement('number_of_vacancy_beds');
						//dd($new_room);
					}
					else if($role_name=="Injuires")
					{
						$new_room=Room::find($input['room_number']);
						$medicalunit = 22;
						$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>99,'reference_doctor_id'=>20107,'user_id'=>$user->id)));
						$new_room->decrement('number_of_vacancy_beds');
					}
					else
					{
						$new_room=Room::find($input['room_number']);
						$medicalunit = $input['medical_id'];
						$visit->medicalunits()->attach(array($medicalunit=>array('room_id'=>$input['room_number'],'reference_doctor_id'=>$input['reference_doctor_id'],'user_id'=>$user->id)));
						$new_room->decrement('number_of_vacancy_beds');
					}

					$request->session()->flash('flash_message', "تم التسجيل بالنجاح");
					$request->session()->flash('vid', $visit->id);
					$request->session()->flash('id', $input['patient_id']);
					DB::commit();
				}
				catch(\Exception $e){
				    dd($e);
					$request->session()->flash('message_type', "false");
					$request->session()->flash('flash_message', "حدث خطأ حاول مرة اخرى");
					DB::rollback();
				}
			}
			}
			//dd('asd');
			return redirect()->action('PatientController@addvisit',array('id'=>$input['patient_id'],'visit_id'=>-1));
		}

	}
	// check if a patient is already exist ( visit closed flag is false ) in the same or another clinic where he/she reserve.
	public function checkIfPatientIsExistTodayInClinic($pid,$mid='',$all_deps=false,$reg_date='')
	{
		if($mid=="")
			$patient_visits=Patient::find($pid)
									 ->visits()
									 ->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
									 ->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
									 ->where('type','c')
									 ->whereDate('visits.created_at','=',date('Y-m-d',time()))
									 ->where('closed',false)
									 ->where('cancelled',false)
									 ->count();
		else
			$patient_visits=Patient::find($pid)
									 ->visits()
									 ->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
									 ->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
									 ->where('type','c')
									 ->where(function($query) use($mid,$all_deps,$reg_date){
										if(!$all_deps){
											$query->where('medical_units.id',$mid);
											if($reg_date=='')
												$query->whereDate('visits.created_at','=',date('Y-m-d',time()));
											else
												$query->whereDate('visits.registration_datetime','=',$reg_date);
										}
										else{
											$query->whereDate('visits.registration_datetime','=',$reg_date);
										}
											
									 })
									 ->where('closed',false)
									 ->where('cancelled',false)
									 ->count();
		if($patient_visits > 0)
			return true;
		return false;
	}
	// check if a patient is already exist in a department.
	public function checkIfPatientIsExistInDepartment($pid)
	{
		$patient_visits=Patient::find($pid)
							   ->visits()
							   //->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
							   //->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
							   //->where('type','d')
							   ->where('closed','=',0)
							   ->count();
		if($patient_visits > 0)
			return true;
		return false;
	}
	public function showvisits($eid,Request $request)
	{
		$user_row=User::find(Auth::id());
		$role_name=$user_row->role->name;
		$medical_units=MedicalUnit::lists('name', 'id');
		$desks=Entrypoint::where('type',3)->get();
		$header="بيانات حجز كشف المرضي المضافة حديثا خلال شهر";
		$medical_type="c";
		$sub_type_entrypoint=$user_row->entrypoints()->first()->sub_type;
		//dd($sub_type_entrypoint);
		$visits = Visit::with(array('patient','medicalunits'=>function($query){
							$query->orderBy('pivot_created_at','asc');	
						}))
						->whereHas('medicalunits',function($query) use($medical_type){
							$query->where('type',$medical_type);
						})
						->where('cancelled',false)
						->where(function($query) use($eid){
							$query->where('entry_id',$eid)
								->orWhere('convert_to_entry_id',$eid);
						})
						->whereBetween('visits.created_at',[Carbon::today()->subMonth()->format('Y-m-d'),Carbon::tomorrow()->format('Y-m-d')])
						->orderBy('id','desc')
						->orderBy('created_at','desc')
						->get();
						//dd('asd');
		return view('show_visits',array('sv_active'=>'active','table_header'=>$header,'data'=>$visits,'medicalunits'=>$medical_units,'role_name'=>$role_name,'desks'=>$desks,'sub_type_entrypoint'=>$sub_type_entrypoint));
	}
	public function showinpatientvisits()
	{
		$user=User::find(Auth::id());
		$user_id=Auth::id();
		$table_header="بيانات مرضي الدخول المضافة حديثا";
		$medical_type="d";
		$ip_active='active';
		$role_name=$user->role->name;
		$role_id=$user->role->id;
		//dd($role_id);
		$sub_type_entrypoint=$user->entrypoints()->first()->sub_type;
		//dd($sub_type_entrypoint);
		// This condition for reception who inserting inpatient data with his/her ticket in clinic reservation
		if($role_name != 'Entrypoint')
			$ticket_and_entry='true';
		
		$departments=MedicalUnit::where('type','d')->whereNull('parent_department_id')->lists('name', 'id');
		$first_medical_unit= MedicalUnit::where('type','d')->whereNull('parent_department_id')->first();
		$first_rooms=$first_medical_unit->rooms()->lists('rooms.name','rooms.id');
		//dd($first_rooms);
		$cure_types=CureType::lists('name', 'id');
		/*$medical_unit_visit_data=Visit::join('medical_unit_visit','visits.id','=','medical_unit_visit.visit_id')
										->join('medicalunits','medicalunits.id','=','medical_unit_visit.medical_unit_id')
										->join(''
									    ->orderBy('pivot_created_at', 'desc');*/
		//dd($medical_unit_visit_data);
		if($role_name=='Entrypoint')
		{
			//dd($role_name);
		$data= Visit::with(array('medicalunits.rooms','contract','exit_status','cure_type','patient','user','medicalunits'=>function($query){
							$query->orderBy('pivot_created_at', 'desc');
						}))
						->whereHas('medicalunits',function($query) use($medical_type){
							$query->where('type',$medical_type);
						})  
						->whereHas('user',function($query) use($role_id){
								$query->whereBetween('role_id',[4,8]);
						})
						->where('cancelled',false)
						->orderBy('visits.id', 'desc')
						->take(10)->get();	
						//dd($data);
		}
		else{
			$data= Visit::with(array('medicalunits.rooms','contract','exit_status','cure_type','patient','user','medicalunits'=>function($query){
							$query->orderBy('pivot_created_at', 'desc');
						}))
						->whereHas('medicalunits',function($query) use($medical_type){
							$query->where('type',$medical_type);
						})  
						->whereHas('user',function($query) use($role_id){
								$query->where('role_id',$role_id);
						})
						->where('cancelled',false)
						->orderBy('visits.id', 'desc')
						->take(10)->get();	
						//dd($data);
		}
		/*	$entr_role=Visit::join('users','users.id','=','visits.user_id')
						->join('roles','roles.id','=','users.role_id')
						->where('visits.id','=',$vid)
						->select('roles.name as enter_role_name')
						->get();  */
		return view('show_visits',compact('ip_active','table_header','data','role_name','ticket_and_entry','sub_type_entrypoint','departments','cure_types','first_rooms','role_id'));
	}
	public function showinpatientvisits_search(Request $request)
	{
		$input=$request->all();
		//dd($input);
		$user=User::find(Auth::id());
		$table_header="بيانات مرضي الدخول حسب نتائج البحث";
		$medical_type="d";
		$ip_active='active';
		$role_name=$user->role->name;
		$role_id=$user->role->id;
		$sub_type_entrypoint=$user->entrypoints()->first()->sub_type;
		// This condition for reception who inserting inpatient data with his/her ticket in clinic reservation
		if($role_name != 'Entrypoint')
			$ticket_and_entry='true';
		$departments=MedicalUnit::where('type','d')->lists('name', 'id');
		$first_medical_unit= MedicalUnit::where('type','d')->whereNull('parent_department_id')->first();
		$first_rooms=$first_medical_unit->rooms()->lists('rooms.name','rooms.id');
		$cure_types=CureType::lists('name', 'id');
		if($role_name=='Entrypoint')
		{
		$data= Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
						->with(array('medicalunits.rooms','contract','exit_status','cure_type','patient','user','medicalunits'=>function($query){
							$query->orderBy('pivot_created_at', 'desc');
						}))
						->whereHas('user',function($query) use($role_id){
								$query->whereBetween('role_id',[4,8]);
						})
						->where('cancelled',false)
						->where(function($query) use($input){
							if($input['fromdate'] != "" && $input['todate'] != "")
								$query->whereBetween('medical_unit_visit.conversion_date',[$input['fromdate'],$input['todate']]);
							if($input['cure_type'] != "")
								$query->where('cure_type_id',$input['cure_type']);
						})
						->where(function($query) use($input){
							if($input['department'] != "")
							$query->where('medical_unit_visit.medical_unit_id',$input['department']);})
						/*->whereHas('medicalunits',function($query) use($medical_type,$input){
							$query->where('type',$medical_type);
							if($input['department'] != "")
								$query->where('id',$input['department']);*/	
						//})
						->whereHas('patient',function($query) use($input){
							if($input['name'] != "")
								$query->where('name','like',"%$input[name]%");
							if($input['code'] != "")
								$query->where('new_id','like',"%$input[code]%");
							if($input['address'] != "")
								$query->where('address','like',"%$input[address]%");
						})->whereNull('medical_unit_visit.convert_to')
						->orderBy('visits.id', 'desc')
						//->take(6)
						->get();
		//dd($data);				
		}
		else
		{
			$data= Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
						->with(array('medicalunits.rooms','contract','exit_status','cure_type','patient','user','medicalunits'=>function($query){
							$query->orderBy('pivot_created_at', 'desc');
						}))
						->whereHas('user',function($query) use($role_id){
								$query->where('role_id',$role_id);
						})
						->where('cancelled',false)
						->where(function($query) use($input){
							if($input['fromdate'] != "" && $input['todate'] != "")
								$query->whereBetween('medical_unit_visit.conversion_date',[$input['fromdate'],$input['todate']]);
							if($input['cure_type'] != "")
								$query->where('cure_type_id',$input['cure_type']);
						})->where(function($query) use($input){
							if($input['department'] != "")
							$query->where('medical_unit_visit.medical_unit_id',$input['department']);})
					/*	->whereHas('medicalunits',function($query) use($medical_type,$input){
							$query->where('type',$medical_type);
							if($input['department'] != "")
								$query->where('id',$input['department']);	
						})*/
						->whereHas('patient',function($query) use($input){
							if($input['name'] != "")
								$query->where('name','like',"%$input[name]%");
							if($input['code'] != "")
								$query->where('new_id','like',"%$input[code]%");
							if($input['address'] != "")
								$query->where('address','like',"%$input[address]%");
						})->whereNull('medical_unit_visit.convert_to')
						->orderBy('visits.id', 'desc')
						//->take(100)
						->get();	
			
		}
		
		//dd($data);
		return view('show_visits',compact('ip_active','table_header','data','role_name','ticket_and_entry','sub_type_entrypoint','departments','cure_types','first_rooms'));
	}
	public function convert_to_another_department(Request $request)
	{
		$input=$request->all();
		$messages['department.required'] = 'هذا الحقل مطلوب الأدخال';
		$messages['department.different'] = 'هذا المريض متواجد فى هذا القسم';

		$constraints['department']='required|different:current_dep';

		$this->validate($request,$constraints,$messages);
		
		$visit=Visit::find($request->v_id);
		$fromRoom=Room::find($request->current_room);
		$toRoom=Room::find($request->room_number);
		
		$toDep=$request->department;
		$fromDep=MedicalUnit::find($request->current_dep);
		DB::beginTransaction();
		try{
			$fromDep->visits()->orderBy('created_at','desc')->updateExistingPivot($request->v_id,array('convert_to'=>$toDep));
			$fromRoom->decrement('number_of_vacancy_beds');
			
			$visit->medicalunits()->attach(array($toDep=>array('room_id'=>$request->room_number,'user_id'=>Auth::id())));
			$toRoom->increment('number_of_vacancy_beds');
			
			DB::commit();
			return redirect()->back()->withSuccess("تم التحويل بالنجاح");
		}
		catch(\Exception $e){
			DB::rollBack();
			return redirect()->back()->withError('حدثت مشكلة مرة أخرى!!');
		}
	}
	public function exitvisit($id,$visit_id)
	{
		$user=User::find(Auth::id());
		$role_name=$user->role->name;
		$medical_visit=Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
				  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
				  ->leftJoin('users','users.id','=','medical_unit_visit.reference_doctor_id')
				  ->leftJoin('rooms','rooms.id','=','medical_unit_visit.room_id')
				  ->where('visits.id','=',$visit_id)
				  ->whereNull('medical_unit_visit.convert_to')
				  ->where('type','=','d')
				  ->select('medical_units.name as mname','rooms.id as room_id','users.name as uname')
				  ->get();
				 // dd($medical_visit);
				$patient_data=Visit::join('patients','patients.id','=','visits.patient_id')
				  ->where('visits.id','=',$visit_id)
				  ->where('patients.id','=',$id)
				  ->select('patients.id as id','patients.new_id as new_id','patients.sin as sin','visits.id as vid','patients.name as name',
				  'entry_date','exit_date','final_diagnosis','exit_status_id','doctor_recommendation','doctor_name')
				  ->get();
		
				//  dd($patient_data);
		/*$patient_data=Visit::join('medical_unit_visit', 'visits.id', '=', 'medical_unit_visit.visit_id')
				  ->join('medical_units','medical_unit_visit.medical_unit_id','=','medical_units.id')
				  ->join('patients','patients.id','=','visits.patient_id')
				  ->leftJoin('users','users.id','=','visits.reference_doctor_id')
				  ->leftJoin('rooms','rooms.id','=','medical_unit_visit.room_id')
				  ->where('visits.id','=',$visit_id)
				  ->where('patients.id','=',$id)
				  ->where('type','=','d')
				  ->whereNull('medical_unit_visit.convert_to')
				  ->select('patients.id as id','patients.new_id as new_id','patients.sin as sin','visits.id as vid','patients.name as name','medical_units.name as mname','users.name as uname',
				  'entry_date','exit_date','final_diagnosis','exit_status_id','doctor_recommendation','rooms.id as room_id')
				  ->get();*/
				 // dd($visit_id);
		$exist_status=ExistStatus::lists('name','id');
		return view('exit_patient',array('s_active'=>'active','data'=>$patient_data,'exist_status'=>$exist_status,'medical_visit'=>$medical_visit,'role_name'=>$role_name));
	}
	public function storeexitvisit($id,$visit_id,Request $request)
	{
		$input=$request->all();
		$messages['final_diagnosis.required'] = 'هذا الحقل مطلوب الأدخال';
		$messages['exit_status_id.required'] = 'هذا الحقل مطلوب الأدخال';
		$messages['exit_time.required'] ='هذا الحقل مطلوب الأدخال';
		$messages['exit_time.date'] = 'هذا الحقل يجب أن يكون تاريخ فقط';

		$constraints['final_diagnosis']='required';
		$constraints['exit_status_id']='required';
		$constraints['exit_time']='required|date';

		$this->validate($request,$constraints,$messages);

		$visit_data=Visit::find($visit_id);
		$patient_data=Patient::find($id);
		
		$entry_date=Carbon::parse($visit_data->entry_date);
		$exit_date=Carbon::parse($input['exit_time']);
		//dd($entry_date);
		if($entry_date->diffInDays($exit_date,false) < 0){
			return redirect()->back()->withErrors(array('exit_time'=>'لا يمكن أن يكون تاريخ الخروج أقل من تاريخ الدخول'))
									 ->withInput();
		}
		DB::beginTransaction();
		try{
			$visit_data->exit_status_id=$input['exit_status_id'];
			$visit_data->exit_date=$input['exit_time'];
			$visit_data->final_diagnosis=$input['final_diagnosis'];
			$visit_data->doctor_recommendation=$input['doctor_recommendation'];
			$visit_data->exit_user_id=Auth::id();
			$visit_data->closed=1;
			$visit_data->out_patient=0;
			$visit_data->save();
			if((Room::find($input['room_id']))&& ($role_name=="Private") )
			{
				Room::find($input['room_id'])->increment('number_of_vacancy_beds');
			}
			$patient_data->hasOpenVisits=0;
			$patient_data->save();
			DB::commit();
			return redirect()->action('PatientController@showinpatientvisits')->withSuccess('تمت العملية بالنجاح ');
			
		}
		catch(\Exception $e)
		{
			DB::rollBack();
			return redirect()->back()->withFlashMessage('حدثت مشكلة حاول مرة أخرى')
									 ->withMessageType('false');
		}
		
	}
	public function checkexistSID(Request $request){
		$patients= Patient::where('sin',$request->get('sin'))->select('id','name','gender','birthdate','address','job','social_status','phone_num')->get();
		if(count($patients) > 0){
			if($request->get('checkflag') == "true")
				return response()->json(['success' => 'false','code'=>$patients[0]['id']]);
			else
				return response()->json(['success' => 'true','data'=>$patients]);
		}
		else{
			if($request->get('checkflag') == "true")
				return response()->json(['success' => 'true']);
			else
				return response()->json(['success' => 'false']);
		}
	}
	public function checkexistPID(Request $request){
		$patients= Patient::where('id','!=',$request->get('pid'))->select('id','sin','name','birthdate','gender','address','job','social_status','phone_num')->get();
		if(count($patients) > 0)
			return response()->json(['success' => 'true','data'=>$patients]);
		else
			return response()->json(['success' => 'false']);
	}
	public function checkExistName(Request $request){
		$patients= Patient::whereHas('visits',function($query){
								$query->whereDate('created_at','=',Carbon::today()->format('Y-m-d'));
							})
							->where('name','like','%'.$request->get('name').'%')
							->select('id','name','address')
							->get();
		if(count($patients) > 0)
			return response()->json(['success' => 'true','patients'=>$patients]);
		else
			return response()->json(['success' => 'false']);
	}
	//=======================================================================================================
	public function showdetails($pid,$vid)
	{
		$data=Visit::with(array('medicalunits.rooms','contract','exit_status','cure_type','patient','user','medicalunits'=>function($query){
							$query->orderBy('pivot_created_at', 'desc');
						}))
		//with(array('contract','exit_status','cure_type','converted_from_relation','patient'))
				/*		->whereHas('medicalunits',function($query) use($medical_type){
							$query->where('type',$medical_type);
						})  */
				/*		->whereHas('user',function($query) use($role_id){
								$query->whereBetween('role_id',[4,8]);
						})*/
						//->join('government','government.government_id','=','patient_government_id')
						->where('visits.id','=',$vid)
				//		->where('patients.id','=',$pid)
						->first();
		//dd($data);
		$government = Patient::find($data->patient->id)->government()->first()->name;
		$doctors=array();
		foreach($data->medicalunits as $med_visit)
		{
			$doctor_name=DB::table('users')
							->where('id','=',$med_visit->pivot->reference_doctor_id)
							->select('name')
							->get();
			$doctors[]=$doctor_name;
			//dd($doctors);							
		}
		//dd($doctors);
	/*	$data=DB::table('patients')->join('visits','patients.id','=','visits.patient_id')
							//->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
							->join('contracts','contracts.id','=','visits.contract_id')
							->join('cure_types','cure_types.id','=','visits.cure_type_id')
							->where('patients.id','=',$pid)
							->where('visits.id','=',$vid)
							->select('patients.name as pname','')
							->get();*/
							//dd($data);
	/*	$med_visit=Visit::
					join('patients','visits.patient_id','=','patients.id')
					->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
					->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
					->join('rooms','rooms.id','=','medical_unit_visit.room_id')
					->join('users','users.id','=','medical_unit_visit.user_id')
					->where('visits.id','=',$vid)
					->get();*/
/*		$med_user=Visit::join('medical_unit_visit','medical_unit_visit.visit_id','visits.id.id')
								->join('users','users.id','medical_unit_visit.user_id')
								->where('medical_unit_visit.visit_id','=',$vid)
								->get();   */
		//dd($med_visit);
		//$med_user=DB::table('')
		//dd($med_user);
		return view('show_details',compact('data','doctors','government'));
		
	}
	//=======================================================================================================
	//written by eng. doaa 
	//modified by michael 
	public function deptstatindex()
	{
		$user=User::find(Auth::id());
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		$entrypoint_Array=array();
		foreach($user->entrypoints as $row)
		$entrypoint_Array[$row->id]=$row->name;
		//$entrypoint_Array[$row->id]=$row->name;
		$relations= Relation::lists('name','id');
		$depts=DB::table('medical_units')->select(array('id', 'name'))->get();
		$deptstat_active="active";
		//dd($depts);
		return view('selectdepartments',array('relations'=>$relations,'entrypoints'=>$entrypoint_Array,'data'=>$depts ,'deptstat_active'=>$deptstat_active));
		//return view('selectdepartments');
	}
//===========================================================================================================
	public function deptstatindex2()
		{
			$user=User::find(Auth::id());
			$entrypoints=$user->entrypoints()->select('name','id')->get();
			$entrypoint_Array=array();
			foreach($user->entrypoints as $row)
			$entrypoint_Array[$row->id]=$row->name;
			//$entrypoint_Array[$row->id]=$row->name;
			$relations= Relation::lists('name','id');
			$depts=DB::table('medical_units')->select(array('id', 'name'))->get();
			$deptstat_active="active";
			//dd($depts);
			return view('prepare_deptState',array('relations'=>$relations,'entrypoints'=>$entrypoint_Array,'data'=>$depts ,'deptstat_active'=>$deptstat_active));
			//return view('selectdepartments');
		}
	//=======================================================================================================
	public function patientcount(Request $request)
	{
		//dd($request);
		$depts=$request->input('dept');
		//dd($depts);
		$fromdate=$request->input('fromdate');
		$todate=$request->input('todate');
		foreach($depts as $dept)
		{
			//dd($dept);
			$patientdata= DB::table('medical_units')
								->select('patients.id as pid','patients.name as pname','medical_units.id','medical_units.name','visits.entry_date')
								->join('medical_unit_visit','medical_unit_visit.medical_unit_id','=','medical_units.id')
								->join('visits','visits.id','=','medical_unit_visit.visit_id')
								->join('patients','patients.id','=','visits.patient_id')
								->where('medical_units.id','=',$dept)
								->whereBetween('entry_date',[$fromdate,$todate])
								->get();
			$patientcount=count($patientdata);
			//$patientcount=$patientdata->count();
			//dd($patientcount);
			//dd($patientdata);
		}
	}
	public function printdeptstat(Request $request)
	{
		$input_date=$request->all;
		$messages["fromdate.required"]='يجب ادخال التاريخ.';
		$messages["todate.required"]='يجب ادخال التاريخ.';
		$messages['fromdate.date'] = 'يجب ادخال تاريخ صحيح';
		$messages['todate.date'] = 'يجب ادخال تاريخ صحيح';
		$messages['fromdate.before'] = 'تاريخ بداية الفترة يجب ان يسبق تاريخ نهاية الفترة';
		$messages['todate.after'] = 'تاريخ نهاية الفترة يجب ان يلي تاريخ بداية الفترة';
		//$messages['todate.wrongdate'] = 'يجب ان يكون تاريخ البداية يسبق تاريخ نهاية الفترة';

		/*$constraints["fromdate"]='required|date|before:todate';
		$constraints["todate"]='required|date|after:fromdate';*/
		$constraints["fromdate"]='required|date';
		$constraints["todate"]='required|date';
		//$constraints["fromdate"]='required|date';
		//$constraints["todate"]='required|date';
		//$constraints["validperiod"]='required|date';
		$depts=$request->input('dept');
		//dd($depts);
		if($depts==null)
		{
		$messages["test.required"]='يجب اختيار قسم واحد على الأقل.';
		$constraints["test"]='required';
		}
		
		$current_user=User::find(Auth::id());
		if($current_user->role->name == "Doctor" || $current_user->role->name == "Nursing")
			return redirect()->guest('auth/login');
		$fromdate=$request->input('fromdate');
		$from=date("d-m-Y", strtotime($fromdate));
		$todate=$request->input('todate');
		$to=date("d-m-Y", strtotime($todate));
		
		$from_date=Carbon::parse($input_date['fromdate']);
		$to_date=Carbon::parse($input_date['todate']);
		//dd($from_date->diffInDays($to_date,false) );
		/*if($from_date->diffInDays($to_date,false) < 0){
			//dd($fromdate);
			return redirect()->back();
		}*/
		$this->validate($request,$constraints,$messages);
		$r4_active="active";
		$determined_date=true;
		$role_name=$current_user->role->name;
		$role_id=$current_user->role->id;
		$depts=$request->input('dept');
				//dd($depts);
				
				$table_header=" احصائية الاقسام خلال الفترة من  ".$from ." الى  ".$to;
				$deptstat = array();
				$deptname=array();
				//dd($depts);
				foreach($depts as $dept)
				{
					$deptn= DB::table('medical_units')
										->select('medical_units.name as uname')
										->where('medical_units.id','=',$dept)
										->get();
					$deptname[]=$deptn;
				}
			switch($role_name)
			{
				case 'GeneralRecept':
				{
					foreach($depts as $dept)
					{
						$data= DB::table('medical_units')
											->select('medical_units.id as mid',DB::raw('count(*) as count'),'medical_units.name as uname')
											->join('medical_unit_visit','medical_unit_visit.medical_unit_id','=','medical_units.id')
											->join('visits','visits.id','=','medical_unit_visit.visit_id')
											->join('patients','patients.id','=','visits.patient_id')
											->join('users','users.id','=','visits.user_id')
											->join('roles','users.role_id','=','roles.id')
											->where('medical_units.id','=',$dept)
											->where('roles.id','=',$role_id)
											->whereBetween('entry_date',[$fromdate,$todate])
											->groupBy('medical_units.id')
											->get();
						$deptstat[]=$data;
						$y=count($data);
					//	echo($y);
					}
					break;
				}
				case 'كل الناس الى بتتحول أقسام':
				{
					foreach($depts as $dept)
					{
						$data= DB::table('medical_units')
											->select('medical_units.id as mid',DB::raw('count(*) as count'),'medical_units.name as uname')
											->join('medical_unit_visit','medical_unit_visit.medical_unit_id','=','medical_units.id')
											->join('visits','visits.id','=','medical_unit_visit.visit_id')
											->join('patients','patients.id','=','visits.patient_id')
											//->join('users','users.id','=','visits.user_id')
											//->join('roles','users.role_id','=','roles.id')
											->where('medical_units.free','=',1)
											->where('medical_units.id','=',$dept)
											//->where('roles.id','=',$role_id)
											->whereBetween('entry_date',[$fromdate,$todate])
											->groupBy('medical_units.id')
											->get();
						$deptstat[]=$data;
						$y=count($data);
					//	echo($y);
					}
					break;
				}
				case 'Entrypoint':
				{
					foreach($depts as $dept)
					{
						$data= DB::table('medical_units')
											->select('medical_units.id as mid',DB::raw('count(*) as count'),'medical_units.name as uname')
											->join('medical_unit_visit','medical_unit_visit.medical_unit_id','=','medical_units.id')
											->join('visits','visits.id','=','medical_unit_visit.visit_id')
											->join('patients','patients.id','=','visits.patient_id')
											->join('users','users.id','=','visits.user_id')
											->join('roles','users.role_id','=','roles.id')
											//->where('medical_units.free','=',1)
											->where('medical_units.id','=',$dept)
											->where('roles.id','=',$role_id)
											->whereBetween('entry_date',[$fromdate,$todate])
											->groupBy('medical_units.id')
											->get();
						$deptstat[]=$data;
						$y=count($data);
					//	echo($y);
					}
					break;
				}
			}
				//$x=count($deptstat);
				//dd($x);
				//dd($deptstat);
					if($current_user->role->id == 4 || $current_user->role->id == 5 || $current_user->role->id == 7 || $current_user->role->id == 8){
						//dd($deptname);
						return view('department_statistics',compact('r4_active','deptstat','table_header','role_name','deptname'));
					}
		else{
			$numberOfVisits=0;
			$reception_users=User::whereIn('role_id',[4,5,7])->get();
			for($i=0;$i<count($reception_users);$i++){
				
			}
			return view('department_statistics',compact('r4_active','data','table_header','patientcount','determined_date'));
		}
	}
/*================================================================================================*/
	public function govdeptindex()
	{
		$govt_active="active";
		$user=User::find(Auth::id());
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		$entrypoint_Array=array();
		foreach($user->entrypoints as $row)
		$entrypoint_Array[$row->id]=$row->name;
		//$entrypoint_Array[$row->id]=$row->name;
		$relations= Relation::lists('name','id');
		//dd($depts);
		return view('prepare_government_Patient',array('relations'=>$relations,'entrypoints'=>$entrypoint_Array,'govt_active'=>$govt_active));
		//return view('selectdepartments');
	}
	public function printdept_gov_stat(Request $request)
	{
		//dd($request);
		$current_user=User::find(Auth::id());
		//dd($current_user->id);
		if($current_user->role->name == "Doctor" || $current_user->role->name == "Nursing")
			return redirect()->guest('auth/login');
		$r4_active="active";
		$determined_date=true;
		$role_name=$current_user->role->name;
		$role_id=$current_user->role->id;
		dd($role_id);
		$selectdate=$request->input('selectdate');
		$messages["fromdate.required"]='يجب ادخال التاريخ.';
		$messages["todate.required"]='يجب ادخال التاريخ.';
		$messages['fromdate.date'] = 'يجب ادخال تاريخ صحيح';
		$messages['todate.date'] = 'يجب ادخال تاريخ صحيح';
		$messages['fromdate.before'] = 'تاريخ بداية الفترة يجب ان يسبق تاريخ نهاية الفترة';
		$messages['todate.after'] = 'تاريخ نهاية الفترة يجب ان يلي تاريخ بداية الفترة';
		$constraints["fromdate"]='required|date|before:todate';
		$constraints["todate"]='required|date|after:fromdate';
		//dd($selectdate);
		$input=$request->all;
		switch($selectdate)
		{
			case 0:
			{
				$this->validate($request,$constraints,$messages);	
			}
			case 1:
			{
					$fromdate=$todate=date('Y-m-d',strtotime("-1 days"));
					break;
			}
			case 2:
				{
					$previous_week = strtotime("-1 week +1 day");

					$fromdate = strtotime("last saturday midnight",$previous_week);
					$todate = strtotime("next friday",$fromdate);
					
					$fromdate = date("Y-m-d",$fromdate);
					$todate = date("Y-m-d",$todate);
					break;
				}
			case 3:
			{
					$fromdate=date('Y-m-d', strtotime('first day of last month'));
					$todate=date('Y-m-d', strtotime('last day of last month'));
					break;
			}
			case 4:
			{
					//
					$previous_year=date('Y')-1;
					//dd($previous_year);
					$fromdate=$previous_year."-07"."-01";
					$todate=date('Y')."-06"."-30";
					break;
			}
			case 5:
			{
					$this->validate($request,$constraints,$messages);
					$fromdate=$request->input('fromdate');
					$from=date("d-m-Y", strtotime($fromdate));
					$todate=$request->input('todate');
					$to=date("d-m-Y", strtotime($todate));	
					break;
			}
		}
		if($request->input('enter_date')=="checked")
		{
			$this->validate($request,$constraints,$messages);
			$fromdate=$request->input('fromdate');
			$from=date("d-m-Y", strtotime($fromdate));
			$todate=$request->input('todate');
			$to=date("d-m-Y", strtotime($todate));	
		}
				/*$fromdat=$request->input('fromdate');*/
				//dd($fromdate);
				$from=date("d-m-Y", strtotime($fromdate));
				//dd($from);
				//$todate=$request->input('todate');
				$to=date("d-m-Y", strtotime($todate));
				//$from_query=date("Y-m-d",$fromdate);
				//dd($from_query);
				//$to_query=date("Y-m-d",$todate);
				if($selectdate !=1)
				{
				$table_header=" احصائية للمرضى من خارج أسيوط خلال الفترة من  ".$from ." الى  ".$to;
				}else
				{
				$table_header="احصائية للمرضى من خارج أسيوط يوم ".$from ;
				}
				$gender = array('M','F');
				$gendername=array('ذكر','أنثى');
				$gender_counts=array();
				$gov_stat_array=array();
				//dd($fromdate);
				//dd($todate);
				$patient_count=0;
				foreach($gender as $gender_type)
				{
					$data= DB::table('patients')
							->join('visits','visits.patient_id','=','patients.id')
							->join('government','government.government_id','=','patients.patient_government_id')
							->join('users','users.id','=','visits.user_id')
							->join('roles','users.role_id','=','roles.id')
					       	->select('gender',DB::raw('count(*) as count','government.name as gov_name'))
						   	->where('entry_date',">=","$fromdate")
						   	->where('entry_date',"<=","$todate")
							->where('government.government_id','!=','18')
							->where('patients.gender','=',$gender_type)
							->where('roles.id','=',$role_id)
							->groupBy('patients.gender')
						   	->get();
				/*$data= DB::table('patients')
							->join('visits','visits.patient_id','=','patients.id')
							->join('government','government.government_id','=','patients.patient_government_id')
							->join('users','users.id','=','visits.user_id')
							->join('roles','users.role_id','=','roles.id')
					       	->select('gender',DB::raw('count(*) as count'),'government.name as gov_name')
						   	->where('entry_date',">=","$fromdate")
						   	->where('entry_date',"<=","$todate")
							//->where('government.government_id','!=','1')
							->where('patients.gender','=',$gender_type)
							->where('roles.id','=',$role_id)
							->groupBy('government_id')
						   	->get();*/
					//$gov_stat_array[]=$gov_stat;
					$gender_counts[]=$data;
					$patient_count=count($data)+$patient_count;
				}
				//dd($gender_counts);
				//$gender_counts[]=$gendername;
					//$datacount=count($data);
					//dd($gender_counts);
				//dd($patient_count);
					if($current_user->role->id == 4 || $current_user->role->id == 5 || $current_user->role->id == 7){
						return view('reports.government_patient_report',compact('r4_active','gender_counts','table_header','role_name','gender','patient_count','gov_stat'));
					}
		else{
			$numberOfVisits=0;
			$reception_users=User::whereIn('role_id',[4,5,7])->get();
			for($i=0;$i<count($reception_users);$i++){
				
			}
			return view('reports.government_patient_report',compact('r4_active','gender_counts','table_header','gender','determined_date','patient_count'));
		}
	}
	//====================================================================================================
	public function select_status_index()
	{
		$user=User::find(Auth::id());
		$entrypoints=$user->entrypoints()->select('name','id')->get();
		$entrypoint_Array=array();
		foreach($user->entrypoints as $row)
		$entrypoint_Array[$row->id]=$row->name;
		//$entrypoint_Array[$row->id]=$row->name;
		$relations= Relation::lists('name','id');
		$exit_status=DB::table('exist_statuses')->select(array('id', 'name'))->get();
		$deptstat_active="active";
		return view('select_exit_status',array('relations'=>$relations,'entrypoints'=>$entrypoint_Array,'data'=>$exit_status ,'deptstat_active'=>$deptstat_active));
	}
	//=============================================================================================
	public function get_status_report(Request $request)
	{
		//dd($request);
		$input=$request->all();
		$messages["fromdate.required"]='يجب ادخال التاريخ.';
		$messages["todate.required"]='يجب ادخال التاريخ.';
		$messages['fromdate.date'] = 'يجب ادخال تاريخ صحيح';
		$messages['todate.date'] = 'يجب ادخال تاريخ صحيح';
		$messages['fromdate.before'] = 'تاريخ بداية الفترة يجب ان يسبق تاريخ نهاية الفترة';
		$messages['todate.after'] = 'تاريخ نهاية الفترة يجب ان يلي تاريخ بداية الفترة';

		$constraints["fromdate"]='required|date|before:todate';
		$constraints["todate"]='required|date|after:fromdate';
		$exit_status=$request->input('exit_status');
		//dd($exit_status);
		if($exit_status==null)
		{
		$messages["exit_state.required"]='يجب اختيار حالة واحدة على الأقل.';
		$constraints["exit_state"]='required';
		}
		$current_user=User::find(Auth::id());
		if($current_user->role->name == "Doctor" || $current_user->role->name == "Nursing")
			return redirect()->guest('auth/login');
		$fromdate=$request->input('fromdate');
		$from=date("d-m-Y", strtotime($fromdate));
		$todate=$request->input('todate');
		$to=date("d-m-Y", strtotime($todate));
		$this->validate($request,$constraints,$messages);
		$deptstat_active="active";
		$determined_date=true;
		$role_name=$current_user->role->name;
		$role_id=$current_user->role->id;
		$exit_status=$request->input('exit_status');
				$table_header="احصائية حالات الخروج من".$from ." الى  ".$to;
				$duration_header="احصائية لعدد أيام اقامة المرضى".$from ." الى  ".$to;
				$total_count=0;
				$exitstat = array();
				foreach($exit_status as $state_id)
				{
					$data= DB::table('visits')
										->select('visits.exit_status_id as v_exit_status_id',DB::raw('count(*) as count'),'exist_statuses.name as state_name')
										->join('exist_statuses','exist_statuses.id','=','visits.exit_status_id')
										->join('users','users.id','=','visits.user_id')
										//->join('roles','users.role_id','=','roles.id')
										->where('visits.exit_status_id','=',$state_id)
										//->where('roles.name','=',$role_name)
										->where('users.role_id','=',$role_id)
										->where('exit_date','!=',null)
										->whereBetween('exit_date',[$fromdate,$todate])
										->groupBy('exist_statuses.id')
										->get();							
					$exitstat[]=$data;
					$total_count=$total_count+count($data);
				//	echo($y);
				}
				$patients_duration= DB::table('visits')
										->select(DB::raw('round(avg(datediff(exit_date,entry_date)+1)) as avg_duration'),
										DB::raw('max(datediff(exit_date,entry_date)+1)as max_duration'),
										DB::raw('min(datediff(exit_date,entry_date)+1)as min_duration'))
										->join('users','users.id','=','visits.user_id')
										//->join('roles','users.role_id','=','roles.id')
										//->where('roles.name','=',$role_name)
										->where('users.role_id','=',$role_id)
										->whereBetween('exit_date',[$fromdate,$todate])
										->where('exit_date','!=',null)
										//->groupBy('visits.id')
										->get();
										//dd($patients_duration);
				/*$visits_count= DB::table('visits')
										->select(DB::raw('count(*) as count'))
										->join('users','users.id','=','visits.user_id')
										//->join('roles','users.role_id','=','roles.id')
										//->where('roles.name','=',$role_name)
										->where('users.role_id','=',$role_id)
										->whereBetween('entry_date',[$fromdate,$todate])
										->get();*/
										//dd($visits_count);
										$total_duration=0;							
				return view('reports.exit_status_report',array('data'=>$exitstat ,'deptstat_active'=>$deptstat_active,
				'total_count'=>$total_count,'table_header'=>$table_header,'patients_duration'=>$patients_duration,'duration_header'=>$duration_header));
				}
		//==========================================================================================
		public function dept_stats_count(Request $request)
		{
			$input_date=$request->all;
		
			$messages["fromdate.required"]='يجب ادخال التاريخ.';
			$messages["todate.required"]='يجب ادخال التاريخ.';
			$messages['fromdate.date'] = 'يجب ادخال تاريخ صحيح';
			$messages['todate.date'] = 'يجب ادخال تاريخ صحيح';
			$messages['fromdate.before'] = 'تاريخ بداية الفترة يجب ان يسبق تاريخ نهاية الفترة';
			$messages['todate.after'] = 'تاريخ نهاية الفترة يجب ان يلي تاريخ بداية الفترة';
			//$messages['todate.wrongdate'] = 'يجب ان يكون تاريخ البداية يسبق تاريخ نهاية الفترة';

			$constraints["fromdate"]='required|date|before:todate';
			$constraints["todate"]='required|date|after:fromdate';
			//$constraints["validperiod"]='required|date';
			$depts=$request->input('dept');
			//dd($depts);
			if($depts==null)
			{
			$messages["test.required"]='يجب اختيار قسم واحد على الأقل.';
			$constraints["test"]='required';
			}
			
			$current_user=User::find(Auth::id());
			if($current_user->role->name == "Doctor" || $current_user->role->name == "Nursing")
				return redirect()->guest('auth/login');
			$fromdate=$request->input('fromdate');
			$from=date("d-m-Y", strtotime($fromdate));
			$todate=$request->input('todate');
			$to=date("d-m-Y", strtotime($todate));
			
			$from_date=Carbon::parse($input_date['fromdate']);
			$to_date=Carbon::parse($input_date['todate']);
			//dd($from_date->diffInDays($to_date,false) );
			$this->validate($request,$constraints,$messages);
			$deptstat_active="active";
			$determined_date=true;
			$role_name=$current_user->role->name;
			$role_id=$current_user->role->id;
			$depts=$request->input('dept');	
			$table_header=" احصائية الاقسام خلال الفترة من  ".$from ." الى  ".$to;
			$exit_status=DB::table('exist_statuses')->select('id','name')->get();
			//dd($exit_status);
			$deptstat = array();
			$deptNames=array();
			$dept_total_count=array();
			$deptscount=0;
				foreach($depts as $dept)
				{
					$deptname=DB::table('medical_units')->select('medical_units.name as deptname','medical_units.id as med_id')->where('medical_units.id','=',$dept)->get();
					$data= DB::table('visits')
								->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
								->join('medical_units','medical_units.id','=','medical_unit_visit.medical_unit_id')
								->join('exist_statuses','exist_statuses.id','=','visits.exit_status_id')
								->select('exist_statuses.name as ex_name','exist_statuses.id as ex_id','medical_units.name as deptname',DB::raw('count(visits.exit_status_id) as ex_count'))
								->where('medical_unit_visit.medical_unit_id','=',$dept)
								->whereBetween('exit_date',[$fromdate,$todate])
								->where('exit_date','!=',null)
								->groupBy('visits.exit_status_id')
								->get();
					$deptscount=$deptscount+count($data);
					$deptstat[]=$data;
					$deptNames[]=$deptname;
					//dd($deptscount);
					$dept_total_count[]=$deptscount;
				}
				//$deptstat[]=$depts;
				//dd($deptstat);
			return view('reports.dept_statuesReport',array('data'=>$deptstat ,'deptstat_active'=>$deptstat_active,
				'total_count'=>$deptscount,'table_header'=>$table_header,'exit_status'=>$exit_status,'deptNames'=>$deptNames));
		}
//============================================================================================================================
	public function onday_patient_perpare()
	{
		return view('private.oneday_patient');
	}
   public function oneday_patient_report(Request $request)
   {
	   $constraints['fromdate']='required';
	   $messages['fromdate.required']="يجب ادخال تاريخ الخروج";
	   $input=$request->all;
		$this->validate($request,$constraints,$messages);
		$current_user=User::find(Auth::id());
		$onedayrep_active="active";
		$determined_date=true;
		$role_name=$current_user->role->name;
		$role_id=$current_user->role->id;
	   $fromdate=$request->input('fromdate');
	   $from=date("d-m-Y", strtotime($fromdate));
	   $table_header=" بيانات مرضي الخروج بتاريخ ".$from;
	
	   $data=DB::table('patients')
	   ->join('visits','visits.patient_id','=','patients.id')
	   ->join('contracts','contracts.id','=','visits.contract_id')
	   ->join('medical_unit_visit','medical_unit_visit.visit_id','=','visits.id')
	   ->join('rooms','rooms.id','=','medical_unit_visit.room_id')
	   ->join('users','users.id','=','visits.user_id')
	   ->join('roles','roles.id','=','users.role_id')
	   ->select('patients.new_id as pid','patients.name as pname',
	   'patients.birthdate as pBD','rooms.name as romname','contracts.name as contname',
	   'patients.address as paddress','visits.entry_date as v_entrydate','visits.ticket_number as vticket_number')
	   ->where('visits.entry_date','=',$fromdate)
	   ->where('users.role_id','=',$role_id)
	   ->get();
	   return view('private.ondayPatientReport',array('data'=>$data ,'onedayrep_active'=>$onedayrep_active,
	   'table_header'=>$table_header,'current_user'=>$current_user,'role_name'=>$role_name));
	   
   }
			
	/*public function printdept_gov_stat(Request $request)
	{
		$current_user=User::find(Auth::id());
		//dd($current_user->id);
		if($current_user->role->name == "Doctor" || $current_user->role->name == "Nursing")
			return redirect()->guest('auth/login');
		$r4_active="active";
		$determined_date=true;
		$role_name=$current_user->role->name;
				//dd($request);
		$selectdate=$request->input('selectdate');
		switch($selectdate)
		{
			case 1:
			{
					$fromdate=$todate=date('d.m.Y',strtotime("-1 days"));
					break;
			}
			case 2:
				{
					$previous_week = strtotime("-1 week +1 day");

					$fromdate = strtotime("last saturday midnight",$previous_week);
					$todate = strtotime("next friday",$fromdate);

					$fromdate = date("Y-m-d",$fromdate);
					$todate = date("Y-m-d",$todate);
					break;
				}
			case 3:
			{
					$fromdate=date('Y-m-d', strtotime('first day of last month'));
					$todate=date('Y-m-d', strtotime('last day of last month'));
					break;
			}
			case 4:
			{
					$previous_year=date('Y')-1;
					$fromdate="01/"."07/".$previous_year;
					$todate="30/"."06/".date('Y');
					break;
			}
			case 5:
			{
					$fromdate=$request->input('fromdate');
					$from=date("d-m-Y", strtotime($fromdate));
					$todate=$request->input('todate');
					$to=date("d-m-Y", strtotime($todate));	
					break;
			}
		}
				/*$fromdat=$request->input('fromdate');*/
	/*			$from=date("d-m-Y", strtotime($fromdate));
				//$todate=$request->input('todate');
				$to=date("d-m-Y", strtotime($todate));
				$table_header=" احصائية للمرضى من خارج أسيوط خلال الفترة من  ".$from ." الى  ".$to;
				$gender = array('M','F');
				$gendername=array('ذكر','أنثى');
				$gender_counts=array();
				//dd($fromdate);
				foreach($gender as $gender_type)
				{
					$data= DB::table('patients')
							->join('visits','visits.patient_id','=','patients.id')
					       ->select('gender',DB::raw('count(*) as count'))
						   ->where('address','!=','اسيوط')
						   ->where('entry_date',">=","$fromdate")
						   ->where('entry_date',"<=","$todate")
						   ->where('address','!=','asyut')
						   ->where('patients.gender','=',$gender_type)
						   ->groupBy('patients.gender')
						   ->get();
					$gender_counts[]=$data;
				}
				//$gender_counts[]=$gendername;
					//$datacount=count($data);
					//dd($gender_counts);
				//dd($deptstat);
					if($current_user->role->id == 4 || $current_user->role->id == 5 || $current_user->role->id == 7){
						return view('reports.government_patient_report',compact('r4_active','gender_counts','table_header','role_name','gender'));
					}
		else{
			$numberOfVisits=0;
			$reception_users=User::whereIn('role_id',[4,5,7])->get();
			for($i=0;$i<count($reception_users);$i++){
				
			}
			return view('reports.government_patient_report',compact('r4_active','gender_counts','table_header','gender','determined_date'));
		}
	}*/
	public function ajax_get_department_doctors(Request $request){

		/*$medicalunit=MedicalUnit::find($request->get('mid'));
		$department_doctors=$medicalunit->users()->select('users.name','users.id')->get();
		$rooms=$medicalunit->rooms()->select('rooms.name','rooms.id','number_of_vacancy_beds')->get();
		if(count($department_doctors) > 0 || count($rooms) > 0 )
			return response()->json(['success' => 'true','deps'=>$department_doctors,'rooms'=>$rooms]);
		else
			return response()->json(['success' => 'false']); */
		$medicalunit=MedicalUnit::find($request->get('mid'));
		$department_doctors=$medicalunit->users()->select('users.name','users.id')->get();
		$rooms=$medicalunit->rooms()->select('rooms.name','rooms.id','number_of_vacancy_beds')->get();
		if(count($department_doctors) > 0 || count($rooms) > 0 )
			return response()->json(['success' => 'true','deps'=>$department_doctors,'rooms'=>$rooms]);
		else
			return response()->json(['success' => 'false']);
	}
	
	public function ajax_get_number_of_vacancy_beds(Request $request){

		//$rooms=Room::find($request->get('romnum'));
		//$department_doctors=$medicalunit->users()->select('users.name','users.id')->get();
		$vacancy_beds=DB::table('rooms')->where('id',$request->get('romnum'))->select('number_of_vacancy_beds')->get();
		if(count($vacancy_beds) > 0 )
			return response()->json(['success' => 'true','vacancy_beds'=>$vacancy_beds]);
		else
			return response()->json(['success' => 'false']);
	}
	public function ajax_get_patient_data(Request $request){

		//$patient_data=Patient::where('id',$request->get('pid'))->select('name','gender','sin','address','birthdate','phone_num','social_status','job')->get();
		$patient_data=DB::table('patients')
		->where('id',$request->get('pid'))
		->select('name','gender','sin','address','birthdate','phone_num','social_status','job','patient_government_id')
		->get();
		if(count($patient_data) > 0)
			return response()->json(['success' => 'true','data'=>$patient_data]);
		else
			return response()->json(['success' => 'false']);
	}
}