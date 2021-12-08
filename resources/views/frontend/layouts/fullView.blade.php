<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ScheduleDown">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="ScheduleDown">
    <link rel="icon" type="image/png" href="{{ asset('frontend/img/fav.png') }}">
    <title>ScheduleDown</title>
    <!-- Slick Slider -->
    <link rel="stylesheet" type="text/css" href="{{asset('frontend/vendor/slick/slick.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{asset('frontend/vendor/slick/slick-theme.min.css') }}" />
    <!-- Feather Icon-->
    <link href="{{asset('frontend/vendor/icons/feather.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap core CSS -->
    <link href="{{asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{asset('frontend/vendor/owl.carousel/assets/owl.carousel.min.css') }}" rel="stylesheet">
    <link href="{{asset('frontend/vendor/animate/animate.css') }}" rel="stylesheet">
    <link href="{{asset('frontend/vendor/aos/aos.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{asset('frontend/css/style.css') }}" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link href="{{asset('frontend/vendor/sidebar/demo.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
</head> 
<body class="fixed-bottom-bar bg-content">  
    
	@yield('content')
    
    <script type="text/javascript" src="{{asset('frontend/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <!-- slick Slider JS-->
    <script type="text/javascript" src="{{asset('frontend/vendor/slick/slick.min.js') }}"></script>
    <!-- Sidebar JS-->
    <script type="text/javascript" src="{{asset('frontend/vendor/sidebar/hc-offcanvas-nav.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script type="text/javascript" src="{{asset('frontend/js/osahan.js') }}"></script>
    <script src="{{asset('frontend/vendor/jquery.easing/jquery.easing.min.js') }}"></script>
    <script src="{{asset('frontend/vendor/waypoints/jquery.waypoints.min.js') }}"></script>
    <script src="{{asset('frontend/vendor/owl.carousel/owl.carousel.min.js') }}"></script>
    <script src="{{asset('frontend/vendor/counterup/counterup.min.js') }}"></script>
    <script src="{{asset('frontend/vendor/aos/aos.js') }}"></script>
    <script src="{{asset('frontend/js/main.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/jquery.mask.js') }}"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    @yield('scripts')
</body>

</html>