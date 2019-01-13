<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Pd Reg | Log in</title>
	<link rel="icon" href="{{ asset('images/favicon.ico') }}">
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="{{asset('bootstrap/css/bootstrap.min.css')}}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('bootstrap/css/font-awesome.min.css')}}">
  <!-- Ionicons -->
 <link rel="stylesheet" href="{{asset('bootstrap/css/ionicons.min.css')}}">
  <!-- Theme style -->
 <link rel="stylesheet" href="{{asset('dist/css/AdminLTE.css')}}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{asset('plugins/iCheck/square/blue.css')}}">

 <noscript>
  <META HTTP-EQUIV="Refresh" CONTENT="0;URL={{ url('/JavascriptNotFound') }}">
 </noscript>
  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">
    <a href="#"><b>Pd</b>Registration</a>


  </div>
  	@if (count($errors) > 0)
		
		  <div class="alert alert-danger" style="direction:rtl">
			<strong>رسالة خطأ !</strong>
			<br>
			<ul style="  text-align: right; direction: rtl;    font-size: 15px;">
			  @foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			  @endforeach
			</ul>
		  </div>

		@endif
  <!-- /.login-logo -->
  <div class="login-box-body">
		<p class="login-box-msg">Sign in to start your session
		
		</p>
		<form  action="{{url('auth/login')}}" method="post">
		  <input type="hidden" name="_token" value="{{ csrf_token() }}">
		  <div class="form-group has-feedback">
			<input type="text" class="form-control" name="name" id="inputEmail" placeholder="Username" required autofocus />
			<span class="glyphicon glyphicon-envelope form-control-feedback"></span>
		  </div>
		  <div class="form-group has-feedback">
			<input type="password" class="form-control" name="password" id="inputPassword" placeholder="Password" required />
			<span class="glyphicon glyphicon-lock form-control-feedback"></span>
		  </div>
		  <div class="row" >
			<div class="col-xs-8">
			  <div class="checkbox icheck">
				<label>
				  <input type="checkbox" value="remember-me" name="remember" > Remember Me
				</label>
			  </div>
			</div>
			<!-- /.col -->
			<div class="col-xs-4">
			  <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
			</div>
			<!-- /.col -->
		  </div>
		</form>
  </div> 
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery 2.2.3 -->
<script src="{{asset('plugins/jQuery/jquery-2.2.3.min.js')}}"></script>
<!-- Bootstrap 3.3.6 -->
<script src="{{asset('bootstrap/js/bootstrap.min.js')}}"></script>
<!-- iCheck -->
<script src="{{asset('plugins/iCheck/icheck.min.js')}}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });
</script>
</body>
</html>
