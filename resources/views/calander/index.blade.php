{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<link href="{{ asset('js/new-full-calander/fullcalendar.css') }}" rel="stylesheet" type="text/css" />

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">

<style>
.block-time-message, .reschedule-message {
	text-align: center;
    background-color: #037aff;
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    height: 70px;
    z-index: 1000;
    font-size: 22px;
    padding: 1% 0;
}

@media screen and (max-width: 600px) {
	.fc-view { 
	  overflow-x: scroll; 
	}
	.fc-view.fc-agenda table{
	  width: 500% !important;
	}
	/* **For 2 day view** */
	/*.fc-view.fc-agendaTwoDay-view.fc-agenda-view{
	  width: 500%;
	}*/ 

	.fc-border-separate tr.fc-last th, .fc-border-separate tr.fc-last td {
		width: auto !important;
	}
	.fc-agenda-axis.fc-widget-header.fc-first {
		width: 50px !important;
	}
	.fc-agenda-days.fc-border-separate + div > div {
		width: 500% !important;
	}
}
</style>
@endsection

@section('content')


<!--begin::Header-->
	
	<!--end::Header-->
	<!--begin::Content-->
				<input type="hidden" class="reschedule_appointment_id" value="{{ $rescheduleAppointmentId }}">
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="background: #FFF;">
					<!--begin::Entry-->
					<div class="d-flex flex-column-fluid">
						<!--begin::Container-->
						<div class="container">
							<!--begin::Sales-->
							<!--begin::Row-->
							<div class="content-header ">
								<div class="display-service justify-content-between">
									<div class="form-group">
										<select class="form-control" name="staffFilter" id="staffFilter">
											@if(!empty($staffResource))
												@if (Auth::user()->can('access_other_staff_calendars'))
													<option value="all-staff" selected>All Staff</option>
												@endif
												
												@foreach($staffResource as $staffResourceData)
													<option value="{{ $staffResourceData['id'] }}">{{ $staffResourceData['name'] }}</option>
												@endforeach
											@else
											<option value="">No staff member found.</option>
											@endif	
										</select>
									</div>
									<div>
										<div class="input-group date">
											<div class="form-group mb-4 full-width">
												<div class='input-icon'>
													<input type='text' class="form-control" id="calander_current_date" readonly placeholder="Select date" />
													<span class=""><i class="la la-calendar-check-o"></i></span>
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<select class="form-control" id="calanderViewFilter">
											<option value="day">Day</option>
											<option value="3days">3 Days</option>
											<option value="week">week</option>
										</select>
									</div>
									<div class="dropdown dropdown-inline ml-2">
										<button type="button" class="btn btn-primary font-weight-bolder dropdown-toggle"
											data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
											Add New</button>
										<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
											<ul class="navi flex-column navi-hover py-2">
												@if (Auth::user()->can('can_book_appointments'))
													<li class="navi-item">
														<a href="{{ route('createAppointment', ['locationId' => $locationId->id]) }}" class="navi-link">
															<span class="navi-text">New Appointment</span>
														</a>
													</li>
													<li class="navi-item">
														<a href="#" class="navi-link block-time-link">
															<span class="navi-text">New Blocked Time</span>
														</a>
													</li>
												@else 
													<li class="navi-item">
														<a href="javascript:;" class="no_access navi-link">
															<span class="navi-text">New Appointment</span>
														</a>
													</li>
													<li class="navi-item">
														<a href="javascript:;" class="no_access navi-link block-time-link">
															<span class="navi-text">New Blocked Time</span>
														</a>
													</li>		
												@endif	
												
												@if (Auth::user()->can('can_check_out_sales'))	
													<li class="navi-item">
														<a href="{{ route('checkoutAppointment', ['locationId' => $locationId->id]) }}" class="navi-link">
															<span class="navi-text">New Sale</span>
														</a>
													</li>
												@else 
													<li class="navi-item">
														<a href="javascript:;" class="no_access navi-link">
															<span class="navi-text">New Sale</span>
														</a>
													</li>		
												@endif	
											</ul>
										</div>
									</div>
								</div>
								<div class="row my-4">
									<div class="card w-100">
										<div class="card-body">
											@csrf
											<input type="hidden" id="updateAppointmentService" value="{{ route('updateAppointmentService') }}">
											<input type="hidden" id="getStaffAppointment" value="{{ route('getStaffAppointment') }}">
										
											<div id="kt_calendar"></div>
										</div>
									</div>
								</div>
							</div>
							<!--end::Row-->

							<!--end::Sales-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Entry-->	
				</div>
				
				
				<div class="modal fade p-0" id="updateAppointmentConfirmation" tabindex="-1" role="dialog" aria-labelledby="updateAppointmentConfirmationLabel Label" aria-modal="true">
					<div class="modal-dialog " role="document">
						<div class="modal-content ">
							<div class="modal-header">
								<h5 class="modal-title font-weight-bold text-center" id="decreaseStockModalLabel">Update Appointment</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
								</button>
							</div>
							<form method="POST" enctype="multipart/form-data" action="{{ route('updateAppointmentConfirmation') }}" id="updateAppointmentConfirmationForm">
								@csrf
								<input type="hidden" name="change_appointment_service_id" id="change_appointment_service_id"> 
								<input type="hidden" name="change_staff_user_id" id="change_staff_user_id"> 
								<input type="hidden" name="change_start_date_time" id="change_start_date_time"> 
								<input type="hidden" name="change_end_date_time" id="change_end_date_time"> 
								
								<div class="modal-body">
									<div class="form-group w-100">
										<input type="checkbox" name="notify_customer" id="notify_customer" value="1">
										<label for="notify_customer">Notify customer about reschedule</label>
									</div>
									<div class="form-group w-100">
										<p>Send a message informing customer that their appointment was rescheduled</p>
									</div>
								</div>
								<div class="modal-footer d-flex justify-content-between">
									<div class="ml-auto">
										<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
										<button type="submit" class="btn btn-primary font-weight-bold" id="updateAppointmentConfirmationBtn">Save</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>


				
				<div class="modal fade p-0" id="blockedTimeModal" tabindex="-1" role="dialog" aria-labelledby="blockedTimeModalLabel Label" aria-modal="true">
					<div class="modal-dialog " role="document">
						<div class="modal-content ">
							<div class="modal-header">
								<h5 class="modal-title font-weight-bold text-center" id="blockedTimeModalTitle">New Blocked Time</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
								</button>
							</div>
							<form method="POST" enctype="multipart/form-data" action="javascript:void(0)" id="blockedTimeModalForm">
								@csrf
								
								<div class="modal-body">
									<div class="form-group w-100">
										<strong>Date</strong>
										<input type="date" name="date" class="form-control blocked-time-date">
									</div>
									<div class="form-group w-100">
										<strong>Staff</strong>
										<select name="staff_user_id" class="form-control blocked-time-staff-user-id">
											<option value="">Select</option>

											@foreach($staffResource as $staffResourceData)
												<option value="{{ $staffResourceData['id'] }}">{{ $staffResourceData['name'] }}</option>
											@endforeach
										</select>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group w-100">
												<strong>Start Time</strong>
												<select name="start_time" class="form-control blocked-time-start-time">
													@if(!empty($TimeSlots))
														<option value="">Please select</option>		
														@foreach($TimeSlots as $key => $TimeSlot)
															<option value="{{ $TimeSlot['timevalue'] }}">{{ $TimeSlot['time'] }}</option>	
														@endforeach
													@else
														<option>No slots found.</option>	
													@endif
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group w-100">
												<strong>End Time</strong>
												<select name="end_time" class="form-control blocked-time-end-time">
													@if(!empty($TimeSlots))
														<option value="">Please select</option>		
														@foreach($TimeSlots as $key => $TimeSlot)
															<option value="{{ $TimeSlot['timevalue'] }}">{{ $TimeSlot['time'] }}</option>	
														@endforeach
													@else
														<option>No slots found.</option>	
													@endif
												</select>
											</div>
										</div>
									</div>
									<div class="form-group w-100">
									
									<label>
										<input type="checkbox" name="allow_online_booking" value="1" class="blocked-time-allow-online-booking">
										Allow online bookings during blocked time
									</label>
									</div>
									<div class="form-group w-100">
										<strong>Description</strong>
										<textarea name="description" class="form-control blocked-description" placeholder="e.g. lunch meeting"></textarea>
									</div>
								</div>
								<div class="modal-footer d-flex justify-content-between">
									<div class="ml-auto">
										<button type="button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
										<button type="submit" class="btn btn-primary font-weight-bold" id="blockedTimeModalBtn">Save</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>
				
				<div class="modal fade p-0" id="editBlockedTimeModal" tabindex="-1" role="dialog" aria-labelledby="editBlockedTimeModalLabel Label" aria-modal="true">
					<div class="modal-dialog " role="document">
						<div class="modal-content ">
							<div class="modal-header">
								<h5 class="modal-title font-weight-bold text-center" id="editBlockedTimeModalTitle">New Blocked Time</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
								</button>
							</div>
							<form method="POST" enctype="multipart/form-data" action="javascript:void(0)" id="editBlockedTimeModalForm">
								@csrf
								
								<input type="hidden" name="staff_blocked_time_id" class="edit-blocked-time-id" value="">
								<div class="modal-body">
									<div class="form-group w-100">
										<strong>Date</strong>
										<input type="date" name="date" class="form-control edit-blocked-time-date">
									</div>
									<div class="form-group w-100">
										<strong>Staff</strong>
										<select name="staff_user_id" class="form-control edit-blocked-time-staff-user-id">
											<option value="">Select</option>

											@foreach($staffResource as $staffResourceData)
												<option value="{{ $staffResourceData['id'] }}">{{ $staffResourceData['name'] }}</option>
											@endforeach
										</select>
									</div>
									<div class="row">
										<div class="col-md-6">
											<div class="form-group w-100">
												<strong>Start Time</strong>
												<select name="start_time" class="form-control edit-blocked-time-start-time">
													@if(!empty($TimeSlots))
														<option value="">Please select</option>		
														@foreach($TimeSlots as $key => $TimeSlot)
															<option value="{{ $TimeSlot['timevalue'] }}">{{ $TimeSlot['time'] }}</option>	
														@endforeach
													@else
														<option>No slots found.</option>	
													@endif
												</select>
											</div>
										</div>
										<div class="col-md-6">
											<div class="form-group w-100">
												<strong>End Time</strong>
												<select name="end_time" class="form-control edit-blocked-time-end-time">
													@if(!empty($TimeSlots))
														<option value="">Please select</option>		
														@foreach($TimeSlots as $key => $TimeSlot)
															<option value="{{ $TimeSlot['timevalue'] }}">{{ $TimeSlot['time'] }}</option>	
														@endforeach
													@else
														<option>No slots found.</option>	
													@endif
												</select>
											</div>
										</div>
									</div>
									<div class="form-group w-100">
										<label>
											<input type="checkbox" name="allow_online_booking" value="1" class="edit-blocked-time-allow-online-booking">
											Allow online bookings during blocked time
										</label>
									</div>
									<div class="form-group w-100">
										<strong>Description</strong>
										<textarea name="description" class="form-control edit-blocked-time-description" placeholder="e.g. lunch meeting"></textarea>
									</div>
								</div>
								<div class="modal-footer d-flex justify-content-between" id="delBlock">
									<button type="button" class="btn btn-outline-danger font-weight-bold" data-dismiss="modal">Delete</button>
									<div class="ml-auto">
										<button type="button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
										<button type="submit" class="btn btn-primary font-weight-bold" id="editBlockedTimeModalBtn">Save</button>
									</div>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div class="block-time-message" style="display: none;">
					Select a time to block
					<span class="float-right block-time-message-close" style="font-size: 1.2em; margin: 0 2%; cursor: pointer;">&times;</span>
				</div>

				<div class="reschedule-message" style="display: none;">
					Select a time to book
					<span class="float-right reschedule-message-close" style="font-size: 1.2em; margin: 0 2%; cursor: pointer;">&times;</span>
				</div>
@endsection

{{-- Scripts Section --}}
@section('scripts') 
	<!--end::Global Theme Bundle-->
	<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
	<script src="{{ asset('js/application.js') }}"></script>
	
	<script src="{{ asset('js/new-full-calander/jquery-ui.min.js') }}"></script>
	<script src="{{ asset('js/new-full-calander/fullcalendar.js') }}"></script>
	<script>
		$(document).ready(function() {

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

			window.selectBlockedTime = false;
			window.selectReschedule = false;

			var date = new Date();
			var d = date.getDate();
			var m = date.getMonth();
			var y = date.getFullYear();

			calendar = $('#kt_calendar').fullCalendar({
				header: {
					left: 'prev,next today',
					center: 'title',
					right: ''
				},
				allDaySlot: false,
				slotDuration: '00:15:00',
				slotLabelInterval: 15,
				slotLabelFormat: 'h(:mm)a',
				defaultView: 'resourceDay',
				editable: true,
				droppable: true,
				resources: <?php echo $jsonStaff ?>,
				// events: <?php // echo $jsonAppointment ?>,
				events: '{{ route("fetchCalendarEvents") }}',
				selectable: true,
				selectHelper: true,
				select: function(start, end, ev) {
					/*console.log(start._i);
					console.log(end);
					console.log(ev.data); // resources

					var allDay = !start.hasTime() && !end.hasTime();
					         console.log(["Event Start date: " + moment(start).format(),
					                "Event End date: " + moment(end).format(),
					                "AllDay: " + allDay].join("\n"));*/

					if(window.selectBlockedTime) {
						$('#blockedTimeModalTitle').text('New Blocked Time');
						$('#blockedTimeModalForm')[0].reset();

						$('.blocked-time-staff-user-id').val(ev.data.id);
						$('.blocked-time-start-time').val(moment(start).format('HH:mm:ss'));
						$('.blocked-time-end-time').val(moment(end).format('HH:mm:ss'));
						$('.blocked-time-date').val(moment(end).format('YYYY-MM-DD'));
						$('#blockedTimeModal').modal('show');
					} else if(window.selectReschedule) {
						$.ajaxSetup({
							headers: {
								'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
							},
						});

						var self = $(this);

						$.ajax({
							type:'POST',
				           	url:"{{ route('rescheduleAppointment') }}",
				           	data: {
				           		appointment_id: $('.reschedule_appointment_id').val(),
				           		start: moment(start).format('HH:mm:ss'),
				           		date: moment(start).format('YYYY-MM-DD'),
				           		staff_user_id: ev.data.id
				           	},
							success: function(response) {
								if(response.status) {
									calendar.fullCalendar('refetchEvents');
									calendar.fullCalendar('unselect');
									closeRescheduleModal();
									toastr.success((response.message) ? response.message : "Appointment rescheduled.");
									window.history.pushState({"html":response.html,"pageTitle":response.pageTitle},"",  location.href.replace(/&?reschedule=([^&]$|[^&]*)/i, ""));
								} else {

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

									toastr.error(response.message);
								}
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
					} else {
						window.location.href = "{{ route('createAppointment', ['locationId' => $locationId->id]) }}?dateTime="+moment(start).format();
					}
				},
				eventClick: function(event) {
					if(event.appointment_id_enc !== undefined) {
						var appointment_id = event.appointment_id_enc;
						window.location.href="{{ url('partners/appointments/view/') }}"+"/"+appointment_id;
					} else {
						populateEditBlockedTimeModal(event);

						$('#editBlockedTimeModal').modal('show');
					}
				},
				eventDrop: function (event, delta, revertFunc) {
					console.log(event);
					
					var staff_user_id = event.resources[0];

					if(event.appointment_id_enc !== undefined) {

						var start_date_time        = $.fullCalendar.moment(event.start,'DD.MM.YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
	                    var end_date_time          = $.fullCalendar.moment(event.end,'DD.MM.YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');

						var url = $("#updateAppointmentService").val();
						var csrf = $("input[name=_token]").val();
						var appointment_service_id = event.appointment_service_id;
						
						var prev_start_time = event.start_time;
						var prev_end_time   = event.end_time;
						
						var g1 = new Date(prev_start_time);
						var g2 = new Date(start_date_time);
						
						console.log("Prev Start Time "+prev_start_time);
						console.log("Start Time "+start_date_time);
						
						if (g2.getTime() < g1.getTime()){
							$("#updateAppointmentConfirmation").modal('show');
							$("#change_appointment_service_id").val(appointment_service_id);
							$("#change_staff_user_id").val(staff_user_id);
							$("#change_start_date_time").val(start_date_time);
							$("#change_end_date_time").val(end_date_time);
							//$('#countrylist').trigger('change');
							return false;
						}
						
						$.ajax({
							type: "POST",
							url: url,
							data: {appointment_service_id : appointment_service_id,staff_user_id : staff_user_id,start_date_time : start_date_time,end_date_time : end_date_time,_token : csrf},
							success: function (response) 
							{
								if(response.status == 'confirm'){
									$("#updateAppointmentConfirmation").modal('show');
									$("#change_appointment_service_id").val(response.appointment_service_id);
									$("#change_staff_user_id").val(response.staff_user_id);
									$("#change_start_date_time").val(response.start_date_time);
									$("#change_end_date_time").val(response.end_date_time);
								} else if(response.status == true){	
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
									toastr.success((response.message) ? response.message : "Something went wrong!");
								} else {
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
									toastr.error((response.message) ? response.message : "Something went wrong!");
								}
							}
						});
					} else {
						event.staff_user_id = staff_user_id;
						populateEditBlockedTimeModal(event);
						$('#editBlockedTimeModalForm').submit();
					}
				},
				eventResize: function(event) {
					var staff_user_id = event.resources[0];

					if(event.appointment_id_enc !== undefined) {
						var url = $("#updateAppointmentService").val();
						var csrf = $("input[name=_token]").val();
						var appointment_service_id = event.appointment_service_id;
						var staff_user_id          = event.resources[0];
						var start_date_time        = $.fullCalendar.moment(event.start,'DD.MM.YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
	                    var end_date_time          = $.fullCalendar.moment(event.end,'DD.MM.YYYY HH:mm:ss').format('YYYY-MM-DD HH:mm:ss');
						
						$.ajax({
							type: "POST",
							url: url,
							data: {appointment_service_id : appointment_service_id,staff_user_id : staff_user_id,start_date_time : start_date_time,end_date_time : end_date_time,_token : csrf},
							success: function (response) 
							{
								if(response.status == true){	
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
									toastr.success((response.message) ? response.message : "Something went wrong!");
								} else {
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
									toastr.error((response.message) ? response.message : "Something went wrong!");
								}
							}
						});
					} else {
						event.staff_user_id = staff_user_id;
						populateEditBlockedTimeModal(event);
						$('#editBlockedTimeModalForm').submit();
					}
				}
			});
			
			$(function(){
				$("#calander_current_date").datepicker({
					dateFormat: 'yy-mm-dd',
					onSelect: function(dateText) {
						$('#kt_calendar').fullCalendar('gotoDate',dateText);
						$('#kt_calendar').fullCalendar('changeView','resourceDay');
						$("#calanderViewFilter").val('day');
					}
				});
				
				var date = new Date();	
				var today = new Date(date.getFullYear(), date.getMonth(), date.getDate());
				$("#calander_current_date").datepicker('setDate', today);
			});

			$(document).on('click','.block-time-link', function() {
				$('.block-time-message').slideDown(500);
				window.selectBlockedTime = true;
			});

			$(document).on('click','.block-time-message-close', function() {
				closeBlockTimeModal();
			});

			function closeBlockTimeModal() {
				$('.block-time-message').slideUp(500);
				window.selectBlockedTime = false;
			}

			$(document).on('submit','#blockedTimeModalForm',function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
				});

				var self = $(this);


				var start_time = moment(self.find('.blocked-time-start-time').val(), 'HH:mm:ss');
				var end_time = moment(self.find('.blocked-time-end-time').val(), 'HH:mm:ss');
				
				if (!start_time.isBefore(end_time)){
					toastr.error('End time must be later than start time');
					return false;
				}

				$.ajax({
					type:'POST',
		           	url:"{{ route('addStaffBlockedTime') }}",
		           	data: self.serialize(),
					success: function(response) {
						if(response.status) {
							$('#blockedTimeModal').modal('hide');
							toastr.success(response.message);

							closeBlockTimeModal();

							calendar.fullCalendar('refetchEvents');
							/*
							setTimeout( function() {
								location.reload();
							}, 1500);*/	
						} else {
							toastr.error(response.message);
						}
					},
					error: function(data) {
						var errors = data.responseJSON;
						var errorsHtml = '';
						$.each(errors.error, function(key, value) {
							errorsHtml += '<p>' + value[0] + '</p>';
						});

						toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
						KTUtil.scrollTop();
					}
				});
			});


			$(document).on('click','#delBlock',function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
				});

				var self = $(this);


				$.ajax({
					type:'POST',
		           	url:"{{ route('deleteBlockedShift') }}",
		           	data: $('#editBlockedTimeModalForm').serialize(),
					success: function(response) {
						if(response.status) {
							$('#editBlockedTimeModal').modal('hide');
							toastr.success(response.message);
							
							calendar.fullCalendar('refetchEvents');
							/*setTimeout( function() {
								location.reload();
							}, 1500);	*/
						} else {
							toastr.error(response.message);
						}
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


			$(document).on('submit','#editBlockedTimeModalForm',function(){
				$.ajaxSetup({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
				});

				var self = $(this);

				var start_time = moment(self.find('.edit-blocked-time-start-time').val(), 'HH:mm:ss');
				var end_time = moment(self.find('.edit-blocked-time-end-time').val(), 'HH:mm:ss');
				
				if (!start_time.isBefore(end_time)){
					toastr.error('End time must be later than start time');
					return false;
				}

				$.ajax({
					type:'POST',
		           	url:"{{ route('updateStaffBlockedTime') }}",
		           	data: self.serialize(),
					success: function(response) {
						if(response.status) {
							$('#editBlockedTimeModal').modal('hide');
							toastr.success(response.message);
							
							calendar.fullCalendar('refetchEvents');
							/*setTimeout( function() {
								location.reload();
							}, 1500);	*/
						} else {
							toastr.error(response.message);
						}
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

			function populateEditBlockedTimeModal(event) {

				$('#editBlockedTimeModalTitle').text('Edit Blocked Time');
				$('#editBlockedTimeModalForm')[0].reset();

				$('.edit-blocked-time-id').val(event.staff_blocked_time_id_enc);
				$('.edit-blocked-time-staff-user-id').val(event.staff_user_id);
				$('.edit-blocked-time-start-time').val(moment(event.start).format('HH:mm:ss'));
				$('.edit-blocked-time-end-time').val(moment(event.end).format('HH:mm:ss'));
				$('.edit-blocked-time-date').val(moment(event.end).format('YYYY-MM-DD'));

				if(event.allow_online_booking) {
					$('.edit-blocked-time-allow-online-booking').prop('checked', true);
				} else {
					$('.edit-blocked-time-allow-online-booking').prop('checked', false);
				}
				$('.edit-blocked-time-description').val(event.description);
			}

			if($('.reschedule_appointment_id').val() != '') {
				$('.reschedule-message').slideDown(500);
				window.selectReschedule = true;
			}

			$(document).on('click','.reschedule-message-close', function() {
				closeRescheduleModal();
			});

			function closeRescheduleModal() {
				$('.reschedule-message').slideUp(500);
				window.selectReschedule = false;
			}
		});
		
		$(document).on('change','#calanderViewFilter',function(){
			var selectedFilter = $(this).val();
			
			if(selectedFilter == 'day'){
				$('#kt_calendar').fullCalendar('changeView','resourceDay');
			} else if(selectedFilter == '3days'){
				$('#kt_calendar').fullCalendar('changeView','agendaThreeDay');
			} else if(selectedFilter == 'week'){
				$('#kt_calendar').fullCalendar('changeView','agendaWeek');
			}
		});
		
		$(document).on('change','#staffFilter',function(){
			var staff_user_id = $(this).val();
			var url = $("#getStaffAppointment").val();
			var csrf = $("input[name=_token]").val();
			
			$.ajax({
				type: "POST",
				url: url,
				data: {staff_user_id : staff_user_id,_token : csrf},
				success: function (response) 
				{
					if(response.status == true){	
						$("#kt_calendar").fullCalendar('removeEvents');
						$('#kt_calendar').fullCalendar('addEventSource',response.events);
					} else {
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
						toastr.error((response.message) ? response.message : "Something went wrong!");
					}
				}
			});
		});
		
	</script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
	<script>
		$('#updateAppointmentConfirmationForm').validate({
			rules: {
				change_appointment_service_id: {
					required: true
				},
				change_staff_user_id: {
					required: true
				},
				change_start_date_time: {
					required: true
				},
				change_end_date_time: {
					required: true
				}
			},
			submitHandler: function(form) {
				$.ajax({
					url: $("#updateAppointmentConfirmationForm").attr('action'), 
					type: "POST",             
					data: $('#updateAppointmentConfirmationForm').serialize(),
					cache: false,             
					processData: false,  
					dataType:'json',			
					success: function(response) 
					{
						if(response.status == true){
							$("#updateAppointmentConfirmation").modal('hide');
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
							toastr.success((response.message) ? response.message : "Something went wrong!");
							$("#staffFilter").trigger('change');
						} else {
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
							toastr.error((response.message) ? response.message : "Something went wrong!");
						}
					},
					timeout: 10000,
					error: function(e){
						toastr.options = {
						  "closeButton": true,
						  "debug": false,
						  "newestOnTop": true,
						  "progressBar": true,
						  "positionClass": "toast-top-right",
						  "preventDuplicates": false,
						  "onclick": null,
						  "showDuration": "300",
						  "hideDuration": "1000",
						  "timeOut": "5000",
						  "extendedTimeOut": "1000",
						  "showEasing": "swing",
						  "hideEasing": "linear",
						  "showMethod": "fadeIn",
						  "hideMethod": "fadeOut"
						}
						toastr.error('Request timeout, Please try again later!');
						return false;
					}
				});
				return false;
			}
		});
		
		$(".no_access").click( function() {
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
			toastr.error("You don't have permission for this action, ask your account owner for access");
		});	
	</script>
	
	@if(Session::has('message'))
	<script>
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

		toastr.success('{{ Session::get('message') }}');
	</script>
	@endif
@endsection	