{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss')

</style>
@endsection
@section('content')
<!-- <div class="container-fluid p-0">
	<div class="my-custom-body-wrapper bg-content">		
		<form method="POST" id="changeLocationOrder" action="{{ route('updateLocationOrder') }}">
			@csrf
			<div class="my-custom-header">
				<div class="p-4 d-flex justify-content-between align-items-center border-bottom">
					<span class="d-flex">
						<p class="cursor-pointer m-0 px-6" onclick="history.back();"><i
								class="text-dark fa fa-times icon-lg"></i>
						</p>
					</span>
					<span class="text-center">
						<h1 class="font-weight-bolder page-title">Edit the order</h1>
						<p>Drag and drop your business locations to change the order in which they appear.</p>
					</span>
					<div>
						<button class="btn btn-lg btn-primary next-step" type="submit">Save</button>
					</div>
				</div>
			</div>
			<div class="my-custom-body">
				<div class="container-fluid">
					<div class="row justify-content-center">
						<div class="col-md-8 m-4 p-4">
							<ul class="sortable p-0 collapse show multi-collapse my-2" id="sortable" style="list-style: none;">
								@if(!empty($Locations))
									@foreach($Locations as $LocationsData)
								<li>
									<div class="card mb-3 shadow-sm">
										<div class="d-flex flex-wrap align-items-center">
											<div class="mx-4">
												<i class="fa fa-bars"></i>
											</div>
											<div class="p-3 text-center" style="width: 70px;height: 70px;">
												<svg viewBox="0 0 25 23" xmlns="http://www.w3.org/2000/svg">
													<path d="M9.75 20.75V15.5a.75.75 0 0 1 .75-.75h4a.75.75 0 0 1 .75.75v5.25h4.5V12.5a.75.75 0 1 1 1.5 0v9a.75.75 0 0 1-.75.75h-16a.75.75 0 0 1-.75-.75v-9a.75.75 0 1 1 1.5 0v8.25h4.5zm1.5 0h2.5v-4.5h-2.5v4.5zm-1.416-10.5a3.727 3.727 0 0 1-2.669-1.124A3.743 3.743 0 0 1 .75 6.498a.75.75 0 0 1 .107-.384l3-5A.75.75 0 0 1 4.5.75h16a.75.75 0 0 1 .643.364l3 5a.75.75 0 0 1 .107.384 3.742 3.742 0 0 1-6.415 2.628 3.727 3.727 0 0 1-5.335.001 3.727 3.727 0 0 1-2.666 1.123zm-4.91-8L2.259 6.695a2.242 2.242 0 0 0 4.238.816.75.75 0 0 1 1.343.003 2.227 2.227 0 0 0 3.99 0 .75.75 0 0 1 1.343 0 2.227 2.227 0 0 0 3.99 0 .75.75 0 0 1 1.342-.003 2.242 2.242 0 0 0 4.238-.816L20.075 2.25H4.925z">
													</path>
												</svg>
											</div>
											<div class="mx-3">
												<input type="hidden" name="location_id[]" value="{{ ($LocationsData['id']) ? $LocationsData['id'] : '' }}">
												<h4 class="card-title m-0 font-weight-bolder">{{ ($LocationsData['location_name']) ? $LocationsData['location_name'] : '' }}</h4>
												<p class="card-text text-muted">{{ ($LocationsData['location_address']) ? $LocationsData['location_address'] : '' }}</p>
											</div>
										</div>
									</div>
								</li>
									@endforeach
								@endif
							</ul>
						</div>
					</div>
				</div>
			</div>
		</form>
	</div>
</div> -->
	<div class="container-fluid p-0">		
		<div class="fullscreen-modal" tabindex="-1" role="dialog" aria-hidden="true"> 
			<form method="POST" id="changeLocationOrder" action="{{ route('updateLocationOrder') }}">
				@csrf 
				<div class="my-custom-header text-dark"> 
		            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
		            	<div style="display: flex; justify-content: flex-start; align-items: center;">
				            <a type="button" class="close" data-dismiss="modal" aria-label="Close" style="top:unset;left: 0;opacity: 1;font-size: 1.75rem;" onclick="window.history.back();">
				                <span aria-hidden="true" class="la la-times"></span>
				            </a>
				        </div>
			            <div style="flex-grow: 1; text-align: center;">
							<h2 class="font-weight-bolder page-title title-hide">Edit the order</h2>
			            </div>
			            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
			                <button class="btn btn-lg btn-primary next-step" type="submit">Save</button>
			            </div>
			        </div>
		        </div> 
				<div class="border-0 mb-5 text-center firstStep"> 
					<h1 class="font-weight-bolder mb-5 text-center text-dark page-title hide-onscroll" style="flex-grow: 1;">Edit the order</h1>  
					<h6 class="modal-title text-muted hide-onscroll" style="flex-grow: 1;">Drag and drop your business locations to change the order in which they appear.</h6> 
				</div>			
				<div class="container my-custom-body">	
					<div class="row justify-content-center">
						<div class="col-md-9 m-4 p-4">
							<ul class="sortable p-0 collapse show multi-collapse my-2" id="sortable" style="list-style: none;">
								@if(!empty($Locations))
									@foreach($Locations as $LocationsData)
								<li>
									<div class="card mb-3 shadow-sm">
										<div class="d-flex flex-wrap align-items-center px-2 py-1">
											<div class="mx-4">
												<i class="fa fa-bars"></i>
											</div>
											<div class="p-3 text-center" style="width: 70px;height: 70px;">
												<svg viewBox="0 0 25 23" xmlns="http://www.w3.org/2000/svg">
													<path d="M9.75 20.75V15.5a.75.75 0 0 1 .75-.75h4a.75.75 0 0 1 .75.75v5.25h4.5V12.5a.75.75 0 1 1 1.5 0v9a.75.75 0 0 1-.75.75h-16a.75.75 0 0 1-.75-.75v-9a.75.75 0 1 1 1.5 0v8.25h4.5zm1.5 0h2.5v-4.5h-2.5v4.5zm-1.416-10.5a3.727 3.727 0 0 1-2.669-1.124A3.743 3.743 0 0 1 .75 6.498a.75.75 0 0 1 .107-.384l3-5A.75.75 0 0 1 4.5.75h16a.75.75 0 0 1 .643.364l3 5a.75.75 0 0 1 .107.384 3.742 3.742 0 0 1-6.415 2.628 3.727 3.727 0 0 1-5.335.001 3.727 3.727 0 0 1-2.666 1.123zm-4.91-8L2.259 6.695a2.242 2.242 0 0 0 4.238.816.75.75 0 0 1 1.343.003 2.227 2.227 0 0 0 3.99 0 .75.75 0 0 1 1.343 0 2.227 2.227 0 0 0 3.99 0 .75.75 0 0 1 1.342-.003 2.242 2.242 0 0 0 4.238-.816L20.075 2.25H4.925z">
													</path>
												</svg>
											</div>
											<div class="mx-3">
												<input type="hidden" name="location_id[]" value="{{ ($LocationsData['id']) ? $LocationsData['id'] : '' }}">
												<h4 class="card-title m-0 font-weight-bolder">{{ ($LocationsData['location_name']) ? $LocationsData['location_name'] : '' }}</h4>
												<p class="card-text text-muted">{{ ($LocationsData['location_address']) ? $LocationsData['location_address'] : '' }}</p>
											</div>
										</div>
									</div>
								</li>
									@endforeach
								@endif
							</ul>
						</div>
					</div>	
				</div>			
			</form>
		</div>
	</div>
@endsection
@section('scripts')
<script src="{{ asset('assets/js/pages/widgets.js') }}"></script>
<script src="{{ asset('assets/js/jquery-ui.js') }}"></script>
<script>
	$(function () {
		$(".sortable").sortable({
			placeholder: 'placeholder',
			forcePlaceholderSize: true,
			tolerance: 'pointer',
			revert: true,
		});
		$("ul, li").disableSelection();
		$(window).on("scroll", function() {   
	        var topHeight = $('.my-custom-header').outerHeight(); 
	        if ($(this).scrollTop() > topHeight) {
				$('.my-custom-header').addClass("bg-white");
				$('.my-custom-header').addClass("border-bottom");  
				$('.hide-onscroll').css('opacity','0');
	        } else{ 
	          	$('.my-custom-header').removeClass("bg-white"); 
	          	$('.my-custom-header').removeClass("border-bottom");  
	          	$('.hide-onscroll').css('opacity','0.999');
	        }   
	    }); 
	});
</script>
@endsection