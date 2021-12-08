{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')
	<style>
	.fv-plugins-message-container {
		display: none;
	}	
	
	#emailError > .fv-plugins-message-container {
		display: block;
	}	
    .parpal{
        background: linear-gradient(-45deg, rgb(190, 74, 244) 0%, rgb(92, 55, 246) 100%);
    }
    .blue{
        background: linear-gradient(-225deg, rgb(11, 109, 217) 0%, rgb(95, 171, 255) 100%);
    }
    .black{
        background: linear-gradient(-225deg, rgb(16, 25, 40) 0%, rgb(32, 48, 71) 100%);
    }
    .green{
        background: linear-gradient(-45deg, rgb(0, 166, 156) 0%, rgb(0, 157, 98) 100%);
    }
    .orange{
        background: linear-gradient(-45deg, rgb(237, 176, 27) 0%, rgb(222, 100, 38) 100%);
    }
	</style>
@endsection

{{-- CSS Section --}}
@section('content')
<!--begin::Content-->

<div class="modal fade p-0" id="blockClientModal" tabindex="-1" role="dialog" aria-labelledby="blockClientModalLabel Label" aria-modal="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold text-center" id="decreaseStockModalLabel">Block Client</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
				</button>
			</div>
			<form method="POST" enctype="multipart/form-data" action="{{ route('blockClient') }}" id="blockClient">
				@csrf
				<input type="hidden" name="block_client_id" id="block_client_id"> 
				<div class="modal-body">
					<h6>Are you sure you want to block this client?</h6>
					<br>
					<h6>Blocking prevents this client from booking online appointments with you, they will find no available time slots.</h6>
					<br>
					<h6>Blocked clients are also automatically excluded from any marketing messages.</h6>
					<br>
					<div class="form-group w-100">
						<label>Blocking reason</label>
						<select class="form-control" name="block_reason" id="block_reason">
							<option value="">Select blocking reason</option>
							<option value="Too many no shows">Too many no shows</option>
							<option value="Too many late cancellations">Too many late cancellations</option>
							<option value="Too many reschedules">Too many reschedules</option>
							<option value="Rude or inappropriate to staff">Rude or inappropriate to staff</option>
							<option value="Refused to pay">Refused to pay</option>
							<option value="Booked fake appointments">Booked fake appointments</option>
							<option value="Other">Other</option>
						</select>
					</div>
					
					<div class="form-group w-100 blockNotes" style="display:none;">
						<label>Add note</label>
						<textarea class="form-control" name="block_notes" id="block_notes"></textarea>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<div class="ml-auto">
						<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary font-weight-bold" id="blockClientBtn">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade p-0" id="voidInvoiceMail" tabindex="-1" role="dialog" aria-labelledby="voidInvoiceMailModalLabel Label" aria-modal="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold text-center" id="decreaseStockModalLabel">Void Invoice</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
				</button>
			</div>
			<form method="POST" enctype="multipart/form-data" action="{{ route('markInvoiceVoid') }}" id="markInvoiceVoid">
				@csrf
				<input type="hidden" name="void_invoice_id" id="void_invoice_id"> 
				<div class="modal-body">
					<h6>Are you sure you want to void this invoice? This action is permanent and cannot be undone.</h6>
					@if($Invoice['payment_date'] != '')
						<hr>
						<p>
							Following payments will be deleted <br>
							CA ${{ $Invoice['inovice_final_total'] }} paid by {{ $Invoice['payment_type'] }} {{ ($Invoice['payment_date']) ? date("l, d M Y",strtotime($Invoice['payment_date'])) : '' }}  
						</p>
					@endif
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<div class="ml-auto">
						<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary font-weight-bold" id="voidInvoiceBtn">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="container-fluid p-0">
	<div class="my-custom-body-wrapper">
		<div class="my-custom-header">
			<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
				<h2 class="m-auto font-weight-bolder">View Invoice</h2>
				<span class="d-flex">
					<p class="cursor-pointer m-0 px-6" onclick="location.href='{{ route('salesList') }}'"><i class="text-dark fa fa-times icon-lg"></i></p>
				</span>
			</div>
		</div>
		
		<input type="hidden" name="printInvoiceAction" id="printInvoiceAction" value="{{ route('printInvoice') }}"> 
		
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
											<div class="card mb-4 border-0 rounded {{ $VoucherSoldData['color'] }}">
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
										<div class="card" style="min-width:400px;">
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
												
												@php $invoiceFinalTotal = $Invoice['inovice_final_total']; @endphp

												@if(!empty($InvoiceVoucher))
													@foreach($InvoiceVoucher as $ivKey => $ivValue)
														<div class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
															<span>
																<p class="mb-0 text-dark font-size-lg">VOUCHER ({{ $ivValue['voucher_code'] }})</p>

																<p class="mb-0 text-muted font-size-lg">{{ ($ivValue['created_at']) ? date("l, d M Y",strtotime($ivValue['created_at'])) : '' }} at {{ ($ivValue['created_at']) ? date("h:ia",strtotime($ivValue['created_at'])) : '' }}</p>
															</span>
															<h4>Ca ${{ $ivValue['voucher_amount'] }}</h4>
															@php 
																$invoiceFinalTotal -= $ivValue['voucher_amount']; 
															@endphp
														</div>
													@endforeach
												@endif
												
												@if($Invoice['payment_id'] > 0)
													<div class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
														<span>
															<p class="mb-0 text-dark font-size-lg">{{ $Invoice['payment_type'] }}</p>
															<p class="mb-0 text-muted font-size-lg">{{ ($Invoice['payment_date']) ? date("l, d M Y",strtotime($Invoice['payment_date'])) : '' }} at {{ ($Invoice['payment_date']) ? date("h:ia",strtotime($Invoice['payment_date'])) : '' }}</p>
														</span>
														<h4>Ca ${{ $invoiceFinalTotal }}</h4>
													</div>
												@endif
												<div class="mt-4 border-bottom py-3 order-total d-flex align-items-center justify-content-between">
													<p class="mb-0 text-dark font-size-lg">Balance</p>
													<h4>Ca ${{ ($Invoice['invoice_status'] == 0) ? $Invoice['inovice_final_total'] : 0 }}</h4>
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
							<div class="customer-bottom-outer">
								<div class="view-appoinment-content mb-2">
									@if($Invoice['invoice_status'] == 1)
										<div class="px-20 py-8 text-center">
											<i class="far fa-check-circle" style="color:rgb(14, 230, 140);font-size: 82px;"></i>
										</div>
										<h2 class="font-weight-bolder px-4 text-center">Completed</h2>
										<h4 class="px-10">Full payment received on {{ ($Invoice['payment_date']) ? date("l, d M Y",strtotime($Invoice['payment_date'])) : '' }} at {{ ($LocationInfo) ? $LocationInfo['location_name'] : '' }} by {{ ($PaymentDoneBy) ? $PaymentDoneBy['first_name'] : '' }} {{ ($PaymentDoneBy) ? $PaymentDoneBy['last_name'] : '' }}
										</h4>
									@elseif($Invoice['invoice_status'] == 2)
										<div class="px-20 py-8 text-center">
											<span class="_3Q2N2k _30vxLD _1d3jRi _3VH7hu ftFjVi"><svg style="width: 100px;" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 54 56"><path d="M39 12H15c-.6 0-1-.4-1-1s.4-1 1-1h24c.6 0 1 .4 1 1s-.4 1-1 1zm-1-5c0-.6.5-1 1-1s1 .4 1 1c0 .5-.5 1-1 1s-1-.5-1-1zm-4 0c0-.6.5-1 1-1s1 .4 1 1c0 .5-.5 1-1 1s-1-.5-1-1zm-4 0c0-.6.5-1 1-1s1 .4 1 1c0 .5-.5 1-1 1s-1-.5-1-1zm-4 0c0-.6.4-1 1-1 .5 0 1 .4 1 1 0 .5-.5 1-1 1-.6 0-1-.5-1-1zm-4 0c0-.6.4-1 1-1 .5 0 1 .4 1 1 0 .5-.5 1-1 1-.6 0-1-.5-1-1zm-4 0c0-.6.4-1 1-1 .5 0 1 .4 1 1 0 .5-.5 1-1 1-.6 0-1-.5-1-1zm-4 0c0-.6.4-1 1-1 .5 0 1 .4 1 1 0 .5-.5 1-1 1-.6 0-1-.5-1-1zm26 42c0-.5.5-1 1-1s1 .5 1 1-.5 1-1 1-1-.5-1-1zm-4 0c0-.5.5-1 1-1s1 .5 1 1-.5 1-1 1-1-.5-1-1zm-4 0c0-.5.5-1 1-1s1 .5 1 1-.5 1-1 1-1-.5-1-1zm-4 0c0-.5.5-1 1-1s1 .5 1 1-.5 1-1 1-1-.5-1-1zm-4 0c0-.5.4-1 1-1 .5 0 1 .5 1 1s-.5 1-1 1c-.6 0-1-.5-1-1zm-4 0c0-.5.4-1 1-1 .5 0 1 .5 1 1s-.5 1-1 1c-.6 0-1-.5-1-1zm-4 0c0-.5.4-1 1-1 .5 0 1 .5 1 1s-.5 1-1 1c-.6 0-1-.5-1-1zm-4 0c0-.5.4-1 1-1 .5 0 1 .5 1 1s-.5 1-1 1c-.6 0-1-.5-1-1z"></path><path d="M45 56c-.2 0-.4-.1-.6-.2L42 54l-2.4 1.8c-.4.3-.8.3-1.2 0L36 54l-2.4 1.8c-.4.3-.8.3-1.2 0L30 54l-2.4 1.8c-.4.3-.8.3-1.2 0L24 54l-2.4 1.8c-.4.3-.8.3-1.2 0L18 54l-2.4 1.8c-.4.3-.8.3-1.2 0L12 54l-2.4 1.8c-.3.2-.7.3-1 .1-.4-.2-.6-.5-.6-.9V5c0-.6.4-1 1-1s1 .4 1 1v48l1.4-1c.4-.3.8-.3 1.2 0l2.4 1.8 2.4-1.8c.4-.3.8-.3 1.2 0l2.4 1.8 2.4-1.8c.4-.3.8-.3 1.2 0l2.4 1.8 2.4-1.8c.4-.3.8-.3 1.2 0l2.4 1.8 2.4-1.8c.4-.3.8-.3 1.2 0l2.4 1.8 2.4-1.8c.4-.3.8-.3 1.2 0l1.4 1V5c0-.6.4-1 1-1s1 .4 1 1v50c0 .4-.2.7-.6.9-.1.1-.2.1-.4.1z"></path><path d="M50 12h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h1c.6 0 1 .4 1 1s-.4 1-1 1H4c-2.2 0-4-1.8-4-4V4c0-2.2 1.8-4 4-4h46c2.2 0 4 1.8 4 4v4c0 2.2-1.8 4-4 4zM34.4 39h-3.2c-.6 0-1-.4-1-1s.4-1 1-1h3.2s.1-.1.2-.1c.2-.2.3-.6.3-.8h-3.2c-.6 0-1-.4-1-1s.4-1 1-1h3.6c.2 0 .6-.5.7-1h-3.8c-.6 0-1-.4-1-1s.4-1 1-1h4.5v-.2c0-.4-.1-.5-.1-.6h-.1c-2.9-.3-5.4-.3-6.4-.3-.4 0-.8-.2-1-.6s-.1-.8.2-1.1c.3-.6.7-4.5.3-5.6-.3-.7-.6-1.3-1-1.6.5 2.8-.7 4.4-2.1 6.2-.6.9-1.4 1.9-2 3.2-.2.5-.8.7-1.3.5-.5-.2-.7-.9-.4-1.3.7-1.5 1.5-2.5 2.2-3.5 1.3-1.8 2.1-2.9 1.6-5.1-.1-.5 0-1 .4-1.4.4-.5 1-.7 1.5-.7 1.7.2 2.6 2 3 3.1.4 1.1.4 4 0 5.9 1.5 0 3.5.1 5.1.2.5 0 2.1.3 2.1 2.6 0 .8-.2 1.4-.7 1.8v.4c0 .9-.4 1.8-1.1 2.4.1.8-.2 1.8-.6 2.6-.4.6-1.1 1-1.9 1zm-5.9-18.4z"></path><path d="M24.3 30H21c-.6 0-1-.4-1-1s.4-1 1-1h3.3c.6 0 1 .4 1 1s-.5 1-1 1z"></path><path d="M20.2 41h-2.7c-.9 0-1.6-.6-1.8-1.5-.7-3.7-.7-8.3-.7-10.7 0-1 .8-1.8 1.8-1.8h3.4c1 0 1.8.8 1.8 1.8v10.4c0 1-.8 1.8-1.8 1.8zm-2.6-2H20V29h-3c0 2 0 6.4.6 10z"></path><path d="M27.3 42c-2 0-3.3-.9-4.1-1.4-.1-.1-.2-.1-.2-.2h-2c-.6 0-1-.4-1-1s.4-1 1-1h2.3c.2 0 .4 0 .5.1.2.1.4.2.6.4.7.5 1.5 1.1 2.9 1.1 2.7 0 3.8-.1 5.1-.4 1.2-.2 1.2-.6 1.2-.9v-.3l-.1-.2c-.1-.5.2-1.1.7-1.2.5-.1 1.1.2 1.2.7v.1c.1.3.1.5.1 1-.1 1.4-1.1 2.3-2.8 2.7-1.5.4-2.8.5-5.4.5zm2.8-12h-1.2c-.6 0-1-.4-1-1s.4-1 1-1h1.2c.6 0 1 .4 1 1s-.4 1-1 1z"></path></svg></span>
										</div>
										<h2 class="font-weight-bolder px-4 text-center">Refunded</h2>
										<h4 class="px-10">Full payment received on {{ ($Invoice['payment_date']) ? date("l, d M Y",strtotime($Invoice['payment_date'])) : '' }} at {{ ($LocationInfo) ? $LocationInfo['location_name'] : '' }} by {{ ($PaymentDoneBy) ? $PaymentDoneBy['first_name'] : '' }} {{ ($PaymentDoneBy) ? $PaymentDoneBy['last_name'] : '' }}
										</h4>
									@elseif($Invoice['invoice_status'] == 0)
										<div class="px-20 py-8 text-center">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 150 152"><path d="M27.1 43c-7.2 0-13-5.8-13-13s5.8-13 13-13 13 5.8 13 13-5.9 13-13 13zm0-24c-6.1 0-11 4.9-11 11s4.9 11 11 11 11-4.9 11-11-5-11-11-11z"></path><path d="M35 38.9c-.3 0-.5-.1-.7-.3l-16-15.9c-.4-.4-.4-1 0-1.4s1-.4 1.4 0l15.9 15.9c.4.4.4 1 0 1.4-.1.2-.4.3-.6.3zM39 12H15c-.6 0-1-.4-1-1s.4-1 1-1h24c.6 0 1 .4 1 1s-.4 1-1 1zm-1-5c0-.6.5-1 1-1s1 .4 1 1c0 .5-.5 1-1 1s-1-.5-1-1zm-4 0c0-.6.5-1 1-1s1 .4 1 1c0 .5-.5 1-1 1s-1-.5-1-1zm-4 0c0-.6.5-1 1-1s1 .4 1 1c0 .5-.5 1-1 1s-1-.5-1-1zm-4 0c0-.6.4-1 1-1 .5 0 1 .4 1 1 0 .5-.5 1-1 1-.6 0-1-.5-1-1zm-4 0c0-.6.4-1 1-1 .5 0 1 .4 1 1 0 .5-.5 1-1 1-.6 0-1-.5-1-1zm-4 0c0-.6.4-1 1-1 .5 0 1 .4 1 1 0 .5-.5 1-1 1-.6 0-1-.5-1-1zm-4 0c0-.6.4-1 1-1 .5 0 1 .4 1 1 0 .5-.5 1-1 1-.6 0-1-.5-1-1zm26 42c0-.5.5-1 1-1s1 .5 1 1-.5 1-1 1-1-.5-1-1zm-4 0c0-.5.5-1 1-1s1 .5 1 1-.5 1-1 1-1-.5-1-1zm-4 0c0-.5.5-1 1-1s1 .5 1 1-.5 1-1 1-1-.5-1-1zm-4 0c0-.5.5-1 1-1s1 .5 1 1-.5 1-1 1-1-.5-1-1zm-4 0c0-.5.4-1 1-1 .5 0 1 .5 1 1s-.5 1-1 1c-.6 0-1-.5-1-1zm-4 0c0-.5.4-1 1-1 .5 0 1 .5 1 1s-.5 1-1 1c-.6 0-1-.5-1-1zm-4 0c0-.5.4-1 1-1 .5 0 1 .5 1 1s-.5 1-1 1c-.6 0-1-.5-1-1zm-4 0c0-.5.4-1 1-1 .5 0 1 .5 1 1s-.5 1-1 1c-.6 0-1-.5-1-1z"></path><path d="M45 56c-.2 0-.4-.1-.6-.2L42 54l-2.4 1.8c-.4.3-.8.3-1.2 0L36 54l-2.4 1.8c-.4.3-.8.3-1.2 0L30 54l-2.4 1.8c-.4.3-.8.3-1.2 0L24 54l-2.4 1.8c-.4.3-.8.3-1.2 0L18 54l-2.4 1.8c-.4.3-.8.3-1.2 0L12 54l-2.4 1.8c-.3.2-.7.3-1 .1-.4-.2-.6-.5-.6-.9V5c0-.6.4-1 1-1s1 .4 1 1v48l1.4-1c.4-.3.8-.3 1.2 0l2.4 1.8 2.4-1.8c.4-.3.8-.3 1.2 0l2.4 1.8 2.4-1.8c.4-.3.8-.3 1.2 0l2.4 1.8 2.4-1.8c.4-.3.8-.3 1.2 0l2.4 1.8 2.4-1.8c.4-.3.8-.3 1.2 0l2.4 1.8 2.4-1.8c.4-.3.8-.3 1.2 0l1.4 1V5c0-.6.4-1 1-1s1 .4 1 1v50c0 .4-.2.7-.6.9-.1.1-.2.1-.4.1z"></path><path d="M50 12h-1c-.6 0-1-.4-1-1s.4-1 1-1h1c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2H4c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h1c.6 0 1 .4 1 1s-.4 1-1 1H4c-2.2 0-4-1.8-4-4V4c0-2.2 1.8-4 4-4h46c2.2 0 4 1.8 4 4v4c0 2.2-1.8 4-4 4z"></path></svg>
										</div>
										<h2 class="font-weight-bolder px-4 text-center">Unpaid</h2>	
									@elseif($Invoice['invoice_status'] == 3)
										<div style="margin: 2% auto; text-align: center;">
											<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" style="width: 48px; height: 48px;"><path d="M12.042 22c-5.514 0-10-4.486-10-10s4.486-10 10-10 10 4.486 10 10-4.486 10-10 10zm0-18c-4.41 0-8 3.59-8 8s3.59 8 8 8 8-3.59 8-8-3.59-8-8-8z"></path><path d="M16.75 8.707l-1.416-1.414-3.292 3.293L8.75 7.293 7.333 8.707 10.627 12l-3.293 3.293 1.415 1.414 3.292-3.292 3.292 3.292 1.415-1.414L13.455 12"></path></svg>
											<p style="font-size: 24px; font-weight: 800; line-height: 32px; margin-bottom: 16px;">Voided</p>
										</div>
									@endif
									<div class="px-4 mt-18">
										<form method="POST" id="sendInvoiceMail" action="{{ route('sendInvoiceMail') }}">
											@csrf
											<input type="hidden" name="invoiceId" id="invoiceId" value="{{ $Invoice['id'] }}">
										
											<label class="font-weight-bolder">Send Invoice</label>
											<div class="mb-0 form-group w-100 d-flex">
												<div class="">
													<input type="email" autocomplete="off" class="form-control form-control-lg mr-4" value="{{ (isset($ClientInfo->email)) ? $ClientInfo->email : '' }}" name="clientInvoiceEmail" id="clientInvoiceEmail" placeholder="Enter email">
												</div>
												<div class="">
													<button class="py-2 px-4 btn btn-primary" id="sendClientInvoiceEmailBtn" type="submit">Send</button>
												</div>
											</div>
											<p id="emailError"></p>
										</form>
									</div>
								</div>
								<div class="view-appoinment-footer border-top w-100 py-6">
									<h5 class="text-center font-weight-bolder mb-4">Total: CA ${{ $Invoice['inovice_final_total'] }}</h5>
									<div class="buttons d-flex justify-content-between">
										<div class="btn-group dropup w-100 mx-4">
											<button type="button" class="btn btn-white dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											More Option
											</button>
											<div class="dropdown-menu dropdown-menu-sm dropdown-menu-center">
												<!--begin::Navigation-->
												<ul class="text-center navi flex-column navi-hover py-2">
													<li class="navi-item">
														<a href="{{ route('saveInvoicePdf', ['id' => $invoiceId]) }}" class="navi-link">
														<span class="navi-text">Download</span>
														</a>
													</li>
													<li class="navi-item">
														<a href="javascript:;" class="navi-link printInvoice" data-InvoiceID="{{ $invoiceId }}">
														<span class="navi-text">Print</span>
														</a>
													</li>
													<li class="navi-item">
														@if($Invoice['invoice_status'] == 1)
															@if($isRefunded == 1 && $isVoided == 1)	
															<a href="javascript:;" class="navi-link invoiceRefunded"><span class="navi-text text-danger">Refund</span></a>	
															@else

															@if($isVoided == 1)
															<a href="{{ route('refundInvoice', $Invoice['location_id'].'/invoice/'.Crypt::encryptString($Invoice['id'])) }}" class="navi-link">
																<span class="navi-text text-danger">Refund</span>
																</a>											
															@else
																
																@if($isRefunded != 1)
																<a href="{{ route('refundInvoice', $Invoice['location_id'].'/invoice/'.Crypt::encryptString($Invoice['id'])) }}" class="navi-link">
																<span class="navi-text text-danger">Refund</span>
																</a>
																@else
																<a href="javascript:;" class="navi-link invoiceRefunded"><span class="navi-text text-danger">Refund</span></a>	
																@endif
															@endif
														@endif
													@endif	
													</li>
													<li class="navi-item">
													@if($Invoice['invoice_status'] != 0 && $Invoice['invoice_status'] != 3)

													@if($isRefunded != 1)
														
														<a href="#" data-toggle="modal" data-target="#voidInvoiceMail" class="navi-link markInvoiceVoid" data-InvoiceID="{{ ($Invoice['id']) ? $Invoice['id'] : 0 }}">
														<span class="navi-text text-danger">Void</span>
														</a>
														@else
														<a href="javascript:;" class="navi-link invoiceRefundedVoid"><span class="navi-text text-danger">Void</span></a>	
														
														@endif
														@endif
													</li>


													@if(!empty($VoucherSold))
														<li class="navi-item">
															<a href="{{ route('sendVoucherEmail',['id' => $Invoice['id']]) }}" class="navi-link">
															<span class="navi-text">Email voucher</span>
															</a>
														</li>
														<li class="navi-item">
															<a href="{{ route('printDownloadVoucher',['id' => $Invoice['id']]) }}" class="navi-link">
															<span class="navi-text">Print voucher</span>
															</a>
														</li>
													@endif
												</ul>
												<!--end::Navigation-->
											</div>
										</div>
										@if($Invoice['invoice_status'] == 0)
											<a href="{{ route('applyPayment',['id' => $Invoice['id']]) }}" class="btn btn-lh btn-primary w-100 mx-4">
											Pay now
											</a>	
										@else
											<a href="{{ route('salesList') }}" class="btn btn-lh btn-primary w-100 mx-4">
											Close
											</a>
										@endif
									</div>
								</div>
							</div>
							<div id="sidebar" class="bg-white">
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

<div class="printInvoiceContent" id="printInvoiceContent">
</div>

<!--end::Content-->
@endsection
{{-- Scripts Section --}}
@section('scripts')	
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
<script src="{{ asset('js/jquery-printme.js') }}"></script>
<script src="{{ asset('js/add-appointment.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>
<script type="text/javascript">
	$(document).ready(function () {

			

		$(".invoiceRefunded").click( function(){
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
			toastr.error('This invoice has already been refunded.');
		});	

		
		$(".invoiceRefundedVoid").click( function(){
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
			toastr.error('Refunded sales cannot be voided');
		});
		
		var titleHeader = $('#title-header').outerHeight();
		var customerData = $('.customer-data').outerHeight();
		// var customerBottom = $('.customer-bottom').outerHeight();

		$('.customer-bottom').css('height', 'calc(100vh - ' + titleHeader + 'px - ' + customerData + 'px)');
		$('.content-height').css('height', 'calc(100vh - ' + titleHeader + 'px)');

		var contentHeight = $('.content-height').css('height');
		$('.side-overlay').css('height', contentHeight + 'px');

		$('#dismiss, .side-overlay').on('click', function () {
			$('#sidebar').removeClass('active');
			$('.side-overlay').removeClass('active');
			
			$(".viewClient").show();
			$(".viewClientInfo").hide();
		});

		$('#sidebarCollapse').on('click', function () {
			$('#sidebar').addClass('active');
			$('.side-overlay').addClass('active');
			
			$(".viewClient").hide();
			$(".viewClientInfo").show();
		});
	});
</script>
@if(!empty($InvoiceTemplate) && $InvoiceTemplate->autoPrint == 1)
<script>
	$(document).ready(function () {
		$(".printInvoice").trigger('click');
	});
</script>	
@endif
@endsection