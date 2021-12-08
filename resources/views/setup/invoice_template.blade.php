{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
	
@endsection

@section('content')
	
	<!--begin::Header-->
	<div id="kt_header" class="header header-fixed">
		<!--begin::Container-->
		<div class="container-fluid d-flex align-items-stretch justify-content-between">
			<!--begin::Header Menu Wrapper-->
			<div class="header-menu-wrapper header-menu-wrapper-left" id="kt_header_menu_wrapper">
				<!--begin::Header Menu-->
				<div id="kt_header_menu" class="header-menu header-menu-mobile header-menu-layout-default">
					<h2 style="line-height: 65px;">Setup</h2>
				</div>
				<!--end::Header Menu-->
			</div>
			<!--end::Header Menu Wrapper-->
			<!--begin::Topbar-->
			<div class="topbar">
				<!--begin::Search-->
				<div class="dropdown" id="kt_quick_search_toggle">
					<!--begin::Toggle-->
					<div class="topbar-item" data-toggle="" data-offset="10px,0px">
						<div class="btn btn-icon btn-clean btn-lg btn-dropdown mr-1">
							<span class="svg-icon svg-icon-xl svg-icon-primary" data-toggle="modal"
								data-target="#searchModal">
								<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
								<svg xmlns="http://www.w3.org/2000/svg"
									xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
									viewBox="0 0 24 24" version="1.1">
									<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
										<rect x="0" y="0" width="24" height="24" />
										<path
											d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
											fill="#000000" fill-rule="nonzero" opacity="0.3" />
										<path
											d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
											fill="#000000" fill-rule="nonzero" />
									</g>
								</svg>
								<!--end::Svg Icon-->
							</span>
						</div>
					</div>
					<!--end::Toggle-->
					<!--begin::Dropdown-->
					<div
						class="dropdown-menu p-0 m-0 dropdown-menu-right dropdown-menu-anim-up dropdown-menu-lg">
						<div class="quick-search quick-search-dropdown" id="kt_quick_search_dropdown">
							<!--begin:Form-->
							<form method="get" class="quick-search-form">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											<span class="svg-icon svg-icon-lg">
												<!--begin::Svg Icon | path:assets/media/svg/icons/General/Search.svg-->
												<svg xmlns="http://www.w3.org/2000/svg"
													xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
													height="24px" viewBox="0 0 24 24" version="1.1">
													<g stroke="none" stroke-width="1" fill="none"
														fill-rule="evenodd">
														<rect x="0" y="0" width="24" height="24" />
														<path
															d="M14.2928932,16.7071068 C13.9023689,16.3165825 13.9023689,15.6834175 14.2928932,15.2928932 C14.6834175,14.9023689 15.3165825,14.9023689 15.7071068,15.2928932 L19.7071068,19.2928932 C20.0976311,19.6834175 20.0976311,20.3165825 19.7071068,20.7071068 C19.3165825,21.0976311 18.6834175,21.0976311 18.2928932,20.7071068 L14.2928932,16.7071068 Z"
															fill="#000000" fill-rule="nonzero"
															opacity="0.3" />
														<path
															d="M11,16 C13.7614237,16 16,13.7614237 16,11 C16,8.23857625 13.7614237,6 11,6 C8.23857625,6 6,8.23857625 6,11 C6,13.7614237 8.23857625,16 11,16 Z M11,18 C7.13400675,18 4,14.8659932 4,11 C4,7.13400675 7.13400675,4 11,4 C14.8659932,4 18,7.13400675 18,11 C18,14.8659932 14.8659932,18 11,18 Z"
															fill="#000000" fill-rule="nonzero" />
													</g>
												</svg>
												<!--end::Svg Icon-->
											</span>
										</span>
									</div>
									<input type="text" class="form-control" placeholder="Search..." />
									<div class="input-group-append">
										<span class="input-group-text">
											<i
												class="quick-search-close ki ki-close icon-sm text-muted"></i>
										</span>
									</div>
								</div>
							</form>
							<!--end::Form-->
							<!--begin::Scroll-->
							<div class="quick-search-wrapper scroll" data-scroll="true" data-height="325"
								data-mobile-height="200"></div>
							<!--end::Scroll-->
						</div>
					</div>
					<!--end::Dropdown-->
				</div>
				<!--end::Search-->
				<!--begin::Quick panel-->
				<div class="topbar-item">
					<div class="btn btn-icon btn-clean btn-lg mr-1" id="kt_quick_panel_toggle">
						<span class="svg-icon svg-icon-xl svg-icon-primary">
							<!--begin::Svg Icon | path:assets/media/svg/icons/Layout/Layout-4-blocks.svg-->
							<svg xmlns="http://www.w3.org/2000/svg"
								xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px"
								viewBox="0 0 24 24" version="1.1">
								<g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
									<rect x="0" y="0" width="24" height="24" />
									<rect fill="#000000" x="4" y="4" width="7" height="7" rx="1.5" />
									<path
										d="M5.5,13 L9.5,13 C10.3284271,13 11,13.6715729 11,14.5 L11,18.5 C11,19.3284271 10.3284271,20 9.5,20 L5.5,20 C4.67157288,20 4,19.3284271 4,18.5 L4,14.5 C4,13.6715729 4.67157288,13 5.5,13 Z M14.5,4 L18.5,4 C19.3284271,4 20,4.67157288 20,5.5 L20,9.5 C20,10.3284271 19.3284271,11 18.5,11 L14.5,11 C13.6715729,11 13,10.3284271 13,9.5 L13,5.5 C13,4.67157288 13.6715729,4 14.5,4 Z M14.5,13 L18.5,13 C19.3284271,13 20,13.6715729 20,14.5 L20,18.5 C20,19.3284271 19.3284271,20 18.5,20 L14.5,20 C13.6715729,20 13,19.3284271 13,18.5 L13,14.5 C13,13.6715729 13.6715729,13 14.5,13 Z"
										fill="#000000" opacity="0.3" />
								</g>
							</svg>
							<!--end::Svg Icon-->
						</span>
					</div>
				</div>
				<!--end::Quick panel-->
				<!--begin::User-->
				<div class="topbar-item">
					<div class="btn btn-icon btn-icon-mobile w-auto btn-clean d-flex align-items-center btn-lg px-2"
						id="kt_quick_user_toggle">
						<span class="symbol symbol-lg-35 symbol-25 symbol-light-success">
							<span class="symbol-label font-size-h5 font-weight-bold">A</span>
						</span>
					</div>
				</div>
				<!--end::User-->
			</div>
			<!--end::Topbar-->
		</div>
		<!--end::Container-->
	</div>
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
						<form name="invoceTem" id="invoceTem" action="{{ route('invoice_template') }}" >
							@csrf
							<div class="card-custom card-stretch gutter-b">
								<!--begin::Body-->
								<!--begin::Item-->
								<div class="">
									<div>
										<a class="text-blue" href="{{ route('setup') }}">
											<h4>Back to setup</h4>
										</a>
										<h2 class="font-weight-bolder">Invoice Template</h2>
										<h6 class="text-dark-50">
											Configure details displayed on invoices issued to your clients
										</h6>
									</div>
									<div class="mt-6 mb-2 row">
										<div class="col-12 col-md-5">
											<h3>Invoice Settings</h3>
											<h6 class="text-dark-50">
												Customize the content displayed on invoices issued to your
												clients
											</h6>
										</div>
										<div class="col-12 col-md-7">
											<div class="card">
												<div class="card-body">
													<div class="form-group">
														<div class="checkbox-list mt-8">
															<label class="checkbox font-weight-bolder">
																<input  id="autoPrint" name="autoPrint" type="checkbox" @if(isset($it->autoPrint) && $it->autoPrint==1)checked @endif >
																<span></span> Automatically print receipts upon
																sale completion
															</label>
														</div>
													</div>
													<div class="form-group">
														<div class="checkbox-list mt-8">
															<label class="checkbox font-weight-bolder">
																<input id="showMobile" name="showMobile" type="checkbox" @if(isset($it->showMobile) && $it->showMobile==1)checked @endif >
																<span></span> Show client mobile and email on
																invoices
															</label>
														</div>
													</div>
													<div class="form-group">
														<div class="checkbox-list mt-8">
															<label class="checkbox font-weight-bolder">
																<input id="showAddress" name="showAddress" type="checkbox" @if(isset($it->showAddress) && $it->showAddress==1)checked @endif >
																<span></span> Show client address on invoices
															</label>
														</div>
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Invoice Title</label>
														<input type="text" placeholder="Enter Invoice Title" @if(isset($it->title))value="{{ $it->title }}"@endif
															name="title" class="form-control">
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Receipt Custom Line
															1</label>
														<input type="text" placeholder="Enter Receipt Custom Line 1" name="receiptLine1" value="@if(isset($it->receiptLine1)){{ $it->receiptLine1 }} @endif" class="form-control">
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Receipt Custom Line
															2</label>
														<input type="text" placeholder="Enter Receipt Custom Line 2" name="receiptLine2" value="@if(isset($it->receiptLine2)){{ $it->receiptLine2 }} @endif" class="form-control">
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Receipt Footer</label>
														<input type="text" placeholder="Enter Receipt Footer" name="receiptfooter" value="@if(isset($it->receiptfooter)){{ $it->receiptfooter }} @endif" class="form-control">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<!--end:Item-->
								<!--end::Body-->
							</div>
							<button type="submit" class="btn btn-primary" id="update" style="float: right" >Save changes</button>
						</form>
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
<script src="{{ asset('js/addInvoiceTemplate.js') }}"></script>

{{-- <script>
	$(document).on('click','#update', function(){
		var form = $("#invoceTem");
		$.ajax({
			type: "POST",
			url: "",
			headers:{
						'X-CSRF-Token': '{{ csrf_token() }}',
					},
			data: form.serialize(),
			success: function (data) {
				console.log(data);
				KTApp.unblockPage();
				toastr.success(data.message);
				window.location.href = "{{url('/setup')}}";
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
</script> --}}
@endsection