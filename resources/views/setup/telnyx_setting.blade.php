{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    <div class="content d-flex flex-column flex-column-fluid" id="kt_content">
        <div class="d-flex flex-column-fluid">
            <div class="container">
                <div class="row my-4">
                    <div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
                        <div class="card-custom card-stretch gutter-b">
                            <div class="mt-6 mb-2">
                                <div class="card">
                                    <form action="{{ route('saveTelnyxSetting') }}" method="POST" name="telnyxSetting" id="telnyxSetting" >
                                        @csrf
                                        <input type="hidden" name="id" id="salesid" value="">
                                        <div class="card-body p-4">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label class="align-items-center">
                                                        <span class="option-control">
                                                            <span class="radio">
                                                                <input type="radio" name="sms_service_type" class="sms_service_type" value="0" {{ ($CurrentUser->sms_service_type == 0) ? "checked='checked'" : ""; }}>
                                                                Admin SMS &nbsp;
                                                                <span></span>
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="align-items-center">
                                                        <span class="option-control">
                                                            <span class="radio">
                                                                <input type="radio" name="sms_service_type" class="sms_service_type" value="1" {{ ($CurrentUser->sms_service_type == 1) ? "checked='checked'" : ""; }}>
                                                                Telnyx &nbsp;
                                                                <span></span>
                                                            </span>
                                                        </span>
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="col-md-8" id="telnyxSMSMethod" style="display: {{ ($CurrentUser->sms_service_type == 0) ? "none" : "block"; }}">
                                                <div class="form-group">
                                                    <lable for="">Telnyx Username</lable>
                                                    <input type="text" class="form-control" placeholder="john@gmail.com" name="telnyx_username" id="telnyx_username" value="{{ $CurrentUser->telnyx_username }}">
                                                </div>
                                                <div class="form-group">
                                                    <lable for="">Telnyx Token</lable>
                                                    <input type="text" class="form-control" placeholder="For Ex. yS3dKcwcSlu7wqsc5ffg" name="telnyx_token" id="telnyx_token" value="{{ $CurrentUser->telnyx_token }}">
                                                </div>
                                                <div class="form-group">
                                                    <lable for="">Telnyx API Key</lable>
                                                    <input type="text" class="form-control" placeholder="For Ex. KEY0170163F410B3D73F3E24ACA4ED6A1AF_dWEQas5cSlu7wqsc" name="telnyx_api_key" id="telnyx_api_key" value="{{ $CurrentUser->telnyx_api_key }}">
                                                </div>
                                                {{-- <div class="form-group">
                                                    <lable for="">Telnyx Number</lable>
                                                    <input type="number" class="form-control" placeholder="For Ex. 13438080589" name="telnyx_number" id="telnyx_number">
                                                </div> --}}
                                            </div>
                                            <div class="col-md-8">
                                                <button type="submit" class="btn btn-primary" id="save">Save Changes</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
@endsection

{{-- Scripts Section --}}
@section("scripts")
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.3/dist/jquery.validate.js"></script>
	
	<script>
        $('.sms_service_type').on('change',function(){
            var thisVal = $(this).val();
            $('#telnyxSMSMethod').show();
            if(thisVal == 0){
                $('#telnyxSMSMethod').hide();
            }
        });
        $('#telnyxSetting').validate({
            rules:{
                telnyx_username:{
                    required:true,
                    email:true
                },
                telnyx_token:{
                    required:true,
                },
                telnyx_api_key:{
                    required:true,
                },
                /* telnyx_number:{
                    required:true,
                    number:true
                } */
            }
        });
		//save tax
		$(document).on('submit','#telnyxSetting', function(e){
            e.preventDefault();
			var form = $("#telnyxSetting");
			$.ajax({
				type: "POST",
				url: $(this).attr('action'),
				headers:{
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
				data: form.serialize(),
				success: function (data) {
					KTApp.unblockPage();
					toastr.success(data.message);
				},
				error: function(data) {
					var errors = data.responseJSON;
					var errorsHtml = '';
					$.each(errors.error, function(key, value) {
						errorsHtml += '<p>' + value[0] + '</p>';
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
	
					toastr.error((errorsHtml) ? errorsHtml : 'There is something wrong!');
					KTUtil.scrollTop();
				}
			});
			
		});
	</script>
@endsection