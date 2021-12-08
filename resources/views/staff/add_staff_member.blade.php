{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!--begin::Tabs-->
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
<?php
$id = "";
$user_id = "";
$staff_user_id = "";
$firstname = "";
$lastname = "";
$mobileno = "";
$tel_country_code = "1";
$email = "";
$user_permission = "";
$staff_title = "";
$staff_notes = "";
$is_appointment_booked = "";
$appointment_color = "";
$employment_start_date = date('D, d M Y');
$employment_end_date = "";
$service_commission = "";
$product_commission = "";
$voucher_commission = "";
$paid_plan_commision = "";
$page_title = "New Staff";

if(!empty($staff))
{
	$id = $staff->id;
	$staff_user_id = $staff->staff_user_id;
	$user_id = $staff->user_id;
	$firstname = $staff->first_name;
	$lastname = $staff->last_name;
	$tel_country_code = $staff->country_code;
	$mobileno = $staff->phone_number;
	$email = $staff->email;
	$user_permission = $staff->user_permission;
	$staff_title = $staff->staff_title;
	$staff_notes = $staff->staff_notes;
	$is_appointment_booked = $staff->is_appointment_booked;
	$appointment_color = $staff->appointment_color;
	$employment_start_date = date('D, d M Y',strtotime($staff->employment_start_date));
	$employment_end_date = ($staff->employment_end_date != '') ? date('D, d M Y',strtotime($staff->employment_end_date)) : "";
	$service_commission = $staff->service_commission;
	$product_commission = $staff->product_commission;
	$voucher_commission = $staff->voucher_commission;
	$paid_plan_commision = $staff->paid_plan_commision;
	$page_title = "Edit Staff";
}
?>
<div class="container-fluid p-0">
	<div class="modal fade p-0" id="deleteStaffModal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			{{ Form::open(array('url' => '','id' => 'delStaff')) }}
				<input type="hidden" name="deleteID" id="deleteID" value="{{ $id }}">
				<div class="modal-content ">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center">Delete Staff</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<div class="modal-body">
						<div class="container">
							<div class="row">
								<p>Are you sure you want to delete this staff member?</p>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<button type="button" id="deleteStaff" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Delete</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
	<div class="my-custom-body-wrapper">
		<div class="my-custom-header">
			<div class="p-4 d-flex justify-content-between border-bottom">
				<h2 class="m-auto font-weight-bolder">{{ $page_title }}</h2>
				<h1 class="cursor-pointer cls-btn" onclick="window.location.href='{{ route('staff_members') }}'">&times;</h1>
			</div>
		</div>
		<div class="my-custom-body">
			<div class="container">
				<form method="POST" enctype="multipart/form-data" action="{{ route('addStaff') }}" id="addStaffMember">
					<input type="hidden" name="edit" value="{{ $id }}">
					<input type="hidden" name="staff_user_edit_id" value="{{ $staff_user_id }}">
					<div class="modal-body container">
						<ul class="nav nav-tabs nav-tabs-line nav-tabs-primary  nav-tabs-line-3x">
							<li class="nav-item">
								<a class="nav-link active" data-toggle="tab" href="#details">Details</a>
							</li>
							<li class="nav-item">
								<a class="nav-link services_tab service_tab_opt" data-toggle="tab" href="#services">Services</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#locations">Locations</a>
							</li>
							<li class="nav-item">
								<a class="nav-link" data-toggle="tab" href="#commission">Commission</a>
							</li>
						</ul>
						<div class="tab-content mt-5" id="myTabContent">
							@csrf
							<div class="tab-pane fade show active" id="details" role="tabpanel" aria-labelledby="kt_tab_pane_2">
								<div class="row">
									<div class="col-12 col-md-6">
										<div class="d-flex justify-content-between">
											<div class="form-group mr-2 w-100">
												<label for="firstname">First Name<span class="text-danger">*</span></label>
												<input type="text" class="form-control" id="firstname" name="firstname" placeholder="John" value="{{ $firstname }}">
											</div>
											<div class="form-group ml-2 w-100">
												<label>Last Name</label>
												<input type="text" class="form-control" id="lastname" name="lastname" placeholder="Doe" value="{{ $lastname }}">
											</div>
										</div>
										<input type="hidden" name="tel_country_code" id="tel_country_code" value="{{ $tel_country_code }}">
										<div class="form-group telephoneclass">
											<label>Mobile Number</label>
											<input type="tel" class="form-control allow_only_numbers" id="mobileno" name="mobileno" placeholder="" id="telephone" value="{{ '+91'.$mobileno }}">
										</div>
										<div class="form-group">
											<label>Email<span class="text-danger">*</label>
											<input type="email" class="form-control" id="email" name="email" placeholder="test@#gmail.com" value="{{ $email }}">
										</div>
										<div class="form-group font-weight-bold m-0">
											<label>User Permission
												<span class="text-dark">
													<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom" title="When saved, this staff member will receive email instructions to setup their own login password" data-original-title="When saved, this staff member will receive email instructions to setup their own login password" class="dark-tooltip">
													</i>
												</span>
											</label>
											<select class="form-control" id="select-location" id="user_permission" name="user_permission">
												<option selected="" value="0">No Access</option>
												@foreach($roles as $val)
													<option @if ($val->id == $user_permission) selected="selected" @endif value="{{ $val->id }}">{{ $val->name }}</option>
												@endforeach	
											</select>
										</div>
										<div class="form-group">
											<div class="mt-4 switch switch-icon switch-primary"
												style="line-height: 33px;">
												<label class="d-flex align-item-center">
													<input type="checkbox" @if ($is_appointment_booked == 1) checked="checked" @endif id="is_appointment_booked" value="1" name="is_appointment_booked">
													<span></span>
													ENABLE APPOINTMENT BOOKINGS <i class="fa fa-question-circle ml-2"
														data-toggle="tooltip" data-placement="bottom" title=""
														data-original-title="When saved, this staff member will receive email instructions to setup their own login password"
														style="line-height: 33px;"></i>
												</label>
											</div>
										</div>
										<div class="form-group" id="appointment-colors" style="@if ($is_appointment_booked == 0) display: none; @endif">
											<label class="form-label">Appoinment Color
												<span class="text-dark">
													<i class="fa fa-question-circle" data-toggle="tooltip" data-placement="bottom"
														title="See your Calendar Settings page under Setup to set how colors are displayed on the calendar" data-original-title="See your Calendar Settings page under Setup to set how colors are displayed on the calendar" class="dark-tooltip">
													</i>
												</span>
											</label>
											<div class="radio-inline flex-wrap appoinment-radio">
												<label class="radio radio-accent pink">
													<input type="radio" checked value="#FF9CBB" <?php echo ($appointment_color == "#FF9CBB") ? "checked='checked'": ""; ?> name="appointment_color" />
													<span></span>
												</label>
												<label class="radio radio-accent purple">
													<input type="radio" value="#E2A6E6" <?php echo ($appointment_color == "#E2A6E6") ? "checked='checked'": ""; ?> name="appointment_color" />
													<span></span>
												</label>
												<label class="radio radio-accent indigo">
													<input type="radio" value="#BBC1E8" <?php echo ($appointment_color == "#BBC1E8") ? "checked='checked'": ""; ?> name="appointment_color" />
													<span></span>
												</label>
												<label class="radio radio-accent blue">
													<input type="radio" value="#A5DFF8" <?php echo ($appointment_color == "#A5DFF8") ? "checked='checked'": ""; ?> name="appointment_color" />
													<span></span>
												</label>
												<label class="radio radio-accent teal">
													<input type="radio" value="#6CD5CB" <?php echo ($appointment_color == "#6CD5CB") ? "checked='checked'": ""; ?> name="appointment_color" />
													<span></span>
												</label>
												<label class="radio radio-accent green">
													<input type="radio" value="#A6E5BD" <?php echo ($appointment_color == "#A6E5BD") ? "checked='checked'": ""; ?> name="appointment_color" />
													<span></span>
												</label>
												<label class="radio radio-accent lime">
													<input type="radio" value="#E7F286" <?php echo ($appointment_color == "#E7F286") ? "checked='checked'": ""; ?> name="appointment_color" />
													<span></span>
												</label>
												<label class="radio radio-accent lime">
													<input type="radio" value="#E7F286" <?php echo ($appointment_color == "#E7F286") ? "checked='checked'": ""; ?> name="appointment_color" />
													<span></span>
												</label>
												<label class="radio radio-accent yellow">
													<input type="radio" value="#FFEC78" <?php echo ($appointment_color == "#FFEC78") ? "checked='checked'": ""; ?> name="appointment_color" />
													<span></span>
												</label>
											</div>
										</div>
									</div>
									<div class="col-12 col-md-6">
										<div class="form-group mr-2 w-100">
											<label>Staff Title</label>
											<input type="text" class="form-control" id="staff_title" name="staff_title" placeholder="" value="{{ $staff_title }}">
										</div>
										<div class="form-group">
											<label for="exampleTextarea">Notes</label>
											<textarea class="form-control"  id="staff_notes" name="staff_notes" placeholder="Add private notes viewable in staff settings only (optional)" id="exampleTextarea" rows="6">{{ $staff_notes }}</textarea>
										</div>
										<div class="d-flex justify-content-between">
											<div class="form-group mr-2 w-100">
												<label for="kt_datepicker_1">Employement Start Date</label>
												<input type="text" class="form-control kt_date employement_date" id="kt_datepicker_1" name="employment_start_date" readonly="readonly" placeholder="Select Start date" value="{{ $employment_start_date }}">
											</div>
											<div class="form-group ml-2 w-100">
												<label for="kt_datepicker_2">Employement End Date</label>
												<input type="text" class="form-control kt_date employement_date" id="kt_datepicker_2"
													readonly="readonly" name="employment_end_date" placeholder="Select End date" value="{{ $employment_end_date }}">
											</div>
										</div>
									
									</div>
								</div>
							</div>
							
							<div class="tab-pane fade services_tab" id="services" role="tabpanel" aria-labelledby="kt_tab_pane_2">
								<p>Assign services this staff member can perform.</p>
								<div class="staf-member-services">
									<ul>
										<li class="mb-6">
											<label class="checkbox">
												<input type="checkbox" id="all_services" value="1" class="form-control" {{ (count($services) == count($staffServices) || empty($id)) ? 'checked' : "" }}>
												<span class="mr-4"></span>Select All
											</label>
										</li>
										<br/>
										@if($id > 0)
											@foreach($services as $val)
												<li class="col-md-3">
													<label class="checkbox">
														<input type="checkbox" name="service_id[]" value="{{ $val->id }}" class="form-control services" data-count="{{ count($services) }}" @if(in_array($val->id, $staffServices)) checked="checked" @endif >
														<span class="mr-4"></span>{{ $val->service_name }}
													</label>
												</li>
											@endforeach	
										@else	
											@foreach($services as $val)
												<li class="col-md-3">
													<label class="checkbox">
														<input type="checkbox" name="service_id[]" value="{{ $val->id }}" checked="checked" class="form-control services" data-count="{{ count($services) }}">
														<span class="mr-4"></span>{{ $val->service_name }}
													</label>
												</li>
											@endforeach	
										@endif	
									</ul>
								</div>
							</div>
							
							<div class="tab-pane fade" id="locations" role="tabpanel" aria-labelledby="kt_tab_pane_2">
								<p>Choose the locations where this staff member works.</p>
								<div class="staf-member-services">
									<ul>
										<li class="mb-6">
											<label class="checkbox">
												<input type="checkbox" id="all_locations" value="1" class="form-control"  {{ (count($locations) == count($staffLocations) || empty($id)) ? 'checked' : "" }}>
												<span class="mr-4"></span>Select All
											</label>
										</li>
										<br/>
										
										@if($id > 0)
											@foreach($locations as $val)
												<li class="col-md-3">
													<label class="checkbox">
														<input type="checkbox" name="location_id[]" value="{{ $val->id }}" class="form-control locations" data-count="{{ count($locations) }}" @if(in_array($val->id, $staffLocations)) checked="checked" @endif >
														<span class="mr-4"></span>{{ $val->location_name }}
													</label>
												</li>
											@endforeach
										@else	
											@foreach($locations as $val)
												<li class="col-md-3">
													<label class="checkbox">
														<input type="checkbox" name="location_id[]" value="{{ $val->id }}" checked class="form-control locations" data-count="{{ count($locations) }}">
														<span class="mr-4"></span>{{ $val->location_name }}
													</label>
												</li>
											@endforeach
										@endif	
									</ul>
								</div>
							</div>
							
							<div class="tab-pane fade" id="commission" role="tabpanel" aria-labelledby="kt_tab_pane_2">
								<div class="col-12 col-md-12">
									<div class="col-12 col-md-6">
										<div class="d-flex justify-content-between">
											<div class="form-group w-100">
												<label>Service Commission</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text rounded-0">%</span>
													</div>
													<input type="text" id="service_commission" name="service_commission" class="form-control rounded-0" placeholder="0.0" value="{{ $service_commission }}">
												</div>
											</div>
											<div class="form-group w-100 mx-4">
												<label>Product Commission</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text rounded-0">%</span>
													</div>
													<input type="text" id="product_commission" name="product_commission" class="form-control rounded-0" placeholder="0.0" value="{{ $product_commission }}">
												</div>
											</div>
										</div>
									</div>
									<div class="col-12 col-md-6">
										<div class="d-flex justify-content-between">
											<div class="form-group w-100">
												<label>Voucher Commission</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text rounded-0">%</span>
													</div>
													<input type="text" id="voucher_commission" name="voucher_commission" class="form-control rounded-0" placeholder="0.0" value="{{ $voucher_commission }}">
												</div>
											</div>
											<div class="form-group w-100 mx-4">
												<label>Paid Plan Commission</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text rounded-0">%</span>
													</div>
													<input type="text" id="paid_plan_commision" name="paid_plan_commision" class="form-control rounded-0" placeholder="0.0" value="{{ $paid_plan_commision }}">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="text-right mb-8 d-flex justify-content-between col-xs-6">
							@if(!empty($staff_user_id))
								<button type="button" class="btn btn-danger font-weight-bold mx-2" data-toggle="modal" data-target="#deleteStaffModal">Delete</button>
								<button type="button" class="btn btn-primary font-weight-bold" id="sendMail" data-url="{{ route('sendMail',['id'=>$staff_user_id]) }}">Send reset password email</button>
							@endif
						</div>
						<div class="col-6 text-right float-right">
							<button type="button" class="btn btn-outline-secondary font-weight-bold" onclick="window.location.href='{{ route('staff_members') }}'">Cancel</button>
							<button type="submit" class="btn btn-primary font-weight-bold" id="addStaffSubmitButton">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

@endsection
{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('js/addStaff.js') }}"></script>
<script src="{{ asset('js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js') }}"></script>
<script type="text/javascript">
	var WEBSITE_URL = "{{ url('partners/') }}";
	var phone_number = window.intlTelInput(document.querySelector("#mobileno"), {
	  	separateDialCode: true,
	  	preferredCountries:["ca"],
	  	hiddenInput: "full",
	  	utilsScript: "{{ asset('js/utils.js') }}"
	});
	
	var ph = $("#tel_country_code").val()+$("#mobileno").val();
	phone_number.setNumber("+"+ph+"");
	
	$(document).on("click keypress", '.telephoneclass .iti__country' , function(e) {
		e.preventDefault();
		if($(this).hasClass("iti__active")){
			var country_code = $(this).attr("data-dial-code");
			$("#tel_country_code").val(country_code);
		} else {
			var country_code = $(this).attr("data-dial-code");
			$("#tel_country_code").val(country_code);
		}
	});
	$(document).ready(function(){
		$("#is_appointment_booked").change( function(){
			if(this.checked) {	
				$("#appointment-colors").show();
				$(".service_tab_opt").show();
			} else {
				$("#appointment-colors").hide();
				$(".service_tab_opt").hide();
			}
		});	
		
		$("#kt_datepicker_1").datepicker({
			format: 'D, dd M yyyy',
			todayBtn:  1,
			autoclose: true,
		}).on('changeDate', function (selected) {
			var minDate = new Date(selected.date.valueOf());
			$('#kt_datepicker_2').datepicker('setStartDate', minDate);
		});
		
		$("#kt_datepicker_2").datepicker({
			format: 'D, dd M yyyy',
			todayBtn:  1,
			autoclose: true,
		}).on('changeDate', function (selected) {
			var minDate = new Date(selected.date.valueOf());
			$('#kt_datepicker_1').datepicker('setEndDate', minDate);
		});
		
		$('#kt_datepicker_2').datepicker('setStartDate', new Date());
		
       	/* $('.iti__country').each(function(){
    		if($(this).attr("data-dial-code") == $("#tel_country_code").val())
    		{
    			var countryCode = $(this).attr('data-country-code');
    			if($('#tel_country_code').val() == 1)
    			{
    				countryCode = "ca"
    			}
    			console.log(countryCode);
    			$('.telephoneclass').find('.iti__selected-flag').children('.iti__flag').addClass('iti__'+countryCode);
    		}
    	}); */


		$(document).on('keypress', '.allow_only_numbers', function(evt){
			evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;

		    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    return true;
		});

		$(document).on('paste', '.allow_only_numbers', function (event) {
		  	if (event.originalEvent.clipboardData.getData('Text').match(/[^\d]/)) {
		    	event.preventDefault();
		  	}
		});
	});

	$('#all_services').on('change',function(){
		if($(this).prop('checked') == true)
		{
			$('.services').prop('checked',true);
		}
		else
		{
			$('.services').prop('checked',false);
		}
	});
	
	$('#all_locations').on('change',function(){
		if($(this).prop('checked') == true) {
			$('.locations').prop('checked',true);
		} else {
			$('.locations').prop('checked',false);
		}
	});
	
	$(".rounded-0").on("keypress keyup blur",function (event) {
	     $(this).val($(this).val().replace(/[^0-9\.]/g,''));
        if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
            event.preventDefault();
        }
    });

	//SEND MAIL
    $(document).on("click", '#sendMail', function (e) {
		var url = $(this).data('url');

		$.ajax({
			type: "get",
			url: url,
			dataType: 'json',
			success: function (data) {
				KTApp.unblockPage();
				console.log(data);
				if(data.status == true)
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
					toastr.success(data.message);
					window.setTimeout(function() {
						window.location = WEBSITE_URL+"/staff_members";
					}, 2000);
				} else {
					//table.ajax.reload();
					
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
					toastr.error(data.message);
					window.setTimeout(function() {
						window.location.reload();
					}, 2000);
				}	
			}
		});
	});
    $(document).on("click", '#deleteStaff', function (e) {
			
		KTApp.blockPage();
		var form = $(this).parents('form:first');

		$.ajax({
			type: "POST",
			url: "{{ route('deleteStaffMember') }}",
			dataType: 'json',
			data: form.serialize(),
			success: function (data) {
				KTApp.unblockPage();
				
				if(data.status == true)
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
					toastr.success(data.message);
					window.setTimeout(function() {
						window.location = WEBSITE_URL+"/staff_members";
					}, 2000);
				} else {
					table.ajax.reload();
					
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
					toastr.error(data.message);
					window.setTimeout(function() {
						window.location.reload();
					}, 2000);
				}	
			}
		});
	});
	$('.services').on('change',function(){
		var totalCheckedBoxes=$('.staf-member-services').find('input[name="service_id[]"]:checked').length;
		var totalLocation = $(this).data('count');
		if(totalCheckedBoxes == totalLocation)
		{
			$('#all_services').prop('checked',true);
		}
		else
		{
			$('#all_services').prop('checked',false);
		}
		// alert(total);
	});

	$('.locations').on('change',function(){
		var totalCheckedBoxes=$('.staf-member-services').find('input[name="location_id[]"]:checked').length;
		var totalLocation = $(this).data('count');
		if(totalCheckedBoxes == totalLocation)
		{
			$('#all_locations').prop('checked',true);
		}
		else
		{
			$('#all_locations').prop('checked',false);
		}
		// alert(total);
	});
</script>
@endsection