<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>P{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0 }}</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
    }
    table{
        font-size: x-small;
    }
    tfoot tr td{
        font-weight: bold;
        font-size: x-small;
    }
    .gray {
        background-color: lightgray
    }
</style>

</head>
<body>
	<table width="100%">
		<tr>
			<td><h2>PRODUCT ORDER P{{ ($InventoryOrders[0]['id']) ? $InventoryOrders[0]['id'] : 0 }}</h2></td>
			<td></td>
		</tr>
	</table>
	
	<table width="100%">
		<tr>
			<td align="left">
				<h3>To</h3>
				<p>{{ $OrderSupplier->supplier_name }}</p>
				<p>{{ $OrderSupplier->address }}</p>
				<p>{{ $OrderSupplier->city }}</p>
				<p>{{ $OrderSupplier->state }}</p>
				<p>{{ $OrderSupplier->zip_code }}</p>
				<p>{{ $OrderSupplier->country }}</p>
			</td>
			<td align="left">
				<h3>From</h3>
				<p>{{ $LocationsData[0]['location_name'] }}</p>
				
				<h3>Deliver To</h3>
				<p>{{ $LocationsData[0]['location_address'] }}</p>	
			</td>
		</tr>
	</table>
	
	<br/>
	<br/>
	<br/>

	<table width="100%">
    <thead style="background-color: lightgray;">
      <tr>
        <th>Product</th>
        <th>Barcode</th>
        <th>Order Qty.</th>
		<th>Received Qty.</th>
        <th>Cost Price</th>
		<th>Total Cost</th>
      </tr>
    </thead>
    <tbody>
		@php
			$total_order_qty = 0;
			$total_received_qty = 0;
		@endphp
		
		@if(!empty($InventoryOrderItems))
			@foreach($InventoryOrderItems as $InventoryOrderItem)
				@php
					$total_order_qty = $total_order_qty + $InventoryOrderItem['order_qty'];
					$total_received_qty = $total_received_qty + $InventoryOrderItem['received_qty'];
				@endphp
				<tr>
					<td>{{ ($InventoryOrderItem['product_name']) ? $InventoryOrderItem['product_name'] : '' }}</td>
					<td align="right">{{ ($InventoryOrderItem['barcode']) ? $InventoryOrderItem['barcode'] : '' }}</td>
					<td align="right">{{ ($InventoryOrderItem['order_qty']) ? $InventoryOrderItem['order_qty'] : 0 }}</td>
					<td align="right">{{ ($InventoryOrderItem['received_qty']) ? $InventoryOrderItem['received_qty'] : 0 }}</td>
					<td align="right">CA ${{ ($InventoryOrderItem['supply_price']) ? $InventoryOrderItem['supply_price'] : 0 }}</td>
					<td align="right">CA ${{ ($InventoryOrderItem['total_cost']) ? $InventoryOrderItem['total_cost'] : 0 }}</td>
				</tr>
			@endforeach
		@endif
    </tbody>

    <tfoot>
        <tr>
            <td colspan="2">Total</td>
			<td align="right">{{ $total_order_qty }}</td>
			<td align="right">{{ $total_received_qty }}</td>
            <td colspan="2" align="right">CA ${{ ($InventoryOrders[0]['order_total']) ? $InventoryOrders[0]['order_total'] : 0 }}</td>
        </tr>
    </tfoot>
  </table>

</body>
</html>