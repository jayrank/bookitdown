{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
	<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />
	<style>	
		.editable_row {
			cursor: pointer;
		}
		/* CHECKBOXES CODE*/
		input[type=checkbox] {appearance: none;
		        -webkit-appearance: none;margin-right: 10px;}
		input[type=checkbox]:before {
		         
		         content: "";
		         display: block;border-radius: 5px;
		         position: absolute;
		         width: 20px;
		         height: 20px;
		         top: 0;
		         left: 0;
		         background-color:#e9e9e9;
		}
		input[type=checkbox]:checked:before {
		         content: "";
		         display: block;border-radius: 5px;
		         position: absolute;
		         width: 20px;
		         height: 20px;
		         top: 0;
		         left: 0;
		         background-color:#1E80EF;
		}
		    input[type=checkbox]:checked:after {
		         content: "";
		         display: block;
		         width: 5px;
		         height: 11px;
		         border: solid white;
		         border-width: 0 2px 2px 0;
		         -webkit-transform: rotate(45deg);
		         -ms-transform: rotate(45deg);
		         transform: rotate(45deg);
		         position: absolute;
		         top: 3px;
		         left: 7px;
		}
		.permission_div {
		         padding-left: 12px !important;
		}

		/* CHECKBOXES CODE*/

	</style>
@endsection

@section('content')
<div class="content d-flex flex-column flex-column-fluid" id="kt_content" >
	<!--begin::Tabs-->
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1 scroll">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5 scroll-manu" role="tablist">
				@if (Auth::user()->can('working_hours'))
					<li class="nav-item pl-3">
						<a class="nav-link {{ request()->is('staff') ? 'active' : '' }}" href="{{ url('partners/staff') }}">Staff Working Hours</a>
					</li>
				@endif
				@if (Auth::user()->can('closed_dates'))	
					<li class="nav-item">
						<a class="nav-link {{ request()->is('staff_closed') ? 'active' : '' }}" href="{{ url('partners/staff_closed') }}">Closed Dates</a>
					</li>
				@endif
				@if (Auth::user()->can('staff_members'))
					<li class="nav-item">
						<a class="nav-link {{ request()->is('staff_members') ? 'active' : '' }}" href="{{ url('partners/staff_members') }}">Staff Members</a>
					</li>
				@endif
				@if (Auth::user()->can('permission_levels'))					
					<li class="nav-item">
						<a class="nav-link {{ request()->is('getUserPermission') ? 'active' : '' }}" href="{{ route('getUserPermission') }}">User Permissions</a>
					</li>
				@endif	
			</ul>
		</div>
	</div>
	<!--end::Tabs-->
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid mt-4">
		<!--begin::Container-->
		<div class="container">
			<!--begin::Sales-->
			<!--begin::Row-->
			<div class="content-header">
				<div class="d-flex justify-content-between">
					<div class="">

					</div>
					<div class="">
						<button class="font-weight-bold btn btn-primary newCloseButton" data-toggle="modal" data-target="#newCloseDateModalCenter">
							New CLosed Date
						</button>
					</div>
				</div>
			</div>
			<div class="row my-2">
				<div class="col-sm-12 col-lg-12 col-xxl-12">
					<!--begin::List Widget 3-->
					<div class="">
						<!--begin::Body-->
						<div class="pt-2">
							<!--begin::Item-->
							<div class="d-flex align-items-center p-2">
								<div class="table-responsive closed-date">
									<table class="table table-hover datatable" id="ClosedDate">
										<thead class="thead-light">
											<tr>
												<th scope="col">DATE RANGE</th>
												<th scope="col">NO. OF DAYS</th>
												<th scope="col">LOCATION</th>
												<th scope="col">DESCRIPTION</th>
											</tr>
										</thead>
										<tbody>
											
										</tbody>
									</table>
								</div>
							</div>
							<!--end:Item-->

						</div>
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
<!-- Modal -->
<div class="modal fade p-0 newCloseDateModalCenter" id="newCloseDateModalCenter" tabindex="-1" role="dialog"
	aria-labelledby="newCloseDateModalCenterTitle" aria-hidden="true">
	<div class="modal-dialog modal-dialog-centered" role="document">
		
		<div class="modal-content">
			<div class="modal-header flex-center">
				<h5 class="font-weight-normal modal-title" id="newCloseDateModalLongTitle">New Close
					Date</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			
			{{ Form::open(array('url' => 'partners/add_staff_closed','id' => 'add_staff_closed')) }}
			<input type="hidden" name="editstaff" id="editstaff">	
			<div class="modal-body">
				<div class="alert alert-blue" role="alert">
					Online bookings can not be placed during closed dates.
				</div>
			
				<div class="row">
					<div class="col-6 pr-0">
						<div class="form-group">
							<label class="text-uppercase font-weight-bold">Start Date</label>
							<input type="text" placeholder="Select Start Date" id="startNewCloseDatePicker"  name="start_date" autocomplete="off" readonly="readonly" class="form-control date-input">
						</div>
					</div>
					<div class="col-6 pr-0">
						<div class="form-group">
							<label class="text-uppercase font-weight-bold">End Date</label>
							<input type="text" placeholder="Select End Date" id="endNewCloseDatePicker"  name="end_date" autocomplete="off" readonly="readonly" class="form-control date-input">
						</div>
					</div>
					<div class="permission_div">
						<div class="form-group">
							<label class="text-uppercase font-weight-bold">Description</label>
							<textarea class="form-control" rows="2" name="description" id="description" placeholder="Add Description"></textarea>
						</div>
					</div>
					<div class="col-12 my-4">
					<?php
					if($location)
					{
					?>
						<div class="col-12 my-4">
							<div class="form-group">
								<input type="checkbox" id="all_permission"  name="all_permission" autocomplete="off" readonly="readonly" class="boolean optional commonPermission" value="1">
								<label class="string optional" for="closed_date_all_locations">All Locations</label>
							</div>
						</div>
						<?php
						foreach ($location as $locData)
						{
						?>
							<div class="col-12 my-4">
								<div class="form-group">
									<input type="checkbox" id="all_permission_<?php echo $locData['id'] ?>" name="permission[]" autocomplete="off" readonly="readonly" class="boolean optional location_permission commonPermission" data-total-loc="<?php echo count($location); ?>" value="<?php echo $locData['id'] ?>">
									<label class="string optional" for="closed_date_all_locations"><?php echo $locData['location_name'] ?></label>
								</div>
							</div>
						<?php
						}
						?>
					<?php
					}
					else
					{
						if(!empty($location))
						{
						?>
						<input type="hidden" name="permission[]" value="<?php echo $location[0]['id'] ?>">
						<?php
						}
					}
					?>
					</div>
				</div>
			</div>
			<div class="modal-footer d-flex justify-content-between">
				<button type="button" data-toggle="modal" data-target="#deleteCatModal" class="self-align-left btn btn-danger deleteData">Delete</button>
				<div>
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
					<button type="submit" class="btn btn-primary" id="addnewcloseddate">Save changes</button>
				</div>
			</div>
		</div>
		{{ Form::close() }}
	</div>
</div>
<div class="modal fade p-0" id="deleteCatModal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-modal="true">
	<div class="modal-dialog " role="document">
		{{ Form::open(array('url' => '','id' => 'delcloseddate')) }}
			<input type="hidden" name="delstaff" id="delstaff">
			<div class="modal-content ">
				<div class="modal-header">
					<h5 class="modal-title font-weight-bold text-center">Delete Closed Date</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
					</button>
				</div>
				<div class="modal-body">
					<div class="container">
						<div class="row">
							<p>Are you sure you want to delete closed date?</p>
						</div>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<button type="button" id="deletecloseddate" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Delete</button>
				</div>
			</div>
		{{ Form::close() }}
	</div>
</div>

@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('js/addStaffClosed.js') }}"></script>
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>


	
	<script type="text/javascript">
	$(function() {
		var table = $('#ClosedDate').DataTable({
			processing: true,
			serverSide: true,
			"paging": true,
			"ordering": false,
			"info":     false,
			'searching' : true,
			ajax: {
				type: "POST",
				url : "{{ route('getclosed_date') }}",
				data: {_token : "{{csrf_token()}}"}
			},
			columns: [
				{data: 'DATE_RANGE'},
					{data: 'NO_OF_DAYS'},
					{data: 'location_id'},
					{data: 'description', name: 'description'},
			]			
		});	
	
		$(document).on('click','.editCloseDateModalCenter',function(){
			
			var editstaff = $(this).data('id');
			var editdescription = $(this).data('description');
			var editstartdate = $(this).data('start_date');
			var editenddate = $(this).data('end_date');
			var allLocation = $(this).data('all-location');
			var locationId = $(this).data('location-id');
			$("#newCloseDateModalLongTitle").html("Edit Closed Date");
			$('#editstaff').val(editstaff);
			$('#delstaff').val(editstaff);
			$('#description').val(editdescription);
			$('#startNewCloseDatePicker').val(editstartdate);
			$('#endNewCloseDatePicker').val(editenddate);
			// console.log(locationId);
			
			$('.commonPermission').prop('checked',false);

			if (typeof locationId !== 'undefined') {
				if($.isNumeric(locationId) == false)
				{
					var locationIdArr = locationId.split(',');
					for (var i = 0; i < locationIdArr.length; i++) 
					{
						$('#all_permission_'+locationIdArr[i]).prop('checked', true);
					}
				}
				else
				{
					$('#all_permission_'+locationId).prop('checked', true);
				}
			} else {
				$('.commonPermission').prop('checked',false);
			}
			
			if(allLocation == 1)
			{
				$('.commonPermission').prop('checked',true);
			}
			$('.deleteData').show();
			$('#newCloseDateModalCenter').modal('show');
		});
		
		$(document).on('click','.newCloseButton',function()
		{
			$("#add_staff_closed")[0].reset();
			var editstaff = $(this).data('id');
			var editdescription = $(this).data('description');
			var editstartdate = $(this).data('start_date');
			var editenddate = $(this).data('end_date');
			$('#editstaff').val("");
			$('#delstaff').val("");
			$('#description').val("");
			$('#startNewCloseDatePicker').val("");
			$('#endNewCloseDatePicker').val("");
			$("#startNewCloseDatePicker").removeClass('is-invalid');
			$('#endNewCloseDatePicker').removeClass('is-invalid');
			$("#description").removeClass('is-invalid');
			$("#newCloseDateModalLongTitle").html("Add Closed Date");
			$('.deleteData').hide();
		});
		
		$(document).on("click", '#deletecloseddate', function (e) {
			$("#add_staff_closed")[0].reset();
			$("#confirmModal").modal("hide");
			KTApp.blockPage();
			var form = $(this).parents('form:first');
			
			$.ajax({
				type: "POST",
				url: "{{ route('deleteclosed_date') }}",
				dataType: 'json',
				data: form.serialize(),
				success: function (data) {
					KTApp.unblockPage();
					
					if(data.status == true)
					{	
						var table = $('#ClosedDate').DataTable();
						table.ajax.reload();
						$('.newCloseDateModalCenter').modal('hide');
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
	
	$('.location_permission').on('change',function(){
		var checkedTotal = $('.location_permission:checked').length;
		var totalLocation = $(this).data('total-loc');
		
		if(checkedTotal == totalLocation)
		{
			$('#all_permission').prop('checked',true);
		}
		else
		{
			$('#all_permission').prop('checked',false);
		}
	});
	
	$('#all_permission').on('change',function(){
		if($(this).prop('checked') == true)
		{
			$('.location_permission').prop('checked',true);
		}
		else
		{
			$('.location_permission').prop('checked',false);
		}
	});
	</script>

<script>
	
	$("#startNewCloseDatePicker").datepicker({ 
	dateFormat: 'yy-mm-dd',
	todayHighlight: true,
	autoclose: true,
	minDate: new Date(),
	maxDate: '+1y',
	onSelect: function(date){
	
	var selectedDate = new Date(date);
	var msecsInADay = 172800; //86400000
	var endDate = new Date(selectedDate.getTime() + msecsInADay);
	
	//Set Minimum Date of EndDatePicker After Selected Date of StartDatePicker
	$("#endNewCloseDatePicker").datepicker( "option", "minDate", endDate );
	$("#endNewCloseDatePicker").datepicker( "option", "maxDate", '+1y' );
	
	}
	});
	
	$("#endNewCloseDatePicker").datepicker({ 
	dateFormat: 'yy-mm-dd',
	todayHighlight: true,
	autoclose: true,
	
	});
				
	</script>
@endsection