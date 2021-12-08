{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
#messagelog_filter{
	display:none;
}
</style>

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
						<a class="nav-link active" href="{{ route('clientMessage') }}">Message Log</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('clientMessageSetting') }}">Settings</a>
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
				<div class="content-header ">
					<div class="d-flex justify-content-between my-2">
						<div class="calender-div my-2">
							<div class="input-group date">
								<div class="form-group mb-0">
									<div class="input-icon">
										<input type="text" id="myInputTextField"
											class="font-weight-500 form-control form-control-lg"
											placeholder="Search by Name, Email or Booking">
										<span>
											<i class="flaticon2-search-1 icon-md"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="action-btn-div">
						</div>
					</div>
					<div class="row my-4">
						<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
							<!--begin::List Widget 3-->
							<div class="card card-custom card-stretch gutter-b">
								<!--begin::Body-->
								<!--begin::Item-->
								<div class="table-responsive">
									<table class="table" id="messagelog">
										<thead>
											<tr>
												<th scope="col">Time sent</th>
												<th scope="col">Client</th>
												<th scope="col">Appointment</th>
												<th scope="col">Destination</th>
												<th scope="col">Type</th>
												<th scope="col">Message</th>
												<th scope="col">Status</th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>
								<!--end:Item-->
								<!--end::Body-->
							</div>
							<!--end::List Widget 3-->
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
	<!--end::Content-->
	

	<!-- Modal -->
	<div class="modal fade" id="reminderSmsModal" tabindex="-1" role="dialog"
		aria-labelledby="reminderSmsModalCenterTitle" aria-hidden="true" style="display: none;">
		<div class="modal-dialog " role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="font-weight-bolder m-auto modal-title" id="reminderSmsModalLongTitle">View Message
					</h4>
					<h3 type="button" class="" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</h3>
				</div>
				<div class="modal-body bg-light-info">
					<div class="m-auto shadow card">
						<div class="card-body">
							<div class="d-flex justify-content-between">
								<h3>
									<svg xmlns="http://www.w3.org/2000/svg" style="height: 30px;width:30px">
										<g fill="none" fill-rule="evenodd">
											<rect fill="#35AEA2" width="25" height="25" rx="5.85"></rect>
											<path
												d="M12.5 6C8.925 6 6 8.455 6 11.455s2.925 5.454 6.5 5.454c.542 0 1.083-.054 1.625-.164l3.033 1.2c.054 0 .109.055.217.055s.217-.055.325-.11c.163-.108.217-.326.217-.49l-.271-2.564C18.512 13.855 19 12.71 19 11.51 19 8.455 16.075 6 12.5 6z"
												fill="#FFF" fill-rule="nonzero"></path>
										</g>
									</svg>
									Messages
								</h3>
								<p class="p-1 m-0 font-weight-bold">now</p>

							</div>
							<div class="message">
								<h3>ScheduleDown </h3>
								<h6 class="p-0 m-0" id="show"></h6>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal fade" id="reminderEmailModal" tabindex="-1" role="dialog"
		aria-labelledby="reminderEmailModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog " role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="font-weight-bolder m-auto modal-title" id="reminderEmailModalLongTitle">View Message
					</h4>
					<h3 type="button" class="" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">×</span>
					</h3>
				</div>
				<div class="modal-body bg-light-info">
					<div class="m-auto shadow card">
						<div class="card-body rounded bg-dark text-white">
							<div class="message">
								<h3>Hi Jayesh See You Soon</h3>
								<span class="mb-3 badge badge-pill badge-info">Confirmed</span>
								<h4>Tuesday, 5 Jan at 10:00am</h4>
								<div class="addr">
									<h4>Gym Area</h4>
									<p class="p-0 m-0 text-muted">Canada Way, Burnby, British</p>
									<p class="p-0 m-0 text-muted">columbia, CA</p>
									<p class="p-0 m-0 text-muted">Booking ref:2016CD09</p>
								</div>
								<div class="my-4 icons justify-content-around d-flex">
									<div class="icon text-center">
										<i class="text-white fas fa-map-marker-alt"></i>
										Direction
									</div>
									<div class="icon text-center">
										<i class="text-white far fa-calendar-alt"></i>
										Schedule
									</div>
									<div class="icon text-center">
										<i class="text-white far fa-times-circle"></i>
										Cancel
									</div>
								</div>
							</div>
						</div>
						<div class="bg-secondary p-3">
							<div class="d-flex justify-content-between">
								<div>
									<h3>Hair Cuts</h3>
									<p class="m-0">1h 15min wiht Adam</p>
								</div>
								<h3 class="font-weight-bolder">CA$30</h3>
							</div>
							<hr>
							<div class="total d-flex justify-content-between">
								<h3 class="font-weight-bolder">Total</h3>
								<h3 class="font-weight-bolder">CA $30</h3>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Modal -->

@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>

	<script type="text/javascript">

		//show table data
		$(document).ready( function(){
			table = $('#messagelog').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					type: "POST",
					url : "{{ route('getmessage') }}",
					data: {_token : "{{csrf_token()}}"}
				},
				columns: [
						{data: 'time' , name: 'time'},
						{data: 'client' , name: 'client'},
						{data: 'appointment' , name: 'appointment'},
						{data: 'destination' , name: 'destination'},
						{data: 'type' , name: 'type'},
						{data: 'message' , name: 'message'},
						{data: 'status' , name: 'status'},
				]			
			});	

			$('#myInputTextField').keyup(function(){
				  table.search($(this).val()).draw();
			});

		});

	</script>
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

			$(document).on('click','#showsms', function() {
				var data = $(this).html();
				$('#show').text(data);

			});
		});
	</script>
	<script type="text/javascript">
		
		function openNav() {
			document.getElementById("myFilter").style.width = "300px";
		}

		function closeNav() {
			document.getElementById("myFilter").style.width = "0%";
		}
	</script>
@endsection