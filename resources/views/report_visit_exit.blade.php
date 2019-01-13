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
<title>بيان خروج مريض</title>
</head>
<body onload="window.print()"> <br>
<h2 align ="center">بيان خروج مريض</h2>
@if(count($data) == 0)
	 <p align ="center" > لا يوجد بيانات </p>
@else

<table align=center >
	<tr>
	<?php //dd($data); ?>
		<th>أسم المريض:</th><td>{{$data->patient->name}}</td>
	</tr>
	<tr>
		<th>السن :</th><td>{{ calculateAge($data->patient->birthdate) }}</td>
	</tr>
	<tr>
		<th>العنوان:</th><td>{{$data->patient->address}}</td>
	</tr>
	@if($role_name=="GeneralRecept")
	<tr>
		<th> طبيب الاستقبال :</th><td>{{ isset($data->doctor_name)?$data->doctor_name:''}}</td>
	</tr>
	@endif
	<tr>
		<th>الحالة عند الخروج :</th><td>{{$data->exit_status->name}}</td>
	</tr>
	<tr>
		<th> تاريخ الخروج :</th><td>{{$data->exit_date}}</td>
	</tr>
	<tr>
		<th> التشخيص النهائي :</th><td>{{$data->final_diagnosis}}</td>
	</tr>
	@if(count($medical_visit)>0)
	<tr>
		<th> الطبيب :</th><td>{{ isset($medical_visit[0]->uname)?$medical_visit[0]->uname:''}}</td>
	</tr>
	@endif
	<tr>
		<th> التوصية :</th><td>{{$data->doctor_recommendation}}</td>
	</tr>
</table>
@endif
</body>
</html>
