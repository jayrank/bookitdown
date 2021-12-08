<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Invoice Report</title>

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
			<td>
				<h2>Voucher List Report</h2>
				<p>{{ $status }} status, generated {{ date("l, d M Y") }} as {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
	
	<table width="100%">
		<thead>
			<tr>
				<th style="text-align:left;">Issue Date</th>
				<th style="text-align:left;">Expiry Date</th>
				<th style="text-align:left;">Invoice no.</th>
				<th style="text-align:left;">Client ID</th>
				<th style="text-align:left;">Client</th>
				<th style="text-align:left;">Type</th>
				<th style="text-align:left;">Status</th>
				<th style="text-align:left;">Code</th>
				<th style="text-align:left;">Total</th>
				<th style="text-align:left;">Redeemed</th>
				<th style="text-align:left;">Remaining</th>
			</tr>
		</thead>
		<tbody>
			@if(!empty($soldVoucher))
				@foreach($soldVoucher as $key => $value)
					<tr>
						<td style="text-align:left;">{{ $value['issue_date'] }}</td>
						<td style="text-align:left;">{{ $value['expiry_date'] }}</td>
						<td style="text-align:left;">{{ $value['invoice_no'] }}</td>
						<td style="text-align:left;">{{ $value['client_id'] }}</td>
						<td style="text-align:left;">{{ $value['client_name'] }}</td>
						<td style="text-align:left;">{{ $value['voucher_type'] }}</td>
						<td style="text-align:left;">{{ $value['invoice_status'] }}</td>
						<td style="text-align:left;">{{ $value['voucher_code'] }}</td>
						<td style="text-align:left;">{{ $value['voucher_total'] }}</td>
						<td style="text-align:left;">{{ $value['redeemed_amount'] }}</td>
						<td style="text-align:left;">{{ $value['remaining_amount'] }}</td>
					</tr>
				@endforeach
			@endif
		</tbody>
	</table>
	
</body>
</html>