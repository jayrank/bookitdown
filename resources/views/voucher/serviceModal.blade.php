@if(!empty($serviceCategory))
		   @foreach($serviceCategory as $key => $service)
			   <div>
				   @if(!empty($service))
					   @foreach($service as $sKey => $sValue)

					   @if($sKey == 0) 
							   <h4 class="font-weight-bolder mb-4 mt-3 category-header">{{ $sValue->category_title }}</h4>
							   <br>
						   @endif

						   <div class="border-bottom mb-3 pb-2">
							   <span class="title d-flex justify-content-between">
								   <h6 class="font-weight-bolder mb-1">{{ $sValue->service_name }}</h6>
								   <h6 class="text-muted mb-1">{{ ($sValue->price_type == 'from' || ($sValue->is_staff_price == 1)) ? 'From' : '' }}</h6>
							   </span>
							   <span class="title d-flex justify-content-between">
								   <h6 class="text-muted">{{ $sValue->serviceDuration }}</h6>
								 @if($sValue->service_price_special_amount <= 0 && $sValue->price_type == 'free')  
								 <h6 class="font-weight-bolder">Free</h6>
								 @else
								 <h6 class="font-weight-bolder">&#8377; {{ $sValue->service_price_special_amount }}</h6>
								 @endif
							   </span>

							   @if($sValue->service_price_special_amount < $sValue->service_price_amount)
								   <span class="title d-flex justify-content-end"> 
									   <h6 class="text-muted"><strike>&#8377; {{ $sValue->service_price_amount }}</strike></h6>
								   </span>
							   @endif
						   </div>
					   @endforeach
				   @endif
			   </div> 
		   @endforeach
	   @endif