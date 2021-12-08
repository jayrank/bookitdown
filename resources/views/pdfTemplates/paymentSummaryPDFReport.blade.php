<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Payment Summary Report</title>

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
				<h1 style="font-weight: normal; color: #000">Payment Summary Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ !empty($LocationName['location_name']) ? $LocationName['location_name'] :'All Locations'}}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
    <table width="100%">
		<thead>
			<tr>
				<th colspan="4" style="text-align:left; padding-top: 16px;">
					<h2 style="font-weight: normal; color: #000">Payment Summary</h2>
				</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Payment Type</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Transactions</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Gross Payments</th>
				<th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Refunds</th>
                <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;">Net Payments</th>
			</tr>
			@if(!empty($completedPaymentSummaryData))
				@foreach($completedPaymentSummaryData as $completedPaymentSummaryDataKey => $completedPaymentSummaryDataValue)
                    <tr> 
                        <td style="padding: 6px 0;">{{ $completedPaymentSummaryDataValue['payment_type'] }}</td>
                        <td style="padding: 6px 0;">{{ $completedPaymentSummaryDataValue['row_count'] }}</td>
                        <td style="padding: 6px 0;"> CA ${{ number_format($completedPaymentSummaryDataValue['gross_total'] , 2,".","") }} </td>
                        <td style="padding: 6px 0;"> CA ${{ number_format(($completedPaymentSummaryDataValue['gross_total'] - $netPaymentSummaryData[$completedPaymentSummaryDataKey]['gross_total']), 2,".","") }}</td>                           
                        <td style="padding: 6px 0;">CA ${{ number_format($netPaymentSummaryData[$completedPaymentSummaryDataKey]['gross_total'], 2, ".","") }}</td>
                    </tr>
				@endforeach
			@endif	
		</tbody>
		<tfoot>
                @if($TotalPaymentSummaryData['gross_total'] > 0)
                    <tr> 
                        <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">Total</td>
                        <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">{{ $TotalPaymentSummaryTransaction }}</td>
                        <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;"> CA ${{ number_format($TotalPaymentSummaryData['gross_total'], 2, ".","") }}</td>
                        <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;"> CA ${{ $totalRefundPaymentSummaryData }}</td>
                        <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">CA ${{ number_format(($TotalPaymentSummaryData['gross_total'] - $totalRefundPaymentSummaryData), 2, ".", "") }}</td>
                    </tr>   
                @else
                    <tr>
                        <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">Total</td>
                        <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">0</td>
                        <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;"> CA $0</td>
                        <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;"> CA $0</td>
                        <td style="padding: 6px 0; border-top: 1px solid gray;color: #000;">CA $0</td>
                    </tr>
                @endif
		</tfoot>
	</table>
</body>
</html>