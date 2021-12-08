{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')
<style type="text/css">
	.specialprice-txt {
		text-decoration: line-through;
		color: #988585;
	}
</style>
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
					<a href="javascript:;" class="m-2 w-45 btn btn-lg btn-primary" data-dismiss="modal">Save</a>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="addTipModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header d-flex justify-content-between">
				<h3 class="font-weight-bolder modal-title">Invoice Details</h3>
				<button type="button" class="text-dark close" data-dismiss="modal"><i class="ki ki-close"></i></button>
			</div>
			<div class="modal-body">
				<div class="form-group d-flex align-items-center w-100">
					<div class="w-70">
						<label class="font-weight-bolder">Tip Amount</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text bg-white isPrice">CA $</span>
								<span class="input-group-text bg-white isPercent" style="display: none;">%</span>
							</div>
							<input class="form-control" id="discount-input" name="discount-type" value="0">
						</div>
					</div>
					<div class="ml-4 mt-6">
						<div class="tgl-radio-tabs">
							<input id="price" value="1" checked="" type="radio" class="form-check-input tgl-radio-tab-child" name="discount-type">
							<label for="price" class="radio-inline">CA $</label>
							<input id="percent" value="2" type="radio" class="form-check-input tgl-radio-tab-child" name="discount-type">
							<label for="percent" class="radio-inline">%</label>
						</div>
					</div> 
				</div>
				<div class="form-group">
					<label class="font-weight-bolder">Staff Tipped</label>
					<select class="form-control" id="staffTipped">
						@foreach($staffLists as $key => $val)
							<option value="{{ $val['id'] }}" @if($key == 0) selected @endif >{{ $val['first_name'].' '.$val['last_name'] }}</option>
						@endforeach	
					</select>
				</div>
				<button class="btn btn-primary btn-lg px-20" id="saveTip">Save</button>
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

<div class="modal fade" id="addItemToSale">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header d-flex justify-content-between">
				<a class="text-primary modal-back cursor-pointer" data-id="opt"><i class="fa fa-chevron-left icon-lg text-primary"></i></a>
				<h3 class="font-weight-bolder modal-title" style="margin: 0 auto;">Select Item</h3>
				<button type="button" class="text-dark close" data-dismiss="modal"><i class="ki ki-close"></i></button>
			</div>
			<div class="modal-body">
				<div class="" style="display: block;">
					<!-- <div class="form-group">
						<div class="input-icon input-icon-right">
							<input type="text" class="rounded-0 form-control" placeholder="Scan barcode or search any item">
							<span><i class="flaticon2-search-1 icon-md"></i></span>
						</div>
					</div> -->
					<ul class="list-group saleoptions">
						<li type="button" class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary productcategory">
							Products<i class="fa fa-chevron-right"></i>
						</li>
						<li type="button" class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary servicecategory">
							Services<i class="fa fa-chevron-right"></i>
						</li>
						<li type="button" class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary viewvoucher">
							Vouchers<i class="fa fa-chevron-right"></i>
						<!-- </li>
						<li type="button" class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary viewpaidplans">
							Paid plans<i class="fa fa-chevron-right"></i>
						</li> -->
					</ul>
					
					<div class="service_sec" style="display: none;">
						<ul class="list-group">
							@foreach($serviceCategory as $category)
								<li type="button" class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary getservice" data-id="{{ $category->id }}">{{ $category->category_title }}</li>
							@endforeach
						</ul>
					</div>
					<div class="product_sec" style="display: none;">
						<ul class="list-group">
							@foreach($productCategory as $category)
								<li type="button" class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary getproduct" data-id="{{ $category->id }}">{{ $category->category_name }}</li>
							@endforeach
							<li type="button" class="d-flex justify-content-between align-items-center list-group-item list-group-item-action text-primary getproduct" data-id="0">No Category</li>
						</ul>
					</div>
					<!-- <div class="paidplan_sec" style="display: none;">
						@forelse($paidPlanLists as $key => $val)
							<div class="card service-paid-plan-card my-2 additemcheckout" data-type="paidplan" data-id="{{ $val->id }}">
								<div class="card-body text-white p-6">
									<div class="d-flex my-3">
										<span>
											<i class="mr-2 fas fa-business-time icon-lg text-white"></i>
										</span>
										<h6>{{ $val->valid_for }} plan</h6>
									</div>
									<h2 class="mb-6 font-weight-bolder">{{ $val->name }}</h2>
									<div class="font-weight-bold d-flex justify-content-between">
										<div>
											<h5>@if($val->sessions == 0) {{ $val->sessions_num }} @else Unlimited @endif</h5>
											<h5>{{ count(explode(",", $val->services_ids)) }} service</h5>
										</div>
										<div>
											<h5>CA ${{ $val->price }}</h5>
										</div>
									</div>
								</div>
							</div>
						@empty
							<li class="d-flex justify-content-between align-items-center text-primary list-group-item list-group-item-action" >No paid plans found</li>
						@endforelse
					</div> -->
					<div class="voucher_sec" style="display: none;">
						<ul class="list-group">
							<ul class="nav nav-pills round-nav mx-auto mb-3">
								<li class="nav-item">
									<a class="nav-link active" data-toggle="pill" href="#chooseVoucher">Choose Voucher</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" data-toggle="pill" href="#createVoucher">Create Voucher</a>
								</li>
							</ul>
							<div class="tab-content w-100">
								<div class="tab-pane container active" id="chooseVoucher">
									@forelse($voucherLists as $key => $val)
										<div class="my-2 card voucher-card additemcheckout" data-type="voucher" data-id="{{ $val->id }}">
											<div class="card-body text-white p-6">
												<div class="my-3 text-center">
													<h6 class="font-weight-bold">Voucher value</h6>
													<h2 class="font-weight-bolder">CA ${{ $val->value }}</h2>
												</div>
												<div class="mt-10 font-weight-bold d-flex justify-content-between">
													<div>
														<h5 class="font-weight-bolder">{{ $val->name }}</h5>
														<h5>Redeem on {{ count(explode(",", $val->services_ids)) }} services</h5>
													</div>
													<div class="text-right">
														<h6 class="font-weight-bolder">CA ${{ $val->retailprice }}</h6>
														@if($val->value > $val->retailprice)
															<h5 class="bagde badge-secondary p-1 rounded text-uppercase">
																Save {{ round((($val->value - $val->retailprice)*100) / $val->value) }}%
															</h5>
														@endif
														@if($val->enable_sale_limit == 1)
															<h6 class="font-weight-bolder">Sold 0/{{ $val->numberofsales }}</h6>
														@else 
															<h6 class="font-weight-bolder">Sold 0</h6>
														@endif
													</div>
												</div>
											</div>
										</div>
									@empty
										<li class="d-flex justify-content-between align-items-center text-primary list-group-item list-group-item-action" >No voucher found</li>
									@endforelse		
								</div>

								<div class="tab-pane container fade" id="createVoucher">
									<form id="createVoucherFrm" action="{{ route('createSaleVoucher') }}" method="POST">
										@csrf
										<div class="form-group" style="position: relative;">
											<label class="font-weight-bolder">Included services</label>
											<input type="text" id="serviceInput" readonly="" class="form-control form-control-lg" placeholder="All services" data-toggle="modal" data-target="#servicesModal" style="cursor: pointer;" value="All Service Selected	">
											<a href="" class="chng_popup" data-toggle="modal" data-target="#servicesModal">Edit</a>
										</div>
										<div class="form-group mr-2">
											<label class="font-weight-bolder">Value</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">CA $</span>
												</div>
												<input type="text" id="crt_voucher_value" name="crt_voucher_value" class="form-control">
											</div>
										</div>
										<div class="form-group mr-2">
											<label class="font-weight-bolder">Retail price</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text">CA $</span>
												</div>
												<input type="text" id="crt_voucher_price" name="crt_voucher_price" class="form-control">
											</div>
										</div>
										<div class="form-group">
											<label class="font-weight-bolder">Valid For</label>
											<select class="form-control" id="maxNumberOfSales" name="maxNumberOfSales">
												<option value="14 days">14 days</option>
												<option value="1 month">1 month</option>
												<option value="2 month">2 months</option>
												<option value="3 month">3 months</option>
												<option value="6 month">6 months</option>
												<option value="1 year">1 year</option>
												<option value="3 year">3 years</option>
												<option value="5 year">5 years</option>
												<option value="never">Forever</option>
											</select>
										</div>
										<div class="form-group mr-2">
											<label class="font-weight-bolder">Voucher name</label>
											<input type="text" id="crt_voucher_name" name="crt_voucher_name" class="form-control" value="Gift Voucher">
										</div>
										
										<div class="form-group mr-2">
											<button type="submit" id="submitVoucher" class="m-2 w-100 btn btn-lg btn-primary" >Submit</button>
										</div>
									</form>
								</div>
							</div>
						</ul>
					</div>
					<div class="itemresult" style="display: none;">
						<ul class="list-group">
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="servicesModal" aria-hidden="true" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header d-flex justify-content-between">
				<h4 class="modal-title">Select services</h4>
				<button type="button" class="text-dark close" data-dismiss="modal">×</button>
			</div>
			<div class="modal-body">
				<!-- <div class="form-group">
					<div class="input-icon input-icon-right">
						<input type="text" class="rounded-0 form-control" placeholder="Scan barcode or search any item">
						<span><i class="flaticon2-search-1 icon-md"></i></span>
					</div>
				</div> -->
				<hr class="m-0">

				<div class="multicheckbox">
					<ul id="treeview">
						<li>
							<label for="all" class="checkbox allService">
								<input type="checkbox" checked name="all" id="all">
								<span></span>
								All Services
							</label>
							<ul>
								<li>
									@php 
										$totalService = 0;
									@endphp
									@foreach($allServiceListing as $value)
										<ul>
											<li>
												<label for="all-{{ $value->category_title }}" class="checkbox">
													<input type="checkbox" name="all-{{ $value->category_title }}" checked id="all-{{ $value->category_title }}">
													<span></span>
													{{ $value->category_title }}
												</label>
												<ul>
													@php
														$i=1;
													@endphp
													@foreach($value->service as $service)
														<li>
															<label for="all-{{ $value->category_title }}-{{ $i }}" class="checkbox">
																<input type="checkbox" name="value_checkbox[]" checked value="{{ $service->id }}" id="all-{{ $value->category_title }}-{{ $i }}">
																<span></span>
																<div class="d-flex align-items-center w-100">
																	<span class="m-0">
																		{{ $service->service_name }}
																		<p class="m-0 text-muted">@foreach($service->servicePrice as $value){{ $value->duration }},@endforeach	</p>
																	</span>
																	<span class="ml-auto">
																		@foreach($service->servicePrice as $value)CA ${{ $value->price }}@endforeach
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
										</ul>
									@endforeach
									<input type="hidden" id="totalService" value="{{ $totalService }}">
								</li>
							</ul>
						</li>
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

<div class="p-4 d-flex justify-content-between" id="title-header">
	<h2 class="m-auto font-weight-bolder">Checkout</h2>
	<h1 class="cursor-pointer" onclick="history.back();">&times;</h1>
</div>

<hr class="m-0"/>
<div class="container-fluid p-0">
	<form id="checkoutfrm" action="{{ route('saveCheckoutItem') }}" method="POST">
		@csrf 
		<input type="hidden" name="locationId" id="locationId" value="{{ $locationId }}">
		<input type="hidden" name="paymentId" id="paymentId" value="">
		<input type="hidden" name="paymentType" id="paymentType" value="">
		<input type="hidden" name="clientId" id="clientId" value="{{(isset($ClientInfo) && !empty($ClientInfo)) ? $ClientInfo->id : '' }}">
		<input type="hidden" id="getCustomerInformation" value="{{ route('getCustomerInformation') }}">
		<input type="hidden" id="searchCustomerUrl" value="{{ route('searchCustomers') }}">
		<input type="hidden" id="searchVoucherUrl" value="{{ route('searchVoucherCode') }}">
		
		<input type="hidden" name="encryptAppId" id="encryptAppId" value="{{ $encryptAppId }}">
		<input type="hidden" name="decryptAppId" id="decryptAppId" value="{{ $decryptAppId }}">
		
		<div class="row m-0">
			<div class="col-12 col-sm-8 p-0 bg-content content-height" style="overflow-y: scroll; position: relative;">
				<div class="container p-12 position-relative">
					@php $is_empty_view = 0; @endphp
					<div id="salesItems">
						@if(!empty($ClientServices))
							@php $is_empty_view = 1; @endphp
							@foreach($ClientServices as $ClientServiceData)	
								<div class="card serviceItm cardId{{ $ClientServiceData['uniqid'] }}" data-id="{{ $ClientServiceData['uniqid'] }}">
									<div class="card-body border-left border-primary border-3">
										<div class="row flex-wrap justify-content-between">
											<div class="d-flex">
												<h4 class="m-1 p-4 fonter-weight-bolder">1</h4>
												<div>
													<h3 class="m-0">{{ $ClientServiceData['service_name'] }}</h3>
													<p class="text-dark-50 itmduration-txt{{ $ClientServiceData['uniqid'] }}">{{  $ClientServiceData['duration'] }} with {{ $ClientServiceData['staff_name'] }}</p>
												</div>
											</div>
											<div class="d-flex flex-wrap">
												@php 
													$itempr = ""; $displacls = ""; $txtreadonly = "";
													if($ClientServiceData['special_price'] > 0) {
														$itempr = $ClientServiceData['special_price'];
														$txtreadonly = "readonly";
													} else {
														$displacls = "d-none";
														$itempr = $ClientServiceData['service_price'];
													}
												@endphp
												<div>
													<h3 class="m-0 itmpr-txt{{ $ClientServiceData['uniqid'] }}">CA $<span>{{ $itempr }}</span></h3>
													<h5 class="m-0 text-dark-50 itmspr-txt{{ $ClientServiceData['uniqid'] }} {{ $displacls }}"><s>CA $<span>{{ $ClientServiceData['service_price'] }}</span></s></h5>
												</div>
												<i class="fa fa-times cursor-pointer text-danger fa-1x mt-2 ml-3 removeItem" data-uid="{{ $ClientServiceData['uniqid'] }}"></i>
											</div>
											<input type="hidden" name="item_id[]" value="{{ $ClientServiceData['service_id'] }}">
											<input type="hidden" name="item_type[]" class="itemtype{{ $ClientServiceData['uniqid'] }}" value="services">
											<input type="hidden" name="item_og_price[]" class="itemogprice{{ $ClientServiceData['uniqid'] }}" value="{{ $ClientServiceData['service_price'] }}">
											<input type="hidden" name="item_price[]" class="itmpr-hd itemprice{{ $ClientServiceData['uniqid'] }}" data-id="{{ $ClientServiceData['uniqid'] }}" value="{{ $itempr }}">
											<!-- <input type="hidden" name="item_tax_rate[]" class="itmpr-hd item_tax_rate{{ $ClientServiceData['uniqid'] }}" data-id="{{ $ClientServiceData['uniqid'] }}" value="">
											<input type="hidden" name="item_tax_amount[]" class="itmpr-hd item_tax_amount{{ $ClientServiceData['uniqid'] }}" data-id="{{ $ClientServiceData['uniqid'] }}" value=""> -->
											<input type="hidden" name="appointment_services_id[]" value="{{ $ClientServiceData['appointment_service_id'] }}">
											<input type="hidden" name="item_discount_price[]" class="itemdiscprice{{ $ClientServiceData['uniqid'] }}" value="0">
											<input type="hidden" name="item_discount_text[]" class="itemdisctxt{{ $ClientServiceData['uniqid'] }}" value="">
										</div>
										<div class="row px-8">
											<div class="col-lg-2 col-md-6 col-sm-6">
												<div class="form-group">
													<label class="font-weight-bolder">Quantity</label>
													<input class="form-control qtinpt{{ $ClientServiceData['uniqid'] }}" readonly value="1" name="quantity[]" type="text">
												</div>
											</div>
											<div class="col-lg-4 col-md-6 col-sm-6" id="pricing-type">
												<div class="form-group">
													<label class="font-weight-bolder">Price</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text">CA $</span>
														</div>
														<input type="text" class="form-control itmpr_inpt itmpr-inpt{{ $ClientServiceData['uniqid'] }}" {{ $txtreadonly }} value="{{ $itempr }}" placeholder="0.00" data-uid="{{ $ClientServiceData['uniqid'] }}">
													</div>
												</div>
											</div>
											<div class="col-lg-3 col-md-6 col-sm-6" id="staff">
												<div class="form-group">
													<label class="font-weight-bolder">Staff</label>
													<select class="form-control staff-list stflist{{ $ClientServiceData['uniqid'] }}" data-uid="{{ $ClientServiceData['uniqid'] }}" name="staff_id[]" style="padding-right: 25px;">
														@if(!empty($ClientServiceData['staff_data']))
															@foreach($ClientServiceData['staff_data'] as $StaffData)
																<option value="{{ $StaffData['staff_id'] }}" data-pr="{{ $StaffData['staff_price'] }}" data-spr="{{ $StaffData['staff_special_price'] }}" data-dur="{{ $StaffData['staff_duration'] }}" @if($StaffData['staff_user_id'] == $ClientServiceData['staff_user_id']) selected @endif>{{ $StaffData['first_name'] }} {{ $StaffData['last_name'] }}</option>
															@endforeach
														@else
															<option value="">No Staff Members Found.</option>	
														@endif	
													</select>
												</div>
											</div>
											<div class="col-lg-3 col-md-6 col-sm-6" id="spc-discount">
												<div class="form-group">
													<label class="font-weight-bolder">Discount</label>
													<select class="form-control item-discount disclist{{ $ClientServiceData['uniqid'] }}" name="discount_id[]" data-uid="{{ $ClientServiceData['uniqid'] }}" style="padding-right: 25px;">
														<option value="">No Discount</option>
														@foreach($ClientServiceData['discount_data'] as $key1 => $val)
															@if($val['prType'] == 0)
																<option value="{{ $val['id'] }}" data-type="{{ $val['prType'] }}" data-amt="{{ $val['value'] }}" >{{ $val['name'].' '.$val['value'] }}% off</option>
															@else
																<option value="{{ $val['id'] }}" data-type="{{ $val['prType'] }}" data-amt="{{ $val['value'] }}">{{ $val['name'].' CA $'.$val['value'] }} off</option>';
															@endif
														@endforeach	
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
							@endforeach
						@endif
						
						@if(!empty($voucherData))
							@php 
								$is_empty_view = 1; 
								$valid_on = date("d M, Y", strtotime($voucherData->validfor));						
							@endphp
							<div class="card voucherItm cardId{{ $voucherData->uniqid }}" data-id="{{ $voucherData->uniqid }}">
								<div class="card-body border-left border-primary border-3">
									<div class="row flex-wrap justify-content-between">
										<div class="d-flex">
											<h4 class="m-1 p-4 fonter-weight-bolder">1</h4>
											<div>
												<h3 class="m-0">CA ${{ $voucherData->value.' - '.$voucherData->name }}</h3>
												<p class="text-dark-50">Expires on {{ $valid_on }}</p>
											</div>
										</div>
										<div class="d-flex flex-wrap">
											@php 
												$itempr = $voucherData->retailprice;
											@endphp
											<div>
												<h3 class="m-0 itmpr-txt{{ $voucherData->uniqid }}">CA $<span>{{ $itempr }}</span></h3>
												<h5 class="m-0 text-dark-50 itmspr-txt{{ $voucherData->uniqid }} d-none"><s>CA $<span>{{ $voucherData->service_price }}</span></s></h5>
											</div>
											<i class="fa fa-times cursor-pointer text-danger fa-1x mt-2 ml-3 removeItem" data-uid="{{ $voucherData->uniqid }}"></i>
										</div>
										<input type="hidden" name="item_id[]" value="{{ $voucherData->id }}">
										<input type="hidden" name="item_type[]" class="itemtype{{ $voucherData->uniqid }}" value="voucher">
										<input type="hidden" name="item_og_price[]" class="itemogprice{{ $voucherData->uniqid }}" value="{{ $itempr }}">
										<input type="hidden" name="item_price[]" class="itmpr-hd itemprice{{ $voucherData->uniqid }}" data-id="{{ $voucherData->uniqid }}" value="{{ $itempr }}">
										<input type="hidden" name="appointment_services_id[]" value="0">
										<input type="hidden" name="item_discount_price[]" class="itemdiscprice{{ $voucherData->uniqid }}" value="0">
										<input type="hidden" name="item_discount_text[]" class="itemdisctxt{{ $voucherData->uniqid }}" value="">
									</div>
									<div class="row px-8">
										<div class="col-md-1 col-sm-6">
											<div class="form-group">
												<label class="font-weight-bolder">Quantity</label>
												<input class="form-control qtinpt{{ $voucherData->uniqid }}" value="1" name="quantity[]" type="text">
											</div>
										</div>
										<div class="col-md-3 col-sm-6" id="pricing-type">
											<div class="form-group">
												<label class="font-weight-bolder">Price</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text">CA $</span>
													</div>
													<input type="text" class="form-control itmpr_inpt itmpr-inpt{{ $voucherData->uniqid }}" value="{{ $itempr }}" placeholder="0.00" data-uid="{{ $voucherData->uniqid }}">
												</div>
											</div>
										</div>
										<div class="col-md-4 col-sm-6" id="staff">
											<div class="form-group">
												<label class="font-weight-bolder">Staff</label>
												<select class="form-control" name="staff_id[]">
													@if(!empty($staff_data))
														@foreach($staff_data as $StaffData)
															<option value="{{ $StaffData['staff_id'] }}">{{ $StaffData['first_name'] }} {{ $StaffData['last_name'] }}</option>
														@endforeach
													@else
														<option value="">No Staff Members Found.</option>	
													@endif
												</select>
											</div>
										</div>
										<div class="col-md-4 col-sm-6" id="spc-discount">
											<div class="form-group">
												<label class="font-weight-bolder">Discount</label>
												<select class="form-control item-discount disclist{{ $voucherData->uniqid }}" name="discount_id[]" data-uid="{{ $voucherData->uniqid }}">
													<option value="">No Discount</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div>
						@endif
					</div>
					<div class="row my-4 p-4 additemsale_sec" @if($is_empty_view == 0) style="display: none;" @endif>
						<div class="col-12 col-sm-12 col-md-6">
							<h5 data-toggle="modal" data-target="#addItemToSale" class="cursor-pointer text-blue"><i class="fa fa-plus text-blue mr-2"></i>Add item to sale</h5>
						</div>
						<div class="col-12 col-sm-12 col-md-6">
							<div>
								<div class="border-bottom d-flex flex-wrap justify-content-between">
									<h5>Subtotal</h5>
									<h5 class="mr-5" id="itemSubTotal">CA $<span>0</span></h5>
								</div>
								<div class="tax_sec"></div>	
								<div class="taxes-list">
									<!-- <input type="hidden" name="taxFormula" id="taxFormula" value="{{ $taxFormula }}">
									@forelse($serviceTaxes as $tax)
										<input type="hidden" class="serviceTax" value="{{ $tax['tax_rates'] }}" data-id="{{ $tax['service_default_tax'] }}" data-name="{{ $tax['tax_name'] }}">
									@empty	
									@endforelse	
									
									@forelse ($productTaxes as $tax)
										<input type="hidden" class="productTax" value="{{ $tax['tax_rates'] }}" data-id="{{ $tax['poducts_default_tax'] }}" data-name="{{ $tax['tax_name'] }}">
									@empty	
									@endforelse	 -->
								</div>		
								<div class="border-bottom d-flex flex-wrap justify-content-between">
									<h5>Total</h5>
									<h5 class="mr-5" id="itemTotal">CA $<span>0</span></h5>
								</div>
								<div class="stafftip_sec border-top border-dark"></div>	
								<div class="addtip_sec border-dark">
									<h6 class="py-3 cursor-pointer text-blue addTipMdl"><i class="text-blue fa fa-plus mr-2"></i>Add tip</h6>
								</div>
								<div class="paymentDetails_sec"></div>	
								<div class="my-4 font-weight-bolder d-flex flex-wrap justify-content-between border-top border-dark">
									<h4>Balance</h4>
									<h4 class="mr-5" id="itemFinalTotal">CA $<span>0</span></h4>
									<input type="hidden" class="itemSubTotal" name="itemsubtotal" value="">
									<input type="hidden" class="itemTotal" name="itemtotal" value="">
									<input type="hidden" class="itemFinalTotal" name="itemfinaltotal" value="">
									<input type="hidden" class="isRemoveTip" name="remove_tip" value="0">
									<input type="hidden" class="totalBalance" value="">
								</div>
							</div>
						</div>
					</div>
					
					<div class="@if($is_empty_view == 0) d-flex @else d-none1 @endif flex-column align-items-center p-10 emptycart" style=" @if($is_empty_view == 1) display: none; @endif">
						<div style="width: 50px;height:50px;">
							<svg viewBox="0 0 56 58" xmlns="http://www.w3.org/2000/svg">
								<g fill-rule="nonzero">
									<path d="M49 20c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h6c.6179394 0 1.0879825.5548673.9863939 1.164399l-3.4430553 20.6583318C52.1408079 42.2329143 50.0545619 44 47.611 44H13c-.4888392 0-.9060293-.353413-.9863939-.835601l-4.00000002-24C7.91201746 18.5548673 8.38206056 18 9 18h8c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-6.81953958l3.66666668 22H47.611c1.46605 0 2.7181281-1.0605314 2.9596061-2.506399L53.8195396 20H49z">
									</path>
									<path d="M50 48c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1H13.828C11.7142153 50 10 48.2857847 10 46.171c0-1.014735.40298826-1.9878479 1.1211951-2.7074085l1.172-1.171c.3906909-.3903576 1.0238558-.3900874 1.4142134.0006036.3903576.3906909.3900874 1.0238558-.0006036 1.4142134l-1.171032 1.1700315C12.1925918 45.2212686 12 45.6863283 12 46.171c0 1.0102153.8187847 1.829 1.828 1.829H50zM1 12c-.55228475 0-1-.4477153-1-1 0-.55228475.44771525-1 1-1h4.438c1.3765066 0 2.57649505.93687534 2.9111513 2.2724996l1.621 6.485c.13392929.5357997-.19185111 1.0787224-.72765087 1.2126517-.53579977.1339293-1.07872244-.1918511-1.21265173-.7276509l-1.62086284-6.4844519C6.2973771 12.3126144 5.8969677 12 5.438 12H1zM46 30c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-2c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h2zM20 30c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-2c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h2zM46 36c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4zM38 36c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4zM30 36c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4zM38 30c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4zM30 30c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4zM22 36c.5522847 0 1 .4477153 1 1s-.4477153 1-1 1h-4c-.5522847 0-1-.4477153-1-1s.4477153-1 1-1h4z">
									</path>
									<path d="M41 49c0-.5522847.4477153-1 1-1s1 .4477153 1 1v2c0 .5522847-.4477153 1-1 1s-1-.4477153-1-1v-2z">
									</path>
									<path d="M44 54c0-1.1047153-.8952847-2-2-2s-2 .8952847-2 2 .8952847 2 2 2 2-.8952847 2-2zm2 0c0 2.2092847-1.7907153 4-4 4s-4-1.7907153-4-4 1.7907153-4 4-4 4 1.7907153 4 4zM17 49c0-.5522847.4477153-1 1-1s1 .4477153 1 1v2c0 .5522847-.4477153 1-1 1s-1-.4477153-1-1v-2z">
									</path>
									<path d="M20 54c0-1.1047153-.8952847-2-2-2s-2 .8952847-2 2 .8952847 2 2 2 2-.8952847 2-2zm2 0c0 2.2092847-1.7907153 4-4 4s-4-1.7907153-4-4 1.7907153-4 4-4 4 1.7907153 4 4zM44 13c0-6.07471525-4.9252847-11-11-11S22 6.92528475 22 13c0 6.0747153 4.9252847 11 11 11s11-4.9252847 11-11zm2 0c0 7.1792847-5.8207153 13-13 13s-13-5.8207153-13-13c0-7.17928475 5.8207153-13 13-13s13 5.82071525 13 13z">
									</path>
									<path d="M36.2928932 8.29289322c.3905243-.39052429 1.0236893-.39052429 1.4142136 0 .3905243.39052429.3905243 1.02368927 0 1.41421356l-8 8.00000002c-.3905243.3905243-1.0236893.3905243-1.4142136 0-.3905243-.3905243-.3905243-1.0236893 0-1.4142136l8-7.99999998z">
									</path>
									<path d="M28.2928932 9.70710678c-.3905243-.39052429-.3905243-1.02368927 0-1.41421356.3905243-.39052429 1.0236893-.39052429 1.4142136 0l8 7.99999998c.3905243.3905243.3905243 1.0236893 0 1.4142136-.3905243.3905243-1.0236893.3905243-1.4142136 0l-8-8.00000002z">
									</path>
								</g>
							</svg>
						</div>
						<h6 class="my-4 font-weight-bolder">Your order is empty. You haven't added any items yet</h6>
						<button class="btn btn-primary px-20" type="button" data-toggle="modal" data-target="#addItemToSale">Add item to sale</button>
					</div>
				</div>
				
				<div class="side-overlay">
					<div id="dismiss">
						<i class="la la-close" style="font-size: 18px;"></i>
						<span style="display: block; margin-top: 10px; color: #fff; font-size: 14px; text-transform: uppercase; font-weight: 700; text-shadow: 0 0 4px #67768c;">CLICK TO CLOSE</span>
					</div>
				</div>
			</div>
			<div class="col-12 col-sm-4 p-0 content-height">
				<div class="form-group border-bottom p-6 searchbarSec" @if(!empty($ClientInfo)) style="display:none;" @endif>
					<div class="input-icon">
						<input type="text" class="form-control searchClients" placeholder="Search..." id="searchClient">
						<span><i class="flaticon2-search-1 icon-md"></i></span>
					</div>
				</div>
				 
				<div class="align-items-center border-bottom p-6 customer-data" @if(empty($ClientInfo)) style="display: none;" @else style="display: flex;" @endif id="selectedClient">
					<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
						<div class="symbol-label rounded-circle"
							style="background-image:url({{ asset('assets/media/users/300_13.jpg') }})">
						</div>
					</div>
					<div>
						@if(!empty($ClientInfo))
							<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
								{{ $ClientInfo->firstname }} {{ $ClientInfo->lastname }}<span class="fonter-weight-bolder">*</span>
							</a>
							<div class="text-muted">+{{ $ClientInfo->mo_country_code }} {{ $ClientInfo->mobileno }}<span class="font-weight-bolder">*</span></div>
							<div class="text-muted">{{ $ClientInfo->email }}
							</div>
						@else
							<a href="javascript:;" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
								Walk In <span class="fonter-weight-bolder">*</span>
							</a>
						@endif	
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
				
				<div class="customer-bottom" style="overflow: hidden scroll;">
					<div class="customer-bottom-outer" id="cartHistory" style="display: none;">
						<div class="view-appoinment- p-4 mb-2">
							<p class="m-0 font-weight-bolder text-center">Pay</p>
							<div class="p-6 border">
								<h2 class="mb-0 text-center font-weight-bolder" id="payamt">CA $<span></span></h2>
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
									<a href="javascript:;" class="m-2 w-45 btn btn-lg btn-primary paymentbtn" data-id="{{ $val->id }}">{{ $val->payment_type }}</a>
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
												<a href="#" class="navi-link">
													<span class="navi-text" data-toggle="modal" data-target="#invoiceDetailModal">Invoice Details</span>
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
							<a href="javascript:;" class="m-2 w-100 btn btn-lg btn-primary" id="backtopaymnt">Back to payments</a>
						</div>
					</div>
										
					<div id="clientHistory" class="bg-white" style="display:none;">
						<div class="card-body p-1">
							<div class="total-appoinment-data justify-content-around d-flex">
								<div class="text-center w-100 data pt-1 p-42">
									<h3 class="price font-weight-bolder text-center text-dark">{{ (isset($PreviousServices)) ? count($PreviousServices) : 0 }}</h3>
									<p class="title text-muted">Total Booking</p>
								</div>
								<div class=" text-center w-100 data pt-1 p-2">
									<h3 class="price font-weight-bolder text-center text-dark">CA ${{ (isset($TotalSpend)) ? $TotalSpend : 0 }}</h3>
									<p class="title text-muted">Total Sales</p>
								</div>
							</div>
							<ul class="nav nav-tabs nav-tabs-line nav-tabs-primary nav-tabs-line-2x" role="tablist">
								<li class="nav-item">
									<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link active"
										data-toggle="tab" href="#appointments">Appointments
										({{ (isset($PreviousServices)) ? count($PreviousServices) : 0 }})</a>
								</li>
								<li class="nav-item">
									<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link"
										data-toggle="tab" href="#consultationforms">Products ({{ (isset($soldProduct)) ? count($soldProduct) : 0 }})</a>
								</li>
								<li class="nav-item">
									<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link"
										data-toggle="tab" href="#products">Invoices ({{ (isset($clientInvoices)) ? count($clientInvoices) : 0 }})</a>
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
											@if(isset($PreviousServices) && !empty($PreviousServices))
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
									<?php echo (isset($ClientProducts)) ? $ClientProducts : '' ?>
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
												<?php echo (isset($ClientInovices)) ? $ClientInovices : '' ?>
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
	</form>
</div>

<!--end::Content-->
@endsection
{{-- Scripts Section --}}
@section('scripts')
<script>
	var WEBSITE_URL = "{{ url('partners') }}";
</script>
<script src="{{ asset('js/checkout.js') }}"></script>
<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js') }}"></script>

<script>
$(document).ready(function () {
	var list = [];
	$("#treeview").hummingbird();
	$("#treeview").on("CheckUncheckDone", function () {
		var count = $('#treeview input[name="value_checkbox[]"]:checked').length;
		var allCount = $('#treeview input[type="checkbox"]:checked').length;
		var allCheck = $('#treeview input[type="checkbox"]').length;

		if (allCheck == allCount) {
			$("#serviceInput").val('All Service Selected')
		} else {
			$("#serviceInput").val(count + ' service Selected')
		}
	});
	
	$('#dismiss, .side-overlay').on('click', function () {
		$('#sidebarClient').hide();
		$('#selectedClient').css('display','flex !important;');
		if($("#salesItems .card").length > 0) {
			$('#cartHistory').show();	
		}
		$('#clientHistory').hide();
		$('.side-overlay').removeClass('active');
	});

	$('#searchClient').on('click', function () {
		$('#sidebarClient').show();
		$('#selectedClient').css('display','none !important;');
		$('#cartHistory').hide();
		$('#clientHistory').hide();
		$('.side-overlay').addClass('active');
	});

	// $(document).on('keypress', '.allow_only_numbers', function(evt){
	// 	evt = (evt) ? evt : window.event;
	// 	var charCode = (evt.which) ? evt.which : evt.keyCode;
	// 	console.log("charcde::"+charCode);
	// 	// console.log($(this).val().length);
	// 	if (charCode > 31 && (charCode < 48 || charCode > 57) && charCode == 47) {
	// 		return false;
	// 	}
	// 	if($(this).val().length > 9){
	// 		return false;	
	// 	}
	// 	return true;
	// });

	$(document).on('paste', '.allow_only_numbers', function (event) {
		if (event.originalEvent.clipboardData.getData('Text').match(/[^\d]/)) {
			event.preventDefault();
		}
	});

	$(document).on('keydown', ".allow_only_numbers",function (event) {
        if (event.shiftKey == true) {
            event.preventDefault();
        }

        if ((event.keyCode >= 48 && event.keyCode <= 57) || 
            (event.keyCode >= 96 && event.keyCode <= 105) || 
            event.keyCode == 8 || event.keyCode == 9 || event.keyCode == 37 ||
            event.keyCode == 39 || event.keyCode == 46 || event.keyCode == 190 || event.keyCode == 110) {

        } else {
            event.preventDefault();
        }

        if($(this).val().indexOf('.') !== -1 && (event.keyCode == 190 || event.keyCode == 110))
            event.preventDefault(); 
        //if a decimal has been added, disable the "."-button

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