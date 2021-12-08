@extends('layouts.index')
@section('innercss')
@endsection
@section('content')
<!-- Start Content here -->

<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
	<!--begin::Tabs-->
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div
			class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5" role="tablist">
				<!-- <li class="nav-item">
					<a class="nav-link" href="{{ url('partners/marketing') }}">Overview</a>
				</li> -->
				<li class="nav-item">
					<a class="nav-link" href="{{ route('smart_campaigns') }}">Smart Campaigns</a>
				</li>
				<li class="nav-item">
					<a class="nav-link active" href="{{ url('partners/marketing/marketing_blast_messages') }}">Blast messages</a>
				</li>
			</ul>
		</div>
	</div>
	<!--end::Tabs-->
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid mt-10">
		<div class="container">
			<div class="row">
				<div class="blast-messages w-100">
					<div class="p-6">
						<div class="d-flex justify-content-between my-6">
							<div>
								<h2 class="font-weight-bolder mb-3">
									<a href="{{ url('partners/marketing/marketing_blast_messages') }}"><i class="la la-1x la-arrow-left mr-4 text-dark"></i></a>{{ ($getOverview['message_name']) ? $getOverview['message_name'] : '' }}
									
									<div class="label label-light-success label-inline text-uppercase ml-2">Sent</div>
								</h2>
								<h6 class="mb-4">Sent on {{ ($getOverview['get_group_email_blast'][0]['created_at']) ? date("jS M Y",strtotime($getOverview['get_group_email_blast'][0]['created_at'])) : '' }} at {{ ($getOverview['get_group_email_blast'][0]['created_at']) ? date("h:ia",strtotime($getOverview['get_group_email_blast'][0]['created_at'])) : '' }} to {{ ($getOverview['client_type']) ? $getOverview['client_type'] : '' }}</h6>
							</div>
							<div class="dropdown dropdown-inline mr-2">
								<button type="button" class="btn btn-primary my-2 dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Options</button>
								<div class="dropdown-menu dropdown-menu-right p-0" style="">
									<ul class="listgroup navi flex-column navi-hover">
										<li class="list-group-item navi-item">
											<a href="javascript:;" class="navi-link" id="cloneThisblast" data-thisId="{{ ($getOverview['id']) ? $getOverview['id'] : '' }}">
												<span class="navi-text">Duplicate</span>
											</a>
										</li>
										<li class="list-group-item navi-item">
											<a href="#" data-toggle="modal" data-target="#blastMessagePreview" class="navi-link">
												<span class="navi-text">Preview</span>
											</a>
										</li>
									</ul>
								</div>
							</div> 
						</div> 
					</div>
				</div>
				<div class="col-12">
					<div class="content-header text-white mb-10">
						<div class="voucher-header rounded p-10" style="background-image: url({{ asset('assets/images/blastOverview.png') }}), linear-gradient(to right, #037aff 25%, #4ea1ff 75%);background-size: contain;">
							<h2 class="font-weight-bolder mb-4">Reach more clients through text message</h2>
							<p class="font-size-lg">Send Gym area is now open! as a text message to increase the reach and effectiveness of your blast message.</p>
							<a href="{{ url('partners/marketing/add_sms_text_message') }}" class="btn btn-white">Creat text message</a>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-3">
					<div class="card border-0">
						<div class="card-body p-4 shadow-sm">
							<div class="d-flex align-items-center justify-content-between">
								<div>
									<h6 class="font-weight-bolder mb-0">Sent to</h6>
								</div>
								<span style="width: 32px;height:32px">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
										<g fill="none" fill-rule="evenodd">
											<rect fill="#4EA1FF" width="32" height="32" rx="16">
											</rect>
											<path
												d="M22.671 8.193l-2.687 13.438a.8.8 0 01-1.174.543l-.09-.06-3.466-2.599-3.288 3.838.004-6.818.22-.147a.496.496 0 01.017-.01l3.882-2.594a.5.5 0 01.626.775l-.07.057-3.31 2.209 5.73 4.299 2.263-11.317-11.317 4.526 1.024.768a.5.5 0 01.148.623l-.048.077a.5.5 0 01-.623.147l-.077-.047-1.308-.98a.8.8 0 01.085-1.336l.098-.048 13.361-5.344zm-9.702 9.609l-.002 2.845 1.485-1.733-1.483-1.112z"
												fill="#FFF"></path>
										</g>
									</svg>
								</span>
							</div>
							<h2 class="mb-6">{{ $TotalBlast }}</h2>
							<div class="progressbar">
								<h6 class="text-muted">100%</h6>
								<div class="progress">
									<div class="progress-bar bg-blue" role="progressbar"
										style="width: 100%;" aria-valuenow="100" aria-valuemin="0"
										aria-valuemax="100">100%
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-3">
					<div class="card border-0">
						<div class="card-body p-4 shadow-sm">
							<div class="d-flex align-items-center justify-content-between">
								<div>
									<h6 class="font-weight-bolder mb-0">Delivered to</h6>
								</div>
								<span style="width: 32px;height:32px">
									<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
										<g fill="none" fill-rule="evenodd">
											<rect fill="#60C290" width="32" height="32" rx="16"></rect>
											<path
												d="M19.809 10.114a.556.556 0 01.133.667l-.049.08L11.677 22l-3.54-4a.557.557 0 01.02-.754.477.477 0 01.64-.041l.067.064 2.744 3.1 7.499-10.165a.48.48 0 01.702-.09zm4.008.006c.19.166.236.45.12.67l-.05.08-8.535 11.112-1.209-1.312a.557.557 0 01.007-.753.477.477 0 01.638-.055l.069.062.427.464 7.829-10.193a.48.48 0 01.704-.075z"
												fill="#FFF"></path>
										</g>
									</svg>
								</span>
							</div>
							<h2 class="mb-6">{{ $TotalDelivered }}</h2>
							<div class="progressbar">
								<h6 class="text-muted">{{ $TotalDeliveredPer }}%</h6>
								<div class="progress">
									<div class="progress-bar bg-success" role="progressbar" style="width: {{ $TotalDeliveredPer }}%;"
										aria-valuenow="{{ $TotalDeliveredPer }}" aria-valuemin="0" aria-valuemax="100">{{ $TotalDeliveredPer }}%
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-3">
					<div class="card border-0">
						<div class="card-body p-4 shadow-sm">
							<div class="d-flex align-items-center justify-content-between">
								<div>
									<h6 class="font-weight-bolder mb-0">Opened by</h6>
								</div>
								<span style="width: 32px;height:32px">
									<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32"><defs></defs><g fill="none" fill-rule="evenodd"><rect fill="#6C4BF6" width="32" height="32" rx="16"></rect><path d="M24 13.667V22.5a1.5 1.5 0 01-1.5 1.5h-13A1.5 1.5 0 018 22.5v-8.833l8 4.265 8-4.265zm-1 1.666l-7 3.734-7-3.733V22.5a.5.5 0 00.41.492L9.5 23h13a.5.5 0 00.5-.5v-7.167zM18.5 14a.5.5 0 01.09.992L18.5 15h-5a.5.5 0 01-.09-.992L13.5 14h5zm2-6a1.5 1.5 0 011.493 1.356L22 9.5V13a.5.5 0 01-.992.09L21 13V9.5a.5.5 0 00-.41-.492L20.5 9h-9a.5.5 0 00-.492.41L11 9.5V13a.5.5 0 01-.992.09L10 13V9.5a1.5 1.5 0 011.356-1.493L11.5 8h9zm-2 3a.5.5 0 01.09.992L18.5 12h-5a.5.5 0 01-.09-.992L13.5 11h5z" fill="#FFF"></path></g></svg>
								</span>
							</div>
							<h2 class="mb-6">{{ $TotalOpened }}</h2>
							<div class="progressbar">
								<h6 class="text-muted">{{ $TotalOpenedPer }}%</h6>
								<div class="progress">
									<div class="progress-bar bg-info" role="progressbar" style="width: {{ $TotalOpenedPer }}%;" aria-valuenow="{{ $TotalOpenedPer }}" aria-valuemin="0" aria-valuemax="100">{{ $TotalOpenedPer }}%
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-3">
					<div class="card border-0">
						<div class="card-body p-4 shadow-sm">
							<div class="d-flex align-items-center justify-content-between">
								<div>
									<h6 class="font-weight-bolder mb-0">Link clicked by</h6>
								</div>
								<span style="width: 32px;height:32px">
									<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><g fill="none" fill-rule="evenodd"><rect fill="#FFC830" width="32" height="32" rx="16"></rect><g stroke="#FFF" stroke-linecap="round" stroke-width="1.5"><path d="M18.737 8.421s-2.526-.842-4.21.842l-5.053 5.053c-1.685 1.684-.842 4.21-.842 4.21M22.947 12.632s.842 2.526-.842 4.21l-5.052 5.053c-1.685 1.684-4.21.842-4.21.842M13.053 18.105L8 23.158M23.368 8l-5.052 5.053"></path></g></g></svg>
								</span>
							</div>
							<h2 class="mb-6">{{ $TotalClicked }}</h2>
							<div class="progressbar">
								<h6 class="text-muted">{{ $TotalClickedPer }}%</h6>
								<div class="progress">
									<div class="progress-bar" role="progressbar" style="width: {{ $TotalClickedPer }}%;"
										aria-valuenow="{{ $TotalClickedPer }}" aria-valuemin="0" aria-valuemax="100">{{ $TotalClickedPer }}%
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-6 mt-8">
					<div class="card shadow-sm border-0">
						<div class="card-body">
							<h6 class="font-weight-bolder">Total booking value</h6>
							<h2 class="font-weight-bolder mb-6">CA $0</h2>
							<p class="text-muted m-0">0 appointments booked</p>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-6 mt-8">
					<div class="card shadow-sm border-0">
						<div class="card-body">
							<h6 class="font-weight-bolder">Total sales</h6>
							<h2 class="font-weight-bolder mb-6">CA $0</h2>
							<p class="text-muted m-0">0 sales</p>
						</div>
					</div>
				</div>
				<div class="col-12 mt-8">
					<div class="card shadow-sm border-0">
						<div class="card-header">
							<h2 class="font-weight-bolder mb-0">Blast message details</h2>
						</div>
						<div class="card-body">
							<div class="row">
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Message type</h6>
									<h4>{{ ($getOverview['message_type_text']) ? $getOverview['message_type_text'] : '' }}</h4> 
								</div>
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Channel</h6>
									<h4>Email</h4> 
								</div>
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Sent to</h6>
									<h4>{{ ($getOverview['client_type']) ? $getOverview['client_type'] : '' }}</h4> 
								</div>
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Total cost</h6>
									<h4>CA ${{ ($getOverview['total_payable_price']) ? $getOverview['total_payable_price'] : '' }} (inc. GST 5%)</h4> 
								</div>
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Payment method</h6>
									<h4><i class="fa fa-wallet mr-3"></i>{{ ($paymentResponse['card_brand']) ? $paymentResponse['card_brand'] : '' }} •••• {{ ($paymentResponse['card_last4']) ? $paymentResponse['card_last4'] : '' }}</h4> 
								</div>
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Created by</h6>
									@if($getOverview['get_creator'] != null)
										<h4>{{ $getOverview['get_creator'] != null ? $getOverview['get_creator']['first_name'] : '' }} {{ ($getOverview['get_creator']['last_name']) ? $getOverview['get_creator']['last_name'] : '' }}</h4> 
									@endif
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!--end::Row-->
			<!--end::Sales-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->

	<!--begin::Modal-->
	<div class="modal fade" id="blastMessagePreview" tabindex="-1" role="dialog" aria-labeledby="blastMessagePreviewModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h2 class="mb-0 font-weight-bolder">Email Preview</h2>
                    <button type="button" class="close" data-dismiss="modal"><i class="la la-times icon-lg"></i></button>
				</div>
				<div class="modal-body">
					 <div class="card">
						<div class="card-body" id="smartCampaignPreview"> 
							<h3>{{ ($locationData[0]['location_name']) ? $locationData[0]['location_name'] : '' }}</h3>
							<p class="text-dark-50 m-0">{{ count($locationData) }} Locations</p> 
							<div class="card-img" style="margin:40px auto;height:auto;width:100px">
								<img alt="img" id="img-preview" width="100%" height="auto" class="rounded" src="https://schedulethat.tjcg.in/public/uploads/schedule_library/campaign_default.png" />
							</div>
							<h1 class="font-weight-bolder headline-text mb-10">{{ ($getOverview['title']) ? $getOverview['title'] : '' }}</h1>
							<h6 class="text-dark-50 my-4 body-text">
								{{ ($getOverview['message']) ? $getOverview['message'] : '' }}
							</h6>
							@if($getOverview['is_button'] == 1)
								<hr class="my-12">
								<div class="w-50 m-auto">
									<button class="text-uppercase btn btn-dark btn-block">Book Now</button>							
								</div>	
							@elseif($getOverview['is_button'] == 2)
								<hr class="my-12">
								<div class="w-50 m-auto">
									<button class="text-uppercase btn btn-dark btn-block">{{ $getOverview['button_text'] }}</button>							
								</div>	
							@endif
						</div>
					</div>
					<p class="text-center mt-10 mb-2">Powered by Schedulethat.com</p>
					<p class="text-center mb-0">Unsubscribe from promotions</p>
				</div>
			</div>
		</div>
	</div>
	<!--end::Modal-->
</div>
<!-- End Content here -->	
@endsection
{{-- Scripts Section --}}
@section('scripts')
<script type="text/javascript">
	$(document).on('click','#cloneThisblast',function(){
		var id = $(this).attr('data-thisId');
		var url = "{{ route('cloneEmailBlastMessage') }}";
		$.ajax({
			type:'POST',
			url:url,
			data: {_token : '{{ csrf_token() }}',id : id},
			success:function(response)
			{
				if(response.status == true) {
					toastr.options = {
						"closeButton": false,
						"debug": false,
						"newestOnTop": true,
						"progressBar": true,
						"positionClass": "toast-top-right",
						"preventDuplicates": false,
						"onclick": null,
						"showDuration": "1500",
						"hideDuration": "1000",
						"timeOut": "4000",
						"extendedTimeOut": "1000",
						"showEasing": "swing",
						"hideEasing": "linear",
						"showMethod": "fadeIn",
						"hideMethod": "fadeOut"
					};
					toastr.success(response.message);
					
					setTimeout(function(){ 
						window.location.href=response.redirect;
					}, 1500);
				} else {
					toastr.options = {
						"closeButton": false,
						"debug": false,
						"newestOnTop": true,
						"progressBar": true,
						"positionClass": "toast-top-right",
						"preventDuplicates": false,
						"onclick": null,
						"showDuration": "3000",
						"hideDuration": "1000",
						"timeOut": "4000",
						"extendedTimeOut": "1000",
						"showEasing": "swing",
						"hideEasing": "linear",
						"showMethod": "fadeIn",
						"hideMethod": "fadeOut"
					};
					toastr.error((response.message) ? response.message : "Something went wrong!");
				}
			}
		});
	});
</script>
@endsection