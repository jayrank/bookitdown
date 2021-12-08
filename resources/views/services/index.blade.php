{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
	#clientList_filter{
		display:none;
	}
	.ad{
		float: left;
	}
	.remove_pricing_opt{
		float: right;
		top: -5px;
    	position: relative;
	}
	.btn-lg {
	    font-size: 16px;
	    padding: 16px 16px;
	    font-weight: 600;
	    min-width: 160px;
	    min-height: 48px;
	    padding: 0 !important;
	}
	.pl-4, .px-4 {
	    padding-left: 1.5rem!important;
	}
	.pr-4, .px-4 {
	    padding-right: 1.5rem!important;
	}
	.pb-2, .py-2 {
	    padding-bottom: .5rem!important;
	}

	.pt-2, .py-2 {
	    padding-top: .5rem!important;
	}
	.open-service {
		cursor: pointer;
	}
	.pricing_row {
	    background-color: #F2F2F7;
	    padding: 20px 10px;
	    border-radius: 5px;
	    margin-top: 2%;
	}
	.pricing_row:first-child{
		margin-top: 0;
	}
	.advacned_pricing_opt {
	    /*position: relative;*/
	    /*left: 03%;*/
	}
	.modal-backdrop {
	  display: none;
	}

	.modal {
	  background: rgba(0, 0, 0, 0.5);
	}
</style>
@endsection
@php 
$duration_array = array("5" => "5 Min", "10" => "10 Min", "15" => "15 Min", "20" => "20 Min", "25" => "25 Min", "30" => "30 Min", "35" => "35 Min", "40" => "40 Min", "45" => "45 Min", "50" => "50 Min", "55" => "55 Min", "60" => "1h", "65" => "1h 5min", "70" => "1h 10min", "75" => "1h 15min", "80" => "1h 20min", "85" => "1h 25min", "90" => "1h 30min", "95" => "1h 35min", "100" => "1h 40min", "105" => "1h 45min", "110" => "1h 50min", "115" => "1h 55min", "120" => "2h");
@endphp

@section('content')

	<!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid bg-content" id="kt_content">
		<!--begin::Tabs-->
		<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
			<div
				class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
				<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
					role="tablist">
					<li class="nav-item">
						<a class="nav-link active" href="{{ route('service') }}">Services menu</a>
					</li>
					@if (Auth::user()->can('paid_plans'))
						<!-- <li class="nav-item">
							<a class="nav-link" href="{{ route('plans') }}">Paid plans</a>
						</li> -->
					@endif	
				</ul>
			</div>
		</div>
		<!--end::Tabs-->
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<div class="content-header" style="margin-top:18px;">
					<div class="align-items-center justify-content-between d-flex">
						<div class="">
							<div class="btn-group btn-group-lg bg-white shadow-sm rounded" role="group"
								aria-label="Large button group">
								<button onclick="collapsAll()" type="button"
									class="btn btn-outline-secondary"><i
										class="far fa-list-alt p-0 m-0"></i> </button>
								<button onclick="collapsRemoveAll()" type="button"
									class="btn btn-outline-secondary"><i
										class="fas fa-bars p-0 m-0"></i></button>
							</div>
						</div>
						<div class="action-btn-div">
							<div class="dropdown dropdown-inline mr-2">
								<button type="button"
									class="btn btn-white font-weight-bolder dropdown-toggle"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:105px;padding: 10px;">
									<span class="svg-icon svg-icon-md">
										<!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
										<svg xmlns="http://www.w3.org/2000/svg">
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
								<div class="dropdown-menu dropdown-menu-xs">
									<!--begin::Navigation-->
									<ul class="navi flex-column navi-hover py-2">
										<li class="navi-item">
											<a href="{{route('serviceinfoexcel')}}" class="navi-link">
												<span class="navi-icon">
													<i class="la la-file-excel-o"></i>
												</span>
												<span class="navi-text">Excel</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="{{ route('serviceinfocsv') }}" class="navi-link">
												<span class="navi-icon">
													<i class="la la-file-text-o"></i>
												</span>
												<span class="navi-text">CSV</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="{{ route('serviceinfopdf') }}" class="navi-link">
												<span class="navi-icon">
													<i class="la la-file-pdf-o"></i>
												</span>
												<span class="navi-text">PDF</span>
											</a>
										</li>
									</ul>
									<!--end::Navigation-->
								</div>
								<!--end::Dropdown Menu-->
							</div>
							<div class="dropdown dropdown-inline mr-2">
								<button type="button"
									class="btn btn-primary font-weight-bolder p-3"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									</span>Add New</button>
								<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
									<ul class="navi flex-column navi-hover py-2">
										<li class="navi-item">
											<a data-toggle="modal" data-target="#addNewSingleServiceModal"
												class="cursor-pointer navi-link">
												<span class="navi-text">New Service</span>
											</a>
										</li>
										<li class="navi-item">
											<a data-toggle="modal" data-target="#categoryModal"
												class="cursor-pointer navi-link">
												<span class="navi-text">New Category</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="services">
					<ul id="sortable" class="catsortable">
						@foreach($category as $catvalue)
						<li class="service-category my-4" data-id="{{ $catvalue->id }}">
							<i class="fa fa-bars px-2 ml-1"></i> {{ $catvalue->category_title }}
							<div class="dropdown dropdown-inline">
								<a href="#" class="btn btn-clean btn-hover-primary btn-sm btn-icon"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<i class="ki ki-bold-more-hor"></i>
								</a>
								<div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
									<ul class="navi navi-hover">
										<li class="navi-item">
											<a data-toggle="modal" data-target="#addNewSingleServiceModal"
												class="cursor-pointer navi-link">
												<span class="navi-text">Add New Service</span>
											</a>
										</li>
										<li class="navi-item">
											<a data-toggle="modal" data-id="{{ $catvalue->id }}" data-url="{{ route('getcat',$catvalue->id) }}" id="editcat" data-target="#editcategoryModal" class="cursor-pointer navi-link">
												<span class="navi-text">Edit Category</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link deleteCategory" data-id="{{ $catvalue->id }}">
												<span class="navi-text text-danger">Delete
													Category</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
							@php 
							$service = App\Models\Services::where('is_deleted',0)->where('user_id', $AdminId)->where('service_category',$catvalue->id)->with('cat','servicePrice')->orderBy('order_id','ASC')->get(); 
							// dd($service);
							@endphp
							<ul id="Sersortable" class="connected-sortable sortable p-1 collapse show multi-collapse my-2">
								@foreach($service as $value)
									<li class="sortindex" data-id="{{ $value->id }}" style="border-left: 4px solid {{ $catvalue->appointment_color }};">
										<div class="row" style="width: 100%;">
											<div class="col-md-4 text-left">
												<span class="open-service" data-href="{{ route('getservice',$value->id) }}" ><i class="fa fa-bars px-2 my-2 mr-2"></i>{{ $value->service_name }}</span>
											</div>
											<div class="col-md-4 text-center">
												<span class="text-muted">
													@foreach($value->servicePrice as $key => $price) 
														<div>
															@php 
																if($price->duration <= 0) {echo '00h 00min';}
																else {  
																	if(sprintf("%02d",floor($price->duration / 60)) > 0){
																		echo sprintf("%02d",floor($price->duration / 60)).'h ';
																	} 
																	if(sprintf("%02d",str_pad(($price->duration % 60), 2, "0", STR_PAD_LEFT)) > 0){
																		echo sprintf("%02d",str_pad(($price->duration % 60), 2, "0", STR_PAD_LEFT)). "min";
																	}
																	
																}
															@endphp
														</div>
												  @endforeach</span>
											</div>
											<div class="col-md-4 text-right">
												<span>
													@foreach($value->servicePrice as $key => $price)
														<div class="row">
															@if($price->price_type != 'free')
																@if($price->price > $price->lowest_price && $price->lowest_price > 0 )
																	<div class="col-md-6 text-muted">
																		<s>${{ $price->price }}</s>
																	</div>
																	<div class="col-md-6">
																		{{ (($price->price_type == 'from') || ($price->is_staff_price == 1)) ? 'From' : '' }} ${{ $price->lowest_price }}{{-- @if($key == 0) - @endif--}}
																	</div>
																@else
																	<div class="col-md-12"> 
																		{{ (($price->price_type == 'from') || ($price->is_staff_price == 1)) ? 'From' : '' }} ${{ $price->price }}
																	</div>
																@endif
															@else
																<div class="col-md-12">
																	Free
																</div>
															@endif
														</div>
													@endforeach
												</span>
											</div>
										</div>
									</li>
								@endforeach
							</ul>
						</li>
						@endforeach
					</ul>
					<!--end::Body-->
				</div>
			</div>
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->

	<!-- Category Modal -->
	<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title m-auto font-weight-bolder" id="categoryModalLabel">New Category</h5>
					<p class="cursor-pointer m-0 " data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i></p>
				</div>
				<div class="modal-body">
					<form id="cat" name="cat" >
						<div class="form-group">
							<label class="font-weight-bolder">Category Name</label>
							<input type="text" class="form-control" name="category_title" placeholder="e.g Hair Services">
						</div>
						<div class="form-group">
							<label class="font-weight-bolder">Appointment color</label>

							<div class="">
							<select class="form-control" name="appointment_color" >
								<option value="pink" >pink</option>
								<option value="purple" >purple</option>
								<option value="indigo" >indigo</option>
								<option value="blue" >blue</option>
								<option value="cyan" >cyan</option>
								<option value="teal" >teal</option>
								<option value="green" >green</option>
								<option value="lime" >lime</option>
								<option value="yellow" >yellow</option>
								<option value="orange" >orange</option>
							</select>
								<!-- <button class="dropdown-toggle form-control text-left" type="button" data-toggle="dropdown"
									aria-haspopup="true" aria-expanded="false"></button> -->
								<div class="dropdown-menu px-3" style="">
									<div class="radio-inline flex-wrap appoinment-radio">
										<!-- <label class="radio radio-accent pink">
											<input type="radio" checked="" name="appointment_color" value="pink" name="color"> <span></span> </label>
										<label class="radio radio-accent purple">
											<input type="radio" name="appointment_color" value="purple" name="color"> <span></span> </label>
										<label class="radio radio-accent indigo">
											<input type="radio" name="appointment_color" value="indigo" name="color"> <span></span> </label>
										<label class="radio radio-accent blue">
											<input type="radio" name="appointment_color" value="blue" name="color"> <span></span> </label>
										<label class="radio radio-accent cyan">
											<input type="radio" name="appointment_color" value="cyan" name="color"> <span></span> </label>
										<label class="radio radio-accent teal">
											<input type="radio" name="appointment_color" value="teal" name="color"> <span></span> </label>
										<label class="radio radio-accent green">
											<input type="radio" name="appointment_color" value="green" name="color"> <span></span> </label>
										<label class="radio radio-accent lime">
											<input type="radio" name="appointment_color" value="lime" name="color"> <span></span> </label>
										<label class="radio radio-accent yellow">
											<input type="radio" name="appointment_color" value="yellow" name="color"> <span></span> </label>
										<label class="radio radio-accent orange">
											<input type="radio" name="appointment_color" value="orange" name="color"> <span></span> </label> -->
									</div>
								</div>
							</div>
							<span class="form-text text-muted px-2">
								See your Calendar Settings page under Setup to set how colors are displayed on the calendar
							</span>
						</div>
						<div class="form-group">
							<label class="font-weight-bolder">Category description</label>
							<textarea class="form-control" name="category_description" placeholder="Add private notes viewable in staff settings only (optional)" id="paidPlanDesc" rows="5"></textarea>
						</div>
					</form>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<button type="button" class="btn btn-primary" id="save">Save</button>
				</div>
			</div>
		</div>
	</div>
	<!--end::category-->

	<!-- Edit Category Modal -->
	<div class="modal fade" id="editcategoryModal" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title m-auto font-weight-bolder" id="categoryModalLabel">Edit Category</h5>
					<p class="cursor-pointer m-0 " data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i></p>
				</div>
				<div class="modal-body">
					<form id="saveeditcat" name="saveeditcat" >
						<input type="hidden" class="form-control" id="catid" name="catid" >

						<div class="form-group">
							<label class="font-weight-bolder">Category Name</label>
							<input type="text" class="form-control" id="category_title" name="category_title" placeholder="e.g Hair Services">
						</div>
						<div class="form-group">
							<label class="font-weight-bolder">Appointment color</label>

							<div class="">
								<select class="form-control" name="appointment_color" id="appointment_color">
									<option value="pink" >pink</option>
									<option value="purple" >purple</option>
									<option value="indigo" >indigo</option>
									<option value="blue" >blue</option>
									<option value="cyan" >cyan</option>
									<option value="teal" >teal</option>
									<option value="green" >green</option>
									<option value="lime" >lime</option>
									<option value="yellow" >yellow</option>
									<option value="orange" >orange</option>
								</select>
								<div class="dropdown-menu px-3" style="">
									<div class="radio-inline flex-wrap appoinment-radio">
									</div>
								</div>
							</div>
							<span class="form-text text-muted px-2">
								See your Calendar Settings page under Setup to set how colors are displayed on the calendar
							</span>
						</div>
						<div class="form-group">
							<label class="font-weight-bolder">Category description</label>
							<textarea class="form-control" id="category_description" name="category_description"
								placeholder="Add private notes viewable in staff settings only (optional)" id="paidPlanDesc"
								rows="5"></textarea>
						</div>
					</form>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<!-- <button type="button" class="btn btn-danger" id="deletecat" data-id="">Delete</button> -->
					<button type="button" class="btn btn-primary" id="editsave">Save</button>
				</div>
			</div>
		</div>
	</div>
	<!--end::Edit category-->

	 <!-- Service Modal -->
	<div class="modal fade p-0" id="addNewServiceModal" tabindex="-1" role="dialog" aria-labelledby="addNewServiceModalLabel" aria-hidden="true">
		<div class="modal-dialog full-width-modal" role="document">
			<div class="modal-content bg-content">
				<div class="border-0 modal-header">
					<p class="cursor-pointer text-right" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i></p>
				</div>
				<div class="modal-body text-center">
					<div class="modal-tab">
						<h1 class="font-weight-bolder">Choose a service type</h1>
						<div class="container">
							<ul class="list-group">
								<a href="{{route('addService')}}" data-toggle="modal" data-target="#addNewSingleServiceModal" id="showmodel">
									<li class="bg-hover-primary-o-1 p-6 my-4 cursor-pointer shadow-sm d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
										<div class="d-flex align-items-center">
											<div class="icon-svg-lg">
												<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
													<g transform="translate(-354.000000, -224.000000) translate(330.000000, 200.000000) translate(0.000000, -0.000000) translate(24.000000, 24.000000)"
														fill="none" fill-rule="evenodd">
														<circle fill="#EBF7FE" cx="28" cy="28" r="28"></circle>
														<g transform="translate(14.000000, 17.000000)">
															<path fill="#FFDB4E" d="M0 0h14v22H0z"></path>
															<path
																d="M12 3c0 1.1046.8954 2 2 2s2-.8954 2-2h7c1.1046 0 2 .8954 2 2v12c0 1.1046-.8954 2-2 2H5c-1.1046 0-2-.8954-2-2V5c0-1.1046.8954-2 2-2h7z"
																stroke="#101928" stroke-width="2"></path>
															<rect fill="#101928" x="7" y="9" width="13" height="2"
																rx="1">
															</rect>
															<rect fill="#101928" x="7" y="12" width="9" height="2"
																rx="1">
															</rect>
														</g>
													</g>
												</svg>
											</div>
											<div class="ml-4 name text-left">
												<h3>Single Service</h3>
												<p class="m-0 text-0muted">Services which can be booked individually
												</p>
											</div>
										</div>
										<i class="fa fa-chevron-right text-dark"></i>
									</li>
								</a>
								{{-- <a href="add_package_service.html">
									<li
										class="p-6 my-4 bg-hover-primary-o-1 cursor-pointer d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
										<div class="d-flex align-items-center">
											<div class="icon-svg-lg">
												<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
													<g transform="translate(-354.000000, -352.000000) translate(330.000000, 200.000000) translate(0.000000, 128.000000) translate(24.000000, 24.000000)"
														fill="none" fill-rule="evenodd">
														<circle fill="#EBF7FE" cx="28" cy="28" r="28"></circle>
														<g transform="translate(14.000000, 17.000000)">
															<path fill="#FFDB4E" d="M0 4h14v20H0z"></path>
															<path
																d="M11 7c0 1.1046.8954 2 2 2s2-.8954 2-2h6c1.1046 0 2 .8954 2 2v10c0 1.1046-.8954 2-2 2H5c-1.1046 0-2-.8954-2-2V9c0-1.1046.8954-2 2-2h6z"
																stroke="#101928" stroke-width="2"></path>
															<rect fill="#101928" x="7" y="12" width="12" height="2"
																rx="1">
															</rect>
															<rect fill="#101928" x="7" y="15" width="7" height="2"
																rx="1">
															</rect>
															<path
																d="M7.9989 4l.0311-.1607c.0874-.4953.2441-1.2367.4188-2.2273C8.6023.7417 9.4321.1607 10.3023.314l16.757 3.7784c.8702.1534 1.4513.9833 1.2978 1.8535l-1.806 10.242c-.1534.8703-.5908 1.779-1.461 1.6255"
																stroke="#101928" stroke-width="2"></path>
														</g>
													</g>
												</svg>
											</div>
											<div class="ml-4 name text-left">
												<h3>Package</h3>
												<p class="m-0 text-0muted">Multiple services booked together in one
													appointment</p>
											</div>
										</div>
										<i class="fa fa-chevron-right text-dark"></i>
									</li>
								</a> --}}
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--end::Service model-->

	<!--begin::Add Single Service Modal --><!-- fullscreen-modal fscreenModal-->
	<div class="modal fade fscreenModal p-0" id="addNewSingleServiceModal" tabindex="-1" role="dialog" aria-labelledby="addNewSingleServiceModalLabel" aria-hidden="true" style="background: #F2F2F7;">
		<form class="form" id="add_service_frm" name="add_service_frm" action="{{ route('ajaxAdd') }}" >
			@csrf
	        <div class="my-custom-header text-dark"> 
	            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
		            <a type="button" class="close" data-dismiss="modal" aria-label="Close" style="display: flex; justify-content: flex-start; align-items: center;top:unset;left: 0;opacity: 1;font-size: 1.75rem;">
		                <span aria-hidden="true" class="la la-times"></span>
		            </a>
		            <div style="flex-grow: 1;"><h2 class="font-weight-bolder title-hide">Add New Single Service</h2></div>
		            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
		                <button type="submit" class="btn btn-primary px-4 py-2" id="service_submit" name="submitButton">Save</button>
		            </div>
		        </div>
	        </div> 
            <h1 class="font-weight-bolder mb-5 text-center text-dark hide-onscroll" style="flex-grow: 1;">Add New Single Service</h1>  
            <div class="modal-content" style="background: #F2F2F7;">  
                <div class="my-custom-body">
					<div class="container">
						<div class="row justify-content-center"> 
							<div class="col-12 col-md-10 p-2 pr-4">
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Basic info</h4>
										<p class="m-0">Add a service name and choose the treatment type.</p>
									</div>
									<div class="card-body w-100">
										<div class="form-group mr-2 w-100">
											<label class="font-weight-bolder">Service name</label>
											<input type="text" class="form-control" name="service_name" id="service_name" autocomplete="off" placeholder="Service name"/>
										</div>
										<div class="form-group mr-2 w-100">
											<label class="font-weight-bolder">Treatment name</label>
											<input type="text" class="form-control" name="treatment_type" id="treatment_type" autocomplete="off" placeholder="Treatment name"/>
											
										</div>
										<div class="form-group" style="position: relative;">
											<label class="font-weight-bolder">Service category</label>
											<select class="select optional form-control" name="service_category" id="service_category">
												<option value="">Select Service Category</option>
												@foreach($category as $val)
													<option value="{{ $val->id }}">{{ $val->category_title }}</option>
												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label class="font-weight-bolder">
												Service description
											</label>
											<textarea class="form-control" placeholder="Add short description" name="service_description"
												id="paidPlanDesc" rows="6"></textarea>
										</div>
										<div class="form-group mr-2 w-100">
											<label class="font-weight-bolder">Service available for</label>
											<select class="form-control" name="available_for" id="available_for">
												<option value="0">Everyone</option>
												<option value="1">Females Only</option>
												<option value="2">Males Only</option>
											</select>
										</div>
									</div>
								</div>
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Online booking</h4>
										<p class="m-0">Enable online bookings, choose who the service is available for and
											add a short description.</p>
									</div>
									<div class="card-body w-70">
										<div class="form-group">
											<div class="mt-4 switch switch-icon switch-success" style="line-height: 33px;">
												<label class="d-flex align-item-center font-weight-bolder">
													<input type="checkbox" checked="checked" name="is_online_booking" id="is_online_booking">
													<span></span>&nbsp;&nbsp;&nbsp;Enable Online bookings</i>
												</label>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Location</h4>
										<p class="m-0">Choose the location where the service will take place.</p>
									</div>
									<div class="card-body">
										<div class="multicheckbox">
											<ul id="treeview-location">
												<li>
													<label for="locations" class="checkbox my-4">
														<input type="checkbox" name="locations" id="locations">
														<span></span>
														Select All
													</label>
													<ul class="d-flex flex-wrap p-0">
														@foreach($location as $locationData)
															<li class="service-member">
																<label for="location_{{ $locationData['id'] }}" class="checkbox">
																	<input type="checkbox" name="location_id[]" id="location_{{ $locationData['id'] }}" value="{{ $locationData['id'] }}">
																	<span></span>
																	<div class="custom-avtar">{{ substr($locationData['location_name'],0,1) }}</div>
																	{{ $locationData['location_name'] }}
																</label>
															</li>
														@endforeach
													</ul>
												</li>
											</ul>
										</div>
									</div>
								</div>
								
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Staff</h4>
										<p class="m-0">Calculate staff commission when the service is sold.</p>
									</div>
									<div class="card-body">
										<div class="multicheckbox">
											<ul id="treeview-staff">
												<li>
													<label for="all" class="checkbox my-4">
														<input type="checkbox" name="all" id="all">
														<span></span>
														Select All
													</label>
													<ul class="d-flex flex-wrap p-0">
														@foreach($staff as $val)
															@php $staffname = App\Models\User::where('id',$val->staff_user_id)->first(); @endphp
															<li class="service-member">
																<label for="staff_{{ $val->id }}" class="checkbox">
																	<input type="checkbox" name="staff_id[]" id="staff_{{ $val->id }}" value="{{ $val->id }}">
																	<span></span>
																	<div class="custom-avtar">{{ substr($staffname->first_name,0,1)." ".substr($staffname->last_name,0,1) }}</div>
																	{{ $staffname->first_name." ".$staffname->last_name }}
																</label>
															</li>
														@endforeach
													</ul>
												</li>
											</ul>
										</div>
									</div>
									<div class="card-footer">
										<div class="form-group">
											<h4 class="font-weight-bolder">Staff commission</h4>
											<p class="m-0">Calculate staff commission when the service is sold.
											</p>
											<div class="mt-4 switch switch-icon switch-success" style="line-height: 33px;">
												<label class="d-flex align-item-center font-weight-bolder">
													<input type="checkbox"  name="is_staff_commision_enable" id="is_staff_commision_enable">
													<span></span>&nbsp;&nbsp;&nbsp;Enable Staff commission</i>
												</label>
											</div>
										</div>
									</div>
								</div>
	
								<div class="card my-4">
									<div class="card-header">
										<h2 class="font-weight-bolder">Pricing and Duration</h2>
										<p class="m-0">Add the pricing options and duration of the service.</p>
									</div>
									<div class="card-body">
										<div class="repeatable">
											<div class="card-body p-0">
												<div class="addpricing_option">
													@php $i=1; @endphp
													<div class="w-100 pricing_row pr_rw{{ $i }}">
														<h3 class="col-md-12 font-weight-bolder pricing_opt_txt">Pricing option {{ $i }}</h3>
														<div class="w-100 price-type-container custom-pricing-row">	
															<div class="col-md-2 ad pricing-option">
																<div class="form-group">
																	<label class="col-form-label text-right">Duration</label>
																	<div class="select-wrapper nw_cl">
																		<div class="_1RNu0qum EJYPiwxT">
																			<select class="select optional form-control ddr " data-optid="1" name="duration{{ $i }}" id="duration1">
																				@foreach($duration_array as $key1 => $val)
																					<option @if (60 == $key1) selected="selected" @endif  value="{{ $key1 }}">{{ $val }}</option>
																				@endforeach
																			</select> 
																		</div>
																	</div>
																</div>	
															</div>
															<div class="col-md-2 ad pricing-option">
																<div class="form-group">
																	<label class="col-form-label text-right">Price type</label>
																	<div class="select-wrapper nw_cl">
																		<div class="_1RNu0qum EJYPiwxT">
																			<select class="select optional form-control dpt" data-optid="1" name="price_type{{ $i }}" id="price_type1">
																				<option value="free">Free</option>
																				<option value="from" >From</option>
																				<option value="fixed" selected="selected" >Fixed</option>
																			</select> 
																		</div>
																	</div>
																</div>	
															</div>
															<div class="col-md-2 freehd1 ad pricing-option">
																<div class="form-group">
																	<label class="col-form-label text-right">Price <span class="price-type-text text-muted"></span></label>
																	<input type="text" class="form-control dpr price1 allow_only_decimal custom-price-field" name="price{{ $i }}"  data-optid="1" id="price1" autocomplete="off" placeholder="Price"/>
																</div>	
															</div>
															<div class="col-md-3 freehd1 ad pricing-option">
																<div class="form-group">
																	<label class="col-form-label text-right">Special price <span class="text-muted">(optional)</span></label>
																	<input type="text" class="form-control dsp allow_only_decimal custom-special-price-field" name="special_price{{ $i }}" data-optid="1" id="special_price1" autocomplete="off" placeholder="Special price"/>
																</div>	
															</div>
															<div class="col-md-3 ad pricing-option">
																<div class="form-group">
																	<label class="col-form-label text-right">Pricing name <span class="text-muted">(optional)</span></label>
																	<input type="text" class="form-control dpn" name="pricing_name{{ $i }}" data-optid="1" id="pricing_name1" autocomplete="off" placeholder="Pricing name"/>
																</div>	
															</div>
														</div>
														
														{{-- ad model --}}
														<div class="modal fade" id="add_pricing_opt{{ $i }}" data-id="add_pricing_opt" tabindex="-1" role="dialog" aria-labelledby="exampleModalSizeXl" style="display: none;" aria-hidden="true">
															<div class="modal-dialog modal-dialog-centered modal-xl" role="document" style="max-width: 1203px !important;">
																<div class="modal-content">
																	<div class="modal-header">
																		<h5 class="modal-title text-left" id="exampleModalLabel">Advanced pricing options</h5>
																		<!--  data-dismiss="modal" aria-label="Close" -->
																		<button type="button" class="close close-add-pricing-opt-modal " style="right: unset;">
																			<i aria-hidden="true" class="ki ki-close"></i>
																		</button>
																	</div>
																	
																	<div class="modal-body">
																		<div class="row custom-pricing-row">
																			<div class="col-md-3">
																				<div class="form-group">
																					<label class="col-form-label text-right">Pricing name <span class="text-muted">(optional)</span></label>
																					<input type="text" class="form-control dpn" data-optid="1" id="mpricing_name{{ $i }}" autocomplete="off" placeholder="Pricing name"/>
																				</div>	
																			</div>
																			<div class="col-md-2">
																				<div class="form-group">
																					<label class="col-form-label text-right">Duration</label>
																					<div class="select-wrapper nw_cl">
																						<div class="_1RNu0qum EJYPiwxT">
																							<select class="select optional form-control ddr" data-optid="1" id="mduration{{ $i }}">
																								@foreach($duration_array as $key => $val)
																									<option @if (60 == $key) selected="selected" @endif value="{{ $key }}">{{ $val }}</option>
																								@endforeach
																							</select> 
																						</div>
																					</div>
																				</div>	
																			</div>
																			<div class="col-md-2">
																				<div class="form-group">
																					<label class="col-form-label text-right">Price type</label>
																					<div class="select-wrapper nw_cl">
																						<div class="_1RNu0qum EJYPiwxT">
																							<select class="select optional form-control dpt" data-optid="1" id="mprice_type{{ $i }}">
																								<option value="free">Free</option>
																								<option value="from">From</option>
																								<option value="fixed" selected >Fixed</option>
																							</select> 
																						</div>
																					</div>
																				</div>	
																			</div>
																			<div class="col-md-2">
																				<div class="form-group">
																					<label class="col-form-label text-right">Price</label>
																					<input type="text" class="form-control dpr allow_only_decimal custom-price-field" data-optid="1" id="mprice{{ $i }}" autocomplete="off" placeholder="Price"/>
																				</div>	
																			</div>
																			<div class="col-md-3">
																				<div class="form-group">
																					<label class="col-form-label text-right">Special price <span class="text-muted">(optional)</span></label>
																					<input type="text" class="form-control dsp allow_only_decimal custom-special-price-field" data-optid="1" id="mspecial_price{{ $i }}" autocomplete="off" placeholder="Special price"/>
																				</div>	
																			</div>
																		</div>	
																		
																		<div class="row">	
																			<div class="col-md-12">
																				<h3>Set price by staff</h3>
																			</div>
																			<div class="col-md-3">
																				<div class="form-group">
																					
																				</div>	
																			</div>
																			<div class="col-md-2">
																				<div class="form-group">
																					<label class="col-form-label text-right">Duration</label>
																				</div>	
																			</div>
																			<div class="col-md-2">
																				<div class="form-group">
																					<label class="col-form-label text-right">Price type</label>
																				</div>	
																			</div>
																			<div class="col-md-2">
																				<div class="form-group">
																					<label class="col-form-label text-right">Price</label>
																				</div>	
																			</div>
																			<div class="col-md-3">
																				<div class="form-group">
																					<label class="col-form-label text-right">Special price <span class="text-muted">(optional)</span></label>
																				</div>	
																			</div>
																			@php
																				$s=1;
																			@endphp
																			@foreach($staff as $val)

																			<div class="staff-price-container col-md-12 row custom-pricing-row">
																				<div class="col-md-3">
																					<div class="form-group">
																						<h5>{{ $val->user->first_name." ".$val->user->last_name }}</h5>
																						<input type="hidden" name="staff_ids{{ $s }}[]" value="{{ $val->user->id }}">
																					</div>	
																				</div>
																				<div class="col-md-2">
																					<div class="form-group">
																						<div class="select-wrapper nw_cl">
																							<div class="_1RNu0qum EJYPiwxT">
																								<select class="select optional form-control sdr1" name="staff_duration{{ $s }}[]">
																									@foreach($duration_array as $key => $val1)
																										<option @if (60 == $key) selected="selected" @endif value="{{ $key }}">{{ $val1 }}</option>
																									@endforeach
																								</select> 
																							</div>
																						</div>
																					</div>	
																				</div>
																				<div class="col-md-2">
																					<div class="form-group">
																						<div class="select-wrapper nw_cl">
																							<div class="_1RNu0qum EJYPiwxT">
																								<select class="select optional form-control spt1 staff-price-type" name="staff_price_type{{ $s }}[]" >
																									<option value="free" >Free</option>
																									<option value="from" >From</option>
																									<option value="fixed" selected="selected">Fixed</option>
																								</select> 
																							</div>
																						</div>
																					</div>	
																				</div>
																				<div class="col-md-2">
																					<div class="form-group">
																						<input type="text" class="form-control spr1 staff-price allow_only_decimal custom-price-field" name="staff_price{{ $s }}[]"  autocomplete="off" placeholder="Price"/>
																					</div>	
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<input type="text" class="form-control ssp1 staff-special-price allow_only_decimal custom-special-price-field"  name="staff_special_price{{ $s }}[]" autocomplete="off" placeholder="Special price"/>
																					</div>	
																				</div>
																			</div>
																			@endforeach
																		</div>
																	</div>
																	<div class="modal-footer">
																		<button type="button" class="close-add-pricing-opt-modal btn btn-light-primary font-weight-bold" data-target="add_pricing_opt{{ $i }}">Close</button><!--  data-dismiss="modal" -->
																		<button type="button" class="close-add-pricing-opt-modal btn btn-primary font-weight-bold" data-target="add_pricing_opt{{ $i }}">Save changes</button><!--  data-dismiss="modal" -->
																	</div>
																</div>
															</div>
														</div>
														{{-- end --}}
														<div class="col-md-12 d-flex w-100">
															<a class="advacned_pricing_opt h4 text-blue" data-target="#add_pricing_opt{{ $i }}" data-toggle="modal" href="javascript:;">Advanced pricing options</a>
														</div>
													</div>
												</div>
											</div>
											<!-- <div class="bg-gray-200 mb-5 card card-custom">
												<div class="card-header border-0">
													<div class="card-title m-0">
														<h3 class="m-0">Pricing option </h3>
													</div>
													{{-- <span class="d-flex align-items-center" id="removebtn"><a class="close opacity-100 remove_pricing_opt"><i class="ki ki-close"></i></a></span> --}}
												</div>
											</div> -->
											<div class="row">									
												<div class="col-md-12" style="margin: 20px 0px 30px;">
													<input type="hidden" name="total_pricing" id="total_pricing" value="1">
													<a class="add_pricing_opt h4" id="add_pricing_opt" href="javascript:;"><i class="la la-plus-circle mr-3 text-blue"></i>Add pricing option</a>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer">
										<h5 class="font-weight-bolder mb-6">Extra time</h5>
										<p>Enable extra time after the service.</p>
										<div class="form-group">
											<div class="mt-4 switch switch-icon switch-success" style="line-height: 33px;">
												<label class="d-flex align-item-center font-weight-bolder">
													<input onchange="isEnableExtraTime()" id="enableExtraTime"
														type="checkbox" value="1" name="is_extra_time" id="is_extra_time">
													<span></span>&nbsp;&nbsp;&nbsp;Enable extra time</i>
												</label>
											</div>
										</div>
										<div class="isEnableExtraTime" style="display:none">
											<div class="form-group">
												<div class="row extra_time_sec extra-time" >
													<div class="col-md-3">
														<label class="option">
															<span class="option-control">
																<span class="radio">
																	<input type="radio" name="extra_time"  value="0">
																	<span></span>
																</span>
															</span>
															<span class="option-label">
																<span class="option-head">
																	<span class="option-title">Processing time</span>
																</span>
																<span class="option-body">Take other bookings during this time.</span>
															</span>
														</label>
														<div class="fv-plugins-message-container"></div>
													</div>
													<div class="col-md-3">
														<label class="option">
															<span class="option-control">
																<span class="radio">
																	<input type="radio" name="extra_time" value="1">
																	<span></span>
																</span>
															</span>
															<span class="option-label">
																<span class="option-head">
																	<span class="option-title">Blocked time</span>
																</span>
																<span class="option-body">Block time between appointments.</span>
															</span>
														</label>
													</div>
													
													<div class="col-md-12">
														<label class="col-form-label text-right">Duration</label>
														<div class="select-wrapper nw_cl">
															<div class="_1RNu0qum EJYPiwxT">
																<select class="select optional form-control" default="default" name="extra_time_duration" id="extra_time_duration">
																	@foreach($duration_array as $key => $val)
																		<option  value="{{ $key }}">{{ $val }}</option>
																	@endforeach
																</select> 
															</div>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
								</div>
	
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Sales settings</h4>
									</div>
									<div class="card-body w-100">
										<h5 class="font-weight-bolder mb-6">Set the tax rate</h5>
										<div class="form-group">
											<label class="font-weight-bolder">
												Tax <span class="text-muted font-size-sm">(Included in price)</span>
											</label>
											<select class="form-control" name="tax_id" id="tax_id">
												<option value="0">No tax</option>

												@foreach($taxes as $key => $value) 
													<option value="{{ $value->id }}">{{ $value->tax_name.' ('.$value->tax_rates.')' }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="card-footer">
										<h5 class="font-weight-bolder mb-6">Voucher sales</h5>
										<div class="form-group">
											<div class="mt-4 switch switch-icon switch-success" style="line-height: 33px;">
												<label class="d-flex align-item-center font-weight-bolder">
													<input class="boolean optional ios-switch-cb" onchange="isSaleVoucher()" type="checkbox" value="1" checked="checked" name="is_voucher_enable" id="enableSale"> <span class="switchery"></span> 
													&nbsp;&nbsp;&nbsp;Enable voucher sales</i>
												</label>
											</div>
										</div>
										<div class="form-group isSaleVoucher">
											<div class="form-group ml-2 w-100">
												<label class="font-weight-bolder">Voucher expiry period</label>
												<select class="form-control" name="voucher_expiry" id="voucher_expiry">
													<option value="days_14">14 Days</option>
													<option value="month_1">1 Month</option>
													<option value="month_2">2 Months</option>
													<option value="month_3">3 Months</option>
													<option value="month_6">6 Months</option>
													<option value="year_1">1 Year</option>
													<option value="year_3">3 Years</option>
													<option value="year_5">5 Years</option>
													<option value="0">No Expiry</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div> 
						</div>
					</div>
				</div>
            </div>
	    </form>
    </div>  
	<!--end::Add Single Service Modal-->

	<!--begin::Scrolltop-->
	<div id="kt_scrolltop" class="scrolltop">
		<span class="svg-icon">
			<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
				height="24px" viewBox="0 0 24 24" version="1.1">
				<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<polygon points="0 0 24 0 24 24 0 24" />
					<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
					<path d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z" fill="#000000" fill-rule="nonzero" />
				</g>
			</svg>
			<!--end::Svg Icon-->
		</span>
	</div>
	<!--end::Scrolltop-->

	<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form class="form" method="POST" id="add_client_frm">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Remove Category</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close"></i>
						</button>
					</div>
					<div class="modal-body">Are you sure you want to delete this category?</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-danger font-weight-bold" data-id="" id="deletecat">Delete</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script src="{{ asset('js/add-service.js') }}"></script>

	<script>

		 $(function () {
			// connect sort
			$(".catsortable .sortable").sortable({
				placeholder: 'placeholder',
				forcePlaceholderSize: true,
				tolerance: 'pointer',
				revert: true,
				connectWith: ".connected-sortable",
				stack: ".connected-sortable ul",
				start: function(event, ui) {
					var start_pos = ui.item.index();
					 ui.item.data('start_pos', start_pos);
				},
				update: function() {
					saveCatInSerOrder();
				}
			}).disableSelection();

			function saveCatInSerOrder() {
				var order = [];
				$('#sortable .my-4').each(function(index,element) {
				  order.push({
					id: $(this).attr('data-id'),
					position: index+1
				  });
				});
				var serOrder = [];
				$('#Sersortable .sortindex').each(function(index,element) {
					serOrder.push({
					  id: $(this).attr('data-id'),
					  position: index+1,
					  catId: $(this).parents("li").attr('data-id')
					});
				});
				console.log(order);
				console.log(serOrder);
				//save cat order
				$.ajax({
				  	type: "POST", 
				  	dataType: "json", 
				  	headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
				 	url: "{{ route('catorder') }}",
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
				//
				//save service order
				$.ajax({
					type: "POST", 
					dataType: "json", 
					headers: {
					  'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					  },
				   url: "{{ route('serviceorder') }}",
				  data: {
					serOrder: serOrder,
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
			//end
			//save cat order
			$(".catsortable").sortable({
				placeholder: 'placeholder',
				forcePlaceholderSize: true,
				tolerance: 'pointer',
				revert: true,
				update: function() {
					saveCatOrder();
				}
			});
			$("ul, li").disableSelection();

			function saveCatOrder() {
				var order = [];
				$('#sortable .my-4').each(function(index,element) {
				  order.push({
					id: $(this).attr('data-id'),
					position: index+1
				  });
				});
				$.ajax({
				  	type: "POST", 
				  	dataType: "json", 
				  	headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
						},
				 	url: "{{ route('catorder') }}",
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
	</script>

	<!-- Collaps -->
	<script>
		function collapsAll() {
			$(".collapse").collapse("show");
			$(".service-category").removeClass("border-2")
		}
		function collapsRemoveAll() {
			$(".collapse").collapse("hide");
			$(".service-category").addClass("border-2")
		}
		/*$(document).on("keypress",".dpr",function(e) {
	     	//if the letter is not digit then display error and don't type anything
	     	if (e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57)) {
	        	//display error message
	        	$("#errmsg").html("Digits Only").show().fadeOut("slow");
	               return false;
	    	}
	   });*/
	</script>
	<!-- Modal Step Hide Show -->
	<script>
		var currentModalTab = 0; // Current tab is set to be the first tab (0)
		showModalTab(currentModalTab); // Display the current tab

		function showModalTab(n) {
			// This function will display the specified tab of the form...
			var modalTab = document.getElementsByClassName("modal-tab");
			modalTab[n].style.display = "block";
			if (n == (modalTab.length - 1)) {
				$(".modal-back").show();
			} else {
				$(".modal-back").hide();
			}
		}

		function nextPrevModal(n) {
			// This function will figure out which tab to display
			var modalTab = document.getElementsByClassName("modal-tab");
			// Hide the current tab:
			modalTab[currentModalTab].style.display = "none";
			// Increase or decrease the current tab by 1:
			currentModalTab = currentModalTab + n;
			// if you have reached the end of the tab
			// Otherwise, display the correct tab:
			showModalTab(currentModalTab);
		} 

		$('#showmodel').click( function(){
			$(".modal-back").hide();
		});
		$('#close').click( function(){
			$(".modal-back").show();
		});
	</script>
	<!--begin::Page Scripts(used by this page)-->
	<script src="{{ asset('assets/js/pages/widgets.js')}}"></script>
	<!--end::Page Scripts-->
	<!-- Multi level checkbox -->
	<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js')}}"></script>

	<script>
		$("#treeview-staff").hummingbird();
		$("#treeview-location").hummingbird();

		function selectCaegory(data) {
			$("#selectedCategory").val(data);
		}
		function isSaleVoucher() {
			if ($('#enableSale').is(':checked')) {
				$(".isSaleVoucher").show();
			} else {
				$(".isSaleVoucher").hide();
			}
		}
		function isEnableExtraTime() {
			if ($('#enableExtraTime').is(':checked')) {
				$(".isEnableExtraTime").show();
			} else {
				$(".isEnableExtraTime").hide();
			}
		}

		$(function () {
			$("#add-pricing-option").on('click', function () {
				var ele = $(this).closest('.card-custom').clone(true);
				var btn = '<a class="close opacity-100 remove_pricing_opt" ><i class="ki ki-close"></i></a>';
				$(this).closest('.card-body').after(ele);
				ele.find('.remove_pricing_opt').show();
				
			})
		})
		$(function () {
			$(".repeat").on('click', function (e) {
				e.preventDefault();
				var $self = $(this);
				$self.before($self.prev('.card-custom').clone());
				//$self.remove();
			});
		});
		$(document).on("click", ".remove_pricing_opt", function(){
			var id = $(this).data("id");
			$(".pr_rw"+id).remove(); 
			var cnt = $("#total_pricing").val();
        	cnt--;
			$("#total_pricing").val(cnt);
			
		});
		
		/*$(document).on("focusout", ".dsp", function() {
            if (parseFloat($(this).val()) > parseFloat($(this).closest('.pricing_row').find(".dpr").val())) {
                var errorsHtml = '';
                toastr.error((errorsHtml) ? errorsHtml : 'special price is not gerate than price.');
                KTUtil.scrollTop();
                bigger = false;
                return
            } else {
                bigger = true;
            }
        });*/
		
	</script>
	<script>
		var currentModalTab = 0; // Current tab is set to be the first tab (0)
		showModalTab(currentModalTab); // Display the current tab

		function showModalTab(n) {
			// This function will display the specified tab of the form...
			var modalTab = document.getElementsByClassName("modal-tab");
			modalTab[n].style.display = "block";
			if (n == (modalTab.length - 1)) {
				$(".modal-back").show();
			} else {
				$(".modal-back").hide();
			}
		}

		function nextPrevModal(n) {
			// This function will figure out which tab to display
			var modalTab = document.getElementsByClassName("modal-tab");
			// Hide the current tab:
			modalTab[currentModalTab].style.display = "none";
			// Increase or decrease the current tab by 1:
			currentModalTab = currentModalTab + n;
			// if you have reached the end of the tab
			// Otherwise, display the correct tab:
			showModalTab(currentModalTab);
		}
	</script>
	<script>
		$(".isServicePriceOrisFree").show()
		$(".isCustomPrice").hide()
		$(".isPercentage").hide()
		function priceSelection(data) {
			console.log(data.value);
			if (data.value === "catalog_price" || data.value === "free") {
				$(".isServicePriceOrisFree").show()
				$(".isCustomPrice").hide()
				$(".isPercentage").hide()
			} else if (data.value === "custom_price") {
				$(".isServicePriceOrisFree").hide()
				$(".isCustomPrice").show()
				$(".isPercentage").hide()
			} else {
				$(".isServicePriceOrisFree").hide()
				$(".isCustomPrice").hide()
				$(".isPercentage").show()
			}
		} 
	</script>
	<script type="text/javascript"> 
		var serviceValid = ""; 
		var duration = '<?php echo json_encode($duration_array);?>'; 
		var staff = '<?php echo json_encode($staff);?>';
		
		const priceValidators = {
			validators: {
				notEmpty: {
					message: 'Price is required'
				}
			}
		};
		
		// save category
		$(document).on("click", "#save", function() {
			var form_data = new FormData($('#cat')[0]);
	
			$.ajax({
				type: 'POST',
				url: "{{ route('addcat') }}",
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: form_data,
				dataType: 'json',
				processData: false,
				contentType: false,
				success: function(response) {
	
					window.location.href = response.redirect;
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
		// end
	
		// get edit cat 
		$(document).on("click", "#editcat", function() {
	
			var url = $(this).data("url");
			var id = $(this).data("id");
	
			$.ajax({
				url: url,
				type: 'get',
				processData: false,
				contentType: false,
				success: function(response) {
					$("#editcategoryModal .modal-dialog #deletecat").data('id', id);
					$("#editcategoryModal .modal-dialog #catid").val(id);
					$("#editcategoryModal .modal-dialog #appointment_color").val(response.cat.appointment_color);
					$("#editcategoryModal .modal-dialog #category_title").val(response.cat.category_title);
					$("#editcategoryModal .modal-dialog #category_description").text(response.cat.category_description);
				},
				error: function(data) {
					var errors = data.responseJSON;
					var errorsHtml = '';
	
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
		// end
		// save edit category
		$(document).on("click", "#editsave", function() {
	
			$.ajax({
				url: "{{ route('editcat') }}",
				type: 'POST',
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				},
				data: $("#saveeditcat").serialize(),
	
				success: function(response) {
					//console.log(response);
	
					window.location.href = response.redirect;
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
	
		// delete edit cat 
		$(document).on('click','.deleteCategory',function(){
			var id = $(this).data('id');
			$("#deletecat").attr('data-id',id);
			$('#confirmModal').modal('show');
		});
		$(document).on("click", "#deletecat", function() {
	
			var id = $(this).data("id");
			{{-- var url = "{{ url('partners/services/deletecat') }}"+'/'+id; --}}
			var url = "{{ route('deletecat',":id") }}";
			url = url.replace(':id',id);

			$.ajax({
				url: url,
				type: 'get',
				processData: false,
				contentType: false,
				success: function(response) {
					$('#confirmModal').modal('hide');
					if(response.status) {
						toastr.success(response.message);
					} else {
						toastr.error(response.message);
					}
					$(".services").load(" .services");
					$('#editcategoryModal').modal('hide');
				},
				error: function(data) {
					var errors = data.responseJSON;
					var errorsHtml = '';
	
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
		// end 
		
		$('#addNewSingleServiceModal').on("scroll", function() {   
	        var topHeight = $('.my-custom-header').outerHeight(); 
	        if ($(this).scrollTop() > topHeight) {
				$('.my-custom-header').addClass("bg-white");
				$('.my-custom-header').addClass("border-bottom");  
	        } else{ 
	          	$('.my-custom-header').removeClass("bg-white"); 
	          	$('.my-custom-header').removeClass("border-bottom");  
	        }   
	    }); 

	    $(document).on('click','.open-service', function(){
	    	window.location.href = $(this).attr('data-href');
	    });

	    $(document).on('click','.close-add-pricing-opt-modal', function(){
	    	var id = $(this).closest('.modal').attr('id');

	    	$('#'+id).modal('hide');
	    });

	    $(document).on('hidden.bs.modal', function (event) {
	    	// console.log(event.target.getAttribute('data-id'));
	    	// if (event.target.getAttribute('data-id') == 'add_pricing_opt') {
	        		
	          	$('.modal').each(function(){
	          		if($(this).hasClass('show')) {
	          			$('body').addClass('modal-open');
	          			return false;
	          		}
	          	});

          // 		setTimeout(function(){
        		// 	$('#addNewSingleServiceModal').modal('show');
        		// }, 10);
          // 	}

	    });

        $(document).on('change','.staff-price-type', function(){
        	if($(this).val() == 'free') {
    	    	$(this).closest('.staff-price-container').find('.staff-price').val('').attr('readonly', true);
    	    	$(this).closest('.staff-price-container').find('.staff-special-price').val('').attr('readonly', true);
    	    } else {
    	    	$(this).closest('.staff-price-container').find('.staff-price').removeAttr('readonly');
    	    	$(this).closest('.staff-price-container').find('.staff-special-price').removeAttr('readonly');
    	    }
        });
	</script> 
@endsection
