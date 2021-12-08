{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
    <link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet" type="text/css" />
    <style>	
		table.table td span.namespan {
		    background: grey;
		    display: inline-block;
		    color: #FFF;
		    line-height: 46px;
		    text-align: center;
		    border-radius: 50%;
		    height: 46px;
		    width: 46px; 
		}
    </style>
@endsection

@section('content')
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
	
	<!--modal-->
	<div class="modal fade" id="ActiveFormModal" tabindex="-1" role="dialog" aria-labelledby="addNewQuestionModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Activate consultation form</h5>
                    <p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
                    </p>
                </div>
                <div class="modal-body">
                    <div>
						When active, <span class="name"> </span> will be sent to all clients when booking some services. Would you like to activate your consultation form?
                    </div>
                </div>
				<input type="hidden" class="formid">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" data-dismiss="model" id="saveactive">Yes, activate</button>
                </div>
            </div>
        </div>
    </div>
	
	<div class="modal fade" id="DeactiveFormModal" tabindex="-1" role="dialog" aria-labelledby="addNewQuestionModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" >Deactivate consultation form <h5>
                    <p class="cursor-pointer m-0 px-2" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
                    </p>
                </div>
                <div class="modal-body">
                    <div>
						When deactivated, <span class="name"> </span> not be sent to all clients when booking some services. Would you like to disable your consultation form?
                    </div>
                </div>
				<input type="hidden" class="formid">
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" data-dismiss="model" id="savedeactive">Yes, Deactivate</button>
                </div>
            </div>
        </div>
    </div>
	<!--end-->
	
	<!--begin::Header-->
	
	<!--end::Header-->
	<!--begin::Content-->
	<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
		<!--begin::Tabs-->
		<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
			<div
				class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
				<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
					role="tablist">
					<!-- <li class="nav-item">
						<a class="nav-link" href="{{ url('partners/conForm') }}">Overview</a>
					</li> -->
					<li class="nav-item">
						<a class="nav-link active" href="{{ route('showconForm') }}">Consultation Form</a>
					</li>
				</ul>
			</div>
		</div>
		<!--end::Tabs-->
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Sales-->
				<!--begin::Row-->
				<div class="row">
					<div class="col-12 my-6">
						<div class="d-flex justify-content-between my-6">
							<div>
								<div class="d-flex align-items-center">
									<h2 class="font-weight-bolder">
										<i onclick="history.back()"
											class="fa fa-arrow-left text-dark mr-3 cursor-pointer"
											aria-hidden="true"></i>{{ $conform->name }}
									</h2>
									@if($conform->status==0)
									<span class="ml-3 badge badge-warning text-uppercase">INACTIVE</span>
									@else 
									<span class="ml-3 badge badge-success text-uppercase">ACTIVE </span>
									@endif
								</div>
								<h6 class="mb-4">
									Created on {{ date("d F Y",strtotime($conform->created_at)) }}
								</h6>
							</div>
							<div class="dropdown dropdown-inline">
								<a href="#"
									class="btn btn-lg btn-white my-2 font-weight-bolder dropdown-toggle my-2"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Option
								</a>
								<div class="dropdown-menu dropdown-menu-right text-center">
									<ul class="navi navi-hover">
										<li class="navi-item">
											<a href="{{ route('geteditconform',$conform->id) }}" class="navi-link">
												<span class="navi-text">Edit</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="{{ route('preview',$conform->id) }}" class="navi-link">
												<span class="navi-text">Preview</span>
											</a>
										</li>
										@if($conform->status == 0)
										<li class="navi-item">
											<a href="#" data-toggle="modal" data-target="#ActiveFormModal" class="navi-link" id="activate" data-id="{{ $conform->id }}" data-name="{{ $conform->name }}">
												<span class="navi-text">Activate</span>
											</a>
										</li>
										@else
										<li class="navi-item">
											<a href="#" data-toggle="modal" data-target="#DeactiveFormModal" class="navi-link" id="deactivate" data-id="{{ $conform->id }}" data-name="{{ $conform->name }}"> 
												<span class="navi-text">Deactivate</span>
											</a>
										</li>
										@endif	
									</ul>
								</div>
							</div>
						</div>
						<div class="row my-3">
							<div class="col-12 col-sm-6 col-md-3">
								<div class="bg-white card shadow-sm p-0 rounded">
									<div class="card-body p-6">
										<div class="d-flex justify-content-between">
											<h6>Sent to</h6>
											<div style="width: 28px;height: 28px;">
												<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
													<g fill="none" fill-rule="evenodd">
														<rect fill="#4EA1FF" width="32" height="32" rx="16">
														</rect>
														<path
															d="M22.671 8.193l-2.687 13.438a.8.8 0 01-1.174.543l-.09-.06-3.466-2.599-3.288 3.838.004-6.818.22-.147a.496.496 0 01.017-.01l3.882-2.594a.5.5 0 01.626.775l-.07.057-3.31 2.209 5.73 4.299 2.263-11.317-11.317 4.526 1.024.768a.5.5 0 01.148.623l-.048.077a.5.5 0 01-.623.147l-.077-.047-1.308-.98a.8.8 0 01.085-1.336l.098-.048 13.361-5.344zm-9.702 9.609l-.002 2.845 1.485-1.733-1.483-1.112z"
															fill="#FFF"></path>
													</g>
												</svg>
											</div>
										</div>
										<h3 class="font-weight-bolder">{{ $totalSentConsultationForm }}</h3>
										<span class="text-dark-50 mt-4">{{ $TotalSentPercentage }}%</span>
										<div class="progress">
											<div class="progress-bar" role="progressbar" style="width: {{ $TotalSentPercentage }}%;"
												aria-valuenow="{{ $TotalSentPercentage }}" aria-valuemin="0" aria-valuemax="100">{{ $TotalSentPercentage }}%
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<div class="bg-white card shadow-sm p-0 rounded">
									<div class="card-body p-6">
										<div class="d-flex justify-content-between">
											<h6>Completed</h6>
											<div style="width: 28px;height: 28px;">
												<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
													<g fill="none" fill-rule="evenodd">
														<rect fill="#60C290" width="32" height="32" rx="16">
														</rect>
														<path
															d="M19.809 10.114a.556.556 0 01.133.667l-.049.08L11.677 22l-3.54-4a.557.557 0 01.02-.754.477.477 0 01.64-.041l.067.064 2.744 3.1 7.499-10.165a.48.48 0 01.702-.09zm4.008.006c.19.166.236.45.12.67l-.05.08-8.535 11.112-1.209-1.312a.557.557 0 01.007-.753.477.477 0 01.638-.055l.069.062.427.464 7.829-10.193a.48.48 0 01.704-.075z"
															fill="#FFF"></path>
													</g>
												</svg>
											</div>
										</div>
										<h3 class="font-weight-bolder">{{ $totalCompletedConsultationForm }}</h3>
										<span class="text-dark-50 mt-4">{{ $TotalCompletedPer }}%</span>
										<div class="progress">
											<div class="progress-bar" role="progressbar" style="width: {{ $TotalCompletedPer }}%;"
												aria-valuenow="{{ $TotalCompletedPer }}" aria-valuemin="0" aria-valuemax="100">{{ $TotalCompletedPer }}%
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<div class="bg-white card shadow-sm p-0 rounded">
									<div class="card-body p-6">
										<div class="d-flex justify-content-between">
											<h6>To be completed</h6>
											<div style="width: 28px;height: 28px;">
												<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
													<g fill="none" fill-rule="evenodd">
														<rect fill="#FFB81C" width="32" height="32" rx="16">
														</rect>
														<path
															d="M16 10a7 7 0 015.29 11.584l1.564 1.562a.5.5 0 01-.638.765l-.07-.057-1.562-1.563A6.973 6.973 0 0116 24a6.973 6.973 0 01-4.584-1.71l-1.562 1.564a.5.5 0 01-.765-.638l.057-.07 1.563-1.562A7 7 0 0116 10zm0 1a6 6 0 100 12 6 6 0 000-12zm-2.216 3.089l.07.057L16.707 17H19.5a.5.5 0 01.492.41l.008.09a.5.5 0 01-.41.492L19.5 18h-3a.5.5 0 01-.294-.095l-.06-.051-3-3a.5.5 0 01.638-.765zM12 8c.599 0 1.18.133 1.712.384a.5.5 0 11-.428.904 3 3 0 00-4.16 3.57.5.5 0 11-.958.284A4 4 0 0112 8zm8 0a4 4 0 013.834 5.142.5.5 0 11-.958-.284 3 3 0 00-4.16-3.57.5.5 0 01-.428-.904A3.991 3.991 0 0120 8z"
															fill="#FFF" fill-rule="nonzero"></path>
													</g>
												</svg>
											</div>
										</div>
										<h3 class="font-weight-bolder">{{ $totalToBeConsultationForm }}</h3>
										<span class="text-dark-50 mt-4">{{ $TotalTobeCompletePer }}%</span>
										<div class="progress">
											<div class="progress-bar" role="progressbar" style="width: {{ $TotalTobeCompletePer }}%;"
												aria-valuenow="{{ $TotalTobeCompletePer }}" aria-valuemin="0" aria-valuemax="100">{{ $TotalTobeCompletePer }}%
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-12 col-sm-6 col-md-3">
								<div class="bg-white card shadow-sm p-0 rounded">
									<div class="card-body p-6">
										<div class="d-flex justify-content-between">
											<h6>Not completed</h6>
											<div style="width: 28px;height: 28px;">
												<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
													<g fill="none" fill-rule="evenodd">
														<rect fill="#E45A74" width="32" height="32" rx="16">
														</rect>
														<path
															d="M10.784 10.089l.07.057L16 15.293l5.146-5.147a.5.5 0 01.765.638l-.057.07L16.707 16l5.147 5.146a.5.5 0 01-.638.765l-.07-.057L16 16.707l-5.146 5.147a.5.5 0 01-.765-.638l.057-.07L15.293 16l-5.147-5.146a.5.5 0 01.638-.765z"
															fill="#FFF"></path>
													</g>
												</svg>
											</div>
										</div>
										<h3 class="font-weight-bolder">{{ $totalNotCompletedConsultationForm }}</h3>
										<span class="text-dark-50 mt-4">{{ $TotalNotCompletePer }}%</span>
										<div class="progress">
											<div class="progress-bar" role="progressbar" style="width: {{ $TotalNotCompletePer }}%;"
												aria-valuenow="{{ $TotalNotCompletePer }}" aria-valuemin="0" aria-valuemax="100">{{ $TotalNotCompletePer }}%
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="bg-white card shadow-sm p-0 rounded my-4">
							<div class="card-header">
								<h3 class="m-0">Consultation form details</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-6">
										<div class="mb-4">
											<h6 class="font-weight-bolder">Request</h6>
											<h5 class="">@if($conform->send_request==0) Before appointment @else Manually @endif </h5>
										</div>
										<div class="mb-4">
											<h6 class="font-weight-bolder">Client signature</h6>
											<h5 class="">@if($conform->signature==1)Required @else Not Required @endif </h5>
										</div>
									</div>
									<div class="col-6">
										<div class="mb-4">
											<h6 class="font-weight-bolder">Services</h6>
											<h5 class=""><a class="cursor-pointer text-blue" data-toggle="modal" data-target="#servicesModal" >{{(json_decode($conform->service_id) ? count(json_decode($conform->service_id)) : 0)}} Services</a></h5>
										</div>
										{{-- select service modal --}}
                                        <div class="modal" id="servicesModal">
                                            <div class="modal-dialog modal-lg">
                                                <div class="modal-content">
                                                    <!-- Modal Header -->
                                                    <div class="modal-header d-flex justify-content-between">
                                                        <h4 class="modal-title">Select services</h4>
                                                        <button type="button" class="text-dark close"
                                                            data-dismiss="modal">×</button>
                                                    </div>
                                                    <!-- Modal body -->
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <div class="input-icon input-icon-right">
                                                                <input type="text" class="rounded-0 form-control"
                                                                    placeholder="Scan barcode or search any item">
                                                                <span>
                                                                    <i class="flaticon2-search-1 icon-md"></i>
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <hr class="m-0">

                                                        <div class="multicheckbox">
                                                            <ul id="treeview">
                                                                <li>
                                                                    <label for="all" class="checkbox allService">
                                                                        <input type="checkbox" name="all" id="all">
                                                                        <span></span>
                                                                        All Services
                                                                    </label>
                                                                    @foreach ($cat as $value)
                                                                        <ul>
                                                                            <li>
                                                                                <label
                                                                                    for="all-{{ $value->category_title }}"
                                                                                    class="checkbox h5 font-weight-bolder">
                                                                                    <input type="checkbox"
                                                                                        name="all-{{ $value->category_title }}"
                                                                                        id="all-{{ $value->category_title }}">
                                                                                    <span></span>
                                                                                    {{ $value->category_title }}
                                                                                </label>
                                                                                <ul>
                                                                                    @php
                                                                                        $i = 1;
                                                                                    @endphp
                                                                                    @foreach ($value->service as $service)

                                                                                        <li>
                                                                                            <label
                                                                                                for="all-{{ $value->category_title }}-{{ $i }}"
                                                                                                class="checkbox">
                                                                                                <input type="checkbox"
                                                                                                    name="value_checkbox[]"
                                                                                                    value="{{ $service->id }}" @if((json_decode($conform->service_id) ? in_array($service->id, json_decode($conform->service_id)) : false)) checked @endif
                                                                                                    id="all-{{ $value->category_title }}-{{ $i }}">
                                                                                                <span></span>
                                                                                                <div
                                                                                                    class="d-flex align-items-center w-100">
                                                                                                    <span class="m-0 h6">
                                                                                                        {{ $service->service_name }}
                                                                                                        <p
                                                                                                            class="m-0 text-muted">
                                                                                                            @foreach ($service->servicePrice as $value)
                                                                                                                {{ $value->duration }},
                                                                                                            @endforeach
                                                                                                        </p>
                                                                                                    </span>
                                                                                                    <span class="ml-auto">
                                                                                                        @foreach ($service->servicePrice as $value)
                                                                                                            CA
                                                                                                            ${{ $value->price }}
                                                                                                        @endforeach
                                                                                                    </span>
                                                                                                </div>
                                                                                            </label>
                                                                                        </li>
                                                                                        @php
                                                                                            $i++;
                                                                                        @endphp
                                                                                    @endforeach
                                                                                </ul>
                                                                            </li>

                                                                        </ul>
                                                                    @endforeach
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-light"
                                                            data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn btn-primary"
                                                            data-dismiss="modal">Select Services</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- end --}}
										<div class="mb-4">
											<h6 class="font-weight-bolder">Ask clients to complete</h6>
											<h5 class="">@if($conform->complete==1) Every time they book an appointment @else Only once @endif </h5>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="card bg-transparent p-0 rounded my-3">
							@if(!empty($ClientConsultationFormGet))
								<div class="card-body">
									<h3 class="font-weight-bolder">Consultation form requests {{ count($ClientConsultationFormGet) }}</h3>
									<hr>
									<div class="table-responsive invoice">
										<table class="table table-hover" id="consultationFormList">
											<thead>
												<tr>
													<th scope="col"></th>
													<th scope="col">Client name</th>
													<th scope="col">Requested on</th>
													<th scope="col">Completed on</th>
													<th scope="col">Status</th>
													<th scope="col"></th>
												</tr>
											</thead>
											<tbody>
												
											</tbody>
										</table>
									</div>
								</div>
							@else
								<div class="card-body text-center">
									<div class="my-4 mx-auto" style="width: 64px;height: 64px;">
										<svg viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
											<path
												d="M44.808 40.997c10.23 0 18.522-8.25 18.522-18.427S55.037 4.143 44.808 4.143c-10.23 0-18.522 8.25-18.522 18.427s8.292 18.427 18.522 18.427z"
												fill="#FBD74C"></path>
											<path fill-rule="evenodd" clip-rule="evenodd"
												d="M24 6.714c-.631 0-1.143.512-1.143 1.143v2.286h-9.143c-.63 0-1.143.511-1.143 1.143v45c0 .63.512 1.143 1.143 1.143H51.81c.63 0 1.142-.512 1.142-1.143v-45c0-.631-.511-1.143-1.142-1.143h-9.524c-.134 0-.262.023-.381.065v-2.35c0-.632-.512-1.144-1.143-1.144H24zm17.905 5.65V15c0 .631-.512 1.143-1.143 1.143H24A1.143 1.143 0 0122.857 15v-2.571h-8v42.714h35.81V12.429h-8.381a1.14 1.14 0 01-.381-.066zm-16.762 1.493V9h14.476v4.857H25.143zm15.593 13.697c.483.407.545 1.128.138 1.61l-9.143 10.858a1.143 1.143 0 01-1.748 0l-4.572-5.429a1.143 1.143 0 011.749-1.472l3.697 4.39 8.269-9.819a1.143 1.143 0 011.61-.138z"
												fill="#101928"></path>
										</svg>
									</div>
									<h3 class="font-weight-bolder">No consultation form requests yet</h3>
									<h6>Your consultation form requests and status will appear here.</h6>
								</div>
							@endif
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
	<!--end::Content-->


@endsection

{{-- Scripts Section --}}
@section('scripts')	
	<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>	
	<script src="{{ asset('js/consultationFrom.js') }}"></script>	
	<script src="{{ asset('js/application.js') }}"></script>	
	<!--end::Page Scripts-->
	<script type="text/javascript">

		function openNav() {
			document.getElementById("myFilter").style.width = "300px";
		}

		function closeNav() {
			document.getElementById("myFilter").style.width = "0%";
		}
	</script>
	<script>
		// Class definition
		var KTBootstrapDatepicker = function () {

			var arrows;
			if (KTUtil.isRTL()) {
				arrows = {
					leftArrow: '<i class="la la-angle-right"></i>',
					rightArrow: '<i class="la la-angle-left"></i>'
				}
			} else {
				arrows = {
					leftArrow: '<i class="la la-angle-left"></i>',
					rightArrow: '<i class="la la-angle-right"></i>'
				}
			}

			// Private functions
			var demos = function () {
				// minimum setup
				$('#kt_datepicker_1').datepicker({
					rtl: KTUtil.isRTL(),
					todayHighlight: true,
					autoclose: true,
					format: 'DD, dd/mm/yyyy',
					orientation: "bottom left",
					templates: arrows
				});
			}

			return {
				// public functions
				init: function () {
					demos();
				}
			};
		}();

		jQuery(document).ready(function () {
			KTBootstrapDatepicker.init();
			
			$(document).on('click','#activate', function(){
				var name = $(this).data('name');
				var id = $(this).data('id');

				$('.name').text(name);
				$('.formid').val(id);
			});
			
			$(document).on('click','#deactivate', function(){
				var name = $(this).data('name');
				var id = $(this).data('id');

				$('.name').text(name);
				$('.formid').val(id);
			});
			
			$(document).on('click','#saveactive', function(){
				var id = $('.formid').val();

				$.ajax({
					type: 'get',
					url: "{{ url('partners/conForm/activeform/') }}/"+id,
					success: function(response) {
						
						$('#ActiveFormModal').modal('hide');
						
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
						
						setTimeout(function(){ location.reload(); }, 1500);

					},
					error: function(data) {
						console.log(data);

						var errors = data.responseJSON;
			
						var errorsHtml = '';
						$.each(errors.errors, function(key, value) {
							errorsHtml += value[0];
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
						toastr.error((errorsHtml) ? errorsHtml : "Something went wrong!");
					}
				});
			});
			$(document).on('click','#savedeactive', function(){
				var id = $('.formid').val();

				$.ajax({
					type: 'get',
					url: "{{ url('partners/conForm/deactiveform/') }}/"+id,
					success: function(response) {
						
						$("#DeactiveFormModal").modal('hide');
						
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
						
						setTimeout(function(){ location.reload(); }, 1500);
					},
					error: function(data) {
						console.log(data);

						var errors = data.responseJSON;
			
						var errorsHtml = '';
						$.each(errors.errors, function(key, value) {
							errorsHtml += value[0];
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
						toastr.error((errorsHtml) ? errorsHtml : "Something went wrong!");
					}
				});
			});
		});
	</script>
	<script>
	$(function() {
		var table = $('#consultationFormList').DataTable({
			processing : true,
			serverSide : true,
			"paging"   : false,
			"ordering" : false,
			"info"     : false,
			"searching": false,
			ajax: {
				type: "POST",
				url : "{{ route('getConsultationForms') }}",
				data: {_token : "{{ csrf_token() }}","consultationFormId" : "{{ $conform->id }}"}
			},
			columns: [
				{data: 'client_firstname', profile: 'client_firstname'},
				{data: 'client_name', name: 'client_name'},
				{data: 'requested_on', name: 'requested_on'},
				{data: 'completed_on', name: 'completed_on'},
				{data: 'form_status', name: 'form_status'},
				{data: 'action', name: 'action'},
			]			
		});	
	});
	
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