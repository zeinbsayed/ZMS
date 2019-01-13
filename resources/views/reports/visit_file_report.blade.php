<html>
<head>
	<meta charset="utf-8">
<style>
body{size: 21cm 29.7cm;
    margin: 30mm 45mm 30mm 45mm;}
	td,th{padding-top:10px; padding-bottom: 10px; padding-right: 30px;text-align: right;}
	td{padding-right:0;}
	th{width: 138px;}
	.head{font-weight: bold}
	table {border:1px solid black;width: 21cm;
    height: 150px; direction:rtl  }
	p{padding-top: 20px;}
	.secondtablecell{padding-right: 50px;}
	body{margin: 0 auto}
</style>
<title>بيانات ملف المريض</title>
</head>
<body onload="window.print()" > <br>
<h2 align ="center">بيانات ملف المريض</h2>
@if(count($visit) == 0)
	 <p align ="center" > لا يوجد بيانات </p>
@else

<table align=center >
	<tr>
		<th>الأســــــــــــــم : </th><td>{{$visit->patient->name}}</td>
	</tr>
	<tr>
		<th>الســــــــــن : </th><td>{{ calculateAge($visit->patient->birthdate) }}</td>
	</tr>
	<tr>
		<th>العنـــــــــوان : </th><td>{{$visit->patient->address}}</td>
	</tr>
	<tr>
		<th>أسم المرافق : </th><td>{{$visit->companion_name}}</td>
	</tr>
	<tr>
		<th>رقم تليفون المرافق : </th><td>{{$visit->companion_phone_num}}</td>
	</tr>
	<tr>
		<th>أسم أقرب الأقارب : </th><td>{{$visit->person_relation_name}}</td>
	</tr>
	<tr>
		<th>رقم تليفون أقرب الأقارب : </th><td>{{$visit->person_relation_phone_num}}</td>
	</tr>
	<tr>
		@if($role_name!="GeneralRecept")
		<th>القســــــــــــم : </th><td>
		@foreach($visit->medicalunits as $m)
		@endforeach
		{{$m->name}}</td>
	</tr>
	<tr>
		<th>أسم الغرفة : </th><td>
		@foreach($m->rooms as $room)
			@if($m->pivot->room_id == $room->id)
				 {{ $room->name }}
			@endif
		@endforeach
		@else
		@if(count($visit->medicalunits)==1)
			<th>القســــــــــــم : </th><td>
		@foreach($visit->medicalunits as $m)
		@endforeach
		{{$m->name}}</td>
		@endif
	</tr>
	<tr>
	@if(count($visit->medicalunits)==1)
		<th>أسم الغرفة : </th><td>
		@foreach($m->rooms as $room)
			@if($m->pivot->room_id == $room->id)
				 {{ $room->name }}
			@endif
		@endforeach
	@endif
		@endif
	</td>
	</tr>
	<tr>
		<th>تـاريخ الدخول :</th><td>{{$visit->entry_date}}</td>
	</tr>
	<tr>
		<th>تاريخ الخروج :</th><td >{{$visit->exit_date}}</td>
	</tr>
	<tr>
		<th>محول من :</th><td >{{$visit->converted_from_relation->name}}</td>
	</tr>
	<tr>
		<th>التشخيص المبدئي :</th><td >{{$visit->entry_reason_desc}}</td>
	</tr>
	<tr>
		<th>كشف طبي :</th><td >{{$visit->checkup==true? 'نعم':'لا'}}</td>
	</tr>
	<tr>
		<th>رقــــــم الملف :</th><td>{{$visit->file_number}}</td>
	</tr>
	<tr>
		<th>نـــــوع الملف :</th><td>{{$visit->file_type_relation->name}}</td>
	</tr>
	<tr>
		<th>نـــــوع العلاج :</th><td>{{$visit->cure_type->name}}</td>
	</tr>
	<tr>
		<th>جهة التعــــاقد :</th><td>{{isset($visit->contract->name)?$visit->contract->name:''}}</td>
	</tr>
	
</table>

@endif
</body>
</html>
