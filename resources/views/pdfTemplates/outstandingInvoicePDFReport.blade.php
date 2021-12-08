<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Outstanding Invoice Report</title>

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
				<h1 style="font-weight: normal; color: #000">Outstanding Invoice Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $location_name }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
    <table width="100%">
		<thead>
			<tr>
				<th colspan="4" style="text-align:left; padding-top: 16px;">
					<h2 style="font-weight: normal; color: #000">Outstanding Invoice</h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 8%;">Invoice#</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 15%;">Location</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 9%;">Status</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 15%">Invoice date</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 15%">Due Date</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%">Overdue</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%">Customer</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 9%">Gross total</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 9%">Amount Due</th>
			</tr>
			@if(!empty($data))
				@foreach($data as $dataKey => $dataValue)
                    <tr> 
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['invoice_id'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['location_name'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ strip_tags($dataValue['status']) }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['invoice_date'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['due_date'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['Overdue'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['customer'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['gross_total'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['amount_due'] }}</td>                        
                    </tr>
				@endforeach
			@endif	
		</tbody>
		<tfoot>
            <tr>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;" colspan="7">Total</td>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;border-right: 1px solid gray;color: #000;">{{ $total }}</td>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">{{ $total }}</td>
            </tr>
		</tfoot>
	</table>
</body>
</html>