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
	.white { fill: #FFF !important; }
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

<div class="modal fade p-0" id="cancellationsReason" tabindex="-1" role="dialog" aria-labelledby="cancellationsReasonModalLabel Label" aria-modal="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold text-center" id="decreaseStockModalLabel">Cancel Appoinment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
				</button>
			</div>
			<form method="POST" enctype="multipart/form-data" action="{{ route('cancelAppointment') }}" id="cancelAppointment">
				@csrf
				<input type="hidden" name="cancel_appointment_id" id="cancel_appointment_id"> 
				<div class="modal-body">
					<div class="form-group w-100">
						<label>Cancellation reason</label>
						<select class="form-control" name="cancel_appointment_reason" id="cancel_appointment_reason">
							@if(!empty($CancellationReasons))
								@foreach($CancellationReasons as $CancellationReasonsData)
									<option value="{{ $CancellationReasonsData['reason'] }}">{{ $CancellationReasonsData['reason'] }}</option>
								@endforeach
							@else
								<option value="Appointment made by mistake">Appointment made by mistake</option>
							@endif
						</select>
					</div>
					
					<div class="form-group w-100">
						<input type="checkbox" name="cancel_appointment_notify" id="cancel_appointment_notify">
						<label for="cancel_appointment_notify">Notify about cancellation</label>
						<p>Send a message informing to customer that appointment was cancelled.</p>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<div class="ml-auto">
						<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary font-weight-bold" id="cancelAppointmentBtn">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade p-0" id="editAppointmentNotes" tabindex="-1" role="dialog" aria-labelledby="editAppointmentNotesModalLabel Label" aria-modal="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold text-center" id="decreaseStockModalLabel">Edit Appointment Notes</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
				</button>
			</div>
			<form method="POST" enctype="multipart/form-data" action="{{ route('editAppointmentNote') }}" id="editAppointmentNote">
				@csrf
				<input type="hidden" name="edit_appointment_id" id="edit_appointment_id"> 
				<div class="modal-body">
					<div class="form-group w-100">
						<label>Appointment notes</label>
						<textarea class="form-control" name="appointment_notes" id="appointment_notes" rows="5">{{ ($Appointment['appointment_notes']) ? $Appointment['appointment_notes'] : '' }}</textarea>
					</div>
					
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<div class="ml-auto">
						<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary font-weight-bold" id="editAppointmentNotesBtn">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="container-fluid p-0">
	<div class="my-custom-body-wrapper">
		<div class="my-custom-header">
			<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
				<h2 class="m-auto font-weight-bolder">View Appointment</h2>
				<span class="d-flex">
					<a class="cursor-pointer m-0 px-6" href="{{ route('calander') }}"><i class="text-dark fa fa-times icon-lg"></i></a>
				</span>
			</div>
		</div>
		<form method="POST" id="createAppointment" action="{{ route('createNewAppointment') }}">
		@csrf
		
		<input type="hidden" id="clientId" name="clientId" value="0">
		<input type="hidden" id="appointmentStatusAction" value="{{ route('appointmentStatus') }}">
		<input type="hidden" id="appointmentNoshowAction" value="{{ route('appointmentNoshow') }}">
		
		<div class="my-custom-body">
			<div class="container-fluid ">
				<div class="row m-0">
					<div class="col-12 col-sm-8" style="max-height: 100vh;overflow-y: scroll; position: relative;">
						<div class="container p-12 position-relative">
							<div class="client-apoinments-list mb-6">
								<div class="">
									<h3 class="font-weight-bolder mb-8">
									{{ ($Appointment['appointment_date']) ? date("l, d M Y",strtotime($Appointment['appointment_date'])) : '' }}
									</h3>
									
									@if(!empty($ClientServices))
										@foreach($ClientServices as $ClientServiceData)
									<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
										<div class="d-flex flex-column align-items-cente py-2 w-75">
											<h6 class="text-dark-75 font-weight-bolder font-size-lg">{{ $ClientServiceData['start_time'] }} <span class="ml-8">{{ $ClientServiceData['service_name'] }}</span></h6>
											<span class="text-muted font-weight-bold  ml-26">{{ $ClientServiceData['duration'] }} with {{-- <i class="fa fa-heart text-danger"></i> --}} {{ $ClientServiceData['staff_name'] }}
											</span>
										</div>
										<h6 class="font-weight-bolder py-4">CA ${{ $ClientServiceData['special_price'] }}</h6>
									</div>
										@endforeach
									@endif
								</div>
							</div>
							
							<div class="apoinment-view-total d-flex justify-content-between w-100 ">
								<div class="text-dark font-weight-bolder ml-26">{{ $TotalDurationString }}
								</div>
								<h6 class="font-weight-bolder py-4">CA ${{ $Appointment['total_amount'] }}</h6>
							</div>

							@if(!empty($Appointment['appointment_notes']))
								<div class="">
									<h3 class="font-weight-bolder mb-8">Appointment note</h3>

									<span>{{ ($Appointment['appointment_notes']) ? $Appointment['appointment_notes'] : '' }}</span>
								</div>
							@endif
							
							<!--div class="appoinment-history mt-10">
								<h2 class="mb-4">Appointment history</h2>
								<p class="mb-1 font-weight-bold font-size-lg">
									<a href="javascrip:void(0)" data-toggle="modal" data-target="#reminderSmsModal"
										class="text-blue">Reminder SMS</a>
									sent at Tue, 5 Jan 2021 at 8:00am
								</p>
								<p class="mb-1 font-weight-bold font-size-lg">
									<a href="javascrip:void(0)" data-toggle="modal" data-target="#reminderEmailModal"
										class="text-blue">Reminder Email</a>
									sent at Tue, 5 Jan 2021 at 8:00am
								</p>
								<p class="mb-1 font-weight-bold font-size-lg">
									<a href="javascrip:void(0)" data-toggle="modal" data-target="#confirmationEmailModal"
										class="text-blue">Confirmation Email</a>
									sent at Sat, 2 Jan 2021 at 7:15pm
								</p>
								<p class="mb-1 font-weight-bold font-size-lg">Booked on ScheduleDown by Jayesh, reference
									2016CD09 at Sat, 2 Jan 2021 at 7:12pm</p>
							</div-->
						</div>
						<div class="side-overlay">
							<div id="dismiss">
								<i class="la la-close" style="font-size: 18px;"></i>
								<span
									style="display: block; margin-top: 10px; color: #fff; font-size: 14px; text-transform: uppercase; font-weight: 700; text-shadow: 0 0 4px #67768c;">CLICK TO CLOSE</span>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-4 p-0">
						<div class="d-flex align-items-center border-bottom p-6 customer-data" id="sidebarCollapse">
							<div class="symbol symbol-60 symbol-xxl-100 mr-5 align-self-start align-self-xxl-center">
								<div class="symbol-label rounded-circle"
									style="background-image:url('{{ asset('assets/media/users/300_13.jpg') }}')">
								</div>
							</div>
							<div>
								@if(!empty($ClientInfo))
									<a href="#" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
										{{ $ClientInfo->firstname }} {{ $ClientInfo->lastname }}<span class="fonter-weight-bolder">*</span>
									</a>
									<div class="text-muted">+{{ $ClientInfo->mo_country_code }} {{ $ClientInfo->mobileno }}<span class="font-weight-bolder">*</span></div>
									<div class="text-muted">{{ $ClientInfo->email }}
									</div>
								@else
									<a href="javascript:;" class="font-weight-bold font-size-h5 text-dark-75 text-hover-primary">
										Walk In <span class="fonter-weight-bolder">*</span>
									</a>	
								@endif	
							</div>
							
							@php $ClientID = (isset($ClientInfo->id) && $ClientInfo->id) ? $ClientInfo->id : '' @endphp
							
							<i class="text-dark fa fa-chevron-right ml-auto viewClient"></i>
							@if(isset($ClientID) && $ClientID != '')
							<div class="dropdown dropdown-inline viewClientInfo" style="display:none;margin-left: 185px;">
								<a href="#" class="btn btn-clean btn-hover-primary btn-sm btn-icon"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="ki ki-bold-more-hor"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right" style="z-index: 1000;">
									<ul class="navi navi-hover">
										<li class="navi-item">
											<!-- url('/view/'.$ClientID.'/client') -->
											<a href="{{ route('viewClient', ['id' => $ClientID]) }}" class="cursor-pointer navi-link">
												<span class="navi-text">Edit client details</span>
											</a>
										</li>
										<li class="navi-item">
											<a data-toggle="modal" data-target="#blockClientModal"
												class="cursor-pointer navi-link clickBlockClient" data-client_id="{{ $ClientID }}">
												<span class="navi-text">Block client</span>
											</a>
										</li>
										@if(!empty($InvoiceInfo))
											@if($InvoiceInfo->invoice_status != 1) 
												<!-- <li class="navi-item">
													<a class="cursor-pointer navi-link removeClientFromAppoin">
														<span class="navi-text">Remove from appointment</span>
													</a>
												</li> -->
											@endif
										@else
											<!-- <li class="navi-item">
												<a class="cursor-pointer navi-link removeClientFromAppoin">
													<span class="navi-text">Remove from appointment</span>
												</a>
											</li> -->	
										@endif	
									</ul>
								</div>
							</div>
							@endif
						</div>
						
						<div class="customer-bottom">
							<div class="customer-bottom-outer">
									@if($Appointment['is_noshow'] == 0)
										@if(!empty($InvoiceInfo))
											@if($InvoiceInfo->invoice_status != 1) 
											<div class="bg-secondary d-flex justify-content-center py-4">
												<div class="w-50 form-group font-weight-bold m-0">
													<select class="form-control" id="changeAppointmentStatus" name="changeAppointmentStatus" data-appointmentID="{{ $Appointment['id'] }}">
														<option value="0" @if($Appointment['appointment_status'] == 0) selected @endif>New Appointment</option>
														<option value="1" @if($Appointment['appointment_status'] == 1) selected @endif>Confirmed</option>
														<option value="2" @if($Appointment['appointment_status'] == 2) selected @endif>Arrived</option>
														<option value="3" @if($Appointment['appointment_status'] == 3) selected @endif>Started</option>
													</select>
												</div>
											</div>
											@endif
										@else
											<div class="bg-secondary d-flex justify-content-center py-4">
												<div class="w-50 form-group font-weight-bold m-0">
													<select class="form-control" id="changeAppointmentStatus" name="changeAppointmentStatus" data-appointmentID="{{ $Appointment['id'] }}">
														<option value="0" @if($Appointment['appointment_status'] == 0) selected @endif>New Appointment</option>
														<option value="1" @if($Appointment['appointment_status'] == 1) selected @endif>Confirmed</option>
														<option value="2" @if($Appointment['appointment_status'] == 2) selected @endif>Arrived</option>
														<option value="3" @if($Appointment['appointment_status'] == 3) selected @endif>Started</option>
													</select>
												</div>
											</div>
										@endif	
									@else
										<div class="bg-secondary d-flex justify-content-center py-4">
											<h3>No show</h3>
										</div>
									@endif
								<div class="view-appoinment-content" style="min-height: 48vh;">
									@if(!empty($InvoiceInfo))
										@if($InvoiceInfo->invoice_status == 1) 	
											<div class="px-20 py-8 text-center">
												<i class="far fa-check-circle" style="color:rgb(14, 230, 140);font-size: 82px;"></i>
											</div>
											<h4 class="px-10 text-center">
												Appointment Completed
											</h4>
											<p class="px-10 text-center">
											Full payment received on {{ date("l, d M Y",strtotime($InvoiceInfo->payment_date)) }} with invoice <a href="{{ route('viewInvoice',['id' => $InvoiceInfo->id]) }}">{{ $InvoiceInfo->id }}</a>
											</p>
										@elseif($InvoiceInfo->invoice_status == 0)
											<div class="px-20 py-8 text-center">
												<svg viewBox="0 0 70 30" xmlns="http://www.w3.org/2000/svg">
												<g fill="#101928" fill-rule="evenodd"><path d="M15.0949345 23L21 18.660445V2c0-.55228475-.4477153-1-1-1H2c-.55228475 0-1 .44771525-1 1v20c0 .5522847.44771525 1 1 1h13.0949345zM2 0h18c1.1045695 0 2 .8954305 2 2v17.1665492L15.4228639 24H2c-1.1045695 0-2-.8954305-2-2V2C0 .8954305.8954305 0 2 0z" fill-rule="nonzero"></path><path d="M1 20v2c0 .5522847.44771525 1 1 1h17c.5522847 0 1-.4477153 1-1v-2H1zm-1-1h21v3c0 1.1045695-.8954305 2-2 2H2c-1.1045695 0-2-.8954305-2-2v-3z" fill-rule="nonzero"></path><path class="white" d="M26 18c0 4.4174545-3.5825455 8-8 8-4.41890909 0-8-3.5825455-8-8 0-4.41745455 3.58109091-8 8-8 4.4174545 0 8 3.58254545 8 8z"></path><path d="M25 18c0-3.8651698-3.1348302-7-7-7-3.8660939 0-7 3.13429971-7 7 0 3.8657003 3.1339061 7 7 7 3.8651698 0 7-3.1348302 7-7zm1 0c0 4.4174545-3.5825455 8-8 8-4.41890909 0-8-3.5825455-8-8 0-4.41745455 3.58109091-8 8-8 4.4174545 0 8 3.58254545 8 8zM3.5 5c-.27614237 0-.5-.22385763-.5-.5 0-.27614237.22385763-.5.5-.5h3c.27614237 0 .5.22385763.5.5 0 .27614237-.22385763.5-.5.5h-3zM9.5 5c-.27614237 0-.5-.22385763-.5-.5 0-.27614237.22385763-.5.5-.5h3c.2761424 0 .5.22385763.5.5 0 .27614237-.2238576.5-.5.5h-3zM15.5 5c-.2761424 0-.5-.22385763-.5-.5 0-.27614237.2238576-.5.5-.5h3c.2761424 0 .5.22385763.5.5 0 .27614237-.2238576.5-.5.5h-3zM3.5 7c-.27614237 0-.5-.22385763-.5-.5 0-.27614237.22385763-.5.5-.5h3c.27614237 0 .5.22385763.5.5 0 .27614237-.22385763.5-.5.5h-3zM3.5 10c-.27614237 0-.5-.22385763-.5-.5 0-.27614237.22385763-.5.5-.5h8c.2761424 0 .5.22385763.5.5 0 .27614237-.2238576.5-.5.5h-8zM3.5 12c-.27614237 0-.5-.2238576-.5-.5s.22385763-.5.5-.5h5c.27614237 0 .5.2238576.5.5s-.22385763.5-.5.5h-5zM3.5 15c-.27614237 0-.5-.2238576-.5-.5s.22385763-.5.5-.5h4c.27614237 0 .5.2238576.5.5s-.22385763.5-.5.5h-4zM3.5 17c-.27614237 0-.5-.2238576-.5-.5s.22385763-.5.5-.5h4c.27614237 0 .5.2238576.5.5s-.22385763.5-.5.5h-4zM9.5 7c-.27614237 0-.5-.22385763-.5-.5 0-.27614237.22385763-.5.5-.5h3c.2761424 0 .5.22385763.5.5 0 .27614237-.2238576.5-.5.5h-3zM15.5 7c-.2761424 0-.5-.22385763-.5-.5 0-.27614237.2238576-.5.5-.5h3c.2761424 0 .5.22385763.5.5 0 .27614237-.2238576.5-.5.5h-3z" fill-rule="nonzero"></path><g fill-rule="nonzero"><path d="M20.32360548 14.86878174c.19526215-.19526215.51184464-.19526214.70710678 0 .19526214.19526214.19526215.51184463 0 .70710678l-5.45482374 5.45482374c-.19526214.19526215-.51184464.19526215-.70710678 0-.19526215-.19526214-.19526215-.51184464 0-.70710678l5.45482374-5.45482374z"></path><path d="M21.03071226 20.32360548c.19526215.19526214.19526215.51184464 0 .70710678-.19526214.19526215-.51184464.19526215-.70710678 0l-5.45482374-5.45482374c-.19526215-.19526215-.19526214-.51184464 0-.70710678.19526214-.19526214.51184463-.19526215.70710678 0l5.45482374 5.45482374z"></path></g></g></svg>
											</div>
											<h4 class="px-10 text-center">
												Unpaid Invoice 
												<a href="{{ route('viewInvoice',['id' => $InvoiceInfo->id]) }}">{{ $InvoiceInfo->id }}</a>
											</h4>
										@endif
									@else
										<h3 class="p-4 text-center">No invoice issued yet</h3>	
									@endif	
								</div>
								<div class="view-appoinment-footer border-top w-100 py-6">
									<h5 class="text-center font-weight-bolder mb-4">Total: CA ${{ $Appointment['total_amount'] }} ({{ $TotalDurationString }})</h5>
									<div class="buttons d-flex justify-content-between">
										<div class="btn-group dropup w-100 mx-4">
											<button type="button" class="btn btn-white dropdown-toggle"
												data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
												More Option
											</button>
											<div class="dropdown-menu dropdown-menu-sm dropdown-menu-center">
												<!--begin::Navigation-->
												<ul class="text-center navi flex-column navi-hover py-2">
													@if($Appointment['is_noshow'] == 0)
														@if(!empty($InvoiceInfo))
															@if($InvoiceInfo->invoice_status == 1) 
																<li class="navi-item">
																	<a href="#" data-toggle="modal" data-target="#editAppointmentNotes" class="navi-link appointmentNotesLink" data-appointmentID="{{ $Appointment['id'] }}">
																		<span class="navi-text text-danger">Edit Notes</span>
																	</a>
																</li>	
															@elseif($InvoiceInfo->invoice_status == 0)
																<li class="navi-item">
																	<a href="#" data-toggle="modal" data-target="#editAppointmentNotes" class="navi-link appointmentNotesLink" data-appointmentID="{{ $Appointment['id'] }}">
																		<span class="navi-text text-danger">Edit Notes</span>
																	</a>
																</li>
																<li class="navi-item">
																	<a href="{{ route('viewInvoice',['id' => $InvoiceInfo->id]) }}" class="navi-link appointmentNotesLink">
																		<span class="navi-text">View Invoice</span>
																	</a>
																</li>
															@endif
														@else
															<li class="navi-item">
																<a href="{{ route('editAppointment', ['id' => $appointmentId]) }}" class="navi-link">
																	<span class="navi-text">Edit Appoinment</span>
																</a>
															</li>
															<li class="navi-item">
																<a href="{{ route('calander') }}?reschedule={{ $appointmentId }}" class="navi-link">
																	<span class="navi-text">Reschedule</span>
																</a>
															</li>
															<li class="navi-item">
																@if($canCancelAppointment == 1)
																	<a href="javascript:;" class="navi-link not_cancel">
																		<span class="navi-text text-danger">Cancel</span>
																	</a>
																@else	
																	<a href="#" data-toggle="modal" data-target="#cancellationsReason" class="navi-link cancelAppointmentLink" data-appointmentID="{{ $Appointment['id'] }}">
																		<span class="navi-text text-danger">Cancel</span>
																	</a>
																@endif	
															</li>
															<li class="navi-item">
																<a href="javascript:;" class="navi-link markAsNoShow" data-appointmentID="{{ $Appointment['id'] }}" data-noShowStatus="1">
																	<span class="navi-text text-danger">No Show</span>
																</a>
															</li>
														@endif	
													@else
														<li class="navi-item">
															<a href="#" class="navi-link">
																<span class="navi-text">Rebook</span>
															</a>
														</li>
														<li class="navi-item">
															<a href="#" data-toggle="modal" data-target="#editAppointmentNotes" class="navi-link appointmentNotesLink" data-appointmentID="{{ $Appointment['id'] }}">
																<span class="navi-text text-danger">Edit Notes</span>
															</a>
														</li>
														<li class="navi-item">
															<a href="javascript:;" class="navi-link markAsUndoNoShow" data-appointmentID="{{ $Appointment['id'] }}" data-noShowStatus="0">
																<span class="navi-text text-danger">Undo No Show</span>
															</a>
														</li>	
													@endif
												</ul>
												<!--end::Navigation-->
											</div>
										</div>
										
										@if(!empty($InvoiceInfo))
											@if($InvoiceInfo->invoice_status == 1) 	
												<button type="button" class="btn btn-lh btn-primary w-100 mx-4" onclick="window.location.href='{{ route('viewInvoice',['id' => $InvoiceInfo->id]) }}'">View Invoice</button>	
											@elseif($InvoiceInfo->invoice_status == 0)
												<button type="button" class="btn btn-lh btn-primary w-100 mx-4" onclick="window.location.href='{{ route('applyPayment',['id' => $InvoiceInfo->id]) }}'">Pay Now</button>	
											@else	
												<button type="button" class="btn btn-lh btn-primary w-100 mx-4" onclick="window.location.href='{{ route('checkoutAppointment',['locationId' => $Appointment['location_id'],'type' => 'appointment','id' => $appointmentId]) }}'">Checkout</button>	
											@endif
										@else
											<button type="button" class="btn btn-lh btn-primary w-100 mx-4" onclick="window.location.href='{{ route('checkoutAppointment',['locationId' => $Appointment['location_id'],'type' => 'appointment','id' => $appointmentId]) }}'">Checkout</button>	
										@endif	
									</div>
								</div>
							</div>
							<div id="sidebar" class="bg-white">
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
											<a style="margin:0px;padding:0 6px;font-size: 13px;" class="nav-link active"
												data-toggle="tab" href="#appointments">Appointments
												({{ count($PreviousServices) }})</a>
										</li>
										<li class="nav-item">
											<a style="margin:0px;padding:0 6px;font-size: 13px;" class="nav-link"
												data-toggle="tab" href="#consultationforms">Products ({{ count($soldProduct) }})</a>
										</li>
										<li class="nav-item">
											<a style="margin:0px;padding:0 6px;font-size: 13px;" class="nav-link"
												data-toggle="tab" href="#products">Invoices ({{ count($clientInvoices) }})</a>
										</li>
										<li class="nav-item">
											<a style="margin:0px;padding:0 6px;font-size: 13px;" class="nav-link"
												data-toggle="tab" href="#invoices">Info</a>
										</li>
									</ul>
									<div class="tab-content mt-5" id="myTabContent">
										<div class="tab-pane fade show active" id="appointments" role="tabpanel"
											aria-labelledby="appointments">
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
										<div class="tab-pane fade" id="consultationforms" role="tabpanel"
											aria-labelledby="consultationforms">
											<?php echo $ClientProducts ?>
										</div>
										<div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products">
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
										<div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices">
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
<script src="{{ asset('js/add-appointment.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>
<script>
$(document).ready(function () {
	
	$(".not_cancel").click( function() {
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
		toastr.error("Past appointments can't be cancelled, use no show action instead.");
	});	
	
	$('#dismiss, .side-overlay').on('click', function () {
		$('#sidebar').removeClass('active');
		$('.side-overlay').removeClass('active');
		
		$(".viewClient").show();
		$(".viewClientInfo").hide();
	});

	$('#sidebarCollapse').on('click', function () {
		$('#sidebar').addClass('active');
		$('.side-overlay').addClass('active');
		
		$(".viewClient").hide();
		$(".viewClientInfo").show();
	});
});
</script>
@endsection