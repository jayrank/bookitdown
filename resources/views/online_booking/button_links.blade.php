{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')

<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
	<!--begin::Tabs-->
	@include('layouts.onlineBookingNav')
	<!--end::Tabs-->
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class="container my-8">
			<!--begin::Sales-->
			<!--begin::Row-->
			<div class="row my-8">
				<div class="container">
					<div class="card mb-3 shadow">
						<div class="row no-gutters">
							<div class=" col-12 col-sm-12 col-md-3">
								<div class="card-img p-3">
									<img src="{{ asset('assets/images/book.png') }}"
										class="img-fluid rounded card-img" alt="">
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-9">
								<div class="card-body">
									<div class="d-flex justify-content-between flex-wrap">
										<h3 class="card-title m-0 font-weight-bolder">Book Now link
										</h3>
										<!-- <div class="badge badge-danger text-uppercase">Disabled
										</div> -->
									</div>
									<p class="card-text text-muted">
										Get your Book Now link to capture online bookings from
										your own website, blog or anywhere you wish.
									</p>
									<a href="#" id="getlink" class="btn btn-primary">Get link</a>
								</div>
							</div>
						</div>
					</div>

					{{-- <div class="card mb-3 shadow">
						<div class="row no-gutters">
							<div class=" col-12 col-sm-12 col-md-3">
								<div class="card-img p-3">
									<img src="./assets/images/social.png"
										class="img-fluid rounded card-img" alt="">
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-9">
								<div class="card-body">
									<div class="d-flex justify-content-between flex-wrap">
										<h3 class="card-title m-0 font-weight-bolder">
											Facebook and Instagram booking buttons
										</h3>
										<div class="badge badge-danger text-uppercase">Disabled
										</div>
									</div>
									<p class="card-text text-muted">
										Add a Book Now button to your Facebook and Instagram
										profiles or send one to clients via Messenger with our
										official
										integrations.
									</p>
									<a href="#" data-toggle="modal" data-target="#setUpModal"
										class="btn btn-primary">Set up now</a>
								</div>
							</div>
						</div>
					</div>

					<div class="card mb-3 shadow">
						<div class="row no-gutters">
							<div class=" col-12 col-sm-12 col-md-3">
								<div class="card-img p-3">
									<img src="./assets/images/reserve_glob.png"
										class="img-fluid rounded card-img" alt="">
								</div>
							</div>
							<div class="col-12 col-sm-12 col-md-9">
								<div class="card-body">
									<div class="d-flex justify-content-between flex-wrap">
										<h3 class="card-title m-0 font-weight-bolder">
											Google booking button
										</h3>
										<div class="badge badge-success text-uppercase">Eligible
										</div>
									</div>
									<p class="card-text text-muted">
										Capture online bookings directly from Google Search,
										Google Maps and more with our official integration with
										Reserve
										with Google.
									</p>
								</div>
							</div>
						</div>
					</div> --}}
				</div>
			</div>
			<!--end::Row-->
			<!--end::Sales-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
</div>
<div class="modal fade" id="getLinkModal" tabindex="-1" role="dialog" aria-labelledby="getLinkModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content d-flex flex-row">
				<div>
					<div class="modal-header pb-0 border-0">
						<p class="cursor-pointer ml-auto" data-dismiss="modal" aria-label="Close"><i
								class="text-dark fa fa-times icon-lg"></i>
						</p>
					</div>
					<div class="modal-body">
						<h3>Book Now link</h3>
						<h5 class="my-4 text-dark-50">Copy and paste the link below to add the button to your website,
							blog
							posts, email
							signature to
							anywhere else online.</h5>
						<h5 class="my-4 text-dark-50">The link leads straight to your online booking service menus,
							letting
							clients
							easily book new
							appointments in just a few
							clicks.</h5>
						<h5 class="my-4 text-dark-50">Your link works perfectly with any device including desktops,
							tablets
							and mobiles.
						</h5>
						<div class="form-group">
							<div class="input-group input-group-lg">
								<div class="input-group-prepend">
									<span class="input-group-text">
										URL
									</span>
								</div>
								<input id="urlLink" type="text" class="form-control" placeholder="URl" disabled="" value="https://schedulethat.tjcg.in/paid-plans/provider/kwtglfg6?pId=429198">
							</div>
							<a class="my-3 btn btn-lg btn-primary" id="copytext">Copy Link</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@endsection
@section('scripts')
<script>
$(document).on('click','#getlink',function(){	
	$.ajax({
		type: "get",
		url: "{{ route('get_booking_link') }}",
		dataType: 'json',
		success: function (data) {
			if(data.status==true){
				$('#getLinkModal').modal('show');
				$('#urlLink').val(data.link);
			} else {
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
				toastr.error((errorsHtml) ? errorsHtml : "Something went wrong!");
			}
		}
	});
});
$(document).ready(function(){
    
	var copyBtn   = $("#copytext"),
		input     = $("#urlLink");

	function copyToClipboardFF(text) {
	window.prompt ("Copy to clipboard: Ctrl C, Enter", text);
	}

	function copyToClipboard() {
		var success   = true,
		range     = document.createRange(),
		selection;

		// For IE.
		if (window.clipboardData) {
			window.clipboardData.setData("Text", input.val());        
		} else {
			// Create a temporary element off screen.
			var tmpElem = $('<div>');
			tmpElem.css({
			position: "absolute",
			left:     "-1000px",
			top:      "-1000px",
			});
			// Add the input value to the temp element.
			tmpElem.text(input.val());
			$("body").append(tmpElem);
			// Select temp element.
			range.selectNodeContents(tmpElem.get(0));
			selection = window.getSelection ();
			selection.removeAllRanges ();
			selection.addRange (range);
			// Lets copy.
			try { 
			success = document.execCommand ("copy", false, null);
			}
			catch (e) {
			copyToClipboardFF(input.val());
			}
			if (success) {
			alert("Copy link ");
			// remove temp element.
			tmpElem.remove();
			}
		}
	}

	copyBtn.on('click', copyToClipboard);
});
</script>
@endsection