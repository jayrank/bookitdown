{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
	<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<style>	
	.editable_row {
		cursor: pointer;
	}
	</style>
@endsection
@section('content')
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
									<a data-toggle="modal" data-target="#addResonModal"
										class="btn btn-primary">New Cancellation Reason</a>
								</div>
							</div>
							<div class="px-8 col-12 py-4 card">
								<div class="table-responsive">
									<table class="table table-hover" id="cancellationReasonTable">
										<thead class="thead-light">
											<tr class="text-uppercase">
												<th scope="col">NAME</th>
												<th scope="col">DATE ADDED</th>
												<th></th>
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

<div class="modal fade" id="addResonModal" tabindex="-1" role="dialog" aria-labelledby="addResonModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="addCancellationReason" method="POST" action="{{ route('addCancellationReason') }}">
				@csrf
				<div class="modal-header">
					<h5 class="modal-title" id="addResonModalLabel">New Cancellation Reason</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="font-weight-bolder">NAME</label>
						<input type="text" class="form-control" placeholder="e.g Reason" name="reason" id="reason">
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<div>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="addReasonBtn">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade" id="editResonModal" tabindex="-1" role="dialog" aria-labelledby="editResonModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<form id="editCancellationReason" method="POST" action="{{ route('editCancellationReason') }}">
				
				@csrf
				<input type="hidden" name="reasonId" id="reasonId">
			
				<div class="modal-header">
					<h5 class="modal-title" id="editResonModalLabel">Edit Cancellation Reason</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<div class="form-group">
						<label class="font-weight-bolder">NAME</label>
						<input type="text" class="form-control" placeholder="e.g Reason" name="editReason" id="editReason">
					</div>
				</div>
				<div class="modal-footer justify-content-between">
					<div>
						<button type="button" class="btn btn-danger" id="deleteReasonBtn">Delete</button>
					</div>
					<div>
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="submit" class="btn btn-primary" id="editReasonBtn">Save</button>
					</div>
				</div>
			</form>
		</div>
	</div>
</div>

<div class="modal fade p-0" id="deleteResonModal" tabindex="-1" role="dialog" aria-labelledby="deleteResonModalLabel" aria-modal="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold text-center" id="deleteResonModalLabel">Delete Cancellation Reason</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
				</button>
			</div>
			<form method="POST" action="{{ route('deleteCancellationReason') }}" id="deleteCancellationReasonFrom">
				
				@csrf
				<input type="hidden" name="deleteReasonId" id="deleteReasonId">
				
				<div class="modal-body">
					<div class="container">
						<div class="row">
							<p>Are you sure you want to delete this Cancellation Reason?</p>
						</div>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<button type="submit" id="deleteCancellationReasonBtn" class="self-align-left btn btn-danger font-weight-bold">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>

@endsection
@section('scripts')
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('js/addCancellationReason.js') }}"></script>

<script type="text/javascript">
$(document).ready( function(){
	var table = $('#cancellationReasonTable').DataTable({
		processing: true,
		serverSide: true,
		"bLengthChange": false,
		"ordering": true,
		"searching" : false,
		ajax: {
			type: "POST",
			url : "{{ route('cancellationReasonList') }}",
			data: {_token : "{{csrf_token()}}"}
		},
		columns: [
			{data: 'reason' , name: 'reason'},
			{data: 'created_at' , name: 'created_at'},
			{data: 'is_default', name: 'is_default'},
		]			
	});	
	
	$(document).on('click','.editCancellationReason',function(){
		var reasonId   = $(this).attr('data-reasonId');	
		var reason     = $(this).attr('data-reason');	
		var is_default = $(this).attr('data-is_default');	
		
		$("#editResonModal").modal('show');
		$("#reasonId").val(reasonId);
		$("#deleteReasonId").val(reasonId);
		$("#editReason").val(reason);
		
		if(is_default == 1){
			$("#editReason").attr('readonly',true);
			$("#deleteReasonBtn").hide();
		} else {
			$("#editReason").attr('readonly',false);
			$("#deleteReasonBtn").show();
		}
	});
	
	$(document).on('click','#deleteReasonBtn',function(){
		$("#editResonModal").modal('hide');
		$("#deleteResonModal").modal('show');
	});
});

</script>	
@endsection