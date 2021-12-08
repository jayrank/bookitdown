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
			<form action="{{ route('updateContactDetails', $locationData->id) }}" method="POST" enctype="multipart/form-data" id="updateContactDetailsFrm">
				@csrf
				<div class="my-custom-header">
					<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
						<span class="d-flex">
							<p class="cursor-pointer m-0 px-6" onclick="history.back();"><i class="text-dark fa fa-times icon-lg"></i>
							</p>
						</span>
						<span class="text-center">
							<h1 class="font-weight-bolder page-title">About your business</h1>
						</span>
						
						<div>
							<button class="btn btn-lg btn-primary next-step" type="submit" id="updateContactDetails" >Save</button>
						</div>
					</div>
				</div>
			
				<div class="my-custom-body">
					<div class="container-fluid">
						
					</div>
				</div>
			</form>
		</div>
	</div> -->

	<div class="fullscreen-modal p-0">
		<form action="{{ route('updateContactDetails', $locationData->id) }}" method="POST" enctype="multipart/form-data" id="updateContactDetailsFrm">
			@csrf
	        <div class="my-custom-header text-dark"> 
	            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
		            <a type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="history.back();" style="display: flex; justify-content: flex-start; align-items: center;top:unset;left: 0;opacity: 1;font-size: 1.75rem;">
		                <span aria-hidden="true" class="la la-times"></span>
		            </a>
		            <div style="flex-grow: 1;"><h2 class="font-weight-bolder title-hide">About your business</h2></div>
		            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
		                <button type="submit" class="btn btn-lg btn-primary" id="updateContactDetails" name="updateContactDetails">Save</button>
		            </div>
		        </div>
	        </div> 
            <h1 class="font-weight-bolder mb-5 text-center text-dark hide-onscroll" style="flex-grow: 1;">About your business</h1>  
            <div class="modal-content">  
                <div class="my-custom-body">
					<div class="container">
						<div class="row justify-content-center">  
							<div class="col-12 col-md-9 p-2 pr-4">
								<div class="p-4">
									<input type="hidden" name="editlocationID" value="{{ $locationData->id }}">
									<div class="">
										<div class="card">
											<div class="card-header">
												<h4>Basic</h4>
											</div>
											<div class="card-body">
												<div class="form-group">
													<label class="font-weight-bolder">Location name</label>
													<input type="text" name="location_name" id="location_name" value="{{$locationData->location_name}}" class="form-control"  required>
												</div>
												<input type="hidden" name="country_code" id="country_code" value="{{$locationData->country_code}}">
												<div class="form-group telephoneclass">
													<label for="location_phone">Telephone</label>
													<input type="tel" class="form-control" placeholder="" id="location_phone" name="location_phone" value="{{$locationData->location_phone}}" required>
												</div>
												<div class="form-group">
													<label class="font-weight-bolder">Location email address</label>
													<input type="email" name="location_email" id="location_email" value="{{$locationData->location_email}}" class="form-control" required>
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

	@endsection
	@section('scripts')
	<!--end::Page Scripts-->
	<script src="{{ asset('js/intlTelInput.min.js') }}"></script>
	<script src="{{ asset('js/editLocation.js') }}"></script>
	<script>
	var limit = 3;
	$('input.multi_checkbox').on('click', function (evt) {
		if ($('.multi_checkbox:checked').length > limit) {
			this.checked = false;
		}
	});
	</script>
	<script type="text/javascript">
	
	var WEBSITE_URL = "{{ url('') }}";
	var location_phone = window.intlTelInput(document.querySelector("#location_phone"), {
	  	separateDialCode: true,
	  	preferredCountries:["in"],
	  	hiddenInput: "full",
	  	utilsScript: "{{ asset('js/utils.js') }}"
	});
	
	$(document).on("click keypress", '.telephoneclass .iti__country' , function(e) {
		e.preventDefault();
		if($(this).hasClass("iti__active")){
			var country_code = $(this).attr("data-dial-code");
			$("#country_code").val(country_code);
		} else {
			var country_code = $(this).attr("data-dial-code");
			$("#country_code").val(country_code);
		}
	});
	
	$('.iti__country').each(function(){
		if($(this).attr("data-dial-code") == $("#country_code").val())
		{
			var countryCode = $(this).attr('data-country-code');
			if($('#country_code').val() == 1)
			{
				countryCode = "ca"
			}
			console.log(countryCode);
			$('.telephoneclass').find('.iti__selected-flag').children('.iti__flag').addClass('iti__'+countryCode);
		}
	});

	$(window).on("scroll", function() {   
        var topHeight = $('.my-custom-header').outerHeight(); 
        if ($(this).scrollTop() > topHeight) {
			$('.my-custom-header').addClass("bg-white");
			$('.my-custom-header').addClass("border-bottom");  
        } else{ 
          	$('.my-custom-header').removeClass("bg-white"); 
          	$('.my-custom-header').removeClass("border-bottom");  
        }   
    }); 
	</script>
@endsection