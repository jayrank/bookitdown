{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')

@endsection

{{-- CSS Section --}}
@section('content')
<!--begin::Content-->

<div class="container-fluid p-0">
	<div class="my-custom-body-wrapper">
		<form id="sendEmailVoucher" action="{{ route('sendVoucherInEmail') }}" method="POST">
			@csrf
			<input type="hidden" name="invoiceID" id="invoiceID" value="{{ $invoiceId }}">
			<div class="my-custom-header">
				<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
					<span class="d-flex">
						<p class="cursor-pointer m-0 px-6" onclick="history.back();"><i class="text-dark fa fa-times icon-lg"></i></p>
					</span>
					<h1 class="font-weight-bolder">Email voucher</h1>
					<button class="btn btn-lg btn-primary next-step" type="submit" id="sendEmailVoucherBtn">Email voucher</button>
				</div>
			</div>
		
			<div class="my-custom-body">
				<div class="container-fluid">
					<div class="row">
						<div class="col-12 col-md-4 p-0">
							<div class="p-4" style="height:calc(100vh - 80px);overflow-y:scroll">
								<div>
									<h3 class="font-weight-bolder">Recipient's info</h3>
									
									<div class="form-group">
										<label class="font-weight-bolder">Recipient's first name</label>
										<input type="text" class="form-control" name="recipient_first_name" id="recipient_first_name">
									</div>
									
									<div class="form-group">
										<label class="font-weight-bolder">Recipient's last name</label>
										<input type="text" class="form-control" name="recipient_last_name" id="recipient_last_name">
									</div>
									
									<div class="form-group">
										<label class="font-weight-bolder">Recipient’s email</label>
										<input type="text" class="form-control" name="recipient_email" id="recipient_email">
										<p>The voucher will be sent to this email address.</p>
									</div>
									
									<div class="form-group">
										<label class="font-weight-bolder">Personalised message</label>
										<textarea class="form-control" name="recipient_personalized_email" id="recipient_personalized_email" rows="5">
										</textarea>
									</div>
								</div>
							</div>
						</div>
						<div class="col-12 col-md-8 p-0 bg-content" style="height:calc(100vh - 80px);overflow-y:scroll">
							@if(!empty($VoucherSold))
								@foreach($VoucherSold as $VoucherSoldData)
									<div class="p-4 d-flex justify-content-center">
										<div class="card w-80" style="border: 1px solid #d5d7da; border-radius: 8px; margin-bottom: 32px; background: #e8e8ee;">
											<div class="card-header px-4 py-5 text-center" style=" background: #F2F2F7; border-radius: 8px 8px 0 0; font-size: 17px; font-weight: 700; line-height: 24px;">Voucher Preview</div>
											<div class="card-header px-4 py-5" style="border: 1px solid #d5d7da; border-radius: 8px 8px 0 0; margin-left: -1px; margin-right: -1px; background: #e8e8ee; border-bottom: 0;">
												<span style="color: #878c93; font-size: 15px;">Voucher email subject: </span>
												<span style="color: #101928; font-size: 15px;"><span id="voucherTitle">{{ ($VoucherSoldData['name']) ? $VoucherSoldData['name'] : '' }}</span> from {{ ($VoucherSoldData['location_name']) ? $VoucherSoldData['location_name'] : '' }}</span>
											</div>
											<div class="card-body" style="border: 1px solid #d5d7da; border-radius: 8px; margin-bottom: -1px; margin-left: -1px; margin-right: -1px; background: #ffffff;">
												<div class="w-80 m-auto position-relative">
													<h5 class="text-center mb-2 voucherPersonalizedMessage" id="voucherDescription" style="padding-bottom: 24px; font-size: 17px;">{{ ($VoucherSoldData['name']) ? $VoucherSoldData['name'] : '' }}</h5>
													<h6 class="text-center mb-2">Voucher for</h6>
													<h5 class="text-center mb-2">
														<span class="personFirstName" style="font-size: 25px; line-height: 35px;"></span> <span class="personLastName" style="font-size: 25px; line-height: 35px;"></span>
													</h5>
													<br>
													<div class="bgi-no-repeat bgi-size-cover justify-content-center pb-1 pt-5 px-10 text-center text-white voucher-wrapper">
														<div class="p-4 text-center">
															<img alt="voucher-thumb" class="rounded mb-4"
																src="{{ asset('./assets/images/thumb.jpg') }}" width="80px" height="80px">
															<h3 class="font-weight-bold">{{ ($VoucherSoldData['location_name']) ? $VoucherSoldData['location_name'] : '' }}</h3>
															<h5 class="text-grey">{{ ($VoucherSoldData['location_address']) ? $VoucherSoldData['location_address'] : '' }}</h5>
														</div>
														<div class="border-bottom w-100 opacity-35"></div>
														<div class="my-8 vouchers-value add-vouchers-value">
															<p class="mb-0 font-size-lg">Voucher Value</p>
															<h1 class="font-weight-bolder mb-0">CA $<span id="vaoucher-price">{{ ($VoucherSoldData['price']) ? $VoucherSoldData['price'] : 0 }}</span></h1>
														</div>
														<div class="border-bottom w-100 opacity-35"></div>
														<div class="my-8 vouchers-bottom">
															<p class="mb-4 font-size-lg">Voucher Code : <span class="font-weight-bolder font-size-lg">{{ ($VoucherSoldData['voucher_code']) ? $VoucherSoldData['voucher_code'] : '' }}</span></p>
															<button class="btn btn-bg-light btn-lg isBookButton my-4 px-4">Book Now</button>
															<p class="mb-1 font-weight-bold font-size-lg">Redeem on <span class="font-weight-bolder cursor-pointer">all services</span> <i class="fa fa-chevron-right icon-sm"></i>
															</p>
															<p class="mb-1 font-weight-bold font-size-lg">Valid until {{ ($VoucherSoldData['end_date']) ? date("d M Y",strtotime($VoucherSoldData['end_date'])) : '' }}</p>
															<p class="mb-1 font-weight-bold font-size-lg">For multiple-use</p>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								@endforeach
							@endif
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div>

<!--end::Content-->
@endsection
{{-- Scripts Section --}}
@section('scripts')	
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
<script src="{{ asset('js/add-appointment.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>

@endsection