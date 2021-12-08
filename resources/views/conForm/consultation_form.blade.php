{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<style>
	
</style>
@endsection

@section('content')
	
	<!--begin::Wrapper-->
		<!--begin::Header-->
		
		<!--end::Header-->
		<!--begin::Content-->
		<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
			<!--begin::Tabs-->
			<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
				<div
					class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
					<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
						role="tablist">
						<!-- <li class="nav-item">
							<a class="nav-link" href="{{ url('partners/conForm') }}">Overview</a>
						</li> -->
						<li class="nav-item">
							<a class="nav-link active" href="{{ route('showconForm') }}">Consultation Form</a>
						</li>
					</ul>
				</div>
			</div>
			<!--end::Tabs-->
			<!--begin::Entry-->
			<div class="d-flex flex-column-fluid">
				<!--begin::Container-->
				<div class="container">
					<!--begin::Sales-->
					<!--begin::Row-->
					<div class="row">
						<div class="col-12 my-6">
							<div class="display-service justify-content-between my-6">
								<div>
									<h2 class="font-weight-bolder">
										Manage Forms
									</h2>
									<h6 class="mb-4">
										Create and send consultation forms to your client.
									</h6>
								</div>
								<a href="{{ route('showCreateform') }}"
									class="btn btn-primary font-weight-bolder my-4">
									Create Form
								</a>
							</div>
							<div class="bg-white card-body p-0 rounded table-responsive">
								<table class="table table-hover" id="consform">
									<thead>
										<tr class="shadow-1" style="box-shadow:0px 3px 2px 0px #CCC">
											<th>Consultation form name</th>
											<th>Services</th>
											<th>Request</th>
											<th>Status</th>
											<th></th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--end::Row-->
					<!--end::Sales-->
				</div>
				<!--end::Container-->
			</div>
			<!--end::Entry-->
		</div>
		<!--end::Content-->
	<!--end::Wrapper-->

	<!--modal-->
	<div class="modal fade" id="ActiveFormModal" tabindex="-1" role="dialog" aria-labelledby="addNewQuestionModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Activate consultation form</h5>
                    <p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
                    </p>
                </div>
                <div class="modal-body">
                    <div>
						When active, <span class="name"> </span> will be sent to all clients when booking some services. Would you like to activate your consultation form?
                    </div>
                </div>
				<input type="hidden" class="formid">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" data-dismiss="model" id="saveactive">Yes, activate</button>
                </div>
            </div>
        </div>
    </div>
	<div class="modal fade" id="DeactiveFormModal" tabindex="-1" role="dialog" aria-labelledby="addNewQuestionModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Deactivate consultation form <h5>
                    <p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
                    </p>
                </div>
                <div class="modal-body">
                    <div>
						When deactivated, <span class="name"> </span> not be sent to all clients when booking some services. Would you like to disable your consultation form?
                    </div>
                </div>
				<input type="hidden" class="formid">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" data-dismiss="model" id="savedeactive">Yes, Deactivate</button>
                </div>
            </div>
        </div>
    </div>
	<!--end-->
	
	<!--begin::Scrolltop-->
	<div id="kt_scrolltop" class="scrolltop">
		<span class="svg-icon">
			<!--begin::Svg Icon | path:assets/media/svg/icons/Navigation/Up-2.svg-->
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
				height="24px" viewBox="0 0 24 24" version="1.1">
				<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
					<polygon points="0 0 24 0 24 24 0 24" />
					<rect fill="#000000" opacity="0.3" x="11" y="10" width="2" height="10" rx="1" />
					<path
						d="M6.70710678,12.7071068 C6.31658249,13.0976311 5.68341751,13.0976311 5.29289322,12.7071068 C4.90236893,12.3165825 4.90236893,11.6834175 5.29289322,11.2928932 L11.2928932,5.29289322 C11.6714722,4.91431428 12.2810586,4.90106866 12.6757246,5.26284586 L18.6757246,10.7628459 C19.0828436,11.1360383 19.1103465,11.7686056 18.7371541,12.1757246 C18.3639617,12.5828436 17.7313944,12.6103465 17.3242754,12.2371541 L12.0300757,7.38413782 L6.70710678,12.7071068 Z"
						fill="#000000" fill-rule="nonzero" />
				</g>
			</svg>
			<!--end::Svg Icon-->
		</span>
	</div>
	<!--end::Scrolltop-->
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>

<!--end::Page Scripts-->
	<script type="text/javascript">

		//show table data
		$(document).ready( function(){
			var table = $('#consform').DataTable({
				processing: true,
				serverSide: true,
				ajax: {
					type: "POST",
					url : "{{ route('getconform') }}",
					data: {_token : "{{csrf_token()}}"}
				},
				columns: [
						{data: 'name' , name: 'name'},
						{data: 'service_id' , name: 'service_id'},
						{data: 'send_request' , name: 'send_request'},
						{data: 'status' , name: 'status'},
						{data: 'id' , name: 'id'},
				]			
			});	
		});

		function openNav() {
			document.getElementById("myFilter").style.width = "300px";
		}

		function closeNav() {
			document.getElementById("myFilter").style.width = "0%";
		}
	</script>
	<script>
		// Class definition
		var KTBootstrapDatepicker = function () {

			var arrows;
			if (KTUtil.isRTL()) {
				arrows = {
					leftArrow: '<i class="la la-angle-right"></i>',
					rightArrow: '<i class="la la-angle-left"></i>'
				}
			} else {
				arrows = {
					leftArrow: '<i class="la la-angle-left"></i>',
					rightArrow: '<i class="la la-angle-right"></i>'
				}
			}

			// Private functions
			var demos = function () {
				// minimum setup
				$('#kt_datepicker_1').datepicker({
					rtl: KTUtil.isRTL(),
					todayHighlight: true,
					autoclose: true,
					format: 'DD, dd/mm/yyyy',
					orientation: "bottom left",
					templates: arrows
				});
			}

			return {
				// public functions
				init: function () {
					demos();
				}
			};
		}();

		jQuery(document).ready(function () {
			KTBootstrapDatepicker.init();

			$(document).on('click','#activate', function(){
				var name = $(this).data('name');
				var id = $(this).data('id');

				$('.name').text(name);
				$('.formid').val(id);
			});
			$(document).on('click','#deactivate', function(){
				var name = $(this).data('name');
				var id = $(this).data('id');

				$('.name').text(name);
				$('.formid').val(id);
			});
			
			$(document).on('click','#saveactive', function(){
				var id = $('.formid').val();

				$.ajax({
					type: 'get',
					url: 'activeform/'+id,
					success: function(response) {
						var table = $('#consform').DataTable();
						table.ajax.reload();
						$('#ActiveFormModal').modal('hide');
						
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
						toastr.success(response.message); 
					},
					error: function(data) {
						console.log(data);

						var errors = data.responseJSON;
			
						var errorsHtml = '';
						$.each(errors.errors, function(key, value) {
							errorsHtml += value[0];
						});
			
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
				});
			});
			$(document).on('click','#savedeactive', function(){
				var id = $('.formid').val();

				$.ajax({
					type: 'get',
					url: 'deactiveform/'+id,
					success: function(response) {
						var table = $('#consform').DataTable();
						table.ajax.reload();
						$('#DeactiveFormModal').modal('hide');

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
						toastr.success(response.message); 
					},
					error: function(data) {
						console.log(data);

						var errors = data.responseJSON;
			
						var errorsHtml = '';
						$.each(errors.errors, function(key, value) {
							errorsHtml += value[0];
						});
			
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
				});
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

		toastr.error('{{ Session::get('error') }}');
	</script>	
	@endif
@endsection