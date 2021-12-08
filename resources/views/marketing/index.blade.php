{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
	<!--begin::Tabs-->
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div
			class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
				role="tablist">
				<!-- <li class="nav-item">
					<a class="nav-link active" href="{{ route('marketingCampaign') }}">Overview</a>
				</li> -->
				<li class="nav-item">
					<a class="nav-link" href="{{ route('smart_campaigns') }}">Smart Campaigns</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ url('partners/marketing/marketing_blast_messages') }}">Blast messages</a>
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
				<div class="marketing marketing-banner">
					<div class="my-16">
						<div class="row">
							<div class="col-12 col-md-12 col-lg-6  p-8">
								<h3 class="sub-title mb-4 font-weight-bolder">ScheduleDown <span
										class="text-orange">Plus</span></h3>
								<h1 class="title mb-4 font-weight-bolder">
									Send smart marketing offers
								</h1>
								<h4 class="desc mb-4">
									Boost sales and fill your calendar with a suite of intelligent
									marketing tools. Retain and reward clients using smart
									targeting and personalize offers for birthdays, lapsing clients and
									more.
								</h4>

								<a href="mailto:inc.nento@gmail.com?subject=Interested%20in%20Marketing" class="btn-primary">Register Interest</a>
							</div>
							<div class="p-0 col-12 col-md-12 col-lg-6">
								<div class="">
									<img alt="img" src="{{ asset('assets/images/booking-banner.png') }}"
										width="100%" />
								</div>
							</div>
						</div>
					</div>
					<div class="bg-white">
						<div class="row p-0">
							<div class="col-12 col-sm-12 col-md-6 p-10">
								<div class="img-div">
									<div class="d-flex justify-content-center align-items-center">
										<img alt="phone" src="{{asset('assets/images/book-now-back.png')}}"
											width="100%" />
										<img alt="phone" class="position-absolute"
											src="{{ asset('assets/images/book-now.png') }}" width="100%" />
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-6 p-14">
								<div class="content-div py-10">
									<h1 class="title mb-4">Book now integrations for clients</h1>
									<h4>
										Allow clients to manage their own appointments without needing
										to call. The ScheduleDown mobile app and social media booking
										integrations let your clients book, reschedule, cancel and
										rebook online.
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
									</h4>
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-6 p-10">
								<div class="img-div">
									<div class="d-flex justify-content-center align-items-center">
										<img alt="phone" class="position-absolute"
											src="{{asset('assets/images/graph.png')}}" width="100%" />
										<img alt="phone" src="{{asset('assets/images/shop.png')}}" width="100%" />
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-sm-12 col-md-6 p-10">
								<div class="img-div">
									<div class="d-flex justify-content-center align-items-center">
										<img alt="phone" src="{{asset('assets/images/map.png')}}" width="100%" />
										<img alt="phone" class="position-absolute"
											src="{{asset('assets/images/chat-location.png')}}" width="100%" />
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-6 p-14">
								<div class="content-div py-10">
									<h1 class="title mb-4">Capture authentic ratings and reviews</h1>
									<h4 class="mb-4">
										Happy clients attract new sales, let your clients spread the
										word about why they love you. ScheduleDown Plus is the best way
										to get clients sharing verified reviews and ratings about their
										experience with your business.
									</h4>

								</div>
							</div>
						</div>
						<div class="row col-sm-reverse">
							<div class="col-12 col-sm-12 col-md-6 p-14">
								<div class="content-div py-10">
									<h1 class="title mb-4">Unbeatable all-in-one system</h1>
									<h4 class="mb-4">
										Online booking is totally in sync with your business calendar
										and true availability. Simply set-and-forget without
										adjusting your daily operations. All bookings, clients and sales
										automatically appear in your account.
									</h4>
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-6 p-10">
								<div class="img-div">
									<div class="d-flex justify-content-center align-items-center">
										<img alt="phone" src="{{asset('assets/images/phone.png')}}" width="100%" />
										<img alt="phone" class="position-absolute"
											src="{{asset('assets/images/chat.png')}}" width="100%" />
									</div>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-12 col-sm-12 col-md-6 p-10">
								<div class="img-div">
									<div class="d-flex justify-content-center align-items-center">
										<img alt="phone" src="{{asset('assets/images/visa-phone.png')}}"
											width="100%" />
										<img alt="phone" class="position-absolute"
											src="{{asset('assets/images/visa.png')}}" width="100%" />
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
								<a href="mailto:inc.nento@gmail.com?subject=Interested%20in%20Marketing" class="btn-primary">Register Interest</a>
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

@endsection

{{-- Scripts Section --}}
@section('scripts')

@endsection