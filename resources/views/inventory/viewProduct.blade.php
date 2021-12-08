{{-- Extends layout --}}
@extends('layouts.index')

{{-- CSS Section --}}
@section('innercss')
<link href="{{ asset('datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
@endsection

{{-- CSS Section --}}
@section('content')
<!--begin::Content-->
<div class="content d-flex flex-column flex-column-fluid" id="kt_content">

	<!-- Modal For stock -->
	<div class="modal fade p-0" id="decreaseStockModal" tabindex="-1" role="dialog"
		aria-labelledby="decreaseStockModalLabel Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			<div class="modal-content ">
				<div class="modal-header">
					<h5 class="modal-title font-weight-bold text-center" id="decreaseStockModalLabel">Decrease Stock
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
					</button>
				</div>
				<form method="POST" enctype="multipart/form-data" action="{{ route('decreaseProductStock') }}" id="decreaseProductStock">
					@csrf
					<input type="hidden" name="outstock_item_id" id="outstock_item_id" value="{{ ($InventoryProducts->id) ? $InventoryProducts->id : 0 }}">
					<input type="hidden" name="outstock_location_id" id="outstock_location_id" value="{{ ($AllLocationData[0]['id']) ? $AllLocationData[0]['id'] : 0 }}">
					<input type="hidden" name="outstock_total_location" id="outstock_total_location" value="{{ count($AllLocationData) }}">
				
					<div class="modal-body">
						<div class="removeLocationOutStockDiv" @if(count($AllLocationData) == 1) style="display:none;" @endif>
							<div class="form">
								<p>Select a location to decrease stock at</p>
								<ul class="list-group">
									@if(count($AllLocationData) > 0)
										@foreach ($AllLocationData as $LocationsData)
											<li type="button" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action selectOutStockLocation" data-location_id="{{ $LocationsData['id'] }}" data-in_stock="{{ $LocationsData['in_stock'] }}" data-location_name="{{ $LocationsData['location_name'] }}"> {{ $LocationsData['location_name'] }} <br>{{ $LocationsData['in_stock'] }} in stock <i class="fa fa-chevron-right"></i>
											</li>
										@endforeach
									@else
										<li type="button" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">No locations found. </li>	
									@endif
								</ul>
							</div>
						</div>
						<div class="removeOutStockDiv" @if(count($AllLocationData) == 1) style="display:block;" @else style="display:none;" @endif>
							<p class=""><span class="selectLocOutQty">{{ ($AllLocationData[0]['in_stock']) ? $AllLocationData[0]['in_stock'] : 0 }}</span> units currently in stock at <span class="selectLocOutName">{{ ($AllLocationData[0]['location_name']) ? $AllLocationData[0]['location_name'] : 0 }}</span>.</p>
							<div class="form-group w-100">
								<label>Decrease qty.</label>
								<input type="text" class="form-control" placeholder="0" value="0" min="1" name="decrease_stock_qty" id="decrease_stock_qty" onkeypress="return validQty(event,this.value);" required>
							</div>
							<div class="form-group w-100">
								<label>Decrease reason</label>
								<select class="form-control" name="out_order_action" id="out_order_action">
									<option value="Internal Use" selected="">Internal use</option>
									<option value="Damaged">Damaged</option>
									<option value="Out Of Date">Out of date</option>
									<option value="Adjustment">Adjustment</option>
									<option value="Lost">Lost</option>
									<option value="Other">Other</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<div class="ml-auto">
							<button type=" button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary font-weight-bold" id="decreaseProductStockBtn">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<div class="modal fade p-0" id="increaseStockModal" tabindex="-1" role="dialog"
		aria-labelledby="increaseStockModalLabel Label" aria-modal="true">
		<div class="modal-dialog" role="document">
			<div class="modal-content ">
				<div class="modal-header">
					<h5 class="modal-title font-weight-bold text-center" id="increaseStockModalLabel">Increase Stock
					</h5>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
					</button>
				</div>
				<form method="POST" enctype="multipart/form-data" action="{{ route('increaseProductStock') }}" id="increaseProductStock">
					@csrf
					<input type="hidden" name="instock_item_id" id="instock_item_id" value="{{ ($InventoryProducts->id) ? $InventoryProducts->id : 0 }}">
					<input type="hidden" name="instock_location_id" id="instock_location_id" value="{{ ($AllLocationData[0]['id']) ? $AllLocationData[0]['id'] : 0 }}">
					<input type="hidden" name="instock_total_location" id="instock_total_location" value="{{ count($AllLocationData) }}">
				
					<div class="modal-body">
						<div class="addLocationStockDiv" @if(count($AllLocationData) == 1) style="display:none;" @endif>
							<div class="form">
								<p>Select a location to increase stock at</p>
								<ul class="list-group">
									@if(count($AllLocationData) > 0)
										@foreach ($AllLocationData as $LocationsData)
											<li type="button" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action selectInStockLocation" data-location_id="{{ $LocationsData['id'] }}" data-in_stock="{{ $LocationsData['in_stock'] }}" data-location_name="{{ $LocationsData['location_name'] }}"> {{ $LocationsData['location_name'] }} <br>{{ $LocationsData['in_stock'] }} in stock <i class="fa fa-chevron-right"></i>
											</li>
										@endforeach
									@else
										<li type="button" class="d-flex justify-content-between align-items-center font-weight-bolder list-group-item list-group-item-action">No locations found. </li>	
									@endif
								</ul>
							</div>	
						</div>
						<div class="addStockDiv" @if(count($AllLocationData) == 1) style="display:block;" @else style="display:none;" @endif>
							<p class=""><span class="selectLocQty">{{ ($AllLocationData[0]['in_stock']) ? $AllLocationData[0]['in_stock'] : 0 }}</span> units currently in stock at <span class="selectLocName">{{ ($AllLocationData[0]['location_name']) ? $AllLocationData[0]['location_name'] : 0 }}</span>.</p>
							<div class="form-group w-100">
								<label>Increase qty.</label>
								<input type="number" class="form-control" placeholder="0" value="0" min="1" name="increase_stock_qty" id="increase_stock_qty" onkeypress="return validQty(event,this.value);" required>
							</div>
							<div class="form-group w-100">
								<label>Supply price</label>
								<input type="text" class="form-control" placeholder="0" value="{{ ($InventoryProducts->supply_price) ? $InventoryProducts->supply_price : 0 }}" data-oldSupplyPrice="{{ ($InventoryProducts->supply_price) ? $InventoryProducts->supply_price : 0 }}" name="increase_stock_price" id="increase_stock_price" onkeypress="return validPrice(event,this.value);">
							</div>
							<div class="form-group">
								<div class="checkbox-list">
									<label class="checkbox">
										<input type="checkbox" id="isPostalSame" name="save_stock_item_price">
										<span></span>Save price for next time
									</label>
								</div>
							</div>
							<div class="form-group w-100">
								<label>Increase reason</label>
								<select class="form-control" name="order_action" id="order_action">
									<option value="New Stock" selected="">New Stock</option>
									<option value="Return">Return</option>
									<option value="Transfer">Transfer</option>
									<option value="Adjustment">Adjustment</option>
									<option value="Other">Other</option>
								</select>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<div class="ml-auto">
							<button type="button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary font-weight-bold" id="increseProductStockBtn">Save</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>

	<!--begin::Tabs-->
	<div class="subheader pt-2 pt-lg-4 subheader-solid" id="kt_subheader">
		<div class="offcanvas-header offcanvas-header-navs d-flex align-items-center justify-content-between mb-1">
			<ul class="border-0 nav nav-tabs nav-tabs-line nav-tabs-primary flex-grow-1 px-5"
				role="tablist">
				<li class="nav-item"><a class="nav-link active" href="{{ route('inventory') }}">Products</a></li>
				<li class="nav-item"><a class="nav-link" href="{{ route('orders') }}">Orders</a></li>
				<li class="nav-item"><a class="nav-link" href="{{ route('brands') }}">Brands</a></li>
				<li class="nav-item"><a class="nav-link" href="{{ route('categories') }}">Categories</a></li>
				<li class="nav-item"><a class="nav-link" href="{{ route('suppliers') }}">Suppliers</a></li>
			</ul>
		</div>
	</div>
	<!--end::Tabs-->
	<!--begin::Entry-->
	<div class="d-flex flex-column-fluid">
		<!--begin::Container-->
		<div class="container">
			<!--begin::nventory-->
			<!--begin::Row-->
			<div class="content-header">
				<div class="ifProductDetailView">
					<div class="d-flex justify-content-between">
						<div class="calender-div my-auto">
							<a href="{{ route('inventory') }}" class="cursor-pointer text-blue"><i class="text-blue fa fa-chevron-left icon-sm"></i> Product List</a>
						</div>
						<div class="action-btn-div mt-4">
							@if($InventoryProducts->enable_stock_control == 1)
								<button data-toggle="modal" data-target="#decreaseStockModal" class="font-weight-bold btn btn-white">Stock <i class="fa fa-minus icon-sm"></i>
								</button>
								<button data-toggle="modal" data-target="#increaseStockModal" class="font-weight-bold btn btn-white">Stock <i class="fa fa-plus icon-sm"></i>
								</button>	
							@else
								<button class="font-weight-bold btn btn-white unlimitedStockError">Stock <i class="fa fa-minus icon-sm"></i>
								</button>
								<button class="font-weight-bold btn btn-white unlimitedStockError">Stock <i class="fa fa-plus icon-sm"></i>
								</button>
							@endif
							<a class="font-weight-bold btn btn-primary" href="{{ route('editProductItem',['id' => $InventoryProducts->id]) }}">Edit Product</a>
						</div>
					</div>
				</div>
			</div>
			<div class="row my-4">
				<div class="col-lg-12 col-xxl-12 order-1 order-xxl-12">
					<div class="ifProductDetailView">
						<div class="card card-custom card-stretch gutter-b p-4">
							<input type="hidden" name="item_id" id="item_id" value="{{ ($InventoryProducts->id) ? $InventoryProducts->id : '0' }}">
						
							<h3 class="font-weight-bolder my-3">{{ ($InventoryProducts->product_name) ? $InventoryProducts->product_name : 'N/A' }}</h3>
							<div class="prod-detail d-flex flex-wrap justify-content-between">
								<div class="detail">
									<div class="title">Barcode</div>
									<div class="value">{{ ($InventoryProducts->barcode) ? $InventoryProducts->barcode : 'N/A' }}</div>
								</div>
								<div class="detail">
									<div class="title">Total On Hand</div>
									<div class="value">
										@if($InventoryProducts->enable_stock_control == 0)
											Unlimited
										@else
											{{ ($InventoryProducts->initial_stock) ? $InventoryProducts->initial_stock : '0' }}
										@endif
									</div>
								</div>
								<div class="detail">
									<div class="title">Brand</div>
									<div class="value">{{ ($InventoryProducts->brand_name) ? $InventoryProducts->brand_name : 'N/A' }}</div>
								</div>
								<div class="detail">
									<div class="title">SKU</div>
									<div class="value">{{ ($InventoryProducts->sku) ? $InventoryProducts->sku : 'N/A' }}</div>
								</div>
								<div class="detail">
									<div class="title">Total Stock Cost</div>
									<div class="value">CA ${{ ($TotalStockCost) ? number_format($TotalStockCost,2) : 0 }}</div>
								</div>
								<div class="detail">
									<div class="title">Category</div>
									<div class="value">{{ ($InventoryProducts->category_name) ? $InventoryProducts->category_name : 'N/A' }}</div>
								</div>
								<div class="detail">
									<div class="title">Retail Price</div>
									@if($InventoryProducts->special_rate != "")
										<div class="value"><s>CA ${{ ($InventoryProducts->retail_price) ? $InventoryProducts->retail_price : '0' }}</s><br>
											<span class="text-danger">CA ${{ ($InventoryProducts->special_rate) ? $InventoryProducts->special_rate : '0' }}</span>
										</div>
									@else 
										<div class="value">CA ${{ ($InventoryProducts->retail_price) ? $InventoryProducts->retail_price : '0' }}</div>
									@endif
								</div>
								<div class="detail">
									<div class="title">Avg. Stock Cost</div>
									<div class="value">CA ${{ ($AvgStockCost) ? $AvgStockCost : 0 }}</div>
								</div>
								<div class="detail">
									<div class="title">Supplier</div>
									<div class="value">{{ (!empty($ProductSupplier[0]->supplier_name)) ? $ProductSupplier[0]->supplier_name : 'N/A' }}</div>
								</div>
								<div class="detail">
									<div class="title">Supply Price</div>
									<div class="value">CA ${{ ($InventoryProducts->supply_price) ? $InventoryProducts->supply_price : '0' }}</div>
								</div>
							</div>
						</div>
						<div class="card card-custom card-stretch gutter-b p-4">
							<div class="d-flex justify-content-between mb-4">
								<h3 class="font-weigh-bolder my-2">Stock History</h3>
								<div class="dropdown dropdown-inline mr-2">
									<button type="button"
										class="btn btn-sm btn-white font-weight-bolder dropdown-toggle"
										data-toggle="dropdown" aria-haspopup="true"
										aria-expanded="false">
										<span class="svg-icon svg-icon-md">
											<!--begin::Svg Icon | path:assets/media/svg/icons/Design/PenAndRuller.svg-->
											<svg xmlns="http://www.w3.org/2000/svg">
												<g>
													<path
														d="M15.072 9.62c.506 0 .911.405.911.912v4.962a.908.908 0 0 1-.911.911H.962c-.506 0-.945-.405-.945-.911v-4.962c0-.507.439-.912.945-.912s.911.405.911.912v4.017H14.16v-4.017c0-.507.405-.912.912-.912z">
													</path>
													<path
														d="M7.376 11.68L3.662 7.965a.878.878 0 0 1 0-1.282.878.878 0 0 1 1.283 0l2.16 2.126V.911c0-.506.406-.911.912-.911s.911.405.911.911v7.9l2.127-2.127a.917.917 0 0 1 1.316 0 .878.878 0 0 1 0 1.282L8.658 11.68a.922.922 0 0 1-.641.27.922.922 0 0 1-.641-.27z">
													</path>
												</g>
											</svg>
											<!--end::Svg Icon-->
										</span>Export</button>
									<!--begin::Dropdown Menu-->
									<div class="dropdown-menu dropdown-menu-xs">
										<!--begin::Navigation-->
										<ul class="navi flex-column navi-hover py-2">
											<li class="navi-item">
												<a href="{{ route('StockHistoryexcel',($InventoryProducts->id) ? $InventoryProducts->id : '0') }}" class="navi-link">
													<span class="navi-icon">
														<i class="la la-file-excel-o"></i>
													</span>
													<span class="navi-text">Excel</span>
												</a>
											</li>
											<li class="navi-item">
												<a href="{{ route('StockHistorycsv',($InventoryProducts->id) ? $InventoryProducts->id : '0') }}" class="navi-link">
													<span class="navi-icon">
														<i class="la la-file-text-o"></i>
													</span>
													<span class="navi-text">CSV</span>
												</a>
											</li>
											<li class="navi-item">
												<a href="#" class="navi-link">
													<span class="navi-icon">
														<i class="la la-file-pdf-o"></i>
													</span>
													<span class="navi-text">PDF</span>
												</a>
											</li>
										</ul>
										<!--end::Navigation-->
									</div>
									<!--end::Dropdown Menu-->
								</div>
							</div>
							<div class="table-responsive">
								<table class="table" id="productStockLogs">
									<thead>
										<tr>
											<th class="tex-center">Time & Date</th>
											<th class="tex-center">Staff</th>
											<th class="tex-center">Location</th>
											<th class="tex-center">Action</th>
											<th class="tex-right">Qty. Adjusted</th>
											<th class="tex-right">Cost Price</th>
											<th class="tex-right">Stock On Hand</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
					<!--end::List Widget 3-->
				</div>
			</div>
			<!--end::Row-->
			<!--end::nventory-->
		</div>
		<!--end::Container-->
	</div>
	<!--end::Entry-->
	
</div>
<!--end::Content-->
@endsection
{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('datatables/datatables.bundle.js') }}"></script>
<script src="{{ asset('js/viewOrder.js') }}"></script>
<script src="{{ asset('js/application.js') }}"></script>
<script>
	$(function() {
		var table = $('#productStockLogs').DataTable({
			processing: true,
			serverSide: true,
			"ordering": true,
			"info":     false,
			"searching": false,
			"bLengthChange": false,
			"info":     true,
			ajax: {
				type: "POST",
				url : "{{ route('getInventoryOrderLogs') }}",
				data: {_token : "{{csrf_token()}}",item_id : $("#item_id").val()}
			},
			columns: [
				{data: 'created_at', profile: 'created_at'},
				{data: 'received_by', name: 'received_by'},
				{data: 'location_name', name: 'location_name'},
				{data: 'order_id', name: 'order_id'},
				{data: 'qty_adjusted', name: 'qty_adjusted'},
				{data: 'cost_price', name: 'cost_price'},
				{data: 'stock_on_hand', name: 'stock_on_hand'},
			]			
		});	
	});
	
	$(document).on('click','.editProduct',function(){
		var thisID = $(this).data('id');
		var url = "{{ url('editProduct') }}";
		if(thisID != "" && typeof thisID !== "undefined") {
			window.location = url+'/'+thisID;
		}
	});
	
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
		
		var current_value = boxValue;
		var old_value     = $("#increase_stock_price").attr('data-oldSupplyPrice');
		
		if(current_value != old_value){
			$("#isPostalSame").prop('checked',true);
		}
		
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

@if(Session::has('message'))
<script>
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

	toastr.success('{{ Session::get('message') }}');
</script>
@endif

@endsection