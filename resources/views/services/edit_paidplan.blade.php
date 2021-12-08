{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')

	<style>
		.help-inline{
			color: red;
		}
	</style>
	<link href="{{ asset('assets/css/owl.css') }}" rel="stylesheet" type="text/css">
@endsection

@section('content')

	<!-- <div class="container-fluid p-0">
		<form class="form" method="POST" id="paidplan" action="{{ route('updatePlans') }}">
			@csrf
			<div class="my-custom-body-wrapper">
				<div class="my-custom-header">
					<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
						<p class="cursor-pointer m-0 px-10 " onclick="history.back();"><i
								class="text-dark fa fa-times icon-lg"></i>
						</p>
						<h1 class="font-weight-bolder">Update a paid plan</h1>
						<div class="modal fade p-0" id="deletePlanModal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-hidden="true" style="display: none;">
							<div class="modal-dialog " role="document">
								<div class="modal-content ">
									<div class="modal-header">
										<h5 class="modal-title font-weight-bold text-center">Delete Plan</h5>
										<button type="button" class="close" data-dismiss="modal" aria-label="Close">
											<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
										</button>
									</div>
									<div class="modal-body">
										<div class="container">
											<div class="row">
												<p>Are you sure you want to delete this Paid Plan?</p>
											</div>
										</div>
									</div>
									<div class="modal-footer d-flex justify-content-between">
										<button type="button" data-url="{{ route('deletePlans',$plan->id) }}" id="delete" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Delete</button>
									</div>
								</div>
							</div>
						</div>
						<div class="d-flex justify-content-between" style="position: relative;left: 18%;">
							<button type="button" class="btn btn-danger font-weight-bold" data-toggle="modal" data-target="#deletePlanModal">Delete</button>
						</div>
						<button type="button" class="btn btn-primary" id="update">Update a paid plan</button>
					</div>
				</div>
				<div class="my-custom-body bg-secondary">
					<div class="container">
						<div class="row justify-content-center">
							<div class="col-12 col-md-10 p-2 pr-4">
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Basic info</h4>
									</div>
									<div class="card-body w-70">
										<div class="form-group mr-2 w-100">
											<label class="font-weight-bolder">Paid plan name</label>
											<input type="hidden" name="id" value="{{ $plan->id }}">
											<input type="text" name="name" value="{{ $plan->name }}" class="form-control" placeholder="Add paid plan name">
										</div>
										<div class="form-group">
											<label class="font-weight-bolder">
												Paid plan description
											</label>
											<textarea class="form-control" name="description"
												placeholder="Add private notes viewable in staff settings only (optional)"
												id="paidPlanDesc" rows="6">{{ $plan->description }}</textarea>
										</div>
									</div>
								</div>
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Services and sessions</h4>
										<p class="m-0">Add the services and sessions included in the paid plan.</p>
									</div>
									<div class="card-body w-70">
										<div class="form-group" style="position: relative;">
											<label class="font-weight-bolder">Included services</label>
											<input type="text" id="serviceInput" readonly=""
												class="form-control form-control-lg" placeholder="All services"
												data-toggle="modal" data-target="#servicesModal" style="cursor: pointer;">
											<a href="" class="chng_popup" data-toggle="modal" data-target="#servicesModal">
												Edit</a>
										</div>
										<div class="d-flex justify-content-between">
											<div class="form-group mr-2 w-100">
												<label class="font-weight-bolder">Sessions</label>
												<select class="form-control" id="select-session" name="sessions">
													<option value="0" @if($plan->sessions==0) selected="" @endif >Limited</option>
													<option value="1" @if($plan->sessions==1) selected="" @endif >Unlimited</option>
												</select>
											</div> 
											<div class="form-group ml-2 w-100 no_of_session" @if($plan->sessions==1) style="display: none;" @endif>
												<label class="font-weight-bolder">Total number of sessions</label>
												<input type="text" class="form-control" value="{{ $plan->sessions_num }}" placeholder="5" name="sessions_num">
											</div>
										</div>
									</div>
								</div>

								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Pricing and payment</h4>
										<p class="m-0">Choose how you want to charge clients.</p>
									</div>
									<div class="card-body w-70">
										<div class="d-flex justify-content-between">
											<div class="form-group">
												<label class="font-weight-bolder">Joint Addons</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text">CA $</span>
													</div>
													<input type="text" class="form-control" value="{{ $plan->price }}" name="price" aria-label="Amount (to the nearest dollar)">
												</div>
											</div>
											<div class="form-group ml-2 w-100">
												<label class="font-weight-bolder">Valid for</label>
												<select class="form-control" id="select-location" name="valid_for">
													<option value="14 Days" @if($plan->valid_for== "14 Days") selected="" @endif >14 Days</option>
													<option value="1 Month" @if($plan->valid_for== "1 Month") selected="" @endif >1 Month</option>
													<option value="2 Month" @if($plan->valid_for== "2 Month") selected="" @endif >2 Months</option>
													<option value="3 Month" @if($plan->valid_for== "3 Month") selected="" @endif >3 Months</option>
													<option value="6 Month" @if($plan->valid_for== "6 Month") selected="" @endif >6 Months</option>
													<option value="1 Year" @if($plan->valid_for== "1 Year") selected="" @endif >1 Year</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="font-weight-bolder">Tax rate</label>
											<select class="form-control" id="select-location" name="tax">
												<option value="0" selected="">No tax</option>
												@foreach($tax_arr as $val)
													<option value="{{ $val['id'] }}" @if($plan->tax == $val['id']) selected="" @endif >{{ $val['title'] }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>

								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Colour customisation</h4>
										<p class="m-0">Select a color that matches your business.</p>
									</div>
									<div class="card-body w-70">
										<div class="form-group">
											<div class="radio-inline flex-wrap paid-plan-radio">
												<label class="radio radio-accent purple">
													<input type="radio" @if($plan->color== "parpal") checked="" @endif  value="purple" name="color">
													<span></span>
												</label>
												<label class="radio radio-accent blue">
													<input type="radio" @if($plan->color== "blue") checked="" @endif value="blue" name="color">
													<span></span>
												</label>
												<label class="radio radio-accent black">
													<input type="radio" @if($plan->color== "black") checked="" @endif value="black" name="color">
													<span></span>
												</label>
												<label class="radio radio-accent green">
													<input type="radio" @if($plan->color== "green") checked="" @endif value="green" name="color">
													<span></span>
												</label>
												<label class="radio radio-accent orange">
													<input type="radio" @if($plan->color== "orange") checked="" @endif value="orange" name="color">
													<span></span>
												</label>
											</div>
										</div>
									</div>
								</div>

								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Online sales</h4>
										<p class="m-0">Choose if you would like to sell your paid plan online.</p>
									</div>
									<div class="card-body w-70">
										<div class="form-group">
											<div class="mt-4 switch switch-icon switch-primary" style="line-height: 33px;">
												<label class="d-flex align-item-center font-weight-bolder">
													<input type="checkbox" @if($plan->online_sales== 1) checked="checked" @endif name="online_sales" >
													<span></span>&nbsp;&nbsp;&nbsp;Enable Online sales</i>
												</label>
											</div>
										</div>
										<div class="paid-plans-header rounded">
											<h2 class="text-dark font-weight-bolder mb-4">Set up payments to sell paid plans
												online
												and increase
												sales</h2>
											<p class="font-size-lg">Congrats! You have ScheduleDown Plus enabled. Let
												clients
												buy
												your
												paid plans online
												through the ScheduleDown marketplace and via
												direct booking links by setting up payments in your account.</p>
											<a class="btn btn-white">Learn More</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal" id="servicesModal">
				<div class="modal-dialog">
					<div class="modal-content"> 
						<div class="modal-header d-flex justify-content-between">
							<h4 class="modal-title">Select services</h4>
							<button type="button" class="text-dark close" data-dismiss="modal">&times;</button>
						</div> 
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
										@foreach($cat as $value)
										<ul>
											<li>
												<label for="all-{{ $value->category_title }}" class="checkbox">
													<input type="checkbox" name="all-{{ $value->category_title }}" id="all-{{ $value->category_title }}">
													<span></span>
													{{ $value->category_title }}
												</label>
												<ul>
													@php
														$i=1;
														$services_ids = explode(",", $plan->services_ids);
													@endphp
													@foreach($value->service as $service)
													<li>
														<label for="all-{{ $value->category_title }}-{{ $i }}" class="checkbox">
															<input type="checkbox" name="value_checkbox[]" value="{{ $service->id }}" @if( in_array($service->id, $services_ids)) checked="" @endif id="all-{{ $value->category_title }}-{{ $i }}">
															<span></span>
															<div class="d-flex align-items-center w-100">
																<span class="m-0">
																	{{ $service->service_name }}
																	<p class="m-0 text-muted">@foreach($service->servicePrice as $value){{ $value->duration }},@endforeach	</p>
																</span>
																<span class="ml-auto">
																	@foreach($service->servicePrice as $value)CA ${{ $value->price }}@endforeach
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
							<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal">Select Services</button>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div> -->

	<div class="fullscreen-modal p-0" id="editPlanModal" tabindex="-1" role="dialog" aria-labelledby="editPlanModalLabel" aria-hidden="true">
		<form class="form" method="POST" id="paidplan" action="{{ route('updatePlans') }}">
			@csrf
	        <div class="my-custom-header text-dark"> 
	            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
		            <a type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="history.back();" style="display: flex; justify-content: flex-start; align-items: center;top:unset;left: 0;opacity: 1;font-size: 1.75rem;">
		                <span aria-hidden="true" class="la la-times"></span>
		            </a>
		            <div style="flex-grow: 1;"><h2 class="font-weight-bolder title-hide">Update a paid plan</h2></div>
		            
		            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
		            	<button type="button" class="btn btn-danger mr-4" data-toggle="modal" data-target="#deletePlanModal">Delete</button>
		                <button type="submit" class="btn btn-primary" id="update">Update a paid plan</button>
		            </div>
		        </div>
	        </div> 
            <h1 class="font-weight-bolder mb-5 text-center text-dark hide-onscroll" style="flex-grow: 1;">Update a paid plan</h1>  
            <div class="modal-content">  
                <div class="my-custom-body">
					<div class="container">
						<div class="row justify-content-center"> 
							<div class="col-12 col-md-10 p-2 pr-4">
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Basic info</h4>
									</div>
									<div class="card-body w-70">
										<div class="form-group mr-2 w-100">
											<label class="font-weight-bolder">Paid plan name</label>
											<input type="hidden" name="id" value="{{ $plan->id }}">
											<input type="text" name="name" value="{{ $plan->name }}" class="form-control" placeholder="Add paid plan name">
										</div>
										<div class="form-group">
											<label class="font-weight-bolder">
												Paid plan description
											</label>
											<textarea class="form-control" name="description"
												placeholder="Add private notes viewable in staff settings only (optional)"
												id="paidPlanDesc" rows="6">{{ $plan->description }}</textarea>
										</div>
									</div>
								</div>
								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Services and sessions</h4>
										<p class="m-0">Add the services and sessions included in the paid plan.</p>
									</div>
									<div class="card-body w-70">
										<div class="form-group" style="position: relative;">
											<label class="font-weight-bolder">Included services</label>
											<input type="text" id="serviceInput" readonly=""
												class="form-control form-control-lg" placeholder="All services"
												data-toggle="modal" data-target="#servicesModal" style="cursor: pointer;">
											<a href="" class="chng_popup" data-toggle="modal" data-target="#servicesModal">
												Edit</a>
										</div>
										<div class="d-flex justify-content-between">
											<div class="form-group mr-2 w-100">
												<label class="font-weight-bolder">Sessions</label>
												<select class="form-control" id="select-session" name="sessions">
													<option value="0" @if($plan->sessions==0) selected="" @endif >Limited</option>
													<option value="1" @if($plan->sessions==1) selected="" @endif >Unlimited</option>
												</select>
											</div> 
											<div class="form-group ml-2 w-100 no_of_session" @if($plan->sessions==1) style="display: none;" @endif>
												<label class="font-weight-bolder">Total number of sessions</label>
												<input type="text" class="form-control" value="{{ $plan->sessions_num }}" placeholder="5" name="sessions_num">
											</div>
										</div>
									</div>
								</div>

								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Pricing and payment</h4>
										<p class="m-0">Choose how you want to charge clients.</p>
									</div>
									<div class="card-body w-70">
										<div class="d-flex justify-content-between">
											<div class="form-group">
												<label class="font-weight-bolder">Joint Addons</label>
												<div class="input-group">
													<div class="input-group-prepend">
														<span class="input-group-text">CA $</span>
													</div>
													<input type="text" class="form-control" value="{{ $plan->price }}" name="price" aria-label="Amount (to the nearest dollar)">
												</div>
											</div>
											<div class="form-group ml-2 w-100">
												<label class="font-weight-bolder">Valid for</label>
												<select class="form-control" id="select-location" name="valid_for">
													<option value="14 Days" @if($plan->valid_for== "14 Days") selected="" @endif >14 Days</option>
													<option value="1 Month" @if($plan->valid_for== "1 Month") selected="" @endif >1 Month</option>
													<option value="2 Month" @if($plan->valid_for== "2 Month") selected="" @endif >2 Months</option>
													<option value="3 Month" @if($plan->valid_for== "3 Month") selected="" @endif >3 Months</option>
													<option value="6 Month" @if($plan->valid_for== "6 Month") selected="" @endif >6 Months</option>
													<option value="1 Year" @if($plan->valid_for== "1 Year") selected="" @endif >1 Year</option>
												</select>
											</div>
										</div>
										<div class="form-group">
											<label class="font-weight-bolder">Tax rate</label>
											<select class="form-control" id="select-location" name="tax">
												<option value="0" selected="">No tax</option>
												@foreach($tax_arr as $val)
													<option value="{{ $val['id'] }}" @if($plan->tax == $val['id']) selected="" @endif >{{ $val['title'] }}</option>
												@endforeach
											</select>
										</div>
									</div>
								</div>

								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Colour customisation</h4>
										<p class="m-0">Select a color that matches your business.</p>
									</div>
									<div class="card-body w-70">
										<div class="form-group">
											<div class="radio-inline flex-wrap paid-plan-radio">
												<label class="radio radio-accent purple">
													<input type="radio" @if($plan->color== "parpal") checked="" @endif  value="purple" name="color">
													<span></span>
												</label>
												<label class="radio radio-accent blue">
													<input type="radio" @if($plan->color== "blue") checked="" @endif value="blue" name="color">
													<span></span>
												</label>
												<label class="radio radio-accent black">
													<input type="radio" @if($plan->color== "black") checked="" @endif value="black" name="color">
													<span></span>
												</label>
												<label class="radio radio-accent green">
													<input type="radio" @if($plan->color== "green") checked="" @endif value="green" name="color">
													<span></span>
												</label>
												<label class="radio radio-accent orange">
													<input type="radio" @if($plan->color== "orange") checked="" @endif value="orange" name="color">
													<span></span>
												</label>
											</div>
										</div>
									</div>
								</div>

								<div class="card my-4">
									<div class="card-header">
										<h4 class="font-weight-bolder">Online sales</h4>
										<p class="m-0">Choose if you would like to sell your paid plan online.</p>
									</div>
									<div class="card-body w-70">
										<div class="form-group">
											<div class="mt-4 switch switch-icon switch-primary" style="line-height: 33px;">
												<label class="d-flex align-item-center font-weight-bolder">
													<input type="checkbox" @if($plan->online_sales== 1) checked="checked" @endif name="online_sales" >
													<span></span>&nbsp;&nbsp;&nbsp;Enable Online sales</i>
												</label>
											</div>
										</div>
										<div class="paid-plans-header rounded">
											<h2 class="text-dark font-weight-bolder mb-4">Set up payments to sell paid plans
												online
												and increase
												sales</h2>
											<p class="font-size-lg">Congrats! You have ScheduleDown Plus enabled. Let
												clients
												buy
												your
												paid plans online
												through the ScheduleDown marketplace and via
												direct booking links by setting up payments in your account.</p>
											<a class="btn btn-white" data-toggle="modal"
							data-target="#learnMoreModal">Learn More</a>
										</div>
									</div>
								</div>
							</div> 
						</div>
					</div>
				</div>
            </div>
            <div class="modal" id="servicesModal">
				<div class="modal-dialog">
					<div class="modal-content bg-white"> 
						<div class="modal-header d-flex justify-content-between">
							<h4 class="modal-title">Select services</h4>
							<button type="button" class="text-dark close" data-dismiss="modal" style="right: 0; top: 0"><i aria-hidden="true" class="la la-times icon-lg"></i></button>
						</div> 
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
										@foreach($serviceCategory as $serviceKey => $serviceValue)
											<li>
												<label for="all-{{ str_replace(' ','',$serviceValue['category_title']) }}" class="checkbox">
													<input type="checkbox" id="all-{{ str_replace(' ','',$serviceValue['category_title']) }}" class="allCategory" data-count="{{ count($serviceCategory) }}" name="all-{{ $value->category_title }}">
													<span></span>
													{{ $serviceValue['category_title'] }}
												</label>
												<ul>
													@foreach ($serviceValue['service'] as $serviceData)
														@foreach ($serviceData['service_price'] as $priceKey => $servicePrice)
															<li>
																<label for="all-{{ str_replace(' ','',$serviceValue['category_title']) }}-{{ $serviceData['id'] }}-{{ $priceKey }}" class="checkbox">
																	<input type="checkbox" name="value_checkbox[]" id="all-{{ str_replace(' ','',$serviceValue['category_title']) }}-{{ $serviceData['id'] }}-{{ $priceKey }}" value="{{ $serviceData['id'] }}" @if( in_array($serviceData['id'], $services_ids)) checked="" @endif data-count="{{ count($serviceValue['service']) }}" class="servicePrices" data-cat="all-{{ str_replace(' ','',$serviceValue['category_title']) }}">
																	<span></span>
																	<div class="d-flex align-items-center w-100">
																		<span class="m-0">
																			<?php
																			$totalMinutes = $servicePrice['duration'];
																			$hours = intval($totalMinutes/60);
																			$minutes = $totalMinutes - ($hours * 60);
																			$duration = $hours."h ".$minutes."min";
																			?>
																			{{ $serviceData['service_name'] }}
																			<p class="m-0 text-muted">p{{ $priceKey + 1 }},{{ $duration }}</p>
																		</span>
																		<span class="ml-auto">
																			CA ${{ $servicePrice['price'] }}
																		</span>
																	</div>
																</label>
															</li>
														@endforeach
													@endforeach
												</ul>
											</li>	
										@endforeach
										{{-- @foreach($cat as $value)
										<ul>
											<li>
												<label for="all-{{ $value->category_title }}" class="checkbox">
													<input type="checkbox" class="allCategory" name="all-{{ $value->category_title }}" id="all-{{ $value->category_title }}" data-count="{{ count($cat) }}">
													<span></span>
													{{ $value->category_title }}
												</label>
												<ul>
													@php
														$i=1;
														$services_ids = explode(",", $plan->services_ids);
													@endphp
													@foreach($value->service as $service)
													<li>
														<label for="all-{{ $value->category_title }}-{{ $i }}" class="checkbox">
															<input type="checkbox" name="value_checkbox[]" value="{{ $service->id }}" @if( in_array($service->id, $services_ids)) checked="" @endif id="all-{{ $value->category_title }}-{{ $i }}" data-count="{{ count(array($value->service)) }}" data-cat="all-{{ str_replace(' ','',$value->category_title) }}">
															<span></span>
															<div class="d-flex align-items-center w-100">
																<span class="m-0">
																	{{ $service->service_name }}
																	@php 
																		$durationArr = array();
																	@endphp
																	@foreach($service->servicePrice as $value)
																		@php 
																			array_push($durationArr,$value->duration);
																		@endphp
																	@endforeach
																	<p class="m-0 text-muted">{{ implode(',',$durationArr) }}</p>
																</span>
																<span class="ml-auto">
																	@foreach($service->servicePrice as $value)CA ${{ $value->price }}@endforeach
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
										@endforeach --}}
									</li>
								</ul>
							</div>
						</div>
						<div class="modal-footer">
							<button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
							<button type="button" class="btn btn-primary" data-dismiss="modal">Select Services</button>
						</div>
					</div>
				</div>
			</div>
			<div class="modal fade p-0" id="deletePlanModal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-hidden="true">
				<div class="modal-dialog " role="document">
					<div class="modal-content bg-white">
						<div class="modal-header">
							<h5 class="modal-title font-weight-bolder text-center">Delete Plan</h5>
							<button type="button" class="close" data-dismiss="modal" aria-label="Close" style="right: 0; top: 0">
								<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
							</button>
						</div>
						<div class="modal-body"> 
							<p>Are you sure you want to delete this Paid Plan?</p> 
						</div>
						<div class="modal-footer">
							<button type="button" data-url="{{ route('deletePlans',$plan->id) }}" id="delete" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Delete</button>
						</div>
					</div>
				</div>
			</div>
	    </form>
    </div>
	<div class="modal fade" id="learnMoreModal" tabindex="-1" role="dialog" aria-labelledby="learnMoreModal" style="display: none;" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered modal-xl" role="document">
			<div class="modal-content">
				<div class="modal-body p-0 m-0">
					<button type="button" class="position-absolute close m-auto p-6 right-0 z-index" data-dismiss="modal" aria-label="Close" style="z-index: 99;font-size: 30px;">
						<span aria-hidden="true">×</span>
					</button>
					<div class="container p-0 m-0">
						<div class="row">
							<div class="col-12 col-md-6">
								<div class="p-10">
									<h1 class="font-weight-bolder my-18">Enable card payments for online booking
										and
										say
										goodbye
										to
										no shows!</h1>
									<h5 class="text-secondary my-18">Setup ScheduleDown Pay now to enable in-app
										payment
										processing,
										take
										back control of your
										calendar by charging no show and
										late cancellation fees to client cards</h5>
									<h5 class="text-secondary my-18">There are <span class="font-weight-bolder"><u>no
												additional
												fees</u></span> to use
										integrated
										payment processing features, it 's already included with ScheduleDown Plus.</h5>
									<div class="d-flex my-4">
										<button class="btn btn-primary mr-8">Setup ScheduleDown Pay</button>
										<a class="btn btn-white">Learn More</a>
									</div>
								</div>
							</div>
							<div class="col-12 col-md-6">
								<div class="learnMoreModalBackImg">
									<div class="owl-carousel">
										<div class="">
											<div class="d-flex">
												<img alt="phone" src="{{ asset('/assets/images/phone.png') }}">
												<img alt="phone" class="position-absolute" src="{{ asset('/assets/images/chat.png') }}">
											</div>
											<div class="px-10">
												<h3 class="text-center mb-10 pt-0 px-20"><span
														class="font-weight-bolder">Protect
														from no
														shows</span> and late
													cancellations by charging client cards
												</h3>
											</div>
										</div>
										<div class="">
											<div class="d-flex">
												<img alt="phone" src="{{ asset('/assets/images/visa-phone.png') }}">
												<img alt="phone" class="position-absolute" src="{{ asset('/assets/images/visa.png') }}">
											</div>
											<div class="px-10">
												<h3 class="text-center mb-10 pt-0 px-20"><span
														class="font-weight-bolder">Integrated card processing</span> for
													easy and secure in-app payments
												</h3>
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

{{-- Scripts Section --}}
@section('scripts')
	<script src="{{ asset('assets/js/owl.js') }}"></script>
	<script>
		$(document).ready(function () {
			$('.owl-carousel').owlCarousel({
				center: true,
				items: 1,
				dots: true,
				loop: false,
				margin: 10
			});
		});
	</script>
	<!-- Multi level checkbox -->
	<script src="{{ asset('assets/js/hummingbird-treeview-1.3.js') }}"></script>
	
	<script>
		$("#select-session").change( function() {
			var val = $(this).val();	
			
			if(val == 1) {
				$(".no_of_session").hide();
			} else {
				$(".no_of_session").show();
			}		
		});
		
		var list = [];
		$("#treeview").hummingbird();
		$("#treeview").on("CheckUncheckDone", function () {
			var count = $('input[name="value_checkbox[]"]:checked').length;
			var allCount = $('input[type="checkbox"]:checked').length;
			var allCheck = $('input[type="checkbox"]').length;

			if (allCheck == allCount) {
				$("#serviceInput").val('All Service Selected')
			} else {
				$("#serviceInput").val(count + ' service Selected')
			}
		});
		$('.servicePrices:checked').each(function(){
			var cat = $(this).data('cat');
			$('#'+cat).prop('checked',true);
		});
		$('.allCategory').each(function(){
			var totalCat = $(this).data('count');
			var checkedCat = $('.allCategory:checked').length;
			if(totalCat == checkedCat){
				$('#all').prop('checked',true);
			}
		});
	</script>
	<script src="https://cdn.jsdelivr.net/jquery.validation/1.16.0/jquery.validate.min.js"></script>
	<script src="{{ asset('js/add-edit-plans.js') }}" type="text/javascript"></script>

	<script type="text/javascript">
		$(window).on("scroll", function() {  
	        var topHeight = $('.my-custom-header').outerHeight();
	        if ($(this).scrollTop() > topHeight) {
				$('.my-custom-header').addClass("bg-white");
				$('.my-custom-header').addClass("border-bottom"); 
	        } else{ 
	          	$('.my-custom-header').removeClass("bg-white"); 
	          	$('.my-custom-header').removeClass("border-bottom"); 
	        }   
	    }); 
	</script>
@endsection
