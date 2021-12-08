{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('assets/css/owl.css') }}" rel="stylesheet" type="text/css">
<style>
	.parpal{
		background: linear-gradient(-45deg, rgb(190, 74, 244) 0%, rgb(92, 55, 246) 100%);
	}
	.blue{
		background: linear-gradient(-225deg, rgb(11, 109, 217) 0%, rgb(95, 171, 255) 100%);
	}
	.black{
		background: linear-gradient(-225deg, rgb(16, 25, 40) 0%, rgb(32, 48, 71) 100%);
	}
	.green{
		background: linear-gradient(-45deg, rgb(0, 166, 156) 0%, rgb(0, 157, 98) 100%);
	}
	.orange{
		background: linear-gradient(-45deg, rgb(237, 176, 27) 0%, rgb(222, 100, 38) 100%);
	}
</style>

@endsection

@section('content')

	<div class="content d-flex flex-column flex-column-fluid pt-0" id="kt_content">
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Sales-->
				<!--begin::Row-->
				<!-- <div class="content-header ">
					<div class="voucher-header rounded">
						<h2 class="text-dark font-weight-bolder mb-4">
							Set up payments to sell vouchers online and increase sales
						</h2>
						<p class="font-size-lg">
							Congrats! You have ScheduleDown Plus enabled. Let clients buy your vouchers
							online
							through the ScheduleDown marketplace and via
							direct booking links by setting up payments in your account.
						</p>
						<button class="btn btn-white" data-toggle="modal"
							data-target="#learnMoreModal">Learn More</button>
					</div>
				</div> -->
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-xxl-12">
						<!--begin::List Widget 3-->
						<div class="">
							<!--begin::Body-->
							<div class="content-header">
								<div class="action-btn d-flex justify-content-end my-8">
									<!-- <button class="btn btn-white mr-4" data-toggle="modal" data-target="#sellPaidPlansModal"><i class="fas fa-link"></i>Sell vouchers online</button> -->
									
									@if (Auth::user()->can('manage_vouchers'))
										<a href="{{ route('create_voucher') }}" class="btn btn-primary">Create voucher type</a>
									@else
										<a href="javascript:;" class="no_access btn btn-primary">Create voucher type</a>
									@endif
								</div>
							</div>
							<div class="container">
								<div class="row">
									@foreach($voucher as $value)
									<div class="col-md-6 my-4">
										<a data-toggle="modal" data-target="#viewVoucher{{ $value->id }}">
											<div class="card voucher-card  {{ $value->color }}">
												<div class="card-body text-white p-6">
													<div class="my-3 text-center">
														<h6 class="font-weight-bold">Voucher value</h6>
														<h2 class="font-weight-bolder">CA ${{ $value->value }}</h2>
													</div>
													<div
														class="mt-10 font-weight-bold d-flex justify-content-between">
														<div>
															<h5 class="font-weight-bolder">{{ $value->name }}</h5>
															<h5>Redeem on {{ count(explode(",", $value->services_ids)) }} services</h5>
														</div>
														<div class="text-right">
															<h6 class="font-weight-bolder">CA ${{ $value->retailprice }}</h6>
															@if($value->value > $value->retailprice)
																<h5 class="bagde badge-secondary p-1 rounded text-uppercase">
																Save {{ round((($value->value - $value->retailprice)*100) / $value->value) }}%
																</h5>
															@endif	
															<h6 class="font-weight-bolder">Sold {{ $value->numberofsales }}</h6>
														</div>
													</div>
												</div>
											</div>
										</a>
										{{-- start  --}}
											<div class="modal fade show" id="viewVoucher{{ $value->id }}" tabindex="-1" role="dialog" aria-labelledby="viewVoucherLabel" aria-modal="true" style="padding-right: 15px; display: none;">
												<div class="modal-dialog modal-lg voucher-card {{ $value->color }}" role="document">
													<div class="modal-content d-flex flex-row">
														<div class="bg-light-secondary modal-image d-none d-md-block">
															<div class="p-2 m-auto position-relative">
																<div class="p-4 text-center text-white justify-content-center bgi-size-cover bgi-no-repeat {{ $value->color }}"  style="width: 380px;">
																	<div class="p-4 text-center">
																		<img alt="voucher-thumb" class="rounded mb-4" src="{{ asset('/assets/images/thumb.jpg') }}" width="80px" height="80px">
																		<h3 class="font-weight-bold">{{ $value->name }}</h3>
																		<input type="hidden" name="voucherId" value="{{ $value->id }}">
																		<h5 class="text-grey">CA ${{ $value->retailprice }} Voucher Price</h5>
																	</div>
																	<div class="border-bottom w-100 opacity-20"></div>
																	<div class="my-8 vouchers-value">
																		<p class="font-weight-bolder mb-0 font-size-lg">Voucher value</p>
																		<h1 class="font-weight-bolder">CA {{ $value->value }} </h1>
																	</div>
																	<div class="border-bottom w-100 opacity-20"></div>
																	<div class="my-8 vouchers-bottom">
																		<p class="mb-4 font-size-lg">Voucher Code : <span class="font-weight-bolder font-size-lg">XXXXXX</span></p>
																		@if($value->button==1)
																			<button class="isBookButton btn btn-light my-4 px-4">Book Now</button>
																		@endif
																		<p class="mb-1 font-weight-bold font-size-lg">Redeem on <span class="font-weight-bolder cursor-pointer" id="getServeses" data-target="#allServicesModal{{ $value->id }}" data-dismiss="modal">{{ count(explode(",", $value->services_ids)) }} services</span> <i class="fa fa-chevron-right icon-sm"></i>
																		</p>
																		<p class="mb-1 font-weight-bold font-size-lg">
																			@if($value->validfor == 'never') 
																				No Expiry Date
																			@else
																				Valid For {{ $value->validfor }}
																			@endif
																		</p>
																		<p class="mb-1 font-weight-bold font-size-lg">For multiple-use</p>
																		@csrf
																		<input type="text" name="email" id="emailid" value="{{ $value->id }}" hidden>
																	</div>
																</div>
																@if(!empty(trim($value->note)))
																	<p class="voucher-note-for-client">Notes from @if(!empty($locationId)) {{ $locationId->location_name }} @endif <br><span id="voucherPreviewNotes">{{ $value->note }}</span></p>
																@endif
															</div>
														</div>
														<div class="w-100">
															<div class="modal-header pb-0 border-0 d-flex">
																<div class="title">
																	<h2 class="card-text">CA ${{ $value->retailprice }} {{ $value->name }}</h2>
																	<h5 class="text-muted">Available in all locations • Sold {{ $value->numberofsales }}</h5>
																</div>
																<h3 class="cursor-pointer m-0 p-0" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
																</h3>
															</div>
															<div class="card-body p-4">
																@if (Auth::user()->can('manage_vouchers'))
																	<a href="{{ route('checkoutAppointment', $locationId->id.'/voucher/'.Crypt::encryptString($value->id)) }}" type="button" class="w-100 btn btn-primary">Sell Voucher</a>
																@else
																	<a href="javascript:;" type="button" class="no_access w-100 btn btn-primary">Sell Voucher</a>
																@endif
																<div class="my-8 row text-center"><!-- d-flex -->
																	@if (Auth::user()->can('manage_vouchers'))
																		<div class="col-md-4">
																			<button class="btn btn-outline-primary" data-url="{{ route('dupVoucher',$value->id) }}" id="duplicate">
																				<i class="fa fa-clone"></i>
																			</button>
																			<p class="font-size-lg font-weight-bolder">Duplicate Voucher</p>
																		</div>
																		<div class="col-md-4">
																			<a href="{{ route('editVoucher',$value->id) }}" class="btn btn-outline-primary">
																				<i class="fa fa-edit"></i>
																			</a>
																			<p class="font-size-lg font-weight-bolder">Edit Voucher</p>
																		</div>
																		<!-- <div class="col-md-4">
																			<button class="btn btn-outline-primary">
																				<i class="fa fa-bullhorn"></i>
																			</button>
																			<p class="font-size-lg font-weight-bolder">Promote Voucher</p>
																		</div> -->
																		<div class="col-md-4">
																			<button class="btn btn-danger" id="delete" data-url="{{ route('deleteVoucher',$value->id) }}">
																				<i class="fa fa-trash"></i>
																			</button>
																			<p class="font-size-lg font-weight-bolder">Delete Voucher</p>
																		</div>
																	@else
																		<div class="col-md-4">
																			<a href="javascript:;" class="no_access btn btn-outline-primary">
																				<i class="fa fa-clone"></i>
																			</a>
																			<p class="font-size-lg font-weight-bolder">Duplicate Voucher</p>
																		</div>
																		<div class="col-md-4">
																			<a href="javascript:;" class="no_access btn btn-outline-primary">
																				<i class="fa fa-edit"></i>
																			</a>
																			<p class="font-size-lg font-weight-bolder">Edit Voucher</p>
																		</div>
																		<!-- <div class="col-md-4">
																			<button class="btn btn-outline-primary">
																				<i class="fa fa-bullhorn"></i>
																			</button>
																			<p class="font-size-lg font-weight-bolder">Promote Voucher</p>
																		</div> -->
																		<div class="col-md-4">
																			<a href="javascript:;" class="no_access btn btn-outline-primary">
																				<i class="fa fa-trash"></i>
																			</a>
																			<p class="font-size-lg font-weight-bolder">Delete Voucher</p>
																		</div>
																	@endif	
																</div>
															</div>
															<div class="card-footer">
																<h3 class="font-weight-bolder">Note</h3>
																<p>Description Would be here</p>
															</div>
														</div>
													</div>
												</div>
											</div>
										{{-- end --}}

										{{-- ServiceModal --}}
<div class="modal fade" id="allServicesModal{{ $value->id }}">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <h4 class="modal-title font-weight-bolder">Redeemable services</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body pt-0 overflow-y">
				<h4 class="font-weight-bolder mb-4 mt-3 category-header">Hair Service</h4>                     
                  <div>   
				  @foreach(explode(",", $value->services_ids) as $tr) 
				  	@php

					@endphp
    					<span>{{$tr}}</span>
                                    <div class="border-bottom mb-3 pb-2">
                                        <span class="title d-flex justify-content-between">
                                            <h6 class="font-weight-bolder mb-1">Hair dry</h6>
                                            <h6 class="text-muted mb-1">From</h6>
                                        </span>
                                        <span class="title d-flex justify-content-between">
                                            <h6 class="text-muted">30min</h6>
                                            <h6 class="font-weight-bolder">&#8377; 150</h6>
                                        </span>
                                     </div>                    
 				 @endforeach                   
                </div> 
            </div> 
        </div>
    </div>
</div>

{{-- ServiceModal --}}

									</div>

									@endforeach
								</div>
							</div>
							<!--end::Body-->
						</div>
						<!--end::List Widget 3-->
					</div>
				</div>
				<!--end::Row-->

				<!--end::Sales-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>


	{{-- model --}}
	<div class="modal fade" id="learnMoreModal" tabindex="-1" role="dialog" aria-labelledby="learnMoreModal" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-body p-0 m-0">
					<button type="button" class="position-absolute close m-auto p-6 right-0 z-index" data-dismiss="modal" aria-label="Close" style="z-index: 99;font-size: 30px;">
						<span aria-hidden="true">×</span>
					</button>
					<div class="container p-0 m-0">
						<div class="row">
							<div class="col-12 col-md-6">
								<div class="p-10">
									<h1 class="font-weight-bolder my-18">Enable card payments for online booking
										and
										say
										goodbye
										to
										no shows!</h1>
									<h5 class="text-secondary my-18">Setup ScheduleDown Pay now to enable in-app
										payment
										processing,
										take
										back control of your
										calendar by charging no show and
										late cancellation fees to client cards</h5>
									<h5 class="text-secondary my-18">There are <span class="font-weight-bolder"><u>no
												additional
												fees</u></span> to use
										integrated
										payment processing features, it 's already included with ScheduleDown Plus.</h5>
									<div class="d-flex my-4">
										<button class="btn btn-primary mr-8">Setup ScheduleDown Pay</button>
										<a class="btn btn-white">Learn More</a>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6">
								<div class="learnMoreModalBackImg">
									<div class="owl-carousel owl-loaded owl-drag">
										<div class="">
											<div class="d-flex">
												<img alt="phone" src="{{ asset('/assets/images/phone.png') }}">
												<img alt="phone" class="position-absolute" src="{{ asset('/assets/images/chat.png') }}">
											</div>
											<div class="px-10">
												<h3 class="text-center mb-10 pt-0 px-20"><span
														class="font-weight-bolder">Protect
														from no
														shows</span> and late
													cancellations by charging client cards
												</h3>
											</div>
										</div>
										<div class="">
											<div class="d-flex">
												<img alt="phone" src="{{ asset('/assets/images/visa-phone.png') }}">
												<img alt="phone" class="position-absolute" src="{{ asset('/assets/images/visa.png') }}">
											</div>
											<div class="px-10">
												<h3 class="text-center mb-10 pt-0 px-20"><span
														class="font-weight-bolder">Integrated card processing</span> for
													easy and secure in-app payments
												</h3>
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
	</div>
	{{-- end --}}
	
<div class="modal fade" id="servicesModal">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header align-items-center">
                <h4 class="modal-title font-weight-bolder">Redeemable services</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body pt-0">
                @if(!empty($serviceCategory))
                    @foreach($serviceCategory as $key => $service)
                        <div>
                            @if(!empty($service))
                                @foreach($service as $sKey => $sValue)

                                    @if($sKey == 0) 
                                        <h4 class="font-weight-bolder mb-4 mt-3 category-header">{{ $sValue->category_title }}</h4>
                                    @endif

                                    <div class="border-bottom mb-3 pb-2">
                                        <span class="title d-flex justify-content-between">
                                            <h6 class="font-weight-bolder mb-1">{{ $sValue->service_name }}</h6>
                                            <h6 class="text-muted mb-1">{{ ($sValue->price_type == 'from' || ($sValue->is_staff_price == 1)) ? 'From' : '' }}</h6>
                                        </span>
                                        <span class="title d-flex justify-content-between">
                                            <h6 class="text-muted">{{ $sValue->serviceDuration }}</h6>
                                            <h6 class="font-weight-bolder">&#8377; {{ $sValue->service_price_special_amount }}</h6>
                                        </span>

                                        @if($sValue->service_price_special_amount < $sValue->service_price_amount)
                                            <span class="title d-flex justify-content-end"> 
                                                <h6 class="text-muted"><strike>&#8377; {{ $sValue->service_price_amount }}</strike></h6>
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            @endif
                        </div> 
                    @endforeach
                @endif
            </div> 
        </div>
    </div>
</div>

	<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<form class="form" method="POST">
					@csrf
					<div class="modal-header">
						<h5 class="modal-title" id="exampleModalLabel">Delete Voucher</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close"></i>
						</button>
					</div>
					<div class="modal-body">Are you sure you want to delete this voucher? Vouchers that have been sold will still be valid.</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Cancel</button>
						<button type="button" class="btn btn-danger font-weight-bold" data-url="" id="deletevoucher">Yes, Delete</button>
					</div>
				</form>	
			</div>
		</div>
	</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('assets/js/owl.js') }}"></script>

<script>
	$(document).ready(function () {
		$('.owl-carousel').owlCarousel({
			center: true,
			items: 1,
			dots: true,
			loop: false,
			margin: 10
		});
	});
</script>
<script>
 {{-- duplicate voucher --}}
	$(document).on('click','#duplicate', function(){

		var url = $(this).data('url');

		$.ajax({
			type: "get",
			url: url,
			success: function (data) {
				KTApp.unblockPage();
				toastr.success(data.message);
				window.location.href = "{{ route('voucherindex') }}";
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
{{-- end --}}

//Get Serveses voucher
$(document).on('click','#getServeses', function() 
{
	var putid = $(this).closest('div').find('#emailid').val();
	$.ajax({
		type: "POST",
		url: "{{ route('getSer') }}",
		dataType: 'json',
		contentType: 'application/x-www-form-urlencoded',
		data:{
			"_token": "{{ csrf_token() }}",
			serviceId:putid
		},
		success: function(dataResult)
        {
			$('#servicesModal .modal-body').html(dataResult.content);
		    $("#servicesModal").modal("toggle");




          if(dataResult.status)
          {
            // location.reload();
            
          } 
          else{
            alert(dataResult.message);
          }              
       },
        error: function(XMLHttpRequest, textStatus, errorThrown) 
        { 
          alert("Status: " + textStatus); 
          alert("Error: " + errorThrown); 
        }       
	});

	
});


//delete voucher
$(document).on('click','#delete', function() {
	$('#deletevoucher').attr('data-url', $(this).attr('data-url'));
	$('#confirmModal').modal('show');
});

$('#confirmModal').on('hidden.bs.modal', function(){
	$('#deletevoucher').attr('data-url', '');
});

$(document).on("click", '#deletevoucher', function() {
	var url = $(this).attr('data-url');
	KTApp.unblockPage();
	
	$.ajax({
		type: "get",
		url: url,
		dataType: 'json',
		success: function(data) {
			KTApp.unblockPage();

			if (data.status == true) {
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
					window.location.href = "{{ route('voucherindex') }}";
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
//end
</script>
@endsection