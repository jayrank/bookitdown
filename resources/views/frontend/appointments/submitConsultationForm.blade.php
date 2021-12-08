{{-- Extends layout --}}
@extends('frontend.layouts.fullView')

{{-- CSS Section --}}  
@section('innercss')
<link href="{{ asset('frontend/css/toastr.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('frontend/css/select2.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!-- Content start -->
<div class="fullscreen-modal" id="consultFormPreview" tabindex="-1" role="dialog" aria-hidden="true">

	<form method="POST" action="{{ route('saveConsultationForm') }}" id="saveConsultationForm">
	@csrf
	<input type="hidden" name="client_consultation_form_id" value="{{ ($ClientConsultationFormGet['id']) ? $ClientConsultationFormGet['id'] : '' }}">

	<div class="my-custom-header bg-transparent text-dark">
		<div class="p-4 d-flex justify-content-between align-items-center">
			<span class="d-flex">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.history.back();">
					<span aria-hidden="true" class="feather-x"></span>
				</button>
				<p class="h6 cursor-pointer mb-0 text-primary previous" onclick="nextPrev(-1)"><i class="border-left mx-4"></i>Previous</p>
			</span> 
			<button class="btn btn-lg btn-dark px-4 py-2 next-step" type="button" onclick="validateForm(1)">Next Step</button>
		</div>
	</div> 
	<div class="border-0 mb-5 text-center firstStep"> 
		<h6 class="modal-title text-muted">Consultation form - Step <span class="steps">1</span> of {{ $TotalSteps }}</h6> 
		<h3 class="modal-title fw-800 step-main-title">Cilent name</h3>
		<h6 class="modal-title step-sub-title">Client comments</h6>  
	</div>
	<div class="modal-dialog" role="document">
		
		<div class="modal-content">  
			<div class="my-custom-body">
			
				<div class="cform-step">
					<div class="card px-0 py-4 border-0" style="box-shadow: 0 4px 8px 0 rgb(16 25 40 / 10%)">  
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_first_name'] == 1)
						<div class="form-group">
							<h6 class="font-weight-bolder">First name</h6>
							<input type="text" class="form-control inputFormData client_first_name" placeholder="First name" name="client_first_name" id="client_first_name" value="{{ ($ClientConsultationFormGet['client_first_name']) ? $ClientConsultationFormGet['client_first_name'] : '' }}" autocomplete="off" data-requiredEle="required" data-elementType="text" data-uniqueClassName="client_first_name">
						</div>
						@endif
						
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_last_name'] == 1)
						<div class="form-group">
							<h6 class="font-weight-bolder">Last name</h6>
							<input type="text" class="form-control inputFormData client_last_name" placeholder="Last name" name="client_last_name" id="client_last_name" value="{{ ($ClientConsultationFormGet['client_last_name']) ? $ClientConsultationFormGet['client_last_name'] : '' }}" autocomplete="off" data-requiredEle="required" data-elementType="text" data-uniqueClassName="client_last_name">
						</div>
						@endif
						
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_email'] == 1)
						<div class="form-group">
							<h6 class="font-weight-bolder">Email</h6>
							<input type="email" class="form-control inputFormData client_email" placeholder="abc@gmail.com" name="client_email" id="client_email" value="{{ ($ClientConsultationFormGet['client_email']) ? $ClientConsultationFormGet['client_email'] : '' }}" autocomplete="off" data-requiredEle="required" data-elementType="text" data-uniqueClassName="client_email">
						</div>
						@endif
						
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_birthday'] == 1)
						<div class="form-group">
							<h6 class="font-weight-bolder">Birthday</h6>
							<input type="date" class="form-control inputFormData client_birthday" placeholder="dd-M-yyyy" name="client_birthday" id="client_birthday" max="{{ date('Y-m-d', strtotime('-1 day')) }}" value="{{ ($ClientConsultationFormGet['client_birthday']) ? date("Y-m-d",strtotime($ClientConsultationFormGet['client_birthday'])) : '' }}" autocomplete="off" data-requiredEle="required" data-elementType="date" data-uniqueClassName="client_birthday" data-maxDateEle="maxDate"><!--   -->
						</div>
						@endif
						
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_mobile'] == 1)
						<div class="form-group">
							<h6 class="font-weight-bolder" for="country_code">Country Code</h6>
							<select class="form-control select2" name="country_code" id="country_code">
								@if($Country)
									@foreach($Country as $CountryData)
										<option value="{{ $CountryData['phonecode'] }}" @if($ClientConsultationFormGet['country_code'] == $CountryData['phonecode']) selected @endif>{{ $CountryData['name'] }} +{{ $CountryData['phonecode'] }}</option>
									@endforeach
								@endif	
							</select>
						</div>	
						<div class="form-group">
							<h6 class="font-weight-bolder" for="client_mobile">Mobile number</h6>
							<input type="tel" class="form-control inputFormData client_mobile" placeholder="" name="client_mobile" id="client_mobile" value="{{ ($ClientConsultationFormGet['client_mobile']) ? $ClientConsultationFormGet['client_mobile'] : '' }}" autocomplete="off"  data-requiredEle="required" data-elementType="text" data-uniqueClassName="client_mobile" onKeyPress="return validQty(event,this.value);"> 
						</div>
						@endif
						
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_gender'] == 1)
						<div class="form-group">
							<h6 class="font-weight-bolder">Gender</h6>
							<select class="form-control inputFormData client_gender" name="client_gender" id="client_gender" autocomplete="off" data-requiredEle="required" data-elementType="text" data-uniqueClassName="client_gender">
								<option value="">Please select</option>
								<option value="Male" @if($ClientConsultationFormGet['client_gender'] == 'Male') selected @endif>Male</option>
								<option value="Female" @if($ClientConsultationFormGet['client_gender'] == 'Female') selected @endif>Female</option>
								<option value="Other" @if($ClientConsultationFormGet['client_gender'] == 'Other') selected @endif>Other</option>
								<option value="I dont want to share" @if($ClientConsultationFormGet['client_gender'] == 'I dont want to share') selected @endif>I don't want to share</option>
							</select>
						</div>
						@endif
						
						@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_address'] == 1)
						<div class="form-group">
							<h6 class="font-weight-bolder">Address</h6>
							<textarea class="form-control inputFormData client_address" placeholder="" name="client_address" id="client_address" rows="4" autocomplete="off" data-requiredEle="required" data-elementType="text" data-uniqueClassName="client_address">{{ ($ClientConsultationFormGet['client_address']) ? $ClientConsultationFormGet['client_address'] : '' }}</textarea>
						</div>  
						@endif
					</div> 
				</div>
				
				@if(!empty($ClientConsultationFormField))
					@php 
						$i = 1; 
						$j = 0;
					@endphp
					@foreach($ClientConsultationFormField as $ClientConsultationFormFieldData)	
						@php 
							$i++; 
						@endphp
						<div class="cform-step subSteps">
						
							<div class="border-0 d-block mb-5 text-center"> 
								<h6 class="modal-title text-muted">Consultation form - Step <span>{{ $i }}</span> of {{ $TotalSteps }}</h6> 
								<h3 class="modal-title fw-800 step-main-title">{{ $ClientConsultationFormFieldData['section_title'] }}</h3>
								<h6 class="modal-title step-sub-title">{{ $ClientConsultationFormFieldData['section_description'] }}</h6>  
							</div>
							
							<div class="card px-0 py-4 border-0" style="box-shadow: 0 4px 8px 0 rgb(16 25 40 / 10%)">  
								@if(!empty($ClientConsultationFormGet['client_consultation_fields']))
									@foreach($ClientConsultationFormGet['client_consultation_fields'] as $clientConsultationFieldsData)	
								
										@php 
											$j++; 
										@endphp
								
										@if($clientConsultationFieldsData['section_id'] == $ClientConsultationFormFieldData['section_id'])
											
											@if($clientConsultationFieldsData['field_type'] == 'shortAnswer')
												<div class="form-group">
													<h6 class="font-weight-bolder">{{ $clientConsultationFieldsData['question'] }}</h6>
													
													<input type="hidden" class="form-control" name="client_consultation_form_field_id[]" value="{{ $clientConsultationFieldsData['id'] }}">
													
													<input type="text" class="form-control inputFormData client_answer{{ $j }}" name="client_answer[{{ $clientConsultationFieldsData['id'] }}][]" id="client_answer{{ $j }}" @if($clientConsultationFieldsData['is_required'] == 1) data-requiredEle="required" @else data-requiredEle="notrequired" @endif value="{{ $clientConsultationFieldsData['client_answer'] }}" autocomplete="off" data-elementType="text" data-uniqueClassName="client_answer{{ $j }}">
												</div>
											@elseif($clientConsultationFieldsData['field_type'] == 'longAnswer')
												<div class="form-group">
													<input type="hidden" class="form-control" name="client_consultation_form_field_id[]" value="{{ $clientConsultationFieldsData['id'] }}">
												
													<h6 class="font-weight-bolder">{{ $clientConsultationFieldsData['question'] }}</h6>
													<textarea class="form-control inputFormData client_answer{{ $j }}" rows="5" name="client_answer[{{ $clientConsultationFieldsData['id'] }}][]" id="client_answer{{ $j }}" @if($clientConsultationFieldsData['is_required'] == 1) data-requiredEle="required" @else data-requiredEle="notrequired" @endif autocomplete="off" data-elementType="text" data-uniqueClassName="client_answer{{ $j }}">{{ $clientConsultationFieldsData['client_answer'] }}</textarea>
												</div> 
											@elseif($clientConsultationFieldsData['field_type'] == 'singleAnswer')
												<div class="form-group">
													<input type="hidden" class="form-control" name="client_consultation_form_field_id[]" value="{{ $clientConsultationFieldsData['id'] }}">
												
													<h6 class="font-weight-bolder mb-4">{{ $clientConsultationFieldsData['question'] }}</h6>  
													
													@php 
														$radioAnswers = (count(json_decode($clientConsultationFieldsData['field_values'])) > 0) ? json_decode($clientConsultationFieldsData['field_values']) : array();
													@endphp
													
													@if(!empty($radioAnswers))
														@foreach($radioAnswers as $key => $radioAnswersData)
														<div class="mb-3">
															<label for="client_answer{{ $j }}_{{ $key }}" class="radio-custom-label">
															<input id="client_answer{{ $j }}_{{ $key }}" class="radio-custom mr-2 inputFormData client_answer{{ $j }}" name="client_answer[{{ $clientConsultationFieldsData['id'] }}][]" type="radio" @if($clientConsultationFieldsData['is_required'] == 1) data-requiredEle="required" @else data-requiredEle="notrequired" @endif value="{{ $radioAnswersData }}" autocomplete="off" data-elementType="radio" data-uniqueClassName="client_answer{{ $j }}" @if($radioAnswersData == $clientConsultationFieldsData['client_answer']) checked @endif>{{ $radioAnswersData }}
															</label>
														</div>
														@endforeach
													@endif
												</div> 
											@elseif($clientConsultationFieldsData['field_type'] == 'singleCheckbox')
												<div class="form-group">
													<input type="hidden" class="form-control" name="client_consultation_form_field_id[]" value="{{ $clientConsultationFieldsData['id'] }}">
												
													<div>
														<input id="client_answer{{ $j }}" class="checkbox-custom inputFormData client_answer{{ $j }}" name="client_answer[{{ $clientConsultationFieldsData['id'] }}][]" type="checkbox" @if($clientConsultationFieldsData['is_required'] == 1) data-requiredEle="required" @else data-requiredEle="notrequired" @endif value="Yes" autocomplete="off" data-elementType="checkbox" data-uniqueClassName="client_answer{{ $j }}" @if($clientConsultationFieldsData['client_answer'] == 'Yes') checked @endif>
														<label for="client_answer{{ $j }}" class="checkbox-custom-label">
															{{ $clientConsultationFieldsData['question'] }}
														</label>
													</div>
												</div> 
											@elseif($clientConsultationFieldsData['field_type'] == 'multipleCheckbox')
												<div class="form-group">
													<input type="hidden" class="form-control" name="client_consultation_form_field_id[]" value="{{ $clientConsultationFieldsData['id'] }}">
													
													<h6 class="font-weight-bolder mb-4">{{ $clientConsultationFieldsData['question'] }}</h6>  
													
													@php 
														$clientAnser = (!empty($clientConsultationFieldsData['client_answer'])) ? explode(",",$clientConsultationFieldsData['client_answer']) : array();
													
														$multiChoiceAnswers = (count(json_decode($clientConsultationFieldsData['field_values'])) > 0) ? json_decode($clientConsultationFieldsData['field_values']) : array();
													@endphp
													
													@if(!empty($multiChoiceAnswers))
														@foreach($multiChoiceAnswers as $key => $multiChoiceAnswersData)
													<div class="mb-3">
														<input id="client_answer{{ $j }}_{{ $key }}" class="checkbox-custom inputFormData client_answer{{ $j }}" name="client_answer[{{ $clientConsultationFieldsData['id'] }}][]" type="checkbox" value="{{ $multiChoiceAnswersData }}" autocomplete="off" @if($clientConsultationFieldsData['is_required'] == 1) data-requiredEle="required" @else data-requiredEle="notrequired" @endif data-elementType="checkbox" data-uniqueClassName="client_answer{{ $j }}" @if(in_array($multiChoiceAnswersData,$clientAnser)) checked @endif>
														<label for="client_answer{{ $j }}_{{ $key }}" class="checkbox-custom-label">{{ $multiChoiceAnswersData }}</label>
													</div>
														@endforeach	
													@endif
												</div> 
											@elseif($clientConsultationFieldsData['field_type'] == 'dropdown')
												<div class="form-group">
													<input type="hidden" class="form-control" name="client_consultation_form_field_id[]" value="{{ $clientConsultationFieldsData['id'] }}">
												
													<h6 class="font-weight-bolder">{{ $clientConsultationFieldsData['question'] }}</h6> 
													<select class="form-control inputFormData client_answer{{ $j }}" name="client_answer[{{ $clientConsultationFieldsData['id'] }}][]" id="client_answer{{ $j }}" @if($clientConsultationFieldsData['is_required'] == 1) data-requiredEle="required" @else data-requiredEle="notrequired" @endif autocomplete="off" data-elementType="text" data-uniqueClassName="client_answer{{ $j }}">
													
													@php 
														$dropdownAnswers = (count(json_decode($clientConsultationFieldsData['field_values'])) > 0) ? json_decode($clientConsultationFieldsData['field_values']) : array();
													@endphp
													<option value="">Please select</option>
													@if(!empty($dropdownAnswers))
														@foreach($dropdownAnswers as $key => $dropdownAnswersData)	
														<option value="{{ $dropdownAnswersData }}" @if($clientConsultationFieldsData['client_answer'] == $dropdownAnswersData) selected @endif>{{ $dropdownAnswersData }}</option>
														@endforeach	
													@endif
														
													</select>
												</div>
											@elseif($clientConsultationFieldsData['field_type'] == 'yesOrNo')
												<div class="form-group">
													<input type="hidden" class="form-control" name="client_consultation_form_field_id[]" value="{{ $clientConsultationFieldsData['id'] }}">
												
													<h6 class="font-weight-bolder">{{ $clientConsultationFieldsData['question'] }}</h6>
													
													<div class="radio-switch">
														<input type="radio" class="inputFormData client_answer{{ $j }}" name="client_answer[{{ $clientConsultationFieldsData['id'] }}][]" id="client_answer{{ $j }}Yes" value="Yes" @if($clientConsultationFieldsData['is_required'] == 1) data-requiredEle="required" @else data-requiredEle="notrequired" @endif autocomplete="off" data-elementType="radio" data-uniqueClassName="client_answer{{ $j }}" @if($clientConsultationFieldsData['client_answer'] == 'Yes') checked @endif>
														<label for="client_answer{{ $j }}Yes">
															Yes
															<div class="checkCircle">
																<span><svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><circle fill="#027AFF" cx="10" cy="10" r="10"></circle><path d="M13.817 6.265a.666.666 0 01.91-.148.62.62 0 01.202.801l-.05.08-4.953 6.737a.666.666 0 01-.88.168l-.075-.054-3.742-3.092a.618.618 0 01-.073-.89.667.667 0 01.842-.126l.077.055 3.2 2.644 4.542-6.175z" fill="#FFF" fill-rule="nonzero"></path></g></svg></span>
															</div>
														</label>
														<input type="radio" class="inputFormData client_answer{{ $j }}" name="client_answer[{{ $clientConsultationFieldsData['id'] }}][]" id="client_answer{{ $j }}No" value="No" @if($clientConsultationFieldsData['is_required'] == 1) data-requiredEle="required" @else data-requiredEle="notrequired" @endif @if($clientConsultationFieldsData['client_answer'] == 'No') checked @endif>
														<label for="client_answer{{ $j }}No" autocomplete="off" data-elementType="radio" data-uniqueClassName="client_answer{{ $j }}">
															No
															<div class="checkCircle">
																<span><svg viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><circle fill="#027AFF" cx="10" cy="10" r="10"></circle><path d="M13.817 6.265a.666.666 0 01.91-.148.62.62 0 01.202.801l-.05.08-4.953 6.737a.666.666 0 01-.88.168l-.075-.054-3.742-3.092a.618.618 0 01-.073-.89.667.667 0 01.842-.126l.077.055 3.2 2.644 4.542-6.175z" fill="#FFF" fill-rule="nonzero"></path></g></svg></span>
															</div>
														</label>
													</div>
												</div>
											@elseif($clientConsultationFieldsData['field_type'] == 'des')
												<div class="form-group">
													<input type="hidden" class="form-control" name="client_consultation_form_field_id[]" value="{{ $clientConsultationFieldsData['id'] }}">
												
													<textarea class="form-control inputFormData client_answer{{ $j }}" rows="5" name="client_answer[{{ $clientConsultationFieldsData['id'] }}][]" id="client_answer{{ $j }}" @if($clientConsultationFieldsData['is_required'] == 1) data-requiredEle="required" @else data-requiredEle="notrequired" @endif placeholder="Please write something" autocomplete="off" data-elementType="text" data-uniqueClassName="client_answer{{ $j }}">{{ ($clientConsultationFieldsData['client_answer']) ? $clientConsultationFieldsData['client_answer'] : '' }}</textarea>
												</div> 
											@endif
										@endif
									@endforeach
								@endif
							</div>
						</div>
					@endforeach
				@endif
				@php $i = 0; @endphp
				@if(!empty($ClientConsultationFormGet) && $ClientConsultationFormGet['is_signature'] == 1)
				<div class="cform-step">
					@php $i++; @endphp
					<div class="border-0 d-block mb-5 text-center"> 
						<h6 class="modal-title text-muted">Consultation form - Step <span>{{ $i }}</span> of {{ $TotalSteps }}</h6> 
						<h3 class="modal-title fw-800 step-main-title">Signatures</h3>
					</div>
				
					<div class="card px-0 py-4 border-0" style="box-shadow: 0 4px 8px 0 rgb(16 25 40 / 10%)"> 
						<div class="form-group">
							<h6 class="font-weight-bolder">Full Name</h6>
							<input type="text" class="form-control inputFormData signature_name" placeholder="Signatures" name="signature_name" id="signature_name" data-requiredEle="required" data-elementType="text" data-uniqueClassName="signature_name" value="{{ ($ClientConsultationFormGet['signature_name']) ? $ClientConsultationFormGet['signature_name'] : '' }}">
						</div>
						<div class="form-group"> 
							 <div>
								<input id="confirm" class="checkbox-custom inputFormData confirm" name="confirm" type="checkbox" checked data-requiredEle="required" data-elementType="checkbox" data-uniqueClassName="confirm">
								<label for="confirm" class="checkbox-custom-label">I confirm the answers I've given are true and correct to the best of my knowledge.</label>
							</div>
						</div>  
					</div>
				</div>
				@endif
			</div> 
		</div>
		
	</div>
	
	</form>
</div>
<!-- END: Content start -->    
@endsection
@section('scripts')
<script type="text/javascript" src="{{asset('frontend/js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('frontend/js/select2.min.js') }}"></script>
<script src="{{ asset('frontend/js/toastr.min.js') }}"></script>
<script>
	// $( document ).ready(function() {
		// $('#country_code').select2({
			// selectOnClose: true
		// });
	// });
</script>
<script>
	var currentTab = 0; // Current tab is set to be the first tab (0)
	showTab(currentTab); // Display the current tab

	function showTab(n) {
		// This function will display the specified tab of the form...
		var tab = document.getElementsByClassName("cform-step");
		$(".next-step").attr("type","button");
		
		if(n > (tab.length - 1)){
			$(".next-step").attr("type","submit");
		}
		
		tab[n].style.display = "block";
		if (n == (tab.length - 1)) {
			$(".previous").show();
			$(".next-step").text("Save");
		} else {
			$(".previous").hide();
			$(".next-step").text("Next Step");
		}
		
		if (n == 0) {	
			$(".steps").text("1");
			$(".firstStep").show();
		} else if (n > 0) {
			$(".previous").show();
			$(".firstStep").hide();
		}
	}

	function nextPrev(n) {
		// This function will figure out which tab to display
		var tab = document.getElementsByClassName("cform-step");
		
		// Hide the current tab:
		tab[currentTab].style.display = "none";
		// Increase or decrease the current tab by 1:
		currentTab = currentTab + n;
		// if you have reached the end of the tab
		// Otherwise, display the correct tab:
	
		showTab(currentTab);
	}
	
	function validateForm(tabno) {
		let $tabs = $('.cform-step');
		let $currentTabInputs = $($tabs[currentTab].getElementsByClassName("inputFormData"));
		let i = 0;
		let valid = false;
		
		var totalElementLength = $currentTabInputs.length;
		var validElementLength = 0;
		
		$currentTabInputs.each(function (e) {
			let $currentTabInput = $($currentTabInputs[e]);
			
			var elementClassName = $currentTabInput.attr('data-uniqueClassName');

			var todayDate = new Date();
			if($currentTabInput.attr('data-elementType') == 'date' && $currentTabInput.attr('data-maxDateEle') == 'maxDate') {
				var endDate = new Date(Date.parse($("."+elementClassName).val()));
				console.log(endDate);
			} else {
				var endDate = new Date(todayDate.getDate() - 1);
			}

			
			if ($currentTabInput.attr('data-elementType') == 'text' && $currentTabInput.attr('data-requiredEle') == 'required' && $currentTabInput.val() == "") {
				$currentTabInput.focus();
				return false;
			} else if($currentTabInput.attr('data-elementType') == 'radio' && $currentTabInput.attr('data-requiredEle') == 'required' && $("."+elementClassName+":checked").length <= 0) {
				$currentTabInput.focus();
				return false;
			} else if($currentTabInput.attr('data-elementType') == 'checkbox' && $currentTabInput.attr('data-requiredEle') == 'required' && $("."+elementClassName+":checked").length <= 0) {
				$currentTabInput.focus();
				return false;
			} else if($currentTabInput.attr('data-elementType') == 'date' && $currentTabInput.attr('data-maxDateEle') == 'maxDate' && ((endDate > todayDate) || (endDate.toString().toLowerCase() == 'invalid date')) ) {
				
				$currentTabInput.focus();
				return false;
			} else {
				validElementLength++;
			}
		});
		
		if(totalElementLength == validElementLength){
			nextPrev(tabno);
		} else {
			return false;
		}
    } 
	
	function validQty(checkDigit, boxValue)
	{
		var charCode = (checkDigit.which) ? checkDigit.which : checkDigit.keyCode;
		
		if(boxValue.length > 100)
		{
			return false;
		}
		else
		{
			if(charCode >31 && (charCode <48 || charCode >57) && charCode != 46)
			{
				return false;	
			}
			return true;
		}
	}
</script>
<script>
	// function validateForm(){
		// var validateval = $('#saveConsultationForm').validate({
			// submitHandler: function(form) {
				// $.ajax({
					// url: $("#saveConsultationForm").attr('action'), 
					// type: "POST",             
					// data: $('#saveConsultationForm').serialize(),
					// cache: false,             
					// processData: false,  
					// dataType:'json',			
					// success: function(response) 
					// {
						
					// },
					// timeout: 10000,
					// error: function(e){
						// toastr.options = {
						  // "closeButton": true,
						  // "debug": false,
						  // "newestOnTop": true,
						  // "progressBar": true,
						  // "positionClass": "toast-top-right",
						  // "preventDuplicates": false,
						  // "onclick": null,
						  // "showDuration": "300",
						  // "hideDuration": "1000",
						  // "timeOut": "5000",
						  // "extendedTimeOut": "1000",
						  // "showEasing": "swing",
						  // "hideEasing": "linear",
						  // "showMethod": "fadeIn",
						  // "hideMethod": "fadeOut"
						// }
						 
						// toastr.error('Request timeout, Please try again later!');
						// return false;
					// }
				// });
				// return false;
			// }
		// });
		
		// return validateval;
	// }
</script>

@endsection