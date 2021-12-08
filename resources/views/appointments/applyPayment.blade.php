{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')

@endsection

{{-- CSS Section --}}
@section('content')
<!--begin::Content-->

<!-- Modal -->
<div class="modal fade" id="payByTextModal" tabindex="-1" role="dialog" aria-labelledby="payByTextModal" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-body p-0 m-0">
				<button type="button" class="position-absolute close m-auto p-6 right-0 z-index" data-dismiss="modal" aria-label="Close" style="z-index: 99;font-size: 30px;">
					<span aria-hidden="true">&times;</span>
				</button>
				<div class="container p-0 m-0">
					<div class="row">
						<div class="col-12 col-md-6">
							<div class="p-10">
								<h1 class="font-weight-bolder my-18">Enable card payments for online booking and say goodbye to no shows!</h1>
								<h5 class="text-secondary my-18">Setup Fresha Pay now to enable in-app payment processing, take back control of your calendar by charging no show and late cancellation fees to client cards</h5>
								<h5 class="text-secondary my-18">There are <span class="font-weight-bolder"><u>no additional fees</u></span> to use integrated payment processing features, it's already included with ScheduleDown Plus.</h5>
								<div class="d-flex my-4">
									<button class="btn btn-lg btn-primary mr-8">Setup ScheduleDown Pay</button>
									<a class="btn btn-lg btn-white">Learn More</a>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-6">
							<div class="learnMoreModalBackImg">
								<div class="owl-carousel">
									<div class="">
										<div class="d-flex">
											<img alt="phone" src="./assets/images/phone.png" />
											<img alt="phone" class="position-absolute"
												src="./assets/images/chat.png" />
										</div>
										<div class="px-10">
											<h3 class="text-center mb-10 pt-0 px-20"><span class="font-weight-bolder">Protect from no shows</span> and late cancellations by charging client cards</h3>
										</div>
									</div>
									<div class="">
										<div class="d-flex">
											<img alt="phone" src="./assets/images/visa-phone.png" />
											<img alt="phone" class="position-absolute" src="./assets/images/visa.png" />
										</div>
										<div class="px-10">
											<h3 class="text-center mb-10 pt-0 px-20"><span
													class="font-weight-bolder">Integrated card processing</span>
												for
												easy and secure in-app payments
											</h3>
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
</div>

<!-- Modal -->
<div class="modal fade" id="voucherPay">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header d-flex justify-content-between">
				<h3 class="font-weight-bolder modal-title">Redeem Voucher</h3>
				<button type="button" class="text-dark close" data-dismiss="modal"><i class="ki ki-close"></i></button>
			</div>
			
			<div class="modal-body">
				<div class="form-group voucher-inpt-sec">
					<div class="input-icon input-icon-right">
						<input type="text" class="rounded-0 form-control" id="voucher_code" placeholder="Enter voucher code">
						<span><i class="flaticon2-search-1 icon-md"></i></span>
					</div>
				</div>
				
				<div class="voucher-nomatch d-none">
					<div class="card shadow bg-content">
						<div class="ribbon ribbon-top-left"><span>SERVICE</span></div>
						<div class="card-body text-center">
							<h4 class="font-weight-bold font-weight-bolder mb-3 p-4 voucher-value">Voucher of CA $<span></span></h4>
							<h6 class="text-center">This voucher cannot be redeemed, the service type does not match.</h6>
						</div>
					</div>
					<div class="my-4">
						<input type="hidden" id="redeemed_amount" value="0">
						<h6 class="mt-3 font-weight-bolder">Voucher History</h6>
						<p class="mb-0 font-weight-bold voucher-exp">Expires on Monday,3 May 2021</p>
						<p class="mb-0 font-weight-bold voucher-pur">Purchased Monday,19 April 2021</p>
					</div>
				</div>
				
				<div class="voucher-info d-none">
					<div class="card shadow bg-content">
						<div class="ribbon ribbon-top-left"><span>Gift</span></div>
						<div class="card-body">
							<h4 class="font-weight-bold font-weight-bolder mb-3 p-4 voucher-outstanding">Outstanding CA $<span>200</span></h4>
							<div class="redeem_sec">
								<div class="form-group px-4">
									<label class="font-weight-bold">Redemption Amount</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text bg-white isPrice">
												CA $ 
											</span>
										</div>
										<input class="form-control" id="redemption_amount" value="0">
									</div>
								</div>
								<button type="button" id="redeem_voucher" class="btn-block btn btn-primary px-4">Redeem Vouchers</button>
							</div>
							<div class="unpaid-voucher d-none">
								<h6 class="text-center">This voucher cannot be redeemed, the original sale is unpaid.</h6>
							</div>
							<div class="existing-voucher d-none">
								<h6 class="text-center">This voucher was already selected for payment on this invoice.</h6>
							</div>
							<div class="redeemed-voucher d-none">
								<h6 class="text-center">This voucher was already fully redeemed.</h6>
							</div>
						</div>
					</div>
					<div class="my-4">
						<input type="hidden" id="redeemed_amount" value="0">
						<h6 class="mt-3 font-weight-bolder">Voucher History</h6>
						<p class="mb-0 font-weight-bold voucher-exp">Expires on Monday,3 May 2021</p>
						<p class="mb-0 font-weight-bold voucher-pur">Purchased Monday,19 April 2021</p>
					</div>
				</div>
				<div class="voucher-rslt"></div>
				<div class="d-flex flex-column justify-content-center align-items-center my-20 voucher-default-text">
					<div style="height: 50px;width: 50px;">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 60 60">
							<path
								d="M21.3 59.4c-1.3 0-2.6-.5-3.5-1.5L1.5 41.6c-1-1-1.5-2.2-1.5-3.5s.5-2.6 1.5-3.5L28.6 7.3c.2-.2.5-.3.7-.3H51c.6 0 1 .4 1 1v22.1c0 .3-.1.5-.3.7L24.9 57.9c-1 1-2.3 1.5-3.6 1.5zM29.8 9L2.9 35.9c-.6.6-.9 1.4-.9 2.2s.3 1.6.9 2.1l16.3 16.3c1.2 1.2 3.1 1.2 4.2 0L50 29.6V9H29.8z"
								class="st0"></path>
							<path
								d="M41.3 23.1c-2.8 0-5-2.2-5-5s2.2-5 5-5 5 2.2 5 5-2.2 5-5 5zm0-8c-1.7 0-3 1.3-3 3s1.3 3 3 3 3-1.3 3-3-1.3-3-3-3zm-34 24c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l9-9c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-9 9c-.1.2-.4.3-.7.3z"
								class="st0"></path>
							<path
								d="M51.3 19.1c-.6 0-1-.4-1-1s.4-1 1-1c.5 0 1-.1 1.5-.3.5-.2 1.1 0 1.3.5.2.5 0 1.1-.5 1.3-.6.3-1.4.5-2.3.5zm-10 0c-.6 0-1-.4-1-1v-.9c0-.5.5-1 1-1 .6 0 1 .5 1 1v.9c0 .5-.4 1-1 1zm14.2-2.4c-.2 0-.4-.1-.6-.2-.4-.3-.5-1-.2-1.4.3-.4.7-1 .9-1.6.2-.5.8-.7 1.3-.4.5.2.7.8.4 1.3-.3.7-.7 1.3-1.1 1.9-.1.3-.4.4-.7.4zm-13.9-1.5h-.1c-.5-.1-.9-.6-.9-1.1.1-.7.2-1.4.3-2 .1-.5.6-.9 1.2-.8.5.1.9.6.8 1.2-.1.6-.2 1.3-.3 1.9-.1.4-.5.8-1 .8zm16.1-3h-.3c-.5-.2-.8-.7-.7-1.3.1-.4.3-.9.4-1.3 0-.2.1-.3.1-.5.1-.5.6-.9 1.2-.8.5.1.9.6.8 1.2 0 .2-.1.4-.1.7-.1.5-.3.9-.4 1.4-.2.3-.6.6-1 .6zm-15-1.9c-.1 0-.2 0-.3-.1-.5-.2-.8-.8-.6-1.3.2-.6.4-1.2.7-1.9.2-.5.8-.7 1.3-.5.5.2.7.8.5 1.3-.3.6-.5 1.2-.7 1.8-.1.4-.5.7-.9.7zm15.4-3c-.4 0-.8-.3-1-.7-.1-.6-.4-1.1-.7-1.6-.3-.5-.1-1.1.3-1.4.5-.3 1.1-.1 1.4.3.4.7.7 1.4.9 2.1.1.5-.2 1.1-.7 1.2-.1.1-.2.1-.2.1zM44.9 5.9c-.2 0-.4-.1-.6-.2-.5-.3-.6-.9-.2-1.4.4-.6.9-1.1 1.3-1.7.4-.4 1-.4 1.4-.1.4.4.4 1 .1 1.4-.4.5-.8.9-1.2 1.5-.1.3-.5.5-.8.5zm10.3-2.6c-.2 0-.4-.1-.6-.2-.5-.3-1-.6-1.5-.8-.5-.2-.8-.8-.6-1.3.2-.5.8-.8 1.3-.6.7.2 1.4.6 2 1 .5.3.6.9.3 1.4-.2.4-.6.5-.9.5zm-6.6-.8c-.4 0-.8-.2-.9-.6-.2-.5 0-1.1.5-1.3.7-.3 1.5-.5 2.2-.6.6-.1 1 .3 1.1.9.1.5-.3 1-.9 1.1-.6.1-1.1.2-1.6.4-.2.1-.3.1-.4.1zM21.3 53.1c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l9-9c.4-.4 1-.4 1.4 0 .4.4.4 1 0 1.4l-9 9c-.1.2-.4.3-.7.3zm-8-16c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm6-2c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm7-1c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm6-2c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3zm7-1c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l5-5c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-5 5c-.1.2-.4.3-.7.3zm-4 4c-.3 0-.5-.1-.7-.3-.4-.4-.4-1 0-1.4l1-1c.4-.4 1-.4 1.4 0s.4 1 0 1.4l-1 1c-.1.2-.4.3-.7.3z"
								class="st0"></path>
						</svg>
					</div>
					<h6 class="font-weight-bold my-3">
						Check an existing voucher status & balance
					</h6>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="invoiceDetailModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header d-flex justify-content-between">
				<h3 class="font-weight-bolder modal-title">Invoice Details</h3>
				<button type="button" class="text-dark close" data-dismiss="modal"><i class="ki ki-close"></i></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="font-weight-bolder">Payment received by</label>
					<select class="form-control" name="paymenyReceivedyBy" id="paymenyReceivedyBy">
						@foreach($staffLists as $key => $val)
							<option value="{{ $val['id'] }}" @if($key == 0) selected @endif >{{ $val['first_name'].' '.$val['last_name'] }}</option>
						@endforeach	
					</select>
				</div>
				<div class="form-group">
					<label class="font-weight-bolder">Invoice notes</label>
					<textarea rows="3" id="invoice_notes" class="form-control"></textarea>
				</div>
				<div class="form-group">
					<a href="javascript:;" class="m-2 w-45 btn btn-lg btn-primary" data-dismiss="modal" id="confirmInvoiceDetails">Save</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="saveUnpaidModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header d-flex justify-content-between">
				<h3 class="font-weight-bolder modal-title">Confirm unpaid tips</h3>
				<button type="button" class="text-dark close" data-dismiss="modal"><i class="ki ki-close"></i></button>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<h5>This invoice must be fully paid in order for tips to be applied. Otherwise tips will be removed. Do you really want to proceed and remove tips?</h5>
				</div>
				<button class="btn btn-primary btn-lg px-20" id="saveUnpaidInovice">Yes, proceed</button>
			</div>
		</div>
	</div>
</div>

<div class="container-fluid p-0">
	<div class="my-custom-body-wrapper">
		<div class="my-custom-header">
			<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
				<h2 class="m-auto font-weight-bolder">Apply Payment</h2>
				<span class="d-flex">
					<p class="cursor-pointer m-0 px-6" onclick="location.href='{{ route('salesList') }}'"><i class="text-dark fa fa-times icon-lg"></i></p>
				</span>
			</div>
		</div>
		<form method="POST" id="payUnpaidInvoice" action="{{ route('payUnpaidInvoice') }}">
		@csrf
		<input type="hidden" name="invoiceId" id="invoiceId" value="{{ ($Invoice['id']) ? $Invoice['id'] : 0 }}">
		<input type="hidden" id="searchVoucherUrl" value="{{ route('searchVoucherCode') }}">	
		
		<input type="hidden" name="isUnpaid" id="isUnpaid" value="0">
		<input type="hidden" name="paymentId" id="paymentId">
		<input type="hidden" name="paymentType" id="paymentType">
		<input type="hidden" name="paymenyReceivedyByField" id="paymenyReceivedyByField">
		<input type="hidden" name="paymenyReceivedyNotes" id="paymenyReceivedyNotes">
		
		<div class="my-custom-body">
			<div class="container-fluid ">
				<div class="row m-0">
					<div class="col-12 col-sm-8 p-0 bg-light-secondary content-height" style="overflow-y: scroll; position: relative;">
						<div class="container p-12 position-relative">
							<div class="d-flex flex-column align-items-center p-10">
								<ul class="nav nav-pills round-nav" id="myTab1" role="tablist">
									@if(!empty($VoucherSold))
										<li class="nav-item font-weight-bolder">
											<a class="nav-link" id="home-tab-1" data-toggle="tab" href="#voucher">
											<span class="nav-text">Voucher</span>
											</a>
										</li>
									@endif
									@if(!empty($PlanSold))
										<li class="nav-item font-weight-bolder">
											<a class="nav-link" id="home-tab-2" data-toggle="tab" href="#paidplan">
											<span class="nav-text">Paid Plan</span>
											</a>
										</li>
									@endif
									<li class="nav-item font-weight-bolder">
										<a class="nav-link active" id="profile-tab-1" data-toggle="tab" href="#invoice" aria-controls="profile">
										<span class="nav-text">Invoice</span>
										</a>
									</li>
								</ul>
								<div class="tab-content mt-5" id="myTabContent1">
									@if(!empty($VoucherSold))
										<div class="tab-pane fade" id="voucher" role="tabpanel" aria-labelledby="home-tab-1">
										@foreach($VoucherSold as $VoucherSoldData)
									
											@php
												$locationImage = '';
												if(!empty($VoucherSoldData['location_image'])){
													$locationImage = asset($VoucherSoldData['location_image']);
												} else {
													$locationImage = asset('assets/media/bg/bg-6.jpg');
												}
											@endphp
											<div class="card mb-4 border-0 rounded">
												<div class="p-10 text-center text-white justify-content-center bgi-size-cover bgi-no-repeat" style="background-image:url({{ $locationImage }});color: #7d3be6;">
													<div class="p-4 text-center">
														<img alt=voucher-thumb class="rounded mb-4" src="{{ asset('./assets/images/thumb.jpg') }}" width="80px" height="80px" />
														<h3 class="font-weight-bold">{{ ($VoucherSoldData['location_name']) ? $VoucherSoldData['location_name'] : '' }}</h3>
														<h5 class="text-grey">{{ ($VoucherSoldData['location_address']) ? $VoucherSoldData['location_address'] : '' }}</h5>
													</div>
													<div class="border-bottom w-100 opacity-20"></div>
													<div class="my-8 vouchers-value">
														<p class="font-weight-bolder mb-0 font-size-lg">Voucher Value</p>
														<h1 class="font-weight-bolder">CA ${{ ($VoucherSoldData['price']) ? $VoucherSoldData['price'] : '' }}</h1>
													</div>
													<div class="border-bottom w-100 opacity-20"></div>
													<div class="my-8 vouchers-bottom">
														<p class="mb-4 font-size-lg">Voucher Code : <span class="font-weight-bolder font-size-lg">{{ ($VoucherSoldData['voucher_code']) ? $VoucherSoldData['voucher_code'] : '' }}</span></p>
														<p class="mb-1 font-weight-bold font-size-lg">Redeem on <span data-toggle="modal" data-target="#allServicesModal" class="font-weight-bolder cursor-pointer">all services</span> <i class="fa fa-chevron-right icon-sm"></i>
														</p>
														<p class="mb-1 font-weight-bold font-size-lg">Valid until {{ ($VoucherSoldData['end_date']) ? date("d M Y",strtotime($VoucherSoldData['end_date'])) : '' }}</p>
														<p class="mb-1 font-weight-bold font-size-lg">For multiple-use</p>
													</div>
												</div>
											</div>
										@endforeach
										</div>
									@endif
									
									@if(!empty($PlanSold))
										<div class="tab-pane fade" id="paidplan" role="tabpanel" aria-labelledby="home-tab-2">
										@foreach($PlanSold as $PlanSoldData)
									
											@php
												$locationImage = '';
												if(!empty($PlanSoldData['location_image'])){
													$locationImage = asset($PlanSoldData['location_image']);
												} else {
													$locationImage = asset('assets/media/bg/bg-6.jpg');
												}
											@endphp
											<div class="card mb-4 border-0 rounded">
												<div class="p-10 text-center text-white justify-content-center bgi-size-cover bgi-no-repeat" style="background-image:url({{ $locationImage }});color: #7d3be6;">
													<div class="p-4 text-center">
														<img alt=voucher-thumb class="rounded mb-4" src="{{ asset('./assets/images/thumb.jpg') }}" width="80px" height="80px" />
														<h3 class="font-weight-bold">{{ ($PlanSoldData['location_name']) ? $PlanSoldData['location_name'] : '' }}</h3>
														<h5 class="text-grey">{{ ($PlanSoldData['location_address']) ? $PlanSoldData['location_address'] : '' }}</h5>
													</div>
													<div class="border-bottom w-100 opacity-20"></div>
													<div class="my-8 vouchers-value">
														<p class="font-weight-bolder mb-0 font-size-lg">Voucher Value</p>
														<h1 class="font-weight-bolder">CA ${{ ($PlanSoldData['price']) ? $PlanSoldData['price'] : '' }}</h1>
													</div>
													<div class="border-bottom w-100 opacity-20"></div>
													<div class="my-8 vouchers-bottom">
														<p class="mb-4 font-size-lg">Sessions</p>
														<p class="mb-4 font-size-lg">{{ ($PlanSoldData['no_of_sessions']) ? $PlanSoldData['no_of_sessions'] : 0 }} sessions</p>
													</div>
													<div class="border-bottom w-100 opacity-20"></div>
													<div class="my-8 vouchers-bottom">
														<p class="mb-1 font-weight-bold font-size-lg">Redeem on <span data-toggle="modal" data-target="#allServicesModal" class="font-weight-bolder cursor-pointer">all services</span> <i class="fa fa-chevron-right icon-sm"></i>
														</p>
														<p class="mb-1 font-weight-bold font-size-lg">{{ ($PlanSoldData['valid_for']) ? $PlanSoldData['valid_for'] : '' }} plan</p>
														<p class="mb-1 font-weight-bold font-size-lg">Valid until {{ ($PlanSoldData['end_date']) ? date("d M Y",strtotime($PlanSoldData['end_date'])) : '' }}</p>
													</div>
												</div>
											</div>
										@endforeach
										</div>
									@endif
									
									<div class="tab-pane fade active show" id="invoice" role="tabpanel" aria-labelledby="profile-tab-1">
										<div class="card">
											<div class="card-header p-4 text-center">
												<h3>Invoice {{ $Invoice['invoice_prefix']." ".$Invoice['invoice_id'] }}</h3>
												<p class="text-muted">{{ ($Invoice['created_at']) ? date("l, d M Y",strtotime($Invoice['created_at'])) : '' }}</p>
											</div>
											<div class="card-body">
												@if(!empty($InvoiceItemsInfo))
													@foreach($InvoiceItemsInfo as $InvoiceItemsData)
														<div class="my-2 border-bottom order-detail d-flex align-items-center justify-content-between">
															<div class="detail mr-4">
																<p class="mb-0 text-muted">{{ ($InvoiceItemsData['quantity']) ? $InvoiceItemsData['quantity'] : 0 }} item</p>
																<p class="mb-0 text-dark font-size-lg">{{ ($InvoiceItemsData['main_title']) ? $InvoiceItemsData['main_title'] : 'N/A' }}</p>
																<p class="mb-0 text-muted">{{ ($InvoiceItemsData['staff_name']) ? $InvoiceItemsData['staff_name'] : 'N/A' }}</p>
															</div>
															<div class="price align-self-end">
																@if($InvoiceItemsData['item_og_price'] > $InvoiceItemsData['item_price'])
																	<h4>Ca ${{ $InvoiceItemsData['item_price'] }}</h4>	
																	<h4 style="text-decoration: line-through;">Ca ${{ $InvoiceItemsData['item_og_price'] }}</h4>	
																@else
																	<h4>Ca ${{ $InvoiceItemsData['item_og_price'] }}</h4>	
																@endif
															</div>
														</div>
													@endforeach
												@endif
												
												<div class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
													<p class="mb-0 text-dark font-size-lg">Subtotal</p>
													<h4>Ca ${{ $Invoice['invoice_sub_total'] }}</h4>
												</div>
												
												@if(!empty($InvoiceTaxes))
													@foreach($InvoiceTaxes as $InvoiceTaxData)
														<div class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
															<p class="mb-0 text-dark font-size-lg">{{ $InvoiceTaxData['tax_name'] }} {{ $InvoiceTaxData['tax_rate'] }}</p>
															<h4>Ca ${{ $InvoiceTaxData['tax_amount'] }}</h4>
														</div>
													@endforeach
												@endif
												
												<div class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
													<p class="mb-0 text-dark font-size-lg">Total</p>
													<h4>Ca ${{ $Invoice['invoice_total'] }}</h4>
												</div>
												
												@if($TotalStaffTip[0]['total_tip'] > 0)
												<div class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
													<p class="mb-0 text-dark font-size-lg">Tips</p>
													<h4>Ca ${{ $TotalStaffTip[0]['total_tip'] }}</h4>
												</div>
												@endif
												
												@if($Invoice['payment_date'] != '' && $Invoice['invoice_status'] != 0)
												<div class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
													<span>
														<p class="mb-0 text-dark font-size-lg">{{ $Invoice['payment_type'] }}</p>
															<p class="mb-0 text-muted font-size-lg">{{ ($Invoice['payment_date']) ? date("l, d M Y",strtotime($Invoice['payment_date'])) : '' }} at {{ ($Invoice['payment_date']) ? date("h:ia",strtotime($Invoice['payment_date'])) : '' }}</p>
													</span>
													<h4>Ca ${{ $Invoice['inovice_final_total'] }}</h4>
												</div>
												@endif

												<div class="paymentDetails_sec"></div>
												<div class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
													<p class="mb-0 text-dark font-size-lg">Balance</p>
													<h4 id="itemFinalTotal">Ca $<span>{{ ($Invoice['invoice_status'] == 0) ? $Invoice['inovice_final_total'] : 0 }}</span></h4>
													<input type="hidden" class="totalBalance" value="{{ ($Invoice['invoice_status'] == 0) ? $Invoice['inovice_final_total'] : 0 }}">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="side-overlay">
							<div id="dismiss">
								<i class="la la-close" style="font-size: 18px;"></i>
								<span style="display: block; margin-top: 10px; color: #fff; font-size: 14px; text-transform: uppercase; font-weight: 700; text-shadow: 0 0 4px #67768c;">CLICK
								TO CLOSE</span>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-4 p-0 content-height">
						@if(!empty($ClientInfo))
						<div class="d-flex align-items-center border-bottom p-6 customer-data" id="sidebarCollapse">
							<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
								<div class="symbol-label rounded-circle" style="background-image:url({{ asset('assets/media/users/300_13.jpg') }})">
								</div>
							</div>
							<div>
								<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">{{ $ClientInfo->firstname }} {{ $ClientInfo->lastname }}<span class="fonter-weight-bolder">*</span></a>
								<div class="text-muted">+{{ $ClientInfo->mo_country_code }} {{ $ClientInfo->mobileno }}<span class="font-weight-bolder">*</span></div>
								<div class="text-muted">{{ $ClientInfo->email }}
								</div>
							</div>
							
							@php $ClientID = (isset($ClientInfo->id) && $ClientInfo->id) ? $ClientInfo->id : '' @endphp
							
							<i class="text-dark fa fa-chevron-right ml-auto viewClient"></i>
							@if(isset($ClientID) && $ClientID != '')
							<div class="dropdown dropdown-inline viewClientInfo" style="display:none;margin-left: 185px;">
								<a href="#" class="btn btn-clean btn-hover-primary btn-sm btn-icon"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="ki ki-bold-more-hor"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
									<ul class="navi navi-hover">
										<li class="navi-item">
											<a href="{{ url('/view/'.$ClientID.'/client') }}" class="cursor-pointer navi-link">
												<span class="navi-text">Edit client details</span>
											</a>
										</li>
										<li class="navi-item">
											<a data-toggle="modal" data-target="#blockClientModal"
												class="cursor-pointer navi-link clickBlockClient" data-client_id="{{ $ClientID }}">
												<span class="navi-text">Block client</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							@endif
							
						</div>
						@else
						<div class="d-flex align-items-center border-bottom p-6 customer-data">
							<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
								<div class="symbol-label rounded-circle" style="background-image:url({{ asset('assets/media/users/300_13.jpg') }})">
								</div>
							</div>
							<div>
								<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">Walk In<span class="fonter-weight-bolder">*</span></a>
							</div>
						</div>	
						@endif	
						
						<div class="customer-bottom" style="overflow: hidden scroll;">
							<div class="customer-bottom-outer" id="cartHistory">
								<div class="view-appoinment- p-4 mb-2">
									<p class="m-0 font-weight-bolder text-center">Pay</p>
									<div class="p-6 border">
										<h2 class="mb-0 text-center font-weight-bolder" id="payamt">CA $<span>{{ $Invoice['inovice_final_total'] }}</span></h2>
									</div>
									<div class="d-flex justify-content-center my-3">
										<p class="mx-2">ScheduleDown Pay accept</p>
										<div class="mx-2" style="height: 28px;width: 28px;">
											<svg viewBox="0 0 40 13" xmlns="http://www.w3.org/2000/svg"
												class="_6pH7Dd _1d8UT3">
												<g fill="none" fill-rule="evenodd">
													<path d="M17.3117 12.5356h-3.2444L16.095.3858h3.2447l-2.028 12.1498zM11.338.3858L8.245 8.7425 7.879 6.943l.0003.0006-1.0916-5.4639s-.132-1.094-1.539-1.094H.1353l-.06.2058S1.639.9087 3.469 1.9802L6.2877 12.536H9.668L14.8297.3858H11.338zm25.5183 12.1498h2.979L37.238.3854H34.63c-1.2043 0-1.4977.9055-1.4977.9055l-4.8386 11.2447h3.382l.6763-1.8048h4.1243l.38 1.8048zm-3.57-4.2978L34.991 3.691l.959 4.5468h-2.6637zm-4.739-4.9303l.463-2.609s-1.4286-.5298-2.918-.5298c-1.61 0-5.4333.686-5.4333 4.0222 0 3.1388 4.4873 3.1778 4.4873 4.8266 0 1.6487-4.025 1.3532-5.3533.3136l-.4823 2.728s1.4486.6861 3.662.6861c2.214 0 5.554-1.1177 5.554-4.1597 0-3.159-4.5277-3.4531-4.5277-4.8265.0003-1.3738 3.16-1.1973 4.5483-.4515z" fill="#2566AF" fill-rule="nonzero"></path>
													<path d="M8 7L6.881 2.0009S6.7455 1 5.3032 1H.0615L0 1.1882s2.5194.4544 4.936 2.1567C7.2464 4.972 8 7 8 7z" fill="#E6A540" fill-rule="nonzero"></path>
													<path d="M-4-6h47v26H-4z"></path>
												</g>
											</svg>
										</div>
										<div class="mx-2" style="height: 28px;width: 28px;">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 19.2 11.7"
												enable-background="new 0 0 19.2 11.7" class="_6pH7Dd _1d8UT3">
												<path fill-rule="evenodd" clip-rule="evenodd" fill="#ff5f00"
													d="M6.6 10.4h5.7V1.2H6.6z"></path>
												<path
													d="M7.2 5.8c0-1.8.8-3.4 2.2-4.5-2.5-2-6.2-1.6-8.2 1-2 2.5-1.6 6.2 1 8.2 2.1 1.7 5.1 1.7 7.2 0C8 9.3 7.2 7.6 7.2 5.8"
													fill-rule="evenodd" clip-rule="evenodd" fill="#eb001b"></path>
												<path
													d="M19.2 10.2v-1h-.1l-.1.7-.1-.7h-.1v1h.1v-.7l.1.7.1-.7.1.7zm-.7 0v-.8h.2v-.2h-.4v.2h.2v.8z"
													fill-rule="evenodd" clip-rule="evenodd" fill="#f79e1b"></path>
												<defs>
													<filter id="a" filterUnits="userSpaceOnUse" x="9.4" y="0" width="9.4"
														height="11.7">
														<feColorMatrix values="1 0 0 0 0 0 1 0 0 0 0 0 1 0 0 0 0 0 1 0">
														</feColorMatrix>
													</filter>
												</defs>
												<mask maskUnits="userSpaceOnUse" x="9.4" y="0" width="9.4" height="11.7"
													id="b">
													<path fill-rule="evenodd" clip-rule="evenodd" fill="#fff"
														d="M9.4 0h9.5v11.7H9.4z" filter="url(#a)"></path>
												</mask>
												<path
													d="M18.9 5.8c0 3.2-2.6 5.8-5.8 5.8-1.3 0-2.6-.4-3.6-1.2 2.5-2 3-5.7 1-8.2-.3-.4-.6-.7-1-1 2.5-2 6.2-1.6 8.2 1 .8 1.1 1.2 2.3 1.2 3.6z"
													mask="url(#b)" fill-rule="evenodd" clip-rule="evenodd" fill="#f79e1b">
												</path>
											</svg>
										</div>
									</div>
									<div class="d-flex flex-wrap justify-content-center my-3">
										@foreach($paymentTypes as $val)
											<a href="javascript:;" class="m-2 w-45 btn btn-lg btn-primary applyPaymentLink" data-id="{{ $val->id }}">{{ $val->payment_type }}</a>
										@endforeach
										<a href="javascript:;" class="m-2 w-45 btn btn-lg btn-primary" data-toggle="modal" data-target="#voucherPay">Voucher</a>
									</div>
								</div>
								
								<div class="view-appoinment-footer border-top w-100 py-6">
									<div class="buttons d-flex justify-content-between">
										<div class="btn-group dropup w-100 mx-4">
											<button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">More Option</button>
											<div class="dropdown-menu dropdown-menu-lg dropdown-menu-center">
												<ul class="text-center navi flex-column navi-hover py-2">
													<li class="navi-item">
														<a href="javascript:;" id="saveUnpaid" class="navi-link">
															<span class="navi-text">Save Unpaid</span>
														</a>
													</li>
													<li class="navi-item">
														<a href="#" class="navi-link" data-toggle="modal" data-target="#invoiceDetailModal">
															<span class="navi-text">Invoice Details</span>
														</a>
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
							</div>
							
							<div id="sidebarClient" class="bg-white" style="display:none;">
								<div class="card-body p-1">
									<div class="searchClientDiv">
										@if(!empty($clientLists))	
											@foreach($clientLists as $Client)
											<div class="d-flex align-items-center border-bottom p-6 customer-data selectclient" data-cid="{{ $Client->id }}">
												<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
													<div class="symbol-label rounded-circle" style="background-image:url({{ asset('assets/media/users/300_13.jpg') }})">
													</div>
												</div>
												<div>
													<h6 class="font-weight-bolder">{{ $Client->firstname }} {{ $Client->lastname }}</h6>
													<div class="text-muted">
														@if($Client->email != '')
															{{ $Client->email }}
														@elseif($Client->mobileno != '')
															{{ $Client->mo_country_code }} {{ $Client->mobileno }}
														@endif
													</div>
												</div>
											</div>
											@endforeach
										@else
											<div class="d-flex align-items-center border-bottom p-6 customer-data">
												<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
													<div class="symbol-label rounded-circle" style="background-image:url({{ asset('assets/media/users/300_13.jpg') }})">
													</div>
												</div>
												<div>
													<div class="text-muted">No client found!</div>
												</div>
											</div>	
										@endif
									</div>	
								</div>
							</div>
									
							<div class="view-appoinment- p-4 mb-2" id="completeSale" style="display: none;">
								<p class="m-0 font-weight-bolder text-center">Full payment has been added</p>						
								<div class="d-flex flex-wrap justify-content-center my-3">
									<button type="submit" id="checkoutSubmitBtn" class="m-2 w-100 btn btn-lg btn-primary">Complete Sale</button>
									<a href="javascript:;" class="m-2 w-100 btn btn-lg btn-primary" id="applyPaymentBack">Back to payments</a>
								</div>
							</div>
												
							<div id="clientHistory" class="bg-white" style="display:none;">
								<div class="card-body p-1">
									<div class="total-appoinment-data justify-content-around d-flex">
										<div class="text-center w-100 data pt-1 p-42">
											<h3 class="price font-weight-bolder text-center text-dark">{{ count($PreviousServices) }}</h3>
											<p class="title text-muted">Total Booking</p>
										</div>
										<div class=" text-center w-100 data pt-1 p-2">
											<h3 class="price font-weight-bolder text-center text-dark">CA ${{ $TotalSpend }}</h3>
											<p class="title text-muted">Total Sales</p>
										</div>
									</div>
									<ul class="nav nav-tabs nav-tabs-line nav-tabs-primary nav-tabs-line-2x" role="tablist">
										<li class="nav-item">
											<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link active"
												data-toggle="tab" href="#appointments">Appointments
												({{ count($PreviousServices) }})</a>
										</li>
										<li class="nav-item">
											<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link"
												data-toggle="tab" href="#consultationforms">Products ({{ count($soldProduct) }})</a>
										</li>
										<li class="nav-item">
											<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link"
												data-toggle="tab" href="#products">Invoices ({{ count($clientInvoices) }})</a>
										</li>
										<li class="nav-item">
											<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link"
												data-toggle="tab" href="#invoices">Info</a>
										</li>
									</ul>
									<div class="tab-content mt-5" id="myTabContent">
										<div class="tab-pane fade show active" id="appointments" role="tabpanel"
											aria-labelledby="appointments">
											<div class="row">
												<div class="card-body py-2 px-8">
													@if(!empty($PreviousServices))
														@foreach($PreviousServices as $PreviousServiceData)
															<div class="client-apoinments-list mb-6">
																<div class="d-flex align-items-center flex-grow-1">
																	<h6 class="font-weight-bolder text-dark">{{ $PreviousServiceData['appointment_date_month'] }}</h6>
																	<div
																		class="d-flex flex-wrap align-items-center justify-content-between w-100">
																		<div class="d-flex flex-column align-items-cente py-2 w-75">
																			<h6 class="text-muted font-weight-bold">
																				{{ $PreviousServiceData['appointment_date_hours'] }} <a class="text-blue" href="#">New</a>
																			</h6>
																			<a href="#"
																				class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg mb-1">{{ $PreviousServiceData['serviceName'] }}</a>
																			<span class="text-muted font-weight-bold">{{ $PreviousServiceData['duration'] }} with <i class="fa fa-heart text-danger"></i>
																				{{ $PreviousServiceData['staff_name'] }}
																			</span>
																		</div>
																		<h6 class="font-weight-bolder py-4">CA ${{ $PreviousServiceData['special_price'] }}</h6>
																	</div>
																</div>
															</div>
														@endforeach
													@else
														<div class="client-apoinments-list mb-6">
															<center>No services found.</center>
														</div>
													@endif
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="consultationforms" role="tabpanel"
											aria-labelledby="consultationforms">
											<?php echo $ClientProducts ?>
										</div>
										<div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products">
											<div class="col-12 col-md-12">
												<table class="table table-hover">
													<thead>
														<tr>
															<th>Status</th>
															<th>Invoice#</th>
															<th>Invoice date</th>
															<th>Total</th>
														</tr>
													</thead>
													<tbody>
														<?php echo $ClientInovices ?>
													</tbody>
												</table>
											</div>
										</div>
										<div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices">
											<h6>Email</h6>
											<h6>{{ (isset($ClientInfo->email)) ? $ClientInfo->email : '' }}</h6>
											<br>
											<h6>Gender</h6>
											<h6>{{ (isset($ClientInfo->gender)) ? $ClientInfo->gender : '' }}</h6>
											<br>
											<h6>Marketing notifications</h6>
											
											@if(isset($ClientInfo->accept_marketing_notification) && $ClientInfo->accept_marketing_notification == 1)
												<h6>Accepts marketing notifications</h6>
											@else
												<h6>Not accepts marketing notifications</h6>
											@endif
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		</form>
	</div>
</div>

<!--end::Content-->
@endsection
{{-- Scripts Section --}}
@section('scripts')	
<script src="{{ asset('js/application.js') }}"></script>
<script src="{{ asset('js/add-appointment.js') }}"></script>
<script>
$('#dismiss, .side-overlay').on('click', function () {
	$('#sidebarClient').hide();
	$('#selectedClient').css('display','flex !important;');
	$('#cartHistory').show();	
	$('#clientHistory').hide();
	$('.side-overlay').removeClass('active');
	
	$(".viewClient").show();
	$(".viewClientInfo").hide();
});

$('#searchClient').on('click', function () {
	$('#sidebarClient').show();
	$('#selectedClient').css('display','none !important;');
	$('#cartHistory').hide();
	$('#clientHistory').hide();
	$('.side-overlay').addClass('active');
});

$('#sidebarCollapse').on('click', function () {
	$('#sidebar').addClass('active');
	$('.side-overlay').addClass('active');
	
	$(".viewClient").hide();
	$(".viewClientInfo").show();
	$("#clientHistory").show();
	$("#cartHistory").hide();
});

$(document).ready(function(){

		
	$(document).on('click','#redeem_voucher',function(e){
		$("#voucherPay").modal('hide');
		var redeemed_amount = parseFloat($("#redeemed_amount").val());
		var redemption_amount = parseFloat($("#redemption_amount").val());
		var itemTotal = $(".itemTotal").val();
		var code = $("#voucher_code").val();
		var totalBalance = $(".totalBalance").val();
		
		if(redemption_amount > 0) {
			if(redemption_amount > redeemed_amount) {
				var disc_amt = redeemed_amount.toFixed(2);
			} else {
				var disc_amt = redemption_amount.toFixed(2);
			}		
			
			var balance = (totalBalance - disc_amt);
			$(".totalBalance").val(balance);
			$("#itemFinalTotal span").html(balance);
			$("#payamt span").html(balance);
			
			if(balance == 0) {
				$(".searchbarSec").hide();
				$("#cartHistory").hide();
				$("#selectedClient").css("display", "flex");
				$("#completeSale").show();
			}	
			
			var vhtml = '';
			vhtml += '<div class="text-blue border-bottom py-4 d-flex flex-wrap justify-content-between paymentSec'+code+'">';
				vhtml += '<h5 class="cursor-pointer">Voucher ('+code+')</h5>';
				vhtml += '<div class="d-flex align-items-center"><h5 class="cursor-pointer m-0">CA $'+disc_amt+'</h5></div>';
				vhtml += '<div class="d-flex align-items-center removePayment" data-id="'+code+'"><i class="ml-2 fa fa-times text-danger fa-1x"></i></div>';
				vhtml += '<input type="hidden" name="voucher_id[]" value="">';
				vhtml += '<input type="hidden" name="voucher_code[]" value="'+code+'">';
				vhtml += '<input type="hidden" name="payment_amt[]" value="'+disc_amt+'">';
			vhtml += '</div>';
			$(".paymentDetails_sec").append(vhtml);
		}	
	});
		
});
</script>
@if(Session::has('message'))
<script>
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

	toastr.success('{{ Session::get('message') }}');
</script>
@endif
@endsection