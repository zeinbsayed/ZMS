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
@if($patient_count>0)
    <table id="example1" class="table table-bordered table-hover" dir="rtl">
								<thead>
								<tr>
									<th>النوع</th>
									<th>عدد المرضى</th>
								</tr>
								</thead>
								<tbody>	
		<!-- <tr>
			<td>ذكر</td>
		</tr>
		<tr>
			<td>أنثى</td>
		</tr>!-->
		<?php $total=0; 
				$gender_type=""?>
					@if(count($gender_counts[0])==0)
				<?php $gender_type="ذكر"; ?>
			<tr>
				<td>{{$gender_type}}</td>
				<td>  0 </td>
			</tr>
				@endif
				@if(count($gender_counts[1])==0)
				<?php $gender_type="أنثى"; ?>
			<tr>
				<td>{{$gender_type}}</td>
				<td>  0 </td>
			</tr>
				@endif
		 @foreach($gender_counts as $row)
		 @foreach($row as $patient)
		 @if($patient->gender=='M')
			<?php $gender_type="ذكر"; ?>
		 @else
			<?php $gender_type="أنثى"; ?>
		 @endif
										<tr>
										    <td>{{$gender_type}}</td>
											<td>{{$patient->count}}</td>
										</tr>
										<?php $total=$total+$patient->count ?>
			@endforeach
			@endforeach
								</tbody>
							</table>
    <br>
	<p style="text-align: center;font-size:25px">اجمالى عدد الحالات ={{$total}}</p>
	@else
	<p style="text-align: center;font-size:25px">لاتوجد بيانات</p>
	@endif
</body>
</html>
