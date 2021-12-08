{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
#orderTable_filter{
	display:none;
}
</style>
@endsection

{{-- CSS Section --}}
@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
	<!--begin::Tabs-->
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1 scroll">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5 scroll-manu"
				role="tablist">
				<li class="nav-item pl-3">
					<a class="nav-link" href="{{ route('inventory') }}">Products</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="{{ route('orders') }}">Orders</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('brands') }}">Brands</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('categories') }}">Categories</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('suppliers') }}">Suppliers</a>
				</li>
			</ul>
		</div>
	</div>
	<!--end::Tabs-->
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" style="margin-top: 14px;margin-bottom: 10px;">
		<!--begin::Container-->
		<div class="container">
			<!--begin::nventory-->
			<!--begin::Row-->
			<div class="content-header ">
				<div class="ifProductView">
					<div class="display-investory justify-content-between">
						<div class="calender-div my-2">
							<div class="input-group date">
								<div class="form-group mb-0 width-100">
									<div class="input-icon">
										<input type="text" class="font-weight-500 form-control form-control-lg width-100" placeholder="Search by order or supplier" id="myInputTextField">
										<span>
											<i class="flaticon2-search-1 icon-md"></i>
										</span>
									</div>
								</div>
							</div>
						</div>
						<div class="action-btn-div width-300px">
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
								<div class="dropdown-menu dropdown-menu-xs">
									<!--begin::Navigation-->
									<ul class="navi flex-column navi-hover py-2">
										<li class="navi-item">
											<a href="{{ route('ordersexcel') }}" class="navi-link">
												<span class="navi-icon">
													<i class="la la-file-excel-o"></i>
												</span>
												<span class="navi-text">Excel</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="{{ route('orderscsv') }}" class="navi-link">
												<span class="navi-icon">
													<i class="la la-file-text-o"></i>
												</span>
												<span class="navi-text">CSV</span>
											</a>
										</li>
										{{-- <li class="navi-item">
											<a href="#" class="navi-link">
												<span class="navi-icon">
													<i class="la la-file-pdf-o"></i>
												</span>
												<span class="navi-text">PDF</span>
											</a>
										</li> --}}
									</ul>
									<!--end::Navigation-->
								</div>
								<!--end::Dropdown Menu-->
							</div>
							<button class="font-weight-bold btn btn-white"
								onclick="openNav()">
								Filter <i class="fa fa-filter"></i>
							</button>
							<a class="font-weight-bold btn btn-primary py-3"
								href="{{ route('createOrder') }}">
								New Order
							</a>
						</div>
					</div>
				</div>
			</div>
			<div class="row my-4">
				<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
					<!--begin::List Widget 3-->
					<div class="card card-custom card-stretch gutter-b p-4">
						<!--begin::Body-->
						<!--begin::Item-->
						<div class="table-responsive brand-table">
							<table class="table table-hover" id="orderTable">
								<thead>
									<tr>
										<th>Order No.</th>
										<th>Created Date</th>
										<th>Supplier</th>
										<th>Status</th>
										<th>Total Cost</th>
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
			<!--end::Row-->
			<!-- Filter Sidebar -->
			<div id="myFilter" class="filter-overlay" style="width: 0px;">
				<a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
				<div class="filter-header pt-2">
					<h2 class="text-center py-3">Filter</h2>
				</div>
				<form id="filterOrder" name="filterOrder">
					<div class="filter-overlay-content">
						<div class="filter-overlay-content p-4">
							<div class="form-group font-weight-bold mb-0">
								<label for=" select-location font-weight-bold">Brand</label>
								<select class="form-control" name="status">
									<option value="1">Ordered</option>
									<option value="2">Received</option>
									<option value="3">Cancelled</option>
								</select>
							</div>
							<div class="form-group font-weight-bold mt-4 mb-0">
								<label for=" select-location font-weight-bold">Supplier</label>
								<select class="form-control" id="select-sup" name="supp">
									@foreach($ProductSupplier as $value)
										<option value="{{ $value->id }}">{{ $value->supplier_name }}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="button-action d-flex justify-content-between px-5">
							<button onclick="closeNav()" class="btn btn-white w-100 mr-4">
								Clear
							</button>
							<button type="button" onclick="closeNav()" class="btn btn-primary w-100" id="apply">
								Apply
							</button>
						</div>
					</div>
				</form>
			</div>
			<!-- End Filter Sidebar -->
			<!--end::nventory-->
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
<script>
	$(function() {
		var table = $('#orderTable').DataTable({
			processing: true,
			serverSide: true,
			"ordering": true,
			"info":     false,
			ajax: {
				type: "POST",
				url : "{{ route('getOrders') }}",
				data: {_token : "{{csrf_token()}}"}
			},
			columns: [
				{data: 'id', name: 'id'},
				{data: 'created_at', name: 'created_at'},
				{data: 'supplier_name', name: 'supplier_name'},
				{data: 'order_status', name: 'order_status'},
				{data: 'order_total', name: 'order_total'},
			]			
		});	
		
		$('#myInputTextField').keyup(function(){
			  table.search($(this).val()).draw();
		});
	});
	
	$(document).on('click','.editInventoryOrder',function(){
		var thisID = $(this).data('id');
		var url = "{{ url('partners/order/view/') }}";
		if(thisID != "" && typeof thisID !== "undefined") {
			window.location = url+'/'+thisID;
		}
	});

	function openNav() {
		document.getElementById("myFilter").style.width = "300px";
	}

	function closeNav() {
		document.getElementById("myFilter").style.width = "0%";
	}

	$(document).on('click','#apply',function(){
		var data = $('#filterOrder').serialize();
		//
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		});
		$.ajax({
			type:'POST',
           	url:"{{ route('filterorder') }}",
           	data: data,
			success: function (data) {
				KTApp.unblockPage();
				html = '';
				for(var j=0;j < data.Data.length; j++) {
					var url = 'https://schedulethat.tjcg.in/partners/order/view/'+data.Data[j].id;
					html +='<tr>'
					   +'<td><a href="'+url+'" class="text-blue cursor-pointer">P'+data.Data[j].id+'</a></td>'
					   +'<td>'+data.Data[j].created_at+'</td>'
					   +'<td>'+data.Data[j].supplier_name+'</td>'
						if(data.Data[j].order_status == 1){
							html +='<td><span class="badge badge-pill badge-warning">Ordered</span></td>'
						} else if(data.Data[j].order_status == 2){
							html +='<td><span class="badge badge-pill badge-success">Received</span></td>'
						} else if(data.Data[j].order_status == 3){
							html +='<td><span class="badge badge-pill badge-danger">Cancelled</span></td>'
						} else {
							html +='<td><span class="badge badge-pill badge-danger">N/A</td>'
						}
						html +='<td>'+data.Data[j].order_total+'</td>'
						html +='</tr>';
				}
				if(data.Data.length==0){
					tableB = $("table tbody").empty();
					tableBody = $("table tbody");
					tableBody.append('<tr><td> No results found </td></tr>');
					$('#orderTable_paginate').remove();

				} else {
					tableB = $("table tbody").empty();
					tableBody = $("table tbody");
					tableBody.append(html);
					$('#orderTable_paginate').remove();
				}
			}
		});
	});


</script>
@if(Session::has('message'))
<script>
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

	toastr.success('{{ Session::get('message') }}');
</script>
@endif

@endsection