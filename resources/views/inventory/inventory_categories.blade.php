{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<style type="text/css">
	#categoryTable_filter {
	    display: none;
	}
</style>
<div class="content d-flex flex-column flex-column-fluid" id="kt_content"style="margin-top: 14px;margin-bottom: 10px;">

	<div class="modal fade p-0" id="newCategoryModal" tabindex="-1" role="dialog" aria-labelledby="newCategoryModalLabel Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			{{ Form::open(array('url' => 'partners/addNewCategory','id' => 'addNewCategory')) }}
				<input type="hidden" name="editCatId" id="editCatId">
				<div class="modal-content ">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center" id="newCategoryModalLabel">Add Category</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<div class="modal-body">
						<div class="container">
							<div class="row">
								<div class="form-group w-100">
									<label>Category name</label>
									<input type="text" class="form-control" name="category_name" id="category_name" placeholder="e.g Hair Product">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<button type="button" id="delCatBtn" data-toggle="modal"  data-dismiss="modal" data-target="#deleteCatModal" class="self-align-left btn btn-danger font-weight-bold" style="display: none;">Delete</button>
						<div>
							<button type="button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
							<button type="submit" id="addNewCategoryButton" class="btn btn-primary font-weight-bold">Save</button>
						</div>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>

	<div class="modal fade p-0" id="deleteCatModal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			{{ Form::open(array('url' => '','id' => 'delCategory')) }}
				<input type="hidden" name="delCatId" id="delCatId">
				<div class="modal-content ">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center">Delete Category</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<div class="modal-body">
						<div class="container">
							<div class="row">
								<p>Are you sure you want to delete this category?</p>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<button type="button" id="deleteCategory" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Delete</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
	<!--begin::Tabs-->
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
					<a class="nav-link" href="{{ route('brands') }}">Brands</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="{{ route('categories') }}">Categories</a>
				</li>
				<li class="nav-item">
					<a class="nav-link" href="{{ route('suppliers') }}">Suppliers</a>
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
						<div class="form-group mb-0 width-100">
							<div class="input-icon">
								<input type="text" class="font-weight-500 form-control form-control-lg width-100"
									placeholder="Search by Category Name" id="myInputTextField">
								<span>
									<i class="flaticon2-search-1 icon-md"></i>
								</span>
							</div>
						</div>
					</div>
					<div class="action-btn-div width-auto-100">
						<button class="font-weight-bold btn btn-primary my-2 addCategory" data-toggle="modal" data-target="#newCategoryModal">New Category</button>
					</div>
				</div>
			</div>
			<div class="row my-4">
				<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
					<!--begin::List Widget 3-->
					<div class="card card-custom card-stretch gutter-b p-4">
						<!--begin::Body-->
						<!--begin::Item-->
						<div class="table-responsive category-table">
							<table class="table table-hover" id="categoryTable">
								<thead>
									<tr>
										<th>Category name</th>
										<th class="">Products Assigned</th>
									</tr>
								</thead>
								<!-- <tbody>
									<tr data-toggle="modal" data-target="#editCategoryModal">
										<td>Hair Product</td>
										<td class="text-right">5</td>
									</tr>
									<tr data-toggle="modal" data-target="#editCategoryModal">
										<td>Spa Product</td>
										<td class="text-right">2</td>
									</tr>
									<tr class="text-center text-muted">
										<td colspan="10">Showing 14 of 14 results</td>
									</tr>
								</tbody> -->
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
<!--end::Content-->
@endsection
{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('js/addInventoryCategory.js') }}"></script>
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>	
<script type="text/javascript">
	$(function() {
		table = $('#categoryTable').DataTable({
			processing: true,
			serverSide: true,
			"paging": true,
			"ordering": false,
			"info":     false,
			'searching' : true,
			ajax: {
				type: "POST",
				url : "{{ route('getInventoryCategory') }}",
				data: {_token : "{{csrf_token()}}"}
			},
			columns: [
				{data: 'category_name', name: 'category_name'},
				{data: 'products_assign', products_assign: 'products_assign'},
			]			
		});	
		
		$('#myInputTextField').keyup(function(){
			  table.search($(this).val()).draw();
		});
		
		$(document).on('click','.editCategoryModal',function(){
			var editCatId = $(this).data('id');
			var editCatName = $(this).data('name');
			$("#newCategoryModalLabel").html("Edit Category");
			$('#editCatId').val(editCatId);
			$('#delCatId').val(editCatId);
			$('#category_name').val(editCatName);
			$('#delCatBtn').show();
			$('#newCategoryModal').modal('show');
		});
		
		$(document).on('click','.addCategory',function(){
			$('#editCatId').val('');
			$("#category_name").removeClass('is-valid');
			$("#newCategoryModalLabel").html("Add Category");
			$("#addNewCategory")[0].reset();
			$('#delCatBtn').hide();
		});
		
		$(document).on("click", '#addNewCategoryButton', function (e) {
			// $('#editCatId').val('');
		});
		$(document).on("click", '#deleteCategory', function (e) {
			$("#confirmModal").modal("hide");
			KTApp.blockPage();
			var form = $(this).parents('form:first');
			
			$.ajax({
				type: "POST",
				url: "{{ route('deleteCategory') }}",
				dataType: 'json',
				data: form.serialize(),
				success: function (data) {
					KTApp.unblockPage();
					
					if(data.status == true)
					{	
						var table = $('#categoryTable').DataTable();
						table.ajax.reload();
						$('#newCategoryModal').modal('hide');
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