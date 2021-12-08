{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
	<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
	<style>	
	.editable_row {
		cursor: pointer;
	}
	</style>
@endsection

@section('content')
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
                            <div class="w-100 content-header d-flex flex-wrap justify-content-between">
                                <div class="">
                                    <div class="form-group">
                                        <select class="form-control" name="filter">
                                            @foreach($data as $row)
                                            <option value="{{$row->id}}">{{$row->location_name}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="action-btn-div">
                                    <a data-toggle="modal" data-target="#newResourceModal"
                                        class="btn btn-primary newResourceButton">New Resource</a>
                                </div>
                            </div>
                            <div class="px-8 col-12 py-4 card">
                                <div class="table-responsive">
                                    <table class="table table-hover" id="Resourcetable">
                                        <thead class="thead-light">
                                            <tr class="text-uppercase">
                                                {{-- <td class="icon-reorder ui-sortable-handle" style="width: 100px;"></td> --}}
												
                                                <th scope="col">RESOURCE NAME</th>
                                                <th scope="col">SERVICES ASSIGNED</th>
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
<div class="modal fade" id="newResourceModal" tabindex="-1" role="dialog" aria-labelledby="newResourceModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="newResourceModalLabel">New Resource</h5>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
                {{ Form::open(array('url' => 'partners/setup/resources/add_resources','id' => 'add_resources')) }}
                <input type="hidden" name="editResource" id="editResource">
                <input type="hidden" name="deleteResource" id="deleteResource">	
				<div class="modal-body">
					<ul class="nav nav-tabs nav-tabs-line">
						<li class="nav-item">
							<a class="nav-link active" data-toggle="tab" href="#kt_tab_pane_1">Details</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" data-toggle="tab" href="#kt_tab_pane_2">Services</a>
						</li>
					</ul>
					<div class="tab-content mt-5" id="myTabContent">
						<div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel"
							aria-labelledby="kt_tab_pane_2">
							<div class="form-group">
								<label class="font-weight-bolder">Name</label>
								<input type="text" name="name" id="name" class="form-control" placeholder="e.g Message Room">
							</div>
							<div class="form-group">
								<label class="font-weight-bolder">Description</label>
								<textarea rows="3" name="description" id="description" class="form-control"
									placeholder="Fill in description (Optional)"></textarea>
							</div>
							<div class="form-group">
								<label class="font-weight-bolder">Select Location</label>
								<select class="form-control" name="location_id" id="location_id">
									@foreach($data as $row)
									<option value="{{$row->id}}">{{$row->location_name}}</option>
									@endforeach
								</select>
							</div>
						</div>
						<div class="tab-pane fade" id="kt_tab_pane_2" role="tabpanel" aria-labelledby="kt_tab_pane_2">
							<div class="text-center m-auto my-6">
								<h3>No Services Assigned</h3>
								<p>There are no services that can be booked with this resource yet. You can assign
									services
									to this resource on your
									services page</p>
							</div>
						</div>
					</div>
				</div>
                <div class="modal-footer d-flex justify-content-between">
                    <button type="button" class="self-align-left btn btn-danger deleteData" id="deleteResourceButton">Delete</button>
                    <div>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" id="addNewResourcesubmitbutton">Save</button>
                    </div>
                </div>
                {{ Form::close() }}
			</div>
		</div>
	</div>
@endsection
@section('scripts')
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
	<script src="{{ asset('js/addResources.js') }}"></script>
	<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
	<script>
		$(function () {
			$(".sortable").sortable({
				placeholder: 'placeholder',
				forcePlaceholderSize: true,
				tolerance: 'pointer',
				revert: true,
			});
			$("tr, td").disableSelection();
		});
	</script>	
	<script type="text/javascript">
	$(document).ready( function(){
		//alert("hello");
		var table = $('#Resourcetable').DataTable({
			processing: true,
			serverSide: true,
			"paging": true,
			"ordering": false,
			"info":     false,
			'searching' : true,
			ajax: {
				type: "POST",
				url : "{{ route('get_resources') }}",
				data: {_token : "{{csrf_token()}}"}
			},
			columns: [
					{data: 'name' , name: 'name'},
					{data: 'services_assign' , name: 'services_assign'},
			]			
		});	
	
		$(document).on('click','.editResourceModal',function(){
			
			var editResource = $(this).data('id');
            var editname = $(this).data('name');
			var editdescription = $(this).data('description');
			$("#newResourceModalLabel").html("Edit Resource ");
			$('#editResource').val(editResource);
			$('#deleteResource').val(editResource);
			$('#description').val(editdescription);
			$('#name').val(editname);
			$('.deleteData').show();
			$('#newResourceModal').modal('show');
		});
		
		$(document).on('click','.newResourceButton',function()
		{
			
			$("#add_resources")[0].reset();
			var editResource = $(this).data('id');
			var editdescription = $(this).data('description');
			var editname = $(this).data('name');
			
			$('#editResource').val("");
			$('#deleteResource').val("");
			$('#description').val("");
			$('#name').val("");
			$("#name").removeClass('is-invalid');
			$("#description").removeClass('is-invalid');
			$("#newResourceModalLabel").html("Add Resource");
			$('.deleteData').hide();
		});
		$(document).on("click", '#deleteResourceButton', function (e) {
			$("#add_resources")[0].reset();
			
			KTApp.blockPage();
			var form = $(this).parents('form:first');
			
			$.ajax({
				type: "POST",
				url: "{{ route('delete_resources') }}",
				dataType: 'json',
				data: form.serialize(),
				success: function (data) {
					
					
					if(data.status == true)
					{	
						var table = $('#Resourcetable').DataTable();
						table.ajax.reload();
						$('#newResourceModal').modal('hide');
						
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