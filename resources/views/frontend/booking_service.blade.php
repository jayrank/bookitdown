{{-- Extends layout --}}
@extends('frontend.layouts.booking')

{{-- CSS Section --}}  
@section('innercss')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
	<link href="{{ asset('frontend/css/toastr.min.css') }}" rel="stylesheet" type="text/css" />
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

        .cata-sub-nav {
            margin: 0 25px;
            /* Make this scrollable when needed */
            overflow-x: auto;
            /* We don't want vertical scrolling */
            overflow-y: hidden;
            /* Make an auto-hiding scroller for the 3 people using a IE */
            -ms-overflow-style: -ms-autohiding-scrollbar;
            /* For WebKit implementations, provide inertia scrolling */
            -webkit-overflow-scrolling: touch;
            /* We don't want internal inline elements to wrap */
            white-space: nowrap;
            /* Remove the default scrollbar for WebKit implementations */
        }

        .cata-sub-nav::-webkit-scrollbar {
            display: none;
        }

        .cata-sub-nav ul {
            margin: 0;
            display: flex;
            -webkit-padding-start: 0px;
        }

        .cata-sub-nav li {
            display: inline-table;
            margin: 0 10px;
            font-size: 16px;
        }

        .arrow {
            background: red;
        }

        .nav-prev,
        .nav-next {
            color: rgb(16, 25, 40);
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
        }

        .nav-next {
            right: 15px;
            left: auto;
        }

        .nav-next svg,
        .nav-prev svg {
            width: 16px;
            height: 16px;
            transition: transform 300ms ease 0s;
            transform: translateX(0px);
        }

        .nav-prev:hover svg {
            transform: translateX(-5px);
        }

        .nav-next:hover svg {
            transform: translateX(5px);
        }

        .nav-prev:hover svg path,
        .nav-next:hover svg path {
            fill: rgb(135, 140, 147)
        }
    </style>
@endsection
@section('content')

	<div class="d-none m-none" id="success_div">
		<div class="d-flex align-items-center" style="height:100vh">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center py-20">
						<div class="check-svg">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 80 80"><path d="M56.2 5c-5.7-3.3-12.1-5-18.7-5C16.8 0 0 16.8 0 37.5S16.8 75 37.5 75 75 58.2 75 37.5c0-6.6-1.7-13.1-5.1-18.8"></path><path d="M18.5 38.5L31 51c.4.4 1 .4 1.4 0 0 0 0-.1.1-.1L63.8 12"></path></svg>
						</div>
						<h2 class="font-weight-bolder">Appointment confirmed</h2>
					</div>
				</div>
			</div>
		</div>
	</div>
	
	<form id="bookingfrm" action="{{ route('saveBookingAppointment') }}" method="POST">
		@csrf
		<div class="my-custom-body-wrapper">
			<div class="my-custom-header">
				<div class="container">
					<div class="row">
						<div class="col-md-10 offset-md-1">
							<div class="p-2 d-flex justify-content-between align-items-center">
								<span class="">
									<p class="m-0 m-0 align-items-center d-flex" style="color: #FFF">
										<i class="cursor-pointer back-arrow font-weight-bolder previous feather-arrow-left mr-2" id="prevBtn" onclick="nextPrev(-1)"></i> Step <span class="steps mx-1"> </span> of 4
									</p>
									<h4 class="m-auto font-weight-bolder page-title">Select Service</h4>
									<input type="hidden" id="loggedUserId" name="loggedUserId" value="{{ $loggedUserId }}">
									<input type="hidden" id="locationID" name="locationID" value="{{ $locationID }}">
									<input type="hidden" id="userId" name="userId" value="{{ $locationUserId }}">
									<input type="hidden" id="staffId" name="staffId" value="">
									<input type="hidden" id="selStaffId" value="{{ $staff_id }}">
									<input type="hidden" id="is_skip_staff" value="0">
									<input type="hidden" id="bookingTime" name="bookingTime" value="">
								</span>
								<p class="cursor-pointer mb-0" onclick="history.back();"><i class="text-white feather-x font-weight-bolder" style="font-size: 20px;"></i>
								</p>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="my-custom-body bg-content pb-4">
				<div class="my-custom-body-bg"></div>
				<div class="container">
					<div class="row">
						<div class="col-12 col-md-6 offset-md-1">
							<div class="main-content">
								<div class="service-menu">
									<div id="service-menu-center">
										<nav class="navbar navbar-expand-sm bg-white">
											<div class="cata-sub-nav">
												<div class="nav-prev" style="display: none;">
													<svg class="Icon-c98r68-0 fKUQXh" viewBox="0 0 16 16">
														<path fill="#101928" fill-rule="evenodd"
															d="M5.937 3.2a.682.682 0 00-.965 0L.2 7.972a.694.694 0 00-.192.378v.004l-.008.1a.689.689 0 00.2.483l-.05-.055.032.037.018.018 4.772 4.772a.682.682 0 00.965-.964L2.329 9.136h11.99a.682.682 0 00.674-.58L15 8.454a.682.682 0 00-.682-.682H2.33l3.608-3.608a.682.682 0 00.072-.88z">
														</path>
													</svg>
												</div>
												<ul class="">
													@foreach($serviceCategory as $key => $cat)
														<li class="nav-item @if($key == 0) active @endif"><a class="font-weight-bolder text-dark py-1 px-3 rounded-pill d-block" href="#cat{{ $cat->id }}">{{ $cat->category_title }}</a></li>
													@endforeach	
												</ul>
												<div class="nav-next" style="">
													<svg class="Icon-c98r68-0 fKUQXh" viewBox="0 0 16 16">
														<path fill="#101928" fill-rule="evenodd"
															d="M10.063 3.2a.682.682 0 01.965 0L15.8 7.972a.694.694 0 01.192.378v.004l.008.1a.689.689 0 01-.2.483l.05-.055a.686.686 0 01-.032.037l-.018.018-4.772 4.772a.682.682 0 01-.965-.964l3.608-3.609H1.681a.682.682 0 01-.674-.58L1 8.454c0-.377.305-.682.682-.682l11.989-.001-3.608-3.608a.682.682 0 01-.072-.88z">
														</path>
													</svg>
												</div>
											</div>
										</nav>
									</div>  
								</div>
								<div class="step-tab-content" id="first-tab">
									@if($serviceCategory->isNotEmpty())
										@foreach($serviceCategory as $key => $cat)
											<div class="mt-4 my-card-body"  id="cat{{ $cat->id }}">
												<h4 class="font-weight-bolder">{{ $cat->category_title }}</h4>
												<div class="card shadow-sm mt-3">
													<div class="card-body" id="my-card-body">
														<div class="container-fluid">
															<ul class="list-style-none services">
																@foreach($cat->services as $key => $services)
																	@if(count($services->servicePrice) > 1)
																		<li>
																			<div data-toggle="collapse" data-target="#servicePr{{ $services->id }}">
																				<div class="custom-check">
																					<div class="round">
																						<label for="featured4">
																							<span class="round-check round-check-dot"></span>
																							<span class="title d-flex justify-content-between">
																								<h5>{{ $services->service_name }}</h5>
																								<h6 class="text-muted">From</h6>
																							</span>
																							<span
																								class="title d-flex justify-content-between">
																								<h6 class="text-muted">{{ $services->serviceDuration }}</h6>
																								@if(min(array_column($services->servicePrice, 'service_price_special_amount')) > 0)
																									<h5>${{ min(array_column($services->servicePrice, 'service_price_special_amount')) }}</h5>
																								@else
																									<h5>Free</h5>
																								@endif
																							</span>
																							<span
																								class="title d-flex justify-content-between">
																								<p>{{ $services->service_description }}</p>
																							</span>
																						</label>
																					</div>
																				</div>
																			</div>
																			<div id="servicePr{{ $services->id }}" class="collapse">
																				<ul class="list-style-none services">
																					@foreach($services->servicePrice as $sprKey => $servPrices)
																						<li data-unqid="{{ $servPrices['service_price_uid'] }}">
																							<div class="custom-check">
																								<div class="round">
																									<input type="checkbox" class="service-chk subServ" id="checkbox{{ $servPrices['service_price_id'] }}" data-unqid="{{ $servPrices['service_price_uid'] }}" data-sid="{{ $servPrices['service_price_id'] }}" name="service{{ $services->id }}" />
																									<label class="servicedtl{{ $servPrices['service_price_uid'] }}" for="checkbox{{ $servPrices['service_price_id'] }}">
																										<span class="radio"></span>
																										<span class="title d-flex justify-content-between">
																											<input type="hidden" class="service-name" value="{{ $services->service_name }}">
																											<input type="hidden" class="service-subname" value="{{ $servPrices['service_price_name'] }}">
																											<h5>{{ $servPrices['service_price_name'] }}</h5>
																											@if($servPrices['service_price_special_amount'] > 0)
																												<h5 class="service-pr">$<span>{{ $servPrices['service_price_special_amount'] }}</span></h5>
																											@else 
																												<h5 class="service-pr"><span>Free</span></h5>
																											@endif
																										</span>
																										<span class="title d-flex justify-content-between">
																											<h6 class="text-muted service-duration">{{ $servPrices['service_price_duration_txt'] }}</h6>
																										</span>
																									</label>
																								</div>
																							</div>
																						</li>
																					@endforeach	
																				</ul>
																			</div>
																		</li>
																	@else
																		<li data-unqid="{{ $services->servicePrice[0]['service_price_uid'] }}">
																			<div class="custom-check">
																				<div class="round">
																					<input type="checkbox" class="service-chk" id="checkbox{{ $services->servicePrice[0]['service_price_id'] }}" data-unqid="{{ $services->servicePrice[0]['service_price_uid'] }}" data-sid="{{ $services->servicePrice[0]['service_price_id'] }}" name="service" />
																					<label class="servicedtl{{ $services->servicePrice[0]['service_price_uid'] }}" for="checkbox{{ $services->servicePrice[0]['service_price_id'] }}">
																						<span class="round-check"></span>
																						<span class="title d-flex justify-content-between">
																							<input type="hidden" class="service-name" value="{{ $services->service_name }}">
																							<input type="hidden" class="service-subname" value="">
																							<h5>{{ $services->service_name }}</h5>
																							@if($services->servicePrice[0]['service_price_special_amount'] > 0)
																								<h5 class="service-pr">@if($services->servicePrice[0]['is_staff_price'] == 1) From @endif $<span>{{ $services->servicePrice[0]['service_price_special_amount'] }}</span></h5>
																							@else 
																								<h5 class="service-pr"><span>Free</span></h5>
																							@endif
																						</span>
																						<h6 class="text-muted service-duration">{{ $services->servicePrice[0]['service_price_duration_txt'] }}</h6>

																						<p>{{ $services->service_description }}</p>
																					</label>
																				</div>
																			</div>
																		</li>
																	@endif		
																@endforeach
															</ul>
														</div>
													</div>
												</div>
											</div>
										@endforeach	
									@else
										<div class="mt-4 my-card-body">
											<h6 class="font-weight-bolder">No services found</h6>
										</div>
									@endif
								</div>
								
								<div class="step-tab-content">
									<div class="card shadow-sm border-0">
										<div class="card-body p-0" id="my-card-body">
											<div class="list-group">
												<a href="javascript:;" class="p-4 d-flex justify-content-between align-items-center list-group-item list-group-item-action noprefernce">
													<div class="staff-flexbox">
														<img src="{{ asset('frontend/img/user/4.jpg') }}" style="width: 60px;height:60px;border-radius: 100px;" />
													</div>
													<div class="staff-flexbox"> 
														<h6 class="font-weight-bolder m-0">No preference</h6>
														<h6 class="text-muted m-0">Maximum availability</h6>
													</div> 
													<div class="staff-flexbox">
														<i class="feather-chevron-right" style="font-size: 22px"></i>
													</div>
												</a>
												<div class="staffListSec">
													
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="step-tab-content">
									<div class="card shadow-sm">
										<div class="card-body" id="my-card-body">
											<!-- <h3>Additional info and quantity</h3> -->
											<div class="form-group dateSliderPicker" style="width: 100%;text-align: center;">
												<div class="rescalendar" id="my_calendar_simple"></div>
											</div>
											<ul class="list-group user_availability" style="max-height: 400px; overflow-y: scroll;">
											</ul>
										</div>
									</div>
								</div>
								
								<div class="step-tab-content">
									<div class="card shadow-sm">
										<div class="card-body" id="my-card-body">
											@php
												$styleShow2 = "";
												if($loggedUserId != ''){
													$styleShow2 = "display:none;";
												}
											@endphp
										
											<div class="loginSettings" style="{{ $styleShow2 }}">
												<div class="row loginOptionStep commonClass">
													<div class="col-md-12 text-center">
														@csrf
													
														<div class="form-group">
															<button type="button" class="btn btn-primary showSignupForm" style="width:100%;">Sign up with email</button>
														</div>
														<div class="form-group">
															<p>Already have an account?</p>
															<p><a href="javascript:;" class="showLoginForm">Log in now</a></p>
														</div>
													</div>
												</div>
												
												<div class="row signupWithEmailStep commonClass" style="display:none;">
													<div class="col-md-12">
														<div class="form-group">
															<label class="front_name">First Name</label>
															<input type="text" class="form-control" name="front_name" id="front_name" autocomplete="off" placeholder="First Name">
														</div>		
														<div class="form-group">
															<label class="front_lastname">Last Name</label>
															<input type="text" class="form-control" name="front_lastname" id="front_lastname" autocomplete="off" placeholder="Last Name">
														</div>		
														<div class="form-group mr-2 w-100 frontMobileNumberClass">
															<label class="front_mobilenumber">Mobile Number</label>
															<br>
															<input type="tel" class="form-control" id="front_mobilenumber" name="front_mobilenumber" autocomplete="off">
															<input type="hidden" class="form-control" id="front_countrycode" name="front_countrycode" value="1">
														</div>		
														<div class="form-group">
															<label class="front_email">Email Address</label>
															<input type="text" class="form-control" name="front_email" id="front_email" autocomplete="off" placeholder="Email Address">
														</div>		
														<div class="form-group">
															<label class="front_password">Password</label>
															<input type="password" class="form-control" name="front_password" id="front_password" autocomplete="off">
														</div>
														<div class="form-group">
															<input type="checkbox" name="front_termsprivacy" id="front_termsprivacy">
															<label for="front_termsprivacy">I agree to the <a href="javascript:;">privacy policy</a>, website <a href="javascript:;">terms</a> and <a href="javascript:;">booking terms</a></label>
														</div>	
														<div class="form-action text-center">
															<button type="button" class="btn btn-primary" id="registerFrontUser">Sign up</button>
														</div>	
														<div class="form-group text-center">
															<p>Already have a booker account?</p>
															<p><a href="javascript:;" class="showLoginForm">Sign in now</a></p>
														</div>
													</div>
												</div>
												
												<div class="row loginWithEmailStep commonClass" style="display:none;">
													<div class="col-md-12">
														<div class="form-group">
															<label class="front_login_email">Email Address</label>
															<input type="text" class="form-control" name="front_login_email" id="front_login_email" autocomplete="off" placeholder="Email Address">
														</div>		
														<div class="form-group">
															<label class="front_login_password">Password</label>
															<input type="password" class="form-control" name="front_login_password" id="front_login_password" autocomplete="off" placeholder="Password">
														</div>
														<div class="form-action text-center">
															<button type="button" class="btn btn-primary" id="loginFrontUser">Log in</button>
														</div>	
														<div class="form-group text-center">
															<p>Don't have a booker account?</p>
															<p><a href="javascript:;" class="showSignupForm">Sign up now</a></p>
														</div>
													</div>
												</div>
												
												<div class="row forgotEmailStep commonClass" style="display:none;">
													<div class="col-md-12">
														<div class="form-group">
															<p>Enter your registered email address and we'll send you a secure link to setup a new password.</p>
														</div>
														<div class="form-group">
															<label class="front_forgot_email">Email Address</label>
															<input type="text" class="form-control" name="front_forgot_email" id="front_forgot_email" autocomplete="off" placeholder="Enter your registred email address">
														</div>
														<div class="form-group text-center">
															<p><a href="javascript:;" class="showLoginForm">Back to login</a></p>
														</div>
													</div>
												</div>
											</div>
											@php
												$styleShow = "";
												if($loggedUserId == ''){
													$styleShow = "display:none;";
												}
											@endphp
											<div class="noteSettings" style="{{ $styleShow }}">
												@if(!empty($onlineSettingData))
													@if($onlineSettingData->important_info != '')
														<div class="form-group mr-2">
															<label class="font-weight-bolder">Important info from {{ $LocationData->location_name }}</label>
															<p>{{ $onlineSettingData->important_info }}</p>
														</div>
													@endif	
												@endif
												<div class="form-group mr-2">
													<label class="font-weight-bolder">Add booking notes (Include comments or request about your booking)</label>
													<textarea type="email" rows="5" name="description" class="form-control"></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
								
							</div>
						</div>
						<div class="col-12 col-md-5">
							<div class="shadow-sm card w-80 invoice">
								<div class="card-body pb-0">
									<div class="card-img text-center">
										<div class="profileImage mb-2">
											@if($LocationData->location_name != "")
												<img src="{{ $LocationData->location_image }}" class="" alt="profile" />
											@else
												<img src="{{ asset('frontend/img/featured1.jpg') }}" class="" alt="profile" />
											@endif
										</div>
									</div> 
									<h5 class="font-weight-bolder text-center">{{ $LocationData->location_name }}</h5>
									<p class="text-muted text-center">
										{{ $LocationData->location_address }}
									</p>
								</div>
								<hr class="m-0">
								<div class="service-list" > 
									<div class="card-body" style="max-height: 220px;overflow-y: scroll;">
										<div id="select-service-list">
											<h6 class="text-muted">No services selected yet</h6>
										</div>
									</div> 
									<div class="card-footer bg-white">
										<div class="tax_sec"></div>
										<div class="taxes-list">
											<input type="hidden" name="taxFormula" id="taxFormula" value="{{ $taxFormula }}">
											<input type="hidden" name="inoviceTotal" id="inoviceTotal" value="">
											@forelse($serviceTaxes as $tax)
												<input type="hidden" class="serviceTax" value="{{ $tax['tax_rates'] }}" data-id="{{ $tax['service_default_tax'] }}" data-name="{{ $tax['tax_name'] }}">
											@empty
											@endforelse
										</div>		
										<div class="d-flex justify-content-between">
											<h4 class="font-weight-bolder taxlbl"></h4>
											<h5 class="font-weight-bolder taxAmt"></h5>
										</div>
										<div class="d-flex justify-content-between">
											<h4 class="font-weight-bolder">Total</h4>
											<h5 class="font-weight-bolder cartTotal">Free</h5>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr class="m-0">
			<div class="my-custom-header container bg-white">
				<div class="col-md-10 offset-md-1 my-3">
					<div class="text-right mt-3 mb-2 d-flex justify-content-between">
						<div class="text-left">
							<p class="m-0 totalService">0</p>
							<h5 class="font-weight-bold text-dark cartTotal">$0</h5>
						</div>
						<div class="ml-auto my-auto">
							@if($serviceCategory->isNotEmpty())
								<button type="button" class="btn btn-primary font-weight-bold next-step" id="nextBtn" onclick="nextPrev(1)">Continue</button>
							@endif
							<button type="button" class="btn btn-primary font-weight-bold d-none" id="submitBooking">Confirm</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
	
    <div class="modal fade" id="previewModal" tabindex="-1" role="dialog" aria-labelledby="previewModal" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="previewModal">Email voucher</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="card">
                        <div class="card-header">
                            <span class="text-muted">Voucher email subject:</span> £100 Treat at The Barbery from Dhaval
                            Makwana
                        </div>
                        <div class="card-body">
                            <p>£100 to redeem on any products or services at our Webber Street salon. Book at:
                                www.thebarberylondon.com</p>
                            <div class="card voucher-card black mb-2">
                                <div class="card-body text-white p-6">
                                    <div
                                        class="p-10 text-center voucher-wrapper text-white justify-content-center bgi-size-cover bgi-no-repeat">
                                        <div class="p-4 text-center">
                                            <h3 class="font-weight-bold">A £100 Treat at The Barbery</h3>
                                            <h5 class="text-grey">£100 Voucher price</h5>
                                        </div>
                                        <div class="border-bottom w-100 opacity-20"></div>
                                        <div class="my-5 vouchers-value add-vouchers-value">
                                            <p class="font-weight-bolder text-white my-1 font-size-lg">Voucher
                                                Value</p>
                                            <h3 class="my-2 font-weight-bolder">CA $<span
                                                    id="vaoucher-price">£100</span>
                                            </h3>
                                        </div>
                                        <div class="border-bottom w-100 opacity-20"></div>
                                        <div class="my-5 vouchers-bottom my-2">
                                            <p class="mb-1 text-white font-weight-bold font-size-lg">Redeem on
                                                <span class="text-white font-weight-bolder cursor-pointer">all
                                                    services</span> <i class="fa fa-chevron-right icon-sm"></i>
                                            </p>
                                            <p class="mb-1 text-white font-weight-bold font-size-lg">Valid until
                                                2 Jul 2021
                                            </p>
                                            <p class="mb-1 text-white font-weight-bold font-size-lg">For
                                                multiple-use</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script>
	var WEBSITE_URL = "{{ url('') }}";
</script>
<script src="{{ asset('js/intlTelInput.min.js') }}"></script>
<script type="text/javascript" src="{{asset('frontend/vendor/rescalendar/js/rescalendar.js') }}"></script>
<script src="{{ asset('frontend/js/toastr.min.js') }}"></script>
<script src="{{ asset('js/booking.js') }}"></script>
    <script>
        $('#my_calendar_simple').rescalendar({
            id: 'my_calendar_simple',
            jumpSize: 3,
            calSize: 6,
            dataKeyField: 'name',
            dataKeyValues: ['']
        });
		
		var phone_number = window.intlTelInput(document.querySelector("#front_mobilenumber"), {
		  separateDialCode: true,
		  preferredCountries:["ca"],
		  hiddenInput: "full",
		  utilsScript: "{{ asset('js/utils.js') }}"
		});
		
		$(document).on("click keypress keydown keyup", '.frontMobileNumberClass .iti__country' , function(e) {
			e.preventDefault();
			
			if($(this).hasClass("iti__active")){
				var country_code = $(this).attr("data-dial-code");
				$("#front_countrycode").val(country_code);
			} else {
				var country_code = $(this).attr("data-dial-code");
				$("#front_countrycode").val(country_code);
			}
		});	
    </script>
    <script>
        $(function () {
            $('.cata-sub-nav ul li').on('click', function () {
                $(this).parent().find('li.active').removeClass('active');
                $(this).addClass('active');
            });
        });

        $(document).ready(function () {
            var topHeader = $('.my-custom-header').outerHeight();
            var serviceHeader = $('.service-menu').outerHeight();
            // var totalHeader = parseFloat(topHeader) + parseFloat(serviceHeader);
            $('ul li.nav-item').on('click', function () {
                // $('.my-card-body').css('padding-top', serviceHeader + 'px'); 
                // $('.main-content').css('padding-top', serviceHeader + 'px'); 
            });
        });
		

    </script>
    <script>
    </script>
    <script>
        (function ($) {
            $(".cata-sub-nav").on('scroll', function () {
                $val = $(this).scrollLeft();

                if ($(this).scrollLeft() + $(this).innerWidth() >= $(this)[0].scrollWidth) {
                    $(".nav-next").hide();
                } else {
                    $(".nav-next").show();
                }

                if ($val == 0) {
                    $(".nav-prev").hide();
                } else {
                    $(".nav-prev").show();
                }
            });

            $(".nav-next").on("click", function () {
                $(".cata-sub-nav").animate({ scrollLeft: '+=460' }, 200);

            });
            $(".nav-prev").on("click", function () {
                $(".cata-sub-nav").animate({ scrollLeft: '-=460' }, 200);
            });
        })(jQuery);

        $(document).ready(function(){
            $(".move_to_last_month").html('<i class="feather-chevrons-left"></i>');
            $(".move_to_yesterday").html('<i class="feather-chevron-left"></i>');
            $(".move_to_tomorrow").html('<i class="feather-chevron-right"></i>');
            $(".move_to_next_month").html('<i class="feather-chevrons-right"></i>');
        });
    </script>
@endsection	
