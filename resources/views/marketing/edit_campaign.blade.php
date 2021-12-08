`{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss')
<style type="text/css">
	body{
		-ms-flex-direction: inherit;
    	flex-direction: inherit;
	}
	.file-man-box {
	    cursor: pointer;
	}
	ul.thumbnails.image_picker_selector li .thumbnail {
	    padding: 0px;
	    border: 2px solid #dee3e7;
	    width: 128px;
	    height: 118px; 
	    display: flex;
	    align-items: center;
	    justify-content: center;
	    overflow: hidden;
	}
	ul.thumbnails.image_picker_selector li .thumbnail.selected{
		background: transparent;
		border-color: #037aff;
	}
	ul.thumbnails.image_picker_selector li .thumbnail.selected img{
		border: none !important;
	}
	.image_picker_image {
	    cursor: pointer;
	    object-fit: cover;
	    max-width: 100%;
	    max-height: 100%;
	}
	ul.thumbnails.image_picker_selector li .thumbnail img[image-library="schedule_library"]{
		border: none !important;
	}
	ul.thumbnails.image_picker_selector li .thumbnail img[image-library="campaign_images"] {
	    width: 100%;
	    height: 100%;
	    object-fit: cover;
	    object-position: center;
	}
	ul.thumbnails.image_picker_selector li .thumbnail img[image-library="campaign_images"] ~ span.remove{
		display: none;
	}
	ul.thumbnails.image_picker_selector li .thumbnail:hover img[image-library="campaign_images"] ~ span.remove{
		display: block;
	}
	ul.thumbnails.image_picker_selector li {
		position: relative;
	}
</style>
@endsection
@section('content')

@php
	$id = 0;
	$user_id = 0;
	$campaign_type = 0;
	$email_subject = '';
	$headline_text = "";
	$body_text = "";
	$campaign_content = 0;
	$discount_value = 10;
	$discount_type = 2;
	$day_before_birthday = "";
	$min_sales_count = "";
	$max_month_considered = "";
	$min_amount_type = "";
	$min_month_since_last_sale = "";
	$at_least_spent_amount = "";
	$appoinment_limit = 0;
	$valid_for = 0;
	$birthday_email_send_on = 0;
	$services = 0;
	$image_path = 'public/uploads/campaign_images/campaign_default.png';
	$enable_sales = 0;
@endphp
@if(!empty($smartCampaign))
	@php
	$id = $smartCampaign->id;
	$user_id = $smartCampaign->user_id;
	$campaign_type = $smartCampaign->campaign_type;
	$email_subject = $smartCampaign->email_subject;
	$headline_text = $smartCampaign->headline_text;
	$body_text = $smartCampaign->body_text;
	$campaign_content = $smartCampaign->campaign_content;
	$discount_value = $smartCampaign->discount_value;
	$discount_type = $smartCampaign->discount_type;
	$day_before_birthday = $smartCampaign->day_before_birthday;
	$min_sales_count = $smartCampaign->min_sales_count;
	$max_month_considered = $smartCampaign->max_month_considered;
	$min_amount_type = $smartCampaign->min_amount_type;
	$min_month_since_last_sale = $smartCampaign->min_month_since_last_sale;
	$at_least_spent_amount = $smartCampaign->at_least_spent_amount;
	$appoinment_limit = $smartCampaign->appoinment_limit;
	$valid_for = $smartCampaign->valid_for;
	$birthday_email_send_on = $smartCampaign->birthday_email_send_on;
	$services = json_decode($smartCampaign->services);
	$image_path = $smartCampaign->image_path;
	$enable_sales = $smartCampaign->enable_sales;
	@endphp
@endif
<div class="container-fluid p-0">
	<form id="smartCampaign" method="post">
		@csrf
		<input type="hidden" name="edit_id" id="edit_id" value="{{ $id }}">
		<input type="hidden" name="previewImage" id="previewImage">
		<input type="hidden" name="default_campaign_id" id="default_campaign_id" value="{{ $defaultCampaign->id }}">
		<input type="hidden" name="min_sales_count" id="min_sales_count" value="3">
		<input type="hidden" name="max_month_considered" id="max_month_considered" value="6">
		<input type="hidden" name="min_month_since_last_sale" id="min_month_since_last_sale" value="2">
		<input type="hidden" name="at_least_spent_amount" id="at_least_spent_amount" value="2">
		<input type="hidden" name="client_type" id="client_type" value="{{ ($defaultCampaign->id == 1) ? 1 : 0 }}">
		<input type="hidden" name="minAmountType" id="minAmountType" value="1">

		<div class="my-custom-body-wrapper">
			<!-- <div class="my-custom-header">
				<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
					<span class="d-flex">
						<p class="cursor-pointer m-0 px-6" onclick="window.location.href='{{ route('smart_campaigns') }}'">
							<i class="text-dark fa fa-times icon-lg"></i>
						</p>
						<p class="cursor-pointer text-blue previous" onclick="nextPrev(-1)"><i
								class="border-left mx-4"></i>Previous</p>
					</span>
					<span>
						<p class="text-dark text-center mb-0">Steps <span class="steps"></span> of 4</p>
						<h1 class="font-weight-bolder page-title">Edit Content</h1>
					</span>
					<div>
						<div class="dropdown dropdown-inline mr-2">
							<button type="button" class="btn btn-lg btn-white font-weight-bolder dropdown-toggle"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Option</button>
							<div class="dropdown-menu text-center dropdown-menu-right">
								<ul class="navi flex-column navi-hover">
									<li class="navi-item">
										<a data-toggle="modal" data-target="#sendTestEmail" class="navi-link">
											<span class="navi-text">Send test email</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
						<button type="button" class="btn btn-primary next-step" onclick="nextPrev(1)">Continue</button>
					</div>
				</div>
			</div> -->
			<div class="my-custom-header bg-white text-dark border-bottom"> 
	            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
	            	<span class="d-flex">
			            <a type="button" class="close" onclick="window.location.href='{{ route('smart_campaigns') }}'" style="display: flex; justify-content: flex-start; align-items: center;top:unset;left: 0;opacity: 1;font-size: 1.75rem;">
			                <span aria-hidden="true" class="la la-times"></span>
			            </a>
			            <p class="h6 cursor-pointer mb-0 text-blue previous" onclick="nextPrev(-1)" style=""><i class="border-left mx-4"></i>Previous</p>
			        </span>
		            <div style="flex-grow: 1;">
		            	<h6 class="text-dark text-center mb-0">Steps <span class="steps"></span> of 4</h6>
						<h1 class="font-weight-bolder page-title">Edit Content</h1>
		            </div>
		            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
		            	<div class="dropdown dropdown-inline mr-2">
							<button type="button" class="btn btn-white font-weight-bolder dropdown-toggle"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								Option</button>
							<div class="dropdown-menu text-center dropdown-menu-right">
								<ul class="navi flex-column navi-hover">
									<li class="navi-item">
										<a data-toggle="modal" data-target="#sendTestEmail" class="navi-link" style="cursor: pointer;">
											<span class="navi-text">Send test email</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
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
									<h6 class="my-3">
										Adjust the message text and upload your own images to customize the message
										appearance. <a href="#" class="text-blue">Learn more.</a>
									</h6>
									<h3 class="font-weight-bolder my-7">Header Image</h3>
									<div class="form-group">
										<label class="w-100 text-center">
											<div class="card-body" id="img-uploader" style="background-image: url(<?php echo url($image_path) ?>);background-size: 130px;background-repeat: no-repeat;background-position-x: 169px; background-size: contain;">
												<a class="btn btn-white" data-target="#filemanager" data-toggle="modal">Change Header Image</a>
											</div>
											<input type="hidden" value="{{ $image_path }}" name="image_path" id="selectedImage">
										</label>
									</div>
									<h3 class="font-weight-bolder my-6">Text Content</h3>
									<div class="form-group">
										<label class="font-weight-bolder">Email subject</label>
										<input type="text" name="email_subject" id="email-subject" value="{{ $email_subject }}" class="form-control" placeholder="Enter email subject">
										<span class="text-muted">This text will display in your client's inbox</span>
									</div>
									<div class="form-group">
										<label class="font-weight-bolder">Headline text</label>
										<input type="text" name="headline_text" id="headline-input" value="{{ $headline_text }}" class="form-control" placeholder="">
									</div>
									<div class="form-group">
										<h6 class="font-weight-bolder">Body text</h6>
										<textarea id="body-input" name="body_text" class="form-control" placeholder="Body text here..." rows="8" class="text-dark-50 font-size-lg">{{ strip_tags($body_text) }}</textarea>
									</div>
								</div>
								<div class="edit-content-tab">
									<h3 class="mb-4 font-weight-bolder">Set discount offer</h3>
									<div class="form-group d-flex align-items-center">
										<div>
											<label class="font-weight-bolder">Discount value</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text bg-white isPrice" <?php echo ($discount_type == 1) ? "style='display:block'" : "style='display:none'";?>>
														CA $
													</span>
													<span class="input-group-text bg-white isPercent" <?php echo ($discount_type == 2) ? "style='display:block'" : "style='display:none'";?>>
														%
													</span>

												</div>
												<input class="form-control numbers" id="discount-input" name="discount_value" value="{{ $discount_value }}">
												<span class="text-dark-50">
													Discount is applied individually to each applicable service
													booked
												</span>
											</div>
										</div>
										<div class="w-70 ml-4">
											<div class="tgl-radio-tabs">
												<input id="price" value="1" checked name="discount_type" type="radio" class="form-check-input tgl-radio-tab-child" onclick="getDiscountType()" {{ ($discount_type == 1) ? 'checked' : '' }}>
												
												<label for="price" class="radio-inline">CA $</label>

												<input id="percent" value="2" type="radio" class="form-check-input tgl-radio-tab-child" name="discount_type" onclick="getDiscountType()" {{ ($discount_type == 2) ? 'checked' : '' }}>
												<label for="percent" class="radio-inline">%</label>
											</div>
										</div>
									</div>
									<div class="form-group">
										<label class="font-weight-bolder">Max appointments booked</label>
										<select class="form-control" onchange="appoinmentLimit(this)"
											name="appoinment_limit">
											<option value="1" {{ ($appoinment_limit == 1) ? 'selected' : '' }}>1</option>
											<option value="3" {{ ($appoinment_limit == 3) ? 'selected' : '' }}>3</option>
											<option value="5" {{ ($appoinment_limit == 5) ? 'selected' : '' }}>5</option>
											<option value="10" {{ ($appoinment_limit == 10) ? 'selected' : '' }}>10</option>
											<option value="unlimited">No limit</option>
										</select>
									</div>
									<div class="form-group">
										<label class="font-weight-bolder">Valid for</label>
										<select class="form-control" id="valid_for" name="valid_for">
											<option value="5" {{ ($valid_for == 5) ? 'selected' : '' }}>5 days</option>
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
										<h4 class="font-weight-bolder">Apply to <span class="servicesApply">All</span>
										</h4>
										<div class="d-flex justify-content-between">
											<h6 class="mr-4 text-dark-50">
												This discount offer applies to specific services in your menu, you can
												adjust selections in the future
											</h6>
											<button type="button" data-toggle="modal" data-target="#servicesModal" class="btn btn-md btn-primary">Edit</button>
										</div>
									</div>
								</div>
								<div class="edit-content-tab">
									<h6 class="mb-8">
										This smart campaign audience is predefined, we've got this! <a href="#"
											class="text-blue">Learn more.</a>
									</h6>
									<div class="form-group w-100 d-flex extra-time">
										<label class="option align-items-center">
											<span class="option-control">
												<span class="radio">
													<input type="radio" name="m_option_1" value="1" checked="checked">
													<span></span>
												</span>
											</span>
											<span class="option-label">
												<span class="option-head">
													<span class="option-title">{{ $defaultCampaign->client_title }}</span>
												</span>
												<span class="option-body text-dark">
													@if($defaultCampaign->is_editable_client == 1)
														@if($defaultCampaign->edit_model_type == 1)
															<span id="selected_birthday_days">on the day of their birthday </span><br>
														@elseif($defaultCampaign->edit_model_type == 3)
															<span class="clientContent">
																@if($min_amount_type == 1)
																	Clients with {{ $min_sales_count }} or more sales, within the last {{ $max_month_considered }} month period.
																@elseif($min_amount_type == 2)
																	Clients with {{ $min_sales_count }} or more sales worth at least CA ${{ $at_least_spent_amount }} in total, within the last {{ $max_month_considered }} month period.
																@endif
															</span>
														@elseif($defaultCampaign->edit_model_type == 2)
															<span class="clientContent">
																Clients with at least {{ $min_sales_count }} sales anytime in the last {{ $max_month_considered }} months, but did not return in the last {{ $min_month_since_last_sale }} months
															</span>
														@endif
														<br>										
														<a href="#" data-toggle="modal" data-target="#{{ $defaultCampaign->model_id }}" style="color: #037aff; font-size: 14px;">Modify</a>
													@else
														{{ $defaultCampaign->client_content }}
													@endif


												</span>
											</span>
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-8 p-0 bg-white last-hide" style="height:calc(100vh - 80px);overflow-y:scroll" id="smartCampaignPreview">
							<div class="p-6">
								<div class="card w-70 m-auto border-light-dark">
									<div class="card-body">
										@if(count($locationData) <= 1)
											<h3>{{ $locationData[0]['location_name'] }}</h3>
											<p class="text-dark-50">{{ $locationData[0]['location_address'] }}</p>
										@else
											<h3>{{ $locationData[0]['location_name'] }}</h3>
											<p class="text-dark-50">{{ count($locationData) }} Locations</p>
										@endif
										<div class="card-img" style="margin:20px auto;height: auto;width:100px">
											<img alt="img" id="img-preview" width="100%" height="auto" class="rounded" src="{{ url($image_path) }}" />
										</div>
										<h2 class="font-weight-bolder headline-text">{{ $headline_text }}</h2>
										<p class="text-dark-50 my-4 body-text">
											@php echo $body_text @endphp
										</p>
										<hr>
										<div class="preview-card-discount">
											<div class="detail">
												<h1 class="my-4 font-weight-bolder text-uppercase">
													<span class="isPrice" style="display: {{ ($discount_type == 1) ? 'inline;' : 'none;' }}" >$</span>
													<span class="discount-text">{{ $discount_value }}</span>
													<span class="isPercent" style="display: {{ ($discount_type == 2) ? 'inline;' : 'none;' }}">%</span>
													{{-- <span class="discountType">
														{{ ($discount_type == 1) ? 'CA $' : '%' }}
													</span> --}}
													Off
												</h1>
												<p>your next <span class="appoinmentLimit">{{ $appoinment_limit }} {{ ($appoinment_limit > 1) ? 'appoinments' : 'appoinment' }}</span></p>
												<div class="text-uppercase btn btn-light">
													Book Now
												</div>
											</div>
										</div>
										<p class="text-center my-4">Applies to <span class="servicesApply">{{ count($services) }} {{ (count($services) <= 1) ? "service" : "services" }}</span>
											, valid for <span class="validFor">{{ $valid_for }}</span> days</p>
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
											<path fill="#39394f" d="M71.5 35.2l-6.3-6.5s-.1-.1-.2-.1V13.1c0-.4-.1-.7-.4-.9L53.5 1.1c-.2-.2-.6-.4-.9-.4H10.7c-.8 0-1.4.6-1.4 1.3v26.1l-7.4 7.1C1.1 36 .7 37 .7 38.1v41.2c0 2.2 1.8 4.1 4.1 4.1h63.8c2.3 0 4.1-1.8 4.1-4.1V38.1c0-1.1-.4-2.2-1.2-2.9zM3.3 79.3V38.9l21.8 20.7-21.7 20c0-.1-.1-.2-.1-.3zM27 61.4l3 2.8c3.7 3.6 9.6 3.6 13.3 0l3-2.8 20.8 19.2H6.2L27 61.4zm43.1 17.9c0 .2 0 .3-.1.4L48.3 59.6l21.8-20.7v40.4zm-1.2-43L65 40v-7.7l3.9 4zm-8-24.1h-7.4V4.8l7.4 7.4zM12 3.3h38.8v10.2c0 .7.6 1.3 1.3 1.3h10.2v27.6l-20.8 20c-2.7 2.6-6.9 2.6-9.6 0L12 43.5V3.3zM9.3 40.9l-4.8-4.6 4.8-4.6v9.2z">
											</path>
											<path fill="#39394f" d="M22.7 27.3l5.9 4.5-2.2 6.9c-.3 1.1 0 2.3 1 3 .9.7 2.2.7 3.1.1l6-4.1 6 4.1c.5.3 1 .5 1.5.5.6 0 1.1-.2 1.6-.5.9-.7 1.3-1.8 1-3l-2.2-6.9 5.9-4.5c.9-.7 1.3-1.9.9-3s-1.4-1.8-2.5-1.8h-7.3l-2.4-7c-.4-1.1-1.4-1.8-2.5-1.8-1.2 0-2.2.7-2.5 1.8l-2.4 7h-7.3c-1.2 0-2.2.7-2.5 1.8-.4 1 0 2.2.9 2.9zm9.9-2.1c.6 0 1.1-.4 1.3-.9l2.7-7.9 2.7 7.9c.2.5.7.9 1.3.9h8.2l-6.8 5c-.4.3-.6.9-.5 1.5l2.4 7.8-6.8-4.6c-.2-.2-.5-.2-.8-.2s-.5.1-.8.2L29 39.5l2.4-7.8c.2-.5 0-1.1-.5-1.5l-6.6-5.1 8.3.1z">
											</path>
										</g>
									</svg>
								</div>
								<h3 class="my-4 font-weight-bolder">Almost Done!</h3>
								<p class="my-4">Your smart campaign is ready to go, once enabled it will automatically
									send messages
									to clients on an ongoing basis. You
									can easily modify or pause this campaign at any time.</p>
								<div class="my-6 d-flex justify-content-around">
									<button type="button" class="btn btn-white" data-toggle="modal"
										data-target="#PreviewBirthdayModal">Preview</button>
									<button class="btn btn-primary" id="updateCampaign">Update Campaign</button>
								</div>
							</div>
						</div>
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
						{{-- <div class="form-group">
							<div class="input-icon input-icon-right">
								<input type="text" class="rounded-0 form-control"
									placeholder="Scan barcode or search any item">
								<span>
									<i class="flaticon2-search-1 icon-md"></i>
								</span>
							</div>
						</div> --}}
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
												<label for="all-{{ str_replace(' ','',$serviceValue['category_title']) }}" class="checkbox">
													<input type="checkbox" id="all-{{ str_replace(' ','',$serviceValue['category_title']) }}" class="allCategory" data-count="{{ count($serviceCategory) }}">
													<span></span>
													{{ $serviceValue['category_title'] }}
												</label>
												<ul>
													@foreach ($serviceValue['service'] as $serviceData)
														@foreach ($serviceData['service_price'] as $priceKey => $servicePrice)
															<li>
																<label for="all-{{ str_replace(' ','',$serviceValue['category_title']) }}-{{ $serviceData['id'] }}-{{ $priceKey }}" class="checkbox">
																	<input type="checkbox" name="service[]" id="all-{{ str_replace(' ','',$serviceValue['category_title']) }}-{{ $serviceData['id'] }}-{{ $priceKey }}" value="{{ $serviceData['id'] }}" {{ (in_array($serviceData['id'], $services)) ? 'checked' : '' }} data-count="{{ count($serviceValue['service']) }}" class="servicePrices" data-cat="all-{{ str_replace(' ','',$serviceValue['category_title']) }}">
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
		<div class="modal" id="BirthdayModel">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h4 class="font-weight-bolder modal-title">Clients By Birthday</h4>
						<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
								class="text-dark fa fa-times icon-lg"></i>
						</p>
					</div>
					<div class="modal-body">
						<div class="form-group">
							<label class="font-weight-bold">Automatically sends to clients:</label>
							<select class="form-control" name="daysBeforeBirthday" id="daysBeforeBirthday">
								<option value="0" {{ ($smartCampaign->day_before_birthday == 1) ? 'selected="selected"' : '' }} data-text="on the day of their birthday">On the day of their birthday</option>
								<option value="3" {{ ($smartCampaign->day_before_birthday == 3) ? 'selected="selected"' : '' }} data-text="3 days before their birthday">3 days before their birthday</option>
								<option value="7" {{ ($smartCampaign->day_before_birthday == 7) ? 'selected="selected"' : '' }} data-text="a week before their birthday">One week before their birthday</option>
								<option value="14" {{ ($smartCampaign->day_before_birthday == 14) ? 'selected="selected"' : '' }} data-text="two weeks before their birthday">Two weeks before their birthday</option>
							</select>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-primary" data-dismiss="modal" style="width: 100%;background: black;">Apply</button>
					</div>
				</div>
			</div>
		</div>
		<!-- Service model end -->
	</form>
</div>
<!-- Modal -->
<div class="modal" id="filemanager" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="font-weight-bolder modal-title">Choose a photo</h4>
				<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i class="text-dark fa fa-times icon-lg"></i></p>
			</div>
			<div class="modal-body">
				<ul class="nav nav-pills round-nav mx-auto" id="myTab1" role="tablist" style="width: max-content;">
					<li class="nav-item font-weight-bolder">
						<a class="nav-link active" id="uploaded-tab-1" data-toggle="tab" href="#uploaded">
							<span class="nav-text">Uploaded images</span>
						</a>
					</li>
					<li class="nav-item font-weight-bolder">
						<a class="nav-link" id="library-tab-1" data-toggle="tab" href="#library"
							aria-controls="library">
							<span class="nav-text">Schedule library</span>
						</a>
					</li>
				</ul>
				<div class="tab-content" id="myTabContent1">
					<div class="tab-pane fade active show" id="uploaded" role="tabpanel"
						aria-labelledby="uploaded-tab-1">
						<div class="card mx-auto mt-16 border-0">
							<div class="row filesDiv m-0">
								<select id="filesDiv" style="position: relative;" class="image-picker show-html">
									<?php 
										$path = public_path('uploads/campaign_images/');
										$imagedata = File::allfiles($path);
										foreach($imagedata as $imgKey => $campaignImg)
										{
										?>
										<option data-img-src="{{ url('public/uploads/campaign_images/'.$campaignImg->getFilename()) }}" data-img-alt="Page 2"
										value="{{ $imgKey }}" data-scr="{{ $campaignImg->getFilename() }}" data-lib="campaign_images"> img {{ $imgKey }} </option>
										
										<?php
										} 
									?>
								</select>
								<!-- <ul class="thumbnails image_picker_selector custom-image-picker">
									<?php 
										$path = public_path('uploads/campaign_images/');
										$imagedata = File::allfiles($path);
										foreach($imagedata as $imgKey => $campaignImg)
										{
										?>
										<li>
											<span class="p-4 pip" style='position:relative'>
												<img height='100px' width='120px' class="imageThumb" src="{{ url('public/uploads/campaign_images/'.$campaignImg->getFilename()) }}" title="{{ $campaignImg->getFilename() }}"/>
												<br/>
												<span class="remove btn btn-white btn-sm p-1" data-filename="{{ $campaignImg->getFilename() }}" style="position:absolute;top: -21px;left: 15px;"><i class="delete-icon p-0 fa fa-trash text-danger"></i></span>
											</span>
										</li>
										<?php
										} 
									?>									
								</ul> -->
								<form type="post" id="ImageUploadForm" action="{{ url('partners/marketing/uploadCampaignImage') }}">
									@csrf
									<label class="" style="height: 120px;width: 130px;">
										<input type="file" id="files" name="files[]" multiple="" class="d-none">
										<div class="h-100 card mx-auto">
											<div class="h-100 py-6 rounded cursor-pointer"
												style="background-color: #e5f1ff;border: 1px solid #037aff !important;">
												<div class="mx-auto my-4" style="width: 45px;height:45px">
													<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 51 47">
														<path
															d="M48 21.147a1 1 0 0 0 2 0V3.528C50 2.005 48.776.766 47.26.766H2.74C1.225.766 0 2.006 0 3.528v34.476c0 1.523 1.224 2.762 2.74 2.762h20.89a1 1 0 0 0 0-2H2.74a.751.751 0 0 1-.74-.762V3.528c0-.424.334-.762.74-.762h44.52c.406 0 .74.338.74.762v17.62z">
														</path>
														<path
															d="M44 21.499a1 1 0 0 0 2 0V5.617a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v30a1 1 0 0 0 1 1h19.13a1 1 0 0 0 0-2H6v-28h38v14.882z">
														</path>
														<path
															d="M19.34 14.63a4.575 4.575 0 1 0-9.149 0 4.575 4.575 0 0 0 9.15 0zm-2 0a2.575 2.575 0 1 1-5.149 0 2.575 2.575 0 0 1 5.15 0zM32.606 17.98l2.225 3.944a1 1 0 1 0 1.742-.982l-2.985-5.292a1 1 0 0 0-1.677-.101l-9.673 13.164-6.09-6.216a1 1 0 0 0-1.48.057L4.298 34.902a1 1 0 0 0 .766 1.643h19.012a1 1 0 1 0 0-2H7.21l8.281-9.86 6.142 6.268a1 1 0 0 0 1.52-.108l9.453-12.865z">
														</path>
														<path
															d="M51 35.25c0-6.192-5.02-11.212-11.213-11.212-6.192 0-11.213 5.02-11.213 11.212 0 6.193 5.02 11.213 11.213 11.213S51 41.443 51 35.25zm-2 0a9.213 9.213 0 1 1-18.426 0 9.213 9.213 0 0 1 18.426 0z">
														</path>
														<path
															d="M38.838 29.87v10.213c0 1.333 2 1.333 2 0V29.87c0-1.333-2-1.333-2 0z">
														</path>
														<path
															d="M34.68 35.76h10.214c1.333 0 1.333-2 0-2H34.68c-1.333 0-1.333 2 0 2z">
														</path>
													</svg>
												</div>
												<h6 class="text-center font-weight-bolder">Upload photo
												</h6>
											</div>
										</div>
									</label>
								</form>
							</div>
						</div>
					</div>
					<div class="tab-pane fade" id="library" role="tabpanel" aria-labelledby="library-tab-1">
						<div class="card mx-auto my-4 library-images border-0">
							<select id="library-images" style="position: relative;" class="image-picker show-html">
								<?php 
									$path = public_path('uploads/schedule_library');
									$imagedata = File::allfiles($path);
									foreach($imagedata as $imgKey => $campaignImg){
									?>
									<option data-img-src="{{ url('public/uploads/schedule_library/'.$campaignImg->getFilename()) }}" data-img-alt="Page 2"
									value="{{ $imgKey }}" data-scr="{{ $campaignImg->getFilename() }}" data-lib="schedule_library"> img {{ $imgKey }} </option>
									<?php
									} 
								?>
							</select>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
					<!-- <button type="button" class="btn btn-primary">Reset</button> -->
				</div>
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
					<div class="card-body p-12">
						@if(count($locationData) <= 1)
							<h3>{{ $locationData[0]['location_name'] }}</h3>
							<p class="text-dark-50 m-0">{{ $locationData[0]['location_address'] }}</p>
						@else
							<h3>{{ $locationData[0]['location_name'] }}</h3>
							<p class="text-dark-50 m-0">{{ count($locationData) }} Locations</p>
						@endif
						<div class="card-img" style="margin:20px auto;height: auto;width:100px">
							<img alt="img" width="100%" height="auto" class="rounded"
								src="{{ url($image_path) }}" />
						</div>
						<h1 class="font-weight-bolder headline-text mb-10">{{ $headline_text }}</h1>
						<h6 class="text-dark-50 my-4 body-text">@php echo $body_text @endphp
						</h6>
						<hr class="my-13">
						<div class="preview-card-discount">
							<div class="detail">
								<h1 class="my-4 font-weight-bolder text-uppercase">
									<span class="isPrice" style="display: {{ ($discount_type == 1) ? 'inline;' : 'none;' }}" >$</span>
									<span class="discount-text">{{ $discount_value }}</span>
									<span class="isPercent" style="display: {{ ($discount_type == 2) ? 'inline;' : 'none;' }}">%</span>
									Off
								</h1>
								<p>your next <span class="appoinmentLimit"></span> appointment</p>
								<div class="text-uppercase btn btn-light">
									Book Now
								</div>
							</div>
						</div>
						<p class="text-center mt-6 mb-0">Applies to <span class="servicesApply">All services</span>
							, valid for <span class="validFor">{{ $valid_for }}</span> days</p>
					</div>
				</div>
			</div>
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
							<input type="text" class="form-control numbers" name="minSalesCount" id="minSalesCount" value="{{ $min_sales_count }}" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
							<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Sales</span>
						</div>
						{{-- <span class="input-group-text">.00</span> --}}
					</div>
					<div class="form-group">
						<label class="font-weight-bold">in the last</label>
						<div class="d-flex align-items-center">
							<input type="text" class="form-control numbers" name="maxMonthsConsidered" id="maxMonthsConsidered" value="{{ $max_month_considered }}" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
							<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Months</span>
						</div>
						 {{-- <span class="input-group-text">.00</span> --}}
					</div>
					<div class="form-group">
						<label class="font-weight-bold">who did not return in the last</label>
						<div class="d-flex align-items-center">
							<input type="text" class="form-control numbers" name="minMonthsSinceLastSale" id="minMonthsSinceLastSale" value="{{ $min_month_since_last_sale }}" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
							<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Months</span>
						</div>
						{{-- <span class="input-group-text">.00</span> --}}
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-dark btn-block">Apply</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal" id="LoyalClientM" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="LoyalClientM" aria-modal="true">
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
							<input type="text" class="form-control numbers" name="minSalesCountLoyal" id="minSalesCountLoyal" value="{{ $min_sales_count }}" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
							<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Sales</span>
						</div>
						{{-- <span class="input-group-text">.00</span> --}}
					</div>
					<div class="form-group">
						<label class="font-weight-bold">in the last</label>
						<div class="d-flex align-items-center">
							<input type="text" class="form-control numbers" name="maxMonthsConsideredLoyal" id="maxMonthsConsideredLoyal" value="{{ $max_month_considered }}" style="border-top-right-radius: 0; border-bottom-right-radius: 0">
							<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Months</span>
						</div>
						 {{-- <span class="input-group-text">.00</span> --}}
					</div>
					<div class="row">
						<div class="col-md-{{ ($min_amount_type == 2) ? '6' : '12' }}" id="who_spent">
							<div class="form-group">
								<label class="font-weight-bold">who spent</label>
								<div class="d-flex align-items-center">
									<select class="form-control" name="who_spent_type" id="who_spent_type">
										<option value="1" {{ ($min_amount_type == 1 || $min_amount_type == "") ? 'selected' : '' }}>any amount</option>
										<option value="2" {{ ($min_amount_type == 2) ? 'selected' : '' }}>at least</option>
									</select>
									{{-- <span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-left: 0;">Months</span> --}}
								</div>
								{{-- <span class="input-group-text">.00</span> --}}
							</div>
						</div>
						<div class="col-md-6" id="at_least" style="display: {{ ($min_amount_type == 2) ? 'block' : 'none' }};">
							<div class="form-group">
								<label class="font-weight-bold">at least</label>
								<div class="d-flex align-items-center">
									<span class="" style="padding: 0.65rem 1rem; font-size: 16px; font-weight: 400; line-height: 1.50; color: #878c93; background-color: #ffffff; background-clip: padding-box; border: 1px solid #E4E6EF; border-radius: 0 0.42rem 0.42rem 0; border-right: 0;">CAD</span>
									<input type="text" class="form-control numbers" name="at_least_spent" id="at_least_spent" value="{{ $at_least_spent_amount }}" style="border-top-right-radius: 0;  border-bottom-right-radius: 0"> 
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-dark btn-block">Apply</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal" id="sendTestEmail">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="font-weight-bolder modal-title">Send a test email</h4>
				<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
						class="text-dark fa fa-times icon-lg"></i>
				</p>
			</div>
			<form id="SendCampaignMessage" method="post">
				<input type="hidden" name="id" id="sendEmailId" value="{{ $id }}">
				<div class="modal-body">
					<div class="form-group">
						<label class="font-weight-bold">Email</label>
						<input type="email" name="email" class="form-control" placeholder="email@gmail.com" required="required" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary">Send Email</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!-- End Modal -->

@endsection
{{-- Scripts Section --}}
@section('scripts')

<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
var imageUrl = "<?php echo url('public/uploads') ?>";
var baseUrl = "<?php echo url('partners/marketing') ?>";
</script>
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
<!-- <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script> -->
<!-- <script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script> -->
<!-- <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script> -->
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js') }}"></script>
<script src="{{ asset('assets/js/image-picker.js') }}"></script>
<script src="{{ asset('js/marketing.js') }}"></script>
<script>
	$(document).ready(function () {
		$("#treeview").hummingbird();
		$("#treeview").on("CheckUncheckDone", function () {
			var count = $('input[name="service[]"]:checked').length;
			var allCount = $('input[type="checkbox"]:checked').length;
			var allCheck = $('input[type="checkbox"]').length;

			if (allCheck == allCount) {
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
		});
		$('#who_spent_type').on('change',function(){
			var thisVal = $(this).val();
			console.log(thisVal);
			if(thisVal == 2){
				$('#at_least').show();
				$('#who_spent').removeClass('col-md-12');
				$('#who_spent').addClass('col-md-6');
			}else{
				$('#at_least').hide();
				$('#who_spent').removeClass('col-md-6');
				$('#who_spent').addClass('col-md-12');
			}
		});
		$('.servicePrices:checked').each(function(){
			var cat = $(this).data('cat');
			$('#'+cat).prop('checked',true);
		});
		$('.allCategory').each(function(){
			var totalCat = $(this).data('count');
			var checkedCat = $('.allCategory:checked').length;
			if(totalCat == checkedCat){
				$('#all').prop('checked',true);
			}
		});
		var allCheck = $('input[type="checkbox"]').length;
		var allCount = $('input[type="checkbox"]:checked').length;
		var count = $('input[name="service[]"]:checked').length;

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
		$("#headline-input").keyup(function () {
			$('.headline-text').text($(this).val())
		});
		$("#body-input").keyup(function () {
			$('.body-text').html($(this).val().replace(/\n/g, '<br />'))
		});
		$("#discount-input").keyup(function () {
			$('.discount-text').html($(this).val())
		});

		var thisText = $('#daysBeforeBirthday option:selected').data('text');
		$('#selected_birthday_days').html(thisText);
	});
	$(document).ready(function () {
		$("#filesDiv").imagepicker();
		$('.image_picker_image').each(function(){
			console.log('image_path:::'+$(this).attr('image_path'))
			$('<br/><span class="remove btn btn-white btn-sm p-1" data-filename="'+$(this).attr('image_path')+'" style="position:absolute;top: 8px;left: 15px;"><i class="delete-icon p-0 fa fa-trash text-danger"></i></span>').insertAfter(this);
		});

		$("#library-images").imagepicker({
			hide_select: true,
			show_label: false,
			selected: function (select) {
				console.log(select)
			}

		});
		if (window.File && window.FileList && window.FileReader) {
			$("#files").on("change", function (e) {
				var files = e.target.files,
					filesLength = files.length;
				for (var i = 0; i < filesLength; i++) {
					var f = files[i]
					var fileReader = new FileReader();
					fileReader.onload = (function (e) {
						var file = e.target;
						/*$('.custom-image-picker').append("<li><span class=\"p-4 pip\" style='position:relative'>" +
							"<img height='100px' width='120px' class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
							"<br/><span class=\"remove btn btn-white btn-sm p-1\" data-filename='"+file.name+"' style='position:absolute;top:-21px;left:15px'><i class='delete-icon p-0 fa fa-trash text-danger'></i></span>" +
							"</span></li>");*/ //.insertAfter("#filesDiv")

							$('.filesDiv').find('.image_picker_selector').append('<li><div class="thumbnail"><img class="image_picker_image" image_path="'+file.name+'" image-library="campaign_images" src="'+e.target.result+'" alt="Page 2"><br><span class="remove btn btn-white btn-sm p-1" data-filename="'+file.name+'" style="position:absolute;top: 8px;left: 15px;"><i class="delete-icon p-0 fa fa-trash text-danger"></i></span></div></li>');
						// Old code here
						/*$("<img></img>", {
						  class: "imageThumb",
						  src: e.target.result,
						  title: file.name + " | Click to remove"
						}).insertAfter("#files").click(function(){$(this).remove();});*/

					});
					fileReader.readAsDataURL(f);
				}
				// $("#ImageUploadForm").submit();
				// $('#ImageUploadForm').on('submit',(function(e) {
		        e.preventDefault();
		        var formData = new FormData(document.getElementById("ImageUploadForm"));

		        $.ajax({
		            type:'POST',
		            url: $('#ImageUploadForm').attr('action'),
		            data:formData,
		            cache:false,
		            contentType: false,
		            processData: false,
		            success:function(data){
		                validationMsg('success',data.message);

		                // $('.custom-image-picker').find('.imageThumb:last').attr('title', data.filename);
		                // $('.custom-image-picker').find('.remove:last').attr('data-filename', data.filename);
		                $('.filesDiv').find('.image_picker_selector').find('.image_picker_image:last').attr('title', data.filename);
		                $('.filesDiv').find('.image_picker_selector').find('.remove:last').attr('data-filename', data.filename);
		            },
		            error: function(data){
		                validationMsg('error',data.message);
		            }
		        });
			    // }));
			});
		} else {
			validationMsg('error',"Your browser doesn't support to File API");
		}

		$(document).on('click','.remove', function(){
			$.ajaxSetup({
			   headers: {
			     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			   }
			});

			var self = $(this);
			var filename = self.attr('data-filename');

			self.find('.delete-icon').removeClass('fa-trash').addClass('fa-spinner fa-spin');

			$.ajax({
				url: '{{ route("deleteCampaignImage") }}',
				type: 'POST',
				dataType: 'JSON',
				data: {
					filename: filename
				},
				success: function(response) {
					if(response.status) {
						// self.parent(".pip").remove();
						self.closest("li").remove();
						validationMsg('success',response.message);
					} else {
						validationMsg('error',response.message);
					}
				},
				error: function(response) {
					validationMsg('error','Something went wrong.');
				},
				complete: function(response) {

					self.find('.delete-icon').removeClass('fa-spinner').removeClass('fa-spin').addClass('fa-trash');
				}
			})
		});
	});
	var form = KTUtil.getById('updateLapsedClients');
		FormValidation
		.formValidation(
			form, {
				fields: {
					minSalesCount: {
						validators: {
							notEmpty: {
                                message: 'Clients with at least sale field is required'
                            }
						}
					},
					maxMonthsConsidered: {
						validators: {
                            callback: {
								message: 'This field must be 2 or higher',
								callback: function(input) {
									if(input.value < 2) {
										return false;
										console.log(input.value);
									} else {
										return true;
									}		
								}
							}
						}
					},
					minMonthsSinceLastSale: {
						validators: {
							notEmpty: {
                                message: 'who did not return in the last month field is required'
                            }
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					submitButton: new FormValidation.plugins.SubmitButton(),
					bootstrap: new FormValidation.plugins.Bootstrap({})
				}
			}
		)
		.on('core.form.valid', function() {
			var minSalesCount = $('#minSalesCount').val()
			var maxMonthsConsidered = $('#maxMonthsConsidered').val()
			var minMonthsSinceLastSale = $('#minMonthsSinceLastSale').val()
			$('#minAmountType').val(0);
			$('#min_sales_count').val(minSalesCount);
			$('#max_month_considered').val(maxMonthsConsidered);
			$('#min_month_since_last_sale').val(minMonthsSinceLastSale);
			var sale = (minSalesCount == 1) ? ' sale' : ' sales';
			var lastMonth = (minMonthsSinceLastSale > 1) ? minMonthsSinceLastSale+' months' : 'month';
			var lapsedContent = 'Clients with at least '+minSalesCount+sale+' anytime in the last '+maxMonthsConsidered+' months, but did not return in the last '+lastMonth;
			$('.clientContent').html(lapsedContent)
			$(".closeLapsedMdl").trigger("click");
			$(".modal-backdrop").hide();
			$('#client_type').val(2);
		})
		.on('core.form.invalid', function() {
			validationMsg('error','Sorry, looks like there are some errors detected, please try again.')
		});
	var form = KTUtil.getById('updateLoyalClient');
		FormValidation
		.formValidation(
			form, {
				fields: {
					minSalesCountLoyal: {
						validators: {
							notEmpty: {
                                message: 'Clients with at least sale field is required'
                            }
						}
					},
					maxMonthsConsideredLoyal: {
						validators: {
							notEmpty: {
                                message: 'within the last month field is required'
                            }
						}
					},
					at_least_spent: {
						validators: {
                            callback: {
								message: 'This field must be 1 or higher',
								callback: function(input) {
									console.log(input.value);
									console.log($('#who_spent_type').val());
									if($('#who_spent_type').val() == 2 && input.value < 1) {
										console.log(input.value);
										return false;
									} else {
										return true;
									}		
								}
							}
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					submitButton: new FormValidation.plugins.SubmitButton(),
					bootstrap: new FormValidation.plugins.Bootstrap({})
				}
			}
		)
		.on('core.form.valid', function() {
			var minSalesCount = $('#minSalesCountLoyal').val()
			var maxMonthsConsidered = $('#maxMonthsConsideredLoyal').val()
			var minMonthsSinceLastSale = $('#minMonthsSinceLastSale').val()
			var minAmountType = $('#who_spent_type').val();
			var at_least_spent = $('#at_least_spent').val();
			$('#min_sales_count').val(minSalesCount);
			$('#max_month_considered').val(maxMonthsConsidered);
			$('#min_month_since_last_sale').val(minMonthsSinceLastSale);
			$('#minAmountType').val(minAmountType);
			$('#at_least_spent_amount').val(at_least_spent);

			if(minAmountType == 2){
				var loyalClientCon = "Clients with "+minSalesCount+" or more sales worth at least CA $"+at_least_spent+" in total, within the last "+maxMonthsConsidered+" month period.";
			}else{
				var loyalClientCon = 'Clients with '+minSalesCount+' or more sales, within the last '+maxMonthsConsidered+' month period.';
			}
			$('.clientContent').html(loyalClientCon)
			$(".closeLapsedMdl").trigger("click");
			$(".modal-backdrop").hide();
			$('#client_type').val(3);
		})
		.on('core.form.invalid', function() {
			validationMsg('error','Sorry, looks like there are some errors detected, please try again.')
		});
	$('#SendCampaignMessage').on('submit',function(e){
		e.preventDefault();
		$.ajaxSetup({
		   headers: {
		     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		   }
		});
		$('.overlay-loader').show();
		var data = $(this).serialize();
		$.ajax({
			type:'POST',
			url:'{{ route("sendCampaignEmail") }}',
			data:data,
			success:function(resp){
				$('.overlay-loader').hide();
				if(resp.status == true)
				{
					validationMsg('success',resp.message);
					$('#sendTestEmail').modal('hide');
					document.getElementById("SendCampaignMessage").reset();
				}else{
					validationMsg('error','Something went wrong');
				}
			}
		});
	});
</script>

@endsection