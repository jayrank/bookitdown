<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Sales By Location Report</title>

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
				<h1 style="font-weight: normal; color: #000">Sales By Location Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $location_name }}, {{ $staff_name }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
    <table width="100%">
		<thead>
			<tr>
				<th colspan="4" style="text-align:left; padding-top: 16px;">
					<h2 style="font-weight: normal; color: #000">Sales By Location</h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 20%;">Item</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 11%;">Items Sold</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Gross Sales</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 11%">Discounts</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 11%">Refunds</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%">Net Sales</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 11%">Tax</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%">Total Sales</th>
			</tr>
			@if(!empty($data))
				@foreach($data as $dataKey => $dataValue)
                    <tr> 
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['item'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['items_sold'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['gross_sales'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['discounts'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['refunds'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['net_sales'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['tax'] }}</td>
                        <td style="padding: 6px 0;font-size: 12px;">{{ $dataValue['total_sales'] }}</td>
                    </tr>
				@endforeach
			@endif	
		</tbody>
		<tfoot>
            <tr>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">Total</td>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">{{ $total_items_sold }}</td>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">{{ $total_gross_sales }}</td>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">{{ $total_discounts }}</td>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">{{ $total_refunds }}</td>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">{{ $total_net_sales }}</td>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">{{ $total_tax }}</td>
                <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">{{ $total_total_sales }}</td>
            </tr>
		</tfoot>
	</table>
</body>
</html>