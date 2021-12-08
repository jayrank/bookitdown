<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Staff Commission Detailed Report</title>

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
				<h1 style="font-weight: normal; color: #000">Staff Commission Detailed Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $getStaffCommissionDetailed['input']['start_date'] }} to {{ $getStaffCommissionDetailed['input']['end_date'] }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
        <table width="100%">
            <thead>
                <tr>
                    <th colspan="4" style="text-align:left; padding-top: 16px;">
                        <h2 style="font-weight: normal; color: #000">Staff Commission Detailed</h2>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%;">Invoice Date</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%;">Invoice No.</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 15%;">Staff Member</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 15%">Item Sold</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%">Quantity</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%">Sale Value</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%">Commission Rate</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%">Commission Amount</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 10%">Item Type</th>
                </tr>
                @if(!empty($getStaffCommissionDetailed['data']))
                    @foreach($getStaffCommissionDetailed['data'] as $dataKey => $dataValue)
                        <tr> 
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['invoice_date'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['invoice_no'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['staff_member'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['item_sold'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['quantity'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['sale_value'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['commission_rate'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['commission_amount'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['item_type'] }}</td>
                        </tr>
                    @endforeach
                @endif	
            </tbody>
        </table>
</body>
</html>