{{-- Extends layout --}}
@extends('layouts.fullview')

{{-- CSS Section --}}
@section('innercss')

@endsection
@section('content')
<!--begin::Tabs-->
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
	<div class="my-custom-body-wrapper">	
	
		<form method="POST" enctype="multipart/form-data" action="{{ route('saveOrder') }}" id="saveOrder">
			@csrf
			<input type="hidden" name="TotalLocations" id="TotalLocations" value="{{ $TotalLocations }}">
			<input type="hidden" name="supplier_id" id="supplier_id" value="">
			<input type="hidden" name="location_id" id="location_id" value="{{ ($LocationArray[0]['id']) ? $LocationArray[0]['id'] : 0 }}">
			<input type="hidden" name="category_id" id="category_id" value="">
			<input type="hidden" name="currentModalTab" id="currentModalTab" value="0">
			<div class="my-custom-body-wrapper">
				<!-- <div class="my-custom-header">
					<div class="p-4 d-flex justify-content-between border-bottom">
						<h2 class="m-auto font-weight-bolder">Create Order</h2>
						<p class="cursor-pointer m-0 px-10 " onclick="history.back();"><i class="text-dark fa fa-times icon-lg"></i>
						</p>
					</div>
				</div> -->
				<div class="my-custom-header sticky-top text-dark bg-white"> 
		            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
		            	<span class="d-flex"></span>
			            <div style="flex-grow: 1;">
							<h1 class="font-weight-bolder page-title">Create Order</h1>
			            </div>
			            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
			                <a type="button" class="close" onclick="history.back();" style="display: flex; justify-content: flex-start; align-items: center;top:unset;left: 0;opacity: 1;font-size: 1.75rem;">
				                <span aria-hidden="true" class="la la-times"></span>
				            </a>
			            </div>
			        </div>
		        </div> 
				<div class="my-custom-body">
					<div class="container">
						<div class="row justify-content-center">
							<div class="col-12 col-md-10 p-2 pr-4">
							
								<div class="create-order-tab w-50 m-auto select_supplier">
									<h3 class="my-6 p-0 font-weight-bolder">Select Supplier</h3>
									<div class="form">
										<p>Select a supplier to order product stock from</p>
										<ul class="list-group">
											@if(count($InventorySupplier) > 0)
												@foreach ($InventorySupplier as $InventorySupplierData)
													<li type="button" onclick="nextPrev(1)" data-supplier_id="{{ $InventorySupplierData->id }}" data-supplier_name="{{ $InventorySupplierData->supplier_name }}" data-address="{{ $InventorySupplierData->address }}" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action selectSupplier"> {{ $InventorySupplierData->supplier_name }} <i class="fa fa-chevron-right"></i>
													</li>
												@endforeach
											@else
												<li type="button" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action"> No supplier found. </li>
											@endif
										</ul>
									</div>
								</div>
								<div class="create-order-tab w-50 m-auto select_target_location">
									<h3 class="my-6 p-0 font-weight-bolder">Select Location</h3>
									<div class="form">
										<p>Select the location to deliver to</p>
										<ul class="list-group">
											@if(count($LocationArray) > 0)
												@foreach ($LocationArray as $LocationsData)
													<li type="button" onclick="nextPrev(1)" data-location_id="{{ $LocationsData['id'] }}" data-location_name="{{ $LocationsData['location_name'] }}" data-location_address="{{ $LocationsData['location_address'] }}" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action selectLocation"> {{ $LocationsData['location_name'] }} <i class="fa fa-chevron-right"></i>
													</li>
												@endforeach
											@else
												<li type="button" onclick="nextPrev(1)" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">No locations found. </li>	
											@endif
										</ul>
									</div>
								</div>
								<div class="create-order-tab create_order" id="cartItemView">
									<div class="row">
										<div class="col-12 col-md-8">
											<div class="p-10 text-center">
												<div class="emtyCartMessage">
													<h3>You haven t added any product to your order yet.</h3>
												</div>
												
												<div class="cartdesign" style="display:none;">
													<table class="table" id="productCartList">
							                            <thead>
							                                <tr>
							                                    <th>Product</th>
							                                    <th>Order Qty.</th>
							                                    <th>Supply Price</th>
							                                    <th>Total Cost</th>
							                                </tr>
							                            </thead>
							                            <tbody id="cartTableData">
							                            </tbody>
							                        </table>
												</div>
												
												<button type="button" data-toggle="modal" data-target="#addProductModal" class="my-6 text-center btn btn-md btn-white px-10" id="addProductBtn"><i class="fa fa-plus"></i> Add Product</button>
											</div>
										</div>
										<div class="col-12 col-md-4">
											<div class="card">
												<div class="card-body p-0">
													<div class="p-6">
														<h3 class="font-weight-bolder" id="supplier_name"></h3>
														<p class="m-0" id="supplier_address"></p>
													</div>
													<hr class="m-0" />
													<div class="p-6">
														<h5 class="font-weight-bolder">DELIVER TO</h5>
														<p class="m-0" id="deliver_to">
															@if(count($LocationArray) > 0)
																{{ $LocationArray[0]['location_name'] }} <br>
																{{ $LocationArray[0]['location_address'] }}
															@endif
														</p>
													</div>
													<hr class="m-0" />
													<div class="p-6">
														<div class="d-flex justify-content-between my-4">
															<p class="m-0">Order Total:</p>
															<h5 class="font-weight-bolder">CA $<div id="cart_total">0.00</div></h5>
															<input type="hidden" name="order_total" id="order_total" value="0">
														</div>
														<button type="button" id="createOrder" class="btn btn-primary w-100">Create Order</button>
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
		</form>

		<div class="modal" id="addProductModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<!-- Modal Header -->
					<div class="modal-header d-flex justify-content-between">
						<a class="text-dark modal-back cursor-pointer clickOnPreviousTab" onclick="nextPrevModal(-1)"><i class="fa fa-chevron-left icon-lg"></i></a>
						<h4 class="modal-title">Select Product</h4>
						<button type="button" class="text-dark close" data-dismiss="modal">&times;</button>
					</div>
					<!-- Modal body -->
					<div class="modal-body productModalLists">
						<div class="modal-tab productCategoryList">
							<!-- <div class="form-group">
								<div class="input-icon input-icon-right">
									<input type="text" class="rounded-0 form-control" placeholder="Scan barcode or search any item" id="searchCategory">
									<span> <i class="flaticon2-search-1 icon-md"></i> </span>
								</div>
							</div> -->
							<ul class="list-group" id="supplierCategory">
								@if(count($InventoryCategory) > 0)
									@foreach ($InventoryCategory as $InventoryCategoryData)
										<li type="button" onclick="nextPrevModal(1)" data-category_id="{{ $InventoryCategoryData->id }}" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action selectProductCategory"> {{ $InventoryCategoryData->category_name }} <i class="fa fa-chevron-right"></i>
										</li>
									@endforeach
									<li type="button" onclick="nextPrevModal(1)" data-category_id="0" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action selectProductCategory"> No Category <i class="fa fa-chevron-right"></i> </li>
								@else
									<li type="button" onclick="nextPrevModal(1)" data-category_id="0" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action selectProductCategory"> No Category <i class="fa fa-chevron-right"></i> </li>
								@endif
							</ul>
						</div>
						<div class="modal-tab productList">
							<!-- <div class="form-group">
								<div class="input-icon input-icon-right">
									<input type="text" class="rounded-0 form-control" placeholder="Scan barcode or search any item" id="searchProduct">
									<span> <i class="flaticon2-search-1 icon-md"></i></span>
								</div>
							</div> -->
							<ul class="list-group" id="supplierProducts">
								
							</ul>
						</div>
					</div>
					<!-- Modal footer -->
					<div class="modal-footer">
						<button type="button" class="btn btn-danger" data-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
{{-- Scripts Section --}}
@section('scripts')
<script>
	var WEBSITE_URL = "{{ url('partners/') }}";
</script>
<script src="{{ asset('js/addProduct.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>

<script type="text/javascript">
	window.validQty = function(checkDigit, boxValue) {
		var charCode = (checkDigit.which) ? checkDigit.which : checkDigit.keyCode;
		
		if(boxValue.length > 100) {
			return false;
		} else {
			if(charCode >31 && (charCode <48 || charCode >57)) {
				return false;	
			}
			return true;
		}
	}
	
	window.validPrice = function(checkDigit, boxValue) {
		var charCode = (checkDigit.which) ? checkDigit.which : checkDigit.keyCode;
		
		if(boxValue.length > 100) {
			return false;
		} else {
			if(charCode >31 && (charCode <48 || charCode >57) && charCode != 46) {
				return false;	
			}
			return true;
		}
	}
	
</script>
<script>
	var currentTab = 0; // Current tab is set to be the first tab (0)
	showTab(currentTab); // Display the current tab

	function showTab(n) {
		// This function will display the specified tab of the form...
		var x = document.getElementsByClassName("create-order-tab");
		x[n].style.display = "block";
	}

	function nextPrev(n) {
		var TotalLocations = $("#TotalLocations").val();
		
		// This function will figure out which tab to display
		var x = document.getElementsByClassName("create-order-tab");
		// Hide the current tab:
		x[currentTab].style.display = "none";
		// Increase or decrease the current tab by 1:
		if(TotalLocations <= 1){
			currentTab = currentTab + n + 1;
		} else {
			currentTab = currentTab + n;
		}
		
		// Otherwise, display the correct tab:
		showTab(currentTab);
	}
</script>

<!-- Modal Step Hide Show -->
<script>
	var currentModalTab = parseInt($("#currentModalTab").val()); // Current tab is set to be the first tab (0)
	showModalTab(currentModalTab); // Display the current tab

	function showModalTab(n) {
		// This function will display the specified tab of the form...
		var modalTab = document.getElementsByClassName("modal-tab");
		
		modalTab[n].style.display = "block";
		if (n == (modalTab.length - 1)) {
			$(".modal-back").show();
		} else {
			$(".modal-back").hide();
		}
	}

	function nextPrevModal(n) {
		// This function will figure out which tab to display
		var modalTab = document.getElementsByClassName("modal-tab");
		// Hide the current tab:	
		
		var c = parseInt($("#currentModalTab").val());
		
		modalTab[c].style.display = "none";
		// Increase or decrease the current tab by 1:
		currentModalTab = c + n;
		$("#currentModalTab").val(currentModalTab);
		// if you have reached the end of the tab
		// Otherwise, display the correct tab:
		showModalTab(currentModalTab);
	}
</script>
@endsection