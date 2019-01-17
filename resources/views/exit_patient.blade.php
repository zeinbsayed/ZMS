@extends('layouts.app')
@section('title')
حجز تذكرة كشف عيادة
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
			<h1>
        بيانات المريض
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">خروج مريض</li>
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
				{!! Form::open(array('class'=>'form','name'=>'patient_form','id'=>'patient_form')) !!}
				  <div class="box-body">

					@if(Session::has('flash_message'))
						@if(Session::get('message_type')=='false')
							<div class="alert alert-danger">
						@else
							<div class="alert alert-success">
						@endif
								<b>{{ Session::get('flash_message') }} </b>
							</div>
					@endif
					<div class="alert alert-danger" style="display: none" id="err_msg"></div>
					<div class="row">
						<div class="col-lg-6" style="float:right">
							<div class="form-group">
							  {!! Form::label('كود المريض',null) !!}
							  {!! Form::text('id',$data[0]->new_id,array('disabled','class'=>'form-control','id'=>'pid','placeholder'=>'كود المريض')) !!}
							</div>
							<div class="form-group">
							  {!! Form::label('رقم البطاقة',null) !!}
							 {!! Form::text('sin',$data[0]->sin,array('disabled','size'=>'14','class'=>'form-control','id'=>'sin','placeholder'=>'رقم البطاقة')) !!}
							</div>
							<div class="form-group">
							  {!! Form::label('الأسم',null) !!} <br>
							  {!! Form::text('name',$data[0]->name,array('class'=>'form-control','disabled','id'=>'name')) !!}
							</div>
								@if($role_name=="GeneralRecept")
							<div class="form-group">
							  {!! Form::label('طبيب الاستقبال',null) !!}
								{!! Form::text('reception_doctor',$data[0]->doctor_name,array('disabled','class'=>'form-control','id'=>'reception_doctor')) !!}
							</div>
							@endif
							@if(count($medical_visit)>0)
							<div class="form-group">
							  {!! Form::label('القسم',null) !!}
								{!! Form::text('department',$medical_visit['0']->mname,array('disabled','class'=>'form-control','id'=>'department')) !!}
							</div>

							<div class="form-group">
							  {!! Form::label('طبيب الخروج',null,array('style'=>'color:red')) !!}
								{!! Form::text('doctor',$medical_visit['0']->uname,array('class'=>'form-control','id'=>'doctor','placeholder'=>'طبيب الخروج')) !!}
							</div>
							@endif
							<div class="form-group">
							  {!! Form::label('تاريخ الدخول',null) !!}
							  {!! Form::text('entry_date_show',$data[0]->entry_date,array('class'=>'form-control','id'=>'diagnosis','placeholder'=>'تاريخ الدخول','readonly')) !!}
							  {!! Form::hidden('entry_date',$data[0]->entry_date) !!}
							  {!! Form::hidden('room_id',$data[0]->room_id) !!}
							</div>
						</div>
						<div class="col-lg-6" style="float:right">
							<div class="form-group  @if ($errors->has('exit_time')) has-error @endif">
							   {!! Form::label('تاريخ الخروج',null,array('style'=>'color:red')) !!}
							 @if(isset($data))
							 {!! Form::text('exit_time',$data[0]->exit_date,array('id'=>'exit_date','class'=>'form-control','placeholder'=>'تاريخ الخروج')) !!}
										   @else
										   {!! Form::text('exit_time',null,array('id'=>'exit_date','class'=>'form-control','placeholder'=>'تاريخ الخروج')) !!}
							 @endif
							 @if ($errors->has('exit_time'))<span class="help-block">{{$errors->first('exit_time')}}</span>@endif
							</div>
							<div class="form-group @if ($errors->has('exit_status_id')) has-error @endif">
								{!! Form::label('الحالة عند الخروج',null,array('style'=>'color:red')) !!}
								@if(isset($data))
									{!! Form::select('exit_status_id',$exist_status,$data[0]->exit_status_id,array('id'=>'exit_status_id','class'=>'form-control')) !!}
								@else
									{!! Form::select('exit_status_id',$exist_status,null,array('id'=>'exit_status_id','class'=>'form-control')) !!}
								@endif
								@if ($errors->has('exit_status_id'))<span class="help-block">{{$errors->first('exit_status_id')}}</span>@endif
							</div>
							<div class="form-group @if ($errors->has('final_diagnosis')) has-error @endif">
							  {!! Form::label('التشخيص النهائي',null,array('style'=>'color:red')) !!}
							  @if(isset($data))
							  {!! Form::textarea('final_diagnosis',$data[0]->final_diagnosis,array('class'=>'form-control','id'=>'diagnosis','rows=4','coloumns=30','placeholder'=>'التشخيص النهائي')) !!}
							  @else
							  {!! Form::textarea('final_diagnosis',null,array('id'=>'address','class'=>'form-control','placeholder'=>'التشخيص النهائي')) !!}
							  @endif
							  @if ($errors->has('final_diagnosis'))<span class="help-block">{{$errors->first('final_diagnosis')}}</span>@endif
							</div>
							
							<div class="form-group">
							  {!! Form::label('التوصية',null) !!}
							  @if(isset($data))
							  {!! Form::textarea('doctor_recommendation',$data[0]->doctor_recommendation,array('class'=>'form-control','id'=>'diagnosis','rows=4','coloumns=30','placeholder'=>'التوصية')) !!}
							  @else
							  {!! Form::textarea('doctor_recommendation',null,array('id'=>'doctor_recommendation','class'=>'form-control','placeholder'=>'التوصية')) !!}
							  @endif
							 
							</div>
						</div>
					</div>
				  </div>
				  <!-- /.box-body -->

				  <div class="box-footer">
					   <button type="submit" class="btn btn-primary" >تسجيل</button>
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
</script>
@stop
