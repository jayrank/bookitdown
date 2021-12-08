{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
#salesAppointmentList_filter{
	display:none;
}
.selected-locations::placeholder {
  	width:  85%;
}
</style>
@endsection

@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class="container">
			<div class="row">
				<div class="col-lg-6">
					<!--begin::Card-->
					<div class="_3ARuHb">
						<h3 class="card-title">Appointment notifications</h3>
						<h3 class="font-size-lg text-dark font-weight-bold mb-6">Get notified about your
							own appointments only, or appointments booked with all staff members. Choose
							the types of appointment activity to get notified about. </h3>
						<!-- <a href="https://support.ScheduleDown .com/hc/en-us/articles/115001917249-Staff-Notifications"
							target="_blank">Learn more about notifications.</a> -->
					</div>
				</div>
				<div class="col-lg-6">
					<!--begin::Card-->
					<div class="card card-custom gutter-b ">
						<form class="form notification-settings-form" method="post" action="{{ route('storeUserNotificationSettings') }}">
							@csrf
							<div class="card-body">
								<div class="mb-15">
									<div class="form-group">
										<label for="exampleSelect1">Notify me about
											<span class="text-danger">*</span></label>
											<select class="form-control notify_about" id="exampleSelect1" name="notify_about">
												<option value="all_staff"
													@if(!empty($userNotificationSettings))
														@if($userNotificationSettings->notify_about == 'all_staff')
															selected
														@endif
													@endif
												>Appointments booked with all staff</option>
												<option value="only_me"
													@if(!empty($userNotificationSettings))
														@if($userNotificationSettings->notify_about == 'only_me')
															selected
														@endif
													@endif
												>Appointments booked with me only</option>
											</select>
									</div>
									<div class="form-group locations" style="position: relative;">
										<label>Booked at</label>

										@php
											$locationNameString = '';
											$locationIds = [];

											if(!empty($notificationSettingsLocations)) {
												foreach($notificationSettingsLocations as $key => $value) {
													$locationNameString .= $value->location_name.', ';
													$locationIds[] = $value->location_id;
												}
											}

											$locationNameString = rtrim($locationNameString,', ');
										@endphp

										<input type="text" readonly class="form-control form-control-lg selected-locations"
											placeholder="{{ $locationNameString }}" data-toggle="modal"
											data-target="#bookedAreModal" style="cursor: pointer;">
										<a href="" class="chng_popup" data-toggle="modal"
											data-target="#bookedAreModal">
											Change</a>
									</div>
									<div class="form-group">
										<div class="checkbox-list">
											<label class="checkbox">
												<input type="checkbox" value="1" name="new_appointments"
													@if(!empty($userNotificationSettings))
														@if($userNotificationSettings->new_appointments)
															checked
														@endif
													@endif
												>
												<span></span>New Appointments & marketplace sales
											</label>
											<label class="checkbox">
												<input type="checkbox" value="1" name="reschedules"
													@if(!empty($userNotificationSettings))
														@if($userNotificationSettings->reschedules)
															checked
														@endif
													@endif
												>
												<span></span>Reschedules
											</label>
											<label class="checkbox">
												<input type="checkbox" value="1" name="cancellations"
													@if(!empty($userNotificationSettings))
														@if($userNotificationSettings->cancellations)
															checked
														@endif
													@endif
												>
												<span></span>Cancellations
											</label>
											<label class="checkbox">
												<input type="checkbox" value="1" name="no_shows"
													@if(!empty($userNotificationSettings))
														@if($userNotificationSettings->no_shows)
															checked
														@endif
													@endif
												>
												<span></span>No Shows
											</label>
											<label class="checkbox">
												<input type="checkbox" value="1" name="confirmations"
													@if(!empty($userNotificationSettings))
														@if($userNotificationSettings->confirmations)
															checked
														@endif
													@endif
												>
												<span></span>Confirmations
											</label>
											<label class="checkbox">
												<input type="checkbox" value="1" name="arrivals"
													@if(!empty($userNotificationSettings))
														@if($userNotificationSettings->arrivals)
															checked
														@endif
													@endif
												>
												<span></span>Arrivals
											</label>
											<label class="checkbox">
												<input type="checkbox" value="1" name="started"
													@if(!empty($userNotificationSettings))
														@if($userNotificationSettings->started)
															checked
														@endif
													@endif
												>
												<span></span>Started
											</label>
											<label class="checkbox">
												<input type="checkbox" value="1" name="tips"
													@if(!empty($userNotificationSettings))
														@if($userNotificationSettings->tips)
															checked
														@endif
													@endif
												>
												<span></span>Tips
											</label>
										</div>
									</div>
								</div>
							</div>

							<div class="col-lg-12 text-right">
								<button type="submit" class="btn btn-primary mr-2 black">Save</button>
							</div>

							<!-- Modal-->
							<div class="modal fade" id="bookedAreModal" tabindex="-1" role="dialog" aria-labelledby="bookedAreModalLabel"
								aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="bookedAreModalLabel">Select Locations</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<i aria-hidden="true" class="ki ki-close"></i>
											</button>
										</div>
										<div class="modal-body my_sett">
											<div class="form-group">
												<div class="checkbox-list popup_check">
													<label class="checkbox select-all-checkbox">
														<input type="checkbox" id="select-all" @if(!empty($locationIds) && is_array($locationIds) && !empty($location) )@if($location->count() == count($locationIds)) checked @endif @endif>
														<span></span><p>Select all</p> <p class="round_tet">{{ $location->count() }}</p>
													</label>
														@if($location->isNotEmpty())
															@foreach($location as $key => $value)
																<label class="checkbox">
																	<input type="checkbox" name="location[]" value="{{ $value->id }}" class="location-checkbox" @if(in_array($value->id, $locationIds)) checked @endif>
																	<span></span><p class="location-name">{{ $value->location_name }}</p>
																</label>
															@endforeach
														@endif

												</div>
											</div>
										</div>
										<div class="modal-footer text-center my_sett">
											<button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal">Select</button>

										</div>
									</div>
								</div>
							</div>

						</form>
						<!--end::Form-->
					</div>
					<!--end::Card-->
				</div>
			</div>
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>

@endsection

{{-- Scripts Section --}}
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('click', '#select-all', function(){
			if($(this).is(':checked')) {
				$('.location-checkbox').prop('checked', true);
			} else {
				$('.location-checkbox').prop('checked', false);
			}
			updateLocationName();
		});

		$(document).on('change','.location-checkbox', function(){
			updateLocationName();
			
			allChecked = true;
			$('.location-checkbox').each(function(){
				if(!$(this).is(':checked')) {
					allChecked = false;
					return false;
				}
			});

			if(allChecked) {
				$('#select-all').prop('checked', true);
			} else {
				$('#select-all').prop('checked', false);
			}
		});

		toggleLocation();
		$(document).on('change','.notify_about', function(){
			toggleLocation();
		});

		$(document).on('submit', '.notification-settings-form', function(e){
			e.preventDefault();

			var self = $(this);
			var url = self.attr('action');

			$.ajax({
				url: url,
				type: 'POST',
				dataType: 'JSON',
				data: self.serialize(),
				success: function(response) {
					if(response.status) {
						responseMessages('success',response.msg);
					} else {
						responseMessages('error', response.msg);
					}
				},
				error: function(response) {
					responseMessages('error','Something went wrong.');
				},
				complete: function(response) {

				}
			})
		});
	});

	function updateLocationName() {
		locationName = '';
		$('.location-checkbox').each(function(){
			if($(this).is(':checked')) {
				locationName += $(this).siblings('.location-name').text()+', ';
			}
		});
		locationName = locationName.slice(0, -2);
		$('.selected-locations').attr('placeholder', locationName);
	}

	function toggleLocation() {
		if($('.notify_about').val() == 'only_me') {
			$('.locations').hide();
		} else if($('.notify_about').val() == 'all_staff') {
			$('.locations').show();
		}
	}
</script>
@endsection