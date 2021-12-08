{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
	<link href="{{ asset('assets/css/owl.css') }}" rel="stylesheet" type="text/css" />
	<style>
		.new{
			padding: 25px 25px;
		}
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
			
	<!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content" style="background: #FFF;">
		<!--begin::Tabs-->
		<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
			<div
				class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
				<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
					role="tablist">
					<li class="nav-item">
						<a class="nav-link" href="{{ route('service') }}">Services menu</a>
					</li>
					<!-- <li class="nav-item">
						<a class="nav-link active" href="{{ route('plans') }}">Paid plans</a>
					</li> -->
				</ul>
			</div>
		</div>
		<!--end::Tabs-->
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container new">
				<!--begin::Sales-->
				<!--begin::Row-->
				<div class="content-header ">
					<div class="paid-plans-header rounded">
						<h2 class="text-dark font-weight-bolder mb-4">Set up payments to sell paid plans
							online
							and increase
							sales</h2>
						<p class="font-size-lg">Congrats! You have ScheduleDown Plus enabled. Let clients
							buy
							your
							paid plans online
							through the ScheduleDown marketplace and via
							direct booking links by setting up payments in your account.</p>
						<button class="btn btn-white" data-toggle="modal"
							data-target="#learnMoreModal">Learn More</button>
					</div>
				</div>
				<div class="row">
					<div class="col-sm-12 col-lg-12 col-xxl-12">
						<!--begin::List Widget 3-->
						<div class="">
							<!--begin::Body-->
							<div class="content-header">
								<div class="action-btn d-flex justify-content-end my-8">
									<button class="btn btn-white mr-4" data-toggle="modal"
										data-target="#sellPaidPlansModal"><i class="fas fa-link"></i>
										Sell paid plans online</button>
									<a href="{{ route('addPlans') }}" class="btn btn-primary">Create paid
										plan</a>
								</div>
							</div>
							<div class="container">
								<div class="row">
									@foreach($plan as $value)
									<div class="col-6">
										<a href="{{ route('editPlans',$value->id) }}">
											<div class="card {{ $value->color }}">
												<div class="card-body text-white p-6">
													<div class="d-flex my-3">
														<span><i class="mr-2 fas fa-business-time icon-lg text-white"></i></span>
														<h6>{{ $value->valid_for }} plan</h6>
													</div>
													<h2 class="mb-6 font-weight-bolder">{{ $value->name }}</h2>
													<div class="font-weight-bold d-flex justify-content-between">
														<div>
															<h5>{{ $value->sessions_num }} session</h5>
															<h5>{{ count(explode(",", $value->services_ids)) }} service</h5>
														</div>
														<div>
															<h5>CA ${{ $value->price }}</h5>
														</div>
													</div>
												</div>
											</div>
										</a>
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
	<!--end::Content-->
	
	<!-- Modal -->
	<div class="modal fade" id="learnMoreModal" tabindex="-1" role="dialog" aria-labelledby="learnMoreModal"
		aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-body p-0 m-0">
					<button type="button" class="position-absolute close m-auto p-6 right-0 z-index"
						data-dismiss="modal" aria-label="Close" style="z-index: 99;font-size: 30px;">
						<span aria-hidden="true">&times;</span>
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
												fees</u></span> touse
										integrated
										payment processing features, it s already included with ScheduleDown Plus.</h5>
									<div class="d-flex my-4">
										<button class="btn btn-primary mr-8">Setup ScheduleDown Pay</button>
										<a class="btn btn-white">Learn More</a>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6">
								<div class="learnMoreModalBackImg">
									<div class="owl-carousel">
										<div class="">
											<div class="d-flex">
												<img alt="phone" src="{{ url('/public/assets/images/phone.png') }}" />
												<img alt="phone" class="position-absolute"
													src="{{ url('/public/assets/images/chat.png') }}" />
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
												<img alt="phone" src="{{ url('/public/assets/images/visa-phone.png') }}" />
												<img alt="phone" class="position-absolute"
													src="{{ url('/public/assets/images/visa.png')}}" />
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
	<!-- Modal -->
	<div class="modal fade" id="sellPaidPlansModal" tabindex="-1" role="dialog"
		aria-labelledby="sellPaidPlansModalLabel" aria-hidden="true">
		<div class="modal-dialog w-50" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="sellPaidPlansModalLabel">Share your paid plans</h5>
					<button style="font-size: 32px;" class="p-0 m-0 bg-transparent btn btn-sm font-size-lg text-dark"
						data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true">&times;</span>
					</button>
				</div>
				<div class="modal-body">
					<p>Copy a link to share your available paid plans with your clients on your website and social media
						pages</p>
					<div class="form-group">
						<label class="font-weight-bolder">Paid plans link</label>
						<div class="input-group input-group-lg">
							<div class="input-group-prepend">
								<span class="input-group-text">
									URL
								</span>
							</div>
							<input id="urlLink" type="text" class="form-control" placeholder="URl" disabled
								value="https://www.ScheduleDown .com/paid-plans/provider/kwtglfg6?pId=429198">
						</div>
					</div>
					<button class="btn btn-primary w-100" data-clipboard="true" data-clipboard-target="#urlLink">
						<i class="la la-link"></i>Copy link</button>
					<div class="my-4 alert alert-blue" role="alert">
						<div class="d-flex">
							<span class="my-icon-svg p-2">
								<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
									<path
										d="M5.91 10.403A6.75 6.75 0 0119 12.752a6.724 6.724 0 01-2.942 5.544l-.058.037v4.917a.75.75 0 01-.648.743L15.25 24h-6a.75.75 0 01-.75-.75v-4.917l-.06-.04a6.75 6.75 0 01-2.613-7.646zm7.268-2.849a5.25 5.25 0 00-3.553 9.714.75.75 0 01.375.65v4.581h4.5v-4.581a.75.75 0 01.282-.587l.095-.064a5.224 5.224 0 002.623-4.52 5.25 5.25 0 00-4.322-5.193zM22.75 12a.75.75 0 01.102 1.493l-.102.007h-1.5a.75.75 0 01-.102-1.493L21.25 12h1.5zm-19.5 0a.75.75 0 01.102 1.493l-.102.007h-1.5a.75.75 0 01-.102-1.493L1.75 12h1.5zm.96-8.338l.085.072 2.12 2.121a.75.75 0 01-.976 1.133l-.084-.072-2.12-2.121a.75.75 0 01.976-1.133zm17.056.072a.75.75 0 01.072.977l-.072.084-2.121 2.12a.75.75 0 01-1.133-.976l.072-.084 2.121-2.12a.75.75 0 011.06 0zM12.25 0a.75.75 0 01.743.648L13 .75v3a.75.75 0 01-1.493.102L11.5 3.75v-3a.75.75 0 01.75-.75z"
										fill="#101928"></path>
								</svg>
							</span>
							<div class="p-2">
								<h5>Set up online payments to share your paid plans online.
									<a href="card_proccessing" class="text-blue">Learn More</a>
								</h5>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				</div>
			</div>
		</div>
	</div>
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
	
	<!--begin::Page Scripts(used by this page)-->
	<script src="{{ asset('assets/js/pages/widgets.js')}}"></script>
	<!--end::Page Scripts-->
	<script src="{{ asset('assets/js/pages/crud/forms/widgets/clipboard.js')}}"></script>
	<!-- Slider Carousel -->
	<script src="{{ asset('assets/js/owl.js')}}"></script>
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
@endsection