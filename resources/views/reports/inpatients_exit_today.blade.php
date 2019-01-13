<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>تقرير خروج مرضى الدخول</title>
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
@if(count($data) && isset($numberOfVisits) && $numberOfVisits > 0)
  @foreach($data as $visits_row)
    @if(count($visits_row) > 0)
    <h4 align ="center" style="direction: rtl;"><b>أسم مدخل البيان : {{ $visits_row[0]->uname }}</b></h4>
    <table class="table table-striped table-bordered " style="direction: rtl;" >
    <tr>
      <th>كود المريض</th>
      <th>أسم المريض</th>
      <th>النوع</th>
      <th>أسم القسم</th>
      <th>تاريخ دخول المريض</th>
	  <th>تاريخ خروج المريض</th>
	  <th>الحالة عند الخروج</th>
	  <th>التشخيص النهائي</th>
    </tr>
	@foreach($visits_row as $row)
    	<tr>
    		<td>{{$row->pnew_id}}</td>
    		<td>{{$row->pname}}</td>
    		<td>{{$row->pgender=='M'?'ذكر':'أنثى'}}</td>
    		<td>{{$row->med_name}}</td>
			<td>{{$row->v_entry_date}}</td>
			<td>{{$row->v_exit_date}}</td>
			<td>{{$row->exit_status}}</td>
			<td>{{$row->v_final_diagnosis}}</td>
    	</tr>
	@endforeach
    </table>
    <br>
    @else
      @continue
    @endif
  @endforeach
  @if(isset($numberOfVisits))
		<p style="margin-left: 15px"><b> أجمالي عدد الحالات : {{$numberOfVisits}} </b></p>
	@endif
@else
	<p style="text-align: center;font-size:25px">لا يوجد بيانات اليوم</p>
@endif

</body>
</html>
