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
<?php
	$id = "";
	$product_name = "";
	$category_id = "";
	$brand_id = "";
	$enable_retail_sale = 1;
	$retail_price = "";
	$special_rate = "";
	$tax_id = "";
	$enable_commission = 0;
	$barcode = "";
	$sku = "";
	$description = "";
	$enable_stock_control = 1;
	$supply_price = "";
	$initial_stock = "";
	$supplier_id = "";
	$reorder_point = "";
	$reorder_qty = "";
	
	if(!empty($InventoryProducts))
	{
		$id                   = $InventoryProducts->id;
		$product_name         = ($InventoryProducts->product_name) ? $InventoryProducts->product_name : '';
		$category_id          = ($InventoryProducts->category_id) ? $InventoryProducts->category_id : '';
		$brand_id             = ($InventoryProducts->brand_id) ? $InventoryProducts->brand_id : '';
		$enable_retail_sale   = ($InventoryProducts->enable_retail_sale) ? 1 : 0;
		$retail_price         = ($InventoryProducts->retail_price) ? $InventoryProducts->retail_price : '';
		$special_rate         = ($InventoryProducts->special_rate) ? $InventoryProducts->special_rate : '';
		$tax_id               = ($InventoryProducts->tax_id) ? $InventoryProducts->tax_id : '';
		$enable_commission    = ($InventoryProducts->enable_commission) ? 1 : 0;
		$barcode              = ($InventoryProducts->barcode) ? $InventoryProducts->barcode : '';
		$sku                  = ($InventoryProducts->sku) ? $InventoryProducts->sku : '';
		$description          = ($InventoryProducts->description) ? $InventoryProducts->description : '';
		$enable_stock_control = ($InventoryProducts->enable_stock_control) ? 1 : 0;
		$supply_price         = ($InventoryProducts->supply_price) ? $InventoryProducts->supply_price : '';
		$initial_stock        = ($InventoryProducts->initial_stock) ? $InventoryProducts->initial_stock : '';
		$supplier_id          = ($InventoryProducts->supplier_id) ? $InventoryProducts->supplier_id : '';
		$reorder_point        = ($InventoryProducts->reorder_point) ? $InventoryProducts->reorder_point : '';
		$reorder_qty          = ($InventoryProducts->reorder_qty) ? $InventoryProducts->reorder_qty : '';
	}
	
?>
<div class="container-fluid p-0">
	<div class="modal fade p-0" id="deleteProductModal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			{{ Form::open(array('url' => '','id' => 'delProduct')) }}
				<input type="hidden" name="deleteID" id="deleteID" value="{{ $id }}">
				<div class="modal-content ">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center">Delete Product</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<div class="modal-body">
						<div class="container">
							<div class="row">
								<p>Are you sure you want to delete this product?</p>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<button type="button" id="deleteProduct" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Delete</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
	
	<div class="my-custom-body-wrapper fullscreen-modal">
		<!-- <div class="my-custom-header">
			<div class="p-4 d-flex justify-content-between border-bottom">
				<h2 class="m-auto font-weight-bolder">Create Product</h2>
				<h1 class="cursor-pointer" onclick="history.back();">&times;</h1>
			</div>
		</div> -->
		<div class="my-custom-header sticky-top text-dark bg-white"> 
            <div class="p-5" style="flex-shrink: 0; display: grid; grid-template-columns: 1fr auto 1fr; align-items: center;">
            	<span class="d-flex"></span>
	            <div style="flex-grow: 1;">
					<h1 class="font-weight-bolder page-title">@if(empty($id)) Create Product @else Edit Product @endif</h1>
	            </div>
	            <div style="display: flex; flex-grow: 1; grid-auto-flow: column; justify-content: flex-end;">
	                <a type="button" class="close" onclick="history.back();" style="display: flex; justify-content: flex-start; align-items: center;top:unset;left: 0;opacity: 1;font-size: 1.75rem;">
		                <span aria-hidden="true" class="la la-times"></span>
		            </a>
	            </div>
	        </div>
        </div> 
		<form method="POST" enctype="multipart/form-data" action="{{ route('ajaxAddProduct') }}" id="ajaxAddProduct">
		<div class="my-custom-body">
			<div class="container">
				<h3 class="my-6">Product Details</h3>
				<div class="row">
				
					@csrf
					<input type="hidden" name="editProductID" value="{{ $id }}">
				
					<div class="col-12 col-md-6 p-2 pr-4">
						<div class="form">
							<div class="form-group">
								<label>Product name <span class="text-danger">*</span></label>
								<input type="text" class="form-control" placeholder="e.g Large Shampoo" name="product_name" id="product_name" value="{{ $product_name }}">
							</div>
							<div class="form-group">
								<label>Select Category</label>
								<select class="form-control" name="category_id" id="category_id">
									<option value="">Select Category</option>
									@foreach ($categories as $category)
										<option value="{{ $category->id }}" @if ($category_id == $category->id) selected="selected" @endif >{{ $category->category_name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<label>Select Brand</label>
								<select class="form-control" name="brand_id" id="brand_id">
									<option value="">Select Brand</option>
									@foreach ($brands as $brand)
										<option value="{{ $brand->id }}" @if ($brand_id == $brand->id) selected="selected" @endif >{{ $brand->brand_name }}</option>
									@endforeach
								</select>
							</div>
							<div class="form-group">
								<div class="mt-4 switch switch-icon switch-primary" style="line-height: 33px;">
									<label class="w-100 d-flex align-item-center">
										<input type="checkbox" id="enable_retail_sale" name="enable_retail_sale" @if ($enable_retail_sale == 1) checked="checked" @endif>
										Enable Retail Sales
										<span class="ml-auto text-right"></span>
									</label>
								</div>
							</div>
							<div class="" id="ifRetailEnable">
								<div class="d-flex">
									<div class="form-group  mr-2 w-100">
										<label>Retail price <span class="text-danger">*</span></label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">CA $</span>
											</div>
											<input type="text" class="form-control" placeholder="0.00" name="retail_price" id="retail_price" value="{{ $retail_price }}" onKeyPress="return validPrice(event,this.value);">
										</div>
									</div>
									<div class="form-group  ml-2 w-100">
										<label>Special price</label>
										<div class="input-group">
											<div class="input-group-prepend">
												<span class="input-group-text">CA $</span>
											</div>
											<input type="text" class="form-control" placeholder="0.00" name="special_rate" id="special_rate" value="{{ $special_rate }}" onKeyPress="return validPrice(event,this.value);">
											<span class="text-danger" id="special-price-error"></span>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label>Tax</label>
									<select class="form-control" name="tax_id" id="tax_id">
										<option value="">Default Tax</option>
										<option value="0">No Tax</option>
										@if(!empty($phtml))
											{!! $phtml !!}
										@endif

									<!-- @if($taxes->isNotEmpty())
										@foreach($taxes as $key => $value) 
											<option value="{{ $value->id }}" @if($value->id == $tax_id) selected @endif >{{ $value->tax_name.' ('.$value->tax_rates.')' }}</option>
										@endforeach
									@endif -->
									</select>
								</div>
								<div class="form-group">
									<div class="checkbox-list">
										<label class="checkbox mb-0">
											<input type="checkbox" name="enable_commission" id="enable_commission" @if ($enable_commission == 1) checked="checked" @endif>
											<span></span>Enable commission<br />
										</label>
										<p class="ml-8 m-0 font-size-sm text-muted">
											Calculate staff commissions when this product is sold
										</p>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-12 col-md-6 p-2">
						<div class="d-flex">
							<div class="form-group  mr-2 w-100">
								<label>Barcode <span class="font-size-xs text-muted">(ISBN, UPC, etc.)</span></label>
								<input type="text" class="form-control" placeholder="r.g. 123ABC" name="barcode" id="barcode" value="{{ $barcode }}" maxlength="60">
							</div>
							<div class="form-group  ml-2 w-100">
								<label>SKU <span class="font-size-xs text-muted">(Stock Keeping Unit)</span></label>
								<input type="text" class="form-control" placeholder="r.g. 123ABC" name="sku" id="sku" value="{{ $sku }}" maxlength="17">
							</div>
						</div>
						<div class="form-group">
							<label>Description</label>
							<textarea class="form-control" placeholder="e.g. the world's most spectacular product" rows="5" name="description" id="description">{{ @description }}</textarea>
						</div>
						<div class="form-group">
							<div class="mt-4 py-4 switch switch-icon switch-primary" style="line-height: 33px;">
								<label class="w-100 d-flex align-item-center">
									<input type="checkbox" id="enable_stock_control" name="enable_stock_control" id="enable_stock_control" @if ($enable_stock_control == 1) checked @endif>
									Enable Stock Control
									<span class="ml-auto text-right"></span>
								</label>
							</div>
						</div>
						<div class="" id="ifStocControlkEnable">
							<div class="d-flex">
								<div class="form-group  mr-2 w-100">
									<label>Supply price</label>
									<div class="input-group">
										<div class="input-group-prepend">
											<span class="input-group-text">CA $</span>
										</div>
										<input type="text" class="form-control" placeholder="0.00" name="supply_price" id="supply_price" onKeyPress="return validPrice(event,this.value);" value="{{ $supply_price }}">
									</div>
								</div>
								<div class="form-group  ml-2 w-100">
									<label>Initial stock</label>
									<input type="text" class="form-control" placeholder="0" name="initial_stock" id="initial_stock" onKeyPress="return validQty(event,this.value);" value="{{ $initial_stock }}">
								</div>
							</div>
							<div class="form-group">
								<label>Supplier</label>
								<select class="form-control" name="supplier_id" id="supplier_id">
									<option value="">Select Supplier</option>
									@foreach ($suppliers as $supplier)
										<option value="{{ $supplier->id }}" @if ($supplier_id == $supplier->id) selected="selected" @endif >{{ $supplier->supplier_name }}</option>
									@endforeach
								</select>
							</div>
							<div class="d-flex">
								<div class="form-group  mr-2 w-100">
									<label>Reorder point</label>
									<input type="text" class="form-control" placeholder="0" name="reorder_point" id="reorder_point" onKeyPress="return validQty(event,this.value);" value="{{ $reorder_point }}">
								</div>
								<div class="form-group  ml-2 w-100">
									<label>Reorder qty.</label>
									<input type="text" class="form-control" placeholder="0" name="reorder_qty" id="reorder_qty" onKeyPress="return validQty(event,this.value);" value="{{ $reorder_qty }}">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<hr class="m-0">
		<div class="my-custom-footer container">
			<div class="text-right my-4 d-flex justify-content-between">
				@if($id != '')
					<button type="button" class="btn btn-danger font-weight-bold" data-toggle="modal" data-target="#deleteProductModal">Delete</button>
				@endif
				<div class="ml-auto">
					<button type="button" class="mr-4 btn btn-outline-secondary font-weight-bold" onclick="history.back();">Cancel</button>
					<button type="submit" class="btn btn-primary font-weight-bold" id="addProductSubmitButton">Save</button>
				</div>
			</div>
		</div>
	</div>
</div>

@endsection
{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('js/addProduct.js') }}"></script>

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
	
    $(document).on("click", '#deleteProduct', function (e) {
			
		KTApp.blockPage();
		var form = $(this).parents('form:first');

		$.ajax({
			type: "POST",
			url: "{{ route('deleteProductItem') }}",
			dataType: 'json',
			data: form.serialize(),
			success: function (data) {
				KTApp.unblockPage();
				
				if(data.status == true)
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
					toastr.success(data.message);
					window.setTimeout(function() {
						window.location = WEBSITE_URL+"/partners/inventory";
					}, 2000);
				} else {
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
					toastr.error(data.message);
					$("#deleteProductModal").modal('toggle');
				}	
			}
		});
	}); 

	$(document).on('keyup', '#retail_price, #special_rate', function(){
		validateRetailPrice();			
	});

	function validateRetailPrice() {
		var retailPrice = ($.trim($('#retail_price').val()) != '') ? parseFloat($('#retail_price').val()) : 0.00;
		var specialRate = ($.trim($('#special_rate').val()) != '') ? parseFloat($('#special_rate').val()) : 0.00;

		if(specialRate >= retailPrice) {
			$('#special-price-error').text('Special price must be lower than retail price');
			$('#addProductSubmitButton').attr('disabled', true);
		} else {
			$('#special-price-error').text('');
			$('#addProductSubmitButton').removeAttr('disabled');
		}
	}
</script>
@endsection