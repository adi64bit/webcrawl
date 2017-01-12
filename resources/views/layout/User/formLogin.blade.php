    <div class="form">
            <form action="{{ url(action('UserController@postLogin')) }}" method="post" class="login-form">
                {{ csrf_field() }}
               <input type="text" name="username" placeholder="insert username" required autofocus/>
                <input type="password" name="password" placeholder="insert password" required />
              <button type="submit">login</button>
              <p class="message">Not registered? Ask Admin or Bosses to Create a new User for you</p>
            </form>
          </div>
