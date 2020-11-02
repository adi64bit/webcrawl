<div id="left-sidebar" class="sidebar">
        <div class="navbar-brand">
            <a href="index.html"><img src="{{ URL::asset('assets/images/logo.png') }}" alt="IMM Logo" class="img-fluid logo"><span>Website Review</span></a>
            <button type="button" class="btn-toggle-offcanvas btn btn-sm float-right"><i class="lnr lnr-menu icon-close"></i></button>
        </div>
        <div class="sidebar-scroll"> 
            <nav id="left-sidebar-nav" class="sidebar-nav">
                <ul id="main-menu" class="metismenu">
                    <li class="header">Main</li>
                    <li><a href="{{ url('/') }}"><i class="icon-speedometer"></i><span>Dashboard</span></a></li>
                    <li><a href="{{ url('/') }}"><i class="icon-rocket"></i><span>Queue Jobs</span></a></li>
                    <li><a href="{{ url('/') }}"><i class="icon-cursor"></i><span>Domain</span></a></li>
                    <li><a href="{{ url('/') }}"><i class="icon-docs"></i><span>Report</span></a></li>
                    @if ( Auth::user()->haveRole('admin') )
                    <li><a href="/user"><i class="icon-lock"></i><span>User</span></a></li>
                    @endif
                </ul>
            </nav>     
        </div>
    </div>