{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss')
<style type="text/css">
	.file-man-box {
	    cursor: pointer;
	}
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
</style>
@endsection
@section('content')
@php
	$id = "";
	$voucher_id = "";
	$message_name = "";
	$email_subject = "";
	$reply_email = "";
	$title = "";
	$message = "";
	$is_button = "";
	$button_text = "";
	$button_link = "";
	$social_media_enable = "";
	$facebook_link = "";
	$instagram_link = "";
	$website = "";
	$message_price = "";
	$total_payable_price = "";
	$is_sended = "";
	$message_type_int = "";
	$message_type_text = "";
	$discount_value = "";
	$discount_type = "";
	$appointment_limit = "";
	$valid_for = "";
	$is_image = "";
	$image_path = "";
	$image_path = "";
	$client_type_int = "";
	$client_type = "";
	$services = array();
@endphp
@if(!empty($emailMessageData))
	@php
	$id = $emailMessageData->id;
	$voucher_id = $emailMessageData->voucher_id;
	$message_name = $emailMessageData->message_name;
	$email_subject = $emailMessageData->email_subject;
	$reply_email = $emailMessageData->reply_email;
	$title = $emailMessageData->title;
	$message = $emailMessageData->message;
	$is_button = $emailMessageData->is_button;
	$button_text = $emailMessageData->button_text;
	$button_link = $emailMessageData->button_link;
	$social_media_enable = $emailMessageData->social_media_enable;
	$facebook_link = $emailMessageData->facebook_link;
	$instagram_link = $emailMessageData->instagram_link;
	$website = $emailMessageData->website;
	$message_price = $emailMessageData->message_price;
	$total_payable_price = $emailMessageData->total_payable_price;
	$is_sended = $emailMessageData->is_sended;
	$message_type_int = $emailMessageData->message_type_int;
	$message_type_text = $emailMessageData->message_type_text;
	$discount_value = $emailMessageData->discount_value;
	$discount_type = $emailMessageData->discount_type;
	$appointment_limit = $emailMessageData->appointment_limit;
	$valid_for = $emailMessageData->valid_for;
	$is_image = $emailMessageData->is_image;
	$image_path = $emailMessageData->image_path;
	$client_type_int = $emailMessageData->client_type_int;
	$client_type = $emailMessageData->client_type;

	$background_color = $emailMessageData->background_color;
	$foreground_color = $emailMessageData->foreground_color;
	$font_color = $emailMessageData->font_color;
	$line_color = $emailMessageData->line_color;
	$button_color = $emailMessageData->button_color;
	$botton_text_color = $emailMessageData->botton_text_color;

	$services = ($emailMessageData->services != "") ? json_decode($emailMessageData->services) : array();
	@endphp
@endif
<div class="container-fluid p-0">
	{{ Form::open(array('url' => 'partners/marketing/saveEmailBlast','id' => 'emailBlastForm','class'=>"require-validation",'data-stripe-publishable-key'=>$setting->stripe_publish_key)) }}
		<input type="hidden" name="edit_id" value="{{ $id }}">
		<input type="hidden" name="message-type" value="{{ $message_type_int }}">
		<input type="hidden" name="voucher_id" value="{{ $voucher_id }}">
		<input type="hidden" name="charge_per_client" id="charge_per_client" value="{{ $setting->per_email_cost }}">
		<div class="my-custom-body-wrapper">
			<div class="my-custom-header">
				<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
					<span class="d-flex">
						<p class="cursor-pointer m-0 px-6" onclick="window.location.href = '{{ url("partners/marketing/marketing_blast_messages") }}'"><i
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
						<button type="button" class="btn btn-primary next-step" onclick="nextPrev(1)">Continue</button>
					</div>
				</div>
			</div>
			<div class="my-custom-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12 col-md-4 p-0 last-hide" style="height:calc(100vh - 80px);overflow-y:scroll">
							<div class="p-4">
								{{-- <div class="edit-content-tab">
									<h3 class="mb-4 font-weight-bolder">Email settings</h3>
									<div class="form-group">
										<label class="font-weight-bolder">Blast message name</label>
										<input type="text" class="form-control" value="{{ $message_name }}"
											name="message_name" />
										<span>This is not visible to clients.</span>
									</div>
									<div class="form-group">
										<label class="font-weight-bolder">Email subject</label>
										<input type="text" class="form-control" value="{{ $email_subject }}"
											name="email_subject" />
									</div>
									<div class="form-group">
										<label class="font-weight-bolder">Reply to email</label>
										<input type="text" class="form-control" value="{{ $reply_email }}" name="email_reply" />
									</div>
									<hr>
									<h3 class="mb-4 font-weight-bolder">Photo</h3>
									<div class="form-group mb-0">
										<div class="switch switch-sm switch-icon switch-success"
											style="line-height: 28px;">
											<label class="d-flex align-item-center font-weight-bolder">
												<input type="checkbox" {{ ($is_image == 1) ? 'checked' : "" }} name="enable_image" id="is_image"><span></span>&nbsp;Enable Photo
											</label>
										</div>
									</div>
									<div class="form-group" id="image_selector">
										<label class="w-100 text-center">
											<div class="card-body" id="img-uploader" style="background-image: url('<?php echo url($image_path) ?>');background-size: 130px;background-repeat: no-repeat;background-position-x: 169px;">
												<a class="btn btn-white" data-target="#filemanager" data-toggle="modal">Change Header Image</a>
											</div>
											<input type="hidden" value="{{ $image_path }}" name="image_path" id="selectedImage">
										</label>
									</div>
									<hr>
									<h3 class="mb-4 font-weight-bolder">Text</h3>
									<div class="form-group ">
										<label class="font-weight-bolder title-input">Title</label>
										<input type="text" class="form-control title_text" value="{{ $title }}" name="title" />
									</div>
									<div class="form-group mb-0">
										<label class="font-weight-bolder">Message</label>
										<textarea class="form-control message body-input" rows="5" id="message" name="desc">{{ $message }}</textarea>
									</div>
									<hr>
									<div class="isUpdate">
										<h3 class="mb-4 font-weight-bolder">Button</h3>
										<div class="form-group">
											<div class="radio-list">
												<label class="radio">
													<input type="radio" value="1" onclick="isCustomButton()" {{ ($is_button == 1) ? 'checked' : "" }} name="isbutton" />
													<span></span>
													Book now button
												</label>
												<label class="radio">
													<input type="radio" {{ ($is_button == 0) ? 'checked' : "" }} value="0" onclick="isCustomButton()" name="isbutton" />
													<span></span>
													No button
												</label>
												<label class="radio">
													<input type="radio" {{ ($is_button == 2) ? 'checked' : "" }} value="2" onclick="isCustomButton()" name="isbutton" />
													<span></span>
													Custom button
												</label>
											</div>
										</div>
										<div class="isCustomButton" style="display: {{ ($is_button == 2) ? 'block' : "none" }}">
											<div class="form-group">
												<label class="font-weight-bolder">Button name</label>
												<input type="text" class="form-control" id="button-text-input" placeholder="Book now" value="{{ $button_text }}" name="btn-text" />
											</div>
											<div class="form-group mb-0">
												<label class="font-weight-bolder">Link (URL)</label>
												<input type="text" class="form-control" placeholder="www.google.com" value="{{ $button_link }}" name="btn_url" />
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
													<input class="form-control" id="discount-input" name="discount_value" value="{{ $discount_value }}">
													<span class="text-dark-50">
														Discount is applied individually to each applicable service
														booked
													</span>
												</div>
											</div>
											<div class="w-70 ml-4">
												<div class="tgl-radio-tabs">
													<input id="price" value="1" type="radio" class="form-check-input tgl-radio-tab-child" {{ ($discount_type == 1) ? 'checked' : "" }}  name="discount_type" onclick="getDiscountType()">
													<label for="price" class="radio-inline">CA $</label>

													<input id="percent" {{ ($discount_type == 2) ? 'checked' : "" }} value="2" type="radio"
														class="form-check-input tgl-radio-tab-child"
														name="discount_type" onclick="getDiscountType()">
													<label for="percent" class="radio-inline">%</label>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label class="font-weight-bolder">Max appointments booked</label>
											<select class="form-control" onchange="appoinmentLimit(this)"
												name="appoinmentsLimits">
												<option value="1" {{ ($appointment_limit == 1) ? 'selected' : "" }}>1</option>
												<option value="3" {{ ($appointment_limit == 3) ? 'selected' : "" }}>3</option>
												<option value="5" {{ ($appointment_limit == 5) ? 'selected' : "" }}>5</option>
												<option value="10" {{ ($appointment_limit== 10) ? 'selected' : "" }}>10</option>
												<option value="unlimited" {{ ($appointment_limit == 'unlimited') ? 'selected' : "" }}>No limit</option>
											</select>
										</div>
										<div class="form-group">
											<label class="font-weight-bolder">Valid for</label>
											<select class="form-control" id="valid_for" name="validFor">
												<option value="5" {{ ($valid_for == 5) ? 'selected' : "" }}>5 days</option>
												<option value="7" {{ ($valid_for == 7) ? 'selected' : "" }}>7 days</option>
												<option value="10" {{ ($valid_for == 10) ? 'selected' : "" }}>10 days</option>
												<option value="14" {{ ($valid_for == 14) ? 'selected' : "" }}>14 days</option>
												<option value="30" {{ ($valid_for == 30) ? 'selected' : "" }}>30 days</option>
												<option value="60" {{ ($valid_for == 60) ? 'selected' : "" }}>60 days</option>
												<option value="90" {{ ($valid_for == 90) ? 'selected' : "" }}>90 days</option>
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
												<input type="text" readonly="" class="form-control form-control-lg" placeholder="All Services" data-toggle="modal" data-target="#servicesModal" style="cursor: pointer;">
												<a href="javascript:;" class="chng_popup" style="top:25%" data-toggle="modal" data-target="#servicesModal">
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
												<input type="checkbox" {{ ($social_media_enable == 1) ? 'checked' : "" }} onclick="isSocialMedia()" name="isSocial">
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
												<input type="text" class="form-control" id="facebook" name="facebook" value="{{ $facebook_link }}" placeholder="www.facebook.com/yoursite">
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
												<input type="text" class="form-control" placeholder="www.instagram.com/yoursite" value="{{ $instagram_link }}" id="instagram" name="instagram">
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
												<input type="text" class="form-control" placeholder="www.yoursite.com" value="{{ $website }}" id="yoursite" name="yoursite">
											</div>
										</div>
									</div>
								</div> --}}
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
													<input type="text" class="form-control" value="{{ $message_name }}"
														name="message_name" />
													<span>This is not visible to clients.</span>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder">Email subject</label>
													<input type="text" class="form-control" value="{{ $email_subject }}"
														name="email_subject" />
												</div>
												<div class="form-group">
													<label class="font-weight-bolder">Reply to email</label>
													<input type="text" class="form-control" value="{{ $reply_email }}" name="email_reply" />
												</div>
												<hr>
												<h3 class="mb-4 font-weight-bolder">Photo</h3>
												<div class="form-group mb-0">
													<div class="switch switch-sm switch-icon switch-success"
														style="line-height: 28px;">
														<label class="d-flex align-item-center font-weight-bolder">
															<input type="checkbox" {{ ($is_image == 1) ? 'checked' : "" }} name="enable_image" id="is_image"><span></span>&nbsp;Enable Photo
														</label>
													</div>
												</div>
												<div class="form-group" id="image_selector">
													<label class="w-100 text-center">
														<div class="card-body" id="img-uploader" style="background-image: url('<?php echo url($image_path) ?>');background-size: 130px;background-repeat: no-repeat;background-position-x: 169px;">
															<a class="btn btn-white" data-target="#filemanager" data-toggle="modal">Change Header Image</a>
														</div>
														<input type="hidden" value="{{ $image_path }}" name="image_path" id="selectedImage">
													</label>
												</div>
												<hr>
												<h3 class="mb-4 font-weight-bolder">Text</h3>
												<div class="form-group ">
													<label class="font-weight-bolder title-input">Title</label>
													<input type="text" class="form-control title_text" value="{{ $title }}" name="title" />
												</div>
												<div class="form-group mb-0">
													<label class="font-weight-bolder">Message</label>
													<textarea class="form-control message body-input" rows="5" id="message" name="desc">{{ $message }}</textarea>
												</div>
												<hr>
												<div class="isUpdate">
													<h3 class="mb-4 font-weight-bolder">Button</h3>
													<div class="form-group">
														<div class="radio-list">
															<label class="radio">
																<input type="radio" value="1" onclick="isCustomButton()" {{ ($is_button == 1) ? 'checked' : "" }} name="isbutton" />
																<span></span>
																Book now button
															</label>
															<label class="radio">
																<input type="radio" {{ ($is_button == 0) ? 'checked' : "" }} value="0" onclick="isCustomButton()" name="isbutton" />
																<span></span>
																No button
															</label>
															<label class="radio">
																<input type="radio" {{ ($is_button == 2) ? 'checked' : "" }} value="2" onclick="isCustomButton()" name="isbutton" />
																<span></span>
																Custom button
															</label>
														</div>
													</div>
													<div class="isCustomButton" style="display: {{ ($is_button == 2) ? 'block' : "none" }}">
														<div class="form-group">
															<label class="font-weight-bolder">Button name</label>
															<input type="text" class="form-control" id="button-text-input" placeholder="Book now" value="{{ $button_text }}" name="btn-text" />
														</div>
														<div class="form-group mb-0">
															<label class="font-weight-bolder">Link (URL)</label>
															<input type="text" class="form-control" placeholder="www.google.com" value="{{ $button_link }}" name="btn_url" />
														</div>
													</div>
												</div>
												<div class="isSpecialOffer">
													<div class="form-group d-flex align-items-center">
														<div>
															<label class="font-weight-bolder">Discount value</label>
															<div class="input-group">
																<div class="input-group-prepend">
																	{{-- <span class="input-group-text bg-white selectedPrice">
																		{{ ($discount_type == 2) ? '%' : "CA $" }}
																	</span> --}}
																	<span class="input-group-text bg-white isPrice">
																		CA $
																	</span>
																	<span class="input-group-text bg-white isPercent">
																		%
																	</span>
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
																<input id="price" value="1" type="radio" class="form-check-input tgl-radio-tab-child" {{ ($discount_type == 1) ? 'checked' : "" }}  name="discount_type" onclick="getDiscountType()">
																<label for="price" class="radio-inline">CA $</label>

																<input id="percent" {{ ($discount_type == 2) ? 'checked' : "" }} value="2" type="radio"
																	class="form-check-input tgl-radio-tab-child"
																	name="discount_type" onclick="getDiscountType()">
																<label for="percent" class="radio-inline">%</label>
															</div>
														</div>
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Max appointments booked</label>
														<select class="form-control" onchange="appoinmentLimit(this)"
															name="appoinmentsLimits">
															<option value="1" {{ ($appointment_limit == 1) ? 'selected' : "" }}>1</option>
															<option value="3" {{ ($appointment_limit == 3) ? 'selected' : "" }}>3</option>
															<option value="5" {{ ($appointment_limit == 5) ? 'selected' : "" }}>5</option>
															<option value="10" {{ ($appointment_limit== 10) ? 'selected' : "" }}>10</option>
															<option value="unlimited" {{ ($appointment_limit == 'unlimited') ? 'selected' : "" }}>No limit</option>
														</select>
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Valid for</label>
														<select class="form-control" id="valid_for" name="validFor">
															<option value="5" {{ ($valid_for == 5) ? 'selected' : "" }}>5 days</option>
															<option value="7" {{ ($valid_for == 7) ? 'selected' : "" }}>7 days</option>
															<option value="10" {{ ($valid_for == 10) ? 'selected' : "" }}>10 days</option>
															<option value="14" {{ ($valid_for == 14) ? 'selected' : "" }}>14 days</option>
															<option value="30" {{ ($valid_for == 30) ? 'selected' : "" }}>30 days</option>
															<option value="60" {{ ($valid_for == 60) ? 'selected' : "" }}>60 days</option>
															<option value="90" {{ ($valid_for == 90) ? 'selected' : "" }}>90 days</option>
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
															<input type="text" readonly="" class="form-control form-control-lg" placeholder="All Services" data-toggle="modal" data-target="#servicesModal" style="cursor: pointer;">
															<a href="javascript:;" class="chng_popup" style="top:25%" data-toggle="modal" data-target="#servicesModal">
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
															<input type="checkbox" {{ ($social_media_enable == 1) ? 'checked' : "" }} onclick="isSocialMedia()" name="isSocial">
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
															<input type="text" class="form-control" id="facebook" name="facebook" value="{{ $facebook_link }}" placeholder="www.facebook.com/yoursite">
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
															<input type="text" class="form-control" placeholder="www.instagram.com/yoursite" value="{{ $instagram_link }}" id="instagram" name="instagram">
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
															<input type="text" class="form-control" placeholder="www.yoursite.com" value="{{ $website }}" id="yoursite" name="yoursite">
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
													  	<input type="color" value="{{ $background_color }}" id="background_color" name="background_color">
													  	<p class="mb-0" id="picker_text1">{{ $background_color }}</p>
													</div>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder" for="foreground-color">Foreground colour</label>
													<div class="picker picker2">
													  	<input type="color" value="{{ $foreground_color }}" id="foreground_color" name="foreground_color">
													  	<p class="mb-0" id="picker_text2">{{ $foreground_color }}</p>
													</div>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder" for="font-color">Font colour</label>
													<div class="picker picker3">
													  	<input type="color" value="{{ $font_color }}" id="font_color" name="font_color">
													  	<p class="mb-0" id="picker_text3">{{ $font_color }}</p>
													</div>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder" for="line-color">Divider and line colour</label>
													<div class="picker picker4">
													  	<input type="color" value="{{ $line_color }}" id="line_color" name="line_color">
													  	<p class="mb-0" id="picker_text4">{{ $line_color }}</p>
													</div>
												</div>
												<hr class="my-12">
												<h3 class="font-weight-bolder mb-8">Action buttons colours</h3>
												<div class="form-group">
													<label class="font-weight-bolder" for="button-color">Button colour</label>
													<div class="picker picker5">
													  	<input type="color" value="{{ $button_color }}" id="button_color" name="button_color">
													  	<p class="mb-0" id="picker_text5">{{ $button_color }}</p>
													</div>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder" for="font-color">Font colour</label>
													<div class="picker picker6">
													  	<input type="color" value="{{ $botton_text_color }}" id="botton_text_color" name="botton_text_color">
													  	<p class="mb-0" id="picker_text6">{{ $botton_text_color }}</p>
													</div>
												</div> 
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
										<div class="form-group ml-2 w-100 d-flex extra-time">
											<label class="option m-3">
												<span class="option-control">
													<span class="radio">
														<input type="radio" onclick="checkAudience()" name="audience" value="1" {{ ($client_type_int == 1 || $client_type_int == 0) ? 'checked="checked"' : '' }} >
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
														<input type="radio" onclick="checkAudience()" name="audience"
															value="2" {{ ($client_type_int == 2) ? 'checked="checked"' : '' }}>
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
														<input type="radio" value="New Clients" name="isclient" class="client_group" data-type="last_client" id="new_client" data-value="30" data-time="Days" {{ ($client_type == 'New Clients') ? 'checked="checked"' : '' }}/>
														<span></span>
														<div class="w-100 d-flex justify-content-between">
															<span>
																<p class="m-0 font-weight-bolder">New Clients</p>
																<p class="m-0 text-dark-50">Clients added in the last 30 days</p>
															</span>
															<span class="text-blue cursor-pointer client_group_model" data-type="new_client">Modify</span>
														</div>
													</label>
													<label class="radio">
														<input type="radio" value="Recent Client" name="isclient" class="client_group" data-type="first_sale" id="recent_client" data-value="30" data-time="Days" {{ ($client_type == 'Recent Client') ? 'checked="checked"' : '' }}/>
														<span></span>
														<div class="w-100 d-flex justify-content-between">
															<span>
																<p class="m-0 font-weight-bolder">Recent clients</p>
																<p class="m-0 text-dark-50">
																	Clients with their first sale in the last 30 days
																</p>
															</span>
															<span class="text-blue cursor-pointer client_group_model" data-type="recent_client" {{-- data-toggle="modal" data-target="#newClientModal" --}}>Modify</span>
														</div>
													</label>
													<label class="radio">
														<input type="radio" value="Loyal Client" name="isclient" class="client_group" data-type="first_3_sale" id="new_client" data-value="30" data-time="Days" {{ ($client_type == 'Loyal Client') ? 'checked="checked"' : '' }}/>
														<span></span>
														<div class="w-100 d-flex justify-content-between">
															<span>
																<p class="m-0 font-weight-bolder">Loyal clients</p>
																<p class="m-0 text-dark-50">
																	Clients with 3 or more sales, within the last 2 month period.
																	
																</p>
															</span>
															<span class="text-blue cursor-pointer client_group_model" data-type="loyal_client" {{-- data-toggle="modal" data-target="#newClientModal" --}}>Modify</span>
														</div>
													</label>
													<label class="radio">
														<input type="radio" value="Lapsed Clients" name="isclient" class="client_group" data-type="at_least_3_sale" id="new_client" data-value="6" data-time="Months" {{ ($client_type == 'Lapsed Clients') ? 'checked="checked"' : '' }}/>
														<span></span>
														<div class="w-100 d-flex justify-content-between">
															<span>
																<p class="m-0 font-weight-bolder">Lapsed clients</p>
																<p class="m-0 text-dark-50">
																	Clients with at least 3 sales anytime in the last 6
																	months, but did not return in the last 2 months
																</p>
															</span>
															<span class="text-blue cursor-pointer client_group_model" data-type="lapsed_client" {{-- data-toggle="modal" data-target="#newClientModal" --}}>Modify</span>
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
											<input type="text" id="totalSelectedClients" readonly=""
												class="form-control form-control-lg" value="0 Clients"
												data-toggle="modal" data-target="#clientsModal"
												style="cursor: pointer;" readonly="readonly">
											<a href="javascript:;" class="chng_popup" style="top:25%" data-toggle="modal"
												data-target="#clientsModal">
												Edit</a>
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
						<div class="col-12 col-md-8 p-0 bg-white last-hide" style="height:calc(100vh - 80px);overflow-y:scroll;">
							<div class="p-6 backgroundColor" style="background-color: {{ $background_color  }};">
								<div class="card w-70 m-auto foregroundColor fontColor" style="background-color: {{ $foreground_color  }}; color: {{ $font_color  }};">
									<div class="card-body">
										@if(count($locationData) <= 1)
											<h3>{{ $locationData[0]['location_name'] }}</h3>
											<p class="text-dark-50">{{ $locationData[0]['location_address'] }}</p>
										@else
											<h3>{{ $locationData[0]['location_name'] }}</h3>
											<p class="text-dark-50">{{ count($locationData) }} Locations</p>
										@endif
										<div class="card-img" style="margin:20px auto;height: auto;width:100px">
											<img alt="img" width="100%" height="auto" class="rounded isUpdate isUpdateImage" src="{{ url($image_path) }}" />
											<img alt="img" width="100%" height="auto" class="isSpecialOffer rounded" src="{{ url($image_path) }}" />
											{{-- <img alt="img" width="100%" height="auto" class="isSpecialOffer rounded" src="./assets/images/present.png" /> --}}
										</div>
										<h2 class="font-weight-bolder isUpdate headline-text">{{ $title }}</h2>
										<h2 class="font-weight-bolder isSpecialOffer headline-text">{{ $title }}</h2>
										<h2 class="font-weight-bolder isVoucher headline-text">Get yourself a gift
										</h2>
										<h6 class="text-dark-50 my-4 isUpdate body-text">
											{{ $message }}
										</h6>
										<h6 class="text-dark-50 my-4 isSpecialOffer body-text">
											{{ $message }}
										</h6>
										<h6 class="text-dark-50 my-4 isVoucher body-text">
											Choose one of our gift vouchers and get it for yourself or someone you care
											about. Click the link to see our gift
											vouchers offer.
										</h6>
										<hr id="deviderAndLine" style="border-color: {{ $line_color }};">
										<div class="preview-card-discount isSpecialOffer">
											<div class="detail">
												<h1 class="my-4 font-weight-bolder text-uppercase">
													<span class="isPrice">$</span>
													<span class="discount-text">{{ $discount_value }}</span><span class="isPercent">%</span>
													Off
												</h1>
												<p>your next <span class="appoinmentLimit">{{ $appointment_limit }}</span> appointment</p>
												<div class="text-uppercase btn btn-light button-text">
													Book now
												</div>
											</div>
										</div>
										<div class="text-center text-uppercase my-4 isButton isUpdate" style="display: {{ ($is_button == 1 || $is_button == 2) ? 'block' : 'none'  }}">
											<button type="button" class="btn btn-primary mx-6 button-text buttonColor" style="background-color:{{ $button_color }};color:{{ $botton_text_color }};">{{ ($is_button == 2) ? $button_text : 'Book Now'  }}</button>
										</div>
										
										<div class="isVoucher p-10 text-center voucher-wrapper text-white justify-content-center bgi-size-cover bgi-no-repeat"
											style="position: relative;">
											<div class="p-4 text-center">
												@if($image_path != '')
													<img alt="voucher-thumb" class="rounded mb-4" src="{{ url($image_path) }}" width="80px" height="80px">	
												@elseif($locationData[0]['location_image'] != '')
													<img alt="voucher-thumb" class="rounded mb-4" src="{{ url($locationData[0]['location_image']) }}" width="80px" height="80px">
												@else
													<img alt="voucher-thumb" class="rounded mb-4" src="./assets/images/thumb.jpg" width="80px" height="80px">
												@endif
												<h3 class="font-weight-bold">{{ ($locationData) ? $locationData[0]['location_name'] : '' }}</h3>
												<h5 class="text-grey">{{ ($locationData) ? $locationData[0]['location_address'] : '' }}</h5>
											</div>
											<div class="border-bottom w-100 opacity-20"></div>
											<div class="my-8 vouchers-value add-vouchers-value">
												<p class="font-weight-bolder mb-0 font-size-lg">Voucher Value</p>
												{{-- <h1 class="font-weight-bolder">CA $<span id="vaoucher-price">{{ (!empty($voucherLists) && $voucherLists['retailprice'] != '' && $voucherLists['retailprice'] < $voucherLists['value']) ? $voucherLists['retailprice'] : $voucherLists['value'] }}</span> --}}
												</h1>
											</div>
											<div class="border-bottom w-100 opacity-20"></div>
											{{-- <div class="my-8 vouchers-bottom">
												<p class="mb-4 font-size-lg">Voucher Code : <span class="font-weight-bolder font-size-lg">******</span></p>
												<button type="button" class="isBookButton btn btn-light my-4 px-4">Buy Voucher Now</button>
												<p class="mb-1 font-weight-bold font-size-lg">Redeem on <span class="font-weight-bolder cursor-pointer">{{ ($voucherLists['services_ids']) ? count(explode(",",$voucherLists['services_ids'])) : '' }}</span> <i class="fa fa-chevron-right icon-sm"></i>
												</p>
												<p class="mb-1 font-weight-bold font-size-lg">Valid for {{ ($voucherLists['validfor']) ? $voucherLists['validfor'] : '' }} 
												</p>
												<p class="mb-1 font-weight-bold font-size-lg">For multiple-use</p>
											</div> --}}
										</div>
										<p class="text-center my-4 isSpecialOffer">Applies to <span class="servicesApply">{{ count($services) }}</span> services, valid for <span class="validFor">{{ $valid_for }}</span> days</p>
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
												<p class="font-weight-bolder text-dark-50"><span class="total_clients">3</span> emails to all clients</p>
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
										<button type="button" type="button"
											class="btn btn-white font-weight-bolder dropdown-toggle"
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
								@php 
									$selectedClient  = array();
								@endphp
								@foreach($clientData as $key => $value)
									@php $selectedClient[] = $value['client_id'] @endphp
								@endforeach
								{{-- @php
								echo "<pre>";
								print_r($selectedClient);
								exit();
								@endphp --}}
								@foreach($allClients as $key => $value)
									<label class="checkbox">
										<input type="checkbox" name="clients[]" class="clients" value="{{ $value->id }}" {{ in_array($value->id, $selectedClient) ? 'checked' : ""  }}>
										<span></span><h3><b>{{ $value->firstname." ".$value->lastname }}</b></h3> &nbsp; {{ $value->mobileno }}
									</label>
								@endforeach
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
																	<input type="checkbox" name="service[]" id="all-{{ $serviceValue['category_title'] }}-{{ $serviceData['id'] }}" value="{{ $serviceData['id'] }}" {{ in_array($serviceData['id'], $services) ? 'checked' : ""  }}>
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
								<form type="post" id="ImageUploadForm" action="{{ url('marketing/uploadCampaignImage') }}">
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
						<input type="text" class="form-control" value="30" />
						<select class="ml-3 form-control" name="validTime">
							<option>Days</option>
							<option>Weeks</option>
							<option>Months</option>
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
var marketingUrl = "<?php echo url('marketing') ?>";
</script>
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js') }}"></script>
<script src="{{ asset('assets/plugins/html2canvas.js') }}"></script>
<script src="{{ asset('assets/js/image-picker.js') }}"></script>
<script src="{{ asset('js/edit_blast_messages.js') }}"></script>
<script src="{{ asset('js/createBlastMessages.js') }}"></script>
<script>
	isSocialMedia();
	getMessageType();
	getDiscountType();
	checkAudience();
	$("#treeview").hummingbird();
	$("#treeview").on("CheckUncheckDone", function () {
		var count = $('input[name="service[]"]:checked').length;
		var allCount = $('input[name="service[]"]:checked').length;
		var allCheck = $('input[name="service[]"]').length;

		if (allCheck === allCount) {
			$(".servicesApply").text('All')
			$("#serviceInput").val("All Services")
		} else {
			$(".servicesApply").text(count)
			$("#serviceInput").val(count)
		}
	});
	$('#all_clients').on('change',function(){
		$('.clients').prop('checked',$(this).prop('checked'));
		TotalChargeCalculation();
	});
	$('.clients').on('change',function(){
		TotalChargeCalculation();	
	});
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
					});
					fileReader.readAsDataURL(f);
				}
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
			});
		} else {
			validationMsg('error',"Your browser doesn't support to File API");
		}
	});
	$('#card_number').mask('0000 0000 0000 0000');
	$('#cvv').mask('000');
	$('#expiry_date').mask('00/0000');
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