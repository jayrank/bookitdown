<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	
	<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
	
	<link href="{{ asset('assets/css/themes/layout/header/base/light.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/themes/layout/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/themes/layout/brand/dark.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/themes/layout/aside/dark.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/image-picker.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css" />
	{{-- Includable JS --}}
	@yield('innercss')
	
	<link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
</head>
	<body id="kt_body" class="bg-content">
		<div class="overlay-loader no-display" style="background: none 0px 0px repeat scroll rgba(238, 238, 238, 0.75); inset: 0px; position: fixed; z-index: 99999999;"><!--  display: none; -->
			<img src="{{ asset('assets/images/aloader.gif') }}" style="position: fixed;top: 40%;left: 40%;width: 20%;">
		</div>
		<!--begin::Main-->
		<div class="d-flex flex-column flex-root">
			@yield('content')
		</div>
		<script>
			var WEBSITE_URL = "{{ url('') }}";
		</script>
		<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
		
		<script src="{{ asset('js/plugins.bundle.js') }}"></script>
		<script src="{{ asset('prismjs/prismjs.bundle.js') }}"></script>
		<script src="{{ asset('js/scripts.bundle.js') }}"></script>
		<script src="{{ asset('assets/plugins/jquery.mask.js') }}"></script>		
		{{-- Includable JS --}}
        @yield('scripts')
	</body>
	<script type="text/javascript">
		$(document).ready(function(){
			$('.overlay-loader').hide();
		});
	</script>
</html>
