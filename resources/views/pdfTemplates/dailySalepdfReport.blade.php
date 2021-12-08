<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Daily Sale Report</title>

<style type="text/css">
    * {
        font-family: Verdana, Arial, sans-serif;
        color: gray;
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
			<td>
				<h1 style="font-weight: normal; color: #000">Daily Sale: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $LocationName }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
	
	<table width="100%">
		<thead>
			<tr>
				<th colspan="4" style="text-align:left;">
					<h2 style="font-weight: normal; color: #000">Transaction Summary</h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Item Type</th>
				<th style="text-align:right; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Sales Qty</th>
				<th style="text-align:right;border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Refund Qty</th>
				<th style="text-align:right;border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Gross Total</th>
			</tr>
			<tr>
				<td style="text-align:left;padding: 6px 0;">Services</td>
				<td style="text-align:right;padding: 6px 0;">{{ $TotalServices }}</td>
				<td style="text-align:right;padding: 6px 0;">{{ $TotalRefundServices }}</td>
				<td style="text-align:right;padding: 6px 0;">CA ${{ number_format($TotalServicesAmount,2) }}</td>
			</tr>
			<tr>
				<td style="text-align:left;padding: 6px 0;">Products</td>
				<td style="text-align:right;padding: 6px 0;">{{ $TotalProducts }}</td>
				<td style="text-align:right;padding: 6px 0;">{{ $TotalRefundProducts }}</td>
				<td style="text-align:right;padding: 6px 0;">CA ${{ number_format($TotalProductsAmount,2) }}</td>
			</tr>
			<tr>
				<td style="text-align:left;padding: 6px 0;">Vouchers</td>
				<td style="text-align:right;padding: 6px 0;">{{ $TotalVouchers }}</td>
				<td style="text-align:right;padding: 6px 0;">{{ $TotalRefundVouchers }}</td>
				<td style="text-align:right;padding: 6px 0;">CA ${{ number_format($TotalVouchersAmount,2) }}</td>
			</tr>
			<tr>
				<td style="text-align:left;padding: 6px 0;">Paid Plans</td>
				<td style="text-align:right;padding: 6px 0;">{{ $TotalPaidplan }}</td>
				<td style="text-align:right;padding: 6px 0;">{{ $TotalRefundPaidplan }}</td>
				<td style="text-align:right;padding: 6px 0;">CA ${{ number_format($TotalPaidplanAmount,2) }}</td>
			</tr>
			<!-- <tr>
				<td style="text-align:left;padding: 6px 0;">Late cancellation fees</td>
				<td style="text-align:right;padding: 6px 0;">0</td>
				<td style="text-align:right;padding: 6px 0;">0</td>
				<td style="text-align:right;padding: 6px 0;">CA $0</td>
			</tr>
			<tr>
				<td style="text-align:left;padding: 6px 0;">No show fees</td>
				<td style="text-align:right;padding: 6px 0;">0</td>
				<td style="text-align:right;padding: 6px 0;">0</td>
				<td style="text-align:right;padding: 6px 0;">CA $0</td> -->
			</tr>
		</tbody>
		<tfoot>
			<tr>
				<td style="text-align:left;border-top: 1px solid gray;padding-top: 6px; color: #000">Total Sales</td>
				<td style="text-align:right;border-top: 1px solid gray;padding-top: 6px; color: #000">{{ $TotalAllThing }}</td>
				<td style="text-align:right;border-top: 1px solid gray;padding-top: 6px; color: #000">{{ $TotalAllRefundThing }}</td>
				<td style="text-align:right;border-top: 1px solid gray;padding-top: 6px; color: #000">CA ${{ number_format($TotalAllAmount,2) }}</td>
			</tr>
		</tfoot>
	</table>
	
	<table width="100%">
		<thead>
			<tr>
				<th colspan="4" style="text-align:left; padding-top: 16px;">
					<h2 style="font-weight: normal; color: #000">Cash Movement Summary</h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Payment Type</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;"></th>
				<th style="text-align:right; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Payments collected </th>
				<th style="text-align:right; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Refunds paid</th>
			</tr>
			@if(!empty($paymentByTotal))
				@foreach($paymentByTotal as $paymentByTotalData)
				<tr>
					<td style="text-align:left;padding: 6px 0;" colspan="2">{{ $paymentByTotalData['payment_method'] }}</td>
					<td style="text-align:right;padding: 6px 0">CA ${{ number_format($paymentByTotalData['total_collected'],2) }}</td>
					<td style="text-align:right;padding: 6px 0">CA ${{ number_format($paymentByTotalData['total_refunded'],2) }}</td>
				</tr>
				@endforeach
			@endif
			<tr>
				<td style="text-align:left;padding: 6px 0;" colspan="2">Voucher Redemptions</td>
				<td style="text-align:right;padding: 6px 0">CA ${{ number_format($totalVoucherRedemption,2) }}</td>
				<td style="text-align:right;padding: 6px 0;">CA ${{ number_format($totalRefundVoucherRedemption,2) }}</td>
			</tr>	
		</tbody>
		<tfoot>
			<tr>
				<td style="text-align:left;padding: 6px 0; border-top: 1px solid gray;color: #000;" colspan="2">Payment Collected</td>
				<td style="text-align:right;padding: 6px 0; border-top: 1px solid gray;color: #000;">CA ${{ number_format($totalPaymentCollected,2) }}</td>
				<td style="text-align:right;padding: 6px 0; border-top: 1px solid gray;color: #000;">CA ${{ number_format($totalPaymentRefunded,2) }}</td>
			</tr>
			<tr>
				<td style="text-align:left;padding: 6px 0; border-top: 1px solid gray;color: #000;" colspan="2">of which tips</td>
				<td style="text-align:right;padding: 6px 0; border-top: 1px solid gray;color: #000;">CA ${{ number_format($totalTips,2) }}</td>
				<td style="text-align:right;padding: 6px 0; border-top: 1px solid gray;color: #000;">CA ${{ number_format($totalRefundedTips,2) }}</td>
			</tr>
		</tfoot>
	</table>
</body>
</html>