        <nav class="navbar navbar-default navbar-fixed">
            <div class="container-fluid">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand logo" href="#"><img src="{{ URL::asset('assets/images/logo.png') }}" /></a>
                </div>
                <div class="collapse navbar-collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li>
                            <a href="#" data-href="none!" class="nonelink">
                                <i class="pe-7s-user"></i>
                                {{ Auth::user()->username }}
                            </a>
                        </li>
                        <li>
                            <a href="#" data-href="logout">
                                <i class="pe-7s-less"></i>
                                Log out
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>