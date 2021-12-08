{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<style>
	
</style>
@endsection

@section('content')
	
	<div class="modal fade" id="testMailModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Send a test email</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<form method="post" id="sendTestEmailFrm" action="{{ route('testEmail') }}">
					<div class="modal-body">
						<div class="form-group">
							<label for="message-text" class="form-control-label">Email:</label>
							<input type="email" class="form-control" required id="email" name="email">
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" id="sendMailBtn" class="btn btn-primary">Send message</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
	
	<div class="modal fade" id="testSmsModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="exampleModalLabel">Send a test message</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</button>
				</div>
				<form method="post" id="sendTestSmsFrm" action="{{ route('testSms') }}">
					<div class="modal-body">
						<div class="d-flex">
							<div class="form-group ml-2 w-100 telephoneclass">
								<label for="telephone">Mobile No.</label>
								<input type="tel" required class="form-control allow_only_numbers" id="telephone" name="telephone">
								<input type="hidden" class="form-control" id="tel_country_code" name="tel_country_code" maxlength="10" value="1">
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" id="sendSmsBtn" class="btn btn-primary">Send message</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
	
	<div class="container-fluid p-0">
		<div class="my-custom-body-wrapper">
			<div class="my-custom-header bg-white">
				<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
					<span class="d-flex">
						<p class="cursor-pointer m-0 px-6" onclick="history.back();"><i
								class="text-dark fa fa-times icon-lg"></i>
						</p>
					</span>
					<h1 class="font-weight-bolder">Reminder Notification</h1>
					<div>
						<div class="dropdown dropdown-inline">
							<button type="button" class="btn btn-primary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Options</button>
							<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right" style="">
								<ul class="navi flex-column navi-hover py-2">
									<li class="navi-item">
										<a data-toggle="modal" data-target="#testMailModal"  class="cursor-pointer navi-link">
											<span class="navi-text">Send test email</span>
										</a>
									</li>
									<li class="navi-item">
										<a data-toggle="modal" data-target="#testSmsModal" class="cursor-pointer navi-link">
											<span class="navi-text">Send test SMS</span>
										</a>
									</li>
								</ul>
							</div>
						</div>
						
						<button class="btn btn-primary next-step" id="update" >Save</button>
					</div>
				</div>
			</div>
			<div class="my-custom-body">
				<div class="container-fluid bg-content">
					<div class="row">
						<div class="col-12 col-md-4 p-0 bg-content" style="height:calc(100vh - 80px);overflow-y:scroll">
							<form name="reminder_appo" id="reminder_appo">
							<div class="p-4">
								<h6 class="text-dark-50">
									This notification automatically sends to clients ahead of their upcoming
									appointment.
								</h6>
								<div class="form-group">
									<div class="checkbox-list mt-8">
										<label class="checkbox font-weight-bolder">
											<input @if(isset($new->is_reminderMessage) && $new->is_reminderMessage==1)checked @endif id="is_reminderMessage" name="is_reminderMessage" type="checkbox">
											<span></span> Enable reminder messages
										</label>
									</div>
								</div>
								<div class="form-group">
									<div class="checkbox-list mt-8">
										<label class="checkbox font-weight-bolder">
											<input name="is_displayServicePrice" id="is_displayServicePrice" @if(isset($new->is_displayServicePrice) && $new->is_displayServicePrice==1)checked @endif type="checkbox">
											<span></span> Display service prices on email
										</label>
									</div>
								</div>
								<h3 class="font-weight-bolder">Reminder timeframe</h3>
								<h6 class="text-dark-50">
									Choose how far in advance your reminder notification messages are sent to clients
								</h6>
								<div class="form-group">
									<label class="font-weight-bolder">Reminder advance notice</label>
									<select class="form-control" name="advance_notice">
										<option value="2" @if(isset($new->advance_notice) && $new->advance_notice==2) selected @endif >2 hours</option>
										<option value="3" @if(isset($new->advance_notice) && $new->advance_notice==3) selected @endif >3 hours</option>
										<option value="24" @if(isset($new->advance_notice) && $new->advance_notice==24) selected @endif >24 hours</option>
										<option value="48" @if(isset($new->advance_notice) && $new->advance_notice==48) selected @endif >48 hours</option>
										<option value="72" @if(isset($new->advance_notice) && $new->advance_notice==72) selected @endif >72 hours</option>
									</select>
								</div>
								<div class="form-group">
									<label class="font-weight-bolder">Additional reminder (optional)</label>
									<select class="form-control" name="additional_reminder">
										<option value="2" @if(isset($new->advance_notice) && $new->additional_reminder==2) selected @endif >2 hours</option>
										<option value="3" @if(isset($new->advance_notice) && $new->additional_reminder==3) selected @endif >3 hours</option>
										<option value="24" @if(isset($new->advance_notice) && $new->additional_reminder==24) selected @endif >24 hours</option>
										<option value="48" @if(isset($new->advance_notice) && $new->additional_reminder==48) selected @endif >48 hours</option>
										<option value="72" @if(isset($new->advance_notice) && $new->additional_reminder==72) selected @endif >72 hours</option>
									</select>
								</div>
								<h3 class="font-weight-bolder">Important info</h3>
								<h6 class="text-dark-50">
									You can optionally include extra details for clients about their visit, this info
										is only displayed in emails
								</h6>
								<div class="form-group">
									<!-- <p>You can optionally include extra details for clients about their visit, this info
										is only displayed in emails</p> -->
									<label class="font-weight-bolder">Important info (optional)</label>
									<textarea rows="4" class="form-control" name="important_info" id="note"
										placeholder="e.g. Parking is available on the street">@if(isset($new->important_info) && $new->important_info!=null){{ $new->important_info }} @endif</textarea>
								</div>
							</div>
							</form>
						</div>
						<div class="col-12 col-md-8 p-0 bg-white" style="height:calc(100vh - 80px);overflow-y:scroll">
							<div class="d-flex flex-column align-items-center p-10">
								<ul class="nav nav-pills round-nav" id="myTab1" role="tablist">
									<li class="nav-item font-weight-bolder">
										<a class="nav-link active" id="home-tab-1" data-toggle="tab" href="#email">
											<span class="nav-text">Email</span>
										</a>
									</li>
									<li class="nav-item font-weight-bolder">
										<a class="nav-link" id="home-tab-1" data-toggle="tab" href="#text-sms">
											<span class="nav-text">SMS Text</span>
										</a>
									</li>
								</ul>
								<div class="p-6">
									<div class="card m-auto">
										<div class="card-body">
											<h3 class="text-center">Message Preview</h3>
											<div class="smartphone">
												<div class="content pt-0">
													<div class="tab-content" id="myTabContent1">
														<div class="tab-pane fade active show" id="email"
															role="tabpanel" aria-labelledby="home-tab-1">
															<div class="card-body bg-dark text-white">
																<div class="message">
																	<h3>Hi {{ $client->firstname }} {{ $client->lastname }} See You Soon</h3>
																	<span
																		class="mb-3 badge badge-pill badge-info">Confirmed</span>
																	<h4>Tuesday, 5 Jan at 10:00am</h4>
																	<div class="addr">
																		<h4>{{ $client->address }}</h4>
																		<p class="p-0 m-0 text-muted">Booking
																			ref:2016CD09</p>
																	</div>
																	<div
																		class="my-4 icons justify-content-around d-flex">
																		<div class="icon text-center">
																			<i
																				class="text-white fas fa-map-marker-alt"></i>
																			Direction
																		</div>
																		<div class="icon text-center">
																			<i
																				class="text-white far fa-calendar-alt"></i>
																			Schedule
																		</div>
																		<div class="icon text-center">
																			<i
																				class="text-white far fa-times-circle"></i>
																			Cancel
																		</div>
																	</div>
																</div>
															</div>
															<div class="bg-secondary p-3">
																<div class="d-flex justify-content-between">
																	<div>
																		<h3>{{ $service->service_name }}</h3>
																		<p class="m-0">1h 15min</p>
																	</div>
																	<h3 class="font-weight-bolder showprice" >CA${{ $service->servicePrice[0]->price }}</h3>
																</div>
																<hr>
																<div class="total d-flex justify-content-between ">
																	<h3 class="font-weight-bolder showprice">Total</h3>
																	<h3 class="font-weight-bolder showprice">CA ${{ $service->servicePrice[0]->price }}</h3>
																</div>
																<hr>
																<div class=" d-flex justify-content-between " >
																	<div>
																		<h3 class="font-weight-bolder shownote">Important info</h3>
																		<p class="m-0 shownote" id="desshow"></p>
																	</div>
																</div>
															</div>
														</div>
														<div class="tab-pane fade" id="text-sms" role="tabpanel"
															aria-labelledby="profile-tab-1">
															<div class="card-body bg-white rounded">
																<div class="chat__message">
																	<div class="date"></div>
																	<div class="isUpdate message">Gym area now accepts
																		online bookings!
																		Check
																		out our service
																		menu and book your next appointment now.<br />
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
						</div>
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
	<!--end::Scrolltop-->
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('js/intlTelInput.min.js') }}"></script>
<script>
	// jQuery 	
	var phone_number = window.intlTelInput(document.querySelector("#telephone"), {
	  separateDialCode: true,
	  preferredCountries:["ca"],
	  hiddenInput: "full",
	  utilsScript: "{{ asset('js/utils.js') }}"
	});
			
	$(document).on("click", '.telephoneclass .iti__country' , function(e) {
		e.preventDefault();
		
		if($(this).hasClass("iti__active")){
			var country_code = $(this).attr("data-dial-code");
			$("#tel_country_code").val(country_code);
		} else {
			var country_code = $(this).attr("data-dial-code");
			$("#tel_country_code").val(country_code);
		}
	});
</script>
<script>
	$(document).on('click','#is_displayServicePrice', function(){
		if($(this).prop("checked") == true){
			$('.showprice').show();
		} else {
			$('.showprice').hide();
		}
	});
	$(document).ready(function() {
		if($('#is_displayServicePrice').prop("checked") == true){
			$('.showprice').show();
		} else {
			$('.showprice').hide();
		}

		var val = $('#note').val();

		if($("#note").val()==''){
			$('.shownote').hide();
		} else {
			$('.shownote').show();
			$('#desshow').text(val);
		}
	});

	$("#note").focusout(function() {
		var val = $(this).val();

		if(val == ''){
			$('.shownote').hide();
		} else {
			$('.shownote').show();
			$('#desshow').text(val);
		}

	});
	
	var KTLogin = function() {
		var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';

		var _handleClientCreate = function() {
			var form = KTUtil.getById('sendTestEmailFrm');
			var formSubmitUrl = KTUtil.attr(form, 'action');
			var formSubmitButton = KTUtil.getById('sendMailBtn');

			if (!form) {
				return;
			}

			FormValidation
				.formValidation( 
					form,
					{
						fields: {
							email:{
								validators: {
									notEmpty: {
										message: 'Email address is required'
									},
									emailAddress: {
										message: 'The value is not a valid email address'
									}
								}
							}
						},
						plugins: {
							trigger: new FormValidation.plugins.Trigger(),
							submitButton: new FormValidation.plugins.SubmitButton(),
							bootstrap: new FormValidation.plugins.Bootstrap({
							})
						}
					}
				)
				.on('core.form.valid', function() {
					// Show loading state on button
					KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

					var email = $("#email").val();
					var is_price_view = 0;
					
					if ($("#is_displayServicePrice").prop('checked')==true){ 
						is_price_view = 1;	
					}
					
					var note = $("#note").val();
					
					$.ajax({
						type: 'POST',       
						url: formSubmitUrl,
						headers:{ 
							'X-CSRF-Token': '{{ csrf_token() }}',
						},
						data: {email : email, is_price_view : is_price_view, note : note, type : 2},		
						success: function (response)
						{
							KTUtil.btnRelease(formSubmitButton);
							$("#testMailModal").modal('hide');
							
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
							toastr.success(response.message);
						},
						error: function (data) {
							KTUtil.btnRelease(formSubmitButton, "Save");	
							var message = data.message;
							
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
							toastr.error((message) ? message : "Something went wrong!");
						}
					});
				})
				.on('core.form.invalid', function() {
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
					toastr.error("Sorry, looks like there are some errors detected, please try again."); 
				});
		}

		var _handleSms = function() {
			var form = KTUtil.getById('sendTestSmsFrm');
			var formSubmitUrl = KTUtil.attr(form, 'action');
			var formSubmitButton = KTUtil.getById('sendSmsBtn');

			if (!form) {
				return;
			}

			FormValidation
				.formValidation( 
					form,
					{
						fields: {
							telephone:{
								validators: {
									notEmpty: {
										message: 'Phone number is required'
									}
								}
							}
						},
						plugins: {
							trigger: new FormValidation.plugins.Trigger(),
							submitButton: new FormValidation.plugins.SubmitButton(),
							bootstrap: new FormValidation.plugins.Bootstrap({
							})
						}
					}
				)
				.on('core.form.valid', function() {
					// Show loading state on button
					KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

					var telephone = $("#telephone").val();
					var tel_country_code = $("#tel_country_code").val();
					
					$.ajax({
						type: 'POST',       
						url: formSubmitUrl,
						headers:{ 
							'X-CSRF-Token': '{{ csrf_token() }}',
						},
						data: {telephone : telephone, tel_country_code : tel_country_code},		
						success: function (response)
						{
							KTUtil.btnRelease(formSubmitButton);
							$("#testSmsModal").modal('hide');
							
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
							toastr.success(response.message);
						},
						error: function (data) {
							KTUtil.btnRelease(formSubmitButton, "Save");	
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
				})
				.on('core.form.invalid', function() {
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
					toastr.error("Sorry, looks like there are some errors detected, please try again."); 
				});
		}

		// Public Functions
		return {
			init: function() {
				_handleClientCreate();
				_handleSms();
			}
		};
	}();

	// Class Initialization
	jQuery(document).ready(function() {
		KTLogin.init();
	});
	
	$(document).on('click','#update', function(){
		var form = $("#reminder_appo");
		$.ajax({
			type: "POST",
			url: "{{ route('updateRemiderNoti') }}",
			headers:{
						'X-CSRF-Token': '{{ csrf_token() }}',
					},
			data: form.serialize(),
			success: function (data) {
				console.log(data);
				KTApp.unblockPage();
				toastr.success(data.message);
				window.location.href = "{{route('clientMessageSetting')}}";
			},
			error: function(data) {
				var errors = data.responseJSON;
				var errorsHtml = '';
				$.each(errors.error, function(key, value) {
					errorsHtml += '<p>' + value[0] + '</p>';
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

				toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
				KTUtil.scrollTop();
			}
		});
	});
</script>
@endsection