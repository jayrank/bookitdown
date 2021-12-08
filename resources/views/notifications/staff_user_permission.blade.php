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
	<div class="d-flex flex-column flex-root">
		<!--begin::Page-->
		<div class="d-flex flex-row flex-column-fluid page">

			<!--begin::Wrapper-->
			<div class="d-flex flex-column flex-row-fluid mt-10" id="kt_wrapper">

				<!--begin::Content-->
				<div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="background: #FFF;">
					<!--begin::Tabs-->
					<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
						<div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
							<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5" role="tablist">
								@if (Auth::user()->can('working_hours'))
									<li class="nav-item">
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
								<div class="col-12">
									<p>Setup which sections are accessible to each user permission level. All logged in staff can access the calendar, and owner accounts have full system access.</p>
								</div>
							</div>
							
							<form method="POST" action="{{ route('saveUserStaffPermission') }}" id="saveUserRolePermissions">		
								@csrf	
								<input type="hidden" id="redirectUrl" value="{{ route('getUserPermission') }}">
								<div class="row my-2">
									<div class="col-sm-12 col-lg-12 col-xl-12">
										<!--begin::Body-->
										<div class="pt-2">
											<!--begin::Item-->
											<div class="p-2">
												<div class="table-responsive permission-table">
													<table class="table table-bordered">
														<thead>
															<tr class="border-0 text-uppercase">
																<th>BOOKINGS & CLIENTS</th>
																@if(!empty($Role))	
																	@foreach($Role as $RoleData)
																		<th class="text-center">{{ $RoleData['name'] }}</th>
																	@endforeach
																@endif
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	Access own calendar
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" name="basic_permission[]" value="12" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" name="low_permission[]" value="12" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" name="medium_permission[]" value="12" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" name="high_permission[]" value="12" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Access other staff calendars
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="13" class="form-control text-center" @if(in_array(13, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="13" class="form-control text-center" @if(in_array(13, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="13" class="form-control text-center" @if(in_array(13, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="13" class="form-control text-center" @if(in_array(13, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Can book appointments
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="14" class="form-control text-center" @if(in_array(14, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="14" class="form-control text-center" @if(in_array(14, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="14" class="form-control text-center" @if(in_array(14, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="14" class="form-control text-center" @if(in_array(14, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Home
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="15" class="form-control text-center" @if(in_array(15, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="15" class="form-control text-center" @if(in_array(15, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="15" class="form-control text-center" @if(in_array(15, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="15" class="form-control text-center" @if(in_array(15, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Clients
																	<input type="hidden" name="clients" id="clients" value="10">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="16" class="form-control text-center" @if(in_array(16, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="16" class="form-control text-center" @if(in_array(16, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="16" class="form-control text-center" @if(in_array(16, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="16" class="form-control text-center" @if(in_array(16, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Can see client contact info
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="17" class="form-control text-center b_contact_info" @if(in_array(17, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="17" class="form-control text-center l_contact_info" @if(in_array(17, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="17" class="form-control text-center m_contact_info" @if(in_array(17, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="17" class="form-control text-center h_contact_info" @if(in_array(17, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Can download clients
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="18" class="form-control text-center b_client_download" @if(in_array(18, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="18" class="form-control text-center l_client_download" @if(in_array(18, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="18" class="form-control text-center m_client_download" @if(in_array(18, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="18" class="form-control text-center h_client_download" @if(in_array(18, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Messages
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="19" class="form-control text-center" @if(in_array(19, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="19" class="form-control text-center" @if(in_array(19, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="19" class="form-control text-center" @if(in_array(19, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="19" class="form-control text-center" @if(in_array(19, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="table-responsive permission-table">
													<table class="table table-bordered">
														<thead>
															<tr class="border-0 text-uppercase">
																<th>Services</th>
																@if(!empty($Role))	
																	@foreach($Role as $RoleData)
																		<th class="text-center">{{ $RoleData['name'] }}</th>
																	@endforeach
																@endif	
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	Services
																	<input type="hidden" name="services" id="services" value="14">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="20" class="form-control text-center" @if(in_array(20, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="20" class="form-control text-center" @if(in_array(20, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="20" class="form-control text-center" @if(in_array(20, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="20" class="form-control text-center" @if(in_array(20, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Paid Plans
																	<input type="hidden" name="paid_plans" id="paid_plans" value="15">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="21" class="form-control text-center" @if(in_array(21, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="21" class="form-control text-center" @if(in_array(21, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="21" class="form-control text-center" @if(in_array(21, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="21" class="form-control text-center" @if(in_array(21, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												
												
												<div class="table-responsive permission-table">
													<table class="table table-bordered">
														<thead>
															<tr class="border-0 text-uppercase">
																<th>Sales</th>
																@if(!empty($Role))	
																	@foreach($Role as $RoleData)
																		<th class="text-center">{{ $RoleData['name'] }}</th>
																	@endforeach
																@endif	
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	Can check out sales
																	<input type="hidden" name="can_check_out_sales" id="can_check_out_sales" value="16">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="22" class="form-control text-center" @if(in_array(22, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="22" class="form-control text-center" @if(in_array(22, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="22" class="form-control text-center" @if(in_array(22, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="22" class="form-control text-center" @if(in_array(22, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Daily sales
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="23" class="form-control text-center" @if(in_array(23, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="23" class="form-control text-center" @if(in_array(23, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="23" class="form-control text-center" @if(in_array(23, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="23" class="form-control text-center" @if(in_array(23, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Appointments
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="24" class="form-control text-center" @if(in_array(24, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="24" class="form-control text-center" @if(in_array(24, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="24" class="form-control text-center" @if(in_array(24, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="24" class="form-control text-center" @if(in_array(24, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Invoices
																	<input type="hidden" name="invoices" id="invoices" value="19">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="25" class="form-control text-center" @if(in_array(25, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="25" class="form-control text-center" @if(in_array(25, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="25" class="form-control text-center" @if(in_array(25, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="25" class="form-control text-center" @if(in_array(25, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Vouchers
																	<input type="hidden" name="vouchers" id="vouchers" value="20">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="26" class="form-control text-center" @if(in_array(26, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="26" class="form-control text-center" @if(in_array(26, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="26" class="form-control text-center" @if(in_array(26, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="26" class="form-control text-center" @if(in_array(26, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Paid plans
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="27" class="form-control text-center" @if(in_array(27, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="27" class="form-control text-center" @if(in_array(27, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="27" class="form-control text-center" @if(in_array(27, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="27" class="form-control text-center" @if(in_array(27, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												
												<div class="table-responsive permission-table">
													<table class="table table-bordered">
														<thead>
															<tr class="border-0 text-uppercase">
																<th>Staff</th>
																@if(!empty($Role))	
																	@foreach($Role as $RoleData)
																		<th class="text-center">{{ $RoleData['name'] }}</th>
																	@endforeach
																@endif	
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	Working hours
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="28" class="form-control text-center" @if(in_array(28, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="28" class="form-control text-center" @if(in_array(28, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="28" class="form-control text-center" @if(in_array(28, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="28" class="form-control text-center" @if(in_array(28, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Closed dates
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="29" class="form-control text-center" @if(in_array(29, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="29" class="form-control text-center" @if(in_array(29, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="29" class="form-control text-center" @if(in_array(29, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="29" class="form-control text-center" @if(in_array(29, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Staff members
																	<input type="hidden" name="staff_members" id="staff_members" value="24">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="30" class="form-control text-center" @if(in_array(30, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="30" class="form-control text-center" @if(in_array(30, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="30" class="form-control text-center" @if(in_array(30, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="30" class="form-control text-center" @if(in_array(30, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Permission levels
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="31" class="form-control text-center" @if(in_array(31, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="31" class="form-control text-center" @if(in_array(31, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="31" class="form-control text-center" @if(in_array(31, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="31" class="form-control text-center" @if(in_array(31, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															<tr>
																<td>
																	Gets notifications about tips
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="32" class="form-control text-center" @if(in_array(32, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="32" class="form-control text-center" @if(in_array(32, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="32" class="form-control text-center" @if(in_array(32, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="32" class="form-control text-center" @if(in_array(32, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												
												<div class="table-responsive permission-table">
													<table class="table table-bordered">
														<thead>
															<tr class="border-0 text-uppercase">
																<th>Inventory</th>
																@if(!empty($Role))	
																	@foreach($Role as $RoleData)
																		<th class="text-center">{{ $RoleData['name'] }}</th>
																	@endforeach
																@endif	
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	Products
																	<input type="hidden" name="products" id="products" value="27">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="33" class="form-control text-center" @if(in_array(33, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="33" class="form-control text-center" @if(in_array(33, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="33" class="form-control text-center" @if(in_array(33, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="33" class="form-control text-center" @if(in_array(33, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															
														</tbody>
													</table>
												</div>
												
												<div class="table-responsive permission-table">
													<table class="table table-bordered">
														<thead>
															<tr class="border-0 text-uppercase">
																<th>Reports</th>
																@if(!empty($Role))	
																	@foreach($Role as $RoleData)
																		<th class="text-center">{{ $RoleData['name'] }}</th>
																	@endforeach
																@endif	
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	All reports
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="34" class="form-control text-center" @if(in_array(34, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="34" class="form-control text-center" @if(in_array(34, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="34" class="form-control text-center" @if(in_array(34, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="34" class="form-control text-center" @if(in_array(34, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															
														</tbody>
													</table>
												</div>
												
												<div class="table-responsive permission-table">
													<table class="table table-bordered">
														<thead>
															<tr class="border-0 text-uppercase">
																<th>Setup</th>
																@if(!empty($Role))	
																	@foreach($Role as $RoleData)
																		<th class="text-center">{{ $RoleData['name'] }}</th>
																	@endforeach
																@endif	
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	Account setup
																	<input type="hidden" name="account_setup" id="account_setup" value="29">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="35" class="form-control text-center" @if(in_array(35, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="35" class="form-control text-center" @if(in_array(35, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="35" class="form-control text-center" @if(in_array(35, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="35" class="form-control text-center" @if(in_array(35, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															
															<tr>
																<td>
																	Point of sale
																	<input type="hidden" name="point_of_sale" id="point_of_sale" value="30">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="36" class="form-control text-center" @if(in_array(36, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="36" class="form-control text-center" @if(in_array(36, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="36" class="form-control text-center" @if(in_array(36, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="36" class="form-control text-center" @if(in_array(36, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															
															<tr>
																<td>
																	Client settings
																	<input type="hidden" name="client_settings" id="client_settings" value="31">	
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="37" class="form-control text-center" @if(in_array(37, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="37" class="form-control text-center" @if(in_array(37, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="37" class="form-control text-center" @if(in_array(37, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="37" class="form-control text-center" @if(in_array(37, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															
															<tr>
																<td>
																	Online booking
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="38" class="form-control text-center" @if(in_array(38, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="38" class="form-control text-center" @if(in_array(38, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="38" class="form-control text-center" @if(in_array(38, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="38" class="form-control text-center" @if(in_array(38, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															
															<tr>
																<td>
																	Wallet and card processing
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="39" class="form-control text-center" @if(in_array(39, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="39" class="form-control text-center" @if(in_array(39, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="39" class="form-control text-center" @if(in_array(39, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="39" class="form-control text-center" @if(in_array(39, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															
															<tr>
																<td>
																	Marketing
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="40" class="form-control text-center" @if(in_array(40, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="40" class="form-control text-center" @if(in_array(40, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="40" class="form-control text-center" @if(in_array(40, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="40" class="form-control text-center" @if(in_array(40, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												
												<div class="table-responsive permission-table">
													<table class="table table-bordered">
														<thead>
															<tr class="border-0 text-uppercase">
																<th>Vouchers</th>
																@if(!empty($Role))	
																	@foreach($Role as $RoleData)
																		<th class="text-center">{{ $RoleData['name'] }}</th>
																	@endforeach
																@endif	
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	View voucher list
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="41" class="form-control text-center" @if(in_array(41, $basicPermission)) checked @endif>
																		<span></span>
																	</label> 
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="41" class="form-control text-center" @if(in_array(41, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="41" class="form-control text-center" @if(in_array(41, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="41" class="form-control text-center" @if(in_array(41, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															
															<tr>
																<td>
																	Manage vouchers
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="42" class="form-control text-center" @if(in_array(42, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="42" class="form-control text-center" @if(in_array(42, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="42" class="form-control text-center" @if(in_array(42, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="42" class="form-control text-center" @if(in_array(42, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												
												<div class="table-responsive permission-table">
													<table class="table table-bordered">
														<thead>
															<tr class="border-0 text-uppercase">
																<th>Consultation Forms</th>
																@if(!empty($Role))	
																	@foreach($Role as $RoleData)
																		<th class="text-center">{{ $RoleData['name'] }}</th>
																	@endforeach
																@endif	
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>
																	Manage consultation forms
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="43" class="form-control text-center" @if(in_array(43, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="43" class="form-control text-center" @if(in_array(43, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="43" class="form-control text-center" @if(in_array(43, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="43" class="form-control text-center" @if(in_array(43, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															
															<tr>
																<td>
																	View client responses
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="44" class="form-control text-center" @if(in_array(44, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="44" class="form-control text-center" @if(in_array(44, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="44" class="form-control text-center" @if(in_array(44, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="44" class="form-control text-center" @if(in_array(44, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
															
															<tr>
																<td>
																	Complete consultation forms
																</td>
																<td class="cursor-not-allowed" style="pointer-events:none;">
																	<label class="cursor-not-allowed checkbox flex-center">
																		<input checked type="checkbox" class="form-control text-center">
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="basic_permission[]" value="45" class="form-control text-center" @if(in_array(45, $basicPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="low_permission[]" value="45" class="form-control text-center" @if(in_array(45, $lowPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="medium_permission[]" value="45" class="form-control text-center" @if(in_array(45, $mediumPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
																<td>
																	<label class="checkbox flex-center">
																		<input type="checkbox" name="high_permission[]" value="45" class="form-control text-center" @if(in_array(45, $highPermission)) checked @endif>
																		<span></span>
																	</label>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
												<div class="text-center">
													<button type="submit" class="btn btn-primary" id="saveRolePermission">Save Changes</button>
												</div>
											</div>
											<!--end:Item-->
										</div>
										<!--end::Body-->
									</div>
								</div>
								<!--end::Row-->
							</form>
							<!--end::Sales-->
						</div>
						<!--end::Container-->
					</div>
					<!--end::Entry-->
				</div>
				<!--end::Content-->
			</div>
			<!--end::Wrapper-->
		</div>
		<!--end::Page-->
	</div>
	<!--end::Main-->
@endsection

{{-- Scripts Section --}}
@section('scripts')	
<script src="{{ asset('js/user-permission.js') }}"></script>

@endsection