<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title> احصائيات الأقسام</title>
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
	@if(count($deptstat)>0)
    <table id="example1" class="table table-bordered table-hover" dir="rtl">
								<thead>
								<tr>
									
									<th>أسم القسم</th>
									<th>عدد المرضى</th>
								</tr>
								</thead>
								<tbody>
    	<tr>

		 @foreach($deptname as $dname)
		 @foreach($dname as $name)
		 		<?php $flag=0; ?>
		 @foreach($deptstat as $row)
		 @foreach($row as $dept)
		 						@if($name->uname==$dept->uname)
										<tr>
											<td>{{$name->uname}}</td>
											<td>{{$dept->count}}</td>
											</tr>
											<?php $flag=1; ?>
								@endif
		@endforeach
		@endforeach
		@if($flag==0)
		<tr>
											<td>{{$name->uname}}</td>
											<td>لايوجد</td>
		</tr>
		@endif
		@endforeach
		@endforeach
								</tbody>
							</table>
    <br>
@else
	<p style="text-align: center;font-size:25px">لا يوجد بيانات </p>
@endif

</body>
</html>
