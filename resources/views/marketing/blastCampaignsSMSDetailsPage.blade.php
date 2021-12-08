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
				<li class="nav-item">
					<a class="nav-link" href="{{ url('partners/marketing') }}">Overview</a>
				</li>
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
								<h6 class="mb-4">Sent on {{ ($getOverview['get_group_sms_blast'][0]['created_at']) ? date("jS M Y",strtotime($getOverview['get_group_sms_blast'][0]['created_at'])) : '' }} at {{ ($getOverview['get_group_sms_blast'][0]['created_at']) ? date("h:ia",strtotime($getOverview['get_group_sms_blast'][0]['created_at'])) : '' }} to {{ ($getOverview['client_type'] == 1) ? 'All Client' : 'Client Group' }}</h6>
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
							<h2 class="font-weight-bolder mb-4">Reach more clients through email message</h2>
							<p class="font-size-lg">Send Gym area is now open! as a email message to increase the reach and effectiveness of your blast message.</p>
							<a href="{{ url('partners/marketing/add_email_message') }}" class="btn btn-white">Creat email message</a>
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
							<h2 class="mb-6">{{ $TotalDelivered }}</h2>
							<div class="progressbar">
								<h6 class="text-muted">{{ ($TotalDelivered) ? 100 : 0 }}%</h6>
								<div class="progress">
									<div class="progress-bar bg-blue" role="progressbar"
										style="width: {{ ($TotalDelivered) ? 100 : 0 }}%;" aria-valuenow="{{ ($TotalDelivered) ? 100 : 0 }}" aria-valuemin="0" aria-valuemax="100">{{ ($TotalDelivered) ? 100 : 0 }}%
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
								<h6 class="text-muted">{{ $TotalClicked }}%</h6>
								<div class="progress">
									<div class="progress-bar" role="progressbar" style="width: {{ $TotalClicked }}%;" aria-valuenow="{{ $TotalClicked }}" aria-valuemin="0" aria-valuemax="100">{{ $TotalClicked }}%
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-3">
					<div class="card shadow-sm border-0">
						<div class="card-body">
							<h6 class="font-weight-bolder">Total booking value</h6>
							<h2 class="font-weight-bolder mb-6">CA $0</h2>
							<p class="text-muted m-0">0 appointments booked</p>
						</div>
					</div>
				</div>
				<div class="col-12 col-sm-12 col-md-3">
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
									<h4>{{ ($getOverview['sms_type_text']) ? $getOverview['sms_type_text'] : '' }}</h4> 
								</div>
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Channel</h6>
									<h4>SMS</h4> 
								</div>
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Sent to</h6>
									<h4>{{ ($getOverview['client_type'] == 1) ? 'All Client' : 'Client Group' }}</h4> 
								</div>
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Total cost</h6>
									<h4>CA ${{ ($paymentResponse['amount']) ? $paymentResponse['amount'] : '' }} (inc. GST 5%)</h4> 
								</div>
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Payment method</h6>
									<h4><i class="fa fa-wallet mr-3"></i>{{ ($paymentResponse['card_brand']) ? $paymentResponse['card_brand'] : '' }} •••• {{ ($paymentResponse['card_last4']) ? $paymentResponse['card_last4'] : '' }}</h4> 
								</div>
								<div class="col-12 col-sm-12 col-md-6 mb-5">
									<h6 class="font-weight-bolder mb-0">Created by</h6>
									<h4>{{ ($getOverview['get_creator']['first_name']) ? $getOverview['get_creator']['first_name'] : '' }} {{ ($getOverview['get_creator']['last_name']) ? $getOverview['get_creator']['last_name'] : '' }}</h4> 
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
					<h2 class="mb-0 font-weight-bolder">Preview Blast Message</h2>
                    <button type="button" class="close" data-dismiss="modal"><i class="la la-times icon-lg"></i></button>
				</div>
				<div class="modal-body">
					<div class="card-body">
						<h3 class="text-center">Message Preview</h3>
						<div class="smartphone">
							<div class="content">
								<div class="chat__message">
									<div class="date"></div>
									<div class="isUpdate message body-text">{{ ($getOverview['message_description']) ? $getOverview['message_description'] : '' }}<br/>
									</div>
									@if($getOverview['is_link'] == 1)
									<div class="isVoucher message">
										Choose voucher offer </br>
										<a class="text-blue linkUrl isLink" href="javascript:;">Link here</a>
									</div>
									@endif
								</div>
							</div>
						</div>
					</div>
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
		var url = "{{ route('cloneSmsBlastMessage') }}";
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