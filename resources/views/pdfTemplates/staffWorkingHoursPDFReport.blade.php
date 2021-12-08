<!doctype html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Staff Working Hours Report</title>

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
				<h1 style="font-weight: normal; color: #000">Staff Working Hours Report: {{ date("l, d M Y") }}</h1>
				<p style="color: #000">{{ $start_date }} to {{ $end_date }}, generated {{ date("l, d M Y") }} at {{ date("h:i A") }}</p>
			</td>
			<td></td>
		</tr>
	</table>
    @foreach($data as $dKey => $dValue)
        <table width="100%">
            <thead>
                <tr>
                    <th colspan="4" style="text-align:left; padding-top: 16px;">
                        <h2 style="font-weight: normal; color: #000">{{ $dValue['staff_name'] }}</h2>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 25%;">Date</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 25%;">Start</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 25%;">End</th>
                    <th style="text-align:left; border-bottom: 1px solid gray;padding-bottom: 6px;color: #000;width: 25%">Duration</th>
                </tr>
                @if(!empty($dValue['data']))
                    @foreach($dValue['data'] as $dataKey => $dataValue)
                        <tr> 
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['date'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['start_time'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['end_time'] }}</td>
                            <td style="padding: 6px 0;font-size: 10px;">{{ $dataValue['duration'] }}</td>
                        </tr>
                    @endforeach
                @endif	
            </tbody>
            <tfoot>
                <tr> 
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">Total</td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;" colspan="2"></td>
                    <td style="padding: 6px 0;font-size: 10px;font-weight: bold;border-top: 1px solid gray;">{{ $dValue['total'] }}</td>
                </tr>
            </tfoot>
        </table>
    @endforeach
</body>
</html>