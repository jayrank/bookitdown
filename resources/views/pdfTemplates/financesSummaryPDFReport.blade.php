<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Finances Summary Report</title>

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
				<h1 style="font-weight: normal; color: #000">Finances Summary Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $start_date }} to {{ $end_date }}, {{ $location_name }} , generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
    <!-- Payments -->
    <table width="100%">
        <thead>
            <tr>
                <th colspan="2" style="text-align:left; padding-top: 16px;">
                    <h2 style="font-weight: normal; color: #000">Payments</h2>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="text-align:left; border-bottom: 0.5px solid gray;padding-bottom: 6px;color: #000;width: 50%;text-align: left;">Payment Method</th>
                <th style="text-align:left; border-bottom: 0.5px solid gray;padding-bottom: 6px;color: #000;width: 50%;text-align: right;">Amount</th>
            </tr>
            @php
                $totalPayment = 0;
            @endphp
            @foreach($getFinancesSummary->original['data']['paymentWiseTotal'] as $dKey => $dValue)
                <tr> 
                    <td style="padding: 6px 0;font-size: 10px;text-align: left;">{{ $dValue['payment_type'] }}</td>
                    <td style="padding: 6px 0;font-size: 10px;text-align: right;">CA ${{ number_format($dValue['total'], 2) }}</td>
                </tr>
                @php
                    $totalPayment += $dValue['total'];
                @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr> 
                <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 0.5px solid gray; width: 50%;text-align: left;">Total Payments</td>
                <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 0.5px solid gray; width: 50%;text-align: right;">CA ${{ number_format($totalPayment, 2) }}</td>
            </tr>
        </tfoot>
    </table>
    <!-- Tips -->
    <table width="100%">
        <thead>
            <tr>
                <th colspan="2" style="text-align:left; padding-top: 16px;">
                    <h2 style="font-weight: normal; color: #000">Tips</h2>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="text-align:left; border-bottom: 0.5px solid gray;padding-bottom: 6px;color: #000;width: 50%;text-align: left;">Tips</th>
                <th style="text-align:left; border-bottom: 0.5px solid gray;padding-bottom: 6px;color: #000;width: 50%;text-align: right;">Amount</th>
            </tr>
            <tr> 
                <td style="padding: 6px 0;font-size: 10px;text-align: left;">Tips Collected</td>
                <td style="padding: 6px 0;font-size: 10px;text-align: right;">{{ $getFinancesSummary->original['totalTip'] }}</td>
            </tr>
        </tbody>
    </table>
    <!-- Vouchers -->
    <table width="100%">
        <thead>
            <tr>
                <th colspan="2" style="text-align:left; padding-top: 16px;">
                    <h2 style="font-weight: normal; color: #000">Vouchers</h2>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="text-align:left; border-bottom: 0.5px solid gray;padding-bottom: 6px;color: #000;width: 50%;text-align: left;">Type</th>
                <th style="text-align:left; border-bottom: 0.5px solid gray;padding-bottom: 6px;color: #000;width: 50%;text-align: right;">Amount</th>
            </tr>
            <tr> 
                <td style="padding: 6px 0;font-size: 10px;text-align: left;">Voucher sales</td>
                <td style="padding: 6px 0;font-size: 10px;text-align: right;">CA ${{ $getFinancesSummary->original['totalVoucherSale'] }}</td>
            </tr>
            <tr> 
                <td style="padding: 6px 0;font-size: 10px;text-align: left;">Voucher redemptions</td>
                <td style="padding: 6px 0;font-size: 10px;text-align: right;">CA ${{ $getFinancesSummary->original['voucherRedemption'] }}</td>
            </tr>
            <tr> 
                <td style="padding: 6px 0;font-size: 10px;text-align: left;">Voucher outstanding balance</td>
                <td style="padding: 6px 0;font-size: 10px;text-align: right;">{{ $getFinancesSummary->original['totalVoucherOutstanding'] }}</td>
            </tr>
        </tbody>
    </table>
    <!-- Sales -->
    <table width="100%">
        <thead>
            <tr>
                <th colspan="2" style="text-align:left; padding-top: 16px;">
                    <h2 style="font-weight: normal; color: #000">Sales</h2>
                </th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <th style="text-align:left; border-bottom: 0.5px solid gray;padding-bottom: 6px;color: #000;width: 50%;text-align: left;">Type</th>
                <th style="text-align:left; border-bottom: 0.5px solid gray;padding-bottom: 6px;color: #000;width: 50%;text-align: right;">Amount</th>
            </tr>
            <tr> 
                <td style="padding: 6px 0;font-size: 10px;text-align: left;">Gross sales</td>
                <td style="padding: 6px 0;font-size: 10px;text-align: right;">CA ${{ $getFinancesSummary->original['data']['grossTotal'] }}</td>
            </tr>
            <tr> 
                <td style="padding: 6px 0;font-size: 10px;text-align: left;">Discounts</td>
                <td style="padding: 6px 0;font-size: 10px;text-align: right;">CA ${{ $getFinancesSummary->original['data']['totalDiscount'] }}</td>
            </tr>
            <tr>
                <td style="padding: 6px 0;font-size: 10px;text-align: left;">Refunds</td>
                <td style="padding: 6px 0;font-size: 10px;text-align: right;">CA ${{ $getFinancesSummary->original['data']['totalRefunds'] }}</td>
            </tr>
            <tr>
                <th style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray; text-align: left;">Net Sales</th>
                <th style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray; text-align: right;">CA ${{ $getFinancesSummary->original['data']['netSales'] }}</th>
            </tr>
            <tr>
                <td style="padding: 6px 0;font-size: 10px;text-align: left;">Taxes</td>
                <td style="padding: 6px 0;font-size: 10px;text-align: right;">CA ${{ $getFinancesSummary->original['data']['totalTaxes'] }}</td>
            </tr>
        </tbody>
        <tfoot>
            <tr> 
                <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 0.5px solid gray; width: 50%;text-align: left;">Total Sales</td>
                <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 0.5px solid gray; width: 50%;text-align: right;">CA ${{ $getFinancesSummary->original['data']['totalSales']  }}</td>
            </tr>
        </tfoot>
    </table>
</body>
</html>