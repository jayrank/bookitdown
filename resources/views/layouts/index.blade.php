<!DOCTYPE html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<!--begin::Head-->

<head>
	<base href="">
	<meta charset="utf-8" />
	
	<title>{{ config('app.name', 'Laravel') }}</title>
	
	<meta name="description"
		content="Metronic admin dashboard live demo. Check out all the features of the admin panel. A large number of settings, additional services and widgets." />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
	<meta name="csrf-token" content="{{ csrf_token() }}">
	<link rel="canonical" href="https://keenthemes.com/metronic" />
	<!--begin::Fonts-->
	<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
	<!--end::Fonts-->
	<!--begin::Page Vendors Styles(used by this page)-->
	<!--link href="{{ asset('js/new-full-calander/fullcalendar.css') }}" rel="stylesheet" type="text/css" /-->
	<!--link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" /-->
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
	
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<!-- International Telephone Css -->
	<link href="{{ asset('assets/css/intlTelInput.css') }}" rel="stylesheet" type="text/css" />
	<style type="text/css">
		.table th, .table td{
			vertical-align: middle;
		}
		table.table.clien_tbl td span {
		    display: block;
		    width: 60px;
		    height: 60px;
		    font-size: 25px;
		}
		.content {
			min-height: 75vh;
		}

		@media only screen and (min-height: 321px) {
			.content {
				min-height: 61vh;
			}
		}
		@media only screen and (min-height: 350px) {
			.content {
				min-height: 65vh;
			}
		}
		@media only screen and (min-height: 530px) {
			.content {
				min-height: 77vh;
			}
		}
		@media only screen and (min-height: 550px) {
			.content {
				min-height: 77vh;
			}
		}
		@media only screen and (min-height: 600px) {
			.content {
				min-height: 80vh;
			}
		}
		@media only screen and (min-height: 640px) {
			.content {
				min-height: 83vh;
			}
		}
		@media only screen and (min-height: 680px) {
			.content {
				min-height: 83vh;
			}
		}
		@media only screen and (min-height: 800px) {
			.content {
				min-height: 85vh;
			}
		}
		@media only screen and (min-height: 950px) {
			.content {
				min-height: 87vh;
			}
		}
		@media only screen and (min-height: 1100px) {
			.content {
				min-height: 92vh;
			}
		}
		@media only screen and (min-height: 1300px) {
			.content {
				min-height: 90vh;
			}
		}
		
	</style>
	{{-- Includable JS --}}
	@yield('innercss')
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_body"
	class="header-fixed header-mobile-fixed subheader-enabled subheader-fixed aside-enabled aside-fixed aside-minimize-hoverable page-loading">
		
		<div class="overlay-loader no-display" style="background: none 0px 0px repeat scroll rgba(238, 238, 238, 0.75); inset: 0px; position: fixed; z-index: 99999999;"><!--  display: none; -->
			<img src="{{ asset('assets/images/aloader.gif') }}" style="position: fixed;top: 40%;left: 40%;width: 20%;">
		</div>
	<!--begin::Main-->
	<!--begin::Header Mobile-->
	@include('layouts.elements.mobile_header')
	<!--end::Header Mobile-->
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="d-flex flex-row flex-column-fluid page">
			<!--begin::Aside-->
			@include('layouts.elements.sidebar')
			<!--end::Aside-->
			<!--begin::Wrapper-->
			<div class="d-flex flex-column flex-row-fluid wrapper nw_setting" id="kt_wrapper">
				<!--begin::Header-->
				@include('layouts.elements.header')
				<!--end::Header-->
				<!--begin::Content-->
				<div class="content " id="kt_content" style="background: #FFF;">
					@yield('content')
					<!--end::Entry-->
				</div>
				<!--end::Content-->
				@include('layouts.elements.footer')
			</div>
			<!--end::Page-->
		</div>
		<!--end::Main-->
		<!--end::Wrapper-->
	</div>
	
	<!-- begin::User Panel-->
	<div id="kt_quick_user" class="offcanvas offcanvas-right p-10">
		<!--begin::Header-->
		<div class="offcanvas-header d-flex align-items-center justify-content-between pb-5">
			<h3 class="font-weight-bold m-0">Profile
				<!-- <small class="text-muted font-size-sm ml-2">12 messages</small> -->
			</h3>
			<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_user_close">
				<i class="ki ki-close icon-xs text-muted"></i>
			</a>
		</div>
		<!--end::Header-->
		<!--begin::Content-->
		@php
			$CurrentUser = auth::user();
		@endphp
		
		<div class="offcanvas-content pr-5 mr-n5">
			<!--begin::Header-->
			<div class="d-flex align-items-center mt-5">
				<div class="symbol symbol-100 mr-5">
					@if($CurrentUser->profile_pic != "")
						<div class="symbol-label" style="background-image:url('{{ asset('uploads/profilepic/'.$CurrentUser->profile_pic) }}')"></div>
					@else 
						<div class="symbol-label" style="background-image:url('{{ asset('assets/media/users/blank.png') }}')"></div>
					@endif
					<i class="symbol-badge bg-success"></i>
				</div>
				<div class="d-flex flex-column">
					<span class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
					{{ ($CurrentUser->first_name) ? $CurrentUser->first_name : ''}} {{ ($CurrentUser->last_name) ? $CurrentUser->last_name : ''}}
					</span>
					<!--div class="text-muted mt-1">Application Developer</div-->
					<div class="navi mt-2">
						<span class="navi-item">
							<span class="navi-link p-0 pb-2">
								<span class="navi-icon mr-1">
									<span class="svg-icon svg-icon-lg svg-icon-primary">
										<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Mail-notification.svg-->
										<svg xmlns="http://www.w3.org/2000/svg"
											xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
											viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<rect x="0" y="0" width="24" height="24" />
												<path
													d="M21,12.0829584 C20.6747915,12.0283988 20.3407122,12 20,12 C16.6862915,12 14,14.6862915 14,18 C14,18.3407122 14.0283988,18.6747915 14.0829584,19 L5,19 C3.8954305,19 3,18.1045695 3,17 L3,8 C3,6.8954305 3.8954305,6 5,6 L19,6 C20.1045695,6 21,6.8954305 21,8 L21,12.0829584 Z M18.1444251,7.83964668 L12,11.1481833 L5.85557487,7.83964668 C5.4908718,7.6432681 5.03602525,7.77972206 4.83964668,8.14442513 C4.6432681,8.5091282 4.77972206,8.96397475 5.14442513,9.16035332 L11.6444251,12.6603533 C11.8664074,12.7798822 12.1335926,12.7798822 12.3555749,12.6603533 L18.8555749,9.16035332 C19.2202779,8.96397475 19.3567319,8.5091282 19.1603533,8.14442513 C18.9639747,7.77972206 18.5091282,7.6432681 18.1444251,7.83964668 Z"
													fill="#000000" />
												<circle fill="#000000" opacity="0.3" cx="19.5" cy="17.5" r="2.5" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</span>
								<span class="navi-text text-muted text-hover-primary">{{ ($CurrentUser->email) ? $CurrentUser->email : ''}}</span>
							</span>
						</span>
						<a href="{{ route('logout') }}" onclick="event.preventDefault();document.getElementById('logout-form').submit();" class="btn btn-sm btn-light-primary font-weight-bolder py-2 px-5">Sign Out</a>
						
						<form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
							@csrf
						</form>
					</div>
				</div>
			</div>
			<!--end::Header-->
			<!--begin::Separator-->
			<div class="separator separator-dashed mt-8 mb-5"></div>
			<!--end::Separator-->
			<!--begin::Nav-->
			<div class="navi navi-spacer-x-0 p-0">
				<a href="{{ route('my_profile') }}" class="navi-item">
					<div class="navi-link">
						<div class="symbol symbol-40 bg-light mr-3">
							<div class="symbol-label">
								<span class="svg-icon svg-icon-md svg-icon-success">
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
										width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<path
												d="M13.2070325,4 C13.0721672,4.47683179 13,4.97998812 13,5.5 C13,8.53756612 15.4624339,11 18.5,11 C19.0200119,11 19.5231682,10.9278328 20,10.7929675 L20,17 C20,18.6568542 18.6568542,20 17,20 L7,20 C5.34314575,20 4,18.6568542 4,17 L4,7 C4,5.34314575 5.34314575,4 7,4 L13.2070325,4 Z"
												fill="#000000" />
											<circle fill="#000000" opacity="0.3" cx="18.5" cy="5.5" r="2.5" />
										</g>
									</svg>
								</span>
							</div>
						</div>
						<div class="navi-text">
							<div class="font-weight-bold">My Profile</div>
							<div class="text-muted">Account settings and more</div>
						</div>
					</div>
				</a>
				<!--begin::Item-->
				<a href="{{ route('userNotificationSettings') }}" class="navi-item">
					<div class="navi-link">
						<div class="symbol symbol-40 bg-light mr-3">
							<div class="symbol-label">
								<span class="svg-icon svg-icon-md svg-icon-warning">
									<!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Chart-bar1.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
										width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
										<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
											<rect x="0" y="0" width="24" height="24" />
											<rect fill="#000000" opacity="0.3" x="12" y="4" width="3" height="13"
												rx="1.5" />
											<rect fill="#000000" opacity="0.3" x="7" y="9" width="3" height="8"
												rx="1.5" />
											<path
												d="M5,19 L20,19 C20.5522847,19 21,19.4477153 21,20 C21,20.5522847 20.5522847,21 20,21 L4,21 C3.44771525,21 3,20.5522847 3,20 L3,4 C3,3.44771525 3.44771525,3 4,3 C4.55228475,3 5,3.44771525 5,4 L5,19 Z"
												fill="#000000" fill-rule="nonzero" />
											<rect fill="#000000" opacity="0.3" x="17" y="11" width="3" height="6"
												rx="1.5" />
										</g>
									</svg>
									<!--end::Svg Icon-->
								</span>
							</div>
						</div>
						<div class="navi-text">
							<div class="font-weight-bold">My Notification Setting</div>
						</div>
					</div>
				</a>
				<!--end:Item-->

				<!--begin::Item-->
				<a href="{{ route('telnyx_setting') }}" class="navi-item">
					<div class="navi-link">
						<div class="symbol symbol-40 bg-light mr-3">
							<div class="symbol-label">
								<span class="svg-icon svg-icon-md svg-icon-warning">
									<!--begin::Svg Icon | path:assets/media/svg/icons/Shopping/Chart-bar1.svg-->
									<svg xmlns="http://www.w3.org/2000/svg" fill="#000000" viewBox="0 0 24 24" width="24px" height="24px"><path d="M 9.6660156 2 L 9.1757812 4.5234375 C 8.3516137 4.8342536 7.5947862 5.2699307 6.9316406 5.8144531 L 4.5078125 4.9785156 L 2.171875 9.0214844 L 4.1132812 10.708984 C 4.0386488 11.16721 4 11.591845 4 12 C 4 12.408768 4.0398071 12.832626 4.1132812 13.291016 L 4.1132812 13.292969 L 2.171875 14.980469 L 4.5078125 19.021484 L 6.9296875 18.1875 C 7.5928951 18.732319 8.3514346 19.165567 9.1757812 19.476562 L 9.6660156 22 L 14.333984 22 L 14.824219 19.476562 C 15.648925 19.165543 16.404903 18.73057 17.068359 18.185547 L 19.492188 19.021484 L 21.826172 14.980469 L 19.886719 13.291016 C 19.961351 12.83279 20 12.408155 20 12 C 20 11.592457 19.96113 11.168374 19.886719 10.710938 L 19.886719 10.708984 L 21.828125 9.0195312 L 19.492188 4.9785156 L 17.070312 5.8125 C 16.407106 5.2676813 15.648565 4.8344327 14.824219 4.5234375 L 14.333984 2 L 9.6660156 2 z M 11.314453 4 L 12.685547 4 L 13.074219 6 L 14.117188 6.3945312 C 14.745852 6.63147 15.310672 6.9567546 15.800781 7.359375 L 16.664062 8.0664062 L 18.585938 7.40625 L 19.271484 8.5917969 L 17.736328 9.9277344 L 17.912109 11.027344 L 17.912109 11.029297 C 17.973258 11.404235 18 11.718768 18 12 C 18 12.281232 17.973259 12.595718 17.912109 12.970703 L 17.734375 14.070312 L 19.269531 15.40625 L 18.583984 16.59375 L 16.664062 15.931641 L 15.798828 16.640625 C 15.308719 17.043245 14.745852 17.36853 14.117188 17.605469 L 14.115234 17.605469 L 13.072266 18 L 12.683594 20 L 11.314453 20 L 10.925781 18 L 9.8828125 17.605469 C 9.2541467 17.36853 8.6893282 17.043245 8.1992188 16.640625 L 7.3359375 15.933594 L 5.4140625 16.59375 L 4.7285156 15.408203 L 6.265625 14.070312 L 6.0878906 12.974609 L 6.0878906 12.972656 C 6.0276183 12.596088 6 12.280673 6 12 C 6 11.718768 6.026742 11.404282 6.0878906 11.029297 L 6.265625 9.9296875 L 4.7285156 8.59375 L 5.4140625 7.40625 L 7.3359375 8.0683594 L 8.1992188 7.359375 C 8.6893282 6.9567546 9.2541467 6.6314701 9.8828125 6.3945312 L 10.925781 6 L 11.314453 4 z M 12 8 C 9.8034768 8 8 9.8034768 8 12 C 8 14.196523 9.8034768 16 12 16 C 14.196523 16 16 14.196523 16 12 C 16 9.8034768 14.196523 8 12 8 z M 12 10 C 13.111477 10 14 10.888523 14 12 C 14 13.111477 13.111477 14 12 14 C 10.888523 14 10 13.111477 10 12 C 10 10.888523 10.888523 10 12 10 z"/></svg>
									<!--end::Svg Icon-->
								</span>
							</div>
						</div>
						<div class="navi-text">
							<div class="font-weight-bold">Telnyx Setting</div>
						</div>
					</div>
				</a>
				<!--end:Item-->
			</div>
			<!--end::Nav-->
		</div>
		<!--end::Content-->
	</div>
	<!-- end::User Panel-->
	<!--begin::Quick Cart-->
	<div id="kt_quick_cart" class="offcanvas offcanvas-right p-10">
		<!--begin::Header-->
		<div class="offcanvas-header d-flex align-items-center justify-content-between pb-7">
			<h4 class="font-weight-bold m-0">Shopping Cart</h4>
			<a href="#" class="btn btn-xs btn-icon btn-light btn-hover-primary" id="kt_quick_cart_close">
				<i class="ki ki-close icon-xs text-muted"></i>
			</a> 
		</div>
		<!--end::Header-->
		<!--begin::Content-->
		<div class="offcanvas-content">
			<!--begin::Wrapper-->
			<div class="offcanvas-wrapper mb-5 scroll-pull">
				<!--begin::Item-->
				<div class="d-flex align-items-center justify-content-between py-8">
					<div class="d-flex flex-column mr-2">
						<a href="#" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">iBlender</a>
						<span class="text-muted">The best kitchen gadget in 2020</span>
						<div class="d-flex align-items-center mt-2">
							<span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 350</span>
							<span class="text-muted mr-1">for</span>
							<span class="font-weight-bold mr-2 text-dark-75 font-size-lg">5</span>
							<a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
								<i class="ki ki-minus icon-xs"></i>
							</a>
							<a href="#" class="btn btn-xs btn-light-success btn-icon">
								<i class="ki ki-plus icon-xs"></i>
							</a>
						</div>
					</div>
					<a href="#" class="symbol symbol-70 flex-shrink-0">
						<img src="{{ asset('assets/media/stock-600x400/img-1.jpg') }}" title="" alt="" />
					</a>
				</div>
				<!--end::Item-->
				<!--begin::Separator-->
				<div class="separator separator-solid"></div>
				<!--end::Separator-->
				<!--begin::Item-->
				<div class="d-flex align-items-center justify-content-between py-8">
					<div class="d-flex flex-column mr-2">
						<a href="#"
							class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">SmartCleaner</a>
						<span class="text-muted">Smart tool for cooking</span>
						<div class="d-flex align-items-center mt-2">
							<span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 650</span>
							<span class="text-muted mr-1">for</span>
							<span class="font-weight-bold mr-2 text-dark-75 font-size-lg">4</span>
							<a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
								<i class="ki ki-minus icon-xs"></i>
							</a>
							<a href="#" class="btn btn-xs btn-light-success btn-icon">
								<i class="ki ki-plus icon-xs"></i>
							</a>
						</div>
					</div>
					<a href="#" class="symbol symbol-70 flex-shrink-0">
						<img src="{{ asset('assets/media/stock-600x400/img-2.jpg') }}" title="" alt="" />
					</a>
				</div>
				<!--end::Item-->
				<!--begin::Separator-->
				<div class="separator separator-solid"></div>
				<!--end::Separator-->
				<!--begin::Item-->
				<div class="d-flex align-items-center justify-content-between py-8">
					<div class="d-flex flex-column mr-2">
						<a href="#" class="font-weight-bold text-dark-75 font-size-lg text-hover-primary">CameraMax</a>
						<span class="text-muted">Professional camera for edge cutting shots</span>
						<div class="d-flex align-items-center mt-2">
							<span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 150</span>
							<span class="text-muted mr-1">for</span>
							<span class="font-weight-bold mr-2 text-dark-75 font-size-lg">3</span>
							<a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
								<i class="ki ki-minus icon-xs"></i>
							</a>
							<a href="#" class="btn btn-xs btn-light-success btn-icon">
								<i class="ki ki-plus icon-xs"></i>
							</a>
						</div>
					</div>
					<a href="#" class="symbol symbol-70 flex-shrink-0">
						<img src="{{ asset('assets/media/stock-600x400/img-3.jpg') }}" title="" alt="" />
					</a>
				</div>
				<!--end::Item-->
				<!--begin::Separator-->
				<div class="separator separator-solid"></div>
				<!--end::Separator-->
				<!--begin::Item-->
				<div class="d-flex align-items-center justify-content-between py-8">
					<div class="d-flex flex-column mr-2">
						<a href="#" class="font-weight-bold text-dark text-hover-primary">4D Printer</a>
						<span class="text-muted">Manufactoring unique objects</span>
						<div class="d-flex align-items-center mt-2">
							<span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 1450</span>
							<span class="text-muted mr-1">for</span>
							<span class="font-weight-bold mr-2 text-dark-75 font-size-lg">7</span>
							<a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
								<i class="ki ki-minus icon-xs"></i>
							</a>
							<a href="#" class="btn btn-xs btn-light-success btn-icon">
								<i class="ki ki-plus icon-xs"></i>
							</a>
						</div>
					</div>
					<a href="#" class="symbol symbol-70 flex-shrink-0">
						<img src="{{ asset('assets/media/stock-600x400/img-4.jpg') }}" title="" alt="" />
					</a>
				</div>
				<!--end::Item-->
				<!--begin::Separator-->
				<div class="separator separator-solid"></div>
				<!--end::Separator-->
				<!--begin::Item-->
				<div class="d-flex align-items-center justify-content-between py-8">
					<div class="d-flex flex-column mr-2">
						<a href="#" class="font-weight-bold text-dark text-hover-primary">MotionWire</a>
						<span class="text-muted">Perfect animation tool</span>
						<div class="d-flex align-items-center mt-2">
							<span class="font-weight-bold mr-1 text-dark-75 font-size-lg">$ 650</span>
							<span class="text-muted mr-1">for</span>
							<span class="font-weight-bold mr-2 text-dark-75 font-size-lg">7</span>
							<a href="#" class="btn btn-xs btn-light-success btn-icon mr-2">
								<i class="ki ki-minus icon-xs"></i>
							</a>
							<a href="#" class="btn btn-xs btn-light-success btn-icon">
								<i class="ki ki-plus icon-xs"></i>
							</a>
						</div>
					</div>
					<a href="#" class="symbol symbol-70 flex-shrink-0">
						<img src="{{ asset('assets/media/stock-600x400/img-8.jpg') }}" title="" alt="" />
					</a>
				</div>
				<!--end::Item-->
			</div>
			<!--end::Wrapper-->
			<!--begin::Purchase-->
			<div class="offcanvas-footer">
				<div class="d-flex align-items-center justify-content-between mb-4">
					<span class="font-weight-bold text-muted font-size-sm mr-2">Total</span>
					<span class="font-weight-bolder text-dark-50 text-right">$1840.00</span>
				</div>
				<div class="d-flex align-items-center justify-content-between mb-7">
					<span class="font-weight-bold text-muted font-size-sm mr-2">Sub total</span>
					<span class="font-weight-bolder text-primary text-right">$5640.00</span>
				</div>
				<div class="text-right">
					<button type="button" class="btn btn-primary text-weight-bold">Place Order</button>
				</div>
			</div>
			<!--end::Purchase-->
		</div>
		<!--end::Content-->
	</div>
	<!--end::Quick Cart-->
	<!--begin::Quick Panel-->
	<div id="kt_quick_panel" class="offcanvas offcanvas-right pt-5 pb-10">
		<!--begin::Header-->
		<div class="offcanvas-header d-flex align-items-center justify-content-between">
			<h4 class="font-weight-bold p-4 m-0">Notifications</h4>
			<div class="pr-2">
				<a href="{{ route('userNotificationSettings') }}" class="btn btn-sm btn-icon btn-light btn-hover-primary">
					<i class="fa fa-cog icon-md text-dark"></i>
				</a>
				<a href="javascript:;" class="btn btn-sm btn-icon" id="kt_quick_panel_close">
					<i class="ki ki-close icon-md text-dark"></i>
				</a>
			</div>
		</div>
		<!--end::Header-->

		<!--begin::Content-->
		<div class="offcanvas-content px-5">
			<div class="tab-content">
			    <!--begin::Tabpane--><!-- tab-pane fade pt-2 pr-5 mr-n5 scroll ps active show ps--active-y -->
			    <div class="tab-pane fade pt-2 pr-5 mr-n5 active show" id="kt_quick_panel_notifications" role="tabpanel" style="height: 563px; overflow: auto;">
			        <!--begin::Nav-->
			        <div class="navi navi-icon-circle navi-spacer-x-0" id="sideBarNotification">

						<!-- <div  style="margin-top: 60%; margin-left: 40%;">
							<img src="{{ asset('assets/images/1495.gif') }}" title="Loader" alt="Loader"/>
						</div> -->

			        </div>
			        <!--end::Nav-->
			    </div>
			    <!--end::Tabpane-->
			</div>
		</div>

		<!--end::Content-->

	</div>
	<!--end::Quick Panel-->

	<!--begin::Chat Panel-->
	<div class="modal modal-sticky modal-sticky-bottom-right" id="kt_chat_modal" role="dialog" data-backdrop="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<!--begin::Card-->
				<div class="card card-custom">
					<!--begin::Header-->
					<div class="card-header align-items-center px-4 py-3">
						<div class="text-left flex-grow-1">
							<!--begin::Dropdown Menu-->
							<div class="dropdown dropdown-inline">
								<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="svg-icon svg-icon-lg">
										<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
										<svg xmlns="http://www.w3.org/2000/svg"
											xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
											viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<polygon points="0 0 24 0 24 24 0 24" />
												<path
													d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
													fill="#000000" fill-rule="nonzero" opacity="0.3" />
												<path
													d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
													fill="#000000" fill-rule="nonzero" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</button>
								<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-md">
									<!--begin::Navigation-->
									<ul class="navi navi-hover py-5">
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-drop"></i>
												</span>
												<span class="navi-text">New Group</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-list-3"></i>
												</span>
												<span class="navi-text">Contacts</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-rocket-1"></i>
												</span>
												<span class="navi-text">Groups</span>
												<span class="navi-link-badge">
													<span
														class="label label-light-primary label-inline font-weight-bold">new</span>
												</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-bell-2"></i>
												</span>
												<span class="navi-text">Calls</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-gear"></i>
												</span>
												<span class="navi-text">Settings</span>
											</a>
										</li>
										<li class="navi-separator my-3"></li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-magnifier-tool"></i>
												</span>
												<span class="navi-text">Help</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-bell-2"></i>
												</span>
												<span class="navi-text">Privacy</span>
												<span class="navi-link-badge">
													<span
														class="label label-light-danger label-rounded font-weight-bold">5</span>
												</span>
											</a>
										</li>
									</ul>
									<!--end::Navigation-->
								</div>
							</div>
							<!--end::Dropdown Menu-->
						</div>
						<div class="text-center flex-grow-1">
							<div class="text-dark-75 font-weight-bold font-size-h5">Matt Pears</div>
							<div>
								<span class="label label-dot label-success"></span>
								<span class="font-weight-bold text-muted font-size-sm">Active</span>
							</div>
						</div>
						<div class="text-right flex-grow-1">
							<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md"
								data-dismiss="modal">
								<i class="ki ki-close icon-1x"></i>
							</button>
						</div>
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body">
						<!--begin::Scroll-->
						<div class="scroll scroll-pull" data-height="375" data-mobile-height="300">
							<!--begin::Messages-->
							<div class="messages">
								<!--begin::Message In-->
								<div class="d-flex flex-column mb-5 align-items-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-circle symbol-40 mr-3">
											<img alt="Pic" src="{{ asset('assets/media/users/300_12.jpg') }}" />
										</div>
										<div>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
												Pears</a>
											<span class="text-muted font-size-sm">2 Hours</span>
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
										How likely are you to recommend our company to your friends and family?
									</div>
								</div>
								<!--end::Message In-->
								<!--begin::Message Out-->
								<div class="d-flex flex-column mb-5 align-items-end">
									<div class="d-flex align-items-center">
										<div>
											<span class="text-muted font-size-sm">3 minutes</span>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
										</div>
										<div class="symbol symbol-circle symbol-40 ml-3">
											<img alt="Pic" src="{{ asset('assets/media/users/300_21.jpg') }}" />
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
										Hey there, we’re just writing to let you know that you’ve been subscribed to
										a
										repository on GitHub.</div>
								</div>
								<!--end::Message Out-->
								<!--begin::Message In-->
								<div class="d-flex flex-column mb-5 align-items-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-circle symbol-40 mr-3">
											<img alt="Pic" src="{{ asset('assets/media/users/300_21.jpg') }}" />
										</div>
										<div>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
												Pears</a>
											<span class="text-muted font-size-sm">40 seconds</span>
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
										Ok, Understood!</div>
								</div>
								<!--end::Message In-->
								<!--begin::Message Out-->
								<div class="d-flex flex-column mb-5 align-items-end">
									<div class="d-flex align-items-center">
										<div>
											<span class="text-muted font-size-sm">Just now</span>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
										</div>
										<div class="symbol symbol-circle symbol-40 ml-3">
											<img alt="Pic" src="{{ asset('assets/media/users/300_21.jpg') }}" />
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
										You’ll receive notifications for all issues, pull requests!</div>
								</div>
								<!--end::Message Out-->
								<!--begin::Message In-->
								<div class="d-flex flex-column mb-5 align-items-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-circle symbol-40 mr-3">
											<img alt="Pic" src="{{ asset('assets/media/users/300_12.jpg') }}" />
										</div>
										<div>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
												Pears</a>
											<span class="text-muted font-size-sm">40 seconds</span>
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
										You can unwatch this repository immediately by clicking here:
										<a href="#">https://github.com</a>
									</div>
								</div>
								<!--end::Message In-->
								<!--begin::Message Out-->
								<div class="d-flex flex-column mb-5 align-items-end">
									<div class="d-flex align-items-center">
										<div>
											<span class="text-muted font-size-sm">Just now</span>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
										</div>
										<div class="symbol symbol-circle symbol-40 ml-3">
											<img alt="Pic" src="{{ asset('assets/media/users/300_21.jpg') }}" />
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
										Discover what students who viewed Learn Figma - UI/UX Design. Essential
										Training
										also viewed</div>
								</div>
								<!--end::Message Out-->
								<!--begin::Message In-->
								<div class="d-flex flex-column mb-5 align-items-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-circle symbol-40 mr-3">
											<img alt="Pic" src="{{ asset('assets/media/users/300_12.jpg') }}" />
										</div>
										<div>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
												Pears</a>
											<span class="text-muted font-size-sm">40 seconds</span>
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
										Most purchased Business courses during this sale!</div>
								</div>
								<!--end::Message In-->
								<!--begin::Message Out-->
								<div class="d-flex flex-column mb-5 align-items-end">
									<div class="d-flex align-items-center">
										<div>
											<span class="text-muted font-size-sm">Just now</span>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
										</div>
										<div class="symbol symbol-circle symbol-40 ml-3">
											<img alt="Pic" src="{{ asset('assets/media/users/300_21.jpg') }}" />
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
										Company BBQ to celebrate the last quater achievements and goals. Food and
										drinks
										provided</div>
								</div>
								<!--end::Message Out-->
							</div>
							<!--end::Messages-->
						</div>
						<!--end::Scroll-->
					</div>
					<!--end::Body-->
					<!--begin::Footer-->
					<div class="card-footer align-items-center">
						<!--begin::Compose-->
						<textarea class="form-control border-0 p-0" rows="2" placeholder="Type a message"></textarea>
						<div class="d-flex align-items-center justify-content-between mt-5">
							<div class="mr-3">
								<a href="#" class="btn btn-clean btn-icon btn-md mr-1">
									<i class="flaticon2-photograph icon-lg"></i>
								</a>
								<a href="#" class="btn btn-clean btn-icon btn-md">
									<i class="flaticon2-photo-camera icon-lg"></i>
								</a>
							</div>
							<div>
								<button type="button"
									class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6">Send</button>
							</div>
						</div>
						<!--begin::Compose-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Card-->
			</div>
		</div>
	</div>
	<!--end::Chat Panel-->
	<!--begin::Scrolltop-->
	<div id="kt_scrolltop" class="scrolltop">
		<span class="svg-icon">
			<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
				height="24px" viewBox="0 0 24 24" version="1.1">
				<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<polygon points="0 0 24 0 24 24 0 24" />
					<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
					<path
						d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
						fill="#000000" fill-rule="nonzero" />
				</g>
			</svg>
			<!--end::Svg Icon-->
		</span>
	</div>
	<script>
		var WEBSITE_URL = "{{ url('') }}";
	</script>
	<!--end::Scrolltop-->
	<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
	<!--begin::Global Config(global config for global JS scripts)-->
	<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
	<!--end::Global Config-->
	<!--begin::Global Theme Bundle(used by all pages)-->
	<script src="{{ asset('js/plugins.bundle.js') }}"></script>
	<script src="{{ asset('prismjs/prismjs.bundle.js') }}"></script>
	<script src="{{ asset('js/scripts.bundle.js') }}"></script>
	{{-- <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script> --}}
	<!--end::Global Theme Bundle-->
	{{-- Includable JS --}}
	@yield('scripts')
	<script type="text/javascript">
		
		$(document).ready(function(){

			$(document).on("click", ".no_access", function() {
				toastr.options = {
					"closeButton": false,
					"debug": false,
					"newestOnTop": true,
					"progressBar": true,
					"positionClass": "toast-top-right",
					"preventDuplicates": false,
					"onclick": null,
					"showDuration": "300",
					"hideDuration": "1000",
					"timeOut": "4000",
					"extendedTimeOut": "1000",
					"showEasing": "swing",
					"hideEasing": "linear",
					"showMethod": "fadeIn",
					"hideMethod": "fadeOut"
				};
				toastr.error("You don't have permission for this action, ask your account owner for access");
			});
			

			var page = 1;

			loadNotification(page);

			$('div#kt_quick_panel_notifications').on('scroll',function() {
				console.log('scrollTop::'+$(this).scrollTop());
				console.log('innerHeight::'+$(this).innerHeight());
				console.log('scrollHeight::'+$(this)[0].scrollHeight);
				console.log('condition::'+($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight));
			    if ($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
			    	page++;
			        loadNotification(page);
			    }
			});

			$(document).on('click','#kt_quick_panel_toggle', function(){
				$.ajax({
					url: '{{ route("notificationMarkRead") }}',
					type: 'GET',
					success: function(response) {
						// $("#sideBarNotification").append(response);
					}
				});				
			});

			function loadNotification(page) {

				$.ajax({
					url: '{{ route("getsidebarnotification") }}'+'?page=' + page,
					type: 'GET',
					dataType: 'JSON',
					success: function(response) {
						$("#sideBarNotification").append(response.notificationData);
						$(".notification-count").append(response.notificationCount);

						if(response.notificationCount > 0) {
							$('.notification-count').show();
						} else {
							$('.notification-count').hide();
						}
					}
				});

			}

			if('{{ Session::has('success') }}')
			{
				responseMessages('success','{{ Session::get('success') }}')	
			}
			else if('{{ Session::has('error') }}')
			{
				responseMessages('error','{{ Session::get('error') }}')	
			}

			fetchUpcomingAppointments($('#searchText').val());
			fetchClients($('#searchText').val());
			$(document).on('keyup','#searchText', function(){
			    var self = $(this);
			    var searchText = self.val();

			    fetchUpcomingAppointments(searchText);
			    fetchClients(searchText);
			});
		});

		function responseMessages(type,message)
		{
			if(type == "success")
		{
			toastr.options = {
				"closeButton": false,
				"debug": false,
				"newestOnTop": true,
				"progressBar": true,
				"positionClass": "toast-top-right",
				"preventDuplicates": false,
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "4000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};

			toastr.success(message);
		}
		else
		{

			toastr.options = {
				"closeButton": false,
				"debug": false,
				"newestOnTop": true,
				"progressBar": true,
				"positionClass": "toast-top-right",
				"preventDuplicates": false,
				"onclick": null,
				"showDuration": "300",
				"hideDuration": "1000",
				"timeOut": "4000",
				"extendedTimeOut": "1000",
				"showEasing": "swing",
				"hideEasing": "linear",
				"showMethod": "fadeIn",
				"hideMethod": "fadeOut"
			};

			toastr.error(message);
			return false;
		}
		}

		function fetchUpcomingAppointments(searchText) {

		    $.ajax({
		        url: '{{ route("appointmentSearch") }}',
		        type: 'POST',
		        dataType: 'JSON',
		        data: {
		            "_token": "{{ csrf_token() }}",
		            searchText: searchText
		        },
		        success: function(response) {
		            if(response.status) {
		            	var content = '';
		            	var status = '';
		            	var duration = 0;

		            	if(response.data.length > 0) {
		            		for( index = 0; index < response.data.length; index++ ) {

		            		content += '<a href="{{ url("partners/appointments/view/") }}/'+response.data[index].encrypt_id+'">';
		            			content += '<div class="row single-appointments">';
			        				content += '<div class="col-md-2">';
			        					content += moment(response.data[index].appointment_date).format('D MMM');
			        				content += '</div>';
			        				content += '<div class="col-md-8">';
			        					content += '<div>';
				        				
		        						content += '<span class="text-muted">'+response.data[index].start_time+'</span>&nbsp;';

			        					status = '';
			        					if(response.data[index].is_cancelled == 1) {
			        						status = 'CANCELLED';
			        					} else {
			        						if(response.data[index].appointment_status == 0) {
			        							status = 'NEW';
			        						} else if(response.data[index].appointment_status == 1) {
			        							status = 'CONFIRMED';
			        						} else if(response.data[index].appointment_status == 2) {
			        							status = 'ARRIVED';
			        						} else if(response.data[index].appointment_status == 3) {
			        							status = 'STARTED';
			        						} else if(response.data[index].appointment_status == 4) {
			        							status = 'COMPLETED';
			        						}
			        					}
				        					content += '<span class="text-primary">'+status+'</span>';
				        				content += '</div>';
				        				content += '<div>';
				        					content += '<h5>';
				        						content += '<b>'+response.data[index].service_name+'</b>';
				        					content += '</h5>';
				        				content += '</div>';
				        				content += '<div>';

				        				duration = parseInt(response.data[index].duration) + parseInt(response.data[index].extra_time_duration);

				        				// duration = duration / 60;
				        				var totalMinutes = duration;

			        				    var hours = Math.floor(totalMinutes / 60);          
			        				    var minutes = totalMinutes % 60;

			        				    duration = hours + 'h ' + minutes + 'm';  

				        					content += '<span class="text-muted">'+duration+'h with '+response.data[index].first_name+' '+response.data[index].last_name+'</span>';
				        				content += '</div>';
			        				content += '</div>';
			        				content += '<div class="col-md-2">';
			        					content += '<h6>$'+response.data[index].special_price+'</h6>';
			        				content += '</div>';
			        			content += '</div>';
		        			content += '</a>';
		            		}
		            	}

		            	$('.upcoming-appointments-container').html(content);
		            } else {
		                $('.upcoming-appointments-container').html('');
		            }
		        },
		        error: function(response) {
		            $('.upcoming-appointments-container').html('');
		        },
		        complete: function(response) {

		        }
		    });
		    
		}

		function fetchClients(searchText) {

		    $.ajax({
		        url: '{{ route("clientSearch") }}',
		        type: 'POST',
		        dataType: 'JSON',
		        data: {
		            "_token": "{{ csrf_token() }}",
		            searchText: searchText
		        },
		        success: function(response) {
		            if(response.status) {
		            	var content = '';
		            	var status = '';
		            	var duration = 0;

		            	if(response.data.length > 0) {
		            		for( index = 0; index < response.data.length; index++ ) {
		            		content += '<a href="{{ url("partners/view/") }}/'+response.data[index].id+'/client">';
		            			content += '<div class="row single-client py-2">';
			        				content += '<div class="col-2">';
			        					content += '<span class="client-initial">'+response.data[index].firstname.charAt(0)+'</span>';
			        				content += '</div>';
			        				content += '<div class="col-10" style="padding-left: 50px;">';
			        					content += '<h6>';
			        						content += '<b>'+response.data[index].firstname+' '+ response.data[index].lastname+'</b><br>';

			        						if(response.data[index].mobileno != '') {
			        							content += '<span class="text-muted">'+response.data[index].mobileno+'</span>';
			        						}
			        					content += '</h6>';
			        				content += '</div>';
			        			content += '</div>';
			        		content += '</a>';
		            		}
		            	}

		            	$('.search-client-container').html(content);
		            } else {
		                $('.search-client-container').html('');
		            }
		        },
		        error: function(response) {  
		            $('.search-client-container').html('');
		        },
		        complete: function(response) {

		        }
		    });
		    
		}

	</script>

	<script type="text/javascript">
		$(document).ready(function(){
			$('.overlay-loader').hide();
		});
	</script>
</html>