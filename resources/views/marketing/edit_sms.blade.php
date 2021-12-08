{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss')
<style type="text/css">
	.file-man-box {
		padding : 16px !important;
		padding: 20px;
	    border: 1px solid #e3eaef;
	    border-radius: 5px;
	    position: relative;
	    margin-bottom: 20px;
	    cursor: pointer;
	}
	[data-letters]:before {
		content:attr(data-letters);
		display:inline-block;
		font-size:1em;
		width:2.5em;
		height:2.5em;
		line-height:2.5em;
		text-align:center;
		border-radius:50%;
		background:plum;
		vertical-align:middle;
		margin-right:1em;
		color:white;
	}
</style>
@endsection
@section('content')
@php
	$id = $smsData->id;
	$voucher_id = $smsData->voucher_id;
	$message_name = $smsData->message_name;
	$message_description = $smsData->message_description;
	$is_link = $smsData->is_link;
	$btn_url = $smsData->btn_url;
	$title = $smsData->title;
	$discount_type = $smsData->discount_type;
	$discount_value = $smsData->discount_value;
	$appointment_limit = $smsData->appointment_limit;
	$valid_for = $smsData->valid_for;
	$sms_type_text = $smsData->sms_type_text;
	$sms_type_int = $smsData->sms_type_int;
	$services = json_decode($smsData->services);
	$group_type = $smsData->group_type;
@endphp
<div class="container-fluid p-0">
	{{ Form::open(array('url' => 'partners/marketing/saveSMSBlast','id' => 'emailSMSForm','class'=>"require-validation",'data-stripe-publishable-key'=>$setting->stripe_publish_key)) }}
	<input type="hidden" name="edit_id" value="{{ $id }}">
	<input type="hidden" name="message_type" value="{{ $sms_type_int }}">
	<input type="hidden" name="charge_per_client" id="charge_per_client" value="{{ $setting->per_sms_cost }}">
	<input type="hidden" id="client_group_url" value="{{ route('getFilteredClients') }}">
	
	<input type="hidden" name="voucher_id" value="{{ $voucher_id }}">
	
	<div class="my-custom-body-wrapper">
		<!-- <div class="my-custom-header">
			<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
				<span class="d-flex">
					<p class="cursor-pointer m-0 px-6" onclick="history.back();"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
					<p class="cursor-pointer text-blue previous" onclick="nextPrev(-1)"><i
							class="border-left mx-4"></i>Previous</p>
				</span>
				<span>
					<p class="text-dark text-center mb-0">Steps <span class="steps"></span> of 4</p>
					<h1 class="font-weight-bolder page-title">Choose a message type</h1>
				</span>
				<div>
					<button type="button" class="btn btn-lg btn-primary next-step" onclick="nextPrev(1)">Continue</button>
				</div>
			</div>
		</div> -->
		<div class="my-custom-header bg-white text-dark border-bottom"> 
            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
            	<span class="d-flex">
		            <a type="button" class="close" onclick="history.back();" style="display: flex; justify-content: flex-start; align-items: center;top:unset;left: 0;opacity: 1;font-size: 1.75rem;">
		                <span aria-hidden="true" class="la la-times"></span>
		            </a>
		            <p class="h6 cursor-pointer mb-0 text-blue previous" onclick="nextPrev(-1)" style=""><i class="border-left mx-4"></i>Previous</p>
		        </span>
	            <div style="flex-grow: 1;">
	            	<h6 class="text-dark text-center mb-0">Steps <span class="steps"></span> of 4</h6>
					<h1 class="font-weight-bolder page-title">Choose a message type</h1>
	            </div>
	            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
	                <button type="button" class="btn btn-primary next-step" onclick="nextPrev(1)">Continue</button>
	            </div>
	        </div>
        </div> 
		<div class="my-custom-body bg-white">
			<div class="container-fluid">
				<div class="row">
					<div class="col-12 col-md-4 p-0 last-hide" style="height:calc(100vh - 80px);overflow-y:scroll">
						<div class="p-4">
							<div class="edit-content-tab">
								<h3 class="mb-4 font-weight-bolder">Customise message</h3>
								<div class="form-group">
									<label class="font-weight-bolder">Blast message name</label>
									<input type="text" class="form-control" value="{{ $message_name }}" name="message_name" />
									<span>This is not visible to clients.</span>
								</div>
								<hr>
								<h3 class="mb-4 font-weight-bolder">Text</h3>
								<div class="form-group mb-0">
									<label class="font-weight-bolder">Message</label>
									<textarea class="form-control message" rows="5" id="message"
										name="desc">{{ $message_description }}</textarea>
								</div>
								<hr>
								<div class="isUpdate">
									
									<h3 class="mb-4 font-weight-bolder">Links</h3>
									<div class="form-group">
										<div class="radio-list">
											<label class="radio">
												<input type="radio" value="1" class="book_now_link" {{ ($is_link == 1) ? 'checked' : '' }} name="isLink" />
												<span></span>
												Book now link
											</label>
											<label class="radio">
												<input type="radio" value="0" class="book_now_link" name="isLink" {{ ($is_link == 0) ? 'checked' : '' }} />
												<span></span>
												No link
											</label>
											<label class="radio">
												<input type="radio" value="2"  {{ ($is_link == 2) ? 'checked' : '' }} class="book_now_link" name="isLink" />
												<span></span>
												Custom link
											</label>
										</div>
									</div>
									<div class="isCustomLink">
										<div class="form-group mb-0">
											<label class="font-weight-bolder">Link (URL)</label>
											<input type="text" class="form-control" id="linkUrl" placeholder="www.google.com" name="btn-url" value="{{ ($is_link == 2) ? $btn_url : '' }}" />
										</div>
									</div>
								</div>
								<div class="isSpecialOffer">
									<h3 class="mb-4 font-weight-bolder">Discount</h3>
									<div class="form-group d-flex align-items-center">
										<div>
											<label class="font-weight-bolder">Discount value</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text bg-white discountType">
														{{ ($discount_type == 1) ? 'CA $' : '%' }}
													</span>
													{{-- <span class="input-group-text bg-white isPrice">
														CA $
													</span>
													<span class="input-group-text bg-white isPercent">
														%
													</span> --}}
												</div>
												<input class="form-control" id="discount-input" name="discount_value" value="{{ $discount_value }}">
												<span class="text-dark-50">
													Discount is applied individually to each applicable service
													booked
												</span>
											</div>
										</div>
										<div class="w-70 ml-4">
											<div class="tgl-radio-tabs">
												<input id="price" value="1" type="radio" class="form-check-input tgl-radio-tab-child" name="discount-type" onclick="getDiscountType()" {{ ($discount_type == 1) ? 'checked' : '' }}>
												<label for="price" class="radio-inline">CA $</label>

												<input id="percent" value="2" type="radio" class="form-check-input tgl-radio-tab-child" name="discount-type" onclick="getDiscountType()" {{ ($discount_type == 2) ? 'checked' : '' }}>
												<label for="percent" class="radio-inline">%</label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="font-weight-bolder">Max appointments booked</label>
										<select class="form-control" onchange="appoinmentLimit(this)"
											name="appoinmentsLimits">
											<option value="1" {{ ($appointment_limit == 1) ? 'selected' : '' }}>1</option>
											<option value="3" {{ ($appointment_limit == 3) ? 'selected' : '' }}>3</option>
											<option value="5" {{ ($appointment_limit == 5) ? 'selected' : '' }}>5</option>
											<option value="10" {{ ($appointment_limit == 10) ? 'selected' : '' }}>10</option>
											<option value="unlimited" {{ ($appointment_limit == 'unlimited') ? 'selected' : '' }}>No limit</option>
										</select>
									</div>
									<div class="form-group">
										<label class="font-weight-bolder">Valid for</label>
										<select class="form-control" onchange="validFor(this)" name="validFor">
											<option value="5" {{ ($valid_for == 1) ? 'selected' : '' }}>5 days</option>
											<option value="7" {{ ($valid_for == 7) ? 'selected' : '' }}>7 days</option>
											<option value="10" {{ ($valid_for == 10) ? 'selected' : '' }}>10 days</option>
											<option value="14" {{ ($valid_for == 14) ? 'selected' : '' }}>14 days</option>
											<option value="30" {{ ($valid_for == 30) ? 'selected' : '' }}>30 days</option>
											<option value="60" {{ ($valid_for == 60) ? 'selected' : '' }}>60 days</option>
											<option value="90" {{ ($valid_for == 90) ? 'selected' : '' }}>90 days</option>
										</select>
										<span class="text-dark-50">Calculated from the date of sending the
											email</span>
									</div>
									<div class="services">
										<h4 class="font-weight-bolder">Apply to <span
												class="servicesApply">{{ ($sms_type_int == 2 && !empty($services)) ? count($services) : 0  }} serice</span>
										</h4>
										<div class="form-group" style="position: relative;">
											<input type="text" readonly="" class="form-control form-control-lg" id="serviceInput" placeholder="All Services" data-toggle="modal" data-target="#servicesModal" style="cursor: pointer;">
											<a href="" class="chng_popup" style="top:25%" data-toggle="modal"
												data-target="#servicesModal">
												Edit</a>
										</div>
									</div>
								</div>
							</div>
							<div class="edit-content-tab">
								<h3 class="font-weight-bolder">Audience</h3>
								<h6>
									Choose which clients will receive your message. <a href="#"
										class="text-blue">Learn more.</a>
								</h6>
								<div class="form-group">
									<div class="form-group w-100 d-flex extra-time">
										<label class="option my-3 align-items-center">
											<span class="option-control">
												<span class="radio">
													<input type="radio" onclick="checkAudience()" name="audience"
														value="1" checked="checked">
													<span></span>
												</span>
											</span>
											<span class="option-label">
												<span class="option-head">
													<span class="option-title">All Clients</span>
												</span>
												<span class="option-body text-dark">Send to every client in your
													client list.</span>
											</span>
										</label>
										<label class="option m-3 align-items-center">
											<span class="option-control">
												<span class="radio">
													<input type="radio" onclick="checkAudience()" name="audience"
														value="2">
													<span></span>
												</span>
											</span>
											<span class="option-label">
												<span class="option-head">
													<span class="option-title">Clients Group</span>
												</span>
												<span class="option-body text-dark">Choose groups of clients in your
													client list.</span>
											</span>
										</label>
									</div>
									<div class="isClientGroups">
										<div class="form-group">
											<div class="radio-list">
												<label class="radio">
													<input type="radio" value="New Clients" checked="checked"
														name="isclient" class="client_group" data-type="last_client" id="new_client" data-value="30" data-time="Days" data-modify="new_client_group_modify"/>
													<span></span>
													<div class="w-100 d-flex justify-content-between">
														<span>
															<p class="m-0 font-weight-bolder">New Clients</p>
															<p class="m-0 text-dark-50" id="newClientText">Clients added in the last 30 days</p>
														</span>
														<span class="text-blue cursor-pointer client_group_model" id="new_client_group_modify" data-model="newClientModal" data-type="new_client">Modify</span>
													</div>
												</label>
												<label class="radio">
													<input type="radio" value="Recent Client" name="isclient" class="client_group" data-type="first_sale" id="recent_client" data-value="30" data-time="Days" data-modify="recent_client_group_modify"/>
													<span></span>
													<div class="w-100 d-flex justify-content-between">
														<span>
															<p class="m-0 font-weight-bolder">Recent clients</p>
															<p class="m-0 text-dark-50" id="recentClientText">
																Clients with their first sale in the last 30 days
															</p>
														</span>
														<span class="text-blue cursor-pointer client_group_model" id="recent_client_group_modify" data-model="newClientModal" data-type="recent_client">Modify</span>
													</div>
												</label>
												<label class="radio">
													<input type="radio" value="Loyal Client" name="isclient" class="client_group loyal_client_group" data-type="loyal_client" id="loyal_client" data-value="30" data-time="Days" data-modify="loyal_client_group_modify"/>
													<span></span>
													<div class="w-100 d-flex justify-content-between">
														<span>
															<p class="m-0 font-weight-bolder">Loyal clients</p>
															<p class="m-0 text-dark-50" id="loyalClientText">Clients with 3 or more sales, within the last 2 month period.
															</p>
														</span>
														<span class="text-blue cursor-pointer client_group_model" data-type="loyal_client" id="loyal_client_group_modify" data-model="loyalClientModel">Modify</span>
													</div>
												</label>
												<label class="radio">
													<input type="radio" value="Lapsed Clients" name="isclient" class="client_group" data-type="at_least_3_sale" id="lapsed_client" data-value="6" data-time="Months" data-modify="lapsed_client_group_modify"/>
													<span></span>
													<div class="w-100 d-flex justify-content-between">
														<span>
															<p class="m-0 font-weight-bolder">Lapsed clients</p>
															<p class="m-0 text-dark-50">
																Clients with at least 3 sales anytime in the last 6
																months, but did not return in the last 2 months
															</p>
														</span>
														<span class="text-blue cursor-pointer client_group_model" id="lapsed_client_group_modify" data-type="lapsed_client" data-model="LapsedClientM">Modify</span>
													</div>
												</label>
											</div>
										</div>
									</div>
								</div>
								<hr>
								<h3 class="font-weight-bolder">Total clients</h3>
								<h6>
									View the number of clients your email will be sent to.
								</h6>
								<div class="clients">
									<div class="form-group" style="position: relative;">
										<input type="text" readonly="" id="clientsInput" class="form-control form-control-lg" placeholder="All Clients" data-toggle="modal" data-target="#clientsModal" style="cursor: pointer;">
										<a href="" class="chng_popup" style="top:25%" data-toggle="modal" data-target="#clientsModal"> Edit</a>
									</div>
								</div>
								<h3 class="font-weight-bolder">Price</h3>
								<ul class="p-0" style="list-style: none;" id="pricing">
									<li class="my-4 p-2 border-bottom my-4 d-flex justify-content-between">
										<h5><span class="total_clients">0</span> emails at CA $0.0312 each</h5>
										<h5>CA $<span id="total_pr_client">0.00</span></h5>
									</li>
									<li class="p-2">
										<div class="d-flex justify-content-between">
											<h5>Subtotal</h5>
											<h5>CA $<span id="subtotal">0.00</span></h5>
										</div>
										<div class="d-flex justify-content-between">
											<h5>GST 5%</h5>
											<h5>CA $<span id="total_gst">0.00</span></h5>
										</div>
									</li>.
									<li class="border-top my-4 p-2 d-flex justify-content-between">
										<h5 class="font-weight-bolder">Total cost</h5>
										<h5 class="font-weight-bolder">CA $<span class="total_cost">0.00</span></h5>
										<input type="hidden" name="message_price" id="message_price">
										<input type="hidden" name="total_payable_price" id="total_payable_price">
									</li>
								</ul>
							</div>
						</div>
					</div>
					{{-- preview --}}
					<div class="col-12 col-md-8 p-0 bg-white last-hide"
						style="height:calc(100vh - 80px);overflow-y:scroll">
						<div class="p-6">
							<div class="card m-auto">
								<div class="card-body">
									<h3 class="text-center">Message Preview</h3>
									<div class="smartphone">
										<div class="content">
											<div class="chat__message">
												<div class="date"></div>
												<div class="isUpdate message body-text">
													{{ $message_description }}
												</div>
												<div class="isUpdate">
													<a class="text-blue linkUrl isLink" href="javascript:;">{{ ($is_link == 2) ? $btn_url : 'Link here' }}</a>
												</div>
												<div class="isSpecialOffer message body-text">
													{{ $message_description }}
												</div>
												<div class="isSpecialOffer">
													<a class="text-blue linkUrl isLink" href="#">{{ ($is_link == 2) ? $btn_url : 'Link here' }}</a>
												</div>
												<div class="isVoucher message">
													Choose voucher offer </br>
													<a class="text-blue linkUrl isLink" href="#">Link here</a>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 edit-content-tab last-tab">
						<div class="w-35 text-center center mx-auto my-30 align-items-center">
							<div class="mx-auto my-8" style="width: 80px;height: 80px;">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 73 84">
									<path fill="#b4dff2" d="M2.9-1.1h67.6v44.4L36.7 13.9 26 21.1 2.9 43.3z"
										transform="matrix(1 0 0 -1 0 80)"></path>
									<path fill="#7abdd9" d="M6.7-1H2v44l4.7-2.9V2.6"
										transform="matrix(1 0 0 -1 0 80)"></path>
									<path
										d="M37.8 64.1l2.7-7.9h8.2c1.3 0 1.8-1.6.8-2.4l-6.6-5.1 2.4-7.8c.4-1.2-1-2.2-2-1.5L36.5 44l-6.8-4.6c-1.1-.7-2.4.3-2 1.5l2.4 7.8-6.6 5.1c-1 .8-.5 2.4.8 2.4h8.2l2.7 7.9c.4 1.2 2.2 1.2 2.6 0z"
										fill="#ffe78c" transform="matrix(1 0 0 -1 0 80)"></path>
									<g>
										<path fill="#39394f"
											d="M71.5 35.2l-6.3-6.5s-.1-.1-.2-.1V13.1c0-.4-.1-.7-.4-.9L53.5 1.1c-.2-.2-.6-.4-.9-.4H10.7c-.8 0-1.4.6-1.4 1.3v26.1l-7.4 7.1C1.1 36 .7 37 .7 38.1v41.2c0 2.2 1.8 4.1 4.1 4.1h63.8c2.3 0 4.1-1.8 4.1-4.1V38.1c0-1.1-.4-2.2-1.2-2.9zM3.3 79.3V38.9l21.8 20.7-21.7 20c0-.1-.1-.2-.1-.3zM27 61.4l3 2.8c3.7 3.6 9.6 3.6 13.3 0l3-2.8 20.8 19.2H6.2L27 61.4zm43.1 17.9c0 .2 0 .3-.1.4L48.3 59.6l21.8-20.7v40.4zm-1.2-43L65 40v-7.7l3.9 4zm-8-24.1h-7.4V4.8l7.4 7.4zM12 3.3h38.8v10.2c0 .7.6 1.3 1.3 1.3h10.2v27.6l-20.8 20c-2.7 2.6-6.9 2.6-9.6 0L12 43.5V3.3zM9.3 40.9l-4.8-4.6 4.8-4.6v9.2z">
										</path>
										<path fill="#39394f"
											d="M22.7 27.3l5.9 4.5-2.2 6.9c-.3 1.1 0 2.3 1 3 .9.7 2.2.7 3.1.1l6-4.1 6 4.1c.5.3 1 .5 1.5.5.6 0 1.1-.2 1.6-.5.9-.7 1.3-1.8 1-3l-2.2-6.9 5.9-4.5c.9-.7 1.3-1.9.9-3s-1.4-1.8-2.5-1.8h-7.3l-2.4-7c-.4-1.1-1.4-1.8-2.5-1.8-1.2 0-2.2.7-2.5 1.8l-2.4 7h-7.3c-1.2 0-2.2.7-2.5 1.8-.4 1 0 2.2.9 2.9zm9.9-2.1c.6 0 1.1-.4 1.3-.9l2.7-7.9 2.7 7.9c.2.5.7.9 1.3.9h8.2l-6.8 5c-.4.3-.6.9-.5 1.5l2.4 7.8-6.8-4.6c-.2-.2-.5-.2-.8-.2s-.5.1-.8.2L29 39.5l2.4-7.8c.2-.5 0-1.1-.5-1.5l-6.6-5.1 8.3.1z">
										</path>
									</g>
								</svg>
							</div>
							<h3 class="my-4 font-weight-bolder">You're almost done!</h3>
							<p class="my-4">
								Your blast email is ready. Select your payment method and send your emails.
							</p>
							<div class="card bg-white">
								<div class="card-body rounded">
									<div class="d-flex justify-content-between">
										<span>
											<h3 class="text-left font-weight-bolder">Total cost</h3>
											<p class="font-weight-bolder text-dark-50">3 emails to all clients</p>
										</span>
										<span>
											<h3 class="font-weight-bolder">CA $0.08</h3>
										</span>
									</div>
									<hr>
									<div class="form-group" style="position: relative;">
										<input type="hidden" name="paymentCardId" id="paymentCardId" value="{{ ($cardList[0]['id']) ? $cardList[0]['id'] : '' }}">
										
										@if(!empty($cardList))
										<input type="text" readonly="" class="form-control form-control-lg setPaymentCardDetails" placeholder="" style="cursor: pointer;" value="{{ ($cardList[0]['brand']) ? $cardList[0]['brand'] : '' }} •••• {{ ($cardList[0]['last4']) ? $cardList[0]['last4'] : '' }}">	
										@else
										<input type="text" readonly="" class="form-control form-control-lg" value="Add New Card" placeholder="" style="cursor: pointer;" value="">												
										@endif
										<a href="javascript:;" class="chng_popup" data-toggle="modal" data-target="#paymentPopup" style="top:25%">Edit</a>
									</div>
								</div>
							</div>
							<div class="my-6 d-flex justify-content-around">
								<div class="dropdown dropdown-inline mr-2">
									<button type="button"
										class="btn btn-white font-weight-bolder dropdown-toggles"
										data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
										Option</button>
									<div class="dropdown-menu text-center dropdown-menu-right">
										<ul class="navi flex-column navi-hover">
											<li class="navi-item">
												<a data-toggle="modal" data-target="#sendTestEmail"
													class="navi-link">
													<span class="navi-text">Send test email</span>
												</a>
											</li>
											<li class="navi-item">
												<a href="javascript:;" id="saveAsDraftSMSBlast" class="navi-link">
													<span class="navi-text">Save as Draft</span>
												</a>
											</li>
											<li class="navi-item">
												<a data-toggle="modal" onclick="nextPrev(-1)" class="navi-link">
													<span class="navi-text">Back to edit</span>
												</a>
											</li>
										</ul>
									</div>
								</div>
								<button type="submit" class="btn btn-primary" id="sendBlastMessagesBtn">Send Now</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal" id="clientsModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="font-weight-bolder modal-title">Clients</h4>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<div class="checkbox-list">
							<label class="checkbox">
								<input type="checkbox" id="all_clients" value="1">
								<span></span><h3><b>All clients</b></h3>
							</label>
							<div id="filteredData">
								@foreach($allClients as $key => $value)
									<label class="checkbox">
										<input type="checkbox" name="clients[]" class="clients" value="{{ $value->id }}">
										<span></span><h3><b><p data-letters="{{ substr($value->firstname,0,1) }}">{{ $value->firstname." ".$value->lastname }}</p></b></h3> &nbsp; <p style="margin-bottom: 1rem;">{{ $value->mobileno }}</p>
									</label>
								@endforeach
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
					<button type="button" class="btn btn-primary" data-dismiss="modal">Save</button>
				</div>
			</div>
		</div>
	</div>
	<!-- Service model start-->
	<div class="modal" id="servicesModal" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header d-flex justify-content-between">
					<h4 class="modal-title">Select services</h4>
					<button type="button" class="text-dark close" data-dismiss="modal">×</button>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<div class="input-icon input-icon-right">
							<input type="text" class="rounded-0 form-control"
								placeholder="Scan barcode or search any item">
							<span>
								<i class="flaticon2-search-1 icon-md"></i>
							</span>
						</div>
					</div>
					<hr class="m-0">

					<div class="multicheckbox">
						<ul id="treeview">
							<li>
								<label for="all" class="checkbox allService">
									<input type="checkbox" name="all" id="all">
									<span></span>
									All Services
								</label>
								<ul>
									@foreach($serviceCategory as $serviceKey => $serviceValue)
										<li>
											<label for="all-{{ $serviceValue['category_title'] }}" class="checkbox">
												<input type="checkbox" id="all-{{ $serviceValue['category_title'] }}">
												<span></span>
												{{ $serviceValue['category_title'] }}
											</label>
											<ul>
												@foreach ($serviceValue['service'] as $serviceData)
													@foreach ($serviceData['service_price'] as $priceKey => $servicePrice)
														<li>
															<label for="all-{{ $serviceValue['category_title'] }}-{{ $serviceData['id'] }}" class="checkbox">
																<input type="checkbox" class="allCheckboxes" name="service[]" id="all-{{ $serviceValue['category_title'] }}-{{ $serviceData['id'] }}" value="{{ $serviceData['id'] }}" {{ ($sms_type_int == 2 && in_array($serviceData['id'], $services)) ? 'checked' : "" }}>
																<span></span>
																<div class="d-flex align-items-center w-100">
																	<span class="m-0">
																		<?php
																		$totalMinutes = $servicePrice['duration'];
																		$hours = intval($totalMinutes/60);
																		$minutes = $totalMinutes - ($hours * 60);
																		$duration = $hours."h ".$minutes."min";
																		?>
																		{{ $serviceData['service_name'] }}
																		<p class="m-0 text-muted">p{{ $priceKey + 1 }},{{ $duration }}</p>
																	</span>
																	<span class="ml-auto">
																		CA ${{ $servicePrice['price'] }}
																	</span>
																</div>
															</label>
														</li>
													@endforeach
												@endforeach
											</ul>
										</li>	
									@endforeach
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
	<input type="hidden" id="amount" name="amount" value="">
	<input type="hidden" id="currency" name="currency" value="CAD">
	<input type="hidden" id="orderid" name="orderid" value="1">
	
	{{ Form::close() }}
	
	<div class="modal" id="paymentPopup">
		<div class="modal-dialog">
			<form action="{{ route('addCustomerPaymentCard') }}" method="POST" id="addNewPaymentMethod">
			@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="font-weight-bolder modal-title">Payment Method</h4>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i class="text-dark fa fa-times icon-lg"></i></p>
				</div>
				<div class="modal-body">
					@if(!empty($cardList))
						@foreach($cardList as $key => $cardData)
							<div class="form-group">
								<label class="radio">
									<input type="radio" class="book_now_link" name="paymentCardType" value="{{ $cardData['id'] }}" @if($key == 0) checked @endif />
									<span></span>&nbsp;{{ $cardData['brand'] }} •••• {{ $cardData['last4'] }}
								</label>
							</div>
						@endforeach
					@endif
					<div class="form-group">
						<label class="radio">
							<input type="radio" class="book_now_link" @if(empty($cardList)) checked="checked" @endif name="paymentCardType" value="newCard" />
							<span></span>&nbsp;
							Add New Detail
						</label>
					</div>
					<div class="addNewCardDetails" @if(!empty($cardList)) style="display:none;" @endif>
						<div class="form-group">
							<label class="font-weight-bold">Card holder name</label>
							<input type="text" class="form-control" placeholder="Card holder name" name="card_holder_name" />
						</div>
						<div class="form-group">
							<label class="font-weight-bold">Card number</label>
							<input type="text" id="card_number" class="form-control card-number" placeholder="Credit or debit card number" name="card_number" />
						</div>
						<div class="row">
							<div class="col-md-6">
								<div class="form-group">
									<label class="font-weight-bold">Expiry Date</label>
									<input type="text" class="form-control card-expiry-month" id="expiry_date" placeholder="MM/YYYY" name="expiry_date" />
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group">
									<label class="font-weight-bold">CVV</label>
									<input type="number" class="form-control card-cvc" id="cvv" placeholder="123" name="cvv" />
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary" id="addPaymentMethodBtn">Set payment card</button>
				</div>
			</div>
			
			</form>
		</div>
	</div>
	<!-- Service model end -->
	
</div>

<!-- Modal -->

<div class="modal" id="sendTestEmail">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="font-weight-bolder modal-title">Send a test email</h4>
				<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
						class="text-dark fa fa-times icon-lg"></i>
				</p>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="font-weight-bold">Email</label>
					<input type="email" class="form-control" placeholder="email@gmail.com" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary">Reset</button>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->

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
<!-- Modal -->


<div class="modal" id="sendTestEmail">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="font-weight-bolder modal-title">Send a test email</h4>
				<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
						class="text-dark fa fa-times icon-lg"></i>
				</p>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="font-weight-bold">Email</label>
					<input type="email" class="form-control" placeholder="email@gmail.com" />
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary">Reset</button>
			</div>
		</div>
	</div>
</div>

<div id="filemanager" class="modal" role="dialog">
	<div class="modal-dialog modal-md modal-lg">
		<div class="modal-content">
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
			<div class="modal-body">
				<div class="row">
					<div class="col-sm-12 col-md-12">
						<div class="panel panel-bd card-box">
							<div class="panel-heading">
								<div class="panel-title">
									<div class="btn-group pull-right"> 
										<div style="position:relative;">
											<a class='btn btn-primary' href='javascript:;'>
												<i class="fa fa-upload"></i> Upload Files
												<input type="file" accept="image/*" style='position:absolute;z-index:2;top:0;left:0;filter: alpha(opacity=0);-ms-filter:"progid:DXImageTransform.Microsoft.Alpha(Opacity=0)";opacity:0;background-color:transparent;color:transparent;' id="email_blast_images" name="blast_email_images[]" multiple size="40">
											</a>
										</div>
									</div>
									<h4>Service Images</h4>
								</div>
							</div>
							<div class="panel-body newtable" id="newtable">
								<div class="row" id="allmenuimages">
									<?php 
										$path = public_path('uploads/email_blast_images');
										$imagedata = File::allfiles($path);
										foreach($imagedata as $emailImg){
										?>
											<div class="col-lg-2 col-xl-2">
												<div class="file-man-box">
													<div class="file-img-box">
														<img src="{{ url('public/uploads/email_blast_images/'.$emailImg->getFilename()) }}" data-scr="{{ $emailImg->getFilename() }}" alt="icon" height="80" width="80" id="emailImg">
													</div>
												</div>
											</div>
										<?php
										} 
									?>	   
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal-footer">
		</div>
	</div>
</div>

<div class="modal" id="newClientModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="font-weight-bolder modal-title">Edit New clients</h4>
				<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
						class="text-dark fa fa-times icon-lg"></i>
				</p>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="font-weight-bolder">Clients added in the last</label>
					<div class="d-flex justify-content-between">
						<input type="text" id="day_value" class="form-control" value="30" />
						<select class="ml-3 form-control" name="validTime" id="selecte_time">
							<option>Days</option>
							<option>Weeks</option>
							<option>Months</option>
						</select>
					</div>

				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary saveEditClient">Save</button>
			</div>
		</div>
	</div>
</div>
<div class="modal" id="loyalClientModel" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="loyalClientModel" aria-modal="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="#" method="POST" id="updateLoyalClient">
				<div class="modal-header">
					<h4 class="font-weight-bolder modal-title">Adjust Loyal Clients</h4>
					<p class="cursor-pointer m-0 px-2 closeLapsedMdl" data-dismiss="modal"><i class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="font-weight-bold">Clients with at least</label>
						<div class="d-flex align-items-center">
							<input type="text" class="form-control numbers" name="minSalesCountLoyal" id="minSalesCountLoyal" value="3" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
							<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Sales</span>
						</div>
					</div>
					<div class="form-group">
						<label class="font-weight-bold">in the last</label>
						<div class="d-flex align-items-center">
							<input type="text" class="form-control numbers" name="maxMonthsConsideredLoyal" id="maxMonthsConsideredLoyal" value="2" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
							<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Months</span>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12" id="who_spent">
							<div class="form-group">
								<label class="font-weight-bold">who spent</label>
								<div class="d-flex align-items-center">
									<select class="form-control" name="who_spent_type" id="who_spent_type">
										<option value="1">any amount</option>
										<option value="2">at least</option>
									</select>
								</div>
							</div>
						</div>
						<div class="col-md-6" id="at_least" style="display: none;">
							<div class="form-group">
								<label class="font-weight-bold">at least</label>
								<div class="d-flex align-items-center">
									<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0.42rem 0 0 0.42rem; border-right: 0;">CAD</span>
									<input type="text" class="form-control numbers" name="at_least_spent" id="at_least_spent" value="" style="border-top-left-radius: 0;  border-bottom-left-radius: 0"> 
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-dark btn-block saveEditClient">Apply</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal" id="LapsedClientM" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="LapsedClientM" aria-modal="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<form action="#" method="POST" id="updateLapsedClients">
				<div class="modal-header">
					<h4 class="font-weight-bolder modal-title">Adjust Lapsed Clients</h4>
					<p class="cursor-pointer m-0 px-2 closeLapsedMdl" data-dismiss="modal"><i class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="font-weight-bold">Clients with at least</label>
						<div class="d-flex align-items-center">
							<input type="text" class="form-control numbers" name="minSalesCount" id="minSalesCount" value="3" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
							<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Sales</span>
						</div>
					</div>
					<div class="form-group">
						<label class="font-weight-bold">in the last</label>
						<div class="d-flex align-items-center">
							<input type="text" class="form-control numbers" name="maxMonthsConsidered" id="maxMonthsConsidered"
							 value="6" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
							<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Months</span>
						</div>
					</div>
					<div class="form-group">
						<label class="font-weight-bold">who did not return in the last</label>
						<div class="d-flex align-items-center">
							<input type="text" class="form-control numbers" name="minMonthsSinceLastSale" id="minMonthsSinceLastSale" value="2" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
							<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Months</span>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-dark btn-block saveEditClient">Apply</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal" id="editClientModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="font-weight-bolder modal-title">Edit Lapsed clients</h4>
				<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
						class="text-dark fa fa-times icon-lg"></i>
				</p>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<label class="font-weight-bolder">Clients with at least</label>
					<input type="text" class="form-control" value="3" />
				</div>
				<div class="form-group">
					<label class="font-weight-bolder">in the last</label>
					<input type="text" class="form-control" value="6" />
				</div>
				<div class="form-group">
					<label class="font-weight-bolder">who did not return in the last</label>
					<select class="ml-3 form-control" name="validTime">
						<option>Days</option>
						<option>Weeks</option>
						<option>Months</option>
					</select>

				</div>
				<div class="form-group">
					<label class="font-weight-bolder">who spent</label>
					<select class="ml-3 form-control" name="validTime">
						<option>any amount</option>
						<option>any least</option>
					</select>

				</div>

			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
				<button type="button" class="btn btn-primary">Reset</button>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="servicesModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<!-- Modal Header -->
			<div class="modal-header d-flex justify-content-between">
				<h4 class="modal-title">Select services</h4>
				<button type="button" class="text-dark close" data-dismiss="modal">×</button>
			</div>
			<!-- Modal body -->
			<div class="modal-body">
				<div class="form-group">
					<div class="input-icon input-icon-right">
						<input type="text" class="rounded-0 form-control"
							placeholder="Scan barcode or search any item">
						<span>
							<i class="flaticon2-search-1 icon-md"></i>
						</span>
					</div>
				</div>
				<hr class="m-0">

				<div class="multicheckbox">
					<ul id="treeview">
						<li>
							<label for="all" class="checkbox allService">
								<input type="checkbox" name="all" id="all">
								<span></span>
								All Services
							</label>
							<ul>
								<li>
									<label for="all-spa" class="checkbox">
										<input type="checkbox" name="all-spa" id="all-spa">
										<span></span>
										Spa
									</label>
									<ul>
										<li>
											<label for="all-spa-1" class="checkbox">
												<input type="checkbox" name="value_checkbox" id="all-spa-1">
												<span></span>
												<div class="d-flex align-items-center w-100">
													<span class="m-0">
														Blow Dry
														<p class="m-0 text-muted">1h 30min</p>
													</span>
													<span class="ml-auto">
														CA $30
													</span>
												</div>
											</label>
										</li>
									</ul>
								</li>
								<li>
									<label for="all-hair" class="checkbox">
										<input type="checkbox" name="all-hair" id="all-hair">
										<span></span>
										Hair
									</label>

									<ul>
										<li>
											<label for="all-hair-1" class="checkbox">
												<input type="checkbox" name="value_checkbox" id="all-hair-1">
												<span></span>
												<div class="d-flex align-items-center w-100">
													<span class="m-0">
														Hair Cut
														<p class="m-0 text-muted">1h 30min</p>
													</span>
													<span class="ml-auto">
														CA $25
													</span>
												</div>
											</label>
										</li>
										<li>
											<label for="all-hair-2" class="checkbox">
												<input type="checkbox" name="value_checkbox" id="all-hair-2">
												<span></span>
												<div class="d-flex align-items-center w-100">
													<span class="m-0">
														Hair Cuts
														<p class="m-0 text-muted">1h</p>
													</span>
													<span class="ml-auto">
														CA $30
													</span>
												</div>
											</label>
										</li>
									</ul>
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

<div class="modal" id="PreviewBirthdayModal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="font-weight-bolder modal-title">Preview Campaign</h4>
				<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
						class="text-dark fa fa-times icon-lg"></i>
				</p>
			</div>
			<div class="modal-body">
				<div class="card">
					<div class="card-body">
						<h3>Gym area</h3>
						<p class="text-dark-50">Canada Way, Burnaby</p>
						<div class="card-img" style="margin:20px auto;height: auto;width:400px">
							<img alt="img" width="100%" height="auto" class="rounded"
								src="./assets/images/online-profile.jpg" />
						</div>
						<h2 class="font-weight-bolder" id="headline-text">First time's the charm!</h2>
						<h6 class="text-dark-50 my-4" id="body-text">
							We hope you enjoyed your first visit, we'd love to see you again soon so
							we're offering a discount just for you!
						</h6>
						<hr>
						<div class="preview-card-discount">
							<div class="detail">
								<h1 class="my-4 font-weight-bolder text-uppercase">
									<span class="isPrice">$</span><span class="discount-text">10</span><span
										class="isPercent">%</span>
									Off
								</h1>
								<p>your next <span class="appoinmentLimit"></span> appointment</p>
								<div class="text-uppercase btn btn-light">
									Book Now
								</div>
							</div>
						</div>
						<p class="text-center my-4">Applies to <span class="servicesApply">0 service</span>, valid for <span class="validFor">5</span> days</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->

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
<!--end::Scrolltop-->
<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>

<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js') }}"></script>
<script src="{{ asset('js/createBlastMessages.js') }}"></script>
<script>
	$("#treeview").hummingbird();
	$("#treeview").on("CheckUncheckDone", function () {
		var count = $('input[name="service[]"]:checked').length;
		var allCount = $('input[type="service[]"]:checked').length;
		var allCheck = $('.allCheckboxes').length;
		if (allCheck === allCount) {
			$(".servicesApply").text('All Services')
			$("#serviceInput").val("All Services")
		} else {
			if(count == 1)
			{
				$(".servicesApply").text(count+" Service");
				$("#serviceInput").val(count+" Service");
			}
			else
			{
				$(".servicesApply").text(count+" Services");
				$("#serviceInput").val(count+" Services");
			}
		}
	});
</script>

<!-- Modal Step Hide Show -->
<script>
	var currentTab = 0; // Current tab is set to be the first tab (0)
	showTab(currentTab); // Display the current tab

	function showTab(n) {
		// This function will display the specified tab of the form...
		var tab = document.getElementsByClassName("edit-content-tab");
		tab[n].style.display = "block";
		if (n == (tab.length - 1)) {
			$(".last-hide").hide();
			$(".last-show").hide();
			$(".next-step").hide();
			$(".previous").show();
			$(".steps").text("3");

		} else {
			$(".last-show").show();
			$(".last-hide").show();
			$(".next-step").show();
			$(".previous").hide();
			$(".next-step").text("Next Step");
		}

		if (n == 0) {
			$(".steps").text("1");
			$(".page-title").text("Email message");
		} else if (n == 1) {
			$(".previous").show();
			$(".steps").text("2");
			$(".page-title").text("Customise message");
		} else if (n == 2) {
			$(".previous").show();
			$(".steps").text("3");
			$(".page-title").text("Choose the audience");
		}
		else if (n == 3) {
			$(".previous").show();
			$(".steps").text("4");
			$(".page-title").text("Choose a payment method and send your message");
		}
	}

	function nextPrev(n) {
		var tab = document.getElementsByClassName("edit-content-tab");
		if(currentTab == 1 && n > 0)
		{
			if($("input[name='message_name']").val() == "")
			{
				validationMsg('error','Message name is required');
				return false;		
			}
			else if($("#message").val() == "")
			{
				validationMsg('error','Message description is required');
				return false;		
			}
			else if($("input[name='isLink']:checked").val() == 2 && isUrlValid($("input[name='btn-url']").val()) == false)
			{
				validationMsg('error','Please enter valid link url');
				return false;		
			}
		}
		tab[currentTab].style.display = "none";
		currentTab = currentTab + n;
		showTab(currentTab);
	}
	function isUrlValid(url) 
	{
	    return /^(https?|s?ftp):\/\/(((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:)*@)?(((\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.(\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5]))|((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.?)(:\d*)?)(\/((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)+(\/(([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)*)*)?)?(\?((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|[\uE000-\uF8FF]|\/|\?)*)?(#((([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(%[\da-f]{2})|[!\$&'\(\)\*\+,;=]|:|@)|\/|\?)*)?$/i.test(url);
	}
	function validationMsg(type,message)
	{
		if(type == "success")
		{
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

			toastr.success(message);
		}
		else
		{

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

			toastr.error(message);
			return false;
		}
	}
	$('.client_group_model').hide();
	$('#new_client_group_modify').show();
	$('.client_group').on('change',function(){
		var thisid = $(this).attr('data-modify');
		
		$('.client_group_model').hide();
		if($(this).prop('checked') == true){
			$('#'+thisid).show();
		}
	});
</script>
<script>
	$(document).ready(function () {
		$(".title-input").keyup(function () {
			$('.headline-text').text($(this).val())
		});
		$(".message").keyup(function () {
			$('.body-text').html($(this).val().replace(/\n/g, '<br />'))
		});
		$(".discount-input").keyup(function () {
			$('.discount-text').html($(this).val())
		});


		$(".isPrice").show();
		$(".isPercent").hide();
	})
	function appoinmentLimit(data) {
		if (data.value === "unlimited") {
			$('.appoinmentLimit').text("")
		} else {
			$('.appoinmentLimit').text(data.value)
		}
	}
	function validFor(data) {
		if (data.value === "unlimited") {
			$('.validFor').text("")
		} else {
			$('.validFor').text(data.value)
		}
	}
	function getDiscountType() {
		var type = $('input[name="discount-type"]:checked').val();
		if (type === '1') {
			$(".isPrice").show();
			$(".isPercent").hide();
		} else {
			$(".isPrice").hide();
			$(".isPercent").show();
		}
	}

	$(".isUpdate").show();
	$(".isSpecialOffer").hide();
	$(".isVoucher").hide();
	getMessageType();
	function getMessageType() {
		var type = "<?php echo $sms_type_int; ?>";
		if (type === '1') {
			$(".isUpdate").show();
			$(".isSpecialOffer").hide();
			$(".isVoucher").hide();
		} else if (type === '3') {
			$(".isUpdate").hide();
			$(".isSpecialOffer").hide();
			$(".isVoucher").show();
		} else if (type === '2') {
			$(".isUpdate").hide();
			$(".isSpecialOffer").show();
			$(".isVoucher").hide();
		}
	}

	$("#title-input").keyup(function () {
		$('#headline-text').text($(this).val())
	});
	$(".message").keyup(function () {
		$('.body-text').html($(this).val().replace(/\n/g, '<br />'))
	});

	$("#linkUrl").keyup(function () {
		$('.linkUrl').text($(this).val())
	});
	var isLink = "<?php echo $is_link ?>";
	(isLink == '2') ? $('.isCustomLink').show() : $('.isCustomLink').hide();
	
	$('.book_now_link').on('change',function(){
		var thisVal = $(this).val();
		$('.linkUrl isLink').hide();
		if(thisVal == 1)
		{
			$('.linkUrl').show();
		}
		else if(thisVal == 2)
		{
			$('.isCustomLink').show();
			$('.linkUrl').show();
		}
		else 
		{
			$('.isCustomLink').hide();
			$('.linkUrl').hide();
		}
	});

	$(".isClientGroups").hide();
	function checkAudience() {
		var type = $('input[name="audience"]:checked').val();
		if (type == '1') {
			$(".isClientGroups").hide();
		} else if (type == '2') {
			var selectedVal = $('.client_group:checked').val();
			$('.client_group:checked').trigger('click');
			$(".isClientGroups").show();
		}
	}

	$(document).on('click','.client_group_model',function(){
		var modelId = $(this).attr('data-model');
		$('#day_value').attr('data-group-type',$(this).data('type'));
		$('#'+modelId).modal('show');
	});
	$(document).on('click','.saveEditClient',function(){
		var group_type = $('#day_value').attr('data-group-type');
		if(group_type == 'new_client')
		{
			var dayValue = $('#day_value').val();
			var selected_time = $('#selecte_time').val();
			$('#newClientText').html('Clients added in the last '+dayValue+' '+selected_time)
			$('#new_client').attr('data-value',dayValue);
			$('#new_client').attr('data-time',selected_time);
			$('#newClientModal').modal('hide');
			$('#new_client').trigger('click');
		}
		else if(group_type == 'recent_client')
		{
			var dayValue = $('#day_value').val();
			var selected_time = $('#selecte_time').val();
			$('#recentClientText').html('Clients with their first sale in the last '+dayValue+' '+selected_time)
			$('#new_client').attr('data-value',dayValue);
			$('#new_client').attr('data-time',selected_time);
			$('#newClientModal').modal('hide');
			$('#recent_client').trigger('click');	
		}
		else if(group_type == 'loyal_client')
		{
			var min_sales_count = $('#minSalesCountLoyal').val();
			var max_month = $('#maxMonthsConsideredLoyal').val();
			var who_spent_type = $('#who_spent_type').val();
			var at_least_spent = $('#at_least_spent').val();

			var content = "";
			if(who_spent_type == 1){
				content = 'Clients with '+min_sales_count+' or more sales, within the last '+max_month+' month period.'
			}else{
				content = 'Clients with '+min_sales_count+' or more sales worth at least CA $'+at_least_spent+' in total, within the last '+max_month+' month period.'
			}
			$('#loyalClientText').html(content);
			
			$('#new_client').attr('data-min-sales',min_sales_count);
			$('#new_client').attr('data-max-month',max_month);
			$('#new_client').attr('data-spent-type',who_spent_type);
			$('#new_client').attr('data-atleast-spent',at_least_spent);
			$('#loyalClientModel').modal('hide');
			$('#loyal_client').trigger('click');
		}
		else if(group_type == 'lapsed_client')
		{
			var min_sales_count = $('#minSalesCount').val();
			var max_month = $('#maxMonthsConsidered').val();
			var min_month_since_last_sale = $('#minMonthsSinceLastSale').val();

			var content = 'Clients with at least '+min_sales_count+' sales anytime in the last '+max_month+' months, but did not return in the last '+min_month_since_last_sale+' months';
			$('#loyalClientText').html(content);
			
			$('#new_client').attr('data-min-sales',min_sales_count);
			$('#new_client').attr('data-max-month',max_month);
			$('#new_client').attr('data-since-last-sale',min_month_since_last_sale);
			$('#loyalClientModel').modal('hide');
			$('#loyal_client').trigger('click');
		}
	});
	$(document).on('click', '.client_group', function(){
		var id = $(this).val();
		var type = $(this).attr('data-type');
		var value = $(this).attr('data-value');
		var time = $(this).attr('data-time');

		var min_sales_count = $(this).attr('data-min-sales');
		var max_month_considered = $(this).attr('data-max-month');
		var who_spent_type = $(this).attr('data-spent-type');
		var at_least_spent = $(this).attr('data-atleast-spent');
		var since_last_sale = $(this).attr('data-since-last-sale');

		var url = $('#client_group_url').val();
		var dataString = 'id='+id+'&type='+type+'&value='+value+'&time='+time+'&min_sales_count='+min_sales_count+'&max_month_considered='+max_month_considered+'&who_spent_type='+who_spent_type+'&at_least_spent='+at_least_spent+'&since_last_sale='+since_last_sale;
		$.ajaxSetup({
		   headers: {
		     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		   }
		});
		$.ajax({
			type:'POST',
			url:url,
			data:dataString,
			success:function(resp){
				if(resp.status == true)
				{
					$('#filteredData').html(resp.html);
					TotalChargeCalculation();
				}
			}
		});
	});

	$(".isSocialMedia").hide();
	function isSocialMedia() {
		if (type = $('input[name="isSocial"]:checked').val()) {
			$(".isSocialMedia").show();
		} else {
			$(".isSocialMedia").hide();
		}
	}
	/*$(document).on('submit', '#emailSMSForm' ,function(e){
		e.preventDefault();
		var url = $(this).attr('action');
		var data = $(this).serialize();
		$.ajax({
			type:'post',
			url:url,
			data:data,
			success:function(resp){
				if(resp.status == true)
				{
					validationMsg('success',resp.message)
				}
				else
				{
					validationMsg('error',resp.message)
				}
			}
		});
	});*/
	$('#all_clients').on('change',function(){
		$('.clients').prop('checked',$(this).prop('checked'));
		TotalChargeCalculation();
	});
	$('.clients').on('change',function(){
		TotalChargeCalculation();	
	});
	$('#all_clients').trigger('click');
	function TotalChargeCalculation()
	{
		var totalClients = $('.clients:checked').length;
		
		//var total_pr_client = 0.0312 * totalClients;
		var total_pr_client = 1 * totalClients;
		
		var total_gst = total_pr_client * 5 / 100;
		var total_cost = total_pr_client + total_gst
		$('#amount').val(total_pr_client);
		$('#total_pr_client').html(parseFloat(total_pr_client).toFixed(2));
		$('#subtotal').html(parseFloat(total_pr_client).toFixed(2));
		$('#total_gst').html(parseFloat(total_gst).toFixed(2));
		$('.total_cost').html(parseFloat(total_cost).toFixed(2));
		$('.total_clients').html(totalClients+" Clients");
		$('#clientsInput').val(totalClients+" Clients");
		
		//$('#message_price').val(0.0312);
		$('#message_price').val(1);
		
		$('#total_payable_price').val(total_cost.toFixed(2));
	}
	$('#card_number').mask('0000 0000 0000 0000');
	$('#cvv').mask('000');
	$('#expiry_date').mask('00/0000');
	
	$(document).on('click','#saveAsDraftSMSBlast',function(){
		var formData = new FormData($("#emailSMSForm")[0]);
		$.ajax({
			type:'POST',
			url: "{{ route('saveAsDraftSMSBlast') }}",
			data:formData,
			contentType: false,
			processData: false,
			success:function(response){
				if(response.status == true) {
					toastr.options = {
						"closeButton": false,
						"debug": false,
						"newestOnTop": true,
						"progressBar": true,
						"positionClass": "toast-top-right",
						"preventDuplicates": false,
						"onclick": null,
						"showDuration": "3000",
						"hideDuration": "1000",
						"timeOut": "4000",
						"extendedTimeOut": "1000",
						"showEasing": "swing",
						"hideEasing": "linear",
						"showMethod": "fadeIn",
						"hideMethod": "fadeOut"
					};
					toastr.success(response.message);
					
					setTimeout(function(){ 
						window.location.href=response.redirect;
					}, 3000);
				} else {
					toastr.options = {
						"closeButton": false,
						"debug": false,
						"newestOnTop": true,
						"progressBar": true,
						"positionClass": "toast-top-right",
						"preventDuplicates": false,
						"onclick": null,
						"showDuration": "5000",
						"hideDuration": "1000",
						"timeOut": "4000",
						"extendedTimeOut": "1000",
						"showEasing": "swing",
						"hideEasing": "linear",
						"showMethod": "fadeIn",
						"hideMethod": "fadeOut"
					};
					toastr.error((response.message) ? response.message : "Something went wrong!");
					
					setTimeout(function(){ 
						window.location.href=response.redirect;
					}, 5000);
				}
			},
			error: function(data){
				var errors = data.responseJSON;
				var errorsHtml='';
				$.each(errors.errors, function( key, value ) {
					errorsHtml += value[0];
				});
				
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
				toastr.error((errorsHtml) ? errorsHtml : "Something went wrong!");
			}
		});
	});
</script>
@endsection