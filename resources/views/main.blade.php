<!DOCTYPE html>
<html lang="en">

  <head>
		@include('partials._header')
	</head>
		<body style="background-image:url('img/hotelwp.jpg'); height:100%; background-position: center; background-repeat:no-repeat; background-size: cover;">
		@include('partials._logo')
		<div class="container">
 			@yield('content')
		</div>
		<script src="vendor/jquery/jquery.min.js"></script>
		<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

			@include('partials._footer')
			
	</body>
</html>