<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width,initial-scale=1">
		<title>@yield("title") </title>
		<!-- Iconic Fonts -->
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<link rel="stylesheet" href="{{ url('ex_plugins/flat-icons/flaticon.css') }}">
		<link href="{{ url('ex_plugins/font-awesome/css/all.min.css') }}" rel="stylesheet">
		<!-- Bootstrap core CSS -->
		<link href="{{ url('assets/css/bootstrap.min.css') }}" rel="stylesheet">
		<!-- jQuery UI -->
		<link href="{{ url('assets/css/jquery-ui.min.css') }}" rel="stylesheet">
		<!-- Costic styles -->
		<link href="{{ url('assets/css/style.css') }}" rel="stylesheet">
		<!-- Favicon -->
		<link rel="icon" type="image/png" sizes="32x32" href="{{ _get_favicon() }}">
		<script src="{{ url('assets/js/jquery-3.3.1.min.js') }}"></script>
	</head>
	<body class="ms-body ms-primary-theme ms-logged-out">
		<!-- Preloader -->
		<div id="preloader-wrap">
			<div class="spinner spinner-8">
				<div class="ms-circle1 ms-child"></div>
				<div class="ms-circle2 ms-child"></div>
				<div class="ms-circle3 ms-child"></div>
				<div class="ms-circle4 ms-child"></div>
				<div class="ms-circle5 ms-child"></div>
				<div class="ms-circle6 ms-child"></div>
				<div class="ms-circle7 ms-child"></div>
				<div class="ms-circle8 ms-child"></div>
				<div class="ms-circle9 ms-child"></div>
				<div class="ms-circle10 ms-child"></div>
				<div class="ms-circle11 ms-child"></div>
				<div class="ms-circle12 ms-child"></div>
			</div>
		</div>
		<!-- Overlays -->
		<div class="ms-aside-overlay ms-overlay-left ms-toggler" data-target="#ms-side-nav" data-toggle="slideLeft"></div>
		<div class="ms-aside-overlay ms-overlay-right ms-toggler" data-target="#ms-recent-activity" data-toggle="slideRight"></div>
		<!-- Sidebar Navigation Left -->
		
		<!-- Main Content -->
		@section("content")
        @show
		<!-- SCRIPTS -->
		<!-- Global Required Scripts Start -->
		<script src="{{ asset('ex_plugins/jquery-validation-1.19.1/dist/jquery.validate.js') }}"></script>
		<script src="{{ url('assets/js/popper.min.js') }}"></script>
		<script src="{{ url('assets/js/bootstrap.min.js') }}"></script>
		<script src="{{ url('assets/js/perfect-scrollbar.js') }}"> </script>
		<script src="{{ url('assets/js/jquery-ui.min.js') }}"> </script>
		<!-- Global Required Scripts End -->
		<!-- Costic core JavaScript -->
		<script src="{{ url('assets/js/framework.js') }}"></script>
		<!-- Settings -->
		<script src="{{ url('assets/js/settings.js') }}"></script>
	</body>
</html>