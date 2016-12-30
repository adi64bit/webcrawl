<div class="logo">
    <img src="{{ URL::asset('assets/images/logo.png') }}" />
</div>
<div class="menu-nav">
    <ul class="navigation">
        <li>
            <a href="#" data-href="dashboard"><i class="fa fa-home"></i> Dashboard</a>
        </li>
        <li>
            <a href="#" data-href=""><i class="fa fa-home"></i> Menu 1</a>
        </li>
        <li>
            <a href="#" data-href=""><i class="fa fa-home"></i> Menu 2</a>
        </li>
        <li>
            <a href="#" data-href=""><i class="fa fa-home"></i> Menu 3</a>
        </li>
        @if ( Auth::user()->haveRole('admin') )
            <li>
                <a href="#" data-href="/userdata"><i class="fa fa-user"></i> User</a>
            </li>
        @endif
        <li>
            <a href="/logout"><i class="fa fa-home"></i> Log Out</a>
        </li>
    </ul>
</div>
