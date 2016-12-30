<!DOCTYPE HTML>
<!--
	base template
-->
<html>

<head>
	<title>Login - Island Media Management</title>
	@include('include.head')
</head>
<body class="login-page">
	<div class="wrapper full-height" style="background-image:url('{{ URL::asset('assets/images/background-login.jpg') }}');">
		<div class="login-wrapper">
			@include('layout.User.formLogin')
		</div>
	</div>
	@include('include.footer')
</body>
</html>
