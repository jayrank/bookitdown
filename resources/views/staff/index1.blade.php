{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />

<style type="text/css">
	select#select-end-shift2, select#select-end-shift {
	    background: none;
	}
	.time {
		min-width: 122px;
		min-height: 45px;
	}
</style>
@endsection
@section('content')
<!--begin::Tabs-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="background: #FFF;">
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
	{{-- add modal --}}
	<div class="modal fade schedule-modal" id="shiftDateModalCenter" tabindex="-1" role="dialog"
		aria-labelledby="shiftDateModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header flex-center flex-column pb-3">
					<h5 class="font-weight-normal modal-title" id="ShiftDateModalLongTitle">
						<p id="username"></p>
					</h5>
					<p id="date"></p>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				{{ Form::open(array('url' => 'add_staff_time','id' => 'add_staff_time')) }}
				<div class="modal-body">
					<input type="hidden" name="staff_id" id="staff_id">
					<input type="hidden" name="timedate" id="timedate">
					<input type="hidden" name="day" id="timeday">
					<div class="row first-shift mb-3">
						<div class="col-6 pr-0">
							<label class="text-uppercase font-weight-bold">Shift Start</label>
							<select class="form-control start-shift1" name="start_time[]" id="select-start-shift">
								<option value="12:00am">12:00am</option>
								<option value="12:05am">12:05am</option>
								<option value="12:10am">12:10am</option>
								<option value="12:15am">12:15am</option>
								<option value="12:20am">12:20am</option>
								<option value="12:25am">12:25am</option>
								<option value="12:30am">12:30am</option>
								<option value="12:35am">12:35am</option>
								<option value="12:40am">12:40am</option>
								<option value="12:45am">12:45am</option>
								<option value="12:50am">12:50am</option>
								<option value="12:55am">12:55am</option>
								<option value="1:00am">1:00am</option>
								<option value="1:05am">1:05am</option>
								<option value="1:10am">1:10am</option>
								<option value="1:15am">1:15am</option>
								<option value="1:20am">1:20am</option>
								<option value="1:25am">1:25am</option>
								<option value="1:30am">1:30am</option>
								<option value="1:35am">1:35am</option>
								<option value="1:40am">1:40am</option>
								<option value="1:45am">1:45am</option>
								<option value="1:50am">1:50am</option>
								<option value="1:55am">1:55am</option>
								<option value="2:00am">2:00am</option>
								<option value="2:05am">2:05am</option>
								<option value="2:10am">2:10am</option>
								<option value="2:15am">2:15am</option>
								<option value="2:20am">2:20am</option>
								<option value="2:25am">2:25am</option>
								<option value="2:30am">2:30am</option>
								<option value="2:35am">2:35am</option>
								<option value="2:40am">2:40am</option>
								<option value="2:45am">2:45am</option>
								<option value="2:50am">2:50am</option>
								<option value="2:55am">2:55am</option>
								<option value="3:00am">3:00am</option>
								<option value="3:05am">3:05am</option>
								<option value="3:10am">3:10am</option>
								<option value="3:15am">3:15am</option>
								<option value="3:20am">3:20am</option>
								<option value="3:25am">3:25am</option>
								<option value="3:30am">3:30am</option>
								<option value="3:35am">3:35am</option>
								<option value="3:40am">3:40am</option>
								<option value="3:45am">3:45am</option>
								<option value="3:50am">3:50am</option>
								<option value="3:55am">3:55am</option>
								<option value="4:00am">4:00am</option>
								<option value="4:05am">4:05am</option>
								<option value="4:10am">4:10am</option>
								<option value="4:15am">4:15am</option>
								<option value="4:20am">4:20am</option>
								<option value="4:25am">4:25am</option>
								<option value="4:30am">4:30am</option>
								<option value="4:35am">4:35am</option>
								<option value="4:40am">4:40am</option>
								<option value="4:45am">4:45am</option>
								<option value="4:50am">4:50am</option>
								<option value="4:55am">4:55am</option>
								<option value="5:00am">5:00am</option>
								<option value="5:05am">5:05am</option>
								<option value="5:10am">5:10am</option>
								<option value="5:15am">5:15am</option>
								<option value="5:20am">5:20am</option>
								<option value="5:25am">5:25am</option>
								<option value="5:30am">5:30am</option>
								<option value="5:35am">5:35am</option>
								<option value="5:40am">5:40am</option>
								<option value="5:45am">5:45am</option>
								<option value="5:50am">5:50am</option>
								<option value="5:55am">5:55am</option>
								<option value="6:00am">6:00am</option>
								<option value="6:05am">6:05am</option>
								<option value="6:10am">6:10am</option>
								<option value="6:15am">6:15am</option>
								<option value="6:20am">6:20am</option>
								<option value="6:25am">6:25am</option>
								<option value="6:30am">6:30am</option>
								<option value="6:35am">6:35am</option>
								<option value="6:40am">6:40am</option>
								<option value="6:45am">6:45am</option>
								<option value="6:50am">6:50am</option>
								<option value="6:55am">6:55am</option>
								<option value="7:00am">7:00am</option>
								<option value="7:05am">7:05am</option>
								<option value="7:10am">7:10am</option>
								<option value="7:15am">7:15am</option>
								<option value="7:20am">7:20am</option>
								<option value="7:25am">7:25am</option>
								<option value="7:30am">7:30am</option>
								<option value="7:35am">7:35am</option>
								<option value="7:40am">7:40am</option>
								<option value="7:45am">7:45am</option>
								<option value="7:50am">7:50am</option>
								<option value="7:55am">7:55am</option>
								<option value="8:00am">8:00am</option>
								<option value="8:05am">8:05am</option>
								<option value="8:10am">8:10am</option>
								<option value="8:15am">8:15am</option>
								<option value="8:20am">8:20am</option>
								<option value="8:25am">8:25am</option>
								<option value="8:30am">8:30am</option>
								<option value="8:35am">8:35am</option>
								<option value="8:40am">8:40am</option>
								<option value="8:45am">8:45am</option>
								<option value="8:50am">8:50am</option>
								<option value="8:55am">8:55am</option>
								<option value="9:00am">9:00am</option>
								<option value="9:05am">9:05am</option>
								<option value="9:10am">9:10am</option>
								<option value="9:15am">9:15am</option>
								<option value="9:20am">9:20am</option>
								<option value="9:25am">9:25am</option>
								<option value="9:30am">9:30am</option>
								<option value="9:35am">9:35am</option>
								<option value="9:40am">9:40am</option>
								<option value="9:45am">9:45am</option>
								<option value="9:50am">9:50am</option>
								<option value="9:55am">9:55am</option>
								<option value="10:00am">10:00am</option>
								<option value="10:05am">10:05am</option>
								<option value="10:10am">10:10am</option>
								<option value="10:15am">10:15am</option>
								<option value="10:20am">10:20am</option>
								<option value="10:25am">10:25am</option>
								<option value="10:30am">10:30am</option>
								<option value="10:35am">10:35am</option>
								<option value="10:40am">10:40am</option>
								<option value="10:45am">10:45am</option>
								<option value="10:50am">10:50am</option>
								<option value="10:55am">10:55am</option>
								<option value="11:00am">11:00am</option>
								<option value="11:05am">11:05am</option>
								<option value="11:10am">11:10am</option>
								<option value="11:15am">11:15am</option>
								<option value="11:20am">11:20am</option>
								<option value="11:25am">11:25am</option>
								<option value="11:30am">11:30am</option>
								<option value="11:35am">11:35am</option>
								<option value="11:40am">11:40am</option>
								<option value="11:45am">11:45am</option>
								<option value="11:50am">11:50am</option>
								<option value="11:55am">11:55am</option>
								<option value="12:00pm">12:00pm</option>
								<option value="12:05pm">12:05pm</option>
								<option value="12:10pm">12:10pm</option>
								<option value="12:15pm">12:15pm</option>
								<option value="12:20pm">12:20pm</option>
								<option value="12:25pm">12:25pm</option>
								<option value="12:30pm">12:30pm</option>
								<option value="12:35pm">12:35pm</option>
								<option value="12:40pm">12:40pm</option>
								<option value="12:45pm">12:45pm</option>
								<option value="12:50pm">12:50pm</option>
								<option value="12:55pm">12:55pm</option>
								<option value="1:00pm">1:00pm</option>
								<option value="1:05pm">1:05pm</option>
								<option value="1:10pm">1:10pm</option>
								<option value="1:15pm">1:15pm</option>
								<option value="1:20pm">1:20pm</option>
								<option value="1:25pm">1:25pm</option>
								<option value="1:30pm">1:30pm</option>
								<option value="1:35pm">1:35pm</option>
								<option value="1:40pm">1:40pm</option>
								<option value="1:45pm">1:45pm</option>
								<option value="1:50pm">1:50pm</option>
								<option value="1:55pm">1:55pm</option>
								<option value="2:00pm">2:00pm</option>
								<option value="2:05pm">2:05pm</option>
								<option value="2:10pm">2:10pm</option>
								<option value="2:15pm">2:15pm</option>
								<option value="2:20pm">2:20pm</option>
								<option value="2:25pm">2:25pm</option>
								<option value="2:30pm">2:30pm</option>
								<option value="2:35pm">2:35pm</option>
								<option value="2:40pm">2:40pm</option>
								<option value="2:45pm">2:45pm</option>
								<option value="2:50pm">2:50pm</option>
								<option value="2:55pm">2:55pm</option>
								<option value="3:00pm">3:00pm</option>
								<option value="3:05pm">3:05pm</option>
								<option value="3:10pm">3:10pm</option>
								<option value="3:15pm">3:15pm</option>
								<option value="3:20pm">3:20pm</option>
								<option value="3:25pm">3:25pm</option>
								<option value="3:30pm">3:30pm</option>
								<option value="3:35pm">3:35pm</option>
								<option value="3:40pm">3:40pm</option>
								<option value="3:45pm">3:45pm</option>
								<option value="3:50pm">3:50pm</option>
								<option value="3:55pm">3:55pm</option>
								<option value="4:00pm">4:00pm</option>
								<option value="4:05pm">4:05pm</option>
								<option value="4:10pm">4:10pm</option>
								<option value="4:15pm">4:15pm</option>
								<option value="4:20pm">4:20pm</option>
								<option value="4:25pm">4:25pm</option>
								<option value="4:30pm">4:30pm</option>
								<option value="4:35pm">4:35pm</option>
								<option value="4:40pm">4:40pm</option>
								<option value="4:45pm">4:45pm</option>
								<option value="4:50pm">4:50pm</option>
								<option value="4:55pm">4:55pm</option>
								<option value="5:00pm">5:00pm</option>
								<option value="5:05pm">5:05pm</option>
								<option value="5:10pm">5:10pm</option>
								<option value="5:15pm">5:15pm</option>
								<option value="5:20pm">5:20pm</option>
								<option value="5:25pm">5:25pm</option>
								<option value="5:30pm">5:30pm</option>
								<option value="5:35pm">5:35pm</option>
								<option value="5:40pm">5:40pm</option>
								<option value="5:45pm">5:45pm</option>
								<option value="5:50pm">5:50pm</option>
								<option value="5:55pm">5:55pm</option>
								<option value="6:00pm">6:00pm</option>
								<option value="6:05pm">6:05pm</option>
								<option value="6:10pm">6:10pm</option>
								<option value="6:15pm">6:15pm</option>
								<option value="6:20pm">6:20pm</option>
								<option value="6:25pm">6:25pm</option>
								<option value="6:30pm">6:30pm</option>
								<option value="6:35pm">6:35pm</option>
								<option value="6:40pm">6:40pm</option>
								<option value="6:45pm">6:45pm</option>
								<option value="6:50pm">6:50pm</option>
								<option value="6:55pm">6:55pm</option>
								<option value="7:00pm">7:00pm</option>
								<option value="7:05pm">7:05pm</option>
								<option value="7:10pm">7:10pm</option>
								<option value="7:15pm">7:15pm</option>
								<option value="7:20pm">7:20pm</option>
								<option value="7:25pm">7:25pm</option>
								<option value="7:30pm">7:30pm</option>
								<option value="7:35pm">7:35pm</option>
								<option value="7:40pm">7:40pm</option>
								<option value="7:45pm">7:45pm</option>
								<option value="7:50pm">7:50pm</option>
								<option value="7:55pm">7:55pm</option>
								<option value="8:00pm">8:00pm</option>
								<option value="8:05pm">8:05pm</option>
								<option value="8:10pm">8:10pm</option>
								<option value="8:15pm">8:15pm</option>
								<option value="8:20pm">8:20pm</option>
								<option value="8:25pm">8:25pm</option>
								<option value="8:30pm">8:30pm</option>
								<option value="8:35pm">8:35pm</option>
								<option value="8:40pm">8:40pm</option>
								<option value="8:45pm">8:45pm</option>
								<option value="8:50pm">8:50pm</option>
								<option value="8:55pm">8:55pm</option>
								<option value="9:00pm">9:00pm</option>
								<option value="9:05pm">9:05pm</option>
								<option value="9:10pm">9:10pm</option>
								<option value="9:15pm">9:15pm</option>
								<option value="9:20pm">9:20pm</option>
								<option value="9:25pm">9:25pm</option>
								<option value="9:30pm">9:30pm</option>
								<option value="9:35pm">9:35pm</option>
								<option value="9:40pm">9:40pm</option>
								<option value="9:45pm">9:45pm</option>
								<option value="9:50pm">9:50pm</option>
								<option value="9:55pm">9:55pm</option>
								<option value="10:00pm">10:00pm</option>
								<option value="10:05pm">10:05pm</option>
								<option value="10:10pm">10:10pm</option>
								<option value="10:15pm">10:15pm</option>
								<option value="10:20pm">10:20pm</option>
								<option value="10:25pm">10:25pm</option>
								<option value="10:30pm">10:30pm</option>
								<option value="10:35pm">10:35pm</option>
								<option value="10:40pm">10:40pm</option>
								<option value="10:45pm">10:45pm</option>
								<option value="10:50pm">10:50pm</option>
								<option value="10:55pm">10:55pm</option>
								<option value="11:00pm">11:00pm</option>
								<option value="11:05pm">11:05pm</option>
								<option value="11:10pm">11:10pm</option>
								<option value="11:15pm">11:15pm</option>
								<option value="11:20pm">11:20pm</option>
								<option value="11:25pm">11:25pm</option>
								<option value="11:30pm">11:30pm</option>
								<option value="11:35pm">11:35pm</option>
								<option value="11:40pm">11:40pm</option>
								<option value="11:45pm">11:45pm</option>
								<option value="11:50pm">11:50pm</option>
								<option value="11:55pm">11:55pm</option>
							</select>
						</div>
						<div class="col-6 pl-0">
							<label class="text-uppercase font-weight-bold">Shift End</label>
							<select class="form-control end-shift1" name="end_time[]" id="select-end-shift">
								<option value="12:00am">12:00am</option>
								<option value="12:05am">12:05am</option>
								<option value="12:10am">12:10am</option>
								<option value="12:15am">12:15am</option>
								<option value="12:20am">12:20am</option>
								<option value="12:25am">12:25am</option>
								<option value="12:30am">12:30am</option>
								<option value="12:35am">12:35am</option>
								<option value="12:40am">12:40am</option>
								<option value="12:45am">12:45am</option>
								<option value="12:50am">12:50am</option>
								<option value="12:55am">12:55am</option>
								<option value="1:00am">1:00am</option>
								<option value="1:05am">1:05am</option>
								<option value="1:10am">1:10am</option>
								<option value="1:15am">1:15am</option>
								<option value="1:20am">1:20am</option>
								<option value="1:25am">1:25am</option>
								<option value="1:30am">1:30am</option>
								<option value="1:35am">1:35am</option>
								<option value="1:40am">1:40am</option>
								<option value="1:45am">1:45am</option>
								<option value="1:50am">1:50am</option>
								<option value="1:55am">1:55am</option>
								<option value="2:00am">2:00am</option>
								<option value="2:05am">2:05am</option>
								<option value="2:10am">2:10am</option>
								<option value="2:15am">2:15am</option>
								<option value="2:20am">2:20am</option>
								<option value="2:25am">2:25am</option>
								<option value="2:30am">2:30am</option>
								<option value="2:35am">2:35am</option>
								<option value="2:40am">2:40am</option>
								<option value="2:45am">2:45am</option>
								<option value="2:50am">2:50am</option>
								<option value="2:55am">2:55am</option>
								<option value="3:00am">3:00am</option>
								<option value="3:05am">3:05am</option>
								<option value="3:10am">3:10am</option>
								<option value="3:15am">3:15am</option>
								<option value="3:20am">3:20am</option>
								<option value="3:25am">3:25am</option>
								<option value="3:30am">3:30am</option>
								<option value="3:35am">3:35am</option>
								<option value="3:40am">3:40am</option>
								<option value="3:45am">3:45am</option>
								<option value="3:50am">3:50am</option>
								<option value="3:55am">3:55am</option>
								<option value="4:00am">4:00am</option>
								<option value="4:05am">4:05am</option>
								<option value="4:10am">4:10am</option>
								<option value="4:15am">4:15am</option>
								<option value="4:20am">4:20am</option>
								<option value="4:25am">4:25am</option>
								<option value="4:30am">4:30am</option>
								<option value="4:35am">4:35am</option>
								<option value="4:40am">4:40am</option>
								<option value="4:45am">4:45am</option>
								<option value="4:50am">4:50am</option>
								<option value="4:55am">4:55am</option>
								<option value="5:00am">5:00am</option>
								<option value="5:05am">5:05am</option>
								<option value="5:10am">5:10am</option>
								<option value="5:15am">5:15am</option>
								<option value="5:20am">5:20am</option>
								<option value="5:25am">5:25am</option>
								<option value="5:30am">5:30am</option>
								<option value="5:35am">5:35am</option>
								<option value="5:40am">5:40am</option>
								<option value="5:45am">5:45am</option>
								<option value="5:50am">5:50am</option>
								<option value="5:55am">5:55am</option>
								<option value="6:00am">6:00am</option>
								<option value="6:05am">6:05am</option>
								<option value="6:10am">6:10am</option>
								<option value="6:15am">6:15am</option>
								<option value="6:20am">6:20am</option>
								<option value="6:25am">6:25am</option>
								<option value="6:30am">6:30am</option>
								<option value="6:35am">6:35am</option>
								<option value="6:40am">6:40am</option>
								<option value="6:45am">6:45am</option>
								<option value="6:50am">6:50am</option>
								<option value="6:55am">6:55am</option>
								<option value="7:00am">7:00am</option>
								<option value="7:05am">7:05am</option>
								<option value="7:10am">7:10am</option>
								<option value="7:15am">7:15am</option>
								<option value="7:20am">7:20am</option>
								<option value="7:25am">7:25am</option>
								<option value="7:30am">7:30am</option>
								<option value="7:35am">7:35am</option>
								<option value="7:40am">7:40am</option>
								<option value="7:45am">7:45am</option>
								<option value="7:50am">7:50am</option>
								<option value="7:55am">7:55am</option>
								<option value="8:00am">8:00am</option>
								<option value="8:05am">8:05am</option>
								<option value="8:10am">8:10am</option>
								<option value="8:15am">8:15am</option>
								<option value="8:20am">8:20am</option>
								<option value="8:25am">8:25am</option>
								<option value="8:30am">8:30am</option>
								<option value="8:35am">8:35am</option>
								<option value="8:40am">8:40am</option>
								<option value="8:45am">8:45am</option>
								<option value="8:50am">8:50am</option>
								<option value="8:55am">8:55am</option>
								<option value="9:00am">9:00am</option>
								<option value="9:05am">9:05am</option>
								<option value="9:10am">9:10am</option>
								<option value="9:15am">9:15am</option>
								<option value="9:20am">9:20am</option>
								<option value="9:25am">9:25am</option>
								<option value="9:30am">9:30am</option>
								<option value="9:35am">9:35am</option>
								<option value="9:40am">9:40am</option>
								<option value="9:45am">9:45am</option>
								<option value="9:50am">9:50am</option>
								<option value="9:55am">9:55am</option>
								<option value="10:00am">10:00am</option>
								<option value="10:05am">10:05am</option>
								<option value="10:10am">10:10am</option>
								<option value="10:15am">10:15am</option>
								<option value="10:20am">10:20am</option>
								<option value="10:25am">10:25am</option>
								<option value="10:30am">10:30am</option>
								<option value="10:35am">10:35am</option>
								<option value="10:40am">10:40am</option>
								<option value="10:45am">10:45am</option>
								<option value="10:50am">10:50am</option>
								<option value="10:55am">10:55am</option>
								<option value="11:00am">11:00am</option>
								<option value="11:05am">11:05am</option>
								<option value="11:10am">11:10am</option>
								<option value="11:15am">11:15am</option>
								<option value="11:20am">11:20am</option>
								<option value="11:25am">11:25am</option>
								<option value="11:30am">11:30am</option>
								<option value="11:35am">11:35am</option>
								<option value="11:40am">11:40am</option>
								<option value="11:45am">11:45am</option>
								<option value="11:50am">11:50am</option>
								<option value="11:55am">11:55am</option>
								<option value="12:00pm">12:00pm</option>
								<option value="12:05pm">12:05pm</option>
								<option value="12:10pm">12:10pm</option>
								<option value="12:15pm">12:15pm</option>
								<option value="12:20pm">12:20pm</option>
								<option value="12:25pm">12:25pm</option>
								<option value="12:30pm">12:30pm</option>
								<option value="12:35pm">12:35pm</option>
								<option value="12:40pm">12:40pm</option>
								<option value="12:45pm">12:45pm</option>
								<option value="12:50pm">12:50pm</option>
								<option value="12:55pm">12:55pm</option>
								<option value="1:00pm">1:00pm</option>
								<option value="1:05pm">1:05pm</option>
								<option value="1:10pm">1:10pm</option>
								<option value="1:15pm">1:15pm</option>
								<option value="1:20pm">1:20pm</option>
								<option value="1:25pm">1:25pm</option>
								<option value="1:30pm">1:30pm</option>
								<option value="1:35pm">1:35pm</option>
								<option value="1:40pm">1:40pm</option>
								<option value="1:45pm">1:45pm</option>
								<option value="1:50pm">1:50pm</option>
								<option value="1:55pm">1:55pm</option>
								<option value="2:00pm">2:00pm</option>
								<option value="2:05pm">2:05pm</option>
								<option value="2:10pm">2:10pm</option>
								<option value="2:15pm">2:15pm</option>
								<option value="2:20pm">2:20pm</option>
								<option value="2:25pm">2:25pm</option>
								<option value="2:30pm">2:30pm</option>
								<option value="2:35pm">2:35pm</option>
								<option value="2:40pm">2:40pm</option>
								<option value="2:45pm">2:45pm</option>
								<option value="2:50pm">2:50pm</option>
								<option value="2:55pm">2:55pm</option>
								<option value="3:00pm">3:00pm</option>
								<option value="3:05pm">3:05pm</option>
								<option value="3:10pm">3:10pm</option>
								<option value="3:15pm">3:15pm</option>
								<option value="3:20pm">3:20pm</option>
								<option value="3:25pm">3:25pm</option>
								<option value="3:30pm">3:30pm</option>
								<option value="3:35pm">3:35pm</option>
								<option value="3:40pm">3:40pm</option>
								<option value="3:45pm">3:45pm</option>
								<option value="3:50pm">3:50pm</option>
								<option value="3:55pm">3:55pm</option>
								<option value="4:00pm">4:00pm</option>
								<option value="4:05pm">4:05pm</option>
								<option value="4:10pm">4:10pm</option>
								<option value="4:15pm">4:15pm</option>
								<option value="4:20pm">4:20pm</option>
								<option value="4:25pm">4:25pm</option>
								<option value="4:30pm">4:30pm</option>
								<option value="4:35pm">4:35pm</option>
								<option value="4:40pm">4:40pm</option>
								<option value="4:45pm">4:45pm</option>
								<option value="4:50pm">4:50pm</option>
								<option value="4:55pm">4:55pm</option>
								<option value="5:00pm">5:00pm</option>
								<option value="5:05pm">5:05pm</option>
								<option value="5:10pm">5:10pm</option>
								<option value="5:15pm">5:15pm</option>
								<option value="5:20pm">5:20pm</option>
								<option value="5:25pm">5:25pm</option>
								<option value="5:30pm">5:30pm</option>
								<option value="5:35pm">5:35pm</option>
								<option value="5:40pm">5:40pm</option>
								<option value="5:45pm">5:45pm</option>
								<option value="5:50pm">5:50pm</option>
								<option value="5:55pm">5:55pm</option>
								<option value="6:00pm">6:00pm</option>
								<option value="6:05pm">6:05pm</option>
								<option value="6:10pm">6:10pm</option>
								<option value="6:15pm">6:15pm</option>
								<option value="6:20pm">6:20pm</option>
								<option value="6:25pm">6:25pm</option>
								<option value="6:30pm">6:30pm</option>
								<option value="6:35pm">6:35pm</option>
								<option value="6:40pm">6:40pm</option>
								<option value="6:45pm">6:45pm</option>
								<option value="6:50pm">6:50pm</option>
								<option value="6:55pm">6:55pm</option>
								<option value="7:00pm">7:00pm</option>
								<option value="7:05pm">7:05pm</option>
								<option value="7:10pm">7:10pm</option>
								<option value="7:15pm">7:15pm</option>
								<option value="7:20pm">7:20pm</option>
								<option value="7:25pm">7:25pm</option>
								<option value="7:30pm">7:30pm</option>
								<option value="7:35pm">7:35pm</option>
								<option value="7:40pm">7:40pm</option>
								<option value="7:45pm">7:45pm</option>
								<option value="7:50pm">7:50pm</option>
								<option value="7:55pm">7:55pm</option>
								<option value="8:00pm">8:00pm</option>
								<option value="8:05pm">8:05pm</option>
								<option value="8:10pm">8:10pm</option>
								<option value="8:15pm">8:15pm</option>
								<option value="8:20pm">8:20pm</option>
								<option value="8:25pm">8:25pm</option>
								<option value="8:30pm">8:30pm</option>
								<option value="8:35pm">8:35pm</option>
								<option value="8:40pm">8:40pm</option>
								<option value="8:45pm">8:45pm</option>
								<option value="8:50pm">8:50pm</option>
								<option value="8:55pm">8:55pm</option>
								<option value="9:00pm">9:00pm</option>
								<option value="9:05pm">9:05pm</option>
								<option value="9:10pm">9:10pm</option>
								<option value="9:15pm">9:15pm</option>
								<option value="9:20pm">9:20pm</option>
								<option value="9:25pm">9:25pm</option>
								<option value="9:30pm">9:30pm</option>
								<option value="9:35pm">9:35pm</option>
								<option value="9:40pm">9:40pm</option>
								<option value="9:45pm">9:45pm</option>
								<option value="9:50pm">9:50pm</option>
								<option value="9:55pm">9:55pm</option>
								<option value="10:00pm">10:00pm</option>
								<option value="10:05pm">10:05pm</option>
								<option value="10:10pm">10:10pm</option>
								<option value="10:15pm">10:15pm</option>
								<option value="10:20pm">10:20pm</option>
								<option value="10:25pm">10:25pm</option>
								<option value="10:30pm">10:30pm</option>
								<option value="10:35pm">10:35pm</option>
								<option value="10:40pm">10:40pm</option>
								<option value="10:45pm">10:45pm</option>
								<option value="10:50pm">10:50pm</option>
								<option value="10:55pm">10:55pm</option>
								<option value="11:00pm">11:00pm</option>
								<option value="11:05pm">11:05pm</option>
								<option value="11:10pm">11:10pm</option>
								<option value="11:15pm">11:15pm</option>
								<option value="11:20pm">11:20pm</option>
								<option value="11:25pm">11:25pm</option>
								<option value="11:30pm">11:30pm</option>
								<option value="11:35pm">11:35pm</option>
								<option value="11:40pm">11:40pm</option>
								<option value="11:45pm">11:45pm</option>
								<option value="11:50pm">11:50pm</option>
								<option value="11:55pm">11:55pm</option>
							</select>
						</div>
					</div>
					<div class="row second-shift" id="second-shift" style="display:none">
						<div class="col-6 pr-0">
							<label class="text-uppercase font-weight-bold d-sm-none">Shift Start</label>
							<select class="form-control start-shift2" name="start_time[]" id="select-start-shift">
								<option value="0" selected>Select time</option>
								<option value="12:00am">12:00am</option>
								<option value="12:05am">12:05am</option>
								<option value="12:10am">12:10am</option>
								<option value="12:15am">12:15am</option>
								<option value="12:20am">12:20am</option>
								<option value="12:25am">12:25am</option>
								<option value="12:30am">12:30am</option>
								<option value="12:35am">12:35am</option>
								<option value="12:40am">12:40am</option>
								<option value="12:45am">12:45am</option>
								<option value="12:50am">12:50am</option>
								<option value="12:55am">12:55am</option>
								<option value="1:00am">1:00am</option>
								<option value="1:05am">1:05am</option>
								<option value="1:10am">1:10am</option>
								<option value="1:15am">1:15am</option>
								<option value="1:20am">1:20am</option>
								<option value="1:25am">1:25am</option>
								<option value="1:30am">1:30am</option>
								<option value="1:35am">1:35am</option>
								<option value="1:40am">1:40am</option>
								<option value="1:45am">1:45am</option>
								<option value="1:50am">1:50am</option>
								<option value="1:55am">1:55am</option>
								<option value="2:00am">2:00am</option>
								<option value="2:05am">2:05am</option>
								<option value="2:10am">2:10am</option>
								<option value="2:15am">2:15am</option>
								<option value="2:20am">2:20am</option>
								<option value="2:25am">2:25am</option>
								<option value="2:30am">2:30am</option>
								<option value="2:35am">2:35am</option>
								<option value="2:40am">2:40am</option>
								<option value="2:45am">2:45am</option>
								<option value="2:50am">2:50am</option>
								<option value="2:55am">2:55am</option>
								<option value="3:00am">3:00am</option>
								<option value="3:05am">3:05am</option>
								<option value="3:10am">3:10am</option>
								<option value="3:15am">3:15am</option>
								<option value="3:20am">3:20am</option>
								<option value="3:25am">3:25am</option>
								<option value="3:30am">3:30am</option>
								<option value="3:35am">3:35am</option>
								<option value="3:40am">3:40am</option>
								<option value="3:45am">3:45am</option>
								<option value="3:50am">3:50am</option>
								<option value="3:55am">3:55am</option>
								<option value="4:00am">4:00am</option>
								<option value="4:05am">4:05am</option>
								<option value="4:10am">4:10am</option>
								<option value="4:15am">4:15am</option>
								<option value="4:20am">4:20am</option>
								<option value="4:25am">4:25am</option>
								<option value="4:30am">4:30am</option>
								<option value="4:35am">4:35am</option>
								<option value="4:40am">4:40am</option>
								<option value="4:45am">4:45am</option>
								<option value="4:50am">4:50am</option>
								<option value="4:55am">4:55am</option>
								<option value="5:00am">5:00am</option>
								<option value="5:05am">5:05am</option>
								<option value="5:10am">5:10am</option>
								<option value="5:15am">5:15am</option>
								<option value="5:20am">5:20am</option>
								<option value="5:25am">5:25am</option>
								<option value="5:30am">5:30am</option>
								<option value="5:35am">5:35am</option>
								<option value="5:40am">5:40am</option>
								<option value="5:45am">5:45am</option>
								<option value="5:50am">5:50am</option>
								<option value="5:55am">5:55am</option>
								<option value="6:00am">6:00am</option>
								<option value="6:05am">6:05am</option>
								<option value="6:10am">6:10am</option>
								<option value="6:15am">6:15am</option>
								<option value="6:20am">6:20am</option>
								<option value="6:25am">6:25am</option>
								<option value="6:30am">6:30am</option>
								<option value="6:35am">6:35am</option>
								<option value="6:40am">6:40am</option>
								<option value="6:45am">6:45am</option>
								<option value="6:50am">6:50am</option>
								<option value="6:55am">6:55am</option>
								<option value="7:00am">7:00am</option>
								<option value="7:05am">7:05am</option>
								<option value="7:10am">7:10am</option>
								<option value="7:15am">7:15am</option>
								<option value="7:20am">7:20am</option>
								<option value="7:25am">7:25am</option>
								<option value="7:30am">7:30am</option>
								<option value="7:35am">7:35am</option>
								<option value="7:40am">7:40am</option>
								<option value="7:45am">7:45am</option>
								<option value="7:50am">7:50am</option>
								<option value="7:55am">7:55am</option>
								<option value="8:00am">8:00am</option>
								<option value="8:05am">8:05am</option>
								<option value="8:10am">8:10am</option>
								<option value="8:15am">8:15am</option>
								<option value="8:20am">8:20am</option>
								<option value="8:25am">8:25am</option>
								<option value="8:30am">8:30am</option>
								<option value="8:35am">8:35am</option>
								<option value="8:40am">8:40am</option>
								<option value="8:45am">8:45am</option>
								<option value="8:50am">8:50am</option>
								<option value="8:55am">8:55am</option>
								<option value="9:00am">9:00am</option>
								<option value="9:05am">9:05am</option>
								<option value="9:10am">9:10am</option>
								<option value="9:15am">9:15am</option>
								<option value="9:20am">9:20am</option>
								<option value="9:25am">9:25am</option>
								<option value="9:30am">9:30am</option>
								<option value="9:35am">9:35am</option>
								<option value="9:40am">9:40am</option>
								<option value="9:45am">9:45am</option>
								<option value="9:50am">9:50am</option>
								<option value="9:55am">9:55am</option>
								<option value="10:00am">10:00am</option>
								<option value="10:05am">10:05am</option>
								<option value="10:10am">10:10am</option>
								<option value="10:15am">10:15am</option>
								<option value="10:20am">10:20am</option>
								<option value="10:25am">10:25am</option>
								<option value="10:30am">10:30am</option>
								<option value="10:35am">10:35am</option>
								<option value="10:40am">10:40am</option>
								<option value="10:45am">10:45am</option>
								<option value="10:50am">10:50am</option>
								<option value="10:55am">10:55am</option>
								<option value="11:00am">11:00am</option>
								<option value="11:05am">11:05am</option>
								<option value="11:10am">11:10am</option>
								<option value="11:15am">11:15am</option>
								<option value="11:20am">11:20am</option>
								<option value="11:25am">11:25am</option>
								<option value="11:30am">11:30am</option>
								<option value="11:35am">11:35am</option>
								<option value="11:40am">11:40am</option>
								<option value="11:45am">11:45am</option>
								<option value="11:50am">11:50am</option>
								<option value="11:55am">11:55am</option>
								<option value="12:00pm">12:00pm</option>
								<option value="12:05pm">12:05pm</option>
								<option value="12:10pm">12:10pm</option>
								<option value="12:15pm">12:15pm</option>
								<option value="12:20pm">12:20pm</option>
								<option value="12:25pm">12:25pm</option>
								<option value="12:30pm">12:30pm</option>
								<option value="12:35pm">12:35pm</option>
								<option value="12:40pm">12:40pm</option>
								<option value="12:45pm">12:45pm</option>
								<option value="12:50pm">12:50pm</option>
								<option value="12:55pm">12:55pm</option>
								<option value="1:00pm">1:00pm</option>
								<option value="1:05pm">1:05pm</option>
								<option value="1:10pm">1:10pm</option>
								<option value="1:15pm">1:15pm</option>
								<option value="1:20pm">1:20pm</option>
								<option value="1:25pm">1:25pm</option>
								<option value="1:30pm">1:30pm</option>
								<option value="1:35pm">1:35pm</option>
								<option value="1:40pm">1:40pm</option>
								<option value="1:45pm">1:45pm</option>
								<option value="1:50pm">1:50pm</option>
								<option value="1:55pm">1:55pm</option>
								<option value="2:00pm">2:00pm</option>
								<option value="2:05pm">2:05pm</option>
								<option value="2:10pm">2:10pm</option>
								<option value="2:15pm">2:15pm</option>
								<option value="2:20pm">2:20pm</option>
								<option value="2:25pm">2:25pm</option>
								<option value="2:30pm">2:30pm</option>
								<option value="2:35pm">2:35pm</option>
								<option value="2:40pm">2:40pm</option>
								<option value="2:45pm">2:45pm</option>
								<option value="2:50pm">2:50pm</option>
								<option value="2:55pm">2:55pm</option>
								<option value="3:00pm">3:00pm</option>
								<option value="3:05pm">3:05pm</option>
								<option value="3:10pm">3:10pm</option>
								<option value="3:15pm">3:15pm</option>
								<option value="3:20pm">3:20pm</option>
								<option value="3:25pm">3:25pm</option>
								<option value="3:30pm">3:30pm</option>
								<option value="3:35pm">3:35pm</option>
								<option value="3:40pm">3:40pm</option>
								<option value="3:45pm">3:45pm</option>
								<option value="3:50pm">3:50pm</option>
								<option value="3:55pm">3:55pm</option>
								<option value="4:00pm">4:00pm</option>
								<option value="4:05pm">4:05pm</option>
								<option value="4:10pm">4:10pm</option>
								<option value="4:15pm">4:15pm</option>
								<option value="4:20pm">4:20pm</option>
								<option value="4:25pm">4:25pm</option>
								<option value="4:30pm">4:30pm</option>
								<option value="4:35pm">4:35pm</option>
								<option value="4:40pm">4:40pm</option>
								<option value="4:45pm">4:45pm</option>
								<option value="4:50pm">4:50pm</option>
								<option value="4:55pm">4:55pm</option>
								<option value="5:00pm">5:00pm</option>
								<option value="5:05pm">5:05pm</option>
								<option value="5:10pm">5:10pm</option>
								<option value="5:15pm">5:15pm</option>
								<option value="5:20pm">5:20pm</option>
								<option value="5:25pm">5:25pm</option>
								<option value="5:30pm">5:30pm</option>
								<option value="5:35pm">5:35pm</option>
								<option value="5:40pm">5:40pm</option>
								<option value="5:45pm">5:45pm</option>
								<option value="5:50pm">5:50pm</option>
								<option value="5:55pm">5:55pm</option>
								<option value="6:00pm">6:00pm</option>
								<option value="6:05pm">6:05pm</option>
								<option value="6:10pm">6:10pm</option>
								<option value="6:15pm">6:15pm</option>
								<option value="6:20pm">6:20pm</option>
								<option value="6:25pm">6:25pm</option>
								<option value="6:30pm">6:30pm</option>
								<option value="6:35pm">6:35pm</option>
								<option value="6:40pm">6:40pm</option>
								<option value="6:45pm">6:45pm</option>
								<option value="6:50pm">6:50pm</option>
								<option value="6:55pm">6:55pm</option>
								<option value="7:00pm">7:00pm</option>
								<option value="7:05pm">7:05pm</option>
								<option value="7:10pm">7:10pm</option>
								<option value="7:15pm">7:15pm</option>
								<option value="7:20pm">7:20pm</option>
								<option value="7:25pm">7:25pm</option>
								<option value="7:30pm">7:30pm</option>
								<option value="7:35pm">7:35pm</option>
								<option value="7:40pm">7:40pm</option>
								<option value="7:45pm">7:45pm</option>
								<option value="7:50pm">7:50pm</option>
								<option value="7:55pm">7:55pm</option>
								<option value="8:00pm">8:00pm</option>
								<option value="8:05pm">8:05pm</option>
								<option value="8:10pm">8:10pm</option>
								<option value="8:15pm">8:15pm</option>
								<option value="8:20pm">8:20pm</option>
								<option value="8:25pm">8:25pm</option>
								<option value="8:30pm">8:30pm</option>
								<option value="8:35pm">8:35pm</option>
								<option value="8:40pm">8:40pm</option>
								<option value="8:45pm">8:45pm</option>
								<option value="8:50pm">8:50pm</option>
								<option value="8:55pm">8:55pm</option>
								<option value="9:00pm">9:00pm</option>
								<option value="9:05pm">9:05pm</option>
								<option value="9:10pm">9:10pm</option>
								<option value="9:15pm">9:15pm</option>
								<option value="9:20pm">9:20pm</option>
								<option value="9:25pm">9:25pm</option>
								<option value="9:30pm">9:30pm</option>
								<option value="9:35pm">9:35pm</option>
								<option value="9:40pm">9:40pm</option>
								<option value="9:45pm">9:45pm</option>
								<option value="9:50pm">9:50pm</option>
								<option value="9:55pm">9:55pm</option>
								<option value="10:00pm">10:00pm</option>
								<option value="10:05pm">10:05pm</option>
								<option value="10:10pm">10:10pm</option>
								<option value="10:15pm">10:15pm</option>
								<option value="10:20pm">10:20pm</option>
								<option value="10:25pm">10:25pm</option>
								<option value="10:30pm">10:30pm</option>
								<option value="10:35pm">10:35pm</option>
								<option value="10:40pm">10:40pm</option>
								<option value="10:45pm">10:45pm</option>
								<option value="10:50pm">10:50pm</option>
								<option value="10:55pm">10:55pm</option>
								<option value="11:00pm">11:00pm</option>
								<option value="11:05pm">11:05pm</option>
								<option value="11:10pm">11:10pm</option>
								<option value="11:15pm">11:15pm</option>
								<option value="11:20pm">11:20pm</option>
								<option value="11:25pm">11:25pm</option>
								<option value="11:30pm">11:30pm</option>
								<option value="11:35pm">11:35pm</option>
								<option value="11:40pm">11:40pm</option>
								<option value="11:45pm">11:45pm</option>
								<option value="11:50pm">11:50pm</option>
								<option value="11:55pm">11:55pm</option>
							</select>
						</div>
						<div class="col-6 pl-0">
							<label class="text-uppercase font-weight-bold d-sm-none">Shift End</label>
							<select class="form-control end-shift2" name="end_time[]" id="select-end-shift" style="-moz-appearance: none; -webkit-appearance: none;">
								<option value="0" selected>Select time</option>
								<option value="12:00am">12:00am</option>
								<option value="12:05am">12:05am</option>
								<option value="12:10am">12:10am</option>
								<option value="12:15am">12:15am</option>
								<option value="12:20am">12:20am</option>
								<option value="12:25am">12:25am</option>
								<option value="12:30am">12:30am</option>
								<option value="12:35am">12:35am</option>
								<option value="12:40am">12:40am</option>
								<option value="12:45am">12:45am</option>
								<option value="12:50am">12:50am</option>
								<option value="12:55am">12:55am</option>
								<option value="1:00am">1:00am</option>
								<option value="1:05am">1:05am</option>
								<option value="1:10am">1:10am</option>
								<option value="1:15am">1:15am</option>
								<option value="1:20am">1:20am</option>
								<option value="1:25am">1:25am</option>
								<option value="1:30am">1:30am</option>
								<option value="1:35am">1:35am</option>
								<option value="1:40am">1:40am</option>
								<option value="1:45am">1:45am</option>
								<option value="1:50am">1:50am</option>
								<option value="1:55am">1:55am</option>
								<option value="2:00am">2:00am</option>
								<option value="2:05am">2:05am</option>
								<option value="2:10am">2:10am</option>
								<option value="2:15am">2:15am</option>
								<option value="2:20am">2:20am</option>
								<option value="2:25am">2:25am</option>
								<option value="2:30am">2:30am</option>
								<option value="2:35am">2:35am</option>
								<option value="2:40am">2:40am</option>
								<option value="2:45am">2:45am</option>
								<option value="2:50am">2:50am</option>
								<option value="2:55am">2:55am</option>
								<option value="3:00am">3:00am</option>
								<option value="3:05am">3:05am</option>
								<option value="3:10am">3:10am</option>
								<option value="3:15am">3:15am</option>
								<option value="3:20am">3:20am</option>
								<option value="3:25am">3:25am</option>
								<option value="3:30am">3:30am</option>
								<option value="3:35am">3:35am</option>
								<option value="3:40am">3:40am</option>
								<option value="3:45am">3:45am</option>
								<option value="3:50am">3:50am</option>
								<option value="3:55am">3:55am</option>
								<option value="4:00am">4:00am</option>
								<option value="4:05am">4:05am</option>
								<option value="4:10am">4:10am</option>
								<option value="4:15am">4:15am</option>
								<option value="4:20am">4:20am</option>
								<option value="4:25am">4:25am</option>
								<option value="4:30am">4:30am</option>
								<option value="4:35am">4:35am</option>
								<option value="4:40am">4:40am</option>
								<option value="4:45am">4:45am</option>
								<option value="4:50am">4:50am</option>
								<option value="4:55am">4:55am</option>
								<option value="5:00am">5:00am</option>
								<option value="5:05am">5:05am</option>
								<option value="5:10am">5:10am</option>
								<option value="5:15am">5:15am</option>
								<option value="5:20am">5:20am</option>
								<option value="5:25am">5:25am</option>
								<option value="5:30am">5:30am</option>
								<option value="5:35am">5:35am</option>
								<option value="5:40am">5:40am</option>
								<option value="5:45am">5:45am</option>
								<option value="5:50am">5:50am</option>
								<option value="5:55am">5:55am</option>
								<option value="6:00am">6:00am</option>
								<option value="6:05am">6:05am</option>
								<option value="6:10am">6:10am</option>
								<option value="6:15am">6:15am</option>
								<option value="6:20am">6:20am</option>
								<option value="6:25am">6:25am</option>
								<option value="6:30am">6:30am</option>
								<option value="6:35am">6:35am</option>
								<option value="6:40am">6:40am</option>
								<option value="6:45am">6:45am</option>
								<option value="6:50am">6:50am</option>
								<option value="6:55am">6:55am</option>
								<option value="7:00am">7:00am</option>
								<option value="7:05am">7:05am</option>
								<option value="7:10am">7:10am</option>
								<option value="7:15am">7:15am</option>
								<option value="7:20am">7:20am</option>
								<option value="7:25am">7:25am</option>
								<option value="7:30am">7:30am</option>
								<option value="7:35am">7:35am</option>
								<option value="7:40am">7:40am</option>
								<option value="7:45am">7:45am</option>
								<option value="7:50am">7:50am</option>
								<option value="7:55am">7:55am</option>
								<option value="8:00am">8:00am</option>
								<option value="8:05am">8:05am</option>
								<option value="8:10am">8:10am</option>
								<option value="8:15am">8:15am</option>
								<option value="8:20am">8:20am</option>
								<option value="8:25am">8:25am</option>
								<option value="8:30am">8:30am</option>
								<option value="8:35am">8:35am</option>
								<option value="8:40am">8:40am</option>
								<option value="8:45am">8:45am</option>
								<option value="8:50am">8:50am</option>
								<option value="8:55am">8:55am</option>
								<option value="9:00am">9:00am</option>
								<option value="9:05am">9:05am</option>
								<option value="9:10am">9:10am</option>
								<option value="9:15am">9:15am</option>
								<option value="9:20am">9:20am</option>
								<option value="9:25am">9:25am</option>
								<option value="9:30am">9:30am</option>
								<option value="9:35am">9:35am</option>
								<option value="9:40am">9:40am</option>
								<option value="9:45am">9:45am</option>
								<option value="9:50am">9:50am</option>
								<option value="9:55am">9:55am</option>
								<option value="10:00am">10:00am</option>
								<option value="10:05am">10:05am</option>
								<option value="10:10am">10:10am</option>
								<option value="10:15am">10:15am</option>
								<option value="10:20am">10:20am</option>
								<option value="10:25am">10:25am</option>
								<option value="10:30am">10:30am</option>
								<option value="10:35am">10:35am</option>
								<option value="10:40am">10:40am</option>
								<option value="10:45am">10:45am</option>
								<option value="10:50am">10:50am</option>
								<option value="10:55am">10:55am</option>
								<option value="11:00am">11:00am</option>
								<option value="11:05am">11:05am</option>
								<option value="11:10am">11:10am</option>
								<option value="11:15am">11:15am</option>
								<option value="11:20am">11:20am</option>
								<option value="11:25am">11:25am</option>
								<option value="11:30am">11:30am</option>
								<option value="11:35am">11:35am</option>
								<option value="11:40am">11:40am</option>
								<option value="11:45am">11:45am</option>
								<option value="11:50am">11:50am</option>
								<option value="11:55am">11:55am</option>
								<option value="12:00pm">12:00pm</option>
								<option value="12:05pm">12:05pm</option>
								<option value="12:10pm">12:10pm</option>
								<option value="12:15pm">12:15pm</option>
								<option value="12:20pm">12:20pm</option>
								<option value="12:25pm">12:25pm</option>
								<option value="12:30pm">12:30pm</option>
								<option value="12:35pm">12:35pm</option>
								<option value="12:40pm">12:40pm</option>
								<option value="12:45pm">12:45pm</option>
								<option value="12:50pm">12:50pm</option>
								<option value="12:55pm">12:55pm</option>
								<option value="1:00pm">1:00pm</option>
								<option value="1:05pm">1:05pm</option>
								<option value="1:10pm">1:10pm</option>
								<option value="1:15pm">1:15pm</option>
								<option value="1:20pm">1:20pm</option>
								<option value="1:25pm">1:25pm</option>
								<option value="1:30pm">1:30pm</option>
								<option value="1:35pm">1:35pm</option>
								<option value="1:40pm">1:40pm</option>
								<option value="1:45pm">1:45pm</option>
								<option value="1:50pm">1:50pm</option>
								<option value="1:55pm">1:55pm</option>
								<option value="2:00pm">2:00pm</option>
								<option value="2:05pm">2:05pm</option>
								<option value="2:10pm">2:10pm</option>
								<option value="2:15pm">2:15pm</option>
								<option value="2:20pm">2:20pm</option>
								<option value="2:25pm">2:25pm</option>
								<option value="2:30pm">2:30pm</option>
								<option value="2:35pm">2:35pm</option>
								<option value="2:40pm">2:40pm</option>
								<option value="2:45pm">2:45pm</option>
								<option value="2:50pm">2:50pm</option>
								<option value="2:55pm">2:55pm</option>
								<option value="3:00pm">3:00pm</option>
								<option value="3:05pm">3:05pm</option>
								<option value="3:10pm">3:10pm</option>
								<option value="3:15pm">3:15pm</option>
								<option value="3:20pm">3:20pm</option>
								<option value="3:25pm">3:25pm</option>
								<option value="3:30pm">3:30pm</option>
								<option value="3:35pm">3:35pm</option>
								<option value="3:40pm">3:40pm</option>
								<option value="3:45pm">3:45pm</option>
								<option value="3:50pm">3:50pm</option>
								<option value="3:55pm">3:55pm</option>
								<option value="4:00pm">4:00pm</option>
								<option value="4:05pm">4:05pm</option>
								<option value="4:10pm">4:10pm</option>
								<option value="4:15pm">4:15pm</option>
								<option value="4:20pm">4:20pm</option>
								<option value="4:25pm">4:25pm</option>
								<option value="4:30pm">4:30pm</option>
								<option value="4:35pm">4:35pm</option>
								<option value="4:40pm">4:40pm</option>
								<option value="4:45pm">4:45pm</option>
								<option value="4:50pm">4:50pm</option>
								<option value="4:55pm">4:55pm</option>
								<option value="5:00pm">5:00pm</option>
								<option value="5:05pm">5:05pm</option>
								<option value="5:10pm">5:10pm</option>
								<option value="5:15pm">5:15pm</option>
								<option value="5:20pm">5:20pm</option>
								<option value="5:25pm">5:25pm</option>
								<option value="5:30pm">5:30pm</option>
								<option value="5:35pm">5:35pm</option>
								<option value="5:40pm">5:40pm</option>
								<option value="5:45pm">5:45pm</option>
								<option value="5:50pm">5:50pm</option>
								<option value="5:55pm">5:55pm</option>
								<option value="6:00pm">6:00pm</option>
								<option value="6:05pm">6:05pm</option>
								<option value="6:10pm">6:10pm</option>
								<option value="6:15pm">6:15pm</option>
								<option value="6:20pm">6:20pm</option>
								<option value="6:25pm">6:25pm</option>
								<option value="6:30pm">6:30pm</option>
								<option value="6:35pm">6:35pm</option>
								<option value="6:40pm">6:40pm</option>
								<option value="6:45pm">6:45pm</option>
								<option value="6:50pm">6:50pm</option>
								<option value="6:55pm">6:55pm</option>
								<option value="7:00pm">7:00pm</option>
								<option value="7:05pm">7:05pm</option>
								<option value="7:10pm">7:10pm</option>
								<option value="7:15pm">7:15pm</option>
								<option value="7:20pm">7:20pm</option>
								<option value="7:25pm">7:25pm</option>
								<option value="7:30pm">7:30pm</option>
								<option value="7:35pm">7:35pm</option>
								<option value="7:40pm">7:40pm</option>
								<option value="7:45pm">7:45pm</option>
								<option value="7:50pm">7:50pm</option>
								<option value="7:55pm">7:55pm</option>
								<option value="8:00pm">8:00pm</option>
								<option value="8:05pm">8:05pm</option>
								<option value="8:10pm">8:10pm</option>
								<option value="8:15pm">8:15pm</option>
								<option value="8:20pm">8:20pm</option>
								<option value="8:25pm">8:25pm</option>
								<option value="8:30pm">8:30pm</option>
								<option value="8:35pm">8:35pm</option>
								<option value="8:40pm">8:40pm</option>
								<option value="8:45pm">8:45pm</option>
								<option value="8:50pm">8:50pm</option>
								<option value="8:55pm">8:55pm</option>
								<option value="9:00pm">9:00pm</option>
								<option value="9:05pm">9:05pm</option>
								<option value="9:10pm">9:10pm</option>
								<option value="9:15pm">9:15pm</option>
								<option value="9:20pm">9:20pm</option>
								<option value="9:25pm">9:25pm</option>
								<option value="9:30pm">9:30pm</option>
								<option value="9:35pm">9:35pm</option>
								<option value="9:40pm">9:40pm</option>
								<option value="9:45pm">9:45pm</option>
								<option value="9:50pm">9:50pm</option>
								<option value="9:55pm">9:55pm</option>
								<option value="10:00pm">10:00pm</option>
								<option value="10:05pm">10:05pm</option>
								<option value="10:10pm">10:10pm</option>
								<option value="10:15pm">10:15pm</option>
								<option value="10:20pm">10:20pm</option>
								<option value="10:25pm">10:25pm</option>
								<option value="10:30pm">10:30pm</option>
								<option value="10:35pm">10:35pm</option>
								<option value="10:40pm">10:40pm</option>
								<option value="10:45pm">10:45pm</option>
								<option value="10:50pm">10:50pm</option>
								<option value="10:55pm">10:55pm</option>
								<option value="11:00pm">11:00pm</option>
								<option value="11:05pm">11:05pm</option>
								<option value="11:10pm">11:10pm</option>
								<option value="11:15pm">11:15pm</option>
								<option value="11:20pm">11:20pm</option>
								<option value="11:25pm">11:25pm</option>
								<option value="11:30pm">11:30pm</option>
								<option value="11:35pm">11:35pm</option>
								<option value="11:40pm">11:40pm</option>
								<option value="11:45pm">11:45pm</option>
								<option value="11:50pm">11:50pm</option>
								<option value="11:55pm">11:55pm</option>
							</select>
							<span class="reset-addon">
								<a class="reset-second-shift" href="#"><i class="la la-close" id="la-close"></i></a>
							</span>
						</div>
					</div>
					<div class="row break-hint text-center justify-content-center hidden">1 hour break time</div>
					<div class="row">
						<div class="col-12">
							<button type="button" class="btn btn-outline-secondary add-shift" id="add-shift">Add another shift</button>
						</div>
					</div>
					<div class="row attached-fields m-0 mt-5">
						<div class="col-12 repeat-switch-column repeat_optin no-padding" id="repeat_optin">
							<label for="schedule_repeats" class="text-uppercase font-weight-bold">Repeats</label>
							<div class="select-wrapper">
								<select class="form-control repeat-switch" id="schedule_repeats" name="repeats">
									<option value="0">Don t repeat</option>
									<option value="1">Weekly</option>
								</select>
							</div>
						</div>
						<div class="col-6 pr-0 repeat-container no-padding" id="end_repeat" style="display: none;">
							<label class="form-label text-uppercase font-weight-bold end_repeat_field">End repeat</label>
							<div class="form-group end-repeat-container end_repeat_field">
								<div class="select-wrapper">
									<select class="form-control end-repeat" id="schedule_end_repeat" name="endrepeat">
										<option value="0">Ongoing</option>
										<option value="1">Specific date</option>
									</select>
								</div>
							</div>
							<div class="form-group position-relative date-end-field" id="specific_date_div" style="display: none;">
								<label class="form-label text-uppercase font-weight-bold">End Date</label>
								<div class="form-group end-repeat-container">
									<input type="text" class="form-control" id="schedule_date_end" name="spenddate" readonly="" placeholder="End date" />
								</div>
							</div>
						</div>
					</div>
				</div>
				{{ Form::close() }}
				<div class="modal-footer">
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="add_working_hours">Save changes</button>
				</div>
			</div>
		</div>
	</div>
	{{-- end --}}
	{{-- edit modal --}}
	<div class="modal fade schedule-modal" id="editshiftDateModalCenter" tabindex="-1" role="dialog"
		aria-labelledby="shiftDateModalCenterTitle" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header flex-center flex-column pb-3">
					<h5 class="font-weight-normal modal-title" id="editShiftDateModalLongTitle">
						<p id="editusername"></p>
					</h5>
					<p id="editdate"></p>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				{{ Form::open(array('url' => 'edit_staff_time','id' => 'edit_staff_time')) }}
				@csrf
				<div class="modal-body">
					<input type="hidden" name="id" id="hours_id">
					<div class="row first-shift mb-3">
						<div class="col-6 pr-0">
							<label class="text-uppercase font-weight-bold">Shift Start</label>
							<select class="form-control edit-start-shift1" name="start_time[]" id="select-start-shift1">
								<option value="12:00am">12:00am</option>
								<option value="12:05am">12:05am</option>
								<option value="12:10am">12:10am</option>
								<option value="12:15am">12:15am</option>
								<option value="12:20am">12:20am</option>
								<option value="12:25am">12:25am</option>
								<option value="12:30am">12:30am</option>
								<option value="12:35am">12:35am</option>
								<option value="12:40am">12:40am</option>
								<option value="12:45am">12:45am</option>
								<option value="12:50am">12:50am</option>
								<option value="12:55am">12:55am</option>
								<option value="1:00am">1:00am</option>
								<option value="1:05am">1:05am</option>
								<option value="1:10am">1:10am</option>
								<option value="1:15am">1:15am</option>
								<option value="1:20am">1:20am</option>
								<option value="1:25am">1:25am</option>
								<option value="1:30am">1:30am</option>
								<option value="1:35am">1:35am</option>
								<option value="1:40am">1:40am</option>
								<option value="1:45am">1:45am</option>
								<option value="1:50am">1:50am</option>
								<option value="1:55am">1:55am</option>
								<option value="2:00am">2:00am</option>
								<option value="2:05am">2:05am</option>
								<option value="2:10am">2:10am</option>
								<option value="2:15am">2:15am</option>
								<option value="2:20am">2:20am</option>
								<option value="2:25am">2:25am</option>
								<option value="2:30am">2:30am</option>
								<option value="2:35am">2:35am</option>
								<option value="2:40am">2:40am</option>
								<option value="2:45am">2:45am</option>
								<option value="2:50am">2:50am</option>
								<option value="2:55am">2:55am</option>
								<option value="3:00am">3:00am</option>
								<option value="3:05am">3:05am</option>
								<option value="3:10am">3:10am</option>
								<option value="3:15am">3:15am</option>
								<option value="3:20am">3:20am</option>
								<option value="3:25am">3:25am</option>
								<option value="3:30am">3:30am</option>
								<option value="3:35am">3:35am</option>
								<option value="3:40am">3:40am</option>
								<option value="3:45am">3:45am</option>
								<option value="3:50am">3:50am</option>
								<option value="3:55am">3:55am</option>
								<option value="4:00am">4:00am</option>
								<option value="4:05am">4:05am</option>
								<option value="4:10am">4:10am</option>
								<option value="4:15am">4:15am</option>
								<option value="4:20am">4:20am</option>
								<option value="4:25am">4:25am</option>
								<option value="4:30am">4:30am</option>
								<option value="4:35am">4:35am</option>
								<option value="4:40am">4:40am</option>
								<option value="4:45am">4:45am</option>
								<option value="4:50am">4:50am</option>
								<option value="4:55am">4:55am</option>
								<option value="5:00am">5:00am</option>
								<option value="5:05am">5:05am</option>
								<option value="5:10am">5:10am</option>
								<option value="5:15am">5:15am</option>
								<option value="5:20am">5:20am</option>
								<option value="5:25am">5:25am</option>
								<option value="5:30am">5:30am</option>
								<option value="5:35am">5:35am</option>
								<option value="5:40am">5:40am</option>
								<option value="5:45am">5:45am</option>
								<option value="5:50am">5:50am</option>
								<option value="5:55am">5:55am</option>
								<option value="6:00am">6:00am</option>
								<option value="6:05am">6:05am</option>
								<option value="6:10am">6:10am</option>
								<option value="6:15am">6:15am</option>
								<option value="6:20am">6:20am</option>
								<option value="6:25am">6:25am</option>
								<option value="6:30am">6:30am</option>
								<option value="6:35am">6:35am</option>
								<option value="6:40am">6:40am</option>
								<option value="6:45am">6:45am</option>
								<option value="6:50am">6:50am</option>
								<option value="6:55am">6:55am</option>
								<option value="7:00am">7:00am</option>
								<option value="7:05am">7:05am</option>
								<option value="7:10am">7:10am</option>
								<option value="7:15am">7:15am</option>
								<option value="7:20am">7:20am</option>
								<option value="7:25am">7:25am</option>
								<option value="7:30am">7:30am</option>
								<option value="7:35am">7:35am</option>
								<option value="7:40am">7:40am</option>
								<option value="7:45am">7:45am</option>
								<option value="7:50am">7:50am</option>
								<option value="7:55am">7:55am</option>
								<option value="8:00am">8:00am</option>
								<option value="8:05am">8:05am</option>
								<option value="8:10am">8:10am</option>
								<option value="8:15am">8:15am</option>
								<option value="8:20am">8:20am</option>
								<option value="8:25am">8:25am</option>
								<option value="8:30am">8:30am</option>
								<option value="8:35am">8:35am</option>
								<option value="8:40am">8:40am</option>
								<option value="8:45am">8:45am</option>
								<option value="8:50am">8:50am</option>
								<option value="8:55am">8:55am</option>
								<option value="9:00am">9:00am</option>
								<option value="9:05am">9:05am</option>
								<option value="9:10am">9:10am</option>
								<option value="9:15am">9:15am</option>
								<option value="9:20am">9:20am</option>
								<option value="9:25am">9:25am</option>
								<option value="9:30am">9:30am</option>
								<option value="9:35am">9:35am</option>
								<option value="9:40am">9:40am</option>
								<option value="9:45am">9:45am</option>
								<option value="9:50am">9:50am</option>
								<option value="9:55am">9:55am</option>
								<option value="10:00am">10:00am</option>
								<option value="10:05am">10:05am</option>
								<option value="10:10am">10:10am</option>
								<option value="10:15am">10:15am</option>
								<option value="10:20am">10:20am</option>
								<option value="10:25am">10:25am</option>
								<option value="10:30am">10:30am</option>
								<option value="10:35am">10:35am</option>
								<option value="10:40am">10:40am</option>
								<option value="10:45am">10:45am</option>
								<option value="10:50am">10:50am</option>
								<option value="10:55am">10:55am</option>
								<option value="11:00am">11:00am</option>
								<option value="11:05am">11:05am</option>
								<option value="11:10am">11:10am</option>
								<option value="11:15am">11:15am</option>
								<option value="11:20am">11:20am</option>
								<option value="11:25am">11:25am</option>
								<option value="11:30am">11:30am</option>
								<option value="11:35am">11:35am</option>
								<option value="11:40am">11:40am</option>
								<option value="11:45am">11:45am</option>
								<option value="11:50am">11:50am</option>
								<option value="11:55am">11:55am</option>
								<option value="12:00pm">12:00pm</option>
								<option value="12:05pm">12:05pm</option>
								<option value="12:10pm">12:10pm</option>
								<option value="12:15pm">12:15pm</option>
								<option value="12:20pm">12:20pm</option>
								<option value="12:25pm">12:25pm</option>
								<option value="12:30pm">12:30pm</option>
								<option value="12:35pm">12:35pm</option>
								<option value="12:40pm">12:40pm</option>
								<option value="12:45pm">12:45pm</option>
								<option value="12:50pm">12:50pm</option>
								<option value="12:55pm">12:55pm</option>
								<option value="1:00pm">1:00pm</option>
								<option value="1:05pm">1:05pm</option>
								<option value="1:10pm">1:10pm</option>
								<option value="1:15pm">1:15pm</option>
								<option value="1:20pm">1:20pm</option>
								<option value="1:25pm">1:25pm</option>
								<option value="1:30pm">1:30pm</option>
								<option value="1:35pm">1:35pm</option>
								<option value="1:40pm">1:40pm</option>
								<option value="1:45pm">1:45pm</option>
								<option value="1:50pm">1:50pm</option>
								<option value="1:55pm">1:55pm</option>
								<option value="2:00pm">2:00pm</option>
								<option value="2:05pm">2:05pm</option>
								<option value="2:10pm">2:10pm</option>
								<option value="2:15pm">2:15pm</option>
								<option value="2:20pm">2:20pm</option>
								<option value="2:25pm">2:25pm</option>
								<option value="2:30pm">2:30pm</option>
								<option value="2:35pm">2:35pm</option>
								<option value="2:40pm">2:40pm</option>
								<option value="2:45pm">2:45pm</option>
								<option value="2:50pm">2:50pm</option>
								<option value="2:55pm">2:55pm</option>
								<option value="3:00pm">3:00pm</option>
								<option value="3:05pm">3:05pm</option>
								<option value="3:10pm">3:10pm</option>
								<option value="3:15pm">3:15pm</option>
								<option value="3:20pm">3:20pm</option>
								<option value="3:25pm">3:25pm</option>
								<option value="3:30pm">3:30pm</option>
								<option value="3:35pm">3:35pm</option>
								<option value="3:40pm">3:40pm</option>
								<option value="3:45pm">3:45pm</option>
								<option value="3:50pm">3:50pm</option>
								<option value="3:55pm">3:55pm</option>
								<option value="4:00pm">4:00pm</option>
								<option value="4:05pm">4:05pm</option>
								<option value="4:10pm">4:10pm</option>
								<option value="4:15pm">4:15pm</option>
								<option value="4:20pm">4:20pm</option>
								<option value="4:25pm">4:25pm</option>
								<option value="4:30pm">4:30pm</option>
								<option value="4:35pm">4:35pm</option>
								<option value="4:40pm">4:40pm</option>
								<option value="4:45pm">4:45pm</option>
								<option value="4:50pm">4:50pm</option>
								<option value="4:55pm">4:55pm</option>
								<option value="5:00pm">5:00pm</option>
								<option value="5:05pm">5:05pm</option>
								<option value="5:10pm">5:10pm</option>
								<option value="5:15pm">5:15pm</option>
								<option value="5:20pm">5:20pm</option>
								<option value="5:25pm">5:25pm</option>
								<option value="5:30pm">5:30pm</option>
								<option value="5:35pm">5:35pm</option>
								<option value="5:40pm">5:40pm</option>
								<option value="5:45pm">5:45pm</option>
								<option value="5:50pm">5:50pm</option>
								<option value="5:55pm">5:55pm</option>
								<option value="6:00pm">6:00pm</option>
								<option value="6:05pm">6:05pm</option>
								<option value="6:10pm">6:10pm</option>
								<option value="6:15pm">6:15pm</option>
								<option value="6:20pm">6:20pm</option>
								<option value="6:25pm">6:25pm</option>
								<option value="6:30pm">6:30pm</option>
								<option value="6:35pm">6:35pm</option>
								<option value="6:40pm">6:40pm</option>
								<option value="6:45pm">6:45pm</option>
								<option value="6:50pm">6:50pm</option>
								<option value="6:55pm">6:55pm</option>
								<option value="7:00pm">7:00pm</option>
								<option value="7:05pm">7:05pm</option>
								<option value="7:10pm">7:10pm</option>
								<option value="7:15pm">7:15pm</option>
								<option value="7:20pm">7:20pm</option>
								<option value="7:25pm">7:25pm</option>
								<option value="7:30pm">7:30pm</option>
								<option value="7:35pm">7:35pm</option>
								<option value="7:40pm">7:40pm</option>
								<option value="7:45pm">7:45pm</option>
								<option value="7:50pm">7:50pm</option>
								<option value="7:55pm">7:55pm</option>
								<option value="8:00pm">8:00pm</option>
								<option value="8:05pm">8:05pm</option>
								<option value="8:10pm">8:10pm</option>
								<option value="8:15pm">8:15pm</option>
								<option value="8:20pm">8:20pm</option>
								<option value="8:25pm">8:25pm</option>
								<option value="8:30pm">8:30pm</option>
								<option value="8:35pm">8:35pm</option>
								<option value="8:40pm">8:40pm</option>
								<option value="8:45pm">8:45pm</option>
								<option value="8:50pm">8:50pm</option>
								<option value="8:55pm">8:55pm</option>
								<option value="9:00pm">9:00pm</option>
								<option value="9:05pm">9:05pm</option>
								<option value="9:10pm">9:10pm</option>
								<option value="9:15pm">9:15pm</option>
								<option value="9:20pm">9:20pm</option>
								<option value="9:25pm">9:25pm</option>
								<option value="9:30pm">9:30pm</option>
								<option value="9:35pm">9:35pm</option>
								<option value="9:40pm">9:40pm</option>
								<option value="9:45pm">9:45pm</option>
								<option value="9:50pm">9:50pm</option>
								<option value="9:55pm">9:55pm</option>
								<option value="10:00pm">10:00pm</option>
								<option value="10:05pm">10:05pm</option>
								<option value="10:10pm">10:10pm</option>
								<option value="10:15pm">10:15pm</option>
								<option value="10:20pm">10:20pm</option>
								<option value="10:25pm">10:25pm</option>
								<option value="10:30pm">10:30pm</option>
								<option value="10:35pm">10:35pm</option>
								<option value="10:40pm">10:40pm</option>
								<option value="10:45pm">10:45pm</option>
								<option value="10:50pm">10:50pm</option>
								<option value="10:55pm">10:55pm</option>
								<option value="11:00pm">11:00pm</option>
								<option value="11:05pm">11:05pm</option>
								<option value="11:10pm">11:10pm</option>
								<option value="11:15pm">11:15pm</option>
								<option value="11:20pm">11:20pm</option>
								<option value="11:25pm">11:25pm</option>
								<option value="11:30pm">11:30pm</option>
								<option value="11:35pm">11:35pm</option>
								<option value="11:40pm">11:40pm</option>
								<option value="11:45pm">11:45pm</option>
								<option value="11:50pm">11:50pm</option>
								<option value="11:55pm">11:55pm</option>
							</select>
						</div>
						<div class="col-6 pl-0">
							<label class="text-uppercase font-weight-bold">Shift End</label>
							<select class="form-control edit-end-shift1" name="end_time[]" id="select-end-shift1">
								<option value="12:00am">12:00am</option>
								<option value="12:05am">12:05am</option>
								<option value="12:10am">12:10am</option>
								<option value="12:15am">12:15am</option>
								<option value="12:20am">12:20am</option>
								<option value="12:25am">12:25am</option>
								<option value="12:30am">12:30am</option>
								<option value="12:35am">12:35am</option>
								<option value="12:40am">12:40am</option>
								<option value="12:45am">12:45am</option>
								<option value="12:50am">12:50am</option>
								<option value="12:55am">12:55am</option>
								<option value="1:00am">1:00am</option>
								<option value="1:05am">1:05am</option>
								<option value="1:10am">1:10am</option>
								<option value="1:15am">1:15am</option>
								<option value="1:20am">1:20am</option>
								<option value="1:25am">1:25am</option>
								<option value="1:30am">1:30am</option>
								<option value="1:35am">1:35am</option>
								<option value="1:40am">1:40am</option>
								<option value="1:45am">1:45am</option>
								<option value="1:50am">1:50am</option>
								<option value="1:55am">1:55am</option>
								<option value="2:00am">2:00am</option>
								<option value="2:05am">2:05am</option>
								<option value="2:10am">2:10am</option>
								<option value="2:15am">2:15am</option>
								<option value="2:20am">2:20am</option>
								<option value="2:25am">2:25am</option>
								<option value="2:30am">2:30am</option>
								<option value="2:35am">2:35am</option>
								<option value="2:40am">2:40am</option>
								<option value="2:45am">2:45am</option>
								<option value="2:50am">2:50am</option>
								<option value="2:55am">2:55am</option>
								<option value="3:00am">3:00am</option>
								<option value="3:05am">3:05am</option>
								<option value="3:10am">3:10am</option>
								<option value="3:15am">3:15am</option>
								<option value="3:20am">3:20am</option>
								<option value="3:25am">3:25am</option>
								<option value="3:30am">3:30am</option>
								<option value="3:35am">3:35am</option>
								<option value="3:40am">3:40am</option>
								<option value="3:45am">3:45am</option>
								<option value="3:50am">3:50am</option>
								<option value="3:55am">3:55am</option>
								<option value="4:00am">4:00am</option>
								<option value="4:05am">4:05am</option>
								<option value="4:10am">4:10am</option>
								<option value="4:15am">4:15am</option>
								<option value="4:20am">4:20am</option>
								<option value="4:25am">4:25am</option>
								<option value="4:30am">4:30am</option>
								<option value="4:35am">4:35am</option>
								<option value="4:40am">4:40am</option>
								<option value="4:45am">4:45am</option>
								<option value="4:50am">4:50am</option>
								<option value="4:55am">4:55am</option>
								<option value="5:00am">5:00am</option>
								<option value="5:05am">5:05am</option>
								<option value="5:10am">5:10am</option>
								<option value="5:15am">5:15am</option>
								<option value="5:20am">5:20am</option>
								<option value="5:25am">5:25am</option>
								<option value="5:30am">5:30am</option>
								<option value="5:35am">5:35am</option>
								<option value="5:40am">5:40am</option>
								<option value="5:45am">5:45am</option>
								<option value="5:50am">5:50am</option>
								<option value="5:55am">5:55am</option>
								<option value="6:00am">6:00am</option>
								<option value="6:05am">6:05am</option>
								<option value="6:10am">6:10am</option>
								<option value="6:15am">6:15am</option>
								<option value="6:20am">6:20am</option>
								<option value="6:25am">6:25am</option>
								<option value="6:30am">6:30am</option>
								<option value="6:35am">6:35am</option>
								<option value="6:40am">6:40am</option>
								<option value="6:45am">6:45am</option>
								<option value="6:50am">6:50am</option>
								<option value="6:55am">6:55am</option>
								<option value="7:00am">7:00am</option>
								<option value="7:05am">7:05am</option>
								<option value="7:10am">7:10am</option>
								<option value="7:15am">7:15am</option>
								<option value="7:20am">7:20am</option>
								<option value="7:25am">7:25am</option>
								<option value="7:30am">7:30am</option>
								<option value="7:35am">7:35am</option>
								<option value="7:40am">7:40am</option>
								<option value="7:45am">7:45am</option>
								<option value="7:50am">7:50am</option>
								<option value="7:55am">7:55am</option>
								<option value="8:00am">8:00am</option>
								<option value="8:05am">8:05am</option>
								<option value="8:10am">8:10am</option>
								<option value="8:15am">8:15am</option>
								<option value="8:20am">8:20am</option>
								<option value="8:25am">8:25am</option>
								<option value="8:30am">8:30am</option>
								<option value="8:35am">8:35am</option>
								<option value="8:40am">8:40am</option>
								<option value="8:45am">8:45am</option>
								<option value="8:50am">8:50am</option>
								<option value="8:55am">8:55am</option>
								<option value="9:00am">9:00am</option>
								<option value="9:05am">9:05am</option>
								<option value="9:10am">9:10am</option>
								<option value="9:15am">9:15am</option>
								<option value="9:20am">9:20am</option>
								<option value="9:25am">9:25am</option>
								<option value="9:30am">9:30am</option>
								<option value="9:35am">9:35am</option>
								<option value="9:40am">9:40am</option>
								<option value="9:45am">9:45am</option>
								<option value="9:50am">9:50am</option>
								<option value="9:55am">9:55am</option>
								<option value="10:00am">10:00am</option>
								<option value="10:05am">10:05am</option>
								<option value="10:10am">10:10am</option>
								<option value="10:15am">10:15am</option>
								<option value="10:20am">10:20am</option>
								<option value="10:25am">10:25am</option>
								<option value="10:30am">10:30am</option>
								<option value="10:35am">10:35am</option>
								<option value="10:40am">10:40am</option>
								<option value="10:45am">10:45am</option>
								<option value="10:50am">10:50am</option>
								<option value="10:55am">10:55am</option>
								<option value="11:00am">11:00am</option>
								<option value="11:05am">11:05am</option>
								<option value="11:10am">11:10am</option>
								<option value="11:15am">11:15am</option>
								<option value="11:20am">11:20am</option>
								<option value="11:25am">11:25am</option>
								<option value="11:30am">11:30am</option>
								<option value="11:35am">11:35am</option>
								<option value="11:40am">11:40am</option>
								<option value="11:45am">11:45am</option>
								<option value="11:50am">11:50am</option>
								<option value="11:55am">11:55am</option>
								<option value="12:00pm">12:00pm</option>
								<option value="12:05pm">12:05pm</option>
								<option value="12:10pm">12:10pm</option>
								<option value="12:15pm">12:15pm</option>
								<option value="12:20pm">12:20pm</option>
								<option value="12:25pm">12:25pm</option>
								<option value="12:30pm">12:30pm</option>
								<option value="12:35pm">12:35pm</option>
								<option value="12:40pm">12:40pm</option>
								<option value="12:45pm">12:45pm</option>
								<option value="12:50pm">12:50pm</option>
								<option value="12:55pm">12:55pm</option>
								<option value="1:00pm">1:00pm</option>
								<option value="1:05pm">1:05pm</option>
								<option value="1:10pm">1:10pm</option>
								<option value="1:15pm">1:15pm</option>
								<option value="1:20pm">1:20pm</option>
								<option value="1:25pm">1:25pm</option>
								<option value="1:30pm">1:30pm</option>
								<option value="1:35pm">1:35pm</option>
								<option value="1:40pm">1:40pm</option>
								<option value="1:45pm">1:45pm</option>
								<option value="1:50pm">1:50pm</option>
								<option value="1:55pm">1:55pm</option>
								<option value="2:00pm">2:00pm</option>
								<option value="2:05pm">2:05pm</option>
								<option value="2:10pm">2:10pm</option>
								<option value="2:15pm">2:15pm</option>
								<option value="2:20pm">2:20pm</option>
								<option value="2:25pm">2:25pm</option>
								<option value="2:30pm">2:30pm</option>
								<option value="2:35pm">2:35pm</option>
								<option value="2:40pm">2:40pm</option>
								<option value="2:45pm">2:45pm</option>
								<option value="2:50pm">2:50pm</option>
								<option value="2:55pm">2:55pm</option>
								<option value="3:00pm">3:00pm</option>
								<option value="3:05pm">3:05pm</option>
								<option value="3:10pm">3:10pm</option>
								<option value="3:15pm">3:15pm</option>
								<option value="3:20pm">3:20pm</option>
								<option value="3:25pm">3:25pm</option>
								<option value="3:30pm">3:30pm</option>
								<option value="3:35pm">3:35pm</option>
								<option value="3:40pm">3:40pm</option>
								<option value="3:45pm">3:45pm</option>
								<option value="3:50pm">3:50pm</option>
								<option value="3:55pm">3:55pm</option>
								<option value="4:00pm">4:00pm</option>
								<option value="4:05pm">4:05pm</option>
								<option value="4:10pm">4:10pm</option>
								<option value="4:15pm">4:15pm</option>
								<option value="4:20pm">4:20pm</option>
								<option value="4:25pm">4:25pm</option>
								<option value="4:30pm">4:30pm</option>
								<option value="4:35pm">4:35pm</option>
								<option value="4:40pm">4:40pm</option>
								<option value="4:45pm">4:45pm</option>
								<option value="4:50pm">4:50pm</option>
								<option value="4:55pm">4:55pm</option>
								<option value="5:00pm">5:00pm</option>
								<option value="5:05pm">5:05pm</option>
								<option value="5:10pm">5:10pm</option>
								<option value="5:15pm">5:15pm</option>
								<option value="5:20pm">5:20pm</option>
								<option value="5:25pm">5:25pm</option>
								<option value="5:30pm">5:30pm</option>
								<option value="5:35pm">5:35pm</option>
								<option value="5:40pm">5:40pm</option>
								<option value="5:45pm">5:45pm</option>
								<option value="5:50pm">5:50pm</option>
								<option value="5:55pm">5:55pm</option>
								<option value="6:00pm">6:00pm</option>
								<option value="6:05pm">6:05pm</option>
								<option value="6:10pm">6:10pm</option>
								<option value="6:15pm">6:15pm</option>
								<option value="6:20pm">6:20pm</option>
								<option value="6:25pm">6:25pm</option>
								<option value="6:30pm">6:30pm</option>
								<option value="6:35pm">6:35pm</option>
								<option value="6:40pm">6:40pm</option>
								<option value="6:45pm">6:45pm</option>
								<option value="6:50pm">6:50pm</option>
								<option value="6:55pm">6:55pm</option>
								<option value="7:00pm">7:00pm</option>
								<option value="7:05pm">7:05pm</option>
								<option value="7:10pm">7:10pm</option>
								<option value="7:15pm">7:15pm</option>
								<option value="7:20pm">7:20pm</option>
								<option value="7:25pm">7:25pm</option>
								<option value="7:30pm">7:30pm</option>
								<option value="7:35pm">7:35pm</option>
								<option value="7:40pm">7:40pm</option>
								<option value="7:45pm">7:45pm</option>
								<option value="7:50pm">7:50pm</option>
								<option value="7:55pm">7:55pm</option>
								<option value="8:00pm">8:00pm</option>
								<option value="8:05pm">8:05pm</option>
								<option value="8:10pm">8:10pm</option>
								<option value="8:15pm">8:15pm</option>
								<option value="8:20pm">8:20pm</option>
								<option value="8:25pm">8:25pm</option>
								<option value="8:30pm">8:30pm</option>
								<option value="8:35pm">8:35pm</option>
								<option value="8:40pm">8:40pm</option>
								<option value="8:45pm">8:45pm</option>
								<option value="8:50pm">8:50pm</option>
								<option value="8:55pm">8:55pm</option>
								<option value="9:00pm">9:00pm</option>
								<option value="9:05pm">9:05pm</option>
								<option value="9:10pm">9:10pm</option>
								<option value="9:15pm">9:15pm</option>
								<option value="9:20pm">9:20pm</option>
								<option value="9:25pm">9:25pm</option>
								<option value="9:30pm">9:30pm</option>
								<option value="9:35pm">9:35pm</option>
								<option value="9:40pm">9:40pm</option>
								<option value="9:45pm">9:45pm</option>
								<option value="9:50pm">9:50pm</option>
								<option value="9:55pm">9:55pm</option>
								<option value="10:00pm">10:00pm</option>
								<option value="10:05pm">10:05pm</option>
								<option value="10:10pm">10:10pm</option>
								<option value="10:15pm">10:15pm</option>
								<option value="10:20pm">10:20pm</option>
								<option value="10:25pm">10:25pm</option>
								<option value="10:30pm">10:30pm</option>
								<option value="10:35pm">10:35pm</option>
								<option value="10:40pm">10:40pm</option>
								<option value="10:45pm">10:45pm</option>
								<option value="10:50pm">10:50pm</option>
								<option value="10:55pm">10:55pm</option>
								<option value="11:00pm">11:00pm</option>
								<option value="11:05pm">11:05pm</option>
								<option value="11:10pm">11:10pm</option>
								<option value="11:15pm">11:15pm</option>
								<option value="11:20pm">11:20pm</option>
								<option value="11:25pm">11:25pm</option>
								<option value="11:30pm">11:30pm</option>
								<option value="11:35pm">11:35pm</option>
								<option value="11:40pm">11:40pm</option>
								<option value="11:45pm">11:45pm</option>
								<option value="11:50pm">11:50pm</option>
								<option value="11:55pm">11:55pm</option>
							</select>
						</div>
					</div>
					<div class="row second-shift" id="second-shift1" style="display:none">
						<div class="col-6 pr-0">
							<label class="text-uppercase font-weight-bold d-sm-none">Shift Start</label>
							<select class="form-control edit-start-shift2" name="start_time[]" id="select-start-shift2">
								<option value="0" selected>Select time</option>
								<option value="12:00am">12:00am</option>
								<option value="12:05am">12:05am</option>
								<option value="12:10am">12:10am</option>
								<option value="12:15am">12:15am</option>
								<option value="12:20am">12:20am</option>
								<option value="12:25am">12:25am</option>
								<option value="12:30am">12:30am</option>
								<option value="12:35am">12:35am</option>
								<option value="12:40am">12:40am</option>
								<option value="12:45am">12:45am</option>
								<option value="12:50am">12:50am</option>
								<option value="12:55am">12:55am</option>
								<option value="1:00am">1:00am</option>
								<option value="1:05am">1:05am</option>
								<option value="1:10am">1:10am</option>
								<option value="1:15am">1:15am</option>
								<option value="1:20am">1:20am</option>
								<option value="1:25am">1:25am</option>
								<option value="1:30am">1:30am</option>
								<option value="1:35am">1:35am</option>
								<option value="1:40am">1:40am</option>
								<option value="1:45am">1:45am</option>
								<option value="1:50am">1:50am</option>
								<option value="1:55am">1:55am</option>
								<option value="2:00am">2:00am</option>
								<option value="2:05am">2:05am</option>
								<option value="2:10am">2:10am</option>
								<option value="2:15am">2:15am</option>
								<option value="2:20am">2:20am</option>
								<option value="2:25am">2:25am</option>
								<option value="2:30am">2:30am</option>
								<option value="2:35am">2:35am</option>
								<option value="2:40am">2:40am</option>
								<option value="2:45am">2:45am</option>
								<option value="2:50am">2:50am</option>
								<option value="2:55am">2:55am</option>
								<option value="3:00am">3:00am</option>
								<option value="3:05am">3:05am</option>
								<option value="3:10am">3:10am</option>
								<option value="3:15am">3:15am</option>
								<option value="3:20am">3:20am</option>
								<option value="3:25am">3:25am</option>
								<option value="3:30am">3:30am</option>
								<option value="3:35am">3:35am</option>
								<option value="3:40am">3:40am</option>
								<option value="3:45am">3:45am</option>
								<option value="3:50am">3:50am</option>
								<option value="3:55am">3:55am</option>
								<option value="4:00am">4:00am</option>
								<option value="4:05am">4:05am</option>
								<option value="4:10am">4:10am</option>
								<option value="4:15am">4:15am</option>
								<option value="4:20am">4:20am</option>
								<option value="4:25am">4:25am</option>
								<option value="4:30am">4:30am</option>
								<option value="4:35am">4:35am</option>
								<option value="4:40am">4:40am</option>
								<option value="4:45am">4:45am</option>
								<option value="4:50am">4:50am</option>
								<option value="4:55am">4:55am</option>
								<option value="5:00am">5:00am</option>
								<option value="5:05am">5:05am</option>
								<option value="5:10am">5:10am</option>
								<option value="5:15am">5:15am</option>
								<option value="5:20am">5:20am</option>
								<option value="5:25am">5:25am</option>
								<option value="5:30am">5:30am</option>
								<option value="5:35am">5:35am</option>
								<option value="5:40am">5:40am</option>
								<option value="5:45am">5:45am</option>
								<option value="5:50am">5:50am</option>
								<option value="5:55am">5:55am</option>
								<option value="6:00am">6:00am</option>
								<option value="6:05am">6:05am</option>
								<option value="6:10am">6:10am</option>
								<option value="6:15am">6:15am</option>
								<option value="6:20am">6:20am</option>
								<option value="6:25am">6:25am</option>
								<option value="6:30am">6:30am</option>
								<option value="6:35am">6:35am</option>
								<option value="6:40am">6:40am</option>
								<option value="6:45am">6:45am</option>
								<option value="6:50am">6:50am</option>
								<option value="6:55am">6:55am</option>
								<option value="7:00am">7:00am</option>
								<option value="7:05am">7:05am</option>
								<option value="7:10am">7:10am</option>
								<option value="7:15am">7:15am</option>
								<option value="7:20am">7:20am</option>
								<option value="7:25am">7:25am</option>
								<option value="7:30am">7:30am</option>
								<option value="7:35am">7:35am</option>
								<option value="7:40am">7:40am</option>
								<option value="7:45am">7:45am</option>
								<option value="7:50am">7:50am</option>
								<option value="7:55am">7:55am</option>
								<option value="8:00am">8:00am</option>
								<option value="8:05am">8:05am</option>
								<option value="8:10am">8:10am</option>
								<option value="8:15am">8:15am</option>
								<option value="8:20am">8:20am</option>
								<option value="8:25am">8:25am</option>
								<option value="8:30am">8:30am</option>
								<option value="8:35am">8:35am</option>
								<option value="8:40am">8:40am</option>
								<option value="8:45am">8:45am</option>
								<option value="8:50am">8:50am</option>
								<option value="8:55am">8:55am</option>
								<option value="9:00am">9:00am</option>
								<option value="9:05am">9:05am</option>
								<option value="9:10am">9:10am</option>
								<option value="9:15am">9:15am</option>
								<option value="9:20am">9:20am</option>
								<option value="9:25am">9:25am</option>
								<option value="9:30am">9:30am</option>
								<option value="9:35am">9:35am</option>
								<option value="9:40am">9:40am</option>
								<option value="9:45am">9:45am</option>
								<option value="9:50am">9:50am</option>
								<option value="9:55am">9:55am</option>
								<option value="10:00am">10:00am</option>
								<option value="10:05am">10:05am</option>
								<option value="10:10am">10:10am</option>
								<option value="10:15am">10:15am</option>
								<option value="10:20am">10:20am</option>
								<option value="10:25am">10:25am</option>
								<option value="10:30am">10:30am</option>
								<option value="10:35am">10:35am</option>
								<option value="10:40am">10:40am</option>
								<option value="10:45am">10:45am</option>
								<option value="10:50am">10:50am</option>
								<option value="10:55am">10:55am</option>
								<option value="11:00am">11:00am</option>
								<option value="11:05am">11:05am</option>
								<option value="11:10am">11:10am</option>
								<option value="11:15am">11:15am</option>
								<option value="11:20am">11:20am</option>
								<option value="11:25am">11:25am</option>
								<option value="11:30am">11:30am</option>
								<option value="11:35am">11:35am</option>
								<option value="11:40am">11:40am</option>
								<option value="11:45am">11:45am</option>
								<option value="11:50am">11:50am</option>
								<option value="11:55am">11:55am</option>
								<option value="12:00pm">12:00pm</option>
								<option value="12:05pm">12:05pm</option>
								<option value="12:10pm">12:10pm</option>
								<option value="12:15pm">12:15pm</option>
								<option value="12:20pm">12:20pm</option>
								<option value="12:25pm">12:25pm</option>
								<option value="12:30pm">12:30pm</option>
								<option value="12:35pm">12:35pm</option>
								<option value="12:40pm">12:40pm</option>
								<option value="12:45pm">12:45pm</option>
								<option value="12:50pm">12:50pm</option>
								<option value="12:55pm">12:55pm</option>
								<option value="1:00pm">1:00pm</option>
								<option value="1:05pm">1:05pm</option>
								<option value="1:10pm">1:10pm</option>
								<option value="1:15pm">1:15pm</option>
								<option value="1:20pm">1:20pm</option>
								<option value="1:25pm">1:25pm</option>
								<option value="1:30pm">1:30pm</option>
								<option value="1:35pm">1:35pm</option>
								<option value="1:40pm">1:40pm</option>
								<option value="1:45pm">1:45pm</option>
								<option value="1:50pm">1:50pm</option>
								<option value="1:55pm">1:55pm</option>
								<option value="2:00pm">2:00pm</option>
								<option value="2:05pm">2:05pm</option>
								<option value="2:10pm">2:10pm</option>
								<option value="2:15pm">2:15pm</option>
								<option value="2:20pm">2:20pm</option>
								<option value="2:25pm">2:25pm</option>
								<option value="2:30pm">2:30pm</option>
								<option value="2:35pm">2:35pm</option>
								<option value="2:40pm">2:40pm</option>
								<option value="2:45pm">2:45pm</option>
								<option value="2:50pm">2:50pm</option>
								<option value="2:55pm">2:55pm</option>
								<option value="3:00pm">3:00pm</option>
								<option value="3:05pm">3:05pm</option>
								<option value="3:10pm">3:10pm</option>
								<option value="3:15pm">3:15pm</option>
								<option value="3:20pm">3:20pm</option>
								<option value="3:25pm">3:25pm</option>
								<option value="3:30pm">3:30pm</option>
								<option value="3:35pm">3:35pm</option>
								<option value="3:40pm">3:40pm</option>
								<option value="3:45pm">3:45pm</option>
								<option value="3:50pm">3:50pm</option>
								<option value="3:55pm">3:55pm</option>
								<option value="4:00pm">4:00pm</option>
								<option value="4:05pm">4:05pm</option>
								<option value="4:10pm">4:10pm</option>
								<option value="4:15pm">4:15pm</option>
								<option value="4:20pm">4:20pm</option>
								<option value="4:25pm">4:25pm</option>
								<option value="4:30pm">4:30pm</option>
								<option value="4:35pm">4:35pm</option>
								<option value="4:40pm">4:40pm</option>
								<option value="4:45pm">4:45pm</option>
								<option value="4:50pm">4:50pm</option>
								<option value="4:55pm">4:55pm</option>
								<option value="5:00pm">5:00pm</option>
								<option value="5:05pm">5:05pm</option>
								<option value="5:10pm">5:10pm</option>
								<option value="5:15pm">5:15pm</option>
								<option value="5:20pm">5:20pm</option>
								<option value="5:25pm">5:25pm</option>
								<option value="5:30pm">5:30pm</option>
								<option value="5:35pm">5:35pm</option>
								<option value="5:40pm">5:40pm</option>
								<option value="5:45pm">5:45pm</option>
								<option value="5:50pm">5:50pm</option>
								<option value="5:55pm">5:55pm</option>
								<option value="6:00pm">6:00pm</option>
								<option value="6:05pm">6:05pm</option>
								<option value="6:10pm">6:10pm</option>
								<option value="6:15pm">6:15pm</option>
								<option value="6:20pm">6:20pm</option>
								<option value="6:25pm">6:25pm</option>
								<option value="6:30pm">6:30pm</option>
								<option value="6:35pm">6:35pm</option>
								<option value="6:40pm">6:40pm</option>
								<option value="6:45pm">6:45pm</option>
								<option value="6:50pm">6:50pm</option>
								<option value="6:55pm">6:55pm</option>
								<option value="7:00pm">7:00pm</option>
								<option value="7:05pm">7:05pm</option>
								<option value="7:10pm">7:10pm</option>
								<option value="7:15pm">7:15pm</option>
								<option value="7:20pm">7:20pm</option>
								<option value="7:25pm">7:25pm</option>
								<option value="7:30pm">7:30pm</option>
								<option value="7:35pm">7:35pm</option>
								<option value="7:40pm">7:40pm</option>
								<option value="7:45pm">7:45pm</option>
								<option value="7:50pm">7:50pm</option>
								<option value="7:55pm">7:55pm</option>
								<option value="8:00pm">8:00pm</option>
								<option value="8:05pm">8:05pm</option>
								<option value="8:10pm">8:10pm</option>
								<option value="8:15pm">8:15pm</option>
								<option value="8:20pm">8:20pm</option>
								<option value="8:25pm">8:25pm</option>
								<option value="8:30pm">8:30pm</option>
								<option value="8:35pm">8:35pm</option>
								<option value="8:40pm">8:40pm</option>
								<option value="8:45pm">8:45pm</option>
								<option value="8:50pm">8:50pm</option>
								<option value="8:55pm">8:55pm</option>
								<option value="9:00pm">9:00pm</option>
								<option value="9:05pm">9:05pm</option>
								<option value="9:10pm">9:10pm</option>
								<option value="9:15pm">9:15pm</option>
								<option value="9:20pm">9:20pm</option>
								<option value="9:25pm">9:25pm</option>
								<option value="9:30pm">9:30pm</option>
								<option value="9:35pm">9:35pm</option>
								<option value="9:40pm">9:40pm</option>
								<option value="9:45pm">9:45pm</option>
								<option value="9:50pm">9:50pm</option>
								<option value="9:55pm">9:55pm</option>
								<option value="10:00pm">10:00pm</option>
								<option value="10:05pm">10:05pm</option>
								<option value="10:10pm">10:10pm</option>
								<option value="10:15pm">10:15pm</option>
								<option value="10:20pm">10:20pm</option>
								<option value="10:25pm">10:25pm</option>
								<option value="10:30pm">10:30pm</option>
								<option value="10:35pm">10:35pm</option>
								<option value="10:40pm">10:40pm</option>
								<option value="10:45pm">10:45pm</option>
								<option value="10:50pm">10:50pm</option>
								<option value="10:55pm">10:55pm</option>
								<option value="11:00pm">11:00pm</option>
								<option value="11:05pm">11:05pm</option>
								<option value="11:10pm">11:10pm</option>
								<option value="11:15pm">11:15pm</option>
								<option value="11:20pm">11:20pm</option>
								<option value="11:25pm">11:25pm</option>
								<option value="11:30pm">11:30pm</option>
								<option value="11:35pm">11:35pm</option>
								<option value="11:40pm">11:40pm</option>
								<option value="11:45pm">11:45pm</option>
								<option value="11:50pm">11:50pm</option>
								<option value="11:55pm">11:55pm</option>
							</select>
						</div>
						<div class="col-6 pl-0">
							<label class="text-uppercase font-weight-bold d-sm-none">Shift End</label>
							<select class="form-control edit-end-shift2" name="end_time[]" id="select-end-shift2" style="-moz-appearance: none; -webkit-appearance: none;">
								<option value="0" selected>Select time</option>
								<option value="12:00am">12:00am</option>
								<option value="12:05am">12:05am</option>
								<option value="12:10am">12:10am</option>
								<option value="12:15am">12:15am</option>
								<option value="12:20am">12:20am</option>
								<option value="12:25am">12:25am</option>
								<option value="12:30am">12:30am</option>
								<option value="12:35am">12:35am</option>
								<option value="12:40am">12:40am</option>
								<option value="12:45am">12:45am</option>
								<option value="12:50am">12:50am</option>
								<option value="12:55am">12:55am</option>
								<option value="1:00am">1:00am</option>
								<option value="1:05am">1:05am</option>
								<option value="1:10am">1:10am</option>
								<option value="1:15am">1:15am</option>
								<option value="1:20am">1:20am</option>
								<option value="1:25am">1:25am</option>
								<option value="1:30am">1:30am</option>
								<option value="1:35am">1:35am</option>
								<option value="1:40am">1:40am</option>
								<option value="1:45am">1:45am</option>
								<option value="1:50am">1:50am</option>
								<option value="1:55am">1:55am</option>
								<option value="2:00am">2:00am</option>
								<option value="2:05am">2:05am</option>
								<option value="2:10am">2:10am</option>
								<option value="2:15am">2:15am</option>
								<option value="2:20am">2:20am</option>
								<option value="2:25am">2:25am</option>
								<option value="2:30am">2:30am</option>
								<option value="2:35am">2:35am</option>
								<option value="2:40am">2:40am</option>
								<option value="2:45am">2:45am</option>
								<option value="2:50am">2:50am</option>
								<option value="2:55am">2:55am</option>
								<option value="3:00am">3:00am</option>
								<option value="3:05am">3:05am</option>
								<option value="3:10am">3:10am</option>
								<option value="3:15am">3:15am</option>
								<option value="3:20am">3:20am</option>
								<option value="3:25am">3:25am</option>
								<option value="3:30am">3:30am</option>
								<option value="3:35am">3:35am</option>
								<option value="3:40am">3:40am</option>
								<option value="3:45am">3:45am</option>
								<option value="3:50am">3:50am</option>
								<option value="3:55am">3:55am</option>
								<option value="4:00am">4:00am</option>
								<option value="4:05am">4:05am</option>
								<option value="4:10am">4:10am</option>
								<option value="4:15am">4:15am</option>
								<option value="4:20am">4:20am</option>
								<option value="4:25am">4:25am</option>
								<option value="4:30am">4:30am</option>
								<option value="4:35am">4:35am</option>
								<option value="4:40am">4:40am</option>
								<option value="4:45am">4:45am</option>
								<option value="4:50am">4:50am</option>
								<option value="4:55am">4:55am</option>
								<option value="5:00am">5:00am</option>
								<option value="5:05am">5:05am</option>
								<option value="5:10am">5:10am</option>
								<option value="5:15am">5:15am</option>
								<option value="5:20am">5:20am</option>
								<option value="5:25am">5:25am</option>
								<option value="5:30am">5:30am</option>
								<option value="5:35am">5:35am</option>
								<option value="5:40am">5:40am</option>
								<option value="5:45am">5:45am</option>
								<option value="5:50am">5:50am</option>
								<option value="5:55am">5:55am</option>
								<option value="6:00am">6:00am</option>
								<option value="6:05am">6:05am</option>
								<option value="6:10am">6:10am</option>
								<option value="6:15am">6:15am</option>
								<option value="6:20am">6:20am</option>
								<option value="6:25am">6:25am</option>
								<option value="6:30am">6:30am</option>
								<option value="6:35am">6:35am</option>
								<option value="6:40am">6:40am</option>
								<option value="6:45am">6:45am</option>
								<option value="6:50am">6:50am</option>
								<option value="6:55am">6:55am</option>
								<option value="7:00am">7:00am</option>
								<option value="7:05am">7:05am</option>
								<option value="7:10am">7:10am</option>
								<option value="7:15am">7:15am</option>
								<option value="7:20am">7:20am</option>
								<option value="7:25am">7:25am</option>
								<option value="7:30am">7:30am</option>
								<option value="7:35am">7:35am</option>
								<option value="7:40am">7:40am</option>
								<option value="7:45am">7:45am</option>
								<option value="7:50am">7:50am</option>
								<option value="7:55am">7:55am</option>
								<option value="8:00am">8:00am</option>
								<option value="8:05am">8:05am</option>
								<option value="8:10am">8:10am</option>
								<option value="8:15am">8:15am</option>
								<option value="8:20am">8:20am</option>
								<option value="8:25am">8:25am</option>
								<option value="8:30am">8:30am</option>
								<option value="8:35am">8:35am</option>
								<option value="8:40am">8:40am</option>
								<option value="8:45am">8:45am</option>
								<option value="8:50am">8:50am</option>
								<option value="8:55am">8:55am</option>
								<option value="9:00am">9:00am</option>
								<option value="9:05am">9:05am</option>
								<option value="9:10am">9:10am</option>
								<option value="9:15am">9:15am</option>
								<option value="9:20am">9:20am</option>
								<option value="9:25am">9:25am</option>
								<option value="9:30am">9:30am</option>
								<option value="9:35am">9:35am</option>
								<option value="9:40am">9:40am</option>
								<option value="9:45am">9:45am</option>
								<option value="9:50am">9:50am</option>
								<option value="9:55am">9:55am</option>
								<option value="10:00am">10:00am</option>
								<option value="10:05am">10:05am</option>
								<option value="10:10am">10:10am</option>
								<option value="10:15am">10:15am</option>
								<option value="10:20am">10:20am</option>
								<option value="10:25am">10:25am</option>
								<option value="10:30am">10:30am</option>
								<option value="10:35am">10:35am</option>
								<option value="10:40am">10:40am</option>
								<option value="10:45am">10:45am</option>
								<option value="10:50am">10:50am</option>
								<option value="10:55am">10:55am</option>
								<option value="11:00am">11:00am</option>
								<option value="11:05am">11:05am</option>
								<option value="11:10am">11:10am</option>
								<option value="11:15am">11:15am</option>
								<option value="11:20am">11:20am</option>
								<option value="11:25am">11:25am</option>
								<option value="11:30am">11:30am</option>
								<option value="11:35am">11:35am</option>
								<option value="11:40am">11:40am</option>
								<option value="11:45am">11:45am</option>
								<option value="11:50am">11:50am</option>
								<option value="11:55am">11:55am</option>
								<option value="12:00pm">12:00pm</option>
								<option value="12:05pm">12:05pm</option>
								<option value="12:10pm">12:10pm</option>
								<option value="12:15pm">12:15pm</option>
								<option value="12:20pm">12:20pm</option>
								<option value="12:25pm">12:25pm</option>
								<option value="12:30pm">12:30pm</option>
								<option value="12:35pm">12:35pm</option>
								<option value="12:40pm">12:40pm</option>
								<option value="12:45pm">12:45pm</option>
								<option value="12:50pm">12:50pm</option>
								<option value="12:55pm">12:55pm</option>
								<option value="1:00pm">1:00pm</option>
								<option value="1:05pm">1:05pm</option>
								<option value="1:10pm">1:10pm</option>
								<option value="1:15pm">1:15pm</option>
								<option value="1:20pm">1:20pm</option>
								<option value="1:25pm">1:25pm</option>
								<option value="1:30pm">1:30pm</option>
								<option value="1:35pm">1:35pm</option>
								<option value="1:40pm">1:40pm</option>
								<option value="1:45pm">1:45pm</option>
								<option value="1:50pm">1:50pm</option>
								<option value="1:55pm">1:55pm</option>
								<option value="2:00pm">2:00pm</option>
								<option value="2:05pm">2:05pm</option>
								<option value="2:10pm">2:10pm</option>
								<option value="2:15pm">2:15pm</option>
								<option value="2:20pm">2:20pm</option>
								<option value="2:25pm">2:25pm</option>
								<option value="2:30pm">2:30pm</option>
								<option value="2:35pm">2:35pm</option>
								<option value="2:40pm">2:40pm</option>
								<option value="2:45pm">2:45pm</option>
								<option value="2:50pm">2:50pm</option>
								<option value="2:55pm">2:55pm</option>
								<option value="3:00pm">3:00pm</option>
								<option value="3:05pm">3:05pm</option>
								<option value="3:10pm">3:10pm</option>
								<option value="3:15pm">3:15pm</option>
								<option value="3:20pm">3:20pm</option>
								<option value="3:25pm">3:25pm</option>
								<option value="3:30pm">3:30pm</option>
								<option value="3:35pm">3:35pm</option>
								<option value="3:40pm">3:40pm</option>
								<option value="3:45pm">3:45pm</option>
								<option value="3:50pm">3:50pm</option>
								<option value="3:55pm">3:55pm</option>
								<option value="4:00pm">4:00pm</option>
								<option value="4:05pm">4:05pm</option>
								<option value="4:10pm">4:10pm</option>
								<option value="4:15pm">4:15pm</option>
								<option value="4:20pm">4:20pm</option>
								<option value="4:25pm">4:25pm</option>
								<option value="4:30pm">4:30pm</option>
								<option value="4:35pm">4:35pm</option>
								<option value="4:40pm">4:40pm</option>
								<option value="4:45pm">4:45pm</option>
								<option value="4:50pm">4:50pm</option>
								<option value="4:55pm">4:55pm</option>
								<option value="5:00pm">5:00pm</option>
								<option value="5:05pm">5:05pm</option>
								<option value="5:10pm">5:10pm</option>
								<option value="5:15pm">5:15pm</option>
								<option value="5:20pm">5:20pm</option>
								<option value="5:25pm">5:25pm</option>
								<option value="5:30pm">5:30pm</option>
								<option value="5:35pm">5:35pm</option>
								<option value="5:40pm">5:40pm</option>
								<option value="5:45pm">5:45pm</option>
								<option value="5:50pm">5:50pm</option>
								<option value="5:55pm">5:55pm</option>
								<option value="6:00pm">6:00pm</option>
								<option value="6:05pm">6:05pm</option>
								<option value="6:10pm">6:10pm</option>
								<option value="6:15pm">6:15pm</option>
								<option value="6:20pm">6:20pm</option>
								<option value="6:25pm">6:25pm</option>
								<option value="6:30pm">6:30pm</option>
								<option value="6:35pm">6:35pm</option>
								<option value="6:40pm">6:40pm</option>
								<option value="6:45pm">6:45pm</option>
								<option value="6:50pm">6:50pm</option>
								<option value="6:55pm">6:55pm</option>
								<option value="7:00pm">7:00pm</option>
								<option value="7:05pm">7:05pm</option>
								<option value="7:10pm">7:10pm</option>
								<option value="7:15pm">7:15pm</option>
								<option value="7:20pm">7:20pm</option>
								<option value="7:25pm">7:25pm</option>
								<option value="7:30pm">7:30pm</option>
								<option value="7:35pm">7:35pm</option>
								<option value="7:40pm">7:40pm</option>
								<option value="7:45pm">7:45pm</option>
								<option value="7:50pm">7:50pm</option>
								<option value="7:55pm">7:55pm</option>
								<option value="8:00pm">8:00pm</option>
								<option value="8:05pm">8:05pm</option>
								<option value="8:10pm">8:10pm</option>
								<option value="8:15pm">8:15pm</option>
								<option value="8:20pm">8:20pm</option>
								<option value="8:25pm">8:25pm</option>
								<option value="8:30pm">8:30pm</option>
								<option value="8:35pm">8:35pm</option>
								<option value="8:40pm">8:40pm</option>
								<option value="8:45pm">8:45pm</option>
								<option value="8:50pm">8:50pm</option>
								<option value="8:55pm">8:55pm</option>
								<option value="9:00pm">9:00pm</option>
								<option value="9:05pm">9:05pm</option>
								<option value="9:10pm">9:10pm</option>
								<option value="9:15pm">9:15pm</option>
								<option value="9:20pm">9:20pm</option>
								<option value="9:25pm">9:25pm</option>
								<option value="9:30pm">9:30pm</option>
								<option value="9:35pm">9:35pm</option>
								<option value="9:40pm">9:40pm</option>
								<option value="9:45pm">9:45pm</option>
								<option value="9:50pm">9:50pm</option>
								<option value="9:55pm">9:55pm</option>
								<option value="10:00pm">10:00pm</option>
								<option value="10:05pm">10:05pm</option>
								<option value="10:10pm">10:10pm</option>
								<option value="10:15pm">10:15pm</option>
								<option value="10:20pm">10:20pm</option>
								<option value="10:25pm">10:25pm</option>
								<option value="10:30pm">10:30pm</option>
								<option value="10:35pm">10:35pm</option>
								<option value="10:40pm">10:40pm</option>
								<option value="10:45pm">10:45pm</option>
								<option value="10:50pm">10:50pm</option>
								<option value="10:55pm">10:55pm</option>
								<option value="11:00pm">11:00pm</option>
								<option value="11:05pm">11:05pm</option>
								<option value="11:10pm">11:10pm</option>
								<option value="11:15pm">11:15pm</option>
								<option value="11:20pm">11:20pm</option>
								<option value="11:25pm">11:25pm</option>
								<option value="11:30pm">11:30pm</option>
								<option value="11:35pm">11:35pm</option>
								<option value="11:40pm">11:40pm</option>
								<option value="11:45pm">11:45pm</option>
								<option value="11:50pm">11:50pm</option>
								<option value="11:55pm">11:55pm</option>
							</select>
							<span class="reset-addon">
								<a class="reset-second-shift" href="#"><i class="la la-close" id="la-close1"></i></a>
							</span>
						</div>
					</div>
					<div class="row break-hint text-center justify-content-center hidden">1 hour break time</div>
					<div class="row">
						<div class="col-12">
							<button type="button" class="btn btn-outline-secondary add-shift" id="add-shift1">Add another shift</button>
						</div>
					</div>
					<div class="row attached-fields m-0 mt-5">
						<div class="col-12 repeat-switch-column repeat_optin no-padding" id="repeat_optin1">
							<label for="schedule_repeats" class="text-uppercase font-weight-bold">Repeats</label>
							<div class="select-wrapper">
								<select class="form-control repeat-switch" id="schedule_repeats1" name="repeats">
									<option value="0">Don t repeat</option>
									<option value="1">Weekly</option>
								</select>
							</div>
						</div>
						<div class="col-6 pr-0 repeat-container no-padding" id="end_repeat1" style="display: none;">
							<label class="form-label text-uppercase font-weight-bold end_repeat_field">End repeat</label>
							<div class="form-group end-repeat-container end_repeat_field">
								<div class="select-wrapper">
									<select class="form-control end-repeat" id="schedule_end_repeat1" name="endrepeat">
										<option value="0">Ongoing</option>
										<option value="1">Specific date</option>
									</select>
								</div>
							</div>
							<div class="form-group position-relative date-end-field" id="specific_date_div1" style="display: none;">
								<label class="form-label text-uppercase font-weight-bold">End Date</label>
								<div class="form-group end-repeat-container">
									<input type="text" class="form-control" id="schedule_date_end1" name="spenddate" readonly="" placeholder="End date" />
								</div>
							</div>
						</div>
					</div>
				</div>
				{{ Form::close() }}
				<div class="modal-footer">
					<div class="modal fade p-0 show" id="deleteWorkModal" tabindex="-1" role="dialog" aria-labelledby="Label" style="display: none; padding-right: 15px;" aria-modal="true">
						<div class="modal-dialog " role="document">
							<div class="modal-content ">
								<div class="modal-header">
									<h5 class="modal-title font-weight-bold text-center">Repeating Shift</h5>
									<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
									</button>
								</div>
								<div class="modal-body">
									<div class="container">
										<div class="row">
											<p>You are deleting a shift that repeats weekly. Deleting upcoming shifts will overwrite Adam s ongoing Sunday schedule.</p>
										</div>
									</div>
								</div>
								<div class="modal-footer d-flex justify-content-between">
									<button class="btn btn-default js-future-button addid" data-date="" data-url="{{ route('removeHours') }}" data-id="" data-tpye="1" id="deletehours" >Delete upcoming shifts</button>
									<button class="btn js-single-button btn-danger addid" data-date="" data-url="{{ route('removeHours') }}" data-id="" data-tpye="2" id="deletehours" >Delete this shift only</button>
								</div>
							</div>
						</div>
					</div>
					<button type="button" class="mr-auto btn btn-danger" data-toggle="modal" data-target="#deleteWorkModal" >Delete</button>
					<button type="button" class="btn btn-outline-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-primary" id="edit_working_hours">Save changes</button>
				</div>
			</div>
		</div>
	</div>
	{{-- end --}}
	<!--end::Tabs-->
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class="container">
			<!--begin::Sales-->
			<!--begin::Row-->
			<div class="content-header mt-4">
				<div class="my-2">
					<div class="">
						<div class="form-group font-weight-bold m-0">
							{{--  <select class="form-control" id="select-location">
								@foreach ($location as $value) 
									<option class="loc" value="{{ $value->id }}" >{{ $value->location_name }}</option>
								@endforeach
							</select>  --}}
						</div>
					</div>
					<div class="all-staff">
						<div class="form-group font-weight-bold m-0">
							<select class="form-control" id="select-member">
								<option selected="" value="all">All Staff</option>
								@foreach ($staffMembers as $key => $value) 
									<option value="<?php echo $value->id ?>"><?php echo $value->first_name." ".$value->last_name ?></option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="date-right"> 
						<div class="calender-div">
							<div class="form-group mb-0">
								<div class="input-icon" >
									<input type="text" class="form-control kt_date" name="daterange" id="kt_datepicker_1" value="" readonly="readonly" placeholder="Select date"
									style="width:93%;">
									<span class=""><i class="la la-calendar-check-o"></i></span>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row" style="clear: both;">
					<div class="col-sm-12 col-lg-12 col-xxl-12">
						<!--begin::List Widget 3-->
						<div class="">
							<!--begin::Body-->
							<div class="pt-2">
								<!--begin::Item-->
								<div class="d-flex align-items-center p-2">
									<div class="table-responsive staff-schedule">
										<table class="table table-bordered" id="hoursdata" >
											<thead>
												<tr class="border-0">
													<th class="border-0"></th>
													@foreach ($currentWeekDate as $key =>  $day)
														@if(isset($day['is_closed']) == 1)
															<th class="p-2 m-2 border-0 closed-lable text-light">Closed<span>&nbsp;&nbsp;&nbsp;<i class="text-light fa fa-comment" data-toggle="tooltip" data-placement="top" title="closed"></i></span></th>
														@else
															<th class="border-0"></th>
														@endif	
													@endforeach		
												</tr>
												<tr>
													<th>Staff</th>
													@foreach ($currentWeekDate as $day) 
														<th>{{ $day['weekdate'] }}</th>
													@endforeach	
												</tr>
											</thead>
											<tbody>
												
												@foreach ($staffMembers as $memberKey => $member)
													@php
														$time1=array(); $time2=array();
													@endphp
													<tr>
														<td class="font-weight-bold" style="font-weight: 900 !important; min-width: 85px; display: inline-block; min-height: 58px;"><?php echo $member->first_name." ".$member->last_name ?><br/>
															<span class="font-weight-normal" id="totalhours{{ $member->id }}" ></span>
														</td>
														@foreach ($currentWeekDate as $key =>  $day)
															@php 
																$currday = date("N",strtotime($day['weekdate'])); 
																$workinghours = App\Models\StaffWorkingHours::where('staff_id',$member->id)->where('day',$currday)->first();
																if(isset($workinghours)){
																	$time = json_decode($workinghours['start_time'],true); $endtime = json_decode($workinghours['end_time'],true); 
																	$startdate = $workinghours['date']; $enddate = $workinghours['remove_date']; $removedate = $workinghours['remove_date']; $currweekdate = Carbon\Carbon::parse($day['weekdate'])->format('Y-m-d'); $diff_in_minutes1 = Carbon\Carbon::parse($time[0])->diffInMinutes(Carbon\Carbon::parse($endtime[0])); $diff_in_minutes2 = Carbon\Carbon::parse($time[1])->diffInMinutes(Carbon\Carbon::parse($endtime[1]));
																}
															@endphp

															@if(isset($day['is_closed']) == 1)
																<td class="closed" data-toggle="tooltip" data-placement="top" title="closed">
																	
																</td>
															@else
																<td>
																	@if(isset($workinghours) && $currweekdate >= $startdate)
																		
																		@if($removedate != 0 && $workinghours['remove_type'] == 2)
																			@if($removedate == $currweekdate)
																				<a href="#" class="shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $member->id }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																					<div class="time" style="padding:12%" title="Add shift time">&nbsp;
																					</div>
																				</a>
																			@else
																				<a href="#" class="edit_shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $workinghours['id'] }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																				<div class="time" title="Edit shift time">
																					{{ $time[0] }}  {{ $endtime[0] }} <br/> @if($time[1]==0 && $endtime[1]==0) @else {{ $time[1] }}  {{ $endtime[1] }}@endif
																				</div>
																				</a>
																				@php array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2); @endphp
																			@endif
																		@else
																			@if($removedate != 0 && $workinghours['remove_type'] == 1)
																				@if($currweekdate >= $removedate)
																					<a href="#" class="shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $member->id }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																						<div class="time" style="padding:12%" title="Add shift time">&nbsp;
																						</div>
																					</a>
																				@else
																					<a href="#" class="edit_shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $workinghours['id'] }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																					<div class="time" title="Edit shift time">
																						{{ $time[0] }}  {{ $endtime[0] }} <br/> @if($time[1]==0 && $endtime[1]==0) @else {{ $time[1] }}  {{ $endtime[1] }}@endif
																					</div>
																					</a>
																					@php array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2); @endphp
																				@endif
																			@else
																				@if($workinghours['repeats'] == 0)
																					@if($currweekdate == $startdate)
																						<a href="#" class="edit_shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $workinghours['id'] }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																						<div class="time" title="Edit shift time">
																							{{ $time[0] }}  {{ $endtime[0] }} <br/> @if($time[1]==0 && $endtime[1]==0) @else {{ $time[1] }}  {{ $endtime[1] }}@endif
																						</div>
																						</a>
																						@php array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2); @endphp
																					@else
																						@if($currweekdate > $startdate)
																							<a href="#" class="shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $member->id }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																								<div class="time" style="padding:12%" title="Add shift time">&nbsp;
																								</div>
																							</a>
																						@elseif($currweekdate < $startdate)
																							<a href="#" class="edit_shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $workinghours['id'] }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																								<div class="time" title="Edit shift time">
																									{{ $time[0] }}  {{ $endtime[0] }} <br/> @if($time[1]==0 && $endtime[1]==0) @else {{ $time[1] }}  {{ $endtime[1] }}@endif
																								</div>
																							</a>
																							@php array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2); @endphp
																						@endif
																					@endif
																				@endif
																				@if($workinghours['repeats'] == 1)
																					@if($workinghours['endrepeat']==0)
																						<a href="#" class="edit_shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $workinghours['id'] }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																							<div class="time" title="Edit shift time">
																								{{ $time[0] }}  {{ $endtime[0] }} <br/> @if($time[1]==0 && $endtime[1]==0) @else {{ $time[1] }}  {{ $endtime[1] }}@endif
																							</div>
																						</a>
																						@php array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2); @endphp
																					@endif
																					@if($workinghours['endrepeat']!=0 && $currweekdate > $workinghours['endrepeat'])
																						<a href="#" class="shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $member->id }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																							<div class="time" style="padding:12%" title="Add shift time">&nbsp;
																							</div>
																						</a>
																					@endif
																					@if($workinghours['endrepeat']!=0 && $currweekdate < $workinghours['endrepeat'])
																						<a href="#" class="edit_shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $workinghours['id'] }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																							<div class="time" title="Edit shift time">
																								{{ $time[0] }}  {{ $endtime[0] }} <br/> @if($time[1]==0 && $endtime[1]==0) @else {{ $time[1] }}  {{ $endtime[1] }}@endif
																							</div>
																						</a>
																						@php array_push($time1,$diff_in_minutes1);array_push($time2,$diff_in_minutes2); @endphp
																					@endif
																				@endif
																			@endif
																		@endif
																		
																	@else
																		<a href="#" class="shift_time" data-date="<?php echo date("l, d F Y",strtotime($day['weekdate'])) ?>" data-id="{{ $member->id }}" data-day="<?php echo date("N",strtotime($day['weekdate'])) ?>" data-name="<?php echo $member->first_name." ".$member->last_name ?>">
																			<div class="time" style="padding:12%" title="Add shift time">&nbsp;
																			</div>
																		</a>
																	@endif
																</td>
															@endif
														@endforeach
														@php
															$totalt1 = array_sum($time1); $totalt2 = array_sum($time2); $totalTime = $totalt1 + $totalt2; if(sprintf("%02d",floor($totalTime / 60))>0){$hor = sprintf("%02d",floor($totalTime / 60)).'h ';}else {$hor = '';}if(sprintf("%02d",str_pad(($totalTime % 60), 2, "0", STR_PAD_LEFT))>0){$min =sprintf("%02d",str_pad(($totalTime % 60), 2, "0", STR_PAD_LEFT)). "min";}else{$min ='';}
														@endphp
														<input type="hidden" id="timeTotal{{ $member->id }}" value="{{ $hor }}{{ $min }}">
													</tr>
												@endforeach
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
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('/assets/js/pages/crud/forms/widgets/bootstrap-daterangepicker.js')}}"></script>
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/strftime-min.js') }}"></script>

<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script type="text/javascript">
	
	$(document).on('change','#schedule_repeats',function(){
		var thisVal = $(this).val();
		if(thisVal == 1)
		{
			$('#repeat_optin').removeClass();
			$('#repeat_optin').addClass('col-6 repeat-switch-column repeat_optin no-padding');
			$('.end_repeat_field').show();
			$('#end_repeat').show();
		}
		else
		{
			$('#schedule_end_repeat').val(0).trigger('change');
			$('#repeat_optin').removeClass();
			$('#repeat_optin').addClass('col-12 repeat-switch-column repeat_optin no-padding')	
			$('#specific_date_div').hide();
			$('#end_repeat').hide();
		}

		
	});

	$(document).on('change','#schedule_repeats1',function(){
		var thisVal = $(this).val();
		if(thisVal == '1')
		{
			$('#repeat_optin1').removeClass();
			$('#repeat_optin1').addClass('col-6 repeat-switch-column repeat_optin no-padding');
			$('.specific_date_div1').show();
			$('#end_repeat1').show();
		}
		else
		{
			$('#schedule_end_repeat1').val(0).trigger('change');
			$('#repeat_optin1').removeClass();
			$('#repeat_optin1').addClass('col-12 repeat-switch-column repeat_optin no-padding')	
			$('#specific_date_div1').hide();
			$('#end_repeat1').hide();
		}

		
	});

	$(document).on('change','#schedule_end_repeat1',function(){
		var Val = $(this).val();
		if(Val == '1')
		{
			$('#specific_date_div1').show();
		}
		else
		{
			$('#specific_date_div1').hide();
		}
	});

	$(document).ready(function() {
		// minimum setup
		$('#schedule_date_end').datepicker({
			rtl: KTUtil.isRTL(),
			todayHighlight: true,
			autoclose: true,
			format: 'DD, dd/mm/yyyy',
			orientation: "top left",
			//templates: arrows
		});

		// minimum setup
		$('#schedule_date_end1').datepicker({
			rtl: KTUtil.isRTL(),
			todayHighlight: true,
			autoclose: true,
			format: 'DD, dd/mm/yyyy',
			orientation: "top left",
			//templates: arrows
		});
		
		var staff = '<?php echo json_encode($staffMembers);?>';
		var staffdata = $.parseJSON(staff);

		$(staffdata).each(function(i, val) {
			var time = $('#timeTotal'+val.id).val();
			if(time == '00h 00min'){
				$('#totalhours'+val.id).text('');
			} else {
				$('#totalhours'+val.id).text(time);
			}
		});
		
	});
	
	$(document).on('change','#schedule_end_repeat',function(){
		var thisVal = $(this).val();
		if(thisVal == 1)
		{
			$('.end_repeat_field').hide();
			$('#specific_date_div').show();
		}
	});

	$("#schedule_date_end").datepicker({
		/*startDate: new Date(),
		autoclose: true,*/
		beforeShowDay: function(date) {
	        return [date.getDay() == 5];
	    }
	});

	$(document).on('click','.shift_time', function(){
		var date = $(this).data('date');
		var id = $(this).data('id');
		var date = $(this).data('date');
		var day = $(this).data('day');
		var name = 'Add '+$(this).data('name');
		$('#username').text(name);
		$('#staff_id').val(id);
		$('#timedate').val(date);
		$('#timeday').val(day);
		$('#date').text(date);
		$('#shiftDateModalCenter').modal('show');
		
	});

	//get edit modal data
	$(document).on('click','.edit_shift_time', function(){
		var date = $(this).data('date');
		var id = $(this).data('id');
		var date = $(this).data('date');
		var day = $(this).data('day');
		var name = 'Edit '+$(this).data('name');
		$('#editdate').text(date);
		$('#editusername').text(name);
		$('#editshiftDateModalCenter').modal('show');
		$('.addid').data('id',id);
		$('.addid').data('date',date);
		if($('#second-shift1').is(":hidden")){
			$('#add-shift1').hide();
		}
		//
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		});
		$.ajax({
			type:'get',
           	url:"getStaffWorkingHours/"+id,
			success: function(response) {
				data = $.parseJSON(response.start_time);
				data1 = $.parseJSON(response.end_time);
				console.log(response);
				if(data[1].length > 0){
					$('#second-shift1').show();
				}
				if(response.repeats == 1){
					$('#end_repeat1').show();
				}
				if(response.endrepeat != 0){
					$('#specific_date_div1').show();
					$('#schedule_date_end1').val(response.endrepeat);
					$('#schedule_end_repeat1').val('1');

				} else {
					$('#schedule_end_repeat1').val(response.endrepeat);
				}
				
				$('#select-start-shift1').val(data[0]);
				$('#select-start-shift2').val(data[1]);
				$('#select-end-shift1').val(data1[0]);
				$('#select-end-shift2').val(data1[1]);
				$('#schedule_repeats1').val(response.repeats);
				$('#hours_id').val(response.id);
				
				$('#editusername').text(name);
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
		//
	});

	$(document).on('click','#add-shift', function(){
		$('#second-shift').fadeToggle();
	});

	$(document).on('click','#add-shift1', function(){
		$('#second-shift1').fadeToggle();
		$(this).hide();
	});

	$(document).on('click','#la-close', function(){
		$('#second-shift').hide();
	});

	$(document).on('click','#la-close1', function(){
		$('#add-shift1').show();
		$('#second-shift1').hide();
		$('#select-start-shift2').val('0');
		$('#select-end-shift2').val('0');
	});

	// add working hours
	$(document).on('click','#add_working_hours',function(){
		var data = $('#add_staff_time').serialize();
		var url = $('#add_staff_time').attr('action');

		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();

		today = yyyy + '-' + mm + '-' + dd;

		var start_shift1 = $('.start-shift1').val();
		var end_shift1 = $('.end-shift1').val();
		var start_shift2 = $('.start-shift2').val();
		var end_shift2 = $('.end-shift2').val();
					
		var start_shift1_strtotime = moment(start_shift1, 'HH:mmA').diff(moment().startOf('day'), 'seconds');
		var end_shift1_strtotime = moment(end_shift1, 'HH:mmA').diff(moment().startOf('day'), 'seconds');
		var start_shift2_strtotime = moment(start_shift2, 'HH:mmA').diff(moment().startOf('day'), 'seconds');
		var end_shift2_strtotime = moment(end_shift2, 'HH:mmA').diff(moment().startOf('day'), 'seconds');

		if(start_shift1_strtotime > end_shift1_strtotime) {
			responseMessages('error', 'Start time must be before end time');
			return false;
		}

		if($('#second-shift').is(':visible')) {

			if(start_shift2_strtotime > end_shift2_strtotime) {
				responseMessages('error', 'Start time must be before end time');
				return false;
			}

			if( (start_shift2_strtotime >= start_shift1_strtotime && start_shift2_strtotime<= end_shift1_strtotime) || (end_shift2_strtotime >= start_shift1_strtotime && end_shift2_strtotime <= end_shift1_strtotime) ) {
				responseMessages('error', 'Shifts are overlapping');
				return false;
			}
		}	
		//
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		});
		$.ajax({
			type:'POST',
           	url:"{{ route('addStaffWorkingHours') }}",
           	data: data,
			success: function(response) {
				$('#shiftDateModalCenter').modal('hide');
				toastr.success('Working Hours create successfully!');
				
				setTimeout( function() {
					location.reload();
				}, 1500);	
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
		//
	});
	//end

	// edit working hours
	$(document).on('click','#edit_working_hours',function(){
		var data = $('#edit_staff_time').serialize();
		var url = $('#edit_staff_time').attr('action');
		var id = $('#edit_staff_time').find('#hours_id').val();

		var today = new Date();
		var dd = String(today.getDate()).padStart(2, '0');
		var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
		var yyyy = today.getFullYear();

		today = yyyy + '-' + mm + '-' + dd;

		var start_shift1 = $('.edit-start-shift1').val();
		var end_shift1 = $('.edit-end-shift1').val();
		var start_shift2 = $('.edit-start-shift2').val();
		var end_shift2 = $('.edit-end-shift2').val();

		var start_shift1_strtotime = moment(start_shift1, 'HH:mmA').diff(moment().startOf('day'), 'seconds');
		var end_shift1_strtotime = moment(end_shift1, 'HH:mmA').diff(moment().startOf('day'), 'seconds');
		var start_shift2_strtotime = moment(start_shift2, 'HH:mmA').diff(moment().startOf('day'), 'seconds');
		var end_shift2_strtotime = moment(end_shift2, 'HH:mmA').diff(moment().startOf('day'), 'seconds');
		
		if(start_shift1_strtotime > end_shift1_strtotime) {
			responseMessages('error', 'Start time must be before end time');
			return false;
		}

		if($('#second-shift1').is(':visible')) {

			if(start_shift2_strtotime > end_shift2_strtotime) {
				responseMessages('error', 'Start time must be before end time');
				return false;
			}

			if( (start_shift2_strtotime >= start_shift1_strtotime && start_shift2_strtotime<= end_shift1_strtotime) || (end_shift2_strtotime >= start_shift1_strtotime && end_shift2_strtotime <= end_shift1_strtotime) ) {
				responseMessages('error', 'Shifts are overlapping');
				return false;
			}
		}
		//
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		});
		$.ajax({
			type:'POST',
           	url:"{{ route('editStaffWorkingHours') }}",
           	data: data,
			success: function(response) {
				if(response.status==true){
					$('.edit_shift_time[data-id='+id+']').find('.time').html(start_shift1 + ' ' + end_shift1 + '<br>' + start_shift2 + ' ' + end_shift2);
					$('#editshiftDateModalCenter').modal('hide');
					toastr.success('Working Hours Update successfully!');
				}
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
		//
	});
	//end

	// remove working hours
	$(document).on('click','#deletehours',function(){
		
		var type = $(this).data('tpye');
		var url = $(this).data('url');
		var id = $(this).data('id');
		var date = $(this).data('date');
		var endrepeat = $('#schedule_end_repeat1').val();
		var repeats = $('#schedule_repeats1').val();
		// 
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		});
		$.ajax({
			type:'POST',
           	url:url,
           	data: {type:type,id:id,date:date,endrepeat:endrepeat,repeats:repeats},
			success: function(response) {
				console.log(response);
				if(response.status==true){
					$('#editshiftDateModalCenter').modal('hide');
					$('#deleteWorkModal').modal('hide');
					toastr.success('Working Hours Remove successfully!');
					window.location.reload();
				}
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
		//
	});
	//end

	// load data by location
	$(document).on('change','#select-location',function(){
		
		var id = $(this).children("option:selected").val();
		//
		$.ajax({
			type: 'get',
			url: "getStaffByLoc/"+id,
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
			data: {id:$(this).children("option:selected").val()},
			dataType: 'json',
			processData: false,
			contentType: false,
			success: function(response) {
				
				$("#hoursdata").empty();
				$("#hoursdata").append(response.data);

				var len = 0;
				if (response.staff != null) {
					len = response.staff.length;
				}

				$(response.staff).each(function(i, val) {
					var time = $('#timeTotal'+val.id).val();
					if(time == '00h 00min'){
						$('#totalhours'+val.id).text('');
					} else {
						$('#totalhours'+val.id).text(time);
					}
				});

				if (len>0) {
					$("#select-member").empty();
					for (var i = 0; i<len; i++) {
						var id = response.staff[i].id;
						var fname = response.staff[i].first_name;
						var lname = response.staff[i].last_name;

						var option = "<option value='"+id+"'>"+fname+' '+lname+"</option>"; 

						$("#select-member").append(option);
					}
				}

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
		//
		
	});

	// load data by staff
	$(document).on('change','#select-member',function(){
		var id = $(this).children("option:selected").val();
		var date = $("#kt_datepicker_1").val();

		//
		$.ajaxSetup({
			headers: {
				'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			},
		});
		$.ajax({
			type:'POST',
			url:"{{ route('getHoursByStaff') }}",
			data:{id:id,date:date,},
			success: function(response) {
				$("#hoursdata").empty();
				$("#hoursdata").append(response.data);
				$(response.staff).each(function(i, val) {
					var time = $('#timeTotal'+val.id).val();
					if(time == '00h 00min'){
						$('#totalhours'+val.id).text('');
					} else {
						$('#totalhours'+val.id).text(time);
					}
				});
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
		//
	});

	// get staff by date
	$(document).ready(function() {
		
		$('#kt_datepicker_1').datepicker();
		$("#kt_datepicker_1").datepicker({
			format: 'dd/mm/yy'
		}).datepicker('setDate', 'today');
	
		$("#kt_datepicker_1").on("change",function(){
			var date = $(this).val();
			var id = $('#select-member').children("option:selected").val();
			//
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
			});
			$.ajax({
				type:'POST',
				url:"{{ route('getDateByStaff') }}",
				data:{id:id,date:date,},
				success: function(response) {
					$("#hoursdata").empty();
					$("#hoursdata").append(response.data);

					$(response.staff).each(function(i, val) {
						var time = $('#timeTotal'+val.id).val();

						if(time == '00h 00min'){
							$('#totalhours'+val.id).text('');
						} else {
							$('#totalhours'+val.id).text(time);
						}
					});
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
			//
		});
	});
	
</script>
@endsection