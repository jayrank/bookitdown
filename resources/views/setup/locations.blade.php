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
                                class="w-100 px-8 my-4 content-header d-flex flex-wrap justify-content-between">
                                <div class="">
                                    <h3 class="font-weight-bolder">
                                        <a class="text-blue cursor-pointer" onclick="window.location='{{ route('setup') }}'"><i
                                                class="text-dark fa fa-arrow-left"></i></a>
                                        Locations
                                    </h3>
                                    <h5 class="font-weight-bolder text-dark-50">Manage multiple
                                        locations for your business</h5>
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
                                                    <a href="{{ route('locationOrder') }}" class="navi-link">
                                                        <span class="navi-text">Edit Order</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <a href="{{ url('partners/setup/location/add/') }}"
                                        class="btn btn-lg btn-primary">Add a new Location</a>
                                </div>
                            </div>
							@foreach($data as $row)
                            <div class="px-8 col-12 col-sm-12">
                                <a class="text-blue" href="{{ url('partners/setup/location/'.$row->id) }}">
                                    <div class="card mb-3 shadow">
                                        <div class="row no-gutters">
                                            <div class=" col-12 col-sm-12 col-md-3">
                                                <div class="card-img p-3">
													@if($row->location_image != '')
														<img src="{{ url($row->location_image) }}" class="img-fluid rounded card-img" alt="{{$row->location_name}}">
													@else
														<!-- do design work here for blank design -->
														<img src="{{asset('assets/images/online-profile.png')}}" class="img-fluid rounded card-img" alt="">
													@endif
                                                </div>
                                            </div>
											
                                            <div class="col-12 col-sm-12 col-md-9">
                                                <div class="card-body">
                                                    <div
                                                        class="d-flex align-items-center justify-content-between flex-wrap">
                                                        <div>
                                                            <h3 class="card-title m-0 font-weight-bolder">{{$row->location_name}}</h3>
                                                            <p class="card-text text-muted">{{$row->location_address}}</p>
                                                        </div>
                                                        <div>
                                                            <i class="fa fa-chevron-right"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
										
                                        </div>
                                    </div>
                                </a>
                            </div>
							@endforeach
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
<!--end::Wrapper-->
</div>
<!--end::Page-->
</div>
@endsection
@section('scripts')

<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset ('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="{{ asset ('assets/js/pages/widgets.js') }}"></script>
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
@if(Session::has('error'))
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

	toastr.error('{{ Session::get('message') }}');
	return false;
</script>	
@endif	
@endsection