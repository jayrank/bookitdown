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
			<div class="row">
				<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
					<!--begin::List Widget 3-->
					<div class="card-stretch gutter-b">
						<!--begin::Body-->
						<!--begin::Item-->
						<div class="content-header" style="margin-top:-3.2%">
							<div class="mb-3">
								<a onclick="history.back()" class="font-size-lg text-blue cursor-pointer"><i class="fa fa-chevron-left text-blue mr-2"></i>Reports</a>
								<h3 class="my-3 font-weight-bolder">Finances summary</h3>
							</div>
							<div class="d-flex justify-content-between">
								<div class="calender-div my-2">
									<div class="input-group date">
										<div class="form-group mb-0 mr-2">
											<div class='input-icon' id='kt_daterangepicker_6'>
												<input type='text' class="form-control kt_date" readonly placeholder="Select date range" />
												<span class=""><i class="la la-calendar-check-o"></i></span>
											</div>
										</div>
									</div>
								</div>
								<div class="action-btn-div">
									<div class="dropdown dropdown-inline mr-2">
										<button type="button" class="btn btn-white my-2 font-weight-bolder dropdown-toggle my-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
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
											</span>Export
										</button>
										<!--begin::Dropdown Menu-->
										<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
											<!--begin::Navigation-->
											<ul class="navi flex-column navi-hover py-2">
												<li class="navi-item">
													<a href="#" class="navi-link getFinancesSummaryExcel">
														<span class="navi-icon">
															<i class="la la-file-excel-o"></i>
														</span>
														<span class="navi-text">Excel</span>
													</a>
												</li>
												<li class="navi-item">
													<a href="#" class="navi-link getFinancesSummaryCSV">
														<span class="navi-icon">
															<i class="la la-file-text-o"></i>
														</span>
														<span class="navi-text">CSV</span>
													</a>
												</li>
												<li class="navi-item">
													<a href="#" class="navi-link getFinancesSummaryPDF">
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
									<button class="font-weight-bold btn btn-white my-2" onclick="openNav()">Filter <i class="fa fa-filter"></i>
									</button>
								</div>
							</div>
						</div>
						<div class="row reports">
							<div class="col-md-6">
								<div class="card">
									<div class="card-body">
										<h3 class="font-weight-bolder">Sales</h3>
										<ul class="list-group summary">
											<li class="border-0 list-group-item d-flex justify-content-between align-items-center font-weight-bolder">
												Gross sales
												<span class="">
													CA $<span class="gross-total"></span>
												</span>
											</li>
											<li class="border-0 list-group-item d-flex justify-content-between align-items-center">
												Discounts
												<span class="">CA $<span class="total-discount"></span></span>
											</li>
											<li class="border-0 list-group-item d-flex justify-content-between align-items-center">
												Refunds
												<span class="">CA $<span class="total-refunds"></span></span>
											</li>
											<li class="border-0 list-group-item d-flex justify-content-between align-items-center font-weight-bolder">
												Net sales
												<span class="">CA $<span class="net-sales"></span></span>
											</li>
											<li class="border-0 list-group-item d-flex justify-content-between align-items-center">
												Taxes
												<span class="">CA $<span class="total-taxes"></span></span>
											</li>
											<li class="total border-0 list-group-item d-flex justify-content-between align-items-center font-weight-bolder">
												Total sales
												<span class="">CA $<span class="total-sales"></span></span>
											</li>
										</ul>
									</div>
								</div>

								<div class="card">
									<div class="card-body">
										<h3 class="font-weight-bolder">Vouchers</h3>
										<ul class="list-group summary">
											<li
												class="border-0 list-group-item d-flex justify-content-between align-items-center">
												Voucher Sale
												<span class="">
													CA $<span class="financeSummaryVoucherSale"></span>
												</span>
											</li>
											<li
												class="border-0 list-group-item d-flex justify-content-between align-items-center">
												Voucher redemptions
												<span class="">
													CA $<span class="financeSummaryVoucherRedemption"></span>
												</span>
											</li>
											<li class="border-0 list-group-item d-flex justify-content-between align-items-center">
												Vouchers outstanding balance
												<span class="">
													<span class="financeSummaryVoucherOutstanding"></span>
												</span>
											</li>
										</ul>
									</div>
								</div>
							</div>
							<div class="col-md-6">
								<div class="card">
									<div class="card-body">
										<h3 class="">Payments</h3>
										<ul class="list-group summary payment-wise-total">
											<li
												class="border-0 list-group-item d-flex justify-content-between align-items-center">
												Other
												<span class="">
													CA $55.00
												</span>
											</li>
											<li
												class="border-0 list-group-item d-flex justify-content-between align-items-center">
												Cash
												<span class="">
													CA $16.00
												</span>
											</li>
											<li
												class="total border-0 list-group-item d-flex justify-content-between align-items-center font-weight-bolder">
												<a class="text-blue" href="payment_summary.html">Total
													Payments</a>
												<span class="">
													CA $71.00
												</span>
											</li>
										</ul>
									</div>
								</div>

								<div class="card">
									<div class="card-body">
										<h3 class="font-weight-bolder">Tips</h3>
										<ul class=" list-group summary">
											<li class="border-0 list-group-item d-flex justify-content-between align-items-center">
												Tips collected
												<span class="">
													<span class="totalTip"></span>
												</span>
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
		</div>
		<div class="button-action d-flex justify-content-between px-5">
			<button onclick="clearNav()" class="btn btn-white w-100 mr-4">
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
<!-- <script src="{{ asset('js/application.js') }}"></script> -->
<script>
$(document).ready(function() {
	$("#kt_wrapper").removeClass('nw_setting');

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

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

	var start = moment().subtract(29, 'days');
	var end = moment();
	$('#kt_daterangepicker_6 .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));
	
	getFinancesSummary();

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
	}, function(start, end, label) {
	    $('#kt_daterangepicker_6 .form-control').val(start.format('MM/DD/YYYY') + ' - ' + end.format('MM/DD/YYYY'));

        getFinancesSummary();
	});

	$(document).on('click','#filterApplyAnalyticsBtn', function(){
		getFinancesSummary();
		closeNav();
	});

	$(document).on('click','.getFinancesSummaryPDF', function(){
		KTApp.blockPage();
		var date = $(".kt_date").val();
		var location_id = $("#userLocations").val();
		
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

		if (date != '') {
			var fileName = 'finances_summary_' + start_date + '_' + end_date + '.pdf';
		} else {
			var fileName = 'finances_summary.pdf';
		}

		$.ajax({
			url: '{{ route("getFinancesSummaryPDF") }}',
			type: 'POST',
			data: {
				start_date: start_date,
				end_date: end_date,
				location_id: location_id
			},
			xhrFields: {
				responseType: 'blob'
			},
			success: function(response) {
				toastr.success((response.message) ? response.message : "PDF Downloading...");

				var blob = new Blob([response]);
				var link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = fileName;
				link.click();
			},
			error: function(response) {
				toastr.error("PDF Not Downloding!! Something Went Wrong!");
			},
			complete: function(response) {
				KTApp.unblockPage();
			}
		});
	});

	$(document).on('click','.getFinancesSummaryCSV', function(){
		KTApp.blockPage();
		var date = $(".kt_date").val();
		var location_id = $("#userLocations").val();
		
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

		if (date != '') {
			var fileName = 'finances_summary_' + start_date + '.csv';
		} else {
			var fileName = 'finances_summary.csv';
		}

		$.ajax({
			url: '{{ route("getFinancesSummaryCSV") }}',
			type: 'POST',
			data: {
				start_date: start_date,
				end_date: end_date,
				location_id: location_id
			},
			xhrFields: {
				responseType: 'blob'
			},
			success: function(response) {
				toastr.success((response.message) ? response.message : "CSV Downloading...");

				var blob = new Blob([response]);
				var link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = fileName;
				link.click();
			},
			error: function(response) {
				toastr.error("CSV Not Downloding!! Something Went Wrong!");
			},
			complete: function(response) {
				KTApp.unblockPage();
			}
		});
	});

	$(document).on('click','.getFinancesSummaryExcel', function(){
		KTApp.blockPage();
		var date = $(".kt_date").val();
		var location_id = $("#userLocations").val();
		
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

		if (date != '') {
			var fileName = 'finances_summary_' + start_date +'.xlsx';
		} else {
			var fileName = 'finances_summary.xlsx';
		}

		$.ajax({
			url: '{{ route("getFinancesSummaryExcel") }}',
			type: 'POST',
			data: {
				start_date: start_date,
				end_date: end_date,
				location_id: location_id
			},
			xhrFields: {
				responseType: 'blob'
			},
			success: function(response) {
				toastr.success((response.message) ? response.message : "Excel Downloading...");

				var blob = new Blob([response]);
				var link = document.createElement('a');
				link.href = window.URL.createObjectURL(blob);
				link.download = fileName;
				link.click();
			},
			error: function(response) {
				toastr.error("Excel Not Downloding!! Something Went Wrong!");
			},
			complete: function(response) {
				KTApp.unblockPage();
			}
		});
	});
});

function getFinancesSummary() {
    KTApp.blockPage();

    var date = $(".kt_date").val();
	// console.log(date);
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

	$.ajax({
		url: '{{ route("getFinancesSummary") }}',
		type: 'POST',
		dataType: 'JSON',
		data: {
			start_date: start_date,
			end_date: end_date,
			location_id: location_id
		},
		success: function(response) {
			if(response.status) {
				$('.gross-total').text(response.data['grossTotal']);
				$('.total-discount').text(response.data['totalDiscount']);
				$('.total-refunds').text(response.data['totalRefunds']);
				$('.net-sales').text(response.data['netSales']);
				$('.total-taxes').text(response.data['totalTaxes']);
				$('.total-sales').text(response.data['totalSales']);
				$('.financeSummaryVoucherSale').text(response.totalVoucherSale);
				$('.financeSummaryVoucherRedemption').text(response.voucherRedemption);
				$('.totalTip').text(response.totalTip);
				$('.financeSummaryVoucherOutstanding').text(response.totalVoucherOutstanding);


				var paymentWiseTotalContent = '';
				var paymentTotal = 0;
				for(index = 0; index < response.data['paymentWiseTotal'].length; index++) {
					paymentWiseTotalContent += '<li class="border-0 list-group-item d-flex justify-content-between align-items-center">';
						paymentWiseTotalContent += response.data['paymentWiseTotal'][ index ]['payment_type'];
						paymentWiseTotalContent += '<span class="">CA $'+parseFloat(response.data['paymentWiseTotal'][ index ]['total']).toFixed(2)+'</span>';
					paymentWiseTotalContent += '</li>';

					paymentTotal += parseFloat(response.data['paymentWiseTotal'][ index ]['total']);
				}

				paymentWiseTotalContent += '<li class="total border-0 list-group-item d-flex justify-content-between align-items-center font-weight-bolder">';
					paymentWiseTotalContent += 'Total Payments';
					paymentWiseTotalContent += '<span class="">CA $'+parseFloat(paymentTotal).toFixed(2)+'</span>';
				paymentWiseTotalContent += '</li>';

				$('.payment-wise-total').html(paymentWiseTotalContent);
			} else {
				toastr.error((response.message) ? response.message : "Something went wrong!");
			}
		},
		error: function(response) {
			toastr.error("Something went wrong!");
		},
		complete: function(response) {
            KTApp.unblockPage();
		}
	})
}

function openNav() {
	document.getElementById("myFilter").style.width = "300px";
}

function closeNav() {
	document.getElementById("myFilter").style.width = "0%";
}

function clearNav() {
	$('#userLocations').val('');
	document.getElementById("myFilter").style.width = "0%";
	
	getFinancesSummary();
}
</script>
@endsection