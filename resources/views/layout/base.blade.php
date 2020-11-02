<!doctype html>
<html lang="en">

<head>
    <title>Island Media Management</title>
    @include('include.head')

</head>

<body class="light_version font-krub theme-cyan">

    @include('components.pageloader')

    <!-- Overlay For Sidebars -->
    <div class="overlay"></div>

    <div id="wrapper">

        @include('include.navbartop')

        @include('include.navbarleft')

        <div id="main-content">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>

    </div>
    <!-- larg modal -->
    <div class="modal fade bd-example-modal-lg" tabindex="-1" role="dialog" id="immModal" aria-labelledby="immModal"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content" id="modal-push">
                
            </div>
        </div>
    </div>

    @include('include.footer')
</body>

</html>