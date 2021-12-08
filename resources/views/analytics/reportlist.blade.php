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
		<div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5" role="tablist">
				<li class="nav-item">
					<a class="nav-link" href="{{ route('analyticsHome') }}">Dashboard</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="{{ route('reportList') }}">Reports</a>
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
			<div class="row my-4">
				<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
					<!--begin::List Widget 3-->
					<div class="card card-custom bg-transparent card-stretch gutter-b">
						<!--begin::Body-->
						<!--begin::Item-->
						<div class="row analytics-report">
							<div class="col-md-6">
								<div class="card">
									<div class="card-body">
										<h3 class="">Finances</h3>
										<p>Monitor your overall finances including sales, refunds, taxes, payments and more</p>
										<ul class="p-0">
											<li><a class="" href="{{ route('financesSummary') }}">Finances summary </a></li>
											<li><a class="" href="{{ route('paymentsSummary') }}">Payments summary</a></li>
											<li><a class="" href="{{ route('paymentsLog') }}">Payments log</a></li>
											<li><a class="" href="{{ route('taxesSummary') }}">Taxes summary</a></li>
											<li><a class="" href="{{ route('tipCollections') }}">Tips collected</a></li>
											<li><a class="" href="{{ route('discountsSummary') }}">Discount summary</a></li>
											<li><a class="" href="{{ route('outstandingInvoices') }}">Outstanding invoices</a></li>
										</ul>
									</div>
								</div>
								<div class="card">
									<div class="card-body">
										<h3 class="">Inventory</h3>
										<p>Monitor product stock levels and adjustments made, analyse product sales performance, consumption costs and more</p>
										<ul class="p-0">
											<li><a class="" href="{{ route('stockOnHand') }}">Stock on hand</a></li>
											<li><a class="" href="{{ route('productSalesPerformance') }}">Product sales performance</a></li>
											<li><a class="" href="{{ route('stockMovementLog') }}">Stock movement log</a></li>
											<!-- <li><a class="" href="./reports/stock_movements_summary.html">Stock movement summary</a></li> -->
											<li><a class="" href="{{ route('productConsumption') }}">Product consumption</a></li>
										</ul>
									</div>
								</div>
								<div class="card">
									<div class="card-body">
										<h3 class="">Appointments</h3>
										<p>View projected revenues of upcoming appointments, track cancellation rates and reasons</p>
										<ul class="p-0">
											<li><a class="" href="{{ route('appointmentsList') }}">Appointments list </a></li>
											<!-- <li><a class="" href="./reports/appoinment_summary.html">Appointments summary</a></li> -->
											<li><a class="" href="{{ route('appointmentCancellations') }}">Appointment cancellations</a></li>
										</ul>
									</div>
								</div>
								<div class="card">
									<div class="card-body">
										<h3 class="">Clients</h3>
										<p>Gain insights into how clients interact with your business and who your top spenders are</p>
										<ul class="p-0">
											<li><a class="" href="{{ route('clientsList') }}">Client list</a></li>
											<li><a class="" href="{{ route('clientRetention') }}">Client retention</a></li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="card-body">
										<h3 class="">Sales</h3>
										<p>Analyse the performance of your business by comparing sales across products, staff, channels and more
										</p>
										<ul class="p-0">
											<li><a class="" href="{{ route('salesByItem') }}">Sales by item</a></li>
											<!-- <li><a class="" href="./reports/sales_by_type.html">Sales by type</a></li> -->
											<li><a class="" href="{{ route('salesByService') }}">Sales by service</a></li>
											<li><a class="" href="{{ route('salesByProduct') }}">Sales by product</a></li>
											<li><a class="" href="{{ route('salesByLocation') }}">Sales by location</a></li>
											<!-- <li><a class="" href="./reports/sales_by_channel.html">Sales by channel</a> </li> -->
											<li><a class="" href="{{ route('salesByClient') }}">Sales by client</li>
											<!-- <li><a class="" href="./reports/sales_by_breakdown.html">Sales by staff breakdown</a></li> -->
											<li><a class="" href="{{ route('salesByStaff') }}">Sales by staff</a></li>
											<!-- <li><a class="" href="./reports/sales_by_hour.html">Sales by hour</a></li> -->
											<!-- <li><a class="" href="./reports/sales_by_hour_of_day.html">Sales by hour of day</a></li> -->
											<li><a class="" href="{{ route('salesByDay') }}">Sales by day</a></li>
											<!-- <li><a class="" href="./reports/sales_by_quarter.html">Sales by quarter</a></li> -->
											<li><a class="" href="{{ route('salesByMonth') }}">Sales by month</a></li>
											<li><a class="" href="{{ route('salesByYear') }}">Sales by year</a></li>
											<!-- <li><a class="" href="./reports/sales_by_log.html">Sales by log</a></li> -->
										</ul>
									</div>
								</div>
								<!-- <div class="card">
									<div class="card-body">
										<h3 class="">Vouchers</h3>
										<p>Track your total outstanding liability as well as voucher
											sales and redemption activity
										</p>
										<ul class="p-0">
											<li><a class=""
												href="./reports/vouchers_outstanding_balance.html">Vouchers
												outstanding balance</a>
											</li>
											<li><a class="" href="./reports/voucher_sales.html">Voucher
												sales</a>
											</li>
											<li><a class=""
												href="./reports/voucher_redemptions.html">Voucher
												redemptions</a>
											</li>
										</ul>
									</div>
								</div> -->
								<div class="card">
									<div class="card-body">
										<h3 class="">Staff</h3>
										<p>View your team's performance, hours worked as well as
											commission and tip earnings
										</p>
										<ul class="p-0">
											<li><a class=""
												href="{{ route('staffWorkingHours') }}">Staff
												working hours</a>
											</li>
											<li><a class="" href="{{ route('tipsByStaff') }}">Tips by
												staff</a>
											</li>
											<li><a class="" href="{{ route('staffCommission') }}">Staff
												commission summary</a>
											</li>
											<li><a class="" href="{{ route('staffCommissionDetailed') }}">Staff
												commission detailed</a>
											</li>
										</ul>
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
			<!--end::Row-->
			<!--end::Sales-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<!--end::Content-->
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('js/application.js') }}"></script>
<script>
$(document).ready(function() {
	$("#kt_wrapper").removeClass('nw_setting');
});
</script>
@endsection