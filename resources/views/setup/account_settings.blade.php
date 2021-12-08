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
			
			<!--end::Topbar-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Header-->
	<!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid" id="kt_content">
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
							<form name="account" id="account" action="{{ route('accountSetting') }}" >
								@csrf
								<div class="">
									<div>
										<a class="text-blue" href="{{ route('setup') }}">
											<h4>Back to setup</h4>
										</a>
										<h2>Account Settings</h2>
										<h6 class="text-dark-50">
											Manage settings such as your business name and time zone
										</h6>
									</div>
									<div class="mt-6 mb-2 row">
										<div class="col-12 col-md-5">
											<h2>Business Info</h2>
											<h6 class="text-dark-50">
												Your business name is displayed across many areas including on
												your online booking profile, sales invoices and messages
												to clients
											</h6>
										</div>
										<div class="col-12 col-md-7">
											<div class="card">
												<div class="card-body">
													<div class="form-group">
														<label class="font-weight-bolder">Business name</label>
														<input type="text" placeholder="" 
															name="business_name" value="{{ (isset($ac->business_name)) ? $ac->business_name : '' }}" class="form-control">
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="my-2 row">
										<div class="col-12 col-md-5">
											<h2>Time and Calendar Settings</h2>
											<h6 class="text-dark-50">
												Choose the time zone and format which suit your business.
												Daylight savings changes will automatically apply based on
												your selected time zone
											</h6>
										</div>
										<div class="col-12 col-md-7">
											<div class="card">
												<div class="card-body">
													<div class="form-group">
														<label class="font-weight-bolder">Time zone</label>
														<select class="form-control m-select2-general" name="timezone">
															@foreach($timezones as $key => $zone)
																<option @if(isset($ac->timezone) && $ac->timezone == $zone) selected @endif value="{{ $zone }}">{{ $key }}</option>
															@endforeach;		
														</select>
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Time format</label>
														<select class="form-control m-select2-general" name="timeformat">
															<option value="12h"@if(isset($ac->timeformat) && $ac->timeformat=='12h') selected @endif>12 hours (e.g. 9:00pm)
															</option>
															<option value="24h"@if(isset($ac->timeformat) && $ac->timeformat=='24h') selected @endif>24 hours (e.g. 21:00)
															</option>
														</select>
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Week start</label>
														<select class="form-control m-select2-general" name="weekStart">
															<option value="sunday"@if(isset($ac->weekStart) && $ac->weekStart=='sunday') selected @endif>Sunday</option>
															<option value="monday"@if(isset($ac->weekStart)&& $ac->weekStart=='monday') selected @endif>Monday</option>
															<option value="tuesday"@if(isset($ac->weekStart)&& $ac->weekStart=='tuesday') selected @endif>Tuesday</option>
															<option value="wednesday"@if(isset($ac->weekStart)&& $ac->weekStart=='wednesday') selected @endif>Wednesday</option>
															<option value="thursday"@if(isset($ac->weekStart)&& $ac->weekStart=='thursday') selected @endif>Thursday</option>
															<option value="friday"@if(isset($ac->weekStart)&& $ac->weekStart=='friday') selected @endif>Friday</option>
															<option value="saturday"@if(isset($ac->weekStart)&& $ac->weekStart=='saturday') selected @endif>Saturday</option>
														</select>
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Appointment color
															source</label>
														<select class="form-control m-select2-general" name="appointmentColorSource">
															<option value="employee"@if(isset($ac->appointmentColorSource) &&$ac->appointmentColorSource=='employee') selected @endif>Employee</option>
															<option value="service_group"@if(isset($ac->appointmentColorSource)&&$ac->appointmentColorSource=='service_group') selected @endif>Service group</option>
															<option value="status"@if(isset($ac->appointmentColorSource)&&$ac->appointmentColorSource=='status') selected @endif>Status</option>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<!-- <div class="my-2 row">
										<div class="col-12 col-md-5">
											<h2>Language Settings</h2>
											<h6 class="text-dark-50">
												Choose the default language for appointment notification
												messages sent to your clients. Per-client language
												preferences
												can also be set within the settings for each client.
											</h6>
										</div>
										<div class="col-12 col-md-7">
											<div class="card">
												<div class="card-body">
													<div class="form-group">
														<label class="font-weight-bolder">Client notification
															language
														</label>
														<select class="form-control m-select2-general"
															name="communicationLanguageCode">
															<option disabled="" value="">Select option</option>
															<option value="bg" @if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='bg') selected @endif>🇧🇬 Bulgarian (български)</option>
															<option value="cs"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='cs') selected @endif>🇨🇿 Czech (čeština)</option>
															<option value="da"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='da') selected @endif>🇩🇰 Danish (dansk)</option>
															<option value="de"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='de') selected @endif>🇩🇪 German (Deutsch)</option>
															<option value="el"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='el') selected @endif>🇬🇷 Greek (Ελληνικά)</option>
															<option value="en"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='en') selected @endif>🇬🇧 English (English)</option>
															<option value="es"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='es') selected @endif>🇪🇸 Spanish (español)</option>
															<option value="fi"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='fi') selected @endif>🇫🇮 Finnish (suomi)</option>
															<option value="fr"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='fr') selected @endif>🇫🇷 French (français)</option>
															<option value="hr"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='hr') selected @endif>🇭🇷 Croatian (hrvatski)</option>
															<option value="hu"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='hu') selected @endif>🇭🇺 Hungarian (magyar)</option>
															<option value="it"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='it') selected @endif>🇮🇹 Italian (italiano)</option>
															<option value="lt"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='lt') selected @endif>🇱🇹 Lithuanian (lietuvių)</option>
															<option value="nb"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='nb') selected @endif>🇳🇴 Norwegian Bokmål (norsk
																bokmål)</option>
															<option value="nl"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='nl') selected @endif>🇳🇱 Dutch (Nederlands)</option>
															<option value="pl"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='pl') selected @endif>🇵🇱 Polish (polski)</option>
															<option value="pt"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='pt') selected @endif>🇵🇹 Portuguese (português)
															</option>
															<option value="ro"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='ro') selected @endif>🇷🇴 Romanian (română)</option>
															<option value="sv"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='sv') selected @endif>🇸🇪 Swedish (svenska)</option>
															<option value="ru"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='ru') selected @endif>🇷🇺 Russian (русский)</option>
															<option value="uk"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='uk') selected @endif>🇺🇦 Ukrainian (українська)
															</option>
															<option value="sl"@if(isset($ac->communicationLanguageCode)&&$ac->communicationLanguageCode=='sl') selected @endif>🇸🇮 Slovenian (slovenščina)
															</option>
														</select>
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Default language for
															your staff
														</label>
														<select class="form-control m-select2-general"
															name="employeeLanguageCode">
															<option disabled="" value="">Select option</option>
															<option value="bg" @if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='bg') selected @endif>🇧🇬 Bulgarian (български)</option>
															<option value="cs"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='cs') selected @endif>🇨🇿 Czech (čeština)</option>
															<option value="da"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='da') selected @endif>🇩🇰 Danish (dansk)</option>
															<option value="de"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='de') selected @endif>🇩🇪 German (Deutsch)</option>
															<option value="el"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='el') selected @endif>🇬🇷 Greek (Ελληνικά)</option>
															<option value="en"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='en') selected @endif>🇬🇧 English (English)</option>
															<option value="es"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='es') selected @endif>🇪🇸 Spanish (español)</option>
															<option value="fi"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='fi') selected @endif>🇫🇮 Finnish (suomi)</option>
															<option value="fr"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='fr') selected @endif>🇫🇷 French (français)</option>
															<option value="hr"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='hr') selected @endif>🇭🇷 Croatian (hrvatski)</option>
															<option value="hu"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='hu') selected @endif>🇭🇺 Hungarian (magyar)</option>
															<option value="it"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='it') selected @endif>🇮🇹 Italian (italiano)</option>
															<option value="lt"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='lt') selected @endif>🇱🇹 Lithuanian (lietuvių)</option>
															<option value="nb"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='nb') selected @endif>🇳🇴 Norwegian Bokmål (norsk
																bokmål)</option>
															<option value="nl"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='nl') selected @endif>🇳🇱 Dutch (Nederlands)</option>
															<option value="pl"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='pl') selected @endif>🇵🇱 Polish (polski)</option>
															<option value="pt"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='pt') selected @endif>🇵🇹 Portuguese (português)
															</option>
															<option value="ro"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='ro') selected @endif>🇷🇴 Romanian (română)</option>
															<option value="sv"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='sv') selected @endif>🇸🇪 Swedish (svenska)</option>
															<option value="ru"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='ru') selected @endif>🇷🇺 Russian (русский)</option>
															<option value="uk"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='uk') selected @endif>🇺🇦 Ukrainian (українська)
															</option>
															<option value="sl"@if(isset($ac->employeeLanguageCode)&&$ac->employeeLanguageCode=='sl') selected @endif>🇸🇮 Slovenian (slovenščina)
															</option>
														</select>
													</div>
													<div class="bagde badge-secondary p-2">
														<h6>New Staff will see Fresha in this language but
															they can override this in their personal user
															settings.
														</h6>
													</div>
												</div>
											</div>
										</div>
									</div> -->

									<div class="row">
										<div class="col-12 col-md-5">
											<h2>Online Links</h2>
											<h6 class="text-dark-50">
												Add your company website and social media links for sharing with
												clients
											</h6>
										</div>
										<div class="col-12 col-md-7">
											<div class="card">
												<div class="card-body">
													<div class="form-group">
														<label class="font-weight-bolder">Website</label>
														<div class="input-icon">
															<input type="text" class="form-control" name="website" value="{{ isset($ac->website) }}"
																placeholder="www.yoursite.com">
															<span>
																<i class="fa fa-globe icon-md"></i>
															</span>
														</div>
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Facebook page</label>
														<div class="input-icon">
															<input type="text" class="form-control" name="facebook" value="{{ isset($ac->facebook) }}"
																placeholder="www.facebook.com/yoursite">
															<span>
																<i class="fab fa-facebook icon-md"></i>
															</span>
														</div>
													</div>
													<div class="form-group">
														<label class="font-weight-bolder">Instagram page</label>
														<div class="input-icon">
															<input type="text" class="form-control" name="Instagram" value="{{ isset($ac->Instagram) }}"
																placeholder="www.instagram.com/yoursite">
															<span>
																<i class="fab fa-instagram icon-md"></i>
															</span>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<button type="submit" class="btn btn-primary mt-4" id="updateSetting" style="float: right">Save changes</button>
							</form>
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
							<select class="form-control m-select2-general" id="exampleSelect1">
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

@endsection

@section('scripts')
<script src="{{ asset('js/addaccountSetting.js') }}"></script>
<script>
	$(".m-select2-general").select2({placeholder:"Select an option"})
</script>

@endsection