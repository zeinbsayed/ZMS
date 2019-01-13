@extends('layouts.app')
@section('title')
دليل المرضى
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
			<h1>
        دليل المرضى
        <small>دليل بيانات المرضى</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">دليل المرضي</li>
      </ol>
	 		
    </section>
    <!-- Main content -->
    <section class="content">
      <!-- Small boxes (Stat box) -->
      <div class="row">
        <div class="col-lg-12 col-xs-24">
          <!-- small box -->
			  <div class="box box-primary" dir="rtl">
				<div class="box-header with-border">
				  <h3 class="box-title">جدول البحث</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">

					<div class="row" >
					{!! Form::open(array('name'=>'patient_form')) !!}
					 
					 <div class="col-lg-6">
							<div class="form-group">
								{!! Form::label('رقم البطاقة') !!}
								{!! Form::text('sin',null,array('id'=>'sin','class'=>'form-control','placeholder'=>'رقم البطاقة','onkeypress'=>'return isNumber(event)&&isForteen("sin")')) !!}
							</div>
							<div class="form-group">
								{!! Form::label('تاريخ الميلاد') !!}
								{!! Form::text('birthdate',null,array('id'=>'datepicker','class'=>'form-control','placeholder'=>'تاريخ الميلاد')) !!}
							</div>
					 </div>
					 <div class="col-lg-6">
					 		
					    <div class="form-group">
						  	{!! Form::label('أسم المريض') !!}
						  	{!! Form::text('name',null,array('class'=>'form-control','placeholder'=>'أسم المريض')) !!}
							</div>
							<div class="form-group">
						  	{!! Form::label('كود المريض') !!}
						  	{!! Form::text('code',null,array('class'=>'form-control','placeholder'=>'كود المريض')) !!}
							</div>
							<div class="form-group">
						  	{!! Form::label('عنوان المريض') !!}
						  	{!! Form::text('address',null,array('class'=>'form-control','placeholder'=>'عنوان المريض')) !!}
							</div>
							<button type="submit" class="btn btn-primary">بحث <i class="fa fa-search"></i> </button>
							<a class="btn btn-info" href="{{ url('patients/show') }}">جديد <i class="fa fa-trash"></i> </a>
					 </div>
					{!! Form::close() !!}
					</div>
					<hr>
					<div class="row">
						<div class="col-lg-12">
							<h3>{{$table_header}}</h3>
							<table id="example1" class="table table-bordered table-hover">
								<thead>
								<tr>
								  <th>الكود</th>
								  <th>الأسم</th>
								  <th>النوع</th>
								  <th>العنوان</th>
								  <th>رقم البطاقة</th>
								  <th>تاريخ الميلاد</th>
								  <th>تاريخ و وقت التسجيل</th>
								  @if($role_name == 'Admin' || $role_name == 'SubAdmin')
									<th style="text-align:center">تعديل بيانات المريض</th>
								  @elseif($role_name == 'Entrypoint' || $role_name == 'Receiption' || $role_name =='GeneralRecept' || $role_name =='Private' || $role_name =='Injuires' )
									<th style="text-align:center">طباعة كارت المريض</th>
								  @endif
								  @if($role_name == 'Entrypoint' || $role_name =='GeneralRecept' || $role_name =='Private' || $role_name =='Injuires')
									<th style="text-align:center">إضافة دخول جديد</th>
								  @elseif($role_name == 'Receiption' || $role_name == 'Desk')
									<th style="text-align:center">إضافة كشف جديد</th>
								  @elseif($role_name == 'Doctor')
									<th style="text-align:center">سجل المريض</th>
								  @endif
								</tr>
								</thead>
								<tbody>
								@foreach($data as $row)
								<tr>
								  <td>{{$row->new_id}}</td>
								  <td>{{$row->name}}</td>
								  <td>{{$row->gender=='M'?'ذكر':'أنثي'}}</td>
								  <td>{{$row->address}}</td>
								  <td>{{$row->sin}}</td>
								  <td>{{$row->birthdate}}</td>
								  <td>{{$row->created_at}}</td>
								  @if($role_name == 'Admin' || $role_name == 'SubAdmin')
									<td style="text-align:center"><a href=' {{ url("admin/$row->id&-1/edit") }} '  class="btn btn-success">تعديل <i class="fa fa-edit"></i></a></td>
								  @elseif($role_name == 'Entrypoint' || $role_name == 'Receiption' || $role_name =='GeneralRecept' || $role_name =='Private' || $role_name =='Injuires')
									<td style="text-align:center"><a href='{{ url("/printpatientcard/$row->id") }}' target="_blank" class="btn btn-info">طباعة <i class="fa fa-print"></i></a></td>
								  @endif
									@if($role_name == 'Entrypoint'||$role_name == 'GeneralRecept' || $role_name =='Private' || $role_name =='Injuires')
										
										@if($row->hasOpenVisits == 1)
											<td style="text-align:center"><a href="visits/{{$row->id}}&-1" class="btn btn-success disabled">إضافة <i class="fa fa-plus"></i></a></td>
										@else
											<td style="text-align:center"><a href="visits/{{$row->id}}&-1" class="btn btn-success ">إضافة <i class="fa fa-plus"></i></a></td>
										@endif
									@elseif($role_name == 'Receiption')
									<td style="text-align:center"><a href='{{ url("/patients/reserve/$row->id") }}' class="btn btn-success">إضافة <i class="fa fa-plus"></i></a></td>
									@elseif($role_name == 'Desk')
									<td style="text-align:center"><a href='{{ url("/patients/desk/$row->id") }}' class="btn btn-success">إضافة <i class="fa fa-plus"></i></a></td>
									@elseif($role_name == 'Doctor')
									<td style="text-align:center"><a href='{{ url("/visits/printhistory/$row->id") }}'  target="_blank" class="btn btn-info"><i class="fa fa-print"></i></a></td>
								  @endif
								</tr>
								@endforeach
								</tbody>
							</table>
						</div> <!-- ./col-lg-12 -->
					</div> <!-- ./row -->
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
</script>
@endsection