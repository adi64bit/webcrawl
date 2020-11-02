<!doctype html>
<html lang="en">

<head>
  <title>Island Media Management</title>
  @include('include.head')

</head>

<body class="light_version font-krub theme-cyan">

  @include('components.pageloader')


  <div class="auth-main particles_js">
    <div class="auth_div vivify popIn">
      <div class="auth_brand">
        <a class="navbar-brand" href="javascript:void(0);"><img src="{{ URL::asset('assets/images/logo.png') }}" width="30" height="30"
            class="d-inline-block align-top mr-2" alt="">IMM Website Review</a>
      </div>
      <div class="card">
        <div class="body">
          <p class="lead">Login to your account</p>
          <form class="form-auth-small m-t-20 login-form" action="{{ url(action('UserController@postLogin')) }}" method="post">
          {{ csrf_field() }}
            <div class="form-group">
              <label for="username" class="control-label sr-only">Username</label>
              <input type="text" class="form-control round" id="username" name="username" placeholder="insert username" required autofocus>
            </div>
            <div class="form-group">
              <label for="password" class="control-label sr-only">Password</label>
              <input type="password" class="form-control round" id="password" name="password" placeholder="Insert Password">
            </div>
            <button type="submit" class="btn btn-primary btn-round btn-block">LOGIN</button>
            <div class="bottom">
              <span>Not registered? Ask Admin or Bosses to Create a new User for you</span>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>



  @include('include.footer')
</body>

</html>