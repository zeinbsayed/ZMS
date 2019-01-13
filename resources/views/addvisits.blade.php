@extends('layouts.app')
@section('title')
تحديث بيانات دخول مريض
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
			<h1>
        بيانات دخول مريض
        <small>{{ $header }}</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">{{ $header }}</li>
      </ol>
	  
    </section>

    <!-- Main content -->
    <section class="content">
	  <div id="overlay"></div>
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-24">
          <!-- small box -->
			  <div class="box box-primary" dir="rtl">

				<!-- /.box-header -->
				<!-- form start -->
				{!! Form::open(array('class'=>'form','method' => 'POST','name'=>'patient_form','id'=>'patient_form')) !!}
				  <div class="box-body">
					@if(Session::has('flash_message'))
						@if(Session::get('message_type') == 'false')
						<div class="alert alert-danger">
							<b>{{ Session::get('flash_message') }}</b>
						@else
						<div class="alert alert-success">
							<b>{{ Session::get('flash_message') }}</b>
							<br>
							@if(isset($data[0]->vid))
								<?php $var_id = $data[0]->vid; ?>
								<?php $pa_id = $data[0]->id; ?>
								لطباعة بطاقة دخول <a href='{{ url("/printpatientdata/$pa_id&$var_id") }}' target="_blank"> اضغط هنا </a>
							@endif
						@endif

						</div>
					@endif
					<div class="alert alert-danger" style="display: none"id="err_msg"></div>
					<div class="row">
						<div class="col-lg-3" style="float:right" >
							<div class="form-group" >
								{!! Form::label('كود المريض',null) !!}
								{!! Form::text('id',$data[0]->new_id,array('class'=>'form-control','id'=>'pid','disabled'=>'true','placeholder'=>'كود المريض','onkeypress'=>'return isNumber(event)')) !!}
							</div>
							
							<div class="form-group @if($errors->has('name')) has-error @endif" >
							  {!! Form::label('الأسم',null) !!}
							  @if(($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::text('name',$data[0]->name,array('class'=>'form-control')) !!}
								@if ($errors->has('name'))<span class="help-block">{{$errors->first('name')}}</span>@endif
							  @else
								{!! Form::text('name',$data[0]->name,array('class'=>'form-control','disabled')) !!}
							  @endif
							  {!! Form::hidden('code',$data[0]->id) !!}
							</div>
							<div class="form-group @if($errors->has('gender')) has-error @endif">
							  {!! Form::label('النوع',null) !!}
							  @if(($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::select('gender',[''=>'أخترالنوع','M' => 'ذكر', 'F' => 'أنثى'],$data[0]->gender,['class'=>'form-control','id'=>'gender']); !!}
								@if ($errors->has('gender'))<span class="help-block">{{$errors->first('gender')}}</span>@endif
							  @else	
								{!! Form::select('gender',[''=>'أخترالنوع','M' => 'ذكر', 'F' => 'أنثى'],$data[0]->gender,['disabled','class'=>'form-control','id'=>'gender']); !!}
							  @endif
							</div>
							<div class="form-group @if($errors->has('sin')) has-error @endif">
							    {!! Form::label('رقم البطاقة',null) !!}
							    @if(($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
									{!! Form::number('sin',$data[0]->sin,array('class'=>'form-control','id'=>'sin','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sin")')) !!}
									@if ($errors->has('sin'))<span class="help-block">{{$errors->first('sin')}}</span>@endif
								@else
									{!! Form::number('sin',$data[0]->sin,array('class'=>'form-control','disabled'=>'disabled')) !!}
								@endif
								{!! Form::hidden('patient_sin',$data[0]->sin,['id'=>'patient_sin']) !!}
								{!! Form::hidden('age',null,['id'=>'age']) !!}
								{!! Form::hidden('v_code',$data[0]->vid) !!}
							</div>
							<div class="form-group @if($errors->has('phone_num')) has-error @endif" >
							  {!! Form::label('رقم التليفون',null) !!}
							  @if(($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								 {!! Form::text('phone_num',$data[0]->phone_num,array('class'=>'form-control','min'=>'1','placeholder'=>'رقم التليفون','onkeypress'=>'return isNumber(event)','id'=>'phone_num')) !!}
							  @else
								 {!! Form::text('phone_num',$data[0]->phone_num,array('class'=>'form-control','disabled'=>'disabled')) !!}
							  @endif
							   @if ($errors->has('phone_num'))<span class="help-block">{{$errors->first('phone_num')}}</span>@endif
							</div>
							@if ($role_name=="GeneralRecept" || $role_name=="Injuires")
							<div class="form-group">
							{!! Form::label('رقم لوحة سيارة الاسعاف',null,array('style'=>'color:black')) !!}
							  @if(($sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::text('ambulance_number',$data[0]->ambulance_number,array('class'=>'form-control','placeholder'=>'رقم لوحة سيارة الاسعاف','id'=>'ambulance_number')) !!}
							  @else
								   @if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
								{!! Form::text('ambulance_number',null,array('class'=>'form-control','placeholder'=>'رقم لوحة سيارة الاسعاف','id'=>'ambulance_number')) !!}
									@endif
							  @endif
								</div>
								
							<div class="form-group">
							  {!! Form::label('اسم المسعف',null,array('style'=>'color:black')) !!}
							  @if(($sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::text('paramedic_name',$data[0]->paramedic_name,array('class'=>'form-control','placeholder'=>'اسم المسعف','id'=>'paramedic_name')) !!}
							  @else
								   @if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
									{!! Form::text('paramedic_name',null,array('class'=>'form-control','placeholder'=>'اسم المسعف','id'=>'paramedic_name')) !!}
									@endif
							  @endif
							</div>
							@endif
						</div>
						<div class="col-lg-3" style="float:right">
							
							<div class="form-group @if($errors->has('address')) has-error @endif" >
							  {!! Form::label('العنوان',null,array('style'=>'color:red')) !!}
								{!! Form::text('address',$data[0]->address,array('class'=>'form-control')) !!}
							  @if ($errors->has('address'))<span class="help-block">{{$errors->first('address')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('government_id')) has-error @endif">
								{!! Form::label('المحافظة',null,array('style'=>'color:red')) !!}
								@if(($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::select('government_id',$governments,[$govdata[0]->government_id],['id'=>'government_id','class' => 'form-control','placeholder'=>' أختر المحافظة']) !!}
								@if ($errors->has('government_id'))<span class="help-block">{{$errors->first('government_id')}}</span>@endif
								@else
								{!! Form::select('government_id',$governments,[$govdata[0]->government_id],['id'=>'government_id','class' => 'form-control','disabled'=>'disabled']) !!}
								@if ($errors->has('government_id'))<span class="help-block">{{$errors->first('government_id')}}</span>@endif
								@endif
							</div>
							<div class="form-group  @if($errors->has('birthdate')) has-error @endif" >
							  {!! Form::label('تاريخ الميلاد',null) !!}
							  @if(($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::text('birthdate',$data[0]->birthdate,array('id'=>'datepicker','class'=>'form-control')) !!}
							  @else
								{!! Form::text('birthdate',$data[0]->birthdate,array('class'=>'form-control','disabled'=>'disabled')) !!}
							  @endif
							  @if ($errors->has('birthdate'))<span class="help-block">{{$errors->first('birthdate')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('age')) has-error @endif" >
							  {!! Form::label('السن',null) !!}
							  @if(($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::text('age',calculateAge($data[0]->birthdate),array('class'=>'form-control')) !!}
							  @else
								{!! Form::text('age',calculateAge($data[0]->birthdate),array('class'=>'form-control','disabled'=>'disabled')) !!}
							  @endif
							  @if ($errors->has('age'))<span class="help-block">{{$errors->first('age')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('job')) has-error @endif" >
							  {!! Form::label('المهنة',null) !!}
							  @if(($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::text('job',$data[0]->job,array('class'=>'form-control','placeholder'=>'المهنة')) !!}
							  @else
								{!! Form::text('job',$data[0]->job,array('class'=>'form-control','placeholder'=>'المهنة','disabled'=>'disabled')) !!}
							  @endif
							   @if ($errors->has('job'))<span class="help-block">{{$errors->first('job')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('social_status')) has-error @endif" >
							  {!! Form::label('الحالة الأجتماعيه',null) !!}
							  @if(($sub_type_entrypoint == "update_only" || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::text('social_status',$data[0]->social_status,array('class'=>'form-control','placeholder'=>'الحالة الأجتماعيه')) !!}
							  @else
								{!! Form::text('social_status',$data[0]->social_status,array('class'=>'form-control','placeholder'=>'الحالة الأجتماعيه','disabled'=>'disabled')) !!}
							  @endif
							   @if ($errors->has('social_status'))<span class="help-block">{{$errors->first('social_status')}}</span>@endif
							</div>
							@if ($role_name=="GeneralRecept" || $role_name=="Injuires")
							<div class="form-group @if($errors->has('ticket_number')) has-error @endif">
							  {!! Form::label('رقم التذكرة',null,array('style'=>'color:red')) !!}
							  @if(($sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::text('ticket_number',$data[0]->ticket_number,array('class'=>'form-control','placeholder'=>'رقم التذكرة','id'=>'ticket_number')) !!}
							  @else
								   @if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
								{!! Form::text('ticket_number',null,array('class'=>'form-control','placeholder'=>'رقم التذكرة','id'=>'ticket_number')) !!}
									@endif
							  @endif
							   @if ($errors->has('ticket_number'))<span class="help-block">{{$errors->first('ticket_number')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('kateb_name')) has-error @endif">
							  {!! Form::label('توقيع كاتب التذكرة',null,array('style'=>'color:red')) !!}
							  @if(($sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								{!! Form::text('kateb_name',$data[0]->kateb_name,array('class'=>'form-control','placeholder'=>'توقيع كاتب التذكرة','id'=>'kateb_name')) !!}
							  @else
								  @if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
								{!! Form::text('kateb_name',null,array('class'=>'form-control','placeholder'=>'توقيع كاتب التذكرة','id'=>'kateb_name')) !!}
								@endif
							  @endif
							   @if ($errors->has('kateb_name'))<span class="help-block">{{$errors->first('kateb_name')}}</span>@endif
							</div>
							@endif
						</div>
						<div class="col-lg-3" style="float:right">
							<fieldset>
								<legend>بيانات اذن الدخول</legend>
									<div class="form-group @if($errors->has('entry_id')) has-error @endif" >
									@if(isset($data[0]->vid))
										{!! Form::label('مكتب الدخول') !!}
										{!! Form::select('entry_id',$entrypoints,$data[0]->entry_id,['class' => 'form-control','disabled'=>'disabled']); !!}
									@else
										{!! Form::label('مكتب الدخول',null,array('style'=>'color:red')) !!}
										{!! Form::select('entry_id',$entrypoints,null,['class' => 'form-control']); !!}
										@if ($errors->has('entry_id'))<span class="help-block">{{$errors->first('entry_id')}}</span>@endif
									@endif
									</div>
								@if($role_name=="Injuires"||$role_name=="GeneralRecept")
								<?php $role_name=="Injuires"?$dname="اصابات":$dname="استقبال عام"; ?>
								<div>
								{!! Form::label('اسم القسم',null,array('style'=>'color:red')) !!}
								{!! Form::text('medical_id',$dname,array('class'=>'form-control','id'=>'medical_id','disabled')) !!}
								</div>
								<div>
								{!! Form::label('اسم الغرفة',null,array('style'=>'color:red')) !!}
								{!! Form::text('room_number',$dname,array('class'=>'form-control','id'=>'room_number','disabled')) !!}
								</div>
								<div class="form-group @if($errors->has('doctor_name')) has-error @endif">
								  {!! Form::label('الطبيب المعالج',null,array('style'=>'color:red')) !!}
								  {!! Form::text('doctor_name',null,array('class'=>'form-control','placeholder'=>'الطبيب المعالج','id'=>'doctor_name')) !!}
								  @if ($errors->has('doctor_name'))<span class="help-block">{{$errors->first('doctor_name')}}</span>@endif
								</div>
								@else
									<div class="form-group @if($errors->has('medical_id')) has-error @endif">
									{!! Form::label('أسم القسم',null,array('style'=>'color:red')) !!}
									@if(count($medical_visit)!=0)
									{!! Form::select('medical_id',$medicalunits,[$medical_visit[0]->dept_id],['id'=>'medical_id','class' => 'form-control']); !!}
									@else
									{!! Form::select('medical_id',$medicalunits,null,['id'=>'medical_id','class' => 'form-control','placeholder'=>'أختر القسم']); !!}
									@endif
									@if ($errors->has('medical_id'))<span class="help-block">{{$errors->first('medical_id')}}</span>@endif
									</div>
									<div class="form-group @if($errors->has('room_number')) has-error @endif">
										{!! Form::label('room_number','أسم الغرفة',array('style'=>'color:red')) !!}
										@if(count($medical_visit)!=0)
										{!! Form::hidden('old_room_number',$medical_visit[0]->room_id) !!}
										{!! Form::select('room_number',$first_rooms,$medical_visit[0]->room_id,['id'=>'room_number','class' => 'form-control']); !!}
										@else
										{!! Form::select('room_number',$first_rooms,null,['id'=>'room_number','class' => 'form-control','placeholder'=>'أختر الغرفة']); !!}
										@endif
										
									    @if ($errors->has('room_number'))<span class="help-block">{{$errors->first('room_number')}}</span>@endif
									  
									</div>
									<div class="form-group @if($errors->has('reference_doctor_id')) has-error @endif" >
									{!! Form::label('الطبيب',null) !!}
									@if(count($medical_visit)>0)
										{!! Form::select('reference_doctor_id',$first_department_doctors,$medical_visit[0]->doctor_name,['id'=>'reference_doctor_id','class' => 'form-control',]); !!}
										@else
										{!! Form::select('reference_doctor_id',$first_department_doctors,null,['id'=>'reference_doctor_id','class' => 'form-control','placeholder'=>'أخترالطبيب']); !!}
										@if ($errors->has('reference_doctor_id'))<span class="help-block">{{$errors->first('reference_doctor_id')}}</span>@endif
									@endif
									</div>
									@endif
									  <div class="form-group @if($errors->has('entry_date')) has-error @endif">
										@if(isset($data[0]->vid))
											{!! Form::label('تاريخ الدخول',null) !!}
											{!! Form::text('entry_date',$data[0]->entry_date,array('class'=>'form-control','id'=>'entry_date','disabled')) !!}
										@else
											{!! Form::label('تاريخ الدخول',null,array('style'=>'color:red')) !!}
											{!! Form::text('entry_date',null,array('class'=>'form-control','id'=>'entry_date')) !!}
											@if ($errors->has('entry_date'))<span class="help-block">{{$errors->first('entry_date')}}</span>@endif
										@endif
									  </div>
								</fieldset>
						</div>
						<div class="col-lg-3" style="float:right">
							<fieldset>
								 <legend>بيانات اذن الدخول</legend>
								  <div class="bootstrap-timepicker">
									@if(isset($data[0]->vid))
									  <div class="form-group">
										{!! Form::label('entry_time','ساعة الدخول') !!}
										{!! Form::text('entry_time',$data[0]->entry_time,array('id'=>'entry_time','class'=>'form-control timepicker','disabled')) !!}
									  </div>
									@else
									  <div class="form-group @if($errors->has('entry_time')) has-error @endif">
										{!! Form::label('entry_time','ساعة الدخول',array('style'=>'color:red')) !!}
										{!! Form::text('entry_time',null,array('id'=>'entry_time','class'=>'form-control timepicker','disabled')) !!}
										@if ($errors->has('entry_time'))<span class="help-block">{{$errors->first('entry_time')}}</span>@endif
									  </div>
									@endif
								  </div>
								  <div class="bootstrap-timepicker">
									@if(isset($data[0]->vid))
									  <div class="form-group">
										  {!! Form::label('reg_time','ساعة الوصول') !!}
										  {!! Form::text('reg_time',$data[0]->reg_time,array('class'=>'form-control timepicker','disabled')) !!}
										  @if ($errors->has('reg_time'))<span class="help-block">{{$errors->first('reg_time')}}</span>@endif
									  </div>
									@else
									  <div class="form-group @if($errors->has('reg_time')) has-error @endif">
										  {!! Form::label('reg_time','ساعة الوصول',array('style'=>'color:red')) !!}
										  {!! Form::text('reg_time',null,array('class'=>'form-control timepicker')) !!}
										  @if ($errors->has('reg_time'))<span class="help-block">{{$errors->first('reg_time')}}</span>@endif
									  </div>
									@endif
								 </div>
								 <div class="form-group @if($errors->has('contract_id')) has-error @endif">
									  {!! Form::label('جهة التعاقد',null,array('style'=>'color:red')) !!}
									  @if(($sub_type_entrypoint == "update_only") ||($sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
									  {!! Form::select('contract_id',$contracts,$data[0]->contract_id,array('class' => 'form-control')); !!}
										@endif
										 @if((($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
											 {!! Form::select('contract_id',$contracts,$data[0]->contract_id,['class' => 'form-control']); !!}
										 @endif
									  @if ($errors->has('contract_id'))<span class="help-block">{{$errors->first('contract_id')}}</span>@endif
								  </div>
								  <div class="form-group @if($errors->has('converted_from')) has-error @endif">
									  {!! Form::label('converted_from','محول من',array('style'=>'color:red')) !!}
									   @if(($sub_type_entrypoint == "update_only") ||($sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
									  {!! Form::select('converted_from',$converted_from,$data[0]->converted_from,['class' => 'form-control']); !!}
										@else
										@if((($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
											{!! Form::select('converted_from',$converted_from,$data[0]->converted_from,['class' => 'form-control']); !!}
										@endif
										@endif
										
								  </div>
								  <div class="form-group @if($errors->has('entry_reason_desc')) has-error @endif">
									  {!! Form::label('entry_reason_desc','تشخيص الدخول المبدئي',array('style'=>'color:red')) !!}
									   @if(($sub_type_entrypoint == "update_only") ||($sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
											{!! Form::textarea('entry_reason_desc',$data[0]->entry_reason_desc,array('class'=>'form-control','rows'=>'2')) !!}
										@else
											@if((($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
											 {!! Form::textarea('entry_reason_desc',$data[0]->entry_reason_desc,array('class'=>'form-control','rows'=>'2')) !!}
											@endif
										 @endif
											
								  </div>
								  <div class="form-group @if($errors->has('cure_type_id')) has-error @endif">
									  {!! Form::label('نوع العلاج') !!}
									  @if(isset($data[0]->cure_type_id) && (($sub_type_entrypoint == "entry_only") && isset($data[0]->vid)))
										{!! Form::select('cure_type_id',$cure_types,$data[0]->cure_type_id,['class' => 'form-control']); !!}
									  @else
										  @if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
										{!! Form::select('cure_type_id',$cure_types,null,['class' => 'form-control']); !!}
										@endif
									  @endif
								  </div>
							</fieldset>
						</div>
					</div>
					<div class="box-header with-border">
					  <h3 class="box-title">بيانات اضافية </h3>
					</div>
					<div class="row">
						<div class="col-lg-6" style="float:right">
								<div class="form-group @if($errors->has('person_relation_name')) has-error @endif">
								  {!! Form::label('أسم اقرب الاقارب',null) !!}
								   @if((($sub_type_entrypoint == "update_only") ||($sub_type_entrypoint == "entry_only")) && (isset($data[0]->vid)))
										{!! Form::text('person_relation_name',$data[0]->person_relation_name,array('class'=>'form-control','placeholder'=>'الاسم','autocomplete'=>'off')) !!}
									@else
										 @if((($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
										{!! Form::text('person_relation_name',$data[0]->person_relation_name,array('class'=>'form-control','placeholder'=>'الاسم','autocomplete'=>'off')) !!}
									@endif
									@endif
								  @if ($errors->has('person_relation_name'))<span class="help-block">{{$errors->first('person_relation_name')}}</span>@endif
								</div>
								
								<div class="form-group @if($errors->has('person_relation_phone_num')) has-error @endif">
								  {!! Form::label('رقم التليفون أقرب الاقارب',null) !!}
								  @if(((($sub_type_entrypoint == "update_only") ||$sub_type_entrypoint == "entry_only") && isset($data[0]->vid)))
										{!! Form::text('person_relation_phone_num',$data[0]->person_relation_phone_num,array('class'=>'form-control','id'=>'person_relation_phone_num','placeholder'=>'رقم التليفون','onkeypress'=>'return isNumber(event)')) !!}
									@else
										 @if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
											  {!! Form::text('person_relation_phone_num',$data[0]->person_relation_phone_num,array('class'=>'form-control','id'=>'person_relation_phone_num','placeholder'=>'رقم التليفون','onkeypress'=>'return isNumber(event)')) !!}
										  @endif
									@endif
								  @if ($errors->has('person_relation_phone_num'))<span class="help-block">{{$errors->first('person_relation_phone_num')}}</span>@endif
								</div>
								<div class="form-group @if($errors->has('person_relation_id')) has-error @endif">
								  {!! Form::label('درجة القرابة') !!}
								  @if(((($sub_type_entrypoint == "update_only") ||$sub_type_entrypoint == "entry_only") && isset($data[0]->vid)))
								  {!! Form::select('person_relation_id',$relations,$data[0]->person_relation_id,['class' => 'form-control']); !!}
								  @else
										 @if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
											{!! Form::select('person_relation_id',$relations,$data[0]->person_relation_id,['class' => 'form-control']); !!}
									@endif
									@endif
								  @if ($errors->has('person_relation_id'))<span class="help-block">{{$errors->first('person_relation_id')}}</span>@endif
								</div>
								<div class="form-group @if($errors->has('companion_name')) has-error @endif">
								  {!! Form::label('أسم المرافق') !!}
								  @if((($sub_type_entrypoint == "entry_only") && isset($data[0]->vid)))
								  {!! Form::text('companion_name',$data[0]->companion_name,array('class'=>'form-control','placeholder'=>'الاسم','autocomplete'=>'off')) !!}
									@else
										 @if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
										{!! Form::text('companion_name',$data[0]->companion_name,array('class'=>'form-control','placeholder'=>'الاسم','autocomplete'=>'off')) !!}
									@endif
									@endif
								  @if ($errors->has('companion_name'))<span class="help-block">{{$errors->first('companion_name')}}</span>@endif
								</div>
								<div class="form-group @if($errors->has('companion_sid')) has-error @endif">
								  {!! Form::label('رقم البطاقة',null) !!}
								  @if((($sub_type_entrypoint == "update_only") || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
								  {!! Form::text('companion_sid',$data[0]->companion_sid,array('class'=>'form-control','id'=>'companion_sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("companion_sid")')) !!}
									@else
										 @if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
											 {!! Form::text('companion_sid',$data[0]->companion_sid,array('class'=>'form-control','id'=>'companion_sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("companion_sid")')) !!}
										 @endif
										 @endif
								  @if ($errors->has('companion_sid'))<span class="help-block">{{$errors->first('companion_sid')}}</span>@endif
								</div>
						</div>
						<div class="col-lg-6" style="float:right">
							<div class="form-group @if($errors->has('companion_address')) has-error @endif">
							  {!! Form::label('محل الاقامة') !!}
							  @if((($sub_type_entrypoint == "update_only") || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
							  {!! Form::text('companion_address',$data[0]->companion_address,array('class'=>'form-control','placeholder'=>'محل الاقامة')) !!}
								@else
								@if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
								 {!! Form::text('companion_address',$data[0]->companion_address,array('class'=>'form-control','placeholder'=>'محل الاقامة')) !!}
								 @endif
								 @endif
							  @if ($errors->has('companion_address'))<span class="help-block">{{$errors->first('companion_address')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('companion_job')) has-error @endif">
							  {!! Form::label('المهنة') !!}
							   @if((($sub_type_entrypoint == "update_only") || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
							  {!! Form::text('companion_job',$data[0]->companion_job,array('class'=>'form-control','placeholder'=>'المهنة')) !!}
							  @else
								 @if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
									 {!! Form::text('companion_job',$data[0]->companion_job,array('class'=>'form-control','placeholder'=>'المهنة')) !!}
								 @endif
								 @endif
							  @if ($errors->has('companion_job'))<span class="help-block">{{$errors->first('companion_job')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('companion_phone_num')) has-error @endif">
							  {!! Form::label('رقم التليفون') !!}
							   @if((($sub_type_entrypoint == "update_only") || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid))
							  {!! Form::text('companion_phone_num',$data[0]->companion_phone_num,array('class'=>'form-control','min'=>'1','onkeypress'=>'return isNumber(event)')) !!}
							  @else
								@if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
								{!! Form::text('companion_phone_num',$data[0]->companion_phone_num,array('class'=>'form-control','min'=>'1','onkeypress'=>'return isNumber(event)')) !!}
								@endif
								@endif
							  @if ($errors->has('companion_phone_num'))<span class="help-block">{{$errors->first('companion_phone_num')}}</span>@endif
							</div>
							@if($role_name=="GeneralRecept")
							<div class="form-group @if($errors->has('Companion_Ticket_Number')) has-error @endif">
							  {!! Form::label('رقم ايصال المرافق',null,array('style'=>'color:black')) !!}
							  @if((($sub_type_entrypoint == "update_only") || $sub_type_entrypoint == "entry_only") && isset($data[0]->vid)))
									{!! Form::text('Companion_Ticket_Number',$data[0]->Companion_Ticket_Number,array('class'=>'form-control','min'=>'1','onkeypress'=>'return isNumber(event)')) !!}
								@else
									@if(($sub_type_entrypoint == "update_only") || (($sub_type_entrypoint == "entry_only") && is_null($data[0]->vid)))
									{!! Form::text('Companion_Ticket_Number',null,array('class'=>'form-control','min'=>'1','onkeypress'=>'return isNumber(event)')) !!}
									@endif
								@endif		
							  @if ($errors->has('Companion_Ticket_Number'))<span class="help-block">{{$errors->first('Companion_Ticket_Number')}}</span>@endif
							</div>
							@endif
						  <br>
						  <div class="checkbox icheck">
							<label>
								<input type="checkbox" id="checkup" name="checkup" @if($data[0]->checkup==1) checked @endif />
								<b>كشف طبي</b>
							</label>
						  </div>
						  <br>
						 
						  
						  <div class="form-group @if($errors->has('file_number')) has-error @endif">
							  {!! Form::label('file_number','رقم الملف') !!}
							  @if(((($sub_type_entrypoint == "entry_only") && isset($data[0]->vid))))
								{!! Form::text('file_number',$data[0]->file_number,array('disabled','class'=>'form-control','placeholder'=>'رقم الملف','onkeypress'=>'return isNumber(event)')) !!}
							  @else
								  
								{!! Form::text('file_number',null,array('class'=>'form-control','placeholder'=>'رقم الملف','onkeypress'=>'return isNumber(event)')) !!}
							  @endif
							  @if ($errors->has('file_number'))<span class="help-block">{{$errors->first('file_number')}}</span>@endif
						  </div>
							<div class="form-group @if($errors->has('file_type')) has-error @endif">
									{!! Form::label('file_type','نوع الملف') !!}
									@if(isset($data[0]->file_type) && (($sub_type_entrypoint == "entry_only") && isset($data[0]->vid)))
										{!! Form::select('file_type',$file_types,$data[0]->file_type,['class' => 'form-control']); !!}
								  @else
										{!! Form::select('file_type',$file_types,'',['class' => 'form-control']); !!}
									@endif
									@if ($errors->has('file_type'))<span class="help-block">{{$errors->first('file_type')}}</span>@endif
							</div>
						  
						</div>

					</div>

					</div>
					<!-- /.box-body -->

				  <div class="box-footer">
					<button type="button" class="btn btn-primary" onclick="removeDisabled();" >@if($data[0]->vid !="") تحديث @else تسجيل @endif</button>
				  </div>
				{!! Form::close() !!}
			  </div>
            <!-- ./box -->
        </div>
        <!-- ./col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>

@endsection
@section('javascript')
<script>
$(document).ajaxStart(function(){
    $("#overlay").show();
});
$(document).ajaxComplete(function(){
    $("#overlay").hide();
});
$(document).ready(function(){
	//	if($visit_id ==-1)
	//{
	// $("#reference_doctor_id").prepend('<option></option>');
	// $("#reference_doctor_id").prop('selectedIndex',0);
	
	 $("#medical_id").change(function(){
		var url = "{{ url('/patients/getDepartmentDoctors/') }}";
		$.ajax({
			type: "POST",
			url: url,
			data: { 'mid':$("#medical_id").val() },
			success: function (data) {
				$("#reference_doctor_id").empty();
				$("#reference_doctor_id").prepend('<option></option>');
				$("#room_number").empty();
				$("#reference_doctor_id").prop('selectedIndex',0);
				if(data['success']=='true'){
				
					for (i=0;i<data['deps'].length;i++) {
						$("#reference_doctor_id").append("<option value='"+data['deps'][i].id+"'>"+data['deps'][i].name+"</option>");
					}
					for (i=0;i<data['rooms'].length;i++) {
						$("#room_number").append("<option value='"+data['rooms'][i].id+"'>"+data['rooms'][i].name+"</option>");
					}
				}
			},
			error: function (data) {
				alert("Error");
			}
		});
	});
	$('#sin').change(function(){
		$("#err_msg").hide();
		if($('#sin').val().length == 14){
						calculateBOD($('#sin').val());
		}
		else if($('#sid').val().length == 0){
			$("#datepicker").removeAttr("disabled");
		}
	});	
});

function calculateBOD(sid){
	var sid_string=sid;
	var prifx_year="";
	if(sid_string[0] == 2)
		prifx_year="19";
	else if(sid_string[0] == 3)
		prifx_year="20";
	else if(sid_string[0] == 4)
		prifx_year="21";
	else{
		$("#err_msg").html('رقم البطاقة غير صحيح');
		$("#err_msg").show();
		$("#sid").val("");
		$("#datepicker").removeAttr("disabled");
		return;
	}
		
	var year=prifx_year+""+sid_string[1]+""+sid_string[2];
	var month=sid_string[3]+""+sid_string[4];
	var day=sid_string[5]+""+sid_string[6];
	var date=year+"-"+month+"-"+day;
	
	var birthdate = new Date(date);
	var today = new Date();
	var diffYears = today.getFullYear() - birthdate.getFullYear(); 
	var diffMonths = today.getMonth() - birthdate.getMonth();
	var diffDays = today.getDate() - birthdate.getDate(); 
	
	if(isNaN(diffDays)){
		$("#age").val('');
		$("#err_msg").html('<b>رقم بطاقة المريض غير صحيح</b>');
		$("#err_msg").show();
		$("#submitButton").attr('disabled','true');
		return;	
	}
	if(diffDays < 0){
		diffMonths--;
		diffDays+=30;
	}
	if(isNaN(diffMonths)){
		$("#age").val('');
		$("#err_msg").html('<b>رقم بطاقة المريض غير صحيح</b>');
		$("#err_msg").show();
		$("#submitButton").attr('disabled','true');
		return;	
	}
	if(diffMonths < 0){
		diffMonths+=12;
		diffYears--;
	}

	$("#datepicker").val(date);
	$("#age").val(diffYears+" سنه -"+diffMonths+" شهر -"+diffDays+" يوم ");
}
function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}
// Function limits the size of SIN field
function isForteen(sid){
	if($("#"+sid).val().length >= 14)
		return false;
	return true;
}
function removeDisabled(){

	$("#entry_date").removeAttr('disabled');
	$("#entry_time").removeAttr('disabled');
	$("#medical_id").removeAttr('disabled');
	$("#patient_form").submit();
}
</script>
@endsection
