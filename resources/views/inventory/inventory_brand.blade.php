{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<style type="text/css">
	#brandTable_filter {
	    display: none;
	}
</style>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="margin-top: 14px;margin-bottom: 10px;">

	<div class="modal fade p-0" id="newBrandModal" tabindex="-1" role="dialog" aria-labelledby="newBrandModalLabel Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			<div class="modal-content ">
				<div class="modal-header">
					<h5 class="modal-title font-weight-bold text-center" id="newBrandModalLabel">Add Brand
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
					</button>
				</div>
				{{ Form::open(array('url' => 'partners/addNewBrand','id' => 'addNewBrand')) }}
					<input type="hidden" name="editBrandId" id="editBrandId">
					<div class="modal-body">
						<div class="container">
							<div class="row">
								<div class="form-group w-100">
									<label>Brand name</label>
									<input type="text" class="form-control" name="brand_name" id="brand_name" placeholder="e.g TJ" value="">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<button type="button" id="deleteBrandBtn" data-toggle="modal" data-target="#deleteBrandModal" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal" style="display: none;">Delete</button>
						<div>
							<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary font-weight-bold" id="addNewBrandButton">Save</button>
						</div>
					</div>
				
				{{ Form::close() }}
			</div>
		</div>
	</div>

	<div class="modal fade p-0" id="deleteBrandModal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			{{ Form::open(array('url' => 'partners/delete-supplier','id' => 'delBrand')) }}
				<input type="hidden" name="deleteBrandId" id="deleteBrandId">
				<div class="modal-content ">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center" id="newBrandModalLabel">Delete Brand</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<div class="modal-body">
						<div class="container">
							<div class="row">
								<p>Are you sure you want to delete this brand?</p>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<button type="button" id="deleteBrand" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Delete</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
	
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div
			class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1 scroll">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5 scroll-manu"
				role="tablist">
				<li class="nav-item pl-3">
					<a class="nav-link" href="{{ route('inventory') }}">Products</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('orders') }}">Orders</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="{{ route('brands') }}">Brands</a>
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

	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="content-header ">
				<div class="display-service justify-content-between">
					<div class="calender-div my-2">
						<div class="form-group mb-0 width-100">
							<div class="input-icon">
								<input type="text" class="font-weight-500 form-control form-control-lg width-100"
									placeholder="Search by Brand Name" id="myInputTextField">
								<span>
									<i class="flaticon2-search-1 icon-md"></i>
								</span>
							</div>
						</div>
					</div>
					<div class="action-btn-div width-auto-100">
						<button class="font-weight-bold btn btn-primary my-2 newBrandButton" data-toggle="modal"
							data-target="#newBrandModal">
							New Brand
						</button>
					</div>
				</div>
			</div>
			<div class="row my-4">
				<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
					<div class="card card-custom card-stretch gutter-b p-4">
						<div class="table-responsive brand-table">
							<table class="table table-hover" id="brandTable">
								<thead>
									<tr>
										<th>Brand name</th>
										<th>Products Assigned</th>
										<th>Updated at</th>
									</tr>
								</thead>
								<tbody>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('js/addInventoryBrand.js') }}"></script>
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script type="text/javascript">
	$(function() {
		table = $('#brandTable').DataTable({
			processing: true,
			serverSide: true,
			"paging": true,
			"ordering": true,
			"info":     false,
			// 'searching' : true,
			ajax: {
				type: "POST",
				url : "{{ route('getInventoryBrands') }}",
				data: {_token : "{{csrf_token()}}"}
			},
			columns: [
				{data: 'brand_name', name: 'brand_name'},
				{data: 'assigned', assigned: 'assigned'},
				{data: 'updated_at', name: 'updated_at'},
			]			
		});	
		
		$('#myInputTextField').keyup(function(){
			  table.search($(this).val()).draw();
		});

		
		$(document).on('click','.editBrandModal',function(){
			var editBrandId = $(this).data('id');
			var deleteBrandId = $(this).data('id');
			var editCatName = $(this).data('name');
			$("#newBrandModalLabel").html("Edit Brand");
			$('#editBrandId').val(editBrandId);
			$('#deleteBrandId').val(deleteBrandId);
			$('#brand_name').val(editCatName);
			$('#deleteBrandBtn').show();
			$('#newBrandModal').modal('show');
		});
		$(document).on('click','.newBrandButton',function(){
			$("#brand_name").removeClass('is-valid');
			$("#addNewBrand")[0].reset();
			$("#newBrandModalLabel").html("Add Brand");
			$('#deleteBrandBtn').hide();
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