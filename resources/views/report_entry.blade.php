<html>
<head>
	<meta charset="utf-8">
<style>
body{size: 21cm 29.7cm;
    margin: 30mm 45mm 30mm 45mm;}
	td,th{padding-top:10px; padding-bottom: 10px; padding-right: 30px;text-align: right;}
	th{width: 104px;}
	.head{font-weight: bold}
	table {border:1px solid black;width: 21cm;
    height: 150px; direction:rtl  }
	p{padding-top: 20px;}
	.secondtablecell{padding-right: 50px;}
	body{margin: 0 auto}
</style>
<title>أذن دخول مريض</title>
</head>
<body onload="window.print()"> <br>
<h2 align ="center">أذن دخول مريض</h2>
@if(count($data) == 0)
	 <p align ="center" > لا يوجد بيانات </p>
@else 
@if(($entr_role_name[0]->enter_role_name=="GeneralRecept" || $entr_role_name[0]->enter_role_name=="Injuires") && (count($data)>1))
<?php $pnum=1;  ?>
@else
<?php $pnum=0;  ?>
@endif
<table align=center >
	<tr>
		<th>ساعة الدخول : </th>
		<td>
			@if (strrpos($data[$pnum]->entry_time,"AM") !== false)
				{{ str_replace('AM', 'ص', $data[$pnum]->entry_time) }}
			@elseif (strrpos($data[$pnum]->entry_time,"PM") !== false)
				{{ str_replace('PM', 'م', $data[$pnum]->entry_time) }}
			@endif
		</td>
		<?php $x=date("d-m-Y", strtotime($data[$pnum]->entry_date));?>
		<th>تاريخ الدخــول : </th><td>{{$x}}</td>
	</tr>
		<tr>
		@if($role_name=="GeneralRecept"||$role_name=="Injuires")
			<th> رقم التذكرة : </th><td>{{$data[$pnum]->vticket_num}}</td>
		@endif
		@if(($role_name=="GeneralRecept"||$role_name=="Injuires") &&( $data[$pnum]->med_id=="26" || $data[$pnum]->med_id=="22"))
		    <th> طبيب الاستقبال المعالج: </th><td>{{$data[$pnum]->doctor_name}}</td>
		@else
			 <th> الطبيب المعالج : </th><td>{{$data[$pnum]->reference_doctor_name}}</td>
		@endif
		</tr>
	<tr>
		<th> القســـــم : </th><td>{{$data[$pnum]->dep_name}}</td>
	</tr>
	<tr>
		<th> أســـــم الغرفة : </th><td>{{$data[$pnum]->room_name}}</td>
	</tr>
	<tr>
		<th> التشخيص المبدئي : </th><td colspan="3">{{$data[$pnum]->entry_reason_desc}}</td>
	</tr>
</table>
<br><br>
<table align=center >
	<tr><th colspan="6" style="text-align:center">بيانات خاصة بالمريض</th></tr>
	<tr>
		<th>الكـــــــــــــود : </th><td>{{$data[$pnum]->new_id}}</td>
	</tr>
	<tr>
		<th>الأســــــــــــم : </th><td>{{$data[$pnum]->pname}}</td>
		<th>النـــــــــــــــــــوع : </th><td>{{$data[$pnum]->gender=='M'?'ذكر':'أنثى'}}</td>
		<th>الســــــــــــــــــن : </th>
		<td>
			<?php
                $current_date = new DateTime();
                $birthdate = new DateTime($data[$pnum]->birthdate);
                $interval = $current_date->diff($birthdate);
            ?>
            @if($interval->y > 0)
              {{ $interval->y }}
              @if( $interval->y > 11 )
                {{ "سنة" }}
              @else
                {{ "سنوات" }}
              @endif
            @elseif($interval->m > 0)
              {{ $interval->m }}
              @if( $interval->m > 11 )
                {{ "شهر" }}
              @else
                {{ "شهور" }}
              @endif
            @else
              {{ $interval->d }}
              @if( $interval->d > 11 )
                {{ "يوم" }}
              @else
                {{ "أيام" }}
              @endif
            @endif
		</td>
		
	</tr>
	<tr>
		<th>العنـــــــــوان :</th><td colspan="5">{{$data[$pnum]->paddress}}</td>
	</tr>
	<tr>
		<th>رقم التليفون :</th><td>{{$data[$pnum]->ppnumber}}</td>
		<th>الحالة الاجتماعية :</th><td>{{$data[$pnum]->social_status}}</td>
		<th>المهنــــــــــــــــة :</th><td>{{$data[$pnum]->pjob}}</td>
	</tr>
	<tr>
		<th>الرقم القومي :</th><td colspan="5">{{$data[$pnum]->psid}}</td>
	</tr>
	<tr>
		<th>محول من:</th><td colspan="5">{{$data[$pnum]->converted_from_name}}</td>
	</tr>
</table>
<p>
<table align=center>
	<tr ><th  colspan="6" style="text-align: center;">بيانات خاصة بمرافق المريض</th></tr>
	<tr>
		<th>الأســــــــــــم : </th><td colspan="2">{{$data[$pnum]->c_name}}</td>
	</tr>
	<tr>
		<th>محــل الاقامة : </th><td colspan="5">{{$data[$pnum]->rel_address}}</td>
	</tr>
	<tr>
		<th>رقم التليفون :</th><td colspan="2">{{$data[$pnum]->rel_phone}}</td>
		<th>المهنــــــــــة :</th><td colspan="2">{{$data[$pnum]->rel_job}}</td>
	</tr>
	<tr>
		<th>الرقم القومي :</th><td colspan="5">{{$data[$pnum]->rel_sid=="0"?"":$data[$pnum]->rel_sid}}</td>
	</tr>
</table>
</p>
@endif
</body>
</html>
