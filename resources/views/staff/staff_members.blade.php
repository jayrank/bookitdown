{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
	<style>	
		.editable_row {
			cursor: pointer;
		}
	</style>
@endsection

@section('content')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<!--begin::Tabs-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="background: #FFF;">
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
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class="container">
			<!--begin::Sales-->
			<!--begin::Row-->
			<div class="content-header">
				<div class="d-flex justify-content-between my-2 width-auto">
					<div class=""> 
					</div>
					<div class="action-btn-div" style="margin-top: 8px;margin-bottom: 8px;">
						<div class="dropdown dropdown-inline mr-2">
							<button type="button"
								class="btn btn-white dropdown-toggle"
								data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<span class="svg-icon svg-icon-md">
									<!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndR
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
							<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
								<!--begin::Navigation-->
								<ul class="navi flex-column navi-hover py-2">
									<li class="navi-item">
										<a href="{{ route('setstafforder') }}" class="navi-link">
											<span class="navi-text">Change the order</span>
										</a>
									</li>
									<li class="navi-item">
										<a href="{{ route('staffinfoexcel') }}" class="navi-link">
											<span class="navi-icon">
												<i class="la la-file-excel-o"></i>
											</span>
											<span class="navi-text">Excel</span>
										</a>
									</li>
									<li class="navi-item">
										<a href="{{ route('staffinfocsv') }}" class="navi-link">
											<span class="navi-icon">
												<i class="la la-file-text-o"></i>
											</span>
											<span class="navi-text">CSV</span>
										</a>
									</li>
									<!-- <li class="navi-item">
										<a href="#" class="navi-link">
											<span class="navi-icon">
												<i class="la la-file-pdf-o"></i>
											</span>
											<span class="navi-text">PDF</span>
										</a>
									</li> -->
								</ul>
								<!--end::Navigation-->
							</div>
							<!--end::Dropdown Menu-->
						</div>
						<a class="font-weight-bold btn btn-primary my-2"
							href="{{ url('partners/add_staff_member') }}">
							New Staff
						</a>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
					<!--begin::List Widget 3-->
					<div class="card card-custom card-stretch gutter-b">
						<!--begin::Body-->
						<!--begin::Item-->
						<div class="table-responsive invoice staff-member-table">
							<input type="hidden" id="edit_url" value="{{ route('add_staff_member') }}">
							<table class="table table-hover clien_tbl" id="staffList">
								<thead>
									<tr>
										<th scope="col"></th>
										<!-- <th scope="col"></th> -->
										<th scope="col">Name</th>
										<th scope="col">Mobile number</th>
										<th scope="col">Email</th>
										<th scope="col">Appointments</th>
										<th scope="col">User</th>
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
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script type="text/javascript">
	$(function() {
		var table = $('#staffList').DataTable({
			processing: true,
			serverSide: true,
			// order: [[ 1, 'ASC' ]],
	        "aaSorting": [],
			ajax: {
				type: "POST",
				url : "{{ route('getStaff') }}",
				data: {_token : "{{csrf_token()}}"}
			},
			columns: [
				{data: 'id', name: 'id'},
				// {data: 'order_id', name: 'order_id'},
				// {data: 'profile', name: 'profile'},
				{data: 'name', name: 'name'},
				{data: 'mobileno', name: 'mobileno'},
				{data: 'email', name: 'email'},
				{data: 'appointment', name: 'appointment'},
				{data: 'permission', name: 'permission', orderable: false, searchable: false},
			]
		});
	});
	
	$(document).ready( function(){
		$(document).on("click",".editable_row", function(){
			var sid = $(this).attr('id');
			var url = $("#edit_url").val();
			location.href = url+"/"+sid;
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