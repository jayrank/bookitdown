{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
	
@endsection

@section('content')
	
	<!--begin::Wrapper-->
		<!--begin::Header-->
		
		<!--end::Header-->
		<!--begin::Content-->
		<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
			<!--begin::Tabs-->
			<!-- <div class="subheader py-2 py-lg-4 subheader-solid" id="kt_subheader">

			</div> -->
			<!--end::Tabs-->
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
								<div class="">
									<div>
										<a class="text-blue" href="{{ route('setup') }}">
											<h4>Back to setup</h4>
										</a>
										<h2 class="font-weight-bolder">Invoice Sequencing</h2>
										<h6 class="text-dark-50">
											Set custom prefixes and number sequence for invoices
										</h6>
									</div>
									<div class="mt-6 mb-2 row">
										<div class="col-12">
											<div class="card">
												<table class="table table-hover" id="invoiceL">
													<thead>
														<tr>
															<th scope="col">Location Name</th>
															<th scope="col">Invoice No. Prefix</th>
															<th scope="col">Next Invoice Number</th>
															<th>Action</th>
														</tr>
													</thead>
													<tbody>
														@foreach ($loc as $location)
														<tr>
															@php
																$is = App\Models\InvoiceSequencing::where('user_id',Auth::id())->where('location_id',$location->id)->first();
															@endphp
															<td>{{ $location->location_name }}</td>
															<td>@if(isset($is->invoice_no_prefix)){{ $is->invoice_no_prefix }}@else - @endif</td>
															<td>@if(isset($is->next_invoice_number)){{ $is->next_invoice_number }}@else - @endif</td>
															<td class="cursor-pointer" data-toggle="modal"
																data-target="#changeIndexModal{{ $location->id }}">
																<i class="fa fa-edit"></i> Change
															</td>
														</tr>
														<!-- Modal -->
														<div class="modal fade" id="changeIndexModal{{ $location->id }}" tabindex="-1" role="dialog" aria-labelledby="changeIndexModalLabel"
															aria-hidden="true">
															<div class="modal-dialog" role="document">
																<form name="sequencing" id="sequencing{{ $location->id }}" action="{{ route('Invoice_sequencing') }}" >
																	@csrf
																	<div class="modal-content">
																		<div class="modal-header">
																			<h5 class="modal-title" id="changeIndexModalLabel">New Resource</h5>
																			<p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i
																					class="text-dark fa fa-times icon-lg"></i>
																			</p>
																		</div>
																		<div class="modal-body">
																			<h5 class="text-dakr-50">You are about to introduce this change for Gym Zone</h5>
																			<div class="mt-3 d-flex flex-wrap justify-content-between">
																				<div class="form-group">
																					<label class="font-weight-bolder">Invoice No. Prefix</label>
																					<input type="hidden" name="id" value="{{ $location->id }}">
																					<input type="text" class="form-control" required name="invoice_no_prefix" value="@if(isset($is->invoice_no_prefix)){{ $is->invoice_no_prefix }}@endif" placeholder="">
																				</div>
																				<div class="form-group">
																					<label class="font-weight-bolder">Next Invoice Number</label>
																					<input type="text" name="next_invoice_number" value="@if(isset($is->next_invoice_number)){{ $is->next_invoice_number }}@endif" class="form-control">
																				</div>
																			</div>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
																			<button type="button" class="btn btn-primary save" data-id="{{ $location->id }}" >Save</button>
																		</div>
																	</div>
																</form>
															</div>
														</div>
														@endforeach
													</tbody>
												</table>
											</div>
										</div>
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
<script src="{{ asset('js/addInvoiceSequencing.js') }}"></script>

<script>
	$(document).on('click','.save', function(){
		var id = $(this).data('id');
		var form = $("#sequencing"+id);
		$.ajax({
			type: "POST",
			url: "{{ route('Invoice_sequencing') }}",
			headers:{
						'X-CSRF-Token': '{{ csrf_token() }}',
					},
			data: form.serialize(),
			success: function (data) {
				KTApp.unblockPage();
				toastr.success(data.message);
				$('#changeIndexModal'+id).modal('hide');
				location.reload();
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