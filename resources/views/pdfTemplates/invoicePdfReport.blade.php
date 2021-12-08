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
				<h2>Invoice List Report</h2>
				<p>all staff, all locations,  generated, {{ date("l, d M Y") }} as {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
	
	<table width="100%">
		<thead>
			<tr>
				<th style="text-align:left;">Invoice #</th>
				<th style="text-align:left;">Client</th>
				<th style="text-align:left;">Status</th>
				<th style="text-align:left;">Invoice Date</th>
				<th style="text-align:left;">Due Date</th>
				<th style="text-align:left;">Billing Name</th>
				<th style="text-align:left;">Location</th>
				<th style="text-align:left;">Tips</th>
				<th style="text-align:left;">Gross Total</th>
			</tr>
		</thead>
		<tbody>
			@if(!empty($invoices))
				@foreach($invoices as $key => $value)
					<tr>
						<td style="text-align:left;">{{ $value['invoice_id'] }}</td>
						<td style="text-align:left;">{{ $value['client_name'] }}</td>
						<td style="text-align:left;">{{ $value['invoice_status'] }}</td>
						<td style="text-align:left;">{{ $value['invoice_date'] }}</td>
						<td style="text-align:left;">{{ $value['due_date'] }}</td>
						<td style="text-align:left;">{{ $value['billing_name'] }}</td>
						<td style="text-align:left;">{{ $value['location_name'] }}</td>
						<td style="text-align:left;">{{ $value['tips'] }}</td>
						<td style="text-align:left;">{{ $value['gross_total'] }}</td>
					</tr>
				@endforeach
			@endif
		</tbody>
	</table>
	
</body>
</html>