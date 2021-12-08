<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Tips By Staff Report</title>

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
				<h1 style="font-weight: normal; color: #000">Tips By Staff Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $getTipsByStaff['input']['start_date'] }} to {{ $getTipsByStaff['input']['end_date'] }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
        <table width="100%">
            <thead>
                <tr>
                    <th colspan="4" style="text-align:left; padding-top: 16px;">
                        <h2 style="font-weight: normal; color: #000">Tips By Staff</h2>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 13%;">Employee Name</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%;">Collected Tips</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 15%;">Refunded Tips</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%">Total Tips</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 12%">Average Tips</th>
                </tr>
                @if(!empty($getTipsByStaff['data']))
                    @foreach($getTipsByStaff['data'] as $dataKey => $dataValue)
                        <tr> 
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['employee_name'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['collected_tip'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['refunded_tips'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['total_tips'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['average_tips'] }}</td>
                        </tr>
                    @endforeach
                @endif	
            </tbody>
            <tfoot>
                <tr> 
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">Total</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getTipsByStaff['collectedTipsTotal']) ? $getTipsByStaff['collectedTipsTotal'] : "0.00" }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getTipsByStaff['refundedTipsTotal']) ? $getTipsByStaff['refundedTipsTotal'] : "0.00"}}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getTipsByStaff['totalTips']) ? $getTipsByStaff['totalTips'] : "0.00" }}</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ !empty($getTipsByStaff['averageTipsTotal']) ? $getTipsByStaff['averageTipsTotal'] : "0.00" }}</td>
                </tr>
            </tfoot>
        </table>
</body>
</html>