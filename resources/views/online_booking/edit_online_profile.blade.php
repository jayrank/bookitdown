@extends('layouts.fullview')
@section('innercss')
@endsection
@section('content')
{{ Form::open(array('url' => 'partners/online_booking/saveOnlineProfile','id' => 'saveProfileForm','enctype'=>"multipart/form-data")) }}
@php $storetimetableid = "" @endphp
@php 
	$storetimetableid = "";
	$is_open_sunday = "";
	$sunday_open_time = "";
	$sunday_close_time = "";
	$is_open_monday = "";
	$monday_open_time = "";
	$monday_close_time = "";
	$is_open_tuesday = "";
	$tuesday_open_time = "";
	$tuesday_close_time = "";
	$is_open_wednesday = "";
	$wednesday_open_time = "";
	$wednesday_close_time = "";
	$is_open_thursday = "";
	$thursday_open_time = "";
	$thursday_close_time = "";
	$is_open_friday = "";
	$friday_open_time = "";
	$friday_close_time = "";
	$is_open_saturday = "";
	$saturday_open_time = "";
	$saturday_close_time = "";;
@endphp
@if(!empty($storeTimetable))
	@php 
	$storetimetableid = $storeTimetable->id; 
	$is_open_sunday = $storeTimetable->is_open_sunday; 
	$sunday_open_time = $storeTimetable->sunday_open_time; 
	$sunday_close_time = $storeTimetable->sunday_close_time; 
	$is_open_monday = $storeTimetable->is_open_monday; 
	$monday_open_time = $storeTimetable->monday_open_time; 
	$monday_close_time = $storeTimetable->monday_close_time; 
	$is_open_tuesday = $storeTimetable->is_open_tuesday; 
	$tuesday_open_time = $storeTimetable->tuesday_open_time; 
	$tuesday_close_time = $storeTimetable->tuesday_close_time; 
	$is_open_wednesday = $storeTimetable->is_open_wednesday; 
	$wednesday_open_time = $storeTimetable->wednesday_open_time; 
	$wednesday_close_time = $storeTimetable->wednesday_close_time; 
	$is_open_thursday = $storeTimetable->is_open_thursday; 
	$thursday_open_time = $storeTimetable->thursday_open_time; 
	$thursday_close_time = $storeTimetable->thursday_close_time; 
	$is_open_friday = $storeTimetable->is_open_friday; 
	$friday_open_time = $storeTimetable->friday_open_time; 
	$friday_close_time = $storeTimetable->friday_close_time; 
	$is_open_saturday = $storeTimetable->is_open_saturday; 
	$saturday_open_time = $storeTimetable->saturday_open_time; 
	$saturday_close_time = $storeTimetable->saturday_close_time;
	@endphp
@else
	@php $storetimetableid = ""; @endphp
@endif
<input type="hidden" name="location_id" value="{{ $locationData->id }}">
<input type="hidden" name="storetimetableid" value="{{ $storetimetableid }}">
<input type="hidden" name="makeLocationOnline" value="{{ $makeLocationOnline }}">
<div class="container-fluid p-0">
	<div class="my-custom-body-wrapper">
		<div class="my-custom-header bg-white">
			<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
				<span class="d-flex align-items-center">
					<p class="cursor-pointer m-0 px-6" onclick="history.back();"><i
							class="text-dark fa fa-times icon-lg"></i>
					</p>
					<p class="cursor-pointer mb-0 text-blue previous" onclick="nextPrev(-1)"><i
							class="border-left mx-4"></i>Previous</p>
				</span>
				<h1 class="font-weight-bolder">Profile Details</h1>
				<button type="button" class="btn btn-primary next-step hideBtn" onclick="nextPrev(1)">Next Step</button>
				<button type="submit" class="btn btn-primary showBtn save-btn" style="display: none;">Save</button>
			</div>
		</div>
		<div class="my-custom-body">
			<div class="container-fluid bg-content">
				<div class="row">
					<div class="col-12 col-md-4 p-0 bg-content" style="height:calc(100vh - 80px);overflow-y:scroll">
						<div class="p-4">
							<h2 class="font-weight-bolder text-center my-6">List Your Online Profile</h2>
							<div class="edit-pro-img">
								<img alt="img" src="{{ asset('assets/images/edit-profile.png') }}" id="editProImgOne"
									width="100%">
								<img alt="img" src="{{ asset('assets/images/edit-profile-2.png') }}" id="editProImgTwo" width="100%">

							</div>
							<h4 class="my-4 font-weight-bolder text-center">Step <span class="steps">1</span> of 3:
								Profile Details
							</h4>
							<p class="text-justify font-weight-bolder pro-desc">Add key details about your business
								to
								display on your online booking profile.
								Explain in simple terms what makes your
								business great, and the overall types of services offered.</p>
						</div>
					</div>
					<div class="col-12 col-md-8 p-0 bg-white" style="height:calc(100vh - 80px);overflow-y:scroll">
						<div class="p-10">
							<div class="add-voucher-tab">
								<div>
									<div class="form-group">
										<h6 class="font-weight-bolder">Business Name</h6>
										<input type="text" disabled value="{{ $bussinessData->name }}" class="form-control" placeholder="">
									</div>
									<div class="form-group">
										<h6 class="font-weight-bolder">Location Name</h6>
										<input type="text" value="{{ $locationData->location_name }}" class="form-control" name="location_name" id="location_name" placeholder="Location">
									</div>
									<div class="form-group">
										<h6 class="font-weight-bolder" for="telephone">Business Phone</h6>
										<input type="tel" class="form-control allow_only_numbers" placeholder="" id="telephone" value="{{ $locationData->location_phone  }}">
									</div>
									<div class="form-group">
										<h6 class="font-weight-bolder">Description</h6>
										<textarea class="form-control" name="location_description" placeholder="Description" rows="8">{{ $locationData->location_description }}</textarea>
									</div>
									<div class="form-group">
										<label class="font-weight-bolder" for="location_address">Where’s your business located?</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text bg-transparent"><i class="fa fa-map-marker"></i></span>
											</div>
											<input type="text" class="form-control" name="location_address" id="location_address" value="{{ $locationData->location_address }}">
											<p id="location_error"></p>
											<input type="hidden" name="lat" id="lat" value="{{ $locationData->location_latitude }}">
											<input type="hidden" name="lng" id="lng" value="{{ $locationData->location_longitude }}">
										</div>
									</div>
									<div class="card" @if($locationData->no_business_address == 1) style="display: none;" 	@endif>
										<div class="card-body p-4 bg-content">
											<h5 class="position-absolute" style="right:20px;z-index: 1;">
												<a data-toggle="modal" data-target="#editBusinessLocation"
													class="text-blue cursor-pointer">Edit</a>
											</h5>
											<div class="row">
												<div class="col-md-4">
													<div class="my-6">
														<h4>Address</h4>
														@if($locationData->loc_address != "")
															<p id="loc_address">{{ $locationData->loc_address }}</p>
														@else 
															<p id="loc_address"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
														@endif	
														<input type="hidden" name="loc_address" class="loc_address" value="{{ $locationData->loc_address }}">
													</div>
												</div>	
												<div class="col-md-4">
													<div class="my-6">
														<h4>Apt./Suite etc</h4>
														@if($locationData->loc_apt != "")
															<p id="loc_apt">{{ $locationData->loc_apt }}</p>
														@else 
															<p id="loc_apt"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
														@endif
														<input type="hidden" name="loc_apt" class="loc_apt" value="{{ $locationData->loc_apt }}">
													</div>
												</div>	
												<div class="col-md-4">
													<div class="my-6">
														<h4>District</h4>
														@if($locationData->loc_district != "")
															<p id="loc_district">{{ $locationData->loc_district }}</p>
														@else 
															<p id="loc_district"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
														@endif
														<input type="hidden" name="loc_district" class="loc_district" value="{{ $locationData->loc_district }}">
													</div>
												</div>	
												<div class="col-md-4">
													<div class="my-6">
														<h4>City</h4>
														@if($locationData->loc_city != "")
															<p id="loc_city">{{ $locationData->loc_city }}</p>
														@else 
															<p id="loc_city"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
														@endif
														
														<input type="hidden" name="loc_city" class="loc_city" value="{{ $locationData->loc_district }}">
													</div>
												</div>	
												<div class="col-md-4">
													<div class="my-6">
														<h4>Region</h4>
														@if($locationData->loc_region != "" && $locationData->loc_county != "")
															<p><span id="loc_region">{{ $locationData->loc_region }}</span><span id="loc_county">{{ $locationData->loc_county }}</span></p>
														@else 
															<p><span id="loc_region"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></span><span id="loc_county"></span></p>
														@endif	
														<input type="hidden" name="loc_region" class="loc_region" value="{{ $locationData->loc_region }}">
														<input type="hidden" name="loc_county" class="loc_county" value="{{ $locationData->loc_county }}">
													</div>
												</div>	
												<div class="col-md-4">
													<div class="my-6">
														<h4>Postcode</h4>
														@if($locationData->loc_postcode != "")
															<p id="loc_postcode">{{ $locationData->loc_postcode }}</p>
														@else 
															<p id="loc_postcode"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
														@endif
														<input type="hidden" name="loc_postcode" class="loc_postcode" value="{{ $locationData->loc_postcode }}">
													</div>
												</div>	
												<div class="col-md-4">
													<div class="my-6">
														<h4>Country</h4>
														@if($locationData->loc_country != "")
															<p id="loc_country">{{ $locationData->loc_country }}</p>
														@else 
															<p id="loc_country"><a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a></p>
														@endif
														<input type="hidden" name="loc_country" class="loc_country" value="{{ $locationData->loc_country }}">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="my-8">
										<div class="map" id="map" style="width: 100%; height: 300px;"></div>
									</div>
									<h6 class="font-weight-bolder">Billing details for clients invoice</h6>
									<div class="card">
										<div class="card-body p-4 bg-content">
											<h5 class="position-absolute" style="right:20px;z-index: 1;">
												<a class="text-blue cursor-pointer" data-toggle="modal"
													data-target="#editBillingDetail">Edit</a>
											</h5>
											<div class="row">
												<div class="col-12 col-md-4">
													<div class="my-6">
														<h4>Company name</h4>
														<h6>{{ $locationData->billing_company_name }}</h6>
													</div>
													<div class="my-6">
														<h4>Address</h4>
														<h6>{{ $locationData->location_address }}</h6>
														@php
															$buss_addr = "";
															if($locationData->billing_address != "") {
																$buss_addr .= $locationData->billing_address.", ";
															}
															if($locationData->billing_city != "") {
																$buss_addr .= $locationData->billing_city.", ";
															}
															if($locationData->billing_postcode != "") {
																$buss_addr .= $locationData->billing_postcode.", ";
															}
															if($locationData->billing_region != "") {
																$buss_addr .= $locationData->billing_region;
															}
														@endphp
														<p id="billing_address">{{ $buss_addr }}</p>
														<input type="hidden" class="isSameBillingAddr" name="is_same_billing_addr" value="{{ $locationData->is_same_billing_addr }}">
														<input type="hidden" class="billing_address" name="billing_address" value="{{ $locationData->billing_address }}">
														<input type="hidden" class="billing_apt" name="billing_apt" value="{{ $locationData->billing_apt }}">
														<input type="hidden" class="billing_city" name="billing_city" value="{{ $locationData->billing_city }}">
														<input type="hidden" class="billing_region" name="billing_region" value="{{ $locationData->billing_region }}">
														<input type="hidden" class="billing_postcode" name="billing_postcode" value="{{ $locationData->billing_postcode }}">
														<input type="hidden" class="billing_company_name" name="billing_company_name" value="{{ $locationData->billing_company_name }}">
													</div>
													<div class="my-6">
														<h4>Notes</h4>
														@if($locationData->billing_notes != "")
															<p id="billing_notes">{{ $locationData->billing_notes }}</p>
														@else 
															<p id="billing_notes"><a data-toggle="modal" data-target="#editBillingDetail" class="text-blue cursor-pointer">+ Add</a></p>
														@endif
														<input type="hidden" class="billing_notes" name="billing_notes" value="{{ $locationData->billing_notes }}">
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="add-voucher-tab">
								<div>
									<h2 class="text-center font-weight-bolder">Availability</h2>
									<div class="d-flex justify-content-between w-100">
										<div class="form-group">
											<div class="checkbox-list mt-8">
												<label class="checkbox font-weight-bolder">
													<input onclick="disable()" id="sundayCheck"
														type="checkbox" name="is_open_sunday" value="1" {{ ($is_open_sunday == 1) ? 'checked' : '' }}>
													<span></span> Sunday
												</label>
											</div>
										</div>
										<div class="d-flex justify-content-between form-group w-70">
											<div class="fomr-group w-100 mr-4">
												<h6 class="font-weight-bolder">Open</h6>
												<select class="form-control openTiming" data-day="Sunday" id="workinghourStartSunday" name="sunday_open_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($sunday_open_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
											<div class="fomr-group w-100">
												<h6 class="font-weight-bolder">Close</h6>
												<select class="form-control" id="workinghourEndSunday" name="sunday_close_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($sunday_close_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="d-flex justify-content-between w-100">
										<div class="form-group">
											<div class="checkbox-list">
												<label class="checkbox font-weight-bolder">
													<input onclick="disable()" id="mondayCheck"
														type="checkbox" name="is_open_monday" value="1" {{ ($is_open_monday == 1) ? 'checked' : '' }}>
													<span></span> Monday
												</label>
											</div>
										</div>
										<div class="d-flex justify-content-between form-group w-70">
											<div class="fomr-group w-100 mr-4">
												<select class="form-control openTiming" data-day="Monday" id="workinghourStartMonday" name="monday_open_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($monday_open_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
											<div class="fomr-group w-100">
												<select class="form-control" id="workinghourEndMonday" name="monday_close_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($monday_close_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="d-flex justify-content-between w-100">
										<div class="form-group">
											<div class="checkbox-list">
												<label class="checkbox font-weight-bolder">
													<input onclick="disable()" id="tuesdayCheck"
														type="checkbox" name="is_open_tuesday" value="1" {{ ($is_open_tuesday == 1) ? 'checked' : '' }}>
													<span></span> Tuesday
												</label>
											</div>
										</div>
										<div class="d-flex justify-content-between form-group w-70">
											<div class="fomr-group w-100 mr-4">
												<select class="form-control openTiming" data-day="Tuesday" id="workinghourStartTuesday" name="tuesday_open_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($tuesday_open_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
											<div class="fomr-group w-100">
												<select class="form-control" id="workinghourEndTuesday" name="tuesday_close_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($tuesday_close_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="d-flex justify-content-between w-100">
										<div class="form-group">
											<div class="checkbox-list">
												<label class="checkbox font-weight-bolder">
													<input onclick="disable()" id="wednesdayCheck" type="checkbox" name="is_open_wednesday" value="1" {{ ($is_open_wednesday == 1) ? 'checked' : '' }}>
													<span></span> Wednesday
												</label>
											</div>
										</div>
										<div class="d-flex justify-content-between form-group w-70">
											<div class="fomr-group w-100 mr-4">
												<select class="form-control openTiming" data-day="Wed" id="workinghourStartWed" name="wednesday_open_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($wednesday_open_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
											<div class="fomr-group w-100">
												<select class="form-control openTiming" data-day="Wed" id="workinghourEndWed" name="wednesday_close_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($wednesday_close_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="d-flex justify-content-between w-100">
										<div class="form-group">
											<div class="checkbox-list">
												<label class="checkbox font-weight-bolder">
													<input onclick="disable()" id="thursdayCheck"
														type="checkbox" name="is_open_thursday" value="1" {{ ($is_open_thursday == 1) ? 'checked' : '' }}>
													<span></span> Thursday
												</label>
											</div>
										</div>
										<div class="d-flex justify-content-between form-group w-70">
											<div class="fomr-group w-100 mr-4">
												<select class="form-control openTiming" data-day="Thursday" id="workinghourStartThursday" name="thursday_open_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($thursday_open_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
											<div class="fomr-group w-100">
												<select class="form-control openTiming" data-day="Thursday" id="workinghourEndThursday" name="thursday_close_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($thursday_close_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="d-flex justify-content-between w-100">
										<div class="form-group">
											<div class="checkbox-list">
												<label class="checkbox font-weight-bolder">
													<input onclick="disable()" id="fridayCheck" type="checkbox" name="is_open_friday" value="1" {{ ($is_open_friday == 1) ? 'checked' : '' }}>
													<span></span> Friday
												</label>
											</div>
										</div>
										<div class="d-flex justify-content-between form-group w-70">
											<div class="fomr-group w-100 mr-4">
												<select class="form-control openTiming" data-day="Friday" id="workinghourStartFriday" name="friday_open_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($friday_open_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
											<div class="fomr-group w-100">
												<select class="form-control openTiming" data-day="Friday" id="workinghourEndFriday" name="friday_close_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($friday_close_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="d-flex justify-content-between w-100">
										<div class="form-group">
											<div class="checkbox-list">
												<label class="checkbox font-weight-bolder">
													<input onclick="disable()" id="saturdayCheck"
														type="checkbox" value="1" name="is_open_saturday" {{ ($is_open_saturday == 1) ? 'checked' : '' }}>
													<span></span> Saturday
												</label>
											</div>
										</div>
										<div class="d-flex justify-content-between form-group w-70">
											<div class="fomr-group w-100 mr-4">
												<select class="form-control openTiming" data-day="Saturday" id="workinghourStartSaturday" name="saturday_open_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($saturday_open_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
											<div class="fomr-group w-100">
												<select class="form-control openTiming" data-day="Saturday" id="workinghourEndSaturday" name="saturday_close_time">
													@foreach($times as $key => $time)
														<option value="{{ $time->time }}" {{ ($saturday_close_time == $time->time) ? 'selected="selected"' : '' }}>{{ $time->time }}</option>
													@endforeach
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<h6 class="font-weight-bolder" for="availablefor">Services available for
										</h6>
										<select class="form-control" id="availablefor" name="available_for">
											<option value="0"
												@if(!empty($locationData))
													@if($locationData->available_for === 0)
														selected
													@endif
												@endif
											>Everyone</option>
											<option value="1"
												@if(!empty($locationData))
													@if($locationData->available_for == 1)
														selected
													@endif
												@endif
											>Females only</option>
											<option value="2"
												@if(!empty($locationData))
													@if($locationData->available_for == 2)
														selected
													@endif
												@endif
											>Males only</option>
										</select>
									</div>
								</div>
							</div>
							<div class="add-voucher-tab">
								<div>
									@php 
										$bgImage = asset('assets/media/users/blank.png'); 
										$bgImageId = "";
										$image_path = "";
									@endphp

									@if(!empty($locationData->location_image))
										@php
										$bgImage = url($locationData->location_image);
										$image_path = $locationData->location_image;
										@endphp
									@endif
									<h2 class="text-center font-weight-bolder">Upload Photo</h2>
									<div class="w-100 image-input image-input-outline" id="kt_image_5" style="background-image: url({{ $bgImage }});height:400px">
										<div class="image-input-wrapper w-100" style="background-image: none;height:400px"></div>
										<label class="btn btn-lg btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow" data-action="change" data-toggle="tooltip" title="" data-original-title="Change avatar">
											<i class="fa fa-pen icon-xl text-muted"></i>
											<input type="file" name="profile_avatar" accept=".png, .jpg, .jpeg">
											<input type="hidden" name="profile_avatar_remove" value="0">
										</label>
										<span class="btn btn-lg btn-icon btn-circle btn-white btn-hover-text-primary btn-shadow remove-cover-image" data-action="remove" data-toggle="tooltip" title="" data-original-title="Remove avatar">
											<i class="flaticon-delete icon-xl text-danger"></i>
										</span>
									</div>
								</div>
								<div class="row">
									<div class="d-flex flex-wrap">
										<div id="filesDiv" style="position: relative;">
										</div>
										@if(!empty($locationImages))
											@foreach($locationImages as $imgKey => $image)
												{{--@if($imgKey >= 1)--}}
													<span class="p-4 pip imageSpan_{{ $image['id'] }}" style="position:relative">
														<img height="150px" width="200px" class="imageThumb" src="{{ url($image['image_path'] ) }}"><br>
														<span class="remove btn btn-white btn-sm p-2 removeImage" data-image-id="{{ $image['id'] }}" data-image-path="{{ $image['image_path'] }}" style="position:absolute;top:20px;left:20px"><i class="p-0 fa fa-trash text-danger"></i>
														</span>
													</span>
												{{--@endif--}}
											@endforeach
										@endif
										<label class="" style="padding-top:10px;height: 160px;width: 200px;">
											<input type="file" id="files" name="files[]" multiple class="d-none" />
											<div class="h-100 card mx-auto">
												<div class="h-100 py-6 rounded cursor-pointer"
													style="background-color: #e5f1ff;border: 1px solid #037aff !important;">
													<div class="mx-auto my-4" style="width: 45px;height:45px">
														<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 51 47">
															<path
																d="M48 21.147a1 1 0 0 0 2 0V3.528C50 2.005 48.776.766 47.26.766H2.74C1.225.766 0 2.006 0 3.528v34.476c0 1.523 1.224 2.762 2.74 2.762h20.89a1 1 0 0 0 0-2H2.74a.751.751 0 0 1-.74-.762V3.528c0-.424.334-.762.74-.762h44.52c.406 0 .74.338.74.762v17.62z">
															</path>
															<path
																d="M44 21.499a1 1 0 0 0 2 0V5.617a1 1 0 0 0-1-1H5a1 1 0 0 0-1 1v30a1 1 0 0 0 1 1h19.13a1 1 0 0 0 0-2H6v-28h38v14.882z">
															</path>
															<path
																d="M19.34 14.63a4.575 4.575 0 1 0-9.149 0 4.575 4.575 0 0 0 9.15 0zm-2 0a2.575 2.575 0 1 1-5.149 0 2.575 2.575 0 0 1 5.15 0zM32.606 17.98l2.225 3.944a1 1 0 1 0 1.742-.982l-2.985-5.292a1 1 0 0 0-1.677-.101l-9.673 13.164-6.09-6.216a1 1 0 0 0-1.48.057L4.298 34.902a1 1 0 0 0 .766 1.643h19.012a1 1 0 1 0 0-2H7.21l8.281-9.86 6.142 6.268a1 1 0 0 0 1.52-.108l9.453-12.865z">
															</path>
															<path
																d="M51 35.25c0-6.192-5.02-11.212-11.213-11.212-6.192 0-11.213 5.02-11.213 11.212 0 6.193 5.02 11.213 11.213 11.213S51 41.443 51 35.25zm-2 0a9.213 9.213 0 1 1-18.426 0 9.213 9.213 0 0 1 18.426 0z">
															</path>
															<path
																d="M38.838 29.87v10.213c0 1.333 2 1.333 2 0V29.87c0-1.333-2-1.333-2 0z">
															</path>
															<path
																d="M34.68 35.76h10.214c1.333 0 1.333-2 0-2H34.68c-1.333 0-1.333 2 0 2z">
															</path>
														</svg>
													</div>
													<h6 class="text-center font-weight-bolder">Upload photo
													</h6>
												</div>
											</div>
										</label>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="modal" id="editBusinessLocation" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="editBillingDetailLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h3 class="modal-title font-weight-bolder" id="editBillingDetailLabel">Edit business location</h3>
						<p class="cursor-pointer m-0" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i></p>
					</div>
					<div class="modal-body">
						<div>
							<div class="d-flex">
								<div class="form-group  mr-2 w-100">
									<label>Address</label>
									<input type="text" class="form-control loc_address" autocomplete="off" placeholder="" value="{{ $locationData->loc_address }}">
								</div>
								<div class="form-group  mr-2 w-50">
									<label>Apt./Suite etc</label>
									<input type="text" class="form-control loc_apt" autocomplete="off" placeholder="" value="{{ $locationData->loc_apt }}">
								</div>
							</div>
							<div class="d-flex">
								<div class="form-group  mr-2 w-100">
									<label>District</label>
									<input type="text" class="form-control loc_district" autocomplete="off" placeholder="" value="{{ $locationData->loc_district }}">
								</div>
								<div class="form-group  mr-2 w-50">
									<label>City</label>
									<input type="text" class="form-control loc_city" autocomplete="off" placeholder="" value="{{ $locationData->loc_city }}">
								</div>
							</div>
							<div class="d-flex">
								<div class="form-group  mr-2 w-100">
									<label>County</label>
									<input type="text" class="form-control loc_county" autocomplete="off" placeholder="" value="{{ $locationData->loc_county }}">
								</div>
								<div class="form-group  mr-2 w-50">
									<label>State</label>
									<input type="text" class="form-control loc_region" autocomplete="off" placeholder="" value="{{ $locationData->loc_region }}">
								</div>
							</div>
							<div class="d-flex">
								<div class="form-group  mr-2 w-100">
									<label>Postcode</label>
									<input type="text" class="form-control loc_postcode allow_only_numbers" autocomplete="off" placeholder="" value="{{ $locationData->loc_postcode }}">
								</div>
								<div class="form-group  ml-2 w-100">
									<label>Country</label>
									<input type="text" class="form-control loc_country" autocomplete="off" placeholder="" value="{{ $locationData->loc_country }}">
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
						<button type="button" class="btn btn-danger updateLocationAddr" data-dismiss="modal">Save</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
{{ Form::close() }}

<!-- Modal -->
<!-- End Modal -->
<!-- Modal -->
<div class="modal" id="editBillingDetail" tabindex="-1" role="dialog" data-keyboard="false" data-backdrop="static" aria-labelledby="editBillingDetailLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h3 class="modal-title font-weight-bolder" id="editBillingDetailLabel">Edit billing details
				</h3>
				<p class="cursor-pointer m-0 closeBillingMdl" data-dismiss="modal" aria-label="Close"><i class="text-dark fa fa-times icon-lg"></i>
				</p>
			</div>
			
			<form action="#" method="POST" id="updateBillingAddress">
				<div class="modal-body">
					<div>
						<div class="form-group">
							<div class="checkbox-list mt-8">
								<label class="checkbox font-weight-bolder">
									<input id="isSameLocationAddr" name="isSameLocationAddr" type="checkbox" @if (0 == $locationData->is_same_billing_addr) checked @endif>
									<span></span> Use location name and address for client invoices
								</label>
							</div>
						</div>
						<div class="form-group">
							<label>Company name </label>
							<input type="text" class="form-control bill_inpt billing_company_name" name="billing_company_name" autocomplete="off" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_company_name }}">
						</div>
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>Address</label>
								<input type="text" class="form-control bill_inpt billing_address" autocomplete="off" name="billing_address" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_address }}">
							</div>
							<div class="form-group  mr-2 w-50">
								<label>Apt./Suite etc</label>
								<input type="text" class="form-control bill_inpt billing_apt" autocomplete="off" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_apt }}">
							</div>
						</div>
						<div class="form-group">
							<label>City </label>
							<input type="text" class="form-control bill_inpt billing_city" autocomplete="off" name="billing_city" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_city }}">
						</div>
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>State</label>
								<input type="text" class="form-control bill_inpt billing_region" autocomplete="off" name="billing_region" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_region }}">
							</div>
							<div class="form-group  ml-2 w-100">
								<label>Postcode</label>
								<input type="text" class="form-control bill_inpt billing_postcode allow_only_numbers" autocomplete="off" name="billing_postcode" @if ($locationData->is_same_billing_addr == 0 ) disabled @endif value="{{ $locationData->billing_postcode }}">
							</div>
						</div>
						<div class="form-group">
							<label>Invoice note</label>
							<textarea class="form-control billing_notes" rows="5" placeholder="VAT number or other info">{{ $locationData->billing_notes }}</textarea>
						</div>
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="btn btn-danger updateBillingAddr" data-dismiss="modal" aria-label="Close">Save</button>
				</div>
			</form>	
		</div>
	</div>
</div>
<!-- End Modal -->

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
<!--end::Scrolltop-->
<script>var HOST_URL = "https://preview.keenthemes.com/metronic/theme/html/tools/preview";</script>
<!--begin::Global Config(global config for global JS scripts)-->
<script>var KTAppSettings = { "breakpoints": { "sm": 576, "md": 768, "lg": 992, "xl": 1200, "xxl": 1400 }, "colors": { "theme": { "base": { "white": "#ffffff", "primary": "#3699FF", "secondary": "#E5EAEE", "success": "#1BC5BD", "info": "#8950FC", "warning": "#FFA800", "danger": "#F64E60", "light": "#E4E6EF", "dark": "#181C32" }, "light": { "white": "#ffffff", "primary": "#E1F0FF", "secondary": "#EBEDF3", "success": "#C9F7F5", "info": "#EEE5FF", "warning": "#FFF4DE", "danger": "#FFE2E5", "light": "#F3F6F9", "dark": "#D6D6E0" }, "inverse": { "white": "#ffffff", "primary": "#ffffff", "secondary": "#3F4254", "success": "#ffffff", "info": "#ffffff", "warning": "#ffffff", "danger": "#ffffff", "light": "#464E5F", "dark": "#ffffff" } }, "gray": { "gray-100": "#F3F6F9", "gray-200": "#EBEDF3", "gray-300": "#E4E6EF", "gray-400": "#D1D3E0", "gray-500": "#B5B5C3", "gray-600": "#7E8299", "gray-700": "#5E6278", "gray-800": "#3F4254", "gray-900": "#181C32" } }, "font-family": "Poppins" };</script>
<!--end::Global Config-->
<!--begin::Global Theme Bundle(used by all pages)-->
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/prismjs/prismjs.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
<script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
<!--end::Page Scripts-->
<script src="{{ asset('assets/js/image-picker.js') }}"></script>

<script src="{{ asset('assets/js/intlTelInput.js')}} "></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCVdjXD_FnLKsQTNU8ki6Np_YlfwU0Oyec&v=3.exp&libraries=places"></script>
<!-- For Internationa Number script -->
<script>
	function initialize() 
	{
		var latlng = new google.maps.LatLng({{ ($locationData->location_latitude) ? $locationData->location_latitude : 28.5355161 }},{{ ($locationData->location_longitude) ? $locationData->location_longitude : 77.39102649999995 }});
		var map = new google.maps.Map(document.getElementById('map'), {
			center: latlng,
			zoom: 13
		});
		
		var marker = new google.maps.Marker({
			map: map,
			position: latlng,
			draggable: true,
			anchorPoint: new google.maps.Point(0, -29)
		});
		
		var input = document.getElementById('location_address');
		//map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);
		var geocoder = new google.maps.Geocoder();
		var autocomplete = new google.maps.places.Autocomplete(input);
		autocomplete.bindTo('bounds', map);
		//var infowindow = new google.maps.InfoWindow();   
		autocomplete.addListener('place_changed', function() {
			//infowindow.close();
			marker.setVisible(false);
			var place = autocomplete.getPlace();
			if (!place.geometry) {
				window.alert("Autocomplete's returned place contains no geometry");
				return;
			}
	  
			// If the place has a geometry, then present it on a map.
			if (place.geometry.viewport) {
				map.fitBounds(place.geometry.viewport);
			} else {
				map.setCenter(place.geometry.location);
				map.setZoom(17);
			}
		   
			marker.setPosition(place.geometry.location);
			marker.setVisible(true);          
			$(".map_section").show();
		
			var premise = addres = district = postal_code = city = region = county = postal_code = country = "";
			var place = autocomplete.getPlace();
			for (var i = 0; i < place.address_components.length; i++) 
			{
				for (var j = 0; j < place.address_components[i].types.length; j++) 
				{
					if (place.address_components[i].types[j] == "premise") 
					{
						premise = place.address_components[i].long_name+", ";
					}
					if (place.address_components[i].types[j] == "street_number") 
					{
						addres += place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "route") 
					{
						addres += " "+place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "neighborhood") 
					{
						district = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "sublocality_level_1") 
					{
						district = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "locality") 
					{
						city = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "administrative_area_level_1") 
					{
						region = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "administrative_area_level_2") 
					{
						county = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "postal_code") 
					{
						postal_code = place.address_components[i].long_name;
					}
					if (place.address_components[i].types[j] == "country") 
					{
						country = place.address_components[i].long_name;
					}
				}
			}
			
			$(".address_detail_setion").show();
			
			if(addres == "") {
				var lbladd = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lbladd = premise+""+addres;
			}		
			
			if(district == "") {
				var lbldist = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lbldist = district;
			}		
			
			if(postal_code == "") {
				var lblpost = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
			} else {
				var lblpost = postal_code;
			}		
			
			$("#loc_apt").html('<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>');
			$("#loc_address").html(lbladd);
			$("#loc_district").html(lbldist);
			$("#loc_city").html(city);
			$("#loc_region").html(region+", ");
			$("#loc_county").html(county);
			$("#loc_postcode").html(lblpost);
			$("#loc_country").html(country);
			
			$('.loc_address').val(premise+""+addres);
			$('.loc_district').val(district);
			$('.loc_city').val(city);
			$('.loc_region').val(region);
			$('.loc_county').val(county);
			$('.loc_postcode').val(postal_code);
			$('.loc_country').val(country);
			
			if($("#isSameLocationAddr").is(':checked'))
			{
				var bill_addr = "";
				
				if(addres != "") {
					bill_addr += premise+""+addres+", ";
				}		
				
				if(city != "") {
					bill_addr += city+", ";
				}		
				
				if(postal_code != "") {
					bill_addr += postal_code+", ";
				}
				
				if(region != "") {
					bill_addr += region;
				}
				
				$("#billing_company_name").html($("#location_name").val());
				$(".billing_company_name").val($("#location_name").val());
				
				$('#billing_address').html(bill_addr);
				$('.billing_address').val(premise+""+addres);
				$('.billing_city').val(city);
				$('.billing_region').val(region);
				$('.billing_postcode').val(postal_code);
			}	
			
			var address = "";
			var lat = place.geometry.location.lat();
			var lng = place.geometry.location.lng();
			
			if(addres != "") {
				address += premise+""+addres+", ";
			} 
			if(city != "") {
				address += city+", ";
			} 
			if(district != "") {
				address += " ("+district+")"+", ";
			} 
			if(region != "") {
				address += region;
			}		
			
			document.getElementById('location_address').value = address;
			document.getElementById('lat').value = lat;
			document.getElementById('lng').value = lng;
		});
		
		// this function will work on marker move event into map 
		google.maps.event.addListener(marker, 'dragend', function() {
			geocoder.geocode({'latLng': marker.getPosition()}, function(results, status) {
				if (status == google.maps.GeocoderStatus.OK) {
					if (results[0]) {        
						bindDataToForm(results[0].formatted_address,marker.getPosition().lat(),marker.getPosition().lng());
					}
				}
			});
		});
	}
	google.maps.event.addDomListener(window, 'load', initialize);

	$(".updateLocationAddr").click( function(){
		var addr = $("#editBusinessLocation .loc_address").val();
		var loc_apt = $("#editBusinessLocation .loc_apt").val();	
		var loc_district = $("#editBusinessLocation .loc_district").val();	
		var loc_city = $("#editBusinessLocation .loc_city").val();	
		var loc_county = $("#editBusinessLocation .loc_county").val();	
		var loc_region = $("#editBusinessLocation .loc_region").val();	
		var loc_postcode = $("#editBusinessLocation .loc_postcode").val();	
		var loc_country = $("#editBusinessLocation .loc_country").val();	
		
		var address = "";
		
		if(addr != "") {
			address += addr+", ";
		} 
		if(loc_city != "") {
			address += loc_city;
		}
		if(loc_district != "") {
			address += " ("+loc_district+")";
		} 
		if(loc_region != "") {
			address += ", "+loc_region;
		}
		document.getElementById('location_address').value = address;
		
		if(addr == "") {
			var lbladd = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
		} else {
			var lbladd = addr;
		}		
		
		if(loc_district == "") {
			var lbldist = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
		} else {
			var lbldist = loc_district;
		}		
		
		if(loc_postcode == "") {
			var lblpost = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
		} else {
			var lblpost = loc_postcode;
		}
		
		if(loc_apt == "") {
			var lblapt = '<a class="text-blue cursor-pointer" data-toggle="modal" data-target="#editBusinessLocation">+ Add</a>';
		} else {
			var lblapt = loc_apt;
		}
		
		$("#loc_address").html(lbladd);
		$("#loc_apt").html(lblapt);
		$("#loc_district").html(lbldist);
		$("#loc_city").html(loc_city);
		$("#loc_region").html(loc_region+", ");
		$("#loc_county").html(loc_county);
		$("#loc_postcode").html(lblpost);
		$("#loc_country").html(loc_country);
		
		$('.loc_address').val(addr);
		$('.loc_apt').val(loc_apt);
		$('.loc_district').val(loc_district);
		$('.loc_city').val(loc_city);
		$('.loc_region').val(loc_region);
		$('.loc_county').val(loc_county);
		$('.loc_postcode').val(loc_postcode);
		$('.loc_country').val(loc_country);
		
		if($("#isSameLocationAddr").is(':checked'))
		{
			var bill_addr = "";
				
			if(addr != "") {
				bill_addr += addr+", ";
			}		
			
			if(loc_apt != "") {
				bill_addr += loc_apt+", ";
			}		
			
			if(loc_city != "") {
				bill_addr += loc_city+", ";
			}		
			
			if(loc_postcode != "") {
				bill_addr += loc_postcode+", ";
			}
			
			if(loc_region != "") {
				bill_addr += loc_region;
			}
			
			$("#billing_company_name").html($("#location_name").val());
			$(".billing_company_name").val($("#location_name").val());
			
			$('#billing_address').html(bill_addr);
			$('.billing_address').val(addr);
			$('.billing_apt').val(loc_apt);
			$('.billing_city').val(loc_city);
			$('.billing_region').val(loc_region);
			$('.billing_postcode').val(loc_postcode);
		}
	});	

	$("#telephone").intlTelInput({
		// whether or not to allow the dropdown
		allowDropdown: true,

		// if there is just a dial code in the input: remove it on blur, and re-add it on focus
		autoHideDialCode: true,

		// add a placeholder in the input with an example number for the selected country
		autoPlaceholder: "polite",

		// modify the auto placeholder
		customPlaceholder: null,

		// append menu to specified element
		dropdownContainer: null,

		// don't display these countries
		excludeCountries: [],

		// format the input value during initialisation and on setNumber
		formatOnDisplay: true,

		// geoIp lookup function
		geoIpLookup: null,

		// inject a hidden input with this name, and on submit, populate it with the result of getNumber
		hiddenInput: "",

		// initial country
		initialCountry: "",

		// localized country names e.g. { 'de': 'Deutschland' }
		localizedCountries: null,

		// don't insert international dial codes
		nationalMode: true,

		// display only these countries
		onlyCountries: [],

		// number type to use for placeholders
		placeholderNumberType: "MOBILE",

		// the countries at the top of the list. defaults to united states and united kingdom
		preferredCountries: ["in"],

		// display the country dial code next to the selected flag so it's not part of the typed number
		separateDialCode: false,

		// specify the path to the libphonenumber script to enable validation/formatting
		utilsScript: ""
	});
</script>

<!-- Modal Step Hide Show -->
<script>
	var currentTab = 0; // Current tab is set to be the first tab (0)
	showTab(currentTab); // Display the current tab
	
	function showTab(n) {
		// This function will display the specified tab of the form...
		var tab = document.getElementsByClassName("add-voucher-tab");
		tab[n].style.display = "block";
		if (n == (tab.length - 1)) {
			$('.hideBtn').hide();
			$('.showBtn').show();
			$(".previous").show();
			$("#editProImgOne").show();
			$("#editProImgTwo").hide();
			$(".steps").text("3");
			$(".pro-desc").text("Upload photos showing your place of business and services offered, logos and stock images are not suitable. At least one photo is required, drag and drop photos to rearrange the order and tap on photos to edit.");
		} else {
			$(".previous").hide();
			$(".next-step").text("Next Step");
		}
		if (n == 0) {
			$("#editProImgOne").show();
			$("#editProImgTwo").hide();
			$(".steps").text("1");
			$(".pro-desc").text("Add key details about your business to display on your online booking profile. Explain in simple terms what makes your business great, and the overall types of services offered.")
		} else if (n == 1) {
			$('.hideBtn').show();
			$('.showBtn').hide();
			$(".previous").show();
			$("#editProImgOne").hide();
			$("#editProImgTwo").show();
			$(".steps").text("2");
			$(".pro-desc").text("Setup standard opening hours to display on your online booking profile, the hours selected here will not impact your actual calendar availability.")
		}
	}

	function nextPrev(n) {
		var tab = document.getElementsByClassName("add-voucher-tab");
		console.log('currentTab : '+currentTab);
		if(currentTab == 0 && n != '-1')
		{
			if($('#location_name').val() == "")
			{
				validationMsg('error','location name is required.');
				return false;
			}
			if($('#location_address').val() == "" || $('#lat').val() == "" || $('#lat').val() == "lng")
			{
				validationMsg('error','Choose location properly.');
				return false;	
			}
		}
		/*else if(currentTab == 1 && n != '-1')
		{
			$('.openTiming').each(function(){
				var day = $(this).data('day');
				var thisVal = $(this).val();
				var thisClosingVal = $('#workinghourEnd'+day).val();
				console.log('thisVal : '+thisVal);
				console.log('thisClosingVal : '+thisClosingVal);
				if(thisVal == thisClosingVal)
				{
					validationMsg('error','Store opening time and closing time must be different.');
					return false;
				}
			});
		}*/
		tab[currentTab].style.display = "none";
		currentTab = currentTab + n;
		showTab(currentTab);
	}
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
<script>
	disable();
	function disable() {
		if ($('#sundayCheck').prop('checked') == true) {
			$("#workinghourStartSunday").prop('disabled', false);
			$("#workinghourEndSunday").prop('disabled', false);
		}
		else {
			$("#workinghourStartSunday").prop('disabled', true);
			$("#workinghourEndSunday").prop('disabled', true);
		}

		if ($('#mondayCheck').prop('checked') == true) {
			$("#workinghourStartMonday").prop('disabled', false);
			$("#workinghourEndMonday").prop('disabled', false);
		}
		else {
			$("#workinghourStartMonday").prop('disabled', true);
			$("#workinghourEndMonday").prop('disabled', true);
		}

		if ($('#tuesdayCheck').prop('checked') == true) {
			$("#workinghourStartTuesday").prop('disabled', false);
			$("#workinghourEndTuesday").prop('disabled', false);
		}
		else {
			$("#workinghourStartTuesday").prop('disabled', true);
			$("#workinghourEndTuesday").prop('disabled', true);
		}

		if ($('#wednesdayCheck').prop('checked') == true) {
			$("#workinghourStartWed").prop('disabled', false);
			$("#workinghourEndWed").prop('disabled', false);
		}
		else {
			$("#workinghourStartWed").prop('disabled', true);
			$("#workinghourEndWed").prop('disabled', true);
		}

		if ($('#thursdayCheck').prop('checked') == true) {
			$("#workinghourStartThursday").prop('disabled', false);
			$("#workinghourEndThursday").prop('disabled', false);
		}
		else {
			$("#workinghourStartThursday").prop('disabled', true);
			$("#workinghourEndThursday").prop('disabled', true);
		}

		if ($('#fridayCheck').prop('checked') == true) {
			$("#workinghourStartFriday").prop('disabled', false);
			$("#workinghourEndFriday").prop('disabled', false);
		}
		else {
			$("#workinghourStartFriday").prop('disabled', true);
			$("#workinghourEndFriday").prop('disabled', true);
		}

		if ($('#saturdayCheck').prop('checked') == true) {
			$("#workinghourStartSaturday").prop('disabled', false);
			$("#workinghourEndSaturday").prop('disabled', false);
		}
		else {
			$("#workinghourStartSaturday").prop('disabled', true);
			$("#workinghourEndSaturday").prop('disabled', true);
		}
	}
	var KTImageInputDemo = function () {
		var initDemos = function () {
			var avatar = new KTImageInput('kt_image_5');

			/*avatar.on('cancel', function (imageInput) {
				swal.fire({
					title: 'Image successfully changed !',
					type: 'success',
					buttonsStyling: false,
					confirmButtonText: 'Awesome!',
					confirmButtonClass: 'btn btn-primary font-weight-bold'
				});
			});

			avatar.on('change', function (imageInput) {
				swal.fire({
					title: 'Image successfully changed !',
					type: 'success',
					buttonsStyling: false,
					confirmButtonText: 'Awesome!',
					confirmButtonClass: 'btn btn-primary font-weight-bold'
				});
			});

			avatar.on('remove', function (imageInput) {
				swal.fire({
					title: 'Image successfully removed !',
					type: 'error',
					buttonsStyling: false,
					confirmButtonText: 'Got it!',
					confirmButtonClass: 'btn btn-primary font-weight-bold'
				});
			});*/
		}

		return {
			init: function () {
				initDemos();
			}
		};
	}();

	KTUtil.ready(function () {
		KTImageInputDemo.init();
	});

</script>
<script>
	$(".billingInput").prop("disabled", true)
	$("#isSameLocation").click(function () {
		if ($('#isSameLocation').prop('checked') == true) {
			$(".billingInput").prop("disabled", true)
		} else {
			$(".billingInput").prop("disabled", false)
		}
	});
</script>
<!-- File Upload Multiple -->
<script>
	$(document).ready(function () {
		if (window.File && window.FileList && window.FileReader) {
			$("#files").on("change", function (e) {
				var files = e.target.files,
					filesLength = files.length;
				for (var i = 0; i < filesLength; i++) {
					var f = files[i]
					var fileReader = new FileReader();
					fileReader.onload = (function (e) {
						var file = e.target;
						$("<span class=\"p-4 pip\" style='position:relative'>" +
							"<img height='150px' width='200px' class=\"imageThumb\" src=\"" + e.target.result + "\" title=\"" + file.name + "\"/>" +
							"<br/><span class=\"remove btn btn-white btn-sm p-2\" style='position:absolute;top:20px;left:20px'><i class='p-0 fa fa-trash text-danger'></i></span>" +
							"</span>").insertAfter("#filesDiv");
						$(".remove").click(function () {
							$(this).parent(".pip").remove();
						});

						// Old code here
						/*$("<img></img>", {
						  class: "imageThumb",
						  src: e.target.result,
						  title: file.name + " | Click to remove"
						}).insertAfter("#files").click(function(){$(this).remove();});*/

					});
					fileReader.readAsDataURL(f);
				}
				/*$('#files').val(files)
				console.log(files);*/
			});
		} else {
			alert("Your browser doesn't support to File API")
		}
	});
	$(document).on('click','.removeImage',function(){
		$.ajaxSetup({
		   headers: {
		     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
		   }
		});
		var url = '{{ route("deleteLocationImage") }}';
		var id = $(this).data('image-id');
		var image_path = $(this).data('image-path');
		$.ajax({
			type:'POST',
			url:url,
			data:{id:id,image_path:image_path},
			success:function(resp){
				if(resp.status == true)
				{
					validationMsg('success',resp.message);
					$('.imageSpan_'+id).hide();
				}
				else
				{
					validationMsg('error',resp.message)
				}
			}
		});
	});

	$(document).on('click','.remove-cover-image', function(){
		$('#kt_image_5').css('background-image', 'unset');
		$('.save-btn').attr('disabled',true);

	});

	$(document).on('keypress', '.allow_only_numbers', function(evt){
		evt = (evt) ? evt : window.event;
	    var charCode = (evt.which) ? evt.which : evt.keyCode;

	    if (charCode > 31 && (charCode < 48 || charCode > 57)) {
	        return false;
	    }
	    return true;
	});

	$(document).on('paste', '.allow_only_numbers', function (event) {
	  	if (event.originalEvent.clipboardData.getData('Text').match(/[^\d]/)) {
	    	event.preventDefault();
	  	}
	});

	document.getElementById('isSameLocationAddr').onchange = function() {
		if(this.checked)
		{
			$('.bill_inpt').attr("disabled", true);
			$(".updateLocationAddr").trigger("click");
		} else {
			$('.bill_inpt').attr("disabled", false);
		}	
	}
	var form = KTUtil.getById('updateBillingAddress');
		
		FormValidation
		.formValidation(
			form, {
				fields: {
					billing_address: {
						validators: {
							callback: {
								message: 'Address is required',
								callback: function(input) {
									if($("#isSameLocationAddr").is(':checked'))
									{
										return true;
									} else {
										if(input.value == "") {
											return false;
										} else {
											return true;
										}		
									}		
								}
							}
						}
					},
					billing_city: {
						validators: {
							callback: {
								message: 'City is required',
								callback: function(input) {
									if($("#isSameLocationAddr").is(':checked'))
									{
										return true;
									} else {
										if(input.value == "") {
											return false;
										} else {
											return true;
										}		
									}		
								}
							}
						}
					},
					billing_region: {
						validators: {
							callback: {
								message: 'Region is required',
								callback: function(input) {
									if($("#isSameLocationAddr").is(':checked'))
									{
										return true;
									} else {
										if(input.value == "") {
											return false;
										} else {
											return true;
										}		
									}		
								}
							}
						}
					},
					billing_postcode: {
						validators: {
							callback: {
								message: 'Postcode is required',
								callback: function(input) {
									if($("#isSameLocationAddr").is(':checked'))
									{
										return true;
									} else {
										if(input.value == "") {
											return false;
										} else {
											return true;
										}		
									}		
								}
							}
						}
					}
				},
				plugins: {
					trigger: new FormValidation.plugins.Trigger(),
					submitButton: new FormValidation.plugins.SubmitButton(),
					bootstrap: new FormValidation.plugins.Bootstrap({})
				}
			}
		)
		.on('core.form.valid', function() {
		   
			$(".closeBillingMdl").trigger("click");
			
			if($("#isSameLocationAddr").is(':checked')) {
				$(".isSameBillingAddr").val(0);
			} else {
				$(".isSameBillingAddr").val(1);
			}		

			var comp = $("#editBillingDetail .billing_company_name").val();
			var addr = $("#editBillingDetail .billing_address").val();
			var apt = $("#editBillingDetail .billing_apt").val();
			var city = $("#editBillingDetail .billing_city").val();
			var region = $("#editBillingDetail .billing_region").val();
			var postcode = $("#editBillingDetail .billing_postcode").val();
			var notes = $("#editBillingDetail .billing_notes").val();

			var address = "";
			
			if(addr != "") {
				address += addr+", ";
			}
			if(apt) {
				address += apt+", ";
			}	
			if(city != "") {
				address += city+", ";
			}	
			if(postcode != "") {
				address += postcode+", ";
			}	
			if(region != "") {
				address += region;
			}	
			
			if(notes != "") {
				$("#billing_notes").html(notes);
				$(".billing_notes").val(notes);
			} else {
				$("#billing_notes").html('<a data-toggle="modal" data-target="#editBillingDetail" class="text-blue cursor-pointer">+ Add</a>');
			}		
			
			$("#billing_company_name").html(comp);
			$(".billing_company_name").val(comp);
			
			$('#billing_address').html(address);
			$('.billing_address').val(addr);
			$('.billing_apt').val(apt);
			$('.billing_city').val(city);
			$('.billing_region').val(region);
			$('.billing_postcode').val(postcode);
			$('#modal-backdrop show').hide();
		})
		.on('core.form.invalid', function() {
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
			toastr.error("Sorry, looks like there are some errors detected, please try again.");
		});
</script>

@endsection
@section('scripts')
@endsection