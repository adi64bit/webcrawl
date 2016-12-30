    <div class="login-form-wrapper">
        <div class="logo-login">
            <img src="{{ URL::asset('assets/images/logo.png') }}" />
            <h3>SKRIPSI INI COY</h3>
        </div>
        <div class="form">
            <form action="{{ url(action('UserController@postLogin')) }}" method="post" class="login-form">
                {{ csrf_field() }}
                <input type="text" name="username" placeholder="insert username" required autofocus/>
                <input type="password" name="password" placeholder="insert password" required />
                <button type="submit">Sign In</button>
            </form>
        </div>
    </div>
