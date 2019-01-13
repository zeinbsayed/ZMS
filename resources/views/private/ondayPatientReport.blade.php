<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>تقرير مرضى الخروج</title>
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
@if(count($data) > 0)
    @if(count($data) > 0)
    <table class="table table-striped table-bordered " style="direction: rtl;" >
    <tr>
      <!-- <th>رقم التذكرة</th> -->
	  @if(isset($role_name) && $role_name == "GeneralRecept")
		  <th>رقم التذكرة</th>
	  @endif
      <th>كود المريض</th>
      <th>أسم المريض</th>
      <th>السن</th>
      <th>رقم الغرفة</th>
      <th>الدرجة العلاجية</th>
      <th>التخصص الطبى</th>
      <th>نوع التعاقد</th>
      <th>العنوان</th>
    </tr>
	 @foreach($data as $row)
	 <tr>
		 @if(isset($role_name) && $role_name == "GeneralRecept")
		  <td>{{$row->vticket_number}}</td>
	     @endif
    		<td>{{$row->pid}}</td>
    		<td>{{$row->pname}}</td>
    		<td>{{calculateAge($row->pBD)}}</td>
    		<td>{{$row->romname}}</td>
			<td> </td>
            <td> </td>
        <td>{{$row->contname}}</td>
        <td>{{$row->paddress}}</td>
		</tr>
    	  @endforeach
    </table>
    <br>
    @else
      @continue
    @endif
		<p style="margin-left: 15px"><b> أجمالي عدد الحالات : {{count($data)}} </b></p>
@else
	<p style="text-align: center;font-size:25px">لا يوجد بيانات اليوم</p>
@endif

</body>
</html>
