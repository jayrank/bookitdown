{{-- Extends layout --}}
@extends('layouts.fullview')
{{-- CSS Section --}}
@section('innercss')
	
@endsection
@section('content')
<!--begin::Body-->
@if ($errors->any())
	<div class="alert alert-danger">
	    <ul>
	        @foreach ($errors->all() as $error)
	            <li>{{ $error }}</li>
	        @endforeach
	    </ul>
	</div>
@endif
@if ($message = Session::get('success'))
	<div class="alert alert-success alert-block">
	    <button type="button" class="close" data-dismiss="alert">×</button>    
	    <strong>{{ $message }}</strong>
	</div>
@endif
	<div class="container-fluid p-0">
		
		<div class="fullscreen-modal" tabindex="-1" role="dialog" aria-hidden="true"> 
			<div class="my-custom-header text-dark"> 
	            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
	            	<div style="display: flex; justify-content: flex-start; align-items: center;">
			            <a type="button" class="close" data-dismiss="modal" aria-label="Close" style="top:unset;left: 0;opacity: 1;font-size: 1.75rem;" onclick="window.history.back();">
			                <span aria-hidden="true" class="la la-times"></span>
			            </a>
			            <p class="h6 cursor-pointer mb-0 text-blue previous" onclick="nextPrev(-1)"><i class="border-left mx-4"></i>Previous</p>
			        </div>
		            <div style="flex-grow: 1; text-align: center;">
		            	<h6 class="modal-title text-muted title-hide">Steps <span class="steps">1</span> of 2</h6> 
						<h2 class="font-weight-bolder page-title title-hide">Choose your main business type</h2>
		            </div>
		            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
		                <button class="btn btn-lg btn-primary next-step" type="button" onclick="nextPrev(1)">Next Step</button>
		            </div>
		        </div>
	        </div> 
			<div class="border-0 mb-5 text-center firstStep"> 
				<h6 class="modal-title text-muted hide-onscroll" style="flex-grow: 1;">Steps <span class="steps">1</span> of 2</h6> 
				<h1 class="font-weight-bolder mb-5 text-center text-dark page-title hide-onscroll" style="flex-grow: 1;">Choose your main business type</h1>  
			</div>
			<form action="{{ route('editbusinesstypes', $edit_id) }}" method="POST" enctype="multipart/form-data" id="editlocation">
				@csrf 
				<div class="container my-custom-body">	
					<div class="row justify-content-center">
						<div class="col-12 col-md-9">			
							<div class="card my-4"> 
								<input type="hidden" name="editlocationID" value="{{ $edit_id }}">
								<div class="edit-content-tab">
									<ul class="ks-cboxtags">
										@foreach($bus_type as $row)
											<li>
												<input type="radio" name="main_business" id="{{$row->name}}" value="{{$row->name}}" {{ (isset($main->name) && $main->name == $row->name) ? "checked='checked'" : "" }}>
												<label for="{{$row->name}}">
													<div class="m-auto" style="width: 50px;height: 50px;">
														{!!$row->image!!}
													</div>
													{{$row->name}}
												</label>
											</li>
										@endforeach
										
										<input type="submit" id="editlocationsubmit" style="display: none;" value="Sunmit">
									</ul>
									
								</div>
								<div class="edit-content-tab">
									<ul class="ks-cboxtags">
										@foreach($bus_type as $row)
											<li>
												<input type="checkbox" class="multi_checkbox" name="secondary_business[]" id="{{$row->id}}" value="{{$row->id}}" @if(in_array($row->id,$ids,true)) checked="checked" @endif>
												<label for="{{$row->id}}">
													<div class="m-auto" style="width: 50px;height: 50px;">
														{!!$row->image!!}
													</div>
													{{$row->name}}
												</label>
											</li>
										@endforeach
									</ul>
								</div>  
							</div>		
						</div>
					</div>		
				</div>			
			</form>
		</div>
	</div>

	@endsection
	@section('scripts')
	<!--end::Page Scripts-->
	<script src="{{ asset('js/editLocation.js') }}"></script>
	<script>
	var limit = 3;
	$('input.multi_checkbox').on('click', function (evt) {
		if ($('.multi_checkbox:checked').length > limit) {
			this.checked = false;
		}
	});
	</script>
	<script type="text/javascript">
		var WEBSITE_URL = "{{ url('') }}";
	</script>
	<!-- Modal Step Hide Show -->
	<script>
		var currentTab = 0; // Current tab is set to be the first tab (0)
		showTab(currentTab); // Display the current tab

		function showTab(n) 
		{
			// This function will display the specified tab of the form...
			var tab = document.getElementsByClassName("edit-content-tab");
			tab[n].style.display = "block";
			if (n == (tab.length - 1)) {
				$(".previous").show();
				$(".steps").text("2");
				$(".next-step").text("Save");
				//$(".next-step").attr("id", "editlocationsubmit");
			} else {
				//$(".next-step").removeAttr('id');
				$(".previous").hide();
				$(".next-step").text("Next Step");
			}

			if (n == 0) {
				$(".steps").text("1");
				$(".page-title").text("Choose your main business types");
			} else if (n == 1) {
				$(".previous").show();
				$(".steps").text("2");
				$(".page-title").text("Choose your secondary business types");
			} 
		}

		function nextPrev(n) 
		{
			// This function will figure out which tab to display
			var tab = document.getElementsByClassName("edit-content-tab");
			// Hide the current tab:
			console.log("cur = "+currentTab+ " n = "+n);
			tab[currentTab].style.display = "none";	
			// Increase or decrease the current tab by 1:
			currentTab = currentTab + n;
			// if you have reached the end of the tab
			// Otherwise, display the correct tab:
			console.log("current = "+currentTab);
			if(currentTab == 2)
			{
				console.log("submit");
				$("#editlocationsubmit").trigger("click");
				return false;
			} else {	
				
				showTab(currentTab);
			}	
		}

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
	</script>
@endsection