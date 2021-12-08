{{-- Extends layout --}}
@extends('frontend.layouts.booking')

{{-- CSS Section --}}  
@section('innercss')
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
	
	<form id="bookingfrm" action="{{ route('BookNowbyloc') }}" method="POST">
		
		@csrf
		<div class="my-custom-body-wrapper">
			<div class="my-custom-header">
				<div class="container">
					<div class="row">
						<div class="col-md-10 offset-md-1">
							<div class="p-2 d-flex justify-content-between align-items-center">
								<span class="">
									
									<h4 class="m-auto font-weight-bolder page-title">Select Loction</h4>
									{{--  <input type="hidden" id="loggedUserId" name="loggedUserId" value="{{ $loggedUserId }}">
									<input type="hidden" id="locationID" name="locationID" value="{{ $locationID }}">
									<input type="hidden" id="userId" name="userId" value="{{ $locationUserId }}">
									<input type="hidden" id="staffId" name="staffId" value="">
									<input type="hidden" id="selStaffId" value="{{ $staff_id }}">
									<input type="hidden" id="is_skip_staff" value="0">
									<input type="hidden" id="bookingTime" name="bookingTime" value="">  --}}
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
									
								</div>
								@if ($errors->any())
									@foreach ($errors->all() as $error)
											<li class="validation-error-item" style="color: #f2f2f7;">{{ $error }}</li>
									@endforeach
								@endif
								<div class="step-tab-content" id="first-tab">
									@foreach($LocationData as $loc)
										@php
											$rating = \DB::table('fuser_location_review')->where('location_id',$loc->id)->selectRaw('SUM(rating)/COUNT(location_id) AS avg_rating')->first()->avg_rating;
											$ratcount = \DB::table('fuser_location_review')->where('location_id',$loc->id)->selectRaw('COUNT(location_id) AS count')->first()->count;
											$rat = round($rating,1);
										@endphp
										@if($rating == 0)
											@php $lable = 'Not Good'; @endphp
										@elseif($rating == 1 || $rating < 2)
											@php $lable = 'Poor'; @endphp
										@elseif($rating == 2 || $rating < 3)
											@php $lable = 'Average'; @endphp
										@elseif($rating == 3 || $rating < 3)
											@php $lable = 'Better'; @endphp
										@elseif($rating == 4 || $rating < 4)
											@php $lable = 'Good'; @endphp
										@elseif($rating == 5 || $rating < 5)
											@php $lable = 'Great'; @endphp
										@endif
										<div class="mt-4 my-card-body" id="{{ $loc->id }}">
											<div class="card shadow-sm mt-3">
												<div class="card-body" id="my-card-body">
													<div class="container-fluid">
														<ul class="list-style-none services">
															<input type="checkbox" class="service-chk subServ" value="{{ $loc->id }}" name="selectLoc" />
															
															<img src="{{ $loc->location_image }}" data-qa="location-card-image" loading="lazy">
															<label>
																<b><span>{{ $loc->location_name }}</span></b>
															</label><br>
															<label>
																<span>{{ $loc->location_address }}</span>
															</label><br>
															<label>
																<span>Open: Sunday - Saturday</span>
															</label><br>
															@if($ratcount != 0)
															<label>
																<b><span>{{ $rat }}</span></b>
															</label>
															<label>
																<b><span>{{ $lable }}</span></b>
															</label>
															@endif
															<label>
																<span>{{ $ratcount }}</span> <span>ratings</span>
															</label>
															
														</ul>
													</div>
												</div>
											</div>
										</div>
									@endforeach
								</div>
							</div>
						</div>
						
					</div>
				</div>
			</div>
			<hr class="m-0">
			<div class="my-custom-header container bg-white">
				<div class="text-right mt-3 mb-2 d-flex justify-content-between">
					<div class="ml-auto">
						<button type="submit" class="btn btn-primary font-weight-bold d-none" id="submitBooking">Confirm</button>
					</div>
				</div>
			</div>
		</div>
	</form>
	
    
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script>
	var WEBSITE_URL = "{{ url('') }}";
</script>
<script src="{{ asset('js/intlTelInput.min.js') }}"></script>
<script type="text/javascript" src="{{asset('frontend/vendor/rescalendar/js/rescalendar.js') }}"></script>
<script src="{{ asset('frontend/js/toastr.min.js') }}"></script>
<script src="{{ asset('js/booknow.js') }}"></script>
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
   
@endsection	
