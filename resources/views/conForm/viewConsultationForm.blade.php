{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}  
@section('innercss')
<link href="{{ asset('frontend/css/toastr.min.css') }}" rel="stylesheet" type="text/css" />
<style type="text/css">
	.card{
		border-radius: 8px;
	}
</style>
@endsection
@section('content')
<!-- Content start -->
<div class="container">
	<div class="row">
		<div class="col-md-1"></div>
		<div class="col-md-10">
			<div class="d-flex justify-content-between align-items-center my-6">
				<div>
					<h1 class="fw-800 mb-2"><a href="javascript:;" class="mr-2 text-dark" onclick="window.history.back();"><i class="fa fa-arrow-left text-dark"></i></a>{{ ($LocationInfo) ? $LocationInfo['location_name'] : '' }}</h1>
					<h6>Last edited on {{ ($ClientConsultationFormGet) ? date("d M Y",strtotime($ClientConsultationFormGet['updated_at'])) : '' }}</h6>
				</div>
				<div class="dropdown">
					<a href="#" class="dropdown-toggle d-block btn btn-primary h6 mb-0" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Options</a>
					<div class="dropdown-menu dropdown-menu-right" aria-labelledby="dropdownMenuButton">
						<a class="dropdown-item" href="{{ route('viewClient',['id' => ($ClientConsultationFormGet) ? $ClientConsultationFormGet['client_id'] : '']) }}">View Profile</a>
						<a class="dropdown-item printInvoice" href="javascript:;">Print</a> 
					</div>
				</div>
			</div>
			<div class="card mb-5">
				<div class="card-body border-0">
					<h3 class="font-weight-bolder mb-8">Personal Information</h3>
					<div class="row">
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_first_name'] == 1)
							<div class="col-md-6 mb-4">
								<h5 class="font-weight-bolder">First name</h5>
								<h5 class="text-muted">{{ ($ClientConsultationFormGet['client_first_name']) ? $ClientConsultationFormGet['client_first_name'] : '' }}</h5>
							</div>
						@endif
						
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_last_name'] == 1)
						<div class="col-md-6 mb-4">
							<h5 class="font-weight-bolder">Last name</h5>
							<h5 class="text-muted">{{ ($ClientConsultationFormGet['client_last_name']) ? $ClientConsultationFormGet['client_last_name'] : '' }}</h5>
						</div> 
						@endif
						
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_email'] == 1)
						<div class="col-md-6 mb-4">
							<h5 class="font-weight-bolder">Email</h5>
							<h5 class="text-muted">{{ ($ClientConsultationFormGet['client_email']) ? $ClientConsultationFormGet['client_email'] : '' }}</h5>
						</div>
						@endif		
								
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_birthday'] == 1)		
						<div class="col-md-6 mb-4">
							<h5 class="font-weight-bolder">Birthday</h5>
							<h5 class="text-muted">{{ ($ClientConsultationFormGet['client_birthday']) ? date("Y-m-d",strtotime($ClientConsultationFormGet['client_birthday'])) : '' }}</h5>
						</div>	
						@endif				
						
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_mobile'] == 1)
						<div class="col-md-6 mb-4">
							<h5 class="font-weight-bolder">Mobile number</h5>
							<h5 class="text-muted">+{{ ($ClientConsultationFormGet['country_code']) ? $ClientConsultationFormGet['country_code'] : '' }} {{ ($ClientConsultationFormGet['client_mobile']) ? $ClientConsultationFormGet['client_mobile'] : '' }}</h5>
						</div>	
						@endif		

						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_gender'] == 1)	
						<div class="col-md-6 mb-4">
							<h5 class="font-weight-bolder">Gender</h5>
							<h5 class="text-muted">{{ ($ClientConsultationFormGet['client_gender']) ? $ClientConsultationFormGet['client_gender'] : '' }}</h5>
						</div>	
						@endif		
						
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_address'] == 1)	
						<div class="col-md-6 mb-4">
							<h5 class="font-weight-bolder">Address</h5>
							<h5 class="text-muted">{{ ($ClientConsultationFormGet['client_address']) ? $ClientConsultationFormGet['client_address'] : '' }}</h5>
						</div>	
						@endif		
						<!--div class="col-md-12 d-md-block d-sm-none mt-3"></div-->
						
					</div>
					<hr>
					@if(!empty($ClientConsultationFormField))
						@php 
							$i = 1; 
						@endphp
						@foreach($ClientConsultationFormField as $ClientConsultationFormFieldData)	
							@php 
								$i++; 
							@endphp
							
							<h3 class="font-weight-bolder mb-8">{{ ($ClientConsultationFormFieldData) ? $ClientConsultationFormFieldData['section_title'] : '' }}</h3>
							<p>{{ ($ClientConsultationFormFieldData) ? $ClientConsultationFormFieldData['section_description'] : '' }}</p>
							<div class="row">
								@if(!empty($ClientConsultationFormGet['client_consultation_fields']))
									@foreach($ClientConsultationFormGet['client_consultation_fields'] as $clientConsultationFieldsData)	
										@if($clientConsultationFieldsData['section_id'] == $ClientConsultationFormFieldData['section_id'])
										<div class="col-md-6 mb-4">
											<h5 class="font-weight-bolder">{{ $clientConsultationFieldsData['question'] }}</h5>
											<h5 class="text-muted">{{ $clientConsultationFieldsData['client_answer'] }}</h5>
										</div>
										@endif
									@endforeach
								@endif
							</div>
							<hr>
						@endforeach	
					@endif
					
					@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_signature'] == 1)
					<h3 class="font-weight-bolder mb-8">Signatures</h3>
					<div class="row">
						<div class="col-md-6 mb-4">
							<h5 class="font-weight-bolder">Full name</h5>
							<h5 class="text-muted">{{ ($ClientConsultationFormGet['signature_name']) ? $ClientConsultationFormGet['signature_name'] : '' }}</h5>
						</div>
						<div class="col-md-6 mb-4">
							<h5 class="font-weight-bolder">I confirm the answers I've given are true and correct to the best of my knowledge.</h5>
							<h5 class="text-muted">Confirmed by {{ ($ClientConsultationFormGet['signature_name']) ? $ClientConsultationFormGet['signature_name'] : '' }}</h5>
						</div> 
					</div>
					@endif
				</div>
			</div> 
		</div>
		<div class="col-md-1"></div>
	</div>
</div>

@csrf
<input type="hidden" name="consultationFormId" id="consultationFormId" value="{{ ($ClientConsultationFormGet) ? Crypt::encryptString($ClientConsultationFormGet['id']) : '' }}">

<div class="printConsultationForm" id="printConsultationForm" value="">
</div>

<!-- END: Content start -->
@endsection
@section('scripts')
<script src="{{ asset('js/jquery-printme.js') }}"></script>
<script src="{{ asset('frontend/js/toastr.min.js') }}"></script>
<script>
	$(document).on('click','.printInvoice',function(){
		var consultationFormId = $("#consultationFormId").val();
		var csrf      = $("input[name=_token]").val();
		
		$.ajax({
			type: "POST",
			url: "{{ route('printClientConsultationForm') }}",
			data: {consultationFormId : consultationFormId,_token : csrf},
			success: function (response) 
			{
				$(".printConsultationForm").html(response);
				$.print("#printConsultationForm");
				setInterval(function(){
					$(".printConsultationForm").html('');
				}, 1000);
			}
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