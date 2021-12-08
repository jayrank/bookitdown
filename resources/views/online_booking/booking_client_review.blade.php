{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}  
@section('innercss')
@endsection

@section('content')

<style type="text/css">
	.client-review-container {
		max-width: 100%;
	    background: #fff;
	    border: 1px solid #f7f7f8;
	    box-shadow: 0 2px 5px 0 rgb(164 173 186 / 25%);
	    padding: 3%;
	    margin-bottom: 2%;
	}
	.client-review-container:hover {
		box-shadow: 0 5px 15px 5px rgb(164 173 186 / 25%);
	}
	.appointment-link {
		color: #0073e9;
	}
	.filter-value {
		background-color: #000;
		color: #fff;
		border-radius: 24px;
		padding: 5px;
		font-size: 12px;
		margin: 2px;
		cursor: pointer;
	}
	.filter-container {
		border: 1px solid #ccc;
		padding: 5px;
		margin: 2%;
		padding: 1%;
	}
	.remove-filters {
		padding: 0px 9px 4px;
		color: #fff;
		background-color: #666;
		border-radius: 999px;
		cursor: pointer;
	}
</style>

<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid p-0" id="kt_content">
	<!--begin::Tabs-->
	@include('layouts.onlineBookingNav')
	<!--end::Tabs-->
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid" style="margin-top: 3%;">
		<!--begin::Container-->
		<div class="container">
			<!--begin::Sales-->
			<!--begin::Row-->
			<div class="my-4 header-content d-flex justify-content-between">
				<div class="">
					<div class="">
						<p class="text-dark m-0" style="font-size: 1.3em;">Average rating</p>
						<h4 style="display: inline;">
							{{ number_format($averageRating, 1) }}
							<span><i class="far fa-star {{ ($averageRating >= 1) ? 'text-warning' : '' }}"></i></span>
							<span><i class="far fa-star {{ ($averageRating >= 2) ? 'text-warning' : '' }}"></i></span>
							<span><i class="far fa-star {{ ($averageRating >= 3) ? 'text-warning' : '' }}"></i></span>
							<span><i class="far fa-star {{ ($averageRating >= 4) ? 'text-warning' : '' }}"></i></span>
							<span><i class="far fa-star {{ ($averageRating >= 5) ? 'text-warning' : '' }}"></i></span>
						</h4>
						&nbsp;<span>({{ $totalRatings }})</span>
					</div>
				</div>
				<div class="action-btn-div">
					<button class="font-weight-bold btn btn-white my-2" onclick="openNav()">
						Filter <i class="fa fa-filter"></i>
					</button>
				</div>
			</div>

			@if(!empty($locationId) || !empty($rating) || !empty($status))
				<div class="row search-container">
					<div class="col-md-12">
						<div class="filter-container">

							<span class="float-right remove-filters clear-form">&times;</span>
							<span>Selected filters: </span>

							@if(!empty($locationId))
								<span class="filter-value filter-location">Location is <span class="filter-location-span"></span> &times;</span>
							@endif

							@if(!empty($rating))
								<span class="filter-value filter-rating">Rating is <span class="filter-rating-span"></span> &times;</span>
							@endif

							@if(!empty($status))
								<span class="filter-value filter-status">Status is <span class="filter-status-span"></span> &times;</span>
							@endif

						</div>
					</div>
				</div>
			@endif
			<div class="row my-4">
				<div class="container online-profile">

					@if($locationReview->isNotEmpty())
						@foreach($locationReview as $key => $value)
							<div class="client-review-container">
								<div class="row">
									<div class="col-md-1">
										<span class="client-initial">{{ strtoupper(substr($value->name, 0, 1)) }}</span>
									</div>
									<div class="col-md-9">
										<b>{{ $value->name.' '.$value->last_name }}</b>
										<h4>
											<span><i class="far fa-star {{ ($value->rating >= 1) ? 'text-warning' : '' }}"></i></span>
											<span><i class="far fa-star {{ ($value->rating >= 2) ? 'text-warning' : '' }}"></i></span>
											<span><i class="far fa-star {{ ($value->rating >= 3) ? 'text-warning' : '' }}"></i></span>
											<span><i class="far fa-star {{ ($value->rating >= 4) ? 'text-warning' : '' }}"></i></span>
											<span><i class="far fa-star {{ ($value->rating >= 5) ? 'text-warning' : '' }}"></i></span>
										</h4>
									</div>
									<div class="col-md-2">
										<select class="form-control update-status" name="update_status" data-id="{{ $value->id }}">
											<option value="pending" @if($value->status == "pending") selected @endif>Pending</option>
											<option value="publish" @if($value->status == "publish") selected @endif>Publish</option>
											<option value="unpublish" @if($value->status == "Un-publish") selected @endif>Un-publish</option>
										</select>
									</div>
								</div>
								<div class="row" style="margin-top: 1%;">
									<div class="col-md-12">
										{{ $value->feedback }}
									</div>
								</div>
								<hr>
								<div class="row">
									<div class="col-md-12">
										<span class="text-muted">{{ date('d M Y at H:ia', strtotime($value->created_at)) }}</span>
									</div>
									<div class="col-md-12">
										<span class="text-muted">Appointment ref: </span><a href="{{ route('viewAppointment', ['id' => Crypt::encryptString($value->appointment_id) ] ) }}" class="appointment-link">{{ $value->appointment_id }}</a>
									</div>
								</div>
							</div>
						@endforeach
					@else
						<div class="center w-35" style="text-align: center;margin: 50px auto; ">
							<h2 class="font-weight-bolder">No Reviews Yet</h2>
							<p>Registered users can rate completed appointments, boosting your
								profile and encouraging new clients to book</p>
						</div>
					@endif
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

<!-- Filter Sidebar -->
<div id="myFilter" class="filter-overlay" style="width:0px;">
	<form method="post" action="{{ route('clientReview') }}" class="filter-form">
		@csrf
		<a href="javascript:void(0)" class="closebtn" onClick="closeNav()">×</a>
		<div class="filter-header pt-2">
			<h2 class="text-center py-3">Filter</h2>
		</div>
		<div class="filter-overlay-content">
			<div class="filter-overlay-content p-4">
				<div class="form-group font-weight-bold">
					<label for=" select-location font-weight-bold">Location</label>
					<select class="form-control filter-location-input" name="locationId">
						<option value="0" @if($locationId == 0) selected @endif>All locations</option>

						@if($locations->isNotEmpty())
							@foreach($locations as $key => $value) 
								<option value="{{ $value->id }}"
									@if($value->id == $locationId)
										selected
									@endif
								>{{ $value->location_name }}</option>
							@endforeach
						@endif
					</select>
				</div>
				<div class="form-group font-weight-bold">
					<label for=" select-location font-weight-bold">Rating</label>
					<select class="form-control filter-rating-input" name="rating">
						<option value="0" @if($rating == 0) selected @endif>All ratings</option>
						<option value="5" @if($rating == 5) selected @endif>Excellent (5)</option>
						<option value="4" @if($rating == 4) selected @endif>Good (4)</option>
						<option value="3" @if($rating == 3) selected @endif>Okay (3)</option>
						<option value="2" @if($rating == 2) selected @endif>Bad (2)</option>
						<option value="1" @if($rating == 1) selected @endif>Terrible (1)</option>
					</select>
				</div>
				<div class="form-group font-weight-bold">
					<label for=" select-location font-weight-bold">Status</label>
					<select class="form-control filter-status-input" name="status">
						<option value="0" @if($status === "0") selected @endif>All statuses</option>
						<option value="pending" @if($status === "pending") selected @endif>Waiting</option>
						<option value="publish" @if($status === "publish") selected @endif>Published</option>
						<option value="unpublish" @if($status === "unpublish") selected @endif>Unpublished</option>
					</select>
				</div>
			</div>
			<div class="button-action d-flex justify-content-between px-5">
				<button class="btn btn-white w-100 mr-4 clear-form">
					Clear
				</button>
				<button type="submit" class="btn btn-primary w-100">
					Apply
				</button>
			</div>
		</div>
	</form>
</div>
<!-- End Filter Sidebar -->
@endsection
{{-- Scripts Section --}}
@section('scripts')
	<!--begin::Page Vendors(used by this page)-->
	<script src="{{ asset('plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
	<!--end::Page Vendors-->
	<!--begin::Page Scripts(used by this page)-->
	<script src="{{ asset('js/pages/crud/forms/widgets/bootstrap-datepicker.js') }}"></script>
	<script src="{{ asset('js/pages/widgets.js') }}"></script>
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

			$('.filter-location-span').text($('.filter-location-input option:selected').text());
			$('.filter-rating-span').text($('.filter-rating-input option:selected').text());
			$('.filter-status-span').text($('.filter-status-input option:selected').text());
			$(document).on('click','.filter-value, .clear-form', function(){

				if($(this).hasClass('filter-location')) {
					$('.filter-location-input').val('0');
				} else if($(this).hasClass('filter-rating')) {
					$('.filter-rating-input').val('0');
				} else if($(this).hasClass('filter-status')) {
					$('.filter-status-input').val('0');
				} else if($(this).hasClass('clear-form')) {
					$('.filter-location-input').val('0');
					$('.filter-rating-input').val('0');
					$('.filter-status-input').val('0');
				}
				$('.filter-form').submit();
			});

			window.status_changed = true;
			window.old_status = $('.update-status').val();
			$(document).on('change', '.update-status', function(){
				var self = $(this);
				var self_this = this;

				var id = self.attr('data-id');
				var status = self.val();


				if(window.status_changed) {
					$.ajax({
						url: '{{ route("updateReviewStatus") }}',
						type: 'POST',
						dataType: 'JSON',
						data: {
							"_token": "{{ csrf_token() }}",
							id: id,
							status: status
						},
						success: function(response) {
							if(response.status) {
								responseMessages('success',response.message);
		                        window.status_changed = true;
		                        window.old_status = status;
							} else {
								responseMessages('error',response.message);
	                            window.status_changed = false;
	                            self.val(window.old_status);
	                            self.change();
							}
						},
						error: function(response) {

							responseMessages('error', 'Something went wrong.');
                            window.status_changed = false;
                            self.val(window.old_status);
                            self.change();
						},
						complete: function(response) {
							
						}
					});
				} else {
	                window.status_changed = true;
				}

			});
		});
	</script>
@endsection