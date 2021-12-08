{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
<link href="{{ asset('assets/css/intlTelInput.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
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
		<div class="d-flex flex-column-fluid">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Sales-->
				<!--begin::Row-->
				<div class="row">
					<div class="marketing">
						<div class="p-10 mt-12">
							<div class="col-12 col-md-12 col-lg-12 p-0">
								<h2 class="mb-4 font-weight-bolder">
									Smart Campaigns
								</h2>
								<h5 class="desc mb-4">
									Choose which Smart Campaigns to enable. Once enabled, you can
									personalise the message, amend the discount offer and
									choose which clients to send the message to.
								</h5>
							</div>
							<div class="row">
								@if(!empty($defaultCampaign))
									@foreach($defaultCampaign as $key => $value)
										<div class="col-12 col-sm-12 col-md-4">
											<div class="card shadow-sm">
												<div class="card-body p-8">
													<div class="card-icon mb-6" style="width: 50px;">
														@php
															echo $value['card_icon'];
														@endphp
													</div>
													<h4 class="mb-3 font-weight-bolder">{{ $value['type'] }}</h4>
													<h6 class="text-dark-50 font-weight-bold">
														{{ $value['default_content'] }}
													</h6>
												</div>
												@if(empty($value['campaign_data']))
													<div class="card-footer p-4 d-flex align-items-center justify-content-between flex-wrap">
														<a href="{{ route('add_campaigns',['id'=>Crypt::encryptString($value['id'])]) }}"
															class="btn btn-md btn-primary">Enable</a>
													</div>
												@else
													<div class="card-footer p-4 d-flex align-items-center justify-content-between flex-wrap">
														<div class="form-group mb-0">
															<div class="switch switch-sm switch-icon switch-success" style="line-height: 28px;">
																<label class="d-flex align-item-center font-weight-bolder">
																	<input type="checkbox" checked="checked"
																		name="select">
																	<span></span>&nbsp;Enable sales</i>
																</label>
															</div>
														</div>
														<div class="dropdown dropdown-inline">
															<a href="#" class="btn btn-clean text-dark btn-sm btn-icon"
																data-toggle="dropdown" aria-haspopup="true"
																aria-expanded="false">
																<i class="ki ki-bold-more-hor text-dark"></i>
															</a>
															<div class="dropdown-menu dropdown-menu-sm text-center dropdown-menu-right">
																<ul class="navi navi-hover">
																	<li class="navi-item">
																		<a href="{{ route('edit_campaigns',['id'=>Crypt::encryptString($value['campaign_data']['id'])]) }}"
																			class="navi-link">
																			<span class="navi-text">Edit</span>
																		</a>
																	</li>
																	<li class="navi-item">
																		<a href="{{ route('campaign_overview',['id'=>Crypt::encryptString($value['campaign_data']['id'])]) }}"
																			class="navi-link">
																			<span class="navi-text">Overview</span>
																		</a>
																	</li>
																	<li class="navi-item">
																		<a href="javascript:;" data-id="{{ $value['campaign_data']['id'] }}" class="navi-link loadPreview">
																			<span class="navi-text">Preview</span>
																		</a>
																	</li>
																	<li class="navi-item">
																		<!--  data-toggle="modal"
																			data-target="#sendTestEmail" -->
																		<a class="navi-link send_test_email" data-id="{{ $value['campaign_data']['id'] }}">
																			<span class="navi-text">Send test
																				email</span>
																		</a>
																	</li>
																	<li class="navi-item">
																		<a href="#" class="navi-link resetCampaign" data-id="{{ $value['campaign_data']['id'] }}" data-default-id="{{ $value['id'] }}">
																			<span class="navi-text">Reset campaigns</span>
																		</a>
																	</li>
																</ul>
															</div>
														</div>
													</div>
												@endif
												{{--  --}}
											</div>
										</div>
									@endforeach
								@endif
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
	<div class="modal" id="sendTestEmail">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="font-weight-bolder modal-title">Send a test email</h4>
					<p class="cursor-pointer m-0 px-2" data-dismiss="modal"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<form id="SendCampaignMessage" method="post">
					<input type="hidden" name="id" id="sendEmailId">
					<div class="modal-body">
						<div class="form-group">
							<label class="font-weight-bold">Email</label>
							<input type="email" name="email" class="form-control" placeholder="email@gmail.com" required="required" />
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-white" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-primary">Send Email</button>
					</div>
				</form>
			</div>
		</div>
	</div>
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script type="text/javascript">
	$('.enableSales').on('change',function(){
		$.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var id = $(this).data('id');
        var val = ($(this).prop('checked') == true) ? 1 : 0;
        $.ajax({
            url : "{{ route('changeSalesStatus') }}",
            data : {id:id,val:val},
            type : 'POST',
            success : function(data)
            {
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
	$('.send_test_email').on('click',function(){
		$('#sendEmailId').val($(this).data('id'));
		$('#sendTestEmail').modal('show');
	});
	$('#SendCampaignMessage').on('submit',function(e){
		e.preventDefault();
		$.ajaxSetup({
		   headers: {
		     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		   }
		});
		$('.overlay-loader').show();
		var data = $(this).serialize();
		$.ajax({
			type:'POST',
			url:'{{ route("sendCampaignEmail") }}',
			data:data,
			success:function(resp){
				$('.overlay-loader').hide();
				if(resp.status == true)
				{
					validationMsg('success',resp.message);
					$('#sendTestEmail').modal('hide');
					document.getElementById("SendCampaignMessage").reset();
				}else{
					validationMsg('error','Something went wrong');
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
</script>
@endsection