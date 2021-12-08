{{-- Extends layout --}}
@extends('frontend.layouts.fullView')

{{-- CSS Section --}}  
@section('innercss')
	<style type="text/css">
		body{
			padding: 0;
			margin: 0;
		}
	</style>
@endsection
@section('content')  
 	<div id="myModal" class="modal fade show" role="dialog" style="padding-right: 17px; display: block;">
	  	<div class="modal-dialog">
		    <!-- Modal content-->
		    <div class="modal-content p-3">
		      	<div class="modal-header border-0">
		        	<h3 class="font-weight-bolder m-0">You can't cancel this appointment</h3>
		        	<a href="{{ route('myAppointments') }}" class="close" data-dismiss="modal"><i class="feather-x"></i></a>
		      	</div>
		      	<div class="modal-body">
	                <div class="list-group">
						<div class="d-flex justify-content-between align-items-center list-group-item list-group-item-action mb-4">
							<div class="staff-flexbox">
								<h6 class="font-weight-bolder text-dark"> {{ date("F, d M Y", strtotime($getAppintmentServices->appointment_date))." at ".date("h:i a", strtotime($getAppintmentServices->start_time)) }}</h6>
								<h6 class="text-muted m-0">{{ $locationInfo->location_name }}</h6>
							</div>
							<div class="staff-flexbox">
								@if($locationInfo->location_name != "")
									<img src="{{ $locationInfo->location_image }}" style="width: 56px;height:56px;border-radius: 16px;" />
								@else
									<img src="{{ asset('frontend/img/featured1.jpg') }}" style="width: 56px;height:56px;border-radius: 16px;" />
								@endif
							</div>
						</div>
					</div>
					<h6 class="mb-4">This appointment cannot be canceled online, because you're trying to cancel within <b>{{ $beforeHours }}</b> of your appointment time.</h6>
					<h6 class="mb-4">You can call {{ $locationInfo->location_name }} directly to proceed. <a href="tel:{{ "+".$locationInfo->country_code.''.$locationInfo->location_phone }}">{{ "+".$locationInfo->country_code." ".$locationInfo->location_phone }}</a></h6>
					<a href="{{ route('myAppointments') }}" class="btn btn-block btn-lg shadow-lg">Close</a>
	      		</div>
		    </div> 
	  	</div>
	</div> 	 
@endsection

{{-- Scripts Section --}}
@section('scripts') 
	<script>
	 	$(window).load(function(){    
   			$('#myModal').modal('show');
		}); 
	</script>
@endsection	 