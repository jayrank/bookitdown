{{-- Extends layout --}}
@extends('frontend.layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<link href="{{ asset('frontend/css/toastr.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!-- Content start -->
<div class="container">
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10">
			<h4 class="fw-800 my-4">Consultation forms</h4>
			<div class="to-complete mb-5 consultFormList">
				<h5 class="font-weight-bolder mb-3">To complete <span class="badge badge-light bg-white rounded-circle">{{ count($ClientConsultationFormGet) }}</span></h5>
				<div class="list-group">
					@if(!empty($ClientConsultationFormGet))
						@foreach($ClientConsultationFormGet as $ClientConsultationFormGetData)
							<a href="{{ route('submitConsultationForm',['consultationId' => Crypt::encryptString($ClientConsultationFormGetData['id'])]) }}" class="d-flex justify-content-between align-items-center list-group-item list-group-item-action">
								<div class="staff-flexbox">
									<img src="{{ (isset($ClientConsultationFormGetData['location_image'])) ? $ClientConsultationFormGetData['location_image'] : asset('frontend/img/featured1.jpg') }}" style="width: 56px;height:56px;border-radius: 16px;" />
								</div>
								<div class="staff-flexbox"> 
									<h6 class="font-weight-bolder text-dark">{{ (isset($ClientConsultationFormGetData['name'])) ? ($ClientConsultationFormGetData['name']) : '' }}</h6>
									<h6 class="text-muted m-0">{{ (isset($ClientConsultationFormGetData['location_name'])) ? ($ClientConsultationFormGetData['location_name']) : '' }}</h6>
								</div> 
								<div class="staff-flexbox">
									<h6 class="text-muted mb-0">Complete before {{ (isset($ClientConsultationFormGetData['complete_before'])) ? date("d M Y",strtotime($ClientConsultationFormGetData['complete_before'])) : '' }} <i class="feather-chevron-right"></i></h6>
								</div>
							</a>
						@endforeach
					@else
					<h4 class="text-center">No data found.</h4>	
					@endif
				</div>
			</div>
			<div class="completed-list mb-5 consultFormList">
				<h5 class="font-weight-bolder mb-3">Completed <span class="badge badge-light bg-white rounded-circle">{{ count($CompletedClientConsultationFormGet) }}</span></h5>
				<div class="list-group">
				
					@if(!empty($CompletedClientConsultationFormGet))
						@foreach($CompletedClientConsultationFormGet as $ClientConsultationFormGetData)
							<a href="{{ route('viewConsultationForm',['consultationId' => Crypt::encryptString($ClientConsultationFormGetData['id'])]) }}" class="d-flex justify-content-between align-items-center list-group-item list-group-item-action">
								<div class="staff-flexbox">
									<img src="{{ (isset($ClientConsultationFormGetData['location_image'])) ? $ClientConsultationFormGetData['location_image'] : asset('frontend/img/featured1.jpg') }}" style="width: 56px;height:56px;border-radius: 16px;" />
								</div>
								<div class="staff-flexbox"> 
									<h6 class="font-weight-bolder text-dark">{{ (isset($ClientConsultationFormGetData['name'])) ? ($ClientConsultationFormGetData['name']) : '' }}</h6>
									<h6 class="text-muted m-0">{{ (isset($ClientConsultationFormGetData['location_name'])) ? ($ClientConsultationFormGetData['location_name']) : '' }}</h6>
								</div> 
								<div class="staff-flexbox">
									<h6 class="text-muted mb-0">Complete before {{ (isset($ClientConsultationFormGetData['complete_before'])) ? date("d M Y",strtotime($ClientConsultationFormGetData['complete_before'])) : '' }} <i class="feather-chevron-right"></i></h6>
								</div>
							</a>
						@endforeach
					@else
					<h4 class="text-center">No data found.</h4>	
					@endif
					
				</div>
			</div>
		</div>
		<div class="col-md-1"></div>
	</div>
</div>
<!-- END: Content start -->    
@endsection
@section('scripts')
<script src="{{ asset('frontend/js/toastr.min.js') }}"></script>
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