@extends('layouts.app')
@section('title')
احصائية المحافظات
@endsection
@section('content')

<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
			<h1>
        استخراج احصائية للمرضى خارج أسيوط
      </h1>
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
				
				<!--{!! Form::open(array('class'=>'form','id'=>'patient_form', 'name'=>'patient_form')) !!}!-->
				  <div class="box-body">
					<div class="alert alert-danger" style="display: none"id="err_msg"></div>
					<div class="row">
				<!--	<form id="myform" action="/patients/governmentpatientreport" method="post"> -->
					<form id="myform" action="{{url('/patients/governmentpatientreport')}}" method="post"> 
					{{ csrf_field() }}
						<div class="col-lg-6" style="float:right">
							<br>
							<div class="form-group">
							{!! Form::label(' التاريخ')!!}   
						{{--	{!! Form::select('selectdate',['1'=>'أمس','2'=>'الأسبوع الماضى','3'=>'الشهر الماضى','4'=>'العام الماضى','5'=>'تحديد تاريخ'],array('class'=>'form-control asd','id'=>'datemenu','onChange=>changetextbox')); !!} --}}
							{!! Form::select('selectdate',array ('1'=>'أمس','2'=>'الأسبوع الماضى','3'=>'الشهر الماضى','4'=>'العام الماضى','5'=>'تحديد تاريخ'),'1',array('id'=>'selectdate','class'=>'form-control')) !!}
							</div>
							<br>
						<!--	<div class="form-group">
							<select name="selectmenu" id="menu" onChange="textbox_enable">
							<option value="1">أمس</option>
							<option value="2">الأسبوع الماضى</option>
							<option value="3">الشهر الماضى</option>
							<option value="4">العام الماضى</option>
							<option value="5">تحديد تاريخ</option>
							</select>
							</div>!-->
							<div class="form-group @if($errors->has('fromdate')) has-error @endif">
								{!! Form::label('بداية الفترة',null,array('style'=>'color:red')) !!}
								{!! Form::text('fromdate',null,array('id'=>'datepicker','class'=>'form-control')) !!}
								@if ($errors->has('fromdate'))<span class="help-block">{{$errors->first('fromdate')}}</span>@endif
							</div>
							<div class="form-group @if($errors->has('todate')) has-error @endif">
								{!! Form::label('نهاية الفترة',null,array('style'=>'color:red')) !!}
								{!! Form::text('todate',null,array('id'=>'datepicker2','class'=>'form-control')) !!}
								@if ($errors->has('todate'))<span class="help-block">{{$errors->first('todate')}}</span>@endif
							</div>
							<button type="submit" class="btn btn-info"> طباعة</button>
						 </div>
					</div>
					<div>
					  <br>
					  <br> <br>  <br> <br><br> <br><br> <br><br><br><br><br>
					  <br>
					</div>
						</div>
					</div>
				  <!-- /.box-body -->
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

/*	$("#reference_doctor_id").prepend('<option></option>');
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
	});*/
	//changetextbox(function(){
		
		if($('#selectdate').val() != 5){
		$("#datepicker").attr("disabled", "disabled");
			$("#datepicker2").attr("disabled", "disabled");
		}
	$('#selectdate').change(function(){
		if($('#selectdate').val() != 5){
			//$("#datepick").removeAttr('disabled');
			$("#datepicker").attr("disabled", "disabled");
			$("#datepicker2").attr("disabled", "disabled");
		}
		else
		{
			$("#datepicker").removeAttr('disabled');
			$("#datepicker2").removeAttr('disabled');
		}
	});
	/* $('#selectdate').change(function(){
		if($('#selectdate').val() != 5){
			
		}
	}); */
})

function textbox_enable()
{
    if (document.myform.selectmenu.value ==5) {
		
        document.getElementById("datepick").disable='true';
    } else {
        document.getElementById("datepick2").disable='true';
    }
}
/*function calculateBOD(sid){
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
}*/
// Function accepts numbers only
/*function isNumber(evt) {
    evt = (evt) ? evt : window.event;
    var charCode = (evt.which) ? evt.which : evt.keyCode;
    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
        return false;
    }
    return true;
}*/
// Function limits the size of SIN field 
/*function isForteen(sid){
	if($("#"+sid).val().length >= 14)
		return false;
	return true;
}*/
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
