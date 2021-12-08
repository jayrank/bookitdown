{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<style>
.error {
    color: red !important;
}
</style>
@endsection
@section('content')

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
		<div class="fullscreen-modal" tabindex="-1" role="dialog" aria-hidden="true"> 
			<div class="my-custom-header text-dark"> 
	            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
	            	<div style="display: flex; justify-content: flex-start; align-items: center;">
			            <a type="button" class="close" data-dismiss="modal" aria-label="Close" style="top:unset;left: 0;opacity: 1;font-size: 1.75rem;" onclick="window.history.back();">
			                <span aria-hidden="true" class="la la-times"></span>
			            </a>
			            <p class="h6 cursor-pointer mb-0 text-blue previous" onclick="nextPrev(-1)"><i class="border-left mx-4"></i>Previous</p>
			        </div>
		            <div style="flex-grow: 1; text-align: center;">
		            	<h6 class="modal-title text-muted title-hide">Steps <span class="steps"></span> of 4 Add a
							new location</h6> 
						<h2 class="font-weight-bolder page-title title-hide">About your business</h2>
		            </div>
		            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
		                <button class="btn btn-lg btn-primary next-step" type="button" onclick="nextPrev(1)">Next Step</button>
		            </div>
		        </div>
	        </div> 
			<div class="border-0 mb-5 text-center firstStep"> 
				<h6 class="modal-title text-muted hide-onscroll" style="flex-grow: 1;">Steps <span class="steps"></span> of 4 Add a
							new location</h6> 
				<h1 class="font-weight-bolder mb-5 text-center text-dark page-title hide-onscroll" style="flex-grow: 1;">About your business</h1>  
			</div>
			<form action="{{ url('partners/setup/addlocation') }}" method="POST" enctype="multipart/form-data" id="addlocation">
				@csrf 
				<div class="container my-custom-body">	
					<div class="row justify-content-center">
						<div class="col-12 col-md-9">			
							<div class="edit-content-tab">
								<div class="card my-4">
									<div class="card-header">
										<h4>Basic</h4>
									</div> 
									<div class="card-body">
										<div class="form-group">
											<label class="font-weight-bolder">Location name</label>
											<input type="text" name="location_name" id="location_name" class="form-control"  required>
										</div>
										<div class="form-group telephoneclass">
											<label class="font-weight-bolder" for="location_phone">Telephone</label><br>
											<input type="hidden" name="country_code" id="country_code" value="91">
											<input type="number" class="form-control" pattern="[1-9]{1}[0-9]{9}" maxlength="10" placeholder="" id="location_phone" name="location_phone"  required><br>
											
										</div>
										<div class="form-group">
											<label class="font-weight-bolder">Location email address</label>
											<input type="email" name="location_email" id="location_email" class="form-control" required>
										</div>
									</div>
								</div>
							</div>
							<div class="edit-content-tab">
								<div class="card my-4">
									<ul class="ks-cboxtags">
										@foreach($main as $row)
										<li>
											<input type="radio" name="main_business" id="{{$row->name}}" value="{{$row->name}}" @if ($loop->first) checked @endif>
											<label for="{{$row->name}}">
												<div class="m-auto" style="width: 50px;height: 50px;">
													{!!$row->image!!}
												</div>
												{{$row->name}}
											</label>
										</li>
										@endforeach
										
									</ul>
								</div>
							</div>
							<div class="edit-content-tab">
								<div class="card my-4">
									<ul class="ks-cboxtags">
										@foreach($main as $row)
									
											<li>
												<input type="checkbox" class="multi_checkbox" name="secondary_business[]" id="{{$row->id}}" value="{{$row->id}}">
												<label for="{{$row->id}}">
													<div class="m-auto" style="width: 50px;height: 50px;">
														{!!$row->image!!}
													</div>
													{{$row->name}}
												</label>
											</li>
										
										@endforeach
										
									</ul>
								</div>
							</div>
							<div class="edit-content-tab">
								<div class="card my-4">
									<div class="card-header">
										<h4>Business location</h4>
									</div>
									<div class="card-body">
										<div class="form-group">
											<label class="font-weight-bolder" for="location_address">Where’s your business located?</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text bg-transparent"><i class="fa fa-map-marker"></i></span>
												</div>
												<input type="text" class="form-control" name="location_address" id="location_address">
												<p id="location_error"></p>
												<input type="hidden" name="lat" id="lat" value="">
												<input type="hidden" name="lng" id="lng" value="">
											</div>
											
											<div class=" bg-content p-4 address_detail_setion mt-5" style="display: none;">
												<h5 class="position-absolute" style="right:40px;z-index: 1;">
													<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">Edit</a>
												</h5>
												<div class="row">
													<div class="col-md-4">
														<div class="my-6">
															<h4>Address</h4>
															<p id="loc_address"></p>
															<input type="hidden" name="loc_address" class="loc_address" value="">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>Apt./Suite etc</h4>
															<p id="loc_apt"></p>
															<input type="hidden" name="loc_apt" class="loc_apt" value="">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>District</h4>
															<p id="loc_district"></p>
															<input type="hidden" name="loc_district" class="loc_district" value="">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>City</h4>
															<p id="loc_city"></p>
															<input type="hidden" name="loc_city" class="loc_city" value="">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>Region</h4>
															<p><span id="loc_region"></span><span id="loc_county"></span></p>
															<input type="hidden" name="loc_region" class="loc_region" value="">
															<input type="hidden" name="loc_county" class="loc_county" value="">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>Postcode</h4>
															<p id="loc_postcode"></p>
															<input type="hidden" name="loc_postcode" class="loc_postcode" value="">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>Country</h4>
															<p id="loc_country"></p>
															<input type="hidden" name="loc_country" class="loc_country" value="">
														</div>
													</div>
												</div>
											</div>
											
										</div>
										<div class="form-group">
											<div class="checkbox-list">
												<label class="checkbox">
													<input type="checkbox" id="no_business_address" name="no_business_address" value="1">
													<span></span> I don’t have a business address (mobile and
													virtual
													services)
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="card map_section" style="display: none;">
									<div class="card-header">
										<h4>Map</h4>
									</div>
									<div class="card-body">
										<div class="form-group">
											 <div class="map" id="map" style="width: 100%; height: 300px;"></div>
										</div>
									</div>
								</div>

								<div class="card my-3">
									<div class="card-header">
										<h4>Billing details for clients invoice</h4>
										<p>These details will appear on the client’s invoice for this location as
											well as the information you’ve configured in your
											Invoice Template settings.
										</p>
									</div>
									<div class="card-body">
										<div class=" bg-content p-4">
											<h5 class="position-absolute" style="right:40px;z-index: 1;">
												<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBillingDetail">Edit</a>
											</h5>
											<div class="row">
												<div class="col-12 col-md-12">
													<div class="my-6">
														<h4>Company name</h4>
														<p id="billing_company_name"></p>
														<input type="hidden" class="form-control billing_company_name" name="billing_company_name" placeholder="" value="">
													</div>
													<div class="my-6">
														<h4>Address</h4>
														<p id="billing_address"></p>
														<input type="hidden" class="isSameBillingAddr" name="is_same_billing_addr" value="0">
														<input type="hidden" class="billing_address" name="billing_address" value="">
														<input type="hidden" class="billing_apt" name="billing_apt" value="">
														<input type="hidden" class="billing_city" name="billing_city" value="">
														<input type="hidden" class="billing_region" name="billing_region" value="">
														<input type="hidden" class="billing_postcode" name="billing_postcode" value="">
													</div>
													<div class="my-6">
														<h4>Notes</h4>
														<p id="billing_notes"><a data-toggle="modal" data-target="#editBillingDetail" class="text-blue cursor-pointer">+ Add</a></p>
														<input type="hidden" class="billing_notes" name="billing_notes" value="">
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
			</form>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal" id="editBusinessLocation" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="editBillingDetailLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title font-weight-bolder" id="editBillingDetailLabel">Edit business location</h3>
					<p class="cursor-pointer m-0" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i></p>
				</div>
				<div class="modal-body">
					<div>
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>Address</label>
								<input type="text" class="form-control loc_address" autocomplete="off" placeholder="" value="">
							</div>
							<div class="form-group  mr-2 w-50">
								<label>Apt./Suite etc</label>
								<input type="text" class="form-control loc_apt" autocomplete="off" placeholder="" value="">
							</div>
						</div>
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>District</label>
								<input type="text" class="form-control loc_district" autocomplete="off" placeholder="" value="">
							</div>
							<div class="form-group  mr-2 w-50">
								<label>City</label>
								<input type="text" class="form-control loc_city" autocomplete="off" placeholder="" value="">
							</div>
						</div>
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>County</label>
								<input type="text" class="form-control loc_county" autocomplete="off" placeholder="" value="">
							</div>
							<div class="form-group  mr-2 w-50">
								<label>State</label>
								<input type="text" class="form-control loc_region" autocomplete="off" placeholder="" value="">
							</div>
						</div>
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>Postcode</label>
								<input type="text" class="form-control loc_postcode" autocomplete="off" placeholder="" value="">
							</div>
							<div class="form-group  ml-2 w-100">
								<label>Country</label>
								<input type="text" class="form-control loc_country" autocomplete="off" placeholder="" value="">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-danger updateLocationAddr" data-dismiss="modal">Save</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal" id="editBillingDetail" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="editBillingDetailLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title font-weight-bolder" id="editBillingDetailLabel">Edit billing details
					</h3>
					<p class="cursor-pointer m-0 closeBillingMdl" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				
				<form action="#" method="POST" id="updateBillingAddress">
					<div class="modal-body">
						<div>
							<div class="form-group">
								<div class="checkbox-list mt-8">
									<label class="checkbox font-weight-bolder">
										<input checked id="isSameLocationAddr" name="isSameLocationAddr" type="checkbox">
										<span></span> Use location name and address for client invoices
									</label>
								</div>
							</div>
							<div class="form-group">
								<label>Company name </label>
								<input type="text" class="form-control bill_inpt billing_company_name" autocomplete="off" disabled value="">
							</div>
							<div class="d-flex">
								<div class="form-group  mr-2 w-100">
									<label>Address</label>
									<input type="text" class="form-control bill_inpt billing_address" autocomplete="off" name="billing_address" disabled value="">
								</div>
								<div class="form-group  mr-2 w-50">
									<label>Apt./Suite etc</label>
									<input type="text" class="form-control bill_inpt billing_apt" autocomplete="off" disabled value="">
								</div>
							</div>
							<div class="form-group">
								<label>City </label>
								<input type="text" class="form-control bill_inpt billing_city" autocomplete="off" name="billing_city" disabled value="">
							</div>
							<div class="d-flex">
								<div class="form-group  mr-2 w-100">
									<label>State</label>
									<input type="text" class="form-control bill_inpt billing_region" autocomplete="off" name="billing_region" disabled value="">
								</div>
								<div class="form-group  ml-2 w-100">
									<label>Postcode</label>
									<input type="text" class="form-control bill_inpt billing_postcode" autocomplete="off" name="billing_postcode" disabled value="">
								</div>
							</div>
							<div class="form-group">
								<label>Invoice note</label>
								<textarea class="form-control billing_notes" rows="5" placeholder="VAT number or other info"></textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-danger updateBillingAddr">Save</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
	<!-- End Modal -->
	@endsection
	@section('scripts')
	<script src="{{ asset('assets/js/pages/widgets.js')}}"></script>
	<!--end::Page Scripts-->
	<script src="{{ asset('js/intlTelInput.min.js') }}"></script>
	<!--script src="{{ asset('js/addLocation.js') }}"></script-->
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVdjXD_FnLKsQTNU8ki6Np_YlfwU0Oyec&v=3.exp&libraries=places"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
	<script>
		var limit = 3;
		$('input.multi_checkbox').on('click', function (evt) {
			if ($('.multi_checkbox:checked').length > limit) {
				this.checked = false;
			}
		});
	</script>
	<script type="text/javascript">

		var WEBSITE_URL = "{{ url('partners/') }}";
		var location_phone = window.intlTelInput(document.querySelector("#location_phone"), {
			separateDialCode: true,
			preferredCountries:["in"],
			hiddenInput: "full",
			utilsScript: "{{ asset('js/utils.js') }}"
		});
		
		$(document).on("click keypress", '.telephoneclass .iti__country' , function(e) {
			e.preventDefault();
			if($(this).hasClass("iti__active")){
				var country_code = $(this).attr("data-dial-code");
				$("#country_code").val(country_code);
			} else {
				var country_code = $(this).attr("data-dial-code");
				$("#country_code").val(country_code);
			}
		});
		
		$('.iti__country').each(function(){
			if($(this).attr("data-dial-code") == $("#country_code").val())
			{
				var countryCode = $(this).attr('data-country-code');
				if($('#country_code').val() == 1)
				{
					countryCode = "in"
				}
				$('.telephoneclass').find('.iti__selected-flag').children('.iti__flag').addClass('iti__'+countryCode);
			}
		});		
	</script>
	<!-- Modal Step Hide Show -->
	<script>
		document.getElementById('no_business_address').onchange = function() {
			if(this.checked)
			{
				$('#location_address').css("pointer-events", "none");
				$('#location_address').removeClass("is-invalid");
				$(".address_detail_setion").hide();
				$(".map_section").hide();
				$(".fv-help-block").html("");
			} else {
				$('#location_address').css("pointer-events", "all");
				$(".address_detail_setion").show();	
				$(".map_section").show();	
			}	
		}
		
		document.getElementById('isSameLocationAddr').onchange = function() {
			if(this.checked)
			{
				$('.bill_inpt').attr("disabled", true);
				$(".updateLocationAddr").trigger("click");
			} else {
				$('.bill_inpt').attr("disabled", false);
			}	
		}
		
		$('#mymodal').on('show.bs.modal', function() {
			$(".updateLocationAddr").trigger("click");
		});	
		
		var currentTab = 0; // Current tab is set to be the first tab (0)
		showTab(currentTab); // Display the current tab

		function showTab(n) 
		{
			// This function will display the specified tab of the form...
			var tab = document.getElementsByClassName("edit-content-tab");
			tab[n].style.display = "block";
			if (n == (tab.length - 1)) {
				$(".previous").show();
				$(".steps").text("4");
				$(".next-step").text("Save");
				$(".next-step").attr("type", "submit");
			} else {
				$(".previous").hide();
				$(".next-step").text("Next Step");
			}

			if (n == 0) {
				$(".steps").text("1");
				$(".page-title").text("About your business");
			} else if (n == 1) {
				$(".previous").show();
				$(".steps").text("2");
				$(".page-title").text("Choose your main business types");
			} else if (n == 2) {
				$(".previous").show();
				$(".steps").text("3");
				$(".page-title").text("Choose your secondary business types");
			}
			else if (n == 3) {
				$(".previous").show();
				$(".steps").text("4");
				$(".page-title").text("Add your location");
			}
		}

		function nextPrev(n) 
		{
			if(n==1)
			{
				//$("#addlocation").valid();
				$("#addlocation").validate({
					rules: {
						location_email: {
							required: true,
							email: true
						},
						location_phone: {
							required: true,
							maxlength: 10
						},
						location_name: {
							required: true
						}
					},
					messages: { 
						location_email: {
							required: "Please enter an email address",
							email: "Not a valid email address"
						},
						location_phone: {
							required: "Please enter your phone"
						},
						location_name: {
							required: "Please enter name"
						}
					}
				});
				
				if(currentTab == 0) {
					$("#billing_company_name").html($("#location_name").val());
					$(".billing_company_name").val($("#location_name").val());
				}	
			}
			
			if($("#addlocation").valid() )
			{	
				// This function will figure out which tab to display
				var tab = document.getElementsByClassName("edit-content-tab");
				// Hide the current tab:
				
				if(currentTab <= 3){
					tab[currentTab].style.display = "none";
					currentTab = currentTab + n;
				} else if(currentTab < 4) {
					// Increase or decrease the current tab by 1:
					currentTab = currentTab + n;
				}
				
				// if you have reached the end of the tab
				// Otherwise, display the correct tab:
				if(currentTab == 4)
				{	
					currentTab = currentTab - n;
					tab[currentTab].style.display = "block";
			
					$(".fv-plugins-message-container").remove();
					var form = KTUtil.getById('addlocation');
					$("#addlocation").valid();
					
					const fv = FormValidation
					.formValidation(
						form, {
							fields: {
								location_address: {
									validators: {
										callback: {
											message: '&nbsp;',
											callback: function(input) {
												if($("#no_business_address").is(':checked'))
												{
													return true;
												} else {
													if(input.value == "") {
														return false;
													} else {
														return true;
													}		
												}		
											}
										}
									}
								}
							},
							plugins: {
								trigger: new FormValidation.plugins.Trigger(),
								submitButton: new FormValidation.plugins.SubmitButton(),
								bootstrap: new FormValidation.plugins.Bootstrap({}),
							}
						}
					);
					
					fv.validate().then(function(status) {
						
						if(status == 'Valid')
						{
							var _buttonSpinnerClasses = 'spinner spinner-right spinner-white pr-15';
							var form = KTUtil.getById('addlocation');
							var formSubmitUrl = KTUtil.attr(form, 'action');
							var formSubmitButton = document.getElementById('addlocationsubmit');
							// Show loading state on button
							KTUtil.btnWait(formSubmitButton, _buttonSpinnerClasses, "Please wait");

							// Simulate Ajax request
							setTimeout(function() {
								KTUtil.btnRelease(formSubmitButton);
							}, 2000);
			
							var form_data = new FormData($("#addlocation")[0]);
							
							$.ajax({
								type: 'POST',
								url: formSubmitUrl,
								data: form_data,
								dataType: 'json',
								processData: false,
								contentType: false,
								success: function(response) {
									KTUtil.btnRelease(formSubmitButton);
						
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
			
									window.setTimeout(function() {
										window.location = response.redirect;
									}, 2000);
								},
								error: function(data) {
									KTUtil.btnRelease(formSubmitButton, "Save");
									var errors = data.responseJSON;
			
									var errorsHtml = '';
									$.each(errors.error, function(key, value) {
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
									window.setTimeout(function() {
										//window.location.reload();
									}, 2000);
								}
							});	
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
							toastr.error("Sorry, looks like there are some errors detected, please try again.");
						}		
					});						
				} else {
					
					if(currentTab <= 3) {
						showTab(currentTab);
					}
				}
			} 
		}
		
		function initialize() 
		{
			var latlng = new google.maps.LatLng(28.5355161,77.39102649999995);
			var map = new google.maps.Map(document.getElementById('map'), {
				center: latlng,
				zoom: 13
			});
			
			var marker = new google.maps.Marker({
				map: map,
				position: latlng,
				draggable: true,
				anchorPoint: new google.maps.Point(0, -29)
			});
			
			var input = document.getElementById('location_address');
			//map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
			var geocoder = new google.maps.Geocoder();
			var autocomplete = new google.maps.places.Autocomplete(input);
			autocomplete.bindTo('bounds', map);
			//var infowindow = new google.maps.InfoWindow();   
			autocomplete.addListener('place_changed', function() {
				//infowindow.close();
				marker.setVisible(false);
				var place = autocomplete.getPlace();
				if (!place.geometry) {
					window.alert("Autocomplete's returned place contains no geometry");
					return;
				}
		  
				// If the place has a geometry, then present it on a map.
				if (place.geometry.viewport) {
					map.fitBounds(place.geometry.viewport);
				} else {
					map.setCenter(place.geometry.location);
					map.setZoom(17);
				}
			   
				marker.setPosition(place.geometry.location);
				marker.setVisible(true);          
				$(".map_section").show();
			
				var premise = addres = district = postal_code = city = region = county = postal_code = country = "";
				var place = autocomplete.getPlace();
				for (var i = 0; i < place.address_components.length; i++) 
				{
					for (var j = 0; j < place.address_components[i].types.length; j++) 
					{
						if (place.address_components[i].types[j] == "premise") 
						{
							premise = place.address_components[i].long_name+", ";
						}
						if (place.address_components[i].types[j] == "street_number") 
						{
							addres += place.address_components[i].long_name;
						}
						if (place.address_components[i].types[j] == "route") 
						{
							addres += " "+place.address_components[i].long_name;
						}
						if (place.address_components[i].types[j] == "neighborhood") 
						{
							district = place.address_components[i].long_name;
						}
						if (place.address_components[i].types[j] == "sublocality_level_1") 
						{
							district = place.address_components[i].long_name;
						}
						if (place.address_components[i].types[j] == "locality") 
						{
							city = place.address_components[i].long_name;
						}
						if (place.address_components[i].types[j] == "administrative_area_level_1") 
						{
							region = place.address_components[i].long_name;
						}
						if (place.address_components[i].types[j] == "administrative_area_level_2") 
						{
							county = place.address_components[i].long_name;
						}
						if (place.address_components[i].types[j] == "postal_code") 
						{
							postal_code = place.address_components[i].long_name;
						}
						if (place.address_components[i].types[j] == "country") 
						{
							country = place.address_components[i].long_name;
						}
					}
				}
				
				$(".address_detail_setion").show();
				
				if(addres == "") {
					var lbladd = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
				} else {
					var lbladd = premise+""+addres;
				}		
				
				if(district == "") {
					var lbldist = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
				} else {
					var lbldist = district;
				}		
				
				if(postal_code == "") {
					var lblpost = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
				} else {
					var lblpost = postal_code;
				}		
				
				$("#loc_apt").html('<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>');
				$("#loc_address").html(lbladd);
				$("#loc_district").html(lbldist);
				$("#loc_city").html(city);
				$("#loc_region").html(region+", ");
				$("#loc_county").html(county);
				$("#loc_postcode").html(lblpost);
				$("#loc_country").html(country);
				
				$('.loc_address').val(premise+""+addres);
				$('.loc_district').val(district);
				$('.loc_city').val(city);
				$('.loc_region').val(region);
				$('.loc_county').val(county);
				$('.loc_postcode').val(postal_code);
				$('.loc_country').val(country);
				
				if($("#isSameLocationAddr").is(':checked'))
				{
					var bill_addr = "";
					
					if(addres != "") {
						bill_addr += premise+""+addres+", ";
					}		
					
					if(city != "") {
						bill_addr += city+", ";
					}		
					
					if(postal_code != "") {
						bill_addr += postal_code+", ";
					}
					
					if(region != "") {
						bill_addr += region;
					}
					
					$('#billing_address').html(bill_addr);
					$('.billing_address').val(premise+""+addres);
					$('.billing_city').val(city);
					$('.billing_region').val(region);
					$('.billing_postcode').val(postal_code);
				}	
				
				var address = "";
				var lat = place.geometry.location.lat();
				var lng = place.geometry.location.lng();
				
				if(addres != "") {
					address += premise+""+addres+", ";
				} 
				if(city != "") {
					address += city+", ";
				} 
				if(district != "") {
					address += " ("+district+")"+", ";
				} 
				if(region != "") {
					address += region;
				}		
				
				document.getElementById('location_address').value = address;
				document.getElementById('lat').value = lat;
				document.getElementById('lng').value = lng;
			});
			
			// this function will work on marker move event into map 
			google.maps.event.addListener(marker, 'dragend', function() {
				geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
					if (status == google.maps.GeocoderStatus.OK) {
						if (results[0]) {        
							bindDataToForm(results[0].formatted_address,marker.getPosition().lat(),marker.getPosition().lng());
						}
					}
				});
			});
		}
		google.maps.event.addDomListener(window, 'load', initialize);
		 
		$(".updateLocationAddr").click( function(){
			var addr = $("#editBusinessLocation .loc_address").val();	
			var loc_apt = $("#editBusinessLocation .loc_apt").val();	
			var loc_district = $("#editBusinessLocation .loc_district").val();	
			var loc_city = $("#editBusinessLocation .loc_city").val();	
			var loc_county = $("#editBusinessLocation .loc_county").val();	
			var loc_region = $("#editBusinessLocation .loc_region").val();	
			var loc_postcode = $("#editBusinessLocation .loc_postcode").val();	
			var loc_country = $("#editBusinessLocation .loc_country").val();	
			
			var address = "";
			
			if(addr != "") {
				address += addr+", ";
			} 
			if(loc_city != "") {
				address += loc_city;
			}
			if(loc_district != "") {
				address += " ("+loc_district+")";
			} 
			if(loc_region != "") {
				address += ", "+loc_region;
			}
			document.getElementById('location_address').value = address;
			
			if(addr == "") {
				var lbladd = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lbladd = addr;
			}		
			
			if(loc_district == "") {
				var lbldist = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lbldist = loc_district;
			}		
			
			if(loc_postcode == "") {
				var lblpost = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lblpost = loc_postcode;
			}
			
			if(loc_apt == "") {
				var lblapt = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lblapt = loc_apt;
			}
			
			$("#loc_address").html(lbladd);
			$("#loc_apt").html(lblapt);
			$("#loc_district").html(lbldist);
			$("#loc_city").html(loc_city);
			$("#loc_region").html(loc_region+", ");
			$("#loc_county").html(loc_county);
			$("#loc_postcode").html(lblpost);
			$("#loc_country").html(loc_country);
			
			$('.loc_address').val(addr);
			$('.loc_apt').val(loc_apt);
			$('.loc_district').val(loc_district);
			$('.loc_city').val(loc_city);
			$('.loc_region').val(loc_region);
			$('.loc_county').val(loc_county);
			$('.loc_postcode').val(loc_postcode);
			$('.loc_country').val(loc_country);
			
			if($("#isSameLocationAddr").is(':checked'))
			{
				var bill_addr = "";
					
				if(addr != "") {
					bill_addr += addr+", ";
				}		
				
				if(loc_apt != "") {
					bill_addr += loc_apt+", ";
				}		
				
				if(loc_city != "") {
					bill_addr += loc_city+", ";
				}		
				
				if(loc_postcode != "") {
					bill_addr += loc_postcode+", ";
				}
				
				if(loc_region != "") {
					bill_addr += loc_region;
				}
				
				$('#billing_address').html(bill_addr);
				$('.billing_address').val(addr);
				$('.billing_apt').val(loc_apt);
				$('.billing_city').val(loc_city);
				$('.billing_region').val(loc_region);
				$('.billing_postcode').val(loc_postcode);
			}
		});	
		
		$(document).ready( function(){
			var form = KTUtil.getById('updateBillingAddress');
			
			FormValidation
            .formValidation(
                form, {
                    fields: {
                        billing_address: {
                            validators: {
                                callback: {
									message: 'Address is required',
									callback: function(input) {
										if($("#isSameLocationAddr").is(':checked'))
										{
											return true;
										} else {
											if(input.value == "") {
												return false;
											} else {
												return true;
											}		
										}		
									}
								}
                            }
                        },
                        billing_city: {
                            validators: {
                                callback: {
									message: 'City is required',
									callback: function(input) {
										if($("#isSameLocationAddr").is(':checked'))
										{
											return true;
										} else {
											if(input.value == "") {
												return false;
											} else {
												return true;
											}		
										}		
									}
								}
                            }
                        },
                        billing_region: {
                            validators: {
                                callback: {
									message: 'Region is required',
									callback: function(input) {
										if($("#isSameLocationAddr").is(':checked'))
										{
											return true;
										} else {
											if(input.value == "") {
												return false;
											} else {
												return true;
											}		
										}		
									}
								}
                            }
                        },
                        billing_postcode: {
                            validators: {
                                callback: {
									message: 'Postcode is required',
									callback: function(input) {
										if($("#isSameLocationAddr").is(':checked'))
										{
											return true;
										} else {
											if(input.value == "") {
												return false;
											} else {
												return true;
											}		
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
               
				$(".closeBillingMdl").trigger("click");
				
				if($("#isSameLocationAddr").is(':checked')) {
					$(".isSameBillingAddr").val(0);
				} else {
					$(".isSameBillingAddr").val(1);
				}		

				var comp = $("#editBillingDetail .billing_company_name").val();
				var addr = $("#editBillingDetail .billing_address").val();
				var apt = $("#editBillingDetail .billing_apt").val();
				var city = $("#editBillingDetail .billing_city").val();
				var region = $("#editBillingDetail .billing_region").val();
				var postcode = $("#editBillingDetail .billing_postcode").val();
				var notes = $("#editBillingDetail .billing_notes").val();

				var address = "";
				
				if(addr != "") {
					address += addr+", ";
				}
				if(apt) {
					address += apt+", ";
				}	
				if(city != "") {
					address += city+", ";
				}	
				if(postcode != "") {
					address += postcode+", ";
				}	
				if(region != "") {
					address += region;
				}	
				
				if(notes != "") {
					$("#billing_notes").html(notes);
					$(".billing_notes").val(notes);
				} else {
					$("#billing_notes").html('<a data-toggle="modal" data-target="#editBillingDetail" class="text-blue cursor-pointer">+ Add</a>');
				}		
				
				$("#billing_company_name").html(comp);
				$(".billing_company_name").val(comp);
				
				$('#billing_address').html(address);
				$('.billing_address').val(addr);
				$('.billing_apt').val(apt);
				$('.billing_city').val(city);
				$('.billing_region').val(region);
				$('.billing_postcode').val(postcode);
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
		});	

		$(window).on("scroll", function() {   
	        var topHeight = $('.my-custom-header').outerHeight(); 
	        if ($(this).scrollTop() > topHeight) {
				$('.my-custom-header').addClass("bg-white");
				$('.my-custom-header').addClass("border-bottom");  
				$('.hide-onscroll').css('opacity','0');
	        } else{ 
	          	$('.my-custom-header').removeClass("bg-white"); 
	          	$('.my-custom-header').removeClass("border-bottom");  
	          	$('.hide-onscroll').css('opacity','0.999');
	        }   
	    }); 
	</script>
@endsection