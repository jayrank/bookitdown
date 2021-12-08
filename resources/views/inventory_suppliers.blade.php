{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<style type="text/css">
	#supplierTable_filter {
	    display: none;
	}
</style>

<div class="content d-flex flex-column flex-column-fluid" id="kt_content"style="margin-top: 14px;margin-bottom: 10px;">
	<!--begin::Tabs-->
	<div class="subheader py-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div
			class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
				role="tablist">
				<li class="nav-item">
					<a class="nav-link" href="{{ route('inventory') }}">Products</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('orders') }}">Orders</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('brands') }}">Brands</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('categories') }}">Categories</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="{{ route('suppliers') }}">Suppliers</a>
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
				<div class="display-service justify-content-between">
					<div class="calender-div my-2">
						<div class="form-group mb-0">
							<div class="input-icon">
								<input type="text" class="font-weight-500 form-control form-control-lg"
									placeholder="Search by Supplier Name" id="myInputTextField">
								<span>
									<i class="flaticon2-search-1 icon-md"></i>
								</span>
							</div>
						</div>
					</div>
					<div class="action-btn-div">
						<a class="font-weight-bold btn btn-primary my-2" href="{{ route('addInventorySupplier') }}">
							New Suppiler
						</a>
					</div>
				</div>
			</div>
			<div class="row my-4">
				<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
					<!--begin::List Widget 3-->
					<div class="card card-custom card-stretch gutter-b p-4">
						<!--begin::Body-->
						<!--begin::Item-->
						<div class="table-responsive supplier-table">
							<table class="table table-hover" id="supplierTable">
								<thead>
									<tr>
										<th>Supplier name</th>
										<th>Phone</th>
										<th>Email</th>
										<th>Products Assigned</th>
										<th>Updated at</th>
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

			<!--end::Sales-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
@endsection
{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('js/addInventoryBrand.js') }}"></script>
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script type="text/javascript">
	$(function() {
		table = $('#supplierTable').DataTable({
			processing: true,
			serverSide: true,
			"paging": true,
			"ordering": true,
			"info":     false,
			'searching' : true,
			ajax: {
				type: "POST",
				url : "{{ route('getInventorySupplier') }}",
				data: {_token : "{{csrf_token()}}"}
			},
			columns: [
				{data: 'supplier_name', name: 'supplier_name'},
				{data: 'mobile', name: 'mobile'},
				{data: 'email', name: 'email'},
				{data: 'assigned', assigned: 'assigned'},
				{data: 'updated_at', name: 'updated_at'},
			]			
		});	
	
		$('#myInputTextField').keyup(function(){
			  table.search($(this).val()).draw();
		});

		$(document).on('click','.editSupplierModal',function(){
			var thisID = $(this).data('id');
			var url = "{{ url('partners/addInventorySupplier') }}";
			if(thisID != ""){
				window.location = url+'/'+thisID;
			}
		});
		$(document).on("click", '#deleteBrand', function (e) {
			
			KTApp.blockPage();
			var form = $(this).parents('form:first');

			$.ajax({
				type: "POST",
				url: "{{ route('deleteBrand') }}",
				dataType: 'json',
				data: form.serialize(),
				success: function (data) {
					KTApp.unblockPage();
					
					if(data.status == true)
					{	
						var table = $('#brandTable').DataTable();
						table.ajax.reload();
						
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

						toastr.success(data.message);
						
					} else {
						table.ajax.reload();
						
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

						toastr.error(data.message);
					}	
				}
			});

		});
	});
</script>
@endsection