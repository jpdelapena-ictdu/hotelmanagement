<!DOCTYPE html>
<html lang="en">



  <head>
  	
		@include('partials._header')
	</head>
		<body style="background-image:url({{ asset('img/hotelwp.jpg') }}); background-repeat:no-repeat; background-size: cover; padding-top: 120px;">
		@include('partials._logo')
		<div class="container">
 			@yield('content')
		</div>
		<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
		<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

{{-- 			@include('partials._footer') --}}
			
	</body>
</html>