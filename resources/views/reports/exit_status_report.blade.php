<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> احصائية حالات الخروج</title>
  <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.css')}}">
  <!-- Font Awesome -->
    <link rel="stylesheet" href="{{asset('bootstrap/css/font-awesome.min.css')}}">
	<style>
		.table th,.table td{text-align:center}
	</style>
</head>
<body onload="window.print()">
<h3 align ="center"  style="direction: rtl;"><b>{!! $table_header !!}</b></h3>
<br>
<?php $stat_count=0; ?>
@if($total_count>0)
    <table id="example1" class="table table-bordered table-hover" dir="rtl">
								<thead>
								<tr>
									
									<th>الحالة</th>
									<th>العدد</th>
								</tr>
								</thead>
								<tbody>	
		 @foreach($data as $row)
		 @foreach($row as $state)
										<tr>
										    <td>{{$state->state_name}}</td>
											<td>{{$state->count}}</td>
										</tr>
										<?php $stat_count=$stat_count+$state->count ?>
			@endforeach
			@endforeach
								</tbody>
							</table>
    <br>
	<p style="text-align: center;font-size:25px">اجمالى عدد الحالات ={{$stat_count}}</p>
	<br>
	<h3 align ="center"  style="direction: rtl;"><b>{!! $duration_header !!}</b></h3>
	<br>
	 <table id="example1" class="table table-bordered table-hover" dir="rtl">
								<thead>
								<tr>
									<th>أقصى عدد أيام اقامة المرضى</th>
									<th>أقل عدد أيام اقامة المرضى</th>
									<th>متوسط عدد أيام اقامة المرضى</th>
								</tr>
								</thead>
								<tbody>	
		 @foreach($patients_duration as $row)
										<tr> 
											<td>{{$row->max_duration}}</td>
											<td>{{$row->min_duration}}</td>
											<td>{{$row->avg_duration}}</td>
										</tr>
			@endforeach
								</tbody>
							</table>
	@else
	<p style="text-align: center;font-size:25px">لا توجد بيانات</p>
	@endif
</body>
</html>
