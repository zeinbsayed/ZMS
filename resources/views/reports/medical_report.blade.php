<html>
<head>
	<meta charset="utf-8">
<style>
body{size: 21cm 29.7cm;
    margin: 30mm 45mm 30mm 45mm;}
	td,th{padding-top:10px; padding-bottom: 10px; padding-right: 30px;text-align: right;}
	th{width: 120px;}
	.head{font-weight: bold}
	.div{font-weight: bold}
	table {border:1px solid black;width: 21cm;
    height: 150px; direction:rtl  }
	p{padding-top: 20px;}
	.secondtablecell{padding-right: 50px;}
	body{margin: 0 auto}
</style>
<title>تقرير طبى</title>
</head>
<body onload="window.print()"> <br>
<h2 align ="center">تقرير طبى</h2>
@if(count($data) == 0)
	 <p align ="center" > لا يوجد بيانات </p>
@else
@foreach($data as $row)
<table align=center >
	<tr>
		<th>أسم المريض:</th><td>{{$row->name}}</td>
	</tr>
	<tr>
		<th>السن :</th><td>{{ calculateAge($row->birthdate) }}</td>
	</tr>
	<tr>
		<th>رقم التذكرة:</th><td>{{$row->ticket_number}}</td>
	</tr>
	<tr>
		<th> تاريخ الدخول:</th><td>{{$row->entry_date}}</td>
	</tr>
	<tr>
		<th> تاريخ الخروج :</th><td>{{$row->exit_date}}</td>
	</tr>
</table>
<br> <br>
			<h4 style="float:right">:بتوقيع الكشف الطبى على المريض تبين أنه يعانى من</h4>
			<br><br><br>
			<div style="float:right">{{$row->final_diagnosis}}</div>
			<br><br><br><br>
			<h4 style="float:right">:ووصف له العلاج اللازم وينصح بالأتى</h4>
			<br>
			<div align=right>{{$row->doctor_recommendation}}</div>
	@endforeach
@endif
</body>
</html>
