<div class="sidebar" data-color="orange" data-image="{{ URL::asset('assets/images/background-image.png') }}">

    <!--

        Tip 1: you can change the color of the sidebar using: data-color="blue | azure | green | orange | red | purple"
        Tip 2: you can also add an image using data-image tag

    -->

    	<div class="sidebar-wrapper">
            <div class="logo">
                <a href=/ class="simple-text">
                     IMM Website Review
                </a>
            </div>

            <ul class="nav">
                <li class="active">
                    <a href="#" data-href="dashboard">
                        <i class="pe-7s-graph"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li>
                    <a href="#" data-href="queuelist">
                        <i class="pe-7s-server"></i>
                        <p>Queue</p>
                    </a>
                </li>
                <li>
                    <a href="#" data-href="domainlist">
                        <i class="pe-7s-note2"></i>
                        <p>Domain</p>
                    </a>
                </li>
                <li>
                    <a href="#" data-href="reportlist">
                        <i class="pe-7s-news-paper"></i>
                        <p>Report</p>
                    </a>
                </li>
                @if ( Auth::user()->haveRole('admin') )
                <li>
                    <a href="#" data-href="/userdata">
                        <i class="pe-7s-users"></i>
                        <p>user</p>
                    </a>
                </li>
                @endif
                <hr />
            </ul>
    	</div>
    </div>