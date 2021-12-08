{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')

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
	
	<div class="container-fluid p-0">
		<div class="my-custom-body-wrapper">
			<div class="my-custom-header bg-white">
				<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
					<span class="d-flex">
						<p class="cursor-pointer m-0 px-6" onclick="history.back();"><i
								class="text-dark fa fa-times icon-lg"></i>
						</p>
					</span>
					<h1 class="font-weight-bolder">New Appointment Notification</h1>
					<div>
						<button class="btn btn-primary" data-toggle="modal" data-target="#testMailModal" >Send test email</button>
						<button class="btn btn-primary next-step" id="update" >Save</button>
					</div>
				</div>
			</div>
			<div class="my-custom-body">
				<div class="container-fluid bg-content">
					<div class="row">
						<div class="col-12 col-md-4 p-0 bg-content" style="height:calc(100vh - 80px);overflow-y:scroll">
							<form name="saveAppointment" id="saveAppointment">
							<div class="p-4">
								<h6 class="text-dark-50">
									This notification automatically sends to clients when their new appointment is
									booked.
								</h6>
								<div class="form-group">
									<div class="checkbox-list mt-8">
										<label class="checkbox font-weight-bolder">
											<input @if(isset($newappo->is_manuallyBook) && $newappo->is_manuallyBook==1)checked @endif type="checkbox" name="is_manuallyBook">
											<span></span> Enable for manually booked appointments
										</label>
									</div>
								</div>
								<div class="form-group">
									<div class="checkbox-list mt-8">
										<label class="checkbox font-weight-bolder">
											<input @if(isset($newappo->is_displayServicePrice) && $newappo->is_displayServicePrice==1)checked @endif type="checkbox" name="is_displayServicePrice" id="is_displayServicePrice">
											<span></span> Display service prices on email
										</label>
									</div>
								</div>
								<h3 class="font-weight-bolder">Important info</h3>
								<h6 class="text-dark-50">
									You can optionally include extra details for clients about their visit, this info is
									only displayed in emails
								</h6>
								<div class="form-group">
									<label class="font-weight-bolder">Important info (optional)</label>
									<textarea rows="4" class="form-control" name="important_info"
										placeholder="e.g. Parking is available on the street" id="note">@if(isset($newappo->important_info) && $newappo->important_info!=null){{ $newappo->important_info }} @endif</textarea>
								</div>
							</div>
							</form>
						</div>
						
						<div class="col-12 col-md-8 p-0 bg-white" style="height:calc(100vh - 80px);overflow-y:scroll">
							<div class="d-flex flex-column align-items-center p-10">
								{{-- <ul class="nav nav-pills round-nav" id="myTab1" role="tablist">
									<li class="nav-item font-weight-bolder">
										<a class="nav-link active" id="home-tab-1" data-toggle="tab" href="#email">
											<span class="nav-text">Email</span>
										</a>
									</li>
								</ul> --}}
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
																	<h3>Hi {{ $client->firstname }} {{ $client->lastname }} your appointment is confirmed</h3>
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

<!-- Modal Step Hide Show -->
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
						data: {email : email, is_price_view : is_price_view, note : note, type : 1},		
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
			}
		};
	}();

	// Class Initialization
	jQuery(document).ready(function() {
		KTLogin.init();
	});
	
	$(document).on('click','#update', function(){
		var form = $("#saveAppointment");
		$.ajax({
			type: "POST",
			url: "{{ route('updateNewAppoNoti') }}",
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