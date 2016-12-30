<!DOCTYPE HTML>
<!--
	base template
-->
<html>

<head>
	<title>Island Media Management</title>
	@include('include.head')
</head>
<body>
	<div class="wrapper">
			@include('include.sidebar')
		<div class="main-panel">
			@include('include.navbar')
			<div class="content">
	            <div class="container-fluid" id="content-push">
	            	<div class="loader"></div>
	            </div>
            </div>
            <footer class="footer">
	            <div class="container-fluid">
	                <p class="copyright pull-right">
	                    &copy; 2017 <a href="/">Muhammad Adi Saputra</a>, made with love for a successfully at the sidang skripsi
	                </p>
	            </div>
	    	</footer>
		</div>
	</div>

	<!-- Modal -->
	<div id="immModal" class="modal fade" role="dialog">
	  <div class="modal-dialog">
	    <!-- Modal content-->
	    <div class="modal-content" id="modal-push">
	      
	    </div>
	  </div>
	</div>
	@include('include.footer')
</body>
</html>
