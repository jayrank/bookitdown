{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!--begin::Body-->
@if ($errors->any())
	<div class="alert alert-danger">
	    <ul>
	        @foreach ($errors->all() as $error)
	            <li>{{ $error }}</li>
	        @endforeach
	    </ul>
	</div>
@endif
@if ($message = Session::get('success'))
	<div class="alert alert-success alert-block">
	    <button type="button" class="close" data-dismiss="alert">×</button>    
	    <strong>{{ $message }}</strong>
	</div>
@endif
	<!-- <div class="container-fluid p-0">
		<div class="my-custom-body-wrapper">
			<form action="{{ route('editlocation',['id' => $locationData->id]) }}" method="POST" enctype="multipart/form-data" id="editlocation">
				@csrf
				<div class="my-custom-header">
					<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
						<span class="d-flex">
							<p class="cursor-pointer m-0 px-6" onclick="history.back();"><i class="text-dark fa fa-times icon-lg"></i></p>
						</span>
						<span class="text-center">
							<h1 class="font-weight-bolder page-title">Edit your location</h1>
						</span>
						<div>
							<button class="btn btn-lg btn-primary next-step" type="submit" id="editlocationsubmit" >Save</button>
						</div>
					</div>
				</div>
				
				<div class="my-custom-body">
					<div class="container-fluid">
						<div class="row justify-content-center">
							<div class="col-6">
								<div class="p-4">
									<input type="hidden" name="editlocationID" value="{{ $locationData->id }}">
									<input type="hidden" id="location_name" name="location_name" value="{{ $locationData->location_name }}">
										<div class="card">
											<div class="card-header">
												<h4>Business location</h4>
											</div>
											<div class="card-body">
												<div class="form-group">
													<label class="font-weight-bolder" for="location_address">Where’s your business located?</label>
													<div class="input-group">
														<div class="input-group-prepend">
															<span class="input-group-text bg-transparent"><i class="fa fa-map-marker"></i></span>
														</div>
														<input type="text" class="form-control" name="location_address" id="location_address" value="{{ $locationData->location_address }}">
														<p id="location_error"></p>
														<input type="hidden" name="lat" id="lat" value="{{ $locationData->location_latitude }}">
														<input type="hidden" name="lng" id="lng" value="{{ $locationData->location_longitude }}">
													</div>
													
													<div class=" bg-content p-4 address_detail_setion mt-5" @if($locationData->no_business_address == 1) style="display: none;" 	@endif >
														<h5 class="position-absolute" style="right:40px;z-index: 1;">
															<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">Edit</a>
														</h5>
														<div class="row">
															<div class="col-md-4">
																<div class="my-6">
																	<h4>Address</h4>
																	@if($locationData->loc_address != "")
																		<p id="loc_address">{{ $locationData->loc_address }}</p>
																	@else 
																		<p id="loc_address"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
																	@endif	
																	<input type="hidden" name="loc_address" class="loc_address" value="{{ $locationData->loc_address }}">
																</div>
															</div>	
															<div class="col-md-4">
																<div class="my-6">
																	<h4>Apt./Suite etc</h4>
																	@if($locationData->loc_apt != "")
																		<p id="loc_apt">{{ $locationData->loc_apt }}</p>
																	@else 
																		<p id="loc_apt"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
																	@endif
																	<input type="hidden" name="loc_apt" class="loc_apt" value="{{ $locationData->loc_apt }}">
																</div>
															</div>	
															<div class="col-md-4">
																<div class="my-6">
																	<h4>District</h4>
																	@if($locationData->loc_district != "")
																		<p id="loc_district">{{ $locationData->loc_district }}</p>
																	@else 
																		<p id="loc_district"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
																	@endif
																	<input type="hidden" name="loc_district" class="loc_district" value="{{ $locationData->loc_district }}">
																</div>
															</div>	
															<div class="col-md-4">
																<div class="my-6">
																	<h4>City</h4>
																	@if($locationData->loc_city != "")
																		<p id="loc_city">{{ $locationData->loc_city }}</p>
																	@else 
																		<p id="loc_city"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
																	@endif
																	
																	<input type="hidden" name="loc_city" class="loc_city" value="{{ $locationData->loc_district }}">
																</div>
															</div>	
															<div class="col-md-4">
																<div class="my-6">
																	<h4>Region</h4>
																	@if($locationData->loc_region != "" && $locationData->loc_county != "")
																		<p><span id="loc_region">{{ $locationData->loc_region }}</span><span id="loc_county">{{ $locationData->loc_county }}</span></p>
																	@else 
																		<p><span id="loc_region"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></span><span id="loc_county"></span></p>
																	@endif	
																	<input type="hidden" name="loc_region" class="loc_region" value="{{ $locationData->loc_region }}">
																	<input type="hidden" name="loc_county" class="loc_county" value="{{ $locationData->loc_county }}">
																</div>
															</div>	
															<div class="col-md-4">
																<div class="my-6">
																	<h4>Postcode</h4>
																	@if($locationData->loc_postcode != "")
																		<p id="loc_postcode">{{ $locationData->loc_postcode }}</p>
																	@else 
																		<p id="loc_postcode"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
																	@endif
																	<input type="hidden" name="loc_postcode" class="loc_postcode" value="{{ $locationData->loc_postcode }}">
																</div>
															</div>	
															<div class="col-md-4">
																<div class="my-6">
																	<h4>Country</h4>
																	@if($locationData->loc_country != "")
																		<p id="loc_country">{{ $locationData->loc_country }}</p>
																	@else 
																		<p id="loc_country"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
																	@endif
																	<input type="hidden" name="loc_country" class="loc_country" value="{{ $locationData->loc_country }}">
																</div>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="checkbox-list">
														<label class="checkbox">
															<input type="checkbox" id="no_business_address" name="no_business_address" @if($locationData->no_business_address == 1) checked @endif value="1">
															<span></span> I don’t have a business address (mobile and virtual services)
														</label>
													</div>
												</div>
											</div>
										</div>
										<div class="card map_section" @if($locationData->no_business_address == 1 && $locationData->location_latitude == "" && $locationData->location_longitude == "") style="display: none;" @endif >
											<div class="card-header">
												<h4>Map</h4>
											</div>
											<div class="card-body">
												<div class="form-group">
													 <div class="map" id="map" style="width: 100%; height: 300px;"></div>
												</div>
											</div>
										</div>

										<div class="card my-3">
											<div class="card-header">
												<h4>Billing details for clients invoice</h4>
												<p>These details will appear on the client’s invoice for this location as
													well as the information you’ve configured in your
													Invoice Template settings.
												</p>
											</div>
											<div class="card-body">
												<div class=" bg-content p-4">
													<h5 class="position-absolute" style="right:40px;z-index: 1;">
														<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBillingDetail">Edit</a>
													</h5>
													<div class="row">
														<div class="col-12 col-md-12">
															<div class="my-6">
																<h4>Company name</h4>
																<p id="billing_company_name">{{ $locationData->billing_company_name }}</p>
																<input type="hidden" class="form-control billing_company_name" name="billing_company_name" placeholder="" value="{{ $locationData->billing_company_name }}">
															</div>
															<div class="my-6">
																<h4>Address</h4>
																@php
																	$buss_addr = "";
																	if($locationData->billing_address != "") {
																		$buss_addr .= $locationData->billing_address.", ";
																	}
																	if($locationData->billing_city != "") {
																		$buss_addr .= $locationData->billing_city.", ";
																	}
																	if($locationData->billing_postcode != "") {
																		$buss_addr .= $locationData->billing_postcode.", ";
																	}
																	if($locationData->billing_region != "") {
																		$buss_addr .= $locationData->billing_region;
																	}
																@endphp
																<p id="billing_address">{{ $buss_addr }}</p>
																<input type="hidden" class="isSameBillingAddr" name="is_same_billing_addr" value="{{ $locationData->is_same_billing_addr }}">
																<input type="hidden" class="billing_address" name="billing_address" value="{{ $locationData->billing_address }}">
																<input type="hidden" class="billing_apt" name="billing_apt" value="{{ $locationData->billing_apt }}">
																<input type="hidden" class="billing_city" name="billing_city" value="{{ $locationData->billing_city }}">
																<input type="hidden" class="billing_region" name="billing_region" value="{{ $locationData->billing_region }}">
																<input type="hidden" class="billing_postcode" name="billing_postcode" value="{{ $locationData->billing_postcode }}">
															</div>
															<div class="my-6">
																<h4>Notes</h4>
																@if($locationData->billing_notes != "")
																	<p id="billing_notes">{{ $locationData->billing_notes }}</p>
																@else 
																	<p id="billing_notes"><a data-toggle="modal" data-target="#editBillingDetail" class="text-blue cursor-pointer">+ Add</a></p>
																@endif
																<input type="hidden" class="billing_notes" name="billing_notes" value="{{ $locationData->billing_notes }}">
															</div>
														</div>
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
	</div> -->

	<div class="container-fluid p-0">		
		<div class="fullscreen-modal" tabindex="-1" role="dialog" aria-hidden="true"> 
			<form action="{{ route('editlocation',['id' => $locationData->id]) }}" method="POST" enctype="multipart/form-data" id="editlocation">
				@csrf
				<div class="my-custom-header text-dark"> 
		            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
		            	<div style="display: flex; justify-content: flex-start; align-items: center;">
				            <a type="button" class="close" data-dismiss="modal" aria-label="Close" style="top:unset;left: 0;opacity: 1;font-size: 1.75rem;" onclick="window.history.back();">
				                <span aria-hidden="true" class="la la-times"></span>
				            </a>
				        </div>
			            <div style="flex-grow: 1; text-align: center;">
							<h2 class="font-weight-bolder page-title title-hide">Edit your location</h2>
			            </div>
			            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
			                <button class="btn btn-lg btn-primary next-step" type="submit" id="editlocationsubmit">Save</button>
			            </div>
			        </div>
		        </div> 
				<div class="border-0 mb-5 text-center firstStep">  
					<h1 class="font-weight-bolder mb-5 text-center text-dark page-title hide-onscroll" style="flex-grow: 1;">Edit your location</h1>  
				</div>
				<div class="container my-custom-body">	
					<div class="row justify-content-center">
						<div class="col-12 col-md-9">			
							<div class="p-4">
								<input type="hidden" name="editlocationID" value="{{ $locationData->id }}">
								<input type="hidden" id="location_name" name="location_name" value="{{ $locationData->location_name }}">
								<div class="card">
									<div class="card-header">
										<h4>Business location</h4>
									</div>
									<div class="card-body">
										<div class="form-group">
											<label class="font-weight-bolder" for="location_address">Where’s your business located?</label>
											<div class="input-group">
												<div class="input-group-prepend">
													<span class="input-group-text bg-transparent"><i class="fa fa-map-marker"></i></span>
												</div>
												<input type="text" class="form-control" name="location_address" id="location_address" value="{{ $locationData->location_address }}">
												<p id="location_error"></p>
												<input type="hidden" name="lat" id="lat" value="{{ $locationData->location_latitude }}">
												<input type="hidden" name="lng" id="lng" value="{{ $locationData->location_longitude }}">
											</div>
											
											<div class=" bg-content p-4 address_detail_setion mt-5" @if($locationData->no_business_address == 1) style="display: none;" 	@endif >
												<h5 class="position-absolute" style="right:40px;z-index: 1;">
													<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">Edit</a>
												</h5>
												<div class="row">
													<div class="col-md-4">
														<div class="my-6">
															<h4>Address</h4>
															@if($locationData->loc_address != "")
																<p id="loc_address">{{ $locationData->loc_address }}</p>
															@else 
																<p id="loc_address"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
															@endif	
															<input type="hidden" name="loc_address" class="loc_address" value="{{ $locationData->loc_address }}">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>Apt./Suite etc</h4>
															@if($locationData->loc_apt != "")
																<p id="loc_apt">{{ $locationData->loc_apt }}</p>
															@else 
																<p id="loc_apt"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
															@endif
															<input type="hidden" name="loc_apt" class="loc_apt" value="{{ $locationData->loc_apt }}">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>District</h4>
															@if($locationData->loc_district != "")
																<p id="loc_district">{{ $locationData->loc_district }}</p>
															@else 
																<p id="loc_district"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
															@endif
															<input type="hidden" name="loc_district" class="loc_district" value="{{ $locationData->loc_district }}">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>City</h4>
															@if($locationData->loc_city != "")
																<p id="loc_city">{{ $locationData->loc_city }}</p>
															@else 
																<p id="loc_city"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
															@endif
															
															<input type="hidden" name="loc_city" class="loc_city" value="{{ $locationData->loc_district }}">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>Region</h4>
															@if($locationData->loc_region != "" && $locationData->loc_county != "")
																<p><span id="loc_region">{{ $locationData->loc_region }}</span><span id="loc_county">{{ $locationData->loc_county }}</span></p>
															@else 
																<p><span id="loc_region"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></span><span id="loc_county"></span></p>
															@endif	
															<input type="hidden" name="loc_region" class="loc_region" value="{{ $locationData->loc_region }}">
															<input type="hidden" name="loc_county" class="loc_county" value="{{ $locationData->loc_county }}">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>Postcode</h4>
															@if($locationData->loc_postcode != "")
																<p id="loc_postcode">{{ $locationData->loc_postcode }}</p>
															@else 
																<p id="loc_postcode"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
															@endif
															<input type="hidden" name="loc_postcode" class="loc_postcode" value="{{ $locationData->loc_postcode }}">
														</div>
													</div>	
													<div class="col-md-4">
														<div class="my-6">
															<h4>Country</h4>
															@if($locationData->loc_country != "")
																<p id="loc_country">{{ $locationData->loc_country }}</p>
															@else 
																<p id="loc_country"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
															@endif
															<input type="hidden" name="loc_country" class="loc_country" value="{{ $locationData->loc_country }}">
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group">
											<div class="checkbox-list">
												<label class="checkbox">
													<input type="checkbox" id="no_business_address" name="no_business_address" @if($locationData->no_business_address == 1) checked @endif value="1">
													<span></span> I don’t have a business address (mobile and virtual services)
												</label>
											</div>
										</div>
									</div>
								</div>
								<div class="card map_section mt-3" @if($locationData->no_business_address == 1 && $locationData->location_latitude == "" && $locationData->location_longitude == "") style="display: none;" @endif >
									<div class="card-header">
										<h4>Map</h4>
									</div>
									<div class="card-body">
										<div class="form-group mb-0">
											 <div class="map" id="map" style="width: 100%; height: 300px;"></div>
										</div>
									</div>
								</div>

								<div class="card my-4">
									<div class="card-header">
										<h4>Billing details for clients invoice</h4>
										<p>These details will appear on the client’s invoice for this location as
											well as the information you’ve configured in your
											Invoice Template settings.
										</p>
									</div>
									<div class="card-body">
										<div class=" bg-content p-4">
											<h5 class="position-absolute" style="right:40px;z-index: 1;">
												<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBillingDetail">Edit</a>
											</h5>
											<div class="row">
												<div class="col-12 col-md-12">
													<div class="my-6">
														<h4>Company name</h4>
														<p id="billing_company_name">{{ $locationData->billing_company_name }}</p>
														<input type="hidden" class="form-control billing_company_name" name="billing_company_name" placeholder="" value="{{ $locationData->billing_company_name }}">
													</div>
													<div class="my-6">
														<h4>Address</h4>
														@php
															$buss_addr = "";
															if($locationData->billing_address != "") {
																$buss_addr .= $locationData->billing_address.", ";
															}
															if($locationData->billing_city != "") {
																$buss_addr .= $locationData->billing_city.", ";
															}
															if($locationData->billing_postcode != "") {
																$buss_addr .= $locationData->billing_postcode.", ";
															}
															if($locationData->billing_region != "") {
																$buss_addr .= $locationData->billing_region;
															}
														@endphp
														<p id="billing_address">{{ $buss_addr }}</p>
														<input type="hidden" class="isSameBillingAddr" name="is_same_billing_addr" value="{{ $locationData->is_same_billing_addr }}">
														<input type="hidden" class="billing_address" name="billing_address" value="{{ $locationData->billing_address }}">
														<input type="hidden" class="billing_apt" name="billing_apt" value="{{ $locationData->billing_apt }}">
														<input type="hidden" class="billing_city" name="billing_city" value="{{ $locationData->billing_city }}">
														<input type="hidden" class="billing_region" name="billing_region" value="{{ $locationData->billing_region }}">
														<input type="hidden" class="billing_postcode" name="billing_postcode" value="{{ $locationData->billing_postcode }}">
													</div>
													<div class="my-6">
														<h4>Notes</h4>
														@if($locationData->billing_notes != "")
															<p id="billing_notes">{{ $locationData->billing_notes }}</p>
														@else 
															<p id="billing_notes"><a data-toggle="modal" data-target="#editBillingDetail" class="text-blue cursor-pointer">+ Add</a></p>
														@endif
														<input type="hidden" class="billing_notes" name="billing_notes" value="{{ $locationData->billing_notes }}">
													</div>
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
	</div>

	<!-- Modal -->
	<div class="modal" id="editBusinessLocation" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="editBillingDetailLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title font-weight-bolder" id="editBillingDetailLabel">Edit business location</h3>
					<p class="cursor-pointer m-0" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i></p>
				</div>
				<div class="modal-body">
					<div>
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>Address</label>
								<input type="text" class="form-control loc_address" autocomplete="off" placeholder="" value="{{ $locationData->loc_address }}">
							</div>
							<div class="form-group  mr-2 w-50">
								<label>Apt./Suite etc</label>
								<input type="text" class="form-control loc_apt" autocomplete="off" placeholder="" value="{{ $locationData->loc_apt }}">
							</div>
						</div>
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>District</label>
								<input type="text" class="form-control loc_district" autocomplete="off" placeholder="" value="{{ $locationData->loc_district }}">
							</div>
							<div class="form-group  mr-2 w-50">
								<label>City</label>
								<input type="text" class="form-control loc_city" autocomplete="off" placeholder="" value="{{ $locationData->loc_city }}">
							</div>
						</div>
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>County</label>
								<input type="text" class="form-control loc_county" autocomplete="off" placeholder="" value="{{ $locationData->loc_county }}">
							</div>
							<div class="form-group  mr-2 w-50">
								<label>State</label>
								<input type="text" class="form-control loc_region" autocomplete="off" placeholder="" value="{{ $locationData->loc_region }}">
							</div>
						</div>
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>Postcode</label>
								<input type="text" class="form-control loc_postcode" autocomplete="off" placeholder="" value="{{ $locationData->loc_postcode }}">
							</div>
							<div class="form-group  ml-2 w-100">
								<label>Country</label>
								<input type="text" class="form-control loc_country" autocomplete="off" placeholder="" value="{{ $locationData->loc_country }}">
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-danger updateLocationAddr" data-dismiss="modal">Save</button>
				</div>
			</div>
		</div>
	</div>
	
	<div class="modal" id="editBillingDetail" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="editBillingDetailLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title font-weight-bolder" id="editBillingDetailLabel">Edit billing details
					</h3>
					<p class="cursor-pointer m-0 closeBillingMdl" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				
				<form action="#" method="POST" id="updateBillingAddress">
					<div class="modal-body">
						<div>
							<div class="form-group">
								<div class="checkbox-list mt-8">
									<label class="checkbox font-weight-bolder">
										<input id="isSameLocationAddr" name="isSameLocationAddr" type="checkbox" @if (0 == $locationData->is_same_billing_addr) checked @endif>
										<span></span> Use location name and address for client invoices
									</label>
								</div>
							</div>
							<div class="form-group">
								<label>Company name </label>
								<input type="text" class="form-control bill_inpt billing_company_name" autocomplete="off" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_company_name }}">
							</div>
							<div class="d-flex">
								<div class="form-group  mr-2 w-100">
									<label>Address</label>
									<input type="text" class="form-control bill_inpt billing_address" autocomplete="off" name="billing_address" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_address }}">
								</div>
								<div class="form-group  mr-2 w-50">
									<label>Apt./Suite etc</label>
									<input type="text" class="form-control bill_inpt billing_apt" autocomplete="off" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_apt }}">
								</div>
							</div>
							<div class="form-group">
								<label>City </label>
								<input type="text" class="form-control bill_inpt billing_city" autocomplete="off" name="billing_city" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_city }}">
							</div>
							<div class="d-flex">
								<div class="form-group  mr-2 w-100">
									<label>State</label>
									<input type="text" class="form-control bill_inpt billing_region" autocomplete="off" name="billing_region" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_region }}">
								</div>
								<div class="form-group  ml-2 w-100">
									<label>Postcode</label>
									<input type="text" class="form-control bill_inpt billing_postcode" autocomplete="off" name="billing_postcode" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_postcode }}">
								</div>
							</div>
							<div class="form-group">
								<label>Invoice note</label>
								<textarea class="form-control billing_notes" rows="5" placeholder="VAT number or other info">{{ $locationData->billing_notes }}</textarea>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="submit" class="btn btn-danger updateBillingAddr">Save</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
	
	<!-- End Modal -->
	@endsection
	@section('scripts')
	<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVdjXD_FnLKsQTNU8ki6Np_YlfwU0Oyec&v=3.exp&libraries=places"></script>
	<!--end::Page Scripts-->
	<script src="{{ asset('js/editLocation.js') }}"></script>
	
	<script type="text/javascript">
	function initialize() 
	{
		var latlng = new google.maps.LatLng({{ ($locationData->location_latitude) ? $locationData->location_latitude : 28.5355161 }},{{ ($locationData->location_longitude) ? $locationData->location_longitude : 77.39102649999995 }});
		var map = new google.maps.Map(document.getElementById('map'), {
			center: latlng,
			zoom: 13
		});
		
		var marker = new google.maps.Marker({
			map: map,
			position: latlng,
			draggable: true,
			anchorPoint: new google.maps.Point(0, -29)
		});
		
		var input = document.getElementById('location_address');
		//map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
		var geocoder = new google.maps.Geocoder();
		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.bindTo('bounds', map);
		//var infowindow = new google.maps.InfoWindow();   
		autocomplete.addListener('place_changed', function() {
			//infowindow.close();
			marker.setVisible(false);
			var place = autocomplete.getPlace();
			if (!place.geometry) {
				window.alert("Autocomplete's returned place contains no geometry");
				return;
			}
	  
			// If the place has a geometry, then present it on a map.
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			} else {
				map.setCenter(place.geometry.location);
				map.setZoom(17);
			}
		   
			marker.setPosition(place.geometry.location);
			marker.setVisible(true);          
			$(".map_section").show();
		
			var premise = addres = district = postal_code = city = region = county = postal_code = country = "";
			var place = autocomplete.getPlace();
			for (var i = 0; i < place.address_components.length; i++) 
			{
				for (var j = 0; j < place.address_components[i].types.length; j++) 
				{
					if (place.address_components[i].types[j] == "premise") 
					{
						premise = place.address_components[i].long_name+", ";
					}
					if (place.address_components[i].types[j] == "street_number") 
					{
						addres += place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "route") 
					{
						addres += " "+place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "neighborhood") 
					{
						district = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "sublocality_level_1") 
					{
						district = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "locality") 
					{
						city = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "administrative_area_level_1") 
					{
						region = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "administrative_area_level_2") 
					{
						county = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "postal_code") 
					{
						postal_code = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "country") 
					{
						country = place.address_components[i].long_name;
					}
				}
			}
			
			$(".address_detail_setion").show();
			
			if(addres == "") {
				var lbladd = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lbladd = premise+""+addres;
			}		
			
			if(district == "") {
				var lbldist = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lbldist = district;
			}		
			
			if(postal_code == "") {
				var lblpost = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lblpost = postal_code;
			}		
			
			$("#loc_apt").html('<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>');
			$("#loc_address").html(lbladd);
			$("#loc_district").html(lbldist);
			$("#loc_city").html(city);
			$("#loc_region").html(region+", ");
			$("#loc_county").html(county);
			$("#loc_postcode").html(lblpost);
			$("#loc_country").html(country);
			
			$('.loc_address').val(premise+""+addres);
			$('.loc_district').val(district);
			$('.loc_city').val(city);
			$('.loc_region').val(region);
			$('.loc_county').val(county);
			$('.loc_postcode').val(postal_code);
			$('.loc_country').val(country);
			
			if($("#isSameLocationAddr").is(':checked'))
			{
				var bill_addr = "";
				
				if(addres != "") {
					bill_addr += premise+""+addres+", ";
				}		
				
				if(city != "") {
					bill_addr += city+", ";
				}		
				
				if(postal_code != "") {
					bill_addr += postal_code+", ";
				}
				
				if(region != "") {
					bill_addr += region;
				}
				
				$("#billing_company_name").html($("#location_name").val());
				$(".billing_company_name").val($("#location_name").val());
				
				$('#billing_address').html(bill_addr);
				$('.billing_address').val(premise+""+addres);
				$('.billing_city').val(city);
				$('.billing_region').val(region);
				$('.billing_postcode').val(postal_code);
			}	
			
			var address = "";
			var lat = place.geometry.location.lat();
			var lng = place.geometry.location.lng();
			
			if(addres != "") {
				address += premise+""+addres+", ";
			} 
			if(city != "") {
				address += city+", ";
			} 
			if(district != "") {
				address += " ("+district+")"+", ";
			} 
			if(region != "") {
				address += region;
			}		
			
			document.getElementById('location_address').value = address;
			document.getElementById('lat').value = lat;
			document.getElementById('lng').value = lng;
		});
		
		// this function will work on marker move event into map 
		google.maps.event.addListener(marker, 'dragend', function() {
			geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {        
						bindDataToForm(results[0].formatted_address,marker.getPosition().lat(),marker.getPosition().lng());
					}
				}
			});
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	
	document.getElementById('no_business_address').onchange = function() {
		if(this.checked)
		{
			$('#location_address').css("pointer-events", "none");
			$('#location_address').removeClass("is-invalid");
			$(".address_detail_setion").hide();
			$(".map_section").hide();
			$(".fv-help-block").html("");
		} else {
			$('#location_address').css("pointer-events", "all");
			$(".address_detail_setion").show();	
			$(".map_section").show();	
		}	
	}
	
	document.getElementById('isSameLocationAddr').onchange = function() {
		if(this.checked)
		{
			$('.bill_inpt').attr("disabled", true);
			$(".updateLocationAddr").trigger("click");
		} else {
			$('.bill_inpt').attr("disabled", false);
		}	
	}
	
	$('#mymodal').on('show.bs.modal', function() {
		$(".updateLocationAddr").trigger("click");
	});	
	
	$(document).ready( function(){
		$(".updateLocationAddr").click( function(){
			var addr = $("#editBusinessLocation .loc_address").val();
			var loc_apt = $("#editBusinessLocation .loc_apt").val();	
			var loc_district = $("#editBusinessLocation .loc_district").val();	
			var loc_city = $("#editBusinessLocation .loc_city").val();	
			var loc_county = $("#editBusinessLocation .loc_county").val();	
			var loc_region = $("#editBusinessLocation .loc_region").val();	
			var loc_postcode = $("#editBusinessLocation .loc_postcode").val();	
			var loc_country = $("#editBusinessLocation .loc_country").val();	
			
			var address = "";
			
			if(addr != "") {
				address += addr+", ";
			} 
			if(loc_city != "") {
				address += loc_city;
			}
			if(loc_district != "") {
				address += " ("+loc_district+")";
			} 
			if(loc_region != "") {
				address += ", "+loc_region;
			}
			document.getElementById('location_address').value = address;
			
			if(addr == "") {
				var lbladd = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lbladd = addr;
			}		
			
			if(loc_district == "") {
				var lbldist = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lbldist = loc_district;
			}		
			
			if(loc_postcode == "") {
				var lblpost = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lblpost = loc_postcode;
			}
			
			if(loc_apt == "") {
				var lblapt = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lblapt = loc_apt;
			}
			
			$("#loc_address").html(lbladd);
			$("#loc_apt").html(lblapt);
			$("#loc_district").html(lbldist);
			$("#loc_city").html(loc_city);
			$("#loc_region").html(loc_region+", ");
			$("#loc_county").html(loc_county);
			$("#loc_postcode").html(lblpost);
			$("#loc_country").html(loc_country);
			
			$('.loc_address').val(addr);
			$('.loc_apt').val(loc_apt);
			$('.loc_district').val(loc_district);
			$('.loc_city').val(loc_city);
			$('.loc_region').val(loc_region);
			$('.loc_county').val(loc_county);
			$('.loc_postcode').val(loc_postcode);
			$('.loc_country').val(loc_country);
			
			if($("#isSameLocationAddr").is(':checked'))
			{
				var bill_addr = "";
					
				if(addr != "") {
					bill_addr += addr+", ";
				}		
				
				if(loc_apt != "") {
					bill_addr += loc_apt+", ";
				}		
				
				if(loc_city != "") {
					bill_addr += loc_city+", ";
				}		
				
				if(loc_postcode != "") {
					bill_addr += loc_postcode+", ";
				}
				
				if(loc_region != "") {
					bill_addr += loc_region;
				}
				
				$("#billing_company_name").html($("#location_name").val());
				$(".billing_company_name").val($("#location_name").val());
				
				$('#billing_address').html(bill_addr);
				$('.billing_address').val(addr);
				$('.billing_apt').val(loc_apt);
				$('.billing_city').val(loc_city);
				$('.billing_region').val(loc_region);
				$('.billing_postcode').val(loc_postcode);
			}
		});	
		
		
		var form = KTUtil.getById('updateBillingAddress');
		
		FormValidation
		.formValidation(
			form, {
				fields: {
					billing_address: {
						validators: {
							callback: {
								message: 'Address is required',
								callback: function(input) {
									if($("#isSameLocationAddr").is(':checked'))
									{
										return true;
									} else {
										if(input.value == "") {
											return false;
										} else {
											return true;
										}		
									}		
								}
							}
						}
					},
					billing_city: {
						validators: {
							callback: {
								message: 'City is required',
								callback: function(input) {
									if($("#isSameLocationAddr").is(':checked'))
									{
										return true;
									} else {
										if(input.value == "") {
											return false;
										} else {
											return true;
										}		
									}		
								}
							}
						}
					},
					billing_region: {
						validators: {
							callback: {
								message: 'Region is required',
								callback: function(input) {
									if($("#isSameLocationAddr").is(':checked'))
									{
										return true;
									} else {
										if(input.value == "") {
											return false;
										} else {
											return true;
										}		
									}		
								}
							}
						}
					},
					billing_postcode: {
						validators: {
							callback: {
								message: 'Postcode is required',
								callback: function(input) {
									if($("#isSameLocationAddr").is(':checked'))
									{
										return true;
									} else {
										if(input.value == "") {
											return false;
										} else {
											return true;
										}		
									}		
								}
							}
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					submitButton: new FormValidation.plugins.SubmitButton(),
					bootstrap: new FormValidation.plugins.Bootstrap({})
				}
			}
		)
		.on('core.form.valid', function() {
		   
			$(".closeBillingMdl").trigger("click");
			
			if($("#isSameLocationAddr").is(':checked')) {
				$(".isSameBillingAddr").val(0);
			} else {
				$(".isSameBillingAddr").val(1);
			}		

			var comp = $("#editBillingDetail .billing_company_name").val();
			var addr = $("#editBillingDetail .billing_address").val();
			var apt = $("#editBillingDetail .billing_apt").val();
			var city = $("#editBillingDetail .billing_city").val();
			var region = $("#editBillingDetail .billing_region").val();
			var postcode = $("#editBillingDetail .billing_postcode").val();
			var notes = $("#editBillingDetail .billing_notes").val();

			var address = "";
			
			if(addr != "") {
				address += addr+", ";
			}
			if(apt) {
				address += apt+", ";
			}	
			if(city != "") {
				address += city+", ";
			}	
			if(postcode != "") {
				address += postcode+", ";
			}	
			if(region != "") {
				address += region;
			}	
			
			if(notes != "") {
				$("#billing_notes").html(notes);
				$(".billing_notes").val(notes);
			} else {
				$("#billing_notes").html('<a data-toggle="modal" data-target="#editBillingDetail" class="text-blue cursor-pointer">+ Add</a>');
			}		
			
			$("#billing_company_name").html(comp);
			$(".billing_company_name").val(comp);
			
			$('#billing_address').html(address);
			$('.billing_address').val(addr);
			$('.billing_apt').val(apt);
			$('.billing_city').val(city);
			$('.billing_region').val(region);
			$('.billing_postcode').val(postcode);
		})
		.on('core.form.invalid', function() {
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
			toastr.error("Sorry, looks like there are some errors detected, please try again.");
		});
	});

	</script>
	<!-- Modal Step Hide Show -->
	<script>
		$(window).on("scroll", function() {   
	        var topHeight = $('.my-custom-header').outerHeight(); 
	        if ($(this).scrollTop() > topHeight) {
				$('.my-custom-header').addClass("bg-white");
				$('.my-custom-header').addClass("border-bottom");  
				$('.hide-onscroll').css('opacity','0');
	        } else{ 
	          	$('.my-custom-header').removeClass("bg-white"); 
	          	$('.my-custom-header').removeClass("border-bottom");  
	          	$('.hide-onscroll').css('opacity','0.999');
	        }   
	    }); 
	</script>
@endsection