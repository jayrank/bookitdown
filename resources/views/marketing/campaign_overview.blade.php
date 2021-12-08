@extends('layouts.index')
@section('innercss')
@endsection
@section('content')
	<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
		<!--begin::Tabs-->
		<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
			<div
				class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
				<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
					role="tablist">
					<!-- <li class="nav-item">
						<a class="nav-link" href="{{ route('marketingCampaign') }}">Overview</a>
					</li> -->
					<li class="nav-item">
						<a class="nav-link active" href="{{ route('smart_campaigns') }}">Smart Campaigns</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{ route('marketing_blast_messages') }}">Blast messages</a>
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
					<div class="w-100 my-6 p-8 content-header d-flex flex-wrap justify-content-between">
						<div class="">
							<h3 class="font-weight-bolder">
								<a class="text-blue cursor-pointer" onclick="history.back()"><i
										class="text-dark fa fa-arrow-left"></i></a>
								{{$smartCampaign->campaign_type}}
								<span class="ml-4 px-4 py-2 badge badge-success" id="isActive">{{ ($smartCampaign->enable_sales == 1) ? 'Active' : 'Deactive' }}</span>
							</h3>
							<h5 class="font-weight-bolder text-dark-50">Sends to clients on the day of their
								birthday</h5>
						</div>
						<div class="action-btn-div">
							<div class="dropdown dropdown-inline mr-2">
								<button type="button"
									class="btn btn-white my-2 font-weight-bolder dropdown-toggle my-2"
									data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									Option</button>
								<div class="dropdown-menu text-center dropdown-menu-right">
									<ul class="navi flex-column navi-hover py-2">
										<li class="navi-item">
											@if($smartCampaign->enable_sales == 1)
												<a href="javascript:;" class="navi-link enableSales" data-val="0" data-id="{{ $smartCampaign->id }}">
													<span class="navi-text campaignStatus">Pause</span>
												</a>
											@else
												<a href="javascript:;" class="navi-link enableSales" data-val="1" data-id="{{ $smartCampaign->id }}">
													<span class="navi-text campaignStatus">Resume</span>
												</a>
											@endif

										</li>
										<li class="navi-item">
											<a href="{{ route('edit_campaigns',['id'=>Crypt::encryptString($smartCampaign->id)]) }}" class="navi-link">
												<span class="navi-text">Edit</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="javascript:;" data-id="{{ $smartCampaign->id }}" class="navi-link loadPreview">
												<span class="navi-text">Preview</span>
											</a>
										</li>
										<li class="navi-item">
											<a href="#" class="navi-link resetCampaign" data-id="{{ $smartCampaign->id }}" data-default-id="{{ $smartCampaign->default_campaign_id }}">
												<span class="navi-text">Reset Campaign</span>
											</a>
										</li>
									</ul>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6">
						<div class="card">
							<div class="card-body p-4 shadow-sm">
								<div class="d-flex align-items-center justify-content-between">
									<div>
										<h4 class="font-weight-bolder">Send to</h4>
									</div>
									<span style="width: 32px;height:32px">
										<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 32">
											<g fill="none" fill-rule="evenodd">
												<rect fill="#4EA1FF" width="32" height="32" rx="16">
												</rect>
												<path d="M22.671 8.193l-2.687 13.438a.8.8 0 01-1.174.543l-.09-.06-3.466-2.599-3.288 3.838.004-6.818.22-.147a.496.496 0 01.017-.01l3.882-2.594a.5.5 0 01.626.775l-.07.057-3.31 2.209 5.73 4.299 2.263-11.317-11.317 4.526 1.024.768a.5.5 0 01.148.623l-.048.077a.5.5 0 01-.623.147l-.077-.047-1.308-.98a.8.8 0 01.085-1.336l.098-.048 13.361-5.344zm-9.702 9.609l-.002 2.845 1.485-1.733-1.483-1.112z" fill="#FFF"></path>
											</g>
										</svg>
									</span>
								</div>
								<h2 class="my-4">{{ $emailCounter['sent_email'] }}</h2>
								<div class="progressbar">
									<h5 class="font-weight-bolder">{{ number_format($emailCounter['percentage'],2) }}%</h5>
									<div class="progress">
										<div class="progress-bar bg-success" role="progressbar"
											style="width: {{ $emailCounter['percentage'] }}%;" aria-valuenow="{{ number_format($emailCounter['percentage'],2) }}" aria-valuemin="0"
											aria-valuemax="100">{{ number_format($emailCounter['percentage'],2) }}%
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-sm-12 col-md-6">
						<div class="card">
							<div class="card-body p-4 shadow-sm">
								<div class="d-flex align-items-center justify-content-between">
									<div>
										<h4 class="font-weight-bolder">Links clicked</h4>
									</div>
									<span style="width: 32px;height:32px">
										<svg viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg">
											<g fill="none" fill-rule="evenodd">
												<rect fill="#60C290" width="32" height="32" rx="16"></rect>
												<path d="M19.809 10.114a.556.556 0 01.133.667l-.049.08L11.677 22l-3.54-4a.557.557 0 01.02-.754.477.477 0 01.64-.041l.067.064 2.744 3.1 7.499-10.165a.48.48 0 01.702-.09zm4.008.006c.19.166.236.45.12.67l-.05.08-8.535 11.112-1.209-1.312a.557.557 0 01.007-.753.477.477 0 01.638-.055l.069.062.427.464 7.829-10.193a.48.48 0 01.704-.075z" fill="#FFF"></path>
											</g>
										</svg>
									</span>
								</div>
								<h2 class="my-4">0</h2>
								<div class="progressbar">
									<h5 class="font-weight-bolder">0%</h5>
									<div class="progress">
										<div class="progress-bar" role="progressbar" style="width: 0%;"
											aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					{{-- <div class="col-12 my-4">
						<div class="card shadow-sm">
							<div class="card-body text-center">
								<h5 class="font-weight-bolder">Total Sales</h5>
								<h1 class="font-weight-bolder">CA $0</h1>
							</div>
						</div>
					</div> --}}
					<div class="col-12 my-4">
						<div class="card shadow-sm">
							<div class="card-header p-4">
								<h3 class="font-weight-bolder">Campaign details</h3>
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-12 col-sm-12 col-md-6">
										<h3 class="font-weight-bolder">History</h3>
										@if(!empty($lastActiveDate))
											<h6>Activated on {{ date('l, d M Y',strtotime($lastActiveDate->created_at)) }} by {{ $createdBy->created_by }}</h6>
										@endif
										<h6>Updated on {{ date('l, d M Y',strtotime($smartCampaign->updated_at)) }} by {{ $createdBy->created_by }}</h6>
										<h6>Created on {{ date('l, d M Y',strtotime($smartCampaign->created_at)) }} by {{ $createdBy->created_by }}</h6>
									</div>
									<div class="col-12 col-sm-12 col-md-6">
										<h3 class="font-weight-bolder">Discount</h3>
										<h6>
											<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#ServiceModal">
												@php 
												$services = ""; 
												$selectedService = json_decode($smartCampaign->services);
												@endphp
												@if($selectedService == count($allServices))
													@php $services = "discount applied to all services" @endphp
												@else
													@php $services = "off specific services" @endphp
												@endif
												 	{{ ($smartCampaign->discount_type == 1) ? "CA $".$smartCampaign->discount_value : $smartCampaign->discount_value.'%'  }}
												 	{{ $services }}
											</a>
										</h6>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal" id="PreviewBirthdayModal">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="font-weight-bolder modal-title">Preview Campaign</h4>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<div class="card" id="marketingPreview">
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="modal" id="resetCampaigns">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="font-weight-bolder modal-title">Are You Sure?</h4>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<h4>Resetting will delete your campaign's custom text, images, discount offer and selected audience.
					</h4>
				</div>
				<input type="hidden" id="campaign_id">
				<input type="hidden" id="default_campaign_id">
				<div class="modal-footer">
					<button type="button" class="btn btn-danger resetCampaignBtn">Reset</button>
				</div>
			</div>
		</div>
	</div>
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

{{-- Scripts Section --}}
@section('scripts')
	<!--end::Scrolltop-->
	<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
	<!--begin::Global Config(global config for global JS scripts)-->
	<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
	<!-- <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script> -->
	<!-- <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script> -->
	<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
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
		});
		$('.enableSales').on('click',function(){
			$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });
	        var id = $(this).attr('data-id');
	        var val = $(this).attr('data-val');
	        $.ajax({
	            url : "{{ route('changeSalesStatus') }}",
	            data : {id:id,val:val},
	            type : 'POST',
	            success : function(data)
	            {
					if(val == 1)
					{
						$('#isActive').html("Active");
						$('.enableSales').attr('data-val',0);
						$('.campaignStatus').html('Paused');
					}
					else
					{
						$('#isActive').html("Deactive");
						$('.enableSales').attr('data-val',1);
						$('.campaignStatus').html('Resume');
					}
	            	if(data.status == true)
					{
						validationMsg('success',data.message);	
					}
					else
					{
						validationMsg('error',data.message);
					}
	            }
	        });
		});	
		$('.loadPreview').on('click',function(){
			var id = $(this).data('id');
			$.ajaxSetup({
	            headers: {
	                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
	            }
	        });
	        $.ajax({
	            url : "{{ route('loadMarketingPreview') }}",
	            data : {id:id},
	            type : 'POST',
	            success : function(data)
	            {
	            	if(data.status == true)
	            	{
	            		$('#marketingPreview').html(data.html);
	            		$('#PreviewBirthdayModal').modal('show');
	            	}
	            }
	        });
		});
		$('.resetCampaign').on('click',function(){
			var campaignId = $(this).data('id');
			var defaultCampaignId = $(this).data('default-id');
			$('#campaign_id').val(campaignId);
			$('#default_campaign_id').val(defaultCampaignId);
			$('#resetCampaigns').modal('show');
		});
		$('.resetCampaignBtn').on('click',function(){
			$.ajaxSetup({
			   headers: {
			     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
			   }
			});
			var campaign_id = $('#campaign_id').val();
			var default_campaign_id = $('#default_campaign_id').val();
			$.ajax({
				type:'POST',
				url:'{{ route("resetCampaign") }}',
				data:{default_campaign_id:default_campaign_id,campaign_id:campaign_id},
				success:function(resp){
					$('.overlay-loader').hide();
					if(resp.status == true)
					{
						validationMsg('success',resp.message);
						$('#resetCampaigns').modal('hide');
					}else{
						validationMsg('error','Something went wrong');
					}
				}
			});
			$('.overlay-loader').show();
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
@endsection
</body>
<!--end::Body-->

</html>