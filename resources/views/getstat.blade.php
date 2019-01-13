@extends('layouts.app')
@section('content')
<!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
      <ol class="breadcrumb">
        <li><a href="{{ url('/') }}"><i class="fa fa-dashboard"></i> الصفحة الرئيسية</a></li>
        <li class="active">
				@if($role_name == 'Entrypoint')
					زيارات مكتب الدخول
				@elseif(isset($ticket_and_entry))
					بيانات مرضي الدخول
				@elseif($role_name == 'Desk')
					زيارات مكتب الاستقبال
				@else
					زيارات مكتب حجز التذاكر
				@endif

				</li>
      </ol>
	  
    </section>

						<!-- Main content -->
						<section class="content">
						  <!-- Small boxes (Stat box) -->
						  <div class="row">
						  
							<a href="/selectdept">احصائيات الاقسام</a>
					</div> <!-- ./row -->
			</section>
			<!-- /.content -->
			</div>
@endsection
@section('javascript')
<script>
function changeRoom(row_id){
	var url = "{{ url('/patients/getDepartmentDoctors/') }}";
	$.ajax({
		type: "POST",
		url: url,
		data: { 'mid':$("#dep_"+row_id).val() },
		success: function (data) {
			$("#room_"+row_id).empty();
			if(data['success']=='true'){
				for (i=0;i<data['rooms'].length;i++) {
					$("#room_"+row_id).append("<option value='"+data['rooms'][i].id+"'>"+data['rooms'][i].name+"</option>");
				}
			}
		},
		error: function (data) {
			alert("Error");
		}
	});
}
</script>
@stop