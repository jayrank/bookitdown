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
							<div class="row">
								<div
									class="w-100 mb-4 content-header d-flex flex-wrap justify-content-between">
									<div class="">

									</div>
									<div class="action-btn-div">
										<a data-toggle="modal" data-target="#addReferralSources"
											class="btn btn-primary">New Referral Source</a>
									</div>
								</div>
								<div class="px-8 col-12 py-4 card">
									<div class="table-responsive">
										<table class="table table-hover" id="referralSour">
											<thead class="thead-light">
												<tr class="text-uppercase">
													<th scope="col"></th>
													<th scope="col">NAME</th>
													<th scope="col">DATE ADDED</th>
													<th scope="col">STATUS</th>
													<!-- <th></th> -->
												</tr>
											</thead>
											<tbody id="sortable" class="sortable">
												
											</tbody>
										</table>
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
	<div class="modal fade" id="addReferralSources" tabindex="-1" role="dialog"
		aria-labelledby="addReferralSourcesLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="addReferralSourcesLabel">Add Referral Source</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<form name="referralSources" id="referralSources" action="{{ route('saveReferral') }}" >
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<label class="font-weight-bolder">NAME</label>
							<input type="text" class="form-control" name="name" placeholder="e.g Facebook">
						</div>
						<div class="form-group">
							<div class="mt-4 switch switch-icon switch-success" style="line-height: 33px;">
								<label class="d-flex align-item-center font-weight-bolder">
									<input type="checkbox" checked="checked" name="is_active">
									<span></span>&nbsp;&nbsp;&nbsp;Active
								</label>
							</div>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
						<div>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary" id="saverefe" >Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!-- edit Modal -->
	<div class="modal fade" id="editReferralSources" tabindex="-1" role="dialog"
		aria-labelledby="editReferralSourcesLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="editReferralSourcesLabel">Edit Referral Source</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<form name="editReferral" id="editReferral" action="{{ route('updateReferral') }}">
					@csrf
					<div class="modal-body">
						<div class="form-group">
							<label class="font-weight-bolder">NAME</label>
							<input name="id" id="upid" type="hidden">
							<input type="text" class="form-control" name="name" id="upname" placeholder="e.g Facebook">
						</div>
						<div class="form-group">
							<div class="mt-4 switch switch-icon switch-success" style="line-height: 33px;">
								<label class="d-flex align-item-center font-weight-bolder">
									<input type="checkbox" name="is_active" id="upis_active">
									<span></span>&nbsp;&nbsp;&nbsp;<p id="showA"></p>
								</label>
							</div>
						</div>
					</div>
					<div class="modal-footer justify-content-between">
						<div>
							<button type="button" class="btn btn-danger" id="deleteref" data-id="">Delete</button>
						</div>
						<div>
							<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
							<button type="submit" class="btn btn-primary" id="updaterefe">Update</button>
						</div>
					</div>
				</form>
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
	<!--end::Scrolltop-->
@endsection
{{-- Scripts Section --}}
@section("scripts")
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/addReferral.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

	<script>
		$(function () {
			$(".sortable").sortable({
				placeholder: 'placeholder',
				forcePlaceholderSize: true,
				tolerance: 'pointer',
				revert: true,
				update: function() {
					saveServiceOrder();
				}
			});
			$("tr, td").disableSelection();

			function saveServiceOrder() {
				var order = [];
				$('#sortable .sortindex').each(function(index,element) {
				  order.push({
					id: $(this).attr('data-id'),
					position: index+1
				  });
				});
				console.log(order);
				$.ajax({
				  	type: "POST", 
				  	dataType: "json", 
				  	headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
				 	url: "{{ route('referralorder') }}",
					data: {
						order: order,
					},
					success: function(response) {
						if (response.status == "success") {
							console.log(response);
						} else {
							console.log(response);
						}
					}
				});
			}
		});

		//show table data
		$(document).ready( function(){
			var table = $('#referralSour').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					type: "POST",
					url : "{{ route('getreferral') }}",
					data: {_token : "{{csrf_token()}}"}
				},
				columns: [
						{data: 'user_id' , name: 'user_id'},
						{data: 'name' , name: 'name'},
						{data: 'date' , name: 'date'},
						{data: 'is_active' , name: 'is_active'},
						// {data: 'id' , name: 'id'},
				]			
			});	
		});

		//edit referral
		$(document).on('click','.editReferralSources',function(){
			var id = $(this).data('id');
			
			$.ajax({
				type: "POST",
				url: "{{ route('getEditReferralData') }}",
				headers:{
							'X-CSRF-Token': '{{ csrf_token() }}',
						},
				data: {id:id},
				success: function (data) {
					console.log(data);
					
					$('#upid').val(data.id);
					$('#upname').val(data.name);
					$('#deleteref').data('id',data.id);
					
					if(data.is_active=='1'){
						$("#upis_active").attr("checked", true);
						$('#showA').text('Active');
					} else {
						$("#upis_active").attr("checked", false);
						$('#showA').text('INACTIVE');
					}

					$('#editReferralSources').modal('show');
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

		//delete referral
		$(document).on('click','#deleteref', function(){
			var id = $(this).data('id');

			$.ajax({
				type: "POST",
				url: "{{ route('deleteRefe') }}",
				headers:{
							'X-CSRF-Token': '{{ csrf_token() }}',
						},
				data: {id:id},
				success: function (data) {
					KTApp.unblockPage();
					toastr.error(data.message);
					$('#editReferralSources').modal('hide');
					var table = $('#referralSour').DataTable();
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
	</script>
@endsection