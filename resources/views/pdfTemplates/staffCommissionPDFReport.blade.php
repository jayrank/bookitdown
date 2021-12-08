<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Staff Commission Report</title>

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
				<h1 style="font-weight: normal; color: #000">Staff Commission Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $getStaffCommission['input']['start_date'] }} to {{ $getStaffCommission['input']['end_date'] }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
        <table width="100%">
            <thead>
                <tr>
                    <th colspan="4" style="text-align:left; padding-top: 16px;">
                        <h2 style="font-weight: normal; color: #000">Staff Commission</h2>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 16%;">Staff Member</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Service Sales Total</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Service Commission</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%">Product Sales Total</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%">Product Commission</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%">Voucher Sales Total</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%">Voucher Commission</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%">Commission Total</th>
                </tr>
                @if(!empty($getStaffCommission['data']))
                    @foreach($getStaffCommission['data'] as $dataKey => $dataValue)
                        <tr> 
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['staff_member'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['service_sales_total'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['service_commission'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['product_sales_total'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['product_commission'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['voucher_sales_total'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['voucher_commission'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['commission_total'] }}</td>
                        </tr>
                    @endforeach
                @endif	
            </tbody>
            <tfoot>
                <tr> 
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">Total</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getStaffCommission['serviceSalesTotal']) ? $getStaffCommission['serviceSalesTotal'] : "0.00" }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getStaffCommission['serviceCommissionTotal']) ? $getStaffCommission['serviceCommissionTotal'] : "0.00"}}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getStaffCommission['productSalesTotal']) ? $getStaffCommission['productSalesTotal'] : "0.00" }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getStaffCommission['productCommissionTotal']) ? $getStaffCommission['productCommissionTotal'] : "0.00" }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getStaffCommission['voucherSalesTotal']) ? $getStaffCommission['voucherSalesTotal'] : "0.00" }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getStaffCommission['voucherCommissionTotal']) ? $getStaffCommission['voucherCommissionTotal'] : "0.00" }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getStaffCommission['commissionTotal']) ? $getStaffCommission['commissionTotal'] : "0.00" }}</td>
                </tr>
            </tfoot>
        </table>
</body>
</html>