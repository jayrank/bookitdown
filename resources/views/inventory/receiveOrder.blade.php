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
	
		<form method="POST" enctype="multipart/form-data" action="{{ route('receiveSaveOrder') }}" id="receiveSaveOrder">
			@csrf
			<input type="hidden" name="order_id" id="order_id" value="{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0 }}">
			<input type="hidden" name="supplier_id" id="supplier_id" value="{{ ($OrderSupplier->id) ? $OrderSupplier->id : 0 }}">
			<input type="hidden" name="location_id" id="location_id" value="{{ ($LocationsData[0]['id']) ? $LocationsData[0]['id'] : 0 }}">
			<div class="my-custom-body-wrapper">
				<div class="my-custom-header">
					<div class="p-4 d-flex justify-content-between border-bottom">
						<h2 class="m-auto font-weight-bolder">Receive Order P{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0 }}</h2>
						<p class="cursor-pointer m-0 px-10 " onclick="history.back();"><i class="text-dark fa fa-times icon-lg"></i>
						</p>
					</div>
				</div>
				<div class="my-custom-body">
					<div class="container">
						<div class="row justify-content-center">
							<div class="col-12 col-md-10 p-2 pr-4">
								<div class="create_order" id="cartItemView">
									<div class="row">
										<div class="col-12 col-md-8">
											<div class="p-10 text-center">
												<div class="cartdesign">
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
															@if(!empty($InventoryOrderItems))
																<?php
																	$i = 1000;
																?>
																@foreach($InventoryOrderItems as $InventoryOrderItem)
																	<?php
																		$i++;
																	?>
															<tr>
																<td class="p-4">
																	{{ ($InventoryOrderItem['product_name']) ? $InventoryOrderItem['product_name'] : '' }}
																</td>
																<td>
																	<input type="hidden" name="cart_item_id[]" value="{{ ($InventoryOrderItem['id']) ? $InventoryOrderItem['id'] : '' }}">
																
																	<input type="hidden" name="cart_item_category_id[]" value="{{ ($InventoryOrderItem['category_id']) ? $InventoryOrderItem['category_id'] : '' }}">
																	<input type="hidden" name="cart_item_ids[]" value="{{ ($InventoryOrderItem['product_id']) ? $InventoryOrderItem['product_id'] : '' }}">
																	
																	<input type="text" name="cart_item_qty[]" id="item_qty_{{ $i }}" class="form-control rounded-0 cart_item_qty" placeholder="0" data-pprice="{{ ($InventoryOrderItem['order_qty']) ? $InventoryOrderItem['order_qty'] : '' }}" data-uid="{{ $i }}" onkeypress="return validQty(event,this.value);" required="required" value="{{ ($InventoryOrderItem['order_qty']) ? $InventoryOrderItem['order_qty'] : '' }}">
																</td>
																<td>
																	<input type="text" name="cart_item_price[]" id="pprice_{{ $i }}" value="{{ ($InventoryOrderItem['supply_price']) ? $InventoryOrderItem['supply_price'] : '' }}" class="form-control rounded-0 all_product_prices" data-uid="{{ $i }}" placeholder="0" onkeypress="return validPrice(event,this.value);" required="">
																</td>
																<td class="p-4">
																	CA $<div id="total_pprice_{{ $i }}">{{ ($InventoryOrderItem['total_cost']) ? $InventoryOrderItem['total_cost'] : '' }}</div>
																	
																	<input type="hidden" class="all_product_total" id="all_product_total{{ $i }}">
																</td>
															</tr>
																@endforeach
															@endif
							                            </tbody>
							                        </table>
												</div>
											</div>
										</div>
										<div class="col-12 col-md-4">
											<div class="card">
												<div class="card-body p-0">
													<div class="p-6">
														<h3 class="font-weight-bolder" id="supplier_name">{{ $OrderSupplier->supplier_name }}</h3>
														<p class="m-0" id="supplier_address">{{ $OrderSupplier->address }}</p>
														<p class="m-0" id="supplier_address">{{ $OrderSupplier->suburb }}</p>
														<p class="m-0" id="supplier_address">{{ $OrderSupplier->city }}</p>
														<p class="m-0" id="supplier_address">{{ $OrderSupplier->state }}</p>
														<p class="m-0" id="supplier_address">{{ $OrderSupplier->zip_code }}</p>
														<p class="m-0" id="supplier_address">{{ $OrderSupplier->country }}</p>
													</div>
													<hr class="m-0" />
													<div class="p-6">
														<h5 class="font-weight-bolder">DELIVER TO</h5>
														<p class="m-0" id="deliver_to">
															@if(count($LocationsData) > 0)
																{{ $LocationsData[0]['location_name'] }} <br>
																{{ $LocationsData[0]['location_address'] }}
															@endif
														</p>
													</div>
													<hr class="m-0" />
													<div class="p-6">
														<div class="d-flex justify-content-between my-4">
															<p class="m-0">Order Total:</p>
															<h5 class="font-weight-bolder">CA $<span id="cart_total">{{ ($InventoryOrders[0]['order_total']) ? $InventoryOrders[0]['order_total'] : 0 }}</span></h5>
															<input type="hidden" name="order_total" id="order_total" value="{{ ($InventoryOrders[0]['order_total']) ? $InventoryOrders[0]['order_total'] : 0 }}">
														</div>
														<button type="submit" class="btn btn-primary w-100">Confirm</button>
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

	</div>
</div>

@endsection
{{-- Scripts Section --}}
@section('scripts')
<script>
	var WEBSITE_URL = "{{ url('partners/') }}";
</script>
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
@endsection