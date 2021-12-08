{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

	<!--begin::Header-->
	
	<!--end::Header-->
	<!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
		<!--begin::Tabs-->
		<!-- <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">

		</div> -->
		<!--end::Tabs-->
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Dashboard-->
				<!--begin::Row-->
				<div class="row my-4">
					<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
						<!--begin::List Widget 3-->
						<div class="card-custom card-stretch gutter-b">
							<!--begin::Body-->
							<!--begin::Item-->
							<div class="col-12">
								<div>
									<a class="text-blue" href="{{ route('setup') }}">
										<h4>Back to setup</h4>
									</a>
									<div class="d-flex justify-content-between flex-wrap">
										<div>
											<h2 class="font-weight-bolder">Discount Types</h2>
											<h6 class="text-dark-50">
												Set up manual discount types for 'use' during checkout
											</h6>
										</div>
										<div>
											<button type="button" data-target="#addNewDiscountModal"
												data-toggle="modal"
												class="btn btn-lg btn-primary my-2 font-weight-bolder">
												Add Discount
											</button>
										</div>
									</div>
								</div>
							</div>
							<div class="mt-6 mb-2 col-12">
								<div class="card">
									<div class="card-body p-4">
										<div class="table-responsive">
											<table class="table table-hover" id="distable">
												<tbody id="sortable" class="sortable">
													
												</tbody>
											</table>
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
				<!--end::Dashboard-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>
	<!--end::Content-->

	<!-- Modal -->
	<div class="modal fade" id="addNewDiscountModal" tabindex="-1" role="dialog"
		aria-labelledby="addNewDiscountModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form name="adddiscount" id="adddiscount" action="{{ route('saveDiscount')}}">
				@csrf
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addNewDiscountModalLabel">New Discount</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<h5 class="text-dakr-50">Set the tax name and percentage rate. To apply this to your products and
						services, adjust your tax defaults settings.
					</h5>
					<div class="form-group">
						<label class="font-weight-bolder">Discount name</label>
						<input type="text" class="form-control" name="name" placeholder="e.g Senior Citizens">
					</div>
					<div class="form-group d-flex align-items-center">
						<div class="w-70">
							<label class="font-weight-bolder">Discount value</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-white isPrice" style="display: none;">
										CAD
									</span>
									<span class="input-group-text bg-white isPercent" style="">
										%
									</span>
								</div>
								<input class="form-control" id="discount-input" name="value">
							</div>
						</div>
						<div class="ml-4 mt-3">
							<label></label>
							<div class="tgl-radio-tabs">
								<input id="price" value="price" type="radio"
									class="form-check-input tgl-radio-tab-child" name="prType"
									onclick="getDiscountType()">
								<label for="price" class="radio-inline">CAD</label>

								<input id="percent" value="percent" checked="" type="radio"
									class="form-check-input tgl-radio-tab-child" name="prType"
									onclick="getDiscountType()">
								<label for="percent" class="radio-inline">%</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox-list mt-8">
							<label class="checkbox font-weight-bolder">
								<input checked="" id="is_service" name="is_service" type="checkbox">
								<span></span> Enable for service sales
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox-list mt-8">
							<label class="checkbox font-weight-bolder">
								<input id="is_product" name="is_product" type="checkbox">
								<span></span> Enable for product sales
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox-list mt-8">
							<label class="checkbox font-weight-bolder">
								<input id="is_voucher" name="is_voucher" type="checkbox">
								<span></span> Enable for voucher sales
							</label>
						</div>
					</div>
					<!-- <div class="form-group">
						<div class="checkbox-list mt-8">
							<label class="checkbox font-weight-bolder">
								<input id="is_plan" name="is_plan" type="checkbox">
								<span></span> Enable for voucher sales
							</label>
						</div>
					</div> -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="saveDis">Save</button>
				</div>
			</div>
		</form>
		</div>
	</div>

	<!-- edit Modal -->
	<div class="modal fade" id="editNewDiscountModal" tabindex="-1" role="dialog"
		aria-labelledby="addNewDiscountModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<form name="editdiscount" id="editdiscount" action="{{ route('updateDiscount')}}">
				@csrf
				<input name="id" id="upid" type="hidden">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addNewDiscountModalLabel">Update Discount</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<h5 class="text-dakr-50">Set the tax name and percentage rate. To apply this to your products and
						services, adjust your tax defaults settings.
					</h5>
					<div class="form-group">
						<label class="font-weight-bolder">Discount name</label>
						<input type="text" class="form-control" id="upname" name="name" placeholder="e.g Senior Citizens">
					</div>
					<div class="form-group d-flex align-items-center">
						<div class="w-70">
							<label class="font-weight-bolder">Discount value</label>
							<div class="input-group">
								<div class="input-group-prepend">
									<span class="input-group-text bg-white isPrice" style="display: none;">
										CAD
									</span>
									<span class="input-group-text bg-white isPercent" style="">
										%
									</span>
								</div>
								<input class="form-control" id="upvalue" name="value">
							</div>
						</div>
						<div class="ml-4 mt-3">
							<label></label>
							<div class="tgl-radio-tabs">
								<input id="upprice" value="price" type="radio"
									class="form-check-input tgl-radio-tab-child" name="prType"
									onclick="getDiscountType()">
								<label for="price" class="radio-inline">CAD</label>

								<input id="uppercent" value="percent" type="radio"
									class="form-check-input tgl-radio-tab-child" name="prType"
									onclick="getDiscountType()">
								<label for="percent" class="radio-inline">%</label>
							</div>
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox-list mt-8">
							<label class="checkbox font-weight-bolder">
								<input  id="upis_service" name="is_service" type="checkbox">
								<span></span> Enable for service sales
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox-list mt-8">
							<label class="checkbox font-weight-bolder">
								<input id="upis_product" name="is_product" type="checkbox">
								<span></span> Enable for product sales
							</label>
						</div>
					</div>
					<div class="form-group">
						<div class="checkbox-list mt-8">
							<label class="checkbox font-weight-bolder">
								<input id="upis_voucher" name="is_voucher" type="checkbox">
								<span></span> Enable for voucher sales
							</label>
						</div>
					</div>
					<!-- <div class="form-group">
						<div class="checkbox-list mt-8">
							<label class="checkbox font-weight-bolder">
								<input id="upis_plan" name="is_plan" type="checkbox">
								<span></span> Enable for voucher sales
							</label>
						</div>
					</div> -->
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="updateDis">Update</button>
					
				</div>
			</div>
		</form>
		</div>
	</div>

	<div class="modal" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="taxculationionModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="deleteModalLabel">Change Tax Calculation</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<h5>
						Are you sure you want to delete this discount?
					</h5>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-danger" data-id="" id="deleteDis">Delete</button>
				</div>
			</div>
		</div>
	</div>

	<!--begin::Scrolltop-->
	<div id="kt_scrolltop" class="scrolltop">
		<span class="svg-icon">
			<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
				height="24px" viewBox="0 0 24 24" version="1.1">
				<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<polygon points="0 0 24 0 24 24 0 24" />
					<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
					<path
						d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
						fill="#000000" fill-rule="nonzero" />
				</g>
			</svg>
			<!--end::Svg Icon-->
		</span>
	</div>

@endsection

{{-- Scripts Section --}}
@section("scripts")
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/addDiscount.js') }}"></script>

	<script>
		function getDiscountType() {
			var type = $('input[name="prType"]:checked').val();
			if (type === 'price') {
				$(".isPrice").show();
				$(".isPercent").hide();
			} else {
				$(".isPrice").hide();
				$(".isPercent").show();
			}
		}

		//show table data
		$(document).ready( function(){
			var table = $('#distable').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					type: "POST",
					url : "{{ route('getDisData') }}",
					data: {_token : "{{csrf_token()}}"}
				},
				columns: [
						{data: 'name' , name: 'name'},
				]			
			});	
		});

		$(document).on('click','.editNewDiscountModal',function(){
			var id = $(this).data('id');
			
			$.ajax({
				type: "POST",
				url: "{{ route('getdiscountdata') }}",
				headers:{
							'X-CSRF-Token': '{{ csrf_token() }}',
						},
				data: {id:id},
				success: function (data) {
					console.log(data);
					
					$('#upid').val(data.id);
					$('#upname').val(data.name);
					$('#upvalue').val(data.value);
					
					if(data.prType=='0'){
						$("#uppercent").prop("checked", true);
					} else {
						$("#upprice").prop("checked", true);
					}
					if (data.prType=='1') {
						$(".isPrice").show();
						$(".isPercent").hide();
					} else {
						$(".isPrice").hide();
						$(".isPercent").show();
					}
					if(data.is_service=='1'){
						$("#upis_service").attr('checked', true);
					} else {
						$("#upis_service").attr('checked', false);
					}
					if(data.is_product=='1'){
						$("#upis_product").attr('checked', true);
					} else {
						$("#upis_product").attr('checked', false);
					}
					if(data.is_voucher=='1'){
						$("#upis_voucher").attr('checked', true);
					} else {
						$("#upis_voucher").attr('checked', false);
					}
					if(data.is_plan=='1'){
						$("#upis_plan").attr('checked', true);
					} else {
						$("#upis_plan").attr('checked', false);
					}

					$('#editNewDiscountModal').modal('show');
				},
				error: function(data) {
					var errors = data.responseJSON;
					var errorsHtml = '';
					$.each(errors.error, function(key, value) {
						errorsHtml += '<p>' + value[0] + '</p>';
					});
	
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
	
					toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
					KTUtil.scrollTop();
				}
			});
			//end
			
		});
		
		$(document).on('click','#disdelete', function(){
			var id = $(this).data('id');
			$("#deleteDis").data('id',id);
		});
		//delete
		$(document).on('click','#deleteDis', function(){
			var id = $(this).data('id');

			$.ajax({
				type: "POST",
				url: "{{ route('deleteDisco') }}",
				headers:{
							'X-CSRF-Token': '{{ csrf_token() }}',
						},
				data: {id:id},
				success: function (data) {
					KTApp.unblockPage();
					toastr.error(data.message);
					$('#deleteModal').modal('hide');
					var table = $('#distable').DataTable();
					table.ajax.reload();
				},
				error: function(data) {
					var errors = data.responseJSON;
					var errorsHtml = '';
					$.each(errors.error, function(key, value) {
						errorsHtml += '<p>' + value[0] + '</p>';
					});
	
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
	
					toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
					KTUtil.scrollTop();
				}
			});
		});
		//end
		
			
		$('#addNewDiscountModal').on('hidden.bs.modal', function(){
			$('#adddiscount')[0].reset();
		});
	</script>

@endsection