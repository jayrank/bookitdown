{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')

@endsection

@section('content')
	
	<!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
		<!--begin::Tabs-->
		<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
			<div
				class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
				<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
					role="tablist">
					<li class="nav-item">
						<a class="nav-link" href="{{ route('clientMessage') }}">Message Log</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="{{ route('clientMessageSetting') }}">Settings</a>
					</li>
				</ul>
			</div>
		</div>
		<!--end::Tabs-->
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Row-->
				<div class="content-header ">
					<div class="row my-4">
						<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
							<div class="card-custom card-stretch gutter-b">
								<!-- <div class="client-messages-header rounded" id="defaultdiv" @if(isset($noti->status) && $noti->status==1) style="display:block;" @else style="display:none;" @endif >
									<h2 class="text-dark font-weight-bolder mb-4">
										Smart message delivery
									</h2>
									<p class="font-size-lg">
										Your notifications are automatically sent to clients using smart
										delivery across email, text message and app
										notification to maximize reach.
									</p>
									<button class="btn btn-white">Learn More</button>
								</div>
								<div class="client-messages-header rounded" id="disablediv" @if(isset($noti->status) && $noti->status==0) style="display:block;" @else style="display:none;" @endif >
									<h2 class="text-dark font-weight-bolder mb-4">
										Client messages disabled
									</h2>
									<p class="font-size-lg">
										Your clients are not receiving notifications about appointments booked by your staff, enable delivery now to send by email, text message and mobile notification
									</p>
									<button class="btn btn-white enablenoti" >Enable Now</button>
								</div> -->
								<div class="d-flex justify-content-between mt-6">
									<div>
										<h3 class="font-weight-bolder">Notification messages</h3>
										<p class="text-dark-50">
											Add extra information to your message templates and customize
											the advance timeframe for reminders.
										</p>
									</div>
									<div>
										<div class="dropdown dropdown-inline">
											<a href="#" class="btn btn-clean text-dark btn-sm btn-icon"
												data-toggle="dropdown" aria-haspopup="true"
												aria-expanded="false">
												<i class="ki ki-bold-more-hor text-dark"></i>
											</a>
											<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
												<ul class="navi navi-hover">
													<li class="navi-item">
														<a class="cursor-pointer navi-link" href="{{ route('account') }}">
															<span class="navi-text">Language Option</span>
														</a>
													</li>
													<li class="navi-item">
														@if(isset($noti->status) && $noti->status==0) 
															<a class="cursor-pointer navi-link enablenoti">
																<span class="text-succes navi-text">Enable notifications</span>
															</a>
														@else
															<a data-toggle="modal"
															data-target="#disableNotification"
															class="cursor-pointer navi-link">
															<span class="text-danger navi-text">Disable
																notifications</span>
															</a>
														@endif
													</li>
												</ul>
											</div>
										</div>
									</div>
								</div>
								<div class="my-1">
									<ul class="list-group client-messages-setting">
										<a href="{{ route('newAppoinmentNoti') }}">
											<li
												class="p-6 my-4 cursor-pointer shadow-sm rounded d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg">
														<svg viewBox="0 0 48 48"
															xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle fill="#EBF7FE" cx="24" cy="24"
																	r="24"></circle>
																<path fill="#ffe78c"
																	d="M14.22 14.22H24v20.77h-9.78z"></path>
																<path stroke="#101928" stroke-width="1.78"
																	stroke-linecap="round"
																	stroke-linejoin="round"
																	d="M18.22 23.92l6.02 5.41 7.32-12.44">
																</path>
															</g>
														</svg>
													</div>
													<div class="ml-4 name text-left">
														<h3>New appointment</h3>
														<p class="m-0 text-dark-50">Automatically sends to
															clients when an appointment is booked for them
														</p>
													</div>
												</div>
												@if(isset($noti->status))
													@if($noti->status==0)
														<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
													@else
														<span class="bagde badge-success text-uppercase p-1 rounded">Enabled</span>
													@endif 
												@else
													<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
												@endif
											</li>
										</a>
										<a href="{{ route('remiderNotification') }}">
											<li
												class="p-6 my-4 d-flex shadow-sm rounded justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg">
														<svg viewBox="0 0 48 48"
															xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle fill="#EBF7FE" cx="24" cy="24"
																	r="24"></circle>
																<path
																	d="M13.34 22.05v-.26c0-5.65 4.72-10.23 10.55-10.23 5.83 0 10.56 4.58 10.56 10.23v.26H13.33z"
																	fill="#ffe78c"></path>
																<g stroke="#24334A" stroke-width="1.33">
																	<path
																		d="M21.59 32.89a2.25 2.25 0 0 0 4.5 0">
																	</path>
																	<path
																		d="M30.6 25.42v-4.48a6.8 6.8 0 0 0-6.76-6.71 6.8 6.8 0 0 0-6.75 6.71v4.48c0 3.8-3 7.47-3 7.47h19.5s-3-3.66-3-7.47z"
																		stroke-linecap="square"></path>
																</g>
															</g>
														</svg>
													</div>
													<div class="ml-4 name text-left">
														<h3>Upcoming appointment reminder</h3>
														<p class="text-dark-50 m-0">Automatically sends to
															clients 2 hours ahead of their appointment</p>
													</div>
												</div>
												@if(isset($noti->status))
													@if($noti->status==0)
														<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
													@else
														<span class="bagde badge-success text-uppercase p-1 rounded">Enabled</span>
													@endif 
												@else
													<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
												@endif
											</li>
										</a>
										<a href="{{ route('rescheduleNotification') }}">
											<li
												class="p-6 my-4 d-flex shadow-sm rounded justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg">
														<svg viewBox="0 0 48 48"
															xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle fill="#EBF7FE" cx="24" cy="24"
																	r="24"></circle>
																<circle fill="#ffe78c" cx="17.33" cy="29.78"
																	r="7.56"></circle>
																<g stroke="#101928" stroke-linecap="round"
																	stroke-linejoin="round"
																	stroke-width="1.33">
																	<path
																		d="M14.67 23.56c0-4.9 4-8.9 8.89-8.9a8.74 8.74 0 0 1 7.9 4.9M32.44 23.56c0 4.88-4 8.88-8.88 8.88a8.74 8.74 0 0 1-7.92-4.88">
																	</path>
																	<path
																		d="M32.27 14.4l-.71 5.24-5.34-.7M14.84 32.71l.72-5.24 5.33.7">
																	</path>
																</g>
															</g>
														</svg>
													</div>
													<div class="ml-4 name text-left">
														<h3>Rescheduled appointment</h3>
														<p class="text-dark-50 m-0">
															Automatically sends to clients when their
															appointment start time is changed
														</p>
													</div>
												</div>
												@if(isset($noti->status))
													@if($noti->status==0)
														<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
													@else
														<span class="bagde badge-success text-uppercase p-1 rounded">Enabled</span>
													@endif 
												@else
													<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
												@endif
											</li>
										</a>
										<a href="{{ route('thankyouNotification') }}">
											<li
												class="p-6 my-4 d-flex shadow-sm rounded justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg">
														<svg viewBox="0 0 48 48"
															xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle fill="#EBF7FE" cx="24" cy="24"
																	r="24"></circle>
																<path fill="#ffe78c"
																	d="M11.14 10.29h13.1v26.89h-13.1z">
																</path>
																<path stroke="#101928" stroke-width="1.29"
																	stroke-linecap="round"
																	stroke-linejoin="round"
																	d="M24 29.57l-6.55 3.44 1.25-7.29-5.3-5.16 7.33-1.07L24 12.86l3.27 6.63 7.33 1.07-5.3 5.16 1.25 7.3z">
																</path>
															</g>
														</svg>
													</div>
													<div class="ml-4 name text-left">
														<h3>Thank you for visiting</h3>
														<p class="text-dark-50 m-0">
															Automatically sends to clients when their
															appointment is checked out
														</p>
													</div>
												</div>
												@if(isset($noti->status))
													@if($noti->status==0)
														<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
													@else
														<span class="bagde badge-success text-uppercase p-1 rounded">Enabled</span>
													@endif 
												@else
													<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
												@endif
											</li>
										</a>
										<a href="{{ route('cancellationNotification') }}">
											<li
												class="p-6 my-4 d-flex shadow-sm rounded justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg">
														<svg viewBox="0 0 48 48"
															xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle fill="#EBF7FE" cx="24" cy="24"
																	r="24"></circle>
																<g transform="translate(10.67 10.67)">
																	<path
																		d="M0 12.44v-.3C0 5.43 5.97 0 13.33 0c7.37 0 13.34 5.43 13.34 12.13v.31H0z"
																		fill="#ffe78c"></path>
																	<circle stroke="#101928"
																		stroke-width="1.33" cx="13.33"
																		cy="13.33" r="10"></circle>
																	<path
																		d="M16.89 9.78l-7.11 7.1M9.78 9.78l7.1 7.1"
																		stroke="#101928" stroke-width="1.33"
																		stroke-linecap="round"
																		stroke-linejoin="round"></path>
																</g>
															</g>
														</svg>
													</div>
													<div class="ml-4 name text-left">
														<h3>Cancelled appointment</h3>
														<p class="text-dark-50 m-0">
															Automatically sends to clients when their
															appointment is cancelled
														</p>
													</div>
												</div>
												@if(isset($noti->status))
													@if($noti->status==0)
														<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
													@else
														<span class="bagde badge-success text-uppercase p-1 rounded">Enabled</span>
													@endif 
												@else
													<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
												@endif
											</li>
										</a>
										<a href="{{ route('noShowNotification') }}">
											<li
												class="p-6 my-4 d-flex shadow-sm rounded justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg">
														<svg viewBox="0 0 48 48"
															xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle fill="#EBF7FE" cx="24" cy="24"
																	r="24"></circle>
																<g transform="translate(16 12.44)">
																	<path
																		d="M8.22 10.27c-2.2 0-4-1.84-4-4.11V4.11c0-2.27 1.8-4.11 4-4.11 2.21 0 4 1.84 4 4.1v2.06c0 2.27-1.79 4.1-4 4.1z"
																		stroke="#101928" stroke-width="1.33"
																		stroke-linecap="round"
																		stroke-linejoin="round"></path>
																	<circle fill="#ffe78c" cx="15.11"
																		cy="17.78" r="8"></circle>
																	<path
																		d="M8.16 21.33H.22v-4.22c0-1.28.77-2.43 1.93-2.88a16.77 16.77 0 0 1 6.07-1.11M18.06 14.37L11.7 20.9M11.7 14.37l6.36 6.53"
																		stroke="#101928" stroke-width="1.33"
																		stroke-linecap="round"
																		stroke-linejoin="round"></path>
																</g>
															</g>
														</svg>
													</div>
													<div class="ml-4 name text-left">
														<h3>Did not show up</h3>
														<p class="text-dark-50 m-0">
															Automatically sends to clients when their
															appointment is marked as no show
														</p>
													</div>
												</div>
												@if(isset($noti->status))
													@if($noti->status==0)
														<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
													@else
														<span class="bagde badge-success text-uppercase p-1 rounded">Enabled</span>
													@endif 
												@else
													<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
												@endif
											</li>
										</a>
										<a href="{{ route('tippingNotification') }}">
											<li
												class="p-6 my-4 d-flex shadow-sm rounded justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
												<div class="d-flex align-items-center">
													<div class="icon-svg-lg">
														<svg xmlns="http://www.w3.org/2000/svg">
															<g fill="none" fill-rule="evenodd">
																<circle cx="28" cy="28" r="28"
																	fill="#EBF7FE"></circle>
																<path fill="#ffe78c" d="M13 14h19v11H13z">
																</path>
																<g fill="#101928" fill-rule="nonzero"
																	transform="translate(17 18)">
																	<path
																		d="M18.5-.5a1 1 0 01.993.883L19.5.5v8.171a1 1 0 01-1.993.117L17.5 8.67V1.5h-16v10h5a1 1 0 01.993.883l.007.117a1 1 0 01-.883.993L6.5 13.5h-6a1 1 0 01-.993-.883L-.5 12.5V.5a1 1 0 01.883-.993L.5-.5h18z">
																	</path>
																	<path
																		d="M22.5 10.5a1 1 0 01.993.883l.007.117v4c0 2.398-3.205 4-7 4-3.707 0-6.85-1.529-6.995-3.834L9.5 15.5v-4a1 1 0 011.993-.117l.007.117v4c0 .916 2.167 2 5 2 2.744 0 4.864-1.017 4.994-1.914l.006-.086v-4a1 1 0 011-1z">
																	</path>
																	<path
																		d="M22.5 14.5a1 1 0 01.993.883l.007.117v4c0 2.398-3.205 4-7 4-3.707 0-6.85-1.529-6.995-3.834L9.5 19.5v-4a1 1 0 011.993-.117l.007.117v4c0 .916 2.167 2 5 2 2.744 0 4.864-1.017 4.994-1.914l.006-.086v-4a1 1 0 011-1z">
																	</path>
																	<path
																		d="M16.5 7.5c-3.795 0-7 1.602-7 4 0 2.398 3.205 4 7 4s7-1.602 7-4c0-2.398-3.205-4-7-4zm0 2c2.832 0 5 1.084 5 2 0 .916-2.168 2-5 2s-5-1.084-5-2c0-.916 2.168-2 5-2zM9.5 5a1.5 1.5 0 100 3 1.5 1.5 0 000-3zm0 1a.5.5 0 110 1 .5.5 0 010-1z">
																	</path>
																	<circle cx="9.5" cy="6.5" r="1">
																	</circle>
																</g>
															</g>
														</svg>
													</div>
													<div class="ml-4 name text-left">
														<h3>Thank you for tipping</h3>
														<p class="text-dark-50 m-0">
															Automatically sends to clients when they add a
															tip after their appointment
														</p>
													</div>
												</div>
												@if(isset($noti->status))
													@if($noti->status==0)
														<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
													@else
														<span class="bagde badge-success text-uppercase p-1 rounded">Enabled</span>
													@endif 
												@else
													<span class="bagde badge-danger text-uppercase p-1 rounded">Disabled</span>
												@endif
											</li>
										</a>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!--end::Row-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>
	<!--end::Content-->

	<!--start::modal-->
	<div class="modal fade " id="disableNotification" tabindex="-1" role="dialog" aria-labelledby="disableNotificationLabel" aria-modal="true" style="display: none;">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title font-weight-bolder" id="disableNotificationLabel">Disable Client
						Notifications</h3>
					<p class="cursor-pointer m-0" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<h5 class="text-dark-50 mb-6">
						Are you sure? Clients will not receive notifications about appointments booked by your staff.
					</h5>
					<h5 class="text-dark-50">
						Messages will continue to send for appointments booked online by clients.
					</h5>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-danger" id="disablenoti">disable</button>
				</div>
			</div>
		</div>
	</div>
	<!--end::modal-->
	
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

	<script>
		// Class definition
		var KTBootstrapDaterangepicker = function () {

			// Private functions
			var demos = function () {

				// predefined ranges
				var start = moment().subtract(29, 'days');
				var end = moment();

				$('#kt_daterangepicker_6').daterangepicker({
					buttonClasses: ' btn',
					applyClass: 'btn-primary',
					cancelClass: 'btn-secondary',

					startDate: start,
					endDate: end,
					ranges: {
						'Today': [moment(), moment()],
						'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
						'Last 7 Days': [moment().subtract(6, 'days'), moment()],
						'Last 30 Days': [moment().subtract(29, 'days'), moment()],
						'This Month': [moment().startOf('month'), moment().endOf('month')],
						'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
					}
				}, function (start, end, label) {
					$('#kt_daterangepicker_6 .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
				});
			}

			return {
				// public functions
				init: function () {
					demos();
				}
			};
		}();

		jQuery(document).ready(function () {
			KTBootstrapDaterangepicker.init();
		});
	</script>
	<script type="text/javascript">

		function openNav() {
			document.getElementById("myFilter").style.width = "300px";
		}

		function closeNav() {
			document.getElementById("myFilter").style.width = "0%";
		}
		//disable
		$(document).on('click','#disablenoti', function(){
			$.ajax({
				type: "get",
				url: "{{ route('desablenotification') }}",
				
				success: function (data) {
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
		//enable
		$(document).on('click','.enablenoti', function(){
			$.ajax({
				type: "get",
				url: "{{ route('enablenotification') }}",
				
				success: function (data) {
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