{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')

	<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
		<!--begin::Tabs-->
		@include('layouts.onlineBookingNav')
		<!--end::Tabs-->
		<!--begin::Entry-->
		<div class="d-flex flex-column-fluid pt-10">
			<!--begin::Container-->
			<div class="container">
				<!--begin::Sales-->
				<!--begin::Row-->
				<div class="row my-4">
					<div class="container online-profile">

						@foreach($locationData as $locKey => $location)
							<div class="card mb-3 shadow single-location-container{{ $location->id }}">
								<div class="row no-gutters">
									<div class=" col-12 col-sm-12 col-md-3">
										<div class="card-img p-3">
											<img src="{{ ($location->location_image != "") ? url($location->location_image) : asset('assets/images/online-profile.jpg') }}" class="img-fluid rounded card-img" alt="">
										</div>
									</div>
									<div class="col-12 col-sm-12 col-md-9">
										<div class="card-body">
											<div class="d-flex justify-content-between flex-wrap">
												<div>
													<h3 class="card-title m-0 font-weight-bolder">{{ $location->location_name }}</h3>
													<p class="card-text text-muted">{{ $location->location_address }}</p>
												</div>
												<div>
													<h5 class="card-text text-muted">Updated {{ date('D d F, Y',strtotime($location->updated_at)) }}
													</h5>
												</div>
											</div>
											<div class="d-flex flex-wrap justify-content-between align-items-center">
													<div class="badge badge-success text-uppercase online-buttons" style="{{ ($location->is_online) ? '' : 'display: none;' }}">Online</div>
													<div class="badge badge-secondary text-uppercase offline-buttons" style="{{ ($location->is_online) ? 'display: none;' : '' }}">Offline</div>
												<span>
													<div class="online-buttons" style="{{ ($location->is_online) ? '' : 'display: none;' }}">
														<div class="dropdown dropdown-inline mr-2">
															<button type="button" class="btn btn-white my-2 font-weight-bolder dropdown-toggle my-2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
																Option</button>
															<div class="dropdown-menu dropdown-menu-right p-0">
																<ul class="listgroup navi flex-column navi-hover">
																	<li class="list-group-item navi-item">
																		<a href="{{ url('partners/online_booking/edit_profile/'.$location->id) }}"
																			class="navi-link">
																			<span class="navi-text">Edit
																				Profile</span>
																		</a>
																	</li>
																	<li class="list-group-item navi-item">
																		<a href="javascript:void(0)" data-toggle="modal"
																			data-target="#unlistModal"
																			class="navi-link openUnlistModal" data-location_id="{{ $location->id }}">
																			<span class="navi-text">Unlist
																				Profile</span>
																		</a>
																	</li>
																</ul>
															</div>
														</div>
														<a href="{{ route('search_detail',['id' => Crypt::encryptString($location->id)]) }}" target="_blank" class="btn btn-primary">View on ScheduleDown
														</a>
													</div>
													<div class="offline-buttons" style="{{ ($location->is_online) ? 'display: none;' : '' }}">
														<a href="{{ route('search_detail',['id' => Crypt::encryptString($location->id)]) }}" target="_blank" class="btn btn-white my-2 font-weight-bolder">Preview</a>
														<a href="{{ url('partners/online_booking/edit_profile/'.$location->id.'/1') }}" class="btn btn-primary">List Profile</a>
													</div>
												</span>
											</div>
										</div>
									</div>
								</div>
							</div>
						@endforeach


						{{-- <div class="card mb-3 shadow">
							<div class="row no-gutters">
								<div class=" col-12 col-sm-12 col-md-3">
									<div class="card-img p-3">
										<img src="./assets/images/online-profile.jpg"
											class="img-fluid rounded card-img" alt="">
									</div>
								</div>
								<div class="col-12 col-sm-12 col-md-9">
									<div class="card-body">
										<div class="d-flex justify-content-between flex-wrap">
											<div>
												<h3 class="card-title m-0 font-weight-bolder">Gym area</h3>
												<p class="card-text text-muted">Canada Way, Burnaby, British
													Columbia</p>
											</div>
											<div>
												<h5 class="card-text text-muted">Updated Sat 2 Jan, 2021
												</h5>
											</div>
										</div>
										<div
											class="d-flex flex-wrap justify-content-between align-items-center">
											<div class="badge badge-danger text-uppercase">Offline</div>
											<span>
												<div class="dropdown dropdown-inline mr-2">
													<button type="button"
														class="btn btn-white my-2 font-weight-bolder dropdown-toggle my-2"
														data-toggle="dropdown" aria-haspopup="true"
														aria-expanded="false">
														Option</button>
													<div class="dropdown-menu dropdown-menu-right p-0">
														<ul class="listgroup navi flex-column navi-hover">
															<li class="list-group-item navi-item">
																<a href="edit_profile.html"
																	class="navi-link">
																	<span class="navi-text">Edit
																		Profile</span>
																</a>
															</li>
															<li class="list-group-item navi-item">
																<a href="javascript:void(0)" data-toggle="modal"
																	data-target="#unlistModal"
																	class="navi-link openUnlistModal">
																	<span class="navi-text">Unlist
																		Profile</span>
																</a>
															</li>
														</ul>
													</div>
												</div>
												<a href="#" class="btn btn-primary">View on ScheduleDown
												</a>
											</span>
										</div>
									</div>
								</div>
							</div>
						</div> --}}
					</div>
				</div>
				<!--end::Row-->
				<!--end::Sales-->
			</div>
			<!--end::Container-->
		</div>
		<!--end::Entry-->
	</div>
	<div class="modal fade" id="unlistModal" tabindex="-1" role="dialog" aria-labelledby="unlistModalLabel"
		aria-hidden="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h3 class="modal-title font-weight-bolder" id="unlistModalLabel">Unlist Profile</h3>
					<p class="cursor-pointer m-0" data-dismiss="modal" aria-label="Close"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
				</div>
				<div class="modal-body">
					<p class="mb-6">Are you sure you want to remove your online booking profile?</p>
					<ul class="unlist-profile">
						<li>Clients will <b>not be able to book new appointments</b> with your business, and will not be
							able to reschedole or cancel their
							bookings online</li>

						<li>
							Clients will <b>no longer find your business online,</b> your profile will be removed from
							the Fresha website, mobile apps, and
							all online booking integrations
						</li>

						<li>
							You will no longer be able to collect <b>client payments through integrated card
								processing,</b> including no show and
							cancelation fees, while your online booking profile is of
						</li>

						<li>
							Your existing <b>marketing campaigns will stop sending,</b> you will be unable to send new
							marketing campaigns to clients.
						</li>
					</ul>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="button" class="btn btn-danger unlistProfileButton" data-location_id="">Unlist Profile</button>
				</div>
			</div>
		</div>
	</div>
@endsection
{{-- Scripts Section --}}
@section('scripts')
<script type="text/javascript">
	$(document).ready(function(){
		$(document).on('click','.openUnlistModal', function(){
			$('.unlistProfileButton').attr('data-location_id', $(this).attr('data-location_id'));
			$('#unlistModal').modal('show');
		});

		$(document).on('click','.unlistProfileButton', function(){
		    var self = $(this);
		    var location_id = self.attr('data-location_id');

		    $.ajax({
		        url: '{{ route("makeLocationOffline") }}',
		        type: 'POST',
		        dataType: 'JSON',
		        data: {
		            "_token": "{{ csrf_token() }}",
		            location_id: location_id
		        },
		        success: function(response) {
		            if(response.status) {
		                $('.single-location-container'+location_id).find('.offline-buttons').show();
		                $('.single-location-container'+location_id).find('.online-buttons').hide();

		                $('#unlistModal').modal('hide');
		                responseMessages('success', response.message);
		            } else {
		                responseMessages('error', response.message);
		            }
		        },
		        error: function(response) {
		            responseMessages('error', 'Something went wrong. Please reload and try again.');
		        },
		        complete: function(response) {

		        }
		    });
		});
	});
</script>
@endsection