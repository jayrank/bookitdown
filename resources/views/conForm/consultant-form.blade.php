{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
	
</style>
@endsection

@section('content')
	
	
		<!--begin::Header-->
		<div id="kt_header" class="header header-fixed">
			<!--begin::Container-->
			<div class="container-fluid d-flex align-items-stretch justify-content-between">
				<!--begin::Header Menu Wrapper-->
				<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
					<!--begin::Header Menu-->
					<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
						<h2 style="line-height: 65px;">Consultation Forms</h2>
					</div>
					<!--end::Header Menu-->
				</div>
				<!--end::Header Menu Wrapper-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Header-->
		<!--begin::Content-->
		<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
			<!--begin::Tabs-->
			<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
				<div
					class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
					<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
						role="tablist">
						<!-- <li class="nav-item">
							<a class="nav-link active" href="{{ url('partners/conForm') }}">Overview</a>
						</li> -->
						<li class="nav-item">
							<a class="nav-link" href="{{ route('showconForm') }}">Consultation Form</a>
						</li>
					</ul>
				</div>
			</div>
			<!--end::Tabs-->
			<!--begin::Entry-->
			<div class="d-flex flex-column-fluid">
				<!--begin::Container-->
				<div class="container">
					<!--begin::Sales-->
					<!--begin::Row-->
					<div class="row">
						<div class="overview ovreview-banner">
							<div class="my-16">
								<div class=" col-12 col-md-12 col-lg-6">
									<h3 class="sub-title mb-4 font-weight-bolder">ScheduleDown <span
											class="text-orange">Plus</span></h3>
									<h1 class="title mb-4 font-weight-bolder">Elevate your business to its full
										potential</h1>
									<h4 class="desc mb-4">Get access to powerful online booking features used by
										millions of
										customers. List your business on ScheduleDown marketplace
										and mobile app, attract new clients, drive more bookings and supercharge
										your growth with integrated marketing tools</h4>
									<a class=" btn-lg btn-primary" href="{{ route('showconForm') }}" >Start now</a>
								</div>
								<div class="p-0 col-12 col-md-12 col-lg-6 overview-md d-md-block d-lg-none">
								</div>
							</div>
							<div class="bg-white">
								<div class="row">
									<div class="col-12 col-sm-12 col-md-6 p-10">
										<div class="img-div">
											<div class="d-flex justify-content-center align-items-center">
												<img alt="phone" src="{{ asset('/assets/images/book-now-back.png') }}"
													width="100%" />
												<img alt="phone" class="position-absolute"
													src="{{ asset('/assets/images/book-now.png') }}" width="100%" />
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-12 col-md-6 p-14">
										<div class="content-div py-10">
											<h1 class="title mb-4">Effortless online booking</h1>
											<h4 class="mb-4">Join the world’s largest beauty network that sends
												millions
												of clients
												to salons and spas every month</h4>
											<h4>
												Create a business profile and be discovered 24/7 on Fresha
												marketplace,
												Instagram, Facebook, Google and your own
												website. Free up your time and focus on what you do best.
												<a class="text-blue" href="booking.html">Learn More</a>
											</h4>

										</div>
									</div>
								</div>
								<div class="row col-sm-reverse">
									<div class="col-12 col-sm-12 col-md-6 p-14">
										<div class="content-div py-10">
											<h1 class="title mb-4">ScheduleDown card terminals</h1>
											<h4 class="mb-4">
												With a ScheduleDown card terminal, you can securely save client
												payment details, take payments and collect tips at no extra
												cost. There s no monthly rental and all processing charges are
												covered by the standard ScheduleDown Plus transaction fees.
												<a class="text-blue" href="card_proccessing.html.html">Learn
													More</a>
											</h4>
										</div>
									</div>
									<div class="col-12 col-sm-12 col-md-6 p-10">
										<div class="img-div">
											<div class="d-flex justify-content-center align-items-center">
												<img alt="phone" class="position-absolute"
													src="{{ asset('/assets/images/swipe.png') }}" width="100%" />
												<img alt="phone" src="{{ asset('/assets/images/watch.png') }}" width="100%" />
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12 col-sm-12 col-md-6 p-10">
										<div class="img-div">
											<div class="d-flex justify-content-center align-items-center">
												<img alt="phone" src="{{ asset('/assets/images/phone.png') }}" width="100%" />
												<img alt="phone" class="position-absolute"
													src="{{ asset('/assets/images/chat.png') }}" width="100%" />
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-12 col-md-6 p-14">
										<div class="content-div py-10">
											<h1 class="title mb-4">Advanced no-show protection</h1>
											<h4 class="mb-4">
												Say goodbye to no-shows and late cancellations with remote
												charging of penalty fees. Take back control of your calendar
												with enforceable booking policies, without the hassle of upfront
												deposits.
												<a class="text-blue" href="booking.html">Learn More</a>
											</h4>

										</div>
									</div>
								</div>
								<div class="row col-sm-reverse">
									<div class="col-12 col-sm-12 col-md-6 p-14">
										<div class="content-div py-10">
											<h1 class="title mb-4">Send smart marketing offers</h1>
											<h4 class="mb-4">
												Boost sales and fill your calendar with a suite of intelligent
												marketing tools. Retain and reward clients using smart
												targeting and personalized offers for birthdays, lapsing clients
												and more.
												<a class="text-blue" href="card_proccessing.html.html">Learn
													More</a>
											</h4>
										</div>
									</div>
									<div class="col-12 col-sm-12 col-md-6 p-10">
										<div class="img-div">
											<div class="d-flex justify-content-center align-items-center">
												<img alt="phone" src="{{ asset('/assets/images/discount.png') }}"
													width="100%" />
												<img alt="phone" class="position-absolute"
													src="{{ asset('/assets/images/birthday.png') }}" width="100%" />
											</div>
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-12 col-sm-12 col-md-6 p-10">
										<div class="img-div">
											<div class="d-flex justify-content-center align-items-center">
												<img alt="phone" src="{{ asset('/assets/images/visa-phone.png') }}"
													width="100%" />
												<img alt="phone" class="position-absolute"
													src="{{ asset('/assets/images/visa.png') }}" width="100%" />
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-12 col-md-6 p-14">
										<div class="content-div py-10">
											<h1 class="title mb-4">Integrated payment processing</h1>
											<h4 class="mb-4">
												Securely save client payment details to ScheduleDown for
												effortless
												in-app payment – all processing charges are included in
												your ScheduleDown Plus transaction fees. Automatically receive
												daily
												payouts direct to your bank account.
												<a class="text-blue" href="booking.html">Learn More</a>
											</h4>

										</div>
									</div>
								</div>

								<div class="row col-sm-reverse">

									<div class="col-12 col-sm-12 col-md-6 p-14">
										<div class="content-div py-10">
											<h1 class="title mb-4">Attract quality new clients</h1>
											<h4 class="mb-4">
												Reach thousands of new clients who visit ScheduleDown every day.
												Rank
												higher in online searches and sharpen your marketing
												with integrated conversion tracking for Google and Facebook.
												<a class="text-blue" href="booking.html">Learn More</a>
											</h4>

										</div>
									</div>
									<div class="col-12 col-sm-12 col-md-6 p-10">
										<div class="img-div">
											<div class="d-flex justify-content-center align-items-center">
												<img alt="phone" src="{{ asset('/assets/images/map.png') }}" width="100%" />
												<img alt="phone" class="position-absolute"
													src="{{ asset('/assets/images/chat-location.png') }}" width="100%" />
											</div>
										</div>
									</div>
								</div>

								<div class="row col-sm-reverse">
									<div class="col-12 col-sm-12 col-md-6 p-10">
										<div class="img-div">
											<div class="d-flex justify-content-center align-items-center">
												<img alt="phone" src="{{ asset('/assets/images/salon.png') }}" width="100%" />
												<img alt="phone" class="position-absolute"
													src="{{ asset('/assets/images/reminder.png') }}" width="100%" />
											</div>
										</div>
									</div>
									<div class="col-12 col-sm-12 col-md-6 p-14">
										<div class="content-div py-10">
											<h1 class="title mb-4">Automated SMS text reminders</h1>
											<h4 class="mb-4">
												Reduce no-shows by reminding clients of their upcoming
												appointments. Send unlimited automated SMS text messages direct
												from our platform.
												<a class="text-blue" href="booking.html">Learn More</a>
											</h4>

										</div>
									</div>
								</div>

								<div class=" p-8 pb-10">
									<div class="col-12 text-center">
										<h3 class="sub-title mb-4 font-weight-bolder">ScheduleDown <span
												class="text-orange">Plus</span></h3>
										<h1 class=" mb-4 font-weight-bolder">
											Elevate your business to its full potential
										</h1>
										<h5 class="mb-4">
											Take your business to the next level with powerful booking features,
											automated marketing tools and integrated payment
											processing. ScheduleDown Plus makes managing and maximizing your
											business
											so much easier – so you have more time to do what
											you do best
										</h5>
										<a class=" btn-lg btn-primary" href="{{ route('showconForm') }}" >Start now</a>
									</div>
								</div>
							</div>
						</div>
					</div>
					<!--end::Row-->
					<!--end::Sales-->
				</div>
				<!--end::Container-->
			</div>
			<!--end::Entry-->
		</div>
		<!--end::Content-->

		


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
	<!--end::Scrolltop-->
@endsection

{{-- Scripts Section --}}
@section('scripts')
	<!--begin::Page Scripts(used by this page)-->
	<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
	<!--end::Page Scripts-->
	<script type="text/javascript">

		function openNav() {
			document.getElementById("myFilter").style.width = "300px";
		}

		function closeNav() {
			document.getElementById("myFilter").style.width = "0%";
		}
	</script>
	<script>
		// Class definition
		var KTBootstrapDatepicker = function () {

			var arrows;
			if (KTUtil.isRTL()) {
				arrows = {
					leftArrow: '<i class="la la-angle-right"></i>',
					rightArrow: '<i class="la la-angle-left"></i>'
				}
			} else {
				arrows = {
					leftArrow: '<i class="la la-angle-left"></i>',
					rightArrow: '<i class="la la-angle-right"></i>'
				}
			}

			// Private functions
			var demos = function () {
				// minimum setup
				$('#kt_datepicker_1').datepicker({
					rtl: KTUtil.isRTL(),
					todayHighlight: true,
					autoclose: true,
					format: 'DD, dd/mm/yyyy',
					orientation: "bottom left",
					templates: arrows
				});
			}

			return {
				// public functions
				init: function () {
					demos();
				}
			};
		}();

		jQuery(document).ready(function () {
			KTBootstrapDatepicker.init();
		});
	</script>
@endsection