<!DOCTYPE html>

<html lang="en">
<!--begin::Head-->

<head>
	<base href="">
	<meta charset="utf-8" />
	<title>ScheduleDown </title>
	<meta name="description"
		content="Metronic admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<link rel="canonical" href="https://keenthemes.com/metronic" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Vendors Styles(used by this page)-->
	<link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
	<!--end::Page Vendors Styles-->
	<!--begin::Global Theme Styles(used by all pages)-->
	<link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/custom.css') }}" rel="stylesheet" type="text/css" />
	<!--end::Global Theme Styles-->
	<!--begin::Layout Themes(used by all pages)-->
	<link href="{{ asset('assets/css/themes/layout/header/base/light.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/themes/layout/header/menu/light.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/themes/layout/brand/dark.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('assets/css/themes/layout/aside/dark.css') }}" rel="stylesheet" type="text/css" />
	<!--end::Layout Themes-->
	<link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />

	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body" class="bg-white">
	<div class="container-fluid p-0">
		<div class="my-custom-body-wrapper">
			<div class="my-custom-header">
				<div class="border-bottom d-flex justify-content-center p-4 text-center">
					<h1 class="font-weight-bolder">List Profile Online
						<a href="{{ route('online_profile') }}" class="cursor-pointer float-right m-0 position-absolute px-10 right-0"><i
							class="text-dark fa fa-times icon-lg"></i></a>
					</h1>
				</div>
			</div>
			<div class="my-custom-body">
				<div class="container">
					<div class="row">
						<div class="col-md-4 offset-md-4 text-center py-20">
							<div class="check-svg">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 80 80"><path d="M56.2 5c-5.7-3.3-12.1-5-18.7-5C16.8 0 0 16.8 0 37.5S16.8 75 37.5 75 75 58.2 75 37.5c0-6.6-1.7-13.1-5.1-18.8"></path><path d="M18.5 38.5L31 51c.4.4 1 .4 1.4 0 0 0 0-.1.1-.1L63.8 12"></path></svg>
							</div>
							<h2 class="font-weight-bolder mb-5">Your updated profile is now listed online</h2>
							<h4 class="mb-10">Spread the word and let clients know they can now book with you online</h4>
							<div class="action-btn d-flex justify-content-center">
								<a href="{{ route('search_detail',['id' => Crypt::encryptString($locationId)]) }}" target="_blank" class="btn btn-lg btn-white mr-4 w-150px">View Profile</a>
								<a href="{{ route('online_profile') }}" class="btn btn-lg btn-dark w-150px">Done</a>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr class="m-0">
			<div class="my-custom-footer container py-9"></div>
		</div>


		<!--begin::Scrolltop-->
		<div id="kt_scrolltop" class="scrolltop">
			<span class="svg-icon">
				<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
				<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
					height="24px" viewBox="0 0 24 24" version="1.1">
					<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
						<polygon points="0 0 24 0 24 24 0 24" />
						<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
						<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
					</g>
				</svg>
				<!--end::Svg Icon-->
			</span>
		</div>


		<!--end::Scrolltop-->
		<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
		<!--begin::Global Config(global config for global JS scripts)-->
		<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
		<!--end::Global Config-->
		<!--begin::Global Theme Bundle(used by all pages)-->
		<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
		<script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
		<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
		<!--end::Global Theme Bundle-->
		<!--begin::Page Vendors(used by this page)-->
		<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
		<!--end::Page Vendors-->
		<!--begin::Page Scripts(used by this page)-->
		<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
		<!--end::Page Scripts-->

		<!-- Isposata address same -->
		<script>
			$("#enableRetailSales").click(function () {
				if ($(this).is(":checked")) {
					$("#ifRetailEnable").show();
				} else {
					$("#ifRetailEnable").hide();
				}
			});
			$("#enableStockControl").click(function () {
				if ($(this).is(":checked")) {
					$("#ifStocControlkEnable").show();
				} else {
					$("#ifStocControlkEnable").hide();
				}
			});
		</script>
</body>
<!--end::Body-->


</html>