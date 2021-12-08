{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

@section('content')

	<!--begin::Wrapper-->
		<!--begin::Header-->
		
		<!--end::Header-->
		<!--begin::Content-->
		<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
			<!--begin::Entry-->
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
								<div class="col-12">
									<div>
										<a class="text-blue" href="{{ route('setup') }}">
											<h4>Back to setup</h4>
										</a>
									</div>
								</div>
								<div class="mt-6 mb-2">
									<div class="card">
										<form name="salesSetting" id="salesSetting" >
											<input type="hidden" name="id" id="salesid" value="{{ isset($sales->id) }}">
											<div class="card-body p-4">
												<div class="col-6">
													<p>Staff Commissions <i class="fa fa-question-circle"></i></p>
													<div class="form-group">
														<div class="checkbox-list mt-8">
															<label class="checkbox font-weight-bolder">
																<input name="salePriceBeforeDis" id="salePriceBeforeDis" @if(isset($sales->salePriceBeforeDis) && $sales->salePriceBeforeDis=='1') checked @endif type="checkbox">
																<span></span> CALCULATE BY ITEM SALE PRICE BEFORE
																DISCOUNT
															</label>
														</div>
													</div>
													<div class="form-group">
														<div class="checkbox-list mt-8">
															<label class="checkbox font-weight-bolder">
																<input name="salePriceIncludTax" id="salePriceIncludTax" @if(isset($sales->salePriceIncludTax) && $sales->salePriceIncludTax=='1') checked @endif type="checkbox">
																<span></span> CALCULATE BY ITEM SALE PRICE INCLUDING
																TAX
															</label>
														</div>
													</div>
													<div class="form-group">
														<div class="checkbox-list mt-8">
															<label class="checkbox font-weight-bolder">
																<input name="servicePriceBeforePaidPlanDis" id="servicePriceBeforePaidPlanDis" @if(isset($sales->servicePriceBeforePaidPlanDis) && $sales->servicePriceBeforePaidPlanDis=='1') checked @endif type="checkbox">
																<span></span> CALCULATE BY SERVICE PRICE BEFORE PAID
																PLAN DISCOUNT
															</label>
														</div>
													</div>
													<p>Vouchers</p>
													<div class="form-group">
														<label class="font-weight-bolder">DEFAULT EXPIRY
															PERIOD</label>
														<select class="form-control" name="expiryPeriod" id="expiryPeriod">
															<option value="days_14" @if(isset($sales->expiryPeriod) && $sales->expiryPeriod=='days_14') selected="selected" @endif>14 Days</option>
															<option value="months_1" @if(isset($sales->expiryPeriod) && $sales->expiryPeriod=='months_1') selected="selected" @endif >1 Month</option>
															<option value="months_2"@if(isset($sales->expiryPeriod) && $sales->expiryPeriod=='months_2') selected="selected" @endif>2 Months</option>
															<option value="months_3"@if(isset($sales->expiryPeriod) && $sales->expiryPeriod=='months_3') selected="selected" @endif>3 Months
															</option>
															<option value="months_4"@if(isset($sales->expiryPeriod) && $sales->expiryPeriod=='months_4') selected="selected" @endif>4 Months</option>
															<option value="months_6"@if(isset($sales->expiryPeriod) && $sales->expiryPeriod=='months_6') selected="selected" @endif>6 Months</option>
															<option value="years_1"@if(isset($sales->expiryPeriod) && $sales->expiryPeriod=='years_1') selected="selected" @endif>1 Year</option>
															<option value="years_3"@if(isset($sales->expiryPeriod) && $sales->expiryPeriod=='years_3') selected="selected" @endif>3 Years</option>
															<option value="years_5"@if(isset($sales->expiryPeriod) && $sales->expiryPeriod=='years_5') selected="selected" @endif>5 Years</option>
															<option value="never"@if(isset($sales->expiryPeriod) && $sales->expiryPeriod=='never') selected="selected" @endif>No Expiry</option>
														</select>
													</div>
													<button type="button" class="btn btn-primary" id="save">Save Changes</button>
												</div>
											</div>
										</form>
									</div>
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
		<!--end::Content-->
	<!--end::Wrapper-->

	
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
@section("scripts")
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
	
	<script>
		//save tax
		$(document).on('click','#save', function(){
			var form = $("#salesSetting");
			console.log(form.serialize())
			$.ajax({
				type: "POST",
				url: "{{ route('saveSalesSetting') }}",
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