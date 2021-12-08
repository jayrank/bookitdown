<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Product Sales Performance Report</title>

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
				<h1 style="font-weight: normal; color: #000">Product Sales Performance Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $getProductSalesPerformance['input']['start_date'] }} To {{ $getProductSalesPerformance['input']['end_date'] }}, {{ $staffName }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
        <table width="100%">
            <thead>
                <tr>
                    <th colspan="4" style="text-align:left; padding-top: 16px;">
                        <h2 style="font-weight: normal; color: #000">Product Sales Performance</h2>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 16%;">Product Name</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Stock On Hand</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Qty Sold</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Cost of Goods Sold</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Net Sales</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Average Net Price</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Margin</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Total Margin</th>
                </tr>
                @if(!empty($getProductSalesPerformance['data']))
                    @foreach($getProductSalesPerformance['data'] as $dataKey => $dataValue)
                        <tr> 
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['product_name'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['stock_on_hand'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['qty_sold'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['cost_of_goods_sold'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['net_sales'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['average_net_price'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['margin'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['total_margin'] }}</td>
                        </tr>
                    @endforeach
                @endif	
            </tbody>
            <tfoot>
                <tr>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">Total</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getProductSalesPerformance['getProductSalesPerformanceTableFooterStockOnHand'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getProductSalesPerformance['getProductSalesPerformanceTableFooterQtySold'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getProductSalesPerformance['getProductSalesPerformanceTableFooterCostOfGoodsSold'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getProductSalesPerformance['getProductSalesPerformanceTableFooterNetSales'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getProductSalesPerformance['getProductSalesPerformanceTableFooterAverageNetPrice'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getProductSalesPerformance['getProductSalesPerformanceTableFooterMargin'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $getProductSalesPerformance['getProductSalesPerformanceTableFooterTotalMargin'] }}</td>
                </tr>
            </tfoot>
        </table>
</body>
</html>