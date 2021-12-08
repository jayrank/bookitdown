{{-- Extends layout --}}
@extends('frontend.layouts.booking')

{{-- CSS Section --}}  
@section('innercss')
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

	<div class="my-custom-body-wrapper d-none" id="success_div">
		<div class="my-custom-body  d-flex align-items-center">
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
		<hr class="m-0">
		<div class="my-custom-footer container py-9"></div>
	</div>
	
	<form id="bookingfrm" action="{{ route('saveRescheduleAppointment') }}" method="POST">
		@csrf
		<div class="my-custom-body-wrapper">
			<div class="my-custom-header">
				<div class="container">
					<div class="row">
						<div class="col-md-10 offset-md-1">
							<div class="p-2 d-flex justify-content-between align-items-center">
								<span class="">
									<p class="m-0 m-0 align-items-center d-flex" style="color: rgb(135, 140, 147)">
										<i class="cursor-pointer back-arrow font-weight-bolder previous feather-arrow-left mr-2" id="prevBtn" onclick="nextPrev(-1)"></i> Step <span class="steps mx-1"> </span> of 3
									</p>
									<h4 class="m-auto font-weight-bolder page-title">Select Service</h4>
									<input type="hidden" id="AppointmentId" name="AppointmentId" value="{{ $appointmentId }}">
									<input type="hidden" id="loggedUserId" name="loggedUserId" value="{{ $loggedUserId }}">
									<input type="hidden" id="locationID" name="locationID" value="{{ $locationId }}">
									<input type="hidden" id="userId" name="userId" value="{{ $locationUserId }}">
									<input type="hidden" id="staffId" name="staffId" value="">
									<input type="hidden" id="selStaffId" value="0">
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
								
								<div class="step-tab-content" id="first-tab">
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
											<ul class="list-group user_availability" style="max-height: 400px; overflow-y: scroll;"></ul>
										</div>
									</div>
								</div>
								<div class="step-tab-content">
									<div class="card shadow-sm">
										<div class="card-body" id="my-card-body">
											<div class="form-group mr-2">
												<h4 class="font-weight-bolder mb-3">Updated date & time</h4>
												<h6>Please confirm the updated time for your appointment, which was originally booked for <b>{{ date("d M Y", strtotime($getAppintmentData->appointment_date)) }} at {{ date("h:i A", strtotime($getAppintmentServices[0]->start_time)) }}</b>.</h6>
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
								<div class="service-list" style="max-height: 220px;overflow-y: scroll;"> 
									<div class="card-body">
										<div id="select-service-list">
											@forelse($getAppintmentServices as $key => $val)
												<div class="mb-3 servRw itemRw{{ $val->uniId }}">
													<span class="title d-flex justify-content-between">
														<h6>{{ $val->service_name }}</h6>
														<h6 class="text-muted">${{ $val->special_price }}</h6>
													</span>
													<span class="title d-flex justify-content-between">
														<h6 class="text-muted"> {{ $val->duration_txt }} </h6>
														<input type="hidden" name="itemPrId[]" value="{{ $val->service_price_id }}">
														<input type="hidden" name="itemPr[]" value="{{ $val->special_price }}">
														<input type="hidden" name="itemType[]" value="service">
													</span>
												</div>
											@empty
											@endforelse
										</div>
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
				<div class="text-right mt-3 mb-2 d-flex justify-content-between">
					<div class="text-left datetime_conform d-none">
						<p class="m-0">Updated date & time</p>
						<h5 class="font-weight-bold text-dark"><span class="bkdt"></span>, <span class="bktm"></span></h5>
					</div>
					<div class="ml-auto">
						<button type="button" class="btn btn-primary font-weight-bold next-step" id="nextBtn" onclick="nextPrev(1)">Continue</button>
						<button type="button" class="btn btn-primary font-weight-bold d-none" id="submitBooking">Confirm</button>
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
                                                <span class="text-white font-weight-bolder cursor-pointer">all services</span> <i class="fa fa-chevron-right icon-sm"></i>
                                            </p>
                                            <p class="mb-1 text-white font-weight-bold font-size-lg">Valid until 2 Jul 2021
                                            </p>
                                            <p class="mb-1 text-white font-weight-bold font-size-lg">For multiple-use</p>
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
<script type="text/javascript" src="{{asset('frontend/vendor/rescalendar/js/rescalendar_reschedule.js') }}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('js/reschedulebooking.js') }}"></script>
    <script>
        $('#my_calendar_simple').rescalendar({
            id: 'my_calendar_simple',
            jumpSize: 3,
            calSize: 6,
            dataKeyField: 'name',
            dataKeyValues: ['']
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
