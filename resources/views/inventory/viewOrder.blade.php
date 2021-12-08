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
	
	<div class="modal fade p-0" id="cancelOrderModal" tabindex="-1" role="dialog" aria-labelledby="Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			{{ Form::open(array('url' => '','id' => 'cancelOrder')) }}
				<input type="hidden" name="order_id" id="order_id" value="{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0 }}">
				<div class="modal-content ">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center">Cancel Order</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<div class="modal-body">
						<div class="container">
							<div class="row">
								<p>Are you sure you want to cancle this order?</p>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<button type="button" id="cancelOrderBtn" class="self-align-left btn btn-danger font-weight-bold" data-dismiss="modal">Cancel</button>
					</div>
				</div>
			{{ Form::close() }}
		</div>
	</div>
	
	<div class="modal fade p-0" id="sendOrderEmail" tabindex="-1" role="dialog" aria-labelledby="Label" aria-modal="true">
		<div class="modal-dialog " role="document">
			<form method="POST" enctype="multipart/form-data" action="{{ route('sendPurchaseOrderEmail') }}" id="sendPurchaseOrderEmail">
				@csrf
				<input type="hidden" name="order_id" id="order_id" value="{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0 }}">
				
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title font-weight-bold text-center">Send Order P{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0 }}</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
							<i aria-hidden="true" class="ki ki-close" style="color: #000 !important;font-size: 16px;"></i>
						</button>
					</div>
					<div class="modal-body">
						<div class="row">
							<div class="col-md-12">
								<div class="form-group">
									<label for="recipient_email_address">Recipient Email Address</label>
									<input type="text" class="form-control" name="recipient_email_address" id="recipient_email_address" placeholder="mail@example.com" autocomplete="off">
								</div>
								<div class="form-group">
									<label for="sender_email_address">Sender Email Address</label>
									<input type="text" class="form-control" name="sender_email_address" id="sender_email_address" placeholder="mail@example.com" autocomplete="off" value="{{ $CurrentUser->email }}">
								</div>
								<div class="form-group">
									<label for="message_subject">Message Subject</label>
									<input type="text" class="form-control" name="message_subject" id="message_subject" autocomplete="off" value="Order P{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0 }} from {{ ($LocationsData[0]['location_name']) ? $LocationsData[0]['location_name'] : '' }}">
								</div>
								<div class="form-group">
									<label for="message_content">Message Content</label>
									<textarea type="text" class="form-control" name="message_content" id="message_content" autocomplete="off">Hi  Please see attached purchase order Have a great day! </textarea>
								</div>
							</div>
						</div>
					</div>
					<div class="modal-footer d-flex justify-content-between">
						<div>
							<button type="button" class="btn btn-outline-secondary font-weight-bold" data-dismiss="modal">Cancel</button>
							<button type="submit" class="btn btn-primary font-weight-bold" id="sendEmailButton">Send Email</button>
						</div>
					</div>
				</div>
			</form>
		</div>
	</div>

	<div class="my-custom-body-wrapper">
		<div class="my-custom-header bg-white">
			<div class="p-4 d-flex justify-content-between border-bottom">
				<h2 class="m-auto font-weight-bolder">Order P{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : N/A }}</h2>
				<h1 class="cursor-pointer" onclick="window.location.href='{{ route('orders') }}';"><span aria-hidden="true" class="la la-times"></span></h1>
			</div>
		</div>
		<div class="my-custom-body">
			<div class="container">
				<div class="row">
					<div class="col-12 col-md-8 p-10">
						<div class="d-flex my-8">
							<div class="my-auto">
								@if($InventoryOrders[0]['order_status'] == 3)
								<i class="position-relative fas fa-box icon-xxl bg-white text-dark">
								<i class="position-absolute bottom-0 rounded-circle fas fa-ban text-white" style="right: 0;font-size: 12px;"></i>
								</i>
								@elseif($InventoryOrders[0]['order_status'] == 2)
								<i class="position-relative fas fa-box icon-xxl bg-white text-dark">
								<i class="position-absolute bottom-0 rounded-circle fas fa-check text-white" style="right: 0;font-size: 12px;"></i>
								</i>	
								@else
								<i class="position-relative fas fa-box icon-xxl bg-white text-dark">
								<i class="position-absolute bottom-0 rounded-circle fas fa-check-circle text-white" style="right: 0;font-size: 12px;"></i>
								</i>
								@endif
							</div>
							<div class="mx-4">
								@if($InventoryOrders[0]['order_status'] == 3)
									<h3 class="p-0 m-0">Order Canceled</h3>
									<p class="m-0">No stock received, order canceled on {{ ($InventoryOrders[0]['cancelled_at']) ? date("d M Y, H:ia",strtotime($InventoryOrders[0]['cancelled_at'])) : '' }} at {{ ($LocationsData[0]['location_name']) ? $LocationsData[0]['location_name'] : '' }} by {{ ($CancelOrderByUser->first_name) ? $CancelOrderByUser->first_name : '' }} {{ ($CancelOrderByUser->last_name) ? $CancelOrderByUser->last_name : '' }}</p>	
								@elseif($InventoryOrders[0]['order_status'] == 2)
									<h3 class="p-0 m-0">Order Received</h3>
									<p class="m-0">Ordered {{ ($InventoryOrders[0]['received_at']) ? date("d M Y, H:ia",strtotime($InventoryOrders[0]['received_at'])) : '' }} at {{ ($LocationsData[0]['location_name']) ? $LocationsData[0]['location_name'] : '' }} by {{ ($ReceivedOrderByUser->first_name) ? $ReceivedOrderByUser->first_name : '' }} {{ ($ReceivedOrderByUser->last_name) ? $ReceivedOrderByUser->last_name : '' }}</p>
								@else
									<h3 class="p-0 m-0">Stock Ordered</h3>
									<p class="m-0">Ordered {{ ($InventoryOrders[0]['order_date']) ? date("d M Y, H:ia",strtotime($InventoryOrders[0]['order_date'])) : '' }} at {{ ($LocationsData[0]['location_name']) ? $LocationsData[0]['location_name'] : '' }} by {{ ($OrderByUser->first_name) ? $OrderByUser->first_name : '' }} {{ ($OrderByUser->last_name) ? $OrderByUser->last_name : '' }}</p>	
								@endif
							</div>
						</div>
						@if($InventoryOrders[0]['order_status'] == 1)
						<div class="d-flex">
							<a class="btn btn-white mr-4" href="javascript:;" data-toggle="modal" data-target="#sendOrderEmail">Email Order</a>
							<a class="btn btn-white" href="{{ url('partners/order/downloadPdf/') }}/{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0 }}">Download PDF</a>
						</div>
						@elseif($InventoryOrders[0]['order_status'] == 2)
						<div class="d-flex">
							<a class="btn btn-white" href="{{ url('partners/order/downloadPdf/') }}/{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0 }}">Download PDF</a>
						</div>
						@endif
						<div class="table-responsive my-6">
							<table class="table">
								<thead>
									<tr>
										<th>Product</th>
										<th>Order Qty.</th>
										<th>Supply Price</th>
										<th>Total Cost</th>
									</tr>
								</thead>
								<tbody>
									@if(!empty($InventoryOrderItems))
										@foreach($InventoryOrderItems as $InventoryOrderItem)
										<tr>
											<td>{{ ($InventoryOrderItem['product_name']) ? $InventoryOrderItem['product_name'] : '' }}</td>
											<td>
												@if($InventoryOrders[0]['order_status'] == 2)
													{{ ($InventoryOrderItem['received_qty']) ? $InventoryOrderItem['received_qty'] : '' }}	
												@else
													{{ ($InventoryOrderItem['order_qty']) ? $InventoryOrderItem['order_qty'] : '' }}
												@endif
											</td>
											<td>CA ${{ ($InventoryOrderItem['supply_price']) ? $InventoryOrderItem['supply_price'] : '' }}</td>
											<td>CA ${{ ($InventoryOrderItem['total_cost']) ? $InventoryOrderItem['total_cost'] : '' }}</td>
										</tr>
										@endforeach
									@endif
								</tbody>
							</table>
						</div>
					</div>
					<div class="col-12 col-md-4 p-10">
						<div class="card">
							<div class="card-body p-0">
								<div class="p-6">
									<h3 class="">{{ $OrderSupplier->supplier_name }}</h3>
									<p class="m-0">{{ $OrderSupplier->address }}</p>
									<p class="m-0">{{ $OrderSupplier->suburb }}</p>
									<p class="m-0">{{ $OrderSupplier->city }}</p>
									<p class="m-0">{{ $OrderSupplier->state }}</p>
									<p class="m-0">{{ $OrderSupplier->zip_code }}</p>
									<p class="m-0">{{ $OrderSupplier->country }}</p>
								</div>
								<hr class="m-0" />
								<div class="p-6">
									<h5 class="font-weight-bold">DELIVER TO</h5> 
									@if($InventoryOrders[0]['order_status'] == 1)
									<a style="float:right;color:red;font-size:15px;" href="javascript:;" data-toggle="modal" data-target="#cancelOrderModal">Cancel Order</a>
									@endif
									<p class="m-0">{{ ($LocationsData[0]['location_name']) ? $LocationsData[0]['location_name'] : '' }}</p>
									<p class="m-0">{{ ($LocationsData[0]['location_address']) ? $LocationsData[0]['location_address'] : '' }}</p>
								</div>
								<hr class="m-0" />
								<div class="p-6">
									<div class="d-flex justify-content-between my-4">
										<p class="m-0">Order Total:</p>
										<h5 class="font-weight-bolder">CA ${{ ($InventoryOrders[0]['order_total']) ? $InventoryOrders[0]['order_total'] : 0 }}</h5>
									</div>
									@if($InventoryOrders[0]['order_status'] == 1)
									<button class="btn btn-primary w-100" onclick="window.location.href='{{ url('partners/order/receive/') }}/{{ $InventoryOrders[0]['id'] }}'">Receive Stock</button>
									@else
									<button class="btn btn-primary w-100" onclick="window.location.href='{{ route('orders') }}'">Close</button>
									@endif
								</div>
							</div>
						</div>
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
	var WEBSITE_URL = "{{ url('') }}";
</script>
<script src="{{ asset('js/application.js') }}"></script>
<script src="{{ asset('js/viewOrder.js') }}"></script>
<script>
	$(document).on("click", '#cancelOrderBtn', function (e) {	
		KTApp.blockPage();
		var form = $(this).parents('form:first');

		$.ajax({
			type: "POST",
			url: "{{ route('cancelOrder') }}",
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
						window.location = data.redirect;
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
					$("#cancelOrderModal").modal('toggle');
				}	
			}
		});
	});
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