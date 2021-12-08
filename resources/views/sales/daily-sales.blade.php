{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')

<!--style>
#clientList_filter{
	display:none;
}
</style-->
@endsection

@section('content')
<div class="flex-column-fluid" id="kt_content">
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1 scroll">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5 scroll-manu" role="tablist">
				@if (Auth::user()->can('daily_sales'))
					<li class="nav-item">
						<a class="nav-link active" href="{{ route('dailySale') }}">Daily Sales</a>
					</li>
				@endif	
				@if (Auth::user()->can('sales_appointments'))
					<li class="nav-item">
						<a class="nav-link" href="{{ route('appointmentsList') }}">Appointments</a>
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
			<div class="content-header ">
				<div class="display-investory justify-content-between">
					<div class="calender-div my-2">
						<div class="form-group mb-0">
							<div class="input-icon" id="kt_daterangepicker_dailysales">
								<input type="text" class="form-control kt_date margin-auto" id="kt_datepicker_1" readonly="readonly" placeholder="Select date">
								<span class=""><i class="la la-calendar-check-o"></i></span>
							</div>
							
							@csrf
							<input type="hidden" name="getDailySalesFilter" id="getDailySalesFilter" value="{{ route('getDailysaleFilter') }}">
							<input type="hidden" name="getDailySalesPDF" id="getDailySalesPDF" value="{{ route('getDailySalesPDF') }}">
							<input type="hidden" name="dailySalesExcelExport" id="dailySalesExcelExport" value="{{ route('dailySalesExcelExport') }}">
							<input type="hidden" name="dailySalesCsvExport" id="dailySalesCsvExport" value="{{ route('dailySalesCsvExport') }}">
							
						</div>
					</div>
					<div class="action-btn-div width-auto">
						<div class="dropdown dropdown-inline">
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
									<li class="navi-item">
										<a href="javascript:;" class="navi-link dailySalesExcelExport">
											<span class="navi-icon">
												<i class="la la-file-excel-o"></i>
											</span>
											<span class="navi-text">Excel</span>
										</a>
									</li>
									<li class="navi-item">
										<a href="javascript:;" class="navi-link dailySalesCsvExport">
											<span class="navi-icon">
												<i class="la la-file-text-o"></i>
											</span>
											<span class="navi-text">CSV</span>
										</a>
									</li>
									<li class="navi-item">
										<a href="javascript:;" class="navi-link generateDailySales">
											<span class="navi-icon">
												<i class="la la-file-pdf-o"></i>
											</span>
											<span class="navi-text">PDF</span>
										</a>
									</li>
								</ul>
								<!--end::Navigation-->
							</div>
							<!--end::Dropdown Menu-->
						</div>
						<button class="btn btn-white font-weight-bold" onclick="openNav()">
							Filter <i class="fa fa-filter"></i>
						</button>
					</div>
				</div>
				<div class="row my-4" id="loadTransactionSummary">
					<div class="col-lg-6 col-xxl-4 order-1 order-xxl-2">
						<!--begin::List Widget 3-->
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Header-->
							<div class="card-header border-0 px-5">
								<h3 class="card-title font-weight-bolder text-dark">Transaction Summary
								</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class=" pt-2">
								<!--begin::Item-->
								<div class="d-flex align-items-center p-2">
									<table class="table">
										<thead>
											<tr>
												<th scope="col">Item type</th>
												<th scope="col">Sales qty</th>
												<th scope="col">Refund qty</th>
												<th scope="col">Gross total</th>
											</tr>
										</thead>
										<tbody>
											<tr>
												<td>Services</td>
												<td>{{ ($TotalServices) ? $TotalServices : 0 }}</td>
												<td>{{ ($TotalRefundServices) ? $TotalRefundServices : 0 }}</td>
												<td>CA ${{ ($TotalServicesAmount) ? number_format($TotalServicesAmount,2) : 0 }}</td>
											</tr>
											<tr>
												<td>Products</td>
												<td>{{ ($TotalProducts) ? $TotalProducts : 0 }}</td>
												<td>{{ ($TotalRefundProducts) ? $TotalRefundProducts : 0 }}</td>
												<td>CA ${{ ($TotalProductsAmount) ? number_format($TotalProductsAmount,2) : 0 }}</td>
											</tr>
											<tr>
												<td>Vouchers</td>
												<td>{{ ($TotalVouchers) ? $TotalVouchers : 0 }}</td>
												<td>{{ ($TotalRefundVouchers) ? $TotalRefundVouchers : 0 }}</td>
												<td>CA ${{ ($TotalVouchersAmount) ? number_format($TotalVouchersAmount,2) : 0 }}</td>
											</tr>
											<tr>
												<td>Paid Plans</td>
												<td>{{ ($TotalPaidplan) ? $TotalPaidplan : 0 }}</td>
												<td>{{ ($TotalRefundPaidplan) ? $TotalRefundPaidplan : 0 }}</td>
												<td>CA ${{ ($TotalPaidplanAmount) ? number_format($TotalPaidplanAmount,2) : 0 }}</td>
											</tr>
											<tr class="font-weight-bold">
												<td>Total</td>
												<td>{{ $TotalAllThing }}</td>
												<td>{{ $TotalAllRefundThing }}</td>
												<td>CA ${{ number_format($TotalAllAmount,2) }}</td>
											</tr>
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
						<!--begin::List Widget 3-->
						<div class="card card-custom card-stretch gutter-b">
							<!--begin::Header-->
							<div class="card-header border-0 px-5">
								<h3 class="card-title font-weight-bolder text-dark">Cash Movement Summary</h3>
							</div>
							<!--end::Header-->
							<!--begin::Body-->
							<div class="pt-2">
								<!--begin::Item-->
								<div class="d-flex align-items-center p-2">
									<table class="table">
										<thead>
											<tr>
												<th scope="col">Payment type</th>
												<th scope="col">Payments collected </th>
												<th scope="col">Refunds paid</th>
											</tr>
										</thead>
										<tbody>
											@if(!empty($paymentByTotal))
												@foreach($paymentByTotal as $paymentByTotalData)
													<tr>
														<td>{{ $paymentByTotalData['payment_method'] }}</td>
														<td>CA ${{ number_format($paymentByTotalData['total_collected'],2) }}</td>
														<td>CA ${{ number_format($paymentByTotalData['total_refunded'],2) }}</td>
													</tr>
												@endforeach
											@endif
											<tr>
												<td>Voucher Redemptions</td>
												<td>CA ${{ number_format($totalVoucherRedemption,2) }}</td>
												<td>CA ${{ number_format($totalRefundVoucherRedemption,2) }}</td>
											</tr>
											<tr class="font-weight-bold">
												<td>Payments collected</td>
												<td>CA ${{ number_format($totalPaymentCollected,2) }}</td>
												<td>CA ${{ number_format($totalPaymentRefunded,2) }}</td>
											</tr>
											<tr class="font-weight-bold">
												<td>Of which tips</td>
												<td>CA ${{ number_format($totalTips,2) }}</td>
												<td>CA ${{ number_format($totalRefundedTips,2) }}</td>
											</tr>
										</tbody>
									</table>
								</div>
								<!--end:Item-->

							</div>
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
	<a href="javascript:void(0)" class="closebtn" onClick="closeNavWithoutClearing()">×</a>
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
		</div>
		<div class="button-action d-flex justify-content-between px-5">
			<button onclick="closeNav()" class="btn btn-white w-100 mr-4" type="button">Clear</button>
			<button class="btn btn-primary w-100" type="submit" id="filterApplyDailysaleBtn">Apply</button>
		</div>
	</div>
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('assets/js/pages/crud/forms/widgets/bootstrap-daterangepicker.js') }}"></script>
<script src="{{ asset('js/sales.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>
<script>
	$(document).ready(function() {
		$("#kt_wrapper").removeClass('nw_setting');
	});
	
	window.openNav = function() {
		document.getElementById("myFilter").style.width = "300px";
	}

	window.closeNavWithoutClearing = function() {
		document.getElementById("myFilter").style.width = "0%";
	}

	window.closeNav = function() {
		document.getElementById("myFilter").style.width = "0%";
		$("#userLocations").val(0);
		$("#kt_datepicker_1").val('');
		
		$.ajax({
			type: "POST",
			url: $("#getDailySalesFilter").val(),
			data: {_token : $("input[name=_token]").val()},
			success: function (response) 
			{
				$("#loadTransactionSummary").html(response.htmldata);
				KTApp.unblockPage();
			}
		});	
	}
</script>		
@endsection