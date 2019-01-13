@extends('layouts.app')
@section('title')
تسجيل بيانات المرضي
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
			<h1>
        بيانات المريض
        <small>تسجيل بيانات المريض</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">تسجيل بيانات المرضي</li>
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

				<!-- form start -->
				
				{!! Form::open(array('class'=>'form','id'=>'patient_form', 'name'=>'patient_form')) !!}
				  <div class="box-body">
				   	
					@if(Session::has('flash_message'))
						@if(Session::get('message_type')=="true")
						<div class="alert alert-success">
						@else
						<div class="alert alert-danger">
						@endif
							<b>{{ Session::get('flash_message') }}</b>
						@if(Session::get('message_type')=="true")
							<br>
							لطباعة بطاقة دخول <a href="printpatientdata/
							@if(Session::has('id'))	
								{{ Session::get('id') }}&{{ Session::get('vid') }}
							@endif"
							target="_blank"> اضغط هنا </a>
						@endif
						</div>
					@endif
					<div class="alert alert-danger" style="display: none"id="err_msg"></div>
					<div class="row">
						<div class="col-lg-3" style="float:right">
							<div class="form-group">
							
							  {!! Form::label('كود المريض',null) !!}
							  @if(old('patient_id') != "") 
							  {!! Form::text('id',null,array('class'=>'form-control','id'=>'pid','disabled','placeholder'=>'كود المريض','onkeypress'=>'return isNumber(event)')) !!}
							  @else
							  {!! Form::text('id',null,array('class'=>'form-control','id'=>'pid','placeholder'=>'كود المريض','onkeypress'=>'return isNumber(event)')) !!}
							  @endif
							  {!! Form::hidden('patient_id',null,array('id'=>'hidden_pid')) !!}
							</div>
							
							
							<div class="form-group @if($errors->has('name')) has-error @endif">
							  {!! Form::label('الأسم',null,array('style'=>'color:red')) !!} <br>
							   @if(old('patient_id') != "")
								{!! Form::text('name',null,array('class'=>'form-control','disabled'=>'true','id'=>'name')) !!}
							   @else
								{!! Form::text('name',null,array('class'=>'form-control','id'=>'name','autocomplete'=>'off')) !!}
							   @endif
							   
							   @if ($errors->has('name'))
							  <span class="help-block">
								@if ($errors->has('name'))
									{{$errors->first('name')}}
								@endif
								</span>
							  @endif
							</div>
							<div class="form-group @if($errors->has('gender')) has-error @endif">
							  {!! Form::label('النوع',null,array('style'=>'color:red')) !!}
							  @if(old('patient_id') != "")
								{!! Form::select('gender',[''=>'أخترالنوع','M' => 'ذكر', 'F' => 'أنثى'], '',['disabled','class'=>'form-control','id'=>'gender']); !!}
							  @else
								{!! Form::select('gender',[''=>'أخترالنوع','M' => 'ذكر', 'F' => 'أنثى'], '',['class'=>'form-control','id'=>'gender']); !!}
							  @endif
							  @if ($errors->has('gender'))<span class="help-block">{{$errors->first('gender')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('sin')) has-error @endif">
							@if(old('patient_id') != "") 
							  {!! Form::label('رقم البطاقة',null) !!}
							  {!! Form::text('sin',null,array('size'=>'14','class'=>'form-control','disabled','id'=>'sin','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sin")')) !!}
							@else
							  {!! Form::label('رقم البطاقة',null) !!}
							  {!! Form::text('sin',null,array('size'=>'14','class'=>'form-control','id'=>'sin','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sin")')) !!}
							@endif
							  @if ($errors->has('sin'))<span class="help-block">{{$errors->first('sin')}}</span>@endif
							</div>
							
							<div class="form-group @if($errors->has('phone_num')) has-error @endif">
							  {!! Form::label('رقم التليفون',null) !!}
							  @if(old('patient_id') != "")
								{!! Form::text('phone_num',null,array('disabled','class'=>'form-control','min'=>'1','placeholder'=>'رقم التليفون','onkeypress'=>'return isNumber(event)','id'=>'phone_num')) !!}
							  @else
								{!! Form::text('phone_num',null,array('class'=>'form-control','min'=>'1','placeholder'=>'رقم التليفون','onkeypress'=>'return isNumber(event)','id'=>'phone_num')) !!}
							  @endif
							  @if ($errors->has('phone_num'))<span class="help-block">{{$errors->first('phone_num')}}</span>@endif
							</div>
							
							@if ($role_id==8||$role_id==12)
							<div class="form-group  @if($errors->has('address')) has-error  @endif">
						
							    {!! Form::label('رقم لوحة سيارة الاسعاف',null,array('style'=>'color:black')) !!}
								{!! Form::text('ambulance_number',null,array('class'=>'form-control','placeholder'=>'رقم لوحة سيارة الاسعاف','id'=>'ambulance_number')) !!}
							   @if ($errors->has('ambulance_number'))<span class="help-block">{{$errors->first('ambulance_number')}}</span>@endif
								
								</div>
								
							<div class="form-group @if($errors->has('address')) has-error @endif">
							  {!! Form::label('اسم المسعف',null,array('style'=>'color:black')) !!}
							  {!! Form::text('paramedic_name',null,array('class'=>'form-control','placeholder'=>'اسم المسعف','id'=>'paramedic_name')) !!}
							   @if ($errors->has('paramedic_name'))<span class="help-block">{{$errors->first('paramedic_name')}}</span>@endif
							</div>
							@endif
						</div>

						<div class="col-lg-3" style="float:right">
							
							
							<div class="form-group @if($errors->has('address')) has-error @endif">
							    {!! Form::label('العنوان',null,array('style'=>'color:red')) !!}
								{!! Form::text('address',null,array('class'=>'form-control','placeholder'=>'العنوان','id'=>'address')) !!}
							   @if ($errors->has('address'))<span class="help-block">{{$errors->first('address')}}</span>@endif
							</div>
							<div class="form-group  @if($errors->has('government_id')) has-error @endif">
								{!! Form::label('المحافظة',null,array('style'=>'color:red')) !!}
								{!! Form::select('government_id',$governments,null,['id'=>'government_id','class' => 'form-control','placeholder'=>'أختر المحافظة']); !!}
								@if ($errors->has('government_id'))<span class="help-block">{{$errors->first('government_id')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('birthdate')) has-error @endif">
							  {!! Form::label('birthdate','تاريخ الميلاد',array('style'=>'color:red')) !!}
							  @if(old('patient_id') != "") 
								{!! Form::text('birthdate',null,array('class'=>'form-control','id'=>'datepicker','disabled','placeholder'=>'1900-01-01')) !!}
							  @else
								{!! Form::text('birthdate',null,array('class'=>'form-control','id'=>'datepicker','placeholder'=>'1900-01-01')) !!}
							  @endif
							  @if ($errors->has('birthdate'))<span class="help-block">{{$errors->first('birthdate')}}</span>@endif
							</div>
							<div class="form-group">
							  {!! Form::label('السن',null,array('style'=>'color:red')) !!}
							  {!! Form::text('age',null,array('class'=>'form-control','min'=>'1','id'=>'age','onkeypress'=>'return isNumber(event)')) !!}
							</div>
							
							<div class="form-group @if($errors->has('job')) has-error @endif">
							  {!! Form::label('المهنة') !!}
							  {!! Form::text('job',null,array('class'=>'form-control','placeholder'=>'المهنة','id'=>'job')) !!}
							  @if ($errors->has('job'))<span class="help-block">{{$errors->first('job')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('social_status')) has-error @endif">
							  {!! Form::label('الحالة الاجتماعية') !!}
							  {!! Form::text('social_status',null,array('class'=>'form-control','placeholder'=>' الحالة الاجتماعية ','id'=>'social_status')) !!}
							  @if ($errors->has('social_status'))<span class="help-block">{{$errors->first('social_status')}}</span>@endif
							</div>
							@if ($role_id==8 || $role_id==12)
							<div class="form-group @if($errors->has('ticket_number')) has-error @endif">
							  {!! Form::label('رقم التذكرة',null,array('style'=>'color:red')) !!}
							  {!! Form::text('ticket_number',null,array('class'=>'form-control','placeholder'=>'رقم التذكرة','id'=>'ticket_number')) !!}
							   @if ($errors->has('ticket_number'))<span class="help-block">{{$errors->first('ticket_number')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('kateb_name')) has-error @endif">
							  {!! Form::label('توقيع كاتب التذكرة',null,array('style'=>'color:red')) !!}
							  {!! Form::text('kateb_name',null,array('class'=>'form-control','placeholder'=>'توقيع كاتب التذكرة','id'=>'kateb_name')) !!}
							   @if ($errors->has('kateb_name'))<span class="help-block">{{$errors->first('kateb_name')}}</span>@endif
							</div>
							@endif
						</div>
						<div class="col-lg-3" style="float:right">
							<fieldset>
								<legend>بيانات اذن الدخول</legend>
								<div class="form-group"  >
								   {!! Form::label('مكتب الدخول',null,array('style'=>'color:red')) !!}
								   {!! Form::select('entry_id',$entrypoints,null,['class' => 'form-control']); !!}
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
								<div class="form-group  @if($errors->has('medical_id')) has-error @endif" >
								{!! Form::label('أسم القسم',null,array('style'=>'color:red')) !!}
								{!! Form::select('medical_id',$medical_units,null,['id'=>'medical_id','class' => 'form-control','placeholder'=>'أختر القسم']); !!}
								@if ($errors->has('medical_id'))<span class="help-block">{{$errors->first('medical_id')}}</span>@endif
								</div>
								<div class="form-group @if($errors->has('room_number')) has-error @endif">
								  {!! Form::label('room_number','أسم الغرفة',array('style'=>'color:red')) !!}
								  {!! Form::select('room_number',[],null,['id'=>'room_number','class' => 'form-control']); !!}
								  @if ($errors->has('room_number'))<span class="help-block">{{$errors->first('room_number')}}</span>@endif
								</div>
								<div>
								  {!! Form::label('number_of_vacancy_beds','عدد الأسرة الشاغرة',array('style'=>'color:black')) !!}
								   {!! Form::text('number_of_vacancy_beds',null,array('class'=>'form-control','id'=>'number_of_vacancy_beds','disabled')) !!}
								</div>
								<div class="form-group" >
								{!! Form::label('الطبيب',null) !!}
								{!! Form::select('reference_doctor_id',[],null,['id'=>'reference_doctor_id','class' => 'form-control']); !!}
								</div>
								@endif
								
								<div class="form-group @if($errors->has('entry_date')) has-error @endif">
								  {!! Form::label('entry_date','تاريخ الدخول',array('style'=>'color:red')) !!}
								  {!! Form::text('entry_date',null,array('class'=>'form-control','id'=>'entry_date')) !!}
								  @if ($errors->has('entry_date'))<span class="help-block">{{$errors->first('entry_date')}}</span>@endif
								</div>
								
								
							</fieldset>
						</div>
						
						<div class="col-lg-3" style="float:right">
							<fieldset>
								<legend>بيانات اذن الدخول</legend>
								<div class="bootstrap-timepicker">
								  <div class="form-group @if($errors->has('entry_time')) has-error @endif">
									  {!! Form::label('entry_time','ساعة الدخول',array('style'=>'color:red')) !!}
									  {!! Form::text('entry_time',null,array('disabled','class'=>'form-control timepicker')) !!}
									  @if ($errors->has('entry_time'))<span class="help-block">{{$errors->first('entry_time')}}</span>@endif
								  </div>
								</div>
								<div class="bootstrap-timepicker">
								  <div class="form-group @if($errors->has('reg_time')) has-error @endif">
									  {!! Form::label('reg_time','ساعة الوصول',array('style'=>'color:red')) !!}
									  {!! Form::text('reg_time',null,array('disabled','class'=>'form-control timepicker')) !!}
									  @if ($errors->has('reg_time'))<span class="help-block">{{$errors->first('reg_time')}}</span>@endif
								  </div>
								</div>
								<div class="form-group @if($errors->has('contract_id')) has-error @endif">
							  {!! Form::label('جهة التعاقد',null,array('style'=>'color:red')) !!}
							  {!! Form::select('contract_id',$contracts,null,['class' => 'form-control']); !!}
							  @if ($errors->has('contract_id'))<span class="help-block">{{$errors->first('contract_id')}}</span>@endif
						  </div>
						  <div class="form-group @if($errors->has('converted_from')) has-error @endif">
							  {!! Form::label('converted_from','محول من',array('style'=>'color:red')) !!}
							  {!! Form::select('converted_from',$converted_from,null,['class' => 'form-control','placeholder'=>'--أختر--']); !!}
							   @if ($errors->has('converted_from'))<span class="help-block">{{$errors->first('converted_from')}}</span>@endif
						  </div>
						  @if($role_name=="Injuires")
						   <div class="form-group @if($errors->has('entry_reason_desc')) has-error @endif">
							  {!! Form::label('entry_reason_desc','تشخيص الدخول المبدئي',array('style'=>'color:red')) !!}
							  {!! Form::select('entry_reason_desc',['1'=>'ادعا ء طلق نارى','2'=>'ادعاء اعتداء من اخرين','3'=>'ادعاء حادث سيارة','4'=>'ادعاء حادث موتوسيكل','6'=>'ادعاء سقوط على الأرض','5'=>'ادعاء سقوط من على سلم ','7'=>'ادعاء سقوط من علو','8'=>'ادعاء سقوط من على دابة','9'=>'ادعاء اصطدام بجسم صلب','10'=>'ادعاء اصابة بألة حادة','11'=>'أخرى'],null,['class' => 'form-control']); !!}
							  <br>
							  {!! Form::textarea('entry_reason_desc_txt',null,array('disabled','id'=>'entry_reason_desc_txt','class'=>'form-control','rows'=>'2')) !!}
							  @if ($errors->has('entry_reason_desc'))<span class="help-block">{{$errors->first('entry_reason_desc')}}</span>@endif
						  </div>
						  @else
							  <div class="form-group @if($errors->has('entry_reason_desc')) has-error @endif">
							  {!! Form::label('entry_reason_desc','تشخيص الدخول المبدئي',array('style'=>'color:red')) !!}
							  {!! Form::textarea('entry_reason_desc',null,array('class'=>'form-control','rows'=>'2')) !!}
							  @if ($errors->has('entry_reason_desc'))<span class="help-block">{{$errors->first('entry_reason_desc')}}</span>@endif
						  </div>
						  @endif
						  
						  <div class="form-group @if($errors->has('cure_type_id')) has-error @endif">
						      {!! Form::label('نوع العلاج') !!}
						      {!! Form::select('cure_type_id',$cure_types,null,['class' => 'form-control']); !!}
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
								  {!! Form::text('person_relation_name',null,array('class'=>'form-control','placeholder'=>'الاسم','autocomplete'=>'off')) !!}
								  
								  @if ($errors->has('person_relation_name'))<span class="help-block">{{$errors->first('person_relation_name')}}</span>@endif
							  </div>
						
							<div class="form-group @if($errors->has('person_relation_phone_num')) has-error @endif">
							  {!! Form::label('رقم التليفون أقرب الاقارب',null) !!}
							  {!! Form::text('person_relation_phone_num',null,array('class'=>'form-control','id'=>'person_relation_phone_num','placeholder'=>'رقم التليفون','onkeypress'=>'return isNumber(event)')) !!}
							  @if ($errors->has('person_relation_phone_num'))<span class="help-block">{{$errors->first('person_relation_phone_num')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('person_relation_id')) has-error @endif">
							  {!! Form::label('درجة القرابة') !!}
							  {!! Form::select('person_relation_id',$relations,null,['class' => 'form-control']); !!}
							  @if ($errors->has('person_relation_id'))<span class="help-block">{{$errors->first('person_relation_id')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('companion_name')) has-error @endif">
							  {!! Form::label('أسم المرافق') !!}
							  {!! Form::text('companion_name',null,array('class'=>'form-control','placeholder'=>'الاسم','autocomplete'=>'off')) !!}
							  
							  @if ($errors->has('companion_name'))<span class="help-block">{{$errors->first('companion_name')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('companion_sid')) has-error @endif">
							  {!! Form::label('رقم بطاقة المرافق',null) !!}
							  {!! Form::text('companion_sid',null,array('class'=>'form-control','id'=>'companion_sid','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("companion_sid")')) !!}
							  @if ($errors->has('companion_sid'))<span class="help-block">{{$errors->first('companion_sid')}}</span>@endif
							</div>
							
						</div>
						<div class="col-lg-6" style="float:right">
							<div class="form-group @if($errors->has('companion_address')) has-error @endif">
							  {!! Form::label('محل اقامه المرافق') !!}
							  {!! Form::text('companion_address',null,array('class'=>'form-control','placeholder'=>'محل الاقامة')) !!}
							  @if ($errors->has('companion_address'))<span class="help-block">{{$errors->first('companion_address')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('companion_job')) has-error @endif">
							  {!! Form::label('مهنه المرافق') !!}
							  {!! Form::text('companion_job',null,array('class'=>'form-control','placeholder'=>'المهنة')) !!}
							  @if ($errors->has('companion_job'))<span class="help-block">{{$errors->first('companion_job')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('companion_phone_num')) has-error @endif">
							  {!! Form::label('رقم تليفون المرافق') !!}
							  {!! Form::text('companion_phone_num',null,array('class'=>'form-control','min'=>'1','onkeypress'=>'return isNumber(event)')) !!}
							  @if ($errors->has('companion_phone_num'))<span class="help-block">{{$errors->first('companion_phone_num')}}</span>@endif
							</div>
							@if($role_name=="GeneralRecept" || $role_name=="Injuires")
							<div class="form-group @if($errors->has('Companion_Ticket_Number')) has-error @endif">
							  {!! Form::label('رقم ايصال المرافق',null,array('style'=>'color:black')) !!}
							  {!! Form::text('Companion_Ticket_Number',null,array('class'=>'form-control','min'=>'1','onkeypress'=>'return isNumber(event)')) !!}
							  @if ($errors->has('Companion_Ticket_Number'))<span class="help-block">{{$errors->first('Companion_Ticket_Number')}}</span>@endif
							</div>
							@endif
						  
						  <br>
						  <div class="checkbox icheck">
							<label>
								<input type="checkbox" id="checkup" name="checkup" />
								<b>كشف طبي</b>
							</label>
						  </div>
						  <br>
						 
						 
						  <div class="form-group @if($errors->has('file_number')) has-error @endif">
							  {!! Form::label('file_number','رقم الملف') !!}
							  {!! Form::text('file_number',null,array('class'=>'form-control','placeholder'=>'رقم الملف','onkeypress'=>'return isNumber(event)','disabled')) !!}
							  @if ($errors->has('file_number'))<span class="help-block">{{$errors->first('file_number')}}</span>@endif
						  </div>
							<div class="form-group @if($errors->has('file_type')) has-error @endif">
								{!! Form::label('file_type','نوع الملف') !!}
								{!! Form::select('file_type',$file_types,null,['class' => 'form-control']); !!}
								@if ($errors->has('file_type'))<span class="help-block">{{$errors->first('file_type')}}</span>@endif
							</div>
						  
						  
						</div>
						
					</div>
				  </div>
				  <!-- /.box-body -->
				
				  <div class="box-footer">
					<button type="submit" class="btn btn-primary" id="submitButton" onclick="removeDisabled();">تسجيل</button>
					<input type="button" class="btn btn-success" onclick="location.reload();" value="جديد"/>
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

	$("#reference_doctor_id").prepend('<option></option>');
	$("#reference_doctor_id").prop('selectedIndex',0);
	if($("#sin").val().length==14)
		$("#datepicker").attr("disabled","disabled");
	
	$('#pid').change(function(){
		$("#err_msg").hide();
		if($('#pid').val()!=""){
			var url = "{{ url('/patients/getPatient/') }}";
			$.ajax({
				type: "POST",
				data: { 'pid':$("#pid").val() },
				url: url,
				success: function (data) {
					if(data['success']=='true'){
						
						$('#hidden_pid').val($('#pid').val());
						$("#sin").val(data['data'][0].sin);
  						$("#name").val(data['data'][0].name);
						$("#gender").val(data['data'][0].gender);
						$("#address").val(data['data'][0].address);
						$("#datepicker").val(data['data'][0].birthdate);
						$("#job").val(data['data'][0].job);
						$("#social_status").val(data['data'][0].social_status);
						$("#government_id").val(data['data'][0].patient_government_id);	
						var birthdate = new Date($("#datepicker").val());
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
						var patient_age;
						if(diffYears > 0){
							patient_age=diffYears;
							if(diffYears >=11 )
								patient_age+=" سنه ";
							else
								patient_age+=" سنوات ";
						}
						else if(diffMonths > 0){
					
							patient_age=diffMonths;
							if(diffMonths >=11 )
								patient_age+=" شهر ";
							else
								patient_age+=" شهور ";
						}
						else if(diffDays > 0){
					
							patient_age=diffDays;
							if(diffDays >=11 )
								patient_age+=" يوم ";
							else
								patient_age+=" أيام ";
						}
						$("#age").val(patient_age);
						$("#job").val(data['data'][0].job);
						$("#social_status").val(data['data'][0].social_status);
						$("#phone_num").val(data['data'][0].phone_num);
						
						$("#pid").attr('disabled','true');
						$("#name").attr('disabled','true');
						$("#sin").attr('disabled','true');
						$("#gender").attr('disabled','true');
						$("#address").attr('disabled','true');
						$("#datepicker").attr('disabled','true');
						$("#phone_num").attr('disabled','true');
						$("#government_id").attr('disabled','true');
					}
					else{
						$("#pid").val("");
						$('#hidden_pid').val("");
					}
				},
				error: function (data) {
					alert("Error");
				}
			});
		}
	});
    $('#sin').change(function(){
		$("#err_msg").hide();
		if($('#sin').val().length == 14){
			var url = "{{ url('/patients/showSID/') }}";
			$.ajax({
				type: "POST",
				url: url,
				data: { 'sin':$("#sin").val() , 'checkflag': 'false' },
				success: function (data) {
					if(data['success']=='true'){
						$('#hidden_pid').val(data['data'][0].id);
						$("#pid").val(data['data'][0].id);
  						$("#name").val(data['data'][0].name);
						$("#gender").val(data['data'][0].gender);
						$("#address").val(data['data'][0].address);
						$("#datepicker").val(data['data'][0].birthdate);
						$("#government_id").val(data['data'][0].government_id);
						var birthdate = new Date($("#datepicker").val());
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
						var patient_age;
						if(diffYears > 0){
							patient_age=diffYears;
							if(diffYears >=11 )
								patient_age+=" سنه ";
							else
								patient_age+=" سنوات ";
						}
						else if(diffMonths > 0){
					
							patient_age=diffMonths;
							if(diffMonths >=11 )
								patient_age+=" شهر ";
							else
								patient_age+=" شهور ";
						}
						else if(diffDays > 0){
					
							patient_age=diffDays;
							if(diffDays >=11 )
								patient_age+=" يوم ";
							else
								patient_age+=" أيام ";
						}
						$("#age").val(patient_age);
						$("#job").val(data['data'][0].job);
						$("#social_status").val(data['data'][0].social_status);
						$("#phone_num").val(data['data'][0].phone_num);
						
						$("#pid").attr('disabled','true');
						$("#name").attr('disabled','true');
						$("#sin").attr('disabled','true');
						$("#gender").attr('disabled','true');
						$("#datepicker").attr('disabled','true');
						$("#address").attr('disabled','true');
						$("#phone_num").attr('disabled','true');
						$("#government_id").attr('disabled','true');
					}
					else{
						$("#datepicker").attr("disabled","disabled");
						$("#age").attr("disabled","disabled");
						$("#submitButton").removeAttr('disabled');
						calculateBOD($('#sin').val());
						$("#pid").val("");
						$('#hidden_pid').val("");
						
					}
				},
				error: function (data) {
					alert("Error");
				}
			});
		
		}
		else if($('#sid').val().length == 0){
			$("#datepicker").removeAttr("disabled");
		}
	});
		$("#medical_id").change(function(){
		$("#err_msg").hide();
		var url = "{{ url('/patients/getDepartmentDoctors/') }}";
		$.ajax({
			type: "POST",
			url: url,
			data: { 'mid':$("#medical_id").val() },
			success: function (data) {
				$("#reference_doctor_id").empty();
				$("#reference_doctor_id").prepend('<option></option>');
				$("#room_number").empty();
				$("#room_number").prepend('<option></option>');
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
				alert(data);
			}
		});
	});
	
	//************************************************************
	$("#room_number").change(function(){
		$("#err_msg").hide();
		var url = "{{ url('/patients/getVacancyBedsNumber/') }}";
		$.ajax({
			type: "POST",
			url: url,
			data: { 'romnum':$("#room_number").val() },
			success: function (data) {
				//$("#number_of_vacancy_beds").empty();
				if(data['success']=='true'){
					$("#number_of_vacancy_beds").val(data['vacancy_beds'][0].number_of_vacancy_beds);
				}
			},
			error: function (data) {
				alert("Error");
			}
		});
	});


	$('#age').keyup(function(){
		if($('#age').val() != ""){
			var today=new Date();
			birthdate_year=today.getFullYear()-$('#age').val();
			$("#datepicker").val(birthdate_year+"-"+(today.getMonth()+1)+"-"+today.getDate());
		}
		else{
			$("#datepicker").val("");
		}
	});
	
	$('#entry_reason_desc').change(function(){
		//alert($('#entry_reason_desc').val());
		if($('#entry_reason_desc').val() != 11){
			$("#entry_reason_desc_txt").attr('disabled','true');
		}
		else
		{
			//alert($('#entry_reason_desc').val());
			
			$("#entry_reason_desc_txt").removeAttr('disabled');
			//$("#datepicker2").removeAttr('disabled');
		}
	});
})


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
// Function accepts numbers only
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
	
	$("#datepicker").removeAttr('disabled');
	$("#pid").removeAttr('disabled');
	$("#sin").removeAttr('disabled');
	$("#name").removeAttr('disabled');
	$("#phone_num").removeAttr('disabled');
	$("#gender").removeAttr('disabled');
	$("#address").removeAttr('disabled');
	$("#entry_date").removeAttr('disabled');
	$("#entry_time").removeAttr('disabled');
	$("#reg_time").removeAttr('disabled');
	$("#patient_form").submit();
}
</script>
@endsection
