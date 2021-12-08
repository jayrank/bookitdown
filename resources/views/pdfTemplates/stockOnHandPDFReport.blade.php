<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Stock On Hand Report</title>

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
				<h1 style="font-weight: normal; color: #000">Stock On Hand Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $getStockOnHand['input']['start_date'] }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
        <table width="100%">
            <thead>
                <tr>
                    <th colspan="4" style="text-align:left; padding-top: 16px;">
                        <h2 style="font-weight: normal; color: #000">Stock On Hand</h2>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 16%;">Product</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Stock On Hand</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Total Cost</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Average Cost</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Total Retail Value</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Retail Price</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Reorder Point</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Reorder Amount</th>
                </tr>
                @if(!empty($getStockOnHand['data']))
                    @foreach($getStockOnHand['data'] as $dataKey => $dataValue)
                        <tr> 
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['product'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['stock_on_hand'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['total_cost'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['average_cost'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['total_retail_value'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['retail_price'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['reorder_point'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['reorder_amount'] }}</td>
                        </tr>
                    @endforeach
                @endif	
            </tbody>
            <tfoot>
                <tr>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">Total</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getStockOnHand['stock_on_hand'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getStockOnHand['total_cost'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getStockOnHand['average_cost'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getStockOnHand['total_retail_value'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;"></td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;"></td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;"></td>
                </tr>
            </tfoot>
        </table>
</body>
</html>