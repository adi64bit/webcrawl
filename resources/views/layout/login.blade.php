<!DOCTYPE HTML>
<!--
	base template
-->
<html>

<head>
	<title>Login - Island Media Management</title>
	<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
<!--[if lte IE 9]><link rel="stylesheet" href="assets/css/ie9.css" /><![endif]-->
<link rel="stylesheet" href="{{ URL::asset('assets/vendor/bootstrap/css/bootstrap.min.css') }}" type="text/css" media="all" />
<link rel="stylesheet" href="{{ URL::asset('assets/vendor/fontawesome/css/font-awesome.css') }}" type="text/css"  media="all" />

 
    
<script src="{{ URL::asset('assets/vendor/jQuery/jquery.min.js') }}" type="text/javascript"></script>

	<style>
		.login-page {
  width: 360px;
  padding:20px 0 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #FFFFFF;
  max-width: 360px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form input {
  font-family: "Roboto", sans-serif;
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}
.form button {
  font-family: "Roboto", sans-serif;
  text-transform: uppercase;
  outline: 0;
  background: #787878;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
}
.form button:hover,.form button:active,.form button:focus {
  background: #585858;
}
.form .message {
  margin: 15px 0 0;
  color: #b3b3b3;
  font-size: 12px;
}
.form .message a {
  color: #4CAF50;
  text-decoration: none;
}
.form .register-form {
  display: none;
}
.container {
  position: relative;
  z-index: 1;
  margin: 0 auto;
}
.container:before, .container:after {
  content: "";
  display: block;
  clear: both;
}
.header-login{
  text-align:center;
  padding-top:50px;
}
.header-login img{}
.header-login h2{
  color:#fff;
}

@media (max-width:468px){
  .login-page{
    width:100%;
  }
  .form{
    padding:20px;
  }
}

body {
  height: 100vh;
  background-size: 110% 110%;
  background-position: 40% 40%;
}
	</style>
</head>
<body style="background-image:url('{{ URL::asset('assets/images/background-login.jpg') }}');">
		<div class="container">
			<div class="header-login">
				 <img src="{{ URL::asset('assets/images/logo.png') }}" />
				<h2>IMM Website Review</h2>
			</div>
			<div class="login-page">
			  @include('layout.User.formLogin')
			</div>
		</div>
	@include('include.footer')
</body>
</html>
