<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="ScheduleDown">
    <meta name="author" content="ScheduleDown">
    <link rel="icon" type="image/png" href="img/fav.png">
    <title>ScheduleDown</title>
    <!-- Slick Slider -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendor/slick/slick.min.css') }}" />
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/vendor/slick/slick-theme.min.css') }}" />
    <!-- Feather Icon-->
    <link href="{{ asset('frontend/vendor/icons/feather.css') }}" rel="stylesheet" type="text/css">
    <!-- Bootstrap core CSS -->
    <link href="{{ asset('frontend/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <!-- Custom styles for this template -->
    <link href="{{ asset('frontend/css/style.css') }}" rel="stylesheet">
    <!-- Sidebar CSS -->
    <link href="{{ asset('frontend/vendor/sidebar/demo.css') }}" rel="stylesheet">
    <link href="{{ asset('frontend/vendor/rescalendar/css/rescalendar.css') }}" rel="stylesheet">
    <style type="text/css">
        .profileImage {
            position: relative;
            display: block;
            margin: 0px auto;
            padding: 0px;
            background: transparent;
            animation: 0s ease 0s 1 normal none running none;
            overflow: hidden;
            min-width: 72px;
            width: 72px;
            height: 72px;
            border-radius: 18px;
            border: 2px solid rgb(255, 255, 255);
            box-shadow: rgb(16 25 40 / 8%) 0px 8px 16px 0px;
        }

        @media (min-width: 1024px) {
            .profileImage {
                min-width: 80px;
                width: 80px;
                height: 80px;
                border-radius: 8px;
            }
        }

        .profileImage img {
            height: 100%;
            width: 100%;
        } 
    </style>
	
	{{-- Includable JS --}}
	@yield('innercss')
</head>

<body data-spy="scroll" data-target=".navbar" data-offset="50">
	@yield('content')

    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="{{asset('frontend/vendor/jquery/jquery.min.js') }}"></script>
    <script type="text/javascript" src="{{asset('frontend/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js') }}"></script>
    <!-- slick Slider JS-->
    <script type="text/javascript" src="{{asset('frontend/vendor/slick/slick.min.js') }}"></script>
    <!-- Sidebar JS-->
    <script type="text/javascript" src="{{asset('frontend/vendor/sidebar/hc-offcanvas-nav.js') }}"></script>
    <!-- Custom scripts for all pages-->
    <script type="text/javascript" src="{{asset('frontend/js/osahan.js') }}"></script>
	@yield('scripts')
</body>

</html>