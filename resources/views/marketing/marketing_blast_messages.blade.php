{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')

<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
	<!-- Modal -->
	<div class="modal fade pr-0" id="addNewMessageModal" tabindex="-1" role="dialog"
		aria-labelledby="addNewMessageModalLabel" aria-hidden="true">
		<div class="modal-dialog full-width-modal" role="document">
			<div class="modal-content bg-content">
				<div class="border-0 modal-header">
					<p class="cursor-pointer text-right" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body text-center">
					<h1 class="font-weight-bolder">Select a channel</h1>
					<div class="container">
						<ul class="list-group">
							<a href="{{ url('partners/marketing/add_email_message') }}">
								<li
									class="p-6 my-4 bg-hover-primary-o-1 cursor-pointer shadow-sm d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
									<div class="d-flex align-items-center">
										<div class="icon-svg-lg">
											<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
												<g fill="none" fill-rule="evenodd">
													<circle fill="#E5F1FF" cx="28" cy="28" r="28"></circle>
													<path fill="#FFDB4E" d="M33.5 16.5v15h-20v-15z"></path>
													<path
														d="M18.5 29.068v5.14c0 .947.767 1.714 1.714 1.714h15.422c.946 0 1.714-.767 1.714-1.713v-5.141"
														stroke="#101928" stroke-width="2" stroke-linecap="round">
													</path>
													<path
														d="M37.35 24.784v-2.57c0-.947-.768-1.714-1.714-1.714H20.214c-.947 0-1.714.767-1.714 1.714v2.57l9.425 5.14 9.425-5.14z"
														stroke="#101928" stroke-width="2" stroke-linecap="round">
													</path>
												</g>
											</svg>
										</div>
										<div class="ml-4 name text-left">
											<h3>Email</h3>
										</div>
									</div>
									<i class="fa fa-chevron-right text-dark"></i>
								</li>
							</a>
							<a href="{{ url('partners/marketing/add_sms_text_message') }}">
								<li
									class="p-6 my-4 bg-hover-primary-o-1 cursor-pointer shadow-sm d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
									<div class="d-flex align-items-center">
										<div class="icon-svg-lg">
											<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
												<g fill="none" fill-rule="evenodd">
													<circle fill="#E5F1FF" cx="28" cy="28" r="28"></circle>
													<rect fill="#FFDB4E" transform="rotate(90 22 34)" x="14" y="26"
														width="16" height="16" rx="8"></rect>
													<g stroke="#101928" stroke-linecap="round" stroke-width="2">
														<path
															d="M37.561 25.19h-6.552l-3.276 1.638v-8.19c0-.905.733-1.638 1.638-1.638h8.19c.905 0 1.638.733 1.638 1.638v4.914c0 .905-.733 1.638-1.638 1.638z">
														</path>
														<path
															d="M25.276 18.638h-1.638c-.905 0-1.638.733-1.638 1.638V33.38c0 .905.733 1.638 1.638 1.638h9.009c.905 0 1.638-.733 1.638-1.638v-5.733">
														</path>
													</g>
												</g>
											</svg>
										</div>
										<div class="ml-4 name text-left">
											<h3>SMS text message</h3>
										</div>
									</div>
									<i class="fa fa-chevron-right text-dark"></i>
								</li>
							</a>
							<!-- <li class="p-6 my-4 d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">
								<div class="d-flex align-items-center">
									<div class="icon-svg-lg">
										<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
											<g fill="none" fill-rule="evenodd">
												<circle cx="28" cy="28" r="28" fill="#68D672"></circle>
												<path
													d="M37.212 18.778A12.893 12.893 0 0028.054 15c-7.135 0-12.943 5.78-12.945 12.883-.001 2.27.595 4.487 1.728 6.44L15 41l6.863-1.792a12.986 12.986 0 006.186 1.569h.005c7.135 0 12.943-5.78 12.946-12.884a12.775 12.775 0 00-3.788-9.115zm-9.158 19.823h-.004c-1.93-.001-3.824-.517-5.476-1.493l-.393-.232-4.073 1.063 1.087-3.951-.256-.405a10.645 10.645 0 01-1.645-5.7c.003-5.904 4.83-10.707 10.765-10.707a10.714 10.714 0 017.607 3.14 10.618 10.618 0 013.148 7.576c-.002 5.905-4.829 10.709-10.76 10.709zm5.902-8.02c-.323-.161-1.913-.94-2.21-1.048-.296-.107-.512-.16-.728.161-.215.323-.835 1.048-1.024 1.263-.189.215-.377.241-.7.08-.324-.16-1.366-.5-2.602-1.598-.962-.853-1.61-1.907-1.8-2.23-.188-.322-.02-.496.142-.657.146-.144.324-.376.486-.564.161-.188.215-.322.323-.537.108-.215.054-.403-.027-.564-.08-.16-.728-1.745-.997-2.39-.263-.628-.53-.543-.728-.553-.188-.009-.404-.01-.62-.01-.216 0-.566.08-.863.402-.296.322-1.132 1.101-1.132 2.686 0 1.584 1.16 3.115 1.321 3.33.162.215 2.281 3.466 5.526 4.86.772.332 1.374.53 1.844.679.775.245 1.48.21 2.037.127.622-.092 1.914-.779 2.184-1.53.27-.752.27-1.397.188-1.531-.08-.135-.296-.215-.62-.376z"
													fill="#FFF" fill-rule="nonzero"></path>
											</g>
										</svg>
									</div>
									<div class="ml-4 name text-left">
										<h3>WhatsApp</h3>
									</div>
								</div>
								<span class="bagde badge-primary p-1 rounded">Comming Soon</span>
							</li> -->
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End Modal -->

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
	<div class="d-flex flex-column-fluid">
		<div class="container">
			<div class="row">
				<div class="blast-messages w-100">
					<div class="p-10 mt-12">
						<div class="d-flex justify-content-between mb-6">
							<div>
								<h2 class="font-weight-bolder">
									Blast messages
								</h2>
								<h6 class="mb-4">
									Choose a message type to customise from our ready-made templates.
								</h6>
							</div>
							<button data-toggle="modal" data-target="#addNewMessageModal"
								class="btn btn-primary font-weight-bolder my-4">
								New message
							</button>
						</div>
						<div class="messages-cards">
							<div class="row">
								<div class="card shadow-sm text-center">
									<div class="card-body p-8">
										<div class="card-icon mb-6 mx-auto" style="width: 50px;">
											<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
												<g fill="none" fill-rule="evenodd">
													<circle fill="#E5F1FF" cx="28" cy="28" r="28">
													</circle>
													<path d="M12.5 27.5c0-8.284 6.716-15 15-15v30c-8.284 0-15-6.716-15-15z" fill="#ffe78c"></path>
													<path d="M37.269 20.81L40 18v8l-7-.8 3.211-3.302a10.234 10.234 0 10.345 11.678.75.75 0 111.256.819 11.734 11.734 0 11-.543-13.585zm-7.019 3.44A.75.75 0 0131 25v5.25l-.007.102a.75.75 0 01-.743.648H25l-.102-.007a.75.75 0 01-.648-.743l.007-.102A.75.75 0 0125 29.5h4.5V25l.007-.102a.75.75 0 01.743-.648z" fill="#101928" fill-rule="nonzero"></path>
												</g>
											</svg>
										</div>
										<h4 class="mb-3 font-weight-bolder">Quick update</h4>
										<h6 class="text-dark-50 font-weight-bold mb-6">
											Send updates to clients
										</h6>
										<div class="my-footer p-4">
											<a class="text-blue" href="javascript:;" data-toggle="modal" data-target="#addNewMessageModal" >Create</a>
											<i class="ml-1 fa fa-chevron-right text-blue fa-1x"></i>
										</div>
									</div>
								</div>
								<div class="card shadow-sm text-center">
									<div class="card-body p-8">
										<div class="card-icon mb-6 mx-auto" style="width: 50px;">
											<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
												<g fill="none" fill-rule="evenodd">
													<circle fill="#E5F1FF" cx="28" cy="28" r="28">
													</circle>
													<circle fill="#ffe78c" cx="19" cy="37" r="8">
													</circle>
													<path d="M24.139 17.049l-6.339.001-.102.007a.75.75 0 00-.648.743l-.001 6.339-3.58 3.58-.072.085a.75.75 0 00.073.976l3.579 3.58.001 6.34.007.102a.75.75 0 00.743.648l6.338-.001 3.582 3.581.084.073a.75.75 0 00.976-.073l3.581-3.581 6.339.001.102-.007a.75.75 0 00.648-.743l-.001-6.339 3.581-3.58.073-.085a.75.75 0 00-.073-.976l-3.581-3.582.001-6.338-.007-.102a.75.75 0 00-.743-.648l-6.34-.001-3.58-3.58a.75.75 0 00-1.06 0l-3.581 3.58zm4.111-1.988l3.27 3.27.09.076a.75.75 0 00.44.143l5.899-.001.001 5.901.01.118a.75.75 0 00.21.412l3.269 3.27-3.27 3.27-.076.09a.75.75 0 00-.143.44l-.001 5.899-5.899.001-.118.01a.75.75 0 00-.412.21l-3.27 3.269-3.27-3.27-.09-.076a.75.75 0 00-.44-.143l-5.901-.001.001-5.899-.01-.118a.75.75 0 00-.21-.412l-3.269-3.27 3.27-3.27.076-.09a.75.75 0 00.143-.44l-.001-5.901 5.901.001a.75.75 0 00.53-.22l3.27-3.269z" fill="#101928" fill-rule="nonzero"></path>
												</g>
											</svg>
										</div>
										<h4 class="mb-3 font-weight-bolder">Special Offer</h4>
										<h6 class="text-dark-50 font-weight-bold mb-6">
											Send clients a discount
										</h6>
										<div class="my-footer p-4">
											<a class="text-blue" href="javascript:;" data-toggle="modal" data-target="#addNewMessageModal" >Create</a>
											<i class="ml-1 fa fa-chevron-right text-blue fa-1x"></i>
										</div>
									</div>
								</div>

								<!-- <div class="card shadow-sm text-center">
									<div class="card-body p-8">
										<div class="card-icon mb-6 mx-auto" style="width: 50px;">
											<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
												<g fill="none" fill-rule="evenodd">
													<circle fill="#E5F1FF" cx="28" cy="28" r="28">
													</circle>
													<path fill="#FFDE78" d="M13 13h23v15H13z"></path>
													<path
														d="M37.75 19A2.25 2.25 0 0140 21.25v3.075a.75.75 0 01-.6.735 3.002 3.002 0 000 5.88c.349.071.6.378.6.735v3.075A2.25 2.25 0 0137.75 37h-19.5A2.25 2.25 0 0116 34.75v-3.075a.75.75 0 01.6-.735 3.002 3.002 0 000-5.88.75.75 0 01-.6-.735V21.25A2.25 2.25 0 0118.25 19zm0 1.5h-19.5a.75.75 0 00-.75.75v2.506a4.505 4.505 0 012.995 4.03L20.5 28a4.504 4.504 0 01-3 4.243v2.507c0 .38.283.693.648.743l.102.007h19.5a.75.75 0 00.75-.75v-2.507a4.505 4.505 0 01-2.995-4.03L35.5 28a4.506 4.506 0 013-4.244V21.25a.75.75 0 00-.648-.743l-.102-.007zm-6 9a.75.75 0 01.102 1.493L31.75 31h-7.5a.75.75 0 01-.102-1.493l.102-.007h7.5zm0-4.5a.75.75 0 01.102 1.493l-.102.007h-7.5a.75.75 0 01-.102-1.493L24.25 25h7.5z"
														fill="#101928"></path>
												</g>
											</svg>
										</div>
										<h4 class="mb-3 font-weight-bolder">Voucher</h4>
										<h6 class="text-dark-50 font-weight-bold mb-6">
											Promote available vouchers to clients
										</h6>
										<div class="my-footer p-4">
											<a class="text-blue" href="#">Create</a>
											<i class="ml-1 fa fa-chevron-right text-blue fa-1x"></i>
										</div>
									</div>
								</div>

								<div class="card shadow-sm text-center">
									<div class="card-body p-8">
										<div class="card-icon mb-6 mx-auto" style="width: 50px;">
											<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
												<g fill="none" fill-rule="evenodd">
													<circle fill="#E5F1FF" cx="28" cy="28" r="28">
													</circle>
													<path fill="#ffe78c" d="M11 45.5v-30h14v30z"></path>
													<path
														d="M37.455 25.394a.75.75 0 01-.449.96l-.153.064c-.21.093-.52.255-.903.5a11.004 11.004 0 00-2.477 2.191c-2.29 2.707-3.673 6.603-3.673 11.941a.75.75 0 01-.648.743l-.102.007a.75.75 0 01-.75-.75c0-5.338-1.383-9.234-3.673-11.94a11.004 11.004 0 00-2.477-2.193c-.493-.314-.865-.493-1.056-.562a.75.75 0 11.512-1.41c1.077.392 2.63 1.38 4.167 3.196 1.47 1.738 2.595 3.905 3.278 6.537.68-2.632 1.805-4.799 3.276-6.537 1.537-1.816 3.09-2.804 4.167-3.196a.75.75 0 01.96.449zM18.55 32.65l.865 1.753 1.935.281-1.4 1.365.33 1.927-1.73-.91-1.73.91.33-1.927-1.4-1.365 1.935-.281.865-1.753zm21 0l.865 1.753 1.935.281-1.4 1.365.33 1.927-1.73-.91-1.73.91.33-1.927-1.4-1.365 1.935-.281.865-1.753zM29.05 21.4a.75.75 0 01.743.648l.007.102v4.2a.75.75 0 01-1.493.102l-.007-.102v-4.2a.75.75 0 01.75-.75zm-12.95-.7a2.5 2.5 0 110 5 2.5 2.5 0 010-5zm25.9 0a2.5 2.5 0 110 5 2.5 2.5 0 010-5zm-25.9 1.5a1 1 0 100 2 1 1 0 000-2zm25.9 0a1 1 0 100 2 1 1 0 000-2zM29.05 12.137l.865 1.753 1.935.281-1.4 1.365.33 1.927-1.73-.91-1.73.91.33-1.927-1.4-1.365 1.935-.281.865-1.753z"
														fill="#101928"></path>
												</g>
											</svg>
										</div>
										<h4 class="mb-3 font-weight-bolder">Event invitation</h4>
										<h6 class="text-dark-50 font-weight-bold mb-6">
											Invite clients to an event
										</h6>
										<div class="my-footer p-4">
											<div class="form-group mb-0">
												<span class="badge badge-primary text-uppercase">Comming
													soon</span>
											</div>
										</div>
									</div>
								</div>
								<div class="card shadow-sm text-center">
									<div class="card-body p-8">
										<div class="card-icon mb-6 mx-auto" style="width: 50px;">
											<svg viewBox="0 0 56 56" xmlns="http://www.w3.org/2000/svg">
												<g fill="none" fill-rule="evenodd">
													<circle fill="#E5F1FF" cx="28" cy="28" r="28">
													</circle>
													<path fill="#ffe78c" d="M11 45.5v-30h14v30z"></path>
													<path d="M37.455 25.394a.75.75 0 01-.449.96l-.153.064c-.21.093-.52.255-.903.5a11.004 11.004 0 00-2.477 2.191c-2.29 2.707-3.673 6.603-3.673 11.941a.75.75 0 01-.648.743l-.102.007a.75.75 0 01-.75-.75c0-5.338-1.383-9.234-3.673-11.94a11.004 11.004 0 00-2.477-2.193c-.493-.314-.865-.493-1.056-.562a.75.75 0 11.512-1.41c1.077.392 2.63 1.38 4.167 3.196 1.47 1.738 2.595 3.905 3.278 6.537.68-2.632 1.805-4.799 3.276-6.537 1.537-1.816 3.09-2.804 4.167-3.196a.75.75 0 01.96.449zM18.55 32.65l.865 1.753 1.935.281-1.4 1.365.33 1.927-1.73-.91-1.73.91.33-1.927-1.4-1.365 1.935-.281.865-1.753zm21 0l.865 1.753 1.935.281-1.4 1.365.33 1.927-1.73-.91-1.73.91.33-1.927-1.4-1.365 1.935-.281.865-1.753zM29.05 21.4a.75.75 0 01.743.648l.007.102v4.2a.75.75 0 01-1.493.102l-.007-.102v-4.2a.75.75 0 01.75-.75zm-12.95-.7a2.5 2.5 0 110 5 2.5 2.5 0 010-5zm25.9 0a2.5 2.5 0 110 5 2.5 2.5 0 010-5zm-25.9 1.5a1 1 0 100 2 1 1 0 000-2zm25.9 0a1 1 0 100 2 1 1 0 000-2zM29.05 12.137l.865 1.753 1.935.281-1.4 1.365.33 1.927-1.73-.91-1.73.91.33-1.927-1.4-1.365 1.935-.281.865-1.753z" fill="#101928"></path>
												</g>
											</svg>
										</div>
										<h4 class="mb-3 font-weight-bolder">New service</h4>
										<h6 class="text-dark-50 font-weight-bold mb-6">
											Update clients about new services
										</h6>
										<div class="my-footer p-4">
											<div class="form-group mb-0">
												<span class="badge badge-primary text-uppercase">Comming
													soon</span>
											</div>
										</div>
									</div>
								</div> -->
							</div>
						</div>
						<div class="my-6">
							<h2 class="font-weight-bolder">
								My Blast Messages
							</h2>
							<div class="bg-white card-body p-0 rounded">
								<table class="table table-responsive table-hover">
									<thead>
										<tr class="shadow-1" style="box-shadow:0px 3px 2px 0px #CCC">
											<th>Blast message name</th>
											<th>Sent to</th>
											<th>Status</th>
											<th>Sent on</th>
											<th>Channel</th>
										</tr>
									</thead>
									<tbody>
										@foreach($allSmsMessges as $msgKey => $smsmessage)
											<tr class="SMS_{{ $smsmessage->id }}">
												<td class="font-weight-bolder">{{ $smsmessage->message_name }}</td>
												<td>
													@if($smsmessage->client_type == 1)
														All Clients
													@elseif($smsmessage->group_type == 1)
														New Clients
													@elseif($smsmessage->group_type == 2)
														Recent Clients
													@elseif($smsmessage->group_type == 3)
														Loyal Clients
													@elseif($smsmessage->group_type == 4)
														Lapsed Clients
													@endif
												</td>
												<td><span class="badge badge-primary text-uppercase">{{ ($smsmessage->payment_status == 1) ? 'SENT' : 'DRAFT' }}</span>
												</td>
												<td>{{ ($smsmessage->payment_status == 1) ? date('d M Y', strtotime($smsmessage->created_at)) : '' }}</td> 
												<td>SMS</td>
												<td>
													<div class="dropdown dropdown-inline">
														<a href="#" class="btn btn-clean text-dark btn-sm btn-icon"
															data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
															<i class="ki ki-bold-more-hor text-dark"></i>
														</a>
														<div
															class="dropdown-menu dropdown-menu-md dropdown-menu-right text-center">
															<ul class="navi navi-hover">
																@if($smsmessage->payment_status != 1)
																	<li class="navi-item">
																		<a href="{{ url('partners/marketing/edit_sms_message').'/'.$smsmessage->id }}" class="navi-link">
																			<span class="navi-text">Edit message</span>
																		</a>
																	</li>
																@endif
																
																@if($smsmessage->is_sms_sended == 1)
																<li class="navi-item">
																	<a href="{{ route('message_overview',['type'=>'SMS','id'=>Crypt::encryptString($smsmessage->id)]) }}" class="navi-link delete_blast_messages">
																		<span class="navi-text">Overview</span>
																	</a>
																</li>
																@endif
																
																<li class="navi-item">
																	<a href="javascript:;" class="navi-link delete_blast_messages" data-id="{{ $smsmessage->id }}" data-type="SMS">
																		<span class="navi-text text-danger">Delete message</span>
																	</a>
																</li>
															</ul>
														</div>
													</div>
												</td>
											</tr>
										@endforeach
										@foreach($allEmailMessges as $emailKey => $emailmessage)
											<tr>
												<td class="font-weight-bolder">{{ $emailmessage->message_name }}</td>
												<td>{{ $emailmessage->client_type }}</td>
												<td><span class="badge badge-primary text-uppercase">{{ ($emailmessage->payment_status == 1) ? 'SENT' : 'DRAFT' }}</span>
												</td>
												<td>{{ ($emailmessage->payment_status == 1) ? date('d M Y', strtotime($emailmessage->created_at)) : '' }}</td>
												<td>Email</td>
												<td>
													<div class="dropdown dropdown-inline">
														<a href="#"
															class="btn btn-clean text-dark btn-sm btn-icon"
															data-toggle="dropdown" aria-haspopup="true"
															aria-expanded="false">
															<i class="ki ki-bold-more-hor text-dark"></i>
														</a>
														<div
															class="dropdown-menu dropdown-menu-md dropdown-menu-right text-center">
															<ul class="navi navi-hover">
																@if($emailmessage->payment_status != 1)
																	<li class="navi-item">
																		<a href="{{ url('partners/marketing/edit_email_message').'/'.$emailmessage->id }}"
																			class="navi-link">
																			<span class="navi-text">Edit
																				message</span>
																		</a>
																	</li>
																@endif
																
																@if($emailmessage->is_sended == 1)
																<li class="navi-item">
																	<a href="{{ route('message_overview',['type'=>'EMAIL','id'=>Crypt::encryptString($emailmessage->id)]) }}" class="navi-link delete_blast_messages">
																		<span class="navi-text">Overview</span>
																	</a>
																</li>
																@endif
																
																<li class="navi-item">
																	<a href="javascript:;" class="navi-link delete_blast_messages" data-id="{{ $emailmessage->id }}" data-type="EMAIL">
																		<span class="navi-text text-danger">Delete
																			message</span>
																	</a>
																</li>
															</ul>
														</div>
													</div>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
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
</div>

@endsection

{{-- Scripts Section --}}
@section('scripts')
<script type="text/javascript">
	$(document).on('click','.delete_blast_messages',function(){
		var id = $(this).data('id');
		var type = $(this).data('type');
		var url = "{{ url('partners/marketing/deleteBlastMessages') }}";
		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
		var dataString = 'id='+id+'&type='+type;
		$.ajax({
			type:'POST',
			url:url,
			data:dataString,
			success:function(resp)
			{
				if(resp.status == true)
				{
					validationMsg('success',resp.message);
					$('.SMS_'+id).fadeOut(1000);
				}
				else
				{
					validationMsg('error',resp.message);
				}
			}
		});
	});
	function validationMsg(type,message)
	{
		if(type == "success")
		{
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

			toastr.success(message);
		}
		else
		{

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

			toastr.error(message);
			return false;
		}
	}
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

	toastr.error('{{ Session::get('message') }}');
	return false;
</script>	
@endif	
@endsection