{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!--begin::Tabs-->
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
<?php
$add = 'Add';
$id = "";
$supplier_name = "";
$user_id = "";
$supplier_description = "";
$first_name = "";
$last_name = "";
$mobile_country_code = "";
$email = "";
$mobile = "";
$tel_country_code = "";
$telephone = "";
$website = "";
$address = "";
$suburb = "";
$city = "";
$state = "";
$zip_code = "";
$country = "";

$is_postal_same = 1;
$postal_address = "";
$postal_suburb  = "";
$postal_city    = "";
$postal_state   = "";
$postal_zip_code= "";
$postal_country = "";


if(!empty($inventory_supplier))
{
	$edit = 'Edit';
	$id = $inventory_supplier->id;
	$supplier_name = $inventory_supplier->supplier_name;
	$user_id = $inventory_supplier->user_id;
	$supplier_description = $inventory_supplier->supplier_description;
	$first_name = $inventory_supplier->first_name;
	$last_name = $inventory_supplier->last_name;
	$mobile_country_code = $inventory_supplier->mobile_country_code;
	$email = $inventory_supplier->email;
	$mobile = $inventory_supplier->mobile;
	$tel_country_code = $inventory_supplier->tel_country_code;
	$telephone = $inventory_supplier->telephone;
	$website = $inventory_supplier->website;
	$address = $inventory_supplier->address;
	$suburb = $inventory_supplier->suburb;
	$city = $inventory_supplier->city;
	$state = $inventory_supplier->state;
	$zip_code = ($inventory_supplier->zip_code) ? $inventory_supplier->zip_code : '';
	$country = $inventory_supplier->country;
	
	$is_postal_same = ($inventory_supplier->is_postal_same == 1) ? 1 : 0;
	$postal_address = ($inventory_supplier->postal_address) ? $inventory_supplier->postal_address : '';
	$postal_suburb  = ($inventory_supplier->postal_suburb) ? $inventory_supplier->postal_suburb : '';
	$postal_city    = ($inventory_supplier->postal_city) ? $inventory_supplier->postal_city : '';
	$postal_state   = ($inventory_supplier->postal_state) ? $inventory_supplier->postal_state : '';
	$postal_zip_code= ($inventory_supplier->postal_zip_code) ? $inventory_supplier->postal_zip_code : '';
	$postal_country = ($inventory_supplier->postal_country) ? $inventory_supplier->postal_country : '';
}
?>
<div class="container-fluid p-0">
	<div class="modal fade p-0" id="deleteSupplierModal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			{{ Form::open(array('url' => '','id' => 'delSupplier')) }}
				<input type="hidden" name="deleteID" id="deleteID" value="{{ $id }}">
				<div class="modal-content ">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center">Delete Supplier</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<div class="modal-body">
						<div class="container">
							<div class="row">
								<p>Are you sure you want to delete this supplier?</p>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<button type="button" id="deleteSupplier" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Delete</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
	<div class="my-custom-body-wrapper">
		<!-- <div class="my-custom-header">
			<div class="p-4 d-flex justify-content-between border-bottom">
				<h2 class="m-auto font-weight-bolder">@if(empty($inventory_supplier)) {{ $add }} @else {{ $edit }} @endif Supplier</h2>
				<h1 class="cursor-pointer" onclick="history.back();">&times;</h1>
			</div>
		</div> -->
		<div class="my-custom-header fixed-top text-dark bg-white border-bottom"> 
            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
            	<span class="d-flex"></span>
	            <div style="flex-grow: 1;">
					<h1 class="font-weight-bolder page-title">@if(empty($inventory_supplier)) {{ $add }} @else {{ $edit }} @endif Supplier</h1>
	            </div>
	            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
	                <a type="button" class="close" onclick="history.back();" style="display: flex; justify-content: flex-start; align-items: center;top:unset;left: 0;opacity: 1;font-size: 1.75rem;">
		                <span aria-hidden="true" class="la la-times"></span>
		            </a>
	            </div>
	        </div>
        </div> 
		<form method="POST" enctype="multipart/form-data" action="{{ route('addNewSupplier') }}" id="addInventorySupplier" class="mt-20">
			@csrf
			<input type="hidden" name="editSupplierID" value="{{ $id }}">
			<div class="my-custom-body">
				<div class="container">
					<div class="row">
						<div class="col-12 col-md-6 p-2 pr-4">
							<h3 class="my-3">Supplier Details</h3>
							<div class="client_pupop">
								<div class="form-group">
									<label>Supplier name <span class="text-danger">*</span></label>
									<input type="text" name="supplier_name" class="form-control" placeholder="e.g TJ" value="{{ $supplier_name }}">
								</div>
								<div class="form-group">
									<label>Supplier description</label>
									<textarea class="form-control" name="supplier_description" placeholder=" e.g. Local provider of hair products" rows="4">{{ $supplier_description }}</textarea>
								</div>
								<h3 class="my-3">Contact Information</h3>
								<div class="d-flex">
									<div class="form-group  mr-2 w-100">
										<label for="exampleSelect1">First name</label>
										<input type="text" name="first_name" class="form-control" placeholder="john" value="{{ $first_name }}">
									</div>
									<div class="form-group  ml-2 w-100">
										<label for="exampleSelect1">Last name</label>
										<input type="text" name="last_name" class="form-control" placeholder="Doe" value="{{ $last_name }}">
									</div>
								</div>
								<div class="d-flex">
									<div class="form-group mr-2 w-100 mobileNumberclass" >
										<label for="exampleSelect1">Mobile</label>
										<input type="tel" name="mobile"  class="form-control allow_only_numbers" placeholder="" id="mobileNumber"  value="{{ $mobile }}">
										<input type="hidden" name="mobile_country_code" id="mobile_country_code" value="{{ $mobile_country_code }}">
									</div>
									<div class="form-group ml-2 w-100">
										<label>Email <span class="text-danger">*</span></label>
										<input type="email" name="email" class="form-control" placeholder="mail@gmail.com" value="{{ $email }}">
									</div>
								</div>
								<div class="d-flex">
									<div class="form-group mr-2 w-100 telephoneclass">
										<label for="exampleSelect1">Telephone</label>
										<input type="tel" name="telephone" class="form-control allow_only_numbers" placeholder="" value="{{ $telephone }}" id="telephone">
										<input type="hidden" name="tel_country_code" id="tel_country_code" value="{{ $tel_country_code }}">
									</div>
									<div class="form-group ml-2 w-100">
										<label>Website</label>
										<input type="text" name="website" class="form-control" placeholder="www.google.com" value="{{ $website }}">
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-12 col-md-6 p-2">
							<div class="row">
								<div class="@if ($is_postal_same == 1) col-md-12 @else col-md-6 @endif p-2 physical_address">
									<h3 class="my-3">Physical Address</h3>
									<div class="form-group">
										<label>Address</label>
										<input type="text" name="address" class="form-control" placeholder="" value="{{ $address }}">
									</div>
									<div class="form-group">
										<label>Suburb</label>
										<input type="text" name="suburb" class="form-control" placeholder="" value="{{ $suburb }}">
									</div>
									<div class="form-group">
										<label for="exampleSelect1">City</label>
										<input type="text" name="city" class="form-control" placeholder="city" value="{{ $city }}">
									</div>
									<div class="form-group">
										<label for="exampleSelect1">State</label>
										<input type="text" name="state" class="form-control" placeholder="state" value="{{ $state }}">
									</div>
									<div class="form-group">
										<label>Zip / Post Code</label>
										<input type="text" name="zip_code" class="form-control" placeholder="" value="{{ $zip_code }}">
									</div>
									<div class="form-group">
										<label>Country</label>
										<select name="country" class="form-control">
											<option value="">--- Select country ---</option>
											@foreach ($Country as $key => $value)
												<option value="{{ $value->sortname }}" {{ ($value->sortname == $country) ? 'selected' : '' }} >{{ $value->name }}</option>
											@endforeach
										</select>
									</div>
									<div class="form-group">
										<div class="checkbox-list">
											<label class="checkbox">
												<input type="checkbox" id="is_postal_same" name="is_postal_same" {{ ($is_postal_same == 1) ? 'checked' : '' }}>
												<span></span>Same as postal Address
											</label>
										</div>
									</div>
								</div>
								<div class="col-md-6 p-2 postal_address" @if($is_postal_same == 1) style="display:none;" @else style="display:block;" @endif >
									<h3 class="my-3">Postal Address</h3>
									<div class="form-group">
										<label>Address</label>
										<input type="text" name="postal_address" id="postal_address" class="form-control" placeholder="" value="{{ $postal_address }}">
									</div>
									<div class="form-group">
										<label>Suburb</label>
										<input type="text" name="postal_suburb" id="postal_suburb" class="form-control" placeholder="" value="{{ $postal_suburb }}">
									</div>
									<div class="form-group">
										<label for="exampleSelect1">City</label>
										<input type="text" name="postal_city" id="postal_city" class="form-control" placeholder="city" value="{{ $postal_city }}">
									</div>
									<div class="form-group">
										<label for="exampleSelect1">State</label>
										<input type="text" name="postal_state" id="postal_state" class="form-control" placeholder="state" value="{{ $postal_state }}">
									</div>
									<div class="form-group">
										<label>Zip / Post Code</label>
										<input type="text" name="postal_zip_code" id="postal_zip_code" class="form-control" placeholder="" value="{{ $postal_zip_code }}">
									</div>
									<div class="form-group">
										<label>Country</label>
										<select name="postal_country" id="postal_country" class="form-control">
											<option value="">--- Select country ---</option>
											@foreach ($Country as $key => $value)
												<option value="{{ $value->sortname }}" {{ ($value->sortname == $postal_country) ? 'selected' : '' }} >{{ $value->name }}</option>
											@endforeach
										</select>
									</div>
								</div>
							<div>
						</div>
					</div>
				</div>
			</div>
			<hr class="m-0">
			<div class="my-custom-header container ">
				<div class="text-right my-4 d-flex justify-content-between">
					@if ($id != '')
					<button type="button" class="btn btn-danger font-weight-bold" data-toggle="modal" data-target="#deleteSupplierModal">Delete</button>
					@endif
					<div class="ml-auto">
						<button type=" button" class="mr-4 btn btn-outline-secondary font-weight-bold" onclick="history.back();">Cancel</button>
						<button type="submit" id="addInventorySupplierButton" class="btn btn-primary font-weight-bold">Save</button>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

@endsection
{{-- Scripts Section --}}
@section('scripts')
<script type="text/javascript">
	var WEBSITE_URL = "{{ url('') }}";
</script>
<script src="{{ asset('js/addInventorySupplier.js') }}"></script>
<script src="{{ asset('js/intlTelInput.min.js') }}"></script>
<script type="text/javascript">
	var phone_number = window.intlTelInput(document.querySelector("#telephone"), {
	  	separateDialCode: true,
	  	preferredCountries:["ca"],
	  	hiddenInput: "full",
	  	utilsScript: "{{ asset('js/utils.js') }}"
	});
	
	$(document).on("click keypress", '.telephoneclass .iti__country' , function(e) {
		e.preventDefault();
		if($(this).hasClass("iti__active")){
			var country_code = $(this).attr("data-dial-code");
			$("#tel_country_code").val(country_code);
		} else {
			var country_code = $(this).attr("data-dial-code");
			$("#tel_country_code").val(country_code);
		}
	});

	var phone_number = window.intlTelInput(document.querySelector("#mobileNumber"), {
	  separateDialCode: true,
	  preferredCountries:["ca"],
	  hiddenInput: "full",
	  utilsScript: "{{ asset('js/utils.js') }}"
	});
	
	$(document).on("click keypress keydown keyup", '.mobileNumberclass .iti__country' , function(e) {
		e.preventDefault();
		
		if($(this).hasClass("iti__active")){
			var country_code = $(this).attr("data-dial-code");
			$("#mobile_country_code").val(country_code);
		} else {
			var country_code = $(this).attr("data-dial-code");
			$("#mobile_country_code").val(country_code);
		}
	});

	$(document).ready(function(){
        var tel_country_code = "<?php echo $tel_country_code ?>";
        var mobile_country_code = "<?php echo $mobile_country_code ?>";
        if($('#tel_country_code').val() != "")
        {
        	$('.iti__country').each(function(){
        		if($(this).attr("data-dial-code") == $('#tel_country_code').val())
        		{
        			var countryCode = $(this).attr('data-country-code');
        			var countryCode = $(this).attr('data-country-code');
        			if($('#mobile_country_code').val() == 1)
        			{
        				countryCode = "ca"
        			}
        			console.log(countryCode);
        			$('.telephoneclass').find('.iti__selected-flag').children('.iti__flag').addClass('iti__'+countryCode);
        		}
        	});
        }
        if($('#mobile_country_code').val() != "")
        {
        	$('.iti__country').each(function(){
        		if($(this).attr("data-dial-code") == $('#mobile_country_code').val())
        		{
        			var countryCode = $(this).attr('data-country-code');
        			if($('#mobile_country_code').val() == 1)
        			{
        				countryCode = "ca"
        			}
        			console.log(countryCode);
        			$('.mobileNumberclass').find('.iti__selected-flag').children('.iti__flag').addClass('iti__'+countryCode);
        		}
        	});
        }

		$(document).on('keypress', '.allow_only_numbers', function(evt){
			evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;

		    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    return true;
		});

		$(document).on('paste', '.allow_only_numbers', function (event) {
		  	if (event.originalEvent.clipboardData.getData('Text').match(/[^\d]/)) {
		    	event.preventDefault();
		  	}
		});
	});
	$(document).on("click", '#deleteSupplier', function (e) {
			
		KTApp.blockPage();
		var form = $(this).parents('form:first');

		$.ajax({
			type: "POST",
			url: "{{ route('deleteSupplier') }}",
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
						window.location = "{{ route('suppliers') }}";
					}, 2000);
				} else {
					table.ajax.reload();
					
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
	
	$(document).on('click','#is_postal_same',function(){
		if($(this).is(":checked")){
			$(".physical_address").removeClass("col-md-6");
			$(".physical_address").addClass("col-md-12");
			$(".postal_address").hide();
			
			$("#postal_address").val('');
			$("#postal_suburb").val('');
			$("#postal_city").val('');
			$("#postal_state").val('');
			$("#postal_zip_code").val('');
			$("#postal_country").val('');
		} else {
			$(".physical_address").removeClass("col-md-12");
			$(".physical_address").addClass("col-md-6");
			$(".postal_address").show();
		}
	});
</script>
@endsection