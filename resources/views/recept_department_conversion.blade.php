@extends('layouts.app')
@section('title')
 ”ÃÌ· »Ì«‰«  «·„—÷Ì
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
			<h1>
        »Ì«‰«  «·„—Ì÷
        <small> ”ÃÌ· »Ì«‰«  «·„—Ì÷</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> «·’›Õ… «·—∆Ì”Ì…</a></li>
        <li class="active"> ”ÃÌ· »Ì«‰«  «·„—÷Ì</li>
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
							·ÿ»«⁄… »ÿ«ﬁ… œŒÊ· <a href="printpatientdata/
							@if(Session::has('id'))	
								{{ Session::get('id') }}&{{ Session::get('vid') }}
							@endif"
							target="_blank"> «÷€ÿ Â‰« </a>
						@endif
						</div>
					@endif
					<div class="alert alert-danger" style="display: none"id="err_msg"></div>
					<div class="row">
						<div class="col-lg-3" style="float:right">
							<div class="form-group">
							
							  {!! Form::label('ﬂÊœ «·„—Ì÷',null) !!}
							  @if(old('patient_id') != "") 
							  {!! Form::text('id',null,array('class'=>'form-control','id'=>'pid','disabled','placeholder'=>'ﬂÊœ «·„—Ì÷','onkeypress'=>'return isNumber(event)')) !!}
							  @else
							  {!! Form::text('id',null,array('class'=>'form-control','id'=>'pid','placeholder'=>'ﬂÊœ «·„—Ì÷','onkeypress'=>'return isNumber(event)')) !!}
							  @endif
							  {!! Form::hidden('patient_id',null,array('id'=>'hidden_pid')) !!}
							</div>
							
							
							<div class="form-group @if($errors->has('name')) has-error @endif">
							  {!! Form::label('«·√”„',null,array('style'=>'color:red')) !!} <br>
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
							<div class="form-group @if($errors->has('sin')) has-error @endif">
							@if(old('patient_id') != "") 
							  {!! Form::label('—ﬁ„ «·»ÿ«ﬁ…',null) !!}
							  {!! Form::text('sin',null,array('size'=>'14','class'=>'form-control','disabled','id'=>'sin','placeholder'=>'—ﬁ„ «·»ÿ«ﬁ…','onkeypress'=>'return isNumber(event)&&isForteen("sin")')) !!}
							@else
							  {!! Form::label('—ﬁ„ «·»ÿ«ﬁ…',null) !!}
							  {!! Form::text('sin',null,array('size'=>'14','class'=>'form-control','id'=>'sin','placeholder'=>'—ﬁ„ «·»ÿ«ﬁ…','onkeypress'=>'return isNumber(event)&&isForteen("sin")')) !!}
							@endif
							  @if ($errors->has('sin'))<span class="help-block">{{$errors->first('sin')}}</span>@endif
							</div>	
						</div>
						<div class="col-lg-3" style="float:right">
							<fieldset>
								<legend>»Ì«‰«  «–‰ «·œŒÊ·</legend>
								<div class="form-group"  >
								   {!! Form::label('„ﬂ » «·œŒÊ·',null,array('style'=>'color:red')) !!}
								   {!! Form::select('entry_id',$entrypoints,null,['class' => 'form-control']); !!}
								</div>
								@if($role_name=="GeneralRecept")
								<div class="form-group">
								{!! Form::label('√”„ «·ﬁ”„',null,array('style'=>'color:black')) !!}
								{!! Form::select('medical_id',$medical_units,null,['id'=>'medical_id','class' => 'form-control','placeholder'=>'√Œ — «·ﬁ”„']); !!}
								</div>
								<div class="form-group">
								  {!! Form::label('room_number','√”„ «·€—›…',array('style'=>'color:black')) !!}
								  {!! Form::select('room_number',[],null,['id'=>'room_number','class' => 'form-control']); !!}
								</div>
								@else
								<div class="form-group  @if($errors->has('medical_id')) has-error @endif" >
								{!! Form::label('√”„ «·ﬁ”„',null,array('style'=>'color:red')) !!}
								{!! Form::select('medical_id',$medical_units,null,['id'=>'medical_id','class' => 'form-control','placeholder'=>'√Œ — «·ﬁ”„']); !!}
								@if ($errors->has('medical_id'))<span class="help-block">{{$errors->first('medical_id')}}</span>@endif
								</div>
								<div class="form-group @if($errors->has('room_number')) has-error @endif">
								  {!! Form::label('room_number','√”„ «·€—›…',array('style'=>'color:red')) !!}
								  {!! Form::select('room_number',[],null,['id'=>'room_number','class' => 'form-control']); !!}
								  @if ($errors->has('room_number'))<span class="help-block">{{$errors->first('room_number')}}</span>@endif
								</div>
								@endif
								<div class="form-group" >
								{!! Form::label('«·ÿ»Ì»',null) !!}
								{!! Form::select('reference_doctor_id',[],null,['id'=>'reference_doctor_id','class' => 'form-control']); !!}
								</div>
								<div class="form-group @if($errors->has('entry_date')) has-error @endif">
								  {!! Form::label('entry_date',' «—ÌŒ «·œŒÊ·',array('style'=>'color:red')) !!}
								  {!! Form::text('entry_date',null,array('class'=>'form-control','id'=>'entry_date')) !!}
								  @if ($errors->has('entry_date'))<span class="help-block">{{$errors->first('entry_date')}}</span>@endif
								</div>
								
								
							</fieldset>
						</div>
						
						

						</div>
						
					</div>
				  </div>
				  <!-- /.box-body -->
				
				  <div class="box-footer">
					<button type="submit" class="btn btn-primary" id="submitButton" onclick="removeDisabled();"> ”ÃÌ·</button>
					<input type="button" class="btn btn-success" onclick="location.reload();" value="ÃœÌœ"/>
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
						var birthdate = new Date($("#datepicker").val());
						var today = new Date();
						var diffYears = today.getFullYear() - birthdate.getFullYear(); 
						var diffMonths = today.getMonth() - birthdate.getMonth();
						var diffDays = today.getDate() - birthdate.getDate(); 
						
						if(isNaN(diffDays)){
							$("#age").val('');
							$("#err_msg").html('<b>—ﬁ„ »ÿ«ﬁ… «·„—Ì÷ €Ì— ’ÕÌÕ</b>');
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
							$("#err_msg").html('<b>—ﬁ„ »ÿ«ﬁ… «·„—Ì÷ €Ì— ’ÕÌÕ</b>');
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
								patient_age+=" ”‰Â ";
							else
								patient_age+=" ”‰Ê«  ";
						}
						else if(diffMonths > 0){
					
							patient_age=diffMonths;
							if(diffMonths >=11 )
								patient_age+=" ‘Â— ";
							else
								patient_age+=" ‘ÂÊ— ";
						}
						else if(diffDays > 0){
					
							patient_age=diffDays;
							if(diffDays >=11 )
								patient_age+=" ÌÊ„ ";
							else
								patient_age+=" √Ì«„ ";
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
						var birthdate = new Date($("#datepicker").val());
						var today = new Date();
						var diffYears = today.getFullYear() - birthdate.getFullYear(); 
						var diffMonths = today.getMonth() - birthdate.getMonth();
						var diffDays = today.getDate() - birthdate.getDate(); 
						
						if(isNaN(diffDays)){
							$("#age").val('');
							$("#err_msg").html('<b>—ﬁ„ »ÿ«ﬁ… «·„—Ì÷ €Ì— ’ÕÌÕ</b>');
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
							$("#err_msg").html('<b>—ﬁ„ »ÿ«ﬁ… «·„—Ì÷ €Ì— ’ÕÌÕ</b>');
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
								patient_age+=" ”‰Â ";
							else
								patient_age+=" ”‰Ê«  ";
						}
						else if(diffMonths > 0){
					
							patient_age=diffMonths;
							if(diffMonths >=11 )
								patient_age+=" ‘Â— ";
							else
								patient_age+=" ‘ÂÊ— ";
						}
						else if(diffDays > 0){
					
							patient_age=diffDays;
							if(diffDays >=11 )
								patient_age+=" ÌÊ„ ";
							else
								patient_age+=" √Ì«„ ";
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
		$("#err_msg").html('—ﬁ„ «·»ÿ«ﬁ… €Ì— ’ÕÌÕ');
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
		$("#err_msg").html('<b>—ﬁ„ »ÿ«ﬁ… «·„—Ì÷ €Ì— ’ÕÌÕ</b>');
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
		$("#err_msg").html('<b>—ﬁ„ »ÿ«ﬁ… «·„—Ì÷ €Ì— ’ÕÌÕ</b>');
		$("#err_msg").show();
		$("#submitButton").attr('disabled','true');
		return;	
	}
	if(diffMonths < 0){
		diffMonths+=12;
		diffYears--;
	}

	$("#datepicker").val(date);
	$("#age").val(diffYears+" ”‰Â -"+diffMonths+" ‘Â— -"+diffDays+" ÌÊ„ ");
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
	$("#patient_form").submit();
}
</script>
@endsection
