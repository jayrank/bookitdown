{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/owl.css') }}" rel="stylesheet" type="text/css">
<style>
	.help-inline{
		color: red;
	}
	.parpal1{
		background: linear-gradient(-45deg, rgb(190, 74, 244) 0%, rgb(92, 55, 246) 100%);
	}
	.blue1{
		background: linear-gradient(-225deg, rgb(11, 109, 217) 0%, rgb(95, 171, 255) 100%);
	}
	.black1{
		background: linear-gradient(-225deg, rgb(16, 25, 40) 0%, rgb(32, 48, 71) 100%);
	}
	.green1{
		background: linear-gradient(-45deg, rgb(0, 166, 156) 0%, rgb(0, 157, 98) 100%);
	}
	.orange1{
		background: linear-gradient(-45deg, rgb(237, 176, 27) 0%, rgb(222, 100, 38) 100%);
	}
	
</style>
@endsection
@section('content')
<!--begin::Tabs-->
@if ($errors->any())
	<div class="alert alert-danger">
	    <ul>
	        @foreach ($errors->all() as $error)
	            <li>{{ $error }}</li>
	        @endforeach
	    </ul>
	</div>
@endif
@if ($message = Session::get('success'))
	<div class="alert alert-success alert-block">
	    <button type="button" class="close" data-dismiss="alert">×</button>    
	    <strong>{{ $message }}</strong>
	</div>
@endif
	<div class="container-fluid p-0">
		<div class="my-custom-body-wrapper">
			<div class="my-custom-header">
				<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
					<span class="d-flex">
						<p class="cursor-pointer m-0 px-6" onclick="history.back();"><i class="text-dark fa fa-times icon-lg"></i>
						</p>
					</span>
					<button class="btn btn-primary previous" onclick="nextPrev(-1)" style="position: absolute;" >Previous</button>
					<h1 class="font-weight-bolder">Update a voucher type</h1>
					<button class="btn btn-primary next-step" onclick="nextPrev(1)" data-count="0">Next Step</button>
					<button class="btn btn-info " id="update" style="display: none">Update</button>
				</div>
			</div>
			<div class="my-custom-body">
				<div class="container-fluid">
					<form  method="post" id="updateV">
						<div class="row">
							<div class="col-12 col-md-4 p-0">
								<div class="p-4" style="height:calc(100vh - 80px);overflow-y:scroll">
									<div class="add-voucher-tab">
										<div>
											<input type="hidden" name="id" value="{{ $voucher->id }}">
											<h3 class="font-weight-bolder">Voucher info <span class="text-danger">*</span></h3>
											<p class="font-size-lg">Add the voucher name, value and duration of the voucher.
												If
												the
												voucher value is
												higher than the retail price it will
												encourage more sales.</p>
											<div class="form-group">
												<input type="text" class="form-control" placeholder="" name="name" value="{{ $voucher->name }}">
											</div>
											<div class="row">
												<div class="form-group col-sm-6">
													<label>Value <span class="text-danger">*</span></label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text">CA $</span>
														</div>
														<input name="value" type="text" value="{{ $voucher->value }}" id="voucher-prica-value" class="form-control allow_only_decimal">
													</div>
												</div>
												<div class="form-group col-sm-6">
													<label>Retail price <span class="text-danger">*</span></label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text">CA $</span>
														</div>
														<input name="retail" value="{{ $voucher->retailprice }}" id="voucher-retail-price" type="text" class="form-control allow_only_decimal">
														<span class="text-danger" id="voucher-retail-price-error"></span>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="font-weight-bolder">Valid for</label>
												<select  class="form-control valid-for-select" name="validfor">
													<option value="14 days" data-value="Valid for 14 days" @if($voucher->validfor=='14 Days') selected @endif >14 days</option>
													<option value="1 month" data-value="Valid for 1 months" @if($voucher->validfor=='1 month') selected @endif >1 month</option>
													<option value="2 month" data-value="Valid for 2 months" @if($voucher->validfor=='2 month') selected @endif >2 months</option>
													<option value="3 month" data-value="Valid for 3 months" @if($voucher->validfor=='3 month') selected @endif >3 months</option>
													<option value="6 month" data-value="Valid for 6 months" @if($voucher->validfor=='6 month') selected @endif >6 months</option>
													<option value="1 year" data-value="Valid for 1 years" @if($voucher->validfor=='1 year') selected @endif >1 year</option>
													<option value="3 year" data-value="Valid for 3 years" @if($voucher->validfor=='3 year') selected @endif >3 years</option>
													<option value="5 year" data-value="Valid for 5 years" @if($voucher->validfor=='5 year') selected @endif >5 years</option>
													<option value="never" data-value="No Expiry Date" @if($voucher->validfor=='never') selected @endif >Forever</option>
												</select>
											</div>
											<div class="form-group">
												<div class="mt-4 switch switch-icon switch-success"
													style="line-height: 33px;">
													<label class="w-100">
														<input type="checkbox" id="enableSalesLimit" name="enableSalesLimit" value="1" @if($voucher->enable_sale_limit == 1) checked="checked" @endif >Limit amount of sales
														<span class="ml-auto text-right"></span>
													</label>
												</div>
											</div>
											<div class="form-group">
												<label class="font-weight-bolder">Number of sales</label>
												<select name="numberofsales" class="form-control" id="maxNumberOfSales" name="maxNumberOfSales" @if($voucher->enable_sale_limit == 0) disabled @endif >
													<option value="10" @if($voucher->numberofsales=='10') selected @endif >10</option>
													<option value="20" @if($voucher->numberofsales=='20') selected @endif >20</option>
													<option value="30" @if($voucher->numberofsales=='30') selected @endif >30</option>
													<option value="50" @if($voucher->numberofsales=='50') selected @endif >50</option>
													<option value="100" @if($voucher->numberofsales=='100') selected @endif >100</option>
													<option value="250" @if($voucher->numberofsales=='250') selected @endif >250</option>
													<option value="500" @if($voucher->numberofsales=='500') selected @endif >500</option>
													<option value="1000" @if($voucher->numberofsales=='1000') selected @endif >1000</option>
												</select>
											</div>
										</div>
										<hr />
										<div class="">
											<div class="form-group" style="position: relative;">
												<label class="font-weight-bolder">Included services</label>
												<input type="text" id="serviceInput" readonly=""
													class="form-control form-control-lg" placeholder="All services"
													data-toggle="modal" data-target="#servicesModal" style="cursor: pointer;">
												<a href="" class="chng_popup" data-toggle="modal"
													data-target="#servicesModal">
													Edit</a>
											</div>
										</div>
										<hr />
										<div class="">
											<h3 class="font-weight-bolder">Online sales</h3>
											<p>Choose if you would like to sell your voucher online.</p>
											<div class="form-group">
												<div class="mt-4 switch switch-icon switch-success"
													style="line-height: 33px;">
													<label class="w-100">
														<input type="checkbox" id="enableOnlineSale" name="online"	@if($voucher->is_online=='1') checked @endif name="select">Enable Online Sale
														<span class="ml-auto text-right"></span>
													</label>
												</div>
											</div>
											<!-- <div>
												<div class="add-voucher-header rounded">
													<h2 class="text-dark font-weight-bolder mb-4">
														Set up payments to sell vouchers online and increase sales
													</h2>
													<p class="font-size-lg">
														Congrats! You have ScheduleDown Plus enabled. Let clients buy your
														vouchers
														online through the ScheduleDown marketplace and via
														direct booking links by setting up payments in your account.
													</p>
													<button type="button" class="bt btn-white" data-toggle="modal"
														data-target="#learnMoreModal">Learn More</button>
												</div>
											</div> -->
										</div>
									</div>
									<div class="add-voucher-tab">
										<div>
											<h3 class="font-weight-bolder">Text</h3>
											<p class="font-size-lg">
												Add a title and a message on the voucher.
											</p>
											<div class="form-group">
												<label>Voucher title <span class="text-danger">*</span></label>
												<input name="title" value="{{ $voucher->title }}" type="text" id="voucherTitleInput" class="form-control">
											</div>
											<div class="form-group">
												<label>Voucher description</label>
												<textarea name="description" class="form-control" id="voucherDescriptionInput"
													rows="8">{{ $voucher->description }}</textarea>
											</div>
										</div>
										<hr />
										<div>
											<h3 class="font-weight-bolder">Voucher colour</h3>
											<p class="font-size-lg">
												Select a color that matches your business.
											</p>
											<div class="form-group">
												<label class="font-weight-bolder">Choose a colour</label>
												<div class="radio-inline flex-wrap paid-plan-radio">
													<label class="radio radio-accent purple">
														<input type="radio" @if($voucher->color== "parpal") checked="" @endif  value="parpal" name="color">
														<span></span>
													</label>
													<label class="radio radio-accent blue">
														<input type="radio" @if($voucher->color== "blue") checked="" @endif value="blue" name="color">
														<span></span>
													</label>
													<label class="radio radio-accent black">
														<input type="radio" @if($voucher->color== "black") checked="" @endif value="black" name="color">
														<span></span>
													</label>
													<label class="radio radio-accent green">
														<input type="radio" @if($voucher->color== "green") checked="" @endif value="green" name="color">
														<span></span>
													</label>
													<label class="radio radio-accent orange">
														<input type="radio" @if($voucher->color== "orange") checked="" @endif value="orange" name="color">
														<span></span>
													</label>
												</div>
											</div>
										</div>
										<hr />
										<div class="">
											<h3 class="font-weight-bolder">Buttons</h3>
											<p>Add buttons to the voucher.</p>
											<div class="form-group">
												<div class="mt-4 switch switch-icon switch-success"
													style="line-height: 33px;">
													<label class="w-100">
														<input type="checkbox" @if($voucher->button==1) checked @endif  id="isAddBookButton"
															name="addbutton">Add a
														Book now button
														<span class="ml-auto text-right"></span>
													</label>
												</div>
											</div>
										</div>
										<hr />
										<div class="">
											<h3 class="font-weight-bolder">Notes for the client</h3>
											<p>Add a note for clients. This will always be visible</p>
											<div class="form-group">
												<div class="mt-4 switch switch-icon switch-success"
													style="line-height: 33px;">
													<label class="w-100">
														<input type="checkbox" @if(!empty(trim($voucher->note))) @else checked @endif id="isEnableNote" name="isnote">
														Enable notes for clients
														<span class="ml-auto text-right"></span>
													</label>
												</div>
											</div>
											<div class="form-group  isNote" @if(!empty(trim($voucher->note))) style="display: none;" @endif>
												<label>Note</label>
												<textarea name="note" id="voucherNote" class="form-control" rows="8">{{ $voucher->note }}</textarea>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-8 p-0 bg-content" style="height:calc(100vh - 80px);overflow-y:scroll">
								<div class="p-4 d-flex justify-content-center">
									<div class="card w-70">
										<div class="card-header p-4 text-center">
											Voucher Preview
										</div>
										<div class="card-header p-4">
											<span class="text-muted">Voucher email subject: </span><span><span
													id="voucherTitle">Voucher</span> from Gym
												area</span>
										</div>
										<div class="card-body">
											<div class="w-70 m-auto position-relative">
												<h5 class="text-center mb-2" id="voucherDescription">sdasdsd</h5>
												<div class="p-10 text-center {{ $voucher->color }}1 newv  text-white justify-content-center bgi-size-cover bgi-no-repeat">
													<div class="p-4 text-center"> 
														@if(count($locationData) <= 1)
															<img alt="voucher-thumb" class="rounded mb-4"
															src="{{ url($locationData[0]['location_image']) }}" width="80px" height="80px">
															<h3 class="font-weight-bold">{{ $locationData[0]['location_name'] }}</h3>
															<h5 class="text-grey">{{ $locationData[0]['location_address'] }}</h5>
														@else
															<img alt="voucher-thumb" class="rounded mb-4"
															src="{{ url($locationData[0]['location_image']) }}" width="80px" height="80px">
															<h3 class="font-weight-bold">{{ $locationData[0]['location_name'] }}</h3>
															<p class="text-grey">{{ count($locationData) }} Locations</p>
														@endif
													</div>
													<div class="border-bottom w-100 opacity-20"></div>
													<div class="my-8 vouchers-value add-vouchers-value">
														<p class="font-weight-bolder mb-0 font-size-lg">Voucher Value</p>
														<h1 class="font-weight-bolder">CA $<span id="vaoucher-price">{{ $voucher->value }}</span>
														</h1>
													</div>
													<div class="border-bottom w-100 opacity-20"></div>
													<div class="my-8 vouchers-bottom">
														<p class="mb-4 font-size-lg">Voucher Code : <span
																class="font-weight-bolder font-size-lg">******</span></p>
														<button type="button" class="isBookButton btn btn-light  my-4 px-4" @if($voucher->button=='0') style="display: none;" @endif>Book
															Now</button>
														<p class="mb-1 font-weight-bold font-size-lg">Redeem on <span
																class="font-weight-bolder cursor-pointer redeemServices">all
																services</span> <i class="fa fa-chevron-right icon-sm"></i>
														</p>
														<p class="mb-1 font-weight-bold font-size-lg valid-for-text">Valid until 2 Jul 2021
														</p>
														<p class="mb-1 font-weight-bold font-size-lg">For multiple-use</p>
													</div>
												</div>

												<p class="voucher-note-for-client" style="@if($voucher->note == 0) display: none; @endif">Notes from @if(!empty($locationData)) {{ $locationData[0]['location_name'] }} @endif <br><span id="voucherPreviewNotes">{{ $voucher->note }}</span></p>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						{{-- start  --}}
						<div class="modal" id="servicesModal">
							<div class="modal-dialog">
								<div class="modal-content">
									<!-- Modal Header -->
									<div class="modal-header d-flex justify-content-between">
										<h4 class="modal-title">Select services</h4>
										<button type="button" class="text-dark close" data-dismiss="modal">&times;</button>
									</div>
									<!-- Modal body -->
									<div class="modal-body">
										<div class="form-group">
											<div class="input-icon input-icon-right">
												<input type="text" class="rounded-0 form-control search-services"
													placeholder="Search any item">
												<span>
													<i class="flaticon2-search-1 icon-md"></i>
												</span>
											</div>
										</div>
										<hr class="m-0">
					
										<div class="multicheckbox">
											<ul id="treeview">
												<li>
													<label for="all" class="checkbox allService all-service-element">
														<input type="checkbox" name="all" id="all">
														<span></span>
														All Services
													</label>
													<ul>
													@php 
														$totalService = 0;
													@endphp
													@foreach($cat as $value)
														<li>
															<label for="all-{{ str_replace(' ', '_', $value->category_title) }}" class="checkbox service-element" data-value="{{ $value->category_title }}">
																<input type="checkbox" class="allCategory" name="all-{{ $value->category_title }}" data-count="{{ $cat->count() }}" id="all-{{ str_replace(' ', '_', $value->category_title) }}">
																<span></span>
																{{ $value->category_title }}
															</label>
															<ul>
																@php
																	$i=1;
																	$services_ids = explode(",", $voucher->services_ids);
																@endphp
																@foreach($value->service as $service)
																<li>
																	<label for="all-{{ $value->category_title }}-{{ $i }}" class="checkbox service-element" data-value="{{ $service->service_name }}">
																		<input type="checkbox" name="value_checkbox[]" value="{{ $service->id }}" @if( in_array($service->id, $services_ids)) checked="" @endif id="all-{{ $value->category_title }}-{{ $i }}" data-count="{{ $value->service->count() }}" class="servicePrices" data-cat="all-{{ str_replace(' ', '_', $value->category_title) }}">
																		<span></span>
																		<div class="d-flex align-items-center w-100">
																			<span class="m-0">
																				{{ $service->service_name }}
																				<p class="m-0 text-muted">@foreach($service->servicePrice as $value1){{ $value1->duration }},@endforeach	</p>
																			</span>
																			<span class="ml-auto">
																				@foreach($service->servicePrice as $value2)CA ${{ $value2->price }}@endforeach
																			</span>
																		</div>
																	</label>
																</li>
																@php
																	$i++; $totalService++;
																@endphp
																@endforeach
															</ul>
														</li>
														
													@endforeach
													</ul>
												</li>
												<input type="hidden" id="totalService" value="{{ $totalService }}">
											</ul>
										</div>
									</div>
									<div class="modal-footer">
										<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
										<button type="button" class="btn btn-primary" data-dismiss="modal">Select Services</button>
									</div>
								</div>
							</div>
						</div>
						{{-- end service modal --}}
				</form>
				</div>
			</div>
		</div>
	</div>

	{{-- model --}}
	<div class="modal fade" id="learnMoreModal" tabindex="-1" role="dialog" aria-labelledby="learnMoreModal" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-body p-0 m-0">
					<button type="button" class="position-absolute close m-auto p-6 right-0 z-index" data-dismiss="modal" aria-label="Close" style="z-index: 99;font-size: 30px;">
						<span aria-hidden="true">×</span>
					</button>
					<div class="container p-0 m-0">
						<div class="row">
							<div class="col-12 col-md-6">
								<div class="p-10">
									<h1 class="font-weight-bolder my-18">Enable card payments for online booking
										and
										say
										goodbye
										to
										no shows!</h1>
									<h5 class="text-secondary my-18">Setup ScheduleDown Pay now to enable in-app
										payment
										processing,
										take
										back control of your
										calendar by charging no show and
										late cancellation fees to client cards</h5>
									<h5 class="text-secondary my-18">There are <span class="font-weight-bolder"><u>no
												additional
												fees</u></span> to use
										integrated
										payment processing features, it's already included with ScheduleDown Plus.</h5>
									<div class="d-flex my-4">
										<button class="btn btn-primary mr-8">Setup ScheduleDown Pay</button>
										<a class="btn btn-white">Learn More</a>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6">
								<div class="learnMoreModalBackImg">
									<div class="owl-carousel owl-loaded owl-drag owl-hidden">
										
										
									<div class="owl-stage-outer"><div class="owl-stage" style="transition: all 0s ease 0s; width: 1095px; transform: translate3d(0px, 0px, 0px);"><div class="owl-item active center" style="width: 537.5px; margin-right: 10px;"><div class="">
											<div class="d-flex">
												<img alt="phone" src="{{ asset('/assets/images/phone.png') }}">
												<img alt="phone" class="position-absolute" src="{{ asset('/assets/images/chat.png') }}">
											</div>
											<div class="px-10">
												<h3 class="text-center mb-10 pt-0 px-20"><span class="font-weight-bolder">Protect
														from no
														shows</span> and late
													cancellations by charging client cards
												</h3>
											</div>
										</div></div><div class="owl-item" style="width: 537.5px; margin-right: 10px;"><div class="">
											<div class="d-flex">
												<img alt="phone" src="{{ asset('/assets/images/visa-phone.png') }}">
												<img alt="phone" class="position-absolute" src="{{ asset('/assets/images/visa.png') }}">
											</div>
											<div class="px-10">
												<h3 class="text-center mb-10 pt-0 px-20"><span class="font-weight-bolder">Integrated card processing</span> for
													easy and secure in-app payments
												</h3>
											</div>
										</div></div></div></div><div class="owl-nav disabled"><div class="owl-prev">prev</div><div class="owl-next">next</div></div><div class="owl-dots"><div class="owl-dot active"><span></span></div><div class="owl-dot"><span></span></div></div></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	{{-- end --}}

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

@endsection
{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('assets/js/owl.js') }}"></script>
<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js') }}"></script>
	<script>
		var list = [];
		$("#treeview").hummingbird();
		countServices();
		$("#treeview").on("CheckUncheckDone", function () {
			countServices();
		});

		function countServices() {
			var count = $('input[name="value_checkbox[]"]:checked').length;
			/*var allCount = $('input[type="checkbox"]:checked').length;
			var allCheck = $('input[type="checkbox"]').length;*/
			var allCount = $('input[name="value_checkbox[]"]:checked').length;
			var allCheck = $('input[name="value_checkbox[]"]').length;

			// if (allCheck == allCount) {
			// 	$("#serviceInput").val('All Services Selected');
			// 	$(".redeemServices").text('all services');
			// } else {
				$("#serviceInput").val(count + ' services Selected');
				$(".redeemServices").text(count + ' services Selected');
			// }
		}
	</script>

	<!-- Modal Step Hide Show -->
	<script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.9/jquery.validate.js"></script>

	<script>
		var currentTab = 0; // Current tab is set to be the first tab (0)
		showTab(currentTab); // Display the current tab

		
		function showTab(n) {
			var save=$(".next-step").text();
			// This function will display the specified tab of the form...
			var tab = document.getElementsByClassName("add-voucher-tab");
			tab[n].style.display = "block";
			if (n == (tab.length - 1)) {
				$(".previous").show();
			} else {
				$(".previous").hide();
			}
			if (n == (tab.length - 1)) {
				$(".next-step").hide();
				$("#update").show();

			} else {
				$(".next-step").text("Next Step");
				$(".next-step").show();
				$("#update").hide();
			}
		}
		function nextPrev(n) {
			// This function will figure out which tab to display
			var tab = document.getElementsByClassName("add-voucher-tab");
			// Hide the current tab:
			tab[currentTab].style.display = "none";
			// Increase or decrease the current tab by 1:
			currentTab = currentTab + n;
			// if you have reached the end of the tab
			// Otherwise, display the correct tab:
			console.log(currentTab);
			showTab(currentTab);
		}

		{{-- edit voucher --}}
			$(document).on('click','#update', function(){
				$("#updateV").validate({
					rules: {
						name: {
							required: true,
						},
						value: {
							required: true,
						},
						retail: {
							required: true,
						},
						title: {
							required: true,
						},
					},
					messages: {
	
						name: {
							required: 'Enter Name',
						},
						value: {
							required: 'Enter Value',
						},
						retail: {
							required: 'Enter Retail Price',
						},
						title: {
							required: 'Enter Title',
						},
					},
					errorClass: "help-inline",
					errorElement: "span",
					highlight: function(element, errorClass, validClass) {
						$(element).parents('.control-group').addClass('error');
					},
					unhighlight: function(element, errorClass, validClass) {
						$(element).parents('.control-group').removeClass('error');
						$(element).parents('.control-group').addClass('success');
					}
				});

				var form = $("#updateV");
				var totalService = $("#totalService").val();
				$.ajax({
					type: "POST",
					url: "{{ route('updateVoucher') }}",
					headers:{
						'X-CSRF-Token': '{{ csrf_token() }}',
					},
					data: form.serialize() + "&totalservice=" + totalService,
					success: function (data) {
						KTApp.unblockPage();
						toastr.success(data.message);
						window.location.href = "{{route('voucherindex')}}";
					},
					error: function(data) {
						var errors = data.responseJSON;
						var errorsHtml = '';

						var errMsg = errors.error.message;
						if(errMsg != '')
						{
							toastr.error(errMsg);

						}else{


						$.each(errors.error, function(key, value) {
							errorsHtml += '<p>' + value[0] + '</p>';
						});
					}		
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
		
						toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
						KTUtil.scrollTop();
					}
				});
			});
		{{-- end --}}

		$(document).ready(function () {
			$("#voucher-prica-value").keyup(function () {
				var val = ($.trim($(this).val()) != '') ? parseFloat($(this).val()).toFixed(2) : ''
				$('#vaoucher-price').text(val);
			});
		})
		$(document).ready(function () {
			$("#voucherTitleInput").keyup(function () {
				$('#voucherTitle').text($(this).val())
			});
		})
		$(document).ready(function () {
			$("#voucherDescriptionInput").keyup(function () {
				$('#voucherDescription').text($(this).val())
			});
		})

		$("#enableSalesLimit").click(function () {
			if ($(this).is(":checked")) {
				$("#maxNumberOfSales").prop('disabled', false);
			} else {
				$("#maxNumberOfSales").prop('disabled', true);
			}
		});
		$("#isAddBookButton").click(function () {
			if ($(this).is(":checked")) {
				$(".isBookButton").show();
			} else {
				$(".isBookButton").hide();
			}
		});
		$("#isEnableNote").click(function () {
			if ($(this).is(":checked")) {
				$(".isNote").show();
				$(".voucher-note-for-client").show();
			} else {
				$(".isNote").hide();
				$(".voucher-note-for-client").hide();
			}
		});

		$('input[name="color"]').change(function () {
			if ($(this).is(':checked')) {
				// $(".newv").css("background", $(this).val())
				$('.newv').removeClass('parple1');
				$('.newv').removeClass('blue1');
				$('.newv').removeClass('black1');
				$('.newv').removeClass('green1');
				$('.newv').removeClass('orange1');
				$(".newv").addClass($(this).val()+'1');
			}
		});

		$(document).on('keydown', ".allow_only_decimal",function (event) {
		    if (event.shiftKey == true) {
		        event.preventDefault();
		    }

		    if ((event.keyCode >= 48 && event.keyCode <= 57) || 
		        (event.keyCode >= 96 && event.keyCode <= 105) || 
		        event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
		        event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190) {

		    } else {
		        event.preventDefault();
		    }

		    if($(this).val().indexOf('.') !== -1 && event.keyCode == 190)
		        event.preventDefault(); 
		    //if a decimal has been added, disable the "."-button

		});

		$(document).on('keyup', '.search-services', function(){
			var val = $.trim($(this).val());
			val = val.toLowerCase();

			if(val != '') {
				$('.all-service-element').hide();
				$('.service-element').hide();

				$('.service-element').each(function(){
					var elementText = $(this).attr('data-value');
					elementText = elementText.toLowerCase();

					if(elementText.search(val) > -1) {
						$(this).show();
						$(this).closest('ul').siblings('.service-element').show();
					}
				});
				// $('.service-element[data-value="'+val+'"]').show();
			} else {
				$('.all-service-element').show();
				$('.service-element').show();
			}
		});

		validateRetailPrice();
		
		$(document).on('keyup', '#voucher-prica-value, #voucher-retail-price', function(){
			validateRetailPrice();			
		});

		function validateRetailPrice() {
			var voucherValue = ($.trim($('#voucher-prica-value').val()) != '') ? parseFloat($('#voucher-prica-value').val()) : 0.00;
			var retailPrice = ($.trim($('#voucher-retail-price').val()) != '') ? parseFloat($('#voucher-retail-price').val()) : 0.00;

			if(retailPrice > voucherValue) {
				$('#voucher-retail-price-error').text('The retail price is higher than the voucher value');
				$('.next-step').attr('disabled', true);
			} else {
				$('#voucher-retail-price-error').text('');
				$('.next-step').removeAttr('disabled');
			}
		}
		$('#voucherNote').on('keyup',function(){
			$('#voucherPreviewNotes').html($(this).val());
		});
		/*$('.servicePrices:checked').each(function(){
			var cat = $(this).data('cat');
			$('#'+cat).prop('checked',true);
		});*/
		$('.allCategory').each(function(){
			var totalCat = $(this).data('count');
			var checkedCat = $('.allCategory:checked').length;
			if(totalCat == checkedCat){
				$('#all').prop('checked',true);
			}

			var cat = $(this).attr('id');
			console.log('cat::'+cat);
			var totalCatServices = $('.servicePrices[data-cat="'+cat+'"]').length;
			var totalCatServicesChecked = $('.servicePrices:checked[data-cat="'+cat+'"]').length;

			if(totalCatServices == totalCatServicesChecked) {
				$('#'+cat).prop('checked',true);
			}
		});
		var allCheck = $('input[type="checkbox"]').length;
		var allCount = $('input[type="checkbox"]:checked').length;
		var count = $('input[name="value_checkbox[]"]:checked').length;

		if (allCheck == allCount || $('#all').prop('checked') == true) {
			$(".servicesApply").text('all services')
		} else {
			if(count == 1)
			{
				$(".servicesApply").text(count+" service")
			}
			else
			{
				$(".servicesApply").text(count+" services")
			}
		}

		updateValidForText();
		$(document).on('change', '.valid-for-select', function(){
			updateValidForText();
		});

		function updateValidForText() {
			$('.valid-for-text').text($('.valid-for-select').find('option:selected').attr('data-value'));
		}
	</script>
</script>
@endsection