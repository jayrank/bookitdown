{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
#salesAppointmentList_filter{
	display:none;
}
</style>
@endsection

@section('content')
<div class="flex-column-fluid" id="kt_content">
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1 scroll">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5 scroll-manu" role="tablist">
				@if (Auth::user()->can('daily_sales'))
					<li class="nav-item">
						<a class="nav-link" href="{{ route('dailySale') }}">Daily Sales</a>
					</li>
				@endif	
				@if (Auth::user()->can('sales_appointments'))
					<li class="nav-item">
						<a class="nav-link active" href="{{ route('appointmentsList') }}">Appointments</a>
					</li>
				@endif	
				@if (Auth::user()->can('sales_invoices'))	
					<li class="nav-item">
						<a class="nav-link" href="{{ route('salesList') }}">Invoices</a>
					</li>
				@endif	
				@if (Auth::user()->can('sales_vouchers'))
					<li class="nav-item">
						<a class="nav-link" href="{{ route('vouchers') }}">Vouchers</a>
					</li>
				@endif
				<!--li class="nav-item">
					<a class="nav-link" href="{{ route('paidPlans') }}">Paid plans</a>
				</li-->
			</ul>
		</div>
	</div>
	<!--end::Tabs-->
	<!--begin::Entry-->
	<div class="flex-column-fluid">
		<!--begin::Container-->
		<div class="container">
			<!--begin::Sales-->
			<!--begin::Row-->
			<div class="content-header">
				<div class="display-investory justify-content-between">
					<div class="calender-div my-2">
						<div class="input-group date">
							<div class="form-group width-100">
								<div class='input-icon' id='kt_daterangepicker_salesapp'>
									<input type='text' class="form-control kt_date" readonly placeholder="Select date range" />
									<span class=""><i class="la la-calendar-check-o"></i></span>
								</div>
							</div>
							<div class="form-group mb-0 width-100">
								<div class="input-icon">
									<input type="text" class="font-weight-500 form-control form-control form-control-lg width-100" placeholder="Search by Reference or Client" id="myInputTextField">
									<span><i class="flaticon2-search-1 icon-md"></i></span>
								</div>
							</div>
						</div>
					</div>
					<div class="action-btn-div width-auto">
						<div class="dropdown dropdown-inline mr-2">
							<button type="button"
								class="btn btn-white font-weight-bolder dropdown-toggle"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="svg-icon svg-icon-md">
									<!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
									<svg xmlns="http://www.w3.org/2000/svg">
										<g>
											<path
												d="M15.072 9.62c.506 0 .911.405.911.912v4.962a.908.908 0 0 1-.911.911H.962c-.506 0-.945-.405-.945-.911v-4.962c0-.507.439-.912.945-.912s.911.405.911.912v4.017H14.16v-4.017c0-.507.405-.912.912-.912z">
											</path>
											<path
												d="M7.376 11.68L3.662 7.965a.878.878 0 0 1 0-1.282.878.878 0 0 1 1.283 0l2.16 2.126V.911c0-.506.406-.911.912-.911s.911.405.911.911v7.9l2.127-2.127a.917.917 0 0 1 1.316 0 .878.878 0 0 1 0 1.282L8.658 11.68a.922.922 0 0 1-.641.27.922.922 0 0 1-.641-.27z">
											</path>
										</g>
									</svg>
									<!--end::Svg Icon-->
								</span>Export</button>
							<!--begin::Dropdown Menu-->
							<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
								<!--begin::Navigation-->
								<ul class="navi flex-column navi-hover py-2">
									<li class="navi-item" id="export">
										<a href="#" class="navi-link">
											<span class="navi-icon">
												<i class="la la-file-excel-o"></i>
											</span>
											<span class="navi-text">Excel</span>
											<form name="ex" id="ex" action="{{ route('appointmentdownloadExcel') }}" method="POST">
												@csrf
												<input type="hidden" name="location_id" id="loc">
												<input type="hidden" name="staff_id" id="sta">
												<input type="hidden" name="datefilter" id="datefilter">
											</form>
										</a>
									</li>
									<li class="navi-item" id="csvex">
										<a href="#" class="navi-link">
											<span class="navi-icon">
												<i class="la la-file-text-o"></i>
											</span>
											<span class="navi-text">CSV</span>
											<form name="csv" id="csv" action="{{ route('appointmentdownloadcsv') }}" method="POST">
												@csrf
												<input type="hidden" name="location_id" id="csvloc">
												<input type="hidden" name="staff_id" id="csvsta">
												<input type="hidden" name="datefilter" id="csvdatefilter">
											</form>
										</a>
									</li>
									<li class="navi-item" id="pdfex">
										<a href="#" class="navi-link">
											<span class="navi-icon">
												<i class="la la-file-pdf-o"></i>
											</span>
											<span class="navi-text">PDF</span>
											<form name="pdf" id="pdf" action="{{ route('appointmentdownloadpdf') }}" method="POST">
												@csrf
												<input type="hidden" name="location_id" id="pdfloc">
												<input type="hidden" name="staff_id" id="pdfsta">
												<input type="hidden" name="datefilter" id="pdfdatefilter">
											</form>
										</a>
									</li>
								</ul>
								<!--end::Navigation-->
							</div>
							<!--end::Dropdown Menu-->
						</div>
						<button class="font-weight-bold btn btn-white" onclick="openNav()">
							Filter <i class="fa fa-filter"></i>
						</button>
					</div>
				</div>
				<div class="row my-4">
					<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
						<!--begin::List Widget 3-->
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Body-->
							<!--begin::Item-->
							<div class="table-responsive apoinment">
								
								<input type="hidden" name="getSalesAppointmentList" id="getSalesAppointmentList" value="{{ route('getSalesAppointmentList') }}">
								@csrf
							
								<table cellspacing="10" cellpadding="10" class="table table-responsive" id="salesAppointmentList">
									<thead>
										<tr>
											<th scope="col">Ref#</th>
											<th scope="col">Client</th>
											<th scope="col">Service</th>
											<th scope="col">Date</th>
											<th scope="col">Time</th>
											<th scope="col">Duration</th>
											<th scope="col">Location</th>
											<th scope="col">Staff</th>
											<th scope="col">Price</th>
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

<!-- Filter Sidebar -->
<div id="myFilter" class="filter-overlay" style="width:0px;">
	<a href="javascript:void(0)" class="closebtn" onClick="closeNav()">×</a>
	<div class="filter-header pt-2">
		<h2 class="text-center py-3">Filter</h2>
	</div>
	<div class="filter-overlay-content">
		<div class="filter-overlay-content p-4">
			<div class="form-group font-weight-bold">
				<label for=" select-location font-weight-bold">Location</label>
				<select class="form-control" id="userLocations" name="userLocations">
					<option selected value="0">All Location</option>
					@if(!empty($Locations))
						@foreach($Locations as $LocationData)
							<option value="{{ $LocationData['id'] }}">{{ $LocationData['location_name'] }}</option>
						@endforeach
					@endif
				</select>
			</div>
			<div class="form-group font-weight-bold">
				<label for=" select-location font-weight-bold">Staff</label>
				<select class="form-control" id="userStaff" name="userStaff">
					<option selected value="0">All Staff</option>
					@if(!empty($Staff))
						@foreach($Staff as $StaffData)
							<option value="{{ ($StaffData['staff_user_id']) ? $StaffData['staff_user_id'] : '' }}">{{ ($StaffData['first_name']) ? $StaffData['first_name'] : '' }} {{ ($StaffData['last_name']) ? $StaffData['last_name'] : '' }}</option>
						@endforeach
					@endif
				</select>
			</div>
			<!--div class="form-group font-weight-bold">
				<label for=" select-location font-weight-bold">Channel</label>
				<select class="form-control" id="select-location">
					<option selected disabled>All Channel</option>
					<option>Facebook</option>
					<option>Fresha</option>
					<option>Instagram</option>
				</select>
			</div-->
		</div>
		<div class="button-action d-flex justify-content-between px-5">
			<button onclick="closeNav()" class="btn btn-white w-100 mr-4" type="button">Clear</button>
			<button class="btn btn-primary w-100" type="submit" id="filterApplyBtn">Apply</button>
		</div>
	</div>
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ asset('js/sales.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>
<script>
	$(document).ready(function() {


		window.table = $('#salesAppointmentList').DataTable({
			processing: true,
			serverSide: true,
			"bLengthChange": false,
			"ordering": false,
			ajax: {
				type: "POST",
				url : "{{ route('getSalesAppointmentList') }}",
				data: function(d){

					var date = $('#kt_daterangepicker_salesapp .form-control').val();
					var date_array = date.split(' - ');
					if(typeof date_array[0] !== 'undefined' && date_array[0] != '') {
						date_array[0] = new Date(date_array[0]);
						window.start_date = date_array[0].getFullYear() + '-' + (date_array[0].getMonth() + 1) + '-' + date_array[0].getDate();
					} else {
						window.start_date = '';
					}
					if(typeof date_array[1] !== 'undefined' && date_array[1] != '') {
						date_array[1] = new Date(date_array[1]);
						window.end_date = date_array[1].getFullYear() + '-' + (date_array[1].getMonth() + 1) + '-' + date_array[1].getDate();
					} else {
						window.end_date = '';
					}

                    return $.extend( {}, d, {
						_token : "{{csrf_token()}}",
						location_id : $("#userLocations").val(),
						staff_id : $("#userStaff").val(),
						start_date : window.start_date,
						end_date : window.end_date
					});
				}
			},
			columns: [
				{data: 'ref_no', profile: 'ref_no'},
				{data: 'client_name', name: 'client_name'},
				{data: 'service_name', name: 'service_name'},
				{data: 'appointment_date', name: 'appointment_date'},
				{data: 'appointment_time', name: 'appointment_time'},
				{data: 'duration', name: 'duration'},
				{data: 'location_name', name: 'location_name'},
				{data: 'staff_name', name: 'staff_name'},
				{data: 'price', name: 'price'},
				{data: 'status', name: 'status'},
			]			
		});	
		
		$('#myInputTextField').keyup(function(){
			  window.table.search($(this).val()).draw();
		});

		$("#kt_wrapper").removeClass('nw_setting');
		$(document).on('click','#export', function() {
			var loc = $("#userLocations").val();
			var staff = $("#userStaff").val();
			var datefilter  = $(".kt_date").val();

 			$("#loc").val(loc);
 			$("#sta").val(staff);
 			$("#datefilter").val(datefilter);
			$("#ex").submit();
		});
		$(document).on('click','#csvex', function() {
			var loc = $("#userLocations").val();
			var staff = $("#userStaff").val();
			var datefilter  = $(".kt_date").val();

 			$("#csvloc").val(loc);
 			$("#csvsta").val(staff);
 			$("#csvdatefilter").val(datefilter);
			$("#csv").submit();
		});
		$(document).on('click','#pdfex', function() {
			var loc = $("#userLocations").val();
			var staff = $("#userStaff").val();
			var datefilter  = $(".kt_date").val();

 			$("#pdfloc").val(loc);
 			$("#pdfsta").val(staff);
 			$("#pdfdatefilter").val(datefilter);
			$("#pdf").submit();
		});

		$(document).on('click','#filterApplyBtn',function(){
		
			window.table.search($('#myInputTextField').val()).draw();
		});

		$(document).on('change','#kt_daterangepicker_salesapp .form-control',function(){
			
			window.table.draw();
		});

	});
	 
	window.openNav = function() {
		document.getElementById("myFilter").style.width = "300px";
	}

	window.closeNav = function() {
		document.getElementById("myFilter").style.width = "0%";
		$("#userLocations").val(0);
		$("#userStaff").val(0);
		
		window.table.search($('#myInputTextField').val()).draw();
		/*$('#salesAppointmentList').DataTable().destroy();
		var table = $('#salesAppointmentList').DataTable({
			processing: true,
			serverSide: true,
			"bLengthChange": false,
			"ordering": false,
			ajax: {
				type: "POST",
				url : "{{ route('getSalesAppointmentList') }}",
				data: {_token : "{{csrf_token()}}"}
			},
			columns: [
				{data: 'ref_no', profile: 'ref_no'},
				{data: 'client_name', name: 'client_name'},
				{data: 'service_name', name: 'service_name'},
				{data: 'appointment_date', name: 'appointment_date'},
				{data: 'appointment_time', name: 'appointment_time'},
				{data: 'duration', name: 'duration'},
				{data: 'location_name', name: 'location_name'},
				{data: 'staff_name', name: 'staff_name'},
				{data: 'price', name: 'price'},
				{data: 'status', name: 'status'},
			]			
		});*/	
	}
</script>
@endsection