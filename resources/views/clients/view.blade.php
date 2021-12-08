{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
@endsection

<?php
$id                            = "";
$firstname                     = "";
$lastname                      = "";
$mo_country_code               = "";
$mobileno                      = "";
$tel_country_code              = "";
$telephoneno                   = "";
$email                         = "";
$send_notification_by          = "";
$preferred_language            = "";
$accept_marketing_notification = "";
$gender                        = "";
$referral_source               = "";
$birthdate                     = "";
$birthdate_date                = "";
$birthdate_month               = "";
$birthdate_year                = "";
$client_notes                  = "";
$display_on_all_bookings       = "";
$address                       = "";
$suburb                        = "";
$city                          = "";
$state                         = "";
$zipcode                       = "";

if(!empty($client_information))
{
	$id                            = $client_information[0]->id;
	$firstname                     = $client_information[0]->firstname;
	$lastname                      = $client_information[0]->lastname;
	$mo_country_code               = ($client_information[0]->mo_country_code) ? $client_information[0]->mo_country_code : 1;
	$mobileno                      = $client_information[0]->mobileno;
	$tel_country_code              = ($client_information[0]->tel_country_code) ? $client_information[0]->tel_country_code : 1;
	$telephoneno                   = $client_information[0]->telephoneno;
	if (Auth::user()->can('can_see_client_contact_info')) {
		$email                     = $client_information[0]->email;
	} else {	
		$email                     = hideEmail($client_information[0]->email);
	}	
	$send_notification_by          = $client_information[0]->send_notification_by;
	$preferred_language            = $client_information[0]->preferred_language;
	$accept_marketing_notification = $client_information[0]->accept_marketing_notification;
	$gender                        = $client_information[0]->gender;
	$referral_source               = $client_information[0]->referral_source;
	$birthdate                     = !empty($client_information[0]->birthdate) ? date("m/d/Y",strtotime($client_information[0]->birthdate)) : '';
	$birthdate_date                = !empty($birthdate) ? date('j', strtotime($birthdate)) : '';
	$birthdate_month               = !empty($birthdate) ? date('n', strtotime($birthdate)) : '';
	$birthdate_year                = !empty($birthdate) ? date('Y', strtotime($birthdate)) : '';
	$client_notes                  = $client_information[0]->client_notes;
	$display_on_all_bookings       = $client_information[0]->display_on_all_bookings;
	$address                       = $client_information[0]->address;
	$suburb                        = $client_information[0]->suburb;
	$city                          = $client_information[0]->city;
	$state                         = $client_information[0]->state;
	$zipcode                       = $client_information[0]->zipcode;
	
	$is_blocked                    = $client_information[0]->is_blocked;
	$block_reason                  = $client_information[0]->block_reason;
}

function hideEmail($email)
{
	$mail_parts = explode("@", $email);
	$length = strlen($mail_parts[0]);
	$show = floor($length/2);
	$hide = $length - $show;
	$replace = str_repeat("*", $hide);

	return substr_replace ( $mail_parts[0] , $replace , $show, $hide );
}
?>

@section('content')
<style type="text/css">
	.tab-pane {
		/*max-height: 250px;*/
		/*overflow-y: scroll;*/
	}
	.dropdown-toggle.btn::after {
        content: none;
    }
    .dropdown-toggle svg {
    	width: 18px;
    }
    .dropdown-menu {
    	width: 15rem;
    }
    .dropdown-menu li {
    	padding: 3% 5%;
    }
    .singleConFormContainer {
    	padding: 21px 16px;
    }
</style>
<div class="d-flex flex-column-fluid">
	<!--begin::Container-->
	<div class="container">
	
		<div class="modal fade p-0" id="blockClientModal" tabindex="-1" role="dialog" aria-labelledby="blockClientModalLabel Label" aria-modal="true">
			<div class="modal-dialog " role="document">
				<div class="modal-content ">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center" id="decreaseStockModalLabel">Block Client</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<form method="POST" enctype="multipart/form-data" action="{{ route('blockClient') }}" id="blockClient">
						@csrf
						<input type="hidden" name="block_client_id" id="block_client_id"> 
						<div class="modal-body">
							<h6>Are you sure you want to block this client?</h6>
							<br>
							<h6>Blocking prevents this client from booking online appointments with you, they will find no available time slots.</h6>
							<br>
							<h6>Blocked clients are also automatically excluded from any marketing messages.</h6>
							<br>
							<div class="form-group w-100">
								<label>Blocking reason</label>
								<select class="form-control" name="block_reason" id="block_reason">
									<option value="">Select blocking reason</option>
									<option value="Too many no shows">Too many no shows</option>
									<option value="Too many late cancellations">Too many late cancellations</option>
									<option value="Too many reschedules">Too many reschedules</option>
									<option value="Rude or inappropriate to staff">Rude or inappropriate to staff</option>
									<option value="Refused to pay">Refused to pay</option>
									<option value="Booked fake appointments">Booked fake appointments</option>
									<option value="Other">Other</option>
								</select>
							</div>
							
							<div class="form-group w-100 blockNotes" style="display:none;">
								<label>Add note</label>
								<textarea class="form-control" name="block_notes" id="block_notes"></textarea>
							</div>
						</div>
						<div class="modal-footer d-flex justify-content-between">
							<div class="ml-auto">
								<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
								<button type="submit" class="btn btn-primary font-weight-bold" id="blockClientBtn">Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	
		<div class="modal fade p-0" id="unblockClientModal" tabindex="-1" role="dialog" aria-labelledby="unblockClientModalLabel Label" aria-modal="true">
			<div class="modal-dialog " role="document">
				<div class="modal-content ">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center" id="decreaseStockModalLabel">Unblock Client</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<form method="POST" enctype="multipart/form-data" action="{{ route('unblockClient') }}" id="unblockClient">
						@csrf
						<input type="hidden" name="unblock_client_id" id="unblock_client_id"> 
						<div class="modal-body">
							<h6>Are you sure you want to unblock this client?</h6>
							<br>
							<h6>This will allow the client to book online appointments with you and to receive marketing messages.</h6>
						</div>
						<div class="modal-footer d-flex justify-content-between">
							<div class="ml-auto">
								<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
								<button type="submit" class="btn btn-primary font-weight-bold" id="unblockClientBtn">Save</button>
							</div>
						</div>
					</form>
				</div>
			</div>
		</div>
	
		<!--begin::Sales-->
		<!--begin::Row-->
		<div class="row">
			<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
				<div class="modal-dialog" role="document">
					<div class="modal-content">
						<form class="form" method="POST" id="add_client_frm">
							@csrf
							<input type="hidden" id="clientId" name="clientId" value="">
							<div class="modal-header">
								<h5 class="modal-title" id="exampleModalLabel">Remove Client</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
									<i aria-hidden="true" class="ki ki-close"></i>
								</button>
							</div>
							<div class="modal-body">Are you sure delete this client?</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal">Close</button>
								<button type="button" class="btn btn-danger font-weight-bold" data-url="{{ route('deleteClient',$id) }}" id="deleteclient">Delete</button>
							</div>
						</form>	
					</div>
				</div>
			</div>
			<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
				<!--begin::List Widget 3-->
				<div class="card-custom card-stretch gutter-b">
					<!--begin::Body-->
					<!--begin::Item-->
					<div class="client-view">
						<div class="row gutters-sm">
							<div class="col-md-4 mb-3">
								<div class="card">
									<div class="card-body">
										<div class="d-flex flex-column align-items-center text-center">
											<img src="https://bootdey.com/img/Content/avatar/avatar7.png" alt="Admin" class="rounded-circle" width="150">
											<div class="mt-3">
												<h4>{{ $firstname}} {{ $lastname }}</h4>
												@if($is_blocked == 1)
													<span class="badge badge-pill badge-info">Blocked</span>	
													<p>{{ $block_reason }}</p>
												@else
													<span class="badge badge-pill badge-info">New Client</span>
												@endif		
											</div>
											<div class="d-flex my-4 justify-content-between w-100">
												<div class=" dropdown dropdown-inline mr-2">
													<button type="button" class="btn btn-white" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fas fa-ellipsis-v p-1"></i></button>
													<div class="dropdown-menu dropdown-menu-sm dropdown-menu-left">
														<ul class="navi flex-column navi-hover py-2">
															@if (Auth::user()->can('can_see_client_contact_info'))
																<li class="navi-item">
																	<a data-toggle="modal" data-target="#clientModal" class="navi-link">
																		<span class="navi-text">Edit</span>
																	</a>
																</li>
															@else	
																<li class="navi-item">
																	<a href="javascript:;" class="no_access navi-link">
																		<span class="navi-text">Edit</span>
																	</a>
																</li>
															@endif	
															<!--li class="navi-item">
																<a href="#" data-toggle="modal" data-target="#mergeclient" class="navi-link">
																	<span class="navi-text">Merge</span>
																</a>
															</li-->
															@if($is_blocked == 1)
																<li class="navi-item">
																	<a data-toggle="modal" data-target="#unblockClientModal" class="cursor-pointer navi-link clickUnblockClient" data-client_id="{{ $id }}">
																		<span class="navi-text">Unblock</span>
																	</a>
																</li>	
															@else
																<li class="navi-item">
																	<a data-toggle="modal" data-target="#blockClientModal" class="cursor-pointer navi-link clickBlockClient" data-client_id="{{ $id }}">
																		<span class="navi-text">Block</span>
																	</a>
																</li>	
															@endif
															<li class="navi-item">
																<a href="#" class="navi-link" data-toggle="modal" data-target="#confirmModal" >
																	<span class="navi-text text-danger">Delete</span>
																</a>
															</li>
														</ul>
													</div>
												</div>
												<a href="{{ route('createAppointment') }}" class="w-100 btn btn-primary">New Appoinment</a>
											</div>
										</div>
									</div>
									<div class="card mt-3">
										<ul class="list-group list-group-flush">
											@if($email != '')
											<li class="list-group-item">
												<h6 class="text-muted mb-0">
													Email
												</h6>
												<h5 class="font-weight-bold">
													{{ $email }}
												</h5>
											</li>
											@endif
											@if($birthdate != '')
												<li class="list-group-item">
													<h6 class="text-muted mb-0">
														Date of Birth
													</h6>
													<h5 class="font-weight-bold">
														{{ $birthdate }}
													</h5>
												</li>
											@endif
											@if($gender != '')
											<li class="list-group-item">
												<h6 class="text-muted mb-0">
													Gender
												</h6>
												<h5 class="font-weight-bold">
													{{ $gender }}
												</h5>
											</li>
											@endif
											@if($accept_marketing_notification == 1)
											<li class="list-group-item">
												<h6 class="text-muted mb-0">
													Marketing notifications
												</h6>
												<h5 class="font-weight-bold">
													Accepts marketing notifications
												</h5>
											</li>
											@endif
										</ul>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="card mb-3">
									<div class="">
										<div class="total-appoinment-data justify-content-around d-flex">
											<div class="text-center w-100 data pt-6 p-4">
												<h3 class="price font-weight-bolder text-center">CA
													${{ $TotalSales }}</h3>
												<p class="title text-muted">Total Sales</p>
											</div>
											<div class=" text-center w-100 data pt-6 p-4">
												<h3 class="price font-weight-bolder text-center">CA
													${{ ($outStandingInvoices[0]->total_outstanding) ? $outStandingInvoices[0]->total_outstanding : 0 }}</h3>
												<p class="title text-muted">Outstanding</p>
											</div>
										</div>
										<hr class="m-0">
										<div class="p-4 total-appoinment-status justify-content-around d-flex">
											<div class="text-center w-100 data  p-4">
												<h3 class="count font-weight-bolder text-center">{{ $CountPreviousAppointmentServices }}
												</h3>
												<p class="title text-muted">All bookings</p>
											</div>
											<div class=" text-center w-100 data  p-4">
												<h3 class="count font-weight-bolder text-center">{{ count($CompletedAppointment) }}
												</h3>
												<p class="title text-muted">Completed</p>
											</div>
											<div class="text-center w-100 data  p-4">
												<h3 class="count font-weight-bolder text-center">{{ count($CancelledAppointment) }}
												</h3>
												<p class="title text-muted">Cancelled</p>
											</div>
											<div class=" text-center w-100 data  p-4">
												<h3 class="count font-weight-bolder text-center">{{ count($NoshowAppointment) }}
												</h3>
												<p class="title text-muted">No Shows</p>
											</div>
										</div>
									</div>
								</div>
								<div class="row gutters-sm">
									<div class="col-sm-12 mb-3">
										<div class="card h-100">
											<div class="card-body" style="padding: 1.5rem;">
												<ul class="nav nav-tabs nav-tabs-line nav-tabs-primary nav-tabs-line-3x"
													role="tablist">
													<li class="nav-item">
														<a class="nav-link active" data-toggle="tab"
															href="#appointments">Appointments
															({{ $CountPreviousAppointmentServices }})</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-toggle="tab"
															href="#consultationforms">Consultation
															forms (<span class="countConsultationForms">0</span>)</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-toggle="tab"
															href="#products">Products ({{ count($soldProduct) }})</a>
													</li>
													<li class="nav-item">
														<a class="nav-link" data-toggle="tab"
															href="#invoices">Invoices ({{ count($ClientInovices) }})</a>
													</li>
												</ul>
												<div class="tab-content mt-5" id="myTabContent">
													<div class="tab-pane fade show active" id="appointments" role="tabpanel" aria-labelledby="appointments">
														<div class="row">
															<div class="card-body py-2 px-4">
																<div class="getAppointmentsContainer"></div>

																<div class="client-apoinments-list mb-6">
																	<a href="javascript:void(0)" class="getAppointments" data-lastId="">Load 5 more</a>
																</div>
															</div>
														</div>
													</div>
													<div class="tab-pane fade" id="consultationforms" role="tabpanel" aria-labelledby="consultationforms">
														<div style="margin-top: 2%;">	
															<h3>To be completed (<span class="countToBeCompletedConForm"></span>)</h3>
															<div class="toBeCompletedConFormContainer"></div>

															<div class="client-apoinments-list mb-6">
																<a href="javascript:void(0)" class="getToBeCompletedConForm" data-lastId="">Load 5 more</a>
															</div>
														</div>
														<div style="margin-top: 5%;">	
															<h3>Completed (<span class="countCompletedConsultationForm">1</span>)</h3>
															<div class="completedConsultationFormContainer"></div>

															<div class="client-apoinments-list mb-6">
																<a href="javascript:void(0)" class="getCompletedConForm" data-lastId="">Load 5 more</a>
															</div>
														</div>
														
													</div>
													<div class="tab-pane fade" id="products" role="tabpanel" aria-labelledby="products">
														<div class="clientProductsContainer"></div>

														<div class="client-apoinments-list mb-6">
															<a href="javascript:void(0)" class="getClientProducts" data-lastId="">Load 5 more</a>
														</div>
													</div>
													<div class="tab-pane fade" id="invoices" role="tabpanel" aria-labelledby="invoices">
														<div class="col-12 col-md-12">
															<table class="table table-hover">
																<thead>
																	<tr>
																		<th>Status</th>
																		<th>Invoice#</th>
																		<th>Invoice date</th>
																		<th>Total</th>
																	</tr>
																</thead>
																<tbody class="clientInvoicesContainer">
																	
																</tbody>
															</table>

															<div class="client-apoinments-list mb-6">
																<a href="javascript:void(0)" class="getClientInvoices" data-lastId="">Load 5 more</a>
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

					<!--end:Item-->
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

<!--edit Client Modal-->
<div class="modal fade p-0" id="clientModal" tabindex="-1" role="dialog" aria-labelledby="clientModalLabel Label"
	aria-modal="true">
	<div class="modal-dialog add-new-client-modal full-width-modal" role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h1 class="font-weight-bolder m-0 text-center" id="clientModalLabel">Edit Client Information</h1>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
				</button>
			</div>
			<form method="POST" enctype="multipart/form-data" action="{{ route('updateClient') }}" id="editClient">
				@csrf
				<div class="modal-body" style="max-height: calc(100vh - 155px);overflow-y: scroll;">
					<div class="container">
						<div class="row">
							<div class="col-12 col-md-6 p-2" style="border-right: 2px solid grey;">
								<div class="client_pupop">
									<div class="d-flex">
										<div class="form-group  mr-2 w-100">
											<input type="hidden" name="id" value="{{ $id }}">
											<label for="firstname">First name</label>
											<input type="text" class="form-control" placeholder="john" name="firstname" value="{{ $firstname }}" id="firstname">
										</div>
										<div class="form-group  ml-2 w-100">
											<label for="lastname">Last name</label>
											<input type="text" class="form-control" placeholder="Doe" name="lastname" value="{{ $lastname }}" id="lastname">
										</div>
									</div>
									<div class="d-flex">
										<div class="form-group mr-2 w-100 mobileNumberclass">
											<label for="mobileNumber">Mobile</label>
											<input type="tel" class="form-control allow_only_numbers" id="mobileNumber" value="{{ $mobileno }}" name="mobileno[main]">
											<input type="hidden" class="form-control" id="mo_country_code"  value="{{ $mo_country_code }}" name="mo_country_code" value="1">
										</div>
										<div class="form-group ml-2 w-100 telephoneclass">
											<label for="telephone">Telephone</label>
											<input type="tel" class="form-control allow_only_numbers" id="telephone" value="{{ $telephoneno }}" name="telephoneno[main]">
											<input type="hidden" class="form-control" id="tel_country_code" value="{{ $tel_country_code }}"  name="tel_country_code" value="1">
										</div>
									</div>
									<div class="form-group">
										<label for="email">Email</label>
										<input type="email" class="form-control" placeholder="" value="{{ $email }}" name="email" id="email">
									</div>
									<div class="form-group">
										<label for="send_notification_by">Send notifications by</label>
										<select class="form-control" id="send_notification_by" name="send_notification_by">
											<option value="Don't send notifications" @if($send_notification_by=="Don't send notifications") selected @endif>Don t send notifications</option>
											<option value="Email" @if($send_notification_by=="Email") selected @endif >Email</option>
											<option value="SMS" @if($send_notification_by=="SMS") selected @endif >SMS</option>
											<option value="Email & SMS" @if($send_notification_by=="Email & SMS") selected @endif >Email & SMS</option>
										</select>
									</div>
									<div class="form-group">
										<label for="preferred_language">Preferred language</label>
										<select class="form-control" id="preferred_language" name="preferred_language">
											<option value="en" @if($preferred_language=="en") selected @endif >Use provider language (English)</option>
											<option value="bg" @if($preferred_language=="bg") selected @endif >Bulgarian (български)</option>
											<option value="cs" @if($preferred_language=="cs") selected @endif >Czech (čeština)</option>
											<option value="da" @if($preferred_language=="da") selected @endif >Danish (dansk)</option>
											<option value="de" @if($preferred_language=="de") selected @endif >German (Deutsch)</option>
											<option value="el" @if($preferred_language=="el") selected @endif >Greek (Ελληνικά)</option>
											<option value="en" @if($preferred_language=="en") selected @endif >English (English)</option>
											<option value="es" @if($preferred_language=="es") selected @endif >Spanish (español)</option>
											<option value="fi" @if($preferred_language=="fi") selected @endif >Finnish (suomi)</option>
											<option value="fr" @if($preferred_language=="fr") selected @endif >French (français)</option>
											<option value="hr" @if($preferred_language=="hr") selected @endif >Croatian (hrvatski)</option>
											<option value="hu" @if($preferred_language=="hu") selected @endif >Hungarian (magyar)</option>
											<option value="it" @if($preferred_language=="it") selected @endif >Italian (italiano)</option>
											<option value="lt" @if($preferred_language=="lt") selected @endif >Lithuanian (lietuvių)</option>
											<option value="nb" @if($preferred_language=="nb") selected @endif >Norwegian Bokmål (norsk bokmål)</option>
											<option value="nl" @if($preferred_language=="nl") selected @endif >Dutch (Nederlands)</option>
											<option value="pl" @if($preferred_language=="pl") selected @endif >Polish (polski)</option>
											<option value="pt" @if($preferred_language=="pt") selected @endif >Portuguese (português)</option>
											<option value="ro" @if($preferred_language=="ro") selected @endif >Romanian (română)</option>
											<option value="sv" @if($preferred_language=="sv") selected @endif >Swedish (svenska)</option>
											<option value="ru" @if($preferred_language=="ru") selected @endif >Russian (русский)</option>
											<option value="uk" @if($preferred_language=="uk") selected @endif >Ukrainian (українська)</option>
											<option value="sl" @if($preferred_language=="sl") selected @endif >Slovenian (slovenščina)</option>
										</select>
									</div>
									<div class="form-group">
										<div class="checkbox-list">
											<label class="checkbox">
												<input type="checkbox" name="accept_marketing_notification" @if($accept_marketing_notification==1) checked @endif  id="accept_marketing_notification" >
												<span></span>Accepts marketing notifications
											</label>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6">
								<div class="client_pupop">
									<ul class="nav nav-tabs nav-tabs-line">
										<li class="nav-item">
											<a class="nav-link active" data-toggle="tab" href="#persnolTab">Personal
												Info</a>
										</li>
										<li class="nav-item">
											<a class="nav-link" data-toggle="tab" href="#addressTab">Address</a>
										</li>
									</ul>
									<div class="tab-content mt-5" id="myTabContent">
										<div class="tab-pane fade show active" id="persnolTab" role="tabpanel"
											aria-labelledby="persnolTabs">
											<div class="d-flex justify-content-between">
												<div class="form-group mr-2 w-100">
													<label for="gender">Gender</label>
													<select class="form-control" id="gender" name="gender">
														<option value="Male" @if($gender=="Male") selected @endif >Male</option>
														<option value="Female" @if($gender=="Female") selected @endif >Female</option>
														<option value="Unknown" @if($gender=="Unknown") selected @endif >Unknown</option>
													</select>
												</div>
												<div class="form-group ml-2 w-100">
													<label for="referral_source">Referral source</label>
													<select class="form-control" id="referral_source" name="referral_source">
														<option value="Select source" @if($referral_source=="Select source") selected @endif >Select source</option>
														<option value="Walk-In" @if($referral_source=="Walk-In") selected @endif >Walk-In</option>
													</select>
												</div>
											</div>
											
											<div class="form-group">
												<label for="birthdate">Birthday</label>

												<div class="">
												<select id="day" class="day" name="date_[day]"></select>
												<!-- 
													<option value="">Day</option>
													<option value="01">1st</option>
													<option value="02">2nd</option>
													<option value="03">3rd</option>
													<option value="04">4th</option>
													<option value="05">5th</option>
													<option value="06">6th</option>
													<option value="07">7th</option>
													<option value="08">8th</option>
													<option value="09">9th</option>
													<option value="10">10th</option>
													<option value="11">11th</option>
													<option value="12">12th</option>
													<option value="13">13th</option>
													<option value="14">14th</option>
													<option value="15">15th</option>
													<option value="16">16th</option>
													<option value="17">17th</option>
													<option value="18">18th</option>
													<option value="19">19th</option>
													<option value="20">20th</option>
													<option value="21">21st</option>
													<option value="22">22nd</option>
													<option value="23">23rd</option>
													<option value="24">24th</option>
													<option value="25">25th</option>
													<option value="26">26th</option>
													<option value="27">27th</option>
													<option value="28">28th</option>
													<option value="29">29th</option>
													<option value="30">30th</option>
													<option value="31">31st</option>
												 -->

												<select id="month" class="month" name="date_[month]" style="width:82px;"></select>
												<!-- 
													<option value="">Month</option>
													<option value="01">January</option>
													<option value="02">February</option>
													<option value="03">March</option>
													<option value="04">April</option>
													<option value="05">May</option>
													<option value="06">June</option>
													<option value="07">July</option>
													<option value="08">August</option>
													<option value="09">September</option>
													<option value="10">October</option>
													<option value="11">November</option>
													<option value="12">December</option>
												 -->

												<select id="year" class="year" name="date_[year]"></select>
												<!-- 
													<option value="">Year</option>
													<option value="2021">2021</option><option value="2020">2020</option><option value="2019">2019</option><option value="2018">2018</option><option value="2017">2017</option><option value="2016">2016</option><option value="2015">2015</option><option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option><option value="1904">1904</option><option value="1903">1903</option><option value="1902">1902</option><option value="1901">1901</option><option value="1900">1900</option> -->
											</div>


												<!-- <div class="d-flex">
													<span class="form-control col-md-3">{{ $birthdate }}</span>
													<input type="date" class="form-control" value="{{ $birthdate }}" name="birthdate" id="birthdate" placeholder="Enter Birth Date">
												</div> -->
											</div>
											<div class="form-group ml-2">
												<label for="client_notes">Client Note</label>
												<input type="text" class="form-control" name="client_notes" value="{{ $client_notes }}" id="client_notes">
											</div>
											<div class="form-group">
												<div class="checkbox-list">
													<label class="checkbox">
														<input type="checkbox" name="display_on_all_bookings" @if($display_on_all_bookings==1 ) checked @endif id="display_on_all_bookings">
														<span></span>Display on all bookings
													</label>
												</div>
											</div>
										</div>
										<div class="tab-pane fade" id="addressTab" role="tabpanel"
											aria-labelledby="addressTab">
											<div class="form-group">
												<label for="address">Address</label>
												<input type="text" class="form-control" value="{{ $address }}" name="address" id="address">
											</div>
											<div class="form-group">
												<label for="suburb">Suburb</label>
												<input type="email" class="form-control" value="{{ $suburb }}" name="suburb" id="suburb">
											</div>
											<div class="d-flex">
												<div class="form-group  mr-2 w-100">
													<label for="city">City</label>
													<input type="text" class="form-control" value="{{ $city }}" name="city" id="city">
												</div>
												<div class="form-group  ml-2 w-100">
													<label for="state">State</label>
													<input type="text" class="form-control" value="{{ $state }}" name="state" id="state">
												</div>
											</div>
											<div class="form-group">
												<label for="zipcode">Zip / Post Code</label>
												<input type="text" class="form-control" value="{{ $zipcode }}" name="zipcode" id="zipcode">
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer text-right position-fixed bg-white bottom-0 w-100 d-block zindex-2">
					<button type=" button" class="btn btn-outline-secondary font-weight-bold"
						data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-primary font-weight-bold" id="editClientSubmitButton">Update</button>
				</div>
			</form>
		</div>
	</div>
</div>
<!--end Modal-->

<div class="modal fade" id="sendEmailReminder">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header d-flex justify-content-between">
				<h3 class="font-weight-bolder modal-title">Send A Consultation Form Reminder</h3>
				<button type="button" class="text-dark close" data-dismiss="modal"><i class="ki ki-close"></i></button>
			</div>
			<div class="modal-body">
				<form method="POST" id="consultationFormEmailReminder" action="{{ route('consultationFormEmailReminder') }}"> 
				@csrf
				<input type="hidden" name="CSID" id="CSID">
				<div class="form-group">
					<h5>Are you sure you want to send a consultation form reminder to your client?</h5>
				</div>
				<div class="form-action text-right">
				<button class="btn btn-primary btn-lg px-20" id="saveUnpaidInovice" type="submit">Yes, send reminder</button>
				</div>
				
				</form>
			</div>
		</div>
	</div>
</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('js/editClient.js') }}"></script>
<script src="{{ asset('js/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('js/jquery.date-dropdowns.js') }}"></script>
<!--end::Page Vendors-->
<!--begin::Page Scripts(used by this page)-->
<script src="{{ asset('js/widgets.js') }}"></script>
<!--end::Page Scripts-->
<!-- International Telephone -->
<script src="{{ asset('js/intlTelInput.min.js') }}"></script>
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>	
<script src="{{ asset('js/application.js') }}"></script>
<script src="{{ asset('js/strftime-min.js') }}"></script>
<script>
	
$("#birthdate").dateDropdowns({
	submitFormat: "yyyy-mm-dd"
});

$(function() 
{
	$(document).on("click", '#deleteclient', function (e) 
	{
		$("#confirmModal").modal("hide");
		KTApp.blockPage();
		var url = $(this).data('url');
		
		$.ajax({
			type: "get",
			url: url,
			dataType: 'json',
			success: function (data) {
				KTApp.unblockPage();
				
				if(data.status == true)
				{	
					var table = $('#clientList').DataTable();
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

					toastr.success(data.message);
					window.location.href='{{ route('clientList') }}';
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
				}	
			}
		});

	});
	
	function fieldU(fieldId, id) {
		$("#" + fieldId).val(id);
	}
});
</script>	
<script>
	// jQuery 	
	var phone_number = window.intlTelInput(document.querySelector("#telephone"), {
	  separateDialCode: true,
	  preferredCountries:["ca"],
	  hiddenInput: "full",
	  utilsScript: "{{ asset('js/utils.js') }}",
	 
	});
			
	$(document).on("click", '.telephoneclass .iti__country' , function(e) {
		e.preventDefault();
		
		if($(this).hasClass("iti__active")){
			var country_code = $(this).attr("data-dial-code");
			$("#tel_country_code").val(country_code);
		} else {
			var country_code = $(this).attr("data-dial-code");
			$("#tel_country_code").val(country_code);
		}
	});	
	var ph = $("#tel_country_code").val()+$("#telephone").val();
	phone_number.setNumber("+"+ph+"");
</script>
<script>
	// jQuery 
	var phone_number = window.intlTelInput(document.querySelector("#mobileNumber"), {
	  separateDialCode: true,
	  preferredCountries:["ca"],
	  hiddenInput: "full",
	  utilsScript: "{{ asset('js/utils.js') }}"
	});
	
	$(document).on("click keypress keydown keyup", '.mobileNumberclass .iti__country' , function(e) {
		e.preventDefault();
		
		if($(this).hasClass("iti__active")){
			var country_code = $(this).attr("data-dial-code");
			$("#mo_country_code").val(country_code);
		} else {
			var country_code = $(this).attr("data-dial-code");
			$("#mo_country_code").val(country_code);
		}

	});

	var mo = $("#mo_country_code").val()+$("#mobileNumber").val();
	phone_number.setNumber("+"+mo+"");
	
	$(document).ready(function(){
		getAppointments(true);
		getClientProducts(true);
		getClientInvoices(true);
		getToBeCompletedConForm(true);
		getCompletedConForm(true);

		$(document).on('click','.getAppointments', function(){
			getAppointments();
		});
		$(document).on('click','.getClientProducts', function(){
			getClientProducts();
		});
		$(document).on('click','.getClientInvoices', function(){
			getClientInvoices();
		});
		$(document).on('click','.getToBeCompletedConForm', function(){
			getToBeCompletedConForm();
		});
		$(document).on('click','.getCompletedConForm', function(){
			getCompletedConForm();
		});

		$(document).on('click','.sendEmailReminder',function(){
			var consultationFormId = $(this).attr('data-consultationFormId');
			$("#sendEmailReminder").modal('show');
			$("#CSID").val(consultationFormId);
		});

		$(document).on('click','.open-view-appointment', function(){
			var href = $(this).attr('data-href');
			$('<a href="'+href+'" target="blank"></a>')[0].click();    
		});

		$(document).on('keypress', '.allow_only_numbers', function(evt){
			evt = (evt) ? evt : window.event;
		    var charCode = (evt.which) ? evt.which : evt.keyCode;

		    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
		        return false;
		    }
		    
		    return true;
		});

		//birthday fields dropdown validation
		  const monthNames = ["January", "February", "March", "April", "May", "June",
		    "July", "August", "September", "October", "November", "December"
		  ];

		  let custom_d = new Date();
		  let custom_year = custom_d.getFullYear();
		  let qntYears = custom_year - 1900;
		  let selectYear = $("#year");
		  let selectMonth = $("#month");
		  let selectDay = $("#day");
		  let currentYear = new Date().getFullYear();

		  for (var y = 0; y < qntYears; y++) {
		    let date = new Date(currentYear);
		    let yearElem = document.createElement("option");
		    yearElem.value = currentYear
		    yearElem.textContent = currentYear;
		    selectYear.append(yearElem);
		    currentYear--;
		  }

		  for (var m = 0; m < 12; m++) {
		    let month = monthNames[m];
		    let monthElem = document.createElement("option");
		    monthElem.value = (m+1);
		    monthElem.textContent = month;
		    selectMonth.append(monthElem);
		  }

		  var d = new Date();
		  var month = '{{ $birthdate_month }}';
		  var year = '{{ $birthdate_year }}';
		  var day = '{{ $birthdate_date }}';
		  var birthdate = '{{ $birthdate }}';

		  console.log('month:: '+month);
		  console.log('year:: '+year);
		  console.log('day:: '+day);
		  console.log('birthdate:: '+birthdate);

		  selectYear.val(year);
		  selectYear.on("change", AdjustDays);
		  selectMonth.val(month);
		  selectMonth.on("change", AdjustDays);

		  AdjustDays();
		  selectDay.val(day)

		  function AdjustDays() {
		    var year = selectYear.val();
		    var month = parseInt(selectMonth.val()) + 1;
		    selectDay.empty();

		    //get the last day, so the number of days in that month
		    var days = new Date(year, month, 0).getDate();

		    //lets create the days of that month
		    for (var d = 1; d <= days; d++) {
		      var dayElem = document.createElement("option");
		      dayElem.value = d;
		      dayElem.textContent = d;
		      selectDay.append(dayElem);
		    }
		  }
	});


	function getAppointments(onload = false) {

	    var lastId = $('.getAppointments').attr('data-lastId');

	    $.ajax({
	        url: '{{ route("getAppointments") }}',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            lastId: lastId,
	            clientId: '{{ $id }}'
	        },
	        success: function(response) {
	            if(response.status) {
	                var content = '';
	                var url = '{{ url("public/") }}/';

	                if(response.data.length > 0) {
	                    for( var index = 0; index < response.data.length; index++ ) {
	                        // content += '<a  href="'+response.data[index].appointment_link+'">';
								content += '<div class="client-apoinments-list mb-6 open-view-appointment" data-href="'+response.data[index].appointment_link+'" style="cursor: pointer;">';
								content += '<div class="d-flex align-items-center flex-grow-1">';
									content += '<h6 class="font-weight-bolder">'+response.data[index].appointment_date_month+'</h6>';
									content += '<div class="d-flex flex-wrap align-items-center justify-content-between w-100">';
										content += '<div class="d-flex flex-column align-items-cente py-2 w-75">';
											content += '<h6 class="text-muted font-weight-bold">'+response.data[index].appointment_date_hours+' <a class="text-blue" href="javascript:void(0)">';//'+response.data[index].appointment_link+'
												if(response.data[index].is_cancelled) {
											        content += 'Cancelled';
												} else if(response.data[index].is_noshow) {
													content += 'No Show';
												} else {
											        if(response.data[index].appointment_status == 0) {
											            content += 'New';
											        } else if(response.data[index].appointment_status == 1) {
											            content += 'Confirmed';
											        } else if(response.data[index].appointment_status == 2)  {
											            content += 'Arrived';
											        } else if(response.data[index].appointment_status == 3) {
											            content += 'Started';
											        } else if(response.data[index].appointment_status == 4) {
											            content += 'Completed';
											        }
												}
											content += '</a>';
											content += '</h6>';
											
											content += '<a href="javascript:void(0)" class="text-dark-75 font-weight-bolder text-hover-primary font-size-lg mb-1">'+response.data[index].serviceName+' </a>';
											
											content += '<span class="text-muted font-weight-bold">'+response.data[index].duration+' with '+response.data[index].staff_name+' </span>';// <i class="fa fa-heart text-danger"></i>
										content += '</div>';
										content += '<h6 class="font-weight-bolder py-4">CA $'+response.data[index].special_price+'</h6>';
									content += '</div>';
								content += '</div>';
							content += '</div>';
							// content += '</a>';

	                        lastId = response.data[index].id;
	                    }

	                    $('.getAppointmentsContainer').append(content);
	                    $('.getAppointments').attr('data-lastId', lastId);

	                    // $(".getAppointmentsContainer").animate({ scrollTop: $(".getAppointmentsContainer")[0].scrollHeight}, 1000);
	                } else {
	                    if(!onload) {
	                        responseMessages('error','There are no more reviews.');
	                    } else {
	                    	$(".getAppointmentsContainer").html('<div class="noitem-box"><h3>No appointments found.</h3></div>');
	                    }
	                }

	                if(response.hideMoreButton) {
	                    $('.getAppointments').hide();
	                }
	            } else {
	                if(!onload) {
	                    responseMessages('error', response.message);
	                }
	            }
	        },
	        error: function(response) {
	            if(!onload) {
	                responseMessages('error', 'Something went wrong. Please reload and try again.');
	            }
	        },
	        complete: function(response) {

	        }
	    });
	}

	function getClientProducts(onload = false) {

	    var lastId = $('.getClientProducts').attr('data-lastId');

	    $.ajax({
	        url: '{{ route("getClientProducts") }}',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            lastId: lastId,
	            clientId: '{{ $id }}'
	        },
	        success: function(response) {
	            if(response.status) {
	                var content = '';
	                var url = '{{ url("public/") }}/';

	                if(response.data.length > 0) {
	                    for( var index = 0; index < response.data.length; index++ ) {
	                        content += '<div class="client-apoinments-list mb-6">';
								content += '<div class="d-flex align-items-center flex-grow-1">';
									content += '<div class="d-flex flex-wrap align-items-center justify-content-between w-100">';
										content += '<div class="d-flex flex-column align-items-cente py-2 w-75">';
											content += '<h6 class="text-muted font-weight-bold">'+response.data[index].product_qty+' sold</h6>';
											content += '<h6 class="text-muted font-weight-bold">'+response.data[index].product_name+'</h6>';
											content += '<h6 class="text-muted font-weight-bold">'+response.data[index].product_createdat+'</h6>';
										content += '</div>';
										content += '<h6 class="font-weight-bolder py-4">CA $'+response.data[index].product_price+'</h6>';
									content += '</div>';
								content += '</div>';
							content += '</div>';

	                        lastId = response.data[index].id;
	                    }

	                    $('.clientProductsContainer').append(content);
	                    $('.getClientProducts').attr('data-lastId', lastId);

	                    // $(".clientProductsContainer").animate({ scrollTop: $(".clientProductsContainer")[0].scrollHeight}, 1000);
	                } else {
	                    if(!onload) {
	                        responseMessages('error','There are no more reviews.');
	                    } else {
	                    	$(".clientProductsContainer").html('<h3>No product</h3><p>Create your first Product</p>');
	                    }
	                }

	                if(response.hideMoreButton) {
	                    $('.getClientProducts').hide();
	                }
	            } else {
	                if(!onload) {
	                    responseMessages('error', response.message);
	                }
	            }
	        },
	        error: function(response) {
	            if(!onload) {
	                responseMessages('error', 'Something went wrong. Please reload and try again.');
	            }
	        },
	        complete: function(response) {

	        }
	    });
	}

	function getClientInvoices(onload = false) {

	    var lastId = $('.getClientInvoices').attr('data-lastId');

	    $.ajax({
	        url: '{{ route("getClientInvoices") }}',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            lastId: lastId,
	            clientId: '{{ $id }}'
	        },
	        success: function(response) {
	            if(response.status) {
	                var content = '';
	                var url = '{{ url("public/") }}/';

	                if(response.data.length > 0) {
	                    for( var index = 0; index < response.data.length; index++ ) {
	                        content += '<tr>';
								content += '<td><span class="badge badge-pill badge-success">'+response.data[index].invoice_status+'</span></td>';
								content += '<td><a class="text-blue" href="'+response.data[index].invoice_link+'">'+response.data[index].invoice_id+'</a></td>';
								content += '<td>'+response.data[index].invoice_payment_date+'</td>';
								content += '<td>CA $'+response.data[index].invoice_final_total+'</td>';
							content += '</tr>';

	                        lastId = response.data[index].invoice_id;
	                    }

	                    $('.clientInvoicesContainer').append(content);
	                    $('.getClientInvoices').attr('data-lastId', lastId);

	                    // $(".clientInvoicesContainer").animate({ scrollTop: $(".clientInvoicesContainer")[0].scrollHeight}, 1000);
	                } else {
	                    if(!onload) {
	                        responseMessages('error','There are no more reviews.');
	                    } else {
	                    	$(".clientInvoicesContainer").html('<tr><td colspan="4" class="text-center">No data found.</td></tr>');
	                    }
	                }

	                if(response.hideMoreButton) {
	                    $('.getClientInvoices').hide();
	                }
	            } else {
	                if(!onload) {
	                    responseMessages('error', response.message);
	                }
	            }
	        },
	        error: function(response) {
	            if(!onload) {
	                responseMessages('error', 'Something went wrong. Please reload and try again.');
	            }
	        },
	        complete: function(response) {

	        }
	    });
	}

	function getToBeCompletedConForm(onload = false) {

	    var lastId = $('.getToBeCompletedConForm').attr('data-lastId');

	    $.ajax({
	        url: '{{ route("getToBeCompletedConForm") }}',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            lastId: lastId,
	            clientId: '{{ $id }}'
	        },
	        success: function(response) {
	            if(response.status) {
	                var content = '';
	                var url = '{{ url("public/") }}/';

	                if(response.data['toBeConsultationForm']['data'].length > 0) {
	                    for( var index = 0; index < response.data['toBeConsultationForm']['data'].length; index++ ) {
	                    	var dateObj = new Date( response.data['toBeConsultationForm']['data'][index].complete_before );
                            var dayOfYear = dateObj.strftime("%d %b %G %I:%M %P");

                            let url = "{{ route('completeConsultationForm', ':id') }}";
                            url = url.replace(':id', response.data['toBeConsultationForm']['data'][index].encrypted_id);

                        	content += '<hr>';
	                        content += '<div class="row singleConFormContainer">';
								content += '<div class="col-md-6">';
									content += '<b>'+response.data['toBeConsultationForm']['data'][index].name+'</b><br>';
									content += '<span>Requested on '+dayOfYear+'</span>';
								content += '</div>';
								content += '<div class="col-md-5 text-right">';
									content += '<span class="badge badge-warning">TO BE COMPLETED</span>';
								content += '</div>';
								content += '<div class="col-md-1 text-right">';
									content += '<div class="dropdown">';
									  	content += '<button class="btn dropdown-toggle" type="button" data-toggle="dropdown">';
									  		content += '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 426.667 426.667" style="enable-background:new 0 0 426.667 426.667;" xml:space="preserve"><g><g><circle cx="42.667" cy="213.333" r="42.667"/></g></g><g><g><circle cx="213.333" cy="213.333" r="42.667"/></g></g><g><g><circle cx="384" cy="213.333" r="42.667"/></g></g></svg>';
									  	content += '</button>';
									  	content += '<ul class="dropdown-menu">';
									    	content += '<li><a href="javascript:void(0)" class="sendEmailReminder" data-consultationFormId="'+response.data['toBeConsultationForm']['data'][index].consultation_from_id+'">Send email reminder</a></li>';
									    	content += '<li><a href="'+url+'">Complete the form</a></li>';
									  	content += '</ul>';
									content += '</div>';
								content += '</div>';
							content += '</div>';

	                        lastId = response.data['toBeConsultationForm']['data'][index].id;
	                    }

	                    $('.toBeCompletedConFormContainer').append(content);
	                    $('.getToBeCompletedConForm').attr('data-lastId', lastId);

	                    // $(".toBeCompletedConFormContainer").animate({ scrollTop: $(".toBeCompletedConFormContainer")[0].scrollHeight}, 1000);
	                } else {
	                    if(!onload) {
	                        responseMessages('error','There are no more reviews.');
	                    } else {
	                    	if(response.data['countCompletedConsultationForm']['totalRecords'] == 0) {

		                    	$("#consultationforms").html('<div class="noitem-box"><h3>No consultation forms</h3><p>Create your first consultation form</p></div>');
	                    	}
	                    }
	                }

	                if(response.data['toBeConsultationForm']['hideMoreButton']) {
	                    $('.getToBeCompletedConForm').hide();
	                }

	                if(response.data['toBeConsultationForm']['totalRecords'] == 0) {
	                	$('.toBeCompletedConFormContainer').hide();
	                } else {
	                	$('.countToBeCompletedConForm').text(response.data['toBeConsultationForm']['totalRecords']);
	                }

	                $('.countConsultationForms').text(response.totalRecords);
	            } else {
	                if(!onload) {
	                    responseMessages('error', response.message);
	                }
	            }
	        },
	        error: function(response) {
	            if(!onload) {
	                responseMessages('error', 'Something went wrong. Please reload and try again.');
	            }
	        },
	        complete: function(response) {

	        }
	    });
	}

	function getCompletedConForm(onload = false) {

	    var lastId = $('.getCompletedConForm').attr('data-lastId');

	    $.ajax({
	        url: '{{ route("getToBeCompletedConForm") }}',
	        type: 'POST',
	        dataType: 'JSON',
	        data: {
	            "_token": "{{ csrf_token() }}",
	            lastId: lastId,
	            clientId: '{{ $id }}'
	        },
	        success: function(response) {
	            if(response.status) {
	                var content = '';
	                var url = '{{ url("public/") }}/';

	                if(response.data['completedConsultationForm']['data'].length > 0) {
	                    for( var index = 0; index < response.data['completedConsultationForm']['data'].length; index++ ) {
	                    	var dateObj = new Date( response.data['completedConsultationForm']['data'][index].completed_at );
                            var dayOfYear = dateObj.strftime("%d %b %G %I:%M %P");

                            let url = "{{ route('viewClientConsultationForm', ':id') }}";
                            url = url.replace(':id', response.data['completedConsultationForm']['data'][index].encrypted_id);

                        	content += '<hr>';
	                        content += '<div class="row singleConFormContainer">';
								content += '<div class="col-md-6">';
									content += '<b>'+response.data['completedConsultationForm']['data'][index].name+'</b><br>';
									content += '<span>Last edited on '+dayOfYear+'</span>';
								content += '</div>';
								content += '<div class="col-md-5 text-right">';
									content += '<span class="badge badge-success">COMPLETED</span>';
								content += '</div>';
								content += '<div class="col-md-1 text-right">';
									content += '<div class="dropdown">';
									  	content += '<button class="btn dropdown-toggle" type="button" data-toggle="dropdown">';
									  		content += '<svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 426.667 426.667" style="enable-background:new 0 0 426.667 426.667;" xml:space="preserve"><g><g><circle cx="42.667" cy="213.333" r="42.667"/></g></g><g><g><circle cx="213.333" cy="213.333" r="42.667"/></g></g><g><g><circle cx="384" cy="213.333" r="42.667"/></g></g></svg>';
									  	content += '</button>';
									  	content += '<ul class="dropdown-menu">';
									    	content += '<li><a href="'+url+'">View client response</a></li>';
									  	content += '</ul>';
									content += '</div>';
								content += '</div>';
							content += '</div>';

	                        lastId = response.data['completedConsultationForm']['data'][index].id;
	                    }

	                    $('.completedConsultationFormContainer').append(content);
	                    $('.getCompletedConForm').attr('data-lastId', lastId);

	                    // $(".completedConsultationFormContainer").animate({ scrollTop: $(".completedConsultationFormContainer")[0].scrollHeight}, 1000);
	                } else {
	                    if(!onload) {
	                        responseMessages('error','There are no more reviews.');
	                    } else {

	                    	if(response.data['completedConsultationForm']['totalRecords'] == 0) {

		                    	$("#consultationforms").html('<div class="noitem-box"><h3>No consultation forms</h3><p>Create your first consultation form</p></div>');
	                    	}
	                    }
	                }

	                if(response.data['completedConsultationForm']['hideMoreButton']) {
	                    $('.getCompletedConForm').hide();
	                }

	                if(response.data['completedConsultationForm']['totalRecords'] == 0) {
	                	$('.completedConsultationFormContainer').hide();
	                } else {
		                $('.countCompletedConsultationForm').text(response.data['completedConsultationForm']['totalRecords']);
	                }

	                $('.countConsultationForms').text(response.totalRecords);
	            } else {
	                if(!onload) {
	                    responseMessages('error', response.message);
	                }
	            }
	        },
	        error: function(response) {
	            if(!onload) {
	                responseMessages('error', 'Something went wrong. Please reload and try again.');
	            }
	        },
	        complete: function(response) {

	        }
	    });
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
@endsection