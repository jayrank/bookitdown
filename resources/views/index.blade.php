{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

@endsection

@section('content')


<!--begin::Header-->
	
	<!--end::Header-->
	<!--begin::Content-->
	<div class="content bg-content" id="kt_content">
		<!--begin::Tabs-->
		<!-- <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">

		</div> -->
		<!--end::Tabs-->
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Dashboard-->
				<!--begin::Row-->
				<div class="row">
					<div class="col-lg-6 col-xxl-4">
						<!--begin::Stats Widget 11-->
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Body-->
							<div class="card-body p-0">
								<div
									class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
									<span class="symbol symbol-50 symbol-light-success mr-2">
										<span class="symbol-label">
											<span class="svg-icon svg-icon-xl svg-icon-success">
												<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
												<svg xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
													height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none"
														fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<rect fill="#000000" x="4" y="4" width="7"
															height="7" rx="1.5" />
														<path
															d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
															fill="#000000" opacity="0.3" />
													</g>
												</svg>
												<!--end::Svg Icon-->
											</span>
										</span>
									</span>
									<div class="d-flex flex-column text-right">
										<span class="text-dark-75 font-weight-bolder font-size-h3">${{ round($intotal) }}</span>
										<span class="text-dark font-weight-bold mt-2">Last seven Days Income</span>
									</div>
								</div>
								<div id="kt_stats_widget_11_chart" class="card-rounded-bottom" 
									data-color="success" style="height: 350px"></div>
							</div>
							<!--end::Body-->
						</div>
					</div>
						<!--end::Stats Widget 11-->
						<!--begin::Stats Widget 12-->
					<div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">
						<div class="card card-custom card-stretch gutter-b">
							<div class="card-body p-0">
								<div
									class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
									<span class="symbol symbol-50 symbol-light-primary mr-2">
										<span class="symbol-label">
											<span class="svg-icon svg-icon-xl svg-icon-primary">
												<svg xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
													height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none"
														fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<rect fill="#000000" x="4" y="4" width="7"
															height="7" rx="1.5" />
														<path
															d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
															fill="#000000" opacity="0.3" />
													</g>
												</svg>
											</span>
										</span>
									</span>
									<div class="d-flex flex-column text-right">
										<span
											class="text-dark-75 font-weight-bolder font-size-h3">{{ $totalAppointments }}</span>
										<span class="text-dark font-weight-bold mt-2">Upcoming Appointments</span>
									</div>
								</div>
								<div id="kt_stats_widget_12_chart" class="card-rounded-bottom"
									data-color="primary" style="height: 350px"></div>
							</div>
						</div>
						
						<!--end::Stats Widget 12-->
					</div>
					<div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">
						<!--begin::List Widget 1-->
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Header-->
							<div class="card-header border-0 pt-5">
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label font-weight-bolder text-dark">Appointments
										Activity</span>
									<span class="text-dark mt-3 font-weight-bold font-size-sm">Pending 10
										Appoinments</span>
								</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body pt-8">
								<!--begin::Item-->
								<div class="mb-10">
									<div class="row">
										<div class="card-body py-2 px-4">
											<table class="table table-hover" id="apoinments">
												<tbody id="sortable" class="sortable">
													@foreach($ap as $value)
														@php
															$date = Carbon\Carbon::parse($value->appointment_date)->format('d M');
															$time = Carbon\Carbon::parse($value->start_time)->format('h:i A.');
															$staff = !empty($value->staff_user_id) ? App\Models\User::where('id',$value->staff_user_id)->first() : '';
														@endphp
													<tr class="open-view-appointment" data-href="{{ route('viewAppointment', ['id' => Crypt::encryptString($value->appointment_id)]) }}">
														<td><h6 class="font-weight-bolder">{{ $date }}</h6></td>
														<td>
															<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
																<div class="d-flex flex-column align-items-cente py-2 w-75">
																	<h6 class="text-muted font-weight-bold">
																		{{ $time }} <a class="text-blue"
																			href="{{ route('viewAppointment', ['id' => Crypt::encryptString($value->appointment_id)]) }}" target="_blank">New</a>
																	</h6>
																	<a href="{{ route('viewAppointment', ['id' => Crypt::encryptString($value->appointment_id)]) }}" target="_blank"
																		class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg mb-1">{{ $value->service_name }}</a>
																	<span class="text-muted font-weight-bold">
																		@php 
																			if($value->duration <= 0) {
																				echo '00h 00min';
																			} else {  
																				if(sprintf("%02d",floor($value->duration / 60)) > 0) {
																					echo sprintf("%02d",floor($value->duration / 60)).'h ';
																				} 
																		
																				if(sprintf("%02d",str_pad(($value->duration % 60), 2, "0", STR_PAD_LEFT)) > 0){
																					echo sprintf("%02d",str_pad(($value->duration % 60), 2, "0", STR_PAD_LEFT)). "min";
																				}
																			}
										  								@endphp with 
																		{{ !empty($staff) ? $staff->first_name : '' }} {{ !empty($staff) ? $staff->last_name : '' }}
																	</span>
																</div>
																<h6 class="font-weight-bolder py-4">
																	CA
																	${{ $value->special_price }}
																</h6>
															</div>
														</td>
													</tr>
													@endforeach
												</tbody>
											</table>
											@php echo $ap->render(); @endphp
										</div>
									</div>
								</div>
								<!--end::Item-->
							</div>
							<!--end::Body-->
						</div>
						<!--end::List Widget 1-->
					</div>

					<!--begin::List Widget 3-->
					<div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">
						<!--begin::List Widget 1-->
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Header-->
							<div class="card-header border-0 pt-5">
								<h3 class="card-title align-items-start flex-column">
									<span class="card-label font-weight-bolder text-dark">Today's Next Appointments</span>
								</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body pt-8">
								<!--begin::Item-->
								<div class="mb-10">
									<div class="row">
										<div class="card-body py-2 px-4">
											<table class="table table-hover" id="apoinments">
												<tbody id="sortable" class="sortable">
													@foreach($appo as $value)
														@php
															$date = Carbon\Carbon::parse($value->appointment_date)->format('d M');
															$time = Carbon\Carbon::parse($value->start_time)->format('h:i A.');
															$staff = !empty($value->staff_user_id) ? App\Models\User::where('id',$value->staff_user_id)->first() : '';
														@endphp
													<tr class="open-view-appointment" data-href="{{ route('viewAppointment', ['id' => Crypt::encryptString($value->appointment_id)]) }}">
														<td><h6 class="font-weight-bolder">{{ $date }}</h6></td>
														<td>
															<div class="d-flex flex-wrap align-items-center justify-content-between w-100">
																<div class="d-flex flex-column align-items-cente py-2 w-75">
																	<h6 class="text-muted font-weight-bold">
																		{{ $time }} <a class="text-blue"
																			href="{{ route('viewAppointment', ['id' => Crypt::encryptString($value->appointment_id)]) }}" target="_blank">New</a>
																	</h6>
																	<a href="{{ route('viewAppointment', ['id' => Crypt::encryptString($value->appointment_id)]) }}" target="_blank"
																		class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg mb-1">{{ $value->service_name }}</a>
																	<span class="text-muted font-weight-bold">
																		@php 
																			if($value->duration <= 0) {
																				echo '00h 00min';
																			} else {  
																				if(sprintf("%02d",floor($value->duration / 60)) > 0) {
																					echo sprintf("%02d",floor($value->duration / 60)).'h ';
																				} 
																					
																				if(sprintf("%02d",str_pad(($value->duration % 60), 2, "0", STR_PAD_LEFT)) > 0) {
																					echo sprintf("%02d",str_pad(($value->duration % 60), 2, "0", STR_PAD_LEFT)). "min";
																				}
																			}
										  								@endphp with 
																		{{ !empty($staff) ? $staff->first_name : '' }} {{ !empty($staff) ? $staff->last_name : '' }}
																	</span>
																</div>
																<h6 class="font-weight-bolder py-4">
																	CA
																	${{ $value->special_price }}
																</h6>
															</div>
														</td>
													</tr>
													@endforeach
												</tbody>
											</table>
										</div>
									</div>
								</div>
								<!--end::Item-->
							</div>
							<!--end::Body-->
						</div>
						<!--end::List Widget 1-->
					</div>
					<!--end::List Widget 3-->
					<div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">
						<!--begin::List Widget 3-->
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Header-->
							<div class="card-header border-0">
								<h3 class="card-title font-weight-bolder text-dark">Top Services</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body pt-2">
								<!--begin::Item-->
								<div class="d-flex align-items-center">
									<table class="table table-hover">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th scope="col">Service</th>
												<th scope="col">This month </th>
												<th scope="col">Last month</th>
											</tr>
										</thead>
										<tbody>

											@if(!empty($ser_ap))
												@foreach($ser_ap as $key => $value)
													<tr>
														<th scope="row">{{ ($key + 1) }}</th>
														<td>{{ $value['service_name'] }}</td>
														<td>{{ $value['total'] }}</td>
														<td>{{ !empty($ser_prap[ $key ]) ? $ser_prap[ $key ]['total'] : 'NA' }}</td>
													</tr>
												@endforeach
											@endif
										</tbody>
									</table>
								</div>
								<!--end:Item-->

							</div>
							<!--end::Body-->
						</div>
						<!--end::List Widget 3-->
					</div>
					<div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">
						<!--begin::List Widget 4-->
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Header-->
							<div class="card-header border-0">
								<h3 class="card-title font-weight-bolder text-dark">Top Staff</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="card-body pt-2">
								<!--begin::Item-->
								<div class="d-flex align-items-center">
									<table class="table table-hover">
										<thead>
											<tr>
												<th scope="col">#</th>
												<th scope="col">Staff</th>
												<th scope="col">This month </th>
												<th scope="col">Last month</th>
											</tr>
										</thead>
										<tbody>
											@if(!empty($thismstaff))
												@foreach($thismstaff as $key => $value)
													<tr>
														<th scope="row">{{ ($key + 1) }}</th>
														<td>{{ $value['first_name'].' '.$value['last_name'] }}</td>
														<td>{{ '$'.$value['cashtotal'] }}</td>
														<td>{{ ( !empty($prmstaff[ $key ]) ) ? '$'.$prmstaff[$key]['cashtotal'] : 'NA' }}</td>
													</tr>
												@endforeach
											@endif
										</tbody>
									</table>
								</div>
								<!--end:Item-->

							</div>
							<!--end::Body-->
						</div>
						<!--end:List Widget 4-->
					</div>

				</div>
				<!--end::Row-->

				<!--end::Dashboard-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>
	<!--end::Content-->

@endsection

{{-- Scripts Section --}}
@section('scripts') 
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script> 

	<!--begin::Page Scripts(used by this page)-->
	<!--end::Page Scripts-->
	<script>
		var weekdata = '<?php echo json_encode($currentWeekDate); ?>';
		var weektotaldata = '<?php echo json_encode($currentWeekdaytotal); ?>';
		var weektotalclient = '<?php echo json_encode($clienttotalday); ?>';
		var upcomingAppointments = '<?php echo json_encode($upcomingAppointments); ?>';
		var showweek = jQuery.parseJSON(weekdata);
		var showweektotal = jQuery.parseJSON(weektotaldata);
		var showweekclienttotal = jQuery.parseJSON(weektotalclient);
		var showUpcomingAppointments = jQuery.parseJSON(upcomingAppointments);

		var week = [];
		var weekdate = [];
		var weekpr = [];
		var appointment = [];
		var clientpr = [];
		var confirmedAppointments = [];
		var cancelledAppointments = [];
		var upcomingAppointmentsDate = [];
		
		$.each( showweek, function( key, value ) {
			week.push(value.weekdate);
		});

		$.each( showweektotal, function( key, value ) {
			weekdate.push(value.weekdate);
			if(value.invoce[0]!=null){
				weekpr.push(Math.round(value.invoce[0].cashtotal));
			} else {
				weekpr.push(0);
			}
			if(value.appointment[0]!=null){
				appointment.push(Math.round(value.appointment[0].sum_special_price));
			} else {
				appointment.push(0);
			}
		});


		$.each( showweekclienttotal, function( key, value ) {
			if(value.client.length > 0){
				clientpr.push(value.client.length);
			} else {
				clientpr.push(0);
			}
		});

		$.each( showUpcomingAppointments, function( key, value ) {
			confirmedAppointments.push(value.confirmed_appointments);
			cancelledAppointments.push(value.cancelled_appointments);
			upcomingAppointmentsDate.push(value.appointment_date);
		});

		$(document).on('click','.open-view-appointment', function(){
			var href = $(this).attr('data-href');
			$('<a href="'+href+'" target="blank"></a>')[0].click();    
		});
	</script>
<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>

@endsection