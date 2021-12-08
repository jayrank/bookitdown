{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss') 
<style type="text/css">
	.file-man-box {
	    cursor: pointer;
	} 
	/* Quick dirty reset */ 

	/* Picker */
	.picker {
	    display: flex;
	    align-items: center;
	    overflow: hidden;
	    border-radius: 6px;
	    box-shadow: 0 3px 6px -4px rgb(0 0 0 / 10%);
	    width: 100%;
	    height: calc(1.5em + 1.3rem + 2px);
	    font-size: 16px;
	    font-weight: 400;
	    line-height: 1.5;
	    color: #3F4254;
	    background-color: #ffffff;
	    background-clip: padding-box;
	    border: 1px solid #E4E6EF;
	    border-radius: 0.42rem;
	    -webkit-box-shadow: none;
	    box-shadow: none;
	    -webkit-transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
	    transition: border-color 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
	    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
	    transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
	}

	/* Picker focus ring */
	.picker:focus-within {
	  outline: 1px dotted #212121;
	  outline: 5px auto -webkit-focus-ring-color;
	}

	/* Inputs */
	.picker input[type="color"], .picker input[type="text"] {
	 	/*height: calc(1.5em + 1.3rem + 2px);*/
	    outline: 0;
	    border: none;
	}

	/* Text field */
	.picker input[type="text"] {
	  width: calc(100% - 26px);
	  text-transform: uppercase;
	}

	/* Color picker */
	.picker input[type="color"] {
	    width: 22px;
	    height: 22px;
	    -webkit-appearance: none;
	    margin: 0.65rem;
	    /*border-radius: 0.42rem;*/
	    border: 1px solid #E4E6EF;
	    padding: 0;
	}

	.picker input[type="color"]::-webkit-color-swatch-wrapper {
	  padding: 0;
	}

	.picker input[type="color"]::-webkit-color-swatch {
	  border: none;
	  border-right: 1px solid rgba(0, 0, 0, 0.1);
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

<div class="container-fluid p-0">
	<input type="hidden" id="client_group_url" value="{{ route('getFilteredClients') }}">
	<input type="hidden" id="getAllClients" value="{{ route('getAllClients') }}">
	{{ Form::open(array('url' => 'partners/marketing/saveEmailBlast','id' => 'emailBlastForm','class'=>"require-validation",'data-stripe-publishable-key'=>$setting->stripe_publish_key)) }}
		<input type="hidden" name="charge_per_client" id="charge_per_client" value="{{ $setting->per_email_cost }}">
		<input type="hidden" name="edit_id" value="">
		<div class="my-custom-body-wrapper"> 
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
				<div class="container-fluid p-0">
					<div class="row m-0">
						<div class="col-12 col-md-4 p-0 last-hide" style="height:calc(100vh - 80px);overflow-y:scroll">
							<div class="edit-content-tab">
								<div class="p-5">
									<div class="w-100 mb-8">
										<h3 class="font-weight-bolder">Choose a message type</h3>
										<h6>
											Select a message type to send clients. You can customise the message before
											sending.<a href="#" class="text-blue">Learn more.</a>
										</h6>
									</div>
									<div class="form-group w-100 d-flex extra-time">
										<label class="option w-100 align-items-center">
											<span class="option-control">
												<span class="radio">
													<input type="radio" onclick="getMessageType()" name="message-type" class="message-type" value="1" checked="checked">
													<span></span>
												</span>
											</span>
											<span class="option-label d-flex align-items-center">
												<span>
													<div class="icon-svg-lg">
														<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle fill="#E5F1FF" cx="28" cy="28" r="28"></circle>
																<path d="M12.5 27.5c0-8.284 6.716-15 15-15v30c-8.284 0-15-6.716-15-15z" fill="#ffe78c"></path>
																<path d="M37.269 20.81L40 18v8l-7-.8 3.211-3.302a10.234 10.234 0 10.345 11.678.75.75 0 111.256.819 11.734 11.734 0 11-.543-13.585zm-7.019 3.44A.75.75 0 0131 25v5.25l-.007.102a.75.75 0 01-.743.648H25l-.102-.007a.75.75 0 01-.648-.743l.007-.102A.75.75 0 0125 29.5h4.5V25l.007-.102a.75.75 0 01.743-.648z" fill="#101928" fill-rule="nonzero"></path>
															</g>
														</svg>
													</div>
												</span>
												<div class="ml-3">
													<span class="option-head">
														<span class="option-title font-weight-bolder">Quick
															update</span>
													</span>
													<span class="option-body text-dark-50 font-weight-bolder pt-0">
														Send updates to clients
													</span>
												</div>
											</span>
										</label>
									</div>
									<div class="form-group w-100 d-flex extra-time">
										<label class="option w-100 align-items-center">
											<span class="option-control">
												<span class="radio">
													<input type="radio" onclick="getMessageType()" name="message-type" class="message-type" value="2">
													<span></span>
												</span>
											</span>
											<span class="option-label d-flex align-items-center">
												<span>
													<div class="icon-svg-lg">
														<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle fill="#E5F1FF" cx="28" cy="28" r="28"></circle>
																<circle fill="#ffe78c" cx="19" cy="37" r="8"></circle>
																<path d="M24.139 17.049l-6.339.001-.102.007a.75.75 0 00-.648.743l-.001 6.339-3.58 3.58-.072.085a.75.75 0 00.073.976l3.579 3.58.001 6.34.007.102a.75.75 0 00.743.648l6.338-.001 3.582 3.581.084.073a.75.75 0 00.976-.073l3.581-3.581 6.339.001.102-.007a.75.75 0 00.648-.743l-.001-6.339 3.581-3.58.073-.085a.75.75 0 00-.073-.976l-3.581-3.582.001-6.338-.007-.102a.75.75 0 00-.743-.648l-6.34-.001-3.58-3.58a.75.75 0 00-1.06 0l-3.581 3.58zm4.111-1.988l3.27 3.27.09.076a.75.75 0 00.44.143l5.899-.001.001 5.901.01.118a.75.75 0 00.21.412l3.269 3.27-3.27 3.27-.076.09a.75.75 0 00-.143.44l-.001 5.899-5.899.001-.118.01a.75.75 0 00-.412.21l-3.27 3.269-3.27-3.27-.09-.076a.75.75 0 00-.44-.143l-5.901-.001.001-5.899-.01-.118a.75.75 0 00-.21-.412l-3.269-3.27 3.27-3.27.076-.09a.75.75 0 00.143-.44l-.001-5.901 5.901.001a.75.75 0 00.53-.22l3.27-3.269z" fill="#101928" fill-rule="nonzero"></path>
															</g>
														</svg>
													</div>
												</span>
												<div class="ml-3">
													<span class="option-head">
														<span class="option-title font-weight-bolder">Special
															offer</span>
													</span>
													<span class="option-body text-dark-50 font-weight-bolder pt-0">
														Send clients a discount
													</span>
												</div>
											</span>
										</label>
									</div>
									<div class="form-group w-100 d-flex extra-time">
										<label class="option w-100 align-items-center">
											<span class="option-control">
												<span class="radio">
													<input type="radio" onclick="getMessageType()" name="message-type" class="message-type" value="3">
													<input type="hidden" name="voucher_id" id="voucher_id">
													<span></span>
												</span>
											</span>
											<span class="option-label d-flex align-items-center">
												<span>
													<div class="icon-svg-lg">
														<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle fill="#E5F1FF" cx="28" cy="28" r="28"></circle>
																<path fill="#FFDE78" d="M13 13h23v15H13z"></path>
																<path
																	d="M37.75 19A2.25 2.25 0 0140 21.25v3.075a.75.75 0 01-.6.735 3.002 3.002 0 000 5.88c.349.071.6.378.6.735v3.075A2.25 2.25 0 0137.75 37h-19.5A2.25 2.25 0 0116 34.75v-3.075a.75.75 0 01.6-.735 3.002 3.002 0 000-5.88.75.75 0 01-.6-.735V21.25A2.25 2.25 0 0118.25 19zm0 1.5h-19.5a.75.75 0 00-.75.75v2.506a4.505 4.505 0 012.995 4.03L20.5 28a4.504 4.504 0 01-3 4.243v2.507c0 .38.283.693.648.743l.102.007h19.5a.75.75 0 00.75-.75v-2.507a4.505 4.505 0 01-2.995-4.03L35.5 28a4.506 4.506 0 013-4.244V21.25a.75.75 0 00-.648-.743l-.102-.007zm-6 9a.75.75 0 01.102 1.493L31.75 31h-7.5a.75.75 0 01-.102-1.493l.102-.007h7.5zm0-4.5a.75.75 0 01.102 1.493l-.102.007h-7.5a.75.75 0 01-.102-1.493L24.25 25h7.5z"
																	fill="#101928"></path>
															</g>
														</svg>
													</div>
												</span>
												<div class="ml-3">
													<span class="option-head">
														<span class="option-title font-weight-bolder">Voucher</span>
													</span>
													<span class="option-body text-dark-50 font-weight-bolder pt-0">
														Promote available vouchers to clients
													</span>
												</div>
											</span>
										</label>
									</div>
									<!-- <div class="form-group w-100 d-flex extra-time">
										<label class="option w-100">
											<span class="option-control">
												<span class="radio">
													<input type="radio" disabled name="m_option_1" value="1">
													<span></span>
												</span>
											</span>
											<span class="option-label d-flex align-items-center">
												<span>
													<div class="icon-svg-lg">
														<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle fill="#E5F1FF" cx="28" cy="28" r="28"></circle>
																<path fill="#ffe78c" d="M11 45.5v-30h14v30z"></path>
																<path
																	d="M37.455 25.394a.75.75 0 01-.449.96l-.153.064c-.21.093-.52.255-.903.5a11.004 11.004 0 00-2.477 2.191c-2.29 2.707-3.673 6.603-3.673 11.941a.75.75 0 01-.648.743l-.102.007a.75.75 0 01-.75-.75c0-5.338-1.383-9.234-3.673-11.94a11.004 11.004 0 00-2.477-2.193c-.493-.314-.865-.493-1.056-.562a.75.75 0 11.512-1.41c1.077.392 2.63 1.38 4.167 3.196 1.47 1.738 2.595 3.905 3.278 6.537.68-2.632 1.805-4.799 3.276-6.537 1.537-1.816 3.09-2.804 4.167-3.196a.75.75 0 01.96.449zM18.55 32.65l.865 1.753 1.935.281-1.4 1.365.33 1.927-1.73-.91-1.73.91.33-1.927-1.4-1.365 1.935-.281.865-1.753zm21 0l.865 1.753 1.935.281-1.4 1.365.33 1.927-1.73-.91-1.73.91.33-1.927-1.4-1.365 1.935-.281.865-1.753zM29.05 21.4a.75.75 0 01.743.648l.007.102v4.2a.75.75 0 01-1.493.102l-.007-.102v-4.2a.75.75 0 01.75-.75zm-12.95-.7a2.5 2.5 0 110 5 2.5 2.5 0 010-5zm25.9 0a2.5 2.5 0 110 5 2.5 2.5 0 010-5zm-25.9 1.5a1 1 0 100 2 1 1 0 000-2zm25.9 0a1 1 0 100 2 1 1 0 000-2zM29.05 12.137l.865 1.753 1.935.281-1.4 1.365.33 1.927-1.73-.91-1.73.91.33-1.927-1.4-1.365 1.935-.281.865-1.753z"
																	fill="#101928"></path>
															</g>
														</svg>
													</div>
												</span>
												<div class="ml-3 w-100">
													<span class="option-head d-flex justify-content-between">
														<span class="option-title font-weight-bolder text-dark-50">Event
															invitation</span>
														<span class="badge badge-primary">Comming Soon</span>
													</span>
													<span class="option-body text-dark-50 font-weight-bolder pt-0">
														Invite clients to an event
													</span>
												</div>
											</span>
										</label>
									</div> -->
								</div>
							</div>
							<div class="edit-content-tab">
								<ul class="nav nav-pills round-nav mx-auto my-5" id="myTab1" role="tablist" style="width: max-content;">
									<li class="nav-item font-weight-bolder">
										<a class="nav-link active" data-toggle="tab" href="#mail-content">
											<span class="nav-text">Content</span>
										</a>
									</li>
									<li class="nav-item font-weight-bolder">
										<a class="nav-link" data-toggle="tab" href="#mail-style" aria-controls="mail-style">
											<span class="nav-text">Style</span>
										</a>
									</li>
								</ul>
								<hr>
								<div class="p-5">
									<div class="tab-content" id="myTabContent1">
										<div class="tab-pane fade active show" id="mail-content" role="tabpanel" aria-labelledby="mail-content">
											<h3 class="mb-4 font-weight-bolder">Email settings</h3>
											<div class="form-group">
												<label class="font-weight-bolder">Blast message name</label>
												<input type="text" class="form-control" value="Blast message name"
													name="message_name" />
												<span>This is not visible to clients.</span>
											</div>
											<div class="form-group">
												<label class="font-weight-bolder">Email subject</label>
												<input type="text" class="form-control email_subject" value="Gym area is now open!"
													name="email_subject" />
											</div>
											<div class="form-group">
												<label class="font-weight-bolder">Reply to email</label>
												<input type="text" class="form-control" value="adamomarcanada@gmail.com"
													name="email_reply" />
											</div>
											<hr>
											<h3 class="mb-4 font-weight-bolder">Photo</h3>
											<div class="form-group mb-0">
												<div class="switch switch-sm switch-icon switch-success"
													style="line-height: 28px;">
													<label class="d-flex align-item-center font-weight-bolder">
														<input type="checkbox" checked="checked" name="enable_image" id="is_image"><span></span>&nbsp;Enable Photo
													</label>
												</div>
											</div>
											<div class="form-group" id="image_selector">
												<label class="w-100 text-center">
													<div class="card-body" id="img-uploader" style="background-image: url(<?php echo url('public/uploads/schedule_library/campaign_default.png') ?>);background-size: 130px;background-repeat: no-repeat;background-position-x: 169px;">
														<a class="btn btn-white" data-target="#filemanager" data-toggle="modal">Change Header Image</a>
													</div>
													<input type="hidden" value="public/uploads/schedule_library/campaign_default.png" name="image_path" id="selectedImage">
												</label>
											</div>
											<hr>
											<h3 class="mb-4 font-weight-bolder">Text</h3>
											<div class="form-group ">
												<label class="font-weight-bolder title-input">Title</label>
												<input type="text" class="form-control title_text" value="Gym area is now open!" name="title" />
											</div>
											<div class="form-group mb-0">
												<label class="font-weight-bolder">Message</label>
												<textarea class="form-control message body-input" rows="5" id="message"
													name="desc">Gym area now accepts online bookings! Check out our service menu and book your next appointment now.</textarea>
											</div>
											<hr>
											<div class="isUpdate">
												<h3 class="mb-4 font-weight-bolder">Button</h3>
												<div class="form-group">
													<div class="radio-list">
														<label class="radio">
															<input type="radio" value="1" onclick="isCustomButton()" checked="checked" name="isbutton" />
															<span></span>
															Book now button
														</label>
														<label class="radio">
															<input type="radio" value="0" onclick="isCustomButton()" name="isbutton" />
															<span></span>
															No button
														</label>
														<label class="radio">
															<input type="radio" value="2" onclick="isCustomButton()" name="isbutton" />
															<span></span>
															Custom button
														</label>
													</div>
												</div>
												<div class="isCustomButton">
													<div class="form-group">
														<label class="font-weight-bolder">Button name</label>
														<input type="text" class="form-control" id="button-text-input"
															placeholder="Book now" value="Book now" name="btn-text" />
													</div>
													<div class="form-group mb-0">
														<label class="font-weight-bolder">Link (URL)</label>
														<input type="text" class="form-control" placeholder="www.google.com" value="" name="btn_url" />
													</div>
												</div>
											</div>
											<div class="isSpecialOffer">
												<div class="form-group d-flex align-items-center">
													<div>
														<label class="font-weight-bolder">Discount value</label>
														<div class="input-group">
															<div class="input-group-prepend">
																<span class="input-group-text bg-white isPrice">
																	CA $
																</span>
																<span class="input-group-text bg-white isPercent">
																	%
																</span>
															</div>
															<input class="form-control" id="discount-input" name="discount_value" value="10">
															<span class="text-dark-50">
																Discount is applied individually to each applicable service
																booked
															</span>
														</div>
													</div>
													<div class="w-70 ml-4">
														<div class="tgl-radio-tabs">
															<input id="price" value="1" type="radio" class="form-check-input tgl-radio-tab-child" checked name="discount_type" onclick="getDiscountType1()">
															<label for="price" class="radio-inline">CA $</label>

															<input id="percent" value="2" type="radio"
																class="form-check-input tgl-radio-tab-child"
																name="discount_type" onclick="getDiscountType1()">
															<label for="percent" class="radio-inline">%</label>
														</div>
													</div>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder">Max appointments booked</label>
													<select class="form-control" onchange="appoinmentLimit(this)"
														name="appoinmentsLimits">
														<option value="1">1</option>
														<option value="3">3</option>
														<option value="5">5</option>
														<option value="10">10</option>
														<option selected value="unlimited">No limit</option>
													</select>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder">Valid for</label>
													<select class="form-control" id="valid_for" name="validFor">
														<option value="5">5 days</option>
														<option value="7">7 days</option>
														<option value="10">10 days</option>
														<option value="14">14 days</option>
														<option value="30">30 days</option>
														<option value="60">60 days</option>
														<option value="90">90 days</option>
													</select>
													<span class="text-dark-50">Calculated from the date of sending the
														email</span>
												</div>
												<div class="services">
													<h4 class="font-weight-bolder">Apply to <span
															class="servicesApply">All</span>
														services
													</h4>
													<div class="form-group" style="position: relative;">
														<input type="text" readonly="" class="form-control form-control-lg" id="serviceInput" placeholder="All Services" data-toggle="modal" data-target="#servicesModal" style="cursor: pointer;">
														<a href="" class="chng_popup" style="top:25%" data-toggle="modal" data-target="#servicesModal">
															Edit</a>
													</div>
												</div>
											</div>
											<hr>
											<h3 class="mb-4 font-weight-bolder">Social media</h3>
											<div class="form-group mb-0">
												<div class="switch switch-sm switch-icon switch-success"
													style="line-height: 28px;">
													<label class="d-flex align-item-center font-weight-bolder">
														<input type="checkbox" onclick="isSocialMedia()" name="isSocial">
														<span></span>&nbsp;Enable social media icons
													</label>
												</div>
											</div>
											<div class="isSocialMedia my-3">
												<div class="form-group">
													<label class="font-weight-bolder">Facebook</label>
													<div class="input-group input-group-lg">
														<div class="input-group-prepend">
															<span class="input-group-text bg-transparent">
																<i class="la la-facebook"></i>
															</span>
														</div>
														<input type="text" class="form-control" id="facebook" name="facebook" placeholder="www.facebook.com/yoursite">
													</div>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder">Instagram</label>
													<div class="input-group input-group-lg">
														<div class="input-group-prepend">
															<span class="input-group-text bg-transparent">
																<i class="la la-instagram"></i>
															</span>
														</div>
														<input type="text" class="form-control" placeholder="www.instagram.com/yoursite" id="instagram" name="instagram">
													</div>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder">Your website</label>
													<div class="input-group input-group-lg">
														<div class="input-group-prepend">
															<span class="input-group-text bg-transparent">
																<i class="la la-globe"></i>
															</span>
														</div>
														<input type="text" class="form-control" placeholder="www.yoursite.com" id="yoursite" name="yoursite">
													</div>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="mail-style" role="tabpanel" aria-labelledby="mail-style">
											<h2 class="font-weight-bolder">Set your message style</h2>
											<h6 class="mb-4">Customise your blast message to match your business.</h6>
											<h3 class="font-weight-bolder my-6">Template design</h3>
											<div class="form-group">
												<label class="font-weight-bolder" for="background-color">Background colour</label>
												<div class="picker picker1">
												  	<input type="color" value="#FFFFFF" id="background_color" name="background_color">
												  	<p class="mb-0" id="picker_text1">#FFFFFF</p>
												</div>
											</div>
											<div class="form-group">
												<label class="font-weight-bolder" for="foreground-color">Foreground colour</label>
												<div class="picker picker2">
												  	<input type="color" value="#FFFFFF" id="foreground_color" name="foreground_color">
												  	<p class="mb-0" id="picker_text2">#FFFFFF</p>
												</div>
											</div>
											<div class="form-group">
												<label class="font-weight-bolder" for="font-color">Font colour</label>
												<div class="picker picker3">
												  	<input type="color" value="#000000" id="font_color" name="font_color">
												  	<p class="mb-0" id="picker_text3">#000000</p>
												</div>
											</div>
											<div class="form-group">
												<label class="font-weight-bolder" for="line-color">Divider and line colour</label>
												<div class="picker picker4">
												  	<input type="color" value="#D8D8D8" id="line_color" name="line_color">
												  	<p class="mb-0" id="picker_text4">#D8D8D8</p>
												</div>
											</div>
											<hr class="my-12">
											<h3 class="font-weight-bolder mb-8">Action buttons colours</h3>
											<div class="form-group">
												<label class="font-weight-bolder" for="button-color">Button colour</label>
												<div class="picker picker5">
												  	<input type="color" value="#000000" id="button_color" name="button_color">
												  	<p class="mb-0" id="picker_text5">#000000</p>
												</div>
											</div>
											<div class="form-group">
												<label class="font-weight-bolder" for="font-color">Font colour</label>
												<div class="picker picker6">
												  	<input type="color" value="#FFFFFF" id="botton_text_color" name="botton_text_color">
												  	<p class="mb-0" id="picker_text6">#FFFFFF</p>
												</div>
											</div> 
										</div>
									</div>
								</div>
							</div>
							<div class="edit-content-tab">
								<div class="p-5">
									<h3 class="font-weight-bolder">Audience</h3>
									<h6>
										Choose which clients will receive your message. <a href="#"
											class="text-blue">Learn more.</a>
									</h6>
									<div class="form-group">
										<div class="form-group ml-2 w-100 d-flex extra-time">
											<label class="option m-3">
												<span class="option-control">
													<span class="radio">
														<input type="radio" onclick="checkAudience()" name="audience" value="1" checked="checked">
														<span></span>
													</span>
												</span>
												<span class="option-label">
													<span class="option-head">
														<span class="option-title">All Clients</span>
													</span>
													<span class="option-body text-dark">Send to every client in your client list.</span>
												</span>
											</label>
											<label class="option m-3">
												<span class="option-control">
													<span class="radio">
														<input type="radio" onclick="checkAudience()" name="audience" value="2">
														<span></span>
													</span>
												</span>
												<span class="option-label">
													<span class="option-head">
														<span class="option-title">Clients Group</span>
													</span>
													<span class="option-body text-dark">Choose groups of clients in your client list.</span>
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
														<input type="radio" value="Loyal Client" name="isclient" class="client_group loyal_client_group" data-type="loyal_client" id="loyal_client" data-value="2" data-time="Months" data-modify="loyal_client_group_modify" data-min-sales="3" data-max-month="2" data-spent-type="1" data-atleast-spent=""/>
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
														<input type="radio" value="Lapsed Clients" name="isclient" class="client_group" data-type="at_least_3_sale" id="lapsed_client" data-value="6" data-time="Months" data-modify="lapsed_client_group_modify" data-min-sales="3" data-max-month="6" data-since-last-sale="2"/>
														<span></span>
														<div class="w-100 d-flex justify-content-between">
															<span>
																<p class="m-0 font-weight-bolder">Lapsed clients</p>
																<p class="m-0 text-dark-50" id="lapsedClientText">
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
										View and edit the clients your message will be sent to.
									</h6>
									<div class="clients">
										<div class="form-group" style="position: relative;">
											<input type="text" id="totalSelectedClients" readonly=""
												class="form-control form-control-lg" value="0 Clients"
												data-toggle="modal" data-target="#clientsModal"
												style="cursor: pointer;" readonly="readonly">
											<a href="" class="chng_popup" style="top:25%" data-toggle="modal"
												data-target="#clientsModal">
												Edit</a>
										</div>
									</div>
									<h3 class="font-weight-bolder">Price</h3>
									<ul class="p-0" style="list-style: none;" id="pricing">
										<li class="my-4 p-2 border-bottom my-4 d-flex justify-content-between">
											<h5><span class="total_clients">0</span> emails at CA ${{$setting->per_email_cost}} each</h5>
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
						<div class="col-12 col-md-8 p-0 bg-white last-hide" style="height:calc(100vh - 80px);overflow-y:scroll">
							<div class="p-6 backgroundColor">
								<div class="card w-70 m-auto foregroundColor fontColor">
									<div class="card-body">
										@if(count($locationData) <= 1)
											<h3>{{ $locationData[0]['location_name'] }}</h3>
											<p class="text-dark-50">{{ $locationData[0]['location_address'] }}</p>
										@else
											<h3>{{ $locationData[0]['location_name'] }}</h3>
											<p class="text-dark-50">{{ count($locationData) }} Locations</p>
										@endif
										<div class="card-img" style="margin:20px auto;height: auto;width:100px">
											<img alt="img" width="100%" height="auto" class="rounded isUpdate isUpdateImage" src="{{ url('public/uploads/schedule_library/campaign_default.png') }}" />
											<img alt="img" width="100%" height="auto" class="isSpecialOffer rounded" src="{{ url('public/uploads/schedule_library/campaign_default.png') }}" />
										</div>
										<h2 class="font-weight-bolder isUpdate headline-text">Gym area is now Open!
										</h2>
										<h2 class="font-weight-bolder isSpecialOffer headline-text ">Gym area is now open!</h2>
										<h2 class="font-weight-bolder isVoucher headline-text">Get yourself a gift
										</h2>
										<h6 class="text-dark-50 my-4 isUpdate body-text fontColor">
											Gym area now accepts online bookings! Check out our service menu and book
											your next appointment now.
										</h6>
										<h6 class="text-dark-50 my-4 isSpecialOffer body-text fontColor">
											Gym area now accepts online bookings! Check out our service menu and book your next appointment now.
										</h6>
										<h6 class="text-dark-50 my-4 isVoucher body-text fontColor">
											Choose one of our gift vouchers and get it for yourself or someone you care
											about. Click the link to see our gift
											vouchers offer.
										</h6>
										<hr id="deviderAndLine">
										<div class="preview-card-discount isSpecialOffer">
											<div class="detail">
												<h1 class="my-4 font-weight-bolder text-uppercase">
													<span class="isPrice">$</span><span
														class="discount-text">10</span><span class="isPercent">%</span>
													Off
												</h1>
												<p>your next <span class="appoinmentLimit"></span> appointment</p>
												<div class="text-uppercase btn btn-light button-text">
													Book now
												</div>
											</div>
										</div>
										<div class="text-center text-uppercase my-4 isButton isUpdate">
											<button type="button" class="btn btn-primary mx-6 button-text buttonColor" style="background-color:black;">Book Now</button>
										</div>
										<div class="isVoucher p-10 text-center voucher-wrapper text-white justify-content-center bgi-size-cover bgi-no-repeat"
											style="position: relative;">
											<div class="p-4 text-center">
												@if(isset($locationData) && $locationData[0]['location_image'] != '')
													<img alt="voucher-thumb" class="rounded mb-4" src="{{ url($locationData[0]['location_image']) }}" width="80px" height="80px">	
												@else
													<img alt="voucher-thumb" class="rounded mb-4" src="{{ asset('./assets/images/thumb.jpg') }}" width="80px" height="80px">
												@endif	
												<h3 class="font-weight-bold">{{ ($locationData) ? $locationData[0]['location_name'] : '' }}</h3>
												<h5 class="text-grey">{{ ($locationData) ? $locationData[0]['location_address'] : '' }}</h5>
											</div>
											<div class="border-bottom w-100 opacity-20"></div>
											<div class="my-8 vouchers-value add-vouchers-value">
												<p class="font-weight-bolder mb-0 font-size-lg">Voucher Value</p>
												<h1 class="font-weight-bolder">CA $<span id="vaoucher-price">80</span>
												</h1>
											</div>
											<div class="border-bottom w-100 opacity-20"></div>
											<div class="my-8 vouchers-bottom">
												<p class="mb-4 font-size-lg">Voucher Code : <span
														class="font-weight-bolder font-size-lg">******</span></p>
												<button type="button" class="isBookButton btn btn-light my-4 px-4">Buy Voucher Now</button>
												<p class="mb-1 font-weight-bold font-size-lg">Redeem on <span class="font-weight-bolder cursor-pointer numberOfServices">all services</span> <i class="fa fa-chevron-right icon-sm"></i>
												</p>
												<p class="mb-1 font-weight-bold font-size-lg">Valid for <span class="validfortext">3 months</span>
												</p>
												<p class="mb-1 font-weight-bold font-size-lg">For multiple-use</p>
											</div>
										</div>
										<p class="text-center my-4 isSpecialOffer">Applies to <span class="servicesApply">All</span>
											services, valid for <span class="validFor">5</span> days</p>
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
												<p class="font-weight-bolder text-dark-50">Emails to <span class="total_clients">3</span></p>
											</span>
											<span>
												<h3 class="font-weight-bolder">CA $<span class="total_cost"></span> </h3>
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
										<button type="button" class="btn btn-white font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Option</button>
										<div class="dropdown-menu text-center dropdown-menu-right">
											<ul class="navi flex-column navi-hover">
												<li class="navi-item">
													<a data-toggle="modal" data-target="#sendTestEmail"
														class="navi-link">
														<span class="navi-text">Send test email</span>
													</a>
												</li>
												<li class="navi-item">
													<input type="hidden" name="saveAsDraftUrl" id="saveAsDraftUrl" value="{{ route('saveAsDraftEmailBlast') }}">
													<a href="javascript:;" id="saveAsDraftEmailBlast" class="navi-link">
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
						<button type="button" class="btn btn-white" data-dismiss="modal">Close</button>
						<!-- <button type="button" class="btn btn-primary" data-dismiss="modal">Save</button> -->
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
										<input type="checkbox" name="all" id="all" checked>
										<span></span>
										All Services
									</label>
									<ul>
										@foreach($serviceCategory as $serviceKey => $serviceValue)
											<li>
												<label for="all-{{ $serviceValue['category_title'] }}" class="checkbox">
													<input type="checkbox" id="all-{{ $serviceValue['category_title'] }}" checked>
													<span></span>
													{{ $serviceValue['category_title'] }}
												</label>
												<ul>
													@foreach ($serviceValue['service'] as $serviceData)
														@foreach ($serviceData['service_price'] as $priceKey => $servicePrice)
															<li>
																<label for="all-{{ $serviceValue['category_title'] }}-{{ $serviceData['id'] }}-{{ $servicePrice['id'] }}" class="checkbox">
																	<input type="checkbox" name="service[]" id="all-{{ $serviceValue['category_title'] }}-{{ $serviceData['id'] }}-{{ $servicePrice['id'] }}" value="{{ $serviceData['id'] }}" checked>
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

			<form id="sendTestEmailBlast" method="post">
				<input type="hidden" name="message-type" id="messageTypeTestEmail">
				<input type="hidden" name="email_subject" id="emailSubjectTestEmail">
				<div class="modal-body">
					<div class="form-group">
						<label class="font-weight-bold">Email</label>
						<input type="email" name="email" class="form-control" id="testEmail" placeholder="email@gmail.com" />
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary send_test_email" data-id="">Send</button>
				</div>
			</form>
		</div>
	</div>
</div>
<div class="modal" id="filemanager" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="font-weight-bolder modal-title">Choose a photo</h4>
				<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
						class="text-dark fa fa-times icon-lg"></i>
				</p>
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
						<div class="card mx-auto mt-16">
							<div class="row filesDiv">
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
						<div class="card mx-auto my-4 library-images">
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
					<button type="button" class="btn btn-primary">Reset</button>
				</div>
			</div>
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
						<p class="text-center my-4">Applies to <span class="servicesApply">All</span>
							services, valid for <span class="validFor">5</span> days</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- End Modal -->

<div class="modal fade" id="selectVoucher">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header d-flex justify-content-between">
				<h3 class="font-weight-bolder modal-title">
					@if(!empty($voucherLists))
						Select Voucher	
					@else
						No Voucher Found
					@endif
				</h3>
				<button type="button" class="text-dark close" data-dismiss="modal"><i class="ki ki-close"></i></button>
			</div>
			<div class="modal-body">
				<div class="voucher_sec">
					<ul class="list-group">
						<div class="tab-content w-100">
							<div class="tab-pane container active" id="chooseVoucher">
								@forelse($voucherLists as $key => $val)
									<div class="my-2 card voucher-card additemcheckout" data-type="voucher" data-id="{{ $val['id'] }}" data-voucherValue="{{ $val['value'] }}" data-voucherName="{{ $val['name'] }}" data-numberOfServices="{{ count(explode(",", $val['services_ids'])) }}" data-voucherRetailPrice="{{ $val['retailprice'] }}" data-voucherRetailPrice="{{ $val['retailprice'] }}" data-voucherValue="{{ $val['value'] }}" data-validFor="{{ $val['validfor'] }}">
										<div class="card-body text-white p-6">
											<div class="my-3 text-center">
												<h6 class="font-weight-bold">Voucher value</h6>
												<h2 class="font-weight-bolder">CA ${{ $val['value'] }}</h2>
											</div>
											<div class="mt-10 font-weight-bold d-flex justify-content-between">
												<div>
													<h5 class="font-weight-bolder">{{ $val['name'] }}</h5>
													<h5>Redeem on {{ count(explode(",", $val['services_ids'])) }} services</h5>
												</div>
												<div class="text-right">
													<h6 class="font-weight-bolder">CA ${{ $val['retailprice'] }}</h6>
													@if($val['value'] > $val['retailprice'])
														<h5 class="bagde badge-secondary p-1 rounded text-uppercase">
															Save {{ round((($val['value'] - $val['retailprice'])*100) / $val['value']) }}%
														</h5>
													@endif
													@if($val['enable_sale_limit'] == 1)
														<h6 class="font-weight-bolder">Sold 0/{{ $val['numberofsales'] }}</h6>
													@else 
														<h6 class="font-weight-bolder">Sold 0</h6>
													@endif
												</div>
											</div>
										</div>
									</div>
								@empty
									<div class="row">
										<div class="col-md-12 text-center">
											<h3>Add a voucher type</h3>
											<br>
											<h5>You have no active voucher types that are available online.</h5>
											<br>
											<button type="button" class="btn btn-primary" onclick='window.location.href="{{ route('voucherindex') }}"'>Go to vouchers</button>
										</div>
									</div>
								@endforelse		
							</div>
						</div>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

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
<script>
var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";
var imageUrl = "<?php echo url('public/uploads') ?>";
var marketingUrl = "<?php echo url('partners/marketing') ?>";
</script>
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js') }}"></script>
<script src="{{ asset('assets/plugins/html2canvas.js') }}"></script>
<script src="{{ asset('assets/js/image-picker.js') }}"></script>
<script src="{{ asset('js/blast_messages.js') }}"></script>
<script src="{{ asset('js/createBlastMessages.js') }}"></script>
<script>
	$("#treeview").hummingbird();

	countServices();
	$("#treeview").on("CheckUncheckDone", function () {
		countServices();
	});

	function countServices() {
		var count = $('input[name="service[]"]:checked').length;
		var allCount = $('input[name="service[]"]:checked').length;
		var allCheck = $('input[name="service[]"]').length;

		if (allCheck === allCount) {
			$(".servicesApply").text('All')
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
	}

	$('#all_clients').on('change',function(){
		$('.clients').prop('checked',$(this).prop('checked'));
		TotalChargeCalculation();
	});
	$(document).on('change','.clients',function(){
		TotalChargeCalculation();	

		var allChecked = true;
		$('input.clients').each(function(){
			if(!$(this).is(':checked')) {
				$('#all_clients').prop('checked', false);
				allChecked = false;
				return false;
			}
		});
		if(allChecked) {
			$('#all_clients').prop('checked', true);
		}
	});
	$('#all_clients').trigger('click');
	TotalChargeCalculation();
	$(document).ready(function () {
		$("#filesDiv").imagepicker();
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
						$("<span class=\"p-4 pip\" style='position:relative'>" +
							"<img height='100px' width='120px' class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
							"<br/><span class=\"remove btn btn-white btn-sm p-1\" style='position:absolute;top:20px;left:20px'><i class='p-0 fa fa-trash text-danger'></i></span>" +
							"</span>").insertAfter("#filesDiv");
						$(".remove").click(function () {
							$(this).parent(".pip").remove();
						});

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

		$('.send_test_email').on('click',function(){
			$('#messageTypeTestEmail').val($('.message-type:checked').val());
			$('#emailSubjectTestEmail').val($('.email_subject').val());
			$('#sendTestEmail').modal('show');
		});

		$('#sendTestEmailBlast').on('submit',function(e){
			e.preventDefault();
			$.ajaxSetup({
			   headers: {
			     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			   }
			});
			$('.overlay-loader').show();

			var formData = new FormData($("#emailBlastForm")[0]);
			formData.append('email', $('#testEmail').val());

			var data = $(this).serialize();
			$.ajax({
				type:'POST',
				url:'{{ route("sendTestEmailBlast") }}',
				data:formData,
				contentType: false,
				processData: false,
				success:function(resp){
					if(resp.status == true)
					{
						validationMsg('success',resp.message);
						$('#sendTestEmail').modal('hide');
						document.getElementById("sendTestEmailBlast").reset();
					}else{
						validationMsg('error','Something went wrong');
					}
				},
				error: function(resp) {
					validationMsg('error','Something went wrong');
				},
				complete: function(resp) {
					$('.overlay-loader').hide();
				}
			});
		});
	});
	$('#card_number').mask('0000 0000 0000 0000');
	$('#cvv').mask('000');
	$('#expiry_date').mask('00/0000');

	$('#who_spent_type').on('change',function(){
		var thisVal = $(this).val();
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

<script type="text/javascript">
	var theInput1 = document.getElementById("background_color");
    var theColor1 = theInput1.value;
    theInput1.addEventListener("input", function() {
		$('.backgroundColor').css('background-color',theInput1.value);
    	document.getElementById("picker_text1").innerHTML = theInput1.value;
    }, false); 

	var theInput2 = document.getElementById("foreground_color");
    var theColor2 = theInput2.value;
    theInput2.addEventListener("input", function() {
    	$('.foregroundColor').css('background-color',theInput2.value);
    	document.getElementById("picker_text2").innerHTML = theInput2.value;
    }, false); 

	var theInput3 = document.getElementById("font_color");
    var theColor3 = theInput3.value;
    theInput3.addEventListener("input", function() {
    	$('.fontColor').css('color',theInput3.value);
    	document.getElementById("picker_text3").innerHTML = theInput3.value;
    }, false); 

	var theInput4 = document.getElementById("line_color");
    var theColor4 = theInput4.value;
    theInput4.addEventListener("input", function() {
    	$('#deviderAndLine').css('border-color',theInput4.value);
    	document.getElementById("picker_text4").innerHTML = theInput4.value;
    }, false); 

	var theInput5 = document.getElementById("button_color");
    var theColor5 = theInput5.value;
    theInput5.addEventListener("input", function() {
    	$('.buttonColor').css('background-color',theInput5.value);
    	document.getElementById("picker_text5").innerHTML = theInput5.value;
    }, false); 

    var theInput6 = document.getElementById("botton_text_color");
    var theColor6 = theInput6.value;
    theInput6.addEventListener("input", function() {
    	$('.buttonColor').css('color',theInput6.value);
    	document.getElementById("picker_text6").innerHTML = theInput6.value;
    }, false); 
</script>
@endsection