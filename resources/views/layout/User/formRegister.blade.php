<html>

<head>
    <title>Register</title>
</head>

<body>
    <div class="login-page">
        <div class="form">
            <form action="{{ url(action('UserController@postRegister')) }}" method="post" class="register-form">
                {{ csrf_field() }}
                <input type="text" name="name" placeholder="insert name" required />
                <input type="text" name="username" placeholder="insert username" required />
                <input type="password" name="password" placeholder="insert password" required />
                <button type="submit">create</button>
            </form>
        </div>
    </div>
</body>

</html>
