@extends('layouts.app')
@section('title')
@if($role_name == 'Entrypoint')
	زيارات مكتب الدخول
@elseif(isset($ticket_and_entry))
	بيانات مرضي الدخول
@elseif($role_name == 'Desk')
	زيارات مكتب الاستقبال
@else
	زيارات مكتب حجز التذاكر
@endif
@endsection
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
			<h1>
			@if($role_name == 'Entrypoint')
				زيارات مكتب الدخول
			@elseif(isset($ticket_and_entry))
				بيانات مرضي الدخول
			@elseif($role_name == 'Desk')
				زيارات مكتب الاستقبال
			@else 
				زيارات مكتب حجز التذاكر
			@endif

      </h1>
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">
				@if($role_name == 'Entrypoint')
					زيارات مكتب الدخول
				@elseif(isset($ticket_and_entry))
					بيانات مرضي الدخول
				@elseif($role_name == 'Desk')
					زيارات مكتب الاستقبال
				@else
					زيارات مكتب حجز التذاكر
				@endif

				</li>
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
				  <h3 class="box-title">{{$table_header}}</h3>
				</div>
				<!-- /.box-header -->
				<div class="box-body">
					@if(Session::has('error'))
					<div class="alert alert-danger" >
						<a href="#"  class="close pull-left" data-dismiss="alert" aria-label="close">&times;</a> 
						<b>{{ Session::get('error') }}</b>
					</div>
					@endif
					@if($errors->has('department'))
					<div class="alert alert-danger" >
						<b>{{ $errors->first('department') }}</b>
					</div>
					@endif
					@if(Session::has('success'))
						<div class="alert alert-success alert-dismissible">
							<a href="#"  class="close pull-left" data-dismiss="alert" aria-label="close">&times;</a> 
							<b>{{ Session::get('success') }}</b>
						</div>
					@endif
					<div class="row">
						{!! Form::open(array('name'=>'patient_form')) !!}
						 <div class="col-lg-6">
							<div class="form-group">
							{!! Form::label('نوع العلاج') !!}
							{!! Form::select('cure_type',$cure_types,null,array('class'=>'form-control','placeholder'=>'')) !!}
							</div>
							{!! Form::label('تاريخ الدخول') !!}
							<div class="form-group">
								{!! Form::label('من') !!}
								{!! Form::text('fromdate',null,array('id'=>'datepicker','class'=>'form-control')) !!}
							</div>
							<div class="form-group">
								{!! Form::label('الي') !!}
								{!! Form::text('todate',null,array('id'=>'datepicker2','class'=>'form-control')) !!}
							</div>
						 </div>
						 <div class="col-lg-6">
							<div class="form-group">
							{!! Form::label('كود المريض') !!}
							{!! Form::text('code',null,array('class'=>'form-control','placeholder'=>'كود المريض')) !!}
							</div>
							<div class="form-group">
							{!! Form::label('أسم المريض') !!}
							{!! Form::text('name',null,array('class'=>'form-control','placeholder'=>'أسم المريض')) !!}
							</div>
							<div class="form-group">
							{!! Form::label('عنوان المريض') !!}
							{!! Form::text('address',null,array('class'=>'form-control','placeholder'=>'عنوان المريض')) !!}
							</div>
							<div class="form-group">
							{!! Form::label('أسم القسم') !!}
							{!! Form::select('department',$departments,null,array('class'=>'form-control','placeholder'=>'')) !!}
							</div>
							<button type="submit" class="btn btn-primary">بحث <i class="fa fa-search"></i> </button>
							<a class="btn btn-info" href="{{ url('patients/showinpatient') }}">جديد <i class="fa fa-trash"></i> </a>
						 </div>
						{!! Form::close() !!}
						<div class="col-lg-12">
							<table id="entry_visits_datatable" class="table table-bordered table-hover">
								<thead>
								<tr>
									@if($role_name != 'Entrypoint')
									 <th style="text-align:center">رقم التذكرة</th>
									@endif
									@if($role_name == 'Desk')
									 <th style="text-align:center">نوع التذكرة</th>
									@endif
								  <th style="text-align:center">كود المريض</th>
								  <th style="text-align:center">أسم المريض</th>
								  <th style="text-align:center">عنوان المريض</th>
								  <th style="text-align:center">{{ $role_name  == 'Entrypoint' || isset($ticket_and_entry) ?'أسم القسم':'أسم العيادة' }}</th>
								  @if($role_name  == 'Entrypoint' ||  isset($ticket_and_entry)||$role_name  == 'GeneralRecept')
									<th style="text-align:center">ساعة دخول المريض</th>
									<th style="text-align:center">تاريخ الدخول</th>
								  @else
									<th style="text-align:center">تاريخ الكشف</th>
								  @endif
								  @if($role_name  == 'Entrypoint' || isset($ticket_and_entry))

									<th style="text-align:center">تاريخ الخروج</th>
									@if($role_name  != 'Receiption')
									<th style="text-align:center">أسم الغرفة</th>
									<th style="text-align:center">نوع العلاج</th>
									<th style="text-align:center">جهة التعاقد</th>
									  @if($role_name  != 'Receiption')
										<th style="text-align:center">طباعة</th>
										@if((!is_null($sub_type_entrypoint)) &&( ($sub_type_entrypoint != "exit_only")))
										<th style="text-align:center">تحديث ملف المريض</th>	
										@if($role_name=='Entrypoint'||$role_name=='Private'||$role_name=='Injuiries')
										<th style="text-align:center">تحويل الي قسم أخر</th>
										@endif
										@endif
										 @if($role_name=="GeneralRecept" && ( $sub_type_entrypoint == "entry_only" ||$sub_type_entrypoint == "exit_only"))									
										<th style="text-align:center">تحويل الى قسم أخر</th>	
										@endif
										@if(!is_null($sub_type_entrypoint) && ($sub_type_entrypoint == "exit_only" || $sub_type_entrypoint == "entry_and_exit"))
										<th style="text-align:center">تسجيل خروج</th>
										@endif
									  @endif
									  <th style="text-align:center">المزيد من التفاصيل</th>
									@endif
								  @else
									<th style="text-align:center">تسجيل كشف جديد</th>
								  @if($role_name  == "Receiption" )
									<th style="text-align:center">التحويل الي مكتب الاستقبال</th>
							      @endif
									<th style="text-align:center">ألغاء الحجز</th>
								  @endif
								</tr>
								</thead>
								<tbody>
								<?php $i=0; ?>
								@foreach($data as $row)
								<tr id="row{{$i}}">
									@if($role_name  != 'Entrypoint' )
										 <td>{{$row->ticket_number}}</td>
									@endif
									@if($role_name  == 'Desk' )
										 <td>{{$row->ticket_type!=null?($row->ticket_type=="G"?'استقبال عام':'استقبال اصابات'):""}}</td>
									@endif 
									
									<td>{{$row->patient->new_id}}</td>
									<td>{{$row->patient->name}}</td>
									<td>{{$row->patient->address}}</td>
									<td>
									@if(count($row->medicalunits)!=0)
										{{ $row->medicalunits[0]->name }}
									@endif
									</td>
									@if($role_name  == 'Entrypoint' ||  isset($ticket_and_entry))
										<td>
												@if (strrpos($row->entry_time,"AM") !== false)
														{{ str_replace('AM', 'ص', $row->entry_time) }}
												@elseif (strrpos($row->entry_time,"PM") !== false)
														{{ str_replace('PM', 'م', $row->entry_time) }}
												@endif
										</td>
										<td>{{$row->entry_date}}</td>
									@elseif($role_name=="Desk")
										<td>{{$row->registration_datetime}}</td>
									@else
										<td>{{$row->created_at}}</td>
									@endif
									@if($role_name  == 'Entrypoint' ||  isset($ticket_and_entry))
									 <td>{{$row->exit_date}}</td>
									 @if($role_name  != 'Receiption')
									 <td>
									 @if(count($row->medicalunits)!=0)
										@foreach($row->medicalunits[0]->rooms as $room)
											@if($row->medicalunits[0]->pivot->room_id == $room->id)
												 {{ $room->name }}
											@endif
										@endforeach
									@endif
									</td>

									 <td>{{ isset($row->cure_type)?$row->cure_type->name:''}}</td>
									 <td>{{isset($row->contract->name)?$row->contract->name:''}}</td>
										@if($role_name  != 'Receiption')
										 <td style="text-align:center">
											<div class="btn-group">
											<a class="btn btn-info dropdown-toggle" title="طباعة" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="caret"></span> <i class="fa fa-print"></i> </a>
											 <ul class="dropdown-menu">
												@if((is_null($sub_type_entrypoint) || ($sub_type_entrypoint == "entry_only" || $sub_type_entrypoint == "entry_and_exit")))
													<li><a href='{{ url("visits/showinpatient_file/{$row->id}") }}'  target="_blank" >بيانات الملف</a></li>
													<li><a href='{{ url("printpatientdata/{$row->patient->id}&{$row->id}") }}'  target="_blank" >اذن الدخول</a></li>
												@endif
												@if(!is_null($sub_type_entrypoint) && $sub_type_entrypoint == "entry_and_exit")
													<!--<li><a href='{{ url("visits/showinpatient_diagnoses/{$row->id}") }}'  target="_blank" >ملف التشخيص</a></li>-->
												@endif
												@if(!is_null($sub_type_entrypoint) && ($sub_type_entrypoint == "exit_only" || $sub_type_entrypoint == "entry_and_exit"))
													@if($row->closed == true)
													<li><a href='{{ url("patients/exit_visit_report/{$row->id}") }}' target="_blank">الخروج</a></li>
													<li><a href='{{ url("patients/visit_medReport/{$row->id}")}}' target="_blank">تقرير طبى</a></li>
													@endif
												@endif
												
											  </ul>
											 </div>
										 </td>
										<!-- <td>@if($row->closed == true) {{$row->updated_at}} @endif</td> -->
											@if((!is_null($sub_type_entrypoint)) && ($sub_type_entrypoint != "exit_only"))
											 <td style="text-align:center">
												<a href='{{ url("/patients/visits/{$row->patient->id}&{$row->id}") }}'
												@if(($row->closed == true) ||(($role_name=="GeneralRecept") && count($row->medicalunits)>1)||(($role_name=="Entrypoint") && count($row->medicalunits)==1 && $row->user->role_id==8)))
													class="btn btn-success disabled " title="تحديث ملف المريض"
												@else
													class="btn btn-success" title="تحديث ملف المريض"
												@endif
												><i class="fa fa-edit"></i></a>
											 </td>
												@if($role_name=="0")
											 <td style="text-align:center">
												<a href='#'
												data-toggle="modal" data-target="#myModal{{$row->id}}"
												@if((count($row->medicalunits)==0)||($row->closed == true && $sub_type_entrypoint == "entry_only"))
													class="btn btn-primary" disabled=true title="التحويل الى قسم اخر"
												@else
												 
													class="btn btn-primary" title="التحويل الى قسم اخر"
												@endif
												>
												<i class="fa fa-undo"></i></a>
												<!-- Modal -->
												  <div class="modal fade" id="myModal{{$row->id}}" role="dialog">
													<div class="modal-dialog modal-sm">
													  <div class="modal-content">
														<div class="modal-header">
														  <button type="button" class="close" data-dismiss="modal">&times;</button>
														  <h4 class="modal-title">التحويل الى قسم اخر</h4>
														</div>
														{!! Form::open(array('action'=>'PatientController@convert_to_another_department')) !!}
														<div style="text-align:right" class="modal-body">
															<div class="form-group">
																{!! Form::label('أسم القسم') !!}
																{!! Form::select('department',$departments,null,array('onchange'=>"changeRoom($row->id)",'class'=>'form-control','id'=>"dep_$row->id")) !!}
																{!! Form::hidden('v_id',$row->id) !!}
																@if(count($row->medicalunits)!=0)
																{!! Form::hidden('current_dep',$row->medicalunits[0]->id) !!}
																@endif
																
															</div><br><br>
															<div class="form-group">
																{!! Form::label('أسم الغرفة') !!}
																{!! Form::select('room_number',$first_rooms,null,array('class'=>'form-control','id'=>"room_$row->id")) !!}
																@if(count($row->medicalunits)!=0)
																{!! Form::hidden('current_room',$row->medicalunits[0]->pivot->room_id) !!}
																@endif
															</div><br><br>
															<div class="form-group" >
															{!! Form::label('الطبيب',null) !!}
															{!! Form::select('reference_doctor_id',[],null,['id'=>'reference_doctor_id','class' => 'form-control','placeholder'=>'أختر الطبيب']); !!}
															</div><br><br>
															<div class="form-group">
															  {!! Form::label('تاريخ التحويل') !!}
															  {!! Form::text('convert_date',null,array('class'=>'form-control','id'=>'datepicker')) !!}
															</div>
														</div>
														<div class="modal-footer">.
														  <button type="submit" class="btn btn-primary" >تحويل</button>
														  <button type="button" class="btn btn-default" data-dismiss="modal">الغاء</button>
														</div>
														{!! Form::close() !!}
													  </div>
													</div>
												  </div>
												 <!-- ./Model -->
											 </td>
											 @endif
											 @endif
											 @if($role_name=="GeneralRecept" ||  $sub_type_entrypoint == "entry_only")
											  <td style="text-align:center">
												
												@if(($row->closed == true) ||(($role_name=="GeneralRecept") && count($row->medicalunits)>1))
												<a href=''
													class="btn btn-primary  disabled " title="تحويل الى قسم أخر"><i class="fa fa-undo"></i></a>
												@else
												<a href='{{ url("/patients/visit_dept/{$row->patient->id}&{$row->id}") }}'
													class="btn btn-primary"  title="تحويل الى قسم أخر"
												
												><i class="fa fa-undo"></i></a>
												@endif
											 </td>
											 @endif
											 @if(!is_null($sub_type_entrypoint) && ($sub_type_entrypoint == "exit_only" || $sub_type_entrypoint == "entry_and_exit"))
											 <td style="text-align:center">
												<a href='{{ url("patients/visit_exit/{$row->patient->id}&{$row->id}") }}'
												@if((($row->closed == true)||(($role_name=="GeneralRecept") && count($row->medicalunits)>1)) && $sub_type_entrypoint == "exit_only")
													class="btn btn-danger disabled" title="تسجيل خروج"
												@else
													class="btn btn-danger" title="تسجيل خروج"
												@endif
												>
													<i class="fa fa-sign-out"></i>
												</a>
											 </td>
											 @endif
										@endif
										 <td style="text-align:center">
												<a href='{{ url("patients/show_details/{$row->patient->id}&{$row->id}") }}'
													class="btn btn-warning" title="تسجيل خروج">
													<i class="glyphicon glyphicon-info-sign"></i>
												</a>
											 </td>
									  @endif
									@else
									<td style="text-align:center">
										<a href='@if($role_name  == "Desk" ) {{ url("/patients/desk/$row->id") }} @else {{ url("/patients/reserve/$row->id") }} @endif' title="تسجيل كشف جديد" class="btn btn-success"><i class="fa fa-plus"></i></a></td>
									</td>
									@if($role_name  == "Receiption" )
									<td style="text-align:center">
										<div class="btn-group">
											<button class="btn btn-info dropdown-toggle" @if($row->closed == true ) disabled title='تم أنهاء الزيارة'
											@elseif($row->created_at->format('Y-m-d') < date('Y-m-d',time())) disabled title='تاريخ الزيارة قد سبق'
											@elseif($row->convert_to_entry_id) disabled title="تم التحويل الي مكتب استقبال" @else title="التحويل الي مكتب الاستقبال" @endif data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											<span class="caret"></span> <i class="fa fa fa-arrow-left"></i> </button>
											 <ul class="dropdown-menu">
												@foreach($desks as $desk)
													<li><a href='{{ url("patients/convertvisit/$desk->id&$row->visit_id") }}'
														onclick="if(confirm('هل تريد تحويل المريض الي مكتب الاستقبال ؟')){return true;}else{return false;}"
														>{{$desk->name}}</a></li>
												@endforeach
											  </ul>
										 </div>
								    </td>
									@endif
									<td style="text-align:center">
										<a href='{{ url("/patients/cancelvisit/$row->visit_id") }}'
										@if($row->closed == true )
											 title="تم أنهاء الزيارة"
										@elseif($row->created_at->format('Y-m-d') < date('Y-m-d',time()))
										  title="تاريخ الزيارة قد سبق"
										@elseif($row->convert_to_entry_id && $role_name != "Desk" )
										  title="تم التحويل الي مكتب استقبال"
										@else
											title="الغاء الحجز"
										@endif
										onclick="if(confirm('هل تريد ألغاء هذا الحجز ؟ ')){return true;}else{return false;}"
										class="btn
										@if($row->closed == true )
											disabled
										@elseif($row->convert_to_entry_id && $role_name != "Desk")
											disabled
										@elseif($row->created_at->format('Y-m-d') < date('Y-m-d',time()))
											disabled
										@endif btn-danger" ><i class="fa fa-close"></i></a></td>
									</td>
									@endif

								</tr> 
								<?php $i++;?>
							
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
function changeRoom(row_id){
	var url = "{{ url('/patients/getDepartmentDoctors/') }}";
	$.ajax({
		type: "POST",
		url: url,
		data: { 'mid':$("#dep_"+row_id).val() },
		success: function (data) {
				$("#room_"+row_id).empty();
				$("#reference_doctor_id").empty();
				$("#reference_doctor_id").append("<option> أختر الطبيب </option>");
			if(data['success']=='true'){
				//alert(data['rooms'][1].id);
					for (i=0;i<data['deps'].length;i++) {
						$("#reference_doctor_id").append("<option value='"+data['deps'][i].id+"'>"+data['deps'][i].name+"</option>");
					}
				for (i=0;i<data['rooms'].length;i++) {
					$("#room_"+row_id).append("<option value='"+data['rooms'][i].id+"'>"+data['rooms'][i].name+"</option>");
				}
			}
		},
		error: function (data) {
			alert("Error");
		}
	});
}
</script>
@stop