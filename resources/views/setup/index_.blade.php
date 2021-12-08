{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
	<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
	<style>	
	.editable_row {
		cursor: pointer;
	}
	</style>
@endsection

@section('content')
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
                    <div class="card card-custom bg-transparent card-stretch gutter-b">
                        <!--begin::Body-->
                        <!--begin::Item-->
                        <div class="row analytics-report">
							@if (Auth::user()->can('account_setup') || Auth::user()->can('point_of_sale'))
								<div class="col-md-6">
									@if (Auth::user()->can('account_setup'))
										<!-- <div class="card">
											<div class="card-body">
												<h3 class="">Account Setup</h3>
												<ul class="p-0">
													<li>
														<a class="" href="{{ route('account') }}">Account
															Settings </a>
														<p class="text-dark-50">Manage settings such as your
															business name and time zone</p>
													</li>
													<li>
														<a class="" href="{{ url('partners/setup/location') }}">Locations </a>
														<p class="text-dark-50">Manage multiple outlets for your
															business</p>
													</li>
													<li>
														<a class="" href="{{ url('partners/setup/resources') }}">Resources </a>
														<p class="text-dark-50">Set up bookable resources such
															as rooms, chairs or equipment</p>
													</li>
												</ul>
											</div>
										</div> -->
									@endif
									
									@if (Auth::user()->can('point_of_sale'))
										<div class="card">
											<div class="card-body">
												<h3 class="">Point of Sale</h3>

												<ul class="p-0">
													<li>
														<a class="" href="{{ route('invoiceTemplate') }}">Invoice
															Template</a>
														<p class="text-dark-50">
															Configure details displayed on invoices issued to
															your clients
														</p>
													</li>
													<li>
														<a class="" href="{{ route('InvoiceSequencing') }}">Invoice
															Sequencing </a>
														<p class="text-dark-50">
															Set custom prefixes and number sequence for invoices
														</p>
													</li>
													<li>
														<a class="" href="{{ route('taxes') }}">Taxes </a>
														<p class="text-dark-50">
															Manage tax rates that apply to items sold at
															checkout
														</p>
													</li>
													<li>
														<a class="" href="{{ route('paymentType') }}">Payment Types
														</a>
														<p class="text-dark-50">
															Set up manual payment types for use during checkout
														</p>
													</li>
													<li>
														<a class="" href="{{ route('getdiscount') }}">Discount
															Types </a>
														<p class="text-dark-50">
															Set up manual discount types for use during checkout
														</p>
													</li>
													<li>
														<a class="" href="{{ route('getsales') }}">Sales
															Settings </a>
														<p class="text-dark-50">
															Manage the default voucher expiry period and staff
															commissions
														</p>
													</li>
												</ul>
											</div>
										</div>
									@endif
								</div>
							@endif
							
							@if (Auth::user()->can('messages') || Auth::user()->can('online_booking') || Auth::user()->can('marketing') || Auth::user()->can('client_settings'))
								<div class="col-md-6">
									@if (Auth::user()->can('messages') || Auth::user()->can('online_booking') || Auth::user()->can('marketing'))
										<div class="card">
											<div class="card-body">
												<h3 class="">Schedulethat Plus</h3>
												</p>
												<ul class="p-0">
													@if (Auth::user()->can('online_booking'))
														<li>
															<a class="" href="{{ route('onlineBooking') }}">Online Booking</a>
															<p class="text-dark-50">
																Adjust your business info and profile images displayed online
															</p>
														</li>
													@endif
													@if (Auth::user()->can('marketing'))	
														<li>
															<a class="" href="{{ route('marketingCampaign') }}">Marketing </a>
															<p class="text-dark-50">
																Boost sales and fill your calendar with intelligent marketing tools
															</p>
														</li>
													@endif	
													@if (Auth::user()->can('messages'))
														<li>
															<a class="" href="{{ route('clientMessageSetting') }}">Client Messages </a>
															<p class="text-dark-50">
																Review messages sent to clients about their appointments
															</p>
														</li>
													@endif
												</ul>
											</div>
										</div>
									@endif
									
									@if (Auth::user()->can('client_settings'))
										<div class="card">
											<div class="card-body">
												<h3 class="">Client Settings</h3>
												<ul class="p-0">
													<li>
														<a class="" href="{{ route('showreferral') }}">Referral
															Sources </a>
														<p class="text-dark-50">
															Set up custom sources to track how clients found
															your business
														</p>
													</li>
													<li>
														<a class="" href="{{ route('clientMessageSetting') }}">Client
															Notifications Settings </a>
														<p class="text-dark-50">
															Manage messages which send to clients about their
															appointments
														</p>
													</li>
													<li>
														<a class=""
															href="{{ route('cancellationReasons') }}">Cancellation
															Reasons </a>
														<p class="text-dark-50">
															Track why clients did not arrive for their
															appointments
														</p>
													</li>
												</ul>
											</div>
										</div>
									@endif
								</div>
							@endif	
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

	<!-- Modal-->
	<div class="modal fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal
	Label" aria-hidden="true">
		<div class="modal-dialog sear_popup" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<!-- <h5 class="modal-title" id="exampleModalLabel">Select Locations</h5> -->
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 18px;"></i>
					</button>
				</div>
				<div class="modal-body container">
					<div class="row">
						<div class="form-group" style="width: 100%;float: left;">
							<div class="input-icon input-icon-right">
								<input type="text" class="form-control" placeholder="What are you looking for?">
							</div>
							<span class="form-text text-muted">Search by client name, mobile, email or booking
								reference</span>
						</div>
						<div class="col-md-6 p-0">
							<h3 class="card-title">Upcoming appointments</h3>
							<span>None found</span>
						</div>
						<div class="col-md-6">
							<h3 class="card-title">Clients (recently added)</h3>
							<div class="c_in">
								<a href="">
									<div class="clent_main"
										style="display: flex; flex-direction: row; flex-grow: 1; align-items: center;">
										<div class="cli_lft" data-qa="row-button-image" style="align-self: flex-start;">
											<div class="clie_img" data-qa="avatar-border-color">
												<div class="cli_img_in" data-qa="avatar">
													<img src="https://cdn-gatekeeper-uploads.fresha.com/avatars/4275297/medium/photo.jpg?v=63776816004"
														class="_2090ha" alt="Jayesh" data-qa="avatar-image">
												</div>
											</div>
										</div>
										<div class="cli_rg"
											style="display: flex; flex-direction: column; flex-grow: 1;">
											<div class="cli_rg_in"
												style="display: flex; flex-direction: column; flex-grow: 1;">
												<p class="cli_nm" data-qa="row-button-primary">Jayesh G *</p>
												<p class="cli_nb">+91 89048 52640 *</p>

											</div>
										</div>
									</div>
								</a>
								<a href="">
									<div class="clent_main"
										style="display: flex; flex-direction: row; flex-grow: 1; align-items: center;">
										<div class="cli_lft" data-qa="row-button-image" style="align-self: flex-start;">
											<div class="clie_img" data-qa="avatar-border-color">
												<div class="cli_img_in" data-qa="avatar">
													<img src="https://cdn-gatekeeper-uploads.fresha.com/avatars/4275297/medium/photo.jpg?v=63776816004"
														class="_2090ha" alt="Jayesh" data-qa="avatar-image">
												</div>
											</div>
										</div>
										<div class="cli_rg"
											style="display: flex; flex-direction: column; flex-grow: 1;">
											<div class="cli_rg_in"
												style="display: flex; flex-direction: column; flex-grow: 1;">
												<p class="cli_nm" data-qa="row-button-primary">Jane Doe</p>
												<p class="cli_nb">jane@example.com</p>

											</div>
										</div>
									</div>
								</a>
								<a href="">
									<div class="clent_main"
										style="display: flex; flex-direction: row; flex-grow: 1; align-items: center;">
										<div class="cli_lft" data-qa="row-button-image" style="align-self: flex-start;">
											<div class="clie_img" data-qa="avatar-border-color">
												<div class="cli_img_in" data-qa="avatar">
													<img src="https://cdn-gatekeeper-uploads.fresha.com/avatars/4275297/medium/photo.jpg?v=63776816004"
														class="_2090ha" alt="Jayesh" data-qa="avatar-image">
												</div>
											</div>
										</div>
										<div class="cli_rg"
											style="display: flex; flex-direction: column; flex-grow: 1;">
											<div class="cli_rg_in"
												style="display: flex; flex-direction: column; flex-grow: 1;">
												<p class="cli_nm" data-qa="row-button-primary">Jayesh G *</p>
												<p class="cli_nb">+91 89048 52640 *</p>

											</div>
										</div>
									</div>
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

    <!-- Modal-->
	<div class="modal fade" id="changeLanguageModal" tabindex="-1" role="dialog"
    aria-labelledby="changeLanguageModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Change Language</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <i aria-hidden="true" class="ki ki-close"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <div class="form-group">
                        <label for="exampleSelect1">Language</label>
                        <select class="form-control" id="exampleSelect1">
                            <option value="bg">🇧🇬 български</option>
                            <option value="cs">🇨🇿 čeština</option>
                            <option value="da">🇩🇰 dansk</option>
                            <option value="de">🇩🇪 Deutsch</option>
                            <option value="el">🇬🇷 Ελληνικά</option>
                            <option value="en">🇬🇧 English</option>
                            <option value="es">🇪🇸 español</option>
                            <option value="fi">🇫🇮 suomi</option>
                            <option value="fr">🇫🇷 français</option>
                            <option value="hr">🇭🇷 hrvatski</option>
                            <option value="hu">🇭🇺 magyar</option>
                            <option value="it">🇮🇹 italiano</option>
                            <option value="lt">🇱🇹 lietuvių</option>
                            <option value="nb">🇳🇴 norsk bokmål</option>
                            <option value="nl">🇳🇱 Nederlands</option>
                            <option value="pl">🇵🇱 polski</option>
                            <option value="pt">🇵🇹 português</option>
                            <option value="ro">🇷🇴 română</option>
                            <option value="sv">🇸🇪 svenska</option>
                            <option value="ru">🇷🇺 русский</option>
                            <option value="uk">🇺🇦 українська</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="modal-footer text-center">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal">Save</button>

            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<script src="{{ asset('js/addStaffClosed.js') }}"></script>
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>

@endsection