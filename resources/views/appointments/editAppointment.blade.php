{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')
<link rel="stylesheet" type="text/css" href="{{ asset('daterangepicker/daterangepicker.css') }}" />
<style type="text/css">
	#new_appointment_date:before {
		font-family: "Font Awesome 5 Free";
		content: "\f107";
	}

	select#schedule_end_repeat,
	input#schedule_date_end,
	select#schedule_repeats {
		padding: 0.65rem 0.5rem;
	}
	
	.notificationdiv .badge {
	  position: absolute;
	  top: 5px;
	  right: 5px;
	  padding: 3px 6px;
	  border-radius: 50%;
	  background: #615c5c;
	  color: white;
	  cursor: pointer;
	}
</style>
@endsection

{{-- CSS Section --}}
@section('content')
<!--begin::Content-->

<div class="modal fade p-0" id="blockClientModal" tabindex="-1" role="dialog" aria-labelledby="blockClientModalLabel Label" aria-modal="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold text-center" id="decreaseStockModalLabel">Block Client</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
				</button>
			</div>
			<form method="POST" enctype="multipart/form-data" action="{{ route('blockClient') }}" id="blockClient">
				@csrf
				<input type="hidden" name="block_client_id" id="block_client_id"> 
				<div class="modal-body">
					<h6>Are you sure you want to block this client?</h6>
					<br>
					<h6>Blocking prevents this client from booking online appointments with you, they will find no available time slots.</h6>
					<br>
					<h6>Blocked clients are also automatically excluded from any marketing messages.</h6>
					<br>
					<div class="form-group w-100">
						<label>Blocking reason</label>
						<select class="form-control" name="block_reason" id="block_reason">
							<option value="">Select blocking reason</option>
							<option value="Too many no shows">Too many no shows</option>
							<option value="Too many late cancellations">Too many late cancellations</option>
							<option value="Too many reschedules">Too many reschedules</option>
							<option value="Rude or inappropriate to staff">Rude or inappropriate to staff</option>
							<option value="Refused to pay">Refused to pay</option>
							<option value="Booked fake appointments">Booked fake appointments</option>
							<option value="Other">Other</option>
						</select>
					</div>
					
					<div class="form-group w-100 blockNotes" style="display:none;">
						<label>Add note</label>
						<textarea class="form-control" name="block_notes" id="block_notes"></textarea>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<div class="ml-auto">
						<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary font-weight-bold" id="blockClientBtn">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Modal -->
<div class="modal fade" id="repeatModal" tabindex="-1" role="dialog" aria-labelledby="repeatModalLabel" aria-hidden="true">
	<div class="modal-dialog  modal-sm" role="document">
		<div class="modal-content">
			<form method="POST" action="{{ route('checkForRepeatAppointment') }}" id="repeatAppointment">
				@csrf
				<input type="hidden" name="appointmentStartDate" id="appointmentStartDate" value="{{ date('Y-m-d') }}">
			
				<div class="modal-body">
					<div class="row attached-fields m-0">
						<div class="col-12 repeat-switch-column repeat_optin no-padding mb-5">
							<label for="schedule_repeats" class="">Frequency</label>
							<div class="select-wrapper">
								<select class="form-control repeat-switch frequencySelect" id="schedule_repeats" name="repeatFrequency" required >
									<option value="no-repeat">Doesn't repeat</option>
									<option value="daily:1">Daily</option>
									<option value="daily:2">Every 2 days</option>
									<option value="daily:3">Every 3 days</option>
									<option value="daily:4">Every 4 days</option>
									<option value="daily:5">Every 5 days</option>
									<option value="daily:6">Every 6 days</option>
									<option value="daily:7">Every 7 days</option>
									<option value="weekly:1">Weekly</option>
									<option value="weekly:2">Every 2 weeks</option>
									<option value="weekly:3">Every 3 weeks</option>
									<option value="weekly:4">Every 4 weeks</option>
									<option value="weekly:5">Every 5 weeks</option>
									<option value="weekly:6">Every 6 weeks</option>
									<option value="weekly:7">Every 7 weeks</option>
									<option value="weekly:8">Every 8 weeks</option>
									<option value="weekly:9">Every 9 weeks</option>
									<option value="weekly:10">Every 10 weeks</option>
									<option value="monthly:1">Monthly</option>
									<option value="monthly:2">Every 2 months</option>
									<option value="monthly:3">Every 3 months</option>
									<option value="monthly:4">Every 4 months</option>
									<option value="monthly:5">Every 5 months</option>
									<option value="monthly:6">Every 6 months</option>
									<option value="monthly:7">Every 7 months</option>
									<option value="monthly:8">Every 8 months</option>
									<option value="monthly:9">Every 9 months</option>
									<option value="monthly:10">Every 10 months</option>
									<option value="monthly:11">Every 11 months</option>
								</select>
							</div>
						</div>
						<div class="col-12 repeat-container mx-0 no-padding frequencyEndDiv" style="display:none;">
							<div class="end-repeat-container mb-0 p-0">
								<label class="">Ends</label>
								<div class="select-wrapper">
									<select class="form-control end-repeat frequencyEnd" id="schedule_end_repeat" name="repeatCount" required>
										<option value="count:2">After 2 times</option>
										<option value="count:3">After 3 times</option>
										<option value="count:4">After 4 times</option>
										<option value="count:5">After 5 times</option>
										<option value="count:6">After 6 times</option>
										<option value="count:7">After 7 times</option>
										<option value="count:8">After 8 times</option>
										<option value="count:9">After 9 times</option>
										<option value="count:10">After 10 times</option>
										<option value="count:11">After 11 times</option>
										<option value="count:12">After 12 times</option>
										<option value="count:13">After 13 times</option>
										<option value="count:14">After 14 times</option>
										<option value="count:15">After 15 times</option>
										<option value="count:20">After 20 times</option>
										<option value="count:25">After 25 times</option>
										<option value="count:30">After 30 times</option>
										<option value="specific_date">Specific date</option>
										<option value="ongoing">Ongoing</option>
									</select>
								</div>
							</div>
							<div class="date-end-field p-0 frequencyDateDiv" style="display:none;">
								<label class="">Selected date</label>
								<input type="text" class="form-control" id="schedule_date_end" name="repeatDate" readonly="" placeholder="End date" />
								<input type="hidden" id="repeatDateEnd" name="repeatDateEnd"/>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-center">
					<button type="button" class="btn btn-outline-secondary min-w-120px" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary min-w-120px" id="checkForRepeatAppointmentBtn">Apply changes</button>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="container-fluid p-0">
	<div class="my-custom-body-wrapper">
		<div class="my-custom-header">
			<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
				<h2 class="m-auto font-weight-bolder">Edit Appointment</h2>
				<span class="d-flex">
					<p class="cursor-pointer m-0 px-6" onclick="history.back();"><i class="text-dark fa fa-times icon-lg"></i></p>
				</span>
			</div>
		</div>
		<form method="POST" id="editAppointment" action="{{ route('editAppointmentInfo') }}">
		@csrf
		
		@php 
			$ClientID = (isset($ClientInfo->id) && $ClientInfo->id != '') ? $ClientInfo->id : ''; 
			$AppointmentID = (isset($Appointment['id']) && $Appointment['id'] != '') ? $Appointment['id'] : ''; 
			$LocationId = (isset($Appointment['location_id']) && $Appointment['location_id'] != '') ? $Appointment['location_id'] : ''; 
		@endphp
		
		<input type="hidden" id="appoinment_id" name="appoinment_id" value="{{ $AppointmentID }}">
		<input type="hidden" id="clientId" name="clientId" value="{{ $ClientID }}">
		<input type="hidden" id="location_id" name="location_id" value="{{ $LocationId }}">
		<input type="hidden" id="repeatAppointmentFrequency" name="repeatAppointmentFrequency" value="">
		<input type="hidden" id="repeatAppointmentCount" name="repeatAppointmentCount" value="">
		<input type="hidden" id="repeatAppointmentDate" name="repeatAppointmentDate" value="">		
		<input type="hidden" id="searchClientAction" value="{{ route('searchClients') }}">
		<input type="hidden" id="getClientInformation" value="{{ route('getClientInformation') }}">
		<input type="hidden" id="removeAppointmentService" value="{{ route('removeAppointmentService') }}">
		<input type="hidden" id="getStaffPriceDetails" value="{{ route('getStaffPriceDetails') }}">
		
		<input type="hidden" id="prevStartTime" name="prevStartTime" value="{{ $NearestTime }}">
		<input type="hidden" id="isNewService" name="isNewService" value="0">
		
		<div class="my-custom-body">
			<div class="container-fluid ">
				<div class="row">
					<div class="col-12 col-md-8 p-0 bg-white" style="height:calc(100vh - 55px);overflow-y:scroll">
						<div class="px-10">
							<div class="container p-12 position-relative">
								<div class="mb-6">									
									<h4 class="align-items-center d-flex font-weight-bolder justify-content-between mb-8">
										<input type="text" class="border-0 cursor-pointer" name="appointment_date" id="new_appointment_date" readonly="readonly" placeholder="Select date" value="{{ ($Appointment['appointment_date']) ? date("l, d M Y",strtotime($Appointment['appointment_date'])) : '' }}" />
										
										<!--div class="blankRepeat">
											<a href="javascript:;" class="text-blue openRepeatModal"><i class="flaticon-refresh mr-3 text-blue"></i>Repeat</a>
										</div>
										
										<div class="fillRepeat" style="display:none;">
											<a href="javascript:;" class="text-blue openRepeatModal"><span class="fillRepeatText"></span> <i class="text-dark fa fa-times icon-lg"></i></a>
										</div-->
									</h4>
									
									<ul class="StepProgress p-0 addServices">
									
										@if(!empty($ClientServices))
											@foreach($ClientServices as $ClientServicesData)
											<li class="StepProgress-item current {{ $ClientServicesData['unique_code'] }}">
												<input type="hidden" name="edit_appointment_services_id[]" value="{{ $ClientServicesData['appointment_service_id'] }}">
											
												<input type="hidden" class="serviceCharge" name="edit_special_price[]" value="{{ $ClientServicesData['special_price'] }}">
												<input type="hidden" class="durationSeconds" name="edit_durationSeconds[]" value="{{ $ClientServicesData['durationSeconds'] }}">
												
												<input type="hidden" class="isExtraTime" name="edit_isExtraTime[]" value="{{ $ClientServicesData['isExtraTime'] }}">
												<input type="hidden" class="extraTimeType" name="edit_extraTimeType[]" value="{{ $ClientServicesData['extraTimeType'] }}">
												<input type="hidden" class="timeExtraDuration" name="edit_timeExtraDuration[]" value="{{ $ClientServicesData['timeExtraDurationSec'] }}">
												
												<div class="shadow-sm card notificationdiv">
													<span class="badge removeSavedServices" data-rowNumber="{{ $ClientServicesData['unique_code'] }}" data-appointmentServiceID="{{ $ClientServicesData['appointment_service_id'] }}" data-clientID="{{ $ClientID }}">X</span>
													<div class="card-body p-6">
														<div class="row">
															<div class="col-md-3 col-sm-4">
																<div class="form-group">
																	<label for="start_time" class="font-weight-bolder">Start time</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceStartTime" name="edit_start_time[]" required data-rowNumber="{{ $ClientServicesData['unique_code'] }}">
																			@if(!empty($TimeSlots))
																				<option value="">Please select</option>		
																				@foreach($TimeSlots as $key => $TimeSlot)
																					<option value="{{ $TimeSlot['timevalue'] }}" @if($ClientServicesData['start_time'] == $TimeSlot['timevalue']) selected @endif>{{ $TimeSlot['time'] }}</option>	
																				@endforeach
																			@else
																				<option>No slots found.</option>	
																			@endif			
																		</select>
																	</div>
																</div>
															</div>
															<div class="col-md-9 col-sm-8">
																<div class="form-group">
																	<label for="start_time"
																		class="font-weight-bolder">Service</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceSelect" data-rowNumber="{{ $ClientServicesData['unique_code'] }}" name="edit_service_id[]" required>
																			@if(!empty($Services))
																				<option value="" data-timeDuration="" data-timeExtraDuration="">Please select</option>			
																				@foreach($Services as $ServiceData)
																					@php
																						$ServiceDuration = $ServiceData['duration'] * 60;
																						$ExtraDuration = 0;
																						if($ServiceData['is_extra_time'] == 1){
																							$ExtraDuration = $ServiceData['extra_time_duration'];
																						} 
																						
																						$serviceCharge = ($ServiceData['special_price'] != 0) ? $ServiceData['price'] : (($ServiceData['price']) ? $ServiceData['price'] : 0);
																					@endphp
																					<option value="{{ $ServiceData['service_price_id'] }}" data-timeDuration="{{ $ServiceDuration }}" data-isExtraTime="{{ $ServiceData['is_extra_time'] }}" data-extraTimeType="{{ $ServiceData['extra_time'] }}" data-timeExtraDuration="{{ $ExtraDuration }}" data-serviceCharge="{{ $serviceCharge }}" @if($ClientServicesData['service_id'] == $ServiceData['service_price_id']) selected @endif>{{$ServiceData['service_name'] }} - {{$ServiceData['pricing_name'] }} - {{$ServiceData['price'] }}	
																					</option>		
																				@endforeach
																			@else
																			<option value="">No Services Found.</option>		
																			@endif
																		</select>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-3 col-sm-4">
																<div class="form-group">
																	<label for="start_time"
																		class="font-weight-bolder">Duration</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceDuration" data-rowNumber="{{ $ClientServicesData['unique_code'] }}" name="edit_duration[]" required>
																			<option value=""></option>
																			<option value="300" @if($ClientServicesData['duration'] == '300') selected @endif>5min</option>
																			<option value="600" @if($ClientServicesData['duration'] == '600') selected @endif>10min</option>
																			<option value="900" @if($ClientServicesData['duration'] == '900') selected @endif>15min</option>
																			<option value="1200" @if($ClientServicesData['duration'] == '1200') selected @endif>20min</option>
																			<option value="1500" @if($ClientServicesData['duration'] == '1500') selected @endif>25min</option>
																			<option value="1800" @if($ClientServicesData['duration'] == '1800') selected @endif>30min</option>
																			<option value="2100" @if($ClientServicesData['duration'] == '2100') selected @endif>35min</option>
																			<option value="2400" @if($ClientServicesData['duration'] == '2400') selected @endif>40min</option>
																			<option value="2700" @if($ClientServicesData['duration'] == '2700') selected @endif>45min</option>
																			<option value="3000" @if($ClientServicesData['duration'] == '3000') selected @endif>50min</option>
																			<option value="3300" @if($ClientServicesData['duration'] == '3300') selected @endif>55min</option>
																			<option value="3600" @if($ClientServicesData['duration'] == '3600') selected @endif>1h</option>
																			<option value="3900" @if($ClientServicesData['duration'] == '3900') selected @endif>1h 5min</option>
																			<option value="4200" @if($ClientServicesData['duration'] == '4200') selected @endif>1h 10min</option>
																			<option value="4500" @if($ClientServicesData['duration'] == '4500') selected @endif>1h 15min</option>
																			<option value="4800" @if($ClientServicesData['duration'] == '4800') selected @endif>1h 20min</option>
																			<option value="5100" @if($ClientServicesData['duration'] == '5100') selected @endif>1h 25min</option>
																			<option value="5400" @if($ClientServicesData['duration'] == '5400') selected @endif>1h 30min</option>
																			<option value="5700" @if($ClientServicesData['duration'] == '5700') selected @endif>1h 35min</option>
																			<option value="6000" @if($ClientServicesData['duration'] == '6000') selected @endif>1h 40min</option>
																			<option value="6300" @if($ClientServicesData['duration'] == '6300') selected @endif>1h 45min</option>
																			<option value="6600" @if($ClientServicesData['duration'] == '6600') selected @endif>1h 50min</option>
																			<option value="6900" @if($ClientServicesData['duration'] == '6900') selected @endif>1h 55min</option>
																			<option value="7200" @if($ClientServicesData['duration'] == '7200') selected @endif>2h</option>
																			<option value="8100" @if($ClientServicesData['duration'] == '8100') selected @endif>2h 15min</option>
																			<option value="9000" @if($ClientServicesData['duration'] == '9000') selected @endif>2h 30min</option>
																			<option value="9900" @if($ClientServicesData['duration'] == '9900') selected @endif>2h 45min</option>
																			<option value="10800" @if($ClientServicesData['duration'] == '10800') selected @endif>3h</option>
																			<option value="11700" @if($ClientServicesData['duration'] == '11700') selected @endif>3h 15min</option>
																			<option value="12600" @if($ClientServicesData['duration'] == '12600') selected @endif>3h 30min</option>
																			<option value="13500" @if($ClientServicesData['duration'] == '13500') selected @endif>3h 45min</option>
																			<option value="14400" @if($ClientServicesData['duration'] == '14400') selected @endif>4h</option>
																			<option value="16200" @if($ClientServicesData['duration'] == '16200') selected @endif>4h 30min</option>
																			<option value="18000" @if($ClientServicesData['duration'] == '18000') selected @endif>5h</option>
																			<option value="19800" @if($ClientServicesData['duration'] == '19800') selected @endif>5h 30min</option>
																			<option value="21600" @if($ClientServicesData['duration'] == '21600') selected @endif>6h</option>
																			<option value="23400" @if($ClientServicesData['duration'] == '23400') selected @endif>6h 30min</option>
																			<option value="25200" @if($ClientServicesData['duration'] == '25200') selected @endif>7h</option>
																			<option value="27000" @if($ClientServicesData['duration'] == '27000') selected @endif>7h 30min</option>
																			<option value="28800" @if($ClientServicesData['duration'] == '28800') selected @endif>8h</option>
																			<option value="32400" @if($ClientServicesData['duration'] == '32400') selected @endif>9h</option>
																			<option value="36000" @if($ClientServicesData['duration'] == '36000') selected @endif>10h</option>
																			<option value="39600" @if($ClientServicesData['duration'] == '39600') selected @endif>11h</option>
																			<option value="43200" @if($ClientServicesData['duration'] == '43200') selected @endif>12h</option>
																		</select>
																	</div>
																</div>
															</div>
															<div class="col-md-9 col-sm-8">
																<div class="form-group">
																	<label for="start_time"
																		class="font-weight-bolder">Staff</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceStaffEdit" data-rowNumber="{{ $ClientServicesData['unique_code'] }}" name="edit_staff_user_id[]" required>
																			@if(!empty($Staff))
																				<option selected value="">Select Staff</option>	
																				@foreach($Staff as $StaffData)
																					<option value="{{ $StaffData['staff_user_id'] }}" @if($ClientServicesData['staff_user_id'] == $StaffData['staff_user_id']) selected @endif>{{ $StaffData['first_name'] }} {{ $StaffData['last_name'] }}</option>
																				@endforeach
																			@else
																				<option value="">No Staff Members Found.</option>	
																			@endif	
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</li>
												@if($ClientServicesData['isExtraTime'] == 1)
													@php
														if($ClientServicesData['extraTimeType'] == 0){	
															$extraTimeText ='processing';	
														}else{
															$extraTimeText = 'blocked';		
														}
													@endphp
												<li class="StepProgress-item current {{ $ClientServicesData['unique_code'] }}Extra">
													<p>{{ $ClientServicesData['timeExtraDuration'] }} of {{ $extraTimeText }} time after </p>
												</li>
												@endif
											@endforeach
											
											<li class="StepProgress-item current defaultrow liwithnodata">
												<input type="hidden" class="serviceCharge" name="special_price[]" value="0">
												<input type="hidden" class="durationSeconds" name="durationSeconds[]" value="0">
												
												<input type="hidden" class="isExtraTime" name="isExtraTime[]" value="0">
												<input type="hidden" class="extraTimeType" name="extraTimeType[]" value="0">
												<input type="hidden" class="timeExtraDuration" name="timeExtraDuration[]" value="0">
												
												<div class="shadow-sm card notificationdiv">
													<span class="badge removeService" data-rowNumber="defaultrow" style="display:none;">X</span>
													<div class="card-body p-6">
														<div class="row">
															<div class="col-md-3 col-sm-4">
																<div class="form-group">
																	<label for="start_time" class="font-weight-bolder">Start time</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceStartTime" name="start_time[]" @if(empty($ClientServices)) required @endif data-rowNumber="defaultrow">
																			@if(!empty($TimeSlots))
																				<option value="">Please select</option>		
																				@foreach($TimeSlots as $key => $TimeSlot)
																					<option value="{{ $TimeSlot['timevalue'] }}" @if($NearestTime == $TimeSlot['timevalue']) selected @endif>{{ $TimeSlot['time'] }}</option>	
																				@endforeach
																			@else
																				<option>No slots found.</option>	
																			@endif			
																		</select>
																	</div>
																</div>
															</div>
															<div class="col-md-9 col-sm-8">
																<div class="form-group">
																	<label for="start_time"
																		class="font-weight-bolder">Service</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceSelect" data-rowNumber="defaultrow" name="service_id[]" @if(empty($ClientServices)) required @endif>
																			@if(!empty($Services))
																				<option value="" data-timeDuration="" data-timeExtraDuration="">Please select</option>			
																				@foreach($Services as $ServiceData)
																					@php
																						$ServiceDuration = $ServiceData['duration'] * 60;
																						$ExtraDuration = 0;
																						if($ServiceData['is_extra_time'] == 1){
																							$ExtraDuration = $ServiceData['extra_time_duration'];
																						} 
																						
																						$serviceCharge = ($ServiceData['special_price'] != 0) ? $ServiceData['price'] : (($ServiceData['price']) ? $ServiceData['price'] : 0);
																					@endphp
																					<option value="{{ $ServiceData['service_price_id'] }}" data-timeDuration="{{ $ServiceDuration }}" data-isExtraTime="{{ $ServiceData['is_extra_time'] }}" data-extraTimeType="{{ $ServiceData['extra_time'] }}" data-timeExtraDuration="{{ $ExtraDuration }}" data-serviceCharge="{{ $serviceCharge }}">{{$ServiceData['service_name'] }} - {{$ServiceData['pricing_name'] }} - {{$ServiceData['price'] }}	
																					</option>		
																				@endforeach
																			@else
																			<option value="">No Services Found.</option>		
																			@endif
																		</select>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-3 col-sm-4">
																<div class="form-group">
																	<label for="start_time"
																		class="font-weight-bolder">Duration</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceDuration" data-rowNumber="defaultrow" name="duration[]" @if(empty($ClientServices)) required @endif>
																			<option value=""></option>
																			<option value="300">5min</option>
																			<option value="600">10min</option>
																			<option value="900">15min</option>
																			<option value="1200">20min</option>
																			<option value="1500">25min</option>
																			<option value="1800">30min</option>
																			<option value="2100">35min</option>
																			<option value="2400">40min</option>
																			<option value="2700">45min</option>
																			<option value="3000">50min</option>
																			<option value="3300">55min</option>
																			<option value="3600">1h</option>
																			<option value="3900">1h 5min</option>
																			<option value="4200">1h 10min</option>
																			<option value="4500">1h 15min</option>
																			<option value="4800">1h 20min</option>
																			<option value="5100">1h 25min</option>
																			<option value="5400">1h 30min</option>
																			<option value="5700">1h 35min</option>
																			<option value="6000">1h 40min</option>
																			<option value="6300">1h 45min</option>
																			<option value="6600">1h 50min</option>
																			<option value="6900">1h 55min</option>
																			<option value="7200">2h</option>
																			<option value="8100">2h 15min</option>
																			<option value="9000">2h 30min</option>
																			<option value="9900">2h 45min</option>
																			<option value="10800">3h</option>
																			<option value="11700">3h 15min</option>
																			<option value="12600">3h 30min</option>
																			<option value="13500">3h 45min</option>
																			<option value="14400">4h</option>
																			<option value="16200">4h 30min</option>
																			<option value="18000">5h</option>
																			<option value="19800">5h 30min</option>
																			<option value="21600">6h</option>
																			<option value="23400">6h 30min</option>
																			<option value="25200">7h</option>
																			<option value="27000">7h 30min</option>
																			<option value="28800">8h</option>
																			<option value="32400">9h</option>
																			<option value="36000">10h</option>
																			<option value="39600">11h</option>
																			<option value="43200">12h</option>
																		</select>
																	</div>
																</div>
															</div>
															<div class="col-md-9 col-sm-8">
																<div class="form-group">
																	<label for="start_time"
																		class="font-weight-bolder">Staff</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceStaffEdit" data-rowNumber="defaultrow" name="staff_user_id[]" @if(empty($ClientServices)) required @endif>
																			@if(!empty($Staff))
																				<option selected value="">Select Staff</option>	
																				@foreach($Staff as $StaffData)
																					<option value="{{ $StaffData['staff_user_id'] }}">{{ $StaffData['first_name'] }} {{ $StaffData['last_name'] }}</option>
																				@endforeach
																			@else
																				<option value="">No Staff Members Found.</option>	
																			@endif	
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</li>
											
										@else
											<li class="StepProgress-item current defaultrow liwithnodata">
												<input type="hidden" class="serviceCharge" name="special_price[]" value="0">
												<input type="hidden" class="durationSeconds" name="durationSeconds[]" value="0">
												
												<input type="hidden" class="isExtraTime" name="isExtraTime[]" value="0">
												<input type="hidden" class="extraTimeType" name="extraTimeType[]" value="0">
												<input type="hidden" class="timeExtraDuration" name="timeExtraDuration[]" value="0">
												
												<div class="shadow-sm card">
													<div class="card-body p-6">
														<div class="row">
															<div class="col-md-3 col-sm-4">
																<div class="form-group">
																	<label for="start_time" class="font-weight-bolder">Start time</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceStartTime" name="start_time[]" required data-rowNumber="defaultrow">
																			@if(!empty($TimeSlots))
																				<option value="">Please select</option>		
																				@foreach($TimeSlots as $key => $TimeSlot)
																					<option value="{{ $TimeSlot['timevalue'] }}" @if($NearestTime == $TimeSlot['timevalue']) selected @endif>{{ $TimeSlot['time'] }}</option>	
																				@endforeach
																			@else
																				<option>No slots found.</option>	
																			@endif			
																		</select>
																	</div>
																</div>
															</div>
															<div class="col-md-9 col-sm-8">
																<div class="form-group">
																	<label for="start_time"
																		class="font-weight-bolder">Service</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceSelect" data-rowNumber="defaultrow" name="service_id[]" required>
																			@if(!empty($Services))
																				<option value="" data-timeDuration="" data-timeExtraDuration="">Please select</option>			
																				@foreach($Services as $ServiceData)
																					@php
																						$ServiceDuration = $ServiceData['duration'] * 60;
																						$ExtraDuration = 0;
																						if($ServiceData['is_extra_time'] == 1){
																							$ExtraDuration = $ServiceData['extra_time_duration'];
																						} 
																						
																						$serviceCharge = ($ServiceData['special_price'] != 0) ? $ServiceData['price'] : (($ServiceData['price']) ? $ServiceData['price'] : 0);
																					@endphp
																					<option value="{{ $ServiceData['service_price_id'] }}" data-timeDuration="{{ $ServiceDuration }}" data-isExtraTime="{{ $ServiceData['is_extra_time'] }}" data-extraTimeType="{{ $ServiceData['extra_time'] }}" data-timeExtraDuration="{{ $ExtraDuration }}" data-serviceCharge="{{ $serviceCharge }}">{{$ServiceData['service_name'] }} - {{$ServiceData['pricing_name'] }} - {{$ServiceData['price'] }}	
																					</option>		
																				@endforeach
																			@else
																			<option value="">No Services Found.</option>		
																			@endif
																		</select>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-3 col-sm-4">
																<div class="form-group">
																	<label for="start_time"
																		class="font-weight-bolder">Duration</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceDuration" data-rowNumber="defaultrow" name="duration[]" disabled required>
																			<option value=""></option>
																			<option value="300">5min</option>
																			<option value="600">10min</option>
																			<option value="900">15min</option>
																			<option value="1200">20min</option>
																			<option value="1500">25min</option>
																			<option value="1800">30min</option>
																			<option value="2100">35min</option>
																			<option value="2400">40min</option>
																			<option value="2700">45min</option>
																			<option value="3000">50min</option>
																			<option value="3300">55min</option>
																			<option value="3600">1h</option>
																			<option value="3900">1h 5min</option>
																			<option value="4200">1h 10min</option>
																			<option value="4500">1h 15min</option>
																			<option value="4800">1h 20min</option>
																			<option value="5100">1h 25min</option>
																			<option value="5400">1h 30min</option>
																			<option value="5700">1h 35min</option>
																			<option value="6000">1h 40min</option>
																			<option value="6300">1h 45min</option>
																			<option value="6600">1h 50min</option>
																			<option value="6900">1h 55min</option>
																			<option value="7200">2h</option>
																			<option value="8100">2h 15min</option>
																			<option value="9000">2h 30min</option>
																			<option value="9900">2h 45min</option>
																			<option value="10800">3h</option>
																			<option value="11700">3h 15min</option>
																			<option value="12600">3h 30min</option>
																			<option value="13500">3h 45min</option>
																			<option value="14400">4h</option>
																			<option value="16200">4h 30min</option>
																			<option value="18000">5h</option>
																			<option value="19800">5h 30min</option>
																			<option value="21600">6h</option>
																			<option value="23400">6h 30min</option>
																			<option value="25200">7h</option>
																			<option value="27000">7h 30min</option>
																			<option value="28800">8h</option>
																			<option value="32400">9h</option>
																			<option value="36000">10h</option>
																			<option value="39600">11h</option>
																			<option value="43200">12h</option>
																		</select>
																	</div>
																</div>
															</div>
															<div class="col-md-9 col-sm-8">
																<div class="form-group">
																	<label for="start_time"
																		class="font-weight-bolder">Staff</label>
																	<div class="select-wrapper">
																		<select class="form-control serviceStaffEdit" data-rowNumber="defaultrow" name="staff_user_id[]" required>
																			@if(!empty($Staff))
																				<option selected value="">Select Staff</option>	
																				@foreach($Staff as $StaffData)
																					<option value="{{ $StaffData['staff_user_id'] }}">{{ $StaffData['first_name'] }} {{ $StaffData['last_name'] }}</option>
																				@endforeach
																			@else
																				<option value="">No Staff Members Found.</option>	
																			@endif	
																		</select>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</div>
											</li>	
										@endif		
									</ul>
									<div class="row">
										<div class="form-group w-100">
											<label class="font-weight-bolder">Appointment notes</label>
											<textarea class="form-control" placeholder="Add an appointment note (visible to staff only)" rows="4" name="appointment_notes" id="appointment_notes">{{ ($Appointment['appointment_notes']) ? $Appointment['appointment_notes'] : '' }}</textarea>
										</div>
										<div class="form-group">
											<div class="checkbox-list">
												<label class="checkbox">
													<input type="checkbox">
													<span></span> Ask client to confirm appointment with card,
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="side-overlay">
								<div id="dismiss">
									<i class="la la-close" style="font-size: 18px;"></i>
									<span style="display: block; margin-top: 10px; color: #fff; font-size: 14px; text-transform: uppercase; font-weight: 700; text-shadow: 0 0 4px #67768c;">CLICK TO CLOSE</span>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-4 p-0" style="overflow-x: hidden;height:calc(100vh - 55px);overflow-y:scroll">
						
						<div class="form-group p-6 border-y mb-0 searchBar" @if(!empty($ClientInfo)) style="display:none;" @endif>
							<div class="input-icon">
								<input type="text" id="sidebarCollapse" class="form-control searchClients" placeholder="Search...">
								<span>
									<i class="flaticon2-search-1 icon-md"></i>
								</span>
							</div>
						</div>
						
						<div class="customer-bottom">
							<div class="customer-bottom-outer">
								
								<div class="view-appoinment p-4 mb-2 searchBlankBar" style="height: calc(100vh - 270px); @if(!empty($ClientInfo)) display:none; @endif">
									<div class="icon my-4 mx-auto" style="height: 48px;width:48px">
										<svg viewBox="0 0 48 48" xmlns="http://www.w3.org/2000/svg">
											<g fill="none" fill-rule="evenodd">
												<circle fill="#FBD74C" cx="22.5" cy="17.5" r="9.5"></circle>
												<g transform="translate(2 2)" stroke="#101928"
													stroke-linecap="round" stroke-linejoin="round"
													stroke-width="1.5">
													<path d="M34.642 34.642L44.5 44.5"></path>
													<circle cx="20.5" cy="20.5" r="20"></circle>
													<path
														d="M29.5 30.5h-18v-2.242a3.999 3.999 0 012.866-3.838c1.594-.472 3.738-.92 6.134-.92 2.356 0 4.514.456 6.125.932a4.003 4.003 0 012.875 3.841V30.5z">
													</path>
													<circle cx="20.5" cy="15.5" r="4.5"></circle>
												</g>
											</g>
										</svg>
									</div>
									<h4 class="px-10 text-center">
										Use the search to add a client, or keep empty to save as walk-in.
									</h4>
								</div>
								
								<div class="userHistory" @if(empty($ClientInfo)) style="display:none;" @endif">
									<div class="view-appoinment mb-2" style="height: calc(100vh - 270px);">							
										<div id="" class="bg-white active">
											<div class="d-flex align-items-center border-bottom p-6 customer-data" id="sidebarCollapse">
												<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
													<div class="symbol-label rounded-circle" style="background-image:url('https://schedulethat.tjcg.in/public/assets/media/users/300_13.jpg')"></div>
												</div>
												<div>
													<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
														{{ (isset($ClientInfo->firstname)) ? $ClientInfo->firstname : '' }} {{ (isset($ClientInfo->lastname)) ? $ClientInfo->lastname : '' }}
														<span class="fonter-weight-bolder">*</span>
													</a>
													<div class="text-muted">+{{ (isset($ClientInfo->mo_country_code)) ? $ClientInfo->mo_country_code : '' }} {{ (isset($ClientInfo->mobileno)) ? $ClientInfo->mobileno : '' }}<span class="font-weight-bolder">*</span></div>
													<div class="text-muted">{{ (isset($ClientInfo->email)) ? $ClientInfo->email : '' }}</div>
												</div>
												
												<div class="dropdown dropdown-inline" style="margin-left: 185px;">
													<a href="#" class="btn btn-clean btn-hover-primary btn-sm btn-icon" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
														<i class="ki ki-bold-more-hor"></i>
													</a>
													<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
														<ul class="navi navi-hover">
															<li class="navi-item">
																<a href="{{ url('/view/'.$ClientID.'/client') }}" class="cursor-pointer navi-link">
																	<span class="navi-text">Edit client details</span>
																</a>
															</li>
															<li class="navi-item">
																<a data-toggle="modal" data-target="#blockClientModal" class="cursor-pointer navi-link clickBlockClient" data-client_id="{{ $ClientID }}">
																	<span class="navi-text">Block client</span>
																</a>
															</li>
															@if(empty($ClientInfo))
															<li class="navi-item">
																<a class="cursor-pointer navi-link removeClientFromAppoin">
																	<span class="navi-text">Remove from appointment</span>
																</a>
															</li>
															@endif
														</ul>
													</div>
												</div>
											</div>
											<div class="card-body p-1">
												<div class="total-appoinment-data justify-content-around d-flex">
													<div class="text-center w-100 data pt-1 p-42">
														<h3 class="price font-weight-bolder text-center text-dark">{{ count($PreviousServices) }}</h3>
														<p class="title text-muted">Total Booking</p>
													</div>
													<div class=" text-center w-100 data pt-1 p-2">
														<h3 class="price font-weight-bolder text-center text-dark">CA ${{ $TotalSpend }}</h3>
														<p class="title text-muted">Total Sales</p>
													</div>
												</div>
												<ul class="nav nav-tabs nav-tabs-line nav-tabs-primary nav-tabs-line-2x" role="tablist">
													<li class="nav-item">
														<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link active" data-toggle="tab" href="#appointments">Appointments ({{ count($PreviousServices) }})</a>
													</li>
													<li class="nav-item">
														<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link" data-toggle="tab" href="#products">Products ({{ count($soldProduct) }})</a>
													</li>
													<li class="nav-item">
														<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link" data-toggle="tab" href="#invoices">Invoices ({{ count($clientInvoices) }})</a>
													</li>
													<li class="nav-item">
														<a style="margin:0px;padding:0 6px;font-size: 12px;" class="nav-link" data-toggle="tab" href="#consultationforms">Info</a>
													</li>
												</ul>
												<div class="tab-content mt-5" id="myTabContent">
													<div class="tab-pane fade show active" id="appointments" role="tabpanel" aria-labelledby="appointments">
														<div class="row">
															<div class="card-body py-2 px-8">
																@if(!empty($PreviousServices))
																	@foreach($PreviousServices as $PreviousServiceData)
																		<div class="client-apoinments-list mb-6">
																			<div class="d-flex align-items-center flex-grow-1">
																				<h6 class="font-weight-bolder text-dark">{{ $PreviousServiceData['appointment_date_month'] }}</h6>
																				<div
																					class="d-flex flex-wrap align-items-center justify-content-between w-100">
																					<div class="d-flex flex-column align-items-cente py-2 w-75">
																						<h6 class="text-muted font-weight-bold">
																							{{ $PreviousServiceData['appointment_date_hours'] }} <a class="text-blue" href="#">New</a>
																						</h6>
																						<a href="#"
																							class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg mb-1">{{ $PreviousServiceData['serviceName'] }}</a>
																						<span class="text-muted font-weight-bold">{{ $PreviousServiceData['duration'] }} with <i class="fa fa-heart text-danger"></i>
																							{{ $PreviousServiceData['staff_name'] }}
																						</span>
																					</div>
																					<h6 class="font-weight-bolder py-4">CA ${{ $PreviousServiceData['special_price'] }}</h6>
																				</div>
																			</div>
																		</div>
																	@endforeach
																@else
																	<div class="client-apoinments-list mb-6">
																		<center>No services found.</center>
																	</div>
																@endif
															</div>
														</div>
													</div>
													<div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products">
														<?php echo $ClientProducts ?>
													</div>
													<div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices">
														<div class="col-12 col-md-12">
															<table class="table table-hover">
																<thead>
																	<tr>
																		<th>Status</th>
																		<th>Invoice#</th>
																		<th>Invoice date</th>
																		<th>Total</th>
																	</tr>
																</thead>
																<tbody>
																	<?php echo $ClientInovices ?>
																</tbody>
															</table>
														</div>
													</div>
													<div class="tab-pane fade" id="consultationforms" role="tabpanel" aria-labelledby="consultationforms">
														<h6>Email</h6>
														<h6>{{ (isset($ClientInfo->email)) ? $ClientInfo->email : '' }}</h6>
														<br>
														<h6>Gender</h6>
														<h6>{{ (isset($ClientInfo->gender)) ? $ClientInfo->gender : '' }}</h6>
														<br>
														<h6>Marketing notifications</h6>
														
														@if(isset($ClientInfo->accept_marketing_notification) && $ClientInfo->accept_marketing_notification == 1)
															<h6>Accepts marketing notifications</h6>
														@else
															<h6>Not accepts marketing notifications</h6>
														@endif
													</div>
												</div>
											</div>
										</div>
									</div>
										
								</div>
								
								<div class="view-appoinment-footer border-top w-100 py-6">
									<h6 class="font-weight-bolder text-center my-3">Total: <span class="totalCharge">{{ (isset($Appointment['final_total'])) ? $Appointment['final_total'] : 'Free' }}</span> 
									(<span class="totalTime">{{ $TotalDurationString }}</span>)
									</h6>
									<div class="buttons d-flex justify-content-between">
										<button type="submit" class="btn btn-white w-100 mx-4" id="expressCheckout" name="expressCheckout" value="expressCheckout">Express checkout</button>
										<button type="submit" class="btn btn-primary w-100 mx-4" id="saveAppointment" name="saveAppointment" value="saveAppointment">Save appointment</button>
									</div>
								</div>
							</div>
							<div id="sidebar" class="bg-white clientList" @if(!empty($ClientInfo)) style="display:none;" @endif">
								<div class="card-body p-1">
									<div class="border-bottom p-4 text-center text-blue">
										<a href="{{ route('clientList') }}"><i class="text-blue fa fa-plus mr-2"></i>Add new client</a>
									</div>
									<div class="searchClientDiv">
										@if(!empty($Clients))	
											@foreach($Clients as $Client)
										<div class="d-flex align-items-center border-bottom p-6 customer-data" onclick="getClientHistory({{ $Client->id }});">
											<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
												<div class="symbol-label rounded-circle" style="background-image:url({{ asset('assets/media/users/300_13.jpg') }})">
												</div>
											</div>
											<div>
												<h6 class="font-weight-bolder">{{ $Client->firstname }} {{ $Client->lastname }}</h6>
												<div class="text-muted">
													@if($Client->email != '')
														{{ $Client->email }}
													@elseif($Client->mobileno != '')
														{{ $Client->mo_country_code }} {{ $Client->mobileno }}
													@endif
												</div>
											</div>
										</div>
											@endforeach
										@else
										<div class="d-flex align-items-center border-bottom p-6 customer-data">
											<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
												<div class="symbol-label rounded-circle" style="background-image:url({{ asset('assets/media/users/300_13.jpg') }})">
												</div>
											</div>
											<div>
												<div class="text-muted">No client found!</div>
											</div>
										</div>	
										@endif	
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
<!--end::Content-->
@endsection
{{-- Scripts Section --}}
@section('scripts')	
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
<script type="text/javascript" src="{{ asset('daterangepicker/daterangepicker.min.js') }}"></script>
<script src="{{ asset('js/add-appointment.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>
<script>
$(document).ready(function () {
	$('#dismiss, .side-overlay').on('click', function () {
		$('#sidebar').removeClass('active');
		$('.side-overlay').removeClass('active');
	});

	$('#sidebarCollapse').on('click', function () {
		$('#sidebar').addClass('active');
		$('.side-overlay').addClass('active');
	});
});
</script>
@endsection