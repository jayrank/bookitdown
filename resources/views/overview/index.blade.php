{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')

	<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
		<!--begin::Tabs-->
		@include('layouts.overviewNav')
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
								<a href="{{ route('overviewWallet') }}" class="btn btn-primary">Start now</a>
							</div>
							<div class="p-0 col-12 col-md-12 col-lg-6 overview-md d-md-block d-lg-none">
							</div>
						</div>
						<div class="bg-white">
							<div class="row">
								<div class="col-12 col-sm-12 col-md-6 p-10">
									<div class="img-div">
										<div class="d-flex justify-content-center align-items-center">
											<img alt="phone" src="{{ asset('assets/images/book-now-back.png') }}"
												width="100%" />
											<img alt="phone" class="position-absolute"
												src="{{ asset('assets/images/book-now.png') }}" width="100%" />
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
											Create a business profile and be discovered 24/7 on ScheduleDown
											marketplace,
											Instagram, Facebook, Google and your own
											website. Free up your time and focus on what you do best.
											<a class="text-blue" href="{{ route('onlineBooking')}}">Learn More</a>
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
											cost. There's no monthly rental and all processing charges are
											covered by the standard ScheduleDown Plus transaction fees.
											<a class="text-blue" href="{{ route('onlineBooking') }}">Learn
												More</a>
										</h4>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-6 p-10">
									<div class="img-div">
										<div class="d-flex justify-content-center align-items-center">
											<img alt="phone" class="position-absolute"
												src="{{ asset('assets/images/swipe.png') }}" width="100%" />
											<img alt="phone" src="{{ asset('assets/images/watch.png') }}" width="100%" />
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-sm-12 col-md-6 p-10">
									<div class="img-div">
										<div class="d-flex justify-content-center align-items-center">
											<img alt="phone" src="{{ asset('assets/images/phone.png') }}" width="100%" />
											<img alt="phone" class="position-absolute"
												src="{{ asset('assets/images/chat.png') }}" width="100%" />
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
											<a class="text-blue" href="{{ route('onlineBooking')}}">Learn More</a>
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
											<a class="text-blue" href="{{ route('onlineBooking') }}">Learn
												More</a>
										</h4>
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-6 p-10">
									<div class="img-div">
										<div class="d-flex justify-content-center align-items-center">
											<img alt="phone" src="{{ asset('assets/images/discount.png') }}"
												width="100%" />
											<img alt="phone" class="position-absolute"
												src="{{ asset('assets/images/birthday.png') }}" width="100%" />
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-12 col-sm-12 col-md-6 p-10">
									<div class="img-div">
										<div class="d-flex justify-content-center align-items-center">
											<img alt="phone" src="{{ asset('assets/images/visa-phone.png') }}"
												width="100%" />
											<img alt="phone" class="position-absolute"
												src="{{ asset('assets/images/visa.png') }}" width="100%" />
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
											<a class="text-blue" href="{{ route('onlineBooking')}}">Learn More</a>
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
											<a class="text-blue" href="{{ route('onlineBooking')}}">Learn More</a>
										</h4>

									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-6 p-10">
									<div class="img-div">
										<div class="d-flex justify-content-center align-items-center">
											<img alt="phone" src="{{ asset('assets/images/map.png') }}" width="100%" />
											<img alt="phone" class="position-absolute"
												src="{{ asset('assets/images/chat-location.png') }}" width="100%" />
										</div>
									</div>
								</div>
							</div>

							<div class="row col-sm-reverse">
								<div class="col-12 col-sm-12 col-md-6 p-10">
									<div class="img-div">
										<div class="d-flex justify-content-center align-items-center">
											<img alt="phone" src="{{ asset('assets/images/salon.png') }}" width="100%" />
											<img alt="phone" class="position-absolute"
												src="{{ asset('assets/images/reminder.png') }}" width="100%" />
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
											<a class="text-blue" href="{{ route('onlineBooking')}}">Learn More</a>
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
									<a href="{{ route('overviewWallet') }}" class="btn btn-primary">Start now</a>
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