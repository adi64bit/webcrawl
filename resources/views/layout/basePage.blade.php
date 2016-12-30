
<div class="content-wrapper">
	<div class="row">
		<div class="col-md-12">
            <div class="card card-plain">
                <div class="header">
                    <h2 class="title">@yield('titlePage')</h2>
                    <hr/>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="card card-plain">
                <div class="header">
                    @yield('afterTitle')
                </div>
            </div>
        </div>
        @yield('content')
    </div>
</div>