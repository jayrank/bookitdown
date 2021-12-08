{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')
@php
$id = "";
$appointment_cancel_time = "";
$before_start_time = "";
$future_time = "";
$time_slot_interval = "";
$is_allowed_staff_selection = "";
$important_info = "";
$is_specific_email = "";
$email = "";
$is_send_booked_staff = "";
$is_show_feature = "";
$is_rating = "";
@endphp
@if(!empty($online_booking))
	@php
		$id = $online_booking->id;
		$appointment_cancel_time = $online_booking->appointment_cancel_time;
		$before_start_time = $online_booking->before_start_time;
		$future_time = $online_booking->future_time;
		$time_slot_interval = $online_booking->time_slot_interval;
		$is_allowed_staff_selection = $online_booking->is_allowed_staff_selection;
		$important_info = $online_booking->important_info;
		$is_specific_email = $online_booking->is_specific_email;
		$email = $online_booking->email;
		$is_send_booked_staff = $online_booking->is_send_booked_staff;
		$is_show_feature = $online_booking->is_show_feature;
		$is_rating = $online_booking->is_rating;
	@endphp
@endif
<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
	@include('layouts.onlineBookingNav')
	<div class="d-flex flex-column-fluid">
		<div class="container">
			{{ Form::open(array('route' => 'saveOnlineSetting','id' => 'saveOnlineSetting')) }}
				<input type="hidden" name="id" value="{{$id}}">
				<div class="row my-8">
					<div class="col-12 col-sm-12 col-md-5 my-4">
						<h3 class="font-weight-bolder">Online Cancellation and Rescheduling</h3>
						<h5 class="my-4 font-weight-bold text-dark-50">
							Set how far in advance clients can cancel or reschedule, after this timeframe
							clients must call to change their
							appointment.
						</h5>
					</div>
					<div class="col-12 col-sm-12 col-md-7 my-4">
						<div class="card shadow-sm">
							<div class="card-body p-6">
								<div class="form-group">
									<label class="font-weight-bolder">
										Clients can cancel or reschedule online
									</label>
									<select class="form-control" name="appointment_cancel_time">
										<option value="0" {{ ($appointment_cancel_time == 0) ? 'selected' : "" }}>Anytime</option>
										<option value="30" {{ ($appointment_cancel_time == 30) ? 'selected' : "" }}>Up to 30 minutes in advance</option>
										<option value="60" {{ ($appointment_cancel_time == 60) ? 'selected' : "" }}>Up to 1 hour in advance</option>
										<option value="120" {{ ($appointment_cancel_time == 120) ? 'selected' : "" }}>Up to 2 hours in advance</option>
										<option value="180" {{ ($appointment_cancel_time == 180) ? 'selected' : "" }}>Up to 3 hours in advance</option>
										<option value="240" {{ ($appointment_cancel_time == 240) ? 'selected' : "" }}>Up to 4 hours in advance</option>
										<option value="300" {{ ($appointment_cancel_time == 300) ? 'selected' : "" }}>Up to 5 hours in advance</option>
										<option value="360" {{ ($appointment_cancel_time == 360) ? 'selected' : "" }}>Up to 6 hours in advance</option>
										<option value="720" {{ ($appointment_cancel_time == 720) ? 'selected' : "" }}>Up to 12 hours in advance</option>
										<option value="1440" {{ ($appointment_cancel_time == 1440) ? 'selected' : "" }}>Up to 24 hours in advance</option>
										<option value="2880" {{ ($appointment_cancel_time == 2880) ? 'selected' : "" }}>Up to 48 hours in advance</option>
									</select>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-sm-12 col-md-5 my-4">
						<h3 class="font-weight-bolder">Online Booking Availability</h3>
						<h5 class="my-4 font-weight-bold text-dark-50">
							Set how far in advance clients can book online, and lead time for when clients
							can cancel or reschedule.
						</h5>
					</div>
					<div class="col-12 col-sm-12 col-md-7 my-4">
						<div class="card shadow-sm">
							<div class="card-body p-6">
								<div class="form-group">
									<label class="font-weight-bolder">
										Clients can book appointments
									</label>
									<select class="form-control" name="before_start_time">
										<option value="0" {{ ($before_start_time == 0) ? 'selected' : "" }}>Immediately before start time</option>
										<option value="900" {{ ($before_start_time == 900) ? 'selected' : "" }}>Up to 15 minutes before start time</option>
										<option value="1800" {{ ($before_start_time == 1800) ? 'selected' : "" }}>Up to 30 minutes before start time</option>
										<option value="3600" {{ ($before_start_time == 3600) ? 'selected' : "" }}>Up to 1 hour before start time</option>
										<option value="7200" {{ ($before_start_time == 7200) ? 'selected' : "" }}>Up to 2 hours before start time</option>
										<option value="10800" {{ ($before_start_time == 10800) ? 'selected' : "" }}>Up to 3 hours before start time</option>
										<option value="14400" {{ ($before_start_time == 14400) ? 'selected' : "" }}>Up to 4 hours before start time</option>
										<option value="18000" {{ ($before_start_time == 18000) ? 'selected' : "" }}>Up to 5 hours before start time</option>
										<option value="21600" {{ ($before_start_time == 21600) ? 'selected' : "" }}>Up to 6 hours before start time</option>
										<option value="25200" {{ ($before_start_time == 25200) ? 'selected' : "" }}>Up to 7 hours before start time</option>
										<option value="28800" {{ ($before_start_time == 28800) ? 'selected' : "" }}>Up to 8 hours before start time</option>
										<option value="32400" {{ ($before_start_time == 32400) ? 'selected' : "" }}>Up to 9 hours before start time</option>
										<option value="36000" {{ ($before_start_time == 36000) ? 'selected' : "" }}>Up to 10 hours before start time</option>
										<option value="39600" {{ ($before_start_time == 39600) ? 'selected' : "" }}>Up to 11 hours before start time</option>
										<option value="43200" {{ ($before_start_time == 43200) ? 'selected' : "" }}>Up to 12 hours before start time</option>
										<option value="46800" {{ ($before_start_time == 46800) ? 'selected' : "" }}>Up to 13 hours before start time</option>
										<option value="50400" {{ ($before_start_time == 50400) ? 'selected' : "" }}>Up to 14 hours before start time</option>
										<option value="54000" {{ ($before_start_time == 54000) ? 'selected' : "" }}>Up to 15 hours before start time</option>
										<option value="57600" {{ ($before_start_time == 57600) ? 'selected' : "" }}>Up to 16 hours before start time</option>
										<option value="61200" {{ ($before_start_time == 61200) ? 'selected' : "" }}>Up to 17 hours before start time</option>
										<option value="64800" {{ ($before_start_time == 64800) ? 'selected' : "" }}>Up to 18 hours before start time</option>
										<option value="68400" {{ ($before_start_time == 68400) ? 'selected' : "" }}>Up to 19 hours before start time</option>
										<option value="72000" {{ ($before_start_time == 72000) ? 'selected' : "" }}>Up to 20 hours before start time</option>
										<option value="75600" {{ ($before_start_time == 75600) ? 'selected' : "" }}>Up to 21 hours before start time</option>
										<option value="79200" {{ ($before_start_time == 79200) ? 'selected' : "" }}>Up to 22 hours before start time</option>
										<option value="82800" {{ ($before_start_time == 82800) ? 'selected' : "" }}>Up to 23 hours before start time</option>
										<option value="86400" {{ ($before_start_time == 86400) ? 'selected' : "" }}>Up to 24 hours before start time</option>
									</select>
								</div>
								<div class="form-group">
									<select class="form-control" name="future_time">
										<option value="6" {{ ($future_time == 6) ? 'selected' : "" }}>No more than 6 months in the future</option>
										<option value="5" {{ ($future_time == 5) ? 'selected' : "" }}>No more than 5 months in the future</option>
										<option value="4" {{ ($future_time == 4) ? 'selected' : "" }}>No more than 4 months in the future</option>
										<option value="3" {{ ($future_time == 3) ? 'selected' : "" }}>No more than 3 months in the future</option>
										<option value="2" {{ ($future_time == 2) ? 'selected' : "" }}>No more than 2 months in the future</option>
										<option value="1" {{ ($future_time == 1) ? 'selected' : "" }}>No more than 1 month in the future</option>
									</select>
									<span class="text-muted my-1">Controls the time slots available for online booking</span>
								</div>
								<div class="form-group">
									<label class="font-weight-bolder">
										Time slot interval
									</label>
									<select class="form-control" name="time_slot_interval">
										<option value="15" {{ ($time_slot_interval == 15) ? 'selected' : "" }}>15 minutes (max availability)</option>
										<option value="20" {{ ($time_slot_interval == 20) ? 'selected' : "" }}>20 minutes</option>
										<option value="30" {{ ($time_slot_interval == 30) ? 'selected' : "" }}>30 minutes</option>
										<option value="60" {{ ($time_slot_interval == 60) ? 'selected' : "" }}>1 hour (low availability)</option>
									</select>
									<span class="text-muted my-1">
										Controls the time slots available for online booking
									</span>
								</div>
								<div class="form-group">
									<div class="checkbox-list mt-8">
										<label class="checkbox font-weight-bolder">
											<input type="checkbox" value="1" name="is_allowed_staff_selection" {{ ($is_allowed_staff_selection == 1) ? 'checked' : "" }}>
											<span></span> Allow clients to select staff members
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-sm-12 col-md-5 my-4">
						<h3 class="font-weight-bolder">Important info</h3>
						<h5 class="my-4 font-weight-bold text-dark-50">
							Add important info you’d like clients to see at checkout when booking an
							appointment or buying a voucher or paid plan.
						</h5>
					</div>
					<div class="col-12 col-sm-12 col-md-7 my-4">
						<div class="card shadow-sm">
							<div class="card-body p-6">
								<div class="form-group">
									<label class="font-weight-bolder">
										Important info
									</label>
									<textarea rows="6" class="form-control" name='important_info'>{{ $important_info }}</textarea>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-sm-12 col-md-5 my-4">
						<h3 class="font-weight-bolder">Online Booking Activity Emails</h3>
						<h5 class="my-4 font-weight-bold text-dark-50">
							Receive emails when clients use online booking to book, reschedule or cancel. These emails are sent in addition to regular staff notifications.
						</h5>
					</div>
					<div class="col-12 col-sm-12 col-md-7 my-4">
						<div class="card shadow-sm">
							<div class="card-body p-6">
								<div class="form-group">
									<div class="checkbox-list mt-8">
										<label class="checkbox font-weight-bolder">
											<input type="checkbox" value="1" name='is_send_booked_staff' {{ ($is_send_booked_staff == 1) ? 'checked' : "" }}>
											<span></span> Send to staff members booked
										</label>
									</div>
								</div>
								<div class="form-group">
									<div class="checkbox-list mt-8">
										<label class="checkbox font-weight-bolder">
											<input onclick="isSpecificEmail()" id="is_specific_email" type="checkbox" value="1" name="is_specific_email" {{ ($is_specific_email == 1) ? 'checked' : "" }} >
											<span></span> Send to specific email addresses
										</label>
									</div>
								</div>
								<div class="form-group isSpecificEmail" style="display: {{ ($is_specific_email == 1) ? 'block' : "none" }}">
									<label class="font-weight-bolder">
										Specific email addresses
									</label>
									<input type="email" class="form-control" name="email" value="{{ $email }}"/>
									<span>Enter multiple addresses by separating with commas</span>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-sm-12 col-md-5 my-4">
						<h3 class="font-weight-bolder">Featured services</h3>
						<h5 class="my-4 font-weight-bold text-dark-50">
							Display your top 6 most popular and discounted services at the top of your online booking menu.
						</h5>
					</div>
					<div class="col-12 col-sm-12 col-md-7 my-4">
						<div class="card shadow-sm">
							<div class="card-body p-6">
								<div class="form-group">
									<div class="checkbox-list mt-8">
										<label class="checkbox font-weight-bolder">
											<input type="checkbox" value="1" name="is_show_feature" {{ ($is_show_feature == 1) ? 'checked' : "" }}>
											<span></span> Show Featured services when clients click on a Book now direct link
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="col-12 col-sm-12 col-md-5 my-4">
						<h3 class="font-weight-bolder">Star ratings for staff</h3>
						<h5 class="my-4 font-weight-bold text-dark-50">
							Show how great your team are by displaying average star ratings next to their names, it's proven to attract more clients online.
						</h5>
					</div>
					<div class="col-12 col-sm-12 col-md-7 my-4">
						<div class="card shadow-sm">
							<div class="card-body p-6">
								<div class="form-group">
									<div class="checkbox-list mt-8">
										<label class="checkbox font-weight-bolder">
											<input type="checkbox" value="1" name="is_rating" {{ ($is_rating == 1) ? 'checked' : "" }}>
											<span></span> Display star ratings for staff
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12">
						<button type='submit' class="px-8 btn btn-primary">Save</button>
					</div>
				</div>
			{{ Form::close() }}
			<!--end::Row-->
			<!--end::Sales-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
@endsection
{{-- Scripts Section --}}
@section('scripts')
<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
	<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<!--end::Global Theme Bundle-->
<!--begin::Page Vendors(used by this page)-->
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
<!--end::Page Scripts-->
<script type="text/javascript">

	function openNav() {
		document.getElementById("myFilter").style.width = "300px";
	}

	function closeNav() {
		document.getElementById("myFilter").style.width = "0%";
	}
</script>
<script>
	// Class definition
	var KTBootstrapDatepicker = function () {

		var arrows;
		if (KTUtil.isRTL()) {
			arrows = {
				leftArrow: '<i class="la la-angle-right"></i>',
				rightArrow: '<i class="la la-angle-left"></i>'
			}
		} else {
			arrows = {
				leftArrow: '<i class="la la-angle-left"></i>',
				rightArrow: '<i class="la la-angle-right"></i>'
			}
		}

		// Private functions
		var demos = function () {
			// minimum setup
			$('#kt_datepicker_1').datepicker({
				rtl: KTUtil.isRTL(),
				todayHighlight: true,
				autoclose: true,
				format: 'DD, dd/mm/yyyy',
				orientation: "bottom left",
				templates: arrows
			});
		}

		return {
			init: function () {
				demos();
			}
		};
	}();

	jQuery(document).ready(function () {
		KTBootstrapDatepicker.init();
	});
</script>
<script>
	function isSpecificEmail() {
		if ($('#is_specific_email').prop('checked') == true) {
			$(".isSpecificEmail").show()
		} else {
			$(".isSpecificEmail").hide()
		}
	}
</script>
@endsection