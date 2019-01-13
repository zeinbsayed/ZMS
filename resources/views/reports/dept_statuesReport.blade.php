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
@if($total_count>0)
    <table id="example1" class="table table-bordered table-hover" dir="rtl">
								<thead>
								<tr>
									<th>القسم</th>
									@foreach($exit_status as $status)
										
											<th>{{$status->name}}</th>
										
										@endforeach
										<th>الإجمالى</th>
										</tr>
								</thead>
								<tbody>	
								
									
									<?php $count=0; ?>
									@foreach($data as $deptRow)
									
									<?php //dd($deptRow[0]->ex_count); ?>
									<?php $flag=0; $states=array(0,0,0,0,0,0,0,0,0);?>
									<?php for($i=0;$i<count($deptRow);$i++)
									{
										
										switch($deptRow[$i]->ex_id)
										{
											case 1:
											{
												$states[1]=$deptRow[$i]->ex_count;
												break;
											}
											case 2:
											{
												$states[2]=$deptRow[$i]->ex_count;
												break;
											}
											case 3:
											{
												$states[3]=$deptRow[$i]->ex_count;
												break;
											}
											case 4:
											{
												$states[4]=$deptRow[$i]->ex_count;
												break;
											}
											case 5:
											{
												$states[5]=$deptRow[$i]->ex_count;
												break;
											}
											case 6:
											{
												$states[6]=$deptRow[$i]->ex_count;
												break;
											}
											case 7:
											{
												$states[7]=$deptRow[$i]->ex_count;
												break;
											}
											default:
											{
												$states[$i]=0;
											}
										}
										} 
									//dd($states);
									$dept_count=0;?>
									<?php for($i=1;$i<count($states);$i++)
									{
										$dept_count=$dept_count+$states[$i]?>
										@if($flag==0)
										<th>{{$deptNames[$count][0]->deptname}}</th>
										@endif
										<th>{{$states[$i]}}</th>
										
									<?php $flag=1;} ?>
									<th>{{$dept_count}}</th>
									</tr>
									<?php $count++;?>
									@endforeach
								</tbody>
							</table>
    <br>
	
	@else
	<p style="text-align: center;font-size:25px">لاتوجد بيانات</p>
	@endif
</body>
</html>
