<html>
<head>
	<meta charset="utf-8">
<style>
body{size: 21cm 29.7cm;
    margin: 30mm 45mm 30mm 45mm;}
	td,th{padding-top:10px; padding-bottom: 10px; padding-right: 30px;text-align: right;}
	th{width: 120px;}
	.head{font-weight: bold}
	table {border:1px solid black;width: 21cm;
    height: 150px; direction:rtl  }
	p{padding-top: 20px;}
	.secondtablecell{padding-right: 50px;}
	body{margin: 0 auto}
</style>
<title>بيانات المريض</title>
</head>
<body> <br>
<h2 align ="center">بيانات المريض</h2>
@if(count($data) == 0)
	 <p align ="center" > لا يوجد بيانات </p>
@else

<table align=center>
	<th>  -       بيانات المريض  </th>
	<tr>
		<th>كود المريض:</th><td>{{$data->patient->id}}</td>
	</tr>
	<tr>
		<th>أسم المريض:</th><td>{{$data->patient->name}}</td>
	</tr>
	<tr>
		<th>النوع:</th><td>{{$data->patient->gender=='M'?'ذكر':'أنثي'}}</td>
	</tr>
	<tr>
		<th>تاريخ الميلاد:</th><td>{{$data->patient->birthdate}}</td>
	</tr>
	<tr>
		<th>السن :</th><td>{{ calculateAge($data->patient->birthdate) }}</td>
	</tr>
	<tr>
		<th>رقم البطاقة:</th><td>{{$data->patient->sin}}</td>
	</tr>
	<tr>
		<th>العنوان:</th><td>{{$data->patient->address}}</td>
	</tr>
	<tr>
		<th>رقم الهاتف:</th><td>{{$data->patient->phone_num}}</td>
	</tr>
	<tr>
		<th>الحالة الاجتماعية:</th><td>{{$data->patient->social_status}}</td>
	</tr>
	<tr>
		<th>الوظيفة:</th><td>{{$data->patient->job}}</td>
	</tr>
	<tr>
		<th>الجنسية:</th><td>{{$data->patient->nationality}}</td>
	</tr>
	<tr>
		<th>المحافظة:</th><td>{{$government}}</td>
	</tr>
		<th>  -       بيانات الدخول  </th>
		@if($data->ticket_number !=null)
	<tr>
		<th>رقم التذكرة:</th><td>{{$data->ticket_number}}</td>
	</tr>
	@endif
	<tr>
		<th>تاريخ الدخول:</th><td>{{$data->entry_date}}</td>
	</tr>
	<tr>
		<th>ساعة الدخول:</th><td>{{$data->entry_time}}</td>
	</tr>
	<tr>
		<th>التشخيص المبدئ:</th><td>{{$data->entry_reason_desc}}</td>
	</tr>
	<tr>
		<th>نوع العلاج:</th><td>{{$data->cure_type->name}}</td>
	</tr>
	<tr>
		<th>جهة التعاقد:</th><td>{{$data->contract->name}}</td>
	</tr>
	<tr>
		<th>محول من:</th><td>{{$data->converted_from_relation->name}}</td>
	</tr>
	<tr>
		<th>اسم موظف الدخول:</th><td>{{$data->user->name}}</td>
	</tr>	
	@if($data->exit_status_id!=null)
		<th>  -   بيانات الخروج  </th>
	<tr>
		<th>الحالة عند الخروج :</th><td>{{$data->exit_status->name}}</td>
	</tr>
	<tr>
		<th> تاريخ الخروج :</th><td>{{$data->exit_date}}</td>
	</tr>
	<tr>
		<th> التشخيص النهائي :</th><td>{{$data->final_diagnosis}}</td>
	</tr>
	<tr>
		<th> التوصية :</th><td>{{$data->doctor_recommendation}}</td>
	</tr>
			</table>
	@endif
		<table align=center>
		<thead>
		<tr> 
		<th>القسم</th> 
		<th>الغرفة</th>
		<th>تاريخ التحويل</th>
		<th> اسم الموظف </th>
		<th> اسم الطبيب </th>
		</tr>
			<?php $count=1; ?>
			@foreach($data->medicalunits as $med_visit)
			<tr>
				<td>{{$med_visit->name}}</td>
				@foreach($med_visit->rooms as $room)
					@if($med_visit->pivot->room_id == $room->id)
						<td>{{ $room->name }} </td>
			@endif
			@endforeach
			<td>{{ $med_visit->pivot->conversion_date}}</td>
			<td> {{$data->user->name}}</td>
					<?php $iteration_num=1; ?>	
		@foreach($doctors as $doctor_name)
		@if($iteration_num==$count)
		@if($med_visit->id==26 || $med_visit->id==22)
			<td>{{$data->doctor_name}}</td>
		@elseif($doctor_name !=Null)
			
		   <td>{{$doctor_name[0]->name}}</td>
		@endif
		@endif
		<?php $iteration_num++ ?>
		@endforeach
		<?php $count++ ?>
		
		@endforeach
			</thead>
		</table>


	

@endif
</body>
</html>
