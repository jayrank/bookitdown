{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
#salesVoucher_filter{
	display:none;
}
</style>
@endsection
@section('content')

<div class="modal fade p-0" id="viewVoucherServices" tabindex="-1" role="dialog" aria-labelledby="viewVoucherServicesModalLabel Label" aria-modal="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold text-center" id="decreaseStockModalLabel">Redeemable services</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
				</button>
			</div>
			<div class="modal-body" id="internalBody">
				
			</div>
		</div>
	</div>
</div>

<div class="flex-column-fluid" id="kt_content">
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1 scroll">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5 scroll-manu" role="tablist">
				@if (Auth::user()->can('daily_sales'))
					<li class="nav-item pl-3">
						<a class="nav-link" href="{{ route('dailySale') }}">Daily Sales</a>
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
					<li class="nav-item active">
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
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class="container">
			<!--begin::Sales-->
			<!--begin::Row-->
			<div class="content-header ">
				<div class="display-service justify-content-between my-2">
					<div class="search-div my-2">
						<div class="input-group date">
							<div class="form-group mb-0 width-100">
								<div class="input-icon">
									<input type="text" class="font-weight-500 form-control form-control form-control-lg width-100" placeholder="Search by Code or Client" id="myInputTextField">
									<span><i class="flaticon2-calendar-4 icon-md"></i></span>
								</div>
							</div>
						</div>
					</div>
					<div class="action-btn-div width-auto">
						<div class="dropdown dropdown-inline mr-2">
							<button type="button" class="btn btn-white font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
							<span class="svg-icon svg-icon-md">
								<!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
								<svg xmlns="http://www.w3.org/2000/svg">
									<g><path d="M15.072 9.62c.506 0 .911.405.911.912v4.962a.908.908 0 0 1-.911.911H.962c-.506 0-.945-.405-.945-.911v-4.962c0-.507.439-.912.945-.912s.911.405.911.912v4.017H14.16v-4.017c0-.507.405-.912.912-.912z"></path><path d="M7.376 11.68L3.662 7.965a.878.878 0 0 1 0-1.282.878.878 0 0 1 1.283 0l2.16 2.126V.911c0-.506.406-.911.912-.911s.911.405.911.911v7.9l2.127-2.127a.917.917 0 0 1 1.316 0 .878.878 0 0 1 0 1.282L8.658 11.68a.922.922 0 0 1-.641.27.922.922 0 0 1-.641-.27z"></path>
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>Export</button>
							<!--begin::Dropdown Menu-->
							<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
								<!--begin::Navigation-->
								<ul class="navi flex-column navi-hover py-2">
									<li class="navi-item" id="export">
										<a href="javascript:void(0)" class="navi-link">
											<form name="ex" id="ex" action="{{ route('vouchersdownloadExcel') }}" method="POST">
												@csrf
												<span class="navi-icon">
													<i class="la la-file-excel-o"></i>
												</span>
												<span class="navi-text">Excel</span>
												<input type="hidden" name="vouchers" id="loc">
											</form>
										</a>
									</li>
									<li class="navi-item" id="csvex">
										<a href="javascript:void(0)" class="navi-link">
											<form name="csv" id="csv" action="{{ route('vouchersdownloadcsv') }}" method="POST">
												@csrf
												<span class="navi-icon">
													<i class="la la-file-text-o"></i>
												</span>
												<span class="navi-text">CSV</span>
												<input type="hidden" name="vouchers" id="csvloc">
											</form>
										</a>
									</li>
									<li class="navi-item" id="pdfex">
										<a href="javascript:void(0)" class="navi-link">
											<span class="navi-icon">
												<i class="la la-file-pdf-o"></i>
											</span>
											<span class="navi-text">PDF</span>
											<form name="pdf" id="pdf" action="{{ route('vouchersdownloadpdf') }}" method="POST">
												@csrf
												<input type="hidden" name="vouchers" id="pdfloc">
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
							<div class="table-responsive vouchers">
							
								<input type="hidden" name="getSalesVoucherList" id="getSalesVoucherList" value="{{ route('getSalesVoucherList') }}">
								<input type="hidden" name="getVoucherServices" id="getVoucherServices" value="{{ route('getVoucherServices') }}">
								@csrf
							
								<table cellspacing="10" cellpadding="10" class="table table-responsive" id="salesVoucher">
									<thead>
										<tr>
											<th scope="col">Issue date</th>
											<th scope="col">Expiry date</th>
											<th scope="col">Invoice no.</th>
											<th scope="col">Client</th>
											<th scope="col">Type</th>
											<th scope="col">Status</th>
											<th scope="col">Code</th>
											<th scope="col">Total</th>
											<th scope="col">Redeemed</th>
											<th scope="col">Remaining</th>
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
				<label for=" select-location font-weight-bold">Status</label>
				<select class="form-control" id="voucherStatus" name="voucherStatus">
					<option selected value="all">All statuses</option>
					<option value="unpaid">Unpaid</option>
					
					<option value="valid">Valid</option>
					<option value="expired">Expired</option>
					<option value="redeemed">Redeemed</option>
					<option value="refundedinvoice">Refunded Invoice</option>
					<option value="voidedinvoice">Voided Invoice</option>
				</select>
			</div>
		</div>
		<div class="button-action d-flex justify-content-between px-5">
			<button onclick="closeNav()" class="btn btn-white w-100 mr-4" type="button">Clear</button>
			<button class="btn btn-primary w-100" type="submit" id="filterApplyVoucherBtn">Apply</button>
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
		window.table = $('#salesVoucher').DataTable({
			processing: true,
			serverSide: true,
			"bLengthChange": false,
			"ordering": false,
			ajax: {
				type: "POST",
				url : "{{ route('getSalesVoucherList') }}",
				data: function(d){
                    return $.extend( {}, d, {
						_token : "{{csrf_token()}}",
						voucher_status : $("#voucherStatus").val()
					});
				}
			},
			columns: [
				{data: 'issue_date', profile: 'issue_date'},
				{data: 'expiry_date', name: 'expiry_date'},
				{data: 'invoice_no', name: 'invoice_no'},
				{data: 'client_name', name: 'client_name'},
				{data: 'voucher_type', name: 'voucher_type'},
				{data: 'voucher_status', name: 'voucher_status'},
				{data: 'voucher_code', name: 'voucher_code'},
				{data: 'voucher_total', name: 'voucher_total'},
				{data: 'redeemed_amount', name: 'redeemed_amount'},
				{data: 'remaining_amount', name: 'remaining_amount'},
			]			
		});	
		
		$('#myInputTextField').keyup(function(){
			  window.table.search($(this).val()).draw();
		});

		$(document).on('click','#filterApplyVoucherBtn',function(){
			window.table.search($('#myInputTextField').val()).draw();
		});

		$("#kt_wrapper").removeClass('nw_setting');

		$(document).on('click','#export', function() {
			var loc = $("#voucherStatus").val();

 			$("#loc").val(loc);
			$("#ex").submit();
		});

		$(document).on('click','#csvex', function() {

			var loc = $("#voucherStatus").val();

 			$("#csvloc").val(loc);
			$("#csv").submit();
		});
		$(document).on('click','#pdfex', function() {
			var loc = $("#voucherStatus").val();

 			$("#pdfloc").val(loc);
			$("#pdf").submit();
		});
	});
	 
	window.openNav = function() {
		document.getElementById("myFilter").style.width = "300px";
	}

	window.closeNav = function() {
		document.getElementById("myFilter").style.width = "0%";
		$("#voucherStatus").val(0);
		window.table.search($('#myInputTextField').val()).draw();
		
		/*$('#salesVoucher').DataTable().destroy();
		var table = $('#salesVoucher').DataTable({
			processing: true,
			serverSide: true,
			"bLengthChange": false,
			"ordering": false,
			ajax: {
				type: "POST",
				url : "{{ route('getSalesVoucherList') }}",
				data: {_token : "{{csrf_token()}}"}
			},
			columns: [
				{data: 'issue_date', profile: 'issue_date'},
				{data: 'expiry_date', name: 'expiry_date'},
				{data: 'invoice_no', name: 'invoice_no'},
				{data: 'client_name', name: 'client_name'},
				{data: 'voucher_type', name: 'voucher_type'},
				{data: 'voucher_status', name: 'voucher_status'},
				{data: 'voucher_code', name: 'voucher_code'},
				{data: 'voucher_total', name: 'voucher_total'},
				{data: 'redeemed_amount', name: 'redeemed_amount'},
				{data: 'remaining_amount', name: 'remaining_amount'},
			]			
		});	*/
	}
</script>
@endsection