
{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
	<style>	
	.editable_row {
		cursor: pointer;
	}
	</style>
@endsection

@section('content')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
				<!--begin::Content-->
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="background: #FFF;">
					<!--begin::Tabs-->
					<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
						<div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1 scroll">
							<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5 scroll-manu" role="tablist">
								<li class="nav-item">
									<a class="nav-link" href="{{ url('staff') }}">Staff Working Hours</a>
								</li>
								<li class="nav-item">
									<a class="nav-link active" href="{{ url('staff_closed') }}">Closed Dates</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="{{ url('staff_members') }}">Staff Members</a>
								</li>
								<li class="nav-item">
									<a class="nav-link" href="staff_user_permission.html">User Permissions</a>
								</li>
							</ul>
						</div>
					</div>
					<!--end::Tabs-->
					<!--begin::Entry-->
					<div class="d-flex flex-column-fluid">
						<!--begin::Container-->
						<div class="container">
							<!--begin::Sales-->
							<!--begin::Row-->
							<div class="content-header">
								<div class="d-flex justify-content-between">
									<div class="">

									</div>
									<div class="">
										<button class="font-weight-bold btn btn-lg btn-primary" data-toggle="modal" data-target="#newCloseDateModalCenter">
											New CLosed Date
										</button>
									</div>
								</div>
							</div>
							<div class="row my-2">
								<div class="col-sm-12 col-lg-12 col-xxl-12">
									<!--begin::List Widget 3-->
									<div class="">
										<!--begin::Body-->
										<div class="pt-2">
											<!--begin::Item-->
											<div class="d-flex align-items-center p-2">
												<div class="table-responsive closed-date">
													<table class="table table-hover">
														<thead class="thead-light">
															<tr>
																<th scope="col">DATE RANGE</th>
																<th scope="col">NO. OF DAYS</th>
																<th scope="col">LOCATIONS</th>
																<th scope="col">DESCRIPTION</th>
															</tr>
														</thead>
														<tbody>
															<tr data-toggle="modal" data-target="#editCloseDateModalCenter">
																<td>Sun, 03 Jan 2021 - Sun, 03 Jan 2021</td>
																<td>1 Day</td>
																<td>Gym area</td>
																<td>Week Off</td>
															</tr>
															<tr data-toggle="modal" data-target="#editCloseDateModalCenter">
																<td>Sun, 03 Jan 2021 - Sun, 03 Jan 2021</td>
																<td>1 Day</td>
																<td>Gym area</td>
																<td>Week Off</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
											<!--end:Item-->

										</div>
										<!--end::Body-->
									</div>
									<!--end::List Widget 3-->
								</div>
							</div>
							<!--end::Row-->

							<!--end::Sales-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Entry-->
				</div>
				<!--end::Content-->
@endsection
			
			<!--end::Wrapper-->
		<!--end::Page-->
	<!--end::Main-->
	
	<!--begin::Chat Panel-->
	<div class="modal modal-sticky modal-sticky-bottom-right" id="kt_chat_modal" role="dialog" data-backdrop="false">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<!--begin::Card-->
				<div class="card card-custom">
					<!--begin::Header-->
					<div class="card-header align-items-center px-4 py-3">
						<div class="text-left flex-grow-1">
							<!--begin::Dropdown Menu-->
							<div class="dropdown dropdown-inline">
								<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<span class="svg-icon svg-icon-lg">
										<!--begin::Svg Icon | path:assets/media/svg/icons/Communication/Add-user.svg-->
										<svg xmlns="http://www.w3.org/2000/svg"
											xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
											viewBox="0 0 24 24" version="1.1">
											<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
												<polygon points="0 0 24 0 24 24 0 24" />
												<path
													d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
													fill="#000000" fill-rule="nonzero" opacity="0.3" />
												<path
													d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
													fill="#000000" fill-rule="nonzero" />
											</g>
										</svg>
										<!--end::Svg Icon-->
									</span>
								</button>
								<div class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-md">
									<!--begin::Navigation-->
									<ul class="navi navi-hover py-5">
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-drop"></i>
												</span>
												<span class="navi-text">New Group</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-list-3"></i>
												</span>
												<span class="navi-text">Contacts</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-rocket-1"></i>
												</span>
												<span class="navi-text">Groups</span>
												<span class="navi-link-badge">
													<span
														class="label label-light-primary label-inline font-weight-bold">new</span>
												</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-bell-2"></i>
												</span>
												<span class="navi-text">Calls</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-gear"></i>
												</span>
												<span class="navi-text">Settings</span>
											</a>
										</li>
										<li class="navi-separator my-3"></li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-magnifier-tool"></i>
												</span>
												<span class="navi-text">Help</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="flaticon2-bell-2"></i>
												</span>
												<span class="navi-text">Privacy</span>
												<span class="navi-link-badge">
													<span
														class="label label-light-danger label-rounded font-weight-bold">5</span>
												</span>
											</a>
										</li>
									</ul>
									<!--end::Navigation-->
								</div>
							</div>
							<!--end::Dropdown Menu-->
						</div>
						<div class="text-center flex-grow-1">
							<div class="text-dark-75 font-weight-bold font-size-h5">Matt Pears</div>
							<div>
								<span class="label label-dot label-success"></span>
								<span class="font-weight-bold text-muted font-size-sm">Active</span>
							</div>
						</div>
						<div class="text-right flex-grow-1">
							<button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md"
								data-dismiss="modal">
								<i class="ki ki-close icon-1x"></i>
							</button>
						</div>
					</div>
					<!--end::Header-->
					<!--begin::Body-->
					<div class="card-body">
						<!--begin::Scroll-->
						<div class="scroll scroll-pull" data-height="375" data-mobile-height="300">
							<!--begin::Messages-->
							<div class="messages">
								<!--begin::Message In-->
								<div class="d-flex flex-column mb-5 align-items-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-circle symbol-40 mr-3">
											<img alt="Pic" src="assets/media/users/300_12.jpg" />
										</div>
										<div>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
												Pears</a>
											<span class="text-muted font-size-sm">2 Hours</span>
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
										How likely are you to recommend our company to your friends and family?</div>
								</div>
								<!--end::Message In-->
								<!--begin::Message Out-->
								<div class="d-flex flex-column mb-5 align-items-end">
									<div class="d-flex align-items-center">
										<div>
											<span class="text-muted font-size-sm">3 minutes</span>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
										</div>
										<div class="symbol symbol-circle symbol-40 ml-3">
											<img alt="Pic" src="assets/media/users/300_21.jpg" />
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
										Hey there, we’re just writing to let you know that you’ve been subscribed to a
										repository on GitHub.</div>
								</div>
								<!--end::Message Out-->
								<!--begin::Message In-->
								<div class="d-flex flex-column mb-5 align-items-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-circle symbol-40 mr-3">
											<img alt="Pic" src="assets/media/users/300_21.jpg" />
										</div>
										<div>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
												Pears</a>
											<span class="text-muted font-size-sm">40 seconds</span>
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
										Ok, Understood!</div>
								</div>
								<!--end::Message In-->
								<!--begin::Message Out-->
								<div class="d-flex flex-column mb-5 align-items-end">
									<div class="d-flex align-items-center">
										<div>
											<span class="text-muted font-size-sm">Just now</span>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
										</div>
										<div class="symbol symbol-circle symbol-40 ml-3">
											<img alt="Pic" src="assets/media/users/300_21.jpg" />
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
										You’ll receive notifications for all issues, pull requests!</div>
								</div>
								<!--end::Message Out-->
								<!--begin::Message In-->
								<div class="d-flex flex-column mb-5 align-items-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-circle symbol-40 mr-3">
											<img alt="Pic" src="assets/media/users/300_12.jpg" />
										</div>
										<div>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
												Pears</a>
											<span class="text-muted font-size-sm">40 seconds</span>
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
										You can unwatch this repository immediately by clicking here:
										<a href="#">https://github.com</a>
									</div>
								</div>
								<!--end::Message In-->
								<!--begin::Message Out-->
								<div class="d-flex flex-column mb-5 align-items-end">
									<div class="d-flex align-items-center">
										<div>
											<span class="text-muted font-size-sm">Just now</span>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
										</div>
										<div class="symbol symbol-circle symbol-40 ml-3">
											<img alt="Pic" src="assets/media/users/300_21.jpg" />
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
										Discover what students who viewed Learn Figma - UI/UX Design. Essential Training
										also viewed</div>
								</div>
								<!--end::Message Out-->
								<!--begin::Message In-->
								<div class="d-flex flex-column mb-5 align-items-start">
									<div class="d-flex align-items-center">
										<div class="symbol symbol-circle symbol-40 mr-3">
											<img alt="Pic" src="assets/media/users/300_12.jpg" />
										</div>
										<div>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">Matt
												Pears</a>
											<span class="text-muted font-size-sm">40 seconds</span>
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-success text-dark-50 font-weight-bold font-size-lg text-left max-w-400px">
										Most purchased Business courses during this sale!</div>
								</div>
								<!--end::Message In-->
								<!--begin::Message Out-->
								<div class="d-flex flex-column mb-5 align-items-end">
									<div class="d-flex align-items-center">
										<div>
											<span class="text-muted font-size-sm">Just now</span>
											<a href="#"
												class="text-dark-75 text-hover-primary font-weight-bold font-size-h6">You</a>
										</div>
										<div class="symbol symbol-circle symbol-40 ml-3">
											<img alt="Pic" src="assets/media/users/300_21.jpg" />
										</div>
									</div>
									<div
										class="mt-2 rounded p-5 bg-light-primary text-dark-50 font-weight-bold font-size-lg text-right max-w-400px">
										Company BBQ to celebrate the last quater achievements and goals. Food and drinks
										provided</div>
								</div>
								<!--end::Message Out-->
							</div>
							<!--end::Messages-->
						</div>
						<!--end::Scroll-->
					</div>
					<!--end::Body-->
					<!--begin::Footer-->
					<div class="card-footer align-items-center">
						<!--begin::Compose-->
						<textarea class="form-control border-0 p-0" rows="2" placeholder="Type a message"></textarea>
						<div class="d-flex align-items-center justify-content-between mt-5">
							<div class="mr-3">
								<a href="#" class="btn btn-clean btn-icon btn-md mr-1">
									<i class="flaticon2-photograph icon-lg"></i>
								</a>
								<a href="#" class="btn btn-clean btn-icon btn-md">
									<i class="flaticon2-photo-camera icon-lg"></i>
								</a>
							</div>
							<div>
								<button type="button"
									class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6">Send</button>
							</div>
						</div>
						<!--begin::Compose-->
					</div>
					<!--end::Footer-->
				</div>
				<!--end::Card-->
			</div>
		</div>
	</div>
	<!--end::Chat Panel-->
	<!-- Modal -->
	<div class="modal fade" id="newCloseDateModalCenter" tabindex="-1" role="dialog"
		aria-labelledby="newCloseDateModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header flex-center">
					<h5 class="font-weight-normal modal-title" id="newCloseDateModalLongTitle">New Close
						Date</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="alert alert-blue" role="alert">
						Online bookings can not be placed during closed dates.
					</div>
					<div class="row">
						<div class="col-6 pr-0">
							<label class="text-uppercase font-weight-bold">Start Date</label>
							<div class="input-group date">
								<input type="text" class="form-control" name="start_date" readonly="readonly" value="--"
									id="startNewCloseDatePicker">
								<div class="input-group-append"> <span
										class="input-group-text input-group-text bg-transparent text-dark">
										<i class="fa fa-chevron-down"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="col-6 pl-0">
							<label class="text-uppercase font-weight-bold">End Date</label>
							<div class="input-group date">
								<input type="text" class="form-control" name="end_date" readonly="readonly" value="--"
									id="endNewCloseDatePicker">
								<div class="input-group-append"> <span
										class="input-group-text input-group-text bg-transparent text-dark">
										<i class="fa fa-chevron-down"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="col-12 my-4">
							<label class="text-uppercase font-weight-bold">Description</label>
							<textarea class="form-control" rows="2"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="editCloseDateModalCenter" tabindex="-1" role="dialog"
		aria-labelledby="editCloseDateModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header flex-center">
					<h5 class="font-weight-normal modal-title" id="editCloseDateModalLongTitle">Edit Close
						Date</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<div class="alert alert-blue" role="alert">
						Online bookings can not be placed during closed dates.
					</div>
					<div class="row">
						<div class="col-6 pr-0">
							<label class="text-uppercase font-weight-bold">Start Date</label>
							<div class="input-group date">
								<input type="text" class="form-control" readonly="readonly" value="05/20/2017"
									id="startEditCloseDatePicker">
								<div class="input-group-append"> <span
										class="input-group-text input-group-text bg-transparent text-dark">
										<i class="fa fa-chevron-down"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="col-6 pl-0">
							<label class="text-uppercase font-weight-bold">End Date</label>
							<div class="input-group date">
								<input type="text" class="form-control" readonly="readonly" value="05/20/2017"
									id="endEditCloseDatePicker">
								<div class="input-group-append"> <span
										class="input-group-text input-group-text bg-transparent text-dark">
										<i class="fa fa-chevron-down"></i>
									</span>
								</div>
							</div>
						</div>
						<div class="col-12 my-4">
							<label class="text-uppercase font-weight-bold">Description</label>
							<textarea class="form-control" rows="2"></textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary">Save changes</button>
				</div>
			</div>
		</div>
	</div>
	<!--begin::Scrolltop-->
	
	<!--begin::Page Vendors(used by this page)-->
	<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js')}}"></script>
	<!--end::Page Vendors-->
	<!--begin::Page Scripts(used by this page)-->
	<script src="{{ asset('/js/widgets.js')}}">{{ asset('js/scripts.bundle.js') }}</script>
	<!--end::Page Scripts-->

	<!-- For Datepicker -->
	@section('scripts')
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
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
			var date1 = function () {
				// minimum setup
				$('#startNewCloseDatePicker').datepicker({
					rtl: KTUtil.isRTL(),
					todayHighlight: true,
					autoclose: true,
					format: 'DD, dd/mm/yyyy',
					orientation: "bottom left",
					templates: arrows
				});
			}
			var date2 = function () {
				// minimum setup
				$('#endNewCloseDatePicker').datepicker({
					rtl: KTUtil.isRTL(),
					todayHighlight: true,
					autoclose: true,
					format: 'DD, dd/mm/yyyy',
					orientation: "bottom left",
					templates: arrows
				});
			}
			var date3 = function () {
				// minimum setup
				$('#startEditCloseDatePicker').datepicker({
					rtl: KTUtil.isRTL(),
					todayHighlight: true,
					autoclose: true,
					format: 'DD, dd/mm/yyyy',
					orientation: "bottom left",
					templates: arrows
				});
			}


			var date4 = function () {
				// minimum setup
				$('#endEditCloseDatePicker').datepicker({
					rtl: KTUtil.isRTL(),
					todayHighlight: true,
					autoclose: true,
					format: 'DD, dd/mm/yyyy',
					orientation: "bottom left",
					templates: arrows
				});
			}

			return {
				// public functions
				init: function () {
					date1();
					date2();
					date3();
					date4();
				}
			};
		}();

		jQuery(document).ready(function () {
			KTBootstrapDatepicker.init();
		});
	</script>
	@endsection

<!--end::Body-->

