{{-- Extends layout --}}
@extends('frontend.layouts.sellvoucher')

{{-- CSS Section --}}  
@section('innercss')
<link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endsection
@section('content')

@php
	$vouchername = $voucherprice = $vouchervalue = $voucherserv = "";
	$voucherserv = "4 services";
	$vouchervalid = "6 months";
	$voucherColor = "purple";
	
	if(!empty($voucherData)) {
		$vouchername = ($voucherData->name) ? $voucherData->name : "";
		$voucherprice = ($voucherData->retailprice) ? $voucherData->retailprice : "";
		$vouchervalue = ($voucherData->value) ? $voucherData->value : "";
		
		if($voucherData->voucher_type == 0) {
			$voucherserv = "all services";
		} else {
			$voucherserv = count(explode(",",$voucherData->services_ids))." services";
		}		
		$vouchervalid = ($voucherData->validfor) ? $voucherData->validfor : "";
		$voucherColor = ($voucherData->color) ? $voucherData->color : "purple";

		if($voucherData->enable_sale_limit) {
			$defaultQty = ( ($voucherData->numberofsales - $voucherData->totalSold) > 0 ) ? 1 : 0;
		} else {
			$defaultQty = 1;
		}
		$maxQty = ($voucherData->numberofsales - $voucherData->totalSold);
	}
@endphp	
	<div class="my-custom-body-wrapper d-none" id="success_div">
		<div class="my-custom-body  d-flex align-items-center">
			<div class="container">
				<div class="row">
					<div class="col-md-12 text-center py-20">
						<div class="check-svg">
							<svg xmlns="http://www.w3.org/2000/svg" viewBox="-2 -2 80 80"><path d="M56.2 5c-5.7-3.3-12.1-5-18.7-5C16.8 0 0 16.8 0 37.5S16.8 75 37.5 75 75 58.2 75 37.5c0-6.6-1.7-13.1-5.1-18.8"></path><path d="M18.5 38.5L31 51c.4.4 1 .4 1.4 0 0 0 0-.1.1-.1L63.8 12"></path></svg>
						</div>
						<h2 class="font-weight-bolder">Voucher purchased successfully.</h2>
						<h4 class="font-weight-bolder gnrtpdfmsg d-none">Please wait for sometime PDF will generate.</h4>
					</div>
				</div>
			</div>
		</div>
		<hr class="m-0">
		<div class="my-custom-footer container py-9"></div>
	</div>
	
	<form id="voucherfrm" action="{{ route('saveSellVoucherData') }}" method="POST">
		@csrf
		<div class="my-custom-body-wrapper">
			<div class="my-custom-header">
				<div class="container">
					<div class="row">
						<div class="col-md-10 offset-md-1">
							<div class="p-2 d-flex justify-content-between align-items-center">
								<span class="">
									<p class="m-0 m-0 align-items-center d-flex" style="color: #FFF">
										<i class="cursor-pointer back-arrow font-weight-bolder previous feather-arrow-left mr-2" id="prevBtn" onclick="nextPrev(-1)"></i> Step <span class="steps mx-1"> </span> of 6
									</p>
									<h3 class="m-auto font-weight-bolder page-title">Select Service</h3>
								</span>
								<a class="cursor-pointer mb-0" href="{{ route('search_detail', $encryptLocationId) }}"><i class="text-white feather-x font-weight-bolder" style="font-size: 20px;"></i>
								</a>
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
								<div class="card shadow-sm">
									<div class="card-body">
										<div class="step-tab-content px-3 py-2" >
											<input type="hidden" id="loggedUserId" name="loggedUserId" value="{{ $loggedUserId }}">
											<input type="hidden" id="locationID" name="locationID" value="{{ $locationId }}">
											<input type="hidden" id="userId" name="userId" value="{{ $locationUserId }}">
											<input type="hidden" id="recipient_as" name="recipient_as" value="0">
											<input type="hidden" id="printVoucher" value="{{ route('printSellVoucherData') }}">
											<input type="hidden" id="selVoucherId" value="{{ $voucherId }}">
											<input type="hidden" id="getServiceUrl" value="{{ route('getVoucherService') }}">
											<!--a href="javascript:;" class="navi-link printInvoice">Print</a-->
											@foreach($voucherLists as $key => $voucher)
												<div class="card voucher-card sel-voucher {{ ($voucher->color) ? $voucher->color : 'purple' }} mb-4" data-uid="{{ $voucher->uniId }}" data-vid="{{ $voucher->id }}" data-color="{{ $voucher->color }}">
													<div class="card-body text-white p-6">
														<div class="my-3 text-center">
															<h6 class="font-weight-bold">Voucher value</h6>
															<h2 class="font-weight-bolder">CA $<span class="vvalue{{ $voucher->uniId }}">{{ $voucher->value }}</span></h2>
														</div>
														<div class="mt-10 font-weight-bold d-flex justify-content-between">
															<div class="mw-300">
																<h6 class="font-weight-bolder vname{{ $voucher->uniId }}">{{ $voucher->name }}</h6>
																@if($voucher->voucher_type == 0)
																	<h6>Redeem on <span class="voucher-srv{{ $voucher->uniId }}">all services</span></h6>
																@else
																	<h6>Redeem on <span class="voucher-srv{{ $voucher->uniId }}">{{ count(explode(",", $voucher->services_ids)) }} services</span></h6>
																@endif
															</div>
															<div class="text-right">
																<h6 class="font-weight-bolder">CA $<span class="vretail{{ $voucher->uniId }}">{{ $voucher->retailprice }}</span></h6>

																@php
																	$voucherValidFor = '';
																	if($voucher->validfor == 'never') {
																		$voucherValidFor = 'No Expiry Date';
																	} else {
																		$voucherValidFor = 'Valid For '.$voucher->validfor;
																	}
																@endphp
																<input type="hidden" class="validfor{{ $voucher->uniId }}" value="{{ $voucherValidFor }}">
																<input type="hidden" class="totalSold{{ $voucher->uniId }}" value="{{ $voucher->totalSold }}">
																<input type="hidden" class="numberofsales{{ $voucher->uniId }}" value="{{ $voucher->numberofsales }}">
																<input type="hidden" class="enable_sale_limit{{ $voucher->uniId }}" value="{{ $voucher->enable_sale_limit }}">
																@if($voucher->value > $voucher->retailprice)
																	<h6 class="bagde badge-light p-1 rounded text-uppercase">
																		Save {{ round((($voucher->value - $voucher->retailprice)*100) / $voucher->value) }}%
																	</h6>
																@endif
																@if($voucher->enable_sale_limit == 1)
																	<h6 class="font-weight-bolder">Sold {{ $voucher->totalSold }}</h6>
																@endif
															</div>
														</div>
													</div>
												</div> 
											@endforeach
										</div>
										
										<div class="step-tab-content">
											<div class="px-3 py-2">
												<h4 class="fw-800">Additional info and quantity</h4>
												<h6 class="text-center">Quantity</h6>
												<div class="qty-input">
													@php
														/*if($voucher->enable_sale_limit) {
															$defaultQty = ( ($voucher->numberofsales - $voucher->totalSold) > 0 ) ? 1 : 0;
														} else {
															$defaultQty = 1;
														}
														$maxQty = ($voucher->numberofsales - $voucher->totalSold);*/
													@endphp
													<button class="qty-count qty-count--minus" data-action="minus" disabled="disabled" type="button">-</button>
													<input class="product-qty" type="number" name="product_qty" min="1" @if($voucher->enable_sale_limit) max="{{ $maxQty }}" @endif value="{{ $defaultQty }}">
													<button class="qty-count qty-count--add" data-action="add" type="button" @if($voucher->enable_sale_limit && ($defaultQty >= $maxQty)) disabled="disabled" @endif >+</button>
												</div>
												<div class="mx-sm-1 mx-lg-5">
													<div class="m-auto"> 
														<div class="text-center voucher-card {{ $voucherColor }} justify-content-center voucher-prw" data-color="{{ $voucherColor }}">
															<div class="p-4 text-center">  
																<h4 class="font-weight-bolder mt-3 vouchernm">{{ ($vouchername) ? $vouchername : "$100 Gift Card" }}</h4>
																<h6 class=""><span class="font-weight-bolder">CA $<span class="voucher-pr">{{ ($voucherprice) ? $voucherprice : 100 }}</span></span> Voucher price</h6> 
															</div>
															<div class="add-vouchers-value">
																<div class="vouchersInner">
																	<h6 class="">Voucher value</h6>
																	<h3 class="font-weight-bolder mb-0">CA $<span class="voucher-vl">{{ ($vouchervalue) ? $vouchervalue : 100 }}</span></h3>
																</div>
															</div>
															<div class="p-4 vouchers-bottom">
																<h6 class="mb-1">Redeem on <a class="d-inline-flex align-items-center font-weight-bold text-white voucher-servs" data-toggle="modal" href="#servicesModal">{{ $voucherserv }}<i class="feather-chevron-right icon-sm"></i></a></h6> 
																<h6 class="mb-1">
																	<span class="voucher-valid">
																		@if($vouchervalid == 'never') 
																			No Expiry Date
																		@else
																			Valid For {{ $vouchervalid }}
																		@endif
																	</span>
																</h6>
																<h6>For multiple-use</h6>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										
										<div class="step-tab-content custom-footer-hide">
											<div class="card shadow-sm border-0">
												<div class="card-body p-0" id="my-card-body">
													<div class="list-group">
														<a href="javascript:;" class="p-4 d-flex justify-content-between align-items-center list-group-item list-group-item-action" id="treatMe">
															<div class="staff-flexbox"> 
																<div class="bg-sky rounded-circle text-center" style="width: 56px; height: 56px; line-height: 56px;">
																	<svg class="" viewBox="0 0 32 28" style="width: 24px; height: 24px;"><g transform="translate(5 1)" fill="none" fill-rule="evenodd"><path d="M0 26h0a8 8 0 018-8h6a8 8 0 018 8" stroke="#101928" stroke-width="1.5" stroke-linecap="round"></path><circle fill="#FFC830" cx="11.5" cy="8.5" r="8.5"></circle><ellipse stroke="#101928" stroke-width="1.5" cx="11.5" cy="9" rx="5.5" ry="6"></ellipse></g></svg>
																</div>
															</div>
															<div class="staff-flexbox"> 
																<h6 class="font-weight-bolder m-0">A treat for me</h6> 
															</div> 
															<div class="staff-flexbox">
																<i class="feather-chevron-right" style="font-size: 22px"></i>
															</div>
														</a>
														<a href="javascript:;" class="p-4 d-flex justify-content-between align-items-center list-group-item list-group-item-action" id="prntFrm">
															<div class="staff-flexbox">
																<div class="bg-sky rounded-circle text-center" style="width: 56px; height: 56px; line-height: 56px;">
																	<svg class="" viewBox="0 0 32 28" style="width: 24px; height: 24px;"><g fill="none" fill-rule="evenodd"><path fill="#FFC830" d="M13.5 1.5h18v15h-18z"></path><path stroke="#101928" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M9 8V5h14v3M9 20H5v-9a2 2 0 012-2h18a2 2 0 012 2v9h-4"></path><path stroke="#101928" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" d="M9 14h14v11H9z"></path></g></svg>
																</div>
															</div>
															<div class="staff-flexbox">
																<h6 class="font-weight-bolder m-0">Print as gift</h6>
															</div> 
															<div class="staff-flexbox">
																<i class="feather-chevron-right" style="font-size: 22px"></i>
															</div>
														</a>
														<a href="javascript:;" class="p-4 d-flex justify-content-between align-items-center list-group-item list-group-item-action" id="emailFrm">
															<div class="staff-flexbox">
																<div class="bg-sky rounded-circle text-center" style="width: 56px; height: 56px; line-height: 56px;">
																	<svg class="" viewBox="0 0 32 28" style="width: 24px; height: 24px;"><g fill="none" fill-rule="evenodd"><path fill="#FFC830" d="M20.5 5.5v14h-19v-14z"></path><g stroke="#101928" stroke-linecap="round" stroke-width="1.5"><path d="M7.5 15.068v5.14c0 .947.767 1.714 1.714 1.714h15.422c.946 0 1.714-.767 1.714-1.713v-5.141"></path><path d="M26.35 10.784v-2.57c0-.947-.768-1.714-1.714-1.714H9.214c-.947 0-1.714.767-1.714 1.714v2.57l9.425 5.14 9.425-5.14z"></path></g></g></svg>
																</div>
															</div>
															<div class="staff-flexbox">
																<h6 class="font-weight-bolder m-0">Email as a gift</h6>
															</div> 
															<div class="staff-flexbox">
																<i class="feather-chevron-right" style="font-size: 22px"></i>
															</div>
														</a>
													</div>
												</div>
											</div>
										</div>
										
										<div class="step-tab-content print-frm-sec">
											<div class="px-3 py-2 ">
												<h4 class="fw-800 mb-4">Recipient's info</h4>
												<div class="form-row">
													<div class="col-md-6 form-group"> 
														<label class="font-weight-bolder h6">First name</label>
														<input type="text" class="form-control prntNmInt" id="print_first_name" name="print_first_name" placeholder="">
													</div>
													<div class="col-md-6 form-group">
														<label class="font-weight-bolder h6">Last name <span class="text-muted">(Optional)</span></label>
														<input type="text" class="form-control prntNmInt" name="print_last_name" name="print_last_name" placeholder="">
													</div>
												</div> 
												<div class="form-group">
													<label class="font-weight-bolder h6">Personalised message <span class="text-muted">(Optional)</span></label>
													<textarea class="form-control prntMsgInt" name="print_message" rows="6"></textarea>
												</div> 
												<div class="form-group mb-0">
													<a href="#printPreview" data-toggle="modal" class="h6">See print preview</a>
												</div> 
											</div>
										</div>
										
										<div class="step-tab-content email-frm-sec">
											<div class="px-3 py-2">
												<h4 class="fw-800 mb-4">Recipient's info</h4> 
												<div class="form-row">
													<div class="col-md-6 form-group"> 
														<label class="font-weight-bolder h6">First name</label>
														<input type="text" class="form-control emlName" name="email_first_name" id="email_first_name" value="" placeholder="">
													</div>
													<div class="col-md-6 form-group">
														<label class="font-weight-bolder h6">Last name <span class="text-muted">(Optional)</span></label>
														<input type="text" class="form-control emlName" name="email_last_name" value="" placeholder="">
													</div>
												</div> 
												<div class="form-group">
													<label class="font-weight-bolder h6">Email</label>
													<input type="email" class="form-control" name="email_address" id="email_address" value="">
													<p class="text-muted mb-0">The voucher will be sent to this email address.</p>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder h6">Personalised message <span class="text-muted">(Optional)</span></label>
													<textarea class="form-control emlMsg" rows="6" name="email_msg"></textarea>
												</div> 
												<div class="form-group mb-0">
													<a href="#emailPreview" data-toggle="modal" class="h6">See email preview</a>
												</div> 
											</div> 
										</div>
										
										<div class="step-tab-content">
											@php
												$styleShow2 = "";
												if($loggedUserId != ''){
													$styleShow2 = "display:none;";
												}
											@endphp
											
											<div class="loginSettings" style="{{ $styleShow2 }}">
												<div class="row loginOptionStep commonClass">
													<div class="col-md-12 text-center">
														
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
											<div class="px-3 py-2 paymentDetails" style="{{ $styleShow }}">
												<h4 class="fw-800">Payment method</h4>
												<h6 class="my-4">Your card will be charged straight away</h6>
												<div class="form-group">
													<label class="font-weight-bolder h6">Card holder full name</label>
													<input type="text" class="form-control" id="card_name" placeholder="Add card holder full name">
												</div>
												<div class="form-group">
													<label class="font-weight-bolder h6">Card number</label>
													<input type="text" class="form-control" id="card_number" placeholder="Credit or debit card number">
												</div>
												<div class="form-row">
													<div class="col-md-6 form-group"> 
														<label class="font-weight-bolder h6">Expiry date</label>
														<input type="text" class="form-control" id="expiry_date" placeholder="MM / YYYY">
													</div>
													<div class="col-md-6 form-group">
														<label class="font-weight-bolder h6">CVV</label>
														<input type="password" class="form-control" id="cvv" placeholder="123">
													</div>
												</div>
												<div class="form-group mb-0">
													<span class="text-muted">Pay securely with</span>
													<svg style="margin-left: 8px; border-radius: 4px; height: 24px; width: 34px;" viewBox="0 0 45 32"><rect width="45" height="32" fill="#FFF" rx="4"></rect><g fill="#1A1876" fill-rule="nonzero"><path d="M16.318 21.78h2.839l1.777-10.534h-2.84zM36.743 11.256h-2.195a1.413 1.413 0 00-1.488.874l-4.218 9.65h2.983s.488-1.298.598-1.583h3.638c.084.37.346 1.579.346 1.579h2.635l-2.3-10.52zm-3.502 6.79c.117-.304 1.276-3.313 1.508-3.95.383 1.815.01.054.85 3.95H33.24zm-19.305-6.792l-2.782 7.183-.296-1.46-.996-4.84c-.15-.58-.7-.959-1.288-.89h-4.58l-.036.22c1.041.249 2.045.638 2.984 1.157l2.524 9.148h3.006l4.472-10.514-3.008-.004zm12.725 4.222c-.992-.487-1.6-.813-1.594-1.306 0-.44.515-.906 1.633-.906.823-.021 1.64.159 2.38.524l.385-2.282a7.27 7.27 0 00-2.547-.44c-2.806 0-4.788 1.43-4.8 3.476-.015 1.514 1.415 2.358 2.489 2.86 1.106.516 1.478.845 1.472 1.306-.007.706-.882 1.03-1.7 1.03a6.165 6.165 0 01-3.039-.721l-.398 2.357a8.886 8.886 0 003.163.562c2.986 0 4.924-1.412 4.946-3.598.006-1.199-.75-2.11-2.39-2.862z"></path></g></svg>
													<svg style="margin-left: 8px; border-radius: 4px; height: 24px; width: 34px;" viewBox="0 0 45 32"><rect width="45" height="32" fill="#FFF" rx="4"></rect><g transform="translate(11 9)" fill-rule="nonzero"><path fill="#F26622" d="M11.47 1.519A6.96 6.96 0 008.789 7a6.96 6.96 0 002.683 5.481A6.96 6.96 0 0014.154 7a6.96 6.96 0 00-2.683-5.481z"></path><ellipse cx="7.077" cy="7" fill="#E61C24" rx="7.077" ry="7"></ellipse><ellipse cx="15.865" cy="7" fill="#F99F1B" rx="7.077" ry="7"></ellipse></g></svg>
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
									<p class="text-muted text-center">{{ $LocationData->location_address }}</p>
								</div>
								<hr class="m-0">
								<div class="service-list" style="max-height: 180px;overflow-y: scroll;"> 
									<div class="card-body">
										<div class="mb-3 vcart-list">
											@if(!empty($voucherData))
												<div class="d-flex justify-content-between">
													<div class="d-flex">
														<span class="mr-2"><p class="px-2 py-1 font-weight-bolder rounded-circle border cart-qty">1</p></span>
														<span>
															<h6>{{ $voucherData->name }}</h6>
															@if($voucherData->voucher_type == 0)
																<h6 class=""><a href="#servicesModal" data-toggle="modal">all services</a></h6>
															@else
																<h6 class=""><a href="#servicesModal" data-toggle="modal">{{ count(explode(",", $voucherData->services_ids)) }} services</a></h6>
															@endif
														</span>
													</div>
													<span>
														<h6 class="text-muted">CA $<span class="vcart-total">{{ $voucherData->retailprice }}</span></h6>
														<input type="hidden" name="voucherId" id="voucherId" value="{{ $voucherData->id }}">
														<input type="hidden" name="voucherPrs" id="voucherPrs" value="{{ $voucherData->retailprice }}">
													</span>
												</div>
											@endif
										</div>
										<hr>
										<div class="d-flex justify-content-between">
											<h4 class="font-weight-bolder">Total</h4>
											<h5 class="font-weight-bolder inoviceTotal">CA $<span>0</span></h5>
											<input type="hidden" name="invoiceTotal" id="invoiceTotal" value="">
											<input type="hidden" name="stripeToken" id="stripeToken" value="">
											<input type="hidden" id="stripe_publish_key" value="{{ $setting->stripe_publish_key }}">
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<hr class="m-0">
			<div class="my-custom-header container bg-white my-custom-footer d-none">
				<div class="text-right mt-3 mb-2 d-flex justify-content-between">
					<div class="text-left">
						<h6 class="text-muted mb-0"><span class="voucherQty">1</span> Voucher</h6>
						<h5 class="font-weight-bolder text-dark inoviceTotal">CA $<span>40</span></h5>
					</div>
					<div class="ml-auto">
						<!--button type=" button" class="mr-4 btn btn-outline-dark font-weight-bold" onclick="history.back();">Cancel</button-->
						<button type="button" class="btn btn-dark font-weight-bold next-step" id="nextBtn" onclick="nextPrev(1)">Continue</button>
						<button type="button" class="btn btn-dark font-weight-bold d-none" id="printBtn">Continue</button>
						<button type="button" class="btn btn-dark font-weight-bold d-none" id="emailBtn">Continue</button>
						<button type="button" class="btn btn-dark font-weight-bold" @if($loggedUserId == '') style="display: none;" @endif id="paymentBtn">Pay now</button>
					</div>
				</div>
			</div>
		</div>
	</form>

    <div class="modal fade fullscreen-modal" id="printPreview" tabindex="-1" role="dialog" aria-labelledby="previewModal" aria-hidden="true">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="feather-x"></span>
        </button>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 justify-content-center mb-5 mt-3">
                    <h3 class="modal-title fw-800" id="previewModal">Print voucher</h3> 
                </div>
                <div class="modal-body"> 
                    <div class="voucher-preview-title h6 font-weight-bolder mb-0">Voucher preview</div>
                    <div class="card p-4" style="margin-top: -8px;"> 
                        <h6 class="text-center mt-2 mb-0 printMsg"></h6>
                        <h6 class="text-center mt-2 mb-0 mt-4">voucher for</h6>
                        <h4 class="text-center mt-2 mb-0 printFor"></h4>
                        <div class="voucher-wrapper voucher-border-card mx-5 my-4 text-center justify-content-center">
                            <div class="p-4 text-center"> 
								@if($LocationData->location_name != "")
									<img alt="voucher-thumb" class="rounded mb-4" src="{{ $LocationData->location_image }}" width="80px" height="80px">
								@else
									<img alt="voucher-thumb" class="rounded mb-4" src="{{ asset('frontend/img/featured1.jpg') }}" width="80px" height="80px">
								@endif
                                
                                <h5 class="font-weight-bold text-purple">{{ $LocationData->location_name }}</h5>
                                <h6 class="text-grey">{{ $LocationData->location_address }}</h6> 
                            </div>
                            <div class="add-vouchers-value">
                                <div class="vouchersInner border-purple">
                                    <h6 class="">Voucher Value</h6>
                                    <h3 class="font-weight-bolder text-purple mb-0">CA $<span class="vaoucher-price"></span> </h3> 
                                </div>
                            </div>
                            <div class="p-4 vouchers-bottom">
                                <h6 class="mb-4 text-purple">Voucher Code : <span class="font-weight-bolder">XXXXXXX</span></h6> 
                                <h6 class="mb-1 font-weight-bold">Redeem on <span class="voucher-servs"><span></h6>
                                <h6 class="mb-1 font-weight-bold">Valid for <span class="voucher-valid"></span> </h6>
                                <h6 class="mb-1 font-weight-bold">For multiple-use</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade fullscreen-modal" id="emailPreview" tabindex="-1" role="dialog" aria-labelledby="previewModal" aria-hidden="true">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true" class="feather-x"></span>
        </button>
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header border-0 justify-content-center mb-5 mt-3">
                    <h3 class="modal-title fw-800" id="previewModal">Email voucher</h3> 
                </div>
                <div class="modal-body"> 
                    <div class="voucher-preview-title h6 font-weight-bolder mb-0">Voucher preview</div>
                    <div class="voucher-preview-title justify-content-start h6 mb-0" style="color: #878c93; background: #E8E8EE">Voucher email subject: <span class="text-dark ml-2">$100 Gift Card</span></div>
                    <div class="card p-4" style="margin-top: -8px;"> 
                        <h6 class="text-center mt-2 mb-0 mlMsg"></h6>
                        <h6 class="text-center mt-2 mb-0 mt-4">voucher for</h6>
                        <h4 class="text-center mt-2 mb-0 mlFor"></h4>
                        <div class="voucher-wrapper bg-purple-gradient mx-5 my-4 text-center justify-content-center">
                            <div class="p-4 text-center"> 
                                @if($LocationData->location_name != "")
									<img alt="voucher-thumb" class="rounded mb-4" src="{{ $LocationData->location_image }}" width="80px" height="80px">
								@else
									<img alt="voucher-thumb" class="rounded mb-4" src="{{ asset('frontend/img/featured1.jpg') }}" width="80px" height="80px">
								@endif
                                <h5 class="font-weight-bold text-purple">{{ $LocationData->location_name }}</h5>
                                <h6 class="text-grey">{{ $LocationData->location_address }}</h6> 
                            </div>
                            <div class="add-vouchers-value">
                                <div class="vouchersInner border-light">
                                    <h6 class="">Voucher Value</h6>
                                    <h3 class="font-weight-bolder mb-0">CA $<span class="vaoucher-price"></span> </h3> 
                                </div>
                            </div>
                            <div class="p-4 vouchers-bottom">
                                <h6 class="mb-4">Voucher Code : <span class="font-weight-bolder">XXXXXXX</span></h6> 
                                <a href="" class="btn btn-light font-weight-bolder mb-4 px-6 py-3">Book now</a>
                                <h6 class="mb-1 font-weight-bold">Redeem on <span class="voucher-servs"><span></h6>
                                <h6 class="mb-1 font-weight-bold"><span class="voucher-valid"></span> </h6>
                                <h6 class="mb-1 font-weight-bold">For multiple-use</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="servicesModal">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header align-items-center">
                    <h4 class="modal-title font-weight-bolder">Redeemable services</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body pt-0">
                    @if(!empty($serviceCategory))
						@foreach($serviceCategory as $key => $service)
							<div>
								@if(!empty($service))
									@foreach($service as $sKey => $sValue)
										@if($sKey == 0) 
											<h4 class="font-weight-bolder mb-4 mt-3 category-header">{{ $sValue->category_title }}</h4>
										@endif
										<div class="border-bottom mb-3 pb-2">
											<span class="title d-flex justify-content-between">
												<h6 class="font-weight-bolder mb-1">{{ $sValue->service_name }}</h6>
												<h6 class="text-muted mb-1">From</h6>
											</span>
											<span class="title d-flex justify-content-between">
												<h6 class="text-muted">{{ $sValue->serviceDuration }}</h6>
												<h6 class="font-weight-bolder">&#8377; {{ $sValue->service_price_special_amount }}</h6>
											</span>

											@if($sValue->service_price_special_amount < $sValue->service_price_amount)
												<span class="title d-flex justify-content-end"> 
													<h6 class="text-muted"><strike>&#8377; {{ $sValue->service_price_amount }}</strike></h6>
												</span>
											@endif
										</div>
									@endforeach
								@endif
							</div> 
						@endforeach
					@endif
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.13.4/jquery.mask.min.js"></script>
<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script src="{{ asset('js/sellvoucher.js') }}"></script>
<script>
	$(document).ready(function () {
		$("#card_number").mask('0000 0000 0000 0000');
		$('#cvv').mask('000');
		$('#expiry_date').mask('00/0000');
		
		var topHeader = $('.my-custom-header').outerHeight();
		var serviceHeader = $('.service-menu').outerHeight();
		// var totalHeader = parseFloat(topHeader) + parseFloat(serviceHeader);
		$('ul li.nav-item').on('click', function () {
			// $('.my-card-body').css('padding-top', serviceHeader + 'px'); 
			// $('.main-content').css('padding-top', serviceHeader + 'px'); 
		});
	});
</script>
@endsection		