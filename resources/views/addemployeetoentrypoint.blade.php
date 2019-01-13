@extends('layouts.app')
@section('title')
أضافة مستخدم الي مكتب
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
		<h1>
        أضافة مستخدم الي مكتب
      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">إدارة مكاتب مستخدمي النظام</li>
        <li class="active">أضافة مستخدم الي مكتب</li>
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
				{!! Form::open(array('class'=>'form','name'=>'user_form')) !!}
				  <div class="box-body">
				   	
					@if(Session::has('flash_message'))
						@if(Session::get('message_type') == "false")
							<div class="alert alert-danger">
						@else
							<div class="alert alert-success">
						@endif
							<b>{{ Session::get('flash_message') }} </b>
						</div>
					@endif
					
					<div class="alert alert-danger" style="display: none" id="err_msg"></div>
					<div class="row">
						<div class="col-lg-12" style="float:right">
							@if ($errors->has('emp_name'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('أسم الموظف',null,array('style'=>'color:red')) !!}
							  {!! Form::select('emp_name',$employees,null,['class' => 'form-control']); !!}
							 
							  @if ($errors->has('emp_name'))<span class="help-block">{{ $errors->first('emp_name') }}</span>@endif
							</div>
							
							
							@if ($errors->has('entrypoint'))
								<div class="form-group has-error">
							@else
								<div class="form-group">
							@endif
							  {!! Form::label('أسم المكتب',null,array('style'=>'color:red')) !!}
							  {!! Form::select('entrypoint',$entrypoints,null,['class' => 'form-control']); !!}
							 
							  @if ($errors->has('entrypoint'))<span class="help-block">{{ $errors->first('entrypoint') }}</span>@endif
							</div>
							
						</div> <!-- col-lg-12 -->
					</div><!-- row -->
				  </div>
				  <!-- /.box-body -->
				
				  <div class="box-footer">
					<button type="submit" class="btn btn-primary" id="submit">أضافة</button>
				  </div>
				{!! Form::close() !!}
				</div>
				 
				<!-- ./box -->
			</div>
			<!-- ./col -->
		  </div> <!-- ./box -->
		  
		  <div class="box" dir="rtl">
			<div class="box-body">
				<div class="row">
					<div class="col-lg-12">
						<table id="example2" class="table table-bordered table-hover">
							<thead>
							<tr>
							  <th style="text-align:center">أسم المكتب</th>
							  <th style="text-align:center">الموظف</th>
							  <th style="text-align:center">البريد الألكتروني</th>
							  <th style="text-align:center">ألغاء الأضافة</th>
							</tr>
							</thead>
							<tbody>
								<?php $i=0; ?>
								@foreach($employee_users as $row)
								<tr id="row{{$i}}">
								  <td>{{$row->entrypointname}}</td>
								  <td>{{$row->username}}</td>
								  <td>{{$row->email}}</td>
								  <td align="center"><a href='{{ url("/admin/delete_entry_user/$row->user_id&$row->entry_id") }}'
										class="btn btn-danger" onclick="if(confirm('هل تريد ألغاء هذة الأضافة ؟ ')){return true;}else{return false;}" ><i class="fa fa-close"></i></a></td>
								</tr>
								<?php $i++;?>
								@endforeach
							</tbody>
						</table>
					</div> <!-- ./col-lg-12 -->
				</div>  <!-- /.row -->
			</div> <!-- ./box body -->
			</div> <!-- ./box -->
				 
				<!-- ./box -->
		  </div>
		
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
@endsection

