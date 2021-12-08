<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Stock Movement Log Report</title>

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
				<h1 style="font-weight: normal; color: #000">Stock Movement Log Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $getStockMovementLog->original['input']['start_date'] }} To {{ $getStockMovementLog->original['input']['end_date'] }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
        <table width="100%">
            <thead>
                <tr>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 17%;">Time & Date</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 17%;">Product</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 11%;">Barcode</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 11%;">Staff</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 11%;">Action</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 11%;">Adjustment</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 11%;">Cost Price</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 11%;">On Hand</th>
                </tr>
            </thead>
            <tbody>
                @if(!empty($getStockMovementLog->original['data']))
                    @foreach($getStockMovementLog->original['data'] as $dataKey => $dataValue)
                        <tr> 
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['time_and_date'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['product'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['barcode'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['staff'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['action'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['adjustment'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['cost_price'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['on_hand'] }}</td>
                        </tr>
                    @endforeach
                @endif	
            </tbody>
        </table>
</body>
</html>