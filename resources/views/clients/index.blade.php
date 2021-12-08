{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
#clientList_filter{
	display:none;
}
</style>
@endsection

@section('content')
<div class="d-flex flex-column-fluid">
	<div class="container">
		<div class="content-header ">
			<div class="a-flex justify-content-between">
				<div class="calender-div">
					<div class="input-group date">
						<div class="form-group width-100">
							<div class="input-icon">
								<input type="text" class="font-weight-500 form-control" placeholder="Searched by Name, Mobile no or Email" id="myInputTextField">
								<span>
									<i class="flaticon2-search-1 icon-md"></i>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="action-btn-div width-auto">
					<div class="dropdown dropdown-inline mr-2">
						<button type="button"
							class="btn btn-light-primary font-weight-bolder dropdown-toggle my-2"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							Options</button>
						<!--begin::Dropdown Menu-->
						<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
							<!--begin::Navigation-->
							<ul class="navi flex-column navi-hover py-2">
								@if (Auth::user()->can('can_download_clients'))
									<li class="navi-item">
										<a href="{{ route('clientinfoexcel') }}" class="navi-link">
											<span class="navi-text">Download Excel</span>
										</a>
									</li>
									<li class="navi-item">
										<a href="{{ route('clientdownloadcsv') }}" class=" navi-link">
											<span class="navi-text">Download CSV</span>
										</a>
									</li>
								@else	
									<li class="navi-item">
										<a href="javascript:;" class="no_access navi-link">
											<span class="navi-text">Download Excel</span>
										</a>
									</li>
									<li class="navi-item">
										<a href="javascript:;" class="no_access navi-link">
											<span class="navi-text">Download CSV</span>
										</a>
									</li>
								@endif
							</ul>
						</div>
					</div>
					<button class="font-weight-bold btn btn-primary" data-toggle="modal" data-target="#clientModal">New Client</button>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
					<div class="card card-custom card-stretch gutter-b">
						<div class="table-responsive invoice">
							<table class="table table-hover clien_tbl" id="clientList">
								<thead>
									<tr>
										<!-- <th scope="col"></th> -->
										<th scope="col">Name</th>
										<th scope="col">Mobile number</th>
										<th scope="col">Email</th>
										<th scope="col">Gender</th>
									</tr>
								</thead>
								<tbody>
									
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal fade p-0" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel Label"
	aria-modal="true">
	<div class="modal-dialog add-new-client-modal full-width-modal" role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h1 class="font-weight-bolder m-0 text-center" id="clientModalLabel">New Client
				</h1>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
				</button>
			</div>
			<form method="POST" enctype="multipart/form-data" action="{{ route('addClient') }}" id="addClient">
				@csrf
				<div class="modal-body" style="max-height: calc(100vh - 155px);overflow-y: scroll;">
					<div class="container">
						<div class="row">
							<div class="col-12 col-md-6 p-2" style="border-right: 2px solid grey;">
								<div class="client_pupop" style="margin-right: 17px;">
									<div class="d-flex">
										<div class="form-group  mr-2 w-100">
											<label for="firstname">First name</label>
											<input type="text" class="form-control" placeholder="john" name="firstname" id="firstname">
										</div>
										<div class="form-group  ml-2 w-100">
											<label for="lastname">Last name</label>
											<input type="text" class="form-control" placeholder="Doe" name="lastname" id="lastname">
										</div>
									</div>
									<div class="d-flex">
										<div class="form-group mr-2 w-100 mobileNumberclass">
											<label for="mobileNumber">Mobile</label>
											<input type="tel" class="form-control allow_only_numbers" id="mobileNumber" name="mobileno[main]" style="padding-right: 34px;">
											<input type="hidden" class="form-control" id="mo_country_code" name="mo_country_code" maxlength="10" value="1">
										</div>
										<div class="form-group ml-2 w-100 telephoneclass">
											<label for="telephone">Telephone</label>
											<input type="tel" class="form-control allow_only_numbers" id="telephone" name="telephoneno[main]">
											<input type="hidden" class="form-control" id="tel_country_code" name="tel_country_code" maxlength="10" value="1">
										</div>
									</div>
									<div class="form-group">
										<label for="email">Email</label>
										<input type="email" class="form-control" placeholder="" name="email" id="email">
									</div>
									<div class="form-group">
										<label for="send_notification_by">Send notifications by</label>
										<select class="form-control" id="send_notification_by" name="send_notification_by">
											<option>Don't send notifications</option>
											<option>Email</option>
											<option>SMS</option>
											<option>Email & SMS</option>
										</select>
									</div>
									<div class="form-group">
										<label for="preferred_language">Preferred language</label>
										<select class="form-control" id="preferred_language" name="preferred_language">
											<option value="en">Use provider language (English)</option>
											<option value="bg">Bulgarian (български)</option>
											<option value="cs">Czech (čeština)</option>
											<option value="da">Danish (dansk)</option>
											<option value="de">German (Deutsch)</option>
											<option value="el">Greek (Ελληνικά)</option>
											<option value="en">English (English)</option>
											<option value="es">Spanish (español)</option>
											<option value="fi">Finnish (suomi)</option>
											<option value="fr">French (français)</option>
											<option value="hr">Croatian (hrvatski)</option>
											<option value="hu">Hungarian (magyar)</option>
											<option value="it">Italian (italiano)</option>
											<option value="lt">Lithuanian (lietuvių)</option>
											<option value="nb">Norwegian Bokmål (norsk bokmål)</option>
											<option value="nl">Dutch (Nederlands)</option>
											<option value="pl">Polish (polski)</option>
											<option value="pt">Portuguese (português)</option>
											<option value="ro">Romanian (română)</option>
											<option value="sv">Swedish (svenska)</option>
											<option value="ru">Russian (русский)</option>
											<option value="uk">Ukrainian (українська)</option>
											<option value="sl">Slovenian (slovenščina)</option>
										</select>
									</div>
									<div class="form-group">
										<div class="checkbox-list">
											<label class="checkbox">
												<input type="checkbox" name="accept_marketing_notification" id="accept_marketing_notification" value="1">
												<span></span>Accepts marketing notifications
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6">
								<div class="client_pupop">
									<ul class="nav nav-tabs nav-tabs-line">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#persnolTab">Personal
												Info</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#addressTab">Address</a>
										</li>
									</ul>
									<div class="tab-content mt-5" id="myTabContent">
										<div class="tab-pane fade show active" id="persnolTab" role="tabpanel"
											aria-labelledby="persnolTabs" style="margin-left:11px;">
											<div class="d-flex justify-content-between">
												<div class="form-group mr-2 w-100">
													<label for="gender">Gender</label>
													<select class="form-control" id="gender" name="gender">
														<option>Male</option>
														<option>Female</option>
														<option>Unknown</option>
													</select>
												</div>
												<div class="form-group ml-2 w-100">
													<label for="referral_source">Referral source</label>
													<select class="form-control" id="referral_source" name="referral_source">
														<option>Select source</option>
														<option>Walk-In</option>
														<?php
															if(!empty($referralSources)){
																foreach($referralSources as $referralSourcesKey => $referralSourcesValue){
																	echo "<option>". $referralSourcesValue['name'] ."</option>";
																}
															}
														?>
													</select>
												</div>
											</div>
											
											<div class="form-group">
												<label for="birthdate" style="margin-left:1px;">Birthday</label>
												<div class="">

												<select id="day" class="day" name="date_[day]"></select>

												<select id="month" class="month" name="date_[month]" style="width:72px;"></select>

												<select id="year" class="year" name="date_[year]"></select>
											</div>
											</div>
											<div class="form-group ml-2">
												<label for="client_notes">Client Note</label>
												<input type="text" class="form-control" name="client_notes" id="client_notes">
											</div>
											<div class="form-group">
												<div class="checkbox-list">
													<label class="checkbox">
														<input type="checkbox" name="display_on_all_bookings" id="display_on_all_bookings" >
														<span></span>Display on all bookings
													</label>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="addressTab" role="tabpanel" aria-labelledby="addressTab" style="margin-left:11px;">
											<div class="form-group">
												<label for="address">Address</label>
												<input type="text" class="form-control" name="address" id="address">
											</div>
											<div class="form-group">
												<label for="suburb">Suburb</label>
												<input type="email" class="form-control" name="suburb" id="suburb">
											</div>
											<div class="d-flex">
												<div class="form-group  mr-2 w-100">
													<label for="city">City</label>
													<input type="text" class="form-control" name="city" id="city">
												</div>
												<div class="form-group  ml-2 w-100">
													<label for="state">State</label>
													<input type="text" class="form-control" name="state" id="state">
												</div>
											</div>
											<div class="form-group">
												<label for="zipcode">Zip / Post Code</label>
												<input type="text" class="form-control" name="zipcode" id="zipcode">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer text-right position-fixed bg-white bottom-0 w-100 d-block zindex-2">
					<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary font-weight-bold" id="addClientSubmitButton"style="width: 71px;">Save</button>
				</div>
			</form>
		</div>
	</div>
</div>


<div class="modal fade" id="importClientModal" tabindex="-1" role="dialog" aria-labelledby="importClientModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg importClientModal" role="document">
		<div class="modal-content d-flex flex-row">
			<div class="bg-light-primary modal-image d-none d-md-block">
				<svg viewBox="0 0 195 152" xmlns="http://www.w3.org/2000/svg">
					<g fill="none" fill-rule="evenodd">
						<g fill="#101928" fill-rule="nonzero">
							<path d="M46.64 68.4l-.624 2.935-2.935-.624.624-2.934zM159.127 92.31l-.624 2.935-2.935-.624.624-2.934zM45.976 82.283l-2.998-.105.105-2.998 2.998.105zM157.002 81.925l2.998.105-.105 2.998-2.998-.105zM156.67 72.98l2.97-.417.417 2.971-2.97.417zM153.454 63.451l2.853-.927.927 2.853-2.853.928zM149.11 54.722l2.648-1.409 1.409 2.649-2.649 1.408zM143.641 46.78l2.364-1.846 1.847 2.364-2.364 1.847zM137.05 39.618l2.008-2.23 2.23 2.008-2.008 2.23zM129.338 33.222l1.59-2.544 2.544 1.59-1.59 2.544zM81.143 133.275l-1.124 2.782-2.781-1.124 1.123-2.782zM121.065 29.747l1.124-2.782 2.781 1.124-1.123 2.781zM91.313 137.192l-.623 2.935-2.935-.624.624-2.935zM115.223 24.705l-.624 2.935-2.934-.624.624-2.935zM101.283 137.024l-.105 2.998-2.998-.105.105-2.998zM100.925 25.998L101.03 23l2.998.105-.105 2.998zM110.227 136.691l.418 2.97-2.971.418-.418-2.97zM91.98 26.33l-.417-2.97 2.971-.417.417 2.97zM116.695 135.38l2.854-.926.927 2.853-2.853.927zM85.305 28.62l-2.854.926-.927-2.853 2.853-.927zM125.63 131.518l2.648-1.408 1.409 2.648-2.649 1.409zM76.37 32.482l-2.648 1.408-1.409-2.648 2.649-1.409zM133.855 126.488l2.364-1.847 1.847 2.364-2.364 1.847zM68.145 37.512L65.78 39.36l-1.847-2.364 2.364-1.847zM141.374 120.28l2.008-2.23 2.23 2.008-2.008 2.23zM60.626 43.72l-2.008 2.23-2.23-2.008 2.008-2.23zM148.188 112.882l1.59-2.544 2.544 1.59-1.59 2.544zM54.79 51.326L53.2 53.87l-2.544-1.59 1.59-2.544zM152.13 104.847l1.123-2.782 2.782 1.124-1.124 2.781zM50.849 59.361l-1.124 2.782-2.782-1.124 1.124-2.781z">
							</path>
						</g>
						<g transform="translate(0 68)">
							<circle fill="#E1EFFC" cx="42" cy="42" r="42"></circle>
							<g fill-rule="nonzero">
								<path fill="#A4ADBA" d="M57.063 74.24v-7.618h7.627z"></path>
								<path fill="#FFF" d="M16.064 6.624H64.69v57.141h-9.725v9.524H16.064v-26.19z">
								</path>
								<path fill="#DCDDDE" d="M50.961 23.766h7.056v6.666h-7.056zM36.849 23.766h7.056v6.666h-7.056zM22.738 23.766h7.056v6.666h-7.056z">
								</path>
								<path d="M51.104 32.337h9.772v-8.57h-9.772v8.57zm2.443-6.122h4.886v3.673h-4.886v-3.673zM36.444 23.766v8.571h9.773v-8.57h-9.773zm7.33 6.122h-4.887v-3.673h4.887v3.673zM31.558 23.766h-9.773v8.571h9.773v-8.57zm-2.443 6.122h-4.887v-3.673h4.887v3.673zM21.785 39.004h35.278v2.449H21.785zM21.785 43.902h25.282v2.449H21.785zM21.785 48.799h31.162v2.449H21.785zM21.785 53.697h23.518v2.449H21.785z" fill="#0C3847"></path>
								<path
									d="M14.304 47.575v28.57H57.16l10.39-10.22V4.719H14.305v42.856zm43.565 24.506v-5.459h5.55l-5.55 5.46zM16.724 7.1H65.13v57.141h-9.68v9.524H16.723V7.1z"
									fill="#101928"></path>
							</g>
							<g transform="translate(49.63 49.472)" fill-rule="nonzero">
								<ellipse stroke="#101928" stroke-width="2" fill="#DEE3E7" cx="16.014" cy="15.245" rx="15.255" ry="15.238"></ellipse>
								<path d="M24.146 15.944c.576 0 1.037.461 1.037 1.037v5.645c0 .576-.46 1.037-1.037 1.037H8.094c-.576 0-1.075-.46-1.075-1.037v-5.645c0-.576.5-1.037 1.075-1.037.576 0 1.037.461 1.037 1.037v4.57H23.11v-4.57c0-.576.461-1.037 1.037-1.037z" fill="#101928"></path>
								<path d="M15.39 18.287l-4.223-4.224c-.423-.423-.423-1.037 0-1.46.422-.422 1.036-.422 1.459 0l2.457 2.42V6.037c0-.576.461-1.037 1.037-1.037.576 0 1.037.46 1.037 1.037v8.986l2.42-2.42a1.043 1.043 0 0 1 1.497 0c.423.423.423 1.037 0 1.46l-4.224 4.224a1.05 1.05 0 0 1-.73.307 1.05 1.05 0 0 1-.73-.307z" fill="#101928"></path>
							</g>
						</g>
						<g transform="translate(109)" fill-rule="nonzero">
							<circle cx="42.807" cy="42.807" r="42.807" fill="#EBF5FD"></circle>
							<circle fill="#FFF" opacity=".686" cx="43" cy="43" r="24.099"></circle>
							<path d="M51.033 40.165c0 3.91-3.18 7.088-7.088 7.088a7.095 7.095 0 0 1-7.088-7.088c0-3.91 3.18-7.088 7.088-7.088a7.095 7.095 0 0 1 7.088 7.088z" fill="#FFF"></path>
							<path d="M56.703 56.388v7.47c-3.756 2.063-8.108 3.24-12.758 3.24-4.65 0-9.002-1.177-12.758-3.24v-7.47c0-2.959 2.537-5.355 5.67-5.355h14.176c3.133 0 5.67 2.396 5.67 5.355z" fill="#FFC857"></path>
							<path d="M62.29 60.355c4.15-4.607 6.699-10.681 6.699-17.355 0-14.33-11.658-25.989-25.989-25.989-14.33 0-25.989 11.658-25.989 25.989 0 14.33 11.658 25.989 25.989 25.989 6.674 0 12.748-2.55 17.355-6.7M19.747 43c0-12.822 10.43-23.253 23.253-23.253 12.822 0 23.253 10.43 23.253 23.253 0 7.706-3.783 14.53-9.575 18.763v-5.085a6.847 6.847 0 0 0-6.839-6.839H36.161a6.847 6.847 0 0 0-6.84 6.84v5.084c-5.79-4.234-9.574-11.057-9.574-18.763m12.31 20.507v-6.829a4.109 4.109 0 0 1 4.104-4.103h5.471v8.207h2.736v-8.207h5.471a4.109 4.109 0 0 1 4.104 4.103v6.829A23.11 23.11 0 0 1 43 66.253a23.11 23.11 0 0 1-10.943-2.746z" fill="#101928"></path>
							<path d="M34.967 41.11c0 4.43 3.603 8.033 8.033 8.033s8.033-3.603 8.033-8.033S47.43 33.077 43 33.077s-8.033 3.603-8.033 8.033zm13.388 0A5.36 5.36 0 0 1 43 46.465a5.36 5.36 0 0 1-5.355-5.355A5.36 5.36 0 0 1 43 35.755a5.36 5.36 0 0 1 5.355 5.355z" fill="#101928"></path>
						</g>
					</g>
				</svg>
			</div>
			<div class="">
				<div class="modal-header flex-center">
					<h5 class="modal-title" id="importClientModalLabel">Import Clients To Fresha
					</h5>
					<h3 class="text-dark ml-auto cursor-pointer" data-dismiss="modal" aria-label="Close">&times;
					</h3>
				</div>
				<div class="card-body p-4">
					<h5 class="m-3 text-muted card-text">Follow the simple instructions below to import clients
						to your Fresha
						partner account</h5>
					<div class="progress-wrapper">
						<ul class="StepProgress">
							<li class="StepProgress-item current">Download your import spreadsheet using
								the button below</li>
							<li class="StepProgress-item current">Copy and paste your client details
								into the spreadsheet
							</li>
							<li class="StepProgress-item current">Email it back to us on <a class="text-blue"
									href="mailto:hello@fresha.com">hello@fresha.com</a> and we'll add the
								clients to your account!</li>
						</ul>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-primary">Download import file</button>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
	<script src="{{ asset('js/addClient.js') }}"></script>
	<script src="{{ asset('js/fullcalendar.bundle.js') }}"></script>
	<script src="{{ asset('js/jquery.date-dropdowns.js') }}"></script>
	<script src="{{ asset('js/widgets.js') }}"></script>
	<script src="{{ asset('js/intlTelInput.min.js') }}"></script>
	<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>	
		
	<script>
	$("#birthdate").dateDropdowns({
		submitFormat: "yyyy-mm-dd"
	});
	
	$(function() {
		var table = $('#clientList').DataTable({
			processing: true,
			serverSide: true,
			"paging": false,
			"ordering": true,
			"info":     false,
			ajax: {
				type: "POST",
				url : "{{ route('getClient') }}",
				data: {_token : "{{csrf_token()}}"}
			},
			columns: [
				
				// {data: 'profile', profile: 'profile'},
				{data: 'name', name: 'name'},
				{data: 'mobileno', name: 'mobileno'},
				{data: 'email', name: 'email'},
				{data: 'gender', name: 'gender'},
			]			
		});	
		
		$(document).on('click','.viewclient',function(){
		var thisID = $(this).data('id');
		var url = "{{ url('/partners/view/') }}";
		if(thisID != "" && typeof thisID !== "undefined") {
			window.location = url+'/'+thisID + '/' + 'client';
		}
	});
		$('#myInputTextField').keyup(function(){
			  table.search($(this).val()).draw();
		});
	});
	
	function fieldU(fieldId, id) {
		$("#" + fieldId).val(id);
	}
	
	</script>
	
	<!-- For Internationa Number script -->
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
		// jQuery 
		var phone_number = window.intlTelInput(document.querySelector("#mobileNumber"), {
		  separateDialCode: true,
		  preferredCountries:["ca"],
		  hiddenInput: "full",
		  utilsScript: "{{ asset('js/utils.js') }}"
		});
		
		$(document).on("click keypress keydown keyup", '.mobileNumberclass .iti__country' , function(e) {
			e.preventDefault();
			
			if($(this).hasClass("iti__active")){
				var country_code = $(this).attr("data-dial-code");
				$("#mo_country_code").val(country_code);
			} else {
				var country_code = $(this).attr("data-dial-code");
				$("#mo_country_code").val(country_code);
			}
		});	

		$(document).on('keypress', '.allow_only_numbers', function(evt){
			evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;
			
		    console.log($(this).val().length);
		    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    if($(this).val().length > 9){
		    	return false;	
		    }
		    return true;
		});

		$(document).on('paste', '.allow_only_numbers', function (event) {
		  	if (event.originalEvent.clipboardData.getData('Text').match(/[^\d]/)) {
		    	event.preventDefault();
		  	}
		});

		$(document).ready(function() {
			//birthday fields dropdown 
		  	const monthNames = ["January", "February", "March", "April", "May", "June",
		   		"July", "August", "September", "October", "November", "December"
		  	];

			let custom_d = new Date();
			let custom_year = custom_d.getFullYear();
			let qntYears = custom_year - 1900;
			let selectYear = $("#year");
			let selectMonth = $("#month");
			let selectDay = $("#day");
			let currentYear = new Date().getFullYear();

			for (var y = 0; y < qntYears; y++) {
				let date = new Date(currentYear);
				let yearElem = document.createElement("option");
				yearElem.value = currentYear
				yearElem.textContent = currentYear;
				selectYear.append(yearElem);
				currentYear--;
			}

			for (var m = 0; m < 12; m++) {
				let month = monthNames[m];
				let monthElem = document.createElement("option");
				monthElem.value = (m+1);
				monthElem.textContent = month;
				selectMonth.append(monthElem);
			}

			var d = new Date();
			var month = d.getMonth();
			var year = d.getFullYear();
			var day = d.getDate();

			console.log(selectDay.val());

			selectYear.val(year);
			selectYear.on("change", AdjustDays);
			selectMonth.val(month);
			selectMonth.on("change", AdjustDays);

		  	AdjustDays();
		  	if(selectDay.val() == ""){
	      		selectDay.val(day)
	      	}

		function AdjustDays() {
		    var year = selectYear.val();
		    var month = parseInt(selectMonth.val());
		    selectDay.empty();


		    //get the last day, so the number of days in that month
		    var days = new Date(year, month, 0).getDate();

		    //lets create the days of that month
		    // console.log(selectDay.val());
		    for (var d = 1; d <= days; d++) {
		      	var dayElem = document.createElement("option");
		      	dayElem.value = d;
		      	dayElem.textContent = d;
		      	/*if(selectDay.val() != ""){
		      		selectDay.val(day)
		      	}*/
		      	selectDay.append(dayElem);
		    }
		}

		$(document).on('click','.open-client-modal', function(){
			$('#addClient')[0].reset();
		});
	});
	$("#zipcode, #suburb").keypress(function (e) {
        var keyCode = e.keyCode || e.which;

        $("#lblError").html("");

        //Regex for Valid Characters i.e. Alphabets and Numbers.
        var regex = /^[A-Za-z0-9]+$/;

        //Validate TextBox value against the Regex.
        var isValid = regex.test(String.fromCharCode(keyCode));
        if (!isValid) {
            return false;
        }

        return isValid;
    });
</script>
	
<script>
		$(function () {
			$('input[name="daterange"]').daterangepicker({
				opens: 'left'
			}, function (start, end, label) {
				console.log("A new date selection was made: " + start.format('YYYY-MM-DD') + ' to ' + end.format('YYYY-MM-DD'));
			});
		});
	</script>
@endsection