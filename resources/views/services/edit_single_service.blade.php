{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')

	<style>
		.pricing_row{
			/*background-color: #ccc;*/
			padding: 10px;
			border-radius: 5px;
			margin-top: 2%;
		}
		.ad{
			float: left;
		}
		{{--  .col-md-12{
			position: inherit !important;
		}  --}}
		.pricing_opt_txt {
			padding-left: 10px !important;
			font-size: 20px;
			font-weight: 600;
		}
		.remove_pricing_opt, .remove_pricing {
			float: right;
		}
		.add_pricing_opt {
			font-size: 18px;
		}
		.advacned_pricing_opt{
			position: relative;
			left: 03%;
			color: #507AFF;
			font-size: 1.35rem;
            font-weight: 500;
            margin-bottom: 12px;
		}
		select {
			padding: 10px 30px 10px 10px !important;
		}
	</style>
@endsection

@php 
$duration_array = array("5" => "5 Min", "10" => "10 Min", "15" => "15 Min", "20" => "20 Min", "25" => "25 Min", "30" => "30 Min", "35" => "35 Min", "40" => "40 Min", "45" => "45 Min", "50" => "50 Min", "55" => "55 Min", "60" => "1h", "65" => "1h 5min", "70" => "1h 10min", "75" => "1h 15min", "80" => "1h 20min", "85" => "1h 25min", "90" => "1h 30min", "95" => "1h 35min", "100" => "1h 40min", "105" => "1h 45min", "110" => "1h 50min", "115" => "1h 55min", "120" => "2h");
$i = 1;
@endphp

@section('content')

	<!--begin::edit Single Service Modal --><!-- fullscreen-modal -->
	<div class="fscreenModal p-0" id="addNewSingleServiceModal" tabindex="-1" role="dialog" aria-labelledby="addNewSingleServiceModalLabel" aria-hidden="true">
		<form class="form" id="edit_service_frm" name="edit_service_frm" action="{{ route('editservice') }}">
			@csrf
			<input type="hidden" name="id" value="{{ $service->id }}">
			<!-- <div class="fixed" style="position:fixed;"> -->
	        <div class="my-custom-header text-dark"> 
	            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
		            <a type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="window.location=document.referrer;" style="display: flex; justify-content: flex-start; align-items: center;top:unset;left: 0;opacity: 1;font-size: 1.75rem;">
		                <span aria-hidden="true" class="la la-times"></span>
		            </a>

		            <div style="flex-grow: 1;"><h2 class="font-weight-bolder title-hide">Update Single Service</h2></div>
		            
		            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
		            	<button type="button" class="btn btn-danger mr-4" data-toggle="modal" data-target="#deleteServiceModal">Delete</button>
		                <button type="submit" class="btn btn-primary" data-id="{{ $service->id }}" id="service_update" name="submitButton" style="font-size: 16px;">Update</button>
		            </div>	
            </div>
	        </div>  
            <h1 class="font-weight-bolder mb-5 text-center text-dark hide-onscroll" style="flex-grow: 1;">Update Single Service</h1> 
            <!-- </div>  -->
            <div class="modal-content bg-content">  
                <div class="my-custom-body">
					<div class="container">
						<div class="row justify-content-center"> 
							<div class="col-12 col-md-10 p-2 pr-4">
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Basic info</h4>
										<p class="m-0">Add a service name and choose the treatment type.</p>
									</div>
									<div class="card-body w-100">
										<div class="form-group mr-2 w-100">
											<label class="font-weight-bolder">Service name</label>
											<input type="text" class="form-control" name="service_name" id="service_name" value="{{ $service->service_name }}" autocomplete="off" placeholder="Service name"/>
										</div>
										<div class="form-group mr-2 w-100">
											<label class="font-weight-bolder">Treatment name</label>
											<input type="text" class="form-control" name="treatment_type" id="treatment_type" value="{{ $service->treatment_type }}" autocomplete="off" placeholder="Treatment name"/>
										</div>
										<div class="form-group" style="position: relative;">
											<label class="font-weight-bolder">Service category</label>
											<select class="select optional form-control" name="service_category" id="service_category">
												<option value="">Select Service Category</option>
												@foreach($category as $val)
													@if($service->service_category == $val->id)
														<option value="{{ $val->id }}" selected>{{ $val->category_title }}</option>
													@else
														<option value="{{ $val->id }}" >{{ $val->category_title }}</option>
													@endif

												@endforeach
											</select>
										</div>
										<div class="form-group">
											<label class="font-weight-bolder">
												Service description
											</label>
											<textarea class="form-control" placeholder="Add short description" name="service_description"
												id="paidPlanDesc" rows="6">{{ $service->service_description }}</textarea>
										</div>
										<div class="form-group mr-2 w-100">
											<label class="font-weight-bolder">Service available for</label>
											<select class="form-control" name="available_for" id="available_for">
												<option value="0" @if($service->available_for == 0) selected @endif >Everyone</option>
												<option value="1" @if($service->available_for == 1) selected @endif >Females Only</option>
												<option value="2" @if($service->available_for == 2) selected @endif >Males Only</option>
											</select>
										</div>
									</div>
								</div>
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Online booking</h4>
										<p class="m-0">Enable online bookings, choose who the service is available for and add a short description.</p>
									</div>
									<div class="card-body">
										<div class="form-group">
											<div class="mt-4 switch switch-icon switch-success" style="line-height: 33px;">
												<label class="d-flex align-item-center font-weight-bolder">
													<input type="checkbox"@if($service->is_online_booking==1) checked="checked"  @endif name="is_online_booking" id="is_online_booking">
													<span></span>&nbsp;&nbsp;&nbsp;Enable Online bookings</i>
												</label>
											</div>
										</div>
									</div>
								</div>
								
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Locations</h4>
										<p class="m-0">Choose the location where the service will take place.</p>
									</div>
									<div class="card-body">
										<div class="multicheckbox">
											<ul id="treeview-location">
												
												@php $locationids = explode(',', $service->location_id); @endphp
											
												<li>
													<label for="locations" class="checkbox my-4">
														@if(count($location)==0)
															<input type="checkbox" disabled name="locations" id="locations" @if(count($locationids) == count($location)) checked @endif>
														@else
															<input type="checkbox" name="locations" id="locations" @if(count($locationids) == count($location)) checked @endif>
														@endif
														<span></span>
														Select All
													</label>
													<ul class="display-service flex-wrap p-0">
														@foreach($location as $locationData)
															<li class="width-100">
																<label for="location_{{ $locationData['id'] }}" class="checkbox">
																	<input type="checkbox" @if(in_array($locationData['id'], $locationids)) checked="checked" @endif name="location_id[]" id="location_{{ $locationData['id'] }}" value="{{ $locationData['id'] }}">
																	<span></span>
																	<div class="custom-avtar">{{ substr($locationData['location_name'],0,1) }}</div>
																	{{ $locationData['location_name'] }}
																</label>
															</li>
														@endforeach
													</ul>
												</li>
											</ul>
										</div>
									</div>
									
								</div>
								
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Staff</h4>
										<p class="m-0">Calculate staff commission when the service is sold.</p>
									</div>
									<div class="card-body">
										<div class="multicheckbox">
											<ul id="treeview-staff">
												<li>
													@php $ids = explode(',', $service->staff_ids); @endphp
												
													<label for="all" class="checkbox my-4">
														@if(count($staff)==0)
															<input type="checkbox" disabled name="all" id="all" @if(count($ids) == count($staff)) checked @endif>
														@else
															<input type="checkbox" name="all" id="all" @if(count($ids) == count($staff)) checked @endif>
														@endif
														<span></span>
														Select All
													</label>
													<ul class="display-service flex-wrap p-0">
														@foreach($staff as $val)
															<li class="width-100">
																<label for="staff_{{ $val->id }}" class="checkbox">
																	<input type="checkbox" @if(in_array($val->id, $ids)) checked="checked" @endif name="staff_id[]" id="staff_{{ $val->id }}" value="{{ $val->id }}">
																	<span></span>
																	<div class="custom-avtar">{{ substr($val->user->first_name,0,1)." ".substr($val->user->last_name,0,1) }}</div>
																	{{ $val->user->first_name." ".$val->user->last_name }}
																</label>
															</li>
														@endforeach
													</ul>
												</li>
											</ul>
										</div>
									</div>
									<div class="card-footer">
										<div class="form-group">
											<h4 class="font-weight-bolder">Staff commission</h4>
											<p class="m-0">Calculate staff commission when the service is sold.
											</p>
											<div class="mt-4 switch switch-icon switch-success" style="line-height: 33px;">
												<label class="d-flex align-item-center font-weight-bolder">
													<input type="checkbox" @if($service->is_staff_commision_enable==1) checked="checked"  @endif  name="is_staff_commision_enable" id="is_staff_commision_enable">
													<span></span>&nbsp;&nbsp;&nbsp;Enable Staff commission</i>
												</label>
											</div>
										</div>
									</div>
								</div>
	
								<div class="card my-4">
									<div class="card-header">
										<h2 class="font-weight-bolder">Pricing and Duration</h2>
										<p class="m-0">Add the pricing options and duration of the service.</p>
									</div>
									<div class="card-body">
										<div class="repeatable">
											<div class="bg-gray-200 mb-5 card card-custom">
												<!-- <div class="card-header border-0">
													<div class="card-title m-0">
														<h3 class="m-0">Pricing option </h3>
													</div>
													<span class="d-flex align-items-center" id="removebtn"><a class="close opacity-100 remove_pricing_opt"><i class="ki ki-close"></i></a></span>
												</div>
 -->
												
												<div class="card-body" style="background-color:#F2F2F7;border-radius: 7px;padding: 0px 10px;">
													<div class="row addpricing_option">
														@foreach($servicPrice as $key => $value)
														<div class="col-md-12 pricing_row pr_rw{{ $value->id }} ">
															<h4 class="col-md-12 pricing_opt_txt">Pricing option {{ $key+1 }}@if($loop->first) @else <a class="remove_pricing" data-servicePriceId="{{ $value->id }}" data-id="{{ $key+1 }}" href="javascript:;">X</a>@endif</h4>
															
															<input type="hidden" name="servicePriceId{{ $key+1 }}" value="{{ $value->id }}">
															
															<div class="col-md-12 row price-type-container custom-pricing-row">	
																<div class="col-md-2 ad">
																	<div class="form-group">
																		<label class="col-form-label text-right">Duration</label>
																		<div class="select-wrapper nw_cl">
																			<div class="_1RNu0qum EJYPiwxT">
																				<select class="select optional form-control ddr " data-optid="{{ $key+1 }}" name="duration{{ $key+1 }}" id="duration{{ $key+1 }}">
																					@foreach($duration_array as $key1 => $val)
																						@if ($value->duration == $key1)  
																							<option  value="{{ $key1 }}" selected="selected">{{ $val }}</option>
																						@else
																							<option  value="{{ $key1 }}">{{ $val }}</option>
																						@endif
																					@endforeach
																				</select> 
																			</div>
																		</div>
																	</div>	
																</div>
																<div class="col-md-2 ad">
																	<div class="form-group">
																		<label class="col-form-label text-right">Price type</label>
																		<div class="select-wrapper nw_cl">
																			<div class="_1RNu0qum EJYPiwxT">
																				<select class="select optional form-control dpt" data-optid="{{ $key+1 }}" name="price_type{{ $key+1 }}" id="price_type{{ $key+1 }}">
																					<option value="free" @if ($value->price_type == "free") selected="selected" @endif >Free</option>
																					<option value="from" @if ($value->price_type == "from") selected="selected" @endif >From</option>
																					<option value="fixed" @if ($value->price_type == "fixed") selected="selected" @endif >Fixed</option>
																				</select> 
																			</div>
																		</div>
																	</div>	
																</div>

																<div class="col-md-2 freehd{{ $key+1 }} ad allow-style" style="{{ ($value->price_type != 'free') ? '' : 'display: none;' }}">
																	<div class="form-group">
																		<label class="col-form-label text-right">Price <span class="price-type-text text-muted">{{ ($value->price_type == 'from') ? '(from)' : '' }}</span></label>
																		<input type="text" class="form-control dpr allow_only_decimal custom-price-field" name="price{{ $key+1 }}" value="{{ $value->price }}" data-optid="{{ $key+1 }}" id="price{{ $key+1 }}" autocomplete="off" placeholder="Price"/>
																	</div>	
																</div>
																<div class="col-md-3 freehd{{ $key+1 }} ad allow-style" style="{{ ($value->price_type != 'free') ? '' : 'display: none;' }}">
																	<div class="form-group">
																		<label class="col-form-label text-right">Special price <span class="text-muted">(optional)</span></label>
																		<input type="text" class="form-control dsp allow_only_decimal custom-special-price-field" name="special_price{{ $key+1 }}" value="{{ $value->special_price }}" data-optid="{{ $key+1 }}" id="special_price{{ $key+1 }}" autocomplete="off" placeholder="Special price"/>
																	</div>	
																</div>

																<div class="col-md-3 ad">
																	<div class="form-group">
																		<label class="col-form-label text-right">Pricing name <span class="text-muted">(optional)</span></label>
																		<input type="text" class="form-control dpn" name="pricing_name{{ $key+1 }}" value="{{ $value->pricing_name }}" data-optid="{{ $key+1 }}" id="pricing_name{{ $key+1 }}" autocomplete="off" placeholder="Pricing name"/>
																	</div>	
																</div>
															</div>
															@php
																$staffDu = json_decode($value->staff_prices,true);
															@endphp
															{{-- ad model --}}
															<div class="modal fade" id="add_pricing_opt{{ $i }}" tabindex="-1" role="dialog" aria-labelledby="exampleModalSizeXl" style="display: none;" aria-hidden="true">
																<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
																	<div class="modal-content">
																		<div class="modal-header">
																			<h5 class="modal-title" id="exampleModalLabel">Advanced pricing options</h5>
																			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																				<i aria-hidden="true" class="ki ki-close"></i>
																			</button>
																		</div>
																		
																		<div class="modal-body">
																			<div class="row custom-pricing-row">
																				<div class="col-md-3">
																					<div class="form-group">
																						<label class="col-form-label text-left">Pricing name <span class="text-muted">(optional)</span></label>
																						<input type="text" class="form-control dpn" data-optid="{{ $i }}" id="mpricing_name{{ $i }}" autocomplete="off" placeholder="Pricing name"/>
																					</div>	
																				</div>
																				<div class="col-md-2">
																					<div class="form-group">
																						<label class="col-form-label text-left">Duration</label>
																						<div class="select-wrapper nw_cl">
																							<div class="_1RNu0qum EJYPiwxT">
																								<select class="select optional form-control ddr" data-optid="{{ $i }}" id="mduration{{ $i }}">
																									@foreach($duration_array as $key => $val)
																										<option @if (60 == $key) selected="selected" @endif value="{{ $key }}">{{ $val }}</option>
																									@endforeach
																								</select> 
																							</div>
																						</div>
																					</div>	
																				</div>
																				<div class="col-md-2">
																					<div class="form-group">
																						<label class="col-form-label text-left">Price type</label>
																						<div class="select-wrapper nw_cl">
																							<div class="_1RNu0qum EJYPiwxT">
																								<select class="select optional form-control dpt" data-optid="{{ $i }}" id="mprice_type{{ $i }}">
																									<option value="free">Free</option>
																									<option value="from">From</option>
																									<option value="fixed" selected >Fixed</option>
																								</select> 
																							</div>
																						</div>
																					</div>	
																				</div>
																				<div class="col-md-2">
																					<div class="form-group">
																						<label class="col-form-label text-left">Price</label>
																						<input type="text" class="form-control dpr custom-price-field allow_only_decimal" data-optid="{{ $i }}" id="mprice{{ $i }}" autocomplete="off" placeholder="Price"/>
																					</div>	
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label class="col-form-label text-left">Special price <span class="text-muted">(optional)</span></label>
																						<input type="text" class="form-control dsp allow_only_decimal custom-special-price-field" data-optid="{{ $i }}" id="mspecial_price{{ $i }}" autocomplete="off" placeholder="Special price"/>
																					</div>	
																				</div>
																			</div>	
																			
																			<div class="row">	
																				<div class="col-md-12">
																					<h3>Set price by staff</h3>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						
																					</div>	
																				</div>
																				<div class="col-md-2">
																					<div class="form-group">
																						<label class="col-form-label text-left">Duration</label>
																					</div>	
																				</div>
																				<div class="col-md-2">
																					<div class="form-group">
																						<label class="col-form-label text-left">Price type</label>
																					</div>	
																				</div>
																				<div class="col-md-2">
																					<div class="form-group">
																						<label class="col-form-label text-left">Price</label>
																					</div>	
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label class="col-form-label text-left">Special price <span class="text-muted">(optional)</span></label>
																					</div>	
																				</div>
																				@php
																					$d=0;
																				@endphp
																				@foreach($staff as $val)
																					@php
																						$d = array_search($val->id, array_column($staffDu, 'staff_id'));
																					@endphp
																				<div class="staff-price-container col-md-12 row custom-pricing-row">
																					<div class="col-md-3">
																						<div class="form-group">
																							<h5>{{ $val->user->first_name." ".$val->user->last_name }}</h5>
																							<input type="hidden" name="staff_ids{{ $i }}[]" value="{{ $val->id }}">
																						</div>	
																					</div>
																					<div class="col-md-2">
																						<div class="form-group">
																							<div class="select-wrapper nw_cl">
																								<div class="_1RNu0qum EJYPiwxT">
																									<select class="select optional form-control sdr{{ $i }}" name="staff_duration{{ $i }}[]">
																										@foreach($duration_array as $key => $val1)
																											<option @if(isset($staffDu[$d]['staff_duration']) && $staffDu[$d]['staff_duration'] == $key) selected="selected" @endif value="{{ $key }}">{{ $val1 }}</option>
																										@endforeach
																									</select> 
																								</div>
																							</div>
																						</div>	
																					</div>
																					<div class="col-md-2">
																						<div class="form-group">
																							<div class="select-wrapper nw_cl">
																								<div class="_1RNu0qum EJYPiwxT">
																									<select class="select optional form-control spt{{ $i }} staff-price-type" name="staff_price_type{{ $i }}[]" >
																										<option value="free" @if (isset($staffDu[$d]['staff_price_type']) && $staffDu[$d]['staff_price_type'] == "free") selected="selected" @endif>Free</option>
																										<option value="from" @if (isset($staffDu[$d]['staff_price_type']) && $staffDu[$d]['staff_price_type'] == "from") selected="selected" @endif>From</option>
																										<option value="fixed" @if (isset($staffDu[$d]['staff_price_type']) && $staffDu[$d]['staff_price_type'] == "fixed") selected="selected" @endif>Fixed</option>
																									</select> 
																								</div>
																							</div>
																						</div>	
																					</div>

																					<div class="col-md-2">
																						<div class="form-group">
																							<input type="text" class="form-control spr{{ $i }} custom-price-field staff-price allow_only_decimal" name="staff_price{{ $i }}[]"  value="{{ (isset($staffDu[$d]['staff_price'])) ? $staffDu[$d]['staff_price'] : '' }}" autocomplete="off" placeholder="Price" {{ ($staffDu[$d]['staff_price_type'] == "free") ?  "readonly" : "" }}/>
																						</div>	
																					</div>
																					<div class="col-md-3">
																						<div class="form-group">
																							<input type="text" class="form-control ssp{{ $i }} staff-special-price custom-special-price-field allow_only_decimal"  value="{{ (isset($staffDu[$d]['staff_special_price'])) ? $staffDu[$d]['staff_special_price'] : '' }}" name="staff_special_price{{ $i }}[]" autocomplete="off" placeholder="Special price" {{ ($staffDu[$d]['staff_price_type'] == "free") ?  "readonly" : "" }}/>
																						</div>	
																					</div>
																				</div>
																					@php //$d++;@endphp
																				@endforeach	
																			</div>
																		</div>
																		<div class="modal-footer">
																			<button type="button" class="btn btn-light-primary font-weight-bold" data-dismiss="modal" data-target="#add_pricing_opt{{ $i }}">Close</button>
																			<button type="button" class="btn btn-primary font-weight-bold" data-dismiss="modal" data-target="#add_pricing_opt{{ $i }}">Save changes</button>
																		</div>
																	</div>
																</div>
															</div>
															{{-- end --}}
															<div class="row">
																<a class="advacned_pricing_opt" data-target="#add_pricing_opt{{ $i }}" data-toggle="modal" href="javascript:;">Advanced pricing options</a>
															</div>
														</div>
														@php $i++; @endphp
														@endforeach
													</div>
												</div>
											</div>
											<div class="row">									
												<div class="col-md-12" style="margin: 20px 0px 30px;">
													<input type="hidden" name="total_pricing" id="total_pricing" value="{{ count($servicPrice) }}">
													<a class="add_pricing_opt" id="add_pricing_opt" href="javascript:;"><i class="la la-plus-circle mr-3 text-blue"></i>Add pricing option</a>
												</div>
											</div>
										</div>
									</div>
									<div class="card-footer">
										<h5 class="font-weight-bolder mb-6">Extra time</h5>
										<p>Enable extra time after the service.</p>
										<div class="form-group">
											<div class="mt-4 switch switch-icon switch-success" style="line-height: 33px;">
												<label class="d-flex align-item-center font-weight-bolder">
													<input onchange="isEnableExtraTime()" id="enableExtraTime"
														type="checkbox" @if($service->is_extra_time == 1) checked="checked" @endif  value="1" name="is_extra_time" id="is_extra_time">
													<span></span>&nbsp;&nbsp;&nbsp;Enable extra time</i>
												</label>
											</div>
										</div>
										<div class="isEnableExtraTime" @if($service->is_extra_time == 1) style="display:block" @else style="display:none" @endif>
											<div class="form-group">
												<div class="row extra_time_sec extra-time" >
													<div class="col-md-3">
														<label class="option">
															<span class="option-control">
																<span class="radio">
																	<input type="radio" @if($service->extra_time == 0) checked @endif name="extra_time"  value="0">
																	<span></span>
																</span>
															</span>
															<span class="option-label">
																<span class="option-head">
																	<span class="option-title">Processing time</span>
																</span>
																<span class="option-body">Take other bookings during this time.</span>
															</span>
														</label>
														<div class="fv-plugins-message-container"></div>
													</div>
													<div class="col-md-3">
														<label class="option">
															<span class="option-control">
																<span class="radio">
																	<input type="radio" @if($service->extra_time == 1) checked @endif name="extra_time" value="1">
																	<span></span>
																</span>
															</span>
															<span class="option-label">
																<span class="option-head">
																	<span class="option-title">Blocked time</span>
																</span>
																<span class="option-body">Block time between appointments.</span>
															</span>
														</label>
													</div>
													
													<div class="col-md-12">
														<label class="col-form-label text-right">Duration</label>
														<div class="select-wrapper nw_cl">
															<div class="_1RNu0qum EJYPiwxT">
																<select class="select optional form-control" default="default" name="extra_time_duration" id="extra_time_duration">
																	@foreach($duration_array as $key => $val)
																		@if ($service->extra_time_duration == $key)  
																			<option  value="{{ $key }}" selected="selected">{{ $val }}</option>
																		@else
																			<option  value="{{ $key }}">{{ $val }}</option>
																		@endif
																	@endforeach
																</select> 
															</div>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
								</div>
	
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Sales settings</h4>
									</div>
									<div class="card-body w-70">
										<h5 class="font-weight-bolder mb-6">Set the tax rate</h5>
										<div class="form-group">
											<label class="font-weight-bolder">
												Tax <span class="text-muted font-size-sm">(Included in price)</span>
											</label>
											<select class="form-control" name="tax_id" id="tax_id">
												<option value="0">No tax</option>

												@foreach($taxes as $key => $value) 
													<option value="{{ $value->id }}" @if($service->tax_id == $value->id) selected @endif>{{ $value->tax_name.' ('.$value->tax_rates.')' }}</option>
												@endforeach
											</select>
										</div>
									</div>
									<div class="card-footer">
										<h5 class="font-weight-bolder mb-6">Voucher sales</h5>
										<div class="form-group">
											<div class="mt-4 switch switch-icon switch-success" style="line-height: 33px;">
												<label class="d-flex align-item-center font-weight-bolder">
													<input class="boolean optional ios-switch-cb" onchange="isSaleVoucher()" type="checkbox" value="1"@if($service->is_voucher_enable == 1) checked="checked" @endif  name="is_voucher_enable" id="enableSale"> <span class="switchery"></span> 
													&nbsp;&nbsp;&nbsp;Enable voucher sales</i>
												</label>
											</div>
										</div>
										<div class="form-group isSaleVoucher" @if($service->is_voucher_enable == 0) style="display: none;" @endif >
											<div class="form-group ml-2 w-100">
												<label class="font-weight-bolder">Voucher expiry period</label>
												<select class="form-control" name="voucher_expiry" id="voucher_expiry">
													<option value="days_14" @if ($service->voucher_expiry_month.'_'.$service->voucher_expiry_day == "days_14") selected="selected" @endif >14 Days</option>
													<option value="month_1" @if ($service->voucher_expiry_month.'_'.$service->voucher_expiry_day == "month_1") selected="selected" @endif>1 Month</option>
													<option value="month_2" @if ($service->voucher_expiry_month.'_'.$service->voucher_expiry_day == "month_2") selected="selected" @endif>2 Months</option>
													<option value="month_3" @if ($service->voucher_expiry_month.'_'.$service->voucher_expiry_day == "month_3") selected="selected" @endif >3 Months</option>
													<option value="month_6" @if ($service->voucher_expiry_month.'_'.$service->voucher_expiry_day == "month_6") selected="selected" @endif >6 Months</option>
													<option value="year_1" @if ($service->voucher_expiry_month.'_'.$service->voucher_expiry_day == "year_1") selected="selected" @endif >1 Year</option>
													<option value="year_3" @if ($service->voucher_expiry_month.'_'.$service->voucher_expiry_day == "year_3") selected="selected" @endif >3 Years</option>
													<option value="year_5" @if ($service->voucher_expiry_month.'_'.$service->voucher_expiry_day == "year_5") selected="selected" @endif >5 Years</option>
													<option value="0">No Expiry</option>
												</select>
											</div>
										</div>
									</div>
								</div>
							</div> 
						</div>
					</div>
				</div>
            </div>

            <div class="modal fade p-0" id="deleteServiceModal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-hidden="true">
				<div class="modal-dialog " role="document">
					<div class="modal-content bg-white">
						<div class="modal-header">
							<h5 class="modal-title font-weight-bolder text-center">Delete Plan</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="right: 0; top: 0">
								<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
							</button>
						</div>
						<div class="modal-body">
							<p>Are you sure you want to delete this Service?</p>
						</div>
						<div class="modal-footer">
							<button type="button" data-url="{{ route('deleteService',$service->id) }}" id="serdelete" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Delete</button>
						</div>
					</div>
				</div>
			</div>
	    </form>
    </div>
	<!--end::Add Single Service Modal-->

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
	
<div class="modal fade p-0" id="deleteServicePriceModal" tabindex="-1" role="dialog" aria-labelledby="deleteServicePriceModalLabel" aria-modal="true">
	<div class="modal-dialog " role="document">
		<div class="modal-content ">
			<div class="modal-header">
				<h5 class="modal-title font-weight-bold text-center" id="deleteServicePriceModalLabel">Delete Service Price</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
				</button>
			</div>
			<form method="POST" action="{{ route('deleteServicePrice') }}" id="deleteServicePrice">
				
				@csrf
				<input type="hidden" name="deleteServicePriceId" id="deleteServicePriceId">
				
				<div class="modal-body">
					<div class="container">
						<div class="row">
							<p>Are you sure you want to delete this pricing option?</p>
						</div>
					</div>
				</div>
				<div class="modal-footer d-flex justify-content-between">
					<button type="submit" id="deleteServicePriceBtn" class="self-align-left btn btn-danger font-weight-bold">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>
	
@endsection

{{-- Scripts Section --}}
@section('scripts')
	<!--begin::Page Scripts(used by this page)-->
	<script src="{{ asset('assets/js/pages/widgets.js')}}"></script>
	<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js')}}"></script>
	<script src="{{ asset('js/add-service.js') }}" type="text/javascript"></script>

	<script type="text/javascript"> 
		var serviceValid = ""; 
		var duration = '<?php echo json_encode($duration_array);?>'; 
		var staff = '<?php echo json_encode($staff);?>';
		
		const priceValidators = {
			validators: {
				notEmpty: {
					message: 'Price is required'
				}
			}
		};

		/*$(document).on("focusout", ".dsp", function() {

            if (parseFloat($(this).val()) > parseFloat($(this).closest('.pricing_row').find(".dpr").val())) {
                var errorsHtml = '';
                toastr.error((errorsHtml) ? errorsHtml : 'special price is not greater than price.');
                // KTUtil.scrollTop();
                bigger = false;
                return
            } else {
                bigger = true;
            }
        });*/
	</script>
	<script>
		$("#treeview-staff").hummingbird();
		$("#treeview-location").hummingbird();
		
		function selectCaegory(data) {
			$("#selectedCategory").val(data);
		}
		function isSaleVoucher() {
			if ($('#enableSale').is(':checked')) {
				$(".isSaleVoucher").show();
			} else {
				$(".isSaleVoucher").hide();
			}
		}
		function isEnableExtraTime() {
			if ($('#enableExtraTime').is(':checked')) {
				$(".isEnableExtraTime").show();
			} else {
				$(".isEnableExtraTime").hide();
			}
		}

		$(function () {
			$("#add-pricing-option").on('click', function () {
				var ele = $(this).closest('.card-custom').clone(true);
				$(this).closest('.card-body').after(ele);
			})
		})
		$(function () {
			$(".repeat").on('click', function (e) {
				e.preventDefault();
				var $self = $(this);
				$self.before($self.prev('.card-custom').clone());
				//$self.remove();
			});
		});
	</script>
	<script>
		$(".isServicePriceOrisFree").show()
		$(".isCustomPrice").hide()
		$(".isPercentage").hide()
		function priceSelection(data) {
			console.log(data.value);
			if (data.value === "catalog_price" || data.value === "free") {
				$(".isServicePriceOrisFree").show()
				$(".isCustomPrice").hide()
				$(".isPercentage").hide()
			} else if (data.value === "custom_price") {
				$(".isServicePriceOrisFree").hide()
				$(".isCustomPrice").show()
				$(".isPercentage").hide()
			} else {
				$(".isServicePriceOrisFree").hide()
				$(".isCustomPrice").hide()
				$(".isPercentage").show()
			}
		} 
		$(document).on("click", ".remove_pricing_opt", function(){
			var id = $(this).data("id");
			var cnt = $("#total_pricing").val();
        	cnt--;
			$("#total_pricing").val(cnt);
			
			$(".pr_rw"+id).remove();
		});	
		
		$(document).on('click','.remove_pricing',function(){
			var servicePriceId = $(this).attr('data-servicePriceId');
			$("#deleteServicePriceModal").modal('show');
			$("#deleteServicePriceId").val(servicePriceId);
		});
		
		$('.col-md-12 *').not('.allow-style').removeAttr('style');

		$(window).on("scroll", function() {   
			// alert();
	        var topHeight = $('.my-custom-header').outerHeight();
	        if ($(this).scrollTop() > topHeight) {
				$('.my-custom-header').addClass("bg-white");
				$('.my-custom-header').addClass("border-bottom"); 
	        } else{ 
	          	$('.my-custom-header').removeClass("bg-white"); 
	          	$('.my-custom-header').removeClass("border-bottom"); 
	        }   
	    }); 

	    $(document).on('change','.staff-price-type', function(){
	    	console.log('staff  price type change');
	    	if($(this).val() == 'free') {
		    	$(this).closest('.staff-price-container').find('.staff-price').val('').attr('readonly', true);
		    	$(this).closest('.staff-price-container').find('.staff-special-price').val('').attr('readonly', true);
		    } else {
		    	$(this).closest('.staff-price-container').find('.staff-price').removeAttr('readonly');
		    	$(this).closest('.staff-price-container').find('.staff-special-price').removeAttr('readonly');
		    }
	    });

	</script>
@endsection
