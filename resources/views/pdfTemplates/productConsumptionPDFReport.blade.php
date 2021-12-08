<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Consumption Report</title>

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
				<h1 style="font-weight: normal; color: #000">Product Consumption Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $start_date }} To {{ $end_date }}, {{ $staff_name }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
    <div class="row reports mx-1 my-3">
        <div class="card w-100">
            <div class="card-body" style="height: 150px;">
                <h3 class="font-weight-bolder" style="font-weight: normal; color: #000;">Summary</h3>
                @php 
                    $width = 145;
                @endphp
                @foreach($getProductConsumption as $dKey => $dValue)
                    <div>
                        <span style="font-weight: normal;font: 11px; color: #000; position: absolute; left: 0px; top: {{ $width }}px;">
                            {{$dValue['heading']}}
                        </span>
                        <span style="font-weight: normal;font: 11px; color: #000; width: 60%; position: absolute; right: 0px; top: {{ $width }}px;">
                            {{$dValue['totalCost']}}
                        </span>
                    </div>
                    @php 
                        $width += 20;
                    @endphp
                @endforeach
            </div>
        </div>
        <div class="table-responsive bg-white rounded">
        @foreach($getProductConsumption as $dKey => $dValue)
            <table width="100%">
                <thead>
                    <tr>
                        <th colspan="4" style="text-align:left; padding-top: 16px;">
                            <h2 style="font-weight: normal; color: #000">{{ $dValue['heading'] }}</h2>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 25%;">Product Name</th>
                        <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 25%;">Quantity Used</th>
                        <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 25%;">Avg. Cost Price</th>
                        <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 25%">Total Cost</th>
                    </tr>
                    @if(!empty($dValue['data']))
                        @foreach($dValue['data'] as $dataKey => $dataValue)
                            <tr> 
                                <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['product_name'] }}</td>
                                <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['quantity_used'] }}</td>
                                <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['average_cost_price'] }}</td>
                                <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['total_cost'] }}</td>
                            </tr>
                        @endforeach
                    @endif	
                </tbody>
                <tfoot>
                    <tr> 
                        <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">Total</td>
                        <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;" colspan="2">{{ $dValue['totalQuantity'] }}</td>
                        <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $dValue['totalCost'] }}</td>
                    </tr>
                </tfoot>
            </table>
        @endforeach
        </div>
    </div>
</body>
</html>