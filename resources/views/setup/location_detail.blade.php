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

    {{-- delete modal --}}
	<div class="modal fade p-0" id="deletelocmodal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			
			{{ Form::open(array('url' => '','id' => 'delloc')) }} 
				<input type="hidden" name="deleteID" id="deleteID" value="{{ $row->id }}">
				<div class="modal-content ">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center">Delete Location</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<div class="modal-body">
						<div class="container">
							<div class="row">
								<p>Are you sure you want to delete this Location?</p>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<button type="button" id="deleteloc" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Delete</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
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
                                class="col-12 px-8 my-4 content-header d-flex flex-wrap justify-content-between">
                                <div class="">
                                    <h3 class="font-weight-bolder">
                                        <a class="text-blue cursor-pointer" onclick="history.back()"><i
                                                class="text-dark fa fa-arrow-left"></i></a>
                                        {{$row->location_name}}
                                    </h3>
                                </div>
                                <div class="action-btn-div">
                                    <div class="dropdown dropdown-inline mr-2">
                                        <button type="button"
                                            class="btn btn-lg btn-white my-2 font-weight-bolder dropdown-toggle my-2"
                                            data-toggle="dropdown" aria-haspopup="true"
                                            aria-expanded="false">
                                            Option</button>
                                        <div
                                            class="dropdown-menu dropdown-menu-sm text-center dropdown-menu-right">
                                            <ul class="navi flex-column navi-hover py-2">
                                                <li class="navi-item">
                                                    <a href="{{ route('updateContactDetails',$row->id) }}" class="navi-link">
                                                        <span class="navi-text">Edit Location</span>
                                                    </a>
                                                </li>
                                                <li class="navi-item">
                                                    <a data-toggle="modal" data-target="#deletelocmodal" class="navi-link">
                                                        <span class="navi-text text-danger">Delete Location</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12 px-8">
                                <div class="row">
                                    <div class="col-12 col-sm-12 col-md-6">
                                        <div class="card shadow-sm border">
                                            <div class="card-header p-6">
                                                <div class="d-flex justify-content-between">
                                                    <h3>Contact details</h3>
                                                    <a href="{{ route('updateContactDetails',$row->id) }}">
                                                        <h5 class="text-blue cursor-pointer">Edit</h5>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <h5 class="font-weight-bolder">Location email
                                                        address</h5>
                                                    <h5 class="text-blue">
                                                       {{$row->location_email}}</h5>
                                                </div>
                                                <div class="">
                                                    <h5 class="font-weight-bolder">Location contact
                                                        number</h5>
                                                    <h5 class=" text-blue">{{$row->country_code}} {{$row->location_phone}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12 col-sm-12 col-md-6">
                                        <div class="card shadow-sm border">
                                            <div class="card-header p-6">
                                                <div class="d-flex justify-content-between">
                                                    <h3>Business types</h3>
                                                    <a href="{{ route('editbusinesstypes', $row->id) }}">
                                                        <h5 class="text-blue cursor-pointer">Edit</h5>
                                                    </a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <h5 class="font-weight-bolder">Main</h5>
                                                    @foreach ($main as $main_type)  
                                                    <h5 class="">{{$main_type->name}}</h5>
                                                    @endforeach
                                                </div>
                                                <div class="">
                                                    <h5 class="font-weight-bolder">Additional</h5>
													<h5 class="">
														@php
															$subbusiness = "";															
															foreach($sec as $sec_type)  {
																$subbusiness .= $sec_type->name.", ";
															}
														@endphp
														
														{{ ($subbusiness != '') ? substr(trim( $subbusiness ), 0, -1) : "-" }}
                                                    </h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="my-6 col-12 col-sm-12 col-md-12">
                                        <div class="card shadow-sm border">
                                            <div class="card-header p-6">
                                                <div class="d-flex justify-content-between">
                                                    <h3>Location</h3>
													
													@if($row->no_business_address == 0)
														<a href="{{ route('editlocation', $row->id) }}">
															<h5 class="text-blue cursor-pointer">Edit</h5>
														</a>
													@endif	
                                                </div>
                                            </div>
                                            <div class="card-body">
												@if($row->no_business_address == 0)
													<div class="mb-3">
														<h5 class="font-weight-bolder">Business address</h5>
														<h5 class="">
														@php
														$loc_arr = "";
														
														if($row->loc_address != "") {
															$loc_arr .= $row->loc_address.", ";
														}
														
														if($row->loc_city != "" && $row->loc_district != "") {
															$loc_arr .= $row->loc_city." (".$row->loc_district."), ";
														} else if($row->loc_city != "") {
															$loc_arr .= $row->loc_city.", ";
														} else if($row->loc_district != "") {
															$loc_arr .= " (".$row->loc_district."), ";
														}
														
														if($row->loc_postcode != "") {
															$loc_arr .= $row->loc_postcode.", ";
														}
														
														if($row->loc_region != "") {
															$loc_arr .= $row->loc_region.", ";
														}
														
														@endphp
															{{$loc_arr}}
														</h5>
													</div>
													<div class="map" id="map" style="width: 100%; height: 300px;"></div>
												@else 
													<div class="mb-3">
														<a href="{{ route('editlocation', $row->id) }}">
															<h5 class="text-blue cursor-pointer">Add +</h5>
														</a>
													</div>
												@endif	
                                            </div>
                                        </div>
                                    </div>

                                    <div class="my-6 col-12 col-sm-12 col-md-12">
                                        <div class="card shadow-sm border">
                                            <div class="card-header p-6">
                                                <div class="d-flex justify-content-between">
                                                    <div>
                                                        <h3 class="font-weight-bolder">Billing details for clients invoice</h3>
                                                        <h5>
                                                            These details will appear on the client’s
                                                            invoice for this location as well as the
                                                            information you’ve configured in your
                                                            Invoice Template settings.
                                                        </h5>
                                                    </div>
                                                    <a href="{{ route('editlocation', $row->id) }}"><h5 class="text-blue cursor-pointer">Edit</h5></a>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <h5 class="font-weight-bolder">Company details</h5>
                                                    <h4 class="">
                                                        {{$row->billing_company_name}}<br>
                                                        {{$row->location_address}}
                                                    </h4>
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
    </div>

@endsection
@section('scripts')

<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVdjXD_FnLKsQTNU8ki6Np_YlfwU0Oyec&v=3.exp&libraries=places"></script>
<script type="text/javascript">

	function initialize() 
	{
		var latlng = new google.maps.LatLng({{$row->location_latitude}},{{$row->location_longitude}});
		var map = new google.maps.Map(document.getElementById('map'), {
			center: latlng,
			zoom: 15
		});
		
		var marker = new google.maps.Marker({
			map: map,
			position: latlng,
			draggable: true,
			anchorPoint: new google.maps.Point(0, -29)
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);
	
	var WEBSITE_URL = "{{ url('') }}";
    $(document).on("click", '#deleteloc', function (e) {
			
		KTApp.blockPage();
		var form = $(this).parents('form:first');

		$.ajax({
			type: "POST",
			url: "{{ route('deletelocation') }}",
			dataType: 'json',
			data: form.serialize(),
			success: function (data) {
				KTApp.unblockPage();
				
				if(data.status == true)
				{					
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
					window.setTimeout(function() {
						window.location = WEBSITE_URL+"/partners/setup/location";
					}, 2000);
				} else {
					// Window.location.reload();
					
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
					window.setTimeout(function() {
						window.location.reload();
					}, 2000);
				}	
			}
		});
	});
</script>
@endsection