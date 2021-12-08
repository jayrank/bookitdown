{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')

@endsection

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Tabs-->
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div
			class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
				role="tablist">
				<li class="nav-item">
					<a class="nav-link active" href="{{ route('analyticsHome') }}">Dashboard</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('reportList') }}">Reports</a>
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
				<div class="d-flex justify-content-between">
					<div class="calender-div my-2 w-70">
						<div class="input-group date">
							<div class="form-group mb-0 mr-2">
								<div class='input-icon' id='kt_daterangepicker_analytics'>
									<input type='text' class="form-control kt_date" readonly placeholder="Select date range" id="kt_datepicker_1" />
									<span class=""><i class="la la-calendar-check-o"></i></span>
								</div>
							</div>
						</div>
					</div>
					<div class="action-btn-div">
						<button class="font-weight-bold btn btn-white my-2" onclick="openNav()">
						Filter <i class="fa fa-filter"></i>
						</button>
					</div>
				</div>
				<div class="row my-4">
					<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
						<!--begin::List Widget 3-->
						<div class="card card-custom bg-transparent card-stretch gutter-b">
							
							@csrf
							<input type="hidden" name="getAnalyticsReportFilter" id="getAnalyticsReportFilter" value="{{ route('getAnalyticsReportFilter') }}">

							<input type="hidden" name="getAnalyticsTotalAppointments" id="getAnalyticsTotalAppointments" value="{{ route('getAnalyticsTotalAppointments') }}">
							<input type="hidden" name="getAnalyticsTotalSales" id="getAnalyticsTotalSales" value="{{ route('getAnalyticsTotalSales') }}">
							<input type="hidden" name="getAnalyticsOccupancy" id="getAnalyticsOccupancy" value="{{ route('getAnalyticsOccupancy') }}">
							<input type="hidden" name="getAnalyticsClientRetention" id="getAnalyticsClientRetention" value="{{ route('getAnalyticsClientRetention') }}">
							
							<!--begin::Body-->
							<!--begin::Item-->
							<div class="row analytics-dashboard">
								<div class="col-md-4" style="position:relative">
									<div class="card">
										<div class="Appointments-loader no-display" style="background: none 0px 0px repeat scroll rgba(238, 238, 238, 0.75); inset: 0px; position: absolute; z-index: 99999999;"><!--  display: none; -->
											<img src="https://scheduledown.com/public/assets/images/aloader.gif" style="position: absolute;top: 40%;left: 40%;width: 20%;">
										</div>
										<div class="card-body">
											<h3 class="font-weight-bolder">Total Appointments</h3>
											<h2 class="TotalAppoCounter">{{ count($TotalAppointments) }}</h2>
											<p><i class="fa fa-minus mr-2 text-dark"></i>0% previous day</p>
											<p class="font-weight-bolder text-muted">
												Completed 
											 	<span class="font-weight-bolder text-dark text- ml-2"><span class="CompletedAppoCounter">{{ count($TotalCompletedAppointments) }}</span> (<span class="TotalCompletedPer">{{  $TotalCompletedPer }}</span>%)</span>
											</p>
											<p class="font-weight-bolder text-muted">
												Not completed
												<span class="font-weight-bolder text-dark text- ml-2"><span class="NotCompletedAppoCounter">{{ count($TotalNotCompletedAppointments) }}</span> (<span class="TotalNotCompletedPer">{{ $TotalNotCompletedPer }}</span>%)</span>
											</p>
											<p class="font-weight-bolder text-muted">
												Canceled
												<span class="font-weight-bolder text-dark text- ml-2"><span class="CancelledAppoCounter">{{ count($TotalCancelledAppointments) }}</span> (<span class="TotalCancelledPer">{{ $TotalCancelledPer }}</span>%)</span>
											</p>
											<p class="font-weight-bolder text-muted">
												No Show
												<span class="font-weight-bolder text-dark text- ml-2"><span class="NoshowAppoCounter">{{ count($TotalNoshowdAppointments) }}</span> (<span class="TotalNoshowPer">{{ $TotalNoshowPer }}</span>%)</span>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-4" style="position:relative">
									<div class="card">
										<div class="Appointments-loader no-display" style="background: none 0px 0px repeat scroll rgba(238, 238, 238, 0.75); inset: 0px; position: absolute; z-index: 99999999;"><!--  display: none; -->
											<img src="https://scheduledown.com/public/assets/images/aloader.gif" style="position: absolute;top: 40%;left: 40%;width: 20%;">
										</div>
										<div class="card-body">
											<h3 class="font-weight-bolder">Online Appointments</h3>
											<h2><span class="TotalOnlineAppoCounter">{{ $TotalOnlineAppoCounter }}</span> (<span class="OnlineAppointmentPercentage">{{ $OnlineAppointmentPercentage }}</span>%)</h2>
											<p><i class="fa fa-minus mr-2 text-dark"></i>0% previous day</p>
											<p class="font-weight-bolder text-muted">
												Completed
												<span class="font-weight-bolder text-dark text- ml-2"><span class="CompletedOnlineAppoCounter">{{ $CompletedOnlineAppoCounter }}</span> (<span class="TotalOnlineCompletedPer">{{ $TotalOnlineCompletedPer }}</span>%)</span>
											</p>
											<p class="font-weight-bolder text-muted">
												Not completed
												<span class="font-weight-bolder text-dark text- ml-2"><span class="NotCompletedOnlineAppoCounter">{{ $NotCompletedOnlineAppoCounter }}</span> (<span class="TotalOnlineNotCompletedPer">{{ $TotalOnlineNotCompletedPer }}</span>%)</span>
											</p>
											<p class="font-weight-bolder text-muted">
												Canceled
												<span class="font-weight-bolder text-dark text- ml-2"><span class="CancelledOnlineAppoCounter">{{ $CancelledOnlineAppoCounter }}</span> (<span class="TotalOnlineCancelledPer">{{ $TotalOnlineCancelledPer }}</span>%)</span>
											</p>
											<p class="font-weight-bolder text-muted">
												No Show
												<span class="font-weight-bolder text-dark text- ml-2"><span class="NoshowOnlineAppoCounter">{{ $NoshowOnlineAppoCounter }}</span> (<span class="TotalOnlineNoshowPer">{{ $TotalOnlineNoshowPer }}</span>%)</span>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-4" style="position:relative">
									<div class="card">
										<div class="Occupancy-loader no-display" style="background: none 0px 0px repeat scroll rgba(238, 238, 238, 0.75); inset: 0px; position: absolute; z-index: 99999999;"><!--  display: none; -->
											<img src="https://scheduledown.com/public/assets/images/aloader.gif" style="position: absolute;top: 40%;left: 40%;width: 20%;">
										</div>
										<div class="card-body">
											<h3 class="font-weight-bolder">Occupancy</h3>
											<h2><span class="occupancy_percentage">0</span>%</h2>
											<p class="font-weight-bolder text-muted">
												<i class="fa fa-minus mr-2 text-dark"></i>0% previous day
											</p>
											<p class="font-weight-bolder text-muted">
												Working Hours
												<span class="font-weight-bolder text-dark text- ml-2 working_hours">0min</span>
											</p>
											<p class="font-weight-bolder text-muted">
												Booked Hours
												<span class="font-weight-bolder text-dark text- ml-2"><span class="booked_hours" >0min</span>(<span class="booked_hours_percentage">0</span>%) </span>
											</p>
											<p class="font-weight-bolder text-muted">
												Blocked Hours
												<span class="font-weight-bolder text-dark text- ml-2"><span class="blocked_hours">0min</span>(<span class="blocked_hours_percentage">0</span>%) </span>
											</p>
											<p class="font-weight-bolder text-muted">
												Unbooked Hours
												<span class="font-weight-bolder text-dark text- ml-2"><span class="unbooked_hours">0min</span>(<span class="unbooked_hours_percentage">0</span>%) </span>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-4" style="position:relative">
									<div class="card">
										<div class="sales-loader no-display" style="background: none 0px 0px repeat scroll rgba(238, 238, 238, 0.75); inset: 0px; position: absolute; z-index: 99999999;"><!--  display: none; -->
											<img src="https://scheduledown.com/public/assets/images/aloader.gif" style="position: absolute;top: 40%;left: 40%;width: 20%;">
										</div>
										<div class="card-body">
											<h3 class="font-weight-bolder">Total Sales</h3>
											<h2>CA $<span class="TotalSale">{{ $TotalSale }}</span></h2>
											<p>
												<i class="fa fa-minus mr-2 text-dark"></i>0% previous day
											</p>
											<p class="font-weight-bolder text-muted">
												Services
												<span class="font-weight-bolder text-dark text- ml-2">CA $<span class="TotalServiceSale">{{ $TotalServiceSale }}</span> (<span class="TotalServiceSalePer">{{ $TotalServiceSalePer }}</span>%)</span>
											</p>
											<p class="font-weight-bolder text-muted">
												Products
												<span class="font-weight-bolder text-dark text- ml-2">CA $<span class="TotalProductSale">{{ $TotalProductSale }}</span> (<span class="TotalProductSalePer">{{ $TotalProductSalePer }}</span>%)</span>
											</p>
											<p class="font-weight-bolder text-muted">
												Late cancellation fees
												<span class="font-weight-bolder text-dark text- ml-2">CA $<span class="TotalLateCancellationFees">{{ $TotalLateCancellationFees }}</span> (<span class="TotalLateCancellationPer">{{ $TotalLateCancellationPer }}</span>%)</span>
											</p>
											<p class="font-weight-bolder text-muted">
												No show fees
												<span class="font-weight-bolder text-dark text- ml-2">CA $<span class="TotalNoShowFees">{{ $TotalNoShowFees }}</span> (<span class="TotalNoShowFeesPer">{{ $TotalNoShowFeesPer }}</span>%)</span>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-4" style="position:relative">
									<div class="card">
										<div class="sales-loader no-display" style="background: none 0px 0px repeat scroll rgba(238, 238, 238, 0.75); inset: 0px; position: absolute; z-index: 99999999;"><!--  display: none; -->
											<img src="https://scheduledown.com/public/assets/images/aloader.gif" style="position: absolute;top: 40%;left: 40%;width: 20%;">
										</div>
										<div class="card-body">
											<h3 class="font-weight-bolder">Average Sale</h3>
											<h2>CA $<span class="AvgTotalSale">{{ $AvgTotalSale }}</span></h2>
											<p>
												<i class="fa fa-minus mr-2 text-dark"></i>0% previous day
											</p>
											<p class="font-weight-bolder text-muted">
												Sales Count
												<span class="font-weight-bolder text-dark text- ml-2"><span class="TotalInvoices">{{ $TotalInvoices }}</span></span>
											</p>
											<p class="font-weight-bolder text-muted">
												Av. Service Sale
												<span class="font-weight-bolder text-dark text- ml-2">CA $<span class="AvgServiceSale">{{ $AvgServiceSale }}</span></span>
											</p>
											<p class="font-weight-bolder text-muted">
												Av. Product Sale
												<span class="font-weight-bolder text-dark text- ml-2">CA $<span class="AvgProductSale">{{ $AvgProductSale }}</span></span>
											</p>
										</div>
									</div>
								</div>
								<div class="col-md-4" style="position:relative">
									<div class="card">
										<div class="ClientRetention-loader no-display" style="background: none 0px 0px repeat scroll rgba(238, 238, 238, 0.75); inset: 0px; position: absolute; z-index: 99999999;"><!--  display: none; -->
											<img src="https://scheduledown.com/public/assets/images/aloader.gif" style="position: absolute;top: 40%;left: 40%;width: 20%;">
										</div>
										<div class="card-body">
											<h3 class="font-weight-bolder">Client Retention (Sales)</h3>
											<h2> <span class="clientRetentionPercentage"></span>%</h2>
											<p>
												<i class="fa fa-minus mr-2 text-dark"></i>0% previous day
											</p>
											<p class="font-weight-bolder text-muted">
												Returning
												<span class="font-weight-bolder text-dark text- ml-2 ">CA $ <span class="clientRetentionReturning">0</span>(<span class="clientRetentionReturningPercentage">0</span>%) </span>
											</p>
											<p class="font-weight-bolder text-muted">
												New
												<span class="font-weight-bolder text-dark text- ml-2">CA $ <span class="clientRetentionNew">0</span>(<span class="clientRetentionNewPercentage">0</span>%) </span>
											</p>
											<p class="font-weight-bolder text-muted">
												Walk-In 
												<span class="font-weight-bolder text-dark text- ml-2">CA $ <span class="clientRetentionWalkIn">0</span>(<span class="clientRetentionWalkInPercentage">0</span>%) </span>
											</p>
										</div>
									</div>
								</div>
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
					<option selected value="">All Location</option>
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
					<option selected value="">All Staff</option>
					@if(!empty($Staff))
						@foreach($Staff as $StaffData)
							<option value="{{ $StaffData['staff_user_id'] }}">{{ $StaffData['first_name'] }} {{ $StaffData['last_name'] }}</option>
						@endforeach
					@endif
				</select>
			</div>
		</div>
		<div class="button-action d-flex justify-content-between px-5">
			<button onclick="closeNav()" class="btn btn-white w-100 mr-4">
				Clear
			</button>
			<button class="btn btn-primary w-100" id="filterApplyAnalyticsBtn">
				Apply
			</button>
		</div>
	</div>
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>
<script>
$(document).ready(function() {
	// $("#kt_wrapper").removeClass('nw_setting');
	var start = moment().subtract(30, "days");
	var end = moment();
	$('#kt_daterangepicker_analytics .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
	// getAnalyticsReportFilter();
	// getAnalyticsTotalSales();
	// getAnalyticsTotalAppointments();
	// getAnalyticsOccupancy();
	// getAnalyticsClientRetention();
	getAnalytics();
});

function openNav() {
	document.getElementById("myFilter").style.width = "300px";
}

function closeNav() {

	document.getElementById("myFilter").style.width = "0%";
	// KTApp.blockPage();
	$("#userLocations").val(0);
	$("#userStaff").val(0);
	// $("#kt_datepicker_1").val('');

	// getAnalyticsReportFilter();
	getAnalytics();
}	

function getAnalyticsReportFilter() {
	KTApp.blockPage();


	var date = $("#kt_daterangepicker_analytics .form-control").val();
	if(date != '') {
		var new1 = date.split('-');
		var startdate = new Date(new1[0]);
		var enddate = new Date(new1[1]);
		var start_date = moment(startdate).format('YYYY-MM-DD');
		var end_date = moment(enddate).format('YYYY-MM-DD');
	} else {
		var new1 = '';
		var startdate = '';
		var enddate = '';
		var start_date = '';
		var end_date = '';
	}

	var location_id = $("#userLocations").val();
	var staff_id = $("#userStaff").val();

	$.ajax({
		type: "POST",
		url: $("#getAnalyticsReportFilter").val(),
		data: { location_id: location_id, staff_id: staff_id, start_date: start_date, end_date: end_date, _token: $("input[name=_token]").val() },
		dataType: 'json',
		success: function(response) {
			KTApp.unblockPage();

			$(".TotalAppoCounter").text(response.returnData.TotalAppoCounter);
			$(".CompletedAppoCounter").text(response.returnData.CompletedAppoCounter);
			$(".TotalCompletedPer").text(response.returnData.TotalCompletedPer);
			$(".NotCompletedAppoCounter").text(response.returnData.NotCompletedAppoCounter);
			$(".TotalNotCompletedPer").text(response.returnData.TotalNotCompletedPer);
			$(".CancelledAppoCounter").text(response.returnData.CancelledAppoCounter);
			$(".TotalCancelledPer").text(response.returnData.TotalCancelledPer);
			$(".NoshowAppoCounter").text(response.returnData.NoshowAppoCounter);
			$(".TotalNoshowPer").text(response.returnData.TotalNoshowPer);

			$(".TotalSale").text(response.returnData.TotalSale);
			$(".TotalServiceSale").text(response.returnData.TotalServiceSale);
			$(".TotalServiceSalePer").text(response.returnData.TotalServiceSalePer);
			$(".TotalProductSale").text(response.returnData.TotalProductSale);
			$(".TotalProductSalePer").text(response.returnData.TotalProductSalePer);
			$(".TotalLateCancellationFees").text(response.returnData.TotalLateCancellationFees);
			$(".TotalLateCancellationPer").text(response.returnData.TotalLateCancellationPer);
			$(".TotalNoShowFees").text(response.returnData.TotalNoShowFees);
			$(".TotalNoShowFeesPer").text(response.returnData.TotalNoShowFeesPer);

			$(".AvgTotalSale").text(response.returnData.AvgTotalSale);
			$(".TotalInvoices").text(response.returnData.TotalInvoices);
			$(".AvgServiceSale").text(response.returnData.AvgServiceSale);
			$(".AvgProductSale").text(response.returnData.AvgProductSale);

			$(".TotalOnlineAppoCounter").text(response.returnData.TotalOnlineAppoCounter);
			$(".CompletedOnlineAppoCounter").text(response.returnData.CompletedOnlineAppoCounter);
			$(".NotCompletedOnlineAppoCounter").text(response.returnData.NotCompletedOnlineAppoCounter);
			$(".CancelledOnlineAppoCounter").text(response.returnData.CancelledOnlineAppoCounter);
			$(".NoshowOnlineAppoCounter").text(response.returnData.NoshowOnlineAppoCounter);
			$(".TotalOnlineCompletedPer").text(response.returnData.TotalOnlineCompletedPer);
			$(".TotalOnlineNotCompletedPer").text(response.returnData.TotalOnlineNotCompletedPer);
			$(".TotalOnlineCancelledPer").text(response.returnData.TotalOnlineCancelledPer);
			$(".TotalOnlineNoshowPer").text(response.returnData.TotalOnlineNoshowPer);
			$(".OnlineAppointmentPercentage").text(response.returnData.OnlineAppointmentPercentage);
			
			$(".working_hours").text(response.returnData.finalTime);
			$(".booked_hours").text(response.returnData.totalBookedHours);
			$(".blocked_hours").text(response.returnData.totalBlockedHours);
			$(".unbooked_hours").text(response.returnData.totalUnBookedHours);
			$(".booked_hours_percentage").text(response.returnData.totalBookedHoursPercentage);
			$(".blocked_hours_percentage").text(response.returnData.totalBlockedHoursPercentage);
			$(".unbooked_hours_percentage").text(response.returnData.totalUnBookedHoursPercentage);
			$(".occupancy_percentage").text(response.returnData.occupancyPercentage);
			$(".clientRetentionReturning").text(response.returnData.returningClient_Total);
			$(".clientRetentionNew").text(response.returnData.newClient_Total);
			$(".clientRetentionWalkIn").text(response.returnData.walk_in_Total);

			$(".clientRetentionReturningPercentage").text(response.returnData.returningClient_Percentage);
			$(".clientRetentionNewPercentage").text(response.returnData.newClient_Percentage);
			$(".clientRetentionWalkInPercentage").text(response.returnData.walk_in_Percentage);
			$(".clientRetentionPercentage").text(response.returnData.returningClient_Percentage);
		}
	});
}
function getAnalyticsTotalSales(){
	// KTApp.blockPage();
	$('.sales-loader').show();


	var date = $("#kt_daterangepicker_analytics .form-control").val();
	if(date != '') {
		var new1 = date.split('-');
		var startdate = new Date(new1[0]);
		var enddate = new Date(new1[1]);
		var start_date = moment(startdate).format('YYYY-MM-DD');
		var end_date = moment(enddate).format('YYYY-MM-DD');
	} else {
		var new1 = '';
		var startdate = '';
		var enddate = '';
		var start_date = '';
		var end_date = '';
	}

	var location_id = $("#userLocations").val();
	var staff_id = $("#userStaff").val();

	$.ajax({
		type: "POST",
		url: $("#getAnalyticsTotalSales").val(),
		data: { location_id: location_id, staff_id: staff_id, start_date: start_date, end_date: end_date, _token: $("input[name=_token]").val() },
		dataType: 'json',
		success: function(response) {
			// KTApp.unblockPage();
			$('.sales-loader').hide();


			$(".TotalSale").text(response.returnData.TotalSale);
			$(".TotalServiceSale").text(response.returnData.TotalServiceSale);
			$(".TotalServiceSalePer").text(response.returnData.TotalServiceSalePer);
			$(".TotalProductSale").text(response.returnData.TotalProductSale);
			$(".TotalProductSalePer").text(response.returnData.TotalProductSalePer);
			$(".TotalLateCancellationFees").text(response.returnData.TotalLateCancellationFees);
			$(".TotalLateCancellationPer").text(response.returnData.TotalLateCancellationPer);
			$(".TotalNoShowFees").text(response.returnData.TotalNoShowFees);
			$(".TotalNoShowFeesPer").text(response.returnData.TotalNoShowFeesPer);

			$(".AvgTotalSale").text(response.returnData.AvgTotalSale);
			$(".TotalInvoices").text(response.returnData.TotalInvoices);
			$(".AvgServiceSale").text(response.returnData.AvgServiceSale);
			$(".AvgProductSale").text(response.returnData.AvgProductSale);
		}
	});

}
function getAnalyticsTotalAppointments(){
	// KTApp.blockPage();
	$('.Appointments-loader').show();


	var date = $("#kt_daterangepicker_analytics .form-control").val();
	if(date != '') {
		var new1 = date.split('-');
		var startdate = new Date(new1[0]);
		var enddate = new Date(new1[1]);
		var start_date = moment(startdate).format('YYYY-MM-DD');
		var end_date = moment(enddate).format('YYYY-MM-DD');
	} else {
		var new1 = '';
		var startdate = '';
		var enddate = '';
		var start_date = '';
		var end_date = '';
	}

	var location_id = $("#userLocations").val();
	var staff_id = $("#userStaff").val();

	$.ajax({
		type: "POST",
		url: $("#getAnalyticsTotalAppointments").val(),
		data: { location_id: location_id, staff_id: staff_id, start_date: start_date, end_date: end_date, _token: $("input[name=_token]").val() },
		dataType: 'json',
		success: function(response) {
			// KTApp.unblockPage();
			$('.Appointments-loader').hide();

			$(".TotalAppoCounter").text(response.returnData.TotalAppoCounter);
			$(".CompletedAppoCounter").text(response.returnData.CompletedAppoCounter);
			$(".TotalCompletedPer").text(response.returnData.TotalCompletedPer);
			$(".NotCompletedAppoCounter").text(response.returnData.NotCompletedAppoCounter);
			$(".TotalNotCompletedPer").text(response.returnData.TotalNotCompletedPer);
			$(".CancelledAppoCounter").text(response.returnData.CancelledAppoCounter);
			$(".TotalCancelledPer").text(response.returnData.TotalCancelledPer);
			$(".NoshowAppoCounter").text(response.returnData.NoshowAppoCounter);
			$(".TotalNoshowPer").text(response.returnData.TotalNoshowPer);

			$(".TotalOnlineAppoCounter").text(response.returnData.TotalOnlineAppoCounter);
			$(".CompletedOnlineAppoCounter").text(response.returnData.CompletedOnlineAppoCounter);
			$(".NotCompletedOnlineAppoCounter").text(response.returnData.NotCompletedOnlineAppoCounter);
			$(".CancelledOnlineAppoCounter").text(response.returnData.CancelledOnlineAppoCounter);
			$(".NoshowOnlineAppoCounter").text(response.returnData.NoshowOnlineAppoCounter);
			$(".TotalOnlineCompletedPer").text(response.returnData.TotalOnlineCompletedPer);
			$(".TotalOnlineNotCompletedPer").text(response.returnData.TotalOnlineNotCompletedPer);
			$(".TotalOnlineCancelledPer").text(response.returnData.TotalOnlineCancelledPer);
			$(".TotalOnlineNoshowPer").text(response.returnData.TotalOnlineNoshowPer);
			$(".OnlineAppointmentPercentage").text(response.returnData.OnlineAppointmentPercentage);
		}
	});

}
function getAnalyticsOccupancy(){
	// KTApp.blockPage();
	$('.Occupancy-loader').show();


	var date = $("#kt_daterangepicker_analytics .form-control").val();
	if(date != '') {
		var new1 = date.split('-');
		var startdate = new Date(new1[0]);
		var enddate = new Date(new1[1]);
		var start_date = moment(startdate).format('YYYY-MM-DD');
		var end_date = moment(enddate).format('YYYY-MM-DD');
	} else {
		var new1 = '';
		var startdate = '';
		var enddate = '';
		var start_date = '';
		var end_date = '';
	}

	var location_id = $("#userLocations").val();
	var staff_id = $("#userStaff").val();

	$.ajax({
		type: "POST",
		url: $("#getAnalyticsOccupancy").val(),
		data: { location_id: location_id, staff_id: staff_id, start_date: start_date, end_date: end_date, _token: $("input[name=_token]").val() },
		dataType: 'json',
		success: function(response) {
			// KTApp.unblockPage();

			$('.Occupancy-loader').hide();

			$(".working_hours").text(response.returnData.finalTime);
			$(".booked_hours").text(response.returnData.totalBookedHours);
			$(".blocked_hours").text(response.returnData.totalBlockedHours);
			$(".unbooked_hours").text(response.returnData.totalUnBookedHours);
			$(".booked_hours_percentage").text(response.returnData.totalBookedHoursPercentage);
			$(".blocked_hours_percentage").text(response.returnData.totalBlockedHoursPercentage);
			$(".unbooked_hours_percentage").text(response.returnData.totalUnBookedHoursPercentage);
			$(".occupancy_percentage").text(response.returnData.occupancyPercentage);
			
		}
	});

}
function getAnalyticsClientRetention(){
	// KTApp.blockPage();
	$('.ClientRetention-loader').show();


	var date = $("#kt_daterangepicker_analytics .form-control").val();
	if(date != '') {
		var new1 = date.split('-');
		var startdate = new Date(new1[0]);
		var enddate = new Date(new1[1]);
		var start_date = moment(startdate).format('YYYY-MM-DD');
		var end_date = moment(enddate).format('YYYY-MM-DD');
	} else {
		var new1 = '';
		var startdate = '';
		var enddate = '';
		var start_date = '';
		var end_date = '';
	}

	var location_id = $("#userLocations").val();
	var staff_id = $("#userStaff").val();

	$.ajax({
		type: "POST",
		url: $("#getAnalyticsClientRetention").val(),
		data: { location_id: location_id, staff_id: staff_id, start_date: start_date, end_date: end_date, _token: $("input[name=_token]").val() },
		dataType: 'json',
		success: function(response) {
			// KTApp.unblockPage();

			$('.ClientRetention-loader').hide();

			$(".clientRetentionReturning").text(response.returnData.returningClient_Total);
			$(".clientRetentionNew").text(response.returnData.newClient_Total);
			$(".clientRetentionWalkIn").text(response.returnData.walk_in_Total);

			$(".clientRetentionReturningPercentage").text(response.returnData.returningClient_Percentage);
			$(".clientRetentionNewPercentage").text(response.returnData.newClient_Percentage);
			$(".clientRetentionWalkInPercentage").text(response.returnData.walk_in_Percentage);
			$(".clientRetentionPercentage").text(response.returnData.returningClient_Percentage);
		}
	});
}

function getAnalytics(){
	getAnalyticsTotalSales();
	getAnalyticsTotalAppointments();
	getAnalyticsOccupancy();
	getAnalyticsClientRetention();
}

</script>
@endsection